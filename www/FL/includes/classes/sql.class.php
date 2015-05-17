<?php
/**
 * Code SQL commun.
 *
 * @license http://www.gnu.org/licenses/gpl.html
 * @link http://www.igalerie.org/
 */
class sql
{
	/**
	 * Partie de la clause WHERE contenant les restrictions
	 * d'accès aux images protégées par mot de passe.
	 *
	 * @var string
	 */
	public static $groupAlbumsAccess;



	/**
	 * Construit la partie de la clause WHERE qui restreint l'accès aux
	 * catégories protégées en fonction des permissions $perms.
	 *
	 * @param array $perms
	 *	Permissions d'accès aux albums de la galerie.
	 * @return string
	 */
	public static function albumsAccess(&$perms)
	{
		self::$groupAlbumsAccess = ' AND cat_password IS NULL';

		switch ($perms['perms']['access_mode'])
		{
			// Toutes les catégories.
			case 1 :
				self::$groupAlbumsAccess = '';
				break;

			// Catégories sélectionnées.
			case 2 :
				if (!empty($perms['albums_access']))
				{
					self::$groupAlbumsAccess = ' AND (cat_password IS NULL';
					foreach ($perms['albums_access'] as $id => &$v)
					{
						self::$groupAlbumsAccess .=
							' OR cat_password LIKE "' . (int) $id . ':%"';
					}
					self::$groupAlbumsAccess .= ')';
				}
				break;
		}

		return self::$groupAlbumsAccess;
	}

	/**
	 * Retourne un intervalle de date pour les recherches SQL d'images.
	 *
	 * @param string $col
	 *	Date d'ajout ou date de création ?
	 * @return string
	 */
	public static function getSQLIntervalDate($col)
	{
		switch (strlen($_GET['date']))
		{
			// Jour.
			case 10 :
				$date_start = $_GET['date'];
				$date_end = $_GET['date'];
				break;

			// Mois.
			case 7 :
				$date_start = $_GET['date'] . '-01';
				$date_end = $_GET['date'] . '-31';
				break;

			// Année.
			case 4 :
				$date_start = $_GET['date'] . '-01-01';
				$date_end = $_GET['date'] . '-12-31';
				break;
		}
		return 'image_' . $col . ' >= "' . $date_start . ' 00:00:00"
			AND image_' . $col . ' <= "' . $date_end . ' 23:59:59" ';
	}

	/**
	 * Construit la clause FROM pour la récupération
	 * des images de la section courante.
	 *
	 * @param string $param
	 * @param string $from
	 * @return string
	 */
	public static function imagesSQLFrom($param, $from = '')
	{
		switch ($_GET[$param])
		{
			case 'basket' :
				$sql = 'LEFT JOIN ' . CONF_DB_PREF . 'basket AS b
							   ON b.image_id = img.image_id
						LEFT JOIN ' . CONF_DB_PREF . 'sessions AS s
							   ON b.session_id = s.session_id';
				break;

			case 'camera-brand' :
				$sql = 'LEFT JOIN ' . CONF_DB_PREF . 'cameras_models_images AS cam_mi
							   ON cam_mi.image_id = img.image_id
						LEFT JOIN ' . CONF_DB_PREF . 'cameras_models AS cam_m
							   ON cam_mi.camera_model_id = cam_m.camera_model_id
						LEFT JOIN ' . CONF_DB_PREF . 'cameras_brands AS cam_b
							   ON cam_m.camera_brand_id = cam_b.camera_brand_id';
				break;

			case 'camera-model' :
				$sql = 'LEFT JOIN ' . CONF_DB_PREF . 'cameras_models_images AS cam_mi
							   ON cam_mi.image_id = img.image_id
						LEFT JOIN ' . CONF_DB_PREF . 'cameras_models AS cam_m
							   ON cam_mi.camera_model_id = cam_m.camera_model_id';
				break;

			case 'tag' :
				$sql = 'LEFT JOIN ' . CONF_DB_PREF . 'tags_images AS ti
							   ON ti.image_id = img.image_id
						LEFT JOIN ' . CONF_DB_PREF . 'tags AS t
							   ON ti.tag_id = t.tag_id';
				break;

			case 'user-comments' :
				$sql = 'LEFT JOIN ' . CONF_DB_PREF . 'comments AS com
							   ON com.image_id = img.image_id';
				break;

			case 'user-favorites' :
				$sql = 'LEFT JOIN ' . CONF_DB_PREF . 'favorites AS fav
							   ON fav.image_id = img.image_id';
				break;

			default :
				$sql = '';
		}

		return CONF_DB_PREF . 'images AS img
			' . $from . '
			LEFT JOIN ' . CONF_DB_PREF . 'categories AS cat
				   ON img.cat_id = cat.cat_id
			' . $sql;
	}

	/**
	 * Construit la clause ORDER BY pour la récupération
	 * des images de la section courante.
	 *
	 * @param array $infos
	 *	Informations utiles de la catégorie.
	 * @return string
	 */
	public static function imagesSQLOrder(&$infos)
	{
		$order_by = '';

		// Ordre de tri selon la section.
		$section = (isset($_GET['section_b']))
			? $_GET['section_b']
			: $_GET['section'];
		switch ($section)
		{
			// Filtre par catégorie.
			case 'album' :
			case 'category' :
				switch ($_GET['section'])
				{
					case 'user-favorites' :
						$order_by = 'fav.fav_date DESC, ';
						break;
				}
				break;

			// Panier.
			case 'basket' :
				$order_by = 'basket_date DESC, ';
				break;

			// Images les plus commentées.
			case 'comments-stats' :
				$order_by = 'image_comments DESC, ';
				break;

			// Images les plus visitées.
			case 'hits' :
				$order_by = 'image_hits DESC, ';
				break;

			// Images récentes.
			case 'recent-images' :
				$order_by = 'image_adddt DESC, cat.cat_id DESC, ';
				break;

			// Favoris de l'utilisateur.
			case 'user-favorites' :
				$order_by = 'fav.fav_date DESC, ';
				break;

			// Images les mieux notées.
			case 'votes' :
				$order_by = 'image_rate DESC, image_votes DESC, ';
				break;
		}

		$order_by .= utils::filters(
			($infos['cat_orderby'] === NULL)
				? utils::$config['sql_images_order_by']
				: $infos['cat_orderby'],
			'order_by'
		);

		// On élimine les doublons.
		$temp = array();
		$order_by = explode(',', $order_by);
		for ($i = 0, $count = count($order_by); $i < $count; $i++)
		{
			$order_by[$i] = explode(' ', trim($order_by[$i]));
			if (in_array($order_by[$i][0], $temp))
			{
				unset($order_by[$i]);
			}
			else
			{
				$temp[] = $order_by[$i][0];
				$order_by[$i] = trim(implode(' ', $order_by[$i]));
			}
		}

		// On ajoute "image_id" pour éviter de générer de mauvaises pages parentes.
		return str_replace(
			array('image_name', 'image_size'),
			array('LOWER(image_name)', '(image_width*image_height)'),
			implode(', ', $order_by)
		) . ' img.image_id DESC';
	}

	/**
	 * Requête enregistrant l'activité de l'utilisateur.
	 *
	 * @param string $action
	 * @param integer $user_id
	 * @param string $match
	 * @param string $post
	 * @return void
	 */
	public static function logUserActivity($action, $user_id = 2, $match = NULL, $post = NULL)
	{
		if (!utils::$config['users_log_activity'])
		{
			return;
		}

		if (is_array($post) && count($post))
		{
			$post = serialize($post);
			$post = (is_string($post)) ? $post : NULL;
		}

		if ($match !== NULL)
		{
			$match = mb_substr($match, 0, 255);
		}

		utils::$db->prepare('INSERT INTO ' . CONF_DB_PREF . 'users_logs (
			user_id, log_page, log_date, log_action, log_match, log_post, log_ip
		) VALUES (
			:user_id, :page, NOW(), :action, :match, :post, :ip
		)');
		utils::$db->executeExec(array(
			'action' => $action,
			'ip' => $_SERVER['REMOTE_ADDR'],
			'match' => $match,
			'post' => $post,
			'page' => isset($_GET['q']) ? $_GET['q'] : '',
			'user_id' => (int) $user_id
		));
	}

	/**
	 * Préférences concernant l'ordre des images.
	 *
	 * @param object $cookie
	 * @return void
	 */
	public static function prefOrderBy($cookie)
	{
		// Valeurs possibles.
		$prefs = array(
			'order_by' => array(
				'cookie' => 'oc',
				'params' => array(
					'p' => 'position',
					'n' => 'name',
					't' => 'path',
					'f' => 'filesize',
					's' => 'size',
					'h' => 'hits',
					'a' => 'adddt',
					'd' => 'crtdt',
					'c' => 'comments',
					'v' => 'votes',
					'r' => 'rate'
				)
			),
			'asc_desc' => array(
				'cookie' => 'os',
				'params' => array(
					'a' => 'ASC',
					'd' => 'DESC'
				)
			)
		);

		// Si l'autorisation pour personnaliser cette option
		// n'est pas accordée, on arrête là.
		if (!utils::$config['widgets_params']['options']['items']['order_by'])
		{
			return;
		}

		$order_by = '';

		foreach ($prefs as $pref => &$infos)
		{
			$p = FALSE;
			$params = array_flip($infos['params']);

			// Lecture de l'information envoyée par POST.
			if (isset($_POST[$pref]))
			{
				$p = $_POST[$pref];
				$p = (isset($params[$p])) ? $params[$p] : FALSE;
			}

			// Lecture de l'information envoyée par COOKIE.
			if ($p === FALSE)
			{
				$p = $cookie->read($infos['cookie']);
				if (!preg_match('`^[a-z]$`', $p))
				{
					$p = FALSE;
				}
			}

			// Si l'option en provenance de POST a été validée,
			// on l'ajoute au cookie.
			if ($p !== FALSE && !empty($_POST))
			{
				$cookie->add($infos['cookie'], $p);
			}

			$order_by = ($p !== FALSE && $order_by !== FALSE)
				? $order_by . ' ' . $infos['params'][$p]
				: FALSE;
		}

		// Si l'option en provenance de POST ou de COOKIE
		// a été validée, on modifie l'option dans la config.
		if (!empty($order_by))
		{
			$order_by = trim($order_by);
			$order_by = (strstr($order_by, 'size') && !strstr($order_by, 'filesize'))
				? str_replace('size', '(image_width*image_height)', $order_by)
				: 'image_' . trim($order_by);

			utils::$config['sql_images_order_by'] = $order_by . ', '
				. utils::$config['sql_images_order_by'];
		}
	}

	/**
	 * Ajoute les restrictions d'accès aux catégories protégées
	 * pour les requêtes passées en paramètre.
	 *
	 * @param string $field_pref
	 *	Image ('image') ou categorie ('cat').
	 * @param string $sql
	 *	Requête SQL.
	 * @param string|array $fetch_style
	 *	Format du résultat.
	 * @param string|array $auth
	 *	L'utilisateur est-il autorisé pour la catégorie courante ?
	 * 	S'il l'est, cela évitera d'effectuer une sous-requête pour le vérifier.
	 * @param array $params
	 *	Paramètres pour la requête préparée.
	 * @param boolean $deactivate
	 *	Les images ou catégories peuvent-elles être désactivées ?
	 * @return array
	 *	Résultat de la requête et nombre de lignes affectées.
	 */
	public static function sqlProtect($field_pref, $sql, $fetch_style = NULL,
	$auth = FALSE, $params = array(), $deactivate = FALSE)
	{
		// Statut.
		$sub = '(' . $field_pref . '_status = "1"';
		$sub .= ($deactivate) ? ' OR ' . $field_pref . '_status = "0"' : '';
		$sub .= ')';

		// Gestion de membres : on ajoute les restrictions d'accès du groupe.
		if (utils::$config['users'] == 1
		&& utils::$config['users_locked_albums_access_mode'] == 'groups')
		{
			if (self::$groupAlbumsAccess === NULL)
			{
				trigger_error('Undefined permissions.', E_USER_ERROR);
				die;
			}

			$sub .= self::$groupAlbumsAccess;
		}

		// Accès par mot de passe.
		// Les images ou catégories doivent ne pas avoir de mot de passe,
		// ou bien un mot de passe correspondant à une session courante.
		else if (!$auth || (utils::$config['users'] == 1
		&& utils::$config['users_locked_albums_access_mode'] != 'groups'))
		{
			if (($session_token = user::getSessionCookieToken()) !== FALSE)
			{
				$sub .= ' AND (cat_password IS NULL OR
								(SELECT 1
								   FROM ' . CONF_DB_PREF . 'passwords
							  LEFT JOIN ' . CONF_DB_PREF . 'sessions USING(session_id)
								  WHERE cat_password LIKE CONCAT("%:", password)
									AND session_token = :session_token
									AND session_expire > NOW()
								) = 1
							  )';
				$params['session_token'] = $session_token;
			}
			else
			{
				$sub .= ' AND cat_password IS NULL';
			}
		}

		// Exécution de la requête.
		if (empty($params))
		{
			if (utils::$db->query(sprintf($sql, $sub), $fetch_style, 1) === FALSE)
			{
				return FALSE;
			}
		}
		else
		{
			if (utils::$db->prepare(sprintf($sql, $sub), 1) === FALSE
			|| utils::$db->executeQuery($params, $fetch_style) === FALSE)
			{
				return FALSE;
			}
		}

		// Résultat de la requête.
		return array(
			'nb_result' => utils::$db->nbResult,
			'query_result' => utils::$db->queryResult
		);
	}

	/**
	 * Crée une clause WHERE selon la section courante pour la récupération
	 * des informations des vignettes.
	 *
	 * @param array $category_infos
	 * @param array $user_infos
	 * @param string $section
	 * @return string
	 *	Clause WHERE
	 */
	public static function thumbsSQLWhere($category_infos, $user_infos = NULL, $section = NULL)
	{
		if ($category_infos['cat_id'] == 1)
		{
			$path = '';
		}
		else if ($category_infos['cat_filemtime'] === NULL)
		{
			$path = utils::filters($category_infos['cat_path'], 'path');
			$path = 'image_path LIKE "' . $path . '/%" AND ';
		}
		else
		{
			$path = 'cat.cat_id = ' . (int) $category_infos['cat_id'] . ' AND ';
		}

		if (!$section)
		{
			$section = $_GET['section'];
		}
		switch ($section)
		{
			// Album, catégorie ou image.
			case 'album' :
			case 'category' :
			case 'image' :
				$where = 'cat.cat_id = ' . (int) $category_infos['cat_id'] . ' ';
				break;

			// Panier.
			case 'basket' :
				if (is_array($user_infos) && isset($user_infos['user_id'])
				&& $user_infos['user_id'] != 2)
				{
					$where = 'b.user_id = ' . (int) $user_infos['user_id'] . ' ';
				}
				else
				{
					$where = 's.session_token = "' . user::getSessionCookieToken() . '" ';
				}
				break;

			// Images du fabriquant.
			case 'camera-brand' :
				$where = $path . 'cam_b.camera_brand_id = ' . (int) $_GET['camera_id'] . ' ';
				break;

			// Images du modèle.
			case 'camera-model' :
				$where = $path . 'cam_m.camera_model_id = ' . (int) $_GET['camera_id'] . ' ';
				break;

			// Images les plus commentées.
			case 'comments-stats' :
				$where = $path . 'image_comments > 0 ';
				break;

			// Images ajoutées le.
			case 'date-added' :
				$where = $path . self::getSQLIntervalDate('adddt');
				break;

			// Images créées le.
			case 'date-created' :
				$where = $path . self::getSQLIntervalDate('crtdt');
				break;

			// Image les plus visitées.
			case 'hits' :
				$where = $path . 'image_hits > 0 ';
				break;

			// Images de la section.
			case 'images' :
				$where = ($path === '')
					? ''
					: substr($path, 0, -4);
				break;

			// Images récentes.
			case 'recent-images' :
				$recent_images_limit = (int) $_SERVER['REQUEST_TIME']
					- ((int) utils::$config['recent_images_time'] * 86400);
				$where = $path . 'image_adddt > "'
					. date('Y-m-d H:i:s', $recent_images_limit) . '" ';
				break;

			// Tags.
			case 'tag' :
				$where = $path . 't.tag_id = ' . (int) $_GET['tag_id'] . ' ';
				break;

			// Favoris de l'utilisateur.
			case 'user-favorites' :
				$where = $path . 'fav.user_id = ' . (int) $_GET['user_id'] . ' ';
				break;

			// Images de l'utilisateur.
			case 'user-images' :
				$where = $path . 'img.user_id = ' . (int) $_GET['user_id'] . ' ';
				break;

			// Images les mieux notées.
			case 'votes' :
				$where = $path . 'image_votes > 0 ';
				break;

			default :
				$where = '';
		}

		return ($where === '')
			? ''
			: $where . 'AND ';
	}
}
?>