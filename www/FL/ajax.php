<?php
/**
 * Traitements des requêtes Ajax.
 *
 * @license http://www.gnu.org/licenses/gpl.html
 * @link http://www.igalerie.org/
 */
require_once(dirname(__FILE__) . '/includes/prepend.php');

header('Content-Type: application/javascript; charset=UTF-8');

if (!isset($_POST['section']))
{
	die;
}

switch ($_POST['section'])
{
	case 'basket-add' :
	case 'basket-remove' :
		ajax::init(FALSE, FALSE, TRUE);
		ajax::basket(substr($_POST['section'], 7));
		break;

	case 'carousel' :
		ajax::init(FALSE, FALSE, FALSE);
		if (!utils::$config['diaporama'])
		{
			die;
		}
		diaporama::getCarousel();
		break;

	case 'delete-image' :
		ajax::init(FALSE, TRUE, TRUE);
		ajax::deleteImage();
		break;

	case 'diaporama' :
		ajax::init(FALSE, FALSE, FALSE);
		if (!utils::$config['diaporama'])
		{
			die;
		}
		diaporama::getDiaporama();
		break;

	case 'edit-category' :
		ajax::init(FALSE, TRUE, TRUE);
		ajax::editCategory();
		break;

	case 'edit-image' :
		ajax::init(FALSE, TRUE, TRUE);
		ajax::editImage();
		break;

	case 'favorites-add' :
	case 'favorites-remove' :
		ajax::init(FALSE, TRUE, TRUE);
		ajax::favorites(substr($_POST['section'], 10));
		break;

	case 'langs-edition' :
		ajax::init(TRUE, FALSE, FALSE);
		ajax::langsEdition();
		break;

	case 'mass-edit' :
		ajax::init(TRUE, TRUE, FALSE);
		ajax::massEdit();
		break;

	case 'prefs' :
		ajax::cookiePrefs();
		break;

	case 'rate' :
		ajax::init(FALSE, FALSE, TRUE);
		ajax::rate();
		break;

	case 'tags-add' :
	case 'tags-remove' :
		ajax::init(FALSE, TRUE, TRUE);
		ajax::tags(substr($_POST['section'], 5));
		break;
}

// Fermeture de la connexion à la bdd.
if (is_object(utils::$db))
{
	utils::$db->connexion = NULL;
}



/**
 * Traitements pour chaque section.
 */
class ajax
{
	/**
	 * Indique si l'utilisateur est authentifié.
	 *
	 * @var boolean
	 */
	public static $auth = FALSE;

	/**
	 * Informations de l'utilisateur.
	 *
	 * @var array
	 */
	public static $userInfos;

	/**
	 * Permissions pour l'utilisateur.
	 *
	 * @var array
	 */
	public static $userPerms;

	/**
	 * Préférences utilisateur.
	 *
	 * @var array
	 */
	public static $userPrefs;



	/**
	 * Ajoute des images au panier.
	 *
	 * @param string $action
	 *	Action à effectuer : 'add' ou 'remove'.
	 * @return void
	 */
	public static function basket($action)
	{
		// Quelques vérifications.
		if (!self::_q() || !utils::$config['basket'] || !isset($_POST['images_id'])
		|| !preg_match('`^\d{1,11}(,\d{1,11}){0,999}$`', $_POST['images_id']))
		{
			self::_forbidden(__LINE__);
		}

		// On récupère les informations utiles des images.
		$sql = 'SELECT image_id,
					   image_filesize
				  FROM ' . CONF_DB_PREF . 'images
				  JOIN ' . CONF_DB_PREF . 'categories USING (cat_id)
				 WHERE %s
				   AND image_id IN (' . $_POST['images_id'] . ')';
		$fetch_style = array('column' => array('image_id', 'image_filesize'));
		if (($result = sql::sqlProtect('image', $sql, $fetch_style)) === FALSE)
		{
			die;
		}
		if ($result['nb_result'] === 0)
		{
			self::_forbidden(__LINE__);
		}
		$images = $result['query_result'];

		// Identifiant des images.
		$images_id = implode(', ', array_keys($images));

		$user_id = 2;

		// Utilisateur authentifié.
		if (self::$auth)
		{
			$user_id = (int) self::$userInfos['user_id'];

			// On ajoute les images au panier.
			if ($action == 'add')
			{
				// Vérification des limites du panier.
				self::_basketCapacity($images);

				$sql_values = array();
				foreach ($images as $image_id => &$image_filesize)
				{
					$sql_values[] = '(' . $user_id . ', ' . (int) $image_id . ', NOW())';
				}
				$sql = 'INSERT IGNORE INTO ' . CONF_DB_PREF . 'basket
							(user_id, image_id, basket_date)
							VALUES ' . implode(',', $sql_values);
			}

			// On retire les images du panier.
			if ($action == 'remove')
			{
				$sql = 'DELETE
						  FROM ' . CONF_DB_PREF . 'basket
						 WHERE user_id = ' . $user_id . '
						   AND image_id IN (' . $images_id . ')';
			}
		}

		// Utilisateur non authentifié.
		else
		{
			$session = user::getSession();

			// Enregistrement de l'identifiant de session
			// dans le cookie de l'utilisateur.
			utils::$cookieSession->delete();
			utils::$cookieSession->add('token', $session['session_token']);
			utils::$cookieSession->write();

			// On ajoute les images au panier.
			if ($action == 'add')
			{
				// Vérification des limites du panier.
				self::_basketCapacity($images, $session);

				$sql_values = array();
				foreach ($images as $image_id => &$image_filesize)
				{
					$sql_values[] = '(' . $user_id . ', ' .  $session['session_id']
						. ', ' . (int) $image_id . ', NOW())';
				}
				$sql = 'INSERT IGNORE INTO ' . CONF_DB_PREF . 'basket
							(user_id, session_id, image_id, basket_date)
							VALUES ' . implode(',', $sql_values);
			}

			// On retire les images du panier.
			if ($action == 'remove')
			{
				$sql = 'DELETE 
						  FROM ' . CONF_DB_PREF . 'basket
						 WHERE user_id = ' . $user_id . '
						   AND session_id = ' .  $session['session_id'] . '
						   AND image_id IN (' . $images_id . ')';
			}
		}

		// Exécution de la requête.
		if (utils::$db->exec($sql) === FALSE)
		{
			die;
		}

		// Log d'activité.
		sql::logUserActivity('basket_' . $action, $user_id, NULL,
			array('images_id' => $images_id));

		// Opération réussie.
		die(json_encode(array(
			'status' => 'success',
			'msg' => ($action == 'add')
				? __('Les images ont été ajoutées à votre panier.')
				: __('Les images ont été retirées de votre panier.')
		)));
	}



	/**
	 * Ajout d'une information au cookie des préférences.
	 *
	 * @return void
	 */
	public static function cookiePrefs()
	{
		if (!isset($_POST['cookie_param'])
		 || !isset($_POST['cookie_value'])
		 || !preg_match('`^[-a-z0-9_]{1,20}$`', $_POST['cookie_param'])
		 || !preg_match('`^[-a-z0-9_,\.]{1,100}$`i', $_POST['cookie_value']))
		{
			return;
		}

		utils::$cookiePrefs = new cookie('igal_prefs', 315360000, CONF_GALLERY_PATH);
		utils::$cookiePrefs->add($_POST['cookie_param'], $_POST['cookie_value']);
		utils::$cookiePrefs->write();
	}

	/**
	 * Supprime une image.
	 *
	 * @return void
	 */
	public static function deleteImage()
	{
		// Quelques vérifications.
		if (!self::_q() || utils::$config['users'] != 1 || !isset($_POST['image_id'])
		|| !preg_match('`^\d{1,11}$`', $_POST['image_id']))
		{
			self::_forbidden(__LINE__);
		}

		$user_id = (int) self::$userInfos['user_id'];

		// On ne récupère les identifiants que des images
		// dont l'utilisateur a la permission de supprimer.
		$sql_where = ($user_id > 1)
			? ' AND img.user_id = ' . $user_id
			: '';
		$sql = 'SELECT image_id,
					   cat.cat_id,
					   image_path,
					   image_filesize,
					   image_name,
					   image_hits,
					   image_comments,
					   image_votes,
					   image_rate,
					   image_status
				  FROM ' . CONF_DB_PREF . 'images AS img
				  JOIN ' . CONF_DB_PREF . 'categories AS cat USING (cat_id)
				 WHERE image_id = ' . (int) $_POST['image_id'] . '
				   AND image_status = "1"'
					 . $sql_where
					 . sql::$groupAlbumsAccess;
		$fetch_style = array('fetch' => PDO::FETCH_ASSOC, 'column' => 'image_id');
		if (utils::$db->query($sql, $fetch_style) === FALSE)
		{
			die;
		}
		if (utils::$db->nbResult == 0)
		{
			self::_forbidden(__LINE__);
		}
		$image_infos = utils::$db->queryResult;

		// Suppression des images et mise à jour de la base de données.
		$report = alb::deleteImages($image_infos[$_POST['image_id']]['cat_id'], $image_infos);

		// Rapport.
		foreach ($report as &$msg)
		{
			if (is_array($msg))
			{
				trigger_error('Unable to delete file : ' . $msg[1], E_USER_WARNING);
				continue;
			}

			if ($msg[0] == 's')
			{
				// Log d'activité.
				sql::logUserActivity('image_delete', $user_id,
					NULL, array('image_id' => (int) $_POST['image_id']));

				die(json_encode(array(
					'status' => 'success',
					'msg' => __('L\'image a été supprimée.')
				)));
			}
			else
			{
				die(json_encode(array(
					'status' => 'error',
					'msg' => __('Une erreur s\'est produite : impossible de supprimer l\'image.')
				)));
			}
		}
	}

	/**
	 * Édition des catégories.
	 *
	 * @return void
	 */
	public static function editCategory()
	{
		self::_decodeData();

		// Quelques vérifications.
		if (!self::_q() || !isset($_POST['id']) || !preg_match('`^\d{1,11}$`', $_POST['id'])
		|| utils::$config['users'] != 1 || !self::$userPerms['gallery']['perms']['edit'])
		{
			self::_forbidden(__LINE__);
		}

		// Catégorie 1.
		if ($_POST['id'] == 1)
		{
			self::_editGalleryDescription();
		}

		$columns = array();
		$params = array();

		// Récupération des informations utiles de la catégorie.
		$sql = 'SELECT user_id,
					   cat_id,
					   cat_name,
					   cat_url,
					   cat_desc,
					   cat_place,
					   cat_filemtime
				  FROM '. CONF_DB_PREF . 'categories
				 WHERE cat_status = "1"
				   AND cat_id = ' . (int) $_POST['id']
					 . sql::$groupAlbumsAccess;
		if (utils::$db->query($sql, 'row') === FALSE)
		{
			die;
		}
		if (utils::$db->nbResult === 0)
		{
			self::_forbidden(__LINE__);
		}
		$infos = utils::$db->queryResult;

		// L'utilisateur n'a-t-il la permission d'éditer
		// que les catégories dont il est propriétaire ?
		if (self::$userPerms['gallery']['perms']['edit_owner'] &&
		$infos['user_id'] != self::$userInfos['user_id'])
		{
			self::_forbidden(__LINE__);
		}
		
		// Titre.
		if (isset($_POST['title'])
		&& ($new_title = self::_editTitle($infos, 'cat_name')) !== FALSE)
		{
			$columns[] = 'cat_name = :cat_name';
			$params['cat_name'] = $new_title;
		}

		// Nom d'URL.
		if (isset($_POST['urlname'])
		&& ($new_urlname = self::_editURLName($infos, 'cat_url')) !== FALSE)
		{
			$columns[] = 'cat_url = :cat_url';
			$params['cat_url'] = $new_urlname;
		}

		// Description.
		if (isset($_POST['desc'])
		&& ($new_description = self::_editDesc($infos, 'cat_desc')) !== FALSE)
		{
			$columns[] = 'cat_desc = :cat_desc';
			$params['cat_desc'] = $new_description;
		}

		if (empty($params))
		{
			die(json_encode(array('status' => 'nochange')));
		}

		// On effectue la mise à jour de la catégorie.
		$sql = 'UPDATE ' . CONF_DB_PREF . 'categories
				   SET ' . implode(', ', $columns) . '
				 WHERE cat_id = ' . (int) $_POST['id'];
		if (utils::$db->prepare($sql) === FALSE
		|| utils::$db->executeExec($params) === FALSE)
		{
			die;
		}

		// Log d'activité.
		sql::logUserActivity('category_edit', self::$userInfos['user_id'],
			NULL, array('image_id' => (int) $_POST['id']));

		self::_editJson($infos);
	}

	/**
	 * Édition des images.
	 *
	 * @return void
	 */
	public static function editImage()
	{
		self::_decodeData();

		// Quelques vérifications.
		if (!self::_q() || !isset($_POST['id']) || !preg_match('`^\d{1,11}$`', $_POST['id'])
		|| utils::$config['users'] != 1 || !self::$userPerms['gallery']['perms']['edit']
		|| utils::$config['images_direct_link'])
		{
			self::_forbidden(__LINE__);
		}

		$columns = array();
		$params = array();

		// Récupération des informations utiles de l'image.
		$sql = 'SELECT i.user_id,
					   image_id,
					   image_name,
					   image_url,
					   image_desc,
					   image_width,
					   image_height,
					   image_place
				  FROM ' . CONF_DB_PREF . 'images AS i
				  JOIN ' . CONF_DB_PREF . 'categories USING (cat_id)
				 WHERE image_status = "1"
				   AND image_id = ' . (int) $_POST['id']
					 . sql::$groupAlbumsAccess;
		if (utils::$db->query($sql, 'row') === FALSE)
		{
			die;
		}
		if (utils::$db->nbResult === 0)
		{
			self::_forbidden(__LINE__);
		}
		$infos = utils::$db->queryResult;

		// L'utilisateur n'a-t-il la permission d'éditer
		// que les images dont il est propriétaire ?
		if (self::$userPerms['gallery']['perms']['edit_owner'] &&
		$infos['user_id'] != self::$userInfos['user_id'])
		{
			self::_forbidden(__LINE__);
		}

		// Tags.
		$update_tags = self::_editTags();

		// Titre.
		if (isset($_POST['title'])
		&& ($new_title = self::_editTitle($infos, 'image_name')) !== FALSE)
		{
			$columns[] = 'image_name = :image_name';
			$params['image_name'] = $new_title;
		}

		// Nom d'URL.
		if (isset($_POST['urlname'])
		&& ($new_urlname = self::_editURLName($infos, 'image_url')) !== FALSE)
		{
			$columns[] = 'image_url = :image_url';
			$params['image_url'] = $new_urlname;
		}

		// Description.
		if (isset($_POST['desc'])
		&& ($new_description = self::_editDesc($infos, 'image_desc')) !== FALSE)
		{
			$columns[] = 'image_desc = :image_desc';
			$params['image_desc'] = $new_description;
		}

		// On effectue la mise à jour de l'image.
		if (!empty($params))
		{
			$sql = 'UPDATE ' . CONF_DB_PREF . 'images
					   SET ' . implode(', ', $columns) . '
					 WHERE image_id = ' . (int) $_POST['id'];
			if (utils::$db->prepare($sql) === FALSE
			|| utils::$db->executeExec($params) === FALSE)
			{
				die;
			}
		}

		// Si aucune mise à jour.
		if (!$update_tags && empty($params))
		{
			die(json_encode(array('status' => 'nochange')));
		}

		// Log d'activité.
		sql::logUserActivity('image_edit', self::$userInfos['user_id'],
			NULL, array('image_id' => (int) $_POST['id']));

		self::_editJson($infos);
	}

	/**
	 * Ajoute ou retire des images dans les favoris d'un utilisateur.
	 *
	 * @param string $action
	 *	Action à effectuer : 'add' ou 'remove'.
	 * @return void
	 */
	public static function favorites($action)
	{
		// Quelques vérifications.
		if (!self::_q() || !utils::$config['users'] || !isset($_POST['images_id'])
		|| !preg_match('`^\d{1,11}(,\d{1,11}){0,999}$`', $_POST['images_id']))
		{
			self::_forbidden(__LINE__);
		}

		$user_id = (int) self::$userInfos['user_id'];

		// On récupère uniquement les identifiants des images
		// dont l'utilisateur a la permission d'accès.
		$sql = 'SELECT image_id
				  FROM ' . CONF_DB_PREF . 'images AS img
				  JOIN ' . CONF_DB_PREF . 'categories AS cat USING (cat_id)
				 WHERE image_id IN (' . $_POST['images_id'] . ')
				   AND image_status = "1"'
					 . sql::$groupAlbumsAccess;
		$fetch_style = array('column' => array('image_id', 'image_id'));
		if (utils::$db->query($sql, $fetch_style) === FALSE)
		{
			die;
		}
		if (utils::$db->nbResult == 0)
		{
			self::_forbidden(__LINE__);
		}
		$images = utils::$db->queryResult;

		// Identifiant des images.
		$images_id = implode(', ', $images);

		// On ajoute ou retire les images dans les favoris de l'utilisateur.
		if ($action == 'add')
		{
			$sql_values = array();
			foreach ($images as &$image_id)
			{
				$sql_values[] = '(' . $user_id . ', ' . (int) $image_id . ', NOW())';
			}
			$sql = 'INSERT IGNORE INTO ' . CONF_DB_PREF . 'favorites
						(user_id, image_id, fav_date)
						VALUES ' . implode(',', $sql_values);
		}
		if ($action == 'remove')
		{
			$sql = 'DELETE
					  FROM ' . CONF_DB_PREF . 'favorites
					 WHERE user_id = ' . $user_id . '
					   AND image_id IN (' . $images_id . ')';
		}

		// Exécution de la requête.
		if (utils::$db->exec($sql) === FALSE)
		{
			die;
		}

		// Log d'activité.
		sql::logUserActivity('favorites_' . $action, $user_id, NULL,
			array('images_id' => $images_id));

		die(json_encode(array(
			'status' => 'success',
			'msg' => ($action == 'add')
				? __('Les images ont été ajoutées à vos favoris.')
				: __('Les images ont été retirées de vos favoris.')
		)));
	}

	/**
	 * Initialisation.
	 *
	 * @param boolean $admin
	 *	La requête ajax s'opère-t-elle depuis l'admin ?
	 * @param boolean $user_must_register
	 *	L'utilisateur doit-il être enregistré ?
	 * @param boolean $csrf_token
	 *	Doit-on vérifier le jeton anti-CSRF ?
	 * @return void
	 */
	public static function init($admin, $user_must_register, $csrf_token)
	{
		// Cookies.
		utils::$cookiePrefs = new cookie('igal_prefs', 315360000, CONF_GALLERY_PATH);
		utils::$cookieSession = new cookie('igal_session', 8640000, CONF_GALLERY_PATH);

		// Récupération de l'identifiant de session que possède l'utilisateur.
		if (($session_token = user::getSessionCookieToken()) === FALSE)
		{
			if ($user_must_register)
			{
				die(json_encode(array(
					'status' => 'error',
					'msg' => 'Session expired.'
				)));
			}
			$session_token = FALSE;
		}

		// Vérification du jeton anti-CSRF.
		if ($csrf_token && $user_must_register
		&& !utils::antiCSRFTokenCheck(utils::$cookiePrefs))
		{
			if ($user_must_register)
			{
				die(json_encode(array(
					'status' => 'error',
					'msg' => 'Invalid token.'
				)));
			}
			$session_token = FALSE;
		}

		// Connexion à la base de données.
		utils::$db = new db();
		if (utils::$db->connexion === NULL)
		{
			die(json_encode(array(
				'status' => 'error',
				'msg' => 'Unable to connect to the database.'
			)));
		}

		// Récupération de la configuration de la galerie.
		utils::getConfig();

		// Galerie fermée ?
		if (!$admin && utils::$config['gallery_closure'])
		{
			self::_forbidden(__LINE__);
		}

		// Préférences utilisateurs : style.
		if (utils::$config['widgets_params']['options']['status']
		&& utils::$config['widgets_params']['options']['items']['styles']
		&& in_array(utils::$cookiePrefs->read('css'), utils::getStyles()))
		{
			utils::$config['theme_style'] = utils::$cookiePrefs->read('css');
		}

		// Préférences utilisateurs : nombre de jours des images récentes.
		if (utils::$config['widgets_params']['options']['status']
		&& utils::$config['widgets_params']['options']['items']['recent']
		&& preg_match('`^(?:[1-9]|\d{2,3})$`', utils::$cookiePrefs->read('rd')))
		{
			utils::$config['recent_images_time'] = utils::$cookiePrefs->read('rd');
		}

		self::_auth($admin, $session_token);

		// Désactivation de certaines fonctionnalités quand la permission
		// d'accès à l'image originale est refusée.
		if (utils::$config['users'] && !self::$userPerms['gallery']['perms']['image_original'])
		{
			utils::$config['basket'] = 0;
			utils::$config['diaporama_resize_gd'] = 0;
			utils::$config['download_zip_albums'] = 0;
			utils::$config['images_resize_method'] = 2;
			utils::$config['thumbs_stats_filesize'] = 0;
			utils::$config['widgets_params']['options']['items']['thumbs_filesize'] = 0;
			utils::$config['widgets_params']['stats_categories']['items']['filesize'] = 0;
			utils::$config['thumbs_stats_size'] = 0;
			utils::$config['widgets_params']['options']['items']['thumbs_size'] = 0;
		}
	}

	/**
	 * Langues d'édition.
	 *
	 * @return void
	 */
	public static function langsEdition()
	{
		if (!isset($_POST['langs']))
		{
			self::_forbidden(__LINE__);
		}

		$regex = '`^[a-z]{2}_[A-Z]{2}(?:,[a-z]{2}_[A-Z]{2}){0,20}$`';
		$langs = (preg_match($regex, $_POST['langs']))
			? $_POST['langs']
			: CONF_DEFAULT_LANG;

		// On met à jour les préférences de l'utilisateur.
		self::$userPrefs['langs_edition'] = $langs;
		$sql = 'UPDATE ' . CONF_DB_PREF . 'users
				   SET user_prefs = :user_prefs
				 WHERE user_id = ' . (int) self::$userInfos['user_id'];
		$params = array(
			'user_prefs' => serialize(self::$userPrefs)
		);
		utils::$db->prepare($sql);
		utils::$db->executeExec($params);
	}

	/**
	 * Récupération des images pour l'édition en masse
	 * de la partie d'administration.
	 *
	 * @return void
	 */
	public static function massEdit()
	{
		if (!isset($_POST['q'])
		|| !isset($_POST['nb_images']) || !preg_match('`^\d{1,4}$`', $_POST['nb_images'])
		|| !isset($_POST['position']) || !preg_match('`^\d{1,11}$`', $_POST['position'])
		|| !isset($_POST['orderby']) || !isset($_POST['sortby'])
		|| !in_array($_POST['orderby'], array('ASC', 'DESC'))
		|| !in_array($_POST['sortby'], array('adddt', 'name', 'path', 'position')))
		{
			self::_forbidden(__LINE__);
		}

		// Extraction de la requête.
		$_GET['q'] = $_POST['q'];
		extract_request(array(
			'mass-edit-album' => array
			(
				'(\d{1,11})' =>
					array('object_id'),

				// search
				'(\d{1,11})/search/([\dA-Za-z]{12})' =>
					array('object_id', 'search'),

				// camera-brands, camera-models
				'(\d{1,11})/(camera-(?:brand|model))/(\d{1,11})' =>
					array('object_id', 'filter', 'cam_id'),

				// tag
				'(\d{1,11})/(tag)/(\d{1,11})' =>
					array('object_id', 'filter', 'tag_id'),

				// user-basket, user-favorites, user-images
				'(\d{1,11})/(user-(?:basket|favorites|images))/(\d{1,11})' =>
					array('object_id', 'filter', 'user_id')
			),
			'mass-edit-category' => array
			(
				'(\d{1,11})' =>
					array('object_id'),

				// search
				'(\d{1,11})/search/([\dA-Za-z]{12})' =>
					array('object_id', 'search'),

				// camera-brands, camera-models
				'(\d{1,11})/(camera-(?:brand|model))/(\d{1,11})' =>
					array('object_id', 'filter', 'cam_id'),

				// tag
				'(\d{1,11})/(tag)/(\d{1,11})' =>
					array('object_id', 'filter', 'tag_id'),

				// user-basket, user-favorites, user-images
				'(\d{1,11})/(user-(?:basket|favorites|images))/(\d{1,11})' =>
					array('object_id', 'filter', 'user_id')
			)
		));
		if (empty($_GET))
		{
			die;
		}

		$nb_images = $_POST['nb_images'];
		$thumb_size = 100;

		// Clauses FROM et WHERE.
		$sql_from = '';
		$sql_where = '';

		// Filtres.
		if (isset($_GET['filter']))
		{
			switch ($_GET['filter'])
			{
				case 'camera-brand' :
					$sql_from = ' LEFT JOIN ' . CONF_DB_PREF . 'cameras_models_images AS cam_i
										 ON img.image_id = cam_i.image_id
								  LEFT JOIN ' . CONF_DB_PREF . 'cameras_models AS cam_m
										 ON cam_i.camera_model_id = cam_m.camera_model_id
								  LEFT JOIN ' . CONF_DB_PREF . 'cameras_brands AS cam_b
										 ON cam_m.camera_brand_id = cam_b.camera_brand_id';
					$sql_where = ' AND cam_b.camera_brand_id = ' . (int) $_GET['cam_id'];
					break;

				case 'camera-model' :
					$sql_from = ' LEFT JOIN ' . CONF_DB_PREF . 'cameras_models_images AS cam_i
										 ON img.image_id = cam_i.image_id';
					$sql_where = ' AND cam_i.camera_model_id = ' . (int) $_GET['cam_id'];
					break;

				case 'tag' :
					$sql_from = ' LEFT JOIN ' . CONF_DB_PREF . 'tags_images AS ti
										 ON img.image_id = ti.image_id';
					$sql_where = ' AND ti.tag_id = ' . (int) $_GET['tag_id'];
					break;

				case 'user-basket' :
					$sql_from = ' LEFT JOIN ' . CONF_DB_PREF . 'basket AS basket
										 ON img.image_id = basket.image_id';
					$sql_where = ' AND basket.user_id != 2
								   AND basket.user_id = ' . (int) $_GET['user_id'];
					break;

				case 'user-favorites' :
					$sql_from = ' LEFT JOIN ' . CONF_DB_PREF . 'favorites AS fav
										 ON img.image_id = fav.image_id';
					$sql_where = ' AND fav.user_id != 2
							       AND fav.user_id = ' . (int) $_GET['user_id'];
					break;

				case 'user-images' :
					$sql_where = ' AND img.user_id != 2
								   AND img.user_id = ' . (int) $_GET['user_id'];
					break;
			}
		}

		// Recherche.
		$params_search = array();
		if (isset($_GET['search']))
		{
			// Récupération de la requête par son identifiant.
			$sql = 'SELECT search_query,
						   search_options
					  FROM ' . CONF_DB_PREF . 'search
					 WHERE search_id = :search_id';
			$params = array('search_id' => $_GET['search']);
			if (utils::$db->prepare($sql) === FALSE
			|| utils::$db->executeQuery($params, 'row') === FALSE)
			{
				die;
			}
			if (utils::$db->nbResult === 0)
			{
				self::_forbidden(__LINE__);
			}
			$search = utils::$db->queryResult;
			$search_options = unserialize($search['search_options']);

			// Requête.
			$_GET['search_query'] = $search['search_query'];

			// Options.
			if (isset($search_options['type']) && $search_options['type'] != 'album')
			{
				self::_forbidden(__LINE__);
			}
			$options = search::$searchAdminImageOptions;
			foreach ($search_options as $o => $v)
			{
				if (isset($options[$o]))
				{
					if ($options[$o] == 'bin')
					{
						$_GET['search_' . $o] = 1;
					}
					else
					{
						if (preg_match('`^' . $options[$o] . '$`', $v))
						{
							$_GET['search_' . $o] = $v;
						}
					}
				}
			}

			// SQL.
			$r = search::getAdminImagesSQLWhere(
				search::$searchAdminImageFields,
				sql::$groupAlbumsAccess
			);
			if (!$r)
			{
				die;
			}
			$sql_where = ' AND ' . $r['sql'];
			$params_search = $r['params'];
		}

		// Récupération du chemin de la catégorie.
		$cat_path = '';
		if ($_GET['object_id'] > 1)
		{
			$sql = 'SELECT cat_path
					  FROM ' . CONF_DB_PREF . 'categories AS cat
					 WHERE cat_id = ' . (int) $_GET['object_id']
						 . sql::albumsAccess(self::$userPerms['admin']);
			if (utils::$db->query($sql, 'value') === FALSE
			|| utils::$db->nbResult === 0)
			{
				die;
			}
			$cat_path = utils::$db->queryResult . '/';
		}
		$params = array_merge($params_search, array('cat_path' => $cat_path));

		// Clause ORDER BY.
		$order_by = 'LOWER(image_' . $_POST['sortby'] . ') '
			. $_POST['orderby'] . ', image_id DESC';

		// Récupération des identifiants de toutes les images de la catégorie.
		$sql = 'SELECT img.image_id
				  FROM ' . CONF_DB_PREF . 'images AS img
					   ' . $sql_from . '
			 LEFT JOIN ' . CONF_DB_PREF . 'categories AS cat
					ON img.cat_id = cat.cat_id
				 WHERE image_path LIKE CONCAT(:cat_path, "%") '
					 . $sql_where
					 . sql::albumsAccess(self::$userPerms['admin']) . '
			  ORDER BY ' . $order_by;
		$fetch_style = array('column' => array('image_id', 'image_id'));
		if (utils::$db->prepare($sql) === FALSE
		|| utils::$db->executeQuery($params, $fetch_style) === FALSE)
		{
			die;
		}
		if (utils::$db->nbResult === 0)
		{
			die(json_encode(array(
				'status' => 'success',
				'images' => array(),
				'images_cat_id' => array(),
				'nb_images' => 0
			)));
		}
		$images_cat_id = array_values(utils::$db->queryResult);

		// Clause LIMIT.
		$start = $_POST['position'] - 1 - $nb_images;
		$nb_images = ($nb_images * 2) + 1;
		if ($start < 0)
		{
			$nb_images -= -$start;
			$start = 0;
		}
		$sql_limit = $start . ',' . $nb_images;

		// Récupération des images de la catégorie.
		$sql = 'SELECT img.image_id,
					   image_adddt,
					   image_width,
					   image_height,
					   image_name,
					   image_path,
					   image_url,
					   image_tb_infos AS tb_infos
				  FROM ' . CONF_DB_PREF . 'images AS img
					   ' . $sql_from . '
			 LEFT JOIN ' . CONF_DB_PREF . 'categories AS cat
					ON img.cat_id = cat.cat_id
				 WHERE image_path LIKE CONCAT(:cat_path, "%") '
					 . $sql_where
				     . sql::albumsAccess(self::$userPerms['admin']) . '
			  ORDER BY ' . $order_by . '
				 LIMIT ' . $sql_limit;
		$fetch_style = array('fetch' => PDO::FETCH_ASSOC, 'column' => 'image_id');
		if (utils::$db->prepare($sql) === FALSE
		|| utils::$db->executeQuery($params, $fetch_style) === FALSE)
		{
			die;
		}

		// On génère le code HTML des vignettes.
		$images = array();
		$position = $start + 1;
		foreach (utils::$db->queryResult as &$i)
		{
			$i['image_id'] = (int) $i['image_id'];
			$i['image_name'] = utils::tplProtect($i['image_name']);
			$i['image_url'] = utils::tplProtect($i['image_url']);

			// Dimensions et centrage de la vignette.
			$tb = img::getThumbSize($i, 'img', $thumb_size);
			$i['thumb_width'] = $tb['width'];
			$i['thumb_height'] = $tb['height'];
			$i['thumb_center'] = img::thumbCenter('img',
				$tb['width'], $tb['height'], $thumb_size);

			// Emplacement de la vignette.
			$i['thumb_src'] = template::getThumbSrc('img', $i);

			$html =
				'<dl id="i_' . $i['image_id'] . '" class="selectable_class selectable_zone">
					<dt style="width:' . $thumb_size . 'px">
						<input class="selectable" type="checkbox" />
						<span style="width:' . $thumb_size . 'px;height:' . $thumb_size . 'px;">
							<img width="' . $i['thumb_width'] . '"
								 height="' . $i['thumb_height'] . '"
								 style="padding:' . $i['thumb_center'] . ';"
								 alt="' . $i['image_name'] . '"
								 src="' . $i['thumb_src'] . '" />
						</span>
					</dt>
				</dl>';

			$images[$position] = $html;
			unset($i);

			// Position.
			$position++;
		}

		die(json_encode(array(
			'status' => 'success',
			'images' => $images,
			'images_cat_id' => $images_cat_id,
			'nb_images' => count($images_cat_id)
		)));
	}

	/**
	 * Vote.
	 *
	 * @return void
	 */
	public static function rate()
	{
		// Quelques vérifications.
		if (!self::_q()
		|| (utils::$config['users'] == 1
		 && !self::$userPerms['gallery']['perms']['votes'])
		|| utils::$config['images_direct_link']
		|| !utils::$config['votes']
		|| !isset($_POST['id']) || !preg_match('`^\d{1,11}$`', $_POST['id'])
		|| !isset($_POST['rate']) || !preg_match('`^[1-5]$`', $_POST['rate']))
		{
			self::_forbidden(__LINE__);
		}

		// Début de la transaction.
		if (!utils::$db->transaction())
		{
			die;
		}

		// Récupération des informations utiles de l'image.
		$sql = 'SELECT cat.cat_id,
					   cat_votable,
					   cat_path,
					   image_id,
					   image_votes,
					   image_rate
				  FROM ' . CONF_DB_PREF . 'images AS img
			 LEFT JOIN ' . CONF_DB_PREF . 'categories AS cat USING (cat_id)
				 WHERE %s
				   AND image_id = ' . (int) $_POST['id'];
		if (($result = sql::sqlProtect('image', $sql, 'row')) === FALSE)
		{
			die;
		}
		if ($result['nb_result'] === 0)
		{
			self::_forbidden(__LINE__);
		}
		$image_infos = $result['query_result'];

		// L'image doit être votable.
		if ($image_infos['cat_votable'] != 1)
		{
			self::_forbidden(__LINE__);
		}
		$parent = dirname($image_infos['cat_path']);
		$sql = '';
		$params = array();
		while ($parent !== '.')
		{
			$sql .= 'cat_path = ? OR ';
			$params[] = $parent;
			$parent = dirname($parent);
		}
		if ($sql !== '')
		{
			$sql = substr($sql, 0, strlen($sql) - 4);
			$sql = 'SELECT cat_votable
					  FROM ' . CONF_DB_PREF . 'categories
					 WHERE ' . $sql . '
				  ORDER BY LENGTH(cat_path) ASC';
			utils::$db->prepare($sql);
			utils::$db->executeQuery($params, PDO::FETCH_ASSOC);
			$parents = utils::$db->queryResult;

			// Détermine si les votes sont désactivés pour au moins un parent.
			foreach ($parents as &$i)
			{
				if ($i['cat_votable'] == 0)
				{
					self::_forbidden(__LINE__);
				}
			}
		}

		$vote_cookie = utils::$cookiePrefs->read('rate');

		// On détermine si l'utilisateur a déjà voté l'image.
		$update = 0;
		if (self::$auth)
		{
			$sql = 'SELECT vote_id,
						   vote_rate
					  FROM ' . CONF_DB_PREF . 'votes
					 WHERE image_id = ' . (int) $_POST['id'] . '
					   AND user_id = ' . (int) self::$userInfos['user_id'] . '
					 LIMIT 1';
			if (utils::$db->query($sql, 'row') === FALSE)
			{
				die;
			}
			$db_vote = utils::$db->queryResult;
			$update = utils::$db->nbResult;
		}
		else
		{
			if (preg_match('`^[a-z0-9]{32}$`i', $vote_cookie))
			{
				$sql = 'SELECT vote_id,
							   vote_rate
						  FROM ' . CONF_DB_PREF . 'votes
						 WHERE image_id = :image_id
						   AND vote_cookie = :vote_cookie
						 LIMIT 1';
				$params = array(
					'vote_cookie' => $vote_cookie,
					'image_id' => (int) $_POST['id']
				);
				if (utils::$db->prepare($sql) === FALSE
				|| utils::$db->executeQuery($params, 'row') === FALSE)
				{
					die;
				}
				$db_vote = utils::$db->queryResult;
				$update = utils::$db->nbResult;
			}
		}

		// Récupération des identifiants des catégories parentes.
		$parents_ids = alb::getParentsIds($image_infos['cat_id']);

		// On update la note de l'utilisateur.
		if ($update === 1)
		{
			// Seulement si elle est différente.
			if ($db_vote['vote_rate'] == $_POST['rate'])
			{
				die;
			}

			// On update la table des votes.
			$sql = 'UPDATE ' . CONF_DB_PREF . 'votes
					   SET vote_rate = :vote_rate
					 WHERE vote_id = :vote_id';
			$params = array(
				'vote_id' => $db_vote['vote_id'],
				'vote_rate' => (int) $_POST['rate']
			);
			if (utils::$db->prepare($sql) === FALSE
			|| utils::$db->executeExec($params) === FALSE)
			{
				die;
			}

			// On update la note moyenne de l'image.
			$sql = 'UPDATE ' . CONF_DB_PREF . 'images
					   SET image_rate = CASE WHEN image_votes = 1
							THEN :vote_rate
							ELSE (((((image_rate * image_votes) - :db_vote_rate)
								/ (image_votes - 1)) * (image_votes - 1)) + :vote_rate)
								/ image_votes
							END
					 WHERE image_id = :image_id';
			$params = array(
				'image_id' => (int) $_POST['id'],
				'vote_rate' => (int) $_POST['rate'],
				'db_vote_rate' => (int) $db_vote['vote_rate']
			);
			if (utils::$db->prepare($sql) === FALSE
			|| utils::$db->executeExec($params) === FALSE)
			{
				die;
			}

			// On update la note moyenne des catégories parentes.
			$sql = 'UPDATE ' . CONF_DB_PREF . 'categories
					   SET cat_a_rate = CASE WHEN cat_a_votes = 1
							THEN :vote_rate
							ELSE (((((cat_a_rate * cat_a_votes) - :db_vote_rate)
								/ (cat_a_votes - 1)) * (cat_a_votes - 1)) + :vote_rate)
								/ cat_a_votes
							END
					 WHERE cat_id IN (' . implode(', ', $parents_ids) . ')';
			$params = array(
				'vote_rate' => (int) $_POST['rate'],
				'db_vote_rate' => (int) $db_vote['vote_rate']
			);
			if (utils::$db->prepare($sql) === FALSE
			|| utils::$db->executeExec($params) === FALSE)
			{
				die;
			}

			// Log d'activité.
			sql::logUserActivity('vote_change', self::$userInfos['user_id'], NULL,
				array('image_id' => (int) $_POST['id'], 'rate' => (int) $_POST['rate']));
		}

		// On ajoute la note de l'utilisateur.
		else
		{
			// Si l'utilisateur ne possède aucun code de cookie,
			// on en génère un nouveau.
			if (!$vote_cookie)
			{
				$vote_cookie = utils::genKey('md5');
				utils::$cookiePrefs->add('rate', $vote_cookie);
				utils::$cookiePrefs->write();
			}

			// On insert le nouveau vote dans la table des votes.
			$sql = 'INSERT INTO ' . CONF_DB_PREF . 'votes (
				user_id, image_id, vote_rate, vote_date, vote_ip, vote_cookie
				) VALUES (
				:user_id, :image_id, :vote_rate, NOW(), :vote_ip, :vote_cookie
				)';
			$params = array(
				'user_id' => (int) self::$userInfos['user_id'],
				'image_id' => (int) $_POST['id'],
				'vote_rate' => (int) $_POST['rate'],
				'vote_ip' => $_SERVER['REMOTE_ADDR'],
				'vote_cookie' => $vote_cookie
			);
			if (utils::$db->prepare($sql) === FALSE
			|| utils::$db->executeExec($params) === FALSE)
			{
				die;
			}

			// On update la table des images du nombre de votes
			// et de la moyenne des notes.
			$sql = 'UPDATE ' . CONF_DB_PREF . 'images
					   SET image_rate = ((image_rate * image_votes) + :vote_rate)
							/ (image_votes + 1),
						   image_votes = image_votes + 1
					 WHERE image_id = :image_id';
			$params = array(
				'image_id' => (int) $_POST['id'],
				'vote_rate' => (int) $_POST['rate']
			);
			if (utils::$db->prepare($sql) === FALSE
			|| utils::$db->executeExec($params) === FALSE)
			{
				die;
			}

			// On update le nombre de votes et
			// la note moyenne des catégories parentes.
			$sql = 'UPDATE ' . CONF_DB_PREF . 'categories
					   SET cat_a_rate = ((cat_a_rate * cat_a_votes) + :vote_rate)
							/ (cat_a_votes + 1),
						   cat_a_votes = cat_a_votes + 1
					 WHERE cat_id IN (' . implode(', ', $parents_ids) . ')';
			$params = array(
				'vote_rate' => (int) $_POST['rate']
			);
			if (utils::$db->prepare($sql) === FALSE
			|| utils::$db->executeExec($params) === FALSE)
			{
				die;
			}

			// Log d'activité.
			sql::logUserActivity('vote_add', self::$userInfos['user_id'], NULL,
				array('image_id' => (int) $_POST['id'], 'rate' => (int) $_POST['rate']));
		}

		// Récupération des informations de l'image mises à jour.
		$sql = 'SELECT image_votes,
					   image_rate
				  FROM ' . CONF_DB_PREF . 'images
				 WHERE image_id = ' . (int) $_POST['id'];
		if (utils::$db->query($sql, 'row') === FALSE)
		{
			die;
		}
		$i = utils::$db->queryResult;

		// Exécution de la transaction.
		if (!utils::$db->commit())
		{
			die;
		}

		// On renvoi les informations utiles.
		$votes = ($i['image_votes'] > 1)
			? __('%s votes')
			: __('%s vote');
		$style_path = utils::tplProtect(
			CONF_GALLERY_PATH . '/template/' . utils::$config['theme_template']
			. '/style/' . utils::$config['theme_style']
		);
		die(json_encode(array(
			'status' => 'success',
			'votes' => sprintf($votes, (int) $i['image_votes']),
			'rate' => number_format((float) $i['image_rate'], 1, __(','), ''),
			'rate_visual' => template::visualRate($i['image_rate'], $style_path)
		)));
	}

	/**
	 * Ajoute ou supprime des tags sur des images.
	 *
	 * @param string $action
	 *	Action à effectuer : 'add' ou 'remove'.
	 * @return void
	 */
	public static function tags($action)
	{
		// Quelques vérifications.
		if (!self::_q()
		|| !utils::$config['tags'] || !self::$userPerms['gallery']['perms']['edit']
		|| self::$userPerms['gallery']['perms']['edit_owner']
		|| !isset($_POST['tags']) || !isset($_POST['images_id'])
		|| !preg_match('`^\d{1,11}(,\d{1,11}){0,999}$`', $_POST['images_id']))
		{
			self::_forbidden(__LINE__);
		}

		$report = explode(
			':',
			tagsImage::$action(
				explode(',', $_POST['images_id']),
				$_POST['tags'],
				TRUE,
				TRUE,
				self::$userInfos['user_id']
			),
			2
		);

		if ($report[0] == 'error')
		{
			self::_error();
		}
		else
		{
			die(json_encode(array(
				'status' => 'success',
				'msg' => $report[1]
			)));
		}
	}



	/**
	 * Authentification utilisateur.
	 *
	 * @param boolean $admin
	 *	La requête ajax s'opère-t-elle depuis l'admin ?
	 * @param string $session_token
	 *	Jeton de session.
	 * @return void
	 */
	private static function _auth($admin, $session_token)
	{
		// Récupération des informations utilisateur.
		if (($admin || (!$admin && utils::$config['users'] == 1)) && $session_token !== FALSE)
		{
			$sql = 'SELECT user_id,
						   user_lang,
						   user_tz,
						   user_prefs,
						   user_nohits,
						   group_admin,
						   group_perms
					  FROM ' . CONF_DB_PREF . 'sessions AS s,
						   ' . CONF_DB_PREF . 'groups AS g,
						   ' . CONF_DB_PREF . 'users AS u
					 WHERE u.session_id = s.session_id
					   AND u.group_id = g.group_id
					   AND user_status = "1"
					   AND session_token = :session_token
					   AND session_expire > NOW()';
			$params = array(
				'session_token' => $session_token
			);
			if (utils::$db->prepare($sql) !== FALSE
			&& utils::$db->executeQuery($params, 'row') !== FALSE
			&& utils::$db->nbResult === 1)
			{
				self::$auth = TRUE;
			}
		}

		// Si non authentifié.
		if (!self::$auth)
		{
			// Accès à l'admin interdit pour les invités.
			if ($admin)
			{
				self::_forbidden(__LINE__);
			}

			// Accès à la galerie interdit pour les invités ?
			if (!$admin && utils::$config['users'] && utils::$config['users_only_members'])
			{
				self::_forbidden(__LINE__);
			}

			// Récupération des droits invités.
			$sql = 'SELECT 2 AS user_id,
						   group_perms
					  FROM ' . CONF_DB_PREF . 'groups
					 WHERE group_id = 2';
			if (utils::$db->query($sql, 'row') === FALSE
			 || utils::$db->nbResult !== 1)
			{
				die;
			}
		}

		self::$userInfos = utils::$db->queryResult;

		// Préférences utilisateur.
		self::$userPrefs = (self::$userInfos['user_id'] != 2
			&& utils::isSerializedArray(self::$userInfos['user_prefs']))
			? unserialize(self::$userInfos['user_prefs'])
			: array();

		// Permissions.
		self::$userPerms = self::$userInfos['group_perms'];
		if (!utils::isSerializedArray(self::$userPerms))
		{
			die;
		}
		self::$userPerms = unserialize(self::$userPerms);

		// Mode d'accès aux albums.
		$perms = ($admin) ? 'admin' : 'gallery';
		sql::albumsAccess(self::$userPerms[$perms]);

		// Langue et fuseau horaire.
		if (($admin || utils::$config['users'] == 1)
		&& self::$userInfos['user_id'] != 2)
		{
			if (isset(utils::$config['locale_langs'][self::$userInfos['user_lang']]))
			{
				utils::$userLang = self::$userInfos['user_lang'];
			}
			utils::$userTz = self::$userInfos['user_tz'];
		}
		else if (utils::$config['lang_switch']
		&& preg_match('`^[a-z]{2}_[A-Z]{2}$`', utils::$cookiePrefs->read('lang'))
		&& isset(utils::$config['locale_langs'][utils::$cookiePrefs->read('lang')]))
		{
			utils::$userLang = utils::$cookiePrefs->read('lang');
		}
		else if (utils::$config['lang_client'])
		{
			utils::detectClientLang();
		}

		// Chargement du fichier de langue.
		utils::locale();
	}

	/**
	 * Vérifie la capacité du panier lors de l'ajout d'images au panier.
	 *
	 * @param array $image_infos
	 *	Informations utiles sur l'image à ajouter au panier.
	 * @param array $session
	 *	Informations de session.
	 * @return void
	 */
	private static function _basketCapacity($images_infos, $session = NULL)
	{
		// Récupération du nombre d'images et du poids total du panier.
		if (self::$auth)
		{
			$sql = 'SELECT COUNT(*) AS nb_images,
						   SUM(image_filesize) AS filesize
					  FROM ' . CONF_DB_PREF . 'basket AS b,
						   ' . CONF_DB_PREF . 'images AS i
					 WHERE image_status = "1"
					   AND b.image_id = i.image_id
					   AND b.user_id = ' . (int) self::$userInfos['user_id'];
		}
		else
		{
			$sql = 'SELECT COUNT(*) AS nb_images,
						   SUM(image_filesize) AS filesize
					  FROM ' . CONF_DB_PREF . 'basket AS b,
						   ' . CONF_DB_PREF . 'images AS i,
						   ' . CONF_DB_PREF . 'sessions AS s
					 WHERE image_status = "1"
					   AND b.image_id = i.image_id
					   AND s.session_id = b.session_id
					   AND s.session_token = "' . $session['session_token'] . '"';
		}
		if (utils::$db->query($sql, 'row') === FALSE)
		{
			die;
		}
		$basket_infos = utils::$db->queryResult;

		// On vérifie si le panier avec la nouvelle image a ajouter
		// dépasse les limites autorisées.
		if (($basket_infos['nb_images'] + count($images_infos))
		> (int) utils::$config['basket_max_images'])
		{
			$msg = (count($images_infos) > 1)
				? __('Vous ne pouvez ajouter ces images au panier'
					. ' car le nombre maximum d\'images du panier a été atteint.')
				: __('Vous ne pouvez ajouter cette image au panier'
					. ' car le nombre maximum d\'images du panier a été atteint.');
		}
		if (($basket_infos['filesize'] + array_sum($images_infos))
		> ((int) utils::$config['basket_max_filesize'] * 1024))
		{
			$msg = (count($images_infos) > 1)
				? __('Vous ne pouvez ajouter ces images au panier'
					. ' car le poids maximum du panier a été atteint.')
				: __('Vous ne pouvez ajouter cette image au panier'
					. ' car le poids maximum du panier a été atteint.');
		}

		if (isset($msg))
		{
			die(json_encode(array(
				'status' => 'full',
				'msg' => wordwrap($msg, 60, "\n")
			)));
		}
	}

	/**
	 * Décode les données d'édition (titre et description)
	 * des catégories et images.
	 *
	 * @return void
	 */
	private static function _decodeData()
	{
		if (!isset($_POST['data']))
		{
			die;
		}

		$data = array_map('urldecode', explode('&', $_POST['data']));
		foreach ($data as &$field)
		{
			if (preg_match('`^(title|desc)\[([a-z]{2}_[A-Z]{2})\]=([^$]*)$`', $field, $m)
			&& isset(utils::$config['locale_langs'][$m[2]]))
			{
				$_POST[$m[1]][$m[2]] = $m[3];
			}
		}

		unset($_POST['data']);
	}

	/**
	 * Édition de la description pour images et catégories.
	 *
	 * @param array $infos
	 * @param string $column_name
	 * @return string|boolean
	 */
	private static function _editDesc(&$infos, $column_name)
	{
		$locale_text = utils::setLocaleText($_POST['desc'], $infos[$column_name], 10000);

		if ($locale_text['change'])
		{
			foreach (utils::$config['locale_langs'] as $code => &$name)
			{
				$_POST['desc'][$code] = utils::getLocale($locale_text['data'], $code);
			}
		}

		return $locale_text['change']
			? $locale_text['data']
			: FALSE;
	}

	/**
	 * Édition de la description de la galerie.
	 *
	 * @return void
	 */
	private static function _editGalleryDescription()
	{
		$sql = 'SELECT conf_value
				  FROM ' . CONF_DB_PREF . 'config
				 WHERE conf_name = "gallery_description"';
		if (utils::$db->query($sql, 'value') === FALSE)
		{
			die;
		}
		if (utils::$db->nbResult === 0)
		{
			self::_forbidden(__LINE__);
		}
		$infos = array(
			'desc' => utils::$db->queryResult
		);

		if (!isset($_POST['desc'])
		|| ($new_description = self::_editDesc($infos, 'desc')) === FALSE)
		{
			die(json_encode(array('status' => 'nochange')));
		}

		$sql = 'UPDATE ' . CONF_DB_PREF . 'config
				   SET conf_value = :desc
				 WHERE conf_name = "gallery_description"
				 LIMIT 1';
		$params = array(
			'desc' => $new_description
		);
		if (utils::$db->prepare($sql) === FALSE
		|| utils::$db->executeExec($params) === FALSE)
		{
			die;
		}

		self::_editJson();
	}

	/**
	 * Retourne les données au format JSON pour l'édition des catégories et images.
	 *
	 * @return void
	 */
	private static function _editJson(&$infos = NULL)
	{
		// Titre.
		$title_langs = array();
		if ($_POST['id'] > 1)
		{
			foreach ($_POST['title'] as $code => &$text)
			{
				$title_langs[$code] = $text;
			}
		}
		$title = (isset($_POST['title'][utils::$userLang]) && $_POST['id'] > 1)
			? utils::tplprotect($_POST['title'][utils::$userLang])
			: '';

		// Description.
		$desc_langs = array();
		foreach ($_POST['desc'] as $code => &$text)
		{
			$desc_langs[$code] = $text;
		}
		if ($infos === NULL)
		{
			$desc = isset($_POST['desc'][utils::$userLang])
				? nl2br(utils::tplHTMLFilter($_POST['desc'][utils::$userLang]))
				: '';
		}
		else
		{
			$desc = isset($_POST['desc'][utils::$userLang])
				? $_POST['desc'][utils::$userLang]
				: '';
			if (isset($infos['cat_desc']))
			{
				$infos['cat_desc'] = $desc;
				$desc = template::desc('cat', $infos);
			}
			else
			{
				$infos['image_desc'] = $desc;
				$desc = template::desc('image', $infos);
			}
		}

		// Tags.
		$tags = (isset($_POST['tags']))
			? $_POST['tags']
			: '';

		// Opération réussie.
		die(json_encode(array(
			'title' => $title,
			'title_langs' => $title_langs,
			'desc' => $desc,
			'desc_langs' => $desc_langs,
			'tags' => $tags,
			'status' => 'success'
		)));
	}

	/**
	 * Édition des tags de l'image.
	 *
	 * @return boolean
	 */
	private static function _editTags()
	{
		if (!utils::$config['tags'] || !isset($_POST['tags']) || !is_string($_POST['tags']))
		{
			return FALSE;
		}

		// Récupération des tags actuels liés à l'image.
		$sql = 'SELECT tag_name
				  FROM ' . CONF_DB_PREF . 'tags AS t,
				       ' . CONF_DB_PREF . 'tags_images AS ti
				 WHERE ti.tag_id = t.tag_id
				   AND ti.image_id = ' . (int) $_POST['id'];
		$fetch_style = array('column' => array('tag_name', 'tag_name'));
		if (utils::$db->query($sql, $fetch_style) === FALSE)
		{
			return FALSE;
		}

		// Mise à jour des tags.
		$tags_update = array(
			'add' => array(),
			'delete' => array(),
			'tags' => array()
		);

		tagsImage::edit(
			(int) $_POST['id'],
			$_POST['tags'],
			utils::$db->queryResult,
			$tags_update
		);

		// Si aucun changement ou erreur.
		if (!is_array($tags_update = tagsImage::update($tags_update))
		 || !$tags_update['success'])
		{
			return FALSE;
		}

		// En cas de mise à changement effectués avec succès,
		// on récupère les tags liés à l'image.
		$sql = 'SELECT t.*
				  FROM ' . CONF_DB_PREF . 'tags AS t,
				       ' . CONF_DB_PREF . 'tags_images AS ti
				 WHERE ti.tag_id = t.tag_id
				   AND ti.image_id = ' . (int) $_POST['id'] . '
			  ORDER BY LOWER(tag_name) ASC';
		$fetch_style = array('fetch' => PDO::FETCH_ASSOC, 'column' => 'tag_id');
		if (utils::$db->query($sql, $fetch_style) === FALSE)
		{
			return FALSE;
		}
		$image_tags = utils::$db->queryResult;

		foreach ($image_tags as &$tag_infos)
		{
			$tag_infos['tag_id'] = (int) $tag_infos['tag_id'];
			$tag_infos['tag_name'] = utils::tplProtect($tag_infos['tag_name']);
			$tag_infos['tag_url'] = utils::tplProtect($tag_infos['tag_url']);
			$tag_infos['tag_link'] = utils::genURL(
				'tag/' . $tag_infos['tag_id'] . '-' . $tag_infos['tag_url']
			);
		}

		$_POST['tags'] = array_values($image_tags);

		return TRUE;
	}

	/**
	 * Édition du titre pour images et catégories.
	 *
	 * @param array $infos
	 * @param string $column_name
	 * @return string|boolean
	 */
	private static function _editTitle(&$infos, $column_name)
	{
		$locale_text = utils::setLocaleText($_POST['title'], $infos[$column_name], 255, TRUE);

		if ($locale_text['change'])
		{
			foreach (utils::$config['locale_langs'] as $code => &$name)
			{
				$_POST['title'][$code] = utils::getLocale($locale_text['data'], $code);
			}
		}

		if ($locale_text['empty'])
		{
			die(json_encode(array(
				'status' => 'warning',
				'msg' => __('Le titre doit contenir au moins 1 caractère.')
			)));
		}

		return $locale_text['change']
			? $locale_text['data']
			: FALSE;
	}

	/**
	 * Édition du nom d'URL pour images et catégories.
	 *
	 * @param array $infos
	 * @param string $column_name
	 * @return string|boolean
	 */
	private static function _editURLName(&$infos, $column_name)
	{
		$_POST['urlname'] = str_replace('/', '', $_POST['urlname']);
		if (trim($_POST['urlname']) === $infos[$column_name])
		{
			return FALSE;
		}

		// Vérification de la longueur.
		if (mb_strlen($_POST['urlname']) > 255 || mb_strlen($_POST['urlname']) < 1)
		{
			die(json_encode(array(
				'status' => 'warning',
				'msg' => __('Le nom d\'URL doit contenir au moins 1 caractère.')
			)));
		}

		return $_POST['urlname'];
	}

	/**
	 * Indique à l'utilisateur qu'une erreur s'est produite.
	 *
	 * @return void
	 */
	private static function _error()
	{
		die(json_encode(array(
			'status' => 'error',
			'msg' => wordwrap(__('Une erreur s\'est produite durant le'
				. ' traitement de votre requête. Vous êtes invité à signaler'
				. ' cette erreur à un administrateur afin que le problème soit'
				. ' corrigé dans les plus brefs délais.'), 60, "\n")
		)));
	}

	/**
	 * Indique à l'utilisateur que l'action demandée lui est interdite.
	 *
	 * @param integer $line
	 * @return void
	 */
	private static function _forbidden($line)
	{
		die(json_encode(array(
			'status' => 'error',
			'msg' => '[' . $line . '] You are not allowed here.'
		)));
	}

	/**
	 * Vérifie l'authenticité du paramètre "q".
	 *
	 * @return boolean
	 */
	private static function _q()
	{
		$ok = isset($_POST['q'])
			&& isset($_POST['q_md5'])
			&& $_POST['q_md5'] == md5('key:' . CONF_KEY . '|q:' . $_POST['q']);

		if ($ok)
		{
			$_GET['q'] = $_POST['q'];
		}

		return $ok;
	}
}
?>