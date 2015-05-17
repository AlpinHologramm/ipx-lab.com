<?php
/**
 * Méthodes de gestion des albums.
 *
 * @license http://www.gnu.org/licenses/gpl.html
 * @link http://www.igalerie.org/
 */
class alb
{
	/**
	 * Création d'une nouvelle catégorie dans la catégorie $cat_id.
	 *
	 * @param integer $cat_id
	 *	Identifiant de la catégorie parente.
	 * @param string $cat_path
	 *	Chemin de la catégorie parente.
	 * @param string $cat_pass
	 *	Mot de passe de la catégorie parente.
	 * @param string $type
	 *	Type de la catégorie à créer : 'alb' ou 'cat'.
	 * @param array $infos
	 *	Informations complémentaires :
	 * 'filename' (facultatif), 'name', 'desc'
	 * @param integer $user_id
	 *	Identifiant de l'utilisateur.
	 * @return boolean|string
	 *	TRUE si succès.
	 *	FALSE si création du répertoire échouée.
	 *	string si message d'erreur.
	 */
	public static function create($cat_id, $cat_path, $cat_pass, $type, $infos, $user_id)
	{
		// Nom de répertoire.
		$dirname = isset($infos['filename']) ? $infos['filename'] : $infos['name'];
		$dirname = utils::removeAccents($dirname);
		$dirname = preg_replace('`([^-_a-z0-9])`i', '_', $dirname);
		$dirname = ($cat_path == '.')
			? $dirname
			: $cat_path . '/' . $dirname;

		// Vérification de la longueur du nom de répertoire.
		if (strlen(basename($dirname)) < 1 || strlen(basename($dirname)) > 255)
		{
			return 'warning:' . __('Le titre doit contenir au moins 1 caractère.');
		}

		// Si un répertoire de même nom existe, on modifie le nom du répertoire.
		$path = GALLERY_ROOT . '/' . CONF_ALBUMS_DIR . '/';
		$test = $dirname;
		$n = 2;
		while (file_exists($path . $test))
		{
			if ($n > 99)
			{
				return FALSE;
			}
			$test = $dirname . '_' . $n;
			$n++;
		}
		$dirname = $test;
		$path .= $dirname;

		// Début de la transaction.
		if (!utils::$db->transaction())
		{
			return 'error:' . utils::$db->msgError;
		}

		// On récupère les identifiants des catégories parentes.
		if (($parents_ids = alb::getParentsIds($cat_id)) === FALSE)
		{
			return 'error:' . utils::$db->msgError;
		}

		// Nom d'URL.
		$cat_url = utils::genURLName(
			isset($infos['filename']) ? $infos['filename'] : $infos['name']
		);

		// On ajoute la nouvelle catégorie à la base de données.
		$sql = 'INSERT INTO ' . CONF_DB_PREF . 'categories (
			user_id, thumb_id, parent_id, cat_path, cat_name, cat_url,
			cat_desc, cat_crtdt, cat_filemtime, cat_password, cat_status
			) VALUES (
			:user_id, :thumb_id, :parent_id, :cat_path, :cat_name, :cat_url,
			:cat_desc, :cat_crtdt, :cat_filemtime, :cat_password, :cat_status
			)';
		$datetime = date('Y-m-d H:i:s');
		$params = array(
			'user_id' => (int) $user_id,
			'thumb_id' => -1,
			'parent_id' => (int) $cat_id,
			'cat_path' => $dirname,
			'cat_name' => $infos['name'],
			'cat_url' => $cat_url,
			'cat_desc' => (trim($infos['desc']) === '')
				? NULL
				: $infos['desc'],
			'cat_crtdt' => $datetime,
			'cat_filemtime' => ($type == 'alb')
				? $datetime
				: NULL,
			'cat_password' => $cat_pass,
			'cat_status' => 0
		);
		if (utils::$db->prepare($sql) === FALSE
		|| utils::$db->executeExec($params) === FALSE)
		{
			return 'error:' . utils::$db->msgError;
		}

		// On met à jour le bon "cat_position".
		$id = utils::$db->connexion->lastInsertId();
		$sql = 'UPDATE ' . CONF_DB_PREF . 'categories
				   SET cat_position = ' . $id . '
				 WHERE cat_id = ' . $id;
		if (utils::$db->exec($sql, FALSE) === FALSE)
		{
			return 'error:' . utils::$db->msgError;
		}

		// On met à jour le nombre de sous-catégories de la catégorie parente.
		if (!alb::updateSubsCats(array($cat_id), FALSE))
		{
			return 'error:' . utils::$db->msgError;
		}

		// On update les catégoires parentes du nombre d'albums.
		if ($type == 'alb')
		{
			$update = array('d' => array(
				'albums' => 1,
				'comments' => 0,
				'hits' => 0,
				'images' => 0,
				'size' => 0,
				'votes' => 0,
				'rate' => 0
			));
			$sql = alb::updateParentsStats($update, FALSE, '+', $parents_ids);
			if (utils::$db->exec($sql, FALSE) === FALSE)
			{
				return 'error:' . utils::$db->msgError;
			}
		}

		// On tente de créer le répertoire.
		if (!files::mkdir($path))
		{
			return FALSE;
		}

		// Exécution de la transaction.
		if (!utils::$db->commit())
		{
			// On supprime le répertoire en cas d'échec de la transaction.
			if (is_dir($path))
			{
				files::rmdir($path);
			}

			return 'error:' . utils::$db->msgError;
		}

		return TRUE;
	}

	/**
	 * Supprime des images d'un même album.
	 *
	 * @param integer $cat_id
	 *	Identifiant de l'album.
	 * @param array $images_infos
	 *	Tableau des informations utiles des images,
	 *	indexé sur l'identifiant de chaque image.
	 * @return array
	 */
	public static function deleteImages($cat_id, $images_infos)
	{
		$report = array();

		try
		{
			// Récupération des id des catégories parentes et des
			// informations qui serviront à updater les catégories parentes.
			if (($parents_ids = alb::getParentsIds($cat_id)) === FALSE)
			{
				throw new Exception(utils::$db->msgError);
			}

			// On calcule les statistiques des catégories parentes à updater.
			$up = array();
			$up['images'] = 0;
			$up['albums'] = 0;
			$up['size'] = 0;
			$up['hits'] = 0;
			$up['comments'] = 0;
			$up['votes'] = 0;
			$up['rate'] = 0;
			$cat_update = array();
			$cat_update['a'] = $up;
			$cat_update['d'] = $up;
			foreach ($images_infos as &$infos)
			{
				$status = ($infos['image_status']) ? 'a' : 'd';

				// On recalcule la note moyenne.
				if ($infos['image_votes'] > 0)
				{
					$cat_update[$status]['rate'] =
						(($cat_update[$status]['rate'] * $cat_update[$status]['votes'])
						+ ($infos['image_rate'] * $infos['image_votes']))
						/ ($cat_update[$status]['votes'] + $infos['image_votes']);
					$cat_update[$status]['votes'] += $infos['image_votes'];
				}

				// Autres informations.
				$cat_update[$status]['images'] += 1;
				$cat_update[$status]['size'] += $infos['image_filesize'];
				$cat_update[$status]['hits'] += $infos['image_hits'];
				$cat_update[$status]['comments'] += $infos['image_comments'];
			}

			// Début de la transaction.
			if (!utils::$db->transaction())
			{
				throw new Exception(utils::$db->msgError);
			}

			// Suppression des images et de tous les tags,
			// votes et commentaires liés.
			$sql = 'DELETE
					  FROM ' . CONF_DB_PREF . 'images
					 WHERE image_id IN (' . implode(', ', array_keys($images_infos)) . ')';
			if (utils::$db->exec($sql, FALSE) === FALSE)
			{
				throw new Exception(utils::$db->msgError);
			}

			// S'il n'existe plus une seule image activée dans l'album
			// alors que l'on vient d'en supprimer au moins une,
			// on ajoute 1 au nombre d'albums désactivés et
			// on retire 1 au nombre d'albums activés pour toutes
			// les catégories parentes.
			$sql = 'SELECT 1
					  FROM ' . CONF_DB_PREF . 'images
					 WHERE cat_id = ' . (int) $cat_id . '
					   AND image_status = "1"
					 LIMIT 1';
			if (utils::$db->query($sql) === FALSE)
			{
				throw new Exception(utils::$db->msgError);
			}
			if (utils::$db->nbResult === 0 && $cat_update['a']['images'] > 0)
			{
				$cat_update['a']['albums']++;
				$cat_update['d']['albums']--;
			}

			// Mise à jour des statistiques des catégories parentes.
			$sql = alb::updateParentsStats($cat_update, '-', '-', $parents_ids);
			if (utils::$db->exec($sql, FALSE) === FALSE)
			{
				throw new Exception(utils::$db->msgError);
			}

			// On met à jour certaines informations des catégories parentes.
			reset($images_infos);
			$i = current($images_infos);
			$path = dirname($i['image_path']);
			if (!alb::updateLastadddt($path, FALSE)
			|| !alb::updateCatThumbs($path, FALSE)
			|| !alb::updateSubsCats($parents_ids, FALSE))
			{
				throw new Exception(utils::$db->msgError);
			}

			// Exécution de la transaction.
			if (!utils::$db->commit())
			{
				throw new Exception(utils::$db->msgError);
			}

			// On supprime les images sur le disque.
			foreach ($images_infos as &$i)
			{
				$image_file = GALLERY_ROOT . '/' . CONF_ALBUMS_DIR . '/' . $i['image_path'];
				if (file_exists($image_file) && !files::unlink($image_file))
				{
					$report[] = array(
						'error:' . __('Impossible de supprimer le fichier.'),
						$i['image_id']
					);
				}
			}

			$report[] = 'success:' . __('Les images sélectionnées ont été supprimées.');
		}
		catch (Exception $e)
		{
			$report[] = 'error:' . $e->getMessage();
		}

		return $report;
	}

	/**
	 * Récupère les identifiants des catégories parentes d'une catégorie.
	 *
	 * @param integer $cat_id
	 *	Identifiant de la catégorie.
	 * @param string $cat_path
	 *	Chemin de la catégorie.
	 * @return boolean|array
	 */
	public static function getParentsIds($cat_id, $cat_path = NULL)
	{
		// Récupération du chemin de la catégorie, si non fourni.
		if ($cat_path === NULL)
		{
			$sql = 'SELECT cat_path
					  FROM ' . CONF_DB_PREF . 'categories
					 WHERE cat_id = ' . (int) $cat_id;
			if (utils::$db->query($sql, 'value') === FALSE
			|| utils::$db->nbResult !== 1)
			{
				return FALSE;
			}
			$cat_path = utils::$db->queryResult;
		}

		// Récupération des id des catégories parentes.
		$parents_ids = array(1, $cat_id);
		$parent = dirname($cat_path);
		$sql_where = '';
		while ($parent !== '.')
		{
			$sql_where .= 'cat_path = "' . utils::filters($parent, 'path') . '" OR ';
			$parent = dirname($parent);
		}
		if ($sql_where !== '')
		{
			$sql_where = substr($sql_where, 0, strlen($sql_where) - 4);
			$sql = 'SELECT cat_id
					  FROM ' . CONF_DB_PREF . 'categories
					 WHERE ' . $sql_where;
			$fetch_style['column'] = array('cat_id', 'cat_id');
			if (utils::$db->query($sql, $fetch_style) === FALSE
			|| utils::$db->nbResult === 0)
			{
				return FALSE;
			}
			$parents_ids = array_merge($parents_ids, utils::$db->queryResult);
		}

		return array_map('intval', array_unique($parents_ids));
	}

	/**
	 * Incrèmente, si nécessaire, le nombre de visites
	 * pour une image et toutes ses catégories parentes.
	 *
	 * @param array $user_infos
	 *	Informations utiles de l'utilisateur.
	 * @param array $image_infos
	 *	Informations utiles de l'image.
	 * @return void
	 */
	public static function imageHits($user_infos, &$image_infos)
	{
		// Ne comptabilise pas les visites en fonction de l'User-Agent.
		if (utils::$config['nohits_useragent'] && isset($_SERVER['HTTP_USER_AGENT']))
		{
			$list = preg_split(
				'`[\r\n]+`', utils::$config['nohits_useragent_list'], -1, PREG_SPLIT_NO_EMPTY
			);
			foreach ($list as &$ent)
			{
				$ent = preg_quote($ent);
				$ent = str_replace(array('\\?', '\\*'), array('.', '.*'), $ent);
				$ent = utils::removeAccents($ent);
				if (mb_eregi('(?:^|\W)' . $ent . '(?:$|\W)', $_SERVER['HTTP_USER_AGENT']))
				{
					return;
				}
			}
		}

		// On vérifie par POST et par cookie
		// si l'image n'a pas déjà été visionnée précédemment.
		// Et on ne compte pas les visites des admins qui le souhaitent.
		if (!(!empty($user_infos['group_admin']) && !empty($user_infos['user_nohits']))
		&& utils::$cookiePrefs->read('last_img') != $image_infos['image_id'])
		{
			// Début de la transaction.
			if (utils::$db->transaction() === FALSE)
			{
				return;
			}

			// On ajoute 1 au nombre de visites de l'image.
			$sql = 'UPDATE ' . CONF_DB_PREF . 'images
				       SET image_hits = image_hits + 1
					 WHERE image_id = ' . (int) $image_infos['image_id'];
			if (utils::$db->exec($sql) === FALSE
			|| utils::$db->nbResult !== 1)
			{
				return;
			}

			// On ajoute 1 au nombre de visites de toutes les catégories parentes.
			$sql_where = array();
			$params = array();
			$parent = dirname($image_infos['image_path']);
			while ($parent !== '.')
			{
				$sql_where[] = 'cat_path = ?';
				$params[] = $parent;
				$parent = dirname($parent);
			}
			$sql = 'UPDATE ' . CONF_DB_PREF . 'categories
					   SET cat_a_hits = cat_a_hits + 1
					 WHERE cat_id = 1 OR ' . implode(' OR ', $sql_where);
			if (utils::$db->prepare($sql) === FALSE
			|| utils::$db->executeExec($params) === FALSE
			|| utils::$db->nbResult !== count($params) + 1)
			{
				return;
			}

			// Exécution de la transaction.
			if (!utils::$db->commit())
			{
				return;
			}

			$image_infos['image_hits']++;
			utils::$cookiePrefs->add('last_img', $image_infos['image_id']);
		}
	}

	/**
	 * Contrôle la vignette des catégories parentes.
	 * C'est à dire choisi une nouvelle vignette pour chaque catégorie
	 * parente lorsque cela est nécessaire.
	 * Permet également de désactiver une catégorie ou de l'indiquer
	 * comme vide lorsque cela est nécessaire.
	 *
	 * @param string $path
	 *	Chemin de la catégorie.
	 * @param boolean $transaction
	 *	Doit-on exécuter les requêtes dans une transaction ?
	 * @return boolean
	 *	TRUE si succès.
	 *	FALSE si échec.
	 */
	public static function updateCatThumbs($path, $transaction = TRUE)
	{
		if ($path == '')
		{
			return FALSE;
		}

		$update = array();
		$update['sql'] = 'UPDATE ' . CONF_DB_PREF . 'categories
							 SET thumb_id = :thumb_id,
								 cat_status = :cat_status
						   WHERE cat_id = :cat_id';
		$update['params'] = array();

		// Début de la transaction.
		if ($transaction && !utils::$db->transaction())
		{
			return FALSE;
		}

		// Pour chaque catégorie.
		while ($path != '.')
		{
			// Paramètres des requêtes préparées.
			$params = array('path' => $path);

			// On récupère les informations utiles de la catégorie.
			$sql = 'SELECT cat_id,
						   thumb_id,
						   cat_a_images,
						   cat_d_images,
						   cat_crtdt,
						   cat_status
					 FROM ' . CONF_DB_PREF . 'categories
					WHERE cat_path = :path';
			if (utils::$db->prepare($sql) === FALSE
			|| utils::$db->executeQuery($params, 'row') === FALSE
			|| utils::$db->nbResult === 0)
			{
				return FALSE;
			}
			$i = utils::$db->queryResult;

			// Si la catégorie ne contient aucune image, on supprime la
			// référence à une vignette, et on désactive la catégorie.
			if (($i['cat_a_images'] + $i['cat_d_images']) == 0)
			{
				$update['params'][] = array(
					'thumb_id' => -1,
					'cat_status' => '0',
					'cat_id' => $i['cat_id']
				);

				// On supprime également la vignette de la catégorie
				// sur le disque si celle-ci existe.
				foreach (array('gif', 'jpg', 'png') as $ext)
				{
					$thumb_file = GALLERY_ROOT . '/'
						. img::filepath('tb_cat', 'i.' . $ext, $i['cat_id'], $i['cat_crtdt']);
					if (file_exists($thumb_file))
					{
						files::unlink($thumb_file);
					}
				}

				$path = dirname($path);
				continue;
			}

			// S'il s'agit d'une vignette d'une image externe,
			// alors on laisse cette vignette.
			if ($i['thumb_id'] == 0)
			{
				$path = dirname($path);
				continue;
			}

			// On récupère le statut de l'image correspondant
			// à la vignette de la catégorie.
			$sql = 'SELECT image_status
					  FROM ' . CONF_DB_PREF . 'images 
					 WHERE image_id = ' . (int) $i['thumb_id'] . '
					   AND image_path LIKE CONCAT(:path, "/%")';
			if (utils::$db->prepare($sql) === FALSE
			|| utils::$db->executeQuery($params, 'value') === FALSE)
			{
				return FALSE;
			}

			// On choisi une nouvelle vignette pour la catégorie si :
			// - L'image de la vignette de la catégorie n'existe pas dans
			//   cette catégorie.
			// - Ou l'image correspondant à cette vignette est désactivée
			//   alors que la catégorie contient des images activées.
			if (utils::$db->nbResult === 0
			|| ($i['cat_a_images'] > 0 && utils::$db->queryResult == '0'))
			{
				$image_status = ($i['cat_a_images'] == 0)
					? ''
					: 'AND image_status = "1" ';
				$sql = 'SELECT image_id,
							   image_path
						  FROM ' . CONF_DB_PREF . 'images 
						 WHERE image_path LIKE CONCAT(:path, "/%") '
							 . $image_status . '
					  ORDER BY image_id DESC
						 LIMIT 1';
				if (utils::$db->prepare($sql) === FALSE
				|| utils::$db->executeQuery($params, 'row') === FALSE
				|| utils::$db->nbResult === 0)
				{
					return FALSE;
				}
				$new_thumb = utils::$db->queryResult;
				$update['params'][] = array(
					'thumb_id' => $new_thumb['image_id'],
					'cat_status' => ($i['cat_a_images'] == 0) ? '0' : '1',
					'cat_id' => $i['cat_id']
				);

				// Si un fichier de même nom existe déjà,
				// on supprime la vignette sur le disque.
				$thumb_file = GALLERY_ROOT . '/' . img::filepath('tb_cat',
					$new_thumb['image_path'], $i['cat_id'], $i['cat_crtdt']);
				if (file_exists($thumb_file))
				{
					files::unlink($thumb_file);
				}
			}

			// Sinon, si la catégorie ne contient aucune image activée,
			// alors qu'elle est activée, on la désactive.
			elseif ($i['cat_a_images'] == 0 && $i['cat_status'] == 1)
			{
				$update['params'][] = array(
					'thumb_id' => $i['thumb_id'],
					'cat_status' => '0',
					'cat_id' => $i['cat_id']
				);
			}

			$path = dirname($path);
		}

		// Si aucune mise à jour nécessaire, alors on arrête là.
		if (empty($update['params']))
		{
			return TRUE;
		}

		// Exécution des requêtes.
		if (utils::$db->exec(array(0 => $update), FALSE) === FALSE)
		{
			return FALSE;
		}

		// Exécution de la transaction.
		if ($transaction && !utils::$db->commit())
		{
			return FALSE;
		}

		return TRUE;
	}

	/**
	 * Met à jour la date de dernier ajout d'une image des catégories parentes.
	 *
	 * @param string $path
	 *	Chemin de la catégorie.
	 * @param boolean $transaction
	 *	Doit-on démarrer une transaction ?
	 * @return boolean
	 *	TRUE si succès.
	 *	FALSE si échec.
	 */
	public static function updateLastadddt($path, $transaction = TRUE)
	{
		if ($path == '')
		{
			$path = '.';
		}

		$update = array(
			'sql' =>'UPDATE ' . CONF_DB_PREF . 'categories
						SET cat_lastadddt = :cat_lastadddt
					  WHERE cat_path = :cat_path',
			'params' => array()
		);

		// Début de la transaction.
		if ($transaction && !utils::$db->transaction())
		{
			return FALSE;
		}

		// Pour chaque catégorie.
		while ($path != '')
		{
			$path = ($path == '.') ? '' : $path . '/';

			// Récupération de la date d'ajout de l'image
			// la plus récente de la catégorie.
			$sql = 'SELECT image_adddt
					  FROM ' . CONF_DB_PREF . 'images
					 WHERE image_path LIKE CONCAT(:path, "%")
				  ORDER BY image_status DESC,
						   image_adddt DESC
					 LIMIT 1';
			$params = array('path' => $path);
			if (utils::$db->prepare($sql) === FALSE
			|| utils::$db->executeQuery($params, 'value') === FALSE)
			{
				return FALSE;
			}

			$update['params'][] = array(
				'cat_path' => ($path == '') ? '.' : substr($path, 0, strlen($path) - 1),
				'cat_lastadddt' => (utils::$db->nbResult === 0)
					? NULL
					: utils::$db->queryResult
			);

			$path = dirname($path);
		}

		// Si aucune mise à jour nécessaire, alors on arrête là.
		if (empty($update['params']))
		{
			return TRUE;
		}

		// Exécution des requêtes.
		if (utils::$db->exec(array(0 => $update), FALSE) === FALSE)
		{
			return FALSE;
		}

		// Exécution de la transaction.
		if ($transaction && !utils::$db->commit())
		{
			return FALSE;
		}

		return TRUE;
	}

	/**
	 * Fabrique la requête de mise à jour des statistiques des catégories parentes.
	 *
	 * @param array $update
	 *	Tableau des statistiques à ajouter ou soustraire
	 *	aux catégories parentes.
	 * @param boolean|string $a_up
	 *	Additionne ('+') ou soustrait ('-') les statistiques des
	 *	images activées. FALSE si aucune modification souhaitée.
	 * @param boolean|string $d_up
	 *	Additionne ('+') ou soustrait ('-') les statistiques des
	 *	images désactivées. FALSE si aucune modification souhaitée.
	 * @param array $ids
	 *	Identifiants des catégories parentes à mettre à jour.
	 * @return string
	 *	Retourne la requête SQL d'UPDATE des catégories parentes.
	 */
	public static function updateParentsStats($update, $a_up, $d_up, $ids)
	{
		$sql_set = array();

		// Statistiques des images activées.
		if ($a_up !== FALSE)
		{
			$nb_votes = (int) $update['a']['votes'];
			$cat_a_rate = ($update['a']['rate'])
				? ' cat_a_rate = CASE
						WHEN cat_a_votes ' . $a_up . ' ' . $nb_votes . ' > 0
						THEN ((cat_a_rate * cat_a_votes)
							' . $a_up . ' (' . (float) $update['a']['rate']
							. ' * ' . $nb_votes . '))
							/ (cat_a_votes ' . $a_up . ' ' . $nb_votes . ')
						ELSE 0
						 END,'
				: '';
			$sql_set[] = 'cat_a_size = cat_a_size ' . $a_up . '
				' . (int) $update['a']['size'] . ',
				cat_a_images = cat_a_images ' . $a_up . '
				' . (int) $update['a']['images'] . ',
				cat_a_albums = CASE
					WHEN cat_filemtime IS NULL
					THEN cat_a_albums ' . $a_up . '
						' . (int) $update['a']['albums'] . '
					ELSE 0
					 END,
				cat_a_hits = cat_a_hits ' . $a_up . '
				' . (int) $update['a']['hits'] . ',
				cat_a_comments = cat_a_comments ' . $a_up . '
				' . (int) $update['a']['comments'] . ',
				' . $cat_a_rate . '
				cat_a_votes = cat_a_votes ' . $a_up . '
				' . $nb_votes;
		}

		// Statistiques des images désactivées.
		if ($d_up !== FALSE)
		{
			$nb_votes = (int) $update['d']['votes'];
			$cat_d_rate = ($update['d']['rate'])
				? ' cat_d_rate = CASE
						WHEN cat_d_votes ' . $d_up . ' ' . $nb_votes . ' > 0
						THEN ((cat_d_rate * cat_d_votes)
							' . $d_up . ' (' . (float) $update['d']['rate']
							. ' * ' . $nb_votes . '))
							/ (cat_d_votes ' . $d_up . ' ' . $nb_votes . ')
						ELSE 0
						 END,'
				: '';
			$sql_set[] = 'cat_d_size = cat_d_size ' . $d_up . '
				' . (int) $update['d']['size'] . ',
				cat_d_images = cat_d_images ' . $d_up . '
				' . (int) $update['d']['images'] . ',
				cat_d_albums = CASE
					WHEN cat_filemtime IS NULL
					THEN cat_d_albums ' . $d_up . '
						' . (int) $update['d']['albums'] . '
					ELSE 0
					 END,
				cat_d_hits = cat_d_hits ' . $d_up . '
				' . (int) $update['d']['hits'] . ',
				cat_d_comments = cat_d_comments ' . $d_up . '
				' . (int) $update['d']['comments'] . ',
				' . $cat_d_rate . '
				cat_d_votes = cat_d_votes ' . $d_up . '
				' . $nb_votes;
		}

		// On force le statut des catégories sur "activée" si des informations
		// d'images activées ont été incrémentées.
		$cat_status = ($a_up == '+') ? ', cat_status = "1"' : '';

		return 'UPDATE ' . CONF_DB_PREF . 'categories
				   SET ' . implode(', ', $sql_set) . $cat_status . '
		         WHERE cat_id IN (' . implode(', ', array_map('intval', $ids)) . ')';
	}

	/**
	 * Met à jour le nombre de sous-catégories des catégories parentes.
	 *
	 * @param array $parent_ids
	 *	Identifiants des catégories parentes.
	 * @param boolean $transaction
	 *	Doit-on démarrer une transaction ?
	 * @return boolean
	 *	TRUE si succès.
	 *	FALSE si échec.
	 */
	public static function updateSubsCats($parent_ids, $transaction = TRUE)
	{
		$update = array(
			'sql' => 'UPDATE ' . CONF_DB_PREF . 'categories
						 SET cat_a_subalbs = :cat_a_subalbs,
							 cat_a_subcats = :cat_a_subcats,
							 cat_d_subalbs = :cat_d_subalbs,
							 cat_d_subcats = :cat_d_subcats
					   WHERE cat_id = :cat_id',
			'params' => array()
		);

		// Début de la transaction.
		if ($transaction && !utils::$db->transaction())
		{
			return FALSE;
		}

		// Pour chaque catégorie.
		foreach ($parent_ids as &$id)
		{
			// Récupération du nombre de sous-catégories.
			$sql = 'SELECT cat_status, COUNT(*) AS subcats
					  FROM ' . CONF_DB_PREF . 'categories
					 WHERE parent_id = ' . (int) $id . '
					   AND cat_filemtime IS NULL
				  GROUP BY cat_status';
			$fetch_style = array('fetch' => PDO::FETCH_ASSOC, 'column' => 'cat_status');
			if (utils::$db->query($sql, $fetch_style) === FALSE)
			{
				return FALSE;
			}
			$subcats = utils::$db->queryResult;
			$cat_a_subcats = (isset($subcats['1']))
				? $subcats['1']['subcats']
				: 0;
			$cat_d_subcats = (isset($subcats['0']))
				? $subcats['0']['subcats']
				: 0;

			// On ne compte pas la catégorie 1 pour la catégorie 1 !
			$cat_a_subcats -= ($id == 1) ? 1 : 0;

			// Récupération du nombre de sous-albums.
			$sql = 'SELECT cat_status, COUNT(*) AS subalbs
					  FROM ' . CONF_DB_PREF . 'categories
					 WHERE parent_id = ' . (int) $id . '
					   AND cat_filemtime IS NOT NULL
				  GROUP BY cat_status';
			$fetch_style = array('fetch' => PDO::FETCH_ASSOC, 'column' => 'cat_status');
			if (utils::$db->query($sql, $fetch_style) === FALSE)
			{
				return FALSE;
			}
			$subalbs = utils::$db->queryResult;
			$cat_a_subalbs = (isset($subalbs['1']))
				? $subalbs['1']['subalbs']
				: 0;
			$cat_d_subalbs = (isset($subalbs['0']))
				? $subalbs['0']['subalbs']
				: 0;

			// Paramètres de la requête préparée.
			$update['params'][] = array(
				'cat_id' => $id,
				'cat_a_subcats' => $cat_a_subcats,
				'cat_a_subalbs' => $cat_a_subalbs,
				'cat_d_subcats' => $cat_d_subcats,
				'cat_d_subalbs' => $cat_d_subalbs
			);
		}

		// Si aucune mise à jour nécessaire, alors on arrête là.
		if (empty($update['params']))
		{
			return TRUE;
		}

		// Exécution des requêtes.
		if (utils::$db->exec(array(0 => $update), FALSE) === FALSE)
		{
			return FALSE;
		}

		// Exécution de la transaction.
		if ($transaction && !utils::$db->commit())
		{
			return FALSE;
		}

		return TRUE;
	}

	/**
	 * Récupère des images envoyées par HTTP et les déplace
	 * dans le répertoire temporaire $temp_dir.
	 *
	 * @param string $name
	 *	Nom du fichier en provenance de $_FILES.
	 * @param string $tmp_name
	 *	Nom de fichier temporaire en provenance de $_FILES.
	 * @param integer $error
	 *	Erreur en provenance de $_FILES.
	 * @param string $temp_dir
	 *	Nom du répertoire temporaire.
	 * @param string $cat_path
	 *	Chemin de l'album destination servant à vérifier
	 *  le nom de fichier du fichier envoyé.
	 * @return mixed
	 *	FALSE : pas de fichier.
	 *	array : message d'erreur.
	 *	string : nom de fichier final.
	 */
	public static function uploadFile($name, $tmp_name, $error, $temp_dir, $cat_path)
	{
		// Liste des fichiers déjà récupérés.
		static $add_images = array();

		// Y a-t-il une erreur ?
		switch ($error)
		{
			// Aucune erreur.
			case 0 :
				break;

			// Fichier trop lourd.
			case 1 :
			case 2 :
				return array('warning', $name . ': ' . __('Le fichier est trop lourd.'));

			// Aucun fichier.
			case 4 :
				return FALSE;

			// Autre erreur.
			default :
				return array('error', sprintf($name . ': ' . __('Code erreur : %s'), $error));
		}

		// Le fichier a-t-il été chargé par POST ?
		if (!is_uploaded_file($tmp_name))
		{
			return FALSE;
		}

		// Nom de fichier et répertoire de destination.
		$albums_dest = GALLERY_ROOT . '/' . CONF_ALBUMS_DIR . '/';
		$filename_dest = $cat_path . '/' . $name;
		$test = $filename_dest;

		// Si un fichier de même nom existe dans le répertoire de destination,
		// on modifie le nom du fichier.
		$warning_name = __('Un fichier du même nom existe déjà dans cet album.');
		$n = 1;
		while (file_exists($albums_dest . $test) || in_array(basename($test), $add_images))
		{
			if ($n > 99)
			{
				return array('warning', $name . ': ' . $warning_name);
			}
			$test = preg_replace('`^(.+)\.([^\.]+)$`', '\1_' . $n . '.\2', $filename_dest);
			$n++;
		}
		if (file_exists($albums_dest . $test))
		{
			return array('warning', $name . ': ' . $warning_name);
		}

		// On déplace l'image vers le répertoire temporaire.
		$temp_filename = $temp_dir . '/' . basename($test);
		$temp_filename_original = $temp_dir . '/original/' . basename($test);
		if (!move_uploaded_file($tmp_name, $temp_filename))
		{
			return array('error', $name . ': '
				. __('Impossible de déplacer l\'image.'));
		}

		// Vérifications des paramètres de l'image.
		try
		{
			// Le fichier est-il trop lourd ?
			if (filesize($temp_filename) > (1024 * utils::$config['upload_maxfilesize']))
			{
				throw new Exception(serialize(array('warning', $name . ': '
					. __('Le fichier est trop lourd.'))));
			}

			// Le format de l'image est-il correct ?
			if (($size = img::getImageSize($temp_filename)) === FALSE
			|| !img::supportType($size['filetype']))
			{
				throw new Exception(serialize(array('warning', $name . ': '
					. __('Le fichier n\'est pas une image valide.'))));
			}

			// Dimensions de l'image.
			if ($size['width'] > utils::$config['upload_maxwidth']
			|| $size['height'] > utils::$config['upload_maxheight'])
			{
				$message = sprintf(
					__('L\'image ne doit pas faire plus de %s pixels'
						. ' de largeur et %s pixels de hauteur.'),
					(int) utils::$config['upload_maxwidth'],
					(int) utils::$config['upload_maxheight']);
				throw new Exception(serialize(array('warning', $name . ': ' . $message)));
			}

			// On redimensionne l'image si nécessaire.
			$max_width = (int) utils::$config['upload_resize_maxwidth'];
			$max_height = (int) utils::$config['upload_resize_maxheight'];
			if (utils::$config['upload_resize']
			&& ($size['width'] > $max_width || $size['height'] > $max_height))
			{
				if (!is_dir($temp_dir . '/original'))
				{
					files::mkdir($temp_dir . '/original');
				}
				if (!files::copyFile($temp_filename, $temp_filename_original))
				{
					throw new Exception(serialize(array('error', $name . ': Cannot copy file.')));
				}
				$image = img::gdCreateImage($temp_filename, $size['filetype']);
				if (is_string($image))
				{
					throw new Exception(serialize(array('error', $name . ': ' . $image)));
				}
				if (is_bool($image))
				{
					throw new Exception(serialize(array('error', $name . ': '
						. sprintf('Cannot create image (%s).', $size['filetype']))));
				}
				$resize = img::imageResize($size['width'], $size['height'],
					$max_width, $max_height);
				$image = img::gdResize($image, 0, 0, $size['width'], $size['height'],
					0, 0, $resize['width'], $resize['height']);

				img::gdCreateFile($image, $temp_filename, $size['filetype'],
					utils::$config['upload_resize_quality']);
			}

			// Tout est OK : on retourne le nom de fichier.
			$add_images[] = basename($test);
			return basename($test);
		}
		catch (Exception $e)
		{
			// On supprime l'image déplacée.
			files::unlink($temp_filename);

			return unserialize($e->getMessage());
		}
	}
}
?>