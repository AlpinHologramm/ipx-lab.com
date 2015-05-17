<?php
/**
 * Gestion des tags.
 *
 * @license http://www.gnu.org/licenses/gpl.html
 * @link http://www.igalerie.org/
 */
class tagsImage
{
	/**
	 * Ajoute des tags à des images.
	 *
	 * @param array $images_id
	 *	Identifiants des images sur lesquelles ajouter les tags.
	 * @param string $tags
	 *	Tags à ajouter, séparés par une virgule.
	 * @param boolean $transaction
	 *	Doit-on effectuer une transaction ?
	 * @param boolean $log
	 *	Doit-on enregistrer l'activité ?
	 * @param boolean $user_id
	 *	Identifiant de l'utilisateur.
	 * @return string
	 */
	public static function add($images_id, $tags, $transaction = TRUE, $log = FALSE, $user_id = 0)
	{
		try
		{
			// Nouveaux tags.
			$tags = array_map('trim', explode(',', $tags));
			$params = array();
			foreach ($tags as &$tag)
			{
				if ($tag === '')
				{
					continue;
				}
				$params[] = array(
					'tag_name' => $tag,
					'tag_url' => utils::genURLName($tag)
				);
			}
			if ($params === array())
			{
				return;
			}

			// Début de la transaction.
			if ($transaction && !utils::$db->transaction())
			{
				throw new Exception(utils::$db->msgError);
			}

			// Enregistrement des tags.
			$sql = array(array(
				'params' => $params,
				'sql' => 'INSERT IGNORE INTO ' . CONF_DB_PREF . 'tags
					(tag_name, tag_url) VALUES (:tag_name, :tag_url)'
			));
			if (utils::$db->exec($sql, FALSE) === FALSE)
			{
				throw new Exception(utils::$db->msgError);
			}

			// On récupère les informations utiles de tous les tags.
			$sql = 'SELECT tag_id,
						   tag_name
					  FROM ' . CONF_DB_PREF . 'tags';
			$fetch_style = array('column' => array('tag_id', 'tag_name'));
			if (utils::$db->query($sql, $fetch_style) === FALSE)
			{
				throw new Exception(utils::$db->msgError);
			}
			$tags_id_name = utils::$db->queryResult;

			// Enregistrement des associations tag => images.
			$params = array();
			foreach ($tags_id_name as $tag_id => &$tag_name)
			{
				if (!in_array($tag_name, $tags))
				{
					continue;
				}
				foreach ($images_id as &$image_id)
				{
					$params[] = array(
						'tag_id' => $tag_id,
						'image_id' => $image_id
					);
				}
			}
			$sql = array(array(
				'params' => $params,
				'sql' => 'INSERT IGNORE INTO ' . CONF_DB_PREF . 'tags_images
					(tag_id, image_id) VALUES (:tag_id, :image_id)'
			));
			if (utils::$db->exec($sql, FALSE) === FALSE)
			{
				throw new Exception(utils::$db->msgError);
			}

			// Log d'activité.
			if ($log)
			{
				sql::logUserActivity('tags_add', $user_id, NULL, array(
					'images' => implode(', ', $images_id),
					'tags' => implode(', ', $tags))
				);
			}

			// Exécution de la transaction.
			if ($transaction && !utils::$db->commit())
			{
				throw new Exception(utils::$db->msgError);
			}

			return 'success:' . __('Les tags ont été ajoutés.');
		}
		catch (Exception $e)
		{
			return 'error:' . $e->getMessage();
		}
	}

	/**
	 * Détermine les tags à ajouter et ceux à supprimer.
	 *
	 * @param integer $id
	 *	Identifiant de l'image.
	 * @param string $tags_post
	 *	Tags envoyés par $_POST, séparés par une virgule.
	 * @param array $tags_current
	 *	Tags actuels de l'image.
	 * @param string $tags_update
	 *	Informations sur la mise à jour des tags.
	 * @return array|null
	 *	Retourne un tableau des tags postés si changement,
	 *	NULL sinon.
	 */
	public static function edit($id, $tags_post, &$tags_current, &$tags_update)
	{
		// Tags actuels de l'image.
		sort($tags_current);

		// Tags postés.
		$tags_post = preg_split('`,`', $tags_post, -1, PREG_SPLIT_NO_EMPTY);
		$tags_post = array_map('trim', $tags_post);
		$tags_post = array_filter(
			$tags_post,
			create_function('$t', 'if (!utils::isEmpty($t)) return $t;')
		);
		$tags_post = array_unique($tags_post);
		sort($tags_post);

		// Si aucun changement.
		if ($tags_current === $tags_post)
		{
			return;
		}

		// Nouveaux tags.
		if (($tags_add = array_diff($tags_post, $tags_current)) !== array())
		{
			$tags_update['add'][$id] = $tags_add;
			$tags_update['tags'] = array_merge($tags_update['tags'], $tags_add);
		}

		// Tags supprimés.
		if (($tags_delete = array_diff($tags_current, $tags_post)) !== array())
		{
			$tags_update['delete'][$id] = $tags_delete;
			$tags_update['tags'] = array_merge($tags_update['tags'], $tags_delete);
		}

		return $tags_post;
	}

	/**
	 * Supprime des tags associés à des images.
	 *
	 * @param array $images_id
	 *	Identifiants des images sur lesquelles supprimer les tags.
	 * @param string $tags
	 *	Tags à supprimer, séparés par une virgule.
	 * @param boolean $transaction
	 *	Doit-on effectuer une transaction ?
	 * @param boolean $log
	 *	Doit-on enregistrer l'activité ?
	 * @param boolean $user_id
	 *	Identifiant de l'utilisateur.
	 * @return string
	 */
	public static function remove($images_id, $tags, $transaction = TRUE, $log = FALSE,
	$user_id = 0)
	{
		try
		{
			// Tags.
			$tags = array_map('trim', explode(',', $tags));

			// Début de la transaction.
			if ($transaction && !utils::$db->transaction())
			{
				throw new Exception(utils::$db->msgError);
			}

			// Suppression des associations tags - images
			$sql = 'DELETE
				 	  FROM ' . CONF_DB_PREF . 'tags_images
					 USING ' . CONF_DB_PREF . 'tags,
						   ' . CONF_DB_PREF . 'tags_images
					 WHERE image_id IN (' . implode(', ', $images_id) . ')
					   AND ' . CONF_DB_PREF . 'tags_images.tag_id
					     = ' . CONF_DB_PREF . 'tags.tag_id
					   AND tag_name IN (?' . str_repeat(', ?', count($tags) - 1) . ')';
			if (utils::$db->prepare($sql) === FALSE
			|| utils::$db->executeExec($tags) === FALSE)
			{
				throw new Exception(utils::$db->msgError);
			}

			// On récupère les identifiants des tags
			// qui ne sont plus associés à au moins une image.
			$sql = 'SELECT tag_id,
						   (SELECT COUNT(*)
							  FROM ' . CONF_DB_PREF . 'tags_images AS ti
							 WHERE ti.tag_id = t.tag_id) AS tag_nb_images
					  FROM ' . CONF_DB_PREF . 'tags AS t
					 WHERE tag_name IN (?' . str_repeat(', ?', count($tags) - 1) . ')';
			$fetch_style = array('column' => array('tag_id', 'tag_nb_images'));
			if (utils::$db->prepare($sql) === FALSE
			|| utils::$db->executeQuery($tags, $fetch_style) === FALSE)
			{
				throw new Exception(utils::$db->msgError);
			}
			$tag_nb_images = array_keys(utils::$db->queryResult, '0');

			// On supprime les tags qui ne sont plus associés à au moins une image.
			if (count($tag_nb_images) > 0)
			{
				$sql = 'DELETE
					      FROM ' . CONF_DB_PREF . 'tags
						 WHERE tag_id IN (' . implode(', ', $tag_nb_images) . ')';
				if (utils::$db->exec($sql, FALSE) === FALSE)
				{
					throw new Exception(utils::$db->msgError);
				}
			}

			// Log d'activité.
			if ($log)
			{
				sql::logUserActivity('tags_remove', $user_id, NULL, array(
					'images' => implode(', ', $images_id),
					'tags' => implode(', ', $tags))
				);
			}

			// Exécution de la transaction.
			if ($transaction && !utils::$db->commit())
			{
				throw new Exception(utils::$db->msgError);
			}

			return 'success:' . __('Les tags ont été supprimés.');
		}
		catch (Exception $e)
		{
			return 'error:' . $e->getMessage();
		}
	}

	/**
	 * Supprime tous les tags associés à des images.
	 *
	 * @param array $images_id
	 *	Identifiants des images sur lesquelles supprimer les tags.
	 * @param boolean $transaction
	 *	Doit-on effectuer une transaction ?
	 * @return string
	 */
	public static function removeAll($images_id, $transaction = TRUE)
	{
		try
		{
			// Début de la transaction.
			if ($transaction && !utils::$db->transaction())
			{
				throw new Exception(utils::$db->msgError);
			}

			// Récupère les tags associés aux images.
			$sql = 'SELECT tag_name
					  FROM ' . CONF_DB_PREF . 'tags AS t,
						   ' . CONF_DB_PREF . 'tags_images AS ti
					 WHERE t.tag_id = ti.tag_id
					   AND image_id IN (' . implode(', ', $images_id) . ')';
			$fetch_style = array('column' => array('tag_name', 'tag_name'));
			if (utils::$db->query($sql, $fetch_style) === FALSE)
			{
				throw new Exception(utils::$db->msgError);
			}
			if (utils::$db->nbResult === 0)
			{
				return;
			}
			$tags = array_keys(utils::$db->queryResult);

			// Suppression des associations tags - images
			$sql = 'DELETE
				 	  FROM ' . CONF_DB_PREF . 'tags_images
					 WHERE image_id IN (' . implode(', ', $images_id) . ')';
			if (utils::$db->exec($sql) === FALSE)
			{
				throw new Exception(utils::$db->msgError);
			}

			// On récupère les identifiants des tags
			// qui ne sont plus associés à au moins une image.
			$sql = 'SELECT tag_id,
						   (SELECT COUNT(*)
							  FROM ' . CONF_DB_PREF . 'tags_images AS ti
							 WHERE ti.tag_id = t.tag_id) AS tag_nb_images
					  FROM ' . CONF_DB_PREF . 'tags AS t
					 WHERE tag_name IN (?' . str_repeat(', ?', count($tags) - 1) . ')';
			$fetch_style = array('column' => array('tag_id', 'tag_nb_images'));
			if (utils::$db->prepare($sql) === FALSE
			|| utils::$db->executeQuery($tags, $fetch_style) === FALSE)
			{
				throw new Exception(utils::$db->msgError);
			}
			$tag_nb_images = array_keys(utils::$db->queryResult, '0');

			// On supprime les tags qui ne sont plus associés à au moins une image.
			if (count($tag_nb_images) > 0)
			{
				$sql = 'DELETE
					      FROM ' . CONF_DB_PREF . 'tags
						 WHERE tag_id IN (' . implode(', ', $tag_nb_images) . ')';
				if (utils::$db->exec($sql, FALSE) === FALSE)
				{
					throw new Exception(utils::$db->msgError);
				}
			}

			// Exécution de la transaction.
			if ($transaction && !utils::$db->commit())
			{
				throw new Exception(utils::$db->msgError);
			}

			return 'success:' . __('Les tags ont été supprimés.');
		}
		catch (Exception $e)
		{
			return 'error:' . $e->getMessage();
		}
	}

	/**
	 * Mise à jour des tags en base de données.
	 *
	 * @param array $tags_update
	 *	Informations sur la mise à jour des tags.
	 * @param boolean $transaction
	 *	Doit-on effectuer une transaction ?
	 * @return array|null
	 */
	public static function update(&$tags_update, $transaction = TRUE)
	{
		if (count($tags_update['tags']) < 1)
		{
			return;
		}

		try
		{
			// Début de la transaction.
			if ($transaction && !utils::$db->transaction())
			{
				throw new Exception();
			}

			// Récupération de l'identifiant
			// des tags ajoutés (s'ils existent) ou supprimés.
			$params = array_unique($tags_update['tags']);
			sort($params);
			$sql = 'SELECT tag_id,
						   tag_name
					  FROM ' . CONF_DB_PREF . 'tags
					 WHERE tag_name IN (?' . str_repeat(', ?', count($params) - 1) . ')';
			$fetch_style = array(
				'column' => array('tag_name', 'tag_id')
			);
			if (utils::$db->prepare($sql) === FALSE
			|| utils::$db->executeQuery($params, $fetch_style) === FALSE)
			{
				throw new Exception();
			}
			$tags_id = utils::$db->queryResult;

			// On ajoute les nouveaux tags dans la table des tags.
			$tags_db = array_keys($tags_id);
			sort($tags_db);
			$tags_insert = array_diff($params, $tags_db);
			if (count($tags_insert) > 0)
			{
				$sql = array(
					'sql' => 'INSERT IGNORE INTO ' . CONF_DB_PREF . 'tags
						(tag_name, tag_url) VALUES (:tag_name, :tag_url)',
					'params' => array()
				);
				foreach ($tags_insert as &$tag_name)
				{
					$sql['params'][] = array(
						'tag_name' => $tag_name,
						'tag_url' => utils::genURLName($tag_name)
					);
				}
				if (utils::$db->exec(array($sql), FALSE) === FALSE)
				{
					throw new Exception();
				}
				$tags_id_params = utils::$db->lastInsertIdParams;
				foreach ($tags_id_params as $tag_id => &$tag_infos)
				{
					$tags_id[$tag_infos['tag_name']] = $tag_id;
				}
			}

			// On supprime les associations tag - image.
			if (count($tags_update['delete']) > 0)
			{
				foreach ($tags_update['delete'] as $image_id => &$tags_delete)
				{
					if (count($tags_delete) < 1)
					{
						continue;
					}

					$tags_delete_ids = '';
					foreach ($tags_delete as &$tag_delete_name)
					{
						$tags_delete_ids .= ', ' . (int) $tags_id[$tag_delete_name];
					}

					$sql = 'DELETE
							  FROM ' . CONF_DB_PREF . 'tags_images
							 WHERE image_id = ' . (int) $image_id . '
							   AND tag_id IN (' . substr($tags_delete_ids, 2) . ')';
					if (utils::$db->exec($sql, FALSE) === FALSE)
					{
						throw new Exception();
					}
				}
			}

			// On ajoute les nouvelles associations tag - image.
			if (count($tags_update['add']) > 0)
			{
				$sql = array(
					'params' => array(),
					'sql' => 'INSERT IGNORE INTO ' . CONF_DB_PREF . 'tags_images
						(tag_id, image_id) VALUES (:tag_id, :image_id)'
				);
				foreach ($tags_update['add'] as $image_id => &$tags_add)
				{
					foreach ($tags_add as &$tag_add_name)
					{
						if ($tags_id[$tag_add_name] > 0)
						{
							$sql['params'][] = array(
								'tag_id' => $tags_id[$tag_add_name],
								'image_id' => $image_id
							);
						}
					}
				}
				if (utils::$db->exec(array($sql), FALSE) === FALSE)
				{
					throw new Exception();
				}
			}

			// Exécution de la transaction.
			if ($transaction && !utils::$db->commit())
			{
				throw new Exception();
			}

			return array(
				'success' => TRUE
			);
		}
		catch (Exception $e)
		{
			return array(
				'success' => FALSE,
				'message' => utils::$db->msgError
			);
		}
	}
}
?>