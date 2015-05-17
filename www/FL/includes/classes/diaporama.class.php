<?php
/**
 * Récupération et envoi au format JSON
 * des informations utiles pour remplir le diaporama.
 * Voir /js/diaporama.js.
 *
 * @license http://www.gnu.org/licenses/gpl.html
 * @link http://www.igalerie.org/
 */
class diaporama
{
	/**
	 * Informations utiles de la catégorie courante.
	 *
	 * @var array
	 */
	private static $_categoryInfos;

	/**
	 * Informations utiles des catégories parentes de l'image courante.
	 *
	 * @var array
	 */
	private static $_currentImageParents;

	/**
	 * Informations utiles des images.
	 *
	 * @var array
	 */
	private static $_images = array();

	/**
	 * Nombre d'images que contient la catégorie courante.
	 *
	 * @var integer
	 */
	private static $_nbImages;



	/**
	 * Récupère les informations utiles pour générer un carrousel.
	 *
	 * @param integer $max_images
	 * @return void
	 */
	public static function getCarousel($max_images = 60)
	{
		self::_controller();

		$size = (isset($_POST['size']) && (int) $_POST['size'] >= 50)
			? (int) $_POST['size']
			: 110;

		// Critères de tri.
		sql::prefOrderBy(utils::$cookiePrefs);

		// Récupération des informations utiles de la catégorie courante.
		self::_getCategoryInfos();

		// Récupération des informations utiles des images.
		if ($_GET['section'] == 'search')
		{
			$sql_where = search::getImagesSQLWhere(ajax::$userPerms);
			if (!is_array($sql_where))
			{
				return;
			}
			$sql_where['sql'] .= ' AND ';
			self::_countImages($sql_where);
			$sql_params = $sql_where['params'];
			$sql_where = $sql_where['sql'];
		}
		else
		{
			$sql_params = array();
			$sql_where = sql::thumbsSQLWhere(self::$_categoryInfos, ajax::$userInfos);
			self::_countImages($sql_where);
		}

		// Clause LIMIT.
		$sql_limit = self::_sqlLimit($max_images);

		// Clause FROM.
		$sql_from = 'LEFT JOIN ' . CONF_DB_PREF . 'users AS u
							ON u.user_id = img.user_id';

		// Récupération de l'image actuelle et des images précédentes et suivantes.
		$sql = 'SELECT img.image_id,
					   img.image_path,
					   img.image_url,
					   img.image_tb_infos AS tb_infos,
					   img.image_width,
					   img.image_height,
					   img.image_name,
					   img.image_adddt
				  FROM ' . sql::imagesSQLFrom('section', $sql_from) . '
				 WHERE ' . str_replace('%', '%%', $sql_where) . ' %s
			  ORDER BY ' . sql::imagesSQLOrder(self::$_categoryInfos) . '
				 LIMIT ' . $sql_limit;
		$fetch_style = array('fetch' => PDO::FETCH_ASSOC, 'column' => 'image_id');
		$result = sql::sqlProtect('image', $sql, $fetch_style, FALSE, $sql_params);
		if ($result === FALSE)
		{
			die(json_encode(array('status' => 'error')));
		}
		if ($result['nb_result'] === 0)
		{
			die(json_encode(array('status' => 'no_result')));
		}

		// Fermeture de la connexion à la base de données.
		utils::$db->connexion = NULL;

		// Paramètres des vignettes.
		$images = array();
		$position = (int) (preg_replace('`,\d+$`', '', $sql_limit)) + 1;
		foreach ($result['query_result'] as &$i)
		{
			$i['image_name'] = utils::tplProtect($i['image_name']);
			$i['image_url'] = utils::tplProtect($i['image_url']);

			// Dimensions et centrage de la vignette.
			$tb = img::getThumbSize($i, 'img', $size);
			$i['thumb_width'] = $tb['width'];
			$i['thumb_height'] = $tb['height'];
			$i['thumb_center'] = img::thumbCenter('img', $tb['width'], $tb['height'], $size);

			// Emplacement de la vignette.
			$i['thumb_src'] = utils::tplProtect(template::getThumbSrc('img', $i));

			$images[$position] = $i;

			// Position.
			$position++;
		}
		unset($result);

		die(json_encode(array(
			'anticsrf' => utils::tplProtect(utils::$anticsrfToken),
			'images' => $images,
			'nb_images' => self::$_nbImages
		)));
	}

	/**
	 * Récupère les informations utiles pour générer un diaporama.
	 *
	 * @return void
	 */
	public static function getDiaporama()
	{
		if (!isset($_POST['preload']))
		{
			$_POST['preload'] = 2;
		}

		self::_controller();

		// Critères de tri.
		sql::prefOrderBy(utils::$cookiePrefs);

		// Récupération des informations utiles de la catégorie courante.
		self::_getCategoryInfos();

		// Récupération des informations utiles des images.
		if ($_GET['section'] == 'search')
		{
			$sql_where = search::getImagesSQLWhere(ajax::$userPerms);
			if (!is_array($sql_where))
			{
				die;
			}
			$sql_where['sql'] .= ' AND ';
			self::_countImages($sql_where);
			self::_getAllImages($sql_where);
		}
		else
		{
			self::_countImages();
			self::_getAllImages();
		}

		// Nouveau jeton anti-CSRF.
		if (ajax::$userInfos['user_id'] != 2)
		{
			utils::antiCSRFTokenNew(utils::$cookiePrefs);
		}

		// Envoi des données.
		self::_JSON();
	}



	/**
	 * Contrôleur.
	 *
	 * @return void
	 */
	public static function _controller()
	{
		// Extraction de la requête.
		extract_request(array(
			'album' => array
			(
				'(\d{1,11})-[^/]{1,255}/position/(\d{1,11})' =>
					array('object_id', 'position')
			),
			'basket' => array
			(
				'position/(\d{1,11})' =>
					array('position')
			),
			'camera-brand' => array
			(
				'(\d{1,11})-[^/]{1,255}/position/(\d{1,11})' =>
					array('camera_id', 'position'),
				'(\d{1,11})-[^/]{1,255}/(album|category)/(\d{1,11})-[^/]{1,255}'
					. '/position/(\d{1,11})' =>
					array('camera_id', 'section_b', 'cat_id', 'position')
			),
			'camera-model' => array
			(
				'(\d{1,11})-[^/]{1,255}/position/(\d{1,11})' =>
					array('camera_id', 'position'),
				'(\d{1,11})-[^/]{1,255}/(album|category)/(\d{1,11})-[^/]{1,255}'
					. '/position/(\d{1,11})' =>
					array('camera_id', 'section_b', 'cat_id', 'position')
			),
			'comments-stats' => array
			(
				'(\d{1,11})-[^/]{1,255}/position/(\d{1,11})' =>
					array('object_id', 'position')
			),
			'date-added' => array
			(
				'(\d{4}(?:-\d{2}){0,2})/position/(\d{1,11})' =>
					array('date', 'position'),
				'(\d{4}(?:-\d{2}){0,2})/(album|category)/(\d{1,11})-[^/]{1,255}'
					. '/position/(\d{1,11})' =>
					array('date', 'section_b', 'cat_id', 'position')
			),
			'date-created' => array
			(
				'(\d{4}(?:-\d{2}){0,2})/position/(\d{1,11})' =>
					array('date', 'position'),
				'(\d{4}(?:-\d{2}){0,2})/(album|category)/(\d{1,11})-[^/]{1,255}'
					. '/position/(\d{1,11})' =>
					array('date', 'section_b', 'cat_id', 'position')
			),
			'hits' => array
			(
				'(\d{1,11})-[^/]{1,255}/position/(\d{1,11})' =>
					array('object_id', 'position')
			),
			'images' => array
			(
				'(\d{1,11})-[^/]{1,255}/position/(\d{1,11})' =>
					array('object_id', 'position')
			),
			'recent-images' => array
			(
				'(\d{1,11})-[^/]{1,255}/position/(\d{1,11})' =>
					array('object_id', 'position')
			),
			'search' => array
			(
				'([\dA-Za-z]{12})/position/(\d{1,11})' =>
					array('search', 'position')
			),
			'tag' => array
			(
				'(\d{1,11})-[^/]{1,255}/position/(\d{1,11})' =>
					array('tag_id', 'position'),
				'(\d{1,11})-[^/]{1,255}/(album|category)/(\d{1,11})-[^/]{1,255}'
					. '/position/(\d{1,11})' =>
					array('tag_id', 'section_b', 'object_id', 'position')
			),
			'user-favorites' => array
			(
				'(\d{1,11})/position/(\d{1,11})' =>
					array('user_id', 'position'),
				'(\d{1,11})/(album|category)/(\d{1,11})-[^/]{1,255}'
					. '/position/(\d{1,11})' =>
					array('user_id', 'section_b', 'object_id', 'position')
			),
			'user-images' => array
			(
				'(\d{1,11})/position/(\d{1,11})' =>
					array('user_id', 'position'),
				'(\d{1,11})/(album|category)/(\d{1,11})-[^/]{1,255}'
					. '/position/(\d{1,11})' =>
					array('user_id', 'section_b', 'object_id', 'position')
			),
			'votes' => array
			(
				'(\d{1,11})-[^/]{1,255}/position/(\d{1,11})' =>
					array('object_id', 'position')
			)
		));

		if (!isset($_GET['section']))
		{
			die;
		}
		if (!isset($_GET['object_id']))
		{
			$_GET['object_id'] = 1;
		}

		// On vérifie que la fonctionnalité correspondant
		// à la section courante est bien activée.
		switch ($_GET['section'])
		{
			case 'basket' :
				if (utils::$config['basket'] != 1
				 || utils::$config['pages_params']['basket']['status'] != 1)
				{
					die;
				}
				break;

			case 'camera-brand' :
			case 'camera-model' :
				if (!isset($_GET['cat_id']))
				{
					$_GET['cat_id'] = 1;
				}
				if (utils::$config['exif'] != 1
				 && utils::$config['pages_params']['cameras']['status'] != 1)
				{
					die;
				}
				break;

			case 'comments-stats' :
				if (utils::$config['comments'] != 1)
				{
					die;
				}
				break;

			case 'recent-images' :
				if (utils::$config['recent_images'] != 1
				 && utils::$config['widgets_params']['options']['items']['recent'] != 1)
				{
					die;
				}
				break;

			case 'search' :
				if (utils::$config['search'] != 1)
				{
					die;
				}
				search::galleryPostGet();

				break;

			case 'tag' :
				if (utils::$config['tags'] != 1)
				{
					die;
				}
				break;

			case 'user-favorites' :
				if (utils::$config['users'] != 1
				 || utils::$config['images_direct_link'] == 1)
				{
					die;
				}
				break;

			case 'user-images' :
				if (utils::$config['users'] != 1)
				{
					die;
				}
				break;

			case 'votes' :
				if (utils::$config['votes'] != 1)
				{
					die;
				}
				break;
		}
	}

	/**
	 * Détermine le nombre d'images de la section courante.
	 *
	 * @param array $sql_where
	 *	Clause WHERE + paramètres de requête préparée.
	 * @return void
	 */
	private static function _countImages($sql_where = NULL)
	{
		$sql = (is_array($sql_where))
			? $sql_where['sql']
			: sql::thumbsSQLWhere(self::$_categoryInfos, ajax::$userInfos);
		$params = (is_array($sql_where))
			? $sql_where['params']
			: array();
		$sql = 'SELECT COUNT(*)
				  FROM ' . sql::imagesSQLFrom('section') . '
				 WHERE ' . str_replace('%', '%%', $sql)
						 . ' %s';
		$result = sql::sqlProtect('image', $sql, 'value', FALSE, $params);
		if ($result === FALSE)
		{
			die(json_encode(array('status' => 'error')));
		}
		if ($result['nb_result'] === 0)
		{
			die(json_encode(array('status' => 'no_result')));
		}

		self::$_nbImages = (int) $result['query_result'];

		if ($_GET['position'] > self::$_nbImages)
		{
			$_GET['position'] = self::$_nbImages;
		}
	}

	/**
	 * Récupération des informations utiles de la catégorie courante.
	 *
	 * @return void
	 */
	private static function _getCategoryInfos()
	{
		if ($_GET['section'] == 'search'
		 || $_GET['section'] == 'basket')
		{
			return;
		}

		$cat_id = (isset($_GET['cat_id']))
			? $_GET['cat_id']
			: $_GET['object_id'];

		$sql = 'SELECT cat_id,
					   cat_path,
					   cat_name,
					   cat_url,
					   cat_filemtime,
					   cat_password,
					   cat_orderby
				  FROM ' . CONF_DB_PREF . 'categories
				 WHERE cat_id = ' . (int) $cat_id . '
				   AND %s';
		$result = sql::sqlProtect('cat', $sql, 'row');
		if ($result === FALSE)
		{
			die(json_encode(array('status' => 'error')));
		}
		if ($result['nb_result'] === 0)
		{
			die(json_encode(array('status' => 'no_result')));
		}

		self::$_categoryInfos = $result['query_result'];
	}

	/**
	 * Récupération des informations utiles de toutes les images demandées.
	 *
	 * @param array $sql_where
	 *	Clause WHERE + paramètres de requête préparée.
	 * @return void
	 */
	private static function _getAllImages($sql_where = NULL)
	{
		// Récupération de l'image actuelle et des images précédentes et suivantes.
		$sql_limit = self::_sqlLimit($_POST['preload']);
		$images = self::_getImages($sql_limit, $sql_where);

		$sql_limit = explode(',', $sql_limit);

		if (is_array($images))
		{
			$n = $sql_limit[0] + 1;
			foreach ($images as &$infos)
			{
				self::$_images[$n] = $infos;
				$n++;
			}
		}

		// Récupération des premières images, si nécessaire.
		if ($sql_limit[0] != '0')
		{
			$images = self::_getImages('0,' . $_POST['preload'], $sql_where);

			$n = 1;
			foreach ($images as &$infos)
			{
				self::$_images[$n] = $infos;
				$n++;
			}
		}

		// Récupération des dernières images, si nécessaire.
		if (($sql_limit[0] + $sql_limit[1]) < self::$_nbImages)
		{
			$limit = $_POST['preload'];
			$images = self::_getImages((self::$_nbImages - $limit) . ',' . $limit, $sql_where);

			$n = self::$_nbImages - $limit + 1;
			foreach ($images as &$infos)
			{
				self::$_images[$n] = $infos;
				$n++;
			}
		}

		// Récupération des tags.
		if (utils::$config['tags'] && count(self::$_images) > 0)
		{
			$ids_positions = array();
			foreach (self::$_images as $position => $i)
			{
				$ids_positions[$i['image_id']] = $position;
			}
			$sql = 'SELECT t.*,
						   ti.image_id
					  FROM ' . CONF_DB_PREF . 'tags AS t,
						   ' . CONF_DB_PREF . 'tags_images AS ti
					 WHERE t.tag_id = ti.tag_id
					   AND ti.image_id IN (' . implode(', ', array_keys($ids_positions)) . ')
				  ORDER BY LOWER(t.tag_name) ASC,
						   ti.image_id ASC';
			if (utils::$db->query($sql, PDO::FETCH_ASSOC) !== FALSE)
			{
				$images_tags = utils::$db->queryResult;
				foreach ($images_tags as $i)
				{
					if (!isset(self::$_images[$ids_positions[$i['image_id']]]['tags']))
					{
						self::$_images[$ids_positions[$i['image_id']]]['tags'] = array();
					}
					self::$_images[$ids_positions[$i['image_id']]]['tags'][] = $i;
				}
			}
		}

		// Si l'utilisateur n'a pas la permission pour accéder
		// aux images originales, alors on utilise le redimensionnement
		// par GD des images plutôt que pour celui pour le dipaorama.
		$images_resize_gd = utils::$config['users']
			&& !ajax::$userPerms['gallery']['perms']['image_original'];

		// On génère les autres informations utiles.
		$no_md5 = array(
			'image_filesize', 'image_hits', 'in_basket', 'in_favorites', 'nb_favorites'
		);
		foreach (self::$_images as $position => &$data)
		{
			// Position de l'image dans la section courante.
			$data['current_image'] = sprintf(__('image %s|%s'), $position, self::$_nbImages);

			// Nouvelles dimensions si rotation.
			img::rotationSize($data, TRUE);

			// Nouvelles dimensions si redimensionnement
			// de l'image à afficher dans le diaporama.
			$resize = FALSE;
			$max_width = $images_resize_gd
				? (int) utils::$config['images_resize_gd_width']
				: (int) utils::$config['diaporama_resize_gd_width'];
			$max_height = $images_resize_gd
				? (int) utils::$config['images_resize_gd_height']
				: (int) utils::$config['diaporama_resize_gd_height'];
			$data['image_width_original'] = $data['image_width'];
			$data['image_height_original'] = $data['image_height'];
			if ((utils::$config['diaporama_resize_gd'] || $images_resize_gd)
			&& ($data['image_width'] > $max_width || $data['image_height'] > $max_height))
			{
				$resize = img::imageResize($data['image_width'],
					$data['image_height'], $max_width, $max_height);
				$data['image_width'] = $resize['width'];
				$data['image_height'] = $resize['height'];
				if ($images_resize_gd)
				{
					$data['image_width_original'] = $resize['width'];
					$data['image_height_original'] = $resize['height'];
				}
			}

			// Nouveau poids si redimensionnement et/ou filigrane
			// de l'image originale.
			$im_type = FALSE;
			$watermark = watermark::getParams($data);
			if ($watermark)
			{
				$im_type = ($images_resize_gd)
					? 'im_resize_watermark'
					: 'im_watermark';
			}
			else if ($images_resize_gd)
			{
				$im_type = 'im_resize';
			}
			if ($im_type !== FALSE)
			{
				$str = (strstr($im_type, 'watermark'))
					? md5(serialize($watermark) . '|' . $data['image_adddt'])
					: $data['image_adddt'];
				$im_file = GALLERY_ROOT . '/' . img::filepath($im_type,
					$data['image_path'], $data['image_id'], $str);
				$data['image_filesize'] = (file_exists($im_file)
					&& ($filesize = filesize($im_file)) !== FALSE)
					? $filesize
					: NULL;
			}

			// Emplacement de l'image.
			$data['image_src'] = ($resize === FALSE)
				? CONF_GALLERY_PATH . '/image.php?id=' . (int) $data['image_id']
				: CONF_GALLERY_PATH . '/resize.php?id=' . (int) $data['image_id']
					. ($images_resize_gd ? '' : '&diaporama=1');
			$data['image_src'] .= '&nohits='
				. md5('nohits|' . CONF_KEY . '|' . (int) $data['image_id']);

			// Position de l'image dans la galerie (fil d'ariane).
			$data['image_position'] = self::_getParents($data, $position);

			// Nom de fichier.
			$data['image_filename'] = basename($data['image_path']);

			// Titre et description localisés.
			$data['locale'] = array('title' => array(), 'desc' => array());
			foreach (utils::$config['locale_langs'] as $code => &$name)
			{
				$data['locale']['title'][$code] = utils::getLocale($data['image_name'], $code);
				$data['locale']['desc'][$code] = utils::getLocale($data['image_desc'], $code);
			}

			// On crée une signature pour identifier l'image.
			// Ainsi, cela permettra de mettre à jour immédiatement le
			// diaporama en cas de changement de l'image ou dans la section
			// courante (ordre des images, image ajoutée ou retirée).
			// On supprime également certaines informations pour générer le md5,
			// pour éviter de recharger les images dans certaines conditions
			// où cela poserait problème.
			foreach ($no_md5 as $d)
			{
				if (array_key_exists($d, $data))
				{
					$$d = $data[$d];
					$data[$d] = 0;
				}
			}
			$data['md5'] = md5(serialize($data));
			foreach ($no_md5 as $d)
			{
				if (array_key_exists($d, $data))
				{
					$data[$d] = $$d;
				}
			}

			// Incrémentation du nombre de visites de l'image courante.
			// A placer après l'appel à _getParents() et après la fabrication du md5.
			if ($position == $_GET['position'] && utils::$config['diaporama_hits'])
			{
				alb::imageHits(ajax::$userInfos, $data);
			}

			// Informations de l'image.
			$data['infos'] = self::_getInfos($data);

			unset($data['image_desc']);

			// Sécurisation des données.
			// A placer en tout dernier.
			foreach ($data as $column => &$i)
			{
				if (in_array($column, array('image_position', 'infos', 'md5')))
				{
					continue;
				}
				if (is_array($i))
				{
					utils::htmlspecialchars($i);
				}
				else
				{
					$i = utils::tplProtect($i);
				}
			}
		}

		ksort(self::$_images);
	}

	/**
	 * Récupération des informations utiles d'images.
	 *
	 * @param string $sql_limit
	 *	Valeurs de la clause LIMIT.
	 * @param array $sql_where
	 *	Clause WHERE + paramètres de requête préparée.
	 * @return boolean|array
	 */
	private static function _getImages($sql_limit, $sql_where = NULL)
	{
		$params = (is_array($sql_where))
			? $sql_where['params']
			: array();
		$sql_where = (is_array($sql_where))
			? $sql_where['sql']
			: sql::thumbsSQLWhere(self::$_categoryInfos, ajax::$userInfos);
		$sql_from = 'LEFT JOIN ' . CONF_DB_PREF . 'users AS u
							ON u.user_id = img.user_id';

		// L'image est-elle dans les favoris de l'utilisateur ?
		$sql_select = '';
		if (utils::$config['users'])
		{
			$params['user_id'] = (int) ajax::$userInfos['user_id'];
			$sql_select .= ', (SELECT 1
						  FROM ' . CONF_DB_PREF . 'favorites
					     WHERE image_id = img_id
						   AND user_id = :user_id) AS in_favorites';
		}

		// L'image est-elle dans le panier de l'utilisateur ?
		if (utils::$config['basket'])
		{
			if (ajax::$auth)
			{
				$sql_select .= ', (SELECT 1
							  FROM ' . CONF_DB_PREF . 'basket
							 WHERE image_id = img_id
							   AND user_id = :user_id
							 LIMIT 1) AS in_basket';
			}
			else
			{
				$sql_select .= ', (SELECT 1
							  FROM ' . CONF_DB_PREF . 'basket AS b
						 LEFT JOIN ' . CONF_DB_PREF . 'sessions AS s USING (session_id)
							 WHERE image_id = img_id
							   AND session_token = "' . user::getSessionCookieToken() . '"
							 LIMIT 1) AS in_basket';
			}
		}

		// L'utilisateur a-t-il la permission d'éditer l'image ?
		$sql_select .= (ajax::$userPerms['gallery']['perms']['edit_owner'])
			? ', CASE WHEN u.user_id = ' . (int) ajax::$userInfos['user_id'] . '
					  THEN 1 ELSE 0 END AS perm_edit'
			: ', 1 AS perm_edit';

		// Nombre de favoris.
		if (utils::$config['users']
		 && utils::$config['widgets_params']['stats_images']['items']['favorites'])
		{
			$sql_select .= ', (SELECT COUNT(*)
								 FROM ' . CONF_DB_PREF . 'users AS u
						    LEFT JOIN ' . CONF_DB_PREF . 'favorites AS fav USING (user_id)
							    WHERE user_status = "1"
								  AND fav.image_id = img_id) AS nb_favorites';
		}

		// On récupère les informations utiles des images.
		$sql = 'SELECT img.image_id,
					   img.image_id AS img_id,
					   cat.cat_id,
					   cat.cat_name,
					   cat.cat_url,
					   cat.cat_watermark,
					   image_path,
					   image_filesize,
					   image_exif,
					   image_iptc,
					   image_xmp,
					   image_width,
					   image_height,
					   image_url,
					   image_lat,
					   image_long,
					   image_place,
					   image_name,
					   image_desc,
					   image_adddt,
					   image_crtdt,
					   image_hits,
					   image_comments,
					   image_votes,
					   image_rate,
					   image_rotation,
					   u.user_id,
					   u.user_login,
					   u.user_status,
					   u.user_watermark
					   ' . $sql_select . '
				  FROM ' . sql::imagesSQLFrom('section', $sql_from) . '
				 WHERE ' . str_replace('%', '%%', $sql_where) . ' %s
			  ORDER BY ' . sql::imagesSQLOrder(self::$_categoryInfos) . '
			     LIMIT ' . $sql_limit;
		$fetch_style = array('fetch' => PDO::FETCH_ASSOC, 'column' => 'image_id');
		$result = sql::sqlProtect('image', $sql, $fetch_style, FALSE, $params);
		if ($result === FALSE || $result['nb_result'] === 0)
		{
			return FALSE;
		}

		return $result['query_result'];
	}

	/**
	 * Récupération des métadonnées
	 * et formatage des statistiques et description de l'image.
	 *
	 * @param array $image_data
	 *	Informations utiles de l'image.
	 * @return boolean|array
	 */
	private static function _getInfos($image_data)
	{
		$infos = array(
			'desc' => array(),
			'stats' => array(),
			'tags' => array(),
			'exif' => array(),
			'iptc' => array(),
			'xmp' => array()
		);

		// Description.
		if (!utils::isEmpty(template::desc('image', $image_data)))
		{
			$infos['desc']['title'] = __('Description');
			$infos['desc']['content'] = template::desc('image', $image_data);
		}

		// Statistiques.
		if (utils::$config['widgets_params']['stats_images']['status']
		&& template::disImageStats())
		{
			$infos['stats']['title'] = __('Statistiques');
			$infos['stats']['items'] = array();
			if (template::disImageStats('filesize'))
			{
				$filesize = ((int) $image_data['image_filesize'] > 0)
					? template::getImageStats('filesize', $image_data)
					: '?';
				$infos['stats']['items'][] = sprintf(
					__('<span>Poids</span> : %s'), $filesize
				);
			}
			if (template::disImageStats('size'))
			{
				$infos['stats']['items'][] = sprintf(
					__('<span>Dimensions</span> : %s x %s'),
					template::getImageStats('width', $image_data),
					template::getImageStats('height', $image_data)
				);
			}
			if (template::disImageStats('hits'))
			{
				$infos['stats']['items'][] = sprintf(
					__('<span>Visitée</span> : %s fois'),
					template::getImageStats('hits', $image_data)
				);
			}
			if (template::disImageStats('favorites'))
			{
				$infos['stats']['items'][] = sprintf(
					__('<span>Mis en favori</span> : %s fois'),
					template::getImageStats('favorites', $image_data)
				);
			}
			if (template::disImageStats('comments'))
			{
				$infos['stats']['items'][] = sprintf(
					__('<span>Commentaires</span> : %s'),
					template::getImageStats('comments', $image_data)
				);
			}
			if (template::disImageStats('votes'))
			{
				$infos['stats']['items'][] = sprintf(
					__('<span>Note moyenne</span> : %s'),
					template::getImageStats('rate', $image_data)
				);
			}
			if (template::disImageStats('added_date'))
			{
				$infos['stats']['items'][] = sprintf(
					__('<span>Ajoutée le</span> : %s'),
					template::getImageStats('added_date', $image_data)
				);
			}
			if (template::disImageStats('added_by'))
			{
				$infos['stats']['items'][] = sprintf(
					__('<span>Ajoutée par</span> : %s'),
					template::getImageStats('added_by', $image_data)
				);
			}
			if (template::disImageStats('created_date'))
			{
				$infos['stats']['items'][] = sprintf(
					__('<span>Créée le</span> : %s'),
					template::getImageStats('created_date', $image_data)
				);
			}
		}

		// Tags.
		if (utils::$config['tags'] && isset($image_data['tags']))
		{
			$infos['tags']['title'] = __('Tags');
			$infos['tags']['items'] = array();
			foreach ($image_data['tags'] as $i)
			{
				$infos['tags']['items'][] = array(
					'tag_name' => utils::tplProtect($i['tag_name']),
					'tag_link' => utils::genURL('tag/' . $i['tag_id'] . '-' . $i['tag_url'])
				);
			}
		}

		// Métadonnées.
		if (utils::$config['exif'] || utils::$config['iptc'] || utils::$config['xmp'])
		{
			$metadata = new metadata(NULL, $image_data);

			// Informations Exif.
			if (utils::$config['exif'])
			{
				$metadata->getExif();

				// Informations Exif en base de données.
				if (empty($metadata->exif)
				&& $image_data['image_exif'] !== NULL)
				{
					$metadata->getExif(unserialize($image_data['image_exif']));
				}
			}

			// Informations IPTC.
			if (utils::$config['iptc'])
			{
				$metadata->getIptc();

				// Informations IPTC en base de données.
				if (empty($metadata->iptc)
				&& $image_data['image_iptc'] !== NULL)
				{
					$metadata->getIptc(unserialize($image_data['image_iptc']));
				}
			}

			// Informations XMP.
			if (utils::$config['xmp'])
			{
				$metadata->getXmp();

				// Informations XMP en base de données.
				if (empty($metadata->xmp)
				&& $image_data['image_xmp'] !== NULL)
				{
					$metadata->getXmp($image_data['image_xmp']);
				}
			}

			foreach (array('exif', 'iptc', 'xmp') as $meta)
			{
				if (empty($metadata->$meta))
				{
					continue;
				}
				$infos[$meta]['title'] = sprintf(__('Informations %s'), strtoupper($meta));
				$infos[$meta]['items'] = $metadata->$meta;
				foreach ($infos[$meta]['items'] as $k => &$i)
				{
					$i['name'] = utils::tplProtect($i['name']);
					$i['value'] = nl2br(utils::tplProtect($i['value']));

					// Liens sur marques et modèles d'appareils photos.
					if ($k == 'Make' && isset($image_data['image_camera']))
					{
						$link = utils::genURL(
							'camera-brand/' . $image_data['image_camera']['camera_brand_id']
							. '-' . $image_data['image_camera']['camera_brand_url']
						);
						$i['value'] =  '<a href="' . $link . '">' . $i['value'] . '</a>';
					}
					else if ($k == 'Model' && isset($image_data['image_camera']))
					{
						$link = utils::genURL(
							'camera-model/' . $image_data['image_camera']['camera_model_id']
							. '-' . $image_data['image_camera']['camera_model_url']
						);
						$i['value'] =  '<a href="' . $link . '">' . $i['value'] . '</a>';
					}
				}
			}
		}

		return $infos;
	}

	/**
	 * Récupère la position de l'image dans la galerie (fil d'ariane).
	 *
	 * @param string $image_infos
	 *	Informations de l'image.
	 * @param integer $position
	 *	Position de l'image.
	 * @return string
	 */
	private static function _getParents($image_infos, $position)
	{
		static $parents;

		// Pour les albums, les parents de toutes les images sont identiques,
		// donc inutile d'effectuer la même requête pour chaque image
		// s'ils ont déjà été récupérés.
		if ((self::$_categoryInfos['cat_filemtime'] !== NULL && $parents !== NULL) === FALSE)
		{
			$parent = dirname(dirname($image_infos['image_path']));
			$sql = '';
			$params = $parents = array();
			while ($parent !== '.')
			{
				$sql .= 'cat_path = ? OR ';
				$params[] = $parent;
				$parent = dirname($parent);
			}
			if ($sql !== '')
			{
				$sql = substr($sql, 0, strlen($sql) - 4);
				$sql = 'SELECT cat_id,
							   cat_name,
							   cat_url
						  FROM ' . CONF_DB_PREF . 'categories
						 WHERE ' . $sql . '
						   AND cat_status = "1"
					  ORDER BY LENGTH(cat_path) ASC';
				utils::$db->prepare($sql);
				utils::$db->executeQuery($params, PDO::FETCH_ASSOC);
				$parents = utils::$db->queryResult;
			}
			$parents[] = array(
				'cat_id' => $image_infos['cat_id'],
				'cat_name' => $image_infos['cat_name'],
				'cat_url' => $image_infos['cat_url']
			);
		}

		if ($position == $_GET['position'])
		{
			self::$_currentImageParents = $parents;
		}

		return template::getPosition(
			'',
			'category',
			'image',
			'album',
			FALSE,
			TRUE,
			__('Accueil'),
			$parents,
			$image_infos,
			1,
			utils::$config['level_separator'],
			true
		);
	}

	/**
	 * Envoi au format JSON toutes les informations utiles des images.
	 *
	 * @return void
	 */
	private static function _JSON()
	{
		// Paramètre "max_user_connections".
		$max_user_connections = 1;
		if (isset($_POST['first']) && $_POST['first'])
		{
			$sql = 'SHOW VARIABLES LIKE "max_user_connections"';
			$fetch_style = array('column' => array('Variable_name', 'Value'));
			if (utils::$db->query($sql, $fetch_style) !== FALSE)
			{
				$max_user_connections = utils::$db->queryResult['max_user_connections'];
			}
		}

		if (is_object(utils::$cookieSession))
		{
			utils::$cookieSession->write();
		}
		if (is_object(utils::$cookiePrefs))
		{
			utils::$cookiePrefs->write();
		}

		// Fermeture de la connexion à la base de données.
		utils::$db->connexion = NULL;

		die(json_encode(array(
			'anticsrf' => utils::tplProtect(utils::$anticsrfToken),
			'images' => self::$_images,
			'max_user_connections' => $max_user_connections,
			'nb_images' => self::$_nbImages
		)));
	}

	/**
	 * Clause LIMIT pour la récupération des images actuelle, précédentes et suivantes.
	 *
	 * @param integer $max_images
	 * @return void
	 */
	private static function _sqlLimit($max_images)
	{
		$start = $_GET['position'] - 1 - $max_images;
		$nb_images = ($max_images * 2) + 1;

		if ($start < 0)
		{
			$nb_images -= -$start;
			$start = 0;
		}

		return $start . ',' . $nb_images;
	}
}
?>