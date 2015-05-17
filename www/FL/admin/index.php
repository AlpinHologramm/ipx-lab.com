<?php
/**
 * Administration de la galerie.
 *
 * @license http://www.gnu.org/licenses/gpl.html
 * @link http://www.igalerie.org/
 */

require_once(dirname(__FILE__) . '/../includes/prepend.php');

// Si la galerie n'est pas installée, on redirige vers le script d'installation.
if (!CONF_INSTALL)
{
	header('Location: ../install/');
	die;
}

// Paramètres pour les URL.
utils::$purlUrlRewrite = FALSE;
utils::$purlDir = '/' . basename(dirname(__FILE__));

// Extraction de la requête.
extract_request(array(
	'album' => array
	(
		'(\d{1,11})(?:/page/(\d{1,11}))?' =>
			array('object_id', 'page'),

		// search
		'(\d{1,11})/search/([\dA-Za-z]{12})(?:/page/(\d{1,11}))?' =>
			array('object_id', 'search', 'page'),

		// camera-brands, camera-models
		'(\d{1,11})/(camera-(?:brand|model))/(\d{1,11})(?:/page/(\d{1,11}))?' =>
			array('object_id', 'filter', 'cam_id', 'page'),

		// tag
		'(\d{1,11})/(tag)/(\d{1,11})(?:/page/(\d{1,11}))?' =>
			array('object_id', 'filter', 'tag_id', 'page'),

		// user-basket, user-favorites, user-images
		'(\d{1,11})/(user-(?:basket|favorites|images))/(\d{1,11})(?:/page/(\d{1,11}))?' =>
			array('object_id', 'filter', 'user_id', 'page')
	),
	'camera-brands' => array
	(
		'(?:page/(\d{1,11}))?' =>
			array('page'),

		// search
		'search/([\dA-Za-z]{12})(?:/page/(\d{1,11}))?' =>
			array('search', 'page')
	),
	'camera-models' => array
	(
		'(?:page/(\d{1,11}))?' =>
			array('page'),

		// search
		'search/([\dA-Za-z]{12})(?:/page/(\d{1,11}))?' =>
			array('search', 'page')
	),
	'category' => array
	(
		'(\d{1,11})(?:/page/(\d{1,11}))?' =>
			array('object_id', 'page'),

		// search
		'(\d{1,11})/search/([\dA-Za-z]{12})(?:/page/(\d{1,11}))?' =>
			array('object_id', 'search', 'page'),

		// camera-brands, camera-models
		'(\d{1,11})/(camera-(?:brand|model))/(\d{1,11})(?:/page/(\d{1,11}))?' =>
			array('object_id', 'filter', 'cam_id', 'page'),

		// tag
		'(\d{1,11})/(tag)/(\d{1,11})(?:/page/(\d{1,11}))?' =>
			array('object_id', 'filter', 'tag_id', 'page'),

		// user-basket, user-favorites, user-images
		'(\d{1,11})/(user-(?:basket|favorites|images))/(\d{1,11})(?:/page/(\d{1,11}))?' =>
			array('object_id', 'filter', 'user_id', 'page')
	),
	'comments-guestbook' => array
	(
		'(?:page/(\d{1,11}))?' =>
			array('page'),

		// date
		'date/(\d{4}-\d{2}-\d{2})(?:/page/(\d{1,11}))?' =>
			array('date', 'page'),

		// ip
		'ip/((?:\d{1,3}\.){3}\d{1,3})(?:/page/(\d{1,11}))?' =>
			array('ip', 'page'),

		// pending
		'(pending)(?:/page/(\d{1,11}))?' =>
			array('status', 'page'),

		// search
		'search/([\dA-Za-z]{12})(?:/page/(\d{1,11}))?' =>
			array('search', 'page'),

		// user
		'user/(\d{1,11})(?:/page/(\d{1,11}))?' =>
			array('user_id', 'page')
	),
	'comments-images' => array
	(
		'(?:page/(\d{1,11}))?' =>
			array('page'),
		'(category|image)/(\d{1,11})(?:/page/(\d{1,11}))?' =>
			array('object_type', 'object_id', 'page'),

		// date
		'date/(\d{4}-\d{2}-\d{2})(?:/page/(\d{1,11}))?' =>
			array('date', 'page'),
		'(category|image)/(\d{1,11})/date/(\d{4}-\d{2}-\d{2})(?:/page/(\d{1,11}))?' =>
			array('object_type', 'object_id', 'date', 'page'),

		// ip
		'ip/((?:\d{1,3}\.){3}\d{1,3})(?:/page/(\d{1,11}))?' =>
			array('ip', 'page'),
		'(category|image)/(\d{1,11})/ip/((?:\d{1,3}\.){3}\d{1,3})(?:/page/(\d{1,11}))?' =>
			array('object_type', 'object_id', 'ip', 'page'),

		// pending
		'(pending)(?:/page/(\d{1,11}))?' =>
			array('status', 'page'),
		'(category|image)/(\d{1,11})/(pending)(?:/page/(\d{1,11}))?' =>
			array('object_type', 'object_id', 'status', 'page'),

		// search
		'search/([\dA-Za-z]{12})(?:/page/(\d{1,11}))?' =>
			array('search', 'page'),
		'(category|image)/(\d{1,11})/search/([\dA-Za-z]{12})(?:/page/(\d{1,11}))?' =>
			array('object_type', 'object_id', 'search', 'page'),

		// user
		'user/(\d{1,11})(?:/page/(\d{1,11}))?' =>
			array('user_id', 'page'),
		'(category|image)/(\d{1,11})/user/(\d{1,11})(?:/page/(\d{1,11}))?' =>
			array('object_type', 'object_id', 'user_id', 'page')
	),
	'comments-options' => array(),
	'dashboard' => array(),
	'edit-album' => array
	(
		'(\d{1,11})' =>
			array('object_id')
	),
	'edit-category' => array
	(
		'(\d{1,11})' =>
			array('object_id')
	),
	'edit-image' => array
	(
		'(\d{1,11})' =>
			array('object_id')
	),
	'exif' => array(),
	'ftp' => array(),
	'functions' => array(),
	'geoloc-album' => array
	(
		'(\d{1,11})' =>
			array('object_id')
	),
	'geoloc-category' => array
	(
		'(\d{1,11})' =>
			array('object_id')
	),
	'geoloc-image' => array
	(
		'(\d{1,11})' =>
			array('object_id')
	),
	'group' => array
	(
		'(\d{1,11})(?:/(new))?' =>
			array('object_id', 'confirm')
	),
	'groups' => array(),
	'image' => array
	(
		'(\d{1,11})' =>
			array('object_id')
	),
	'images-pending' => array
	(
		'(?:page/(\d{1,11}))?' =>
			array('page')
	),
	'incidents' => array(),
	'logs' => array
	(
		'(?:page/(\d{1,11}))?' =>
			array('page'),
		'user/(\d{1,11})(?:/page/(\d{1,11}))?' =>
			array('user_id', 'page'),

		// date
		'date/(\d{4}-\d{2}-\d{2})(?:/page/(\d{1,11}))?' =>
			array('date', 'page'),
		'user/(\d{1,11})/date/(\d{4}-\d{2}-\d{2})(?:/page/(\d{1,11}))?' =>
			array('user_id', 'date', 'page'),

		// ip
		'ip/((?:\d{1,3}\.){3}\d{1,3})(?:/page/(\d{1,11}))?' =>
			array('ip', 'page'),
		'user/(\d{1,11})/ip/((?:\d{1,3}\.){3}\d{1,3})(?:/page/(\d{1,11}))?' =>
			array('user_id', 'ip', 'page'),

		// search
		'search/([\dA-Za-z]{12})(?:/page/(\d{1,11}))?' =>
			array('search', 'page'),
		'user/(\d{1,11})/search/([\dA-Za-z]{12})(?:/page/(\d{1,11}))?' =>
			array('user_id', 'search', 'page')
	),
	'stats-objects' => array(),
	'stats-users' => array(),
	'iptc' => array(),
	'maintenance' => array(),
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
	),
	'new-group' => array(),
	'new-page' => array(),
	'new-thumb' => array
	(
		'(\d{1,11})(?:/page/(\d{1,11}))?' =>
			array('object_id', 'page'),
		'(\d{1,11})/cat/(\d{1,11})(?:/page/(\d{1,11}))?' =>
			array('object_id', 'cat_id', 'page')
	),
	'new-user' => array(),
	'new-widget' => array(),
	'options-advanced' => array(),
	'options-blacklists' => array(),
	'options-descriptions' => array(),
	'options-gallery' => array(),
	'options-images' => array(),
	'options-email' => array(),
	'options-thumbs' => array(),
	'page' => array
	(
		'(comments|contact|guestbook|members|worldmap)' =>
			array('page'),
		'(perso)/(\d{1,2})(?:/(new))?' =>
			array('page', 'perso', 'confirm')
	),
	'pages' => array(),
	'sort-album' => array
	(
		'(\d{1,11})' =>
			array('object_id')
	),
	'sort-category' => array
	(
		'(\d{1,11})' =>
			array('object_id')
	),
	'system' => array(),
	'tags' => array
	(
		'(?:page/(\d{1,11}))?' =>
			array('page'),

		// search
		'search/([\dA-Za-z]{12})(?:/page/(\d{1,11}))?' =>
			array('search', 'page')
	),
	'themes' => array(),
	'thumb-album' => array
	(
		'(\d{1,11})' =>
			array('object_id')
	),
	'thumb-category' => array
	(
		'(\d{1,11})' =>
			array('object_id')
	),
	'thumb-image' => array
	(
		'(\d{1,11})' =>
			array('object_id')
	),
	'user' => array
	(
		'(\d{1,11})(?:/(new))?' =>
			array('object_id', 'confirm'),
		'(\d{1,11})/group/(\d{1,11})' =>
			array('object_id', 'group_id'),

		// pending
		'(\d{1,11})/(pending)' =>
			array('object_id', 'status'),
		'(\d{1,11})/group/(\d{1,11})/(pending)' =>
			array('object_id', 'group_id', 'status'),

		// search
		'(\d{1,11})/search/([\dA-Za-z]{12})' =>
			array('object_id', 'search'),
		'(\d{1,11})/group/(\d{1,11})/search/([\dA-Za-z]{12})' =>
			array('object_id', 'group_id', 'search')
	),
	'user-avatar' => array
	(
		'(\d{1,11})' =>
			array('object_id')
	),
	'user-watermark' => array
	(
		'(\d{1,11})' =>
			array('object_id')
	),
	'user-sendmail' => array
	(
		'(\d{1,11})' =>
			array('object_id')
	),
	'users' => array
	(
		'(?:page/(\d{1,11}))?' =>
			array('page'),
		'group/(\d{1,11})(?:/page/(\d{1,11}))?' =>
			array('group_id', 'page'),

		// pending
		'(pending)(?:/page/(\d{1,11}))?' =>
			array('status', 'page'),
		'group/(\d{1,11})/(pending)(?:/page/(\d{1,11}))?' =>
			array('group_id', 'status', 'page'),

		// search
		'search/([\dA-Za-z]{12})(?:/page/(\d{1,11}))?' =>
			array('search', 'page'),
		'group/(\d{1,11})/search/([\dA-Za-z]{12})(?:/page/(\d{1,11}))?' =>
			array('group_id', 'search', 'page')
	),
	'users-options' => array(),
	'users-sendmail' => array(),
	'votes' => array
	(
		'(?:page/(\d{1,11}))?' =>
			array('page'),
		'(category|image)/(\d{1,11})(?:/page/(\d{1,11}))?' =>
			array('object_type', 'object_id', 'page'),

		// date
		'date/(\d{4}-\d{2}-\d{2})(?:/page/(\d{1,11}))?' =>
			array('date', 'page'),
		'(category|image)/(\d{1,11})/date/(\d{4}-\d{2}-\d{2})(?:/page/(\d{1,11}))?' =>
			array('object_type', 'object_id', 'date', 'page'),

		// ip
		'ip/((?:\d{1,3}\.){3}\d{1,3})(?:/page/(\d{1,11}))?' =>
			array('ip', 'page'),
		'(category|image)/(\d{1,11})/ip/((?:\d{1,3}\.){3}\d{1,3})(?:/page/(\d{1,11}))?' =>
			array('object_type', 'object_id', 'ip', 'page'),

		// search
		'search/([\dA-Za-z]{12})(?:/page/(\d{1,11}))?' =>
			array('search', 'page'),
		'(category|image)/(\d{1,11})/search/([\dA-Za-z]{12})(?:/page/(\d{1,11}))?' =>
			array('object_type', 'object_id', 'search', 'page'),

		// user
		'user/(\d{1,11})(?:/page/(\d{1,11}))?' =>
			array('user_id', 'page'),
		'(category|image)/(\d{1,11})/user/(\d{1,11})(?:/page/(\d{1,11}))?' =>
			array('object_type', 'object_id', 'user_id', 'page')
	),
	'watermark' => array(),
	'watermark-album' => array
	(
		'(\d{1,11})' =>
			array('object_id')
	),
	'watermark-category' => array
	(
		'(\d{1,11})' =>
			array('object_id')
	),
	'widgets' => array(),
	'widget' => array
	(
		'(geoloc|image|links|navigation|stats-'
		. '(?:categories|images)|online-users|options|tags|user)' =>
			array('widget'),
		'(perso)/(\d{1,2})' =>
			array('widget', 'perso'),
		'(perso)/(\d{1,2})/(new)' =>
			array('widget', 'perso', 'confirm')
	),
	'xmp' => array()
));

// Première page si aucune page n'est définie.
if (!isset($_GET['page']))
{
	$_GET['page'] = 1;
}

// Section par défaut.
if (!isset($_GET['section']))
{
	$_GET['section'] = 'dashboard';
}

// Initialisation de l'admin.
admin::init();

// Traitement de la requête.
switch ($_GET['section'])
{
	// Gestion des images.
	case 'album' :

		// Permissions d'accès.
		if (!auth::$perms['admin']['perms']['albums_modif']
		&& !auth::$perms['admin']['perms']['albums_edit']
		&& !auth::$perms['admin']['perms']['all'])
		{
			die;
		}

		admin::$pageTitle = mb_strtolower(__('Gestion des albums'));

		// Recherche.
		admin::searchGetPost();

		if (!isset($_GET['search_type']) || $_GET['search_type'] == 'album')
		{
			admin::controlAlbum();
			final class tpl extends tplAlbum{};
		}
		else
		{
			admin::controlCategory();
			final class tpl extends tplCategory{};
		}

		break;

	// Gestion des marques d'appareils photos.
	case 'camera-brands' :

		// Permissions d'accès
		// (identique à celles pour la gestion des tags).
		if (!auth::$perms['admin']['perms']['tags']
		&& !auth::$perms['admin']['perms']['all'])
		{
			die;
		}

		admin::$pageTitle = mb_strtolower(__('Gestion des appareils photos'));
		admin::$tplFile = 'cameras';

		// Recherche.
		cameras::searchGetPost(cameras::$searchOptions['brand']);

		admin::displayOptions('camera_brands');
		cameras::getItems('brand');
		cameras::edit('brand');

		final class tpl extends tplCameras{};
		break;

	// Gestion des modèles d'appareils photos.
	case 'camera-models' :

		// Permissions d'accès
		// (identique à celles pour la gestion des tags).
		if (!auth::$perms['admin']['perms']['tags']
		&& !auth::$perms['admin']['perms']['all'])
		{
			die;
		}

		admin::$pageTitle = mb_strtolower(__('Gestion des appareils photos'));
		admin::$tplFile = 'cameras';

		// Recherche.
		cameras::searchGetPost(cameras::$searchOptions['model']);

		admin::displayOptions('camera_models');
		cameras::getItems('model');
		cameras::edit('model');

		final class tpl extends tplCameras{};
		break;

	// Gestion des catégories.
	case 'category' :

		// Permissions d'accès.
		if (!auth::$perms['admin']['perms']['albums_modif']
		&& !auth::$perms['admin']['perms']['albums_edit']
		&& !auth::$perms['admin']['perms']['all'])
		{
			die;
		}

		admin::$pageTitle = mb_strtolower(__('Gestion des albums'));

		// Recherche.
		admin::searchGetPost();

		if (!isset($_GET['filter'])
		&& (!isset($_GET['search_type']) || $_GET['search_type'] == 'category'))
		{
			admin::controlCategory();
			final class tpl extends tplCategory{};
		}
		else
		{
			admin::controlAlbum();
			final class tpl extends tplAlbum{};
		}

		break;

	// Gestion des commentaires
	case 'comments-guestbook' :

		// Permissions d'accès.
		if (!auth::$perms['admin']['perms']['comments_edit']
		&& !auth::$perms['admin']['perms']['all'])
		{
			die;
		}

		// Liste des utilisateurs.
		users::getUsersList();
		if (isset($_GET['user_id']) && !isset(users::$usersList[$_GET['user_id']]))
		{
			utils::redirect('comments-guestbook');
		}

		// Recherche.
		admin::searchGetPost(guestbook::$searchOptions);

		admin::$pageTitle = mb_strtolower(__('Gestion des commentaires'));
		admin::$tplFile = 'comments_guestbook';

		// Actions sur la sélection, que l'on effectue avant
		// la récupération des informations pour générer la page.
		guestbook::actions();

		// Options d'affichage.
		admin::displayOptions('guestbook');

		// Récupération des données pour générer la page.
		guestbook::getComments();

		// Édition des commentaires, que l'on effectue après
		// la récupération des informations pour générer la page.
		guestbook::edit();

		final class tpl extends tplCommentsGuestbook{};
		break;

	// Gestion des commentaires
	case 'comments-images' :

		// Permissions d'accès.
		if (!auth::$perms['admin']['perms']['comments_edit']
		&& !auth::$perms['admin']['perms']['all'])
		{
			die;
		}

		// Liste des utilisateurs.
		users::getUsersList();
		if (isset($_GET['user_id']) && !isset(users::$usersList[$_GET['user_id']]))
		{
			utils::redirect('comments-images');
		}

		// Recherche.
		admin::searchGetPost(comments::$searchOptions);

		admin::$pageTitle = mb_strtolower(__('Gestion des commentaires'));
		admin::$tplFile = 'comments_images';

		// Actions sur la sélection, que l'on effectue avant
		// la récupération des informations pour générer la page.
		comments::actions();

		// Options d'affichage.
		admin::displayOptions('comments');

		// Récupération des données pour générer la page.
		comments::getComments();
		albums::getMap(FALSE);
		comments::reduceMap();
		comments::getImages();

		// Édition des commentaires, que l'on effectue après
		// la récupération des informations pour générer la page.
		comments::edit();

		// Récupérations des informations des catégories parentes.
		albums::$infos =& comments::$objectInfos;
		if (isset($_GET['object_type']) && $_GET['object_type'] == 'image')
		{
			albums::$infos['cat_path'] .= '/1';
		}
		albums::parents();

		final class tpl extends tplCommentsImages{};
		break;

	// Options sur les commentaires.
	case 'comments-options' :

		// Permissions d'accès.
		if (!auth::$perms['admin']['perms']['comments_options']
		&& !auth::$perms['admin']['perms']['all'])
		{
			die;
		}

		comments::options();

		admin::$pageTitle = mb_strtolower(__('Gestion des commentaires'));
		admin::$tplFile = 'comments_options';

		final class tpl extends tplCommentsOptions{};
		break;

	// Tableau de bord.
	case 'dashboard' :

		admin::dashboard();

		admin::$pageTitle = mb_strtolower(__('Tableau de bord'));
		admin::$tplFile = 'dashboard';

		final class tpl extends tplDashboard{};
		break;

	// Édition d'un album.
	case 'edit-album' :

		// Permissions d'accès.
		if (!auth::$perms['admin']['perms']['albums_modif']
		&& !auth::$perms['admin']['perms']['albums_edit']
		&& !auth::$perms['admin']['perms']['all'])
		{
			die;
		}

		// Interdit pour la catégorie 1.
		if ($_GET['object_id'] == 1)
		{
			utils::redirect('category/1');
			return;
		}

		category::infos();

		category::$styles = utils::getStyles();

		category::delete();
		category::editGeneral();
		if (category::editInfosSettings($_GET['object_id'], albums::$infos))
		{
			category::infos();
		}

		albums::getMap();
		albums::parents();
		albums::parentPage();

		users::getUsersList();

		admin::$pageTitle = mb_strtolower(__('Gestion des albums'));
		admin::$tplFile = 'edit_album';

		final class tpl extends tplAlbum{};
		break;

	// Édition d'une catégorie.
	case 'edit-category' :

		// Permissions d'accès.
		if (!auth::$perms['admin']['perms']['albums_modif']
		&& !auth::$perms['admin']['perms']['albums_edit']
		&& !auth::$perms['admin']['perms']['all'])
		{
			die;
		}

		category::infos();
		if ((isset(category::$infos['cat_filemtime'])
		&& category::$infos['cat_filemtime'] !== NULL)
		|| category::$infos['cat_id'] == 1)
		{
			utils::redirect('category/1', TRUE);
		}

		category::$styles = utils::getStyles();

		category::delete();
		category::editGeneral();
		if (category::editInfosSettings($_GET['object_id'], albums::$infos))
		{
			category::infos();
		}

		albums::getMap();
		albums::parents();
		albums::parentPage();

		users::getUsersList();

		admin::$pageTitle = mb_strtolower(__('Gestion des albums'));
		admin::$tplFile = 'edit_category';

		final class tpl extends tplAlbum{};
		break;

	// Édition d'une image.
	case 'edit-image' :

		// Permissions d'accès.
		if (!auth::$perms['admin']['perms']['albums_modif']
		&& !auth::$perms['admin']['perms']['albums_edit']
		&& !auth::$perms['admin']['perms']['all'])
		{
			die;
		}

		image::infos();
		if (image::$infos === NULL)
		{
			utils::redirect('category/1');
			return;
		}

		image::delete();
		image::editGeneral();
		image::update();

		if (album::editInfos($_GET['object_id'], image::$infos))
		{
			image::infos();
		}

		albums::getMap();
		albums::parents('image');
		image::parentPage();

		tags::getAllTags('tag_id, tag_name');

		admin::$pageTitle = mb_strtolower(__('Gestion des albums'));
		admin::$tplFile = 'edit_image';

		final class tpl extends tplImage{};
		break;

	// Informations Exif.
	case 'exif' :

		// Permissions d'accès.
		if (!auth::$perms['admin']['perms']['settings_functions']
		&& !auth::$perms['admin']['perms']['all'])
		{
			die;
		}

		admin::$pageTitle = sprintf(mb_strtolower(__('Informations %s')), 'EXIF');
		admin::$tplFile = 'exif';

		utils::$config['exif_order'] = unserialize(utils::$config['exif_order']);
		utils::$config['exif_params'] = unserialize(utils::$config['exif_params']);

		admin::changeOrderParams('exif');

		final class tpl extends tplMetadata{};
		break;

	// Ajout d'images par FTP.
	case 'ftp' :

		// Permissions d'accès.
		if (!auth::$perms['admin']['perms']['ftp']
		&& !auth::$perms['admin']['perms']['all'])
		{
			die;
		}

		admin::$pageTitle = __('ajout d\'images par FTP');
		admin::$tplFile = 'ftp';

		ftp::scan();

		final class tpl extends tplFTP{};
		break;

	// Fonctionnalités.
	case 'functions' :

		// Permissions d'accès.
		if (!auth::$perms['admin']['perms']['settings_functions']
		&& !auth::$perms['admin']['perms']['all'])
		{
			die;
		}

		admin::$pageTitle = mb_strtolower(__('Fonctionnalités'));
		admin::$tplFile = 'functions';

		settings::functions();

		final class tpl extends tplFunctions{};
		break;

	// Géolocalisation d'un album.
	// Géolocalisation d'une catégorie.
	case 'geoloc-album' :
	case 'geoloc-category' :

		// Permissions d'accès.
		if (!auth::$perms['admin']['perms']['albums_edit']
		&& !auth::$perms['admin']['perms']['all'])
		{
			die;
		}

		// Interdit pour la catégorie 1.
		if ($_GET['object_id'] == 1)
		{
			utils::redirect('category/1');
			return;
		}

		category::infos();

		albums::getMap();
		albums::getPlaces();
		albums::parents();
		if (category::editInfosSettings($_GET['object_id'], albums::$infos))
		{
			category::infos();
			albums::getPlaces();
		}

		admin::$pageTitle = mb_strtolower(__('Gestion des albums'));
		admin::$tplFile = 'geoloc_category';

		final class tpl extends tplAlbums{};
		break;

	// Géolocalisation d'une image.
	case 'geoloc-image' :

		// Permissions d'accès.
		if (!auth::$perms['admin']['perms']['albums_edit']
		&& !auth::$perms['admin']['perms']['all'])
		{
			die;
		}

		image::infos();
		if (image::$infos === NULL)
		{
			utils::redirect('category/1');
			return;
		}

		if (album::editInfos($_GET['object_id'], image::$infos))
		{
			image::infos();
			albums::getPlaces();
		}

		albums::getMap();
		albums::getPlaces();
		albums::parents('image');
		image::parentPage();

		admin::$pageTitle = mb_strtolower(__('Gestion des albums'));
		admin::$tplFile = 'geoloc_image';

		final class tpl extends tplImage{};
		break;

	// Édition des groupes.
	case 'group' :

		// Permissions d'accès.
		if (auth::$infos['user_id'] != 1)
		{
			die;
		}

		admin::$pageTitle = mb_strtolower(__('Gestion des utilisateurs'));
		admin::$tplFile = 'group';

		users::getGroup();
		users::changeGroup();
		users::getCatProtect();

		final class tpl extends tplGroup{};
		break;

	// Gestion des groupes.
	case 'groups' :

		// Permissions d'accès.
		if (!auth::$perms['admin']['perms']['users_members']
		&& !auth::$perms['admin']['perms']['all'])
		{
			die;
		}

		admin::$pageTitle = mb_strtolower(__('Gestion des utilisateurs'));
		admin::$tplFile = 'groups';

		users::groupActions();
		users::getGroups();

		final class tpl extends tplGroups{};
		break;

	// Édition des images.
	case 'image' :

		// Permissions d'accès.
		if (!auth::$perms['admin']['perms']['albums_modif']
		&& !auth::$perms['admin']['perms']['all'])
		{
			die;
		}

		admin::$pageTitle = mb_strtolower(__('Gestion des albums'));
		admin::$tplFile = 'image';

		image::infos();
		if (image::$infos === NULL)
		{
			utils::redirect('category/1');
			return;
		}

		image::delete();
		image::edit();
		albums::parents('image');
		image::parentPage();

		final class tpl extends tplImage{};
		break;

	// Images en attente.
	case 'images-pending' :

		// Permissions d'accès.
		if (!auth::$perms['admin']['perms']['albums_pending']
		&& !auth::$perms['admin']['perms']['all'])
		{
			die;
		}

		admin::$pageTitle = mb_strtolower(__('Images en attente'));
		admin::$tplFile = 'images_pending';

		pending::actions();
		admin::displayOptions('pending');

		pending::getInfos();
		if (albums::$nbItems > 0)
		{
			albums::getMap();
			albums::pages();
			pending::getImages();

			pending::edit();
		}

		final class tpl extends tplImagesPending{};
		break;

	// Incidents.
	case 'incidents' :

		// Permissions d'accès.
		if (!auth::$perms['admin']['perms']['infos_incidents']
		&& !auth::$perms['admin']['perms']['all'])
		{
			die;
		}

		admin::$pageTitle = mb_strtolower(__('Incidents'));
		admin::$tplFile = 'incidents';

		admin::incidents();

		final class tpl extends tplIncidents{};
		break;

	// Informations IPTC.
	case 'iptc' :

		// Permissions d'accès.
		if (!auth::$perms['admin']['perms']['settings_functions']
		&& !auth::$perms['admin']['perms']['all'])
		{
			die;
		}

		admin::$pageTitle = sprintf(mb_strtolower(__('Informations %s')), 'IPTC');
		admin::$tplFile = 'iptc';

		utils::$config['iptc_order'] = unserialize(utils::$config['iptc_order']);
		utils::$config['iptc_params'] = unserialize(utils::$config['iptc_params']);

		admin::changeOrderParams('iptc');

		final class tpl extends tplMetadata{};
		break;

	// Logs d'activité des utilisateurs.
	case 'logs' :

		// Permissions d'accès.
		if (auth::$infos['user_id'] != 1)
		{
			die;
		}

		admin::$pageTitle = mb_strtolower(__('Activité des utilisateurs'));
		admin::$tplFile = 'logs';

		// Options d'affichage.
		admin::displayOptions('logs');

		// Recherche.
		admin::searchGetPost(logs::$searchOptions);

		// Actions sur la sélection, que l'on effectue avant
		// la récupération des informations pour générer la page.
		logs::actions();

		logs::getLogs();
		logs::getUsers();

		users::getUsersList();

		final class tpl extends tplLogs{};
		break;

	// Maintenance.
	case 'maintenance' :

		// Permissions d'accès.
		if (!auth::$perms['admin']['perms']['settings_maintenance']
		&& !auth::$perms['admin']['perms']['all'])
		{
			die;
		}

		admin::$pageTitle = mb_strtolower(__('Maintenance'));
		admin::$tplFile = 'maintenance';

		maintenance::tools();

		final class tpl extends tplMaintenance{};
		break;

	// Édition en masse des images d'une catégorie.
	case 'mass-edit-album' :
	case 'mass-edit-category' :

		// Permissions d'accès.
		if (!auth::$perms['admin']['perms']['albums_edit']
		&& !auth::$perms['admin']['perms']['all'])
		{
			die;
		}

		// Liste des utilisateurs.
		users::getUsersList();
		if (isset($_GET['user_id']) && !isset(users::$usersList[$_GET['user_id']]))
		{
			utils::redirect('mass-edit-category/1');
		}

		// Informations de la catégorie.
		category::infos();

		// Recherche.
		unset($_POST['search_query']);
		admin::searchGetPost();

		albums::getMap();
		album::reduceMap();
		albums::parents();
		if (!isset($_GET['search']) && !isset($_GET['filter']))
		{
			albums::parentPage();
		}

		albums::massEdit();
		cameras::getInfos();
		tags::getAllTags('tag_id, tag_name');

		admin::$pageTitle = mb_strtolower(__('Gestion des albums'));
		admin::$tplFile = 'mass_edit';

		final class tpl extends tplMassEdit{};
		break;

	// Nouveau groupe.
	case 'new-group' :

		// Permissions d'accès.
		if (auth::$infos['user_id'] != 1)
		{
			die;
		}

		admin::$pageTitle = mb_strtolower(__('Gestion des utilisateurs'));
		admin::$tplFile = 'new_group';

		users::newGroup();
		users::getCatProtect();

		final class tpl extends tplGroup{};
		break;

	// Nouveau groupe.
	case 'new-page' :

		// Permissions d'accès.
		if (!auth::$perms['admin']['perms']['settings_pages']
		&& !auth::$perms['admin']['perms']['all'])
		{
			die;
		}

		admin::$pageTitle = mb_strtolower(__('Gestion des pages'));
		admin::$tplFile = 'new_page';

		widgets::create('page');

		final class tpl extends tplPage{};
		break;

	// Nouvelle vignette.
	case 'new-thumb' :

		// Permissions d'accès.
		if (!auth::$perms['admin']['perms']['albums_modif']
		&& !auth::$perms['admin']['perms']['all'])
		{
			die;
		}

		// Interdit pour la catégorie 1.
		if ($_GET['object_id'] == 1)
		{
			utils::redirect('category/1');
			return;
		}

		admin::$pageTitle = mb_strtolower(__('Gestion des albums'));
		admin::$tplFile = 'new_thumb';

		category::infos();

		// Sous-plan.
		albums::getMap(TRUE, albums::$infos['cat_status'] ? 'AND cat_status = "1"' : '');
		albums::$subMapCategories = albums::$mapCategories;
		albums::getMap(FALSE, 'AND cat_a_images + cat_d_images > 0');

		albums::parents();
		albums::parentPage();
		albums::getThumbsImages();

		final class tpl extends tplNewThumb{};
		break;

	// Nouvel utilisateur.
	case 'new-user' :

		// Permissions d'accès.
		if (!auth::$perms['admin']['perms']['users_members']
		&& !auth::$perms['admin']['perms']['all'])
		{
			die;
		}

		admin::$pageTitle = mb_strtolower(__('Gestion des utilisateurs'));
		admin::$tplFile = 'new_user';

		users::getUsersGroups();
		users::newUser();

		final class tpl extends tplUserProfile{};
		break;

	// Nouveau widget.
	case 'new-widget' :

		// Permissions d'accès.
		if (!auth::$perms['admin']['perms']['settings_widgets']
		&& !auth::$perms['admin']['perms']['all'])
		{
			die;
		}

		admin::$pageTitle = mb_strtolower(__('Gestion des widgets'));
		admin::$tplFile = 'new_widget';

		widgets::create('widget');

		final class tpl extends tplWidgetPerso{};
		break;

	// Options : avancé.
	case 'options-advanced' :

		// Permissions d'accès.
		if (!auth::$perms['admin']['perms']['settings_options']
		&& !auth::$perms['admin']['perms']['all'])
		{
			die;
		}

		admin::$pageTitle = mb_strtolower(__('Options'));
		admin::$tplFile = 'options_advanced';
		
		settings::optionsAdvanced();

		final class tpl extends tplOptionsAdvanced{};
		break;

	// Options : listes noires.
	case 'options-blacklists' :

		// Permissions d'accès.
		if (!auth::$perms['admin']['perms']['settings_options']
		&& !auth::$perms['admin']['perms']['all'])
		{
			die;
		}

		admin::$pageTitle = mb_strtolower(__('Options'));
		admin::$tplFile = 'options_blacklists';

		settings::blacklists();

		final class tpl extends tplOptionsBlacklists{};
		break;

	// Options : modèles de descriptions.
	case 'options-descriptions' :

		// Permissions d'accès.
		if (!auth::$perms['admin']['perms']['settings_options']
		&& !auth::$perms['admin']['perms']['all'])
		{
			die;
		}

		admin::$pageTitle = mb_strtolower(__('Options'));
		admin::$tplFile = 'options_descriptions';

		settings::optionsDescriptions();

		final class tpl extends tplOptionsDescriptions{};
		break;

	// Options de la galerie.
	case 'options-gallery' :

		// Permissions d'accès.
		if (!auth::$perms['admin']['perms']['settings_options']
		&& !auth::$perms['admin']['perms']['all'])
		{
			die;
		}

		admin::$pageTitle = mb_strtolower(__('Options'));
		admin::$tplFile = 'options_gallery';

		settings::optionsGallery();

		final class tpl extends tplOptionsGallery{};
		break;

	// Options des images.
	case 'options-images' :

		// Permissions d'accès.
		if (!auth::$perms['admin']['perms']['settings_options']
		&& !auth::$perms['admin']['perms']['all'])
		{
			die;
		}

		admin::$pageTitle = mb_strtolower(__('Options'));
		admin::$tplFile = 'options_images';

		settings::optionsImages();

		final class tpl extends tplOptionsImages{};
		break;

	// Options de courriel.
	case 'options-email' :

		// Permissions d'accès.
		if (!auth::$perms['admin']['perms']['settings_options']
		&& !auth::$perms['admin']['perms']['all'])
		{
			die;
		}

		admin::$pageTitle = mb_strtolower(__('Options'));
		admin::$tplFile = 'options_email';

		settings::optionsEmail();

		final class tpl extends tplOptionsEmail{};
		break;

	// Options des vignettes.
	case 'options-thumbs' :

		// Permissions d'accès.
		if (!auth::$perms['admin']['perms']['settings_options']
		&& !auth::$perms['admin']['perms']['all'])
		{
			die;
		}

		admin::$pageTitle = mb_strtolower(__('Options'));
		admin::$tplFile = 'options_thumbs';

		settings::optionsThumbs();

		final class tpl extends tplOptionsThumbs{};
		break;

	// Gestion de chaque page.
	case 'page' :

		// Permissions d'accès.
		if (!auth::$perms['admin']['perms']['settings_pages']
		&& !auth::$perms['admin']['perms']['all'])
		{
			die;
		}

		admin::$pageTitle = mb_strtolower(__('Gestion des pages'));
		admin::$tplFile = 'page_' . str_replace('-', '_', $_GET['page']);

		switch ($_GET['page'])
		{
			case 'comments' :
				widgets::comments();
				final class tpl extends tplPageComments{};
				break;

			case 'contact' :
				widgets::contact();
				final class tpl extends tplPageContact{};
				break;

			case 'guestbook' :
				widgets::guestbook();
				final class tpl extends tplPageGuestbook{};
				break;

			case 'members' :
				widgets::members();
				final class tpl extends tplPageMembers{};
				break;

			case 'perso' :
				widgets::perso('pages');
				final class tpl extends tplPage{};
				break;

			case 'worldmap' :
				widgets::worldmap();
				final class tpl extends tplPageWorldmap{};
				break;
		}
		break;

	// Gestion des pages.
	case 'pages' :

		// Permissions d'accès.
		if (!auth::$perms['admin']['perms']['settings_pages']
		&& !auth::$perms['admin']['perms']['all'])
		{
			die;
		}

		admin::$pageTitle = mb_strtolower(__('Gestion des pages'));
		admin::$tplFile = 'pages';

		admin::changeOrderParams('pages');

		final class tpl extends tplPages{};
		break;

	// Tri des images.
	case 'sort-album' :

		// Permissions d'accès.
		if (!auth::$perms['admin']['perms']['albums_modif']
		&& !auth::$perms['admin']['perms']['all'])
		{
			die;
		}

		admin::$pageTitle = mb_strtolower(__('Gestion des albums'));
		admin::$tplFile = 'sort_album';

		category::infos();
		albums::sort('image');
		albums::getMap(FALSE,
			' AND 
			   CASE WHEN cat_filemtime IS NULL
				  THEN cat_a_subalbs + cat_a_subcats + cat_d_subalbs + cat_d_subcats > 0
				  ELSE cat_a_images + cat_d_images > 0
				  END'
		);
		if (albums::$infos === NULL
		|| albums::$infos['cat_images'] == 0)
		{
			utils::redirect('album/' . $_GET['object_id']);
			return;
		}
		album::getImages(TRUE);
		albums::parents();
		albums::parentPage();

		final class tpl extends tplSortAlbum{};
		break;

	// Tri des catégories.
	case 'sort-category' :

		// Permissions d'accès.
		if (!auth::$perms['admin']['perms']['albums_modif']
		&& !auth::$perms['admin']['perms']['all'])
		{
			die;
		}

		admin::$pageTitle = mb_strtolower(__('Gestion des albums'));
		admin::$tplFile = 'sort_category';

		category::infos();
		if (albums::$infos === NULL
		|| albums::$infos['cat_subs'] == 0)
		{
			utils::redirect('category/' . $_GET['object_id']);
			return;
		}
		albums::sort('cat');
		albums::getMap(FALSE,
			' AND 
			   CASE WHEN cat_filemtime IS NULL
				  THEN cat_a_subalbs + cat_a_subcats + cat_d_subalbs + cat_d_subcats > 0
				  ELSE cat_a_images + cat_d_images > 0
				  END'
		);
		category::getCategories(TRUE);
		albums::parents();
		albums::parentPage();

		final class tpl extends tplSortCategory{};
		break;


	// Statistiques des objets de la galerie.
	case 'stats-objects' :

		// Permissions d'accès.
		if (auth::$infos['user_id'] != 1)
		{
			die;
		}

		admin::$pageTitle = mb_strtolower(__('Informations galerie'));
		admin::$tplFile = 'stats_objects';

		admin::galleryStats();

		final class tpl extends tplGalleryStats{};
		break;

	// Activité des utilisateurs.
	case 'stats-users' :

		// Permissions d'accès.
		if (auth::$infos['user_id'] != 1)
		{
			die;
		}

		admin::$pageTitle = mb_strtolower(__('Informations galerie'));
		admin::$tplFile = 'stats_users';

		admin::usersStats();

		final class tpl extends tplUsersStats{};
		break;

	// Informations systèmes.
	case 'system' :

		// Permissions d'accès.
		if (auth::$infos['user_id'] != 1)
		{
			die;
		}

		admin::$pageTitle = mb_strtolower(__('Système'));
		admin::$tplFile = 'system';

		admin::system();
		utils::$config['history'] = unserialize(utils::$config['history']);

		final class tpl extends tplSystem{};
		break;

	// Gestion des tags.
	case 'tags' :

		// Permissions d'accès.
		if (!auth::$perms['admin']['perms']['tags']
		&& !auth::$perms['admin']['perms']['all'])
		{
			die;
		}

		admin::$pageTitle = mb_strtolower(__('Gestion des tags'));
		admin::$tplFile = 'tags';

		// Recherche.
		admin::searchGetPost(tags::$searchOptions);

		// Actions sur la sélection, que l'on effectue avant
		// la récupération des informations pour générer la page.
		tags::actions();

		admin::displayOptions('tags');
		tags::newTags();
		tags::getTags();
		tags::edit();

		final class tpl extends tplTags{};
		break;

	// Gestion des thèmes.
	case 'themes' :

		// Permissions d'accès.
		if (!auth::$perms['admin']['perms']['settings_themes']
		&& !auth::$perms['admin']['perms']['all'])
		{
			die;
		}

		admin::$pageTitle = mb_strtolower(__('Gestion des thèmes'));
		admin::$tplFile = 'themes';

		admin::themes();

		final class tpl extends tplThemes{};
		break;

	// Modification de la vignette des catégories.
	case 'thumb-album' :
	case 'thumb-category' :

		// Permissions d'accès.
		if (!auth::$perms['admin']['perms']['albums_modif']
		&& !auth::$perms['admin']['perms']['all'])
		{
			die;
		}

		// Interdit pour la catégorie 1.
		if ($_GET['object_id'] == 1)
		{
			utils::redirect('category/1');
			return;
		}

		admin::$pageTitle = mb_strtolower(__('Gestion des albums'));
		admin::$tplFile = 'thumb_category';

		// Récupération des informations pour générer la page courante.
		category::infos();
		if (albums::$infos === NULL
		|| albums::$infos['cat_images'] == 0)
		{
			$type = (albums::$infos['cat_filemtime'] === NULL)
				? 'category'
				: 'album';
			utils::redirect($type . '/' . $_GET['object_id']);
			return;
		}

		albums::thumbChange('cat');
		albums::thumbPreview('cat');

		albums::getMap(FALSE, 'AND cat_a_images + cat_d_images > 0');
		albums::parents();
		albums::parentPage();

		final class tpl extends tplThumbCategory{};
		break;

	// Modification de la vignette des images.
	case 'thumb-image' :

		// Permissions d'accès.
		if (!auth::$perms['admin']['perms']['albums_modif']
		&& !auth::$perms['admin']['perms']['all'])
		{
			die;
		}

		admin::$pageTitle = mb_strtolower(__('Gestion des albums'));
		admin::$tplFile = 'thumb_image';

		image::infos();
		if (albums::$infos === NULL)
		{
			utils::redirect('category/1');
			return;
		}
		albums::thumbChange('img');
		albums::thumbPreview('img');
		albums::parents('image');
		image::parentPage();

		final class tpl extends tplThumbImage{};
		break;

	// Édition du profil d'un utilisateur.
	case 'user' :

		// Permissions d'accès.
		if (auth::$infos['user_id'] != $_GET['object_id']
		&& !auth::$perms['admin']['perms']['users_members']
		&& !auth::$perms['admin']['perms']['all'])
		{
			die;
		}

		// Impossible d'accéder à l'utilisateur 'guest'.
		if ($_GET['object_id'] == 2)
		{
			utils::redirect('users', TRUE);
		}

		// Recherche.
		admin::searchGetPost(users::$searchOptions);

		admin::$pageTitle = mb_strtolower(__('Gestion des utilisateurs'));
		admin::$tplFile = 'user';

		users::getUsersGroups();
		users::getUser();

		users::deleteUser();

		users::changeProfile();
		users::parentPage();

		final class tpl extends tplUserProfile{};
		break;

	// Édition de l'avatar de l'utilisateur.
	case 'user-avatar' :

		// Permissions d'accès.
		if (auth::$infos['user_id'] != $_GET['object_id']
		&& !auth::$perms['admin']['perms']['users_members']
		&& !auth::$perms['admin']['perms']['all'])
		{
			die;
		}

		admin::$pageTitle = mb_strtolower(__('Gestion des utilisateurs'));
		admin::$tplFile = 'user_avatar';

		users::getUser();
		users::avatar();
		users::parentPage();

		final class tpl extends tplUserProfile{};
		break;

	// Édition du filigrane de l'utilisateur.
	case 'user-watermark' :

		// Permissions d'accès.
		if (auth::$infos['user_id'] != $_GET['object_id']
		&& !auth::$perms['admin']['perms']['users_members']
		&& !auth::$perms['admin']['perms']['all'])
		{
			die;
		}

		admin::$pageTitle = mb_strtolower(__('Gestion des utilisateurs'));
		admin::$tplFile = 'user_watermark';

		users::getUser();
		users::watermark();
		users::parentPage();

		final class tpl extends tplUser{};
		break;

	// Envoi d'un courriel à l'utilisateur.
	case 'user-sendmail' :

		// Permissions d'accès.
		if (auth::$infos['user_id'] != $_GET['object_id']
		&& !auth::$perms['admin']['perms']['users_members']
		&& !auth::$perms['admin']['perms']['all'])
		{
			die;
		}

		admin::$pageTitle = mb_strtolower(__('Gestion des utilisateurs'));
		admin::$tplFile = 'user_sendmail';

		users::getUser();
		users::sendmailUser();
		users::parentPage();

		final class tpl extends tplUserSendmail{};
		break;

	// Gestion des utilisateurs.
	case 'users' :

		// Permissions d'accès.
		if (!auth::$perms['admin']['perms']['users_members']
		&& !auth::$perms['admin']['perms']['all'])
		{
			die;
		}

		// Recherche.
		admin::searchGetPost(users::$searchOptions);

		admin::$pageTitle = mb_strtolower(__('Gestion des utilisateurs'));
		admin::$tplFile = 'users';

		users::usersActions();
		admin::displayOptions('users');

		users::getUsers();
		users::getUsersGroups();

		final class tpl extends tplUsers{};
		break;

	// Options pour les utilisateurs.
	case 'users-options' :

		// Permissions d'accès.
		if (!auth::$perms['admin']['perms']['users_options']
		&& !auth::$perms['admin']['perms']['all'])
		{
			die;
		}

		users::options();
		albums::getMap(FALSE, 'AND cat_filemtime IS NULL');

		admin::$pageTitle = mb_strtolower(__('Gestion des utilisateurs'));
		admin::$tplFile = 'users_options';

		final class tpl extends tplUsersOptions{};
		break;

	// Envoi d'un mail à des utilisateurs.
	case 'users-sendmail' :

		// Permissions d'accès.
		if (!auth::$perms['admin']['perms']['users_members']
		&& !auth::$perms['admin']['perms']['all'])
		{
			die;
		}

		users::sendmail();

		admin::$pageTitle = mb_strtolower(__('Gestion des utilisateurs'));
		admin::$tplFile = 'users_sendmail';

		final class tpl extends tplUsersSendmail{};
		break;

	// Gestion des votes.
	case 'votes' :

		// Permissions d'accès.
		if (!auth::$perms['admin']['perms']['admin_votes']
		&& !auth::$perms['admin']['perms']['all'])
		{
			die;
		}

		// Liste des utilisateurs.
		users::getUsersList();
		if (isset($_GET['user_id']) && !isset(users::$usersList[$_GET['user_id']]))
		{
			utils::redirect('votes');
		}

		// Recherche.
		admin::searchGetPost(votes::$searchOptions);

		admin::$pageTitle = mb_strtolower(__('Gestion des votes'));
		admin::$tplFile = 'votes';

		// Actions sur la sélection, que l'on effectue avant
		// la récupération des informations pour générer la page.
		votes::actions();

		admin::displayOptions('votes');
		votes::getVotes();
		votes::getImages();

		// Liste des catégories.
		albums::getMap(FALSE);
		votes::reduceMap();

		// Récupérations des informations des catégories parentes.
		albums::$infos =& votes::$objectInfos;
		if (isset($_GET['object_type']) && $_GET['object_type'] == 'image')
		{
			albums::$infos['cat_path'] .= '/1';
		}
		albums::parents();

		final class tpl extends tplVotes{};
		break;

	// Options du filigrane.
	case 'watermark' :

		// Permissions d'accès.
		if (!auth::$perms['admin']['perms']['settings_functions']
		&& !auth::$perms['admin']['perms']['all'])
		{
			die;
		}

		admin::$pageTitle = mb_strtolower(__('Filigrane'));
		admin::$tplFile = 'watermark';

		admin::watermark();

		final class tpl extends tplAdmin{};
		break;

	// Filigrane de la catégorie.
	case 'watermark-album' :
	case 'watermark-category' :

		// Permissions d'accès.
		if (!auth::$perms['admin']['perms']['albums_modif']
		&& !auth::$perms['admin']['perms']['all'])
		{
			die;
		}

		admin::$pageTitle = mb_strtolower(__('Gestion des albums'));
		admin::$tplFile = 'watermark_category';

		category::infos();
		category::watermark();

		albums::getMap();
		albums::parents();

		final class tpl extends tplAlbums{};
		break;

	// Gestion de chaque widget.
	case 'widget' :

		// Permissions d'accès.
		if (!auth::$perms['admin']['perms']['settings_widgets']
		&& !auth::$perms['admin']['perms']['all'])
		{
			die;
		}

		admin::$pageTitle = mb_strtolower(__('Gestion des widgets'));
		admin::$tplFile = 'widget_' . str_replace('-', '_', $_GET['widget']);

		switch ($_GET['widget'])
		{
			case 'geoloc' :
				widgets::geoloc();
				final class tpl extends tplWidgetGeoloc{};
				break;

			case 'image' :
				widgets::imagesBloc();
				final class tpl extends tplWidgetImage{};
				break;

			case 'links' :
				widgets::links();
				final class tpl extends tplWidgetLinks{};
				break;

			case 'navigation' :
				widgets::navigation();
				final class tpl extends tplWidgetNavigation{};
				break;

			case 'online-users' :
				widgets::onlineUsers();
				final class tpl extends tplWidgetOnlineUsers{};
				break;

			case 'options' :
				widgets::options();
				final class tpl extends tplWidgetOptions{};
				break;

			case 'perso' :
				widgets::perso('widgets');
				final class tpl extends tplWidgetPerso{};
				break;

			case 'stats-categories' :
				widgets::statsCategories();
				final class tpl extends tplWidgetStatsCategories{};
				break;

			case 'stats-images' :
				widgets::statsImages();
				final class tpl extends tplWidgetStatsImages{};
				break;

			case 'tags' :
				widgets::tags();
				final class tpl extends tplWidgetTags{};
				break;

			case 'user' :
				widgets::user();
				final class tpl extends tplWidgetUser{};
				break;
		}
		break;

	// Gestion des widgets.
	case 'widgets' :

		// Permissions d'accès.
		if (!auth::$perms['admin']['perms']['settings_widgets']
		&& !auth::$perms['admin']['perms']['all'])
		{
			die;
		}

		admin::$pageTitle = mb_strtolower(__('Gestion des widgets'));
		admin::$tplFile = 'widgets';

		admin::changeOrderParams('widgets');

		final class tpl extends tplWidgets{};
		break;

	// Informations XMP.
	case 'xmp' :

		// Permissions d'accès.
		if (!auth::$perms['admin']['perms']['settings_functions']
		&& !auth::$perms['admin']['perms']['all'])
		{
			die;
		}

		admin::$pageTitle = sprintf(mb_strtolower(__('Informations %s')), 'XMP');
		admin::$tplFile = 'xmp';

		utils::$config['xmp_order'] = unserialize(utils::$config['xmp_order']);
		utils::$config['xmp_params'] = unserialize(utils::$config['xmp_params']);

		admin::changeOrderParams('xmp');

		final class tpl extends tplMetadata{};
		break;
}

// Fermeture de la connexion.
if (is_object(utils::$db))
{
	utils::$db->connexion = NULL;
}

// Création de l'objet de template.
$tpl = new tpl();

// Écriture des données du cookie des préférences.
utils::$cookiePrefs->write();

// Chargement du template.
require_once(GALLERY_ROOT . utils::$purlDir . '/template/'
	. utils::filters(utils::$config['admin_template'], 'dir') . '/index.tpl.php');



/**
 * Opérations concernant toute l'administration.
 */
class admin
{
	/**
	 * Nom du champ de formulaire pour lequel il y a une erreur.
	 *
	 * @var string
	 */
	public static $fieldError;

	/**
	 * Informations diverses à destination du template.
	 *
	 * @var array
	 */
	public static $infos = array();

	/**
	 * Nom de la page courante.
	 *
	 * @var string
	 */
	public static $pageTitle;

	/**
	 * La recherche a-t-elle pu être initiée ?
	 *
	 * @var boolean
	 */
	public static $searchInit = FALSE;

	/**
	 * Contenu du paramètre GET "q", mais sans le numéro de page.
	 *
	 * @var string
	 */
	public static $sectionRequest;

	/**
	 * Modification de la config par le template
	 * (désactivation de certaines fonctionnalités).
	 *
	 * @var string
	 */
	public static $tplDisabledConfig;

	/**
	 * Nom du fichier de template à inclure.
	 *
	 * @var string
	 */
	public static $tplFile;

	/**
	 * Rapport détaillé des mises à jour.
	 *
	 * @var array
	 */
	public static $report = array();

	/**
	 * Date et heure  de la requête
	 * (le moment où l'utilisateur demande une page).
	 *
	 * @var integer
	 */
	public static $time;

	/**
	 * Paramètres de filigrane.
	 *
	 * @var array
	 */
	public static $watermarkParams;



	/**
	 * Partie de la clause WHERE pour la recherche.
	 *
	 * @var boolean|array
	 */
	protected static $_sqlSearch;



	/**
	 * Modification de l'ordre et de paramètres des objets de type "widgets".
	 *
	 * @return void
	 */
	public static function changeOrderParams($type)
	{
		// Quelques vérifications
		if (empty($_POST)
		|| !isset($_POST['w']) || !is_array($_POST['w'])
		|| !isset($_POST['serial']) || !is_string($_POST['serial'])
		|| !preg_match('`^(?:i\[\]=\d{1,2}&)*(?:i\[\]=\d{1,2})$`', $_POST['serial']))
		{
			return;
		}

		// On convertit en tableau les nouvelles positions.
		$new_positions = str_replace('i[]=', '', $_POST['serial']);
		$new_positions = explode('&', $new_positions);

		// On met à jour les positions.
		if (count($new_positions) == count(utils::$config[$type . '_order'])
		&& $new_positions != utils::$config[$type . '_order'])
		{
			$w_order = array();
			foreach ($new_positions as $current_pos)
			{
				$w_order[] = utils::$config[$type . '_order'][$current_pos];
			}
		}

		// Paramètres, statuts et suppression.
		$w_order = (empty($w_order))
			? utils::$config[$type . '_order']
			: $w_order;
		$w_params = utils::$config[$type . '_params'];
		foreach ($w_params as $name => &$p)
		{
			// Suppression (uniquement pour objets personnalisés).
			if ($type == 'widgets' || $type == 'pages')
			{
				if (substr($name, 0, 6) == 'perso_'
				&& isset($_POST['w'][$name]['delete']))
				{
					unset($w_order[array_search($name, $w_order)]);
					unset($w_params[$name]);
					continue;
				}
			}

			// Format.
			if ($type == 'exif')
			{
				if (isset($_POST['w'][$name]['format']) && isset($p['format'])
				&& $_POST['w'][$name]['format'] != $p['format'])
				{
					$p['format'] = $_POST['w'][$name]['format'];
				}
			}

			// Activation.
			if (isset($_POST['w'][$name]['activate']) && !$p['status'])
			{
				$p['status'] = 1;
			}

			// Désactivation.
			if (!isset($_POST['w'][$name]['activate']) && $p['status'])
			{
				$p['status'] = 0;
			}
		}

		// Si modification des paramètres, on met à jour la configuration.
		$columns = array();
		$params = array();
		if ($w_order != utils::$config[$type . '_order'])
		{
			$columns[] = '"' . $type . '_order" THEN :' . $type . '_order';
			$params[$type . '_order'] = serialize(array_values($w_order));
		}
		if ($w_params != utils::$config[$type . '_params'])
		{
			$columns[] = '"' . $type . '_params" THEN :' . $type . '_params';
			$params[$type . '_params'] = serialize($w_params);
		}

		if (empty($columns))
		{
			return;
		}

		// On effectue la mise à jour des paramètres.
		$sql = 'UPDATE ' . CONF_DB_PREF . 'config
				   SET conf_value = CASE conf_name
					   WHEN ' . implode(' WHEN ', $columns) . '
					   ELSE conf_value END';
		if (utils::$db->prepare($sql) === FALSE
		|| utils::$db->executeExec($params) === FALSE)
		{
			self::report('error:' . utils::$db->msgError);
			return;
		}

		// Mise à jour du tableau de configuration.
		foreach ($params as $name => &$value)
		{
			utils::$config[$name] = unserialize($value);
		}

		// Mise à jour réussie.
		self::report('success:' . __('Modifications enregistrées.'));
	}

	/**
	 * Controleur de la section 'album'.
	 *
	 * @return void
	 */
	public static function controlAlbum()
	{
		// Liste des utilisateurs.
		users::getUsersList();
		if (isset($_GET['user_id']) && !isset(users::$usersList[$_GET['user_id']]))
		{
			utils::redirect('category/1');
		}

		// Actions sur la sélection, que l'on effectue avant
		// la récupération des informations pour générer la page.
		album::actions();

		admin::displayOptions('album');

		// Récupération des informations pour générer la page courante.
		category::infos();
		albums::getMap();

		// Si l'objet n'existe pas ou que ce n'est pas un album, on redirige.
		if (album::$infos === NULL)
		{
			utils::redirect('category/1', TRUE);
		}

		// Ajout d'images.
		if (!isset($_GET['search']))
		{
			album::upload();
		}

		albums::pages();
		album::getImages();
		album::reduceMap();
		if (count(albums::$items) === 0)
		{
			albums::$nbPages = 0;
			albums::$nbItems = 0;
		}

		albums::parents();
		if (!isset($_GET['search']) && !isset($_GET['filter']))
		{
			albums::parentPage();
		}

		// Édition des images, que l'on effectue après
		// la récupération des informations pour générer la page,
		// mais avant la récupération de la liste des tags.
		album::edit();

		cameras::getInfos();
		tags::getAllTags('tag_id, tag_name');

		if (is_array(albums::$items))
		{
			reset(albums::$items);
		}

		admin::$tplFile = 'album';
	}

	/**
	 * Controleur de la section 'category'.
	 *
	 * @return void
	 */
	public static function controlCategory()
	{
		// Liste des utilisateurs.
		users::getUsersList();
		if (isset($_GET['user_id']) && !isset(users::$usersList[$_GET['user_id']]))
		{
			utils::redirect('category/1');
		}

		// Actions sur la sélection, que l'on effectue avant
		// la récupération des informations pour générer la page.
		category::actions();

		admin::displayOptions('category');

		category::infos();

		// Nouvelle catégorie.
		if (!isset($_GET['search']))
		{
			category::newCategory();
		}

		// Si l'objet n'existe pas, on redirige.
		if (album::$infos === NULL && $_GET['object_id'] > 1)
		{
			utils::redirect('category/1', TRUE);
		}

		albums::getMap();

		albums::pages();

		category::getCategories();
		category::reduceMap();
		if (count(albums::$items) === 0)
		{
			albums::$nbPages = 0;
			albums::$nbItems = 0;
		}
		category::$styles = utils::getStyles();

		category::stats();

		// Édition des catégories, que l'on effectue après
		// la récupération des informations pour générer la page.
		category::edit();

		category::parentsSettings();

		albums::parents();
		albums::parentPage();

		if (is_array(albums::$items))
		{
			reset(albums::$items);
		}

		admin::$tplFile = 'category';
	}

	/**
	 * Récupération des informations utiles pour le tableau de bord.
	 *
	 * @return void
	 */
	public static function dashboard()
	{
		// Version de MySQL.
		if (auth::$infos['user_id'] == 1)
		{
			self::$infos['mysql_version'] = system::getMySQLVersion();
		}

		// Nombre d'incidents.
		if (utils::$config['admin_dashboard_errors']
			&& (auth::$perms['admin']['perms']['infos_incidents']
			 || auth::$perms['admin']['perms']['all']))
		{
			self::$infos['errors'] = count(glob(GALLERY_ROOT . '/errors/*.xml'));
		}

		// Permissions d'accès aux albums de la galerie.
		$sql_admin_albums_access = sql::albumsAccess(auth::$perms['admin']);
		$sql_gallery_albums_access = sql::albumsAccess(auth::$perms['gallery']);

		// Nombre d'images et nombre de visites.
		$sql = 'SELECT COUNT(*) AS nb_images,
					   SUM(image_hits) AS nb_hits
				  FROM ' . CONF_DB_PREF . 'images AS img,
					   ' . CONF_DB_PREF . 'categories AS cat
				 WHERE img.cat_id = cat.cat_id
				   AND image_status = "1"
					   ' . $sql_gallery_albums_access;
		self::$infos = array_merge(
			self::$infos,
			utils::$db->query($sql, 'row') !== FALSE
				? utils::$db->queryResult
				: array('nb_images' => '?', 'nb_hits' => '?')
		);

		// Nombre d'albums.
		$sql = 'SELECT COUNT(*)
				  FROM ' . CONF_DB_PREF . 'categories
				 WHERE cat_status = "1"
				   AND cat_filemtime IS NOT NULL
					   ' . $sql_gallery_albums_access;
		self::$infos['nb_albums'] = (utils::$db->query($sql, 'value') !== FALSE)
			? utils::$db->queryResult
			: '?';

		// Nombre de commentaires.
		if (utils::$config['comments'] && !isset(self::$tplDisabledConfig['comments']))
		{
			$sql = 'SELECT COUNT(*)
					  FROM ' . CONF_DB_PREF . 'comments AS com,
						   ' . CONF_DB_PREF . 'images AS img,
						   ' . CONF_DB_PREF . 'categories AS cat
					 WHERE com.image_id = img.image_id
					   AND img.cat_id = cat.cat_id
					   AND com_status = "1"
					   AND image_status = "1"
						   ' . $sql_gallery_albums_access;
			self::$infos['nb_comments'] = (utils::$db->query($sql, 'value') !== FALSE)
				? utils::$db->queryResult
				: '?';
		}

		// Nombre de tags.
		if (utils::$config['tags'] && !isset(self::$tplDisabledConfig['tags']))
		{
			$sql = 'SELECT DISTINCT t.tag_id
					  FROM ' . CONF_DB_PREF . 'tags AS t
				 LEFT JOIN ' . CONF_DB_PREF . 'tags_images AS ti
						ON t.tag_id = ti.tag_id
				 LEFT JOIN ' . CONF_DB_PREF . 'images AS img
						ON ti.image_id = img.image_id
				 LEFT JOIN ' . CONF_DB_PREF . 'categories AS cat
						ON img.cat_id = cat.cat_id
				     WHERE image_status = "1"
						   ' . $sql_gallery_albums_access;
			self::$infos['nb_tags'] = (utils::$db->query($sql) !== FALSE)
				? count(utils::$db->queryResult)
				: '?';
		}

		// Nombre de votes.
		if (utils::$config['votes'] && !isset(self::$tplDisabledConfig['votes']))
		{
			$sql = 'SELECT COUNT(*)
					  FROM ' . CONF_DB_PREF . 'votes AS v,
						   ' . CONF_DB_PREF . 'images AS img,
						   ' . CONF_DB_PREF . 'categories AS cat
					 WHERE v.image_id = img.image_id
					   AND img.cat_id = cat.cat_id
					   AND image_status = "1"
						   ' . $sql_gallery_albums_access;
			self::$infos['nb_votes'] = (utils::$db->query($sql, 'value') !== FALSE)
				? utils::$db->queryResult
				: '?';
		}

		if (utils::$config['users'] && !isset(self::$tplDisabledConfig['users']))
		{
			// Nombre de membres.
			$sql = 'SELECT COUNT(*)
					  FROM ' . CONF_DB_PREF . 'users AS u,
					       ' . CONF_DB_PREF . 'groups AS g
					 WHERE u.group_id = g.group_id
					   AND group_admin != "1"
					   AND user_status = "1"
					   AND user_id != 2';
			self::$infos['nb_members'] = (utils::$db->query($sql, 'value') !== FALSE)
				? utils::$db->queryResult
				: '?';

			// Nombre de favoris.
			$sql = 'SELECT COUNT(*)
					  FROM ' . CONF_DB_PREF . 'favorites AS fav
				 LEFT JOIN ' . CONF_DB_PREF . 'users AS u
						ON fav.user_id = u.user_id
				 LEFT JOIN ' . CONF_DB_PREF . 'images AS img
						ON fav.image_id = img.image_id
				 LEFT JOIN ' . CONF_DB_PREF . 'categories AS cat
						ON img.cat_id = cat.cat_id
					 WHERE image_status = "1"
					   AND user_status = "1"
					   ' . $sql_gallery_albums_access;
			self::$infos['nb_favorites'] = (utils::$db->query($sql, 'value') !== FALSE)
				? utils::$db->queryResult
				: '?';
		}

		// Nombre d'admins.
		$sql = 'SELECT COUNT(*)
				  FROM ' . CONF_DB_PREF . 'users AS u,
				       ' . CONF_DB_PREF . 'groups AS g
				 WHERE u.group_id = g.group_id
				   AND group_admin = "1"
				   AND user_status = "1"';
		self::$infos['nb_admins'] = (utils::$db->query($sql, 'value') !== FALSE)
			? utils::$db->queryResult
			: '?';

		// Nombre de groupes.
		$sql = 'SELECT COUNT(*)
				  FROM ' . CONF_DB_PREF . 'groups';
		self::$infos['nb_groups'] = (utils::$db->query($sql, 'value') !== FALSE)
			? utils::$db->queryResult
			: '?';

		// Dernières images.
		$sql = 'SELECT image_id,
					   image_adddt,
					   image_width,
					   image_height,
					   image_name,
					   image_path,
					   image_url,
					   image_tb_infos AS tb_infos,
					   cat.cat_id,
					   cat.cat_name,
					   cat.cat_url,
					   cat.cat_password,
					   u.user_id,
					   u.user_login
				  FROM ' . CONF_DB_PREF . 'images AS img
			 LEFT JOIN ' . CONF_DB_PREF . 'users AS u
					ON img.user_id = u.user_id
			 LEFT JOIN ' . CONF_DB_PREF . 'categories AS cat
					ON img.cat_id = cat.cat_id
				 WHERE image_status = "1"
				   ' . $sql_gallery_albums_access . '
			  ORDER BY image_id DESC
				 LIMIT 10';
		$fetch_style = array('fetch' => PDO::FETCH_ASSOC, 'column' => 'image_id');
		utils::$db->query($sql, $fetch_style);
		self::$infos['lastimages'] = utils::$db->queryResult;

		$sql_comments = array();
		if (utils::$config['comments']
		&& !isset(self::$tplDisabledConfig['comments']))
		{
			$sql_comments[] =
				   'SELECT com_id,
						   com_crtdt,
						   com_message,
						   CASE WHEN com.user_id = 2
							  THEN com_author
							  ELSE user_login
							  END AS author,
						   CASE WHEN com.user_id = 2
							  THEN 0
							  ELSE user_avatar
							  END AS avatar,
						   img.image_id,
						   img.image_name,
						   img.image_url,
						   cat.cat_id,
						   cat.cat_name,
						   cat.cat_url,
						   cat.cat_password,
						   com.user_id
					  FROM ' . CONF_DB_PREF . 'comments AS com
				 LEFT JOIN ' . CONF_DB_PREF . 'users AS u
						ON com.user_id = u.user_id
				 LEFT JOIN ' . CONF_DB_PREF . 'images AS img
						ON com.image_id = img.image_id
				 LEFT JOIN ' . CONF_DB_PREF . 'categories AS cat
						ON img.cat_id = cat.cat_id
					 WHERE com_status = "1"
					   AND image_status = "1"
					   ' . $sql_gallery_albums_access;
		}
		if (utils::$config['pages_params']['guestbook']['status']
		&& !isset(self::$tplDisabledConfig['guestbook']))
		{
			$sql_comments[] =
				   'SELECT guestbook_id AS com_id,
						   guestbook_crtdt AS com_crtdt,
						   guestbook_message AS com_message,
						   CASE WHEN com.user_id = 2
							  THEN guestbook_author
							  ELSE user_login
							  END AS author,
						   CASE WHEN com.user_id = 2
							  THEN 0
							  ELSE user_avatar
							  END AS avatar,
						   0 AS image_id,
						   0 AS image_name,
						   0 AS image_url,
						   "guestbook" AS cat_id,
						   0 AS cat_name,
						   0 AS cat_url,
						   0 AS cat_password,
						   com.user_id
					  FROM ' . CONF_DB_PREF . 'guestbook AS com
				 LEFT JOIN ' . CONF_DB_PREF . 'users AS u
						ON com.user_id = u.user_id
					 WHERE guestbook_status = "1"';
		}
		if (count($sql_comments) && (auth::$perms['admin']['perms']['comments_edit']
		|| auth::$perms['admin']['perms']['all']))
		{
			$sql = implode(' UNION ', $sql_comments) . '
				 ORDER BY com_crtdt DESC
				    LIMIT 10';
			utils::$db->query($sql, PDO::FETCH_ASSOC);
			self::$infos['lastcomments'] = utils::$db->queryResult;

			// Smilies pour les commentaires.
			admin::$infos['smilies'] = FALSE;
			$smilies_file = GALLERY_ROOT . '/images/smilies/'
					. utils::$config['comments_smilies_icons_pack'] . '/icons.php';
			if (utils::$config['comments_smilies'] && file_exists($smilies_file))
			{
				$smilies = array();
				include_once($smilies_file);
				admin::$infos['smilies'] = $smilies;
			}
		}

		if (utils::$config['users'] && !isset(self::$tplDisabledConfig['users']))
		{
			// Derniers utilisateurs.
			$sql = 'SELECT user_id,
						   user_login,
						   user_avatar,
						   user_crtdt,
						   group_admin
					  FROM ' . CONF_DB_PREF . 'users
				 LEFT JOIN ' . CONF_DB_PREF . 'groups USING (group_id)
					 WHERE user_status = "1"
					   AND user_id NOT IN (1, 2)
				  ORDER BY user_id DESC
					 LIMIT 10';
			$fetch_style = array('fetch' => PDO::FETCH_ASSOC, 'column' => 'user_id');
			utils::$db->query($sql, $fetch_style);
			self::$infos['lastusers'] = utils::$db->queryResult;

			// Nombre d'images en attente de validation.
			if (auth::$perms['admin']['perms']['albums_pending']
			 || auth::$perms['admin']['perms']['all'])
			{
				$sql = 'SELECT COUNT(*)
						  FROM ' . CONF_DB_PREF . 'uploads AS up,
						       ' . CONF_DB_PREF . 'categories AS cat
						 WHERE up.cat_id = cat.cat_id
						   ' . $sql_admin_albums_access;
				utils::$db->query($sql, 'value');
				self::$infos['nb_images_pending'] = utils::$db->queryResult;
			}

			// Nombre d'utilisateurs en attente de validation.
			if (auth::$perms['admin']['perms']['users_members']
			 || auth::$perms['admin']['perms']['all'])
			{
				$sql = 'SELECT COUNT(*)
						  FROM ' . CONF_DB_PREF . 'users
						 WHERE user_status = "-1"';
				utils::$db->query($sql, 'value');
				self::$infos['nb_users_pending'] = utils::$db->queryResult;
			}
		}

		// Nombre de commentaires sur des images en attente de validation.
		if (utils::$config['comments']
		&& !isset(self::$tplDisabledConfig['comments'])
		&& (auth::$perms['admin']['perms']['comments_edit']
		 || auth::$perms['admin']['perms']['all']))
		{
			$sql = 'SELECT COUNT(*)
					  FROM ' . CONF_DB_PREF . 'comments AS com,
						   ' . CONF_DB_PREF . 'images AS img,
						   ' . CONF_DB_PREF . 'categories AS cat
					 WHERE com.image_id = img.image_id
					   AND img.cat_id = cat.cat_id
					   AND com_status = "-1"
					   ' . $sql_admin_albums_access;
			utils::$db->query($sql, 'value');
			self::$infos['nb_comments_pending'] = utils::$db->queryResult;
		}

		// Nombre de commentaires dans le livre d'or en attente de validation.
		if (utils::$config['pages_params']['guestbook']['status']
		&& !isset(self::$tplDisabledConfig['guestbook'])
		&& (auth::$perms['admin']['perms']['comments_edit']
		 || auth::$perms['admin']['perms']['all']))
		{
			$sql = 'SELECT COUNT(*)
					  FROM ' . CONF_DB_PREF . 'guestbook
					 WHERE guestbook_status = "-1"';
			utils::$db->query($sql, 'value');
			self::$infos['nb_guestbook_pending'] = utils::$db->queryResult;
		}

		// Cacher le message de démarrage.
		if (isset($_POST['start_message_hide'])
		&& utils::$config['admin_dashboard_start_message'] != 0)
		{
			$sql = 'UPDATE ' . CONF_DB_PREF . 'config
					   SET conf_value = "0"
					 WHERE conf_name = "admin_dashboard_start_message"
					 LIMIT 1';
			utils::$db->exec($sql) === FALSE;
			utils::$config['admin_dashboard_start_message'] = 0;
		}
	}

	/**
	 * Modifie les options d'affichage pour les objets.
	 *
	 * @param string $sql
	 * @return void
	 */
	public static function displayOptions($section)
	{
		if (empty($_POST) || !isset($_POST['options']))
		{
			return;
		}

		$change = FALSE;

		// Nombre d'objets par page.
		if (isset($_POST['nb_per_page']))
		{
			if (preg_match('`^\d{1,3}$`', $_POST['nb_per_page'])
			&& $_POST['nb_per_page'] > 0
			&& $_POST['nb_per_page'] != auth::$infos['user_prefs'][$section]['nb_per_page'])
			{
				auth::$infos['user_prefs'][$section]['nb_per_page'] = $_POST['nb_per_page'];
				$change = TRUE;
			}
		}

		// Critère de tri.
		if (isset($_POST['sortby']))
		{
			$p = array(
				'adddt', 'crtdt', 'date', 'lastvstdt', 'lastupddt', 'login',
				'name','nb_images', 'path', 'position', 'rate'
			);
			if (in_array($_POST['sortby'], $p)
			&& (!isset(auth::$infos['user_prefs'][$section]['sortby']) ||
			$_POST['sortby'] != auth::$infos['user_prefs'][$section]['sortby']))
			{
				auth::$infos['user_prefs'][$section]['sortby'] = $_POST['sortby'];
				$change = TRUE;
			}
		}

		// Ordre de tri.
		if (isset($_POST['orderby']))
		{
			if (in_array($_POST['orderby'], array('ASC', 'DESC'))
			&& (!isset(auth::$infos['user_prefs'][$section]['orderby']) ||
			$_POST['orderby'] != auth::$infos['user_prefs'][$section]['orderby']))
			{
				auth::$infos['user_prefs'][$section]['orderby'] = $_POST['orderby'];
				$change = TRUE;
			}
		}

		if (!$change)
		{
			return;
		}

		// On met à jour les préférences de l'utilisateur.
		$sql = 'UPDATE ' . CONF_DB_PREF . 'users
				   SET user_prefs = :user_prefs
				 WHERE user_id = ' . (int) auth::$infos['user_id'];
		$params = array(
			'user_prefs' => serialize(auth::$infos['user_prefs'])
		);
		utils::$db->prepare($sql);
		utils::$db->executeExec($params);
	}

	/**
	 * Récupération des statistiques détaillées des objets de la galerie.
	 *
	 * @return void
	 */
	public static function galleryStats()
	{
		// Permissions d'accès aux albums de la galerie.
		$sql_albums_access = sql::albumsAccess(auth::$perms['gallery']);

		self::$infos = array(
			'activate' => array(),
			'deactivate' => array(),
			'protected' => array(),
			'pending' => array(),
			'total' => array()
		);

		// Nombre d'images, nombre de visites et poids total activés.
		$sql = 'SELECT COUNT(DISTINCT(i.image_id))
				  FROM ' . CONF_DB_PREF . 'images AS i,
					   ' . CONF_DB_PREF . 'categories AS c
				 WHERE i.cat_id = c.cat_id
				   AND image_status = "1"
				   AND image_hits > 0
					   ' . $sql_albums_access;
		$sql = 'SELECT COUNT(*) AS nb_images,
					   SUM(image_hits) AS nb_hits,
					   (' . $sql . ') AS nb_images_hits,
					   SUM(image_filesize) AS filesize_total
				  FROM ' . CONF_DB_PREF . 'images AS img,
					   ' . CONF_DB_PREF . 'categories AS cat
				 WHERE img.cat_id = cat.cat_id
				   AND image_status = "1"
					   ' . $sql_albums_access;
		self::$infos['activate'] = array_merge(
			self::$infos['activate'],
			utils::$db->query($sql, 'row') !== FALSE
				? utils::$db->queryResult
				: array('nb_images' => '?', 'nb_hits' => '?', 'filesize_total' => '?')
		);

		// Nombre d'images, nombre de visites et poids total désactivés.
		$sql = 'SELECT COUNT(DISTINCT(i.image_id))
				  FROM ' . CONF_DB_PREF . 'images AS i,
					   ' . CONF_DB_PREF . 'categories AS c
				 WHERE i.cat_id = c.cat_id
				   AND image_status = "0"
				   AND image_hits > 0
					   ' . $sql_albums_access;
		$sql = 'SELECT COUNT(*) AS nb_images,
					   SUM(image_hits) AS nb_hits,
					   (' . $sql . ') AS nb_images_hits,
					   SUM(image_filesize) AS filesize_total
				  FROM ' . CONF_DB_PREF . 'images AS img,
					   ' . CONF_DB_PREF . 'categories AS cat
				 WHERE img.cat_id = cat.cat_id
				   AND image_status = "0"
					   ' . $sql_albums_access;
		self::$infos['deactivate'] = array_merge(
			self::$infos['deactivate'],
			utils::$db->query($sql, 'row') !== FALSE
				? utils::$db->queryResult
				: array('nb_images' => '?', 'nb_hits' => '?', 'filesize_total' => '?')
		);

		// Nombre d'images, nombre de visites et poids total protégés.
		$sql = 'SELECT COUNT(DISTINCT(i.image_id))
				  FROM ' . CONF_DB_PREF . 'images AS i,
					   ' . CONF_DB_PREF . 'categories AS c
				 WHERE i.cat_id = c.cat_id
				   AND cat_password IS NOT NULL
				   AND image_hits > 0
					   ' . $sql_albums_access;
		$sql = 'SELECT COUNT(*) AS nb_images,
					   SUM(image_hits) AS nb_hits,
					   (' . $sql . ') AS nb_images_hits,
					   SUM(image_filesize) AS filesize_total
				  FROM ' . CONF_DB_PREF . 'images AS img,
					   ' . CONF_DB_PREF . 'categories AS cat
				 WHERE img.cat_id = cat.cat_id
				   AND cat_password IS NOT NULL
					   ' . $sql_albums_access;
		self::$infos['protected'] = array_merge(
			self::$infos['protected'],
			utils::$db->query($sql, 'row') !== FALSE
				? utils::$db->queryResult
				: array('nb_images' => '?', 'nb_hits' => '?', 'filesize_total' => '?')
		);

		// Nombre d'images et poids total en attente.
		$sql = 'SELECT COUNT(*) AS nb_images,
					   SUM(up_filesize) AS filesize_total
				  FROM ' . CONF_DB_PREF . 'uploads';
		self::$infos['pending'] = array_merge(
			self::$infos['pending'],
			utils::$db->query($sql, 'row') !== FALSE
				? utils::$db->queryResult
				: array('nb_images' => '?', 'filesize_total' => '?')
		);

		// Nombre d'albums et de catégories.
		$sql = 'SELECT cat_id,
					   cat_status,
					   cat_filemtime,
					   cat_password
				  FROM ' . CONF_DB_PREF . 'categories AS cat
				 WHERE cat_id != 1'
					 . $sql_albums_access;
		$fetch_style = array('fetch' => PDO::FETCH_ASSOC, 'column' => 'cat_id');
		if (utils::$db->query($sql, $fetch_style) === FALSE)
		{
			self::$infos['activate']['nb_albums'] = '?';
			self::$infos['activate']['nb_categories'] = '?';
			self::$infos['deactivate']['nb_albums'] = '?';
			self::$infos['deactivate']['nb_categories'] = '?';
			self::$infos['protected']['nb_albums'] = '?';
			self::$infos['protected']['nb_categories'] = '?';
		}
		else
		{
			$categories = utils::$db->queryResult;
			self::$infos['activate']['nb_albums'] = 0;
			self::$infos['activate']['nb_categories'] = 0;
			self::$infos['deactivate']['nb_albums'] = 0;
			self::$infos['deactivate']['nb_categories'] = 0;
			self::$infos['protected']['nb_albums'] = 0;
			self::$infos['protected']['nb_categories'] = 0;
			foreach ($categories as &$infos)
			{
				// Catégories activées.
				if ($infos['cat_status'] == '1')
				{
					if ($infos['cat_filemtime'] === NULL)
					{
						self::$infos['activate']['nb_categories']++;
					}
					else
					{
						self::$infos['activate']['nb_albums']++;
					}
				}

				// Catégories désactivées.
				else
				{
					if ($infos['cat_filemtime'] === NULL)
					{
						self::$infos['deactivate']['nb_categories']++;
					}
					else
					{
						self::$infos['deactivate']['nb_albums']++;
					}
				}

				// Catégories protégées.
				if ($infos['cat_password'] !== NULL)
				{
					if ($infos['cat_filemtime'] === NULL)
					{
						self::$infos['protected']['nb_categories']++;
					}
					else
					{
						self::$infos['protected']['nb_albums']++;
					}
				}
			}
		}

		// Nombre de commentaires activés.
		$sql = 'SELECT COUNT(DISTINCT(i.image_id))
				  FROM ' . CONF_DB_PREF . 'comments AS m,
					   ' . CONF_DB_PREF . 'images AS i,
					   ' . CONF_DB_PREF . 'categories AS c
				 WHERE m.image_id = i.image_id
				   AND i.cat_id = c.cat_id
				   AND com_status = "1"
				   AND image_status = "1"
					   ' . $sql_albums_access;
		$sql = 'SELECT COUNT(*) AS nb_comments,
					   (' . $sql . ') AS nb_images_comments
				  FROM ' . CONF_DB_PREF . 'comments AS com,
					   ' . CONF_DB_PREF . 'images AS img,
					   ' . CONF_DB_PREF . 'categories AS cat
				 WHERE com.image_id = img.image_id
				   AND img.cat_id = cat.cat_id
				   AND com_status = "1"
				   AND image_status = "1"
					   ' . $sql_albums_access;
		self::$infos['activate'] = array_merge(
			self::$infos['activate'],
			utils::$db->query($sql, 'row') !== FALSE
				? utils::$db->queryResult
				: array('nb_comments' => '?', 'nb_images_comments' => '?')
		);

		// Nombre de commentaires désactivés.
		$sql = 'SELECT COUNT(DISTINCT(i.image_id))
				  FROM ' . CONF_DB_PREF . 'comments AS m,
					   ' . CONF_DB_PREF . 'images AS i,
					   ' . CONF_DB_PREF . 'categories AS c
				 WHERE m.image_id = i.image_id
				   AND i.cat_id = c.cat_id
				   AND (com_status = "0" OR image_status = "0")
					   ' . $sql_albums_access;
		$sql = 'SELECT COUNT(*) AS nb_comments,
					   (' . $sql . ') AS nb_images_comments
				  FROM ' . CONF_DB_PREF . 'comments AS com,
					   ' . CONF_DB_PREF . 'images AS img,
					   ' . CONF_DB_PREF . 'categories AS cat
				 WHERE com.image_id = img.image_id
				   AND img.cat_id = cat.cat_id
				   AND (com_status = "0" OR image_status = "0")
					   ' . $sql_albums_access;
		self::$infos['deactivate'] = array_merge(
			self::$infos['deactivate'],
			utils::$db->query($sql, 'row') !== FALSE
				? utils::$db->queryResult
				: array('nb_comments' => '?', 'nb_images_comments' => '?')
		);

		// Nombre de commentaires en attente.
		$sql = 'SELECT COUNT(DISTINCT(i.image_id))
				  FROM ' . CONF_DB_PREF . 'comments AS m,
					   ' . CONF_DB_PREF . 'images AS i,
					   ' . CONF_DB_PREF . 'categories AS c
				 WHERE m.image_id = i.image_id
				   AND i.cat_id = c.cat_id
				   AND com_status = "-1"
					   ' . $sql_albums_access;
		$sql = 'SELECT COUNT(*) AS nb_comments,
					   (' . $sql . ') AS nb_images_comments
				  FROM ' . CONF_DB_PREF . 'comments AS com,
					   ' . CONF_DB_PREF . 'images AS img,
					   ' . CONF_DB_PREF . 'categories AS cat
				 WHERE com.image_id = img.image_id
				   AND img.cat_id = cat.cat_id
				   AND com_status = "-1"
					   ' . $sql_albums_access;
		self::$infos['pending'] = array_merge(
			self::$infos['pending'],
			utils::$db->query($sql, 'row') !== FALSE
				? utils::$db->queryResult
				: array('nb_comments' => '?', 'nb_images_comments' => '?')
		);

		// Nombre de commentaires protégés.
		$sql = 'SELECT COUNT(DISTINCT(i.image_id))
				  FROM ' . CONF_DB_PREF . 'comments AS m,
					   ' . CONF_DB_PREF . 'images AS i,
					   ' . CONF_DB_PREF . 'categories AS c
				 WHERE m.image_id = i.image_id
				   AND i.cat_id = c.cat_id
				   AND cat_password IS NOT NULL
					   ' . $sql_albums_access;
		$sql = 'SELECT COUNT(*) AS nb_comments,
					   (' . $sql . ') AS nb_images_comments
				  FROM ' . CONF_DB_PREF . 'comments AS com,
					   ' . CONF_DB_PREF . 'images AS img,
					   ' . CONF_DB_PREF . 'categories AS cat
				 WHERE com.image_id = img.image_id
				   AND img.cat_id = cat.cat_id
				   AND cat_password IS NOT NULL
					   ' . $sql_albums_access;
		self::$infos['protected'] = array_merge(
			self::$infos['protected'],
			utils::$db->query($sql, 'row') !== FALSE
				? utils::$db->queryResult
				: array('nb_comments' => '?', 'nb_images_comments' => '?')
		);

		// Nombre de votes activés.
		$sql = 'SELECT COUNT(DISTINCT(i.image_id))
				  FROM ' . CONF_DB_PREF . 'votes AS v,
					   ' . CONF_DB_PREF . 'images AS i,
					   ' . CONF_DB_PREF . 'categories AS c
				 WHERE v.image_id = i.image_id
				   AND i.cat_id = c.cat_id
				   AND image_status = "1"
					   ' . $sql_albums_access;
		$sql = 'SELECT COUNT(*) AS nb_votes,
					   (' . $sql . ') AS nb_images_votes
				  FROM ' . CONF_DB_PREF . 'votes AS votes,
					   ' . CONF_DB_PREF . 'images AS img,
					   ' . CONF_DB_PREF . 'categories AS cat
				 WHERE votes.image_id = img.image_id
				   AND img.cat_id = cat.cat_id
				   AND image_status = "1"
					   ' . $sql_albums_access;
		self::$infos['activate'] = array_merge(
			self::$infos['activate'],
			utils::$db->query($sql, 'row') !== FALSE
				? utils::$db->queryResult
				: array('nb_votes' => '?', 'nb_images_votes' => '?')
		);

		// Nombre de votes désactivés.
		$sql = 'SELECT COUNT(DISTINCT(i.image_id))
				  FROM ' . CONF_DB_PREF . 'votes AS v,
					   ' . CONF_DB_PREF . 'images AS i,
					   ' . CONF_DB_PREF . 'categories AS c
				 WHERE v.image_id = i.image_id
				   AND i.cat_id = c.cat_id
				   AND image_status = "0"
					   ' . $sql_albums_access;
		$sql = 'SELECT COUNT(*) AS nb_votes,
					   (' . $sql . ') AS nb_images_votes
				  FROM ' . CONF_DB_PREF . 'votes AS votes,
					   ' . CONF_DB_PREF . 'images AS img,
					   ' . CONF_DB_PREF . 'categories AS cat
				 WHERE votes.image_id = img.image_id
				   AND img.cat_id = cat.cat_id
				   AND image_status = "0"
					   ' . $sql_albums_access;
		self::$infos['deactivate'] = array_merge(
			self::$infos['deactivate'],
			utils::$db->query($sql, 'row') !== FALSE
				? utils::$db->queryResult
				: array('nb_votes' => '?', 'nb_images_votes' => '?')
		);

		// Nombre de votes protégés.
		$sql = 'SELECT COUNT(DISTINCT(i.image_id))
				  FROM ' . CONF_DB_PREF . 'votes AS v,
					   ' . CONF_DB_PREF . 'images AS i,
					   ' . CONF_DB_PREF . 'categories AS c
				 WHERE v.image_id = i.image_id
				   AND i.cat_id = c.cat_id
				   AND cat_password IS NOT NULL
					   ' . $sql_albums_access;
		$sql = 'SELECT COUNT(*) AS nb_votes,
					   (' . $sql . ') AS nb_images_votes
				  FROM ' . CONF_DB_PREF . 'votes AS votes,
					   ' . CONF_DB_PREF . 'images AS img,
					   ' . CONF_DB_PREF . 'categories AS cat
				 WHERE votes.image_id = img.image_id
				   AND img.cat_id = cat.cat_id
				   AND cat_password IS NOT NULL
					   ' . $sql_albums_access;
		self::$infos['protected'] = array_merge(
			self::$infos['protected'],
			utils::$db->query($sql, 'row') !== FALSE
				? utils::$db->queryResult
				: array('nb_votes' => '?', 'nb_images_votes' => '?')
		);

		// Note moyenne des images activées.
		$sql = 'SELECT AVG(vote_rate) AS average_rate
				  FROM ' . CONF_DB_PREF . 'votes AS votes,
					   ' . CONF_DB_PREF . 'images AS img,
					   ' . CONF_DB_PREF . 'categories AS cat
				 WHERE votes.image_id = img.image_id
				   AND img.cat_id = cat.cat_id
				   AND image_status = "1"
					   ' . $sql_albums_access;
		self::$infos['activate'] = array_merge(
			self::$infos['activate'],
			utils::$db->query($sql, 'row') !== FALSE
				? utils::$db->queryResult
				: array('average_rate' => '?')
		);

		// Note moyenne des images désactivées.
		$sql = 'SELECT AVG(vote_rate) AS average_rate
				  FROM ' . CONF_DB_PREF . 'votes AS votes,
					   ' . CONF_DB_PREF . 'images AS img,
					   ' . CONF_DB_PREF . 'categories AS cat
				 WHERE votes.image_id = img.image_id
				   AND img.cat_id = cat.cat_id
				   AND image_status = "0"
					   ' . $sql_albums_access;
		self::$infos['deactivate'] = array_merge(
			self::$infos['deactivate'],
			utils::$db->query($sql, 'row') !== FALSE
				? utils::$db->queryResult
				: array('average_rate' => '?')
		);

		// Note moyenne des images protégées.
		$sql = 'SELECT AVG(vote_rate) AS average_rate
				  FROM ' . CONF_DB_PREF . 'votes AS votes,
					   ' . CONF_DB_PREF . 'images AS img,
					   ' . CONF_DB_PREF . 'categories AS cat
				 WHERE votes.image_id = img.image_id
				   AND img.cat_id = cat.cat_id
				   AND cat_password IS NOT NULL
					   ' . $sql_albums_access;
		self::$infos['protected'] = array_merge(
			self::$infos['protected'],
			utils::$db->query($sql, 'row') !== FALSE
				? utils::$db->queryResult
				: array('average_rate' => '?')
		);

		// Note moyenne de toutes les images.
		$sql = 'SELECT AVG(vote_rate) AS average_rate
				  FROM ' . CONF_DB_PREF . 'votes AS votes,
					   ' . CONF_DB_PREF . 'images AS img,
					   ' . CONF_DB_PREF . 'categories AS cat
				 WHERE votes.image_id = img.image_id
				   AND img.cat_id = cat.cat_id
					   ' . $sql_albums_access;
		self::$infos['total'] = array_merge(
			self::$infos['total'],
			utils::$db->query($sql, 'row') !== FALSE
				? utils::$db->queryResult
				: array('average_rate' => '?')
		);

		// Nombre de tags activés.
		$sql = 'SELECT COUNT(DISTINCT(i.image_id))
				  FROM ' . CONF_DB_PREF . 'tags_images AS ti,
					   ' . CONF_DB_PREF . 'images AS i,
					   ' . CONF_DB_PREF . 'categories AS c
				 WHERE ti.image_id = i.image_id
				   AND i.cat_id = c.cat_id
				   AND image_status = "1"
					   ' . $sql_albums_access;
		$sql = 'SELECT COUNT(DISTINCT(t_img.tag_id)) AS nb_tags,
					   COUNT(*) AS nb_tags_images,
					   (' . $sql . ') AS nb_images_tags
				  FROM ' . CONF_DB_PREF . 'tags_images AS t_img,
					   ' . CONF_DB_PREF . 'images AS img,
					   ' . CONF_DB_PREF . 'categories AS cat
				 WHERE t_img.image_id = img.image_id
				   AND img.cat_id = cat.cat_id
				   AND image_status = "1"
					   ' . $sql_albums_access;
		self::$infos['activate'] = array_merge(
			self::$infos['activate'],
			utils::$db->query($sql, 'row') !== FALSE
				? utils::$db->queryResult
				: array('nb_tags' => '?', 'nb_images_tags' => '?')
		);

		// Nombre de tags désactivés.
		$sql = 'SELECT COUNT(DISTINCT(i.image_id))
				  FROM ' . CONF_DB_PREF . 'tags_images AS ti,
					   ' . CONF_DB_PREF . 'images AS i,
					   ' . CONF_DB_PREF . 'categories AS c
				 WHERE ti.image_id = i.image_id
				   AND i.cat_id = c.cat_id
				   AND image_status = "0"
					   ' . $sql_albums_access;
		$sql = 'SELECT COUNT(DISTINCT(t_img.tag_id)) AS nb_tags,
					   COUNT(*) AS nb_tags_images,
					   (' . $sql . ') AS nb_images_tags
				  FROM ' . CONF_DB_PREF . 'tags_images AS t_img,
					   ' . CONF_DB_PREF . 'images AS img,
					   ' . CONF_DB_PREF . 'categories AS cat
				 WHERE t_img.image_id = img.image_id
				   AND img.cat_id = cat.cat_id
				   AND image_status = "0"
					   ' . $sql_albums_access;
		self::$infos['deactivate'] = array_merge(
			self::$infos['deactivate'],
			utils::$db->query($sql, 'row') !== FALSE
				? utils::$db->queryResult
				: array('nb_tags' => '?', 'nb_images_tags' => '?')
		);

		// Nombre de tags protégés.
		$sql = 'SELECT COUNT(DISTINCT(i.image_id))
				  FROM ' . CONF_DB_PREF . 'tags_images AS ti,
					   ' . CONF_DB_PREF . 'images AS i,
					   ' . CONF_DB_PREF . 'categories AS c
				 WHERE ti.image_id = i.image_id
				   AND i.cat_id = c.cat_id
				   AND cat_password IS NOT NULL
					   ' . $sql_albums_access;
		$sql = 'SELECT COUNT(DISTINCT(t_img.tag_id)) AS nb_tags,
					   COUNT(*) AS nb_tags_images,
					   (' . $sql . ') AS nb_images_tags
				  FROM ' . CONF_DB_PREF . 'tags_images AS t_img,
					   ' . CONF_DB_PREF . 'images AS img,
					   ' . CONF_DB_PREF . 'categories AS cat
				 WHERE t_img.image_id = img.image_id
				   AND img.cat_id = cat.cat_id
				   AND cat_password IS NOT NULL
					   ' . $sql_albums_access;
		self::$infos['protected'] = array_merge(
			self::$infos['protected'],
			utils::$db->query($sql, 'row') !== FALSE
				? utils::$db->queryResult
				: array('nb_tags' => '?', 'nb_images_tags' => '?')
		);

		// Nombre d'utilisateurs.
		$sql = 'SELECT user_id,
					   user_status,
					   group_admin
				  FROM ' . CONF_DB_PREF . 'users AS u,
					   ' . CONF_DB_PREF . 'groups AS g
				 WHERE u.group_id = g.group_id
				   AND user_id != 2
				   AND user_status != "-2"';
		$fetch_style = array('fetch' => PDO::FETCH_ASSOC, 'column' => 'user_id');
		if (utils::$db->query($sql, $fetch_style) === FALSE)
		{
			self::$infos['activate']['nb_admins'] = '?';
			self::$infos['activate']['nb_members'] = '?';
			self::$infos['deactivate']['nb_admins'] = '?';
			self::$infos['deactivate']['nb_members'] = '?';
			self::$infos['pending']['nb_admins'] = '?';
			self::$infos['pending']['nb_members'] = '?';
		}
		else
		{
			$users = utils::$db->queryResult;
			self::$infos['activate']['nb_admins'] = 0;
			self::$infos['activate']['nb_members'] = 0;
			self::$infos['deactivate']['nb_admins'] = 0;
			self::$infos['deactivate']['nb_members'] = 0;
			self::$infos['pending']['nb_admins'] = 0;
			self::$infos['pending']['nb_members'] = 0;
			foreach ($users as &$infos)
			{
				// Utilisateurs activés.
				if ($infos['user_status'] == '1')
				{
					if ($infos['group_admin'] == '1')
					{
						self::$infos['activate']['nb_admins']++;
					}
					else
					{
						self::$infos['activate']['nb_members']++;
					}
				}

				// Utilisateurs désactivés.
				else if ($infos['user_status'] == '0')
				{
					if ($infos['group_admin'] == '1')
					{
						self::$infos['deactivate']['nb_admins']++;
					}
					else
					{
						self::$infos['deactivate']['nb_members']++;
					}
				}

				// Utilisateurs en attente.
				else
				{
					if ($infos['group_admin'] == '1')
					{
						self::$infos['pending']['nb_admins']++;
					}
					else
					{
						self::$infos['pending']['nb_members']++;
					}
				}
			}
		}

		// Nombre de groupes.
		$sql = 'SELECT COUNT(*)
				  FROM ' . CONF_DB_PREF . 'groups';
		self::$infos['activate']['nb_groups'] = (utils::$db->query($sql, 'value') !== FALSE)
			? utils::$db->queryResult
			: '?';

		// Nombre de favoris activés.
		$sql = 'SELECT COUNT(DISTINCT(i.image_id))
				  FROM ' . CONF_DB_PREF . 'favorites AS f,
					   ' . CONF_DB_PREF . 'images AS i,
					   ' . CONF_DB_PREF . 'categories AS c
				 WHERE f.image_id = i.image_id
				   AND i.cat_id = c.cat_id
				   AND image_status = "1"
					   ' . $sql_albums_access;
		$sql = 'SELECT COUNT(*) AS nb_favorites,
					   (' . $sql . ') AS nb_images_favorites
				  FROM ' . CONF_DB_PREF . 'favorites AS fav,
					   ' . CONF_DB_PREF . 'images AS img,
					   ' . CONF_DB_PREF . 'categories AS cat
				 WHERE fav.image_id = img.image_id
				   AND img.cat_id = cat.cat_id
				   AND image_status = "1"
					   ' . $sql_albums_access;
		self::$infos['activate'] = array_merge(
			self::$infos['activate'],
			utils::$db->query($sql, 'row') !== FALSE
				? utils::$db->queryResult
				: array('nb_favorites' => '?', 'nb_images_favorites' => '?')
		);

		// Nombre de favoris désactivés.
		$sql = 'SELECT COUNT(DISTINCT(i.image_id))
				  FROM ' . CONF_DB_PREF . 'favorites AS f,
					   ' . CONF_DB_PREF . 'images AS i,
					   ' . CONF_DB_PREF . 'categories AS c
				 WHERE f.image_id = i.image_id
				   AND i.cat_id = c.cat_id
				   AND image_status = "0"
					   ' . $sql_albums_access;
		$sql = 'SELECT COUNT(*) AS nb_favorites,
					   (' . $sql . ') AS nb_images_favorites
				  FROM ' . CONF_DB_PREF . 'favorites AS fav,
					   ' . CONF_DB_PREF . 'images AS img,
					   ' . CONF_DB_PREF . 'categories AS cat
				 WHERE fav.image_id = img.image_id
				   AND img.cat_id = cat.cat_id
				   AND image_status = "0"
					   ' . $sql_albums_access;
		self::$infos['deactivate'] = array_merge(
			self::$infos['deactivate'],
			utils::$db->query($sql, 'row') !== FALSE
				? utils::$db->queryResult
				: array('nb_favorites' => '?', 'nb_images_favorites' => '?')
		);

		// Nombre de favoris protégés.
		$sql = 'SELECT COUNT(DISTINCT(i.image_id))
				  FROM ' . CONF_DB_PREF . 'favorites AS f,
					   ' . CONF_DB_PREF . 'images AS i,
					   ' . CONF_DB_PREF . 'categories AS c
				 WHERE f.image_id = i.image_id
				   AND i.cat_id = c.cat_id
				   AND cat_password IS NOT NULL
					   ' . $sql_albums_access;
		$sql = 'SELECT COUNT(*) AS nb_favorites,
					   (' . $sql . ') AS nb_images_favorites
				  FROM ' . CONF_DB_PREF . 'favorites AS fav,
					   ' . CONF_DB_PREF . 'images AS img,
					   ' . CONF_DB_PREF . 'categories AS cat
				 WHERE fav.image_id = img.image_id
				   AND img.cat_id = cat.cat_id
				   AND cat_password IS NOT NULL
					   ' . $sql_albums_access;
		self::$infos['protected'] = array_merge(
			self::$infos['protected'],
			utils::$db->query($sql, 'row') !== FALSE
				? utils::$db->queryResult
				: array('nb_favorites' => '?', 'nb_images_favorites' => '?')
		);
	}

	/**
	 * Retourne la description par défaut des trois premiers groupes.
	 *
	 * @param integer $id
	 * @return string
	 */
	public static function getL10nGroupDesc($id)
	{
		switch ($id)
		{
			case 1 :
				return __('Ce groupe ne contient qu\'un seul utilisateur :'
					. ' la personne qui a installée la galerie, c\'est à dire'
					. ' l\'administrateur principal ou le webmaster.');

			case 2 :
				return __('Ce groupe est spécial car il ne contient aucun'
					. ' utilisateur enregistré. Il désigne l\'ensemble'
					. ' des visiteurs non membres de la galerie.');

			case 3 :
				return __('Ce groupe contient tout utilisateur'
					. ' qui vient de s\'enregistrer.');
		}
	}

	/**
	 * Retourne le nom par défaut des trois premiers groupes.
	 *
	 * @param integer $id
	 * @return string
	 */
	public static function getL10nGroupName($id)
	{
		switch ($id)
		{
			case 1 :
				return __('Super-administrateur');

			case 2 :
				return __('Invités');

			case 3 :
				return __('Nouveaux membres');
		}
	}

	/**
	 * Retourne le titre par défaut des trois premiers groupes.
	 *
	 * @param integer $id
	 * @return string
	 */
	public static function getL10nGroupTitle($id)
	{
		switch ($id)
		{
			case 1 :
				return __('Super-administrateur');

			case 2 :
				return __('Invité');

			case 3 :
				return __('Membre');
		}
	}

	/**
	 * Gestion des incidents.
	 *
	 * @return void
	 */
	public static function incidents()
	{
		// Actions sur la sélection.
		if (!empty($_POST['action'])
		 && !empty($_POST['select']) && is_array($_POST['select']))
		{
			switch ($_POST['action'])
			{
				// Suppression.
				case 'delete' :
					self::_incidentsDelete();
					break;

				// Téléchargement.
				case 'export' :
					self::_incidentsExport();
					break;
			}
		}

		// Récupération des erreurs.
		$errors = glob(GALLERY_ROOT . '/errors/*.xml');
		if (!empty($errors))
		{
			foreach ($errors as &$file)
			{
				if (($xml = simplexml_load_file($file)) === FALSE)
				{
					continue;
				}
				self::$infos[$xml->date . $xml['md5']] = $xml;
				self::$infos[$xml->date . $xml['md5']]['error_file'] = basename($file);
			}
			krsort(self::$infos);
		}
	}

	/**
	 * Initialisation de l'admin.
	 *
	 * @return void
	 */
	public static function init()
	{
		// Connexion à la base de données.
		utils::$db = new db();
		if (utils::$db->connexion === NULL)
		{
			die('Unable to connect to the database.');
		}

		// Récupération de la configuration de la galerie.
		$sql = 'SELECT *
				  FROM ' . CONF_DB_PREF . 'config
				 WHERE conf_name NOT LIKE "blacklist%"';
		$fetch_style = array(
			'column' => array('conf_name', 'conf_value')
		);
		if (utils::$db->query($sql, $fetch_style) === FALSE
		|| utils::$db->nbResult === 0)
		{
			die('Missing data in the database.');
		}
		utils::$config = utils::$db->queryResult;

		utils::$config['locale_langs'] = unserialize(utils::$config['locale_langs']);
		utils::$config['users_profile_infos'] =
			unserialize(utils::$config['users_profile_infos']);
		utils::$config['admin_group_perms_default'] =
			unserialize(utils::$config['admin_group_perms_default']);

		utils::$config['widgets_content_maxlength'] = 2000;

		// Modification de la config par le template.
		$config = array();
		include_once(GALLERY_ROOT . '/template/'
			. utils::filters(utils::$config['theme_template'], 'dir') . '/_config.php');
		self::$tplDisabledConfig = $config;

		// Requête sans le numéro de page.
		if (isset($_GET['q']))
		{
			self::$sectionRequest = preg_replace('`/page/\d+$`', '', $_GET['q']);
		}

		// Date et heure de la requête.
		self::$time = (int) $_SERVER['REQUEST_TIME'];

		// Identifiant de session : chargement cookie.
		utils::$cookieSession = new cookie('igal_session', 8640000, CONF_GALLERY_PATH);

		// Cookie des préférences.
		utils::$cookiePrefs = new cookie('igal_prefs', 315360000, CONF_GALLERY_PATH);

		// Authentification utilisateur.
		if (!auth::checkSession())
		{
			utils::$purlFile = 'connexion.php?q=session-expire';
			utils::redirect('', TRUE);
			die;
		}

		// Désérialisation.
		utils::$config['pages_order'] = unserialize(utils::$config['pages_order']);
		utils::$config['pages_params'] = unserialize(utils::$config['pages_params']);
		utils::$config['widgets_order'] = unserialize(utils::$config['widgets_order']);
		utils::$config['widgets_params'] = unserialize(utils::$config['widgets_params']);

		// Chargement du fichier de langue.
		utils::locale();

		utils::$db->msgFailure = __('L\'action demandée a échouée car une erreur'
			. ' de base de données est survenue :');
	}

	/**
	 * Crée un rapport sur les actions effectuées.
	 *
	 * @param object|string $msg
	 * @param integer $id
	 * @param null|string $filemtime
	 * @return void
	 */
	public static function report($msg, $id = 0, $filemtime = NULL)
	{
		// Message.
		$msg = (is_object($msg))
			? explode(':', $msg->getMessage(), 2)
			: explode(':', $msg, 2);
		$message = $msg[1];

		// Type d'objet.
		if ($id > 0)
		{
			switch ($_GET['section'])
			{
				case 'album' :
				case 'images-pending' :
					$object = __('image %s');
					break;

				case 'category' :
					$object = ($filemtime !== NULL
					|| albums::$items[$id]['cat_filemtime'] !== NULL)
						? __('album %s')
						: __('catégorie %s');
					break;

				case 'comments-images' :
					$object = __('commentaire %s');
					break;
			}
			if (isset($object))
			{
				$message = sprintf($object, $id) . ' : ' . $message;
			}
		}

		switch ($msg[0])
		{
			case 'error' :
			case 'warning' :
				self::$report[$msg[0]][] = $message;
				break;

			default :
				self::$report[$msg[0]] = $message;
		}
	}

	/**
	 * Gestion de la requête du moteur de recherche.
	 *
	 * @param array $options
	 * @return void
	 */
	public static function searchGetPost($options = NULL)
	{
		// POST.
		if (isset($_POST['search'])
		 && isset($_POST['search_query'])
		 && isset($_POST['search_options'])
		 && !utils::isEmpty($_POST['search_query'])
		 && mb_strlen($_POST['search_query']) <= 255
		 && is_array($_POST['search_options'])
		 && count($_POST['search_options']) < 50)
		{
			// Section "Albums".
			if (in_array($_GET['section'], array('album', 'category'))
			&& isset($_POST['search_options']['type']))
			{
				// Dates.
				if ($_POST['search_options']['type'] == 'album')
				{
					$options = search::$searchAdminImageOptions;
					if (isset($_POST['search_options']['image_date_field']))
					{
						$_POST['search_options']['date_field'] =
							$_POST['search_options']['image_date_field'];
					}
				}

				if ($_POST['search_options']['type'] == 'category')
				{
					$options = category::$searchOptions;
					if (isset($_POST['search_options']['cat_date_field']))
					{
						$_POST['search_options']['date_field'] =
							$_POST['search_options']['cat_date_field'];
					}
				}
			}

			// Vérifications des options.
			foreach ($_POST['search_options'] as $o => &$v)
			{
				if (isset($options[$o]))
				{
					if ($options[$o] == 'bin')
					{
						$v = 1;
						continue;
					}
					else
					{
						if (preg_match('`^' . $options[$o] . '$`', $v))
						{
							continue;
						}
					}
				}
				unset($_POST['search_options'][$o]);
			}

			// Identifiant de recherche.
			$search_id = utils::genKey(FALSE, 12);

			// Enregistrement de la requête en base de données.
			$sql = 'INSERT INTO ' . CONF_DB_PREF . 'search (
				search_id,
				search_query,
				search_options,
				search_date
				) VALUES (
				:search_id,
				:search_query,
				:search_options,
				NOW())';
			$params = array(
				'search_id' => $search_id,
				'search_query' => $_POST['search_query'],
				'search_options' => serialize($_POST['search_options'])
			);
			if (utils::$db->prepare($sql) === FALSE
			|| utils::$db->executeExec($params) === FALSE
			|| utils::$db->nbResult === 0)
			{
				self::report('error:' . utils::$db->msgError);
				return;
			}

			// Redirection.
			$section = preg_replace(
				'`/(?:(?:camera-(?:brand|model)|date|ip|tag|'
				. 'user(?:-(?:basket|favorites|images))?)/.+$|pending)`',
				'',
				admin::$sectionRequest
			);
			utils::redirect($section . '/search/' . $search_id, TRUE);
		}

		// GET.
		else if (isset($_GET['search']))
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
				self::report('error:' . utils::$db->msgError);
				return;
			}
			if (utils::$db->nbResult === 0)
			{
				return;
			}

			$search = utils::$db->queryResult;
			$search_options = unserialize($search['search_options']);

			if (isset($search_options['type']))
			{
				$options = ($search_options['type'] == 'album')
					? search::$searchAdminImageOptions
					: category::$searchOptions;
			}
			if (empty($options))
			{
				return;
			}

			// Requête.
			$_GET['search_query'] = $search['search_query'];

			// Options.
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
		}
	}

	/**
	 * Section "Système".
	 *
	 * @return void
	 */
	public static function system()
	{
		self::$infos['errors'] = count(glob(GALLERY_ROOT . '/errors/*.xml'));
		self::$infos['mysql_version'] = system::getMySQLVersion(TRUE);
		self::$infos['mysql_variables'] = system::getMySQLSystemVariables();
	}

	/**
	 * Gestion des thèmes.
	 *
	 * @return void
	 */
	public static function themes()
	{
		$regex = '`^[-_a-z0-9]{1,48}$`i';

		// Récupération des thèmes et styles disponibles.
		$templates_dir = GALLERY_ROOT . '/template/';
		$themes = scandir($templates_dir);
		for ($i = 0, $count_i = count($themes); $i < $count_i; $i++)
		{
			if (!preg_match($regex, $themes[$i]))
			{
				unset($themes[$i]);
				continue;
			}

			$styles_dir = $templates_dir . $themes[$i] . '/style/';
			if (!is_dir($styles_dir))
			{
				continue;
			}

			$styles = scandir($styles_dir);
			for ($n = 0, $count_n = count($styles); $n < $count_n; $n++)
			{
				if (!preg_match($regex, $styles[$n])
				|| !file_exists($styles_dir . $styles[$n] . '/screenshot.jpg')
				|| !file_exists($styles_dir . $styles[$n] . '/infos.xml'))
				{
					unset($styles[$n]);
					continue;
				}

				$xml = simplexml_load_file($styles_dir . $styles[$n] . '/infos.xml');
				if (isset($xml->author) && isset($xml->description))
				{
					self::$infos[$themes[$i]][$styles[$n]] = $xml;
				}
			}
		}

		// Changement du thème.
		if (empty($_POST) || empty($_POST['theme']) || empty($_POST['style'])
		|| !isset($_POST['theme_css'])
		|| !is_array($_POST['style']) || !isset($_POST['style'][$_POST['theme']])
		|| !preg_match($regex, $_POST['theme'])
		|| !preg_match($regex, $_POST['style'][$_POST['theme']]))
		{
			return;
		}

		$_POST['theme_style'] = $_POST['style'][$_POST['theme']];
		$_POST['theme_template'] = $_POST['theme'];
		$fields = array(
			'text' => array(
				'theme_css',
				'theme_style',
				'theme_template'
			)
		);

		self::_changeDBConfig($fields, array(), array());
	}

	/**
	 * Récupération des statistiques des utilisateurs de la galerie.
	 *
	 * @return void
	 */
	public static function usersStats()
	{
		$sql = 'SELECT user_id,
					   user_login,
					   user_status,
					   group_admin,
					   (SELECT COUNT(*)
					      FROM ' . CONF_DB_PREF . 'images AS i
						 WHERE i.user_id = u.user_id) AS nb_images,
					   (SELECT COUNT(*)
					      FROM ' . CONF_DB_PREF . 'comments AS c
						 WHERE c.user_id = u.user_id) AS nb_comments,
					   (SELECT COUNT(*)
					      FROM ' . CONF_DB_PREF . 'votes AS v
						 WHERE v.user_id = u.user_id) AS nb_votes,
					   (SELECT COUNT(*)
					      FROM ' . CONF_DB_PREF . 'favorites AS f
						 WHERE f.user_id = u.user_id) AS nb_favorites
				  FROM ' . CONF_DB_PREF . 'users AS u,
					   ' . CONF_DB_PREF . 'groups AS g
				 WHERE u.group_id = g.group_id
				   AND user_id != 2
				   AND user_status != "-2"
			  ORDER BY LOWER(user_login) ASC';
		$fetch_style = array('fetch' => PDO::FETCH_ASSOC, 'column' => 'user_id');
		if (utils::$db->query($sql, $fetch_style) !== FALSE)
		{
			self::$infos = utils::$db->queryResult;
		}
	}

	/**
	 * Options du filigrane.
	 *
	 * @return void
	 */
	public static function watermark()
	{
		admin::$watermarkParams
			= utils::$config['watermark_params']
			= unserialize(utils::$config['watermark_params']);
		$image_dir = 'images/watermarks/';

		// Modification des options du filigrane.
		$r = watermark::changeOptions(admin::$watermarkParams, $image_dir);
		if (admin::$watermarkParams != utils::$config['watermark_params'])
		{
			// On effectue la mise à jour des paramètres.
			$sql = 'UPDATE ' . CONF_DB_PREF . 'config
					   SET conf_value = :watermark_params
					 WHERE conf_name = "watermark_params"
					 LIMIT 1';
			$params = array(
				'watermark_params' => serialize(admin::$watermarkParams)
			);
			if (utils::$db->prepare($sql) === FALSE
			 || utils::$db->executeExec($params) === FALSE)
			{
				self::report('error:' . utils::$db->msgError);
				return;
			}

			self::report('success:' . __('Modifications enregistrées.'));
			self::report('success_p:' . __('Les autres modifications ont été enregistrées.'));
		}

		// Rapport du changement de l'image de filigrane.
		if (is_string($r))
		{
			self::report($r);
		}

		// Chemin de l'image de filigrane.
		$image_file = $image_dir . admin::$watermarkParams['image_file'];
		admin::$watermarkParams['image_file'] = (admin::$watermarkParams['image_file']
		&& file_exists(GALLERY_ROOT . '/' . $image_file)
		&& is_file(GALLERY_ROOT . '/' . $image_file))
			? $image_file
			: NULL;
	}



	/**
	 * Modifications de paramètres de configuration de base de données.
	 *
	 * @param array $fields
	 * @param array $columns
	 * @param array $params
	 * @return void
	 */
	protected static function _changeDBConfig($fields = array(),
	$columns = array(), $params = array())
	{
		if (empty($_POST))
		{
			return;
		}

		try
		{
			// Cases à cocher.
			if (isset($fields['checkboxes']))
			{
				foreach ($fields['checkboxes'] as $c)
				{
					if (isset(admin::$tplDisabledConfig[$c])
					|| ($c == 'recent_images_nb'
						&& isset(admin::$tplDisabledConfig['recent_images'])))
					{
						continue;
					}

					if (isset($_POST[$c])
					&& !utils::$config[$c])
					{
						$columns[] = '"' . $c . '" THEN "1"';
					}
					elseif (!isset($_POST[$c])
					&& utils::$config[$c])
					{
						$columns[] = '"' . $c . '" THEN "0"';
					}
				}
			}

			// Champs 'integer'.
			if (isset($fields['integer']))
			{
				foreach ($fields['integer'] as $i)
				{
					if (isset($_POST[$i]) && (int) $_POST[$i] >= 0
					&& $_POST[$i] != utils::$config[$i])
					{
						switch ($i)
						{
							case 'avatars_maxfilesize' :
							case 'upload_maxfilesize' :
								if (strlen($_POST[$i]) > 5)
								{
									continue 2;
								}
								if ($_POST[$i] > (utils::uploadMaxFilesize() / 1024))
								{
									continue 2;
								}
						}
						$columns[] = '"' . $i . '" THEN "' . (int) $_POST[$i] . '"';
					}
				}
			}

			// Champs texte.
			if (isset($fields['text']))
			{
				foreach ($fields['text'] as $t)
				{
					if (isset($_POST[$t]) && $_POST[$t] != utils::$config[$t])
					{
						$columns[] = '"' . $t . '" THEN :' . $t;
						$params[$t] = $_POST[$t];
					}
				}
			}

			// Champs texte localisés.
			if (isset($fields['text_locale']))
			{
				foreach ($fields['text_locale'] as $t)
				{
					if (!isset($_POST[$t]))
					{
						continue;
					}
					$locale_text = utils::setLocaleText($_POST[$t], utils::$config[$t]);
					if ($locale_text['change'])
					{
						$columns[] = '"' . $t . '" THEN :' . $t;
						$params[$t] = utils::$config[$t];
					}
				}
			}

			// Listes prédéfinies.
			if (isset($fields['lists']))
			{
				foreach ($fields['lists'] as $l => $list)
				{
					if (isset($_POST[$l]) && $_POST[$l] != utils::$config[$l]
					&& in_array($_POST[$l], $list))
					{
						$columns[] = '"' . $l . '" THEN :' . $l;
						$params[$l] = $_POST[$l];
					}
				}
			}

			// Aucun changement.
			if (empty($columns))
			{
				return;
			}

			// On effectue la mise à jour des paramètres.
			$sql = 'UPDATE ' . CONF_DB_PREF . 'config
					   SET conf_value = CASE conf_name
						   WHEN ' . implode(' WHEN ', $columns) . '
						   ELSE conf_value END';
			if (utils::$db->prepare($sql) === FALSE
			|| utils::$db->executeExec($params) === FALSE)
			{
				throw new Exception('error:' . utils::$db->msgError);
			}

			// Mise à jour du tableau de config.
			foreach ($columns as $id => $value)
			{
				if (strstr($value, 'THEN :'))
				{
					$value = explode(' THEN :', $value);
					$p = preg_replace('`^"(.+)"$`', '$1', $value[0]);
					utils::$config[$p] = (utils::isSerializedArray($params[$value[1]]))
						? unserialize($params[$value[1]])
						: $params[$value[1]];
					continue;
				}

				$value = explode(' THEN ', $value);
				$p = preg_replace('`^"(.+)"$`', '$1', $value[0]);
				$v = preg_replace('`^"(.+)"$`', '$1', $value[1]);
				utils::$config[$p] = $v;
			}

			self::report('success:' . __('Modifications enregistrées.'));
			self::report('success_p:' . __('Les autres modifications ont été enregistrées.'));
		}
		catch (Exception $e)
		{
			self::report($e);
		}
	}

	/**
	 * Formatage des listes de la configuration avant mise à jour.
	 *
	 * @param array $post_lists
	 * @param array $config_lists
	 * @return void
	 */
	protected static function _configList($post_lists, $config_lists = NULL)
	{
		foreach ($post_lists as $l)
		{
			if ($config_lists !== NULL)
			{
				utils::$config[$l] = $config_lists[$l];
			}
			if (!isset($_POST[$l]))
			{
				continue;
			}
			$post_list = preg_split('`[\r\n]+`', $_POST[$l], -1, PREG_SPLIT_NO_EMPTY);
			foreach ($post_list as &$entry)
			{
				$entry = trim(mb_strtolower($entry));
			}
			sort($post_list);
			$_POST[$l] = implode("\n", $post_list);
			unset($post_list);
		}
	}

	/**
	 * Traitement à effectuer si aucun objet n'a été récupéré.
	 *
	 * @return boolean
	 */
	protected static function _objectsNbResult()
	{
		if (utils::$db->nbResult > 0)
		{
			return TRUE;
		}

		// Section courante.
		$section = ($_GET['section'] == 'album' || $_GET['section'] == 'category')
			? 'category/1'
			: $_GET['section'];

		// Action ayant entraîné une modification
		// du nombre d'objets à récupérer.
		if (isset($_POST['action']))
		{
			if ($_GET['page'] == 1)
			{
				return FALSE;
			}
			$_GET['page']--;
			if ($section == 'category/1')
			{
				albums::pages();
			}
			$trace = debug_backtrace();
			eval($trace[1]['class'] . '::' . $trace[1]['function'] . '();');
		}

		// Aucune action effectuée, mais page autre que la première :
		// on redirige vers la première page de la section.
		// Exemple :
		// 		Page demandée  : votes/album/5/date/2012-07-23/page/3
		//	 	Page redirigée : votes/album/5/date/2012-07-23
		else if ($_GET['page'] > 1)
		{
			utils::redirect(admin::$sectionRequest, TRUE);
		}

		// Aucune action effectuée et première page,
		// mais section spéciale (filtres) :
		// on redirige vers la même section sans filtre.
		// Exemple :
		// 		Page demandée  : votes/album/5/date/2012-07-23
		//	 	Page redirigée : votes/album/5
		else if (isset($_GET['date']) || isset($_GET['ip'])
		|| isset($_GET['status']) || isset($_GET['tag']) || isset($_GET['user_id']))
		{
			utils::redirect(preg_replace(
				'`/(?:(?:camera-(?:brand|model)|date|ip|tag|user)/.+$|pending)`',
				'',
				admin::$sectionRequest
			), TRUE);
		}

		// Aucune action effectuée et première page,
		// mais recherche :
		// on redirige vers la page d'accueil de la section
		// avec la recherche si on n'y est pas déjà.
		// Exemple :
		// 		Page demandée  : comments-images/album/5/search/s9Y62g1o811W
		//	 	Page redirigée : comments-images/search/s9Y62g1o811W
		else if (isset($_GET['search'])
		&& $_GET['q'] != $section . '/search/' . $_GET['search'])
		{
			utils::redirect($section . '/search/' . $_GET['search'], TRUE);
		}

		// Aucune action effectuée, première page, mais
		// section non principale (dans l'arborescence de la galerie) :
		// on redirige vers la section principale, sauf dans le cas
		// d'une recherche.
		// Exemple :
		// 		Page demandée  : votes/album/5
		//	 	Page redirigée : votes
		else if ($_GET['q'] != $section && !isset($_GET['search']))
		{
			// Pour la section "Albums", on ne redirige que si
			// la catégorie n'existe pas car elle doit pouvoir
			// être affichée même si elle est vide.
			if ($section != 'category/1'
			|| ($section == 'category/1' && albums::$infos === NULL))
			{
				utils::redirect($section, TRUE);
			}
		}

		return FALSE;
	}

	/**
	 * Filtre la liste des catégories pour ne conserver que
	 * les albums de $list ainsi que leurs catégories parentes.
	 *
	 * @param array $list
	 *	Identifiants des albums à conserver.
	 * @return void
	 */
	protected static function _reduceMapAlbums(&$list)
	{
		$categories_id = array(1);

		foreach (albums::$mapCategories as $id => &$infos)
		{
			if ($infos['cat_filemtime'] !== NULL)
			{
				if (in_array($id, $list))
				{
					$parent_id = $infos['parent_id'];
					while ($parent_id != 1)
					{
						if (!in_array($parent_id, $categories_id))
						{
							$categories_id[] = $parent_id;
						}
						$parent_id = albums::$mapCategories[$parent_id]['parent_id'];
					}
				}
				else
				{
					unset(albums::$mapCategories[$id]);
				}
			}
		}

		foreach (albums::$mapCategories as $id => &$infos)
		{
			if ($infos['cat_filemtime'] === NULL
			&& !in_array($id, $categories_id))
			{
				unset(albums::$mapCategories[$id]);
			}
		}
	}

	/**
	 * Filtre la liste des catégories pour ne conserver que
	 * les catégories de $list ainsi que leurs catégories parentes.
	 *
	 * @param array $list
	 *	Identifiants des catégories à conserver.
	 * @return void
	 */
	protected static function _reduceMapCategories(&$list)
	{
		$categories_id = array(1);

		foreach (albums::$mapCategories as $id => &$infos)
		{
			if ($infos['cat_filemtime'] === NULL)
			{
				if (in_array($id, $list))
				{
					$parent_id = $id;
					while ($parent_id != 1)
					{
						if (!in_array($parent_id, $categories_id))
						{
							$categories_id[] = $parent_id;
						}
						$parent_id = albums::$mapCategories[$parent_id]['parent_id'];
					}
				}
			}
			else
			{
				unset(albums::$mapCategories[$id]);
			}

		}

		foreach (albums::$mapCategories as $id => &$infos)
		{
			if ($infos['cat_filemtime'] === NULL
			&& !in_array($id, $categories_id))
			{
				unset(albums::$mapCategories[$id]);
			}
		}
	}

	/**
	 * Initialisation pour les actions sur des objets.
	 *
	 * @return boolean|array
	 *	FALSE si données POST incorrectes.
	 *	Tableau des identifiants des objets sélectionnées si OK.
	 */
	protected static function _initObjectsActions()
	{
		if (isset($_POST['save']) || !isset($_POST['selection'])
		|| empty($_POST['action']) || empty($_POST['select'])
		|| !is_array($_POST['select']))
		{
			return FALSE;
		}

		$selected_ids = array_map('intval', array_keys($_POST['select']));
		if (!isset($selected_ids[0]))
		{
			return FALSE;
		}

		self::$report = array('error' => array(), 'warning' => array());

		return $selected_ids;
	}



	/**
	 * Supprime les incidents sélectionnés.
	 *
	 * @return void
	 */
	private static function _incidentsDelete()
	{
		foreach ($_POST['select'] as $file => &$select)
		{
			if (!preg_match('`^[a-z0-9_]{32,52}$`i', $file))
			{
				continue;
			}

			$xml_file = GALLERY_ROOT . '/errors/' . $file . '.xml';
			if (!file_exists($xml_file))
			{
				continue;
			}

			$unlink = files::unlink($xml_file);
			if (!isset($ok) || (isset($ok) && !$unlink))
			{
				$ok = $unlink;
			}
		}

		if (!empty($ok))
		{
			self::report('success:' . __('Les incidents sélectionnés ont été supprimés.'));
		}
	}

	/**
	 * Exporte les incidents sélectionnés.
	 *
	 * @return void
	 */
	private static function _incidentsExport()
	{
		// Fichiers à placer dans l'archive.
		$files = array();
		foreach ($_POST['select'] as $file => &$select)
		{
			if (!preg_match('`^[a-z0-9_]{32,52}$`i', $file))
			{
				continue;
			}

			$files[] = GALLERY_ROOT . '/errors/' . $file . '.xml';
		}

		// Envoi de l'archive.
		if (count($files))
		{
			utils::zipArchive('errors.zip', $files);
		}
	}
}

/**
 * Section "albums".
 */
class albums extends admin
{
	/**
	 * Liste des groupes ayant accès à l'objet courant protégé par un mot de passe.
	 *
	 * @var array
	 */
	public static $allowedGroups;

	/**
	 * Liste des groupes interdir d'accès à l'objet courant protégé par un mot de passe.
	 *
	 * @var array
	 */
	public static $forbiddenGroups;

	/**
	 * Liste de tous les groupes.
	 *
	 * @var array
	 */
	public static $groups;

	/**
	 * Informations de la catégorie.
	 *
	 * @var array
	 */
	public static $infos;

	/**
	 * Informations utiles des éléments de la catégorie courante.
	 *
	 * @var array
	 */
	public static $items;

	/**
	 * Liste de toutes les catégories de la galerie.
	 *
	 * @var array
	 */
	public static $listCategories;

	/**
	 * Liste de toutes les catégories de la galerie
	 * utilisées pour la liste déroulante "Parcourir".
	 *
	 * @var array
	 */
	public static $mapCategories;

	/**
	 * Nombre de pages.
	 *
	 * @var integer
	 */
	public static $nbPages;

	/**
	 * Nombre d'objets par page.
	 *
	 * @var integer
	 */
	public static $nbPerPage;

	/**
	 * Nombre d'objets de la catégorie.
	 *
	 * @var integer
	 */
	public static $nbItems;

	/**
	 * Page de la catégorie parente où se situe la catégorie actuelle.
	 *
	 * @var integer
	 */
	public static $parentPage;

	/**
	 * Liste des catégories parentes.
	 *
	 * @var array
	 */
	public static $parents;

	/**
	 * Liste des lieux connus.
	 *
	 * @var array
	 */
	public static $places = array();

	/**
	 * Style défini pour la catégorie courante.
	 *
	 * @var string
	 */
	public static $style;

	/**
	 * Liste de toutes les catégories de la galerie
	 * utilisées par le sous-plan.
	 *
	 * @var array
	 */
	public static $subMapCategories;



	/**
	 * Premier argument de la clause LIMIT pour la récupération des
	 * informations des catégories.
	 *
	 * @var integer
	 */
	protected static $_catSqlStart;



	/**
	 * Récupération des informations utiles des groupes
	 * ayant accès à l'objet courant protégé par un mot de passe.
	 *
	 * @return void
	 */
	public static function getAllowedGroups($password)
	{
		if ($password === NULL)
		{
			return;
		}

		$password = explode(':', $password, 2);
		$cat_id = $password[0];

		$sql = 'SELECT group_id,
					   group_name,
					   group_perms
				  FROM ' . CONF_DB_PREF . 'groups
			  ORDER BY group_id ASC';
		$fetch_style = array('fetch' => PDO::FETCH_ASSOC, 'column' => 'group_id');
		if (utils::$db->query($sql, $fetch_style) === FALSE
		|| utils::$db->nbResult === 0)
		{
			return;
		}

		self::$groups = utils::$db->queryResult;
		foreach (self::$groups as $group_id => &$i)
		{
			$perms = $i['group_perms'];
			if (!utils::isSerializedArray($perms))
			{
				continue;
			}
			$perms = unserialize($perms);
			if ($perms['gallery']['perms']['access_mode'] == 1
			|| ($perms['gallery']['perms']['access_mode'] == 2
				&& isset($perms['gallery']['albums_access'][$cat_id])))
			{
				unset($i['group_perms']);
				self::$allowedGroups[$group_id] = $i;
			}
			else
			{
				unset($i['group_perms']);
				self::$forbiddenGroups[$group_id] = $i;
			}
		}
	}

	/**
	 * Récupération des informations utiles des images
	 * pour le choix de la nouvelle vignette.
	 *
	 * @return void
	 */
	public static function getThumbsImages()
	{
		$images_per_page = 48;

		$i = self::$infos;

		if (isset($_GET['cat_id']))
		{
			$sql = 'SELECT cat_path,
						   cat_a_images,
						   cat_a_images + cat_d_images AS cat_images
					 FROM ' . CONF_DB_PREF . 'categories
					WHERE cat_id = ' . (int) $_GET['cat_id'];
			if (utils::$db->query($sql, 'row') === FALSE
			|| utils::$db->nbResult === 0)
			{
				utils::redirect('category/1');
				return;
			}
			$i = utils::$db->queryResult;
		}

		// Nombre d'images considérées selon le statut de la catégorie.
		if (self::$infos['cat_status'])
		{
			$status = ' AND image_status = "1"';
			$nb_images = (int) $i['cat_a_images'];
		}
		else
		{
			$status = '';
			$nb_images = (int) $i['cat_images'];
		}

		// Nombre de pages.
		self::$nbPages = ceil($nb_images / $images_per_page);
		self::$_catSqlStart = $images_per_page * ($_GET['page'] - 1);

		$sql = 'SELECT image_id,
					   image_name,
					   image_path,
					   image_width,
					   image_height,
					   image_tb_infos AS tb_infos,
					   image_adddt
				  FROM ' . CONF_DB_PREF . 'images
				 WHERE image_path LIKE CONCAT(:cat_path, "/%")'
					 . $status . '
			  ORDER BY cat_id DESC, image_id DESC
			     LIMIT ' . self::$_catSqlStart . ',' . $images_per_page;
		$params = array('cat_path' => $i['cat_path']);
		$fetch_style = array('fetch' => PDO::FETCH_ASSOC, 'column' => 'image_id');
		if (utils::$db->prepare($sql) === FALSE
		|| utils::$db->executeQuery($params, $fetch_style) === FALSE
		|| (utils::$db->nbResult === 0 && $_GET['page'] == 1))
		{
			utils::redirect('category/1');
		}
		else if (utils::$db->nbResult === 0 && $_GET['page'] > 1)
		{
			utils::redirect(admin::$sectionRequest, TRUE);
		}
		self::$items = utils::$db->queryResult;
	}

	/**
	 * Récupération des informations pour le plan.
	 *
	 * @param boolean $from_current
	 *	Doit-on construire le plan à partir de la catégorie courante ?
	 * @param string $where
	 *	Clause WHERE additionnelle.
	 * @return void
	 */
	public static function getMap($from_current = FALSE, $where = '')
	{
		$sql_where = '';
		$params = array();
		$cat_id = 1;
		if ($from_current)
		{
			$sql_where .= ' AND (cat_path LIKE CONCAT(:cat_path, "/%") OR cat_path = :cat_path)';
			$params = array('cat_path' => albums::$infos['cat_path']);
			$cat_id = albums::$infos['cat_id'];
		}
		if ($where)
		{
			$sql_where .= ' ' . $where;
		}

		// Récupération de toutes les catégories.
		$sql = 'SELECT cat_id,
					   parent_id,
					   cat_name,
					   cat_a_size,
					   cat_a_images,
					   cat_lastadddt,
					   cat_filemtime
				  FROM ' . CONF_DB_PREF . 'categories
				 WHERE 1=1'
					 . $sql_where
					 . sql::$groupAlbumsAccess . '
			  ORDER BY cat_name ASC';
		$fetch_style = array('fetch' => PDO::FETCH_ASSOC, 'column' => 'cat_id');
		if (utils::$db->prepare($sql) === FALSE
		|| utils::$db->executeQuery($params, $fetch_style) === FALSE
		|| utils::$db->nbResult === 0)
		{
			return;
		}
		self::$listCategories = self::$mapCategories = utils::$db->queryResult;
	}

	/**
	 * Récupération des lieux connus.
	 *
	 * @return void
	 */
	public static function getPlaces()
	{
		$sql = 'SELECT image_place AS place,
					   image_lat AS latitude,
					   image_long AS longitude
				  FROM ' . CONF_DB_PREF . 'images AS img
			 LEFT JOIN ' . CONF_DB_PREF . 'categories AS cat
					ON img.cat_id = cat.cat_id
				 WHERE image_place IS NOT NULL
				   AND image_lat IS NOT NULL
				   AND image_long IS NOT NULL'
					 . sql::$groupAlbumsAccess . '
				 UNION
				SELECT cat_place AS place,
					   cat_lat AS latitude,
					   cat_long AS longitude
				  FROM ' . CONF_DB_PREF . 'categories AS cat
				 WHERE cat_place IS NOT NULL
				   AND cat_lat IS NOT NULL
				   AND cat_long IS NOT NULL'
					 . sql::$groupAlbumsAccess . '
			  GROUP BY place, latitude, longitude
			  ORDER BY LOWER(place) ASC';
		if (utils::$db->query($sql, PDO::FETCH_ASSOC) === FALSE
		|| utils::$db->nbResult === 0)
		{
			return;
		}
		self::$places = utils::$db->queryResult;
	}

	/**
	 * Édition en masse des images sélectionnées.
	 *
	 * @return void
	 */
	public static function massEdit()
	{
		// Quelques vérifications...
		if (empty($_POST)
		|| !isset($_POST['selected_ids']) || !is_string($_POST['selected_ids'])
		|| !isset($_POST['orderby']) || !isset($_POST['sortby'])
		|| !in_array($_POST['orderby'], array('ASC', 'DESC'))
		|| !in_array($_POST['sortby'], array('adddt', 'name', 'path', 'position')))
		{
			return;
		}
		$selected_ids = explode(',', $_POST['selected_ids']);
		foreach ($selected_ids as &$id)
		{
			if (!preg_match('`^\d{1,11}$`', $id))
			{
				return;
			}
		}

		try
		{
			// Début de la transaction.
			if (!utils::$db->transaction())
			{
				throw new Exception('error:' . utils::$db->msgError);
			}

			// Récupération des informations utiles des images sélectionnées.
			$sql = 'SELECT cat.cat_id,
						   cat_name,
						   cat_desc,
						   image_id,
						   image_name,
						   image_path,
						   image_url,
						   image_height,
						   image_width,
						   image_filesize,
						   image_desc,
						   image_place,
						   image_lat,
						   image_long,
						   image_crtdt
					  FROM ' . CONF_DB_PREF . 'images AS img
				 LEFT JOIN ' . CONF_DB_PREF . 'categories AS cat
						ON img.cat_id = cat.cat_id
					 WHERE image_id IN (' . implode(', ', $selected_ids) . ')
					   ' . sql::$groupAlbumsAccess . '
				  ORDER BY LOWER(image_' . $_POST['sortby'] . ') '
					. $_POST['orderby'] . ', image_id DESC';
			if (utils::$db->query($sql, PDO::FETCH_ASSOC) === FALSE
			|| utils::$db->nbResult === 0)
			{
				throw new Exception('error:' . utils::$db->msgError);
			}
			$images_infos = utils::$db->queryResult;

			$update_crtdt = isset($_POST['crtdt_day']) && isset($_POST['crtdt_month'])
				&& isset($_POST['crtdt_year']) && isset($_POST['crtdt_hour'])
				&& isset($_POST['crtdt_minute']) && isset($_POST['crtdt_second'])
				&& preg_match('`^(\d{2}|{DAY})$`', $_POST['crtdt_day'])
				&& preg_match('`^(\d{2}|{MONTH})$`', $_POST['crtdt_month'])
				&& preg_match('`^(\d{4}|{YEAR})$`', $_POST['crtdt_year'])
				&& preg_match('`^(\d{2}|{HOUR})$`', $_POST['crtdt_hour'])
				&& preg_match('`^(\d{2}|{MINUTE})$`', $_POST['crtdt_minute'])
				&& preg_match('`^(\d{2}|{SECOND})$`', $_POST['crtdt_second']);

			$report_title_warning_1 = FALSE;
			$report_urlname_warning_1 = FALSE;

			// Compteur.
			$counter = (isset($_POST['counter_start'])
			&& preg_match('`^\d{1,16}$`', $_POST['counter_start']))
				? (int) $_POST['counter_start']
				: 1;

			// Pour chaque image.
			$params = array();
			foreach ($images_infos as &$infos)
			{
				$update = array(
					'image_id' => $infos['image_id'],
					'image_crtdt' => $infos['image_crtdt'],
					'image_name' => $infos['image_name'],
					'image_url' => $infos['image_url'],
					'image_desc' => $infos['image_desc']
				);

				// Nom de fichier.
				$image_filename = preg_replace('`^.+/([^/]+)$`', '$1', $infos['image_path']);
				$image_filename_temp = preg_replace('`^(.+)\.[a-z]+$`i', '$1', $image_filename);
				$image_filename_title = str_replace('_', ' ', $image_filename_temp);
				$image_filename_urlname = str_replace('_', '-', $image_filename_temp);

				// Variables disponibles.
				$vars_replace = array(
					'{ALBUM_DESCRIPTION}',
					'{ALBUM_ID}',
					'{ALBUM_TITLE}',
					'{COUNTER}',
					'{IMAGE_CRTDT}',
					'{IMAGE_DESCRIPTION}',
					'{IMAGE_FILENAME}',
					'{IMAGE_FILENAME_TITLE}',
					'{IMAGE_FILENAME_URLNAME}',
					'{IMAGE_FILESIZE}',
					'{IMAGE_HEIGHT}',
					'{IMAGE_ID}',
					'{IMAGE_LATITUDE}',
					'{IMAGE_LONGITUDE}',
					'{IMAGE_PATH}',
					'{IMAGE_PLACE}',
					'{IMAGE_TITLE}',
					'{IMAGE_URLNAME}',
					'{IMAGE_WIDTH}'
				);

				// Valeurs de variables correspondantes.
				$vals_replace = array(
					$infos['cat_desc'],
					$infos['cat_id'],
					$infos['cat_name'],
					$counter,
					$infos['image_crtdt'],
					$infos['image_desc'],
					$image_filename,
					$image_filename_title,
					$image_filename_urlname,
					$infos['image_filesize'],
					$infos['image_height'],
					$infos['image_id'],
					$infos['image_lat'],
					$infos['image_long'],
					$infos['image_path'],
					$infos['image_place'],
					$infos['image_name'],
					$infos['image_url'],
					$infos['image_width']
				);
				$vals_replace_langs = array();
				foreach (utils::$config['locale_langs'] as $code => $lang)
				{
					foreach ($vals_replace as $value)
					{
						$vals_replace_langs[$code][] = utils::getLocale($value, $code);
					}
				}

				// Titre.
				if (isset($_POST['title']) && is_array($_POST['title'])
				&& !$report_title_warning_1)
				{
					$post_title = $_POST['title'];
					foreach ($post_title as $lang => &$v)
					{
						$v = str_replace($vars_replace, $vals_replace_langs[$lang], $v);
					}
					$text = utils::setLocaleText($post_title, $infos['image_name'], 255, TRUE);
					if ($text['empty'])
					{
						$report_title_warning_1 = TRUE;
					}
					else if ($text['change'])
					{
						$update['image_name'] = $text['data'];
					}
				}

				// Nom d'URL.
				if (isset($_POST['urlname']) && !$report_urlname_warning_1)
				{
					// Vérification de la longueur.
					if (mb_strlen($_POST['urlname']) < 1)
					{
						$report_urlname_warning_1 = TRUE;
					}
					else
					{
						$update['image_url'] = str_replace($vars_replace,
							$vals_replace, $_POST['urlname']);
					}
				}

				// Date de création.
				if ($update_crtdt)
				{
					$year = $_POST['crtdt_year'];
					$month = $_POST['crtdt_month'];
					$day = $_POST['crtdt_day'];
					$hour = $_POST['crtdt_hour'];
					$minute = $_POST['crtdt_minute'];
					$second = $_POST['crtdt_second'];
					$date = $year . $month . $day . $hour . $minute . $second;

					// Suppression de la date.
					if ($year . $month . $day === '00000000')
					{
						$update['image_crtdt'] = NULL;
					}

					// Modification de la date ou d'une partie de la date.
					else if (($day == '{DAY}' || ($day <= 31 && $day >= 1))
					 && ($month == '{MONTH}' || ($month <= 12 && $month >= 1))
					 && ($year == '{YEAR}' || ($year <= date('Y') && $year >= 1900))
					 && ($hour == '{HOUR}' || ($hour <= 24 && $hour >= 0))
					 && ($minute == '{MINUTE}' || ($minute <= 59 && $minute >= 0))
					 && ($second == '{SECOND}' || ($second <= 59 && $second >= 0))
					 && $date != '{YEAR}{MONTH}{DAY}{HOUR}{MINUTE}{SECOND}')
					{
						if (($infos['image_crtdt'] === NULL &&
						($day == '{DAY}' || $month == '{MONTH}'
						|| $year == '{YEAR}' || $hour == '{HOUR}'
						|| $minute == '{MINUTE}' || $second == '{SECOND}')) === FALSE)
						{
							if ($infos['image_crtdt'] !== NULL)
							{
								$old_crtdt = explode(' ', $infos['image_crtdt']);

								// Date.
								$old_date = explode('-', $old_crtdt[0]);
								if ($year == '{YEAR}')
								{
									$year = $old_date[0];
								}
								if ($month == '{MONTH}')
								{
									$month = $old_date[1];
								}
								if ($day == '{DAY}')
								{
									$day = $old_date[2];
								}

								// Heure.
								$old_time = explode(':', $old_crtdt[1]);
								if ($hour == '{HOUR}')
								{
									$hour = $old_time[0];
								}
								if ($minute == '{MINUTE}')
								{
									$minute = $old_time[1];
								}
								if ($second == '{SECOND}')
								{
									$second = $old_time[2];
								}
							}
							$update['image_crtdt'] = $year . '-' . $month . '-' . $day
								. ' ' . $hour . ':' . $minute . ':' . $second;
						}
					}
				}

				// Description.
				if (isset($_POST['description']) && is_array($_POST['description']))
				{
					$post_desc = $_POST['description'];
					foreach ($post_desc as $lang => &$v)
					{
						$v = str_replace($vars_replace, $vals_replace_langs[$lang], $v);
					}
					$text = utils::setLocaleText($post_desc, $infos['image_desc'], 5000);
					if ($text['change'])
					{
						$update['image_desc'] = $text['data'];
					}
				}

				// Transformation du titre, du nom d'URL et de la description.
				foreach (array('name', 'url', 'desc') as $field)
				{
					if (isset($_POST[$field . '_transform'])
					&& $_POST[$field . '_transform'] != 'none')
					{
						$text_langs = array();
						foreach (utils::$config['locale_langs'] as $code => $lang)
						{
							$text_langs[$code]
								= utils::getLocale($update['image_' . $field], $code);
						}

						switch ($_POST[$field . '_transform'])
						{
							case 'lowercase' :
								foreach ($text_langs as &$v)
								{
									$v = strtolower($v);
								}
								break;

							case 'lowercase_ucfirst' :
								foreach ($text_langs as &$v)
								{
									$v = ucfirst($v);
								}
								break;

							case 'lowercase_ucwords' :
								foreach ($text_langs as &$v)
								{
									$v = ucwords($v);
								}
								break;

							case 'uppercase' :
								foreach ($text_langs as &$v)
								{
									$v = strtoupper($v);
								}
								break;
						}

						$text = utils::setLocaleText($text_langs, $update['image_' . $field]);
						if ($text['change'])
						{
							$update['image_' . $field] = $text['data'];
						}
					}
				}

				$params[] = $update;
				$counter++;
			}

			// Messages d'avertissement.
			if ($report_title_warning_1)
			{
				self::report('warning:'
					. __('Le titre doit contenir au moins 1 caractère.'));
			}
			if ($report_urlname_warning_1)
			{
				self::report('warning:'
					. __('Le nom d\'URL doit contenir au moins 1 caractère.'));
			}

			$success = FALSE;

			// Mise à jour de la base de données.
			$sql = 'UPDATE ' . CONF_DB_PREF . 'images
					   SET image_crtdt = :image_crtdt,
					       image_name = :image_name,
						   image_url = :image_url,
						   image_desc = :image_desc
					 WHERE image_id = :image_id';
			$sql = array(array('sql' => $sql, 'params' => $params));
			if (utils::$db->exec($sql, FALSE) === FALSE)
			{
				throw new Exception('error:' . utils::$db->msgError);
			}
			if (utils::$db->nbResult > 0)
			{
				$success = TRUE;
			}

			// Suppression de tags.
			if (isset($_POST['tags_delete_all']))
			{
				$report = tagsImage::removeAll($selected_ids, FALSE);
				if (substr($report, 0, 5) == 'error')
				{
					throw new Exception($report);
				}
				if (substr($report, 0, 7) == 'success')
				{
					$success = TRUE;
				}
			}
			else if (isset($_POST['tags_remove']) && !utils::isEmpty($_POST['tags_remove']))
			{
				$report = tagsImage::remove($selected_ids, $_POST['tags_remove'], FALSE);
				if (substr($report, 0, 5) == 'error')
				{
					throw new Exception($report);
				}
				if (substr($report, 0, 7) == 'success')
				{
					$success = TRUE;
				}
			}

			// Ajout de tags.
			if (isset($_POST['tags_add']) && !utils::isEmpty($_POST['tags_add']))
			{
				$report = tagsImage::add($selected_ids, $_POST['tags_add'], FALSE);
				if (substr($report, 0, 5) == 'error')
				{
					throw new Exception($report);
				}
				if (substr($report, 0, 7) == 'success')
				{
					$success = TRUE;
				}
			}

			// Exécution de la transaction.
			if (!utils::$db->commit())
			{
				throw new Exception('error:' . utils::$db->msgError);
			}

			if ($success)
			{
				self::report('success:' . __('Modifications enregistrées.'));
				self::report('success_p:'
					. __('Les autres modifications ont été enregistrées.'));
			}
		}
		catch (Exception $e)
		{
			self::report($e->getMessage());
		}
	}

	/**
	 * Paramètres pour la gestion des pages.
	 *
	 * @return void
	 */
	public static function pages()
	{
		// Nombre de pages.
		self::$nbPages = ceil((int) self::$nbItems / self::$nbPerPage);

		// Premier argument de la clause LIMIT pour la récupération
		// des informations des catégories, qui est fonction de la page courante.
		self::$_catSqlStart = self::$nbPerPage * ($_GET['page'] - 1);
	}

	/**
	 * Détermine le numéro de page de la catégorie parente.
	 *
	 * @return void
	 */
	public static function parentPage()
	{
		// On n'effectue de requête uniquement pour les catégories et albums,
		if ($_GET['object_id'] == 1)
		{
			return;
		}

		// Identifiant de la catégorie parente.
		if (is_array(self::$parents))
		{
			$parent = end(self::$parents);

			// Si la catégorie parente ne contient pas un nombre de catégories
			// supérieur au nombre de vignettes par page,
			// inutile d'aller plus loin.
			if ($parent['cat_subs'] <= auth::$infos['user_prefs']['category']['nb_per_page'])
			{
				return;
			}

			$cat_id = (int) $parent['cat_id'];
		}
		else
		{
			$cat_id = 1;
		}

		// Récupération des informations utiles de toutes les catégories
		// qui ont le même parent que la catégorie courante.
		$order_by = str_replace('adddt', 'crtdt',
			auth::$infos['user_prefs']['category']['sortby']);
		$ascdesc = auth::$infos['user_prefs']['category']['orderby'];
		$order_by = 'LOWER(cat_' . $order_by . ') '
			. $ascdesc . ', cat_id ' . $ascdesc;
		$sql = 'SELECT cat_id,
					   cat_name,
					   cat_filemtime
				  FROM ' . CONF_DB_PREF . 'categories
				 WHERE parent_id = ' . $cat_id . '
				   AND cat_id != 1 '
					 . sql::$groupAlbumsAccess . '
			  ORDER BY ' . $order_by;
		utils::$db->query($sql, PDO::FETCH_ASSOC);
		$neighbours = utils::$db->queryResult;

		// Position de la catégorie actuelle par rapport à ses voisines.
		$neighboursPosition = 0;
		for ($i = 0; $i < utils::$db->nbResult; $i++)
		{
			if ($neighbours[$i]['cat_id'] == $_GET['object_id'])
			{
				$neighboursPosition = $i + 1;
				break;
			}
		}

		// Page de la catégorie parente où se situe la catégorie actuelle.
		$parent_page = ceil($neighboursPosition
			/ auth::$infos['user_prefs']['category']['nb_per_page']);
		if ($parent_page > 1)
		{
			self::$parentPage = $parent_page;
		}
	}

	/**
	 * Récupère les informations utiles des
	 * catégories parentes de la catégorie courante.
	 *
	 * @param string $type
	 * @param string $path
	 * @return void
	 */
	public static function parents($type = 'cat', $path = FALSE)
	{
		self::$parents = NULL;

		$path = ($path) ? $path : self::$infos[$type . '_path'];
		if ($path == '')
		{
			return;
		}

		$parent = dirname($path);
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
			$sql = 'SELECT cat_id,
						   cat_name,
						   cat_commentable,
						   cat_creatable,
						   cat_uploadable,
						   cat_votable,
						   cat_a_subalbs + cat_a_subcats +
						   cat_d_subalbs + cat_d_subcats AS cat_subs,
						   cat_style,
						   cat_filemtime
					  FROM ' . CONF_DB_PREF . 'categories
					 WHERE ' . $sql . '
				  ORDER BY LENGTH(cat_path) ASC';
			$fetch_style = array('fetch' => PDO::FETCH_ASSOC, 'column' => 'cat_id');
			if (utils::$db->prepare($sql) === FALSE
			|| utils::$db->executeQuery($params, $fetch_style) === FALSE
			|| utils::$db->nbResult === 0)
			{
				return;
			}
			self::$parents = utils::$db->queryResult;

			// Détermine si les commentaires et les votes
			// sont désactivés pour au moins un parent.
			foreach (self::$parents as &$parent_infos)
			{
				self::_parentsSettings(albums::$infos, $parent_infos);
			}
		}
	}

	/**
	 * Tri des images et catégories.
	 *
	 * @param $type
	 *	Images ou catégories à trier.
	 * @return void
	 */
	public static function sort($type)
	{
		if (empty($_POST['sort']) || empty($_POST['serial']))
		{
			return;
		}

		// Vérification du format de l'ordre des vignettes.
		$new_positions = (string) $_POST['serial'];
		if (!preg_match('`(?:i\[\]=\d{1,11}&)*(?:i\[\]=\d{1,11})`', $new_positions))
		{
			return;
		}

		// On le convertit en tableau.
		$new_positions = str_replace('i[]=', '', $new_positions);
		$new_positions = explode('&', $new_positions);

		try
		{
			// Début de la transaction.
			if (!utils::$db->transaction())
			{
				throw new Exception();
			}

			$sql_table = ($type == 'cat') ? 'categories' : 'images';
			$sql_where = ($type == 'cat') ? 'parent' : 'cat';
			$sql_where_exception = ($type == 'cat')
				? ' AND ' . $type . '_position != 1
					AND cat_id != 1'
				: '';

			// On récupère les anciennes positions.
			$sql = 'SELECT ' . $type . '_id,
						   ' . $type . '_position
					  FROM ' . CONF_DB_PREF . $sql_table . '
					 WHERE ' . $sql_where . '_id = ' . (int) self::$infos['cat_id']
							 . $sql_where_exception . '
				  ORDER BY ' . $type . '_position ASC';
			if (utils::$db->query($sql, PDO::FETCH_ASSOC) === FALSE
			|| utils::$db->nbResult != count($new_positions))
			{
				throw new Exception();
			}
			$old_positions = utils::$db->queryResult;

			// On met à jour uniquement les positions qui ont changées.
			$sql = 'UPDATE ' . CONF_DB_PREF . $sql_table . '
					   SET ' . $type . '_position = :new_position
					 WHERE ' . $sql_where . '_id = ' . (int) self::$infos['cat_id'] . '
					   AND ' . $type . '_id = :' . $type . '_id';
			$params = array();
			foreach ($new_positions as $i => &$id)
			{
				if ($id == $old_positions[$i][$type . '_id'])
				{
					continue;
				}
				$params[] = array(
					'new_position' => $old_positions[$i][$type . '_position'],
					$type . '_id' => $id
				);
			}
			if (empty($params))
			{
				utils::$db->rollBack();
				return;
			}
			$sql = array(array('sql' => $sql, 'params' => $params));
			if (utils::$db->exec($sql, FALSE) === FALSE
			|| utils::$db->nbResult == 0)
			{
				throw new Exception();
			}

			// On vérifie qu'il n'y a pas de positions doublons dans la catégorie.
			$sql = 'SELECT DISTINCT ' . $type . '_position
					  FROM ' . CONF_DB_PREF . $sql_table . '
					 WHERE ' . $sql_where . '_id = ' . (int) self::$infos['cat_id']
							 . $sql_where_exception;
			$compare = ($type == 'cat') ? 'cat_subs' : 'cat_images';
			if (utils::$db->query($sql, PDO::FETCH_ASSOC) === FALSE
			|| utils::$db->nbResult != self::$infos[$compare])
			{
				throw new Exception();
			}

			// Exécution de la transaction.
			if (!utils::$db->commit())
			{
				throw new Exception();
			}

			$success_message = ($type == 'cat')
				? __('L\'ordre des catégories a été enregistré.')
				: __('L\'ordre des images a été enregistré.');
			self::report('success:' . $success_message);
		}
		catch (Exception $e)
		{
			self::report('error:' . utils::$db->msgError);
		}
	}

	/**
	 * Action à effectuer sur la vignette.
	 *
	 * @param $type
	 *	Type de la vignette : catégorie ou image
	 * @return void
	 */
	public static function thumbChange($type)
	{
		if (empty($_POST['action']))
		{
			return;
		}

		switch ($_POST['action'])
		{
			// Rognage.
			case 'crop' :
				self::_thumbCrop($type);
				break;

			// Suppression.
			case 'delete' :
				self::_thumbDelete($type);
				break;

			// Nouvelle vignette par image externe.
			case 'external' :
				self::_thumbExternal();
				break;

			// Nouvelle vignette par identifiant ou sélection.
			case 'new_by_id' :
			case 'new_by_select' :
				self::_thumbNew();
				break;
		}
	}

	/**
	 * Détermine les dimensions de l'image redimensionnée.
	 *
	 * @param string $type
	 * @return void
	 */
	public static function thumbPreview($type)
	{
		// On récupère les dimensions de l'image si c'est une image externe.
		if ($type == 'cat' && self::$infos['thumb_id'] == 0)
		{
			$tb_infos = explode('.', self::$infos['tb_infos']);
			self::$infos['image_width'] = $tb_infos[4];
			self::$infos['image_height'] = $tb_infos[5];
		}

		$size = img::imageResize(self::$infos['image_width'],
			self::$infos['image_height'], 400, 400);
		self::$infos['preview_width'] = $size['width'];
		self::$infos['preview_height'] = $size['height'];
	}



	/**
	 * Édition de la date de création d'une image.
	 *
	 * @param integer $id
	 *	Identifiant de l'objet.
	 * @param array $infos
	 *	Informations de l'objet.
	 * @return string|boolean
	 */
	protected static function _editCreationDate($id, &$infos)
	{
		// Vérification de la présence et du format de la date.
		if (!isset($_POST[$id]['crtdt_day'])
		 || !isset($_POST[$id]['crtdt_month'])
		 || !isset($_POST[$id]['crtdt_year'])
		 || !isset($_POST[$id]['crtdt_hour'])
		 || !isset($_POST[$id]['crtdt_minute'])
		 || !isset($_POST[$id]['crtdt_second'])
		 || !preg_match('`^\d{2}$`', $_POST[$id]['crtdt_day'])
		 || !preg_match('`^\d{2}$`', $_POST[$id]['crtdt_month'])
		 || !preg_match('`^\d{4}$`', $_POST[$id]['crtdt_year'])
		 || !preg_match('`^\d{2}$`', $_POST[$id]['crtdt_hour'])
		 || !preg_match('`^\d{2}$`', $_POST[$id]['crtdt_minute'])
		 || !preg_match('`^\d{2}$`', $_POST[$id]['crtdt_second']))
		{
			return FALSE;
		}

		$day = $_POST[$id]['crtdt_day'];
		$month = $_POST[$id]['crtdt_month'];
		$year = $_POST[$id]['crtdt_year'];

		// Vérification de la valeur de la date.
		if ((($day <= 31 && $day >= 1
		 && $month <= 12 && $month >= 1
		 && $year <= date('Y') && $year >= 1900)
		 || ($year . $month . $day) === '00000000') === FALSE)
		{
			return FALSE;
		}

		$hour = $_POST[$id]['crtdt_hour'];
		$minute = $_POST[$id]['crtdt_minute'];
		$second = $_POST[$id]['crtdt_second'];

		// Vérification de la valeur de l'heure.
		if (($hour <= 23 && $day >= 0
		 && $minute <= 59 && $minute >= 0
		 && $second <= 59 && $second >= 0) === FALSE)
		{
			return FALSE;
		}

		// La date est-elle identique ?
		$post_crtdt = $year . '-' . $month . '-' . $day
			. ' ' . $hour . ':' . $minute . ':' . $second;
		if (($year . $month . $day) === '00000000')
		{
			$post_crtdt = NULL;
		}
		if ($post_crtdt === $infos['image_crtdt'])
		{
			return FALSE;
		}

		return $infos['image_crtdt'] = $post_crtdt;
	}

	/**
	 * Édition de la description.
	 *
	 * @param integer $id
	 *	Identifiant de l'objet.
	 * @param array $infos
	 *	Informations de l'objet.
	 * @param string $column_name
	 *	'image_desc' ou 'cat_desc'.
	 * @return string|boolean
	 */
	protected static function _editDescription($id, &$infos, $column_name)
	{
		if (!isset($_POST[$id]['description']))
		{
			return FALSE;
		}

		$text = utils::setLocaleText($_POST[$id]['description'], $infos[$column_name], 5000);

		return $text['change']
			? $text['data']
			: FALSE;
	}

	/**
	 * Édition des coordonnées de géolocalisation.
	 *
	 * @param integer $id
	 *	Identifiant de l'objet.
	 * @param array $infos
	 *	Informations de l'objet.
	 * @param string $column_name
	 * @return string|boolean
	 */
	protected static function _editGeoloc($id, &$infos, $column_name)
	{
		try
		{
			$type = explode('_', $column_name);
			$type = ($type[1] == 'lat') ? 'latitude' : 'longitude';

			if (!isset($_POST[$id][$type]))
			{
				return FALSE;
			}

			// Supprime la coordonnée.
			if ($_POST[$id][$type] === '')
			{
				return ($infos[$column_name] === NULL) ? FALSE : NULL;
			}

			$warning_message = sprintf(
				__('Format de la %s incorrect'),
				($type == 'latitude') ? __('latitude') : __('longitude')
			);

			// Formats de la coordonnée.
			$coord = trim(str_replace(',', '.', $_POST[$id][$type]));
			$regex_1 = '`^(?:[-+]?\d{1,3}(?:\.\d+)?)?$`';
			$regex_2 = '`^([-+])?(\d{1,3})\s*°?\s*(\d{1,2})\s*[\'′]?\s*'
				. '(\d+(?:\.\d+)?)\s*["″]?\s*([ENOSW])?$`iu';
			if (preg_match($regex_1, $coord))
			{
				$coord = (float) $coord;
			}
			else if (preg_match($regex_2, $coord, $m))
			{
				$s = (isset($m[5]))
					? ((in_array(strtoupper($m[5]), array('E','N'))) ? '' : '-')
					: $m[1];
				$coord = (float) ($s . ($m[2] + ($m[3] / 60) + ($m[4] / 3600)));
			}
			else
			{
				throw new Exception('warning:' . $warning_message);
			}

			// Si la coordonnée entrée est identique
			// à celle enregistrée en base de données,
			// inutile d'aller plus loin.
			if ((float) (string) $coord == (float) (string) $infos[$column_name])
			{
				return FALSE;
			}

			// Bornes.
			if ($type == 'latitude'
			&& ($coord > 90 || $coord < -90))
			{
				throw new Exception('warning:' . $warning_message);
			}
			if ($type == 'longitude'
			&& ($coord > 180 || $coord < -180))
			{
				throw new Exception('warning:' . $warning_message);
			}

			return (string) $coord;
		}
		catch (Exception $e)
		{
			self::report($e, $id);

			return FALSE;
		}
	}

	/**
	 * Édition du lieu pour géolocalisation.
	 *
	 * @param integer $id
	 *	Identifiant de l'objet.
	 * @param array $infos
	 *	Informations de l'objet.
	 * @param string $column_name
	 * @return string|boolean
	 */
	protected static function _editPlace($id, &$infos, $column_name)
	{
		if (!isset($_POST[$id]['place'])
		|| $_POST[$id]['place'] == $infos[$column_name])
		{
			return FALSE;
		}

		return $_POST[$id]['place'];
	}

	/**
	 * Édition du titre.
	 *
	 * @param integer $id
	 *	Identifiant de l'objet.
	 * @param array $infos
	 *	Informations de l'objet.
	 * @param string $column_name
	 *	'image_name' ou 'cat_name'.
	 * @return string|boolean
	 */
	protected static function _editTitle($id, &$infos, $column_name)
	{
		if (!isset($_POST[$id]['title']))
		{
			return FALSE;
		}

		$text = utils::setLocaleText($_POST[$id]['title'], $infos[$column_name], 255, TRUE);

		if ($text['empty'])
		{
			self::report('warning:'
				. __('Le titre doit contenir au moins 1 caractère.'), $id);
		}

		return $text['change']
			? $text['data']
			: FALSE;
	}

	/**
	 * Édition du nom d'URL.
	 *
	 * @param integer $id
	 *	Identifiant de l'objet.
	 * @param array $infos
	 *	Informations de l'objet.
	 * @param string $column_name
	 *	'image_url' ou 'cat_url'.
	 * @return string|boolean
	 */
	protected static function _editURLName($id, &$infos, $column_name)
	{
		if (!isset($_POST[$id]['urlname'])
		|| ($new_urlname = str_replace('/', '', $_POST[$id]['urlname']))
		=== $infos[$column_name])
		{
			return FALSE;
		}

		// Vérification de la longueur.
		if (mb_strlen($new_urlname) < 1)
		{
			self::report('warning:'
				. __('Le nom d\'URL doit contenir au moins 1 caractère.'), $id);
			return FALSE;
		}

		return $new_urlname;
	}

	/**
	 * Autorise ou non les réglages de la catégorie courante
	 * en fonction des réglages des catégories parentes.
	 *
	 * @param array $cat_infos
	 * @param array $parent_infos
	 * @return void
	 */
	protected static function _parentsSettings(&$cat_infos, &$parent_infos)
	{
		if ($parent_infos['cat_commentable'] == 0)
		{
			$cat_infos['cat_commentable_parent'] = 0;
			$cat_infos['cat_commentable'] = 0;
		}
		if ($parent_infos['cat_creatable'] == 0)
		{
			$cat_infos['cat_creatable_parent'] = 0;
			$cat_infos['cat_creatable'] = 0;
		}
		if ($parent_infos['cat_uploadable'] == 0)
		{
			$cat_infos['cat_uploadable_parent'] = 0;
			$cat_infos['cat_uploadable'] = 0;
		}
		if ($parent_infos['cat_votable'] == 0)
		{
			$cat_infos['cat_votable_parent'] = 0;
			$cat_infos['cat_votable'] = 0;
		}
		if (empty($cat_infos['cat_style'])
		 && is_array(category::$styles)
		 && in_array($parent_infos['cat_style'], category::$styles))
		{
			$cat_infos['cat_style'] = $parent_infos['cat_style'];
		}
	}



	/**
	 * Rognage de la vignette.
	 *
	 * @param string $type
	 *	Type de la vignette : catégorie ou image.
	 * @return void
	 */
	private static function _thumbCrop($type)
	{
		if (empty($_POST['coords']) || !preg_match('`^[\d\s,.]{8,50}$`', $_POST['coords']))
		{
			return;
		}

		try
		{
			$error_message = __('Impossible de recréer la vignette.');

			$new_coords = explode('.', $_POST['coords']);

			// S'il existe des coordonnées de rognage.
			if (!empty(self::$infos['tb_infos']))
			{
				$mode = ($type == 'cat')
					? CONF_THUMBS_CAT_METHOD
					: CONF_THUMBS_IMG_METHOD;
				$old_coords = explode('.', self::$infos['tb_infos']);
				$sconf = img::isThumbConfCrop($type, $mode, $old_coords);

				// Et que ces coordonnées sont identiques, inutile d'aller plus loin.
				if ($sconf && $new_coords[1] == $old_coords[3])
				{
					return;
				}

				// Et que ces coordonnées ne correspondent pas
				// aux dimensions de la configuration actuelle des vignettes,
				// alors on supprime la vignette correspondant
				// à cette ancienne configuration.
				if (!$sconf)
				{
					$method = ($old_coords[0] == 0) ? 'crop' : 'prop';
					$tb_quality = ($type == 'cat')
						? CONF_THUMBS_CAT_QUALITY
						: CONF_THUMBS_IMG_QUALITY;
					$size = $method . '|' . $tb_quality . '|'
						 . ($method == 'crop' ? $old_coords[1] . '|'
						 . $old_coords[2] : $old_coords[0]);

					// Vignette externe.
					if (self::$infos['thumb_id'] == 0)
					{
						$tb_file = GALLERY_ROOT . '/' . img::filepath('tb_cat',
							'i.' . $old_coords[6], self::$infos['cat_id'],
							self::$infos['cat_crtdt'], $size);
					}

					// Vignette interne.
					else
					{
						$tb_file = ($type == 'cat')
							? GALLERY_ROOT . '/' . img::filepath('tb_cat',
								self::$infos['image_path'], self::$infos['cat_id'],
								self::$infos['cat_crtdt'], $size)
							: GALLERY_ROOT . '/' . img::filepath('tb_img',
								self::$infos['image_path'], self::$infos['image_id'],
								self::$infos['image_adddt'], $size);
					}

					if (file_exists($tb_file))
					{
						files::unlink($tb_file);
					}
				}
			}

			// Vignette externe.
			if ($type == 'cat' && self::$infos['thumb_id'] == 0)
			{
				$tbi = explode('.', self::$infos['tb_infos']);
				$img_file = GALLERY_ROOT . '/' . img::filepath('im_external',
					'i.' . $tbi[6], self::$infos['cat_id'], self::$infos['cat_crtdt']);
				$tb_file = GALLERY_ROOT . '/' . img::filepath('tb_cat',
					'i.' . $tbi[6], self::$infos['cat_id'], self::$infos['cat_crtdt']);
				$edit_file = GALLERY_ROOT . '/' . img::filepath('im_edit',
					'i.' . $tbi[6], self::$infos['cat_id'], self::$infos['cat_crtdt']);
				$tb_quality = CONF_THUMBS_CAT_QUALITY;
			}

			// Vignette interne.
			else
			{
				if ($type == 'cat')
				{
					$tb_file = GALLERY_ROOT . '/' . img::filepath('tb_cat',
						self::$infos['image_path'], self::$infos['cat_id'],
						self::$infos['cat_crtdt']);
					$tb_quality = CONF_THUMBS_CAT_QUALITY;
				}
				else
				{
					$tb_file = GALLERY_ROOT . '/' . img::filepath('tb_img',
						self::$infos['image_path'], self::$infos['image_id'],
						self::$infos['image_adddt']);
					$tb_quality = CONF_THUMBS_IMG_QUALITY;
				}
				$img_file = GALLERY_ROOT . '/' . CONF_ALBUMS_DIR
					. '/' . self::$infos['image_path'];
				$edit_file = GALLERY_ROOT . '/' . img::filepath('im_edit',
					self::$infos['image_path'], self::$infos['image_id'],
					self::$infos['image_adddt']);
			}

			$conf = img::thumbConf($type);
			$i = img::getImageSize($img_file);

			// Création d'une image de travail.
			$src_img = img::gdCreateImage($img_file, $i['filetype']);
			if (!is_resource($src_img))
			{
				throw new Exception($error_message);
			}

			if (file_exists($tb_file))
			{
				files::unlink($tb_file);
			}

			// Coté le plus grand de l'aperçu.
			$i_edit = img::getImageSize($edit_file);
			$max_preview = ($i_edit['width'] > $i_edit['height'])
				? $i_edit['width']
				: $i_edit['height'];

			// Coordonnées pour le découpage et le redimensionnement de l'image.
			$max = ($i['width'] > $i['height']) ? $i['width'] : $i['height'];
			$ratio = $max / $max_preview;
			$coords = explode(',', $new_coords[1]);
			if ($conf['mode'] == 'crop')
			{
				$tb_height = $conf['crop_height'];
				$tb_width = $conf['crop_width'];
			}
			else
			{
				$tb_height = $conf['height'];
				$tb_width = $conf['width'];
				if ($coords[2] > $coords[3])
				{
					$tb_height = $tb_width / ($coords[2] / $coords[3]);
				}
				else
				{
					$tb_width = $tb_height / ($coords[3] / $coords[2]);
				}
			}
			$crop = array(
				'x' => round((int) $coords[0] * $ratio),
				'y' => round((int) $coords[1] * $ratio),
				'w' => round((int) $coords[2] * $ratio),
				'h' => round((int) $coords[3] * $ratio)
			);

			// Ajustement des dimensions de découpe.
			$image_width = (isset($old_coords[4]))
				? $old_coords[4]
				: self::$infos['image_width'];
			$image_height = (isset($old_coords[5]))
				? $old_coords[5]
				: self::$infos['image_height'];
			while ($crop['w'] + $crop['x'] > $image_width)
			{
				$crop['w']--;
			}
			while ($crop['h'] + $crop['y'] > $image_height)
			{
				$crop['h']--;
			}

			// Création de la nouvelle vignette.
			$dst_img = img::gdResize($src_img, $crop['x'], $crop['y'],
				$crop['w'], $crop['h'], 0, 0, $tb_width, $tb_height);
			img::gdCreateFile($dst_img, $tb_file, $i['filetype'], $tb_quality);
			if (!file_exists($tb_file))
			{
				throw new Exception($error_message);
			}

			// On enregistre les dimensions de la vignette et les nouvelles
			// coordonnées de rognage dans la base de données.
			$i = img::getImageSize($tb_file);
			$conf = ($type == 'cat')
				? (CONF_THUMBS_CAT_METHOD == 'crop' ? 0 : CONF_THUMBS_CAT_SIZE)
				: (CONF_THUMBS_IMG_METHOD == 'crop' ? 0 : CONF_THUMBS_IMG_SIZE);
			self::$infos['tb_infos'] = $conf . '.'
				. $i['width'] . '.' . $i['height'] . '.' . $new_coords[1];
			if ($type == 'cat' && self::$infos['thumb_id'] == 0)
			{
				self::$infos['tb_infos'] .= '.' . $tbi[4] . '.' . $tbi[5] . '.' . $tbi[6];
			}
			if ($type == 'cat')
			{
				$sql = 'UPDATE ' . CONF_DB_PREF . 'categories
						   SET cat_tb_infos = :tb_infos
						 WHERE cat_id = ' . (int) self::$infos['cat_id'];
			}
			else
			{
				$sql = 'UPDATE ' . CONF_DB_PREF . 'images
						   SET image_tb_infos = :tb_infos
						 WHERE image_id = ' . (int) self::$infos['image_id'];
			}
			$params = array('tb_infos' => self::$infos['tb_infos']);
			if (utils::$db->prepare($sql) === FALSE
			|| utils::$db->executeExec($params) === FALSE
			|| utils::$db->nbResult !== 1)
			{
				throw new Exception(utils::$db->msgError);
			}

			self::report('success:' . __('La vignette a été recréée.'));
		}
		catch (Exception $e)
		{
			self::report('error:' . $e->getMessage());
		}
	}

	/**
	 * Supprime la vignette.
	 *
	 * @param $string type
	 *	Type de la vignette : catégorie ou image.
	 * @return void
	 */
	private static function _thumbDelete($type)
	{
		try
		{
			$error_message = __('Impossible de supprimer la vignette.');

			// On supprime les informations de rognage.
			if (!empty(self::$infos['tb_infos']))
			{
				if ($type == 'cat')
				{
					self::$infos['tb_infos'] = (self::$infos['thumb_id'] == 0)
						? preg_replace('`^(?:\d+\.){3}(?:\d+,){3}\d+`',
							'0.0.0.0,0,0,0', self::$infos['tb_infos'])
						: NULL;
					$sql = 'UPDATE ' . CONF_DB_PREF . 'categories
							   SET cat_tb_infos = :tb_infos
							 WHERE cat_id = ' . (int) self::$infos['cat_id'] . '
							 LIMIT 1';
				}
				else
				{
					self::$infos['tb_infos'] = NULL;
					$sql = 'UPDATE ' . CONF_DB_PREF . 'images
							   SET image_tb_infos = :tb_infos
							 WHERE image_id = ' . (int) self::$infos['image_id'] . '
							 LIMIT 1';
				}
				$params = array('tb_infos' => self::$infos['tb_infos']);
				if (utils::$db->prepare($sql) === FALSE
				|| utils::$db->executeExec($params) === FALSE)
				{
					throw new Exception(utils::$db->msgError);
				}
			}

			// On supprime le fichier.
			if ($type == 'cat')
			{
				$tbi = explode('.', self::$infos['tb_infos']);
				$path = (self::$infos['thumb_id'] == 0)
					? '.' . $tbi[6]
					: self::$infos['image_path'];
				$file = GALLERY_ROOT . '/' . img::filepath('tb_cat',
					$path, self::$infos['cat_id'],
					self::$infos['cat_crtdt']);
			}
			else
			{
				$file = GALLERY_ROOT . '/' . img::filepath('tb_img',
					self::$infos['image_path'], self::$infos['cat_id'],
					self::$infos['image_adddt']);
			}
			if (file_exists($file) && !files::unlink($file))
			{
				throw new Exception($error_message);
			}

			self::report('success:' . __('La vignette a été supprimée.'));
		}
		catch (Exception $e)
		{
			self::report('error:' . $e->getMessage());
		}
	}

	/**
	 * Nouvelle vignette par envoi d'image.
	 *
	 * @return void
	 */
	private static function _thumbExternal()
	{
		if (!isset($_FILES['image']))
		{
			return;
		}
		$i =& $_FILES['image'];

		$max_filesize = 5242880;
		$max_width = 5000;
		$max_height = 5000;

		try
		{	
			// Y a-t-il une erreur ?
			switch ($i['error'])
			{
				// Aucune erreur.
				case 0 :
					break;

				// Fichier trop lourd.
				case 1 :
				case 2 :
					throw new Exception('warning', __('Le fichier est trop lourd.'));

				// Aucun fichier.
				case 4 :
					return FALSE;

				// Autre erreur.
				default :
					throw new Exception('error', __('Code erreur : %s'), $i['error']);
			}

			if (!is_uploaded_file($i['tmp_name']))
			{
				return FALSE;
			}

			// Le fichier est-il trop lourd ?
			if (filesize($i['tmp_name']) > $max_filesize)
			{
				throw new Exception('warning', __('Le fichier est trop lourd.'));
			}

			// Le format de l'image est-il correct ?
			if (($size = img::getImageSize($i['tmp_name'])) === FALSE
			|| !img::supportType($size['filetype']))
			{
				throw new Exception('warning', __('Le fichier n\'est pas une image valide.'));
			}

			// Dimensions de l'image.
			if ($size['width'] > $max_width || $size['height'] > $max_height)
			{
				$message = sprintf(
					__('L\'image ne doit pas faire plus de %s pixels'
						. ' de largeur et %s pixels de hauteur.'),
					$max_width,
					$max_height);
				throw new Exception('warning', $message);
			}

			// Extension du fichier.
			$ext = strtolower(preg_replace('`.+\.([a-z]{2,4})$`i', '$1', $i['name']));

			// Destination.
			$filepath = GALLERY_ROOT . '/' . img::filepath('im_external',
				$i['name'], (int) $_GET['object_id'],
				self::$infos['cat_crtdt']);

			// On déplace l'image.
			if (!move_uploaded_file($i['tmp_name'], $filepath))
			{
				throw new Exception('error', __('Impossible de déplacer l\'image.'));
			}

			// Suppression de l'image d'édition et de la vignette.
			if (self::$infos['thumb_id'] == 0)
			{
				$tbi = explode('.', self::$infos['tb_infos']);
				$path = 'i.' . $tbi[6];
			}
			else
			{
				$path = self::$infos['image_path'];
			}
			$edit_file = GALLERY_ROOT . '/' . img::filepath('im_edit',
				$path, self::$infos['cat_id'], self::$infos['cat_crtdt']);
			if (file_exists($edit_file))
			{
				files::unlink($edit_file);
			}
			$tb_file = GALLERY_ROOT . '/' . img::filepath('tb_cat',
				$path, self::$infos['cat_id'], self::$infos['cat_crtdt']);
			if (file_exists($tb_file))
			{
				files::unlink($tb_file);
			}

			// Mise à jour du tableau d'informations.
			self::$infos['thumb_id'] = 0;
			self::$infos['tb_infos'] = '0.0.0.0,0,0,0.'
				. $size['width'] . '.' . $size['height'] . '.' . $ext;

			// On met à jour la catégorie.
			$sql = 'UPDATE ' . CONF_DB_PREF . 'categories
					   SET thumb_id = 0,
					       cat_tb_infos = :tb_infos
					 WHERE cat_id = ' . (int) $_GET['object_id'] . '
					 LIMIT 1';
			$params = array('tb_infos' => self::$infos['tb_infos']);
			if (utils::$db->prepare($sql) === FALSE
			|| utils::$db->executeExec($params) === FALSE)
			{
				throw new Exception(utils::$db->msgError);
			}

			// Dimensions de l'image redimensionnée.
			$size = img::imageResize($size['width'], $size['height'], 400, 400);
			self::$infos['preview_width'] = $size['width'];
			self::$infos['preview_height'] = $size['height'];
			
			self::report('success:' . __('La vignette a été changée.'));
		}
		catch (Exception $e)
		{
			self::report($e);
		}
	}

	/**
	 * Nouvelle vignette.
	 *
	 * @return void
	 */
	private static function _thumbNew()
	{
		if (empty($_POST['image_id']))
		{
			return;
		}
		$image_id = (int) $_POST['image_id'];

		try
		{
			// Récupération du chemin de la catégorie.
			$sql = 'SELECT cat_path
					  FROM ' . CONF_DB_PREF . 'categories
					 WHERE cat_id = ' . (int) $_GET['object_id'];
			if (utils::$db->query($sql, 'value') === FALSE
			|| utils::$db->nbResult === 0)
			{
				throw new Exception(utils::$db->msgError);
			}
			$cat_path = utils::$db->queryResult;

			// On vérifie que l'image choisie existe dans cette catégorie
			// et on récupère les informations utiles de cette image.
			$sql = 'SELECT image_id,
						   image_name,
						   image_path,
						   image_height,
						   image_width,
						   image_adddt
					  FROM ' . CONF_DB_PREF . 'images
					 WHERE image_id = ' . $image_id . '
					   AND image_path LIKE CONCAT(:cat_path, "/%")';
			$params = array('cat_path' => $cat_path);
			if (utils::$db->prepare($sql) === FALSE
			|| utils::$db->executeQuery($params, 'row') === FALSE)
			{
				throw new Exception(utils::$db->msgError);
			}
			if (utils::$db->nbResult === 0)
			{
				throw new Exception('warning:' .
					__('Cette image n\'existe pas dans cette catégorie.'));
			}
			$image_infos = utils::$db->queryResult;

			// On met à jour la catégorie.
			$sql = 'UPDATE ' . CONF_DB_PREF . 'categories
					   SET thumb_id = ' . $image_id . ',
						   cat_tb_infos = NULL
					 WHERE cat_id = ' . (int) $_GET['object_id'] . '
					 LIMIT 1';
			if (utils::$db->exec($sql) === FALSE)
			{
				throw new Exception(utils::$db->msgError);
			}

			// Suppression de l'image d'édition et de la vignette.
			if (self::$infos['thumb_id'] == 0)
			{
				$tbi = explode('.', self::$infos['tb_infos']);
				$path = 'i.' . $tbi[6];
				$edit_file = GALLERY_ROOT . '/' . img::filepath('im_edit',
					$path, self::$infos['cat_id'], self::$infos['cat_crtdt']);
				if (file_exists($edit_file))
				{
					files::unlink($edit_file);
				}
			}
			else
			{
				$path = self::$infos['image_path'];
			}
			$tb_file = GALLERY_ROOT . '/' . img::filepath('tb_cat',
				$path, self::$infos['cat_id'], self::$infos['cat_crtdt']);
			if (file_exists($tb_file))
			{
				files::unlink($tb_file);
			}

			// Mise à jour du tableau des informations.
			self::$infos['image_id'] = $image_infos['image_id'];
			self::$infos['image_name'] = $image_infos['image_name'];
			self::$infos['image_path'] = $image_infos['image_path'];
			self::$infos['image_height'] = $image_infos['image_height'];
			self::$infos['image_width'] = $image_infos['image_width'];
			self::$infos['image_adddt'] = $image_infos['image_adddt'];
			self::$infos['thumb_id'] = $image_infos['image_id'];
			self::$infos['tb_infos'] = NULL;

			self::report('success:' . __('La vignette a été changée.'));
		}
		catch (Exception $e)
		{
			self::report($e->getMessage());
		}
	}
}

/**
 * Gestion des images.
 */
class album extends albums
{
	/**
	 * Actions sur la sélection d'images.
	 *
	 * @return void
	 */
	public static function actions()
	{
		if (($selected_ids = self::_initObjectsActions()) === FALSE)
		{
			return;
		}

		// Permissions d'accès.
		if (!auth::$perms['admin']['perms']['albums_modif']
		&& !auth::$perms['admin']['perms']['all'])
		{
			die;
		}

		$cat_ids = array();

		// Recherche.
		if (isset($_GET['search']))
		{
			$sql = 'SELECT img.cat_id,
						   image_id
					  FROM ' . CONF_DB_PREF . 'images AS img
				 LEFT JOIN ' . CONF_DB_PREF . 'categories USING (cat_id)
					 WHERE image_id IN (' . implode(', ', $selected_ids) . ')'
						 . sql::$groupAlbumsAccess;
			$fetch_style = array('fetch' => PDO::FETCH_ASSOC, 'column' => 'image_id');
			if (utils::$db->query($sql, $fetch_style) === FALSE)
			{
				throw new Exception(utils::$db->msgError);
			}
			foreach (utils::$db->queryResult as $image_id => &$ids)
			{
				if (!isset($cat_ids[$ids['cat_id']]))
				{
					$cat_ids[$ids['cat_id']] = array();
				}
				$cat_ids[$ids['cat_id']][] = $ids['image_id'];
			}
		}

		// Album.
		else
		{
			$cat_ids[$_GET['object_id']] = $selected_ids;
		}

		$update_success = FALSE;
		$update_success_message = __('Les images sélectionnées sont déjà à jour.');

		foreach ($cat_ids as $cat_id => &$images)
		{
			// Action à effectuer.
			switch ($_POST['action'])
			{
				// Suppression.
				case 'delete' :
					self::_delete($images, $cat_id);
					break;

				// Nombre de visites.
				case 'hits' :
					if (self::_hits($images, $cat_id))
					{
						self::report('success:' . __('Le nombre de visites des '
							. 'images sélectionnées a été modifié.'));
					}
					break;

				// Déplacement.
				case 'move' :
					self::_move($images, $cat_id);
					break;

				// Activation.
				case 'publish' :
					if (self::_status($images, $cat_id, 1))
					{
						self::report('success:'
							. __('Les images sélectionnées ont été activées.'));
					}
					break;

				// Désactivation.
				case 'unpublish' :
					if (self::_status($images, $cat_id, 0))
					{
						self::report('success:'
							. __('Les images sélectionnées ont été désactivées.'));
					}
					break;

				// Mise à jour.
				case 'update' :
					if (($result = self::_update($images, $cat_id)) !== FALSE)
					{
						$update_success = TRUE;
						if ($result === 1)
						{
							$update_success_message =
								__('Les images sélectionnées ont été mises à jour.');
						}
					}
					break;
			}
		}

		if ($update_success)
		{
			self::report('success:' . $update_success_message);
		}
	}

	/**
	 * Édition des images.
	 *
	 * @return void
	 */
	public static function edit()
	{
		if (!isset($_POST['save']))
		{
			return;
		}

		$get_images = FALSE;

		// Tableau de mise à jour des tags.
		$tags_update = array(
			'add' => array(),
			'delete' => array(),
			'tags' => array()
		);

		foreach (albums::$items as $id => &$infos)
		{
			if (empty($_POST[$id]) || !is_array($_POST[$id]))
			{
				continue;
			}

			if (self::editInfos($id, $infos, $tags_update))
			{
				$get_images = TRUE;
			}
		}

		// On récupère à nouveau les images s'il y a eu des modifications.
		if ($get_images)
		{
			self::getImages();
		}

		// Mise à jour des tables de tags.
		if (!is_array(($tags_update = tagsImage::update($tags_update))))
		{
			return;
		}
		if ($tags_update['success'])
		{
			self::report('success:' . __('Modifications enregistrées.'));
			self::report('success_p:' . __('Les autres modifications ont été enregistrées.'));
		}
		else
		{
			self::report('error:' . $tags_update['message']);
		}
	}

	/**
	 * Édition des informations d'une image.
	 *
	 * @param integer $id
	 *	Identifiant de l'image.
	 * @param array $infos
	 *	Informations de l'image.
	 * @param array $tags_update
	 *	Tableau de mise à jour des tags.
	 * @return boolean
	 */
	public static function editInfos($id, &$infos, &$tags_update = NULL)
	{
		if (!isset($_POST['save']))
		{
			return;
		}

		$change = FALSE;
		$columns = array();
		$get_images = FALSE;
		$params = array();
		$update_tags = FALSE;

		if ($tags_update === NULL)
		{
			$update_tags = TRUE;
			$tags_update = array(
				'add' => array(),
				'delete' => array(),
				'tags' => array()
			);
		}

		// Tags.
		if (isset($_POST[$id]['tags']))
		{
			$tags_current = (isset($infos['image_tags']))
				? $infos['image_tags']
				: array();
			$tags_image = tagsImage::edit($id, $_POST[$id]['tags'],
				$tags_current, $tags_update);
			if (is_array($tags_image))
			{
				albums::$items[$id]['image_tags'] = $tags_image;
			}
		}

		// Titre.
		if (($new_title = self::_editTitle($id, $infos, 'image_name')) !== FALSE)
		{
			$columns[] = 'image_name = :image_name';
			$params['image_name'] = $new_title;
			$change = TRUE;
			if (auth::$infos['user_prefs']['album']['sortby'] == 'name')
			{
				$get_images = TRUE;
			}
		}

		// Nom d'URL.
		if (($new_urlname = self::_editURLName($id, $infos, 'image_url')) !== FALSE)
		{
			$columns[] = 'image_url = :image_url';
			$params['image_url'] = $new_urlname;
			$change = TRUE;
		}

		// Date de création.
		if (($new_crtdt = self::_editCreationDate($id, $infos)) !== FALSE)
		{
			$columns[] = 'image_crtdt = :image_crtdt';
			$params['image_crtdt'] = $new_crtdt;
			$change = TRUE;
		}

		// Description.
		if (($new_description = self::_editDescription($id, $infos, 'image_desc')) !== FALSE)
		{
			$columns[] = 'image_desc = :image_desc';
			$params['image_desc'] = $new_description;
			$change = TRUE;
		}

		// Nom de fichier.
		if (($new_filename = self::_editFilename($id, $infos)) !== FALSE)
		{
			$infos['image_path'] = $new_filename;
			$change = TRUE;
			if (auth::$infos['user_prefs']['album']['sortby'] == 'path')
			{
				$get_images = TRUE;
			}
		}

		// Latitude.
		if (($new_latitude = self::_editGeoloc($id, $infos, 'image_lat')) !== FALSE)
		{
			$columns[] = 'image_lat = :image_lat';
			$params['image_lat'] = $new_latitude;
			$change = TRUE;
			$get_images = TRUE;
		}

		// Longitude.
		if (($new_longitude = self::_editGeoloc($id, $infos, 'image_long')) !== FALSE)
		{
			$columns[] = 'image_long = :image_long';
			$params['image_long'] = $new_longitude;
			$change = TRUE;
			$get_images = TRUE;
		}

		// Lieu.
		if (($new_place = self::_editPlace($id, $infos, 'image_place')) !== FALSE)
		{
			$columns[] = 'image_place = :image_place';
			$params['image_place'] = $new_place;
			$change = TRUE;
		}

		// Mise à jour des tables de tags.
		if ($update_tags)
		{
			if (is_array(($tags_update = tagsImage::update($tags_update))))
			{
				if ($tags_update['success'])
				{
					if ($_GET['section'] == 'edit-image')
					{
						image::getImageTags();
					}
					self::report('success:'
						. __('Modifications enregistrées.'));
					self::report('success_p:'
						. __('Les autres modifications ont été enregistrées.'));
				}
				else
				{
					self::report('error:' . $tags_update['message']);
				}
			}
		}

		if (!$change)
		{
			return FALSE;
		}

		// On effectue la mise à jour de l'image.
		if (!empty($params))
		{
			$sql = 'UPDATE ' . CONF_DB_PREF . 'images
					   SET ' . implode(', ', $columns) . '
					 WHERE image_id = ' . (int) $id;
			if (utils::$db->prepare($sql) === FALSE
			|| utils::$db->executeExec($params) === FALSE
			|| utils::$db->nbResult === 0)
			{
				self::report('error:' . utils::$db->msgError, $id);
				return FALSE;
			}
		}

		// Mise à jour du tableau des images.
		foreach (array('desc', 'lat', 'long', 'name', 'place', 'url') as $p)
		{
			if (isset($params['image_' . $p]))
			{
				$infos['image_' . $p] = $params['image_' . $p];
			}
		}

		self::report('success:' . __('Modifications enregistrées.'));
		self::report('success_p:' . __('Les autres modifications ont été enregistrées.'));

		return $get_images;
	}

	/**
	 * Récupération des informations utiles
	 * des images de l'album courant.
	 *
	 * @param boolean $sort_images
	 *	Option pour la page de tri des images.
	 * @return void
	 */
	public static function getImages($sort_images = FALSE)
	{
		$sw = self::_sqlWhereImages();
		$sql_where = $sw['sql'];
		$params = $sw['params'];

		// Recherche et filtres.
		if (isset($_GET['search']) || isset($_GET['filter']))
		{
			// Limitation à la catégorie courante.
			if (self::$infos['cat_id'] > 1)
			{
				$sql_where .= ' AND image_path LIKE CONCAT(:path, "/%")';
				$params['path'] = self::$infos['cat_path'];
			}

			// Récupération du nombre d'images.
			$sql = 'SELECT COUNT(*)
					  FROM ' . CONF_DB_PREF . 'images AS img
						   ' . self::_sqlFromImages() . '
				 LEFT JOIN ' . CONF_DB_PREF . 'categories AS cat USING (cat_id)
					 WHERE ' . $sql_where
							 . sql::$groupAlbumsAccess;
			if (utils::$db->prepare($sql) === FALSE
			|| utils::$db->executeQuery($params, 'value') === FALSE)
			{
				return;
			}
			self::$nbItems = utils::$db->queryResult;

			self::pages();
		}

		// Album.
		else
		{
			$sql_where = 'img.cat_id = ' . (int) $_GET['object_id'];
		}

		// Options d'affichage.
		if ($sort_images)
		{
			$limit = '';
			$order_by = 'image_position ASC';
		}
		else
		{
			$limit = self::$_catSqlStart . ','
				. auth::$infos['user_prefs']['album']['nb_per_page'];
			$limit = ' LIMIT ' . $limit;

			$order_by = ' ' . auth::$infos['user_prefs']['album']['orderby'];
			$order_by = 'LOWER(image_' . auth::$infos['user_prefs']['album']['sortby'] . ')'
				. $order_by . ', image_id DESC';
		}

		// Récupération des images.
		$sql = 'SELECT img.*,
					   img.image_tb_infos AS tb_infos,
					   img.image_id AS img_id,
					   cat.cat_password,
					   u.user_login,
					   u.user_status
				  FROM ' . CONF_DB_PREF . 'images AS img
				       ' . self::_sqlFromImages() . '
			 LEFT JOIN ' . CONF_DB_PREF . 'categories AS cat
					ON img.cat_id = cat.cat_id
			 LEFT JOIN ' . CONF_DB_PREF . 'users AS u
					ON img.user_id = u.user_id
				 WHERE ' . $sql_where . '
					   ' . sql::$groupAlbumsAccess . '
			  ORDER BY ' . $order_by
						 . $limit;
		$fetch_style = array('fetch' => PDO::FETCH_ASSOC, 'column' => 'image_id');
		if (utils::$db->prepare($sql) === FALSE
		|| utils::$db->executeQuery($params, $fetch_style) === FALSE)
		{
			return;
		}
		if (!admin::_objectsNbResult())
		{
			return;
		}
		albums::$items = utils::$db->queryResult;

		// Récupération des tags associés à chaque image.
		if (utils::$db->nbResult > 0)
		{
			$sql = 'SELECT image_id,
						   tag_name
					  FROM ' . CONF_DB_PREF . 'tags AS t,
						   ' . CONF_DB_PREF . 'tags_images AS ti
					 WHERE ti.tag_id = t.tag_id
					   AND ti.image_id IN (' . implode(', ', array_keys(albums::$items)) . ')';
			if (utils::$db->query($sql, PDO::FETCH_ASSOC) === FALSE)
			{
				return;
			}
			$tags = utils::$db->queryResult;

			foreach ($tags as &$infos)
			{
				if (!isset(albums::$items[$infos['image_id']]['image_tags']))
				{
					albums::$items[$infos['image_id']]['image_tags'] = array();
				}

				albums::$items[$infos['image_id']]['image_tags'][] = $infos['tag_name'];
			}
		}
	}

	/**
	 * Réduit la liste des catégories selon
	 * les critères de recherche.
	 *
	 * @return void
	 */
	public static function reduceMap()
	{
		$sw = self::_sqlWhereImages();
		$sql_where = $sw['sql'];
		$params = $sw['params'];

		if ($sql_where == '')
		{
			return;
		}

		$sql = 'SELECT cat.cat_id
				  FROM ' . CONF_DB_PREF . 'images AS img
				       ' . self::_sqlFromImages() . '
			 LEFT JOIN ' . CONF_DB_PREF . 'categories AS cat
					ON img.cat_id = cat.cat_id
				 WHERE cat.cat_id = img.cat_id
				   AND ' . $sql_where;
		$fetch_style = array('column' => array('cat_id', 'cat_id'));
		if (utils::$db->prepare($sql) === FALSE
		|| utils::$db->executeQuery($params, $fetch_style) === FALSE)
		{
			return;
		}

		self::_reduceMapAlbums(utils::$db->queryResult);
	}

	/**
	 * Ajout d'images envoyées par HTTP.
	 *
	 * @return void
	 */
	public static function upload()
	{
		if (empty($_POST) || isset($_GET['search']) || !isset($_POST['tempdir']))
		{
			return;
		}

		// Permissions d'accès.
		if (!auth::$perms['admin']['perms']['albums_add']
		&& !auth::$perms['admin']['perms']['all'])
		{
			die;
		}

		$add_images = array();

		// Envoi unitaire.
		if (isset($_POST['upload']) && isset($_FILES['files']))
		{
			$publish_images = isset($_POST['classic_publish_images']);

			// Répertoire temporaire.
			$temp_dir = GALLERY_ROOT . '/cache/up_temp/' . $_POST['tempdir'];
			if (!files::mkdir($temp_dir))
			{
				self::report('error:' . __('Impossible de créer le répertoire.'));
				return;
			}

			$i =& $_FILES['files'];
			foreach ($i['name'] as $id => $name)
			{
				$r = alb::uploadFile($name, $i['tmp_name'][$id], $i['error'][$id],
					$temp_dir, self::$infos['cat_path']);

				if ($r === FALSE)
				{
					continue;
				}

				else if (is_array($r))
				{
					self::report($r[0] . ':' . $r[1]);
				}

				else if (is_string($r))
				{
					$add_images[] = $r;

					// On déplace l'image du répertoire
					// temporaire vers le répertoire de l'album destination.
					$oldname = $temp_dir . '/' . $r;
					$newname = GALLERY_ROOT . '/' . CONF_ALBUMS_DIR . '/'
						. self::$infos['cat_path'] . '/' . $r;
					if (file_exists($oldname) && !file_exists($newname))
					{
						files::renameFile($oldname, $newname);
					}
				}
			}
		}

		// Envoi multiple.
		else if (isset($_POST['multiple']))
		{
			$publish_images = isset($_POST['multiple_publish_images']);

			// Répertoire temporaire.
			$temp_dir = GALLERY_ROOT . '/cache/up_temp/' . $_POST['tempdir'];
			if (!file_exists($temp_dir))
			{
				return;
			}

			// Nouvelles images.
			if (isset($_POST['files']) && is_array($_POST['files']))
			{
				$add_images = array_map('urldecode', $_POST['files']);

				// On déplace toutes les images du répertoire
				// temporaire vers le répertoire de l'album destination.
				$files = scandir($temp_dir);
				foreach ($add_images as $file)
				{
					if (!in_array($file, $files))
					{
						continue;
					}

					$oldname = $temp_dir . '/' . $file;
					$newname = GALLERY_ROOT . '/' . CONF_ALBUMS_DIR . '/'
						. self::$infos['cat_path'] . '/' . $file;
					if (file_exists($oldname) && !file_exists($newname))
					{
						files::renameFile($oldname, $newname);
					}
				}
			}

			// Avertissements.
			if (isset($_POST['warning']) && is_array($_POST['warning']))
			{
				foreach ($_POST['warning'] as $msg)
				{
					self::report('warning:' . urldecode($msg));
				}
			}

			// Erreurs.
			if (isset($_POST['error']) && is_array($_POST['error']))
			{
				foreach ($_POST['error'] as $msg)
				{
					self::report('error:' . urldecode($msg));
				}
			}
		}

		try
		{
			// Si aucune image à ajouter, inutile d'aller plus loin.
			if (empty($add_images))
			{
				throw new Exception();
			}

			// Initialisation du scan.
			$upload = new upload();
			if ($upload->getInit === FALSE)
			{
				self::report('error:' . __('Une requête SQL a échouée : '
					. 'le scan ne peut se poursuivre.'));
				throw new Exception();
			}

			// Options de scan.
			$upload->setHttp = TRUE;
			$upload->setHttpImages = array_flip($add_images);
			$upload->setPublishImages = $publish_images;
			$upload->setReportAllFiles = FALSE;
			$upload->setUpdateImages = FALSE;
			$upload->setUpdateThumbId = (bool) utils::$config['upload_update_thumb_id'];
			$upload->setUserId = auth::$infos['user_id'];
			$upload->setUserLogin = auth::$infos['user_login'];

			// Scan du répertoire de l'album courant.
			if ($upload->getAlbums(self::$infos['cat_path']) === FALSE)
			{
				self::report('error:' . __('Une erreur s\'est produite : '
					. 'la mise à jour de la base de données a échouée.'));
				throw new Exception();
			}

			// Rapport.
			if (!empty($upload->getReport['errors']))
			{
				foreach ($upload->getReport['errors'] as $e)
				{
					self::report('error:' . $e[0] . ': ' . $e[1]);
				}
			}
			if (!empty($upload->getReport['img_reject']))
			{
				foreach ($upload->getReport['img_reject'] as $i)
				{
					self::report('warning:' . $i[0] . ': ' . $i[2]);
				}
			}
			if ($upload->getReport['img_add'] > 0)
			{
				self::$nbItems += $upload->getReport['img_add'];
				$message = ($upload->getReport['img_add'] > 1)
					? __('%s images ont été ajoutées à l\'album.')
					: __('%s image a été ajoutée à l\'album.');
				self::report('success:' . sprintf($message, $upload->getReport['img_add']));
				self::report('success_p:' . sprintf($message, $upload->getReport['img_add']));

				// On récupère à nouveau les informations de l'album.
				category::infos();
			}
		}
		catch (Exception $e) {}

		// On supprime le répertoire temporaire.
		files::rmdir($temp_dir);
	}



	/**
	 * Supprime des images.
	 *
	 * @param array $selected_ids
	 *	Identifiants des images sélectionnées.
	 * @param integer $cat_id
	 *	Identifiant de la catégorie des images sélectionnées.
	 * @return void
	 */
	private static function _delete($selected_ids, $cat_id)
	{
		if (($images_infos = self::_getImagesInfos($selected_ids)) === FALSE)
		{
			if (utils::$db->nbResult > 0)
			{
				self::report(utils::$db->msgError);
			}
			return;
		}

		$report = alb::deleteImages($cat_id, $images_infos);

		foreach ($report as &$msg)
		{
			if (is_array($msg))
			{
				self::report($msg[0], $msg[1]);
			}
			else
			{
				self::report($msg);
			}
		}
	}

	/**
	 * Édition du nom de fichier.
	 *
	 * @param integer $id
	 *	Identifiant de l'image.
	 * @param array $infos
	 *	Informations de l'image.
	 * @return boolean|string
	 */
	private static function _editFilename($id, $infos)
	{
		if (!isset($_POST[$id]['filename'])
		|| ($new_filename = trim($_POST[$id]['filename'])) == basename($infos['image_path']))
		{
			return FALSE;
		}

		try
		{
			// Vérification de la longueur.
			if (strlen($new_filename) > 128 || strlen($new_filename) < 1)
			{
				$message = __('Le nom de fichier doit contenir entre 1 et 128 caractères.');
				throw new Exception('warning:' . $message);
			}

			// Vérification du format.
			if (!preg_match('`^[-_a-z0-9]+\.(jpe?g|gif|png)$`i', $new_filename))
			{
				throw new Exception('warning:' . __('Format du nom de fichier invalide.'));
			}

			// Ancien et nouveau chemin de l'image.
			$old_path = $infos['image_path'];
			$new_path = dirname($old_path) . '/' . $new_filename;

			// Chemin du répertoire des albums.
			$albums_path = GALLERY_ROOT . '/' . CONF_ALBUMS_DIR;

			// On vérifie s'il n'existe pas déjà un fichier avec le même nom.
			// On n'autorise pas à juste changer la casse sous Windows, car un bug
			// de PHP fait que la fonction rename() ne renomme pas le fichier
			// ni ne provoque d'erreur !
			if (file_exists($albums_path . '/' . $new_path))
			{
				$message = __('Un fichier du même nom existe déjà dans cet album.');
				throw new Exception('warning:' . $message);
			}

			// Début de la transaction.
			if (!utils::$db->transaction())
			{
				throw new Exception('error:' . utils::$db->msgError);
			}

			// On modifie le chemin de l'image.
			$params = array('new_path' => $new_path);
			$sql = 'UPDATE ' . CONF_DB_PREF . 'images
					   SET image_path = :new_path
					 WHERE image_id = ' . (int) $infos['image_id'];
			if (utils::$db->prepare($sql) === FALSE
			|| utils::$db->executeExec($params) === FALSE
			|| utils::$db->nbResult === 0)
			{
				throw new Exception('error:' . utils::$db->msgError);
			}

			// On renomme le fichier.
			if (!files::renameFile($albums_path . '/' . $old_path,
			$albums_path . '/' . $new_path))
			{
				throw new Exception('error:' . __('Impossible de renommer le fichier.'));
			}

			// Exécution de la transaction.
			if (!utils::$db->commit())
			{
				// On tente de renommer le fichier avec l'ancien nom.
				files::renameFile($albums_path . '/' . $new_path,
				$albums_path . '/' . $old_path);

				throw new Exception('error:' . utils::$db->msgError);
			}

			return $new_path;
		}
		catch (Exception $e)
		{
			self::report($e, $id);

			return FALSE;
		}
	}

	/**
	 * Récupère les informations utiles des images sélectionnées.
	 *
	 * @param array $selected_ids
	 *	Identifiants des images sélectionnées.
	 * @param boolean $all_cols
	 *	Doit-on récupèrer toutes les colonnes ?
	 * @return boolean|array
	 */
	private static function _getImagesInfos($selected_ids, $all_cols = FALSE)
	{
		$columns = ($all_cols)
			? '*'
			: 'image_id,
			   image_path,
			   image_filesize,
			   image_name,
			   image_hits,
			   image_comments,
			   image_votes,
			   image_rate,
			   image_status';

		// Récupération des informations utiles des images sélectionnées.
		$sql = 'SELECT ' . $columns . '
				  FROM ' . CONF_DB_PREF . 'images
			 LEFT JOIN ' . CONF_DB_PREF . 'categories USING (cat_id)
				 WHERE image_id IN (' . implode(', ', $selected_ids) . ')';
		$fetch_style = array('fetch' => PDO::FETCH_ASSOC, 'column' => 'image_id');
		if (utils::$db->query($sql, $fetch_style) === FALSE
		|| utils::$db->nbResult === 0)
		{
			return FALSE;
		}

		return utils::$db->queryResult;
	}

	/**
	 * Change le nombre de visites des images sélectionnées.
	 *
	 * @param array $selected_ids
	 *	Identifiants des images sélectionnées.
	 * @param integer $cat_id
	 *	Identifiant de la catégorie des images sélectionnées.
	 * @return boolean
	 */
	protected static function _hits($selected_ids, $cat_id)
	{
		if (!isset($_POST['hits']) || !preg_match('`^\d{1,11}$`', $_POST['hits']))
		{
			return;
		}

		try
		{
			// Début de la transaction.
			if (!utils::$db->transaction())
			{
				throw new Exception(utils::$db->msgError);
			}

			// Récupération des id des catégories parentes et des
			// informations qui serviront à updater les catégories parentes.
			if (($parents_ids = alb::getParentsIds($cat_id)) === FALSE
			|| ($images = self::_getImagesInfos($selected_ids)) === FALSE)
			{
				if ($images === FALSE && utils::$db->nbResult === 0)
				{
					return;
				}
				throw new Exception(utils::$db->msgError);
			}

			// Nombre de visites à ajouter aux catégories parentes.
			$cat_a_hits = 0;
			$cat_d_hits = 0;
			foreach ($images as &$i)
			{
				$column = ($i['image_status'])
					? 'cat_a_hits'
					: 'cat_d_hits';
				${$column} += (int) $_POST['hits'] - (int) $i['image_hits'];
			}

			// On change le nombre de visites des images et des catégories parentes.
			$sql = array
			(
				'UPDATE ' . CONF_DB_PREF . 'images
					SET image_hits = image_hits + ' . (int) $_POST['hits']  . ' - image_hits
				  WHERE image_id IN (' . implode(', ', $selected_ids) . ')',

				'UPDATE ' . CONF_DB_PREF . 'categories
					SET cat_a_hits = cat_a_hits + ' . $cat_a_hits . ',
						cat_d_hits = cat_d_hits + ' . $cat_d_hits . '
				  WHERE cat_id IN (' . implode(', ', $parents_ids) . ')'
			);

			// Exécution des requêtes.
			if (utils::$db->exec($sql, FALSE) === FALSE)
			{
				throw new Exception(utils::$db->msgError);
			}

			// Exécution de la transaction.
			if (!utils::$db->commit())
			{
				throw new Exception(utils::$db->msgError);
			}

			return TRUE;
		}
		catch (Exception $e)
		{
			self::report($e);
			return FALSE;
		}
	}

	/**
	 * Déplace des images.
	 *
	 * @param array $selected_ids
	 *	Identifiants des images sélectionnées.
	 * @param integer $cat_id
	 *	Identifiant de la catégorie des images sélectionnées.
	 * @return boolean
	 */
	protected static function _move($selected_ids, $cat_id)
	{
		if (empty($_POST['destination_cat']))
		{
			return;
		}

		try
		{
			// On récupère le chemin du répertoire destination
			// et on vérifie que la destination est bien un album
			// et aussi que ce n'est pas le même album.
			$sql = 'SELECT cat_path
					  FROM ' . CONF_DB_PREF . 'categories
					 WHERE cat_id = ' . (int) $_POST['destination_cat'] . '
					   AND cat_id != ' . (int) $cat_id . '
					   AND cat_filemtime IS NOT NULL';
			if (utils::$db->query($sql, 'value') === FALSE)
			{
				throw new Exception('error:' . utils::$db->msgError);
			}
			if (utils::$db->nbResult === 0)
			{
				$message = __('Les images ne peuvent pas être déplacées'
					. ' dans une catégorie ou dans le même album.');
				throw new Exception('warning:' . $message);
			}
			$dest_path = utils::$db->queryResult;

			// Récupération des id des catégories parentes de la catégorie source,
			// des id des catégories parentes de la catégorie destination et des
			// informations qui serviront à updater les catégories parentes.
			if (($source_parents_ids =
			alb::getParentsIds($cat_id)) === FALSE
			|| ($dest_parents_ids =
			alb::getParentsIds($_POST['destination_cat'])) === FALSE
			|| ($images = self::_getImagesInfos($selected_ids)) === FALSE)
			{
				if ($images === FALSE && utils::$db->nbResult === 0)
				{
					return;
				}
				throw new Exception('error:' . utils::$db->msgError);
			}

			$i = current($images);
			$source_path = dirname($i['image_path']);

			// Déplacement des images une à une.
			foreach ($selected_ids as &$id)
			{
				self::_moveFile($images[$id], $dest_path,
					$source_parents_ids, $dest_parents_ids);
			}

			if (isset(self::$report['success_p']))
			{
				self::report('success:' . __('Les images sélectionnées ont été déplacées.'));
			}

			// On met à jour certaines informations des catégories parentes.
			alb::updateLastadddt($source_path);
			alb::updateLastadddt($dest_path);

			alb::updateCatThumbs($source_path);
			alb::updateCatThumbs($dest_path);

			alb::updateSubsCats($source_parents_ids);
			alb::updateSubsCats($dest_parents_ids);

			return TRUE;
		}
		catch (Exception $e)
		{
			self::report($e);
			return FALSE;
		}
	}

	/**
	 * Déplace une image.
	 *
	 * @param array $i
	 *	Informations de l'image.
	 * @param string $dest_path
	 *	Chemin du répertoire destination.
	 * @param array $source_parents_ids
	 *	Identifiants des catégories parentes source.
	 * @param array $dest_parents_ids
	 *	Identifiants des catégories parentes destination.
	 * @return void
	 */
	private static function _moveFile($i, $dest_path,
	$source_parents_ids, $dest_parents_ids)
	{
		try
		{
			// Ancien et nouveau chemin de l'image.
			$old_path = $i['image_path'];
			$new_path = $dest_path . '/' . basename($i['image_path']);

			// Si l'image existe déjà dans l'album destination,
			// inutile d'aller plus loin.
			if ($old_path == $new_path)
			{
				return;
			}

			// Chemin du répertoire des albums.
			$albums_path = GALLERY_ROOT . '/' . CONF_ALBUMS_DIR;

			// On vérifie s'il n'existe pas déjà dans l'album
			// destination un fichier avec le même nom.
			if (file_exists($albums_path . '/' . $new_path))
			{
				$message = __('Un fichier du même nom existe '
					. 'déjà dans l\'album destination.');
				throw new Exception('warning:' . $message);
			}

			// Début de la transaction.
			if (!utils::$db->transaction())
			{
				throw new Exception('error:' . utils::$db->msgError);
			}

			$cat_update = array();
			$status = ($i['image_status']) ? 'a' : 'd';
			$cat_update[$status]['images'] = 1;
			$cat_update[$status]['albums'] = 0;
			$cat_update[$status]['size'] = $i['image_filesize'];
			$cat_update[$status]['hits'] = $i['image_hits'];
			$cat_update[$status]['comments'] = $i['image_comments'];
			$cat_update[$status]['votes'] = $i['image_votes'];
			$cat_update[$status]['rate'] = $i['image_rate'];

			// On update les informations de l'image.
			$sql = 'UPDATE ' . CONF_DB_PREF . 'images
					   SET cat_id = ' . (int) $_POST['destination_cat'] . ',
						   image_path = :new_path
					 WHERE image_id = ' . (int) $i['image_id'];
			$params = array('new_path' => $new_path);
			if (utils::$db->prepare($sql) === FALSE
			|| utils::$db->executeExec($params) === FALSE
			|| utils::$db->nbResult === 0)
			{
				throw new Exception('error:' . utils::$db->msgError);
			}

			$sql = array();

			// On met à jour les informations des catégories parentes
			// de l'album source.
			$a_up = ($i['image_status']) ? '-' : FALSE;
			$d_up = ($i['image_status']) ? FALSE : '-';
			$sql[] = alb::updateParentsStats($cat_update,
				$a_up, $d_up, $source_parents_ids);

			// On met à jour les informations des catégories parentes
			// de l'album destination.
			$a_up = ($i['image_status']) ? '+' : FALSE;
			$d_up = ($i['image_status']) ? FALSE : '+';
			$sql[] = alb::updateParentsStats($cat_update,
				$a_up, $d_up, $dest_parents_ids);

			// Exécution des requêtes.
			if (utils::$db->exec($sql, FALSE) === FALSE)
			{
				throw new Exception('error:' . utils::$db->msgError);
			}

			// On déplace le fichier
			if (!files::renameFile($albums_path . '/' . $old_path,
			$albums_path . '/' . $new_path))
			{
				throw new Exception('error:' . __('Impossible de déplacer l\'image.'));
			}

			// Exécution de la transaction.
			if (!utils::$db->commit())
			{
				// On tente de renommer le fichier et sa vignette avec l'ancien nom.
				files::renameFile($albums_path . '/' . $new_path,
				$albums_path . '/' . $old_path);

				throw new Exception('error:' . utils::$db->msgError);
			}

			self::report('success_p:' . __('Les autres images ont été déplacées.'));
		}
		catch (Exception $e)
		{
			utils::$db->rollBack();
			self::report($e, $i['image_id']);
		}
	}

	/**
	 * Retourne la clause FROM correspondant
	 * aux critères de recherche des images à récupérer.
	 *
	 * @return string
	 */
	private static function _sqlFromImages()
	{
		$sql = '';

		if (isset($_GET['filter']))
		{
			switch ($_GET['filter'])
			{
				case 'camera-brand' :
					$sql = ' LEFT JOIN ' . CONF_DB_PREF . 'cameras_models_images AS cam_i
								    ON img.image_id = cam_i.image_id
							 LEFT JOIN ' . CONF_DB_PREF . 'cameras_models AS cam_m
									ON cam_i.camera_model_id = cam_m.camera_model_id
							 LEFT JOIN ' . CONF_DB_PREF . 'cameras_brands AS cam_b
									ON cam_m.camera_brand_id = cam_b.camera_brand_id';
					break;

				case 'camera-model' :
					$sql = ' LEFT JOIN ' . CONF_DB_PREF . 'cameras_models_images AS cam_i
								    ON img.image_id = cam_i.image_id';
					break;

				case 'tag' :
					$sql = ' LEFT JOIN ' . CONF_DB_PREF . 'tags_images AS ti
								    ON img.image_id = ti.image_id';
					break;

				case 'user-basket' :
					$sql = ' LEFT JOIN ' . CONF_DB_PREF . 'basket AS basket
								    ON img.image_id = basket.image_id';
					break;

				case 'user-favorites' :
					$sql = ' LEFT JOIN ' . CONF_DB_PREF . 'favorites AS fav
								    ON img.image_id = fav.image_id';
					break;
			}
		}

		return $sql;
	}

	/**
	 * Retourne la clause WHERE correspondant
	 * aux critères de recherche des images à récupérer.
	 *
	 * @return string
	 */
	private static function _sqlWhereImages()
	{
		$sql = '';
		$params = array();

		if (isset($_GET['search']))
		{
			$r = search::getAdminImagesSQLWhere(
				search::$searchAdminImageFields,
				sql::$groupAlbumsAccess
			);
			if (!$r)
			{
				if ($_GET['object_id'] > 1)
				{
					utils::redirect('category/1/search/' . $_GET['search']);
				}
				else
				{
					utils::redirect('category/1');
				}
				return;
			}
			$sql = $r['sql'];
			$params = $r['params'];
			self::$searchInit = TRUE;
		}

		if (isset($_GET['filter']))
		{
			switch ($_GET['filter'])
			{
				case 'camera-brand' :
					$sql = ' cam_b.camera_brand_id = ' . (int) $_GET['cam_id'];
					break;

				case 'camera-model' :
					$sql = ' cam_i.camera_model_id = ' . (int) $_GET['cam_id'];
					break;

				case 'tag' :
					$sql = ' ti.tag_id = ' . (int) $_GET['tag_id'];
					break;

				case 'user-basket' :
					$sql = ' basket.user_id != 2
						 AND basket.user_id = ' . (int) $_GET['user_id'];
					break;

				case 'user-favorites' :
					$sql = ' fav.user_id != 2
						 AND fav.user_id = ' . (int) $_GET['user_id'];
					break;

				case 'user-images' :
					$sql = ' img.user_id != 2
						 AND img.user_id = ' . (int) $_GET['user_id'];
					break;
			}
		}

		return array(
			'sql' => $sql,
			'params' => $params
		);
	}

	/**
	 * Active ou désactive des images.
	 *
	 * @param array $selected_ids
	 *	Identifiants des images sélectionnées.
	 * @param integer $cat_id
	 *	Identifiant de la catégorie des images sélectionnées.
	 * @param integer $status
	 *	Nouveau statut (0 ou 1).
	 * @return void
	 */
	protected static function _status($selected_ids, $cat_id, $status)
	{
		if ($status !== 1 && $status !== 0)
		{
			return FALSE;
		}

		try
		{
			// Début de la transaction.
			if (!utils::$db->transaction())
			{
				throw new Exception(utils::$db->msgError);
			}

			// Récupération des id des catégories parentes et des
			// informations qui serviront à updater les catégories parentes.
			if (($parents_ids = alb::getParentsIds($cat_id)) === FALSE
			|| ($images = self::_getImagesInfos($selected_ids)) === FALSE)
			{
				if ($images === FALSE && utils::$db->nbResult === 0)
				{
					return;
				}
				throw new Exception(utils::$db->msgError);
			}

			// On récupère le nombre d'images activées de l'album.
			$sql = 'SELECT COUNT(image_id)
					  FROM ' . CONF_DB_PREF . 'images
					 WHERE image_status = "1"
					   AND cat_id = ' . (int) $cat_id;
			if (utils::$db->query($sql, 'value') === FALSE
			|| utils::$db->nbResult !== 1)
			{
				throw new Exception(utils::$db->msgError);
			}
			$nb_active_images = utils::$db->queryResult;

			// On détermine les valeurs des colonnes
			// des catégories parentes à updater.
			$cat_update = array();
			$cat_update['images'] = 0;
			$cat_update['albums'] = 0;
			$cat_update['size'] = 0;
			$cat_update['hits'] = 0;
			$cat_update['comments'] = 0;
			$cat_update['votes'] = 0;
			$cat_update['rate'] = 0;
			foreach ($images as $id => $infos)
			{
				// Si le statut est le même, on ignore l'image.
				if ($infos['image_status'] == $status)
				{
					continue;
				}

				// On recalcule la note moyenne.
				if ($infos['image_votes'] > 0)
				{
					$cat_update['rate'] =
						(($cat_update['rate'] * $cat_update['votes'])
						+ ($infos['image_rate'] * $infos['image_votes']))
						/ ($cat_update['votes'] + $infos['image_votes']);
					$cat_update['votes'] += $infos['image_votes'];
				}

				// Autres stats.
				$cat_update['images'] += 1;
				$cat_update['size'] += $infos['image_filesize'];
				$cat_update['hits'] += $infos['image_hits'];
				$cat_update['comments'] += $infos['image_comments'];
			}

			// Si aucune image n'est à mettre à jour, on arrête là.
			if ($cat_update['images'] == 0)
			{
				return;
			}

			// On détermine s'il faut updater le nombre d'albums
			// des statistiques des catégories parentes.
			if (($status == 1 && $nb_active_images == 0)
			|| ($status == 0 && $nb_active_images == $cat_update['images']))
			{
				$cat_update['albums'] = 1;
			}

			$sql = array();

			// Changement du statut des images sélectionnées.
			$sql[] = 'UPDATE ' . CONF_DB_PREF . 'images
						 SET image_status = "' . $status . '"
					   WHERE image_id IN (' . implode(', ', $selected_ids) . ')
						 AND image_status != "' . $status . '"';

			// Mise à jour des statistiques des catégories parentes.
			$cat_update = array('a' => $cat_update, 'd' => $cat_update);
			switch ($status)
			{
				case 0 :
					$sql[] = alb::updateParentsStats(
						$cat_update, '-', '+', $parents_ids);
					break;

				case 1 :
					$sql[] = alb::updateParentsStats(
						$cat_update, '+', '-', $parents_ids);
					break;
			}

			// Exécution des requêtes.
			if (utils::$db->exec($sql, FALSE) === FALSE)
			{
				throw new Exception(utils::$db->msgError);
			}

			// On met à jour certaines informations des catégories parentes.
			reset($images);
			$i = current($images);
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

			return TRUE;
		}
		catch (Exception $e)
		{
			self::report('error:' . $e->getMessage());
			return FALSE;
		}
	}

	/**
	 * Met à jour (poids, dimensions et métadonnées) les images sélectionnées.
	 *
	 * @param array $selected_ids
	 *	Identifiants des images sélectionnées.
	 * @param integer $cat_id
	 *	Identifiant de la catégorie des images sélectionnées.
	 * @return integer|boolean
	 *	1 : mise à jour des images réussie
	 *	0 : aucune image mise à jour
	 *	FALSE : erreur
	 */
	protected static function _update($selected_ids, $cat_id)
	{
		try
		{
			// Récupération du chemin des images.
			$sql = 'SELECT image_id,
						   image_path
					  FROM ' . CONF_DB_PREF . 'images
					 WHERE image_id IN (' . implode(', ', $selected_ids) . ')';
			$fetch_style = array('column' => array('image_id', 'image_path'));
			if (utils::$db->query($sql, $fetch_style) === FALSE
			 || utils::$db->nbResult === 0)
			{
				throw new Exception(utils::$db->msgError);
			}
			$images = utils::$db->queryResult;
			$images = array_map('basename', $images);

			// Récupération du chemin de l'album.
			$sql = 'SELECT cat_path
					  FROM ' . CONF_DB_PREF . 'categories
					 WHERE cat_id = ' . (int) $cat_id;
			if (utils::$db->query($sql, 'value') === FALSE
			|| utils::$db->nbResult === 0)
			{
				throw new Exception(utils::$db->msgError);
			}
			$album_path = utils::$db->queryResult;

			// Initialisation du scan.
			$upload = new upload();
			if ($upload->getInit === FALSE)
			{
				throw new Exception(utils::$db->msgError);
			}

			// Options de scan.
			$upload->setForcedScan = TRUE;
			$upload->setHttp = TRUE;
			$upload->setHttpImages = array_flip($images);
			$upload->setMailAlert = FALSE;
			$upload->setReportAllFiles = FALSE;
			$upload->setUpdateImages = TRUE;
			$upload->setUpdateThumbId = FALSE;

			// Scan du répertoire des albums.
			if ($upload->getAlbums($album_path) === FALSE)
			{
				throw new Exception(__('Une erreur s\'est produite : '
					. 'la mise à jour de la base de données a échouée.'));
			}

			// Contrôle du temps d'exécution.
			if ($upload->getTimeExceeded)
			{
				throw new Exception('Scan time exceeded.');
			}

			return ($upload->getReport['img_update'] > 0
				 || $upload->getReport['tag_add'] > 0
				 || $upload->getReport['camera_add'] > 0)
				? 1
				: 0;
		}
		catch (Exception $e)
		{
			self::report('error:' . $e->getMessage());
			return FALSE;
		}
	}
}

/**
 * Authentification utilisateur.
 */
class auth extends admin
{
	/**
	 * Informations de l'utilisateur authentifié.
	 *
	 * @var array
	 */
	public static $infos;

	/**
	 * Droits utilisateur.
	 *
	 * @var array
	 */
	public static $perms;



	/**
	 * Authentification utilisateur par cookie.
	 *
	 * @return boolean
	 */
	public static function checkSession()
	{
		// Récupération de l'identifiant de session que possède l'utilisateur.
		if (($session_token = user::getSessionCookieToken()) === FALSE)
		{
			return FALSE;
		}

		// Récupération des informations et permissions utilisateur
		// si identifiant de session valide.
		$sql = 'SELECT u.group_id,
					   group_admin,
					   group_perms,
					   user_id,
					   user_login,
					   user_password,
					   user_name,
					   user_avatar,
					   user_lang,
					   user_tz,
					   user_email,
					   user_prefs,
					   user_crtdt,
					   user_lastvstdt
				  FROM ' . CONF_DB_PREF . 'sessions AS s,
					   ' . CONF_DB_PREF . 'groups AS g,
					   ' . CONF_DB_PREF . 'users AS u
				 WHERE u.session_id = s.session_id
				   AND u.group_id = g.group_id
				   AND user_status = "1"
				   AND session_token = :session_token
				   AND session_expire > NOW()
				   AND group_admin = "1"';
		$params = array(
			'session_token' => $session_token
		);
		if (utils::$db->prepare($sql) === FALSE
		|| utils::$db->executeQuery($params, 'row') === FALSE
		|| utils::$db->nbResult !== 1)
		{
			return FALSE;
		}
		$infos = utils::$db->queryResult;
		$perms = $infos['group_perms'];
		unset($infos['group_perms']);
		if (!utils::isSerializedArray($perms))
		{
			return FALSE;
		}

		// Il n'y a que l'utilisateur à l'user_id 1 qui peut être superadmin !
		if ($infos['user_id'] != 1 && $infos['group_id'] == 1)
		{
			return FALSE;
		}

		// Vérification du jeton anti-CSRF.
		if ($_GET['section'] == 'album'
		&& isset($_POST['upload']) && isset($_FILES['files']))
		{
			utils::$config['anticsrf_token_expire'] =
				((int) utils::$config['anticsrf_token_expire'] < 7200)
				? 7200 : utils::$config['anticsrf_token_expire'];
		}
		if (!utils::antiCSRFTokenCheck(utils::$cookiePrefs))
		{
			self::report('warning:' . __('Le jeton de formulaire a expiré. '
				. 'Vous devez recommencer l\'opération pour que vos actions '
				. 'soient prises en compte.'));
		}

		// Déconnexion.
		if (isset($_POST['deconnect']) && user::deconnect($infos['user_id'], $session_token))
		{
			utils::$purlFile = 'connexion.php';
			utils::redirect('', TRUE);
			die;
		}

		self::$infos = $infos;
		self::$perms = unserialize($perms);

		// Langue et fuseau horaire de l'utilisateur.
		if (isset(utils::$config['locale_langs'][$infos['user_lang']]))
		{
			utils::$userLang = $infos['user_lang'];
		}
		utils::$userTz = $infos['user_tz'];

		// Options d'affichage et valeurs par défaut.
		self::$infos['user_prefs'] = (utils::isSerializedArray($infos['user_prefs']))
			? unserialize($infos['user_prefs'])
			: array();

		// Langues d'édition.
		if (empty(self::$infos['user_prefs']['langs_edition']))
		{
			self::$infos['user_prefs']['langs_edition'] = CONF_DEFAULT_LANG;
		}

		// Paramètres des options d'affichage.
		self::_displayOptions();

		// Mode d'accès aux albums.
		sql::albumsAccess(self::$perms['admin']);

		// Mise à jour de la date de dernière visite.
		$sql = 'UPDATE ' . CONF_DB_PREF . 'users
				   SET user_lastvstdt = NOW(),
					   user_lastvstip = :lastvstip
				 WHERE user_id = :id';
		$params = array(
			'lastvstip' => $_SERVER['REMOTE_ADDR'],
			'id' => (int) $infos['user_id']
		);
		utils::$db->prepare($sql);
		utils::$db->executeExec($params);

		// Nouveau jeton anti-CSRF.
		utils::antiCSRFTokenNew(utils::$cookiePrefs);

		// Mise à jour de la session.
		$sql = 'UPDATE ' . CONF_DB_PREF . 'sessions
				   SET session_expire = DATE_ADD(NOW(), INTERVAL :expire SECOND)
				 WHERE session_token = :session_token
				   AND session_expire > NOW()';
		$params = array(
			'expire' => (int) utils::$config['sessions_expire'],
			'session_token' => $session_token
		);
		utils::$db->prepare($sql);
		utils::$db->executeExec($params);

		return TRUE;
	}



	/**
	 * Détermine la valeur des options d'affichage pour les objets.
	 *
	 * @return void
	 */
	private static function _displayOptions()
	{
		// Images.
		self::_displayOptionsValue('album', 'nb_per_page', 5);
		self::_displayOptionsValue('album', 'sortby', 'adddt',
			array('adddt', 'crtdt', 'name', 'path', 'position'));
		self::_displayOptionsValue('album', 'orderby', 'DESC',
			array('ASC', 'DESC'));

		// Marques d'appareils photos.
		self::_displayOptionsValue('camera_brands', 'nb_per_page', 20);
		self::_displayOptionsValue('camera_brands', 'sortby', 'name',
			array('name', 'nb_images'));
		self::_displayOptionsValue('camera_brands', 'orderby', 'ASC',
			array('ASC', 'DESC'));

		// Modèles d'appareils photos.
		self::_displayOptionsValue('camera_models', 'nb_per_page', 20);
		self::_displayOptionsValue('camera_models', 'sortby', 'name',
			array('name', 'nb_images'));
		self::_displayOptionsValue('camera_models', 'orderby', 'ASC',
			array('ASC', 'DESC'));

		// Catégories.
		self::_displayOptionsValue('category', 'nb_per_page', 5);
		self::_displayOptionsValue('category', 'sortby', 'adddt',
			array('adddt', 'crtdt', 'name', 'path', 'position'));
		self::_displayOptionsValue('category', 'orderby', 'DESC',
			array('ASC', 'DESC'));

		// Commentaires.
		self::_displayOptionsValue('comments', 'nb_per_page', 5);
		self::_displayOptionsValue('comments', 'sortby', 'crtdt',
			array('lastupddt', 'crtdt'));
		self::_displayOptionsValue('comments', 'orderby', 'DESC',
			array('ASC', 'DESC'));

		// Livre d'or.
		self::_displayOptionsValue('guestbook', 'nb_per_page', 5);
		self::_displayOptionsValue('guestbook', 'sortby', 'crtdt',
			array('lastupddt', 'crtdt'));
		self::_displayOptionsValue('guestbook', 'orderby', 'DESC',
			array('ASC', 'DESC'));

		// Images en attente de validation.
		self::_displayOptionsValue('pending', 'nb_per_page', 5);
		self::_displayOptionsValue('pending', 'sortby', 'adddt',
			array('name', 'adddt'));
		self::_displayOptionsValue('pending', 'orderby', 'DESC',
			array('ASC', 'DESC'));

		// Logs.
		self::_displayOptionsValue('logs', 'nb_per_page', 20);
		self::_displayOptionsValue('logs', 'sortby', 'date',
			array('date'));
		self::_displayOptionsValue('logs', 'orderby', 'DESC',
			array('ASC', 'DESC'));

		// Tags.
		self::_displayOptionsValue('tags', 'nb_per_page', 20);
		self::_displayOptionsValue('tags', 'sortby', 'name',
			array('name', 'nb_images'));
		self::_displayOptionsValue('tags', 'orderby', 'ASC',
			array('ASC', 'DESC'));

		// Utilisateurs.
		self::_displayOptionsValue('users', 'nb_per_page', 5);
		self::_displayOptionsValue('users', 'sortby', 'crtdt',
			array('login', 'crtdt', 'lastvstdt'));
		self::_displayOptionsValue('users', 'orderby', 'DESC',
			array('ASC', 'DESC'));

		// Votes.
		self::_displayOptionsValue('votes', 'nb_per_page', 10);
		self::_displayOptionsValue('votes', 'sortby', 'date',
			array('date', 'rate'));
		self::_displayOptionsValue('votes', 'orderby', 'DESC',
			array('ASC', 'DESC'));
	}

	/**
	 * Détermine la valeur des options d'affichage pour les objets.
	 *
	 * @param string $section
	 *	Section courante.
	 * @param string $pref
	 *	Nom de l'option.
	 * @param string $default
	 *	Valeur par défaut.
	 * @param array $values
	 *	Valeurs possibles.
	 * @return void
	 */
	private static function _displayOptionsValue($section, $pref, $default, $values = NULL)
	{
		if ($values === NULL)
		{
			$val = (isset(auth::$infos['user_prefs'][$section][$pref]))
				? (int) auth::$infos['user_prefs'][$section][$pref]
				: $default;
			if ($val < 1)
			{
				$val = $default;
			}
			auth::$infos['user_prefs'][$section][$pref] = $val;
		}
		else
		{
			auth::$infos['user_prefs'][$section][$pref] =
				(isset(auth::$infos['user_prefs'][$section][$pref])
				&& in_array(auth::$infos['user_prefs'][$section][$pref], $values))
				? auth::$infos['user_prefs'][$section][$pref]
				: $default;
		}
	}
}

/**
 * Gestion des appareils photos.
 */
class cameras extends admin
{
	/**
	 * Informations sur l'élément courant.
	 *
	 * @var array
	 */
	public static $infos;

	/**
	 * Liste des éléments récupérés.
	 *
	 * @var array
	 */
	public static $items;

	/**
	 * Nombre d'éléments.
	 *
	 * @var integer
	 */
	public static $nbItems;

	/**
	 * Nombre de pages.
	 *
	 * @var integer
	 */
	public static $nbPages;

	/**
	 * Champs de recherche.
	 *
	 * @var array
	 */
	public static $searchFields = array(
		'brand' => array(
			'camera_brand_name',
			'camera_brand_url'
		),
		'model' => array(
			'camera_model_name',
			'camera_model_url'
		)
	);

	/**
	 * Options de recherche.
	 *
	 * @var array
	 */
	public static $searchOptions = array(
		'brand' => array(
			'all_words' => 'bin',
			'camera_brand_name' => 'bin',
			'camera_brand_nb_images' => 'bin',
			'camera_brand_nb_images_max' => '\d{1,6}',
			'camera_brand_nb_images_min' => '\d{1,6}',
			'camera_brand_url' => 'bin'
		),
		'model' => array(
			'all_words' => 'bin',
			'camera_model_name' => 'bin',
			'camera_model_nb_images' => 'bin',
			'camera_model_nb_images_max' => '\d{1,6}',
			'camera_model_nb_images_min' => '\d{1,6}',
			'camera_model_url' => 'bin'
		)
	);



	/**
	 * Édition des éléments.
	 *
	 * @param string $type
	 * @return void
	 */
	public static function edit($type)
	{
		if (!isset($_POST['save']))
		{
			return;
		}

		foreach (self::$items as $id => &$infos)
		{
			if (empty($_POST[$id]) || !is_array($_POST[$id]))
			{
				continue;
			}

			$columns = array();
			$params = array();

			// Nom.
			if (($new_name = self::_editName($type, $id, $infos, 'name')) !== FALSE)
			{
				$columns[] = 'camera_' . $type . '_name = :name';
				$params['name'] = $new_name;
			}

			// Nom d'URL.
			if (($new_url = self::_editName($type, $id, $infos, 'url')) !== FALSE)
			{
				$columns[] = 'camera_' . $type . '_url = :url';
				$params['url'] = $new_url;
			}

			if ($columns === array())
			{
				continue;
			}

			// On effectue la mise à jour.
			if (!empty($params))
			{
				$sql = 'UPDATE IGNORE ' . CONF_DB_PREF . 'cameras_' . $type . 's
					 	   SET ' . implode(', ', $columns) . '
						 WHERE camera_' . $type . '_id = ' . (int) $id;
				if (utils::$db->prepare($sql) === FALSE
				|| utils::$db->executeExec($params) === FALSE)
				{
					self::report('error:' . utils::$db->msgError, $id);
					continue;
				}
			}

			// Mise à jour du tableau des éléments.
			foreach (array('name', 'url') as $p)
			{
				if (isset($params[$p]))
				{
					$infos['camera_' . $type . '_' . $p] = $params[$p];
				}
			}

			self::report('success:' . __('Modifications enregistrées.'));
			self::report('success_p:' . __('Les autres modifications ont été enregistrées.'));
		}
	}

	/**
	 * Récupération des informations utiles de l'élément courant.
	 *
	 * @return void
	 */
	public static function getInfos()
	{
		if (!isset($_GET['filter']) || substr($_GET['filter'], 0, 6) != 'camera')
		{
			return;
		}

		// Marque.
		if ($_GET['filter'] == 'camera-brand')
		{
			$sql = 'SELECT camera_brand_name AS name
					  FROM ' . CONF_DB_PREF . 'cameras_brands
					 WHERE camera_brand_id = ' . $_GET['cam_id'];
		}

		// Modèle.
		if ($_GET['filter'] == 'camera-model')
		{
			$sql = 'SELECT camera_model_name AS name
					  FROM ' . CONF_DB_PREF . 'cameras_models
					 WHERE camera_model_id = ' . $_GET['cam_id'];
		}

		if (utils::$db->query($sql, 'row') === FALSE
		|| utils::$db->nbResult === 0)
		{
			return;
		}

		self::$infos = utils::$db->queryResult;
	}

	/**
	 * Récupération des marques ou modèles d'appareils photos.
	 *
	 * @param string $type
	 * @return void
	 */
	public static function getItems($type)
	{
		$sql_where = '1=1';
		$params = array();

		// Sous-requête permettant de récupérer
		// le nombre d'images liées à chaque élément.
		$sql_nb_images = ($type == 'brand')
			? 'SELECT COUNT(*)
			     FROM ' . CONF_DB_PREF . 'cameras_models_images AS cam_i,
					  ' . CONF_DB_PREF . 'cameras_models AS cam_m
			    WHERE cam_m.camera_brand_id = cam_t.camera_brand_id
			      AND cam_i.camera_model_id = cam_m.camera_model_id'
			: 'SELECT COUNT(*)
			     FROM ' . CONF_DB_PREF . 'cameras_models_images AS cam_i
			    WHERE cam_i.camera_model_id = cam_t.camera_model_id';

		// Moteur de recherche.
		if (isset($_GET['search_query']))
		{
			self::$_sqlSearch = search::getSQLWhere(
				self::$searchFields[$type], FALSE, TRUE
			);
			if (self::$_sqlSearch)
			{
				// Nombre d'images liées.
				if (isset($_GET['search_cam_nb_images'])
				 && isset($_GET['search_cam_nb_images_max'])
				 && isset($_GET['search_cam_nb_images_min']))
				{
					$sql_where .=
						' AND (' . $sql_tag_nb_images . ') >= '
							. (int) $_GET['search_cam_nb_images_min'] . '
						  AND (' . $sql_tag_nb_images . ') <= '
							. (int) $_GET['search_cam_nb_images_max'];
				}

				$sql_where .= ' AND ' . self::$_sqlSearch['sql'];
				$params = array_merge($params, self::$_sqlSearch['params']);
				self::$searchInit = TRUE;
			}
		}

		// Détermine le nombre d'éléments.
		$sql = 'SELECT COUNT(*)
				  FROM ' . CONF_DB_PREF . 'cameras_' . $type . 's AS cam_t
				 WHERE ' . $sql_where;
		if (utils::$db->prepare($sql) === FALSE
		|| utils::$db->executeQuery($params, 'value') === FALSE)
		{
			return;
		}
		self::$nbItems = (int) utils::$db->queryResult;

		// Nombre de pages.
		$items_per_page = auth::$infos['user_prefs']['camera_' . $type . 's']['nb_per_page'];
		self::$nbPages = ceil(self::$nbItems / $items_per_page);
		$sql_limit_start = $items_per_page * ($_GET['page'] - 1);

		// Critère de tri.
		$sql_order_by =
			(auth::$infos['user_prefs']['camera_' . $type . 's']['sortby'] == 'nb_images')
			? 'camera_' . $type . '_' . auth::$infos['user_prefs']
				['camera_' . $type . 's']['sortby'] . ' '
			: 'LOWER(camera_' . $type . '_' . auth::$infos['user_prefs']
				['camera_' . $type . 's']['sortby'] . ') ';
		$sql_order_by .= auth::$infos['user_prefs']['camera_' . $type . 's']['orderby']
			. ', camera_' . $type . '_id '
			. auth::$infos['user_prefs']['camera_' . $type . 's']['orderby'];

		// Récupération des éléments.
		$sql = 'SELECT *,
					   (' . $sql_nb_images . ') AS camera_' . $type . '_nb_images
				  FROM ' . CONF_DB_PREF . 'cameras_' . $type . 's AS cam_t
				 WHERE ' . $sql_where . '
			  ORDER BY ' . $sql_order_by . '
				 LIMIT ' . $sql_limit_start . ',' . $items_per_page;
		$fetch_style = array(
			'fetch' => PDO::FETCH_ASSOC, 'column' => 'camera_' . $type . '_id'
		);
		if (utils::$db->prepare($sql) === FALSE
		|| utils::$db->executeQuery($params, $fetch_style) === FALSE)
		{
			return;
		}
		if (!admin::_objectsNbResult())
		{
			return;
		}
		self::$items = utils::$db->queryResult;
	}


	/**
	 * Édition du nom et du nom d'URL.
	 *
	 * @param string $type
	 * @param integer $id
	 * @param array $infos
	 * @param string $col_name
	 * @return string|boolean
	 */
	protected static function _editName($type, $id, &$infos, $col_name)
	{
		if (!isset($_POST[$id][$col_name])
		|| $_POST[$id][$col_name] === $infos['camera_' . $type . '_' . $col_name])
		{
			return FALSE;
		}

		// Vérification de la longueur.
		if (mb_strlen($_POST[$id][$col_name]) < 1)
		{
			return FALSE;
		}

		return $_POST[$id][$col_name];
	}
}

/**
 * Gestion des catégories.
 */
class category extends albums
{
	/**
	 * Champs de la table "category" pour la recherche.
	 *
	 * @var array
	 */
	public static $searchFields = array(
		'cat_desc',
		'cat_name',
		'cat_path',
		'cat_url'
	);

	/**
	 * Options de recherche de catégories.
	 *
	 * @var array
	 */
	public static $searchOptions = array(
		'all_words' => 'bin',
		'date' => 'bin',
		'date_field' => '(?:cat_crtdt|cat_lastadddt)',
		'date_end_day' => '\d{2}',
		'date_end_month' => '\d{2}',
		'date_end_year' => '\d{4}',
		'date_start_day' => '\d{2}',
		'date_start_month' => '\d{2}',
		'date_start_year' => '\d{4}',
		'cat_desc' => 'bin',
		'cat_name' => 'bin',
		'cat_path' => 'bin',
		'cat_url' => 'bin',
		'status' => '(?:all|publish|unpublish)',
		'type' => 'category',
		'user' => '(?:all|\d{1,11})'
	);

	/**
	 * Liste des différentes feuilles de style disponibles.
	 *
	 * @var array
	 */
	public static $styles;



	/**
	 * Actions sur la sélection de catégories.
	 *
	 * @return void
	 */
	public static function actions()
	{
		if (($selected_ids = self::_initObjectsActions()) === FALSE)
		{
			return;
		}

		// Permissions d'accès.
		if (!auth::$perms['admin']['perms']['albums_modif']
		&& !auth::$perms['admin']['perms']['all'])
		{
			die;
		}

		$cat_ids = array();

		// Recherche.
		if (isset($_GET['search']))
		{
			$sql = 'SELECT parent_id,
						   cat_id
					  FROM ' . CONF_DB_PREF . 'categories
					 WHERE cat_id IN (' . implode(', ', $selected_ids) . ')'
						 . sql::$groupAlbumsAccess;
			$fetch_style = array('fetch' => PDO::FETCH_ASSOC, 'column' => 'cat_id');
			if (utils::$db->query($sql, $fetch_style) === FALSE)
			{
				throw new Exception(utils::$db->msgError);
			}
			foreach (utils::$db->queryResult as $cat_id => &$ids)
			{
				if (!isset($cat_ids[$ids['parent_id']]))
				{
					$cat_ids[$ids['parent_id']] = array();
				}
				$cat_ids[$ids['parent_id']][] = $ids['cat_id'];
			}
		}

		// Catégorie.
		else
		{
			$cat_ids[$_GET['object_id']] = $selected_ids;
		}

		foreach ($cat_ids as $cat_id => &$categories)
		{
			// Action à effectuer.
			switch ($_POST['action'])
			{
				// Suppression.
				case 'delete' :
					if (self::_delete($categories, $cat_id))
					{
						self::report('success:'
							. __('Les catégories sélectionnées ont été supprimées.'));
					}
					break;

				// Déplacement.
				case 'move' :
					if (self::_move($categories, $cat_id))
					{
						self::report('success:'
							. __('Les catégories sélectionnées ont été déplacées.'));
						self::report('success_p:'
							. __('Les autres catégories ont été déplacées.'));
					}
					break;

				// Changement de propriétaire.
				case 'owner' :
					if (self::_owner($categories))
					{
						self::report('success:' . __('Le propriétaire'
							. ' des catégories sélectionnées a été changé.'));
					}
					break;

				// Activation.
				case 'publish' :
					if (self::_status($categories, $cat_id, 1))
					{
						self::report('success:'
							. __('Les catégories sélectionnées ont été activées.'));
					}
					break;

				// Remise à zéro du nombre de visites.
				case 'reset_hits' :
					if (self::_resetHits($categories, $cat_id))
					{
						self::report('success:' . __('Le nombre de visites des '
							. 'catégories sélectionnées a été remis à zéro.'));
					}
					break;

				// Désactivation.
				case 'unpublish' :
					if (self::_status($categories, $cat_id, 0))
					{
						self::report('success:'
							. __('Les catégories sélectionnées ont été désactivées.'));
					}
					break;
			}
		}
	}

	/**
	 * Récupération des informations de la catégorie.
	 *
	 * @param string $sql
	 * @return void
	 */
	public static function infos($sql = '')
	{
		// Nombre d'objets par page.
		$section = ($_GET['section'] == 'category'
			&& !isset($_GET['search']) && !isset($_GET['filter']))
			? 'category'
			: 'album';
		self::$nbPerPage = auth::$infos['user_prefs'][$section]['nb_per_page'];

		// Type de catégorie.
		switch (preg_replace('`^.*(album|category)$`', '$1', $_GET['section']))
		{
			case 'album' :
				$sql_type = ' AND cat_filemtime IS NOT NULL ';
				break;

			case 'category' :
				$sql_type = ' AND cat_filemtime IS NULL ';
				break;

			default :
				$sql_type = '';
				break;
		}

		$sql = ($sql)
			? $sql
			: 'SELECT cat.*,
					  cat_a_images + cat_d_images AS cat_images,
					  cat_a_subalbs + cat_d_subalbs +
					  cat_a_subcats + cat_d_subcats AS cat_subs,
					  cat.cat_tb_infos AS tb_infos,
					  img.image_id,
					  img.image_path,
					  img.image_width,
					  img.image_height,
					  img.image_adddt,
					  u.user_login,
					  u.user_status
				 FROM ' . CONF_DB_PREF . 'categories AS cat
			LEFT JOIN ' . CONF_DB_PREF . 'users AS u
				   ON cat.user_id = u.user_id
			LEFT JOIN ' . CONF_DB_PREF . 'images AS img
				   ON cat.thumb_id = img.image_id
				WHERE cat.cat_id = ' . (int) $_GET['object_id']
					. $sql_type
					. sql::$groupAlbumsAccess;
		if (utils::$db->query($sql, 'row') === FALSE
		|| utils::$db->nbResult === 0)
		{
			if (isset($_GET['object_id']) && $_GET['object_id'] != 1)
			{
				if (isset($_GET['filter']) && substr($_GET['filter'], 0, 5) == 'user-')
				{
					utils::redirect('category/1/' . $_GET['filter'] . '/' . $_GET['user_id']);
				}
				else if (isset($_GET['search']))
				{
					utils::redirect('category/1/search/' . $_GET['search']);
				}
				else
				{
					utils::redirect('category/1');
				}
			}
			return;
		}
		self::$infos = utils::$db->queryResult;

		// Catégorie ou album ?
		// Et nombre d'éléments dans la catégorie courante.
		if (self::$infos['cat_filemtime'] === NULL)
		{
			self::$infos['cat_type'] = 'category';
			if (isset(self::$infos['cat_subs']))
			{
				self::$nbItems = self::$infos['cat_subs'];
			}
		}
		else
		{
			self::$infos['cat_type'] = 'album';
			if (isset(self::$infos['cat_images']))
			{
				self::$nbItems = self::$infos['cat_images'];
			}
		}

		// Nom de la catégorie.
		self::$infos['cat_name'] = (self::$infos['cat_id'] == 1)
			? __('galerie')
			: self::$infos['cat_name'];

		// Groupes ayant accès à la catégorie.
		albums::getAllowedGroups(self::$infos['cat_password']);
	}

	/**
	 * Récupération des informations utiles
	 * des catégories de la page courante.
	 *
	 * @param boolean $sort_categories
	 *	Option pour la page de tri des ccatégories.
	 * @return void
	 */
	public static function getCategories($sort_categories = FALSE)
	{
		if ($sort_categories)
		{
			$limit = '';
			$order_by = 'cat_position ASC';
		}
		else
		{
			$limit = self::$_catSqlStart . ','
				. auth::$infos['user_prefs']['category']['nb_per_page'];
			$limit = ' LIMIT ' . $limit;

			$order_by = str_replace('adddt', 'crtdt',
				auth::$infos['user_prefs']['category']['sortby']);
			$ascdesc = auth::$infos['user_prefs']['category']['orderby'];
			$order_by = 'LOWER(cat_' . $order_by . ') '
				. $ascdesc . ', cat_id DESC';
		}

		// Moteur de recherche.
		if (isset($_GET['search_query']))
		{
			$sql_where = '';

			self::$_sqlSearch = search::getSQLWhere(self::$searchFields, FALSE, TRUE);
			if (self::$_sqlSearch
			 && self::$_sqlSearch['params'] !== NULL
			 && self::$_sqlSearch['sql'] !== NULL)
			{
				// Statut.
				if (isset($_GET['search_status']))
				{
					switch ($_GET['search_status'])
					{
						case 'publish' :
							self::$_sqlSearch['sql'] .= ' AND cat_status = "1"';
							break;

						case 'unpublish' :
							self::$_sqlSearch['sql'] .= ' AND cat_status = "0"';
							break;
					}
				}

				// Utilisateur.
				if (isset($_GET['search_user'])
				&& preg_match('`^\d{1,11}$`', $_GET['search_user']))
				{
					self::$_sqlSearch['sql'] .=
						' AND cat.user_id = ' . (int) $_GET['search_user'];
				}

				if ($_GET['section'] == 'category'
				|| isset($_GET['search_type']) && $_GET['search_type'] == 'category')
				{
					self::$_sqlSearch['sql'] = str_replace(
						'image_path LIKE', 'cat_path LIKE', self::$_sqlSearch['sql']
					);
				}

				$sql_where .= self::$_sqlSearch['sql'];
				$params_search = self::$_sqlSearch['params'];
				self::$searchInit = TRUE;
			}
			else
			{
				utils::redirect('category/1');
				return;
			}

			// Limitation à la catégorie courante.
			if (self::$infos['cat_id'] > 1)
			{
				$sql_where .= ' AND cat_path LIKE CONCAT(:path, "/%")';
				$params_search['path'] = self::$infos['cat_path'];
			}

			// Nombre de catégories.
			$sql = 'SELECT COUNT(*)
					  FROM ' . CONF_DB_PREF . 'categories AS cat
					 WHERE ' . $sql_where . '
					   AND cat.cat_id != 1
						   ' . sql::$groupAlbumsAccess;
			if (utils::$db->prepare($sql) === FALSE
			|| utils::$db->executeQuery($params_search, 'value') === FALSE)
			{
				return;
			}
			self::$nbItems = utils::$db->queryResult;

			self::pages();
		}

		// Catégorie.
		else if (isset($_GET['object_id']))
		{
			$sql_where = 'cat.parent_id = ' . (int) $_GET['object_id'];
		}

		else
		{
			utils::redirect('category/1');
			return;
		}

		// Récupération des catégories.
		$sql = 'SELECT cat.*,
					   cat.cat_tb_infos AS tb_infos,
					   CASE WHEN cat_filemtime IS NULL
							THEN "category" ELSE "album"
							 END AS type,
					   img.image_id,
					   img.image_path,
					   img.image_width,
					   img.image_height,
					   img.image_adddt,
					   u.user_login,
					   u.user_status
				  FROM ' . CONF_DB_PREF . 'categories AS cat
			 LEFT JOIN ' . CONF_DB_PREF . 'users AS u
					ON cat.user_id = u.user_id
			 LEFT JOIN ' . CONF_DB_PREF . 'images AS img
					ON cat.thumb_id = img.image_id
				 WHERE ' . $sql_where . '
				   AND cat.cat_id != 1
					   ' . sql::$groupAlbumsAccess . '
			  ORDER BY ' . $order_by . $limit;
		$fetch_style = array('fetch' => PDO::FETCH_ASSOC, 'column' => 'cat_id');
		if (isset($_GET['search_query']))
		{
			if (utils::$db->prepare($sql) === FALSE
			|| utils::$db->executeQuery($params_search, $fetch_style) === FALSE)
			{
				return;
			}
		}
		else if (utils::$db->query($sql, $fetch_style) === FALSE)
		{
			return;
		}
		if (!admin::_objectsNbResult())
		{
			return;
		}
		albums::$items = utils::$db->queryResult;
	}

	/**
	 * Réduit la liste des catégories
	 * selon la recherche effectuée.
	 *
	 * @return void
	 */
	public static function reduceMap()
	{
		if (!self::$searchInit)
		{
			return;
		}

		$sql = 'SELECT cat.parent_id AS cat_id
				  FROM ' . CONF_DB_PREF . 'categories AS cat
				 WHERE ' . self::$_sqlSearch['sql'];
		$fetch_style = array('column' => array('cat_id', 'cat_id'));
		if (utils::$db->prepare($sql) === FALSE
		|| utils::$db->executeQuery(self::$_sqlSearch['params'], $fetch_style) === FALSE)
		{
			return;
		}

		self::_reduceMapCategories(utils::$db->queryResult);
	}

	/**
	 * Édition des catégories et albums.
	 *
	 * @return void
	 */
	public static function edit()
	{
		if (!isset($_POST['save']))
		{
			return;
		}

		$get_categories = FALSE;

		foreach (albums::$items as $id => &$infos)
		{
			if (empty($_POST[$id]) || !is_array($_POST[$id]))
			{
				continue;
			}

			if (self::editInfosSettings($id, $infos))
			{
				$get_categories = TRUE;
			}
		}

		// On récupère à nouveau les catégories s'il y a eu des modifications.
		if ($get_categories)
		{
			category::getCategories();
		}
	}

	/**
	 * Suppression d'une catégorie.
	 *
	 * @return void
	 */
	public static function delete()
	{
		if (!isset($_POST['delete']))
		{
			return;
		}

		if (self::_delete(array($_GET['object_id']), self::$infos['parent_id']))
		{
			utils::redirect('category/' . self::$infos['parent_id']);
		}
	}

	/**
	 * Édition du nombre de visites, du statut,
	 * de la catégorie parente et du propriétaire d'une catégorie.
	 *
	 * @return void
	 */
	public static function editGeneral()
	{
		if (!isset($_POST['save']))
		{
			return;
		}

		// Permissions d'accès.
		if (!auth::$perms['admin']['perms']['albums_modif']
		&& !auth::$perms['admin']['perms']['all'])
		{
			die;
		}

		$selected_ids = array($_GET['object_id']);
		$success = FALSE;

		// Nombre de visites à zéro.
		if (isset($_POST['reset_hits'])
		&& self::_resetHits($selected_ids, self::$infos['parent_id']))
		{
			$success = TRUE;
		}

		// Changement de statut.
		if ($_POST['status'] != self::$infos['cat_status']
		&& (self::$infos['cat_a_images'] + self::$infos['cat_d_images'] > 0)
		&& self::_status($selected_ids, self::$infos['parent_id'], (int) $_POST['status']))
		{
			$success = TRUE;
		}

		// Changement de catégorie.
		if ($_POST['destination_cat'] != self::$infos['parent_id']
		&& self::_move($selected_ids, self::$infos['parent_id']))
		{
			$success = TRUE;
		}

		// Changement de propriétaire.
		if ($_POST['owner'] != self::$infos['user_id']
		&& self::_owner($selected_ids))
		{
			$success = TRUE;
		}

		if ($success)
		{
			self::report('success:' . __('Modifications enregistrées.'));
			self::report('success_p:' . __('Les autres modifications ont été enregistrées.'));
			self::infos();
		}
	}

	/**
	 * Édition des informations et réglages d'une catégorie.
	 *
	 * @param integer $id
	 *	Identifiant de la catégorie.
	 * @param array $infos
	 *	Informations de la catégorie.
	 * @return boolean
	 */
	public static function editInfosSettings($id, &$infos)
	{
		if (!isset($_POST['save']))
		{
			return;
		}

		$change = FALSE;
		$columns = array();
		$params = array();

		// Latitude.
		if (($new_latitude = self::_editGeoloc($id, $infos, 'cat_lat')) !== FALSE)
		{
			$columns[] = 'cat_lat = :cat_lat';
			$params['cat_lat'] = $new_latitude;
			$change = TRUE;
		}

		// Longitude.
		if (($new_longitude = self::_editGeoloc($id, $infos, 'cat_long')) !== FALSE)
		{
			$columns[] = 'cat_long = :cat_long';
			$params['cat_long'] = $new_longitude;
			$change = TRUE;
		}

		// Lieu.
		if (($new_place = self::_editPlace($id, $infos, 'cat_place')) !== FALSE)
		{
			$columns[] = 'cat_place = :cat_place';
			$params['cat_place'] = $new_place;
			$change = TRUE;
		}

		if (substr($_GET['section'], 0, 6) != 'geoloc')
		{
			// Titre.
			if (($new_title = self::_editTitle($id, $infos, 'cat_name')) !== FALSE)
			{
				$columns[] = 'cat_name = :cat_name';
				$params['cat_name'] = $new_title;
				$change = TRUE;
			}

			// Nom d'URL.
			if (($new_urlname = self::_editURLName($id, $infos, 'cat_url')) !== FALSE)
			{
				$columns[] = 'cat_url = :cat_url';
				$params['cat_url'] = $new_urlname;
				$change = TRUE;
			}

			// Description.
			if (($new_description = self::_editDescription($id, $infos, 'cat_desc')) !== FALSE)
			{
				$columns[] = 'cat_desc = :cat_desc';
				$params['cat_desc'] = $new_description;
				$change = TRUE;
			}

			// Nom de répertoire.
			if (($new_dirname = self::_editDirname($id, $infos)) !== FALSE)
			{
				$infos['image_path'] = preg_replace('`^' . $infos['cat_path'] . '`',
					$new_dirname, $infos['image_path']);
				$infos['cat_path'] = $new_dirname;
				$change = TRUE;
			}
		}

		// Réglages.
		if (substr($_GET['section'], 0, 6) != 'geoloc'
		 && (auth::$perms['admin']['perms']['albums_modif']
		  || auth::$perms['admin']['perms']['all']))
		{
			// Mot de passe.
			// L'utilisateur doit avoir accès à tous les albums protégés.
			if (auth::$perms['admin']['perms']['access_mode'] == 1
			&& ($new_password = self::_editPassword($id, $infos)) !== FALSE)
			{
				$infos['cat_password'] = $new_password;
				$change = TRUE;
			}

			// Autorisations.
			foreach (array('commentable', 'creatable', 'uploadable', 'votable') as $col)
			{
				// L'autorisation 'creatable' est inutile pour les albums.
				if ($col == 'creatable' && $infos['cat_filemtime'] !== NULL)
				{
					continue;
				}

				if (!isset($infos['cat_' . $col . '_parent'])
				|| $infos['cat_' . $col . '_parent'])
				{
					if (!empty($_POST[$id][$col])
					&& $infos['cat_' . $col] == 0)
					{
						$columns[] = 'cat_' . $col . ' = :cat_' . $col;
						$params['cat_' . $col] = '1';
						$change = TRUE;
					}
					elseif (empty($_POST[$id][$col])
					&& $infos['cat_' . $col] == 1)
					{
						$columns[] = 'cat_' . $col . ' = :cat_' . $col;
						$params['cat_' . $col] = '0';
						$change = TRUE;
					}
				}
			}

			// Style.
			if (isset($_POST[$id]['style'])
			&& (in_array($_POST[$id]['style'], self::$styles) || $_POST[$id]['style'] == '*')
			&& (($_POST[$id]['style'] != '*' && $infos['cat_style'] != $_POST[$id]['style'])
			 || ($_POST[$id]['style'] == '*' && $infos['cat_style'] !== NULL)))
			{
				$columns[] = 'cat_style = :cat_style';
				$params['cat_style'] =
					($_POST[$id]['style'] == '*')
					? NULL
					: $_POST[$id]['style'];
				$change = TRUE;
			}

			// Tri des objets.
			$orderby = ($infos['cat_filemtime'] === NULL)
				? array(
					'cat_position', 'cat_path', 'cat_name',
					'cat_crtdt', 'cat_lastadddt', 'cat_a_size'
				)
				: array(
					'image_position', 'image_name', 'image_path', 'image_size',
					'image_filesize', 'image_hits', 'image_comments', 'image_votes',
					'image_rate', 'image_adddt', 'image_crtdt'
				);
			$new_orderby = ($infos['cat_orderby'] === NULL)
				? str_repeat('cat_position ASC,', 3)
				: $infos['cat_orderby'];
			if (isset($_POST[$id]['orderby_1'])
			&& $_POST[$id]['orderby_1'] == 'default')
			{
				$new_orderby = NULL;
			}
			else
			{
				for ($i = 1; $i < 4; $i++)
				{
					if (isset($_POST[$id]['orderby_' . $i])
					&& in_array($_POST[$id]['orderby_' . $i], $orderby)
					&& isset($_POST[$id]['ascdesc_' . $i])
					&& ($_POST[$id]['ascdesc_' . $i] == 'ASC'
					 || $_POST[$id]['ascdesc_' . $i] == 'DESC'))
					{
						$p1 = '$1'; $p2 = '$2'; $p3 = '$3';
						${'p' . $i} = $_POST[$id]['orderby_' . $i]
							. ' ' . $_POST[$id]['ascdesc_' . $i];
						$new_orderby = preg_replace(
							'`^([^,]+),([^,]+),([^,]+),$`i',
							sprintf('%s,%s,%s,', $p1, $p2, $p3),
							$new_orderby
						);
					}
					else
					{
						$new_orderby = $infos['cat_orderby'] = NULL;
						break;
					}
				}
			}
			if ($new_orderby != $infos['cat_orderby'])
			{
				$columns[] = 'cat_orderby = :cat_orderby';
				$params['cat_orderby'] = $new_orderby;
				$change = TRUE;
			}
		}

		if (!$change)
		{
			return FALSE;
		}

		// On effectue la mise à jour de la catégorie.
		if (!empty($params))
		{
			$sql = 'UPDATE ' . CONF_DB_PREF . 'categories
					   SET ' . implode(', ', $columns) . '
					 WHERE cat_id = ' . (int) $id;
			if (utils::$db->prepare($sql) === FALSE
			|| utils::$db->executeExec($params) === FALSE
			|| utils::$db->nbResult === 0)
			{
				self::report('error:' . utils::$db->msgError, $id);
				return FALSE;
			}
		}

		// Mise à jour du tableau des catégories.
		foreach (array('commentable', 'creatable', 'desc', 'lat', 'long', 'name',
		'place', 'url', 'uploadable', 'votable', 'style', 'orderby') as $p)
		{
			if (array_key_exists('cat_' . $p, $params))
			{
				$infos['cat_' . $p] = $params['cat_' . $p];
			}
		}

		self::report('success:' . __('Modifications enregistrées.'));
		self::report('success_p:' . __('Les autres modifications ont été enregistrées.'));

		return TRUE;
	}

	/**
	 * Création d'une nouvelle catégorie.
	 *
	 * @return void
	 */
	public static function newCategory()
	{
		if (!isset($_POST['new_cat']) || !isset($_POST['type']) || !isset($_POST['name']))
		{
			return;
		}

		// Permissions d'accès.
		if (!auth::$perms['admin']['perms']['albums_add']
		&& !auth::$perms['admin']['perms']['all'])
		{
			die;
		}

		$r = alb::create(
			self::$infos['cat_id'],
			self::$infos['cat_path'],
			self::$infos['cat_password'],
			$_POST['type'],
			array('name' => $_POST['name'], 'desc' => ''),
			auth::$infos['user_id']
		);

		if ($r === FALSE)
		{
			self::report('error:' . __('Impossible de créer le répertoire.'));
			return;
		}
		
		if ($r !== TRUE)
		{
			self::report($r);
			return;
		}

		self::$nbItems++;
		self::report('success:' . __('La catégorie a été créée.'));
	}

	/**
	 * Détermine les bonnes informations de réglages.
	 *
	 * @return void
	 */
	public static function parentsSettings()
	{
		if (!is_array(albums::$items))
		{
			return;
		}

		$parents = array();
		foreach (albums::$items as &$infos)
		{
			if (dirname($infos['cat_path']) == '.')
			{
				continue;
			}

			// Récupération des catégories parentes de la catégorie courante.
			if (!isset($parents[dirname($infos['cat_path'])]))
			{
				albums::parents('cat', $infos['cat_path']);
				$parents[dirname($infos['cat_path'])] = albums::$parents;
			}

			// Détermine si les commentaires et les votes
			// sont désactivés pour au moins un parent.
			foreach ($parents[dirname($infos['cat_path'])] as &$parent_infos)
			{
				self::_parentsSettings($infos, $parent_infos);
			}
		}
	}

	/**
	 * Calcule les bonnes statistiques de la catégorie et des sous-catégories
	 * qu'elle contient selon les permissions de l'utilisateur.
	 *
	 * @return void
	 */
	public static function stats()
	{
		// Si l'utilisateur a le droit d'accès à tous les albums protégés,
		// inutile d'aller plus loin.
		if (auth::$perms['admin']['perms']['access_mode'] == 1)
		{
			return;
		}

		// On récupère les infos utiles de toutes les catégories protégées
		// non déverrouillées par les permissions de l'utilisateur.
		$sql = '';
		$params = array();
		if (auth::$perms['admin']['perms']['access_mode'] == 2
		&& !empty(auth::$perms['admin']['albums_access']))
		{
			$params = array_keys(auth::$perms['admin']['albums_access']);
			$sql = ' AND cat_id NOT IN (?' . str_repeat(', ?', count($params) - 1) . ')';
		}
		$sql = 'SELECT cat_path
				  FROM ' . CONF_DB_PREF . 'categories
				 WHERE cat_password LIKE CONCAT(cat_id, ":%")
				   AND cat_path LIKE CONCAT(?, "%")'
				     . $sql . '
			 ORDER BY LENGTH(cat_path) ASC';
		$c_path = (self::$infos['cat_path'] == '.') ? '' : self::$infos['cat_path'] . '/';
		array_unshift($params, $c_path);
		$fetch_style = array('fetch' => PDO::FETCH_ASSOC, 'column' => 'cat_path');
		if (utils::$db->prepare($sql) === FALSE
		|| utils::$db->executeQuery($params, $fetch_style) === FALSE
		|| utils::$db->nbResult === 0)
		{
			return;
		}
		$protected = utils::$db->queryResult;

		// On réduit les statistiques de la catégorie courante..
		$temp_paths = array();
		foreach ($protected as $path => &$s)
		{
			// Il ne faut pas retrancher les stats pour une
			// sous-catégorie d'une catégorie qui l'a déjà été !
			foreach ($temp_paths as $p)
			{
				if (strpos($path, $p . '/', 0) !== FALSE)
				{
					continue 2;
				}
			}
			$temp_paths[] = $path;

			// On réduit le nombre de sous-albums et de sous-catégories.
			self::$nbItems -= (preg_match('`^' . $c_path . '[^/]+$`', $path))
				? 1
				: 0;
		}

		// On recalcule les bons paramètres de pages.
		self::pages();
	}

	/**
	 * Options du filigrane.
	 *
	 * @return void
	 */
	public static function watermark()
	{
		admin::$watermarkParams = utils::$config['watermark_params_default']
			= unserialize(utils::$config['watermark_params_default']);
		if (utils::isSerializedArray(category::$infos['cat_watermark']))
		{
			admin::$watermarkParams = category::$infos['cat_watermark']
				= unserialize(category::$infos['cat_watermark']);
		}

		$image_dir = 'images/watermarks/categories/' . (int) self::$infos['cat_id'] . '/';

		// Modification des options du filigrane.
		$r = watermark::changeOptions(admin::$watermarkParams, $image_dir);
		if (admin::$watermarkParams != category::$infos['cat_watermark']
		&& (category::$infos['cat_watermark'] === NULL
		&& admin::$watermarkParams == utils::$config['watermark_params_default']) === FALSE)
		{
			// On effectue la mise à jour des paramètres.
			$sql = 'UPDATE ' . CONF_DB_PREF . 'categories
					   SET cat_watermark = :watermark_params
					 WHERE cat_id = :cat_id
					 LIMIT 1';
			$params = array(
				'cat_id' => (int) self::$infos['cat_id'],
				'watermark_params' => serialize(admin::$watermarkParams)
			);
			if (utils::$db->prepare($sql) === FALSE
			 || utils::$db->executeExec($params) === FALSE)
			{
				self::report('error:' . utils::$db->msgError);
				return;
			}

			self::report('success:' . __('Modifications enregistrées.'));
			self::report('success_p:' . __('Les autres modifications ont été enregistrées.'));
		}

		// Rapport du changement de l'image de filigrane.
		if (is_string($r))
		{
			self::report($r);
		}

		// Chemin de l'image de filigrane.
		$image_file = $image_dir . admin::$watermarkParams['image_file'];
		admin::$watermarkParams['image_file'] = (admin::$watermarkParams['image_file']
		&& file_exists(GALLERY_ROOT . '/' . $image_file)
		&& is_file(GALLERY_ROOT . '/' . $image_file))
			? $image_file
			: NULL;
	}



	/**
	 * Supprime des catégories.
	 *
	 * @param array $selected_ids
	 *	Identifiants des catégories sélectionnées.
	 * @param integer $cat_id
	 *	Identifiant de la catégorie des catégories sélectionnées.
	 * @return boolean
	 */
	private static function _delete($selected_ids, $cat_id)
	{
		try
		{
			// On récupère les identifiants des catégories parentes
			// et les informations utiles des catégories sélectionnées.
			if (($parents_ids = alb::getParentsIds($cat_id)) === FALSE
			|| ($cat_infos = self::_getCategoriesInfos($selected_ids)) === FALSE)
			{
				// Si aucune catégorie sélectionnée n'existe, on arrête là.
				if (utils::$db->nbResult === 0)
				{
					return FALSE;
				}

				throw new Exception(utils::$db->msgError);
			}

			// On détermine les informations des catégories parentes à updater.
			$update_stats = array(
				'size' => 0,
				'albums' => 0,
				'images' => 0,
				'hits' => 0,
				'comments' => 0,
				'rate' => 0,
				'votes' => 0);
			$update_stats = array(
				'a' => $update_stats,
				'd' => $update_stats
			);
			foreach ($cat_infos as $i)
			{
				// Nombre d'albums.
				if ($i['cat_filemtime'] === NULL)
				{
					$update_stats['a']['albums'] += $i['cat_a_albums'];
					$update_stats['d']['albums'] += $i['cat_d_albums'];
				}
				else
				{
					$status = ($i['cat_status']) ? 'a' : 'd';
					$update_stats[$status]['albums'] += 1;
				}

				// On recalcule la note moyenne.
				if ($i['cat_a_votes'] > 0)
				{
					$update_stats['a']['rate'] =
						(($update_stats['a']['rate'] * $update_stats['a']['votes'])
						+ ($i['cat_a_rate'] * $i['cat_a_votes']))
						/ ($update_stats['a']['votes'] + $i['cat_a_votes']);
					$update_stats['a']['votes'] += $i['cat_a_votes'];
				}

				if ($i['cat_d_votes'] > 0)
				{
					$update_stats['d']['rate'] =
						(($update_stats['d']['rate'] * $update_stats['d']['votes'])
						+ ($i['cat_d_rate'] * $i['cat_d_votes']))
						/ ($update_stats['d']['votes'] + $i['cat_d_votes']);
					$update_stats['d']['votes'] += $i['cat_d_votes'];
				}

				// Autres stats.
				$update_stats['a']['size'] += $i['cat_a_size'];
				$update_stats['a']['images'] += $i['cat_a_images'];
				$update_stats['a']['hits'] += $i['cat_a_hits'];
				$update_stats['a']['comments'] += $i['cat_a_comments'];

				$update_stats['d']['size'] += $i['cat_d_size'];
				$update_stats['d']['images'] += $i['cat_d_images'];
				$update_stats['d']['hits'] += $i['cat_d_hits'];
				$update_stats['d']['comments'] += $i['cat_d_comments'];
			}

			$sql = array();

			// Suppression des catégories sélectionnées, de leurs sous-catégories,
			// albums, images, tags, votes et commentaires qui leurs sont liés.
			$sql[] = 'DELETE
						FROM ' . CONF_DB_PREF . 'categories
					   WHERE cat_id IN (' . implode(', ', $selected_ids) . ')';

			// Mise à jour des statistiques des catégories parentes.
			$sql[] = alb::updateParentsStats($update_stats, '-', '-', $parents_ids);

			// Début de la transaction.
			if (!utils::$db->transaction())
			{
				throw new Exception(utils::$db->msgError);
			}

			// Exécution des requêtes.
			if (utils::$db->exec($sql, FALSE) === FALSE)
			{
				throw new Exception(utils::$db->msgError);
			}

			// On met à jour certaines informations des catégories parentes.
			reset($cat_infos);
			$i = current($cat_infos);
			$path = dirname($i['cat_path']);
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

			// On supprime les catégories sur le disque.
			foreach ($cat_infos as &$i)
			{
				$dir = GALLERY_ROOT . '/' . CONF_ALBUMS_DIR . '/' . $i['cat_path'];
				if (file_exists($dir) && !files::rmdir($dir))
				{
					self::report(
						'error:' . __('Impossible de supprimer le répertoire.'),
						$i['cat_id'],
						$i['cat_filemtime']
					);
				}
			}

			return TRUE;
		}
		catch (Exception $e)
		{
			self::report('error:' . $e->getMessage());
			return FALSE;
		}
	}

	/**
	 * Édition du mot de passe.
	 *
	 * @param integer $id
	 *	Identifiant de la catégorie.
	 * @param array $infos
	 *	Informations de la catégorie.
	 * @return string|boolean
	 */
	private static function _editPassword($id, &$infos)
	{
		if (!isset($_POST[$id]['password']) || $_POST[$id]['password'] == '**********'
		|| ($_POST[$id]['password'] == '' && $infos['cat_password'] === NULL))
		{
			return FALSE;
		}

		try
		{
			$new_password = $_POST[$id]['password'];

			// Début de la transaction.
			if (!utils::$db->transaction())
			{
				throw new Exception('error:' . utils::$db->msgError);
			}

			$id_password = $id;
			if ($infos['parent_id'] != 1)
			{
				// Récupération du mot de passe de la catégorie parente.
				$sql = 'SELECT cat_password
						  FROM ' . CONF_DB_PREF . 'categories
						 WHERE cat_id = ' . (int) $infos['parent_id'];
				if (utils::$db->query($sql, 'value') === FALSE
				|| utils::$db->nbResult === 0)
				{
					throw new Exception('error:' . utils::$db->msgError);
				}
				$parent_password = utils::$db->queryResult;

				// Si la catégorie parente possède un mot de passe,
				// la catégorie courante doit aussi en possèder un.			
				if ($parent_password !== NULL && $new_password == '')
				{
					throw new Exception('warning:' . __('Un mot de passe est requis'
						. ' car la catégorie parente est protégée.'));
				}

				// Si le mot de passe de la catégorie parente est
				// le même que le nouveau de la catégorie courante,
				// on prend l'identifiant de la catégorie parente, de
				// manière à ne pas avoir à entrer deux fois le même
				// mot de passe pour entrer dans les deux catégories.
				if ($parent_password !== NULL)
				{
					$parent_password = explode(':', $parent_password, 2);
					if ($parent_password[1] ==
					utils::hashPassword($new_password, $parent_password[0]))
					{
						$id_password = $parent_password[0];
					}
				}
			}

			$sql = array();

			// Mise à jour du mot de passe des catégories et images
			// que contient la catégorie courante.
			$cat_password = $id_password . ':'
				. utils::hashPassword($new_password, $id_password);
			$params = array(
				'cat_password' => ($new_password == '')
					? NULL
					: $cat_password,
				'cat_path' => $infos['cat_path']
			);
			$sql = 'UPDATE ' . CONF_DB_PREF . 'categories
					   SET cat_password = :cat_password
					 WHERE cat_path LIKE CONCAT(:cat_path, "/%")
						OR cat_id = ' . (int) $infos['cat_id'];
			if (utils::$db->prepare($sql) === FALSE
			|| utils::$db->executeExec($params) === FALSE)
			{
				throw new Exception('error:' . utils::$db->msgError);
			}

			// On supprime les enregistrement contenant
			// l'ancien mot de passe dans la table 'passwords'.
			if (albums::$items[$id]['cat_password'] !== NULL)
			{
				$old_password = explode(':', albums::$items[$id]['cat_password']);
				$old_password = $old_password[1];
				$sql = 'DELETE
					      FROM ' . CONF_DB_PREF . 'passwords
						 WHERE password = :password';
				$params = array('password' => $old_password);
				if (utils::$db->prepare($sql) === FALSE
				|| utils::$db->executeExec($params) === FALSE)
				{
					throw new Exception();
				}
			}

			// Exécution de la transaction.
			if (!utils::$db->commit())
			{
				throw new Exception('error:' . utils::$db->msgError);
			}

			return ($new_password == '') ? NULL : $cat_password;
		}
		catch (Exception $e)
		{
			self::report($e, $id);

			return FALSE;
		}
	}

	/**
	 * Déplace des catégories.
	 *
	 * @param array $selected_ids
	 *	Identifiants des catégories sélectionnées.
	 * @param integer $cat_id
	 *	Identifiant de la catégorie des catégories sélectionnées.
	 * @return boolean
	 */
	private static function _move($selected_ids, $cat_id)
	{
		if (empty($_POST['destination_cat']) || $_POST['destination_cat'] == $cat_id)
		{
			return FALSE;
		}

		try
		{
			// On récupère le chemin du répertoire destination
			// et on vérifie que la destination est bien une catégorie
			// et aussi que ce n'est pas la même catégorie.
			$sql = 'SELECT cat_path
					  FROM ' . CONF_DB_PREF . 'categories
					 WHERE cat_id = ' . (int) $_POST['destination_cat'] . '
					   AND cat_id != ' . (int) $cat_id . '
					   AND cat_filemtime IS NULL'
					     . sql::$groupAlbumsAccess;
			if (utils::$db->query($sql, 'value') === FALSE
			|| utils::$db->nbResult === 0)
			{
				throw new Exception('error:' . utils::$db->msgError);
			}
			$dest_path = utils::$db->queryResult;
			$dest_path = ($dest_path == '.') ? '' : $dest_path;

			// On récupère les identifiants des catégories parentes sources et destination
			// et les informations utiles des catégories sélectionnées.
			if (($source_parents_ids = alb::getParentsIds($cat_id)) === FALSE
			|| ($dest_parents_ids = alb::getParentsIds($_POST['destination_cat'])) === FALSE
			|| ($categories = self::_getCategoriesInfos($selected_ids)) === FALSE)
			{
				throw new Exception('error:' . utils::$db->msgError);
			}

			$current_cat = current($categories);
			$source_path = dirname($current_cat['cat_path']);

			// On vérifie que la destination n'est pas un descendant de l'une des sources.
			foreach ($categories as &$i)
			{
				if (preg_match('`^' . $i['cat_path'] . '/.*`', $dest_path . '/'))
				{
					return FALSE;
				}
			}

			// Déplacement des catégories une à une.
			foreach ($selected_ids as &$id)
			{
				self::_moveCategory($categories[$id], $dest_path,
					$source_parents_ids, $dest_parents_ids);
			}

			// On met à jour certaines informations des catégories parentes.
			alb::updateLastadddt($source_path);
			alb::updateLastadddt($dest_path);

			alb::updateCatThumbs($source_path);
			alb::updateCatThumbs($dest_path);

			alb::updateSubsCats($source_parents_ids);
			alb::updateSubsCats($dest_parents_ids);

			return TRUE;
		}
		catch (Exception $e)
		{
			self::report($e);

			return FALSE;
		}
	}

	/**
	 * Déplace une catégorie.
	 *
	 * @param array $i
	 *	Informations de la catégorie.
	 * @param string $dest_path
	 *	Chemin du répertoire destination.
	 * @param array $source_parents_ids
	 *	Identifiants des catégories parentes source.
	 * @param array $dest_parents_ids
	 *	Identifiants des catégories parentes destination.
	 * @return void
	 */
	private static function _moveCategory($i, $dest_path,
	$source_parents_ids, $dest_parents_ids)
	{
		try
		{
			// Actuel et nouveau chemin de la catégorie.
			$current_path = $i['cat_path'];
			$dest_path .= ($dest_path == '') ? '' : '/';
			$new_path = $dest_path . basename($i['cat_path']);

			// Si la catégorie existe déjà dans la catégorie destination,
			// inutile d'aller plus loin.
			if ($current_path == $new_path)
			{
				return;
			}

			// Chemin du répertoire des albums.
			$albums_path = GALLERY_ROOT . '/' . CONF_ALBUMS_DIR;

			// On vérifie s'il n'existe pas déjà dans la catégorie
			// destination un répertoire avec le même nom.
			if (file_exists($albums_path . '/' . $new_path))
			{
				$message = __('Un répertoire du même nom existe '
					. 'déjà dans la catégorie destination.');
				throw new Exception('warning:' . $message);
			}

			// Début de la transaction.
			if (!utils::$db->transaction())
			{
				throw new Exception('error:' . utils::$db->msgError);
			}

			$params = array(
				'new_path' => $new_path,
				'old_path' => $current_path
			);

			// On récupère le mot de passe de la catégorie destination.
			$sql = 'SELECT cat_password
					  FROM ' . CONF_DB_PREF . 'categories
					 WHERE cat_id = ' . (int) $_POST['destination_cat'];
			if (utils::$db->query($sql, 'value') === FALSE
			|| utils::$db->nbResult === 0)
			{
				throw new Exception('error:' . utils::$db->msgError);
			}
			$dest_password = utils::$db->queryResult;

			// Si la catégorie destination est protégée par un mot de passe,
			// alors on attribut ce mot de passe à la catégorie à déplacer.
			if ($dest_password !== NULL)
			{
				$params['password'] = $dest_password;
			}

			// Sinon, si la catégorie à déplacer est protégée par
			// le mot de passe d'une catégorie parente,
			// alors on retire le mot de passe de la catégorie.
			else if ($i['cat_password'] !== NULL)
			{
				$password = explode(':', $i['cat_password'], 2);
				if ($password[0] != $i['cat_id'])
				{
					$params['password'] = NULL;
				}
			}

			// Mise à jour du nom de répertoire de la catégorie.
			if ((self::_updateCategoriesPaths($i, $params)) !== TRUE)
			{
				throw new Exception('error:' . utils::$db->msgError);
			}

			// Informations de mise à jour.
			$cat_update = array();
			$cat_update['a']['images'] = $i['cat_a_images'];
			$cat_update['a']['size'] = $i['cat_a_size'];
			$cat_update['a']['hits'] = $i['cat_a_hits'];
			$cat_update['a']['comments'] = $i['cat_a_comments'];
			$cat_update['a']['votes'] = $i['cat_a_votes'];
			$cat_update['a']['rate'] = $i['cat_a_rate'];
			$cat_update['d']['images'] = $i['cat_d_images'];
			$cat_update['d']['size'] = $i['cat_d_size'];
			$cat_update['d']['hits'] = $i['cat_d_hits'];
			$cat_update['d']['comments'] = $i['cat_d_comments'];
			$cat_update['d']['votes'] = $i['cat_d_votes'];
			$cat_update['d']['rate'] = $i['cat_d_rate'];

			// Nombre d'albums.
			if ($i['cat_filemtime'] !== NULL)
			{
				if ($i['cat_status'])
				{
					$cat_update['a']['albums'] = 1;
					$cat_update['d']['albums'] = 0;
				}
				else
				{
					$cat_update['a']['albums'] = 0;
					$cat_update['d']['albums'] = 1;
				}
			}
			else
			{
				$cat_update['a']['albums'] = $i['cat_a_albums'];
				$cat_update['d']['albums'] = $i['cat_d_albums'];
			}

			// On met à jour les informations des catégories parentes
			// de la catégorie source et de la catégorie destination.
			$sql = array(
				alb::updateParentsStats($cat_update, '-', '-', $source_parents_ids),
				alb::updateParentsStats($cat_update, '+', '+', $dest_parents_ids)
			);

			// On change l'id de la catégorie parente.
			$sql[] = 'UPDATE ' . CONF_DB_PREF . 'categories
					     SET parent_id = ' . (int) $_POST['destination_cat'] . '
					   WHERE cat_id = ' . (int) $i['cat_id'];

			// Exécution des requêtes.
			if (utils::$db->exec($sql, FALSE) === FALSE)
			{
				throw new Exception('error:' . utils::$db->msgError);
			}

			// On renomme le répertoire.
			if (!files::renameDir($albums_path . '/' . $current_path,
			$albums_path . '/' . $new_path))
			{
				throw new Exception('error:' . __('Impossible de renommer le répertoire.'));
			}

			// Exécution de la transaction.
			if (!utils::$db->commit())
			{
				// On tente de renommer le répertoire avec l'ancien nom.
				files::renameFile($albums_path . '/' . $new_path,
					$albums_path . '/' . $current_path);

				throw new Exception('error:' . utils::$db->msgError);
			}
		}
		catch (Exception $e)
		{
			utils::$db->rollBack();
			self::report($e, $i['cat_id']);
		}
	}

	/**
	 * Change le propriétaire des catégories.
	 *
	 * @param array $selected_ids
	 *	Identifiants des catégories.
	 * @return boolean
	 */
	private static function _owner($selected_ids)
	{
		if (empty($_POST['owner']) || $_POST['owner'] == 2)
		{
			return;
		}

		try
		{
			// On vérifie que l'utilisateur existe.
			$sql = 'SELECT 1
					  FROM ' . CONF_DB_PREF . 'users
					 WHERE user_id = ' . (int) $_POST['owner'];
			if (utils::$db->query($sql) === FALSE
			|| utils::$db->nbResult !== 1)
			{
				throw new Exception(utils::$db->msgError);
			}

			// On change le propriétaire des catégories.
			$sql = 'UPDATE ' . CONF_DB_PREF . 'categories
					   SET user_id = ' . (int) $_POST['owner'] . '
					 WHERE cat_id IN (' . implode(', ', $selected_ids) . ')';
			if (utils::$db->exec($sql) === FALSE)
			{
				throw new Exception(utils::$db->msgError);
			}

			// Aucun changement.
			if (utils::$db->nbResult === 0)
			{
				return;
			}

			return TRUE;
		}
		catch (Exception $e)
		{
			self::report('error:' . $e->getMessage());
			return FALSE;
		}
	}

	/**
	 * Édition du nom de répertoire.
	 *
	 * @param integer $id
	 *	Identifiant de la catégorie.
	 * @param array $infos
	 *	Informations de la catégorie.
	 * @return string|boolean
	 */
	private static function _editDirname($id, &$infos)
	{
		if (!isset($_POST[$id]['dirname'])
		|| ($new_dirname = trim($_POST[$id]['dirname'])) == basename($infos['cat_path']))
		{
			return FALSE;
		}

		try
		{
			// Vérification de la longueur.
			if (strlen($new_dirname) > 128 || strlen($new_dirname) < 1)
			{
				$message = __('Le nom de répertoire doit contenir entre 1 et 128 caractères.');
				throw new Exception('warning:' . $message);
			}

			// Vérification du format.
			if (preg_match('`([^-_a-z0-9])`i', $new_dirname))
			{
				$message = __('Format du nom de répertoire invalide.');
				throw new Exception('warning:' . $message);
			}

			// Ancien et nouveau chemin de la catégorie.
			$old_path = $infos['cat_path'];
			$p = dirname($old_path) . '/';
			$p = ($p == './') ? '' : $p;
			$new_path = $p . $new_dirname;

			// Chemin du répertoire des albums.
			$albums_path = GALLERY_ROOT . '/' . CONF_ALBUMS_DIR;

			// On vérifie s'il n'existe pas déjà un répertoire avec le même nom.
			// On n'autorise pas à juste changer la casse sous Windows, car un bug
			// de PHP fait que la fonction rename() ne renomme pas le répertoire
			// ni ne provoque d'erreur !
			if (file_exists($albums_path . '/' . $new_path))
			{
				$message = __('Un répertoire du même nom existe déjà dans cette catégorie.');
				throw new Exception('warning:' . $message);
			}

			// Début de la transaction.
			if (!utils::$db->transaction())
			{
				throw new Exception('error:' . utils::$db->msgError);
			}

			// Mise à jour du nom de répertoire de la catégorie.
			$params = array('old_path' => $old_path, 'new_path' => $new_path);
			if ((self::_updateCategoriesPaths($infos, $params)) !== TRUE)
			{
				throw new Exception('error:' . utils::$db->msgError);
			}

			// On renomme le répertoire.
			if (!files::renameDir($albums_path . '/' . $old_path,
			$albums_path . '/' . $new_path))
			{
				throw new Exception('error:' . __('Impossible de renommer le répertoire.'));
			}

			// Exécution de la transaction.
			if (!utils::$db->commit())
			{
				// On tente de renommer le répertoire avec l'ancien nom.
				files::renameDir($albums_path . '/' . $new_path,
				$albums_path . '/' . $old_path);

				throw new Exception('error:' . utils::$db->msgError);
			}

			return $new_path;
		}
		catch (Exception $e)
		{
			self::report($e, $id);

			return FALSE;
		}
	}

	/**
	 * Récupère les informations utiles des catégories sélectionnées.
	 *
	 * @param array $selected_ids
	 *	Identifiants des catégories sélectionnées.
	 * @return boolean|array
	 */
	private static function _getCategoriesInfos($selected_ids)
	{
		$sql = 'SELECT cat_id,
					   parent_id,
					   cat_path,
					   cat_name,
					   cat_d_size,
					   cat_d_albums,
					   cat_d_images,
					   cat_d_hits,
					   cat_d_comments,
					   cat_d_votes,
					   cat_d_rate,
					   cat_a_size,
					   cat_a_albums,
					   cat_a_images,
					   cat_a_hits,
					   cat_a_comments,
					   cat_a_votes,
					   cat_a_rate,
					   cat_filemtime,
					   cat_password,
					   cat_status
				  FROM ' . CONF_DB_PREF . 'categories
				 WHERE cat_id IN (' . implode(', ', $selected_ids) . ')'
				     . sql::$groupAlbumsAccess;
		$fetch_style = array('fetch' => PDO::FETCH_ASSOC, 'column' => 'cat_id');
		if (utils::$db->query($sql, $fetch_style) === FALSE
		|| utils::$db->nbResult === 0)
		{
			return FALSE;
		}

		return utils::$db->queryResult;
	}

	/**
	 * Active ou désactive des catégories.
	 *
	 * @param array $selected_ids
	 *	Identifiants des catégories sélectionnées.
	 * @param integer $cat_id
	 *	Identifiant de la catégorie parente des catégories sélectionnées.
	 * @param integer $status
	 *	Nouveau statut (0 ou 1).
	 * @return boolean
	 */
	private static function _status($selected_ids, $cat_id, $status)
	{
		if ($status !== 1 && $status !== 0)
		{
			return FALSE;
		}

		try
		{
			// Début de la transaction.
			if (!utils::$db->transaction())
			{
				throw new Exception(utils::$db->msgError);
			}

			// On récupère les identifiants des catégories parentes
			// et les informations utiles des catégories sélectionnées.
			if (($parents_ids = alb::getParentsIds($cat_id)) === FALSE
			|| ($cat_infos = self::_getCategoriesInfos($selected_ids)) === FALSE)
			{
				throw new Exception(utils::$db->msgError);
			}

			$sql_img_paths = '';
			$sql_cat_paths = '';
			$params_path = array();
			$update_stats = array(
				'size' => 0,
				'albums' => 0,
				'images' => 0,
				'hits' => 0,
				'comments' => 0,
				'rate' => 0,
				'votes' => 0);
			$s = ($status) ? 'd' : 'a';
			foreach ($cat_infos as $id => $i)
			{
				// Si la catégorie a le même statut,
				// on ne la prend pas en compte.
				if ($i['cat_status'] == $status)
				{
					continue;
				}

				// Chemins des enfants à updater.
				$sql_img_paths .= 'image_path LIKE CONCAT(?, "/%") OR ';
				$sql_cat_paths .= 'cat_id = ' . (int) $id
					. ' OR cat_path LIKE CONCAT(?, "/%") OR ';

				// Paramètres des requêtes préparées.
				$params_path[] = $i['cat_path'];

				// Nombre d'albums.
				if ($i['cat_filemtime'] === NULL)
				{
					$update_stats['albums'] += $i['cat_' . $s . '_albums'];
				}
				else
				{
					$update_stats['albums'] += 1;
				}

				// On recalcule la note moyenne.
				if ($i['cat_' . $s . '_votes'] > 0)
				{
					$update_stats['rate'] =
						(($update_stats['rate'] * $update_stats['votes'])
						+ ($i['cat_' . $s . '_rate'] * $i['cat_' . $s . '_votes']))
						/ ($update_stats['votes'] + $i['cat_' . $s . '_votes']);
					$update_stats['votes'] += $i['cat_' . $s . '_votes'];
				}

				// Autres stats.
				$update_stats['images'] += $i['cat_' . $s . '_images'];
				$update_stats['hits'] += $i['cat_' . $s . '_hits'];
				$update_stats['comments'] += $i['cat_' . $s . '_comments'];
				$update_stats['size'] += $i['cat_' . $s . '_size'];
			}

			// Si aucune catégorie n'est à mettre à jour, on arrête là.
			if ($update_stats['images'] == 0)
			{
				return;
			}

			// On met à jour toutes les images des catégories sélectionnées.
			$sql = 'UPDATE ' . CONF_DB_PREF . 'images
					   SET image_status = "' . $status . '"
					 WHERE ' . substr($sql_img_paths, 0, strlen($sql_img_paths) - 4);
			if (utils::$db->prepare($sql) === FALSE
			|| utils::$db->executeExec($params_path) === FALSE)
			{
				throw new Exception(utils::$db->msgError);
			}

			// On met à jour toutes les sous-catégories des catégories sélectionnées.
			$s1 = ($status) ? 'd' : 'a';
			$s2 = ($status) ? 'a' : 'd';
			$sql_stats =
			   'cat_' . $s2 . '_size = cat_' . $s2 . '_size
					+ cat_' . $s1 . '_size,
				cat_' . $s2 . '_albums = cat_' . $s2 . '_albums
					+ cat_' . $s1 . '_albums,
				cat_' . $s2 . '_images = cat_' . $s2 . '_images
					+ cat_' . $s1 . '_images,
				cat_' . $s2 . '_hits = cat_' . $s2 . '_hits
					+ cat_' . $s1 . '_hits,
				cat_' . $s2 . '_comments = cat_' . $s2 . '_comments
					+ cat_' . $s1 . '_comments,
				cat_' . $s2 . '_rate = CASE
					WHEN cat_' . $s2 . '_votes + cat_' . $s1 . '_votes > 0
					THEN ((cat_' . $s2 . '_rate * cat_' . $s2 . '_votes)
						+ (cat_' . $s1 . '_rate * cat_' . $s1 . '_votes))
						/ (cat_' . $s2 . '_votes + cat_' . $s1 . '_votes)
					ELSE 0
					 END,
				cat_' . $s2 . '_votes = cat_' . $s2 . '_votes
					+ cat_' . $s1 . '_votes,
				cat_' . $s1 . '_size = 0,
				cat_' . $s1 . '_albums = 0,
				cat_' . $s1 . '_images = 0,
				cat_' . $s1 . '_hits = 0,
				cat_' . $s1 . '_comments = 0,
				cat_' . $s1 . '_rate = 0,
				cat_' . $s1 . '_votes = 0';
			$sql = 'UPDATE ' . CONF_DB_PREF . 'categories
					   SET ' . $sql_stats . ',
						   cat_status = "' . $status . '"
					 WHERE cat_a_images + cat_d_images > 0
					   AND (' . substr($sql_cat_paths, 0, strlen($sql_cat_paths) - 4) . ')';
			if (utils::$db->prepare($sql) === FALSE
			|| utils::$db->executeExec($params_path) === FALSE)
			{
				throw new Exception(utils::$db->msgError);
			}

			// Mise à jour des statistiques des catégories parentes.
			$update_stats = array('a' => $update_stats, 'd' => $update_stats);
			switch ($status)
			{
				case 0 :
					$sql = alb::updateParentsStats(
						$update_stats, '-', '+', $parents_ids);
					break;

				case 1 :
					$sql = alb::updateParentsStats(
						$update_stats, '+', '-', $parents_ids);
					break;
			}

			// Exécution des requêtes.
			if (utils::$db->exec($sql, FALSE) === FALSE)
			{
				throw new Exception(utils::$db->msgError);
			}

			// Mise à jour des autres informations des catégories parentes.
			reset($cat_infos);
			$i = current($cat_infos);
			if (!alb::updateLastadddt($i['cat_path'], FALSE)
			|| !alb::updateCatThumbs(dirname($i['cat_path']), FALSE)
			|| !alb::updateSubsCats($parents_ids, FALSE))
			{
				throw new Exception(utils::$db->msgError);
			}

			// Exécution de la transaction.
			if (!utils::$db->commit())
			{
				throw new Exception(utils::$db->msgError);
			}

			return TRUE;
		}
		catch (Exception $e)
		{
			self::report('error:' . $e->getMessage());
			return FALSE;
		}
	}

	/**
	 * Remet à zéro le nombre de visites des catégories sélectionnées.
	 *
	 * @param array $selected_ids
	 *	Identifiants des catégories sélectionnées.
	 * @param integer $cat_id
	 *	Identifiant de la catégorie des catégories sélectionnées.
	 * @return boolean
	 */
	private static function _resetHits($selected_ids, $cat_id)
	{
		try
		{
			// Début de la transaction.
			if (!utils::$db->transaction())
			{
				throw new Exception(utils::$db->msgError);
			}

			// Récupération des id des catégories parentes et des
			// informations qui serviront à updater les catégories parentes.
			if (($parents_ids = alb::getParentsIds($cat_id)) === FALSE
			|| ($cat_infos = self::_getCategoriesInfos($selected_ids)) === FALSE)
			{
				throw new Exception(utils::$db->msgError);
			}

			// Nombre de visites à soustraire des catégories parentes
			// et clauses WHERE pour la mise à jour des catégories enfants.
			$sql_img_paths = '';
			$sql_cat_paths = '';
			$params_img = array();
			$params_cat = array();
			$cat_a_hits = 0;
			$cat_d_hits = 0;
			foreach ($cat_infos as &$i)
			{
				// Si la catégorie ne contient aucune image ou aucune visite,
				// inutile de faire des mises à jour.
				if (($i['cat_a_images'] + $i['cat_d_images']) == 0
				|| ($i['cat_a_hits'] + $i['cat_d_hits']) == 0)
				{
					continue;
				}

				// Nombre de visites.
				$cat_a_hits += $i['cat_a_hits'];
				$cat_d_hits += $i['cat_d_hits'];

				// Mise à jour des images.
				$params_img[] = $i['cat_path'];
				$sql_img_paths .= 'image_path LIKE CONCAT(?, "/%") OR ';

				// Mise à jour de la catégorie.
				$sql_cat_paths .= 'cat_id = ' . (int) $i['cat_id'] . ' OR ';

				// Mise à jour des catégories enfants, si ce n'est pas un album.
				if ($i['cat_filemtime'] === NULL)
				{
					$params_cat[] = $i['cat_path'];
					$sql_cat_paths .= 'cat_path LIKE CONCAT(?, "/%") OR ';
				}
			}

			// On met à zéro le nombre de visites des images.
			if ($sql_img_paths != '')
			{
				$sql = 'UPDATE ' . CONF_DB_PREF . 'images
						   SET image_hits = 0
						 WHERE ' . substr($sql_img_paths, 0, strlen($sql_img_paths) - 4);
				if (utils::$db->prepare($sql) === FALSE
				|| utils::$db->executeExec($params_img) === FALSE)
				{
					throw new Exception(utils::$db->msgError);
				}
			}

			// On met à zéro le nombre de visites des catégories.
			if ($sql_cat_paths != '')
			{
				$sql = 'UPDATE ' . CONF_DB_PREF . 'categories
						   SET cat_a_hits = 0,
						       cat_d_hits = 0
						 WHERE ' . substr($sql_cat_paths, 0, strlen($sql_cat_paths) - 4);
				if (utils::$db->prepare($sql) === FALSE
				|| utils::$db->executeExec($params_cat) === FALSE)
				{
					throw new Exception(utils::$db->msgError);
				}
			}

			// On met à jour les stats des catégories parentes.
			if ($sql_img_paths != '')
			{
				$sql = 'UPDATE ' . CONF_DB_PREF . 'categories
						   SET cat_a_hits = cat_a_hits - ' . $cat_a_hits . ',
							   cat_d_hits = cat_d_hits - ' . $cat_d_hits . '
						 WHERE cat_id IN (' . implode(', ', $parents_ids) . ')';
				if (utils::$db->exec($sql, FALSE) === FALSE)
				{
					throw new Exception(utils::$db->msgError);
				}
			}

			// Exécution de la transaction.
			if (!utils::$db->commit())
			{
				throw new Exception(utils::$db->msgError);
			}

			return TRUE;
		}
		catch (Exception $e)
		{
			self::report('error:' . $e->getMessage());
			return FALSE;
		}
	}

	/**
	 * Met à jour le chemin d'une catégorie, de ses sous-catégories
	 * et de ses images.
	 *
	 * @param array $infos
	 *	Informations de la catégorie.
	 * @param array $params
	 *	Paramètres des requêtes préparées.
	 * @return boolean
	 */
	private static function _updateCategoriesPaths($infos, $params)
	{
		// Mise à jour du mot de passe pour les catégories.
		$cat_password = (array_key_exists('password', $params))
			? ', cat_password = :password'
			: '';

		// On modifie le chemin de la catégorie
		// et de toutes les catégories descendantes.
		$sql = 'UPDATE ' . CONF_DB_PREF . 'categories
				   SET cat_path = REPLACE(CONCAT("^", cat_path),
					   CONCAT("^", :old_path), :new_path)
					   ' . $cat_password . '
				 WHERE cat_id = ' . (int) $infos['cat_id'] . '
					OR cat_path LIKE CONCAT(:old_path, "/%")';
		$sql = array(array('sql' => $sql, 'params' => array($params)));
		if (utils::$db->exec($sql, FALSE) === FALSE
		|| ($rows_affected = utils::$db->nbResult[0][0]) === 0)
		{
			return FALSE;
		}
		if (array_key_exists('password', $params))
		{
			unset($params['password']);
		}

		// On vérifie que le chemin des catégories a bien été changé.
		$sql = 'SELECT COUNT(cat_id)
				  FROM ' . CONF_DB_PREF . 'categories
				 WHERE cat_path = :new_path
					OR cat_path LIKE CONCAT(:new_path, "/%")';
		if (utils::$db->prepare($sql) === FALSE
		|| utils::$db->executeQuery($params, 'value') === FALSE
		|| utils::$db->queryResult != $rows_affected)
		{
			return FALSE;
		}

		// On modifie le chemin de toutes les images
		// contenues dans la catégorie.
		$nb_images = $infos['cat_a_images'] + $infos['cat_d_images'];
		$sql = 'UPDATE ' . CONF_DB_PREF . 'images
				   SET image_path = REPLACE(CONCAT("^", image_path),
					   CONCAT("^", :old_path), :new_path)
				 WHERE image_path LIKE CONCAT(:old_path, "/%")';
		$sql = array(array('sql' => $sql, 'params' => array($params)));
		if (utils::$db->exec($sql, FALSE) === FALSE
		|| utils::$db->nbResult[0][0] != $nb_images)
		{
			return FALSE;
		}

		return TRUE;
	}
}

/**
 * Gestion des commentaires.
 */
class comments extends admin
{
	/**
	 * Informations utiles des éléments de la page courante.
	 *
	 * @var array
	 */
	public static $items;

	/**
	 * Nombre de commentaires avec le filtre courant.
	 *
	 * @var integer
	 */
	public static $nbItems;

	/**
	 * Nombre de pages.
	 *
	 * @var integer
	 */
	public static $nbPages;

	/**
	 * Informations sur l'objet courant.
	 *
	 * @var array
	 */
	public static $objectInfos;

	/**
	 * Informations utiles sur les images de l'album courant.
	 *
	 * @var array
	 */
	public static $images;

	/**
	 * Champs de la table "comments" pour la recherche.
	 *
	 * @var array
	 */
	public static $searchFields = array(
		'com_message',
		'com_author',
		'com_ip',
		'com_email',
		'com_website'
	);

	/**
	 * Options de recherche.
	 *
	 * @var array
	 */
	public static $searchOptions = array(
		'all_words' => 'bin',
		'com_author' => 'bin',
		'com_email' => 'bin',
		'com_ip' => 'bin',
		'com_message' => 'bin',
		'com_website' => 'bin',
		'date' => 'bin',
		'date_end_day' => '\d{2}',
		'date_end_month' => '\d{2}',
		'date_end_year' => '\d{4}',
		'date_field' => '(?:com_crtdt|com_lastupddt)',
		'date_start_day' => '\d{2}',
		'date_start_month' => '\d{2}',
		'date_start_year' => '\d{4}',
		'status' => '(?:pending|publish|unpublish)',
		'user' => '(?:all|\d{1,11})'
	);



	/**
	 * Actions sur la sélection de commentaires.
	 *
	 * @return void
	 */
	public static function actions()
	{
		if (($selected_ids = self::_initObjectsActions()) === FALSE)
		{
			return;
		}

		// Action à effectuer.
		switch ($_POST['action'])
		{
			// Suppression.
			case 'delete' :
				$success_message = __('Les commentaires sélectionnés ont été supprimés.');
				self::_action($selected_ids, 'delete', $success_message);
				break;

			// Activation.
			case 'publish' :
				$success_message = __('Les commentaires sélectionnés ont été activés.');
				self::_action($selected_ids, 'publish', $success_message);
				break;

			// Désactivation.
			case 'unpublish' :
				$success_message = __('Les commentaires sélectionnés ont été désactivés.');
				self::_action($selected_ids, 'unpublish', $success_message);
				break;
		}
	}

	/**
	 * Édition des commentaires.
	 *
	 * @return void
	 */
	public static function edit()
	{
		// Liste des champs.
		$fields = array('author', 'email', 'website', 'message');

		self::_edit($fields, 'comments', 'com_');
	}

	/**
	 * Récupération des informations utiles des commentaires de la page courante.
	 *
	 * @return void
	 */
	public static function getComments()
	{
		$comments_per_page = auth::$infos['user_prefs']['comments']['nb_per_page'];

		$sw = self::_sqlWhereComments('com');
		$sql_where = $sw['sql'];
		$params = $sw['params'];

		// Informations utiles de l'image courante.
		if (isset($_GET['object_type']) && $_GET['object_type'] == 'image')
		{
			$sql = 'SELECT cat.cat_id,
						   cat_name,
						   cat_path,
						   image_id,
						   image_name
					  FROM ' . CONF_DB_PREF . 'images AS img
				 LEFT JOIN ' . CONF_DB_PREF . 'categories AS cat USING (cat_id)
					 WHERE image_id = ' . (int) $_GET['object_id'];
			if (utils::$db->query($sql, 'row') === FALSE
			|| utils::$db->nbResult === 0)
			{
				utils::redirect('comments-images');
			}
			self::$objectInfos = utils::$db->queryResult;

			$sql_where .= ' AND img.image_id = ' . (int) self::$objectInfos['image_id'];
		}

		// Informations utiles de la catégorie courante.
		else if (isset($_GET['object_type']) && $_GET['object_type'] == 'category')
		{
			$sql = 'SELECT cat_id,
						   cat_name,
						   cat_path,
						   cat_filemtime
					  FROM ' . CONF_DB_PREF . 'categories
					 WHERE cat_id = ' . (int) $_GET['object_id'];
			if (utils::$db->query($sql, 'row') === FALSE
			|| utils::$db->nbResult === 0)
			{
				utils::redirect('comments-images');
			}
			self::$objectInfos = utils::$db->queryResult;

			if ($_GET['object_id'] > 1)
			{
				$sql_where .= ' AND img.image_path LIKE CONCAT(:path, "/%")';
				$params['path'] = self::$objectInfos['cat_path'];
			}
		}

		// Moteur de recherche.
		if (isset($_GET['search_query']))
		{
			self::$_sqlSearch = search::getSQLWhere(self::$searchFields, FALSE, TRUE);
			if (self::$_sqlSearch)
			{
				// Statut.
				if (isset($_GET['search_status']))
				{
					switch ($_GET['search_status'])
					{
						case 'publish' :
							self::$_sqlSearch['sql'] .= ' AND com_status = "1"';
							break;

						case 'unpublish' :
							self::$_sqlSearch['sql'] .= ' AND com_status = "0"';
							break;

						case 'pending' :
							self::$_sqlSearch['sql'] .= ' AND com_status = "-1"';
							break;
					}
				}

				// Utilisateur.
				if (isset($_GET['search_user'])
				&& preg_match('`^\d{1,11}$`', $_GET['search_user']))
				{
					self::$_sqlSearch['sql'] .=
						' AND com.user_id = ' . (int) $_GET['search_user'];
				}

				$sql_where .= ' AND ' . self::$_sqlSearch['sql'];
				$params = array_merge($params, self::$_sqlSearch['params']);
				self::$searchInit = TRUE;
			}
		}

		// Nombre de commentaires.
		$sql = 'SELECT COUNT(*)
				  FROM ' . CONF_DB_PREF . 'comments AS com,
				       ' . CONF_DB_PREF . 'images AS img,
					   ' . CONF_DB_PREF . 'categories AS cat
				 WHERE com.image_id = img.image_id
				   AND cat.cat_id = img.cat_id
				   ' . $sql_where
					 . sql::$groupAlbumsAccess;
		if (utils::$db->prepare($sql) === FALSE
		|| utils::$db->executeQuery($params, 'value') === FALSE)
		{
			return;
		}
		self::$nbItems = utils::$db->queryResult;

		// Nombre de pages.
		self::$nbPages = ceil(self::$nbItems / $comments_per_page);
		$sql_limit_start = $comments_per_page * ($_GET['page'] - 1);

		// Critère de tri.
		$sql_order_by = auth::$infos['user_prefs']['comments']['orderby'];
		$sql_order_by = 'LOWER(com_' . auth::$infos['user_prefs']['comments']['sortby'] . ') '
			. $sql_order_by . ', com_id ' . $sql_order_by;

		// Récupération des commentaires.
		$sql = 'SELECT com.*,
					   user.user_login,
					   img.image_name,
					   img.image_adddt,
					   img.image_path,
					   img.image_url,
					   img.image_width,
					   img.image_height,
					   img.image_tb_infos AS tb_infos,
					   cat.cat_id,
					   cat.cat_name
				  FROM ' . CONF_DB_PREF . 'comments AS com,
					   ' . CONF_DB_PREF . 'categories AS cat,
					   ' . CONF_DB_PREF . 'images AS img,
					   ' . CONF_DB_PREF . 'users AS user
				 WHERE com.user_id = user.user_id
				   AND com.image_id = img.image_id
				   AND img.cat_id = cat.cat_id'
					 . $sql_where
					 . sql::$groupAlbumsAccess . '
			  ORDER BY ' . $sql_order_by . '
			     LIMIT ' . $sql_limit_start . ',' . $comments_per_page;
		$fetch_style = array('fetch' => PDO::FETCH_ASSOC, 'column' => 'com_id');
		if (utils::$db->prepare($sql) === FALSE
		|| utils::$db->executeQuery($params, $fetch_style) === FALSE)
		{
			return;
		}
		if (!admin::_objectsNbResult())
		{
			return;
		}
		self::$items = utils::$db->queryResult;
	}

	/**
	 * Récupération des images de l'album courant.
	 *
	 * @return void
	 */
	public static function getImages()
	{
		if ((isset($_GET['object_type']) &&
		(($_GET['object_type'] == 'category' && self::$objectInfos['cat_filemtime'] !== NULL)
		|| $_GET['object_type'] == 'image')) === FALSE)
		{
			return;
		}

		$sw = self::_sqlWhereComments('com');
		$sql_where = $sw['sql'];
		$params = $sw['params'];

		if (self::$_sqlSearch)
		{
			$sql_where .= ' AND ' . self::$_sqlSearch['sql'];
			$params = array_merge($params, self::$_sqlSearch['params']);
		}

		$sql = 'SELECT img.image_id,
					   img.image_name
				  FROM ' . CONF_DB_PREF . 'categories AS cat,
					   ' . CONF_DB_PREF . 'comments AS com,
					   ' . CONF_DB_PREF . 'images AS img
				 WHERE cat.cat_id = ' . (int) self::$objectInfos['cat_id'] . '
				   AND cat.cat_id = img.cat_id
				   AND img.image_id = com.image_id'
					 . $sql_where;
		$fetch_style = array('fetch' => PDO::FETCH_ASSOC, 'column' => 'image_id');
		if (utils::$db->prepare($sql) === FALSE
		|| utils::$db->executeQuery($params, $fetch_style) === FALSE
		|| utils::$db->nbResult === 0)
		{
			return;
		}

		self::$images = utils::$db->queryResult;
	}

	/**
	 * Réduit la liste des catégories à celles commentées
	 * selon le statut choisi et la requête de la recherche.
	 *
	 * @return void
	 */
	public static function reduceMap()
	{
		$sw = self::_sqlWhereComments('com');
		$sql_where = $sw['sql'];
		$params = $sw['params'];

		if (self::$_sqlSearch)
		{
			$sql_where .= ' AND ' . self::$_sqlSearch['sql'];
			$params = array_merge($params, self::$_sqlSearch['params']);
		}

		$sql = 'SELECT cat.cat_id
				  FROM ' . CONF_DB_PREF . 'categories AS cat,
					   ' . CONF_DB_PREF . 'images AS img,
					   ' . CONF_DB_PREF . 'comments AS com
				 WHERE cat.cat_id = img.cat_id
				   AND img.image_id = com.image_id'
					 . $sql_where;
		$fetch_style = array('column' => array('cat_id', 'cat_id'));
		if (utils::$db->prepare($sql) === FALSE
		|| utils::$db->executeQuery($params, $fetch_style) === FALSE)
		{
			return;
		}

		self::_reduceMapAlbums(utils::$db->queryResult);
	}

	/**
	 * Options des commentaires.
	 *
	 * @return void
	 */
	public static function options()
	{
		if (empty($_POST))
		{
			return;
		}

		$columns = array();
		$params = array();

		// Jeux d'icones.
		$smilies_icons_pack = array();
		foreach (scandir(GALLERY_ROOT . '/images/smilies') as $dirname)
		{
			if (preg_match('`^[-a-z0-9_]{1,64}$`i', $dirname) &&
			file_exists(GALLERY_ROOT . '/images/smilies/' . $dirname . '/icons.php'))
			{
				$smilies_icons_pack[] = $dirname;
			}
		}

		// Vérification de la limite pour le paramètre 'comments_maxchars'.
		if (isset($_POST['comments_maxchars']) && (int) $_POST['comments_maxchars'] > 5000)
		{
			unset($_POST['comments_maxchars']);
		}

		$fields = array(
			'checkboxes' => array(
				'comments_moderate',
				'comments_required_email',
				'comments_required_website',
				'comments_smilies',
				'comments_words_limit',
				'comments_convert_urls'
			),
			'integer' => array(
				'comments_maxurls',
				'comments_antiflood',
				'comments_maxchars',
				'comments_maxlines',
				'comments_words_maxlength',
				'comments_links_maxlength'
			),
			'lists' => array(
				'comments_order' => array('ASC', 'DESC'),
				'comments_smilies_icons_pack' => $smilies_icons_pack
			)
		);

		self::_changeDBConfig($fields, $columns, $params);
	}



	/**
	 * Édition des commentaires.
	 *
	 * @return void
	 */
	protected static function _edit($fields, $table, $pref)
	{
		if (!isset($_POST['save']))
		{
			return;
		}

		foreach (self::$items as $id => &$infos)
		{
			if (empty($_POST[$id]) || !is_array($_POST[$id]))
			{
				continue;
			}

			$change = FALSE;
			$columns = array();
			$params = array();

			foreach ($fields as &$i)
			{
				if (isset($_POST[$id][$i])
				&& trim($_POST[$id][$i]) != $infos[$pref . $i])
				{
					// Quelques vérifications.
					switch ($i)
					{
						case 'author' :
							if ($infos['user_id'] != 2)
							{
								continue 2;
							}
							if (utils::isEmpty($_POST[$id][$i]))
							{
								self::report('warning:' .
									__('Le nom d\'auteur doit contenir au moins 1 caractère.'),
									$id);
								continue 2;
							}
							break;

						case 'email' :
							if ($infos['user_id'] != 2)
							{
								continue 2;
							}
							$regex = '`^(' . utils::regexpEmail() . ')?$`i';
							if (!preg_match($regex, $_POST[$id][$i]))
							{
								self::report('warning:' .
									__('Format de l\'adresse de courriel incorrect.'), $id);
								continue 2;
							}
							break;

						case 'message' :
							if ($_POST[$id][$i] == '')
							{
								self::report('warning:' .
									__('Le message doit contenir au moins 1 caractère.'), $id);
								continue 2;
							}
							break;

						case 'rate' :
							if (!in_array($_POST[$id][$i], array('0', '1', '2', '3', '4', '5')))
							{
								continue 2;
							}
							if ($_POST[$id][$i] == '0')
							{
								$_POST[$id][$i] = NULL;
							}
							break;

						case 'website' :
							if ($infos['user_id'] != 2)
							{
								continue 2;
							}
							$regex = '`^(' . utils::regexpURL() . ')?$`i';
							if (!preg_match($regex, $_POST[$id][$i]))
							{
								self::report('warning:' .
									__('Format de l\'adresse du site Web incorrect.'), $id);
								continue 2;
							}
							break;
					}

					$columns[] = $pref . $i . ' = :' . $i;
					$params[$i] = (is_null($_POST[$id][$i]))
						? $_POST[$id][$i]
						: trim($_POST[$id][$i]);
					$change = TRUE;
				}
			}

			if (!$change)
			{
				continue;
			}

			// On effectue la mise à jour du commentaire.
			if (!empty($params))
			{
				$sql = 'UPDATE ' . CONF_DB_PREF . $table . '
					 	   SET ' . implode(', ', $columns) . ',
							   ' . $pref . 'lastupddt = NOW()
						 WHERE ' . $pref . 'id = ' . (int) $id;
				if (utils::$db->prepare($sql) === FALSE
				|| utils::$db->executeExec($params) === FALSE
				|| utils::$db->nbResult === 0)
				{
					self::report('error:' . utils::$db->msgError, $id);
					continue;
				}
			}

			// Mise à jour du tableau des commentaires.
			foreach ($fields as &$i)
			{
				if (isset($params[$i]))
				{
					$infos[$pref . $i] = $params[$i];
				}
			}

			self::report('success:' . __('Modifications enregistrées.'));
			self::report('success_p:' . __('Les autres modifications ont été enregistrées.'));
		}
	}



	/**
	 * Action sur les commentaires sélectionnés.
	 *
	 * @param array $selected_ids
	 *	Identifiants des commentaires sélectionnés.
	 * @param string $action
	 * @param string $message
	 * @return void
	 */
	private static function _action($selected_ids, $action, $message)
	{
		try
		{
			// Début de la transaction.
			if (!utils::$db->transaction())
			{
				throw new Exception('error:' . utils::$db->msgError);
			}

			$new_status = ($action == 'publish') ? 1 : 0;
			$cur_status = ($action == 'publish')
				? array('"-1"', '"0"')
				: array('"-1"', '"1"');

			// Récupération des informations utiles des images sur
			// lesquelles ont été postés les commentaires sélectionnés.
			$status = ($action == 'delete')
				? ''
				: ' AND com.com_status IN (' . implode(', ', $cur_status) . ')';
			$sql = 'SELECT com.com_id,
						   com.com_status,
						   img.image_id,
						   img.image_status,
						   cat.cat_id,
						   cat.cat_path
					  FROM ' . CONF_DB_PREF . 'comments AS com,
						   ' . CONF_DB_PREF . 'images AS img,
						   ' . CONF_DB_PREF . 'categories AS cat
					 WHERE com.image_id = img.image_id
					   AND img.cat_id = cat.cat_id
					   AND com.com_id IN (' . implode(', ', $selected_ids) . ')'
					     . $status
						 . sql::$groupAlbumsAccess;
			if (utils::$db->query($sql, PDO::FETCH_ASSOC) === FALSE)
			{
				throw new Exception(utils::$db->msgError);
			}

			// Si aucune ligne affectée, il ne faut rien changer.
			if (utils::$db->nbResult === 0)
			{
				utils::$db->rollBack();
				return;
			}

			$infos = utils::$db->queryResult;

			$images_comments = array();
			$images_infos = array();
			foreach ($infos as &$i)
			{
				$images_comments[$i['image_id']][$i['com_id']] = $i['com_status'];
				$images_infos[$i['image_id']] = array(
					'image_status' => $i['image_status'],
					'cat_id' => $i['cat_id'],
					'cat_path' => $i['cat_path']
				);
			}

			// Suppression des commentaires.
			if ($action == 'delete')
			{
				$sql = 'DELETE
					      FROM ' . CONF_DB_PREF . 'comments
						 WHERE com_id IN (' . implode(', ', $selected_ids) . ')';
			}

			// (dés)activation des commentaires.
			else
			{
				$sql = 'UPDATE ' . CONF_DB_PREF . 'comments
					       SET com_status = "' . $new_status . '"
						 WHERE com_id IN (' . implode(', ', $selected_ids) . ')
						   AND com_status IN (' . implode(', ', $cur_status) . ')';
			}

			if (utils::$db->exec($sql, FALSE) === FALSE)
			{
				throw new Exception(utils::$db->msgError);
			}

			$sql = array();

			// Doit-on ajouter ou soustraire le nombre de commentaires ?
			$c = ($action == 'publish') ? '+' : '-';

			// Traitement image par image.
			$parents_id = array();
			foreach ($images_comments as $image_id => &$comments_id)
			{
				$i =& $images_infos[$image_id];

				// Récupération des identifiants
				// des catégories parentes de l'image.
				if (!isset($parents_id[$i['cat_id']]))
				{
					$parents_id[$i['cat_id']] =
						alb::getParentsIds($i['cat_id'], $i['cat_path']);
					if ($parents_id[$i['cat_id']] === FALSE)
					{
						throw new Exception(utils::$db->msgError);
					}
				}

				// Quand supression ou désactivation de commentaires,
				// il faut updater uniquement le nombre de commentaires activés.
				if ($action == 'delete' || $action == 'unpublish')
				{
					$comments_id = array_filter($comments_id, create_function(
						'$a', 'return $a == 1;'
					));
				}

				// Nombre de commentaires à updater.
				if (($nb_comments = count($comments_id)) == 0)
				{
					continue;
				}

				// On met à jour le nombre de commentaires de l'image
				// et de ses catégories parentes.
				$stat = ($i['image_status'] == 1) ? 'a' : 'd';
				$sql[] = 'UPDATE ' . CONF_DB_PREF . 'images
						     SET image_comments = image_comments '
								. $c . ' ' . $nb_comments . '
						   WHERE image_id = ' . (int) $image_id;

				$sql[] = 'UPDATE ' . CONF_DB_PREF . 'categories
							 SET cat_' . $stat . '_comments
							   = cat_' . $stat . '_comments '
								. $c . ' ' . $nb_comments . '
						   WHERE cat_id IN (' . implode(', ', $parents_id[$i['cat_id']]) . ')';
			}

			// Exécution de la transaction.
			if (empty($sql))
			{
				if (!utils::$db->commit())
				{
					throw new Exception('error:' . utils::$db->msgError);
				}
			}
			else
			{
				if (utils::$db->exec($sql, TRUE) === FALSE)
				{
					throw new Exception(utils::$db->msgError);
				}
			}

			unset($sql);
			unset($parents_id);
			self::report('success:' . $message);
		}
		catch (Exception $e)
		{
			self::report('error:' . $e->getMessage());
		}
	}

	/**
	 * Retourne la clause WHERE correspondant
	 * aux critères de recherche des commentaires à récupérer.
	 *
	 * @param string $pref
	 * @return string
	 */
	protected static function _sqlWhereComments($pref)
	{
		$sql = '';
		$params = array();

		if (isset($_GET['date']))
		{
			$sql = ' AND ' . $pref . '_crtdt >= :crtdt " 00:00:00"'
				 . ' AND ' . $pref . '_crtdt <= :crtdt " 23:59:59"';
			$params['crtdt'] = $_GET['date'];
		}

		if (isset($_GET['ip']))
		{
			$sql = ' AND ' . $pref . '_ip = :ip';
			$params['ip'] = $_GET['ip'];
		}

		if (isset($_GET['status']))
		{
			$sql = ' AND ' . $pref . '_status = "-1"';
		}

		if (isset($_GET['user_id']))
		{
			$sql = ' AND com.user_id != 2
					 AND com.user_id = ' . (int) $_GET['user_id'];
		}
		
		return array(
			'sql' => $sql,
			'params' => $params
		);
	}
}

/**
 * Gestion du livre d'or.
 */
class guestbook extends comments
{
	/**
	 * Champs de la table "guestbook" pour la recherche.
	 *
	 * @var array
	 */
	public static $searchFields = array(
		'guestbook_message',
		'guestbook_author',
		'guestbook_ip',
		'guestbook_email',
		'guestbook_website'
	);

	/**
	 * Options de recherche.
	 *
	 * @var array
	 */
	public static $searchOptions = array(
		'all_words' => 'bin',
		'date' => 'bin',
		'date_end_day' => '\d{2}',
		'date_end_month' => '\d{2}',
		'date_end_year' => '\d{4}',
		'date_field' => '(?:guestbook_crtdt|guestbook_lastupddt)',
		'date_start_day' => '\d{2}',
		'date_start_month' => '\d{2}',
		'date_start_year' => '\d{4}',
		'guestbook_author' => 'bin',
		'guestbook_email' => 'bin',
		'guestbook_ip' => 'bin',
		'guestbook_message' => 'bin',
		'guestbook_website' => 'bin',
		'rate' => '(?:all|null|[1-5])',
		'status' => '(?:pending|publish|unpublish)',
		'user' => '(?:all|\d{1,11})'
	);



	/**
	 * Actions sur la sélection de commentaires.
	 *
	 * @return void
	 */
	public static function actions()
	{
		if (($selected_ids = self::_initObjectsActions()) === FALSE)
		{
			return;
		}

		// Action à effectuer.
		switch ($_POST['action'])
		{
			// Suppression.
			case 'delete' :
				$success_message = __('Les commentaires sélectionnés ont été supprimés.');
				self::_action($selected_ids, 'delete', $success_message);
				break;

			// Activation.
			case 'publish' :
				$success_message = __('Les commentaires sélectionnés ont été activés.');
				self::_action($selected_ids, 'publish', $success_message);
				break;

			// Désactivation.
			case 'unpublish' :
				$success_message = __('Les commentaires sélectionnés ont été désactivés.');
				self::_action($selected_ids, 'unpublish', $success_message);
				break;
		}
	}

	/**
	 * Édition des commentaires.
	 *
	 * @return void
	 */
	public static function edit()
	{
		// Liste des champs.
		$fields = array('author', 'email', 'website', 'rate', 'message');

		self::_edit($fields, 'guestbook', 'guestbook_');
	}

	/**
	 * Récupération des informations utiles des commentaires de la page courante.
	 *
	 * @return void
	 */
	public static function getComments()
	{
		$comments_per_page = auth::$infos['user_prefs']['guestbook']['nb_per_page'];

		$sw = self::_sqlWhereComments('guestbook');
		$sql_where = $sw['sql'];
		$params = $sw['params'];

		// Moteur de recherche.
		if (isset($_GET['search_query']))
		{
			self::$_sqlSearch = search::getSQLWhere(self::$searchFields, FALSE, TRUE);
			if (self::$_sqlSearch)
			{
				// Note.
				if (isset($_GET['search_rate']))
				{
					switch ($_GET['search_rate'])
					{
						case 'null' :
							self::$_sqlSearch['sql'] .= ' AND guestbook_rate IS NULL';
							break;

						case '1' :
						case '2' :
						case '3' :
						case '4' :
						case '5' :
							self::$_sqlSearch['sql'] .= ' AND guestbook_rate = "'
								. $_GET['search_rate'] . '"';
							break;
					}
				}

				// Statut.
				if (isset($_GET['search_status']))
				{
					switch ($_GET['search_status'])
					{
						case 'publish' :
							self::$_sqlSearch['sql'] .= ' AND guestbook_status = "1"';
							break;

						case 'unpublish' :
							self::$_sqlSearch['sql'] .= ' AND guestbook_status = "0"';
							break;

						case 'pending' :
							self::$_sqlSearch['sql'] .= ' AND guestbook_status = "-1"';
							break;
					}
				}

				// Utilisateur.
				if (isset($_GET['search_user'])
				&& preg_match('`^\d{1,11}$`', $_GET['search_user']))
				{
					self::$_sqlSearch['sql'] .=
						' AND user_id = ' . (int) $_GET['search_user'];
				}

				$sql_where .= ' AND ' . self::$_sqlSearch['sql'];
				$params = array_merge($params, self::$_sqlSearch['params']);
				self::$searchInit = TRUE;
			}
		}

		// Nombre de commentaires.
		$sql = 'SELECT COUNT(*)
				  FROM ' . CONF_DB_PREF . 'guestbook AS com
				 WHERE 1 = 1 '
					 . $sql_where;
		if (utils::$db->prepare($sql) === FALSE
		|| utils::$db->executeQuery($params, 'value') === FALSE)
		{
			return;
		}
		self::$nbItems = utils::$db->queryResult;

		// Nombre de pages.
		self::$nbPages = ceil(self::$nbItems / $comments_per_page);
		$sql_limit_start = $comments_per_page * ($_GET['page'] - 1);

		// Critère de tri.
		$sql_order_by = auth::$infos['user_prefs']['guestbook']['orderby'];
		$sql_order_by = 'LOWER(guestbook_'
			. auth::$infos['user_prefs']['guestbook']['sortby'] . ') '
			. $sql_order_by . ', guestbook_id ' . $sql_order_by;

		// Récupération des commentaires.
		$sql = 'SELECT com.*,
					   u.user_avatar,
					   u.user_login
				  FROM ' . CONF_DB_PREF . 'guestbook AS com,
					   ' . CONF_DB_PREF . 'users AS u
				 WHERE com.user_id = u.user_id'
					 . $sql_where . '
			  ORDER BY ' . $sql_order_by . '
			     LIMIT ' . $sql_limit_start . ',' . $comments_per_page;
		$fetch_style = array('fetch' => PDO::FETCH_ASSOC, 'column' => 'guestbook_id');
		if (utils::$db->prepare($sql) === FALSE
		|| utils::$db->executeQuery($params, $fetch_style) === FALSE)
		{
			return;
		}
		if (!admin::_objectsNbResult())
		{
			return;
		}
		self::$items = utils::$db->queryResult;
	}



	/**
	 * Action sur les commentaires sélectionnés.
	 *
	 * @param array $selected_ids
	 *	Identifiants des commentaires sélectionnés.
	 * @param string $action
	 * @param string $message
	 * @return void
	 */
	private static function _action($selected_ids, $action, $message)
	{
		try
		{
			$new_status = ($action == 'publish') ? '1' : '0';
			$cur_status = ($action == 'publish')
				? array('"-1"', '"0"')
				: array('"-1"', '"1"');

			// Suppression des commentaires.
			if ($action == 'delete')
			{
				$sql = 'DELETE
					      FROM ' . CONF_DB_PREF . 'guestbook
						 WHERE guestbook_id IN (' . implode(', ', $selected_ids) . ')';
			}

			// (dés)activation des commentaires.
			else
			{
				$sql = 'UPDATE ' . CONF_DB_PREF . 'guestbook
					       SET guestbook_status = "' . $new_status . '"
						 WHERE guestbook_id IN (' . implode(', ', $selected_ids) . ')
						   AND guestbook_status IN (' . implode(', ', $cur_status) . ')';
			}

			if (utils::$db->exec($sql, FALSE) === FALSE)
			{
				throw new Exception(utils::$db->msgError);
			}

			self::report('success:' . $message);
		}
		catch (Exception $e)
		{
			self::report('error:' . $e->getMessage());
		}
	}
}

/**
 * Gestion des images.
 */
class image extends album
{
	/**
	 * Images de l'album courant.
	 *
	 * @var array
	 */
	public static $albumImages;



	/**
	 * Suppression de l'image.
	 *
	 * @return void
	 */
	public static function delete()
	{
		if (!isset($_POST['delete']))
		{
			return;
		}

		$image_infos = array(self::$infos['image_id'] => self::$infos);
		$report = alb::deleteImages(self::$infos['cat_id'], $image_infos);

		if (!is_array($report[0]) && substr($report[0], 0, 5) == 'error')
		{
			self::report($report[0]);
			return;
		}

		utils::redirect('album/' . self::$infos['cat_id']);
	}

	/**
	 * Édition de l'image.
	 *
	 * @return void
	 */
	public static function edit()
	{
		if (empty($_POST['action']))
		{
			return;
		}

		// Action à effectuer sur l'image.
		switch ($_POST['action'])
		{
			case 'modify' :
				self::_modify();
				break;

			case 'replace' :
				self::_replace();
				break;

			case 'restore' :
				self::_restore();
				break;
		}
	}

	/**
	 * Édition du nombre de visites, du statut
	 * et de l'album d'une image.
	 *
	 * @return void
	 */
	public static function editGeneral()
	{
		if (!isset($_POST['save']))
		{
			return;
		}

		// Permissions d'accès.
		if (!auth::$perms['admin']['perms']['albums_modif']
		&& !auth::$perms['admin']['perms']['all'])
		{
			die;
		}

		$selected_ids = array($_GET['object_id']);
		$success = FALSE;

		// Changement du nombre de visites.
		if ($_POST['hits'] != self::$infos['image_hits']
		&& self::_hits($selected_ids, self::$infos['cat_id']))
		{
			$success = TRUE;
		}

		// Changement de statut.
		if ($_POST['status'] != self::$infos['image_status']
		&& self::_status($selected_ids, self::$infos['cat_id'], (int) $_POST['status']))
		{
			$success = TRUE;
		}

		// Changement de catégorie.
		if (isset($_POST['destination_cat']))
		{
			if ($_POST['destination_cat'] != self::$infos['cat_id']
			&& self::_move($selected_ids, self::$infos['cat_id']))
			{
				$success = TRUE;
			}
		}

		if ($success)
		{
			self::report('success:' . __('Modifications enregistrées.'));
			self::report('success_p:' . __('Les autres modifications ont été enregistrées.'));
			self::infos();
		}
	}

	/**
	 * Récupère les informations utiles de l'image.
	 *
	 * @return void
	 */
	public static function infos()
	{
		category::infos(
			'SELECT img.*,
					img.image_tb_infos AS tb_infos,
					cat.cat_id,
					cat.thumb_id,
					cat.cat_path,
					cat.cat_name,
					cat.cat_crtdt,
					cat.cat_a_images + cat.cat_d_images AS cat_images,
					cat.cat_filemtime,
					cat.cat_password,
					u.user_login,
					u.user_status
			   FROM ' . CONF_DB_PREF . 'images AS img
		  LEFT JOIN ' . CONF_DB_PREF . 'categories AS cat
				 ON img.cat_id = cat.cat_id
		  LEFT JOIN ' . CONF_DB_PREF . 'users AS u
				 ON img.user_id = u.user_id
			  WHERE img.image_id = ' . (int) $_GET['object_id'] . '
				AND cat.cat_id = img.cat_id'
				  . sql::$groupAlbumsAccess
		);
		if (empty(self::$infos))
		{
			utils::redirect('category/1', TRUE);
			return;
		}

		// Dimensions de l'image redimensionnée.
		$size = img::imageResize(self::$infos['image_width'],
			self::$infos['image_height'], 400, 400);
		self::$infos['preview_width'] = $size['width'];
		self::$infos['preview_height'] = $size['height'];

		// Groupes ayant accès à l'image.
		albums::getAllowedGroups(self::$infos['cat_password']);

		// Récupération des tags associés à l'image.
		image::getImageTags();
	}

	/**
	 * Récupération des tags de l'image.
	 *
	 * @return void
	 */
	public static function getImageTags()
	{
		$sql = 'SELECT tag_name
				  FROM ' . CONF_DB_PREF . 'tags AS t,
					   ' . CONF_DB_PREF . 'tags_images AS ti
				 WHERE ti.tag_id = t.tag_id
				   AND ti.image_id = ' . (int) $_GET['object_id'];
		$fetch_style = array('column' => array('tag_name', 'tag_name'));
		if (utils::$db->query($sql, $fetch_style) === FALSE)
		{
			return;
		}
		$tags = utils::$db->queryResult;
		sort($tags);
		self::$infos['image_tags'] = $tags;
	}

	/**
	 * Détermine le numéro de page de l'album parent.
	 *
	 * @return void
	 */
	public static function parentPage()
	{
		// Récupération de toutes les images de l'album.
		$order_by =  ' ' . auth::$infos['user_prefs']['album']['orderby'];
		$order_by = 'LOWER(image_' . auth::$infos['user_prefs']['album']['sortby'] . ')'
			. $order_by . ', image_id ' . $order_by;
		$sql = 'SELECT image_id,
					   image_name
				  FROM ' . CONF_DB_PREF . 'images
				 WHERE cat_id = ' . (int) self::$infos['cat_id'] . '
			  ORDER BY ' . $order_by;
		if (utils::$db->query($sql, PDO::FETCH_ASSOC) === FALSE
		|| utils::$db->nbResult === 0)
		{
			utils::redirect('category/1');
		}
		self::$albumImages = utils::$db->queryResult;

		// Position de l'image.
		$current_image = array_search(
			array(
				'image_id' => self::$infos['image_id'],
				'image_name' => self::$infos['image_name']
			),
			self::$albumImages
		) + 1;

		// On détermine la page de l'album parent
		// où se situe l'image actuelle.
		$parent_page = ceil($current_image
			/ auth::$infos['user_prefs']['album']['nb_per_page']);
		if ($parent_page > 1)
		{
			self::$parentPage = $parent_page;
		}
	}

	/**
	 * Mise à jour d'une image.
	 *
	 * @return void
	 */
	public static function update()
	{
		if (!isset($_POST['update']))
		{
			return;
		}

		$image_id = array($_GET['object_id']);
		$cat_id = self::$infos['cat_id'];

		if (($result = self::_update($image_id, $cat_id)) !== FALSE)
		{
			$message = ($result === 1)
				? __('L\'image a été mise à jour.')
				: __('L\'image est déjà à jour.');
			self::report('success:' . $message);
			self::infos();
		}
	}



	/**
	 * Effectue une copie de sauvegarde de l'image.
	 *
	 * @return boolean
	 */
	private static function _backup()
	{
		// Si le fichier de sauvegarde existe déjà, inutile d'aller plus loin.
		$backup = GALLERY_ROOT . '/' . img::filepath('im_backup',
			self::$infos['image_path'], self::$infos['image_id'], self::$infos['image_adddt']);
		if (file_exists($backup))
		{
			return TRUE;
		}

		try
		{
			$img_file = GALLERY_ROOT . '/' . CONF_ALBUMS_DIR
				. '/' . self::$infos['image_path'];
			$error_message = __('Impossible de sauvegarder l\'image.');

			// On vérifie que l'image a bien été sauvegardée.
			if (!files::copyFile($img_file, $backup) || !file_exists($backup))
			{
				throw new Exception($error_message);
			}

			// Et que ses dimensions correspondent bien
			// à celles enregistrées en base de données.
			$infos = img::getImageSize($backup);
			if ($infos['width'] != self::$infos['image_width']
			|| $infos['height'] != self::$infos['image_height'])
			{
				throw new Exception($error_message);
			}

			return TRUE;
		}
		catch (Exception $e)
		{
			self::report('error:' . $e->getMessage());

			return FALSE;
		}
	}

	/**
	 * Rognage de l'image.
	 *
	 * @param string $img_file
	 *	Chemin de l'image.
	 * @param array $i
	 *	Informations utiles de l'image.
	 * @param array $coords
	 *	Coordonnées de découpe.
	 * @return boolean|resource
	 */
	private static function _crop($img_file, &$i, $coords)
	{
		$coords = explode(',', $coords);

		// Si toute l'image est sélectionnée, inutile d'aller plus loin.
		if ($coords[0] == 0 && $coords[1] == 0
		&& $coords[2] == self::$infos['preview_width']
		&& $coords[3] == self::$infos['preview_height'])
		{
			return TRUE;
		}

		// Création d'une image de travail.
		$src_img = img::gdCreateImage($img_file, $i['filetype']);
		if (!is_resource($src_img))
		{
			return FALSE;
		}

		$file_edit = GALLERY_ROOT . '/' . img::filepath('im_edit',
			self::$infos['image_path'], self::$infos['image_id'], self::$infos['image_adddt']);

		// Coté le plus grand de l'image.
		$max_image = ($i['width'] > $i['height']) ? $i['width'] : $i['height'];

		// Coté le plus grand de l'aperçu.
		$i_edit = img::getImageSize($file_edit);
		$max_preview = ($i_edit['width'] > $i_edit['height'])
			? $i_edit['width']
			: $i_edit['height'];

		// Coordonnées pour le découpage et le redimensionnement de l'image.
		$ratio = $max_image / $max_preview;
		$crop = array(
			'x' => round((int) $coords[0] * $ratio),
			'y' => round((int) $coords[1] * $ratio),
			'w' => round((int) $coords[2] * $ratio),
			'h' => round((int) $coords[3] * $ratio)
		);

		// Ajustement des dimensions de découpe.
		while ($crop['w'] + $crop['x'] > self::$infos['image_width'])
		{
			$crop['w']--;
		}
		while ($crop['h'] + $crop['y'] > self::$infos['image_height'])
		{
			$crop['h']--;
		}

		// Dimensions de l'image rognée.
		$width = $crop['w'];
		$height = $crop['h'];
		if (!empty($_POST['width']) && !empty($_POST['height'])
		&& ($_POST['width'] != self::$infos['image_width']
		|| $_POST['height'] != self::$infos['image_height']))
		{
			$resize = $i['width'] / (int) $_POST['width'];
			$width = $width / $resize;
			$height = $height / $resize;
		}

		// Rognage de l'image.
		$dst_img = img::gdResize($src_img, $crop['x'], $crop['y'],
			$crop['w'], $crop['h'], 0, 0, $width, $height);

		return (is_resource($dst_img)) ? $dst_img : FALSE;
	}

	/**
	 * Supprime toutes les images redimensionnées de l'image courante.
	 *
	 * @return boolean
	 */
	private static function _deleteResizedImages()
	{
		return img::deleteResizedImages
		(
			self::$infos['image_id'],
			self::$infos['image_path'],
			self::$infos['image_adddt']
		);
	}

	/**
	 * Modification de l'image.
	 *
	 * @return void
	 */
	private static function _modify()
	{
		// Si la copie de sauvegarde à échouée,
		// on n'effectue aucune modification de l'image.
		if (!self::_backup())
		{
			return;
		}

		try
		{
			$error_message = __('Impossible de modifier l\'image.');

			// Informations utiles de l'image.
			$img_file = GALLERY_ROOT . '/' . CONF_ALBUMS_DIR . '/' . self::$infos['image_path'];
			$i = img::getImageSize($img_file);

			$image = NULL;

			// Si les coordonnées sont identiques, on ne rognera pas.
			if (!empty($_POST['crop']) && preg_match('`^[\d\s,.]{8,50}$`', $_POST['crop']))
			{
				$coords = explode('.', $_POST['crop']);
				if ($coords[0] == $coords[1])
				{
					unset($_POST['crop']);
				}
			}

			// Rognage et redimensionnement.
			if (!empty($_POST['crop']))
			{
				if (($image = self::_crop($img_file, $i, $coords[1])) === FALSE)
				{
					throw new Exception($error_message);
				}
			}

			// Redimensionnement.
			elseif (!empty($_POST['width']) && !empty($_POST['height'])
			&& (int) $_POST['width'] != self::$infos['image_width'])
			{
				if (($image = self::_resize($img_file, $i)) === FALSE)
				{
					throw new Exception($error_message);
				}
			}

			// Rotation.
			if (!empty($_POST['rotate']) && preg_match('`^90|180|270$`', $_POST['rotate']))
			{
				// Création d'une image de travail.
				if (!is_resource($image))
				{
					$image = img::gdCreateImage($img_file, $i['filetype']);
				}

				if (is_resource($image)
				&& ($image = img::gdRotate($image, $_POST['rotate'])) === FALSE)
				{
					throw new Exception($error_message);
				}
			}

			// Aucune modification.
			if (!is_resource($image))
			{
				return;
			}

			// Enregistrement de l'image.
			$quality = (isset($_POST['gd_quality']))
				? (int) $_POST['gd_quality']
				: 85;
			if (!img::gdCreateFile($image, $img_file, $i['filetype'], $quality))
			{
				throw new Exception($error_message);
			}

			// Mise à jour des informations de base de données.
			if (!self::_updateImage($img_file))
			{
				throw new Exception(utils::$db->msgError);
			}

			self::_deleteResizedImages();

			self::report('success:' . __('L\'image a été modifiée.'));
		}
		catch (Exception $e)
		{
			self::report('error:' . $e->getMessage());
		}
	}

	/**
	 * Remplace l'image.
	 *
	 * @return void
	 */
	private static function _replace()
	{
		if (empty($_FILES['file']))
		{
			return;
		}

		// Répertoire temporaire.
		$temp_dir = GALLERY_ROOT . '/cache/up_temp/' . utils::genKey();
		if (!files::mkdir($temp_dir))
		{
			self::report('error:' . __('Impossible de créer le répertoire.'));
			return;
		}

		// Récupération et vérification du fichier.
		$r = alb::uploadFile(
			$_FILES['file']['name'], $_FILES['file']['tmp_name'], $_FILES['file']['error'],
			$temp_dir, self::$infos['cat_path']
		);

		// Pas de fichier.
		if ($r === FALSE)
		{
			return;
		}

		// Erreur.
		else if (is_array($r))
		{
			self::report($r[0] . ':' . $r[1]);
			return;
		}

		// On déplace l'image du répertoire
		// temporaire vers le répertoire de l'album destination.
		if (files::renameFile(
			$temp_dir . '/' . $r,
			GALLERY_ROOT . '/' . CONF_ALBUMS_DIR . '/'
				. self::$infos['cat_path'] . '/' . basename(self::$infos['image_path'])
		) === FALSE)
		{
			self::report('error:' . __('Impossible de remplacer le fichier.'));
			return;
		}

		// On supprime le répertoire temporaire.
		if (file_exists($temp_dir))
		{
			files::rmdir($temp_dir);
		}

		// On supprime toutes les images redimensionnées de l'ancienne image.
		img::deleteResizedImages
		(
			self::$infos['image_id'],
			self::$infos['image_path'],
			self::$infos['image_adddt']
		);

		// Initialisation du scan.
		$upload = new upload();
		if ($upload->getInit === FALSE)
		{
			self::report('error:' . __('Une requête SQL a échouée : '
				. 'le scan ne peut se poursuivre.'));
			return;
		}

		// Options de scan.
		$upload->setHttp = TRUE;
		$upload->setHttpImages = array(basename(self::$infos['image_path']) => 0);
		$upload->setUpdateImages = TRUE;

		// Scan du répertoire de l'album courant.
		if ($upload->getAlbums(self::$infos['cat_path']) === FALSE)
		{
			self::report('error:' . __('Une erreur s\'est produite : '
				. 'la mise à jour de la base de données a échouée.'));
			return;
		}

		// Rapport.
		if (!empty($upload->getReport['errors']))
		{
			foreach ($upload->getReport['errors'] as $e)
			{
				self::report('error:' . $e[0] . ': ' . $e[1]);
			}
		}
		if ($upload->getReport['img_update'] == 1)
		{
			self::report('success:' . __('L\'image a été remplacée.'));

			// On récupère à nouveau les informations de l'image.
			image::infos();
		}
	}

	/**
	 * Redimensionne l'image.
	 *
	 * @param string $img_file
	 *	Chemin de l'image.
	 * @param array $i
	 *	Informations utiles de l'image.
	 * @return boolean|resource
	 */
	private static function _resize($img_file, &$i)
	{
		if (!preg_match('`^\d{1,5}$`', $_POST['width'])
		|| !preg_match('`^\d{1,5}$`', $_POST['height']))
		{
			return TRUE;
		}

		// Création d'une image de travail.
		$src_img = img::gdCreateImage($img_file, $i['filetype']);
		if (!is_resource($src_img))
		{
			return FALSE;
		}

		// Redimensionnement.
		$dst_img = img::gdResize($src_img, 0, 0, $i['width'], $i['height'],
			0, 0, $_POST['width'], $_POST['height']);

		return (is_resource($dst_img)) ? $dst_img : FALSE;
	}

	/**
	 * Restaure l'image originale.
	 *
	 * @return void
	 */
	private static function _restore()
	{
		// Chemins de l'image et de la copie de sauvegarde.
		$img_file = GALLERY_ROOT . '/' . CONF_ALBUMS_DIR
			. '/' . self::$infos['image_path'];
		$backup_file = GALLERY_ROOT . '/' . img::filepath('im_backup',
			self::$infos['image_path'], self::$infos['image_id'], self::$infos['image_adddt']);

		if (!file_exists($backup_file))
		{
			return;
		}

		try
		{
			$error_message = __('L\'image n\'a pas pu être restaurée.');

			// On remplace l'image actuelle par l'originale.
			if (!files::copyFile($backup_file, $img_file) || !file_exists($img_file))
			{
				throw new Exception($error_message);
			}

			// On supprime la copie de sauvegarde, et toutes les images associées.
			files::unlink($backup_file);
			self::_deleteResizedImages();

			// Mise à jour des informations de base de données.
			if (!self::_updateImage($img_file))
			{
				throw new Exception(utils::$db->msgError);
			}

			self::report('success:' . __('L\'image a été restaurée.'));
		}
		catch (Exception $e)
		{
			self::report('error:' . $e->getMessage());
		}
	}

	/**
	 * Mise à jour des informations de l'image.
	 *
	 * @param string $img_file
	 *	Chemin de l'image.
	 * @return boolean
	 */
	private static function _updateImage($img_file)
	{
		clearstatcache();
		$i = img::getImageSize($img_file);
		$filesize = filesize($img_file);
		$update_size = $filesize - (int) self::$infos['image_filesize'];
		self::$infos['image_filesize'] = $filesize;

		$sql = array();

		// Mise à jour des informations en relation avec l'image.
		$sql[] = 'UPDATE ' . CONF_DB_PREF . 'images
				     SET image_width = ' . (int) $i['width'] . ',
					     image_height = ' . (int) $i['height'] . ',
					     image_filesize = ' . (int) $filesize . ',
					     image_tb_infos = NULL
				   WHERE image_id = ' . (int) self::$infos['image_id'];

		$sql[] = 'UPDATE ' . CONF_DB_PREF . 'categories
				     SET cat_tb_infos = NULL
				   WHERE thumb_id = ' . (int) self::$infos['image_id'];

		// Mise à jour du poids des catégories parentes.
		$parents_ids = alb::getParentsIds(self::$infos['cat_id'], self::$infos['cat_path']);
		$up = array(
			'images' => 0,
			'albums' => 0,
			'size' => 0,
			'hits' => 0,
			'rate' => 0,
			'comments' => 0,
			'votes' => 0
		);
		$cat_update = array('a' => $up, 'd' => $up);
		if (self::$infos['image_status'])
		{
			$cat_update['a']['size'] = $update_size;
			$a_up = '+';
			$d_up = FALSE;
		}
		else
		{
			$cat_update['d']['size'] = $update_size;
			$a_up = FALSE;
			$d_up = '+';
		}
		$sql[] = alb::updateParentsStats($cat_update, $a_up, $d_up, $parents_ids);

		// Exécution des requêtes.
		if (utils::$db->exec($sql, TRUE) === FALSE)
		{
			return FALSE;
		}

		self::$infos['image_width'] = $i['width'];
		self::$infos['image_height'] = $i['height'];

		// On recalcule les dimensions de l'aperçu.
		$preview_size = img::imageResize(self::$infos['image_width'],
			self::$infos['image_height'], 400, 400);
		self::$infos['preview_width'] = $preview_size['width'];
		self::$infos['preview_height'] = $preview_size['height'];

		return TRUE;
	}
}

/**
 * Opérations concernant l'ajout d'images par FTP.
 */
class ftp extends admin
{
	/**
	 * Identifiants des groupes à exclure pour la
	 * notification par courriel lors du prochain scan
	 * lorsque la durée limite du scan a été dépassée.
	 *
	 * @var array
	 */
	public static $notifyGroups;

	/**
	 * Message d'erreur.
	 *
	 * @var string
	 */
	public static $msgError;

	/**
	 * Message indiquant un temps de scan limite dépassé.
	 *
	 * @var string
	 */
	public static $msgTimeExceeded;

	/**
	 * Rapport complet du scan.
	 *
	 * @var array
	 */
	public static $report;



	/**
	 * Gestion du scan du répertoire des albums.
	 *
	 * @return void
	 */
	public static function scan()
	{
		if (!isset($_POST['action']) || $_POST['action'] != 'scan')
		{
			return;
		}

		// Initialisation du scan.
		$upload = new upload();
		if ($upload->getInit === FALSE)
		{
			self::$msgError = __('Une requête SQL a échouée :'
				. ' le scan ne peut se poursuivre.');
			return;
		}

		// Options de scan.
		$upload->setPublishImages = isset($_POST['publish_images']);
		$upload->setUpdateImages = (bool) utils::$config['upload_update_images'];
		$upload->setUpdateThumbId = (bool) utils::$config['upload_update_thumb_id'];
		$upload->setReportAllFiles = (bool) utils::$config['upload_report_all_files'];
		$upload->setUserId = auth::$infos['user_id'];
		if (isset($_POST['time_exceeded'])
		&& preg_match('`^\d+(\-\d+)*$`', $_POST['time_exceeded']))
		{
			$upload->setNotifyGroupsExclude = explode('-', $_POST['time_exceeded']);
		}

		// Scan du répertoire des albums.
		if ($upload->getAlbums() === FALSE)
		{
			self::$msgError = __('Une erreur s\'est produite :'
				. ' la mise à jour de la base de données a échouée.');
			return;
		}

		// Contrôle du temps d'exécution.
		if ($upload->getTimeExceeded)
		{
			self::$notifyGroups = array_merge(
				$upload->setNotifyGroupsExclude,
				(is_array($upload->getNotifyGroups))
					? $upload->getNotifyGroups
					: array()
			);
			self::$msgTimeExceeded = __('Durée limite du scan dépassée,'
				. ' cliquez à nouveau sur le bouton pour'
				. ' scanner les albums restants.');
		}

		// Rapport.
		self::$report =& $upload->getReport;
	}
}

/**
 * Logs d'activité des utilisateurs.
 */
class logs extends admin
{
	/**
	 * Liste de toutes les actions enregistrées.
	 *
	 * @var array
	 */
	public static $actions = array();

	/**
	 * Entrées récupérées.
	 *
	 * @var array
	 */
	public static $logs = array();

	/**
	 * Nombre d'entrées.
	 *
	 * @var integer
	 */
	public static $nbEntries = 0;

	/**
	 * Nombre de pages.
	 *
	 * @var integer
	 */
	public static $nbPages = 0;

	/**
	 * Champs de la table "logs" pour la recherche.
	 *
	 * @var array
	 */
	public static $searchFields = array(
		'log_ip'
	);

	/**
	 * Options de recherche.
	 *
	 * @var array
	 */
	public static $searchOptions = array(
		'action' => '[_a-z]{3,30}',
		'all_words' => 'bin',
		'log_ip' => 'bin',
		'date' => 'bin',
		'date_end_day' => '\d{2}',
		'date_end_month' => '\d{2}',
		'date_end_year' => '\d{4}',
		'date_field' => 'log_date',
		'date_start_day' => '\d{2}',
		'date_start_month' => '\d{2}',
		'date_start_year' => '\d{4}',
		'result' => '(?:accept|all|reject)'
	);

	/**
	 * Liste des utilisateurs.
	 *
	 * @var array
	 */
	public static $users = array();



	/**
	 * Actions sur la sélection d'entrées.
	 *
	 * @return void
	 */
	public static function actions()
	{
		if (($selected_ids = self::_initObjectsActions()) === FALSE)
		{
			return;
		}

		// Action à effectuer.
		switch ($_POST['action'])
		{
			// Suppression.
			case 'delete' :
				self::_delete($selected_ids);
				break;
		}
	}

	/**
	 * Récupération des logs.
	 *
	 * @return void
	 */
	public static function getLogs()
	{
		// Liste des actions.
		self::$actions = array(
			'all' => '*' . __('toutes'),
			'avatar_change' => __('changement de l\'avatar'),
			'avatar_delete' => __('suppression de l\'avatar'),
			'basket_add' => __('ajout d\'image(s) au panier'),
			'basket_empty' => __('vidage du panier'),
			'basket_remove' => __('retrait d\'image(s) du panier'),
			'comment' => __('ajout d\'un commentaire'),
			'contact' => __('envoi d\'un courriel'),
			'category_create' => __('création d\'une catégorie'),
			'category_edit' => __('édition d\'une catégorie'),
			'favorites_add' => __('ajout d\'image(s) aux favoris'),
			'favorites_remove' => __('retrait d\'image(s) des favoris'),
			'forgot' => __('demande d\'un nouveau mot de passe'),
			'image_delete' => __('suppression d\'une image'),
			'image_edit' => __('édition d\'une image'),
			'login_admin' => __('connexion à l\'administration'),
			'login_gallery' => __('connexion à la galerie'),
			'logout_admin' => __('déconnexion de l\'administration'),
			'logout_gallery' => __('déconnexion de la galerie'),
			'profile_change' => __('modification du profil'),
			'register' => __('création d\'un compte'),
			'tags_add' => __('ajout de tags'),
			'tags_remove' => __('retrait de tags'),
			'upload_images_direct' => __('envoi d\'images directement'),
			'upload_images_pending' => __('envoi d\'images en attente'),
			'vote_add' => __('ajout d\'un vote'),
			'vote_change' => __('modification d\'un vote'),
			'watermark_change' => __('changement du filigrane utilisateur')
		);

		$sw = self::_sqlWhereLogs();
		$sql_where = $sw['sql'];
		$params = $sw['params'];

		// Nombre d'éléments par page.
		$items_per_page = auth::$infos['user_prefs']['logs']['nb_per_page'];

		// Moteur de recherche.
		if (isset($_GET['search_query']))
		{
			self::$_sqlSearch = search::getSQLWhere(self::$searchFields, FALSE, TRUE);
			if (self::$_sqlSearch)
			{
				// Action.
				if (isset($_GET['search_action'])
				&& $_GET['search_action'] != 'all'
				&& isset(self::$actions[$_GET['search_action']]))
				{
					self::$_sqlSearch['sql'] .=
						' AND log_action LIKE "' . $_GET['search_action'] . '%"';
				}

				// Résultat.
				if (isset($_GET['search_result'])
				&& preg_match('`^(?:accept|reject)$`', $_GET['search_result']))
				{
					if ($_GET['search_result'] == 'accept')
					{
						self::$_sqlSearch['sql'] .=
							' AND log_action NOT LIKE "%_reject_%"
							  AND log_action NOT LIKE "%_failure"';
					}
					if ($_GET['search_result'] == 'reject')
					{
						self::$_sqlSearch['sql'] .=
							' AND (log_action LIKE "%_reject_%"
								OR log_action LIKE "%_failure")';
					}
				}

				// Utilisateur.
				if (isset($_GET['search_user'])
				&& preg_match('`^\d{1,11}$`', $_GET['search_user']))
				{
					self::$_sqlSearch['sql'] .=
						' AND l.user_id = ' . (int) $_GET['search_user'];
				}

				$sql_where .= ' AND ' . self::$_sqlSearch['sql'];
				$params = array_merge($params, self::$_sqlSearch['params']);
				self::$searchInit = TRUE;
			}
		}

		// Recherche par utilisateur.
		if (isset($_GET['user_id']))
		{
			$sql_where .= ' AND l.user_id = ' . (int) $_GET['user_id'];
		}

		// Nombre d'entrées.
		$sql = 'SELECT COUNT(*)
				  FROM ' . CONF_DB_PREF . 'users_logs AS l
				 WHERE ' . $sql_where;
		if (utils::$db->prepare($sql) === FALSE
		|| utils::$db->executeQuery($params, 'value') === FALSE)
		{
			if ($_GET['q'] != 'logs')
			{
				utils::redirect('logs');
			}
			return;
		}
		self::$nbEntries = utils::$db->queryResult;

		// Nombre de pages.
		self::$nbPages = ceil(self::$nbEntries / $items_per_page);
		$sql_limit_start = $items_per_page * ($_GET['page'] - 1);

		// Critère de tri.
		$sql_order_by = auth::$infos['user_prefs']['logs']['orderby'];
		$sql_order_by = 'LOWER(l.log_' . auth::$infos['user_prefs']['logs']['sortby'] . ') '
			. $sql_order_by . ', log_id ' . $sql_order_by;

		// Récupération des logs.
		$sql = 'SELECT l.*,
					   u.user_login,
					   u.user_avatar
				  FROM ' . CONF_DB_PREF . 'users_logs AS l
			 LEFT JOIN ' . CONF_DB_PREF . 'users AS u USING (user_id)
				 WHERE ' . $sql_where . '
			  ORDER BY ' . $sql_order_by . '
				 LIMIT ' . $sql_limit_start . ',' . $items_per_page;
		if (utils::$db->prepare($sql) === FALSE
		|| utils::$db->executeQuery($params, PDO::FETCH_ASSOC) === FALSE)
		{
			return;
		}
		if (!admin::_objectsNbResult())
		{
			return;
		}
		self::$logs = utils::$db->queryResult;
	}

	/**
	 * Récupération des utilisateurs ayant une activité.
	 *
	 * @return void
	 */
	public static function getUsers()
	{
		$sw = self::_sqlWhereLogs();
		$sql_where = $sw['sql'];
		$params = $sw['params'];
		if (self::$searchInit)
		{
			$sql_where .= ' AND ' . self::$_sqlSearch['sql'];
			$params = array_merge($params, self::$_sqlSearch['params']);
		}
		$sql = 'SELECT DISTINCT l.user_id,
					   u.user_login
				  FROM ' . CONF_DB_PREF . 'users_logs AS l
			 LEFT JOIN ' . CONF_DB_PREF . 'users AS u USING (user_id)
				 WHERE ' . $sql_where . '
			  ORDER BY u.user_login';
		$fetch_style = array('fetch' => PDO::FETCH_ASSOC, 'column' => 'user_login');
		if (utils::$db->prepare($sql) === FALSE
		|| utils::$db->executeQuery($params, $fetch_style) === FALSE
		|| utils::$db->nbResult === 0)
		{
			return;
		}
		self::$users = utils::$db->queryResult;
		foreach (self::$users as $login => $infos)
		{
			if ($infos['user_id'] == 0)
			{
				self::$users['*delete'] = $infos;
				unset(self::$users[$login]);
			}
			if ($infos['user_id'] == 2)
			{
				self::$users['*guest'] = $infos;
				unset(self::$users[$login]);
			}
		}
		ksort(self::$users);
	}



	/**
	 * Supprime des entrées.
	 *
	 * @param array $selected_ids
	 *	Identifiants des entrées sélectionnés.
	 * @return void
	 */
	private static function _delete($selected_ids)
	{
		$sql = 'DELETE
				  FROM ' . CONF_DB_PREF . 'users_logs
				 WHERE log_id IN (' . implode(', ', $selected_ids) . ')';
		if (utils::$db->exec($sql, FALSE) === FALSE)
		{
			self::report('error:' . utils::$db->msgError);
			return;
		}

		self::report('success:' . __('Les entrées sélectionnés ont été supprimés.'));
	}

	/**
	 * Récupération des utilisateurs possédant des entrées.
	 *
	 * @return string
	 */
	private static function _sqlWhereLogs()
	{
		$sql = '1=1';
		$params = array();

		// Recherche par date.
		if (isset($_GET['date']))
		{
			$sql .= ' AND log_date >= :log_date " 00:00:00"'
				  . ' AND log_date <= :log_date " 23:59:59"';
			$params['log_date'] = $_GET['date'];
		}

		// Recherche par IP.
		if (isset($_GET['ip']))
		{
			$sql .= ' AND log_ip = :ip';
			$params['ip'] = $_GET['ip'];
		}

		return array(
			'sql' => $sql,
			'params' => $params
		);
	}
}

/**
 * Outils de maintenance.
 */
class maintenance extends admin
{
	/**
	 * Nombre de statistiques corrigées.
	 *
	 * @var integer
	 */
	public static $statsReportCount = 0;

	/**
	 * Rapport de l'outil de vérification des statistiques.
	 *
	 * @var array
	 */
	public static $statsReportDetails = array();



	/**
	 * Effectue les opérations de maintenance.
	 *
	 * @return void
	 */
	public static function tools()
	{
		if (empty($_POST['tool']))
		{
			return;
		}

		switch ($_POST['tool'])
		{
			case 'db_optimize' :
				self::_dbOptimize();
				break;

			case 'db_stats' :
				self::_dbStats();
				break;

			case 'delete_tb_img' :
				self::_deleteCache(array('tb_img', 'tb_wid'));
				break;

			case 'delete_tb_cat' :
				self::_deleteCache(array('tb_cat'));
				break;

			case 'delete_im_resize' :
				self::_deleteCache(array('im_diaporama', 'im_resize'));
				break;

			case 'delete_im_watermark' :
				self::_deleteCache(array(
					'im_diaporama_watermark', 'im_watermark', 'im_resize_watermark'
				));
				break;

			case 'delete_im_edit' :
				self::_deleteCache(array('im_edit'));
				break;

			case 'delete_up_temp' :
				self::_deleteCache(array('up_temp'));
				break;

			case 'change_filemtime' :
				self::_changeCategoriesFilemtime();
				break;
		}
	}



	/**
	 * Change la date de dernière modification des répertoires de catégories.
	 *
	 * @return void
	 */
	private static function _changeCategoriesFilemtime($dir = NULL)
	{
		$sql = 'UPDATE ' . CONF_DB_PREF . 'categories
				   SET cat_filemtime = "0000-00-00 00:00:00"
				 WHERE cat_filemtime IS NOT NULL
				   AND cat_id > 1';
		if (utils::$db->exec($sql, FALSE) === FALSE)
		{
			return;
		}

		self::report('success:'
			. __('La date de dernière modification des répertoires a été changée.'));
	}

	/**
	 * Optimisation des tables de la base de données.
	 *
	 * @return void
	 */
	private static function _dbOptimize()
	{
		$tables = array(
			'cameras_brands', 'cameras_models', 'cameras_models_images',
			'categories', 'comments', 'config', 'favorites', 'groups',
			'history', 'images', 'passwords', 'search', 'sessions',
			'tags', 'tags_images', 'uploads', 'users', 'votes'
		);
		foreach ($tables as &$table)
		{
			$sql = 'OPTIMIZE TABLE ' . CONF_DB_PREF . $table;
			if (utils::$db->query($sql) === FALSE)
			{
				self::report('error:' . utils::$db->msgError);
				return;
			}
		}

		self::report('success:' . __('Les tables ont été optimisées.'));
	}

	/**
	 * Vérification des statistiques des albums.
	 *
	 * @return void
	 */
	private static function _dbStats()
	{
		if (function_exists('set_time_limit'))
		{
			set_time_limit(60);
		}

		try
		{
			// Début de la transaction.
			if (!utils::$db->transaction())
			{
				throw new Exception(utils::$db->msgError);
			}

			// Informations des images.
			if (($msg = self::_dbStatsImages()) !== TRUE)
			{
				throw new Exception($msg);
			}

			// Informations des catégories.
			if (($msg = self::_dbStatsCategories()) !== TRUE)
			{
				throw new Exception($msg);
			}

			// Exécution de la transaction.
			if (!utils::$db->commit())
			{
				throw new Exception(utils::$db->msgError);
			}

			// Vérification réussie.
			if (self::$statsReportCount)
			{
				ksort(self::$statsReportDetails);
				if (self::$statsReportCount > 1)
				{
					self::report('success:' . sprintf(__('%s valeurs étaient'
						. ' incorrectes et ont été corrigées.'),
						self::$statsReportCount));
				}
				else
				{
					self::report('success:' . __('1 valeur était'
						. ' incorrecte et a été corrigée.'));
				}
			}
			else
			{
				self::report('success:' . __('Toutes les valeurs sont correctes.'));
			}
		}
		catch (Exception $e)
		{
			self::report('error:' . $e->getMessage());
		}
	}

	/**
	 * Vérification des informations des catégories.
	 *
	 * @return boolean|string
	 */
	private static function _dbStatsCategories()
	{
		// Récupérations des statistiques de toutes les catégories.
		$sql = 'SELECT cat_id, cat_path, cat_a_size, cat_a_subalbs,
					   cat_a_subcats, cat_a_albums, cat_a_images, cat_a_hits,
					   cat_a_comments, cat_a_votes, cat_a_rate, cat_a_size,
					   cat_d_subalbs, cat_d_subcats, cat_d_albums, cat_d_images,
					   cat_d_hits, cat_d_comments, cat_d_votes, cat_d_rate,
					   cat_d_size, cat_lastadddt, cat_filemtime,
					   CASE WHEN cat_filemtime IS NULL
							THEN "category" ELSE "album"
							 END AS type
				  FROM ' . CONF_DB_PREF . 'categories
			  ORDER BY cat_path ASC';
		if (utils::$db->query($sql, PDO::FETCH_ASSOC) === FALSE)
		{
			return utils::$db->msgError;
		}
		$categories = utils::$db->queryResult;

		// Vérifications des informations pour chaque catégories.
		foreach ($categories as &$i)
		{
			$select_path = ($i['cat_id'] > 1)
				? utils::filters($i['cat_path'], 'path') . '/'
				: '';
			$images_stats = array();
			$columns = array();
			$report = array();
			$stats_integer = array('comments', 'hits', 'images', 'size', 'votes');

			// Récupération des informations des images activées.
			$sql = 'SELECT COUNT(*) AS a_images,
						   SUM(image_filesize) AS a_size,
						   SUM(image_hits) AS a_hits,
						   SUM(image_comments) AS a_comments,
						   SUM(image_votes) AS a_votes,
						   SUM(image_rate*image_votes)/SUM(image_votes) AS a_rate,
						   MAX(image_adddt) AS a_lastadddt
					  FROM ' . CONF_DB_PREF . 'images
					 WHERE image_path LIKE "' . $select_path . '%"
					   AND image_status = "1"';
			if (utils::$db->query($sql, 'row') === FALSE)
			{
				return utils::$db->msgError;
			}
			$images_stats = array_merge($images_stats, utils::$db->queryResult);

			// Récupération des informations des images désactivées.
			$sql = 'SELECT COUNT(*) AS d_images,
						   SUM(image_filesize) AS d_size,
						   SUM(image_hits) AS d_hits,
						   SUM(image_comments) AS d_comments,
						   SUM(image_votes) AS d_votes,
						   SUM(image_rate*image_votes)/SUM(image_votes) AS d_rate,
						   MAX(image_adddt) AS d_lastadddt
					  FROM ' . CONF_DB_PREF . 'images
					 WHERE image_path LIKE "' . $select_path . '%"
					   AND image_status = "0"';
			if (utils::$db->query($sql, 'row') === FALSE)
			{
				return utils::$db->msgError;
			}
			$images_stats = array_merge($images_stats, utils::$db->queryResult);

			// Seulement pour les catégories.
			if ($i['cat_filemtime'] === NULL)
			{
				$stats_integer = array_merge($stats_integer,
					array('albums', 'subalbs', 'subcats'));

				// Récupération du nombre d'albums activés.
				$sql = 'SELECT COUNT(*) AS a_albums
						  FROM ' . CONF_DB_PREF . 'categories
						 WHERE cat_path LIKE "' . $select_path . '%"
						   AND cat_filemtime IS NOT NULL
						   AND cat_status = "1"
						   AND cat_id > 1';
				if (utils::$db->query($sql, 'row') === FALSE)
				{
					return utils::$db->msgError;
				}
				$images_stats = array_merge($images_stats, utils::$db->queryResult);

				// Récupération du nombre d'albums désactivés.
				$sql = 'SELECT COUNT(*) AS d_albums
						  FROM ' . CONF_DB_PREF . 'categories
						 WHERE cat_path LIKE "' . $select_path . '%"
						   AND cat_filemtime IS NOT NULL
						   AND cat_status = "0"
						   AND cat_id > 1';
				if (utils::$db->query($sql, 'row') === FALSE)
				{
					return utils::$db->msgError;
				}
				$images_stats = array_merge($images_stats, utils::$db->queryResult);

				// Récupération du nombre de sous-albums activés.
				$sql = 'SELECT COUNT(*) AS a_subalbs
						  FROM ' . CONF_DB_PREF . 'categories
						 WHERE cat_path REGEXP "^' . $select_path . '[^/]+$"
						   AND cat_filemtime IS NOT NULL
						   AND cat_status = "1"
						   AND cat_id > 1';
				if (utils::$db->query($sql, 'row') === FALSE)
				{
					return utils::$db->msgError;
				}
				$images_stats = array_merge($images_stats, utils::$db->queryResult);

				// Récupération du nombre de sous-albums désactivés.
				$sql = 'SELECT COUNT(*) AS d_subalbs
						  FROM ' . CONF_DB_PREF . 'categories
						 WHERE cat_path REGEXP "^' . $select_path . '[^/]+$"
						   AND cat_filemtime IS NOT NULL
						   AND cat_status = "0"
						   AND cat_id > 1';
				if (utils::$db->query($sql, 'row') === FALSE)
				{
					return utils::$db->msgError;
				}
				$images_stats = array_merge($images_stats, utils::$db->queryResult);

				// Récupération du nombre de sous-catégories activées.
				$sql = 'SELECT COUNT(*) AS a_subcats
						  FROM ' . CONF_DB_PREF . 'categories
						 WHERE cat_path REGEXP "^' . $select_path . '[^/]+$"
						   AND cat_filemtime IS NULL
						   AND cat_status = "1"
						   AND cat_id > 1';
				if (utils::$db->query($sql, 'row') === FALSE)
				{
					return utils::$db->msgError;
				}
				$images_stats = array_merge($images_stats, utils::$db->queryResult);

				// Récupération du nombre de sous-catégories désactivées.
				$sql = 'SELECT COUNT(*) AS d_subcats
						  FROM ' . CONF_DB_PREF . 'categories
						 WHERE cat_path REGEXP "^' . $select_path . '[^/]+$"
						   AND cat_filemtime IS NULL
						   AND cat_status = "0"
						   AND cat_id > 1';
				if (utils::$db->query($sql, 'row') === FALSE)
				{
					return utils::$db->msgError;
				}
				$images_stats = array_merge($images_stats, utils::$db->queryResult);
			}

			// Comparaisons.
			foreach (array('a_', 'd_') as $t)
			{
				// Statistiques de type INTEGER.
				foreach ($stats_integer as $s)
				{
					$cat_stat = utils::getIntVal($i['cat_' . $t . $s]);
					$img_stat = utils::getIntVal($images_stats[$t . $s]);

					if ($cat_stat != $img_stat)
					{
						$columns[] = 'cat_' . $t . $s . ' = ' . $img_stat;
						$report['cat_' . $t . $s] = array(
							'before' => $cat_stat,
							'after' => $img_stat
						);
						self::$statsReportCount++;
					}
				}

				// Note moyenne.
				if (round((float) $i['cat_' . $t . 'rate'], 8)
				 != round((float) $images_stats[$t . 'rate'], 8))
				{
					$after = ($images_stats[$t . 'rate'] == '')
						? 0
						: $images_stats[$t . 'rate'];
					$columns[] = 'cat_' . $t . 'rate = ' . $after;
					$report['cat_' . $t . 'rate'] = array(
						'before' => (float) $i['cat_' . $t . 'rate'],
						'after' => $after
					);
					self::$statsReportCount++;
				}
			}

			// Date du dernier ajout d'une image.
			if ($images_stats['a_images'] > 0)
			{
				$lastadddt = $images_stats['a_lastadddt'];
			}
			else if ($images_stats['d_images'] > 0)
			{
				$lastadddt = $images_stats['d_lastadddt'];
			}
			else
			{
				$lastadddt = '';
			}
			if ($lastadddt != $i['cat_lastadddt'])
			{
				$columns[] = ($lastadddt == '')
					? 'cat_lastadddt = NULL'
					: 'cat_lastadddt = "' . $lastadddt . '"';
				$report['cat_lastadddt'] = array(
					'before' => ($i['cat_lastadddt'] == '')
						? 'NULL'
						: $i['cat_lastadddt'],
					'after' => ($lastadddt == '')
						? 'NULL'
						: $lastadddt
				);
				self::$statsReportCount++;
			}

			if ($columns === array())
			{
				continue;
			}

			// Mise à jour de la catégorie.
			$sql = 'UPDATE ' . CONF_DB_PREF . 'categories
					   SET ' . implode(', ', $columns) . '
					 WHERE cat_id = ' . (int) $i['cat_id'] . '
					 LIMIT 1';
			if (utils::$db->exec($sql, FALSE) === FALSE
			|| utils::$db->nbResult === 0)
			{
				return utils::$db->msgError;
			}

			self::$statsReportDetails[$i['cat_path']] = array(
				'id' => $i['cat_id'],
				'type' => $i['type'],
				'report' => $report
			);
		}

		return TRUE;
	}

	/**
	 * Vérification du nombre de commentaires, de votes
	 * et de la note moyenne sur les images.
	 *
	 * @return boolean|string
	 */
	private static function _dbStatsImages()
	{
		// Récupération du nombre de commentaires
		// dans la table des commentaires.
		$sql = 'SELECT c.image_id,
					   i.image_path,
					   COUNT(*) AS image_comments
				  FROM ' . CONF_DB_PREF . 'comments AS c
			 LEFT JOIN ' . CONF_DB_PREF . 'images AS i USING (image_id)
				 WHERE com_status = "1"
			  GROUP BY c.image_id
			  ORDER BY c.image_id ASC';
		$fetch_style = array('fetch' => PDO::FETCH_ASSOC, 'column' => 'image_id');
		if (utils::$db->query($sql, $fetch_style) === FALSE)
		{
			return utils::$db->msgError;
		}
		$comments_table = utils::$db->queryResult;

		// Récupération du nombre de votes et de la note moyenne
		// dans la table des votes.
		$sql = 'SELECT v.image_id,
					   i.image_path,
					   COUNT(*) AS image_votes,
					   SUM(vote_rate)/COUNT(*) AS image_rate
				  FROM ' . CONF_DB_PREF . 'votes AS v
			 LEFT JOIN ' . CONF_DB_PREF . 'images AS i USING (image_id)
			  GROUP BY v.image_id
			  ORDER BY v.image_id ASC';
		$fetch_style = array('fetch' => PDO::FETCH_ASSOC, 'column' => 'image_id');
		if (utils::$db->query($sql, $fetch_style) === FALSE)
		{
			return utils::$db->msgError;
		}
		$votes_table = utils::$db->queryResult;

		// Récupération du nombre de commentaires, de votes
		// et de la note moyenne dans la table des images.
		$sql = 'SELECT image_id,
					   image_path,
					   image_comments,
					   image_votes,
					   image_rate
				  FROM ' . CONF_DB_PREF . 'images
				 WHERE image_votes > 0 || image_comments > 0
			  GROUP BY image_id
			  ORDER BY image_id ASC';
		$fetch_style = array('fetch' => PDO::FETCH_ASSOC, 'column' => 'image_id');
		if (utils::$db->query($sql, $fetch_style) === FALSE)
		{
			return utils::$db->msgError;
		}
		$images_table = utils::$db->queryResult;
		foreach ($images_table as &$i)
		{
			ksort($i);
		}

		// On fusionne les tables des commentaires et celle des votes.
		$comments_votes_tables = $comments_table;
		foreach ($votes_table as $id => &$i)
		{
			$comments_votes_tables[$id]['image_id'] = $i['image_id'];
			$comments_votes_tables[$id]['image_path'] = $i['image_path'];
			$comments_votes_tables[$id]['image_votes'] = $i['image_votes'];
			$comments_votes_tables[$id]['image_rate'] = $i['image_rate'];
		}
		foreach ($comments_votes_tables as &$i)
		{
			foreach (array('image_comments', 'image_votes', 'image_rate') as $col)
			{
				if (!isset($i[$col]))
				{
					$i[$col] = 0;
				}
			}
			ksort($i);
		}
		ksort($comments_votes_tables);

		// On détermine les différences entre la table des images
		// et les tables des commentaires et des votes.
		$params = array();
		foreach ($comments_votes_tables as $id => &$i)
		{
			if (!isset($images_table[$id])
			 || $images_table[$id]['image_comments'] != $i['image_comments']
			 || $images_table[$id]['image_votes'] != $i['image_votes']
			 || round((float) $images_table[$id]['image_rate'], 8) !=
				round((float) $i['image_rate'], 8))
			{
				$params[$id] = $i;
			}
		}
		foreach ($images_table as $id => &$i)
		{
			if (!isset($comments_votes_tables[$id]))
			{
				$params[$id] = $i;
				$params[$id]['image_comments'] = 0;
				$params[$id]['image_rate'] = 0;
				$params[$id]['image_votes'] = 0;
			}
		}
		if (count($params) === 0)
		{
			return TRUE;
		}

		// Pour le rapport, on détermine quelles
		// statistiques diffèrent exactement.
		foreach ($params as $id => &$i)
		{
			$report = array();

			foreach ($i as $col => &$val)
			{
				switch ($col)
				{
					case 'image_comments' :
						$before = (isset($images_table[$id]))
							? $images_table[$id]['image_comments']
							: 0;
						if ($before != $val)
						{
							$report['image_comments'] = array(
								'before' => $before,
								'after' => $i['image_comments']
							);
							self::$statsReportCount++;
						}
						break;

					case 'image_votes' :
						$before = (isset($images_table[$id]))
							? $images_table[$id]['image_votes']
							: 0;
						if ($before != $val)
						{
							$report['image_votes'] = array(
								'before' => $before,
								'after' => $i['image_votes']
							);
							self::$statsReportCount++;
						}
						break;

					case 'image_rate' :
						$before = (isset($images_table[$id]))
							? round((float) $images_table[$id]['image_rate'], 8)
							: 0;
						if ($before != round((float) $val, 8))
						{
							$report['image_rate'] = array(
								'before' => (isset($images_table[$id]))
									? $images_table[$id]['image_rate']
									: 0,
								'after' => $i['image_rate']
							);
							self::$statsReportCount++;
						}
						break;
				}
			}

			self::$statsReportDetails[$i['image_path']] = array(
				'id' => $id,
				'type' => 'image',
				'report' => $report
			);

			unset($i['image_path']);
		}

		// On met à jour la base de données.
		sort($params);
		$sql = 'UPDATE ' . CONF_DB_PREF . 'images
				   SET image_comments = :image_comments,
					   image_votes = :image_votes,
				       image_rate = :image_rate
				 WHERE image_id = :image_id';
		$sql = array(array('sql' => $sql, 'params' => $params));
		if (utils::$db->exec($sql, FALSE) === FALSE
		|| utils::$db->nbResult == 0)
		{
			return utils::$db->msgError;
		}

		return TRUE;
	}

	/**
	 * Supprime des images ou vignettes du répertoire "cache".
	 *
	 * @return void
	 */
	private static function _deleteCache($dirs)
	{
		$ok = TRUE;

		foreach ($dirs as &$dir)
		{
			if (!files::rmdir(GALLERY_ROOT . '/cache/' . $dir, FALSE, array('.htaccess')))
			{
				$ok = FALSE;
			}
		}

		if (!$ok)
		{
			self::report('error:' . __('Certains fichiers n\'ont pas pu être supprimés.'));
			return;
		}

		self::report('success:' . __('Les fichiers ont été supprimés.'));
	}
}

/**
 * Gestion des images en attente.
 */
class pending extends albums
{
	/**
	 * Actions sur la sélection d'images en attente.
	 *
	 * @return void
	 */
	public static function actions()
	{
		if (($selected_ids = self::_initObjectsActions()) === FALSE)
		{
			return;
		}

		// Action à effectuer.
		switch ($_POST['action'])
		{
			// Déplacement et copie.
			case 'move' :
			case 'publish' :
				self::_publish($selected_ids);
				break;

			// Suppression.
			case 'delete' :
				self::_delete($selected_ids);
				break;
		}
	}

	/**
	 * Édition des images.
	 *
	 * @return void
	 */
	public static function edit()
	{
		if (!isset($_POST['save']))
		{
			return;
		}

		foreach (albums::$items as $id => &$infos)
		{
			if (empty($_POST[$id]) || !is_array($_POST[$id]))
			{
				continue;
			}

			$change = FALSE;
			$columns = array();
			$params = array();

			// Titre.
			if (($new_title = self::_editTitle($id, $infos, 'up_name')) !== FALSE)
			{
				$columns[] = 'up_name = :up_name';
				$params['up_name'] = $new_title;
				$change = TRUE;
			}

			// Description.
			if (($new_description = self::_editDescription($id, $infos, 'up_desc')) !== FALSE)
			{
				$columns[] = 'up_desc = :up_desc';
				$params['up_desc'] = $new_description;
				$change = TRUE;
			}

			if (!$change)
			{
				continue;
			}

			// On effectue la mise à jour de l'image.
			if (!empty($params))
			{
				$sql = 'UPDATE ' . CONF_DB_PREF . 'uploads
					 	   SET ' . implode(', ', $columns) . '
						 WHERE up_id = ' . (int) $id;
				if (utils::$db->prepare($sql) === FALSE
				|| utils::$db->executeExec($params) === FALSE
				|| utils::$db->nbResult === 0)
				{
					self::report('error:' . utils::$db->msgError, $id);
					continue;
				}
			}

			// Mise à jour du tableau des catégories.
			if (isset($params['up_name']))
			{
				$infos['up_name'] = $params['up_name'];
			}
			if (isset($params['up_desc']))
			{
				$infos['up_desc'] = $params['up_desc'];
			}

			self::report('success:' . __('Modifications enregistrées.'));
			self::report('success_p:' . __('Les autres modifications ont été enregistrées.'));
		}
	}

	/**
	 * Récupère les images en attente de la page courante.
	 *
	 * @return void
	 */
	public static function getImages()
	{
		$limit = ' LIMIT ' . self::$_catSqlStart . ','
			. auth::$infos['user_prefs']['pending']['nb_per_page'];

		$order_by = 'LOWER(up_' . auth::$infos['user_prefs']['pending']['sortby'] . ') '
			. auth::$infos['user_prefs']['pending']['orderby'];

		$sql = 'SELECT up.*,
					   up_height AS image_height,
					   up_width AS image_width,
					   cat.cat_name,
					   u.user_login
				  FROM ' . CONF_DB_PREF . 'uploads AS up,
					   ' . CONF_DB_PREF . 'categories AS cat,
					   ' . CONF_DB_PREF . 'users AS u
				 WHERE up.cat_id = cat.cat_id
				   AND up.user_id = u.user_id
					   ' . sql::$groupAlbumsAccess . '
			  ORDER BY ' . $order_by
						 . $limit;
		$fetch_style = array('fetch' => PDO::FETCH_ASSOC, 'column' => 'up_id');
		if (utils::$db->query($sql, $fetch_style) === FALSE)
		{
			return;
		}
		if (!admin::_objectsNbResult())
		{
			return;
		}
		self::$items = utils::$db->queryResult;
	}

	/**
	 * Récupère les informations utiles des images en attente.
	 *
	 * @return void
	 */
	public static function getInfos()
	{
		$sql = 'SELECT COUNT(*)
				  FROM ' . CONF_DB_PREF . 'uploads
			 LEFT JOIN ' . CONF_DB_PREF . 'categories USING (cat_id)
				 WHERE 1=1
				   ' . sql::$groupAlbumsAccess;
		if (utils::$db->query($sql, 'value') !== FALSE)
		{
			self::$nbItems = utils::$db->queryResult;
		}

		// Nombre d'images par page.
		self::$nbPerPage = auth::$infos['user_prefs']['pending']['nb_per_page'];
	}



	/**
	 * Supprime les images sélectionnées.
	 *
	 * @param array $selected_ids
	 *	Identifiants des images sélectionnées.
	 * @return void
	 */
	private static function _delete($selected_ids)
	{
		try
		{
			// Récupération du nom de fichier des images sélectionnées.
			$sql = 'SELECT up_id,
						   up_file
					  FROM ' . CONF_DB_PREF . 'uploads
					 WHERE up_id IN (' . implode(', ', $selected_ids) . ')';
			$fetch_style = array(
				'column' => array('up_id', 'up_file')
			);
			if (utils::$db->query($sql, $fetch_style) === FALSE)
			{
				throw new Exception(utils::$db->msgError);
			}
			$images = utils::$db->queryResult;
			if (empty($images))
			{
				return;
			}

			// On supprime les images de la table des images en attente.
			$sql = 'DELETE
					  FROM ' . CONF_DB_PREF . 'uploads
					 WHERE up_id IN (' . implode(', ', $selected_ids) . ')';
			if (utils::$db->exec($sql, FALSE) === FALSE)
			{
				throw new Exception(utils::$db->msgError);
			}

			// On supprime les images sur le disque.
			foreach ($images as &$filename)
			{
				$ext = preg_replace('`^.+(\.[^\.]+)$`', '$1', $filename);
				$filehash = utils::hashImages($filename) . $ext;
				$image_file = GALLERY_ROOT . '/users/uploads/' . $filehash;
				if (!files::unlink($image_file))
				{
					self::report(
						'error:' . __('Impossible de supprimer le fichier.'),
						$i['image_id']
					);
				}
			}

			self::report('success:' . __('Les images sélectionnées ont été supprimées.'));
		}
		catch (Exception $e)
		{
			self::report('error:' . $e->getMessage());
		}
	}

	/**
	 * Ajoute les images sélectionnées à la galerie.
	 *
	 * @param array $selected_ids
	 *	Identifiants des images sélectionnées.
	 * @return void
	 */
	private static function _publish($selected_ids)
	{
		try
		{
			// Début de la transaction.
			if (!utils::$db->transaction())
			{
				throw new Exception('error:' . utils::$db->msgError);
			}

			// Si les images doivent être déplacées dans l'album spécifié,
			// on vérifie que cet album existe bien et on attribue cet album
			// aux images en attente sélectionnées.
			if ($_POST['action'] == 'move' && !empty($_POST['destination_cat']))
			{
				$sql = 'SELECT 1
						  FROM ' . CONF_DB_PREF . 'categories
						 WHERE cat_id = ' . (int) $_POST['destination_cat'] . '
						   AND cat_filemtime IS NOT NULL';
				if (utils::$db->query($sql) === FALSE)
				{
					throw new Exception('error:' . utils::$db->msgError);
				}
				if (utils::$db->nbResult === 0)
				{
					$message = __('La destination doit être un album.');
					throw new Exception('warning:' . $message);
				}

				$sql = 'UPDATE ' . CONF_DB_PREF . 'uploads
						   SET cat_id = ' . (int) $_POST['destination_cat'] . '
						 WHERE up_id IN (' . implode(', ', $selected_ids) . ')';
				if (utils::$db->exec($sql) === FALSE)
				{
					throw new Exception('error:' . utils::$db->msgError);
				}
			}

			// Récupération des informations utiles de chaque image.
			$sql = 'SELECT up.up_id,
						   up.up_file,
						   up.up_exif,
						   up.up_iptc,
						   up.up_xmp,
						   up.up_name,
						   up.up_desc,
						   up.user_id,
						   cat_path
					  FROM ' . CONF_DB_PREF . 'uploads AS up
				 LEFT JOIN ' . CONF_DB_PREF . 'categories USING (cat_id)
					 WHERE up_id IN (' . implode(', ', $selected_ids) . ')';
			$fetch_style = array('fetch' => PDO::FETCH_ASSOC, 'column' => 'up_id');
			if (utils::$db->query($sql, $fetch_style) === FALSE)
			{
				throw new Exception('error:' . utils::$db->msgError);
			}
			$images = utils::$db->queryResult;

			// Regroupement des images par albums.
			$albums = array();
			foreach ($images as $up_id => &$i)
			{
				$albums[$i['cat_path']][] = &$i;
			}

			$pending_path = GALLERY_ROOT . '/users/uploads/';
			$albums_notify = array();
			$report_errors = array();
			$report_reject = array();
			$report_img_add = 0;

			// Lancement d'un scan pour chaque album.
			foreach ($albums as $path => $files)
			{
				$add_images = array();

				// On déplace les images du répertoire d'attente
				// vers le répertoire de l'album.
				$album_path = GALLERY_ROOT . '/' . CONF_ALBUMS_DIR . '/' . $path . '/';
				foreach ($files as &$i)
				{
					// Nom de fichier du répertoire d'attente.
					$ext = preg_replace('`^.+(\.[^\.]+)$`', '$1', $i['up_file']);
					$filehash = utils::hashImages($i['up_file']) . $ext;

					// On tente de modifier le nom du fichier si un fichier
					// de même nom existe déjà dans le répertoire de l'album.
					try
					{
						$n = 0;
						$filename = $i['up_file'];
						while (file_exists($album_path . $filename))
						{
							if ($n > 99)
							{
								throw new Exception();
							}
							$filename = preg_replace('`^(.+)\.([^\.]+)$`', '\1_' . $n . '.\2',
								$i['up_file']);
							$n++;
						}

						if (!file_exists($pending_path . $filehash)
						|| !files::renameFile($pending_path . $filehash,
						$album_path . $filename))
						{
							throw new Exception();
						}

						$add_images[$filename] = array(
							'cat_path' => $i['cat_path'],
							'user_id' => $i['user_id'],
							'image_exif' => $i['up_exif'],
							'image_iptc' => $i['up_iptc'],
							'image_xmp' => $i['up_xmp'],
							'image_desc' => $i['up_desc'],
							'image_name' => $i['up_name']
						);
					}
					catch (Exception $e)
					{
						self::report('error:' . __('Impossible de déplacer l\'image.'),
							$i['up_id']);
					}
				}

				if (empty($add_images))
				{
					continue;
				}

				// Initialisation du scan.
				$upload = new upload();
				if ($upload->getInit === FALSE)
				{
					throw new Exception('error:' . __('Une requête SQL a échouée : '
						. 'le scan ne peut se poursuivre.'));
				}

				// Options de scan.
				$upload->setHttp = TRUE;
				$upload->setHttpImages = $add_images;
				$upload->setMailAlert = FALSE;
				$upload->setReportAllFiles = FALSE;
				$upload->setTransaction = FALSE;
				$upload->setUpdateImages = FALSE;
				$upload->setUpdateThumbId = (bool) utils::$config['upload_update_thumb_id'];

				// Scan du répertoire de l'album courant.
				if ($upload->getAlbums($path) === FALSE)
				{
					throw new Exception('error:' . __('Une erreur s\'est produite : '
						. 'la mise à jour de la base de données a échouée.'));
				}

				$report_errors = array_merge($report_errors, $upload->getReport['errors']);
				$report_reject = array_merge($report_reject, $upload->getReport['img_reject']);
				$report_img_add += $upload->getReport['img_add'];

				if ($upload->getReport['img_add'] > 0)
				{
					$albums_notify[] = $path;
				}
			}

			// On supprime les images de la table des images en attente.
			$sql = 'DELETE
					  FROM ' . CONF_DB_PREF . 'uploads
					 WHERE up_id IN (' . implode(', ', $selected_ids) . ')';
			if (utils::$db->exec($sql, FALSE) === FALSE)
			{
				throw new Exception('error:' . utils::$db->msgError);
			}

			// Exécution de la transaction.
			if (!utils::$db->commit())
			{
				throw new Exception('error:' . utils::$db->msgError);
			}

			// On détermine dans quels albums des images ont été ajoutées
			// avec succès, puis on notifie par e-mail les membres autorisés
			// d'accès pour ces albums.
			$infos = array(
				'user_id' => auth::$infos['user_id'],
				'user_login' => auth::$infos['user_login']
			);
			$mail = new mail();
			$mail->notify('images', $albums_notify, 0, $infos);
			$mail->send();

			// Rapport.
			if (!empty($report_errors))
			{
				foreach ($report_errors as &$e)
				{
					self::report('error:' . $e[0] . ': ' . $e[1]);
				}
			}
			if (!empty($report_reject))
			{
				foreach ($report_reject as &$i)
				{
					self::report('warning:' . $i[0] . ': ' . $i[2]);
				}
			}
			if ($report_img_add > 0)
			{
				$message = ($report_img_add > 1)
					? __('%s images ont été ajoutées à la galerie.')
					: __('%s image a été ajoutée à la galerie.');
				self::report('success:' . sprintf($message, $report_img_add));
				self::report('success_p:' . sprintf($message, $report_img_add));
			}
		}
		catch (Exception $e)
		{
			self::report($e);
		}
	}
}

/**
 * Gestion des réglages de la galerie.
 */
class settings extends admin
{
	/**
	 * Valeurs du fichier de configuration de la page courante.
	 *
	 * @var array
	 */
	public static $configValues = array();

	/**
	 * Formats de date prédéfinis.
	 *
	 * @var array
	 */
	public static $dateFormats = array(
		'%d-%m-%y',
		'%d/%m/%y',
		'%d-%m-%Y',
		'%d/%m/%Y',
		'%y-%m-%d',
		'%y/%m/%d',
		'%Y-%m-%d',
		'%Y/%m/%d',
		'%d %b %Y',
		'%d %B %Y',
		'%B %d, %Y',
		'%a %d %b %Y',
		'%a %d %B %Y',
		'%A %d %b %Y',
		'%A %d %B %Y'
	);



	/**
	 * Gestion des listes noires.
	 *
	 * @return void
	 */
	public static function blacklists()
	{
		// Récupération des listes noires.
		$sql = 'SELECT conf_name,
					   conf_value
				  FROM ' . CONF_DB_PREF . 'config
				 WHERE conf_name = "blacklist_emails"
				    OR conf_name = "blacklist_ips"
					OR conf_name = "blacklist_names"
				    OR conf_name = "blacklist_words"';
		$fetch_style = array('column' => array('conf_name', 'conf_value'));
		if (utils::$db->query($sql, $fetch_style) === FALSE || utils::$db->nbResult === 0)
		{
			return;
		}
		$blacklists = utils::$db->queryResult;

		// Modifications des listes.
		$lists = array(
			'blacklist_emails', 'blacklist_ips', 'blacklist_names', 'blacklist_words'
		);
		foreach ($lists as $l)
		{
			utils::$config[$l] = $blacklists[$l];
			if (!isset($_POST[$l]))
			{
				continue;
			}
			$post_list = preg_split('`[\r\n]+`', $_POST[$l], -1, PREG_SPLIT_NO_EMPTY);
			foreach ($post_list as &$entry)
			{
				$entry = trim(mb_strtolower($entry));
			}
			sort($post_list);
			$_POST[$l] = implode("\n", $post_list);
			unset($post_list);
		}

		// Enregistrement si modifications.
		$fields = array(
			'text' => array(
				'blacklist_emails',
				'blacklist_ips',
				'blacklist_names',
				'blacklist_words'
			)
		);
		self::_changeDBConfig($fields);
	}

	/**
	 * Gestion des fonctionnalités.
	 *
	 * @return void
	 */
	public static function functions()
	{
		system::getPHPExtensions();

		$fields = array(
			'checkboxes' => array(
				'basket',
				'comments',
				'diaporama',
				'diaporama_auto_start',
				'diaporama_carousel',
				'diaporama_hits',
				'diaporama_resize_gd',
				'download_zip_albums',
				'exif',
				'exif_crtdt',
				'exif_camera',
				'exif_gps',
				'geoloc',
				'iptc',
				'iptc_description',
				'iptc_keywords',
				'iptc_title',
				'rss',
				'search',
				'search_advanced',
				'tags',
				'users',
				'votes',
				'watermark',
				'watermark_categories',
				'watermark_users',
				'xmp',
				'xmp_crtdt',
				'xmp_description',
				'xmp_keywords',
				'xmp_title',
				'xmp_priority'
			),
			'integer' => array(
				'basket_max_filesize',
				'basket_max_images',
				'diaporama_resize_gd_height',
				'diaporama_resize_gd_quality',
				'diaporama_resize_gd_width',
				'rss_max_items'
			),
			'lists' => array(
				'geoloc_type' => array('ROADMAP', 'TERRAIN', 'SATELLITE', 'HYBRID'),
				'rss_notify_albums' => array('0', '1')
			)
		);

		self::_changeDBConfig($fields, array(), array());
	}

	/**
	 * Options avancées.
	 *
	 * @return void
	 */
	public static function optionsAdvanced()
	{
		self::$configValues = array(
			'CONF_DEBUG' => CONF_DEBUG,
			'CONF_ERRORS_DISPLAY' => CONF_ERRORS_DISPLAY,
			'CONF_ERRORS_DISPLAY_TRACE' => CONF_ERRORS_DISPLAY_TRACE,
			'CONF_ERRORS_DISPLAY_NOW' => CONF_ERRORS_DISPLAY_NOW,
			'CONF_ERRORS_TRACE_ARGS' => CONF_ERRORS_TRACE_ARGS,
			'CONF_ERRORS_LOG' => CONF_ERRORS_LOG,
			'CONF_ERRORS_LOG_MAX' => CONF_ERRORS_LOG_MAX,
			'CONF_ERRORS_MAIL' => CONF_ERRORS_MAIL
		);

		if (empty($_POST))
		{
			return;
		}

		// Mise à jour des options.
		try
		{
			$columns = array();
			$params = array();
			$update = FALSE;

			// Fichier de configuration.
			$const = array(
				'CONF_DEBUG' => 'debug_mode',
				'CONF_ERRORS_DISPLAY' => 'display_errors',
				'CONF_ERRORS_DISPLAY_TRACE' => 'display_errors_trace',
				'CONF_ERRORS_DISPLAY_NOW' => 'display_errors_now',
				'CONF_ERRORS_TRACE_ARGS' => 'display_errors_args',
				'CONF_ERRORS_LOG' => 'logs_errors',
				'CONF_ERRORS_LOG_MAX' => 'logs_errors_max',
				'CONF_ERRORS_MAIL' => 'notify_errors'
			);
			$change = files::changeConfig($const, self::$configValues);
			if ($change === FALSE)
			{
				self::report('error:'
					. __('Impossible de modifier le fichier de configuration.'));
			}
			else if ($change === TRUE)
			{
				$update = TRUE;
			}

			// Cases à cocher et listes.
			$fields = array(
				'checkboxes' => array(
					'admin_dashboard_errors',
					'anticsrf_token_unique',
					'db_close_template',
					'exec_time',
					'debug_sql'
				),
				'integer' => array(
					'anticsrf_token_expire',
					'passwords_usleep',
					'sessions_expire',
					'users_password_minlength'
				)
			);

			self::_changeDBConfig($fields, $columns, $params);

			if ($update)
			{
				self::report('success:' . __('Modifications enregistrées.'));
				self::report('success_p:' . __('Les autres modifications ont été enregistrées.'));
			}
		}
		catch (Exception $e)
		{
			self::report($e);
		}
	}

	/**
	 * Modèles de descriptions.
	 *
	 * @return void
	 */
	public static function optionsDescriptions()
	{
		// Cases à cocher et champs textes.
		$fields = array(
			'checkboxes' => array(
				'desc_template_categories_active',
				'desc_template_images_active'
			),
			'text_locale' => array(
				'desc_template_categories_text',
				'desc_template_images_text'
			)
		);
		self::_changeDBConfig($fields, array(), array());
	}

	/**
	 * Options de la galerie.
	 *
	 * @return void
	 */
	public static function optionsGallery()
	{
		self::$configValues = array(
			'CONF_URL_REWRITE' => CONF_URL_REWRITE,
			'CONF_INTEGRATED' => CONF_INTEGRATED,
			'CONF_GD_TRANSPARENCY' => CONF_GD_TRANSPARENCY,
			'CONF_DEFAULT_LANG' => CONF_DEFAULT_LANG,
			'CONF_DEFAULT_TZ' => CONF_DEFAULT_TZ
		);

		// Paramètres de bannière.
		utils::$config['gallery_banner'] = unserialize(utils::$config['gallery_banner']);

		if (empty($_POST))
		{
			return;
		}

		// Mise à jour des options.
		try
		{
			$update = FALSE;
			$columns = array();
			$params = array();

			// Bannière.
			$gallery_banner = utils::$config['gallery_banner'];
			if (isset($_POST['gallery_banner']) && !$gallery_banner['banner'])
			{
				$gallery_banner['banner'] = 1;
			}
			elseif (!isset($_POST['gallery_banner']) && $gallery_banner['banner'])
			{
				$gallery_banner['banner'] = 0;
			}
			if (isset($_POST['gallery_banner_file'])
			&& $gallery_banner['banner']
			&& $_POST['gallery_banner_file'] != $gallery_banner['src']
			&& preg_match('`^[-a-z0-9_]{1,64}\.(?:gif|jpg|png)$`i',
			   $_POST['gallery_banner_file']))
			{
				$i = getimagesize(GALLERY_ROOT . '/images/banners/'
					. $_POST['gallery_banner_file']);
				if ($i !== FALSE && in_array($i[2], array(1, 2, 3)) !== FALSE)
				{
					$gallery_banner['src'] = $_POST['gallery_banner_file'];
					$gallery_banner['width'] = $i[0];
					$gallery_banner['height'] = $i[1];
				}
			}
			if ($gallery_banner != utils::$config['gallery_banner'])
			{
				$columns[] = '"gallery_banner" THEN :gallery_banner';
				$params['gallery_banner'] = serialize($gallery_banner);
				utils::$config['gallery_banner'] = $gallery_banner;
			}

			// Installation/désinstallation des langues.
			$locale_langs = utils::$config['locale_langs'];
			if (isset($_POST['uninstall_langs']) && isset($_POST['uninstall_langs_select'])
			&& is_array($_POST['uninstall_langs_select']))
			{
				foreach ($_POST['uninstall_langs_select'] as &$lang)
				{
					if (!isset($locale_langs[$lang]))
					{
						continue;
					}

					// On interdit de désinstaller la langue par défaut.
					if ($lang == CONF_DEFAULT_LANG)
					{
						continue;
					}

					unset($locale_langs[$lang]);
				}
			}
			if (isset($_POST['install_langs']) && isset($_POST['install_langs_select'])
			&& is_array($_POST['install_langs_select']))
			{
				$locale_path = GALLERY_ROOT . '/locale';
				foreach ($_POST['install_langs_select'] as &$lang)
				{
					$lang_file = $locale_path . '/' . $lang . '/lang.php';
					if (isset($locale_langs[$lang]) || !file_exists($lang_file))
					{
						continue;
					}
					include($locale_path . '/' . $lang . '/lang.php');
					if (isset($name))
					{
						$locale_langs[$lang] = $name;
					}
				}
			}
			asort($locale_langs);
			if ($locale_langs !== utils::$config['locale_langs'])
			{
				$columns[] = '"locale_langs" THEN :locale_langs';
				$params['locale_langs'] = serialize($locale_langs);
				utils::$config['locale_langs'] = $locale_langs;
			}

			// Fichier de configuration.
			$const = array(
				'CONF_URL_REWRITE' => 'url_rewriting',
				'CONF_INTEGRATED' => 'gallery_integrated',
				'CONF_GD_TRANSPARENCY' => 'gd_transparency',
				'CONF_DEFAULT_LANG' => 'lang_default',
				'CONF_DEFAULT_TZ' => 'tz_default'
			);
			$change = files::changeConfig($const, self::$configValues);
			if ($change === FALSE)
			{
				self::report('error:'
					. __('Impossible de modifier le fichier de configuration.'));
			}
			else if ($change === TRUE)
			{
				$update = TRUE;
			}

			// Modifications des listes.
			self::_configList(array(
				'nohits_useragent_list'
			));

			// Cases à cocher et champs textes.
			$fields = array(
				'checkboxes' => array(
					'gallery_closure',
					'html_filter',
					'lang_client',
					'lang_switch',
					'nohits_useragent',
					'recaptcha_comments',
					'recaptcha_comments_guest_only',
					'recaptcha_contact',
					'recaptcha_inscriptions',
					'upload_report_all_files',
					'upload_update_images',
					'upload_update_thumb_id'
				),
				'text' => array(
					'level_separator',
					'nohits_useragent_list',
					'recaptcha_private_key',
					'recaptcha_public_key'
				),
				'text_locale' => array(
					'gallery_closure_message',
					'gallery_description',
					'gallery_description_guest',
					'gallery_footer_message',
					'gallery_title'
				),
				'lists' => array(
					'nav_bar' => array('bottom', 'top', 'top_bottom')
				)
			);
			self::_changeDBConfig($fields, $columns, $params);

			if ($update)
			{
				self::report('success:' . __('Modifications enregistrées.'));
				self::report('success_p:' . __('Les autres modifications ont été enregistrées.'));
			}
		}
		catch (Exception $e)
		{
			self::report($e);
		}
	}

	/**
	 * Options des images.
	 *
	 * @return void
	 */
	public static function optionsImages()
	{
		// Qualité des images.
		foreach (array('images_resize_gd_quality', 'images_orientation_quality') as $o)
		{
			if (isset($_POST[$o]))
			{
				$_POST[$o] = (int) $_POST[$o];
				if ($_POST[$o] > 100)
				{
					unset($_POST[$o]);
				}
			}
		}

		// Cases à cocher et champs textes.
		$fields = array(
			'checkboxes' => array(
				'images_anti_copy',
				'images_direct_link',
				'images_orientation',
				'images_resize',
				'recent_images',
				'recent_images_nb'
			),
			'integer' => array(
				'images_orientation_quality',
				'images_resize_gd_height',
				'images_resize_gd_quality',
				'images_resize_gd_width',
				'images_resize_html_height',
				'images_resize_html_width',
				'recent_images_time'
			),
			'lists' => array(
				'images_resize_method' => array('1', '2')
			)
		);
		self::_changeDBConfig($fields, array(), array());
	}

	/**
	 * Options de courriel.
	 *
	 * @return void
	 */
	public static function optionsEmail()
	{
		if (empty($_POST))
		{
			return;
		}

		$fields = array
		(
			// Cases à cocher.
			'checkboxes' => array(
				'mail_auto_bcc'
			),

			// Champs textes.
			'text' => array(
				'mail_auto_sender_address',
				'mail_notify_comment_message',
				'mail_notify_comment_subject',
				'mail_notify_comment_auth_message',
				'mail_notify_comment_auth_subject',
				'mail_notify_comment_pending_subject',
				'mail_notify_comment_pending_message',
				'mail_notify_comment_pending_auth_subject',
				'mail_notify_comment_pending_auth_message',
				'mail_notify_comment_follow_subject',
				'mail_notify_comment_follow_message',
				'mail_notify_comment_follow_auth_subject',
				'mail_notify_comment_follow_auth_message',
				'mail_notify_guestbook_message',
				'mail_notify_guestbook_subject',
				'mail_notify_guestbook_auth_message',
				'mail_notify_guestbook_auth_subject',
				'mail_notify_guestbook_pending_subject',
				'mail_notify_guestbook_pending_message',
				'mail_notify_guestbook_pending_auth_subject',
				'mail_notify_guestbook_pending_auth_message',
				'mail_notify_images_message',
				'mail_notify_images_subject',
				'mail_notify_images_pending_subject',
				'mail_notify_images_pending_message',
				'mail_notify_inscription_message',
				'mail_notify_inscription_subject',
				'mail_notify_inscription_pending_subject',
				'mail_notify_inscription_pending_message'
			)
		);

		// Champs textes ne pouvant être vide.
		foreach ($fields['text'] as &$f)
		{
			if (isset($_POST[$f]) && utils::isEmpty($_POST[$f]))
			{
				unset($_POST[$f]);
			}
		}

		// Champs textes pouvant être vide.
		$fields['text'][] = 'mail_auto_primary_recipient_address';

		// Vérification du format de l'adresse de l'expéditeur.
		if (isset($_POST['mail_auto_sender_address']))
		{
			$regex = '`^' . utils::regexpEmail() . '$`i';
			if (!preg_match($regex, $_POST['mail_auto_sender_address']))
			{
				self::report('warning:' . __('Format de l\'adresse de courriel incorrect.'));
				unset($_POST['mail_auto_sender_address']);
			}
		}

		// Mise à jour des options.
		self::_changeDBConfig($fields, array(), array());
	}

	/**
	 * Options des vignettes.
	 *
	 * @return void
	 */
	public static function optionsThumbs()
	{
		self::$configValues = array(
			'CONF_THUMBS_CAT_METHOD' => CONF_THUMBS_CAT_METHOD,
			'CONF_THUMBS_CAT_WIDTH' => CONF_THUMBS_CAT_WIDTH,
			'CONF_THUMBS_CAT_HEIGHT' => CONF_THUMBS_CAT_HEIGHT,
			'CONF_THUMBS_CAT_SIZE' => CONF_THUMBS_CAT_SIZE,
			'CONF_THUMBS_CAT_QUALITY' => CONF_THUMBS_CAT_QUALITY,
			'CONF_THUMBS_IMG_METHOD' => CONF_THUMBS_IMG_METHOD,
			'CONF_THUMBS_IMG_WIDTH' => CONF_THUMBS_IMG_WIDTH,
			'CONF_THUMBS_IMG_HEIGHT' => CONF_THUMBS_IMG_HEIGHT,
			'CONF_THUMBS_IMG_SIZE' => CONF_THUMBS_IMG_SIZE,
			'CONF_THUMBS_IMG_QUALITY' => CONF_THUMBS_IMG_QUALITY,
			'CONF_THUMBS_PROTECT' => CONF_THUMBS_PROTECT
		);

		if (empty($_POST))
		{
			return;
		}

		// Mise à jour des options.
		try
		{
			$columns = array();
			$params = array();
			$update = FALSE;

			// Fichier de configuration.
			$const = array(
				'CONF_THUMBS_CAT_METHOD' => 'thumbs_cat_method',
				'CONF_THUMBS_CAT_WIDTH' => 'thumbs_cat_width',
				'CONF_THUMBS_CAT_HEIGHT' => 'thumbs_cat_height',
				'CONF_THUMBS_CAT_SIZE' => 'thumbs_cat_size',
				'CONF_THUMBS_CAT_QUALITY' => 'thumbs_cat_quality',
				'CONF_THUMBS_IMG_METHOD' => 'thumbs_img_method',
				'CONF_THUMBS_IMG_WIDTH' => 'thumbs_img_width',
				'CONF_THUMBS_IMG_HEIGHT' => 'thumbs_img_height',
				'CONF_THUMBS_IMG_SIZE' => 'thumbs_img_size',
				'CONF_THUMBS_IMG_QUALITY' => 'thumbs_img_quality',
				'CONF_THUMBS_PROTECT' => 'thumbs_protect'
			);
			$change = files::changeConfig($const, self::$configValues);
			if ($change === FALSE)
			{
				self::report('error:'
					. __('Impossible de modifier le fichier de configuration.'));
			}
			else if ($change === TRUE)
			{
				$update = TRUE;

				// Protection des vignettes aux accès direct.
				$thumbs_cache_dir = array('tb_cat', 'tb_img', 'tb_wid');
				if (isset($_POST['thumbs_protect']) && !CONF_THUMBS_PROTECT)
				{
					// Création des fichiers .htaccess.
					foreach ($thumbs_cache_dir as $dir)
					{
						$htaccess_file = GALLERY_ROOT . '/cache/' . $dir . '/.htaccess';
						files::filePutContents($htaccess_file, 'Deny from all');
					}
				}
				else if (!isset($_POST['thumbs_protect']) && CONF_THUMBS_PROTECT)
				{
					// Suppression des fichiers .htaccess.
					foreach ($thumbs_cache_dir as $dir)
					{
						$htaccess_file = GALLERY_ROOT . '/cache/' . $dir . '/.htaccess';
						if (file_exists($htaccess_file))
						{
							files::unlink($htaccess_file);
						}
					}
				}
			}

			// Nombre de vignettes par page.
			$options = array(
				'thumbs_alb_nb',
				'thumbs_cat_nb'
			);
			foreach ($options as &$o)
			{
				if (isset($_POST[$o]) && !preg_match('`^[1-9]\d{0,3}$`', $_POST[$o]))
				{
					unset($_POST[$o]);
				}
			}

			// Distinction catégories/albums.
			$_POST['sql_categories_order_by_type'] = '';
			if (isset($_POST['thumbs_cat_type']))
			{
				switch ($_POST['thumbs_cat_type'])
				{
					case 'albums' :
						$_POST['sql_categories_order_by_type'] = 'type DESC,';
						break;

					case 'categories' :
						$_POST['sql_categories_order_by_type'] = 'type ASC,';
						break;
				}
			}

			// Critères de tri.
			$orderby = array(
				'cat' => array(
					'cat_position', 'cat_path', 'cat_name',
					'cat_crtdt', 'cat_lastadddt', 'cat_a_size',
					'cat_a_images'
				),
				'img' => array(
					'image_position', 'image_name', 'image_path', 'image_size',
					'image_filesize', 'image_hits', 'image_comments', 'image_votes',
					'image_rate', 'image_adddt', 'image_crtdt'
				)
			);
			foreach ($orderby as $t => &$options)
			{
				$sql_type = ($t == 'cat') ? 'categories' : 'images';
				$_POST['sql_' . $sql_type . '_order_by'] = '';
				$p = utils::$config['sql_' . $sql_type . '_order_by'];
				for ($i = 1; $i < 4; $i++)
				{
					if (isset($_POST['thumbs_' . $t . '_order_by_' . $i])
					&& isset($_POST['thumbs_' . $t . '_ascdesc_' . $i])
					&& in_array($_POST['thumbs_' . $t . '_order_by_' . $i], $options)
					&& ($_POST['thumbs_' . $t . '_ascdesc_' . $i] == 'ASC'
					 || $_POST['thumbs_' . $t . '_ascdesc_' . $i] == 'DESC'))
					{
						$p1 = '$1'; $p2 = '$2'; $p3 = '$3';
						${'p' . $i} = $_POST['thumbs_' . $t . '_order_by_' . $i]
							. ' ' . $_POST['thumbs_' . $t . '_ascdesc_' . $i];
						$p = preg_replace(
							'`^([^,]+),([^,]+),([^,]+),$`i',
							sprintf('%s,%s,%s,', $p1, $p2, $p3),
							$p
						);
					}
				}
				$_POST['sql_' . $sql_type . '_order_by'] = $p;
			}

			// Cases à cocher et listes.
			$fields = array(
				'checkboxes' => array(
					'thumbs_stats_albums',
					'thumbs_stats_category_title',
					'thumbs_stats_comments',
					'thumbs_stats_date',
					'thumbs_stats_filesize',
					'thumbs_stats_hits',
					'thumbs_stats_images',
					'thumbs_stats_image_title',
					'thumbs_stats_size',
					'thumbs_stats_votes'
				),
				'integer' => array(
					'thumbs_alb_nb',
					'thumbs_cat_nb'
				),
				'lists' => array(
					'thumbs_cat_extended' => array('0', '1')
				),
				'text' => array(
					'sql_categories_order_by',
					'sql_categories_order_by_type',
					'sql_images_order_by'
				)
			);

			self::_changeDBConfig($fields, $columns, $params);

			if ($update)
			{
				self::report('success:' . __('Modifications enregistrées.'));
				self::report('success_p:' . __('Les autres modifications ont été enregistrées.'));
			}
		}
		catch (Exception $e)
		{
			self::report($e);
		}
	}
}

/**
 * Gestion des tags.
 */
class tags extends admin
{
	/**
	 * Liste des tags récupérés.
	 *
	 * @var array
	 */
	public static $items;

	/**
	 * Nombre de tags.
	 *
	 * @var integer
	 */
	public static $nbItems;

	/**
	 * Nombre de pages.
	 *
	 * @var integer
	 */
	public static $nbPages;

	/**
	 * Champs de la table "tags" pour la recherche.
	 *
	 * @var array
	 */
	public static $searchFields = array(
		'tag_name',
		'tag_url'
	);

	/**
	 * Options de recherche.
	 *
	 * @var array
	 */
	public static $searchOptions = array(
		'all_words' => 'bin',
		'tag_name' => 'bin',
		'tag_nb_images' => 'bin',
		'tag_nb_images_max' => '\d{1,6}',
		'tag_nb_images_min' => '\d{1,6}',
		'tag_url' => 'bin'
	);



	/**
	 * Actions sur la sélection de tags.
	 *
	 * @return void
	 */
	public static function actions()
	{
		if (($selected_ids = self::_initObjectsActions()) === FALSE)
		{
			return;
		}

		// Action à effectuer.
		switch ($_POST['action'])
		{
			// Suppression.
			case 'delete' :
				self::_delete($selected_ids);
				break;
		}
	}

	/**
	 * Édition des tags.
	 *
	 * @return void
	 */
	public static function edit()
	{
		if (!isset($_POST['save']))
		{
			return;
		}

		$tags_delete = FALSE;

		foreach (self::$items as $id => &$infos)
		{
			if (empty($_POST[$id]) || !is_array($_POST[$id]))
			{
				continue;
			}

			$columns = array();
			$params = array();

			// Nom du tags.
			if (($new_name = self::_editName($id, $infos, 'name')) !== FALSE)
			{
				$columns[] = 'tag_name = :tag_name';
				$params['tag_name'] = $new_name;
			}

			// Nom d'URL.
			if (($new_url = self::_editName($id, $infos, 'url')) !== FALSE)
			{
				$columns[] = 'tag_url = :tag_url';
				$params['tag_url'] = $new_url;
			}

			if ($columns === array())
			{
				continue;
			}

			// On effectue la mise à jour du tag.
			if (!empty($params))
			{
				$sql = 'UPDATE IGNORE ' . CONF_DB_PREF . 'tags
					 	   SET ' . implode(', ', $columns) . '
						 WHERE tag_id = ' . (int) $id;
				if (utils::$db->prepare($sql) === FALSE
				|| utils::$db->executeExec($params) === FALSE)
				{
					self::report('error:' . utils::$db->msgError, $id);
					continue;
				}

				// Si aucune ligne affectée et nom du tag courant modifié,
				// c'est qu'il existe déjà un tag du même nom.
				// Donc, on fusionne les deux tags.
				if (utils::$db->nbResult === 0 && $new_name !== FALSE)
				{
					// On récupère l'identifiant du tag existant.
					$sql = 'SELECT tag_id
							  FROM ' . CONF_DB_PREF . 'tags
							 WHERE tag_name = :tag_name';
					$params = array(
						'tag_name' => $new_name
					);
					if (utils::$db->prepare($sql) === FALSE
					|| utils::$db->executeQuery($params, 'value') === FALSE
					|| utils::$db->nbResult === 0)
					{
						self::report('error:' . utils::$db->msgError, $id);
						continue;
					}

					$sql = array();

					// On associe ce tag aux images du tag courant.
					$sql[] = 'UPDATE IGNORE ' . CONF_DB_PREF . 'tags_images
								 SET tag_id = ' . (int) utils::$db->queryResult . '
							   WHERE tag_id = ' . (int) $id;

					// On supprime le tag courant.
					$sql[] = 'DELETE
								FROM ' . CONF_DB_PREF . 'tags
							   WHERE tag_id = ' . (int) $id;

					// Exécution de la transaction.
					if (utils::$db->exec($sql, TRUE) === FALSE)
					{
						self::report('error:' . utils::$db->msgError, $id);
						continue;
					}

					$tags_delete = TRUE;
				}
			}

			// Mise à jour du tableau des tags.
			foreach (array('name', 'url') as $p)
			{
				if (isset($params['tag_' . $p]))
				{
					$infos['tag_' . $p] = $params['tag_' . $p];
				}
			}

			self::report('success:' . __('Modifications enregistrées.'));
			self::report('success_p:' . __('Les autres modifications ont été enregistrées.'));
		}

		// Si des tags ont été supprimés par fusion,
		// on récupère à nouveau les tags de la page courante.
		if ($tags_delete)
		{
			tags::getTags();
		}
	}

	/**
	 * Récupère tous les tags de la galerie.
	 *
	 * @param string $select
	 *	Clause SELECT de la requête SQL.
	 * @return void
	 */
	public static function getAlltags($select = '*')
	{
		$sql = 'SELECT ' . $select . '
				  FROM ' . CONF_DB_PREF . 'tags
			  ORDER BY LOWER(tag_name) ASC';
		$fetch_style = array('fetch' => PDO::FETCH_ASSOC, 'column' => 'tag_id');
		if (utils::$db->query($sql, $fetch_style) === FALSE)
		{
			return;
		}
		self::$items = utils::$db->queryResult;
	}

	/**
	 * Récupère les tags de la page courante de la section "tags".
	 *
	 * @return void
	 */
	public static function getTags()
	{
		$sql_where = '1=1';
		$params = array();

		// Sous-requête permettant de récupérer
		// le nombre d'images liées à chaque tag.
		$sql_tag_nb_images =
			'SELECT COUNT(*)
			   FROM ' . CONF_DB_PREF . 'tags_images AS ti
			  WHERE ti.tag_id = t.tag_id';

		// Moteur de recherche.
		if (isset($_GET['search_query']))
		{
			self::$_sqlSearch = search::getSQLWhere(self::$searchFields, FALSE, TRUE);
			if (self::$_sqlSearch)
			{
				// Nombre d'images liées.
				if (isset($_GET['search_tag_nb_images'])
				 && isset($_GET['search_tag_nb_images_max'])
				 && isset($_GET['search_tag_nb_images_min']))
				{
					$sql_where .=
						' AND (' . $sql_tag_nb_images . ') >= '
							. (int) $_GET['search_tag_nb_images_min'] . '
						  AND (' . $sql_tag_nb_images . ') <= '
							. (int) $_GET['search_tag_nb_images_max'];
				}

				$sql_where .= ' AND ' . self::$_sqlSearch['sql'];
				$params = array_merge($params, self::$_sqlSearch['params']);
				self::$searchInit = TRUE;
			}
		}

		// Détermine le nombre de tags.
		$sql = 'SELECT COUNT(*)
				  FROM ' . CONF_DB_PREF . 'tags AS t
				 WHERE ' . $sql_where;
		if (utils::$db->prepare($sql) === FALSE
		|| utils::$db->executeQuery($params, 'value') === FALSE)
		{
			return;
		}
		self::$nbItems = (int) utils::$db->queryResult;

		// Nombre de pages.
		$tags_per_page = auth::$infos['user_prefs']['tags']['nb_per_page'];
		self::$nbPages = ceil(self::$nbItems / $tags_per_page);
		$sql_limit_start = $tags_per_page * ($_GET['page'] - 1);

		// Critère de tri.
		$sql_order_by = (auth::$infos['user_prefs']['tags']['sortby'] == 'nb_images')
			? 'tag_' . auth::$infos['user_prefs']['tags']['sortby'] . ' '
			: 'LOWER(tag_' . auth::$infos['user_prefs']['tags']['sortby'] . ') ';
		$sql_order_by .= auth::$infos['user_prefs']['tags']['orderby']
			. ', tag_id ' . auth::$infos['user_prefs']['tags']['orderby'];

		// Récupération des tags.
		$sql = 'SELECT *,
					   (' . $sql_tag_nb_images . ') AS tag_nb_images
				  FROM ' . CONF_DB_PREF . 'tags AS t
				 WHERE ' . $sql_where . '
			  ORDER BY ' . $sql_order_by . '
				 LIMIT ' . $sql_limit_start . ',' . $tags_per_page;
		$fetch_style = array('fetch' => PDO::FETCH_ASSOC, 'column' => 'tag_id');
		if (utils::$db->prepare($sql) === FALSE
		|| utils::$db->executeQuery($params, $fetch_style) === FALSE)
		{
			return;
		}
		if (!admin::_objectsNbResult())
		{
			return;
		}
		self::$items = utils::$db->queryResult;
	}

	/**
	 * Enregistre les nouveaux tags.
	 *
	 * @return void
	 */
	public static function newTags()
	{
		if (empty($_POST['new_tags']) || !is_array($_POST['new_tags']))
		{
			return;
		}

		$sql = array(
			'params' => array(),
			'sql' => 'INSERT IGNORE INTO ' . CONF_DB_PREF . 'tags
								(tag_name, tag_url)
						 VALUES (:tag_name, :tag_url)'
		);
		foreach ($_POST['new_tags'] as &$tag_name)
		{
			$tag_name = str_replace(',', '', $tag_name);

			if (utils::isEmpty($tag_name))
			{
				continue;
			}

			$sql['params'][] = array(
				'tag_name' => $tag_name,
				'tag_url' => utils::genURLName($tag_name)
			);
		}

		if (count($sql['params']) < 1)
		{
			return;
		}

		if (utils::$db->exec(array($sql)) === FALSE)
		{
			self::report('error:' . utils::$db->msgError);
			return;
		}

		$nb_tags = array_sum(utils::$db->nbResult[0]);

		if ($nb_tags === 0)
		{
			self::report('warning:' . __('Aucun tag a été ajouté.'));
			return;
		}

		self::report('success:' . (($nb_tags > 1)
			? sprintf(__('%s tags ont été ajoutés.'), $nb_tags)
			: __('1 tag a été ajouté.')));
	}



	/**
	 * Supprime des tags.
	 *
	 * @param array $selected_ids
	 *	Identifiants des tags sélectionnés.
	 * @return void
	 */
	private static function _delete($selected_ids)
	{
		$sql = 'DELETE
				  FROM ' . CONF_DB_PREF . 'tags
				 WHERE tag_id IN (' . implode(', ', $selected_ids) . ')';
		if (utils::$db->exec($sql, FALSE) === FALSE)
		{
			self::report('error:' . utils::$db->msgError);
			return;
		}

		self::report('success:' . __('Les tags sélectionnés ont été supprimés.'));
	}

	/**
	 * Édition du nom et du nom d'URL.
	 *
	 * @param integer $id
	 *	Identifiant du tag.
	 * @param array $infos
	 *	Informations du tag.
	 * @return string|boolean
	 */
	protected static function _editName($id, &$infos, $column_name)
	{
		if (!isset($_POST[$id][$column_name])
		|| ($new_name = str_replace(',', '', $_POST[$id][$column_name]))
		=== $infos['tag_' . $column_name])
		{
			return FALSE;
		}

		// Vérification de la longueur.
		if (mb_strlen($new_name) < 1)
		{
			return FALSE;
		}

		return $new_name;
	}
}

/**
 * Gestion des utilisateurs.
 */
class users extends admin
{
	/**
	 * Permission d'accès aux catégories protégées.
	 *
	 * @var array
	 */
	public static $catAccess;

	/**
	 * Liste des groupes pour la gestion des utilisateurs.
	 *
	 * @var array
	 */
	public static $groups;

	/**
	 * Informations utiles des éléments de la page courante.
	 *
	 * @var array
	 */
	public static $items;

	/**
	 * Nombre d'utilisateurs avec le filtre courant.
	 *
	 * @var integer
	 */
	public static $nbItems;

	/**
	 * Nombre de pages.
	 *
	 * @var integer
	 */
	public static $nbPages;

	/**
	 * Page parente où se situe l'utilisateur courant.
	 *
	 * @var integer
	 */
	public static $parentPage;

	/**
	 * Informations utiles de la section courante.
	 *
	 * @var array
	 */
	public static $infos;

	/**
	 * Champs de la table "users" pour la recherche.
	 *
	 * @var array
	 */
	public static $searchFields = array(
		'user_login',
		'user_email',
		'user_website',
		'user_crtip',
		'user_lasvstip'
	);

	/**
	 * Options de recherche.
	 *
	 * @var array
	 */
	public static $searchOptions = array(
		'all_words' => 'bin',
		'date' => 'bin',
		'date_field' => '(?:user_crtdt|user_lastvstdt)',
		'date_end_day' => '\d{2}',
		'date_end_month' => '\d{2}',
		'date_end_year' => '\d{4}',
		'date_start_day' => '\d{2}',
		'date_start_month' => '\d{2}',
		'date_start_year' => '\d{4}',
		'status' => '(?:all|activate|deactivate|pending)',
		'user_login' => 'bin',
		'user_email' => 'bin',
		'user_website' => 'bin',
		'user_crtip' => 'bin',
		'user_lasvstip' => 'bin'
	);

	/**
	 * Informations utiles de tous les utilisateurs.
	 *
	 * @var array
	 */
	public static $usersList;



	/**
	 * Modification de l'avatar.
	 *
	 * @return void
	 */
	public static function avatar()
	{
		if (empty($_POST['action']))
		{
			return;
		}

		// Modification du profil d'un admin impossible
		// si l'utilisateur n'est pas super-admin.
		if (auth::$infos['user_id'] != 1 && self::$infos['group_admin'] == 1
		 && auth::$infos['user_id'] != self::$infos['user_id'])
		{
			utils::redirect('users', TRUE);
			return;
		}

		switch ($_POST['action'])
		{
			case 'new' :
				self::_avatarChange();
				break;

			case 'delete' :
				self::_avatarDelete();
				break;
		}
	}

	/**
	 * Modification ou création d'un groupe.
	 *
	 * @return void
	 */
	public static function changeGroup()
	{
		// Message de confirmation pour la création d'un nouveau groupe.
		if (isset($_GET['confirm']) && $_GET['confirm'] == 'new')
		{
			self::report('success:' . sprintf(__('Le groupe %s a été créé.'),
				self::$infos['group_name']));
		}

		if (empty($_POST))
		{
			return;
		}

		try
		{
			$columns = array();
			$params = array();

			// Permissions.
			$current_perms = self::$infos['group_perms'];
			$types = array('admin', 'gallery');
			if (auth::$infos['user_id'] != 1
			|| ($_GET['section'] == 'group' && $_GET['object_id'] == 2))
			{
				unset($types['admin']);
			}
			if ($_GET['section'] == 'group' && $_GET['object_id'] == 1)
			{
				$types = array();
			}
			$group_2 = array('alert_email', 'upload', 'upload_mode',
				'create_albums', 'upload_create_owner');
			foreach ($types as $type)
			{
				if (!empty($_POST[$type]))
				{
					foreach ($current_perms[$type]['perms'] as $name => $value)
					{
						if ($_GET['section'] == 'group' && $_GET['object_id'] == 2
						&& in_array($name, $group_2))
						{
							continue;
						}

						if (isset($_POST[$type]['perms'][$name]) && $value == 0)
						{
							$current_perms[$type]['perms'][$name] = 1;
						}
						elseif (!isset($_POST[$type]['perms'][$name]) && $value == 1)
						{
							$current_perms[$type]['perms'][$name] = 0;
						}
					}

					// Mode d'envoi des commentaires.
					if (isset($current_perms[$type]['perms']['add_comments_mode'])
					&& isset($_POST[$type]['perms']['add_comments_mode']))
					{
						$add_comments_mode = (int) $_POST[$type]['perms']['add_comments_mode'];
						if ($add_comments_mode
						!= $current_perms[$type]['perms']['add_comments_mode'])
						{
							$current_perms[$type]['perms']['add_comments_mode']
								= $add_comments_mode;
						}
					}

					// Mode d'envoi des images.
					if (isset($current_perms[$type]['perms']['upload_mode'])
					&& isset($_POST[$type]['perms']['upload_mode']))
					{
						$upload_mode = (int) $_POST[$type]['perms']['upload_mode'];
						if ($upload_mode != $current_perms[$type]['perms']['upload_mode'])
						{
							$current_perms[$type]['perms']['upload_mode'] = $upload_mode;
						}
					}

					// Mode d'accès aux albums protégés.
					if (isset($_POST[$type]['access_mode']))
					{
						$access_mode = (int) $_POST[$type]['access_mode'];
						if ($access_mode != $current_perms[$type]['perms']['access_mode'])
						{
							$current_perms[$type]['perms']['access_mode'] = $access_mode;
						}
					}

					// Albums à dévérouiller.
					if (isset($_POST[$type]['albums_forbidden'])
					&& is_array($_POST[$type]['albums_forbidden'])
					&& isset($_POST[$type]['allow']))
					{
						foreach ($_POST[$type]['albums_forbidden'] as &$id)
						{
							if (!isset($current_perms[$type]['albums_access'][$id]))
							{
								$current_perms[$type]['albums_access'][$id] = 1;
							}
						}
					}

					// Albums à vérouiller.
					if (isset($_POST[$type]['albums_allowed'])
					&& is_array($_POST[$type]['albums_allowed'])
					&& isset($_POST[$type]['forbid']))
					{
						foreach ($_POST[$type]['albums_allowed'] as &$id)
						{
							if (isset($current_perms[$type]['albums_access'][$id]))
							{
								unset($current_perms[$type]['albums_access'][$id]);
							}
						}
					}
				}
			}
			if ($current_perms != self::$infos['group_perms']
			|| $_GET['section'] == 'new-group')
			{
				$columns[] = 'group_perms = :group_perms';
				$params['group_perms'] = serialize($current_perms);
				self::$infos['group_perms'] = $current_perms;
			}

			// Nom du groupe.
			if ($_GET['section'] == 'new-group' || (isset($_POST['name'])))
			{
				$locale_text = utils::setLocaleText($_POST['name'],
					self::$infos['group_name'], 64, TRUE);
				if ($locale_text['empty'])
				{
					if (in_array(self::$infos['group_id'], array(1, 2, 3)))
					{
						if (self::$infos['group_name'] != '')
						{
							$columns[] = 'group_name = :group_name';
							$params['group_name'] = '';
						}
					}
					else
					{
						throw new Exception('warning:'
							. __('Le nom de groupe doit contenir au moins 1 caractère.'));
					}
				}
				else if ($locale_text['change'])
				{
					$columns[] = 'group_name = :group_name';
					$params['group_name'] = $locale_text['data'];
				}
			}

			// Titre.
			if ($_GET['section'] == 'new-group' || (isset($_POST['title'])))
			{
				$locale_text = utils::setLocaleText($_POST['title'],
					self::$infos['group_title'], 64, TRUE);
				if ($locale_text['empty'])
				{
					if (in_array(self::$infos['group_id'], array(1, 2, 3)))
					{
						if (self::$infos['group_title'] != '')
						{
							$columns[] = 'group_title = :group_title';
							$params['group_title'] = '';
						}
					}
					else
					{
						throw new Exception('warning:'
							. __('Le titre doit contenir au moins 1 caractère.'));
					}
				}
				else if ($locale_text['change'])
				{
					$columns[] = 'group_title = :group_title';
					$params['group_title'] = $locale_text['data'];
				}
			}

			// Description.
			if (isset($_POST['desc']))
			{
				$locale_text = utils::setLocaleText($_POST['desc'], self::$infos['group_desc']);
				if ($locale_text['change'])
				{
					$columns[] = 'group_desc = :group_desc';
					$params['group_desc'] = $locale_text['data'];
				}
			}

			// Aucun changement.
			if (empty($params))
			{
				return;
			}

			// Droits admin ?
			if ($_GET['section'] == 'new-group'
			|| ($_GET['object_id'] != 1 && $_GET['object_id'] != 2))
			{
				$columns[] = 'group_admin = :group_admin';
				$params['group_admin'] =
					(int) self::isAdminPerms($current_perms['admin']['perms']);
			}

			// On effectue la mise à jour du groupe.
			if ($_GET['section'] == 'group')
			{
				$sql = 'UPDATE ' . CONF_DB_PREF . 'groups
						   SET ' . implode(', ', $columns) . '
						 WHERE group_id = ' . (int) self::$infos['group_id'];
			}
			else
			{
				$params['group_crtdt'] = date('Y-m-d H:i:s');
				$columns = array_keys($params);
				$sql = 'INSERT INTO ' . CONF_DB_PREF . 'groups
									(' . implode(', ', $columns) . ')
							 VALUES (:' . implode(', :', $columns) . ')';
			}
			if (utils::$db->prepare($sql) === FALSE
			|| utils::$db->executeExec($params) === FALSE)
			{
				throw new Exception('error:' . utils::$db->msgError);
			}

			// Mise à jour du tableau d'informations.
			foreach ($params as $k => $v)
			{
				if ($k != 'group_perms')
				{
					self::$infos[$k] = $v;
				}
			}

			// Confirmation.
			if ($_GET['section'] == 'group')
			{
				self::report('success:' . __('Modifications enregistrées.'));
				return;
			}

			utils::redirect('group/' . utils::$db->connexion->lastInsertId() . '/new', TRUE);
		}
		catch (Exception $e)
		{
			self::report($e);
		}
	}

	/**
	 * Modification ou création d'un profil utilisateur.
	 *
	 * @return void
	 */
	public static function changeProfile()
	{
		// Message de confirmation pour la création d'un nouvel utilisateur.
		if (isset($_GET['confirm']) && $_GET['confirm'] == 'new')
		{
			self::report('success:' . sprintf(__('L\'utilisateur %s a été créé.'),
				self::$infos['user_login']));
		}

		if (empty($_POST))
		{
			return;
		}

		// Modification du profil d'un admin impossible
		// si l'utilisateur n'est pas super-admin.
		if (auth::$infos['user_id'] != 1 && self::$infos['group_admin'] == 1
		 && auth::$infos['user_id'] != self::$infos['user_id'])
		{
			return;
		}

		$success_message = __('Modifications enregistrées.');
		$columns = array();
		$params = array();

		// Modification du compte.
		if ($_GET['section'] == 'new-user'
		   || (auth::$infos['user_id'] != self::$infos['user_id']
		      && (auth::$infos['user_id'] == 1
		         || auth::$infos['user_id'] != 1 && self::$infos['group_admin'] != 1)))
		{
			// Changement du statut.
			if (isset($_POST['status']) && $_POST['status'] != self::$infos['user_status'])
			{
				switch ($_POST['status'])
				{
					// Suspension du compte.
					case '0' :
						if ($_GET['section'] == 'new-user')
						{
							$columns[] = 'user_status = :user_status';
							$params['user_status'] = '0';
						}
						else if (self::_activate(array(self::$infos['user_id']), 'deactivate',
						$success_message))
						{
							self::$infos['user_status'] = '0';
							self::report('success:' . __('Modifications enregistrées.'));
						}
						break;

					// Activation du compte.
					case '1' :
						if ($_GET['section'] == 'new-user')
						{
							$columns[] = 'user_status = :user_status';
							$params['user_status'] = '1';
						}
						else if (self::_activate(array(self::$infos['user_id']), 'activate',
						$success_message))
						{
							self::$infos['user_status'] = '1';
							self::report('success:' . __('Modifications enregistrées.'));
						}
						break;
				}
			}

			// Changement du groupe.
			if (isset($_POST['group']) && $_POST['group'] != self::$infos['group_id'])
			{
				if ($_GET['section'] == 'new-user')
				{
					$columns[] = 'group_id = :group_id';
					$params['group_id'] = $_POST['group'];
				}
				else if (self::_changeUsersGroup(array(self::$infos['user_id'])))
				{
					self::$infos['group_id'] = $_POST['group'];
					self::$infos['group_admin'] = self::$items[$_POST['group']]['group_admin'];
					self::$infos['group_name'] = self::$items[$_POST['group']]['group_name'];
					self::$infos['group_title'] = self::$items[$_POST['group']]['group_title'];
				}
			}
		}

		// Nom d'utilisateur.
		if ($_GET['section'] == 'new-user' ||
		(isset($_POST['login']) && $_POST['login'] != self::$infos['user_login']))
		{
			$user_id = ($_GET['section'] == 'new-user')
				? 0
				: self::$infos['user_id'];
			if (($control = user::checkUserLogin($_POST['login'], $user_id)) === TRUE)
			{
				$columns[] = 'user_login = :user_login';
				$params['user_login'] = $_POST['login'];
			}
			else if (substr($control, 0, 6) == 'error:')
			{
				self::report($control);
			}
			else
			{
				self::report('warning:' . $control);
			}
		}

		// Mot de passe.
		if ($_GET['section'] == 'new-user' || !empty($_POST['pwd']))
		{
			// Vérification de la confirmation du mot de passe.
			if ($_POST['pwd_confirm'] != $_POST['pwd'])
			{
				self::report('warning:'
					. __('Les mots de passe ne correspondent pas.'));
			}

			// Vérification du mot de passe.
			else if (($control = user::checkForm('pwd', $_POST['pwd'])) === TRUE)
			{
				$columns[] = 'user_password = :user_password';
				$crtdt = ($_GET['section'] == 'new-user')
					? date('Y-m-d H:i:s', self::$time)
					: self::$infos['user_crtdt'];
				$params['user_password'] = utils::hashPassword($_POST['pwd'], $crtdt);
			}
			else
			{
				self::report('warning:' . $control);
			}
		}

		// Sexe.
		if (isset($_POST['sex'])
		&& ($_POST['sex'] == '?' || $_POST['sex'] == 'F' || $_POST['sex'] == 'M'))
		{
			$_POST['sex'] = ($_POST['sex'] == '?') ? NULL : $_POST['sex'];
			if ($_POST['sex'] != self::$infos['user_sex'])
			{
				$columns[] = 'user_sex = :user_sex';
				$params['user_sex'] = $_POST['sex'];
			}
		}

		// Nom.
		if (isset($_POST['name']) && $_POST['name'] != self::$infos['user_name'])
		{
			$columns[] = 'user_name = :user_name';
			$params['user_name'] = utils::isEmpty($_POST['name'])
				? NULL
				: $_POST['name'];
		}

		// Prénom.
		if (isset($_POST['firstname'])
		&& $_POST['firstname'] != self::$infos['user_firstname'])
		{
			$columns[] = 'user_firstname = :user_firstname';
			$params['user_firstname'] = utils::isEmpty($_POST['firstname'])
				? NULL
				: $_POST['firstname'];
		}

		// Date de naissance.
		if (isset($_POST['day']) && isset($_POST['month']) && isset($_POST['year']))
		{
			$birthdate = $_POST['year'] . '-' . $_POST['month'] . '-' . $_POST['day'];
			$regexp = '`^(?:(?!0000)\d{4}-(?!00)\d{2}-(?!00)\d{2}|0000-00-00)$`';
			if (preg_match($regexp, $birthdate))
			{
				$birthdate = ($birthdate == '0000-00-00') ? NULL : $birthdate;
				if ($birthdate != self::$infos['user_birthdate'])
				{
					$columns[] = 'user_birthdate = :user_birthdate';
					$params['user_birthdate'] = $birthdate;
				}
			}
		}

		// Localisation.
		if (isset($_POST['loc']) && $_POST['loc'] != self::$infos['user_loc'])
		{
			$columns[] = 'user_loc = :user_loc';
			$params['user_loc'] = ($_POST['loc'] == '') ? NULL : $_POST['loc'];
		}

		// Courriel.
		if (isset($_POST['email']) && $_POST['email'] != self::$infos['user_email'])
		{
			$user_id = ($_GET['section'] == 'new-user')
				? 0
				: self::$infos['user_id'];

			if ($_POST['email'] == ''
			|| ($control = user::checkForm('email', $_POST['email'], $user_id)) === TRUE)
			{
				$columns[] = 'user_email = :user_email';
				$params['user_email'] = utils::isEmpty($_POST['email'])
					? NULL
					: $_POST['email'];
			}
			else
			{
				self::report('warning:' . $control);
			}
		}

		// Site Web.
		if (isset($_POST['website']) && $_POST['website'] != self::$infos['user_website'])
		{
			if ($_POST['website'] == ''
			|| ($control = user::checkForm('website', $_POST['website'])) === TRUE)
			{
				$columns[] = 'user_website = :user_website';
				$params['user_website'] = utils::isEmpty($_POST['website'])
					? NULL
					: $_POST['website'];
			}
			else
			{
				self::report('warning:' . $control);
			}
		}

		// Description.
		if (isset($_POST['desc'])
		&& $_POST['desc'] != self::$infos['user_desc'])
		{
			if ($_POST['desc'] == ''
			|| ($control = user::checkForm('desc', $_POST['desc'])) === TRUE)
			{
				$columns[] = 'user_desc = :user_desc';
				$params['user_desc'] = utils::isEmpty($_POST['desc'])
					? NULL
					: $_POST['desc'];
			}
			else
			{
				self::report('warning:' . $control);
			}
		}

		// Langue.
		if (isset($_POST['lang'])
		&& ($_GET['section'] == 'new-user' || $_POST['lang'] != self::$infos['user_lang'])
		&& user::checkForm('lang', $_POST['lang']))
		{
			$columns[] = 'user_lang = :user_lang';
			$params['user_lang'] = $_POST['lang'];
		}

		// Fuseau horaire.
		if (isset($_POST['tz'])
		&& ($_GET['section'] == 'new-user' || $_POST['tz'] != self::$infos['user_tz'])
		&& user::checkForm('tz', $_POST['tz']))
		{
			$columns[] = 'user_tz = :user_tz';
			$params['user_tz'] = $_POST['tz'];
		}

		// Notifications par courriel.
		$user_alert = self::$infos['user_alert'];
		for ($i = 0; $i < 6; $i++)
		{
			if (isset($_POST['alert'][$i])
			&& self::$infos['user_alert'][$i] == 0)
			{
				$user_alert[$i] = '1';
			}
			else if (!isset($_POST['alert'][$i])
			&& self::$infos['user_alert'][$i] == 1)
			{
				$user_alert[$i] = '0';
			}
		}
		if ($user_alert != self::$infos['user_alert'])
		{
			$columns[] = 'user_alert = :user_alert';
			$params['user_alert'] = $user_alert;
		}

		// Ne pas comptabiliser les visites.
		if ($_GET['section'] == 'user' && users::$infos['group_admin'])
		{
			if (isset($_POST['nohits']) && self::$infos['user_nohits'] == 0)
			{
				$columns[] = 'user_nohits = "1"';
			}
			else if (!isset($_POST['nohits']) && self::$infos['user_nohits'] == 1)
			{
				$columns[] = 'user_nohits = "0"';
			}
		}

		// Informations personnalisées.
		$user_other = self::$infos['user_other'];
		foreach (utils::$config['users_profile_infos']['perso'] as $id => $p)
		{
			if (isset($_POST[$id]))
			{
				if (!isset($user_other[$id]) || $_POST[$id] != $user_other[$id])
				{
					$user_other[$id] = $_POST[$id];
				}
			}
		}
		reset(utils::$config['users_profile_infos']['perso']);
		if ($user_other != self::$infos['user_other'])
		{
			$columns[] = 'user_other = :user_other';
			$params['user_other'] = serialize($user_other);
		}

		// Erreurs ou avertissements avec la création d'un nouvel utilisateur.
		if ($_GET['section'] == 'new-user'
		&& (!empty(self::$report['error']) || !empty(self::$report['warning'])))
		{
			return;
		}

		// Aucun changement.
		if (empty($columns))
		{
			return;
		}

		// On effectue la création ou la mise à jour de l'utilisateur.
		if ($_GET['section'] == 'new-user')
		{
			$params['user_crtdt'] = date('Y-m-d H:i:s', time());
			$params['user_crtip'] = $_SERVER['REMOTE_ADDR'];
			$params['user_lastvstip'] = '';
			$columns = array_keys($params);
			$sql = 'INSERT INTO ' . CONF_DB_PREF . 'users
								(' . implode(', ', $columns) . ')
						 VALUES (:' . implode(', :', $columns) . ')';
		}
		else
		{
			$sql = 'UPDATE ' . CONF_DB_PREF . 'users
					   SET ' . implode(', ', $columns) . '
					 WHERE user_id = ' . (int) self::$infos['user_id'] . '
					   AND group_id != 2
					 LIMIT 1';
		}
		if (utils::$db->prepare($sql) === FALSE
		|| utils::$db->executeExec($params) === FALSE)
		{
			self::report('error:' . utils::$db->msgError);
			return;
		}

		// Mise à jour du tableau d'informations.
		foreach ($params as $k => $v)
		{
			self::$infos[$k] = $v;
		}
		self::$infos['user_other'] = $user_other;

		// Sauvegarde de la langue dans un cookie.
		if ($_GET['section'] == 'user' && isset($params['user_lang'])
		&& self::$infos['user_id'] == auth::$infos['user_id'])
		{
			utils::$cookiePrefs->add('lang', $_POST['lang']);
		}

		// Confirmation.
		if ($_GET['section'] == 'user')
		{
			self::report('success:' . $success_message);
			self::report('success_p:' . __('Les autres modifications ont été enregistrées.'));
			return;
		}

		utils::redirect('user/' . utils::$db->connexion->lastInsertId() . '/new', TRUE);
	}

	/**
	 * Supprime un utilisateur.
	 *
	 * @return void
	 */
	public static function deleteUser()
	{
		if (!isset($_POST['delete']))
		{
			return;
		}

		// Vérifications des permissions.
		if (auth::$infos['user_id'] == self::$infos['user_id']
		|| self::$infos['user_id'] == 1
		|| (auth::$infos['user_id'] != 1 && self::$infos['group_admin'] == 1))
		{
			return;
		}

		// Si la suppression s'est bien déroulée,
		// on redirige vers la page des utilisateurs.
		if (self::_deleteUsers(array(self::$infos['user_id'])) === TRUE)
		{
			utils::redirect('users');
		}
	}

	/**
	 * Récupération des catégories protégées.
	 *
	 * @return void
	 */
	public static function getCatProtect()
	{
		$sql = 'SELECT cat_id,
					   cat_path,
					   cat_name
				  FROM ' . CONF_DB_PREF . 'categories
				 WHERE cat_password LIKE CONCAT(cat_id, ":%")';
		$fetch_style = array('fetch' => PDO::FETCH_ASSOC, 'column' => 'cat_id');
		if (utils::$db->query($sql, $fetch_style) === FALSE)
		{
			utils::redirect('users');
			return;
		}
		$cat_protect = utils::$db->queryResult;

		self::$catAccess = array(
			'admin' => array(
				'allowed' => array(),
				'forbidden' => array()
			),
			'gallery' => array(
				'allowed' => array(),
				'forbidden' => array()
			)
		);
		if (empty($cat_protect))
		{
			return;
		}

		// Autorisation d'accès aux albums protégés.
		foreach (array('admin', 'gallery') as $type)
		{
			foreach ($cat_protect as $id => &$infos)
			{
				// On remplace le nom du répertoire par les noms des
				// catégories composant le chemin.
				if ($type == 'admin')
				{
					$path =& $infos['cat_path'];
					$parent = dirname($path);
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
						$sql = 'SELECT cat_id,
									   cat_name
								  FROM ' . CONF_DB_PREF . 'categories
								 WHERE ' . $sql . '
								 ORDER BY LENGTH(cat_path) ASC
								 LIMIT 120';
						$fetch_style = array('column' => array('cat_id', 'cat_name'));
						if (utils::$db->prepare($sql) === FALSE
						|| utils::$db->executeQuery($params, $fetch_style) === FALSE
						|| utils::$db->nbResult === 0)
						{
							utils::redirect('users');
							return;
						}
						$path = implode(' / ', utils::$db->queryResult)
							. ' / ' . $infos['cat_name'];
					}
					else
					{
						$path = $infos['cat_name'];
					}
				}

				$auth = isset(users::$infos['group_perms'][$type]['albums_access'][$id])
					? 'allowed'
					: 'forbidden';
				self::$catAccess[$type][$auth][$id] = $infos['cat_path'];
			}
		}
	}

	/**
	 * Récupération des informations du groupe courant.
	 *
	 * @return void
	 */
	public static function getGroup()
	{
		$sql = 'SELECT *
				  FROM ' . CONF_DB_PREF . 'groups
				 WHERE group_id = ' . (int) $_GET['object_id'];
		if (utils::$db->query($sql, 'row') === FALSE
		|| utils::$db->nbResult !== 1)
		{
			utils::redirect('groups');
			return;
		}
		self::$infos = utils::$db->queryResult;
		self::$infos['group_perms'] = unserialize(self::$infos['group_perms']);
	}

	/**
	 * Récupération des informations utiles des groupes.
	 *
	 * @return void
	 */
	public static function getGroups()
	{
		$sql = 'SELECT group_id,
					   group_name,
					   group_title,
					   group_desc,
					   group_admin,
					   group_crtdt,
					   (SELECT COUNT(*)
					      FROM ' . CONF_DB_PREF . 'users
						 WHERE group_id = g.group_id
						   AND user_status != "-2") AS nb_users
				  FROM ' . CONF_DB_PREF . 'groups AS g
			  ORDER BY group_id ASC, group_name ASC';
		$fetch_style = array('fetch' => PDO::FETCH_ASSOC, 'column' => 'group_id');
		if (utils::$db->query($sql, $fetch_style) === FALSE
		|| utils::$db->nbResult === 0)
		{
			utils::redirect('users');
			return;
		}
		self::$items = utils::$db->queryResult;
	}

	/**
	 * Récupération des informations utiles de l'utilisateur.
	 *
	 * @return void
	 */
	public static function getUser()
	{
		$sql = 'SELECT u.*,
					   g.group_id,
					   g.group_name,
					   g.group_title,
					   g.group_admin,
					   g.group_perms,
					   ' . self::_sqlUserStats() . '
				  FROM ' . CONF_DB_PREF . 'users AS u
			 LEFT JOIN ' . CONF_DB_PREF . 'groups AS g USING (group_id)
				 WHERE user_id = ' . (int) $_GET['object_id'] . '
				   AND g.group_id != 2
				   AND user_status != "-2"';
		if (utils::$db->query($sql, 'row') === FALSE
		|| utils::$db->nbResult !== 1)
		{
			utils::redirect('users');
			return;
		}
		self::$infos = utils::$db->queryResult;
		self::$infos['user_other'] = (empty(self::$infos['user_other']))
			? array()
			: unserialize(self::$infos['user_other']);
		self::$infos['group_perms'] = unserialize(self::$infos['group_perms']);
	}

	/**
	 * Récupération des informations utiles des utilisateurs de la galerie.
	 *
	 * @return void
	 */
	public static function getUsers()
	{
		$users_per_page = auth::$infos['user_prefs']['users']['nb_per_page'];

		// Clause WHERE.
		$sql_where = self::_sqlWhereUsers();

		// Groupe.
		$sql_where .= (isset($_GET['group_id']))
			? ' AND u.group_id = ' . (int) $_GET['group_id']
			: '';

		$params = array();

		// Moteur de recherche.
		if (isset($_GET['search_query']))
		{
			self::$_sqlSearch = search::getSQLWhere(self::$searchFields, FALSE, TRUE);
			if (self::$_sqlSearch)
			{
				// Statut.
				if (isset($_GET['search_status']))
				{
					switch ($_GET['search_status'])
					{
						case 'activate' :
							self::$_sqlSearch['sql'] .= ' AND user_status = "1"';
							break;

						case 'deactivate' :
							self::$_sqlSearch['sql'] .= ' AND user_status = "0"';
							break;

						case 'pending' :
							self::$_sqlSearch['sql'] .= ' AND user_status = "-1"';
							break;
					}
				}

				$sql_where .= ' AND ' . self::$_sqlSearch['sql'];
				$params = array_merge($params, self::$_sqlSearch['params']);
				self::$searchInit = TRUE;
			}
		}

		// Nombre d'utilisateurs.
		$sql = 'SELECT COUNT(*)
				  FROM ' . CONF_DB_PREF . 'users AS u
				 WHERE ' . $sql_where;
		if (utils::$db->prepare($sql) === FALSE
		|| utils::$db->executeQuery($params, 'value') === FALSE)
		{
			return;
		}
		self::$nbItems = utils::$db->queryResult;

		// Nombre de pages.
		self::$nbPages = ceil(self::$nbItems / $users_per_page);
		$sql_limit_start = $users_per_page * ($_GET['page'] - 1);

		// Critère de tri.
		$sql_order_by = auth::$infos['user_prefs']['users']['orderby'];
		$sql_order_by = 'LOWER(user_' . auth::$infos['user_prefs']['users']['sortby'] . ') '
			. $sql_order_by . ', user_id ' . $sql_order_by;

		// Récupération des utilisateurs.
		$sql = 'SELECT user_id,
					   user_login,
					   user_name,
					   user_firstname,
					   user_sex,
					   user_birthdate,
					   user_email,
					   user_website,
					   user_loc,
					   user_desc,
					   user_other,
					   user_avatar,
					   user_status,
					   user_crtdt,
					   user_crtip,
					   user_lastvstdt,
					   user_lastvstip,
					   g.group_id,
					   g.group_name,
					   g.group_title,
					   g.group_admin,
					   ' . self::_sqlUserStats() . '
				  FROM ' . CONF_DB_PREF . 'users AS u
			 LEFT JOIN ' . CONF_DB_PREF . 'groups AS g USING (group_id)
				 WHERE ' . $sql_where . '
			  ORDER BY ' . $sql_order_by . '
			     LIMIT ' . $sql_limit_start . ',' . $users_per_page;
		$fetch_style = array('fetch' => PDO::FETCH_ASSOC, 'column' => 'user_id');
		if (utils::$db->prepare($sql) === FALSE
		|| utils::$db->executeQuery($params, $fetch_style) === FALSE)
		{
			return;
		}
		if (!admin::_objectsNbResult())
		{
			return;
		}
		self::$items = utils::$db->queryResult;

		// Informations de profil.
		foreach (self::$items as $id => &$infos)
		{
			$infos['user_other'] = unserialize($infos['user_other']);
		}
	}

	/**
	 * Récupération des groupes pour la gestion des utilisateurs.
	 *
	 * @return void
	 */
	public static function getUsersGroups()
	{
		$params = array();
		$sql_search = '';

		// Recherche.
		if (self::$_sqlSearch)
		{
			$params = self::$_sqlSearch['params'];
			$sql_search = ' AND ' . self::$_sqlSearch['sql'];
		}

		// Récupération des groupes.
		$sql = 'SELECT group_id,
					   group_id AS g_id,
					   group_name,
					   group_admin,
					   (SELECT COUNT(*)
						  FROM ' . CONF_DB_PREF . 'users AS u,
							   ' . CONF_DB_PREF . 'groups AS g
						 WHERE ' . self::_sqlWhereUsers() . '
						   AND g.group_id = u.group_id
						   AND g.group_id = g_id'
						         . $sql_search . ') AS nb_users
				  FROM ' . CONF_DB_PREF . 'groups
				 WHERE group_id != 2
			  ORDER BY LOWER(group_name) ASC';
		$fetch_style = array('fetch' => PDO::FETCH_ASSOC, 'column' => 'group_id');
		if (utils::$db->prepare($sql) === FALSE
		|| utils::$db->executeQuery($params, $fetch_style) === FALSE
		|| utils::$db->nbResult === 0)
		{
			if ($_GET['page'] > 1)
			{
				utils::redirect('users', TRUE);
			}
			return;
		}
		self::$groups = utils::$db->queryResult;
	}

	/**
	 * Récupère les informations utiles pour
	 * générer une liste de tous les utilisateurs.
	 *
	 * @return void
	 */
	public static function getUsersList()
	{
		$sql = 'SELECT user_id,
					   user_login
				  FROM ' . CONF_DB_PREF . 'users
				 WHERE user_id != 2
				   AND (user_status = "1" OR user_status = "0")
			  ORDER BY LOWER(user_login) ASC';
		$fetch_style = array('column' => array('user_id', 'user_login'));
		if (utils::$db->query($sql, $fetch_style) !== FALSE
		|| utils::$db->nbResult > 0)
		{
			self::$usersList = utils::$db->queryResult;
		}
	}

	/**
	 * Actions sur les groupes.
	 *
	 * @return void
	 */
	public static function groupActions()
	{
		if (!isset($_POST['selection']) || empty($_POST['action'])
		|| empty($_POST['select']) || !is_array($_POST['select'])
		|| auth::$infos['user_id'] != 1)
		{
			return;
		}

		// Identifiants des groupes sélectionnés.
		$selected_ids = array_map('intval', array_keys($_POST['select']));
		if (!isset($selected_ids[0]))
		{
			return;
		}

		// Opérations impossible sur les trois premiers groupes.
		if (in_array(1, $selected_ids)
		 || in_array(2, $selected_ids)
		 || in_array(3, $selected_ids))
		{
			return;
		}

		self::$report = array('error' => array(), 'warning' => array());

		// Action à effectuer.
		switch ($_POST['action'])
		{
			// Suppression.
			case 'delete' :
				self::_deleteGroups($selected_ids);
				break;
		}
	}

	/**
	 * Permet de savoir si un groupe possède des droits
	 * d'administration en comparant avec les permissions par défaut.
	 *
	 * @param array $user_perms
	 *	Permissions admin de l'utilisateur.
	 * @return boolean
	 */
	public static function isAdminPerms($group_perms)
	{
		foreach (utils::$config['admin_group_perms_default']['admin']['perms'] as $p => $v)
		{
			if ($p == 'access_mode')
			{
				continue;
			}
			if ($group_perms[$p] == 1)
			{
				return TRUE;
			}
		}

		return FALSE;
	}

	/**
	 * Création d'un nouveau groupe.
	 *
	 * @return void
	 */
	public static function newGroup()
	{
		self::$infos = array(
			'group_id' => 0,
			'group_name' => '',
			'group_title' => '',
			'group_desc' => '',
			'group_perms' => utils::$config['admin_group_perms_default'],
			'group_admin' => 0
		);

		users::changeGroup();
	}

	/**
	 * Création d'un nouvel utilisateur.
	 *
	 * @return void
	 */
	public static function newUser()
	{
		self::$infos = array(
			'group_id' => 3,
			'group_admin' => 0,
			'user_id' => 0,
			'user_login' => '',
			'user_name' => '',
			'user_firstname' => '',
			'user_sex' => '',
			'user_birthdate' => '',
			'user_loc' => '',
			'user_desc' => '',
			'user_email' => '',
			'user_website' => '',
			'user_other' => array(),
			'user_watermark' => '',
			'user_avatar' => '',
			'user_alert' => '000000',
			'user_lang' => CONF_DEFAULT_LANG,
			'user_tz' => CONF_DEFAULT_TZ,
			'user_status' => '1'
		);

		// Les nouveaux utilisateurs possédent les permissions du groupe 3.
		$sql = 'SELECT group_perms
				  FROM ' . CONF_DB_PREF . 'groups
				 WHERE group_id = 3';
		if (utils::$db->query($sql, 'value') === FALSE
		|| utils::$db->nbResult === 0)
		{
			utils::redirect('users');
		}
		self::$infos['group_perms'] = unserialize(utils::$db->queryResult);

		users::changeProfile();
	}

	/**
	 * Options utilisateurs.
	 *
	 * @return void
	 */
	public static function options()
	{
		if (empty($_POST))
		{
			return;
		}

		$columns = array();
		$params = array();

		// Mot de passe.
		$p = 'users_inscription_password';
		if ((!isset($_POST[$p]) || $_POST[$p] == '**********') === FALSE
		&& (utils::$config[$p] === '' && $_POST[$p] == '') === FALSE)
		{
			$columns[] = '"' . $p . '" THEN :' . $p . '';
			$params[$p] = ($_POST[$p] == '')
				? ''
				: utils::hashPassword($_POST[$p], 'validate');
		}

		$profile_infos = utils::$config['users_profile_infos'];

		// Informations de profil.
		foreach ($profile_infos['infos'] as $id => &$i)
		{
			if (!isset($_POST['infos']))
			{
				break;
			}
			if (!isset($_POST['infos'][$id]))
			{
				continue;
			}

			// Activation.
			if (isset($_POST['infos'][$id]['activate']) && $i['activate'] == 0)
			{
				$i['activate'] = 1;
			}
			elseif (!isset($_POST['infos'][$id]['activate']) && $i['activate'] == 1)
			{
				$i['activate'] = 0;
			}

			// Obligation.
			if (isset($_POST['infos'][$id]['required']) && $i['required'] == 0)
			{
				$i['required'] = 1;
			}
			elseif (!isset($_POST['infos'][$id]['required']) && $i['required'] == 1)
			{
				$i['required'] = 0;
			}
		}

		// Informations de profil personnalisées.
		if (isset($_POST['perso']))
		{
			foreach ($_POST['perso'] as $id => &$i)
			{
				// Quelques vérifications.
				if (!preg_match('`^perso_(new_)?\d+$`', $id)
				|| !isset($_POST['perso'][$id]['name']))
				{
					continue;
				}

				// L'information existe déjà.
				if (isset($profile_infos['perso'][$id]))
				{
					// Faut-il la supprimer ?
					if (isset($_POST['perso'][$id]['delete']))
					{
						unset($profile_infos['perso'][$id]);
						continue;
					}

					// Nom.
					$locale_text = utils::setLocaleText($_POST['perso'][$id]['name'],
						$profile_infos['perso'][$id]['name'], 64, TRUE);
					if ($locale_text['empty'])
					{
						continue;
					}

					// On modifie les paramètres.
					$profile_infos['perso'][$id]['activate']
						= isset($_POST['perso'][$id]['activate']) ? 1 : 0;
					$profile_infos['perso'][$id]['required']
						= isset($_POST['perso'][$id]['required']) ? 1 : 0;
					continue;
				}

				$name = '';
				$locale_text = utils::setLocaleText($_POST['perso'][$id]['name'], $name, 64);
				if (!$locale_text['change'])
				{
					continue;
				}

				// On ajoute la nouvelle information.
				$profile_infos['counter']++;
				$profile_infos['perso']['perso_' . $profile_infos['counter']] = array(
					'name' => $name,
					'activate' => isset($_POST['perso'][$id]['activate']) ? 1 : 0,
					'required' => isset($_POST['perso'][$id]['required']) ? 1 : 0,
				);
			}
		}

		if ($profile_infos != utils::$config['users_profile_infos'])
		{
			$columns[] = '"users_profile_infos" THEN :users_profile_infos';
			$params['users_profile_infos'] = serialize($profile_infos);
		}

		// Le titre de la catégorie créée automatiquement lors
		// de l'inscription d'un utilisateur doit contenir la variable {USER_LOGIN}.
		if (isset($_POST['users_inscription_autocat_title'])
		&& is_array($_POST['users_inscription_autocat_title']))
		{
			foreach (utils::$config['locale_langs'] as $code => $lang)
			{
				if (!preg_match('`\{USER_LOGIN\}`',
				$_POST['users_inscription_autocat_title'][$code]))
				{
					unset($_POST['users_inscription_autocat_title']);
					self::report('warning:' . __('Le titre de la catégorie créée lors de'
						. ' l\'inscription d\'un utilisateur doit obligatoirement contenir'
						. ' la variable {USER_LOGIN}.'));
					break;
				}
			}
		}

		$fields = array(
			'checkboxes' => array(
				'avatars',
				'upload_categories_empty',
				'upload_resize',
				'users_inscription',
				'users_inscription_autocat',
				'users_inscription_by_mail',
				'users_inscription_by_password',
				'users_inscription_moderate',
				'users_only_members',
				'users_log_activity',
				'users_log_activity_delete'
			),
			'integer' => array(
				'avatars_maxfilesize',
				'avatars_maxsize',
				'upload_maxfilesize',
				'upload_maxwidth',
				'upload_maxheight',
				'upload_resize_maxwidth',
				'upload_resize_maxheight',
				'upload_resize_quality',
				'users_desc_maxlength',
				'users_inscription_autocat_category',
				'users_log_activity_delete_days'
			),
			'text_locale' => array(
				'users_inscription_autocat_title',
				'users_inscription_password_text'
			),
			'lists' => array(
				'users_locked_albums_access_mode' => array('groups', 'password'),
				'users_inscription_autocat_type' => array('album', 'category')
			)
		);

		self::_changeDBConfig($fields, $columns, $params);
	}

	/**
	 * Détermine le numéro de page de la liste les utilisateurs.
	 *
	 * @return void
	 */
	public static function parentPage()
	{
		// Critère de tri.
		$sql_order_by = auth::$infos['user_prefs']['users']['orderby'];
		$sql_order_by = 'LOWER(user_' . auth::$infos['user_prefs']['users']['sortby'] . ') '
			. $sql_order_by . ', user_id ' . $sql_order_by;

		$params = array();
		$sql_where = '';

		// Moteur de recherche.
		if (isset($_GET['search']))
		{
			self::$_sqlSearch = search::getSQLWhere(self::$searchFields, FALSE, TRUE);
			if (self::$_sqlSearch)
			{
				// Statut.
				if (isset($_GET['search_status']))
				{
					switch ($_GET['search_status'])
					{
						case 'activate' :
							self::$_sqlSearch['sql'] .= ' AND user_status = "1"';
							break;

						case 'deactivate' :
							self::$_sqlSearch['sql'] .= ' AND user_status = "0"';
							break;

						case 'pending' :
							self::$_sqlSearch['sql'] .= ' AND user_status = "-1"';
							break;
					}
				}

				$sql_where .= ' AND ' . self::$_sqlSearch['sql'];
				$params = array_merge($params, self::$_sqlSearch['params']);
				self::$searchInit = TRUE;
			}
		}

		// Statut.
		if (isset($_GET['status']))
		{
			$sql_where .= ' AND user_status = "-1"';
		}

		// Groupe.
		if (isset($_GET['group_id']))
		{
			$sql_where .= ' AND group_id = ' . (int) $_GET['group_id'];
		}

		// Récupération de tous les utilisateurs.
		$sql = 'SELECT user_id,
					   user_login
				  FROM ' . CONF_DB_PREF . 'users
				 WHERE user_status != "-2"
				   AND user_id != 2
				   AND group_id != 2
					   ' . $sql_where . '
			  ORDER BY ' . $sql_order_by;
		$fetch_style = array(
			'column' => array('user_id', 'user_login')
		);
		if (utils::$db->prepare($sql) === FALSE
		|| utils::$db->executeQuery($params, PDO::FETCH_ASSOC) === FALSE
		|| utils::$db->nbResult === 0)
		{
			utils::redirect('users');
		}
		self::$usersList = utils::$db->queryResult;

		// Position de l'utilisateur.
		$current_user = array_search(
			array(
				'user_id' => self::$infos['user_id'],
				'user_login' => self::$infos['user_login']
			),
			self::$usersList
		) + 1;

		// On détermine la page où se situe l'utilisateur courant.
		$parent_page = ceil($current_user
			/ auth::$infos['user_prefs']['users']['nb_per_page']);
		if ($parent_page > 1)
		{
			self::$parentPage = $parent_page;
		}
	}

	/**
	 * Envoi d'un message à des utilisateurs.
	 *
	 * @return void
	 */
	public static function sendmail()
	{
		// Récupération de tous les utilisateurs.
		$sql = 'SELECT user_id,
					   user_login,
					   user_email
				  FROM ' . CONF_DB_PREF . 'users
				 WHERE user_status IN ("0", "1")
				   AND user_id NOT IN (2, ' . (int) auth::$infos['user_id'] . ')
				   AND user_email IS NOT NULL';
		$fetch_style = array('fetch' => PDO::FETCH_ASSOC, 'column' => 'user_id');
		if (utils::$db->query($sql, $fetch_style) !== FALSE)
		{
			self::$usersList = utils::$db->queryResult;
		}

		// Récupération de tous les groupes.
		$sql = 'SELECT group_id,
					   group_name
				  FROM ' . CONF_DB_PREF . 'groups
				 WHERE group_id NOT IN ("1", "2")';
		$fetch_style = array('fetch' => PDO::FETCH_ASSOC, 'column' => 'group_id');
		if (utils::$db->query($sql, $fetch_style) !== FALSE)
		{
			self::$groups = utils::$db->queryResult;
		}

		// Quelques vérifications.
		if (empty($_POST)
		|| !isset($_POST['message'])
		|| !isset($_POST['email'])
		|| !isset($_POST['name'])
		|| !isset($_POST['subject']))
		{
			return;
		}

		try
		{
			// Encore des vérifications.
			foreach (array('name', 'email', 'subject', 'message') as $field)
			{
				// On vérifie que chaque champ a été rempli.
				if (!mb_eregi('\w', $_POST[$field]))
				{
					self::$fieldError = $field;
					throw new Exception(
						'warning:' . __('Certains champs n\'ont pas été renseignés.')
					);
				}

				// On vérifie la longueur des champs.
				if ($field != 'message' && mb_strlen($_POST[$field]) > 255)
				{
					return;
				}
			}

			// On vérifie le format de l'adresse courriel.
			if (!preg_match('`^' . utils::regexpEmail() . '$`i', $_POST['email']))
			{
				self::$fieldError = 'email';
				throw new Exception(
					'warning:' . __('Format de l\'adresse de courriel invalide.')
				);
			}

			$sql_where = array();

			// Tous les utilisateurs.
			if (isset($_POST['users_all']))
			{
				$sql_where = array('1=1');
			}
			else
			{
				// Les groupes sélectionnés.
				if (isset($_POST['groups_select']) && is_array($_POST['groups_list'])
				&& !empty($_POST['groups_list']))
				{
					foreach ($_POST['groups_list'] as $k => &$group_id)
					{
						if ((int) $group_id < 3)
						{
							unset($_POST['groups_list'][$k]);
						}
					}
					$sql_where[] = 'group_id IN (' . implode(',', $_POST['groups_list']) . ')';
				}

				// Les utilisateurs sélectionnés.
				if (isset($_POST['users_select']) && is_array($_POST['users_list'])
				&& !empty($_POST['users_list']))
				{
					foreach ($_POST['users_list'] as $k => &$user_id)
					{
						if (in_array((int) $user_id, array(0, 2, (int) auth::$infos['user_id'])))
						{
							unset($_POST['users_list'][$k]);
						}
					}
					$sql_where[] = 'user_id IN (' . implode(',', $_POST['users_list']) . ')';
				}

				// Tous les utilisateurs activées.
				if (isset($_POST['users_activate']))
				{
					$sql_where[] = 'user_status = "1"';
				}

				// Tous les utilisateurs désactivées.
				if (isset($_POST['users_deactivate']))
				{
					$sql_where[] = 'user_status = "0"';
				}

				// Tous les utilisateurs en attente de validation.
				if (isset($_POST['users_pending']))
				{
					$sql_where[] = 'user_status = "-1"';
				}
			}

			// Aucune option sélectionnée.
			if (empty($sql_where))
			{
				throw new Exception(
					'warning:' . __('Aucun utilisateur n\'a été sélectionné.')
				);
			}

			// On récupère l'addresse de courriel des utilisateurs sélectionnés.
			$sql = 'SELECT user_email
					  FROM ' . CONF_DB_PREF . 'users
					 WHERE user_status IN ("-1", "0", "1")
					   AND user_id NOT IN (2, ' . (int) auth::$infos['user_id'] . ')
					   AND user_email IS NOT NULL
					   AND (' . implode(' OR ', $sql_where) . ')';
			$fetch_style = array('column' => array('user_email', 'user_email'));
			if (utils::$db->query($sql, $fetch_style) === FALSE)
			{
				throw new Exception('error:' . utils::$db->msgError);
			}
			if (utils::$db->nbResult === 0)
			{
				throw new Exception(
					'warning:' . __('Aucun utilisateur n\'a été sélectionné.')
				);
			}
			$mails = utils::$db->queryResult;

			// Envoi du courriel.
			$mail = new mail();
			$mail->messages[] = array(
				'to' => '',
				'name' => $_POST['name'],
				'from' => $_POST['email'],
				'subject' => $_POST['subject'],
				'message' => $_POST['message'],
				'bcc' => implode(', ', $mails)
			);
			$mail->send();

			self::report('success:' . __('Votre message a été envoyé.'));
			$_POST = array();
		}
		catch (Exception $e)
		{
			self::report($e);
		}
	}

	/**
	 * Envoi d'un message à l'utilisateur.
	 *
	 * @return void
	 */
	public static function sendmailUser()
	{
		// Quelques vérifications.
		if (empty($_POST)
		|| !isset($_POST['message'])
		|| !isset($_POST['email'])
		|| !isset($_POST['name'])
		|| !isset($_POST['subject'])
		|| empty(self::$infos['user_email']))
		{
			return;
		}

		try
		{
			// Encore des vérifications.
			foreach (array('name', 'email', 'subject', 'message') as $field)
			{
				// On vérifie que chaque champ a été rempli.
				if (!mb_eregi('\w', $_POST[$field]))
				{
					self::$fieldError = $field;
					throw new Exception(
						'warning:' . __('Certains champs n\'ont pas été renseignés.')
					);
				}

				// On vérifie la longueur des champs.
				if ($field != 'message' && mb_strlen($_POST[$field]) > 255)
				{
					return;
				}
			}

			// On vérifie le format de l'adresse courriel.
			if (!preg_match('`^' . utils::regexpEmail() . '$`i', $_POST['email']))
			{
				self::$fieldError = 'email';
				throw new Exception(
					'warning:' . __('Format de l\'adresse de courriel invalide.')
				);
			}

			// Envoi du courriel.
			$mail = new mail();
			$mail->messages[] = array(
				'to' => self::$infos['user_email'],
				'name' => $_POST['name'],
				'from' => $_POST['email'],
				'subject' => $_POST['subject'],
				'message' => $_POST['message'],
				'bcc' => ''
			);
			$mail->send();

			self::report('success:' . __('Votre message a été envoyé.'));
			$_POST = array();
		}
		catch (Exception $e)
		{
			self::report($e);
		}
	}

	/**
	 * Actions sur la sélection d'utilisateurs.
	 *
	 * @return void
	 */
	public static function usersActions()
	{
		if (!isset($_POST['selection']) || empty($_POST['action'])
		|| empty($_POST['select']) || !is_array($_POST['select']))
		{
			return;
		}

		// Identifiants des utilisateurs sélectionnés.
		$selected_ids = array_map('intval', array_keys($_POST['select']));
		if (!isset($selected_ids[0]))
		{
			return;
		}

		// Opérations impossible sur le super-admin ou l'utilisateur courant.
		if (in_array(1, $selected_ids)
		 || in_array(auth::$infos['user_id'], $selected_ids))
		{
			return;
		}

		// Opérations impossibles sur un admin
		// si l'utilisateur courant n'est pas super-admin.
		if (auth::$infos['user_id'] != 1)
		{
			$sql = 'SELECT COUNT(user_id)
					  FROM ' . CONF_DB_PREF . 'users
					  JOIN ' . CONF_DB_PREF . 'groups USING (group_id)
					 WHERE user_id IN (' . implode(', ', $selected_ids) . ')
					   AND group_admin = "0"';
			if (utils::$db->query($sql, 'value') === FALSE
			|| utils::$db->nbResult === 0
			|| (int) utils::$db->queryResult != count($selected_ids))
			{
				return;
			}
		}

		self::$report = array('error' => array(), 'warning' => array());

		// Action à effectuer.
		switch ($_POST['action'])
		{
			// Activation de comptes.
			case 'activate' :
				$success_message = __('Les utilisateurs sélectionnés ont été activés.');
				self::_activate($selected_ids, 'activate', $success_message);
				break;

			// Suppression.
			case 'delete' :
				self::_deleteUsers($selected_ids);
				break;

			// Suspension de comptes.
			case 'deactivate' :
				$success_message = __('Les utilisateurs sélectionnés ont été suspendus.');
				self::_activate($selected_ids, 'deactivate', $success_message);
				break;

			// Changement de groupe.
			case 'group' :
				self::_changeUsersGroup($selected_ids);
		}
	}

	/**
	 * Options du filigrane.
	 *
	 * @return void
	 */
	public static function watermark()
	{
		admin::$watermarkParams = utils::$config['watermark_params_default']
			= unserialize(utils::$config['watermark_params_default']);
		if (utils::isSerializedArray(self::$infos['user_watermark']))
		{
			admin::$watermarkParams = self::$infos['user_watermark']
				= unserialize(self::$infos['user_watermark']);
		}

		$image_dir = 'images/watermarks/users/' . (int) self::$infos['user_id'] . '/';

		// Modification des options du filigrane.
		$r = watermark::changeOptions(admin::$watermarkParams, $image_dir);
		if (admin::$watermarkParams != self::$infos['user_watermark']
		&& (self::$infos['user_watermark'] === NULL
		&& admin::$watermarkParams == utils::$config['watermark_params_default']) === FALSE)
		{
			// On effectue la mise à jour des paramètres.
			$sql = 'UPDATE ' . CONF_DB_PREF . 'users
					   SET user_watermark = :watermark_params
					 WHERE user_id = :user_id
					 LIMIT 1';
			$params = array(
				'user_id' => (int) self::$infos['user_id'],
				'watermark_params' => serialize(admin::$watermarkParams)
			);
			if (utils::$db->prepare($sql) === FALSE
			 || utils::$db->executeExec($params) === FALSE)
			{
				self::report('error:' . utils::$db->msgError);
				return;
			}

			self::report('success:' . __('Modifications enregistrées.'));
			self::report('success_p:' . __('Les autres modifications ont été enregistrées.'));
		}

		// Rapport du changement de l'image de filigrane.
		if (is_string($r))
		{
			self::report($r);
		}

		// Chemin de l'image de filigrane.
		$image_file = $image_dir . admin::$watermarkParams['image_file'];
		admin::$watermarkParams['image_file'] = (admin::$watermarkParams['image_file']
		&& file_exists(GALLERY_ROOT . '/' . $image_file)
		&& is_file(GALLERY_ROOT . '/' . $image_file))
			? $image_file
			: NULL;
	}



	/**
	 * Gestion du statut des utilisateurs.
	 *
	 * @param array $selected_ids
	 *	Identifiants des utilisateurs sélectionnés.
	 * @param string $status
	 *	Action à effectuer : activer ou suspendre.
	 * @param string $success_message
	 *	Message à afficher en cas de réussite.
	 * @return boolean
	 */
	private static function _activate($selected_ids, $status, $success_message)
	{
		try
		{
			// Pour l'activation de comptes,
			// récupération de l'adresse de courriel des
			// utilisateurs sélectionnés en attente de validation.
			if ($status == 'activate')
			{
				$sql = 'SELECT user_id,
							   user_login,
							   user_email
						  FROM ' . CONF_DB_PREF . 'users
						 WHERE user_id IN (' . implode(', ', $selected_ids) . ')
						   AND user_status = "-1"';
				$fetch_style = array('fetch' => PDO::FETCH_ASSOC, 'column' => 'user_id');
				if (utils::$db->query($sql, $fetch_style) !== FALSE
				 && utils::$db->nbResult > 0)
				{
					$new_users = utils::$db->queryResult;
				}
			}

			// Changement du statut des utilisateurs sélectionnés.
			$user_status = ($status == 'activate') ? 1 : 0;
			$sql = 'UPDATE ' . CONF_DB_PREF . 'users
					   SET user_status = "' . $user_status . '"
					 WHERE user_id IN (' . implode(', ', $selected_ids) . ')
					   AND user_status != "' . $user_status . '"';
			if (utils::$db->exec($sql, FALSE) === FALSE)
			{
				throw new Exception(utils::$db->msgError);
			}

			// Nouveaux utilisateurs activés.
			if (isset($new_users))
			{
				// Création d'une catégorie à l'inscription.
				if (utils::$config['users_inscription_autocat'])
				{
					foreach ($new_users as $id => $infos)
					{
						user::createCategory($id, $infos['user_login']);
					}
				}

				// Envoi d'un courriel informant les utilisateurs
				// en attente de validation que leur compte a été activé.
				$new_users_email = array();
				foreach ($new_users as $id => $infos)
				{
					$new_users_email[] = $infos['user_email'];
				}
				$message = 'Ce courriel vous a été envoyé automatiquement'
					. ' par la galerie %s pour vous informer que votre compte'
					. ' a été validé par un administrateur.' . "\n\n";
				$message .= 'Vous pouvez désormais vous connecter avec les'
					. ' informations de connexion que vous avez fournis lors'
					. ' de votre inscription.';
				$message = sprintf($message, GALLERY_HOST . CONF_GALLERY_PATH . '/');
				$mail = new mail();
				$mail->messages[] = array(
					'to' => '',
					'name' => '',
					'from' => '',
					'subject' => 'Validation de votre compte',
					'message' => $message,
					'bcc' => implode(', ', $new_users_email)
				);
				$mail->send();
			}

			self::report('success:' . $success_message);
			return TRUE;
		}
		catch (Exception $e)
		{
			self::report('error:' . $e->getMessage());
			return FALSE;
		}
	}

	/**
	 * Changement de l'avatar.
	 *
	 * @return void
	 */
	private static function _avatarChange()
	{
		if (!isset($_FILES['new']))
		{
			return;
		}

		$error_message = __('Impossible de modifier l\'avatar.');
		$change = avatar::change($_FILES['new'], self::$infos['user_id'],
			self::$infos['user_avatar'], $error_message);

		if ($change === FALSE)
		{
			return;
		}

		if ($change === TRUE)
		{
			self::$infos['user_avatar'] = 1;
			self::report('success:' . __('L\'avatar a été changé.'));
			return;
		}

		self::report($change);
	}

	/**
	 * Suppression de l'avatar.
	 *
	 * @return void
	 */
	private static function _avatarDelete()
	{
		if (!self::$infos['user_avatar'])
		{
			return;
		}

		if (!avatar::delete(self::$infos['user_id']))
		{
			self::report('error:' . __('Impossible de supprimer l\'avatar.'));
			return;
		}

		self::$infos['user_avatar'] = 0;
		self::report('success:' . __('L\'avatar a été supprimé.'));
	}

	/**
	 * Change le groupe des utilisateurs sélectionnés.
	 *
	 * @param array $selected_ids
	 *	Identifiants des utilisateurs sélectionnés.
	 * @return boolean
	 */
	private static function _changeUsersGroup($selected_ids)
	{
		if (!isset($_POST['group'])
		|| !preg_match('`^\d{1,11}$`', $_POST['group'])
		|| (int) $_POST['group'] < 3)
		{
			return;
		}

		// On vérifie que le groupe existe.
		$sql = 'SELECT group_admin
				  FROM ' . CONF_DB_PREF . 'groups
				 WHERE group_id = ' . (int) $_POST['group'] . '
				 LIMIT 1';
		if (utils::$db->query($sql, 'value') === FALSE
		 || utils::$db->nbResult === 0)
		{
			return;
		}

		// Restriction pour les "simples" administrateurs.
		if (auth::$infos['user_id'] != 1
		 && utils::$db->queryResult == 1)
		{
			return;
		}

		// Mise à jour des utilisateurs sélectionnés.
		$sql = 'UPDATE ' . CONF_DB_PREF . 'users
				   SET group_id = ' . (int) $_POST['group'] . '
				 WHERE user_id IN (' . implode(', ', $selected_ids) . ')';
		if (utils::$db->exec($sql) === FALSE)
		{
			self::report('error:' . utils::$db->msgError);
			return FALSE;
		}

		else if (utils::$db->nbResult > 0)
		{
			self::report('success:' . __('Modifications enregistrées.'));
			return TRUE;
		}
	}

	/**
	 * Suppression de groupes.
	 *
	 * @param array $selected_ids
	 *	Identifiants des groupes sélectionnés.
	 * @return void
	 */
	private static function _deleteGroups($selected_ids)
	{
		$sql = array
		(
			// Tous les utilisateurs des groupes à supprimer
			// feront désormais partie du groupe 3 (membres).
			'UPDATE ' . CONF_DB_PREF . 'users
				SET group_id = 3
			  WHERE group_id IN (' . implode(', ', $selected_ids) . ')',

			// Suppression des groupes.
			'DELETE
			   FROM ' . CONF_DB_PREF . 'groups
			  WHERE group_id IN (' . implode(', ', $selected_ids) . ')'
		);
		if (utils::$db->exec($sql) === FALSE)
		{
			self::report('error:' . utils::$db->msgError);
		}

		else if (utils::$db->nbResult > 0)
		{
			self::report('success:' . __('Les groupes sélectionnés ont été supprimés.'));
		}
	}

	/**
	 * Suppression d'utilisateurs.
	 *
	 * @param array $selected_ids
	 *	Identifiants des utilisateurs sélectionnés.
	 * @return void
	 */
	private static function _deleteUsers($selected_ids)
	{
		$sql = array
		(
			// Mise à jour du user_id des autres tables.
			'UPDATE ' . CONF_DB_PREF . 'categories
				SET user_id = 2
			  WHERE user_id IN (' . implode(', ', $selected_ids) . ')
				AND user_id NOT IN (1, 2)',

			'UPDATE ' . CONF_DB_PREF . 'images
				SET user_id = 2
			  WHERE user_id IN (' . implode(', ', $selected_ids) . ')
				AND user_id NOT IN (1, 2)',

			'UPDATE ' . CONF_DB_PREF . 'votes
				SET user_id = 2
			  WHERE user_id IN (' . implode(', ', $selected_ids) . ')
				AND user_id NOT IN (1, 2)',

			'UPDATE ' . CONF_DB_PREF . 'comments AS c
		  LEFT JOIN ' . CONF_DB_PREF . 'users AS u USING (user_id)
				SET c.user_id = 2,
					c.com_author = u.user_login
			  WHERE c.user_id IN (' . implode(', ', $selected_ids) . ')
				AND c.user_id NOT IN (1, 2)',

			'UPDATE ' . CONF_DB_PREF . 'uploads
				SET user_id = 2
			  WHERE user_id IN (' . implode(', ', $selected_ids) . ')
				AND user_id NOT IN (1, 2)',

			'UPDATE ' . CONF_DB_PREF . 'users_logs
				SET user_id = 2
			  WHERE user_id IN (' . implode(', ', $selected_ids) . ')
				AND user_id NOT IN (1, 2)',

			// Suppression des utilisateurs.
			'DELETE
			   FROM ' . CONF_DB_PREF . 'users
			  WHERE user_id IN (' . implode(', ', $selected_ids) . ')
				AND user_id NOT IN (1, 2)',

			// Supression des favoris.
			'DELETE
			   FROM ' . CONF_DB_PREF . 'favorites
			  WHERE user_id IN (' . implode(', ', $selected_ids) . ')
				AND user_id NOT IN (1, 2)'
		);
		if (utils::$db->exec($sql) === FALSE)
		{
			self::report('error:' . utils::$db->msgError);
			return;
		}

		if (utils::$db->nbResult < 1)
		{
			return;
		}

		// Suppression de l'avatar des utilisateurs.
		foreach ($selected_ids as &$id)
		{
			// Avatar.
			$avatar_file = GALLERY_ROOT . '/users/avatars/user' . $id . '.jpg';
			if (file_exists($avatar_file))
			{
				files::unlink($avatar_file);
			}

			// Vignette de l'avatar.
			$avatar_thumb = GALLERY_ROOT . '/users/avatars/user' . $id . '_thumb.jpg';
			if (file_exists($avatar_thumb))
			{
				files::unlink($avatar_thumb);
			}
		}

		self::report('success:'
			. __('Les utilisateurs sélectionnés ont été supprimés.'));
		return TRUE;
	}

	/**
	 * Retourne les sous-requêtes SQL utiles
	 * pour récupérer les statistiques utilisateur.
	 *
	 * @return string
	 */
	private static function _sqlUserStats()
	{
		return
		  '(SELECT image_adddt
			  FROM ' . CONF_DB_PREF . 'images
			 WHERE user_id = u.user_id
		  ORDER BY image_adddt DESC
			 LIMIT 1) AS user_lastimgdt,
		   (SELECT COUNT(*)
			  FROM ' . CONF_DB_PREF . 'users_logs
			 WHERE user_id = u.user_id) AS nb_logs,
		   (SELECT COUNT(*)
			  FROM ' . CONF_DB_PREF . 'comments
			 WHERE user_id = u.user_id
			   AND com_status = "1") AS nb_comments_publish,
		   (SELECT COUNT(*)
			  FROM ' . CONF_DB_PREF . 'comments
			 WHERE user_id = u.user_id
			   AND com_status = "0") AS nb_comments_unpublish,
		   (SELECT COUNT(*)
			  FROM ' . CONF_DB_PREF . 'comments
			 WHERE user_id = u.user_id
			   AND com_status = "-1") AS nb_comments_pending,
		   (SELECT COUNT(*)
			  FROM ' . CONF_DB_PREF . 'basket
			 WHERE user_id = u.user_id) AS nb_basket,
		   (SELECT COUNT(*)
			  FROM ' . CONF_DB_PREF . 'favorites
			 WHERE user_id = u.user_id) AS nb_favorites,
		   (SELECT COUNT(*)
			  FROM ' . CONF_DB_PREF . 'images
			 WHERE user_id = u.user_id
			   AND image_status = "1") AS nb_images_publish,
		   (SELECT COUNT(*)
			  FROM ' . CONF_DB_PREF . 'images
			 WHERE user_id = u.user_id
			   AND image_status = "0") AS nb_images_unpublish,
		   (SELECT COUNT(*)
			  FROM ' . CONF_DB_PREF . 'uploads
			 WHERE user_id = u.user_id) AS nb_images_pending,
		   (SELECT COUNT(*)
			  FROM ' . CONF_DB_PREF . 'votes
			 WHERE user_id = u.user_id) AS nb_votes';
	}

	/**
	 * Retourne la clause WHERE pour la récupération des utilisateurs.
	 *
	 * @return string
	 */
	private static function _sqlWhereUsers()
	{
		// On ne récupère pas les utilisateur et groupe "invités".
		$sql_where = 'user_id != 2 AND u.group_id != 2';

		// Statut.
		$status = (isset($_GET['status']))
			? $_GET['status']
			: '';
		switch ($status)
		{
			// Utilisateurs en attente de validation par un admin.
			case 'pending' :
				$sql_where .= ' AND user_status = "-1"';
				break;

			// Tous les utilisateurs
			// (sauf ceux en attente de validation par courriel).
			default :
				$sql_where .= ' AND (user_status = "-1"
					OR user_status = "0"
					OR user_status = "1")';
		}

		return $sql_where;
	}
}

/**
 * Gestion des votes.
 */
class votes extends admin
{
	/**
	 * Informations utiles sur les images de l'album courant.
	 *
	 * @var array
	 */
	public static $images;

	/**
	 * Informations utiles des votes.
	 *
	 * @var array
	 */
	public static $items;

	/**
	 * Nombre de votes.
	 *
	 * @var integer
	 */
	public static $nbItems;

	/**
	 * Nombre de pages.
	 *
	 * @var integer
	 */
	public static $nbPages;

	/**
	 * Informations sur l'objet courant.
	 *
	 * @var array
	 */
	public static $objectInfos;

	/**
	 * Champs de la table "votes" pour la recherche.
	 *
	 * @var array
	 */
	public static $searchFields = array(
		'vote_ip'
	);

	/**
	 * Options de recherche.
	 *
	 * @var array
	 */
	public static $searchOptions = array(
		'all_words' => 'bin',
		'vote_ip' => 'bin',
		'date' => 'bin',
		'date_end_day' => '\d{2}',
		'date_end_month' => '\d{2}',
		'date_end_year' => '\d{4}',
		'date_field' => 'vote_date',
		'date_start_day' => '\d{2}',
		'date_start_month' => '\d{2}',
		'date_start_year' => '\d{4}',
		'rate' => '[1-5]',
		'user' => '(?:all|\d{1,11})'
	);



	/**
	 * Actions sur la sélection de votes.
	 *
	 * @return void
	 */
	public static function actions()
	{
		if (($selected_ids = self::_initObjectsActions()) === FALSE)
		{
			return;
		}

		// Action à effectuer.
		switch ($_POST['action'])
		{
			// Suppression.
			case 'delete' :
				self::_delete($selected_ids);
				break;
		}
	}

	/**
	 * Récupération des images de l'album courant.
	 *
	 * @return void
	 */
	public static function getImages()
	{
		if ((isset($_GET['object_type']) &&
		(($_GET['object_type'] == 'category' && self::$objectInfos['cat_filemtime'] !== NULL)
		|| $_GET['object_type'] == 'image')) === FALSE)
		{
			return;
		}

		$sw = self::_sqlWhereVotes();
		$sql_where = $sw['sql'];
		$params = $sw['params'];

		if (self::$_sqlSearch)
		{
			$sql_where .= ' AND ' . self::$_sqlSearch['sql'];
			$params = array_merge($params, self::$_sqlSearch['params']);
		}

		$sql = 'SELECT i.image_id,
					   i.image_name
				  FROM ' . CONF_DB_PREF . 'categories AS c,
					   ' . CONF_DB_PREF . 'votes AS v,
					   ' . CONF_DB_PREF . 'images AS i
				 WHERE c.cat_id = ' . (int) self::$objectInfos['cat_id'] . '
				   AND c.cat_id = i.cat_id
				   AND i.image_id = v.image_id'
					 . $sql_where;
		$fetch_style = array('fetch' => PDO::FETCH_ASSOC, 'column' => 'image_id');
		if (utils::$db->prepare($sql) === FALSE
		|| utils::$db->executeQuery($params, $fetch_style) === FALSE
		|| utils::$db->nbResult === 0)
		{
			return;
		}

		self::$images = utils::$db->queryResult;
	}

	/**
	 * Réduit la liste des catégories à celles votées
	 * selon le statut choisi et la requête de la recherche.
	 *
	 * @return void
	 */
	public static function reduceMap()
	{
		$sw = self::_sqlWhereVotes();
		$sql_where = $sw['sql'];
		$params = $sw['params'];

		if (self::$_sqlSearch)
		{
			$sql_where .= ' AND ' . self::$_sqlSearch['sql'];
			$params = array_merge($params, self::$_sqlSearch['params']);
		}

		$sql = 'SELECT c.cat_id
				  FROM ' . CONF_DB_PREF . 'categories AS c,
					   ' . CONF_DB_PREF . 'images AS i,
					   ' . CONF_DB_PREF . 'votes AS v
				 WHERE c.cat_id = i.cat_id
				   AND i.image_id = v.image_id
				   AND cat_a_votes + cat_d_votes > 0'
					 . $sql_where;
		$fetch_style = array('column' => array('cat_id', 'cat_id'));
		if (utils::$db->prepare($sql) === FALSE
		|| utils::$db->executeQuery($params, $fetch_style) === FALSE)
		{
			return;
		}

		self::_reduceMapAlbums(utils::$db->queryResult);
	}

	/**
	 * Récupération des informations utiles des votes.
	 *
	 * @return void
	 */
	public static function getVotes()
	{
		$votes_per_page = auth::$infos['user_prefs']['votes']['nb_per_page'];

		$sw = self::_sqlWhereVotes();
		$sql_where = $sw['sql'];
		$params = $sw['params'];

		// Informations utiles de l'image courante.
		if (isset($_GET['object_type']) && $_GET['object_type'] == 'image')
		{
			$sql = 'SELECT cat.cat_id,
						   cat_name,
						   cat_path,
						   image_id,
						   image_name
					  FROM ' . CONF_DB_PREF . 'images AS img
				 LEFT JOIN ' . CONF_DB_PREF . 'categories AS cat USING (cat_id)
					 WHERE image_id = ' . (int) $_GET['object_id'];
			if (utils::$db->query($sql, 'row') === FALSE
			|| utils::$db->nbResult === 0)
			{
				utils::redirect('votes');
			}
			self::$objectInfos = utils::$db->queryResult;

			$sql_where .= ' AND v.image_id = ' . (int) $_GET['object_id'];
		}

		// Informations utiles de la catégorie courante.
		else if (isset($_GET['object_type']) && $_GET['object_type'] == 'category')
		{
			$sql = 'SELECT cat_id,
						   cat_name,
						   cat_path,
						   cat_filemtime
					  FROM ' . CONF_DB_PREF . 'categories
					 WHERE cat_id = ' . (int) $_GET['object_id'];
			if (utils::$db->query($sql, 'row') === FALSE
			|| utils::$db->nbResult === 0)
			{
				utils::redirect('votes');
			}
			self::$objectInfos = utils::$db->queryResult;

			if ($_GET['object_id'] > 1)
			{
				$sql_where .= ' AND i.image_path LIKE CONCAT(:cat_path, "/%")';
				$params['cat_path'] = self::$objectInfos['cat_path'];
			}
		}

		// Moteur de recherche.
		if (isset($_GET['search_query']))
		{
			self::$_sqlSearch = search::getSQLWhere(self::$searchFields, FALSE, TRUE);
			if (self::$_sqlSearch)
			{
				// Note.
				if (isset($_GET['search_rate'])
				&& preg_match('`^[1-5]$`', $_GET['search_rate']))
				{
					self::$_sqlSearch['sql'] .=
						' AND vote_rate = ' . (int) $_GET['search_rate'];
				}

				// Utilisateur.
				if (isset($_GET['search_user'])
				&& preg_match('`^\d{1,11}$`', $_GET['search_user']))
				{
					self::$_sqlSearch['sql'] .=
						' AND v.user_id = ' . (int) $_GET['search_user'];
				}

				$sql_where .= ' AND ' . self::$_sqlSearch['sql'];
				$params = array_merge($params, self::$_sqlSearch['params']);
				self::$searchInit = TRUE;
			}
		}

		// Détermine le nombre de votes.
		$sql = 'SELECT COUNT(*)
				  FROM ' . CONF_DB_PREF . 'votes AS v,
					   ' . CONF_DB_PREF . 'images AS i,
					   ' . CONF_DB_PREF . 'categories AS c,
					   ' . CONF_DB_PREF . 'users AS u
				 WHERE v.image_id = i.image_id
				   AND i.cat_id = c.cat_id
				   AND v.user_id = u.user_id'
				     . $sql_where
					 . sql::$groupAlbumsAccess;
		if (utils::$db->prepare($sql) === FALSE
		|| utils::$db->executeQuery($params, 'value') === FALSE)
		{
			return;
		}
		self::$nbItems = utils::$db->queryResult;

		// Nombre de pages.
		self::$nbPages = ceil(self::$nbItems / $votes_per_page);
		$sql_limit_start = $votes_per_page * ($_GET['page'] - 1);

		// Critère de tri.
		$sql_order_by = auth::$infos['user_prefs']['votes']['orderby'];
		$sql_order_by = 'LOWER(vote_' . auth::$infos['user_prefs']['votes']['sortby'] . ') '
			. $sql_order_by . ', vote_id ' . $sql_order_by;

		// Récupération des votes.
		$sql = 'SELECT v.vote_id,
					   v.vote_rate,
					   v.vote_date,
					   v.vote_ip,
					   i.image_id,
					   i.image_name,
					   i.image_adddt,
					   i.image_path,
					   i.image_height,
					   i.image_width,
					   i.image_tb_infos AS tb_infos,
					   c.cat_id,
					   c.cat_name,
					   u.user_id,
					   u.user_login
				  FROM ' . CONF_DB_PREF . 'votes AS v,
					   ' . CONF_DB_PREF . 'images AS i,
					   ' . CONF_DB_PREF . 'categories AS c,
					   ' . CONF_DB_PREF . 'users AS u
				 WHERE v.image_id = i.image_id
				   AND i.cat_id = c.cat_id
				   AND v.user_id = u.user_id'
				     . $sql_where
					 . sql::$groupAlbumsAccess . '
			  ORDER BY ' . $sql_order_by . '
				 LIMIT ' . $sql_limit_start . ',' . $votes_per_page;
		$fetch_style = array('fetch' => PDO::FETCH_ASSOC, 'column' => 'vote_id');
		if (utils::$db->prepare($sql) === FALSE
		|| utils::$db->executeQuery($params, $fetch_style) === FALSE)
		{
			return;
		}
		if (!admin::_objectsNbResult())
		{
			return;
		}
		self::$items = utils::$db->queryResult;
	}



	/**
	 * Supprime des votes.
	 *
	 * @param array $selected_ids
	 *	Identifiants des images sélectionnées.
	 * @return void
	 */
	private static function _delete($selected_ids)
	{
		try
		{
			// Début de la transaction.
			if (!utils::$db->transaction())
			{
				throw new Exception('error:' . utils::$db->msgError);
			}

			// Récupération des informations utiles des images sur
			// lesquelles ont été ajoutés les votes sélectionnés.
			$sql = 'SELECT v.vote_id,
						   v.vote_rate,
						   i.image_id,
						   i.image_status,
						   c.cat_id,
						   c.cat_path
					  FROM ' . CONF_DB_PREF . 'votes AS v,
						   ' . CONF_DB_PREF . 'images AS i,
						   ' . CONF_DB_PREF . 'categories AS c
					 WHERE v.image_id = i.image_id
					   AND i.cat_id = c.cat_id
					   AND v.vote_id IN (' . implode(', ', $selected_ids) . ')'
						 . sql::$groupAlbumsAccess;
			if (utils::$db->query($sql, PDO::FETCH_ASSOC) === FALSE)
			{
				throw new Exception(utils::$db->msgError);
			}

			// Si aucune ligne affectée, il ne faut rien changer.
			if (utils::$db->nbResult === 0)
			{
				utils::$db->rollBack();
				return;
			}

			$infos = utils::$db->queryResult;

			$images_votes = array();
			$images_infos = array();
			foreach ($infos as &$i)
			{
				$images_votes[$i['image_id']][$i['vote_id']] = (int) $i['vote_rate'];
				$images_infos[$i['image_id']] = array(
					'image_status' => $i['image_status'],
					'cat_id' => $i['cat_id'],
					'cat_path' => $i['cat_path']
				);
			}

			// Suppression des votes.
			$sql = 'DELETE
					  FROM ' . CONF_DB_PREF . 'votes
					 WHERE vote_id IN (' . implode(', ', $selected_ids) . ')';
			if (utils::$db->exec($sql, FALSE) === FALSE)
			{
				throw new Exception(utils::$db->msgError);
			}

			// Mise à jour des statistiques.
			$sql = array();
			$parents_id = array();
			foreach ($images_votes as $image_id => &$votes_id)
			{
				$i =& $images_infos[$image_id];

				// Récupération des identifiants
				// des catégories parentes de l'image.
				if (!isset($parents_id[$i['cat_id']]))
				{
					$parents_id[$i['cat_id']] =
						alb::getParentsIds($i['cat_id'], $i['cat_path']);
					if ($parents_id[$i['cat_id']] === FALSE)
					{
						throw new Exception(utils::$db->msgError);
					}
				}

				// Nombre de votes.
				$nb_votes = count($votes_id);

				// On met à jour le nombre de votes et la note moyenne
				// de l'image et de ses catégories parentes.
				$stat = ($i['image_status'] == 1) ? 'a' : 'd';
				$sql[] = 'UPDATE ' . CONF_DB_PREF . 'images
						     SET image_rate = CASE
								WHEN image_votes - ' . $nb_votes . ' > 0
								THEN ((image_rate * image_votes)
									- ' . array_sum($votes_id) . ')
									/ (image_votes - ' . $nb_votes . ')
								ELSE 0
								 END,
								 image_votes = image_votes - ' . $nb_votes . '
						   WHERE image_id = ' . (int) $image_id;

				$sql[] = 'UPDATE ' . CONF_DB_PREF . 'categories
							 SET cat_' . $stat . '_rate = CASE
								WHEN cat_' . $stat . '_votes - ' . $nb_votes . ' > 0
								THEN ((cat_' . $stat . '_rate
									* cat_' . $stat . '_votes)
									- ' . array_sum($votes_id) . ')
									/ (cat_' . $stat . '_votes - ' . $nb_votes . ')
								ELSE 0
								 END,
								 cat_' . $stat . '_votes
									= cat_' . $stat . '_votes - ' . $nb_votes . '
						   WHERE cat_id
							  IN (' . implode(', ', $parents_id[$i['cat_id']]) . ')';
			}

			// Exécution de la transaction.
			if (utils::$db->exec($sql, TRUE) === FALSE)
			{
				throw new Exception(utils::$db->msgError);
			}

			unset($sql);
			unset($parents_id);
			self::report('success:' . __('Les votes sélectionnés ont été supprimés.'));
		}
		catch (Exception $e)
		{
			self::report('error:' . $e->getMessage());
		}
	}

	/**
	 * Complète la clause WHERE de récupération des votes
	 * avec les critères de recherche choisis par l'utilisateur.
	 *
	 * @return array
	 */
	private static function _sqlWhereVotes()
	{
		$params = array();
		$sql = '';

		// Recherche par date.
		if (isset($_GET['date']))
		{
			$sql = ' AND vote_date >= :vote_date " 00:00:00"'
				 . ' AND vote_date <= :vote_date " 23:59:59"';
			$params['vote_date'] = $_GET['date'];
		}

		// Recherche par IP.
		else if (isset($_GET['ip']))
		{
			$sql = ' AND v.vote_ip = :ip';
			$params['ip'] = $_GET['ip'];
		}

		// Recherche par utilisateur.
		else if (isset($_GET['user_id']))
		{
			$sql = ' AND v.user_id = ' . (int) $_GET['user_id'];
		}

		return array(
			'sql' => $sql,
			'params' => $params
		);
	}
}

/**
 * Gestion des widgets.
 */
class widgets extends admin
{
	/**
	 * Valeurs du fichier de configuration de la page courante.
	 *
	 * @var array
	 */
	public static $configValues = array();



	/**
	 * Page des commentaires.
	 *
	 * @return void
	 */
	public static function comments()
	{
		if (empty($_POST))
		{
			return;
		}

		$w_params = utils::$config['pages_params'];

		// Nombre de commentaires par page.
		if (isset($_POST['nb_per_page']) && strlen($_POST['nb_per_page']) < 4
		&& (int) $_POST['nb_per_page'] > 0)
		{
			if ($_POST['nb_per_page'] != $w_params['comments']['nb_per_page'])
			{
				$w_params['comments']['nb_per_page'] = $_POST['nb_per_page'];
			}
		}

		self::_updateWidget($w_params, 'pages');
	}

	/**
	 * Page de contact.
	 *
	 * @return void
	 */
	public static function contact()
	{
		if (empty($_POST))
		{
			return;
		}

		$pages_params = utils::$config['pages_params'];

		// Email.
		if (isset($_POST['email']) && $_POST['email'] != $pages_params['contact']['email'])
		{
			$pages_params['contact']['email'] = $_POST['email'];
		}

		// Message.
		if (isset($_POST['message']))
		{
			utils::setLocaleText(
				$_POST['message'],
				$pages_params['contact']['message']
			);
		}

		self::_updateWidget($pages_params, 'pages');
	}

	/**
	 * Création d'un nouveau widget ou d'une nouvelle page.
	 *
	 * @param string $type
	 *	 'page' ou 'widget'.
	 * @return void
	 */
	public static function create($type)
	{
		if (empty($_POST) || !isset($_POST['title']) || !isset($_POST['text']))
		{
			return;
		}

		$order = utils::$config[$type . 's_order'];
		$params = utils::$config[$type . 's_params'];

		// Titre.
		$title = '';
		utils::setLocaleText($_POST['title'], $title, 64, TRUE);
		if ($title === '')
		{
			self::report('warning:' . __('Le titre doit contenir au moins 1 caractère.'));
			return;
		}
		else if ($type == 'page')
		{
			$url = utils::genURLName(utils::removeTagsLangs($title));
		}

		// Contenu.
		if (isset($_POST['file']) && isset($_POST['filename'])
		&& preg_match('`^[-a-z0-9_]{1,64}\.php$`', $_POST['filename']))
		{
			$file = $_POST['filename'];
		}
		$text = '';
		utils::setLocaleText($_POST['text'], $text, utils::$config['widgets_content_maxlength']);

		// On détermine l'identifiant.
		for ($i = 1; $i < 99; $i++)
		{
			if (!isset($params['perso_' . $i]))
			{
				$id = 'perso_' . $i;
				break;
			}
		}
		if (!isset($id))
		{
			return;
		}

		// On prépare les informations à enregistrer.
		$order[] = $id;
		$params[$id] = array(
			'status' => 0,
			'title' => $title
		);
		$params[$id]['text'] = $text;
		if (isset($file))
		{
			$params[$id]['file'] = $file;
		}
		if (isset($url))
		{
			$params[$id]['url'] = $url;
		}
		$params[$id]['type'] = (isset($file))
			? 'file'
			: 'text';
		$columns = array(
			'"' . $type . 's_order" THEN :order',
			'"' . $type . 's_params" THEN :params'
		);
		$params = array(
			'order' => serialize($order),
			'params' => serialize($params)
		);

		// On effectue la mise à jour des paramètres.
		$sql = 'UPDATE ' . CONF_DB_PREF . 'config
				   SET conf_value = CASE conf_name
					   WHEN ' . implode(' WHEN ', $columns) . '
					   ELSE conf_value END';
		if (utils::$db->prepare($sql) === FALSE
		|| utils::$db->executeExec($params) === FALSE)
		{
			self::report('error:' . utils::$db->msgError);
			return;
		}

		// On redirige.
		utils::redirect($type . '/perso/' . $i . '/new', TRUE);
	}

	/**
	 * Widget 'géolocalisation'.
	 *
	 * @return void
	 */
	public static function geoloc()
	{
		if (empty($_POST))
		{
			return;
		}

		$widgets_params = utils::$config['widgets_params'];

		// Titre.
		if (isset($_POST['title']))
		{
			utils::setLocaleText($_POST['title'], $widgets_params['geoloc']['title'], 128);
		}

		self::_updateWidget($widgets_params);
	}

	/**
	 * Page du livre d'or.
	 *
	 * @return void
	 */
	public static function guestbook()
	{
		if (empty($_POST))
		{
			return;
		}

		$w_params = utils::$config['pages_params'];

		// Nombre de commentaires par page.
		if (isset($_POST['nb_per_page']) && strlen($_POST['nb_per_page']) < 4
		&& (int) $_POST['nb_per_page'] > 0)
		{
			if ($_POST['nb_per_page'] != $w_params['guestbook']['nb_per_page'])
			{
				$w_params['guestbook']['nb_per_page'] = $_POST['nb_per_page'];
			}
		}

		// Message.
		if (isset($_POST['message']))
		{
			utils::setLocaleText(
				$_POST['message'],
				$w_params['guestbook']['message']
			);
		}

		self::_updateWidget($w_params, 'pages');
	}

	/**
	 * Widget 'bloc image'.
	 *
	 * @return void
	 */
	public static function imagesBloc()
	{
		self::$configValues = array(
			'CONF_THUMBS_WID_METHOD' => CONF_THUMBS_WID_METHOD,
			'CONF_THUMBS_WID_WIDTH' => CONF_THUMBS_WID_WIDTH,
			'CONF_THUMBS_WID_HEIGHT' => CONF_THUMBS_WID_HEIGHT,
			'CONF_THUMBS_WID_SIZE' => CONF_THUMBS_WID_SIZE,
			'CONF_THUMBS_WID_QUALITY' => CONF_THUMBS_WID_QUALITY
		);

		if (empty($_POST))
		{
			return;
		}

		$widgets_params = utils::$config['widgets_params'];


		// Paramètres des vignettes.
		$const = array(
			'CONF_THUMBS_WID_METHOD' => 'thumbs_wid_method',
			'CONF_THUMBS_WID_WIDTH' => 'thumbs_wid_width',
			'CONF_THUMBS_WID_HEIGHT' => 'thumbs_wid_height',
			'CONF_THUMBS_WID_SIZE' => 'thumbs_wid_size',
			'CONF_THUMBS_WID_QUALITY' => 'thumbs_wid_quality'
		);
		$change = files::changeConfig($const, self::$configValues);
		if ($change === FALSE)
		{
			self::report('error:'
				. __('Impossible de modifier le fichier de configuration.'));
		}
		else if ($change === TRUE)
		{
			self::report('success:' . __('Modifications enregistrées.'));
			self::report('success_p:' . __('Les autres modifications ont été enregistrées.'));
		}

		// Titre.
		if (isset($_POST['title']))
		{
			utils::setLocaleText($_POST['title'], $widgets_params['image']['title'], 128);
		}

		// Type d'image.
		if (isset($_POST['mode']) && in_array($_POST['mode'], array('fixed', 'last', 'random'))
		&& $_POST['mode'] != $widgets_params['image']['params']['mode'])
		{
			$widgets_params['image']['params']['mode'] = $_POST['mode'];
		}

		// Identifiants des images et des albums.
		foreach (array('albums', 'images') as $p)
		{
			if (isset($_POST[$p]))
			{
				$_POST[$p] = trim($_POST[$p]);
				if (preg_match('`^(?:(\d{1,11},){0,9}\d{1,11})?$`', $_POST[$p])
				&& $_POST[$p] != implode(',', $widgets_params['image']['params'][$p]))
				{
					$widgets_params['image']['params'][$p] = explode(',', $_POST[$p]);
				}
			}
		}

		// Nombre d'images.
		if (isset($_POST['nb_images']) && preg_match('`^\d{1,2}$`', $_POST['nb_images'])
		&& $_POST['nb_images'] != $widgets_params['image']['params']['nb_thumbs'])
		{
			$widgets_params['image']['params']['nb_thumbs'] = $_POST['nb_images'];
		}

		self::_updateWidget($widgets_params);
	}

	/**
	 * Widget 'liens externes'.
	 *
	 * @return void
	 */
	public static function links()
	{
		if (empty($_POST['links']))
		{
			return;
		}

		$widgets_params = utils::$config['widgets_params'];

		$warning_message_title = 'warning:' . __('Lien \'%s\' : ')
			. __('Le titre doit contenir au moins 1 caractère.');

		// Titre.
		if (isset($_POST['title']))
		{
			utils::setLocaleText($_POST['title'], $widgets_params['links']['title'], 128);
		}

		// Liens.
		foreach ($_POST['links'] as $id => $link)
		{
			// Ajout d'un nouveau lien.
			if (isset($link['new']) && !isset($link['delete'])
			&& isset($link['title']) && is_array($link['title'])
			&& isset($link['url']) && !is_array($link['url'])
			&& isset($link['desc']) && is_array($link['desc']))
			{
				// Titre du lien.
				$title = '';
				utils::setLocaleText($link['title'], $title, 128, TRUE);
				if ($title === '')
				{
					self::report(sprintf($warning_message_title, __('Nouveau lien')));
					continue;
				}

				// Description du lien.
				$desc = '';
				utils::setLocaleText($link['desc'], $desc, 128);

				// On ajoute le nouveau lien.
				$widgets_params['links']['items'][$id] = array(
					'activate' => isset($link['activate']) ? 1 : 0,
					'title' => $title,
					'url' => $link['url'],
					'desc' => $desc,
				);
				continue;
			}

			// Suppression.
			if (isset($link['delete']))
			{
				unset($widgets_params['links']['items'][$id]);
				continue;
			}

			// Modifications.
			if (!isset($widgets_params['links']['items'][$id]))
			{
				continue;
			}


			// Activation.
			if (isset($link['activate'])
			&& !$widgets_params['links']['items'][$id]['activate'])
			{
				$widgets_params['links']['items'][$id]['activate'] = 1;
			}
			elseif (!isset($link['activate'])
			&& $widgets_params['links']['items'][$id]['activate'])
			{
				$widgets_params['links']['items'][$id]['activate'] = 0;
			}

			// Titre.
			if (isset($link['title']))
			{
				$locale_text = utils::setLocaleText(
					$link['title'],
					$widgets_params['links']['items'][$id]['title'],
					128
				);
				if ($locale_text['empty'])
				{
					self::report(sprintf(
						$warning_message_title,
						utils::getLocale($widgets_params['links']['items'][$id]['title'])
					));
				}
			}

			// URL.
			if (isset($link['url'])
			&& $link['url'] != $widgets_params['links']['items'][$id]['url'])
			{
				$widgets_params['links']['items'][$id]['url'] = $link['url'];
			}

			// Description.
			if (isset($link['desc']))
			{
				utils::setLocaleText(
					$link['desc'],
					$widgets_params['links']['items'][$id]['desc'],
					128
				);
			}
		}

		// Ordre des liens.
		$new_positions = (string) $_POST['serial'];
		if (preg_match('`(?:i\[\]=\d{1,2}&)*(?:i\[\]=\d{1,2})`', $new_positions))
		{
			$new_positions = str_replace('i[]=', '', $new_positions);
			$new_positions = explode('&', $new_positions);
			$newpos = array();
			foreach ($new_positions as $pos)
			{
				if (isset($widgets_params['links']['items'][$pos]))
				{
					$newpos[] = $widgets_params['links']['items'][$pos];
				}
			}
			$widgets_params['links']['items'] = $newpos;
		}

		self::_updateWidget($widgets_params);
	}

	/**
	 * Widget 'liste des membres'.
	 *
	 * @return void
	 */
	public static function members()
	{
		if (empty($_POST))
		{
			return;
		}

		$w_params = utils::$config['pages_params'];

		// Nombre de membres par page.
		if (isset($_POST['nb_per_page']) && strlen($_POST['nb_per_page']) < 4
		&& (int) $_POST['nb_per_page'] > 0)
		{
			if ($_POST['nb_per_page'] != $w_params['members']['nb_per_page'])
			{
				$w_params['members']['nb_per_page'] = $_POST['nb_per_page'];
			}
		}

		// Critère de tri.
		if (isset($_POST['order_by']) && isset($_POST['ascdesc'])
		&& in_array($_POST['order_by'], array('user_lastvstdt', 'user_crtdt', 'user_login'))
		&& in_array($_POST['ascdesc'], array('ASC', 'DESC')))
		{
			if ($_POST['order_by'] . ' ' . $_POST['ascdesc']
			!= $w_params['members']['order_by'])
			{
				$w_params['members']['order_by']
					= $_POST['order_by'] . ' ' . $_POST['ascdesc'];
			}
		}

		// Informations à afficher.
		foreach (array('show_crtdt', 'show_lastvstdt', 'show_title') as $item)
		{
			if (isset($_POST[$item])
			&& !$w_params['members'][$item])
			{
				$w_params['members'][$item] = 1;
			}
			else if (!isset($_POST[$item])
			&& $w_params['members'][$item])
			{
				$w_params['members'][$item] = 0;
			}
		}

		self::_updateWidget($w_params, 'pages');
	}

	/**
	 * Widget 'navigation'.
	 *
	 * @return void
	 */
	public static function navigation()
	{
		if (empty($_POST))
		{
			return;
		}

		$widgets_params = utils::$config['widgets_params'];

		// Titre.
		if (isset($_POST['title']))
		{
			utils::setLocaleText($_POST['title'], $widgets_params['navigation']['title'], 128);
		}

		// Éléments de navigation.
		foreach (array('categories', 'neighbours', 'search') as $item)
		{
			if (isset($_POST[$item])
			&& !$widgets_params['navigation']['items'][$item])
			{
				$widgets_params['navigation']['items'][$item] = 1;
			}
			else if (!isset($_POST[$item])
			&& $widgets_params['navigation']['items'][$item])
			{
				$widgets_params['navigation']['items'][$item] = 0;
			}
		}

		self::_updateWidget($widgets_params);
	}

	/**
	 * Widget 'qui est en ligne ?'.
	 *
	 * @return void
	 */
	public static function onlineUsers()
	{
		if (empty($_POST))
		{
			return;
		}

		$widgets_params = utils::$config['widgets_params'];

		// Titre.
		if (isset($_POST['title']))
		{
			utils::setLocaleText($_POST['title'], $widgets_params['online_users']['title'], 128);
		}

		// Durée pendant laquelle un utilisateur est considéré
		// comme en ligne depuis sa dernière visite.
		if (isset($_POST['duration']) && preg_match('`^[1-9]\d{0,3}$`', $_POST['duration'])
		&& $_POST['duration'] != $widgets_params['online_users']['params']['duration'])
		{
			$widgets_params['online_users']['params']['duration'] = $_POST['duration'];
		}

		self::_updateWidget($widgets_params);
	}

	/**
	 * Widget 'options d'affichage'.
	 *
	 * @return void
	 */
	public static function options()
	{
		if (empty($_POST))
		{
			return;
		}

		$widgets_params = utils::$config['widgets_params'];

		// Titre.
		if (isset($_POST['title']))
		{
			utils::setLocaleText($_POST['title'], $widgets_params['options']['title'], 128);
		}

		// Éléments.
		$items = array(
			'image_size', 'nb_thumbs', 'order_by', 'recent', 'styles', 'thumbs_albums',
			'thumbs_category_title', 'thumbs_comments', 'thumbs_date', 'thumbs_filesize',
			'thumbs_hits', 'thumbs_image_title', 'thumbs_images', 'thumbs_size', 'thumbs_votes'
		);
		foreach ($items as $item)
		{
			if (isset($_POST[$item])
			&& !$widgets_params['options']['items'][$item])
			{
				$widgets_params['options']['items'][$item] = 1;
			}
			else if (!isset($_POST[$item])
			&& $widgets_params['options']['items'][$item])
			{
				$widgets_params['options']['items'][$item] = 0;
			}
		}

		self::_updateWidget($widgets_params);
	}

	/**
	 * Widgets et pages personnalisés.
	 *
	 * @param string $type
	 *	'widgets' ou 'pages'.
	 * @return void
	 */
	public static function perso($type)
	{
		// Message de confirmation pour la création
		// d'un nouveau widget ou d'une nouvelle page.
		if (isset($_GET['confirm']) && $_GET['confirm'] == 'new')
		{
			self::report('success:' . (($type == 'pages')
				? 'La page a été créée.'
				: 'Le widget a été créé.'));
		}

		$w_params = utils::$config[$type . '_params'];

		if (empty($_POST) || !isset($_POST['title']) || !isset($_POST['text'])
		|| !isset($w_params['perso_' . $_GET['perso']]))
		{
			return;
		}

		// Titre.
		$locale_text = utils::setLocaleText($_POST['title'],
			$w_params['perso_' . $_GET['perso']]['title'], 64, TRUE);
		if ($locale_text['empty'])
		{
			self::report('warning:' . __('Le titre doit contenir au moins 1 caractère.'));
		}
		else if ($locale_text['change'])
		{
			if ($type == 'pages')
			{
				$w_params['perso_' . $_GET['perso']]['url']
					= utils::genURLName(utils::removeTagsLangs($locale_text['data']));
			}
		}

		// Type de contenu.
		if (isset($_POST['file'])
		&& $w_params['perso_' . $_GET['perso']]['type'] != 'file')
		{
			$w_params['perso_' . $_GET['perso']]['type'] = 'file';
		}
		else if (!isset($_POST['file'])
		&& $w_params['perso_' . $_GET['perso']]['type'] == 'file')
		{
			$w_params['perso_' . $_GET['perso']]['type'] = 'text';
		}

		// Fichier.
		if (isset($_POST['filename'])
		&& preg_match('`^[-a-z0-9_]{1,64}\.php$`', $_POST['filename'])
		&& (!isset($w_params['perso_' . $_GET['perso']]['file'])
			|| $_POST['filename'] != $w_params['perso_' . $_GET['perso']]['file']))
		{
			$w_params['perso_' . $_GET['perso']]['file'] = $_POST['filename'];
		}

		// Texte.
		utils::setLocaleText($_POST['text'], $w_params['perso_' . $_GET['perso']]['text'], 2000);

		self::_updateWidget($w_params, $type);
	}

	/**
	 * Modification du widget 'statistiques des catégories'.
	 *
	 * @return void
	 */
	public static function statsCategories()
	{
		if (empty($_POST))
		{
			return;
		}

		$widgets_params = utils::$config['widgets_params'];

		// Titre.
		if (isset($_POST['title']))
		{
			utils::setLocaleText($_POST['title'],
				$widgets_params['stats_categories']['title'], 128);
		}

		// Stats.
		$items = array(
			'images', 'albums', 'filesize', 'recents', 'hits', 'comments', 'votes'
		);
		foreach ($items as $item)
		{
			if (isset($_POST[$item])
			&& !$widgets_params['stats_categories']['items'][$item])
			{
				$widgets_params['stats_categories']['items'][$item] = 1;
			}
			else if (!isset($_POST[$item])
			&& $widgets_params['stats_categories']['items'][$item])
			{
				$widgets_params['stats_categories']['items'][$item] = 0;
			}
		}

		self::_updateWidget($widgets_params);
	}

	/**
	 * Modification du widget 'statistiques des images'.
	 *
	 * @return void
	 */
	public static function statsImages()
	{
		if (empty($_POST))
		{
			return;
		}

		$widgets_params = utils::$config['widgets_params'];

		// Titre.
		if (isset($_POST['title']))
		{
			utils::setLocaleText($_POST['title'],
				$widgets_params['stats_images']['title'], 128);
		}

		// Stats.
		$items = array(
			'favorites', 'filesize', 'size', 'hits', 'comments', 'votes',
			'added_date', 'added_by', 'created_date'
		);
		foreach ($items as $item)
		{
			if (isset($_POST[$item])
			&& !$widgets_params['stats_images']['items'][$item])
			{
				$widgets_params['stats_images']['items'][$item] = 1;
			}
			else if (!isset($_POST[$item])
			&& $widgets_params['stats_images']['items'][$item])
			{
				$widgets_params['stats_images']['items'][$item] = 0;
			}
		}

		self::_updateWidget($widgets_params);
	}

	/**
	 * Modification du widget 'tags'.
	 *
	 * @return void
	 */
	public static function tags()
	{
		if (empty($_POST))
		{
			return;
		}

		$widgets_params = utils::$config['widgets_params'];

		// Titre.
		if (isset($_POST['title']))
		{
			utils::setLocaleText($_POST['title'], $widgets_params['tags']['title'], 128);
		}

		// Nombre maximum de tags.
		if (isset($_POST['max_tags']) && preg_match('`^\d{1,2}$`', $_POST['max_tags'])
		&& $_POST['max_tags'] != $widgets_params['tags']['params']['max_tags'])
		{
			$widgets_params['tags']['params']['max_tags'] = $_POST['max_tags'];
		}

		self::_updateWidget($widgets_params);
	}

	/**
	 * Modification du widget 'console utilisateur'.
	 *
	 * @return void
	 */
	public static function user()
	{
		if (empty($_POST))
		{
			return;
		}

		$widgets_params = utils::$config['widgets_params'];

		// Titre.
		if (isset($_POST['title']))
		{
			utils::setLocaleText($_POST['title'], $widgets_params['user']['title'], 128);
		}

		self::_updateWidget($widgets_params);
	}

	/**
	 * Modification du widget 'carte du monde'.
	 *
	 * @return void
	 */
	public static function worldmap()
	{
		if (empty($_POST))
		{
			return;
		}

		$w_params = utils::$config['pages_params'];

		// Options.
		foreach (array('center_lat', 'center_long', 'zoom') as $item)
		{
			if (isset($_POST[$item]) && preg_match('`^-?\d{1,3}(?:\.\d{1,40})?$`', $_POST[$item])
			&& $w_params['worldmap'][$item] != $_POST[$item])
			{
				$w_params['worldmap'][$item] = (float) $_POST[$item];
			}
		}

		self::_updateWidget($w_params, 'pages');
	}



	/**
	 * Met à jour les paramètres de chaque widget.
	 *
	 * @param string $type
	 *	'widgets' ou 'pages'.
	 * @return void
	 */
	private static function _updateWidget($w_params, $type = 'widgets')
	{
		// Si aucune modification, inutile d'aller plus loin.
		if ($w_params == utils::$config[$type . '_params'])
		{
			return;
		}

		// On effectue la mise à jour des paramètres.
		$params = array($type . '_params' => serialize($w_params));
		$sql = 'UPDATE ' . CONF_DB_PREF . 'config
				   SET conf_value = :' . $type . '_params
				 WHERE conf_name = "' . $type . '_params"';
		if (utils::$db->prepare($sql) === FALSE
		|| utils::$db->executeExec($params) === FALSE)
		{
			self::report('error:' . utils::$db->msgError);
			return;
		}

		// Mise à jour du tableau de configuration.
		utils::$config[$type . '_params'] = $w_params;

		// Mise à jour réussie.
		self::report('success:' . __('Modifications enregistrées.'));
		self::report('success_p:' . __('Les autres modifications ont été enregistrées.'));
	}
}



/**
 * Méthodes de template pour l'ensemble de l'administration.
 */
class tplAdmin
{
	/**
	 * Doit-on afficher le champ de formulaire où une erreur s'est produite ?
	 *
	 * @return boolean
	 */
	public function disFieldError()
	{
		return (bool) admin::$fieldError;
	}

	/**
	 * Retournle le nom du champ de formulaire où une erreur s'est produite.
	 *
	 * @return boolean
	 */
	public function getFieldError()
	{
		return admin::$fieldError;
	}

	/**
	 * L'aide contextuelle doit-elle être affichée ?
	 *
	 * @return boolean
	 */
	public function disHelp()
	{
		static $file_exists = -1;

		if ($file_exists === -1)
		{
			$second = (isset($_GET['widget']))
				? '_' . str_replace('-', '_', $_GET['widget'])
				: '';
			$file_exists = file_exists(GALLERY_ROOT
				. '/locale/' . utils::$userLang
				. '/help/' . str_replace('-', '_', $_GET['section']) . $second . '.html'
			);
		}

		return $file_exists;
	}

	/**
	 * Affiche l'aide contextuelle.
	 *
	 * @return void
	 */
	public function getHelp()
	{
		$second = (isset($_GET['widget']))
			? '_' . str_replace('-', '_', $_GET['widget'])
			: '';
		include_once(GALLERY_ROOT
			. '/locale/' . utils::$userLang
			. '/help/' . str_replace('-', '_', $_GET['section']) . $second . '.html'
		);
	}

	/**
	 * L'élément de configuration $item est-il désactivé par le template ?
	 *
	 * @return boolean
	 */
	public function disDisabledConfig($item = '')
	{
		if ($item == '')
		{
			return !empty(admin::$tplDisabledConfig);
		}

		else
		{
			return isset(admin::$tplDisabledConfig[$item])
				&& admin::$tplDisabledConfig[$item] == 0;
		}
	}

	/**
	 * L'utilisateur possède-t-il la permission pour $item ?
	 *
	 * @param string $item
	 * @return boolean
	 */
	public function disPerm($item)
	{
		switch ($item)
		{
			// L'utilisateur dispose-t-il de la permission
			// d'accès à tous les albums protégés ?
			case 'access_all' :
				return auth::$perms['admin']['perms']['access_mode'] == 1;

			case 'ftp' :
			case 'albums_edit' :
			case 'albums_modif' :
			case 'albums_pending' :
			case 'albums_add' :
			case 'comments_edit' :
			case 'comments_options' :
			case 'admin_votes' :
			case 'tags' :
			case 'users_members' :
			case 'users_options' :
			case 'settings_pages' :
			case 'settings_widgets' :
			case 'settings_functions' :
			case 'settings_options' :
			case 'settings_themes' :
			case 'settings_maintenance' :
			case 'infos_incidents' :
				return auth::$perms['admin']['perms'][$item]
					|| auth::$perms['admin']['perms']['all'];

			case 'albums' :
				return $this->disPerm('albums_edit')
					|| $this->disPerm('albums_modif')
					|| $this->disPerm('albums_pending');

			case 'comments' :
				return $this->disPerm('comments_edit')
					|| $this->disPerm('comments_options');

			case 'objects' :
				return $this->disPerm('albums')
					|| $this->disPerm('comments')
					|| $this->disPerm('admin_votes')
					|| $this->disPerm('tags')
					|| $this->disPerm('users');

			case 'settings' :
				return $this->disPerm('settings_pages')
					|| $this->disPerm('settings_widgets')
					|| $this->disPerm('settings_functions')
					|| $this->disPerm('settings_options')
					|| $this->disPerm('settings_themes')
					|| $this->disPerm('settings_maintenance');

			case 'users' :
				return $this->disPerm('users_members')
					|| $this->disPerm('users_groups')
					|| $this->disPerm('users_options');

			case 'users_groups' :
				return auth::$infos['user_id'] == 1;
		}
	}

	/**
	 * Retourne l'élément de l'utilisateur authentifié $item.
	 *
	 * @param string $item
	 * @return string
	 */
	public function getAuthUser($item)
	{
		switch ($item)
		{
			// Identifiant.
			case 'id' :
				return (int) auth::$infos['user_id'];

			// Identifiant de connexion.
			case 'login' :
				return utils::tplProtect(auth::$infos['user_login']);
		}
	}

	/**
	 * Retourne une chaîne limitée en longueur à $limit caractères.
	 *
	 * @param string $item
	 * @param integer $limit
	 * @return string
	 */
	public function getStrLimit($str, $limit = 50)
	{
		$str = utils::tplProtect($str, TRUE);
		$str = utils::strLimit($str, $limit);

		return utils::tplProtect($str);
	}

	/**
	 * Indique que l'on peut afficher les erreurs à partir de maintenant.
	 *
	 * @return void
	 */
	public function displayErrors()
	{
		error::displayErrors();
	}

	/**
	 * L'élément admin $item doit-il être affiché ?
	 *
	 * @param string $item
	 * @return boolean
	 */
	public function disAdmin($item)
	{
		switch ($item)
		{
			// Configuration.
			case 'exec_time' :
			case 'users' :
				return (bool) utils::$config[$item];

			// Langues d'édition.
			case 'langs_edition' :
				return $_GET['section'] == 'album'
					|| $_GET['section'] == 'category'
					|| $_GET['section'] == 'edit-album'
					|| $_GET['section'] == 'edit-category'
					|| $_GET['section'] == 'edit-image'
					|| $_GET['section'] == 'group'
					|| $_GET['section'] == 'images-pending'
					|| $_GET['section'] == 'mass-edit-album'
					|| $_GET['section'] == 'new-group'
					|| $_GET['section'] == 'new-page'
					|| $_GET['section'] == 'new-widget'
					|| $_GET['section'] == 'options-gallery'
					|| $_GET['section'] == 'options-descriptions'
					|| ($_GET['section'] == 'page'
						&& isset($_GET['page'])
						&& ($_GET['page'] == 'perso' || $_GET['page'] == 'contact'))
					|| $_GET['section'] == 'users-options'
					|| $_GET['section'] == 'widget';

			// Superadmin.
			case 'superadmin' :
				return auth::$infos['user_id'] == 1;
		}
	}

	/**
	 * Retourne l'élément admin $item.
	 *
	 * @param string $item
	 * @return string
	 */
	public function getAdmin($item)
	{
		switch ($item)
		{
			// Base pour tout URL de l'admin.
			case 'admin_base_url' :
				return substr(utils::genURL('1'), 0, -1);

			// Nom du répertoire d'administration.
			case 'admin_dir' :
				return basename(dirname(__FILE__));

			// Chemin absolu du répertoire d'administration.
			case 'admin_abs_path' :
				return utils::tplProtect(GALLERY_ROOT)
					. '/' . $this->getAdmin('admin_dir');

			// Chemin du répertoire d'administration.
			case 'admin_path' :
				return utils::tplProtect(CONF_GALLERY_PATH)
					. '/' . $this->getAdmin('admin_dir');

			// Nouveau jeton anti-CSRF.
			case 'anticsrf' :
				return utils::tplProtect(utils::$anticsrfToken);

			// Jeu de caractères.
			case 'charset' :
				return utils::tplProtect(CONF_CHARSET);

			// La date courante.
			case 'current_date' :
				return utils::tplProtect(
					utils::localeTime(__('%A %d %B %Y'))
				);

			// Temps d'exécution de la page.
			case 'exec_time' :
				$total_time = microtime(TRUE) - START_TIME;
				$message = ($total_time > 1)
					? __('page générée en %.3f secondes')
					: __('page générée en %.3f seconde');
				return utils::numeric(sprintf($message, $total_time));

			// Nom du fichier de template.
			case 'file' :
				return admin::$tplFile;

			// Nom d'hôte du serveur.
			case 'gallery_host' :
				return GALLERY_HOST;

			// Chemin de la galerie.
			case 'gallery_path' :
				return utils::tplProtect(CONF_GALLERY_PATH);

			// Géolocalisation : type de carte.
			case 'geoloc_type' :
				return utils::tplProtect(utils::$config['geoloc_type']);

			// Contenu de l'aide pour les champs acceptant du HTML.
			case 'help_html_content' :
				$allowed_tags = implode('&gt;, &lt;', utils::allowedTags());
				$allowed_attrs = implode(', ', array_keys(utils::allowedAttrs()));
				$text = __('Liste des balises HTML autorisées :');
				$text .= '<br /><pre>&lt;' . $allowed_tags . '&gt;.</pre>';
				$text .= __('Liste des attributs de balises autorisés :');
				$text .= '<br /><pre>' . $allowed_attrs . '.</pre>';
				return $text;

			// Balises HTML autorisées dans les champs textes ?
			case 'html_filter' :
				return (bool) utils::$config['html_filter'];

			// Code de la langue de l'utilisateur courant.
			case 'lang_current' :
				return utils::tplProtect(utils::$userLang);

			// Code de la langue par défaut.
			case 'lang_default_code' :
				return utils::tplProtect(CONF_DEFAULT_LANG);

			// Propulsé par.
			case 'powered_by' :
				return sprintf(__('propulsé par %s'),
					'<a class="ex" href="http://www.igalerie.org/">iGalerie</a>');

			// Paramètre d'URL "q".
			case 'q' :
				return isset($_GET['q']) ? utils::php2js($_GET['q']) : '';

			// Requête sans le numéro de page.
			case 'section_request' :
				return admin::$sectionRequest;

			// Hash de l'identifiant de session.
			case 'session_token' :
				return utils::tplProtect(utils::$cookieSession->read('token'));

			// Nom de la feuille de style CSS.
			case 'style_name' :
				return utils::tplProtect(utils::$config['admin_style']);

			// Chemin du fichier de la feuille de style CSS.
			case 'style_file' :
				return $this->getAdmin('style_path') . '/'
					. utils::tplProtect(utils::$config['admin_style']) . '.css';

			// Chemin du répertoire de la feuille de style CSS.
			case 'style_path' :
				return $this->getAdmin('template_path') . utils::tplProtect(
					'/style/' . utils::$config['admin_style']
				);

			// Nom du template.
			case 'template_name' :
				return utils::tplProtect(utils::$config['admin_template']);

			// Chemin du template.
			case 'template_path' :
				return utils::tplProtect(CONF_GALLERY_PATH
					. '/' . $this->getAdmin('admin_dir')
					. '/template/'
					. utils::$config['admin_template']
				);

			// Titre de la section.
			case 'title' :
				return admin::$pageTitle;

			// Valeur formatée de la directive PHP 'upload_max_filesize'.
			case 'upload_max_filesize_formated' :
				return utils::tplProtect(
					utils::filesize($this->getAdmin('upload_max_filesize_value'))
				);

			// Valeur entière de la directive PHP 'upload_max_filesize'.
			case 'upload_max_filesize_value' :
				return utils::tplProtect(utils::uploadMaxFilesize());
		}
	}

	/**
	 * Informations détaillées des requêtes SQL.
	 *
	 * @return string
	 */
	public function getDebugSQL()
	{
		return template::debugSQL();
	}

	/**
	 * Localisation de textes pour variables Javascript.
	 *
	 * @param string $text
	 *	Texte à protéger.
	 * @return string
	 */
	public function getL10nJS($text)
	{
		return utils::php2js(wordwrap($text, 60, "\n"));
	}

	/**
	 * L'élément de langue $item doit-il être affiché ?
	 *
	 * @param string $item
	 * @return boolean
	 */
	public function disLang($item)
	{
		switch ($item)
		{
			case 'current' :
				return key(utils::$config['locale_langs']) == utils::$userLang;

			case 'default' :
				return key(utils::$config['locale_langs']) == CONF_DEFAULT_LANG;

			case 'selected' :
				return (bool) strstr(
					auth::$infos['user_prefs']['langs_edition'],
					key(utils::$config['locale_langs'])
				);
		}
	}

	/**
	 * Retourne l'élément de langue $item.
	 *
	 * @param string $item
	 * @return string
	 */
	public function getLang($item)
	{
		switch ($item)
		{
			case 'code' :
				return utils::tplProtect(key(utils::$config['locale_langs']));

			case 'name' :
				return utils::tplProtect(current(utils::$config['locale_langs']));
		}
	}

	/**
	 * Y a-t-il une prochaine langue installée ?
	 *
	 * @return boolean
	 */
	public function nextLang()
	{
		static $next = -1;

		$this->_thumbForced =& $thumb_size;

		return template::nextObject(utils::$config['locale_langs'], $next);
	}

	/**
	 * Retourne un URL de galerie.
	 *
	 * @param string $section
	 *	Section à convertir en URL.
	 * @return string
	 */
	public function getGalleryLink($section = NULL)
	{
		return utils::genGalleryURL($section);
	}

	/**
	 * Retourne un URL admin.
	 *
	 * @param string $section
	 *	Section à convertir en URL.
	 * @return string
	 */
	public function getLink($section)
	{
		return utils::genURL($section);
	}

	/**
	 * Inclusion de page.
	 *
	 * @param string $inc
	 *	Page à inclure.
	 * @return void
	 */
	public function inc($inc)
	{
		$tpl =& $this;
		switch ($inc)
		{
			case 'page' :
				$inc = $tpl->getAdmin('admin_abs_path') . '/template/'
					. $tpl->getAdmin('template_name') . '/' . admin::$tplFile . '.tpl.php';
				break;

			case 'style_header' :
				$inc = $tpl->getAdmin('admin_abs_path') . '/template/'
					. $tpl->getAdmin('template_name') . '/style/'
					. $tpl->getAdmin('style_name') . '/head.php';
				break;
		}
		include $inc;
	}

	/**
	 * L'élément de rapport $item doit-il être affiché ?
	 *
	 * @param string $item
	 * @return boolean
	 */
	public function disReport($item = '')
	{
		switch ($item)
		{
			case 'error' :
			case 'warning' :
				return !empty(admin::$report[$item]);

			case 'success' :

				// Succès total.
				if ((empty(admin::$report['error'])
				&& empty(admin::$report['warning']))
				&& isset(admin::$report['success']))
				{
					return TRUE;
				}

				// Succès partiel.
				if ((!empty(admin::$report['error'])
				|| !empty(admin::$report['warning']))
				&& isset(admin::$report['success_p']))
				{
					return TRUE;
				}

				return FALSE;

			default :
				return $this->disReport('error')
					|| $this->disReport('warning')
					|| $this->disReport('success');
		}
	}

	/**
	 * Retourne l'élément de rapport $item.
	 *
	 * @param string $item
	 * @return string
	 */
	public function getReport($item)
	{
		$i =& admin::$report[$item];
		$message = (is_array($i)) ? current($i) : $i;

		switch ($item)
		{
			// Avertissements et erreurs.
			case 'error' :
			case 'warning' :
				return nl2br(utils::tplProtect($message));

			// Succès.
			case 'success' :

				// Succès total.
				if ((empty(admin::$report['error'])
				&& empty(admin::$report['warning']))
				&& isset(admin::$report['success']))
				{
					return nl2br(utils::tplProtect(admin::$report['success']));
				}

				// Succès partiel.
				if ((!empty(admin::$report['error'])
				|| !empty(admin::$report['warning']))
				&& isset(admin::$report['success_p']))
				{
					return nl2br(utils::tplProtect(admin::$report['success_p']));
				}
		}
	}

	/**
	 * Y a-t-il un prochaine message du rapport ?
	 *
	 * @return boolean
	 */
	public function nextReport($item)
	{
		static $next = -1;

		switch ($item)
		{
			case 'error' :
			case 'warning' :
				return template::nextObject(admin::$report[$item], $next);
		}
	}

	/**
	 * L'option de filigrane $item doit-elle être affichée ?
	 *
	 * @param string $item
	 * @return boolean
	 */
	public function disWatermarkOption($item)
	{
		return template::disWatermarkOption($item, admin::$watermarkParams);
	}

	/**
	 * Retourne le paramètre de filigrane $item.
	 *
	 * @param string $item
	 * @return string
	 */
	public function getWatermarkOption($item)
	{
		return template::getWatermarkOption($item, admin::$watermarkParams);
	}



	/**
	 * Génère et retourne une liste HTML pour les options d'affichage.
	 *
	 * @param string $section
	 *	Section courante.
	 * @param string $pref
	 *	Préférence d'affichage.
	 * @param string $default
	 *	Valeur par défaut.
	 * @param array $p
	 *	Liste des options disponibles.
	 * @return string
	 */
	protected function _displayOptionsList($section, $pref, $default, $p)
	{
		$html = '';

		// Si l'option d'affichage existe dans les préférences
		// de l'utilisateur, on prend la valeur correspondante,
		// sinon ce sera celle par défaut qui sera utilisée.
		if (isset(auth::$infos['user_prefs'][$section][$pref])
		&& isset($p[auth::$infos['user_prefs'][$section][$pref]]))
		{
			$default = auth::$infos['user_prefs'][$section][$pref];
		}

		foreach ($p as $value => &$text)
		{
			$selected = ($value == $default)
				? ' selected="selected"'
				: '';

			$html .= '<option' . $selected . ' value="' . $value . '">';
			$html .= $text . '</option>';
		}
		return $html;
	}

	/**
	 * Retourne l'élément de recherche $item.
	 * Éléments communs à tous les moteurs de recherche.
	 *
	 * @param string $item
	 * @param integer $year_start
	 *	Année de début.
	 * @return string
	 */
	protected function _getSearch($item, $year_start = 2000)
	{
		switch ($item)
		{
			// Rechercher tous les mots.
			case 'all_words' :
				return (!isset($_GET['search_query']) || isset($_GET['search_all_words']))
					? ' checked="checked"'
					: '';

			// Recherche par date.
			case 'date' :
				return (isset($_GET['search_date']))
					? ' checked="checked"'
					: '';

			// Recherche par date : jours.
			case 'date_end_day' :
			case 'date_start_day' :
				$options = '';
				for ($i = 1; $i <= 31; $i++)
				{
					$selected = ((isset($_GET['search_' . $item])
					&& $_GET['search_' . $item] == $i)
					|| !isset($_GET['search_' . $item]) && $i == date('j'))
						? ' selected="selected"'
						: '';
					$day = str_pad($i, 2, 0, STR_PAD_LEFT);
					$options .= '<option' . $selected
						. ' value="' . $day . '">' . $day . '</option>';
				}
				return $options;

			// Recherche par date : mois.
			case 'date_end_month' :
			case 'date_start_month' :
				$options = '';
				for ($i = 1; $i <= 12; $i++)
				{
					$selected = ((isset($_GET['search_' . $item])
					&& $_GET['search_' . $item] == $i)
					|| !isset($_GET['search_' . $item]) && $i == date('n'))
						? ' selected="selected"'
						: '';
					$month = str_pad($i, 2, 0, STR_PAD_LEFT);
					$options .= '<option' . $selected
						. ' value="' . $month . '">'
						. utils::localeTime('%B', date('Y-' . $month . '-01'))
						. '</option>';
				}
				return $options;

			// Recherche par date : années.
			case 'date_end_year' :
			case 'date_start_year' :
				$options = '';
				for ($i = $year_start; $i <= date('Y'); $i++)
				{
					$selected = (isset($_GET['search_' . $item])
					&& $_GET['search_' . $item] == $i
					|| !isset($_GET['search_' . $item]) && $i == date('Y'))
						? ' selected="selected"'
						: '';
					$options .= '<option' . $selected
						. ' value="' . $i . '">' . $i . '</option>';
				}
				return $options;

			// Requête.
			case 'query' :
				return (isset($_GET['search_query']))
					? utils::tplProtect($_GET['search_query'])
					: '';

			// Lien vers la section courante sans la recherche.
			case 'section_link' :
				return utils::genURL(
					preg_replace('`/(?:search/.+$)`', '', admin::$sectionRequest)
				);

			// Utilisateurs.
			case 'users' :
				$selected = (!isset($_GET['search_user'])
					|| !preg_match('`^\d{1,11}$`', $_GET['search_user']))
					? ' selected="selected"'
					: '';
				$list = '<option' . $selected . ' value="all">*' . __('tous') . '</option>';
				users::$usersList = array(2 => '*' . __('invité')) + users::$usersList;
				foreach (users::$usersList as $id => &$login)
				{
					$selected = (isset($_GET['search_user']) && $_GET['search_user'] == $id)
						? ' selected="selected"'
						: '';
					$list .= '<option' . $selected . ' value="' . (int) $id . '">'
						. utils::tplProtect($login) . '</option>';
				}
				return $list;
		}
	}

	/**
	 * Retourne l'élément $item de la vignette.
	 *
	 * @param array $item
	 *	Élément à retourner.
	 * @param array $i
	 *	Informations utiles de l'image.
	 * @param array $thumb_forced
	 *	Dimensions du cadre que doit avoir la vignette.
	 * @return string
	 */
	protected function _getThumbImage(&$item, &$i, $thumb_forced)
	{
		switch ($item)
		{
			// Centrage de la vignette par CSS.
			case 'thumb_center' :
				$tb = img::getThumbSize($i, 'img', $thumb_forced);
				return img::thumbCenter('img', $tb['width'],
					$tb['height'], $thumb_forced);

			// Dimensions de la vignette.
			case 'thumb_size' :
				$tb = img::getThumbSize($i, 'img', $thumb_forced);
				return 'width="' . $tb['width'] . '" height="' . $tb['height'] . '"';

			// Emplacement de la vignette de l'image.
			case 'thumb_src' :
				return utils::tplProtect(template::getThumbSrc('img', $i));
		}
	}

	/**
	 * Retourne les éléments de la liste des critères de tri.
	 *
	 * @param array $options
	 * @param string $regexp
	 * @param string $config
	 * @param string $item
	 * @return string
	 */
	protected function _orderby($options, $regexp, $config, $item)
	{
		$o = '';
		preg_match('`^' . $regexp . '$`i', $config, $m);
		$item = substr($item, -1, 1);
		foreach ($options as $field => &$name)
		{
			$selected = (isset($m[$item]) && $m[$item] == $field)
				? ' selected="selected"'
				: '';
			$o .= '<option' . $selected
				. ' value="' . $field . '">' . $name . '</option>';
		}
		return $o;
	}

	/**
	 * L'élément $item de l'utilisateur doit-il être affiché ?
	 *
	 * @param string $item
	 * @param array $i
	 * @return boolean
	 */
	public function _disUser($item, $i)
	{
		switch ($item)
		{
			// Administrateur ?
			case 'admin' :
				return (bool) $i['group_admin'];

			// Statut.
			case 'activate' :
				return $i['user_status'] == '1';

			case 'deactivate' :
				return $i['user_status'] == '0';

			case 'pending' :
				return $i['user_status'] == '-1';

			// Superadmin ?
			case 'superadmin' :
				return $i['user_id'] == 1;
		}
	}

	/**
	 * Retourne l'information $item de l'utilisateur
	 * à partir des informations contenues $i.
	 *
	 * @param string $item
	 * @param array $i
	 * @return string
	 */
	protected function _getUser($item, $i)
	{
		switch ($item)
		{
			// Vignette de l'avatar.
			case 'avatar_thumb_src' :
				return ($i['user_avatar'])
					? CONF_GALLERY_PATH . '/users/avatars/user'
						. (int) $i['user_id'] . '_thumb.jpg'
					: $this->getAdmin('style_path') . '/avatar-default.png';

			// Dates de création du compte.
			case 'crtdt' :
				return utils::tplProtect(
					utils::localeTime(__('%A %d %B %Y à %H:%M:%S'), $i['user_' . $item])
				);

			// IPs de création du compte et de dernière visite.
			case 'crtip' :
			case 'lastvstip' :
				return (empty($i['user_' . $item]))
					? '/'
					: utils::tplProtect(
						$i['user_' . $item]
					  );

			// Identifiant de l'utilisateur.
			case 'id' :
				return (int) $i['user_id'];

			// Lien vers la galerie.
			case 'gallery_link' :
				return $this->getGalleryLink('user/' . $i['user_id']);

			// Identifiant du groupe.
			case 'group_id' :
				return (int) $i['group_id'];

			// Nom du groupe.
			case 'group_name' :
				if ($i['group_id'] > 3 || $i['group_name'] != '')
				{
					return utils::tplProtect(utils::getLocale($i['group_name']));
				}
				return admin::getL10nGroupName($i['group_id']);

			// Titre de l'utilisateur.
			case 'group_title' :
				if ($i['group_id'] > 3 || $i['group_title'] != '')
				{
					return utils::tplProtect(utils::getLocale($i['group_title']));
				}
				return admin::getL10nGroupTitle($i['group_id']);

			// Date de dernier ajout d'une image et de dernière visite.
			case 'lastimgdt' :
			case 'lastvstdt' :
				return (empty($i['user_' . $item]) || $i['user_' . $item] === NULL)
					? '/'
					: utils::tplProtect(
						utils::localeTime(__('%A %d %B %Y à %H:%M:%S'), $i['user_' . $item])
					);

			// Identifiant de connexion.
			case 'login' :
				return utils::tplProtect($i['user_login']);

			// Nom de l'utilisateur.
			case 'name' :
				return utils::tplProtect($i['user_name']);

			// Statistiques.
			case 'nb_comments_pending' :
			case 'nb_comments_publish' :
			case 'nb_comments_unpublish' :
			case 'nb_images_pending' :
			case 'nb_images_publish' :
			case 'nb_images_unpublish' :
				return (int) $i[$item];

			// Statistiques avec lien vers la section correspondante.
			case 'nb_basket' :
				$nb = $i['nb_basket'];
				if ($nb == 0
				|| (!auth::$perms['admin']['perms']['albums_modif']
				 && !auth::$perms['admin']['perms']['albums_edit']
				 && !auth::$perms['admin']['perms']['all']))
				{
					return $nb;
				}
				$link = utils::genURL('category/1/user-basket/' . $i['user_id']);
				return '<a href="' . $link . '">' . $nb . '</a>';

			case 'nb_comments' :
				$nb = $this->_getUser('nb_comments_pending', $i)
					+ $this->_getUser('nb_comments_publish', $i)
					+ $this->_getUser('nb_comments_unpublish', $i);
				if ($nb == 0
				|| (!auth::$perms['admin']['perms']['comments_edit']
				 && !auth::$perms['admin']['perms']['all']))
				{
					return $nb;
				}
				$link = utils::genURL('comments-images/user/' . $i['user_id']);
				return '<a href="' . $link . '">' . $nb . '</a>';

			case 'nb_favorites' :
				$nb = $i['nb_favorites'];
				if ($nb == 0
				|| (!auth::$perms['admin']['perms']['albums_modif']
				 && !auth::$perms['admin']['perms']['albums_edit']
				 && !auth::$perms['admin']['perms']['all']))
				{
					return $nb;
				}
				$link = utils::genURL('category/1/user-favorites/' . $i['user_id']);
				return '<a href="' . $link . '">' . $nb . '</a>';

			case 'nb_images' :
				$nb = $this->_getUser('nb_images_publish', $i)
					+ $this->_getUser('nb_images_unpublish', $i);
				if ($nb == 0
				|| (!auth::$perms['admin']['perms']['albums_modif']
				 && !auth::$perms['admin']['perms']['albums_edit']
				 && !auth::$perms['admin']['perms']['all']))
				{
					return $nb;
				}
				$link = utils::genURL('category/1/user-images/' . $i['user_id']);
				return '<a href="' . $link . '">' . $nb . '</a>';

			case 'nb_logs' :
				$nb = (int) $i[$item];
				if ($nb == 0 || auth::$infos['user_id'] != 1)
				{
					return $nb;
				}
				$link = utils::genURL('logs/user/' . $i['user_id']);
				return '<a href="' . $link . '">' . $nb . '</a>';

			case 'nb_votes' :
				$nb = (int) $i[$item];
				if ($nb == 0
				|| (!auth::$perms['admin']['perms']['admin_votes']
				 && !auth::$perms['admin']['perms']['all']))
				{
					return $nb;
				}
				$link = utils::genURL('votes/user/' . $i['user_id']);
				return '<a href="' . $link . '">' . $nb . '</a>';

			// Message de statut.
			case 'status_msg' :
				switch ($i['user_status'])
				{
					case '-1' :
						return __('en attente');

					case '0' :
						return __('suspendu');

					case '1' :
						return __('activé');
				}
				break;
		}
	}
}

/**
 * Méthodes de template communes pour les pages de gestion des albums.
 */
class tplAlbums extends tplAdmin
{
	/**
	 * Retourne les limites de poids et de dimensions des images.
	 *
	 * @return mixed
	 */
	public function getLimits($item)
	{
		switch ($item)
		{
			// Poids maximum.
			case 'maxfilesize' :
				return (int) utils::$config['upload_maxfilesize'];

			// Dimensions maximum
			case 'maxsize' :
				return (int) utils::$config['upload_maxwidth']
					. ' x ' . (int) utils::$config['upload_maxheight'];
		}
	}

	/**
	 * Retourne le poids maximum que peuvent avoir les fichiers envoyés.
	 *
	 * @return integer
	 */
	public function getMaxFileSize()
	{
		return 1024 * (int) utils::$config['upload_maxfilesize'];
	}

	/**
	 * Retourne l'élément de filtre $item.
	 *
	 * @param string $item
	 * @return string
	 */
	public function getFilter($item)
	{
		switch ($item)
		{
			case 'section_link' :
				return utils::genURL(preg_replace(
					'`/(?:camera-(?:brand|model)|tag|user-(?:basket|favorites|images))/.+$`',
					'',
					admin::$sectionRequest
				));

			case 'text' :
				if (isset($_GET['filter']))
				{
					switch ($_GET['filter'])
					{
						case 'camera-brand' :
							return __('Images prises par un appareil photos %s');

						case 'camera-model' :
							return __('Images prises par le modèle d\'appareil photos %s');

						case 'tag' :
							return __('Images liées au tag %s');

						case 'user-basket' :
							return __('Panier de %s');

						case 'user-favorites' :
							return __('Favoris de %s');

						case 'user-images' :
							return __('Images de %s');
					}
				}
				break;

			case 'value' :
				if (isset($_GET['filter']))
				{
					switch ($_GET['filter'])
					{
						case 'camera-brand' :
							return utils::tplProtect(cameras::$infos['name']);

						case 'camera-model' :
							return utils::tplProtect(cameras::$infos['name']);

						case 'tag' :
							return utils::tplProtect(tags::$items[$_GET['tag_id']]['tag_name']);

						case 'user-basket' :
						case 'user-favorites' :
						case 'user-images' :
							$link = utils::genURL('user/' . $_GET['user_id']);
							$login = utils::tplProtect(users::$usersList[$_GET['user_id']]);
							return '<a href="' . $link . '">' . $login . '</a>';
					}
				}
				break;
		}
	}

	/**
	 * Génère un nom de répertoire temporaire.
	 *
	 * @return string
	 */
	public function getTempDir()
	{
		static $tempdir;

		return $tempdir
			? $tempdir
			: $tempdir = utils::tplProtect(utils::genKey());
	}

	/**
	 * L'élément de la catégorie courante  $item doit-il être affiché ?
	 *
	 * @param string $item
	 * @return boolean
	 */
	public function disCategoryInfo($item)
	{
		$i =& albums::$infos;

		return $this->_disCategoryInfo($item, $i);
	}

	/**
	 * Retourne l'information de la catégorie courante $item.
	 *
	 * @return string
	 */
	public function getCategoryInfo($item)
	{
		$i =& albums::$infos;
		$thumb_forced = 60;

		return $this->_getCategoryInfo($item, $i, $thumb_forced);
	}

	/**
	 * Y a-t-il des éléments dans la catégorie courante ?
	 *
	 * @return boolean
	 */
	public function disItems()
	{
		return (bool) albums::$items;
	}

	/**
	 * Retourne une valeur d'une propriété de la classe albums.
	 *
	 * @param array|string $property
	 * @return mixed
	 */
	public function getInfo($property)
	{
		return (is_array($property))
			? utils::tplProtect(albums::${$property[0]}[$property[1]])
			: utils::tplProtect(albums::${$property});
	}

	/**
	 * Retourne le plan de la galerie.
	 *
	 * @return string
	 */
	public function getMap()
	{
		// Filtres.
		$filter = '';
		if (isset($_GET['search']))
		{
			$filter = '/search/' . $_GET['search'];
		}
		if (isset($_GET['filter']))
		{
			switch ($_GET['filter'])
			{
				case 'camera-brand' :
				case 'camera-model' :
					$filter = '/' . $_GET['filter'] . '/' . $_GET['cam_id'];
					break;

				case 'tag' :
					$filter = '/tag/' . $_GET['tag_id'];
					break;

				case 'user-basket' :
				case 'user-favorites' :
				case 'user-images' :
					$filter = '/' . $_GET['filter'] . '/' . $_GET['user_id'];
					break;
			}
		}
		$filter = utils::tplProtect($filter);

		return template::mapSelect(albums::$mapCategories, array(
			'cat_one' => in_array($_GET['section'], array(
				'album', 'category',
				'mass-edit-album', 'mass-edit-category',
				'sort-album', 'sort-category'
			)),
			'class_id' => TRUE,
			'class_infos' => TRUE,
			'class_selected' => TRUE,
			'selected' => (isset($_GET['object_id'])) ? $_GET['object_id'] : 1,
			'value_tpl' => in_array($_GET['section'], array('new-thumb'))
				? $_GET['section'] . '/{ID}'
				: preg_replace('`(album|category)$`', '', $_GET['section']) . '{TYPE}/{ID}'
					. $filter
		));
	}

	/**
	 * Retourne l'élément $item de la liste des sections.
	 *
	 * @param string $item
	 * @return string
	 */
	public function getSectionsList($item)
	{
		switch ($item)
		{
			// Partie commune aux filtres des paramètres d'URL.
			case 'filter_url' :
				$url = $_GET['object_id'];
				if (isset($_GET['filter']) && substr($_GET['filter'], 0, 5) == 'user-')
				{
					$url .= '/' . $_GET['filter'] . '/' . $_GET['user_id'];
				}
				if (isset($_GET['filter']) && substr($_GET['filter'], 0, 7) == 'camera-')
				{
					$url .= '/' . $_GET['filter'] . '/' . $_GET['cam_id'];
				}
				if (isset($_GET['filter']) && $_GET['filter'] == 'tag')
				{
					$url .= '/tag/' . $_GET['tag_id'];
				}
				if (isset($_GET['search']))
				{
					$url .= '/search/' . $_GET['search'];
				}
				return $url;

			// Texte correspondant au type d'objets afficher.
			case 'objects_text' :
				if (isset($_GET['filter']))
				{
					switch ($_GET['filter'])
					{
						case 'camera-brand' :
						case 'camera-model' :
							return __('Images de l\'appareil photos');
						case 'tag' :
							return __('Images liées au tag');
						case 'user-basket' :
							return __('Panier de l\'utilisateur');
						case 'user-favorites' :
							return __('Favoris de l\'utilisateur');
						case 'user-images' :
							return __('Images de l\'utilisateur');
					}
				}
				if (isset($_GET['search']))
				{
					return (isset($_GET['search_type']) && $_GET['search_type'] == 'album')
						? __('Images de la recherche')
						: __('Objets de la recherche');
				}
				return ($_GET['section'] == 'album')
					? __('Images de l\'album')
					: __('Objets de la catégorie');
		}
	}

	/**
	 * Doit-on afficher l'élément de barre de position $item ?
	 *
	 * @return boolean
	 */
	public function disPosition($item)
	{
		switch ($item)
		{
			// Barre de position pour les filtres ?
			case 'filter' :
				return isset($_GET['filter']);

			// Barre de position normale ?
			case 'normal' :
				return !$this->disPosition('filter')
					&& !$this->disPosition('search');

			// Barre de position pour la recherche ?
			case 'search' :
				return isset($_GET['search']);
		}
	}

	/**
	 * Retourne les liens de la barre de position (fil d'ariane).
	 *
	 * @return string
	 */
	public function getPosition()
	{
		$position = template::getPosition(
			'',
			'category',
			albums::$infos['cat_type'],
			'',
			TRUE,
			FALSE,
			__('galerie'),
			albums::$parents,
			albums::$infos,
			albums::$parentPage,
			' / ',
			$_GET['object_id'] > 1
		);

		// Filtres de recherche.
		if (isset($_GET['filter']) && substr($_GET['filter'], 0, 5) == 'user-')
		{
			$position = preg_replace('`(\?q=(?:album|category)/\d+)`',
				'$1/' . $_GET['filter'] . '/' . $_GET['user_id'], $position);
		}
		if (isset($_GET['filter']) && $_GET['filter'] == 'tag')
		{
			$position = preg_replace('`(\?q=(?:album|category)/\d+)`',
				'$1/tag/' . $_GET['tag_id'], $position);
		}
		else if (isset($_GET['search']))
		{
			$position = preg_replace('`(\?q=(?:album|category)/\d+)`',
				'$1/search/' . $_GET['search'], $position);
		}

		return $position;
	}

	/**
	 * S'agit-il d'une recherche ?
	 *
	 * @return boolean
	 */
	public function disSearch()
	{
		return albums::$searchInit;
	}

	/**
	 * Retourne l'élément de recherche $item.
	 *
	 * @param string $item
	 * @return string
	 */
	public function getSearch($item)
	{
		switch ($item)
		{
			// Type d'objet à rechercher.
			case 'album' :
				return (!isset($_GET['search_query'])
					|| (isset($_GET['search_type']) && $_GET['search_type'] == 'album'))
					? ' checked="checked"'
					: '';

			case 'category' :
				return (isset($_GET['search_type']) && $_GET['search_type'] == 'category')
					? ' checked="checked"'
					: '';

			// Options : champs textes.
			case 'filesize_end' :
			case 'filesize_start' :
			case 'size_height_end' :
			case 'size_height_start' :
			case 'size_width_end' :
			case 'size_width_start' :
				return (isset($_GET['search_' . $item]))
					? utils::tplProtect($_GET['search_' . $item])
					: '';

			// Options : cases à cocher.
			case 'cat_path' :
			case 'date' :
			case 'filesize' :
			case 'image_path' :
			case 'size' :
				return (isset($_GET['search_' . $item]))
					? ' checked="checked"'
					: '';

			// Options : cases à cocher avec option cochée par défaut.
			case 'cat_url' :
			case 'cat_desc' :
			case 'cat_name' :
				return (!isset($_GET['search_query']) || isset($_GET['search_' . $item])
					|| $_GET['search_type'] == 'album')
					? ' checked="checked"'
					: '';

			case 'image_desc' :
			case 'image_name' :
			case 'image_tags' :
			case 'image_url' :
				return (!isset($_GET['search_query']) || isset($_GET['search_' . $item])
					|| $_GET['search_type'] == 'category')
					? ' checked="checked"'
					: '';

			// Champs de recherche pour la date.
			case 'date_field_cat_crtdt' :
			case 'date_field_cat_lastadddt' :
				return (((!isset($_GET['search_date_field'])
						|| (isset($_GET['search_type']) && $_GET['search_type'] != 'category'))
							&& $item == 'date_field_cat_crtdt')
					|| (isset($_GET['search_date_field'])
						&& 'date_field_' . $_GET['search_date_field'] == $item))
					? ' checked="checked"'
					: '';

			case 'date_field_image_crtdt' :
			case 'date_field_image_adddt' :
				return (((!isset($_GET['search_date_field'])
						|| (isset($_GET['search_type']) && $_GET['search_type'] != 'album'))
							&& $item == 'date_field_image_adddt')
					|| (isset($_GET['search_date_field'])
						&& 'date_field_' . $_GET['search_date_field'] == $item))
					? ' checked="checked"'
					: '';

			// Exclusions.
			case 'exclude' : 
				return (isset($_GET['search_exclude']))
					? ' checked="checked"'
					: '';

			case 'exclude_filters' :
				$options = '';
				$filters = array(
					'crtdt' => __('date de création'),
					'desc' => __('description'),
					'geoloc' => __('géolocalisation'),
					'place' => __('lieu'),
					'tags' => __('tags')
				);
				foreach ($filters as $filter => $text)
				{
					$selected = (isset($_GET['search_exclude_filter'])
						&& $_GET['search_exclude_filter'] == $filter)
						? ' class="selected" selected="selected"'
						: '';
					$options .= '<option' . $selected . ' value="' . $filter . '">'
						. $text . '</option>';
				}
				return $options;

			// Statut.
			case 'status' :
				$status = array(
					'publish' => __('publié'),
					'unpublish' => __('non publié')
				);
				$selected = (!isset($_GET['search_status'])
					|| !isset($status[$_GET['search_status']]))
					? ' selected="selected"'
					: '';
				$list = '<option' . $selected . ' value="all">*' . __('tous') . '</option>';
				foreach ($status as $value => &$text)
				{
					$selected = (isset($_GET['search_status'])
						&& $_GET['search_status'] == $value)
						? ' selected="selected"'
						: '';
					$list .= '<option' . $selected . ' value="' . $value . '">'
						. $text . '</option>';
				}
				return $list;

			// Utilisateurs.
			case 'users' :
				$selected = (!isset($_GET['search_user'])
					|| !preg_match('`^\d{1,11}$`', $_GET['search_user']))
					? ' selected="selected"'
					: '';
				$list = '<option' . $selected . ' value="all">*' . __('tous') . '</option>';
				foreach (users::$usersList as $id => &$login)
				{
					$selected = (isset($_GET['search_user']) && $_GET['search_user'] == $id)
						? ' selected="selected"'
						: '';
					$list .= '<option' . $selected . ' value="' . (int) $id . '">'
						. utils::tplProtect($login) . '</option>';
				}
				return $list;

			default :
				return $this->_getSearch($item, 1900);
		}
	}

	/**
	 * La galerie contient-elle des tags ?
	 *
	 * @return boolean
	 */
	public function disTagsList()
	{
		return count(tags::$items) > 0;
	}

	/**
	 * Retourne la liste des tags.
	 *
	 * @return string
	 */
	public function getTagsList()
	{
		$tags = '';
		foreach (tags::$items as &$infos)
		{
			$tags .= ', "' . utils::tplProtect($infos['tag_name']) . '"';
		}
		return substr($tags, 2);
	}



	/**
	 * L'élément de la catégorie courante  $item doit-il être affiché ?
	 *
	 * @param string $item
	 * @return boolean
	 */
	protected function _disCategoryInfo($item, $i)
	{
		switch ($item)
		{
			case 'commentable' :
				return ($_GET['object_id'] != 1
					&& !$this->_disCategoryInfo('commentable_parent', $i))
					? FALSE
					: (bool) $i['cat_commentable'];

			case 'commentable_parent' :
				return !isset($i['cat_commentable_parent'])
					|| $i['cat_commentable_parent'];

			case 'creatable' :
				return ($_GET['object_id'] != 1
					&& !$this->_disCategoryInfo('creatable_parent', $i))
					? FALSE
					: (bool) $i['cat_creatable'];

			case 'creatable_field' :
				return $i['cat_filemtime'] === NULL;

			case 'creatable_parent' :
				return !isset($i['cat_creatable_parent'])
					|| $i['cat_creatable_parent'];

			case 'empty' :
				return albums::$nbItems == 0
					|| $i['thumb_id'] == -1;

			case 'password_groups' :
				return utils::$config['users'] == 1
					&& utils::$config['users_locked_albums_access_mode'] == 'groups';

			case 'protected' :
				return $i['cat_password'] !== NULL;

			case 'protected_root' :
				if ($i['cat_password'] === NULL)
				{
					return FALSE;
				}
				$password = explode(':', $i['cat_password'], 2);
				return $password[0] == $i['cat_id'];

			case 'publish' :
				return (bool) $i['cat_status'];

			case 'type_album' :
				return $i['cat_filemtime'] !== NULL;

			case 'type_category' :
				return $i['cat_filemtime'] === NULL;

			case 'uploadable' :
				return ($_GET['object_id'] != 1
					&& !$this->_disCategoryInfo('uploadable_parent', $i))
					? FALSE
					: (bool) $i['cat_uploadable'];

			case 'uploadable_parent' :
				return !isset($i['cat_uploadable_parent'])
					|| $i['cat_uploadable_parent'];

			case 'votable' :
				return ($_GET['object_id'] != 1
					&& !$this->_disCategoryInfo('votable_parent', $i))
					? FALSE
					: (bool) $i['cat_votable'];

			case 'votable_parent' :
				return !isset($i['cat_votable_parent'])
					|| $i['cat_votable_parent'];
		}
	}

	/**
	 * Retourne l'information de la catégorie courante $item.
	 *
	 * @return string
	 */
	protected function _getCategoryInfo($item, $i, $thumb_forced = 0)
	{
		switch ($item)
		{
			// Poids des images activées.
			// Poids des images désactivées.
			case 'a_size' :
			case 'd_size' :
				$size = $i['cat_' . $item];
				return ($size) ? utils::filesize($size) : 0;

			// Poids de toutes les images.
			case 'size' :
				$size = $i['cat_a_size'] + $i['cat_d_size'];
				return ($size) ? utils::filesize($size) : 0;

			// Note moyenne de toutes les images.
			case 'rate' :
				if ($i['cat_a_rate'] > 0 && $i['cat_d_rate'] > 0)
				{
					return sprintf('%1.1f', ((float) $i['cat_a_rate']
						+ (float) $i['cat_d_rate']) / 2);
				}
				if ($i['cat_a_rate'] > 0)
				{
					return $this->_getCategoryInfo('a_rate', $i);
				}
				return $this->_getCategoryInfo('d_rate', $i);

			// Statistiques pour les images activées et désactivées.
			case 'a_albums' :
			case 'd_albums' :
				return (int) $i['cat_' . $item];

			case 'a_rate' :
			case 'd_rate' :
				return number_format((float) $i['cat_' . $item], 1, __(','), '');

			case 'a_images' :
			case 'a_hits' :
			case 'a_comments' :
			case 'a_votes' :
			case 'd_images' :
			case 'd_hits' :
			case 'd_comments' :
			case 'd_votes' :
				return (int) $i['cat_' . $item];

			// Statistiques pour toutes les images.
			case 'albums' :
			case 'images' :
			case 'links' :
			case 'hits' :
				return $this->_getCategoryInfo('a_' . $item, $i)
					 + $this->_getCategoryInfo('d_' . $item, $i);

			// Nombre total de commentaires et de votes.
			case 'comments' :
			case 'votes' :
				$n = $this->_getCategoryInfo('a_' . $item, $i)
				   + $this->_getCategoryInfo('d_' . $item, $i);
				if ($item == 'comments')
				{
					$item = 'comments-images';
				}
				return ($n > 0)
					? '<a href="' . utils::genURL($item . '/category/'
						. $i['cat_id']) . '">' . $n . '</a>'
					: $n;

			// Tri des images.
			case 'orderby_1' :
			case 'orderby_2' :
			case 'orderby_3' :
				$options = ($i['cat_filemtime'] === NULL)
					? array(
						'default' => '*' . __('par défaut'),
						'cat_position' => __('tri manuel'),
						'cat_name' => __('titre'),
						'cat_path' => __('nom de répertoire'),
						'cat_crtdt' => __('date de création'),
						'cat_lastadddt' => __('date de mise à jour'),
						'cat_a_size' => __('poids')
					)
					: array(
						'default' => '*' . __('par défaut'),
						'image_position' => __('tri manuel'),
						'image_name' => __('titre'),
						'image_path' => __('nom de fichier'),
						'image_size' => __('taille'),
						'image_filesize' => __('poids'),
						'image_hits' => __('nombre de visites'),
						'image_comments' => __('nombre de commentaires'),
						'image_votes' => __('nombre de votes'),
						'image_rate' => __('note moyenne'),
						'image_adddt' => __('date d\'ajout'),
						'image_crtdt' => __('date de création')
					);
				if (substr($item, -1, 1) != 1)
				{
					unset($options['default']);
				}
				return $this->_orderby(
					$options,
					'([^\s]+)[^,]+,([^\s]+)[^,]+,([^\s]+)[^,]+,',
					$i['cat_orderby'],
					$item
				);

			case 'ascdesc_1' :
			case 'ascdesc_2' :
			case 'ascdesc_3' :
				$options = array(
					'ASC' => __('croissant'),
					'DESC' => __('décroissant')
				);
				return $this->_orderby(
					$options,
					'[^,]+(ASC|DESC),[^,]+(ASC|DESC),[^,]+(ASC|DESC),',
					$i['cat_orderby'],
					$item
				);

			// Liste des catégories.
			case 'categories_list' :
				return template::mapSelect(albums::$mapCategories, array(
					'class_selected' => TRUE,
					'ignore' => ($_GET['section'] == 'edit-category')
						? array($i['cat_id'])
						: array(),
					'ignore_albums' => true,
					'selected' => $i['parent_id']
				));

			// Date de création.
			case 'crtdt' :
				return utils::tplProtect(
					utils::localeTime(__('%A %d %B %Y à %H:%M:%S'), $i['cat_crtdt'])
				);

			// Description.
			case 'description' :
				return utils::tplProtect(utils::getLocale($i['cat_desc']));

			// Description dans la langue courante.
			case 'description_lang' :
				return utils::tplProtect(
					utils::getLocale($i['cat_desc'], $this->getLang('code'))
				);

			// Nom de répertoire.
			case 'dirname' :
				return utils::tplProtect(basename($i['cat_path']));

			// Lien vers la galerie.
			case 'gallery_link' :
				$object = ($i['cat_filemtime'] === NULL)
					? 'category'
					: 'album';
				return $this->getGalleryLink($object . '/'
					. $i['cat_id'] . '-' . $i['cat_url']);

			// Liste des groupes autorisés et non autorisés à accéder à la catégorie.
			case 'groups_list_allowed' :
			case 'groups_list_forbidden' :
				$options = '';
				$groups = ($item == 'groups_list_allowed')
					? albums::$allowedGroups
					: albums::$forbiddenGroups;
				foreach ($groups as $group_id => $group_infos)
				{
					$name = (utils::isEmpty($group_infos['group_name']))
						? admin::getL10nGroupName($group_infos['group_id'])
						: utils::tplProtect(utils::getLocale($group_infos['group_name']));
					$options .= '<option value="' . (int) $group_id . '">' . $name . '</li>';
				}
				return $options;

			// Identifiant.
			case 'id' :
				return utils::tplProtect($i['cat_id']);

			// Latitude.
			case 'latitude' :
				return preg_replace('`^(\d+\.\d+?)0+$`', '$1', utils::tplProtect($i['cat_lat']));

			// Longitude.
			case 'longitude' :
				return preg_replace('`^(\d+\.\d+?)0+$`', '$1', utils::tplProtect($i['cat_long']));

			// Lien vers la catégorie ou l'album.
			case 'object_link' :
				$object = ($i['cat_filemtime'] === NULL)
					? 'category'
					: 'album';
				return utils::genURL($object . '/' . $this->_getCategoryInfo('id', $i));

			// Type d'objet + identifiant.
			case 'object_type' :
				$object = ($i['cat_filemtime'] === NULL)
					? __('catégorie %s')
					: __('album %s');
				return sprintf($object, $this->_getCategoryInfo('id', $i));

			// Identifiant du propriétaire.
			case 'owner_id' :
				return (int) $i['user_id'];

			// Lien vers la page de profil du propriétaire.
			case 'owner_link' :
				$user = '/';
				if ($i['user_id'] != 2
				&& ($i['user_status'] == 1 || $i['user_status'] == 0))
				{
					$login = utils::strLimit($this->_getCategoryInfo('owner_login', $i), 30);

					// On ne met pas de lien pour les utilisateurs
					// qui n'ont pas la permission d'accès à la page
					// de gestion des utilisateurs.
					if (!$this->disPerm('users_members'))
					{
						return $login;
					}

					$user = utils::genURL('user/' . (int) $i['user_id']);
					$user = '<a href="' . $user . '">' . $login . '</a>';
				}
				return $user;

			// Login du propriétaire.
			case 'owner_login' :
				return utils::tplProtect($i['user_login']);

			// Statut du propriétaire
			case 'owner_status' :
				return utils::tplProtect($i['user_status']);

			// Mot de passe.
			case 'password' :
				return ($i['cat_password'] === NULL)
					? ''
					: '**********';

			// Liste des groupes ayant accès à la catégorie verrouillée.
			case 'password_groups' :
				$list = '';
				foreach (albums::$allowedGroups as $group_id => $group_infos)
				{
					$name = (utils::isEmpty($group_infos['group_name']))
						? admin::getL10nGroupName($group_infos['group_id'])
						: utils::tplProtect(utils::getLocale($group_infos['group_name']));
					$link = utils::genURL('group/' . $group_infos['group_id']);
					$list .= '<li><a href="' . $link . '">' . $name . '</a></li>';
				}
				return $list;

			// Informations sur le mot de passe.
			case 'password_infos' :
				$password = explode(':', $i['cat_password'], 2);
				if ($password[0] == $i['cat_id'])
				{
					$text = ($i['cat_filemtime'] === NULL)
						? __('L\'accès à cette catégorie est protégé par un mot de passe.')
						: __('L\'accès à cet album est protégé par un mot de passe.');
				}
				else
				{
					$cat_infos = albums::$parents[$password[0]];
					$link = '<a href="' . utils::genURL('category/' . $cat_infos['cat_id']) . '">'
						. utils::tplProtect(utils::getLocale($cat_infos['cat_name'])) . '</a>';
					$object = sprintf(__('la catégorie %s'), $link);
					$text = ($i['cat_filemtime'] === NULL)
						? sprintf(__('L\'accès à cette catégorie est protégé'
							. ' par un mot de passe qui a été placé sur %s.'), $object)
						: sprintf(__('L\'accès à cet album est protégé'
							. ' par un mot de passe qui a été placé sur %s.'), $object);
				}
				return $text;

			// Lieu.
			case 'place' :
				return utils::tplProtect($i['cat_place']);

			// Lieux connus.
			case 'places' :
				$options = '<option value=";">&nbsp;</option>';
				foreach (albums::$places as $infos)
				{
					$selected = ($infos['latitude'] == $i['cat_lat']
					&& $infos['longitude'] == $i['cat_long']
					&& $infos['place'] == $i['cat_place'])
						? ' selected="selected"'
						: '';
					$options .= '<option' . $selected . ' value="'
						. utils::tplProtect($infos['latitude'])
						. ';' . utils::tplProtect($infos['longitude']) . '">'
						. utils::tplProtect($infos['place']) . '</option>';
				}
				return $options;

			// Statut.
			case 'status_list' :
				$status = array(0 => __('non publié'), 1 => __('publié'));
				if ($i['cat_a_images'] + $i['cat_d_images'] == 0)
				{
					unset($status[1]);
				}
				$options = '';
				foreach ($status as $k => $v)
				{
					$selected = ($k == $i['cat_status'])
						? ' selected="selected"'
						: '';
					$options .= '<option' . $selected
						. ' value="' . $k . '">' . $v . '</option>';
				}
				return $options;

			case 'status_msg' :
				return ($i['cat_status'])
					? __('publié')
					: __('non publié');

			// Liste des styles disponibles.
			case 'styles' :
				$options = '<option value="*">*' . __('par défaut') . '</option>';
				$style = (in_array($i['cat_style'], category::$styles))
					? $i['cat_style']
					: '*';
				foreach (category::$styles as $name)
				{
					$selected = ($style == $name)
						? ' selected="selected"'
						: '';
					$name = utils::tplprotect($name);
					$options .= '<option' . $selected
						. ' value="' . $name . '">' . $name . '</option>';
				}
				return $options;

			// Centrage de la vignette par CSS.
			case 'thumb_center' :
				$tb = img::getThumbSize($i, 'cat', $thumb_forced);
				return img::thumbCenter('cat', $tb['width'],
					$tb['height'], $thumb_forced);

			// Lien vers la page d'édition de la vignette.
			case 'thumb_link' :
				$type = ($i['cat_filemtime'] === NULL)
					? 'category'
					: 'album';
				return utils::genURL('thumb-' . $type . '/' . $i['cat_id']);

			// Dimensions de la vignette.
			case 'thumb_size' :
				$tb = img::getThumbSize($i, 'cat', $thumb_forced);
				return 'width="' . $tb['width'] . '" height="' . $tb['height'] . '"';

			// Emplacement de la vignette.
			case 'thumb_src' :
				return utils::tplProtect(template::getThumbSrc('cat', $i));

			// Titre.
			case 'title' :
				return utils::tplProtect(utils::getLocale($i['cat_name']));

			// Titre dans la langue courante.
			case 'title_lang' :
				return utils::tplProtect(
					utils::getLocale($i['cat_name'], $this->getLang('code'))
				);

			// Type d'objet.
			case 'type' :
			
				return ($i['cat_filemtime'] === NULL)
					? sprintf(__('catégorie %s'), $i['cat_id'])
					: sprintf(__('album %s'), $i['cat_id']);

			// Nom d'URL.
			case 'urlname' :
				return utils::tplProtect($i['cat_url']);

			// Liste des utilisateurs.
			case 'users_list' :
				$list = '';
				foreach (users::$usersList as $id => &$login)
				{
					$selected = ($id == $i['user_id'])
						? ' selected="selected"'
						: '';
					$list .= '<option' . $selected . ' value="' . (int) $id . '">'
						. utils::tplProtect($login) . '</option>';
				}
				return $list;

			case 'watermark_link' :
				return utils::genURL('watermark-' . $i['type'] . '/' . $i['cat_id']);
		}
	}

	/**
	 * Retourne l'élément $item de l'aperçu.
	 *
	 * @param string $item
	 *	Élément.
	 * @param string $type
	 *	Type de vignette ('cat', 'img').
	 * @param string $mode
	 *	Mode de vignette (crop, prop).
	 * @return mixed
	 */
	protected function _getImagePreview($item, $type, $mode)
	{
		$i =& albums::$infos;

		switch ($item)
		{
			// Centrage de l'image par CSS.
			case 'center' :
				return img::thumbCenter(
					array('mode' => 'prop', 'width' => 400, 'height' => 400),
					$this->getImagePreview('width'),
					$this->getImagePreview('height')
				);

			// Hauteur de l'image.
			case 'height' :
				return (int) $i['preview_height'];

			// Largeur de l'image.
			case 'width' :
				return (int) $i['preview_width'];

			// Coordonnées de la zone sélectionnée.
			case 'set_select' :
				$coords = array();

				// Vignette rognée.
				if (!empty($i['tb_infos']))
				{
					$type = ($_GET['section'] == 'thumb-album'
						  || $_GET['section'] == 'thumb-category') ? 'cat' : 'img';
					$coords = explode('.', $i['tb_infos']);
					$sconf = img::isThumbConfCrop($type, $mode, $coords);
					$coords = explode(',', $coords[3]);
					if (!empty($coords[2]) && $sconf)
					{
						return $coords[0] . ',' . $coords[1] . ','
							. ($coords[2] + $coords[0]) . ','
							. ($coords[3] + $coords[1]);
					}
				}

				// Vignette non rognée, méthode 'crop'.
				if ($mode == 'crop')
				{
					$coords = img::resizeCrop(
						$this->getImagePreview('width'),
						$this->getImagePreview('height'),
						$this->getConf('thumb_width'),
						$this->getConf('thumb_height')
					);
					return $coords['x'] . ', ' . $coords['y'] . ', '
						 . $coords['w'] . ', ' . $coords['h'];
				}

				// Vignette non rognée, méthode 'prop'.
				return '0, 0, ' . $this->getImagePreview('width')
					. ', ' . $this->getImagePreview('height');

			// Emplacement de l'image.
			case 'src' :
				$args = ($type == 'cat' && $i['thumb_id'] == 0)
					? 'id=' . $i['cat_id'] . '&type=external'
					: 'id=' . $i['image_id'];
				return utils::tplProtect(
					CONF_GALLERY_PATH . utils::$purlDir . '/edit.php?' . $args . '&' . mt_rand()
				);
		}
	}

	/**
	 * Retourne l'information $item de l'image courante.
	 *
	 * @return string
	 */
	protected function _getImageInfo($item, $i, $thumb_forced = 0)
	{
		switch ($item)
		{
			// Date d'ajout.
			case 'adddt' :
				return utils::tplProtect(
					utils::localeTime(__('%A %d %B %Y à %H:%M:%S'), $i['image_adddt'])
				);

			// Liste des albums.
			case 'albums_list' :
				return template::mapSelect(albums::$mapCategories, array(
					'class_selected' => TRUE,
					'selected' => $i['cat_id']
				));

			// Nombre de visites.
			case 'hits' :
				return (int) $i['image_' . $item];

			// Nombre de commentaires.
			case 'comments' :
				$n = $i['image_comments'];
				return ($n > 0)
					? '<a href="' . utils::genURL('comments-images/image/'
						. $i['image_id']) . '">' . $n . '</a>'
					: $n;

			// Date de création.
			case 'crtdt' :
				return template::dateSelect(
					$i['image_crtdt'], 1900, $i['image_id'] . '[crtdt_%s]', TRUE
				);

			// Description.
			case 'description' :
				return utils::tplProtect(utils::getLocale($i['image_desc']));

			// Description dans la langue courante.
			case 'description_lang' :
				return utils::tplProtect(
					utils::getLocale($i['image_desc'], $this->getLang('code'))
				);

			// Nom de fichier.
			case 'filename' :
				return utils::tplProtect(basename($i['image_path']));

			// Poids de l'image.
			case 'filesize' :
				return utils::filesize($i['image_filesize']);

			// Lien vers la galerie.
			case 'gallery_link' :
				return (utils::$config['images_direct_link'])
					? CONF_GALLERY_PATH . '/image.php?id=' . (int) $i['image_id']
					: $this->getGalleryLink(
						'image/' . $i['image_id'] . '-' . $i['image_url']
					);

			// Hauteur.
			case 'height' :
				return (int) $i['image_height'];

			// Identifiant.
			case 'id' :
				return (int) $i['image_id'];

			// Latitude.
			case 'latitude' :
				return preg_replace('`^(\d+\.\d+?)0+$`', '$1',
					utils::tplProtect($i['image_lat']));

			// Lien vers l'image.
			case 'link' :
				return CONF_GALLERY_PATH . utils::$purlDir . '/image.php?id=' . $i['image_id'];

			// Longitude.
			case 'longitude' :
				return preg_replace('`^(\d+\.\d+?)0+$`', '$1',
					utils::tplProtect($i['image_long']));

			// Type d'objet + identifiant.
			case 'object_type' :
				return sprintf(__('image %s'), $this->_getImageInfo('id', $i, $thumb_forced));

			// Identifiant d'utilisateur.
			case 'owner_id' :
				return (int) $i['user_id'];

			// Lien vers la page de profil du propriétaire.
			case 'owner_link' :
				$user = '/';
				if ($i['user_id'] != 2
				&& ($i['user_status'] == 1 || $i['user_status'] == 0))
				{
					$login = utils::strLimit(
						$this->_getImageInfo('owner_login', $i, $thumb_forced), 30
					);

					// On ne met pas de lien pour les utilisateurs
					// qui n'ont pas la permission d'accès à la page
					// de gestion des utilisateurs.
					if (!$this->disPerm('users_members'))
					{
						return $login;
					}

					$user = utils::genURL('user/' . (int) $i['user_id']);
					$user = '<a href="' . $user . '">' . $login . '</a>';
				}
				return $user;

			// Login du propriétaire.
			case 'owner_login' :
				return utils::tplProtect($i['user_login']);

			// Statut du propriétaire
			case 'owner_status' :
				return utils::tplProtect($i['user_status']);

			// Liste des groupes ayant accès à l'image verrouillée.
			case 'password_groups' :
				$list = '';
				foreach (albums::$allowedGroups as $group_id => $i)
				{
					$name = (utils::isEmpty($i['group_name']))
						? admin::getL10nGroupName($i['group_id'])
						: utils::tplProtect(utils::getLocale($i['group_name']));
					$link = utils::genURL('group/' . $i['group_id']);
					$list .= '<li><a href="' . $link . '">' . $name . '</a></li>';
				}
				return $list;

			// Informations sur le mot de passe.
			case 'password_infos' :
				$password = explode(':', $i['cat_password'], 2);
				$cat_infos = albums::$parents[$password[0]];
				if ($cat_infos['cat_filemtime'] === NULL)
				{
					$object = __('la catégorie %s');
					$type = 'category';
				}
				else
				{
					$object = __('l\'album %s');
					$type = 'album';
				}
				$link = '<a href="' . utils::genURL($type . '/' . $cat_infos['cat_id']) . '">'
					. utils::tplProtect(utils::getLocale($cat_infos['cat_name'])) . '</a>';
				$object = sprintf($object, $link);
				return sprintf(__('L\'accès à cette image est protégé'
					. ' par un mot de passe qui a été placé sur %s.'), $object);

			// Lieu.
			case 'place' :
				return utils::tplProtect($i['image_place']);

			// Lieux connus.
			case 'places' :
				$options = '<option value=";">&nbsp;</option>';
				foreach (albums::$places as $infos)
				{
					$selected = ($infos['latitude'] == $i['image_lat']
					&& $infos['longitude'] == $i['image_long']
					&& $infos['place'] == $i['image_place'])
						? ' selected="selected"'
						: '';
					$options .= '<option' . $selected . ' value="'
						. utils::tplProtect($infos['latitude'])
						. ';' . utils::tplProtect($infos['longitude']) . '">'
						. utils::tplProtect($infos['place']) . '</option>';
				}
				return $options;

			// Note moyenne.
			case 'rate' :
				return number_format((float) $i['image_rate'], 1, __(','), '');

			// Dimensions de l'image.
			case 'size' :
				return sprintf('%s x %s', (int) $i['image_width'], (int) $i['image_height']);

			// Emplacement de l'image.
			case 'src' :
				return CONF_GALLERY_PATH . '/' . CONF_ALBUMS_DIR . '/' . $i['image_path'];

			// Message de statut.
			case 'status_msg' :
				return ($i['image_status'])
					? __('publié')
					: __('non publié');

			// Choix du statut.
			case 'status_list' :
				$options = '';
				foreach (array(0 => __('non publié'), 1 => __('publié')) as $k => $v)
				{
					$selected = ($k == $i['image_status'])
						? ' selected="selected"'
						: '';
					$options .= '<option' . $selected
						. ' value="' . $k . '">' . $v . '</option>';
				}
				return $options;

			// Tags.
			case 'tags' :
				return (isset($i['image_tags']))
					? utils::tplProtect(implode(', ', $i['image_tags']))
					: '';

			// Paramètres de la vignette.
			case 'thumb_center' :
			case 'thumb_size' :
			case 'thumb_src' :
				return $this->_getThumbImage($item, $i, $thumb_forced);

			// Titre.
			case 'title' :
				return utils::tplProtect(utils::getLocale($i['image_name']));

			// Titre dans la langue courante.
			case 'title_lang' :
				return utils::tplProtect(
					utils::getLocale($i['image_name'], $this->getLang('code'))
				);

			// Type.
			case 'type' :
				return sprintf(__('image %s'), $i['image_id']);

			// Nom d'URL.
			case 'urlname' :
				return utils::tplProtect($i['image_url']);

			// Nombre de votes.
			case 'votes' :
				$n = $i['image_votes'];
				return ($n > 0)
					? '<a href="' . utils::genURL('votes/image/'
						. $i['image_id']) . '">' . $n . '</a>'
					: $n;

			// Largeur.
			case 'width' :
				return (int) $i['image_width'];
		}
	}

	/**
	 * Retourne la liste des images de l'album sous forme de balises <option>.
	 *
	 * @param string $section
	 * @return string
	 */
	protected function _getImagesList($section)
	{
		$options = '';
		$images = array();
		foreach (image::$albumImages as &$infos)
		{
			$images[$infos['image_id']] = $infos['image_name'];
		}
		asort($images);
		foreach ($images as $id => &$name)
		{
			$selected = ($id == albums::$infos['image_id'])
				? ' class="selected" selected="selected"'
				: '';
			$options .= '<option' . $selected . ' value="' . $section . '/' . (int) $id . '">'
				. utils::tplProtect($name) . '</option>';
		}
		return $options;
	}

	/**
	 * Retourne l'élément des options d'affichage $item.
	 *
	 * @param string $section
	 * @param string $item
	 * @param string $sort_by
	 * @return string
	 */
	protected function _getDisplayOptions($section, $item, $sort_by)
	{
		switch ($item)
		{
			// Ordre de tri.
			case 'orderby' :
				$p = array(
					'ASC' => __('croissant'),
					'DESC' => __('décroissant')
				);
				return $this->_displayOptionsList($section, $item, 'ASC', $p);

			// Nombre d'objets par page.
			case 'nb_per_page':
				return (int) auth::$infos['user_prefs'][$section][$item];

			// Critère de tri.
			case 'sortby' :
				return $this->_displayOptionsList($section, $item, 'name', $sort_by);
		}
	}

	/**
	 * Retourne l'élément $item de la vignette.
	 *
	 * @param string $item
	 * @param string $type
	 *	Type de vignette ('cat', 'img').
	 * @return string
	 */
	protected function _getThumb($item, $type)
	{
		$i =& albums::$infos;

		switch ($item)
		{
			// Centrage de la vignette par CSS.
			case 'center' :
				$tb = img::getThumbSize($i, $type);
				return img::thumbCenter($type, $tb['width'], $tb['height']);

			// Dimensions de la vignette.
			case 'size' :
				$tb = img::getThumbSize($i, $type);
				return 'width="' . $tb['width'] . '" height="' . $tb['height'] . '"';

			// Emplacement de la vignette.
			case 'src' :
				return utils::tplProtect(template::getThumbSrc($type, $i));
		}
	}
}

/**
 * Méthodes de template pour les pages de gestion des images.
 */
class tplAlbum extends tplAlbums
{
	/**
	 * Taille de vignette forcée, communiquée par le template.
	 *
	 * @see function nextImage
	 * @var integer
	 */
	private $_thumbForced = 0;



	/**
	 * Retourne la liste des catégories.
	 *
	 * @return string
	 */
	public function getCategoriesList()
	{
		$ignore = (isset($_GET['search']))
			? array()
			: array(isset($_GET['object_id']) ? $_GET['object_id'] : 0);
		return template::mapSelect(albums::$listCategories, array(
			'class_type' => TRUE,
			'ignore' => $ignore,
			'nolines_category' => TRUE
		));
	}

	/**
	 * Retourne l'élément des options d'affichage $item.
	 *
	 * @param string $item
	 * @return string
	 */
	public function getOptions($item)
	{
		return $this->_getDisplayOptions('album', $item, array(
			'name' => __('titre'),
			'adddt' => __('date d\'ajout'),
			'path' => __('nom de fichier'),
			'position' => __('tri manuel')
		));
	}

	/**
	 * L'élément de l'image $item doit-il être affiché ?
	 *
	 * @param string $item
	 * @return boolean
	 */
	public function disImage($item)
	{
		$i = current(albums::$items);

		switch ($item)
		{
			// Image protégée.
			case 'protected' :
				return $i['cat_password'] != '';

			// Image activée.
			case 'publish' :
				return (bool) $i['image_status'];

			// Statistiques.
			case 'stats' :
				return auth::$perms['admin']['perms']['access_mode'] == 1;
		}
	}

	/**
	 * Retourne l'élément de l'image $item.
	 *
	 * @param string $item
	 * @return string
	 */
	public function getImage($item)
	{
		$i = current(albums::$items);

		return $this->_getImageInfo($item, $i, $this->_thumbForced);
	}

	/**
	 * Y a-t-il une prochaine image ?
	 * 
	 * @param integer $thumb_size
	 *	Taille des vignettes.
	 * @return boolean
	 */
	public function nextImage($thumb_size)
	{
		static $next = -1;

		$this->_thumbForced =& $thumb_size;

		return template::nextObject(albums::$items, $next);
	}

	/**
	 * L'élément de navigation entre les pages $item doit-il être affiché ?
	 *
	 * @param string $item
	 * @return boolean
	 */
	public function disNavigation($item = '')
	{
		return template::disNavigation($item, albums::$nbPages);
	}

	/**
	 * Retourne l'élément de navigation entre les pages $item.
	 *
	 * @param string $item
	 * @return string
	 */
	public function getNavigation($item)
	{
		return template::getNavigation($item, albums::$nbPages, admin::$sectionRequest);
	}
}

/**
 * Méthodes de template pour la page de gestion des modèles d'appareils photos.
 */
class tplCameras extends tplAdmin
{
	/**
	 * Retourne une valeur d'une propriété de la classe "cameras".
	 *
	 * @param array|string $property
	 * @return mixed
	 */
	public function getInfo($property)
	{
		return (is_array($property))
			? utils::tplProtect(cameras::${$property[0]}[$property[1]])
			: utils::tplProtect(cameras::${$property});
	}

	/**
	 * L'élément de navigation entre les pages $item doit-il être affiché ?
	 *
	 * @param string $item
	 * @return boolean
	 */
	public function disNavigation($item = '')
	{
		return template::disNavigation($item, cameras::$nbPages);
	}

	/**
	 * Retourne l'élément de navigation entre les pages $item.
	 *
	 * @param string $item
	 * @return string
	 */
	public function getNavigation($item)
	{
		return template::getNavigation($item, cameras::$nbPages, admin::$sectionRequest);
	}

	/**
	 * Retourne l'élément des options d'affichage $item.
	 *
	 * @param string $item
	 * @return string
	 */
	public function getOptions($item)
	{
		$section = str_replace('-', '_', $_GET['section']);

		switch ($item)
		{
			// Ordre de tri.
			case 'orderby' :
				$p = array(
					'ASC' => __('croissant'),
					'DESC' => __('décroissant')
				);
				return $this->_displayOptionsList($section, $item, 'DESC', $p);

			// Nombre d'objets par page.
			case 'nb_per_page':
				return (int) auth::$infos['user_prefs'][$section][$item];

			// Critère de tri.
			case 'sortby' :
				$p = array(
					'name' => __('Nom'),
					'nb_images' => __('Nombre d\'images liées')
				);
				return $this->_displayOptionsList($section, $item, 'date', $p);
		}
	}

	/**
	 * Retourne l'élément d'appareil photos $item.
	 *
	 * @param string $item
	 * @return string
	 */
	public function getCam($item)
	{
		$i = current(cameras::$items);
		$pref_col = str_replace('-', '_', substr($_GET['section'], 0, -1));

		switch ($item)
		{
			// Identifiant.
			case 'id' :
				return (int) $i[$pref_col . '_id'];

			// Lien vers les images liées.
			case 'images_link' :
				return utils::genURL(
					'category/1/' . substr($_GET['section'], 0, -1) . '/' . $i[$pref_col . '_id']
				);

			// Nombre d'images liées.
			case 'nb_images' :
				return (int) $i[$pref_col . '_nb_images'];

			// Nom.
			case 'name' :
				return utils::tplProtect($i[$pref_col . '_name']);

			// Nom d'URL.
			case 'urlname' :
				return utils::tplProtect($i[$pref_col . '_url']);
		}
	}

	/**
	 * Y a-t-il un prochain élément d'appareil photos ?
	 *
	 * @return boolean
	 */
	public function nextCam()
	{
		static $next = -1;

		return template::nextObject(cameras::$items, $next);
	}

	/**
	 * Y a-t-il des éléments ?
	 *
	 * @return boolean
	 */
	public function disCam()
	{
		return cameras::$nbItems > 0;
	}

	/**
	 * S'agit-il d'une recherche ?
	 *
	 * @return boolean
	 */
	public function disSearch()
	{
		return cameras::$searchInit;
	}

	/**
	 * Retourne l'élément de recherche $item.
	 *
	 * @param string $item
	 * @return string
	 */
	public function getSearch($item)
	{
		$pref_col = str_replace('-', '_', substr($_GET['section'], 0, -1));

		switch ($item)
		{
			// Options : cases à cocher.
			case 'nb_images' :
				return (isset($_GET['search_' . $pref_col . '_' . $item]))
					? ' checked="checked"'
					: '';

			// Options : champs textes.
			case 'nb_images_max' :
			case 'nb_images_min' :
				return (isset($_GET['search_' . $pref_col . '_' . $item]))
					? utils::tplProtect($_GET['search_' . $pref_col . '_' . $item])
					: '';

			// Préfixe des colonnes.
			case 'pref_col' :
				return $pref_col;

			// Options : cases à cocher avec option cochée par défaut.
			case 'name' :
			case 'url' :
				return (!isset($_GET['search_query'])
					|| isset($_GET['search_' . $pref_col . '_' . $item]))
					? ' checked="checked"'
					: '';

			default :
				return $this->_getSearch($item);
		}
	}
}

/**
 * Méthodes de template pour les pages de gestion des catégories.
 */
class tplCategory extends tplAlbums
{
	/**
	 * Taille de vignette forcée, communiquée par le template.
	 *
	 * @see function nextCategory
	 * @var integer
	 */
	private $_thumbForced = 0;



	/**
	 * Retourne la liste des catégories.
	 *
	 * @return string
	 */
	public function getCategoriesList()
	{
		return template::mapSelect(albums::$listCategories, array(
			'ignore_albums' => TRUE
		));
	}

	/**
	 * Retourne la liste des utilisateurs.
	 *
	 * @return string
	 */
	public function getUsersList()
	{
		$list = '';

		foreach (users::$usersList as $id => &$login)
		{
			$list .= '<option value="' . (int) $id . '">'
				. utils::tplProtect($login) . '</option>';
		}

		return $list;
	}

	/**
	 * L'élément de catégorie $item doit-il être affiché ?
	 *
	 * @param string $item
	 * @return boolean
	 */
	public function disCategory($item)
	{
		$i = current(albums::$items);

		return $this->_disCategoryInfo($item, $i);
	}

	/**
	 * Retourne l'élément de catégorie $item.
	 *
	 * @param string $item
	 * @return string
	 */
	public function getCategory($item)
	{
		$i = current(albums::$items);

		return $this->_getCategoryInfo($item, $i, $this->_thumbForced);
	}

	/**
	 * Y a-t-il une prochaine image ?
	 * 
	 * @param integer $thumb_size
	 *	Taille des vignettes.
	 * @return boolean
	 */
	public function nextCategory($thumb_size)
	{
		static $next = -1;

		$this->_thumbForced =& $thumb_size;

		return template::nextObject(albums::$items, $next);
	}

	/**
	 * L'élément de navigation entre les pages $item doit-il être affiché ?
	 *
	 * @param string $item
	 * @return boolean
	 */
	public function disNavigation($item = '')
	{
		return template::disNavigation($item, albums::$nbPages);
	}

	/**
	 * Retourne l'élément de navigation entre les pages $item.
	 *
	 * @param string $item
	 * @return string
	 */
	public function getNavigation($item)
	{
		return template::getNavigation($item, albums::$nbPages, admin::$sectionRequest);
	}

	/**
	 * Retourne l'élément des options d'affichage $item.
	 *
	 * @param string $item
	 * @return string
	 */
	public function getOptions($item)
	{
		return $this->_getDisplayOptions('category', $item, array(
			'name' => __('titre'),
			'adddt' => __('date d\'ajout'),
			'path' => __('nom de répertoire'),
			'position' => __('tri manuel')
		));
	}
}

/**
 * Méthodes de template communes pour les pages de gestion de commentaires.
 */
class tplComments extends tplAdmin
{
	/**
	 * Taille de vignette forcée, communiquée par le template.
	 *
	 * @see function nextComment
	 * @var integer
	 */
	protected $_thumbForced = 0;



	/**
	 * Y a-t-il des commentaires ?
	 *
	 * @return boolean
	 */
	public function disComments()
	{
		return !empty(comments::$items);
	}

	/**
	 * L'élément de navigation entre les pages $item doit-il être affiché ?
	 *
	 * @param string $item
	 * @return boolean
	 */
	public function disNavigation($item = '')
	{
		return template::disNavigation($item, comments::$nbPages);
	}

	/**
	 * Retourne l'élément de navigation entre les pages $item.
	 *
	 * @param string $item
	 * @return string
	 */
	public function getNavigation($item)
	{
		return template::getNavigation($item, comments::$nbPages, admin::$sectionRequest);
	}

	/**
	 * Retourne une valeur d'une propriété de la classe comments.
	 *
	 * @param array|string $property
	 * @return mixed
	 */
	public function getInfo($property)
	{
		return (is_array($property))
			? utils::tplProtect(comments::${$property[0]}[$property[1]])
			: utils::tplProtect(comments::${$property});
	}

	/**
	 * Doit-on afficher l'élément de barre de position $item ?
	 *
	 * @return boolean
	 */
	public function disPosition($item)
	{
		switch ($item)
		{
			// Barre de position pour les filtres ?
			case 'filter' :
				return isset($_GET['date'])
					|| isset($_GET['ip'])
					|| isset($_GET['status'])
					|| isset($_GET['user_id']);

			// Barre de position normale ?
			case 'normal' :
				return !$this->disPosition('filter')
					&& !$this->disPosition('search');

			// Barre de position pour la recherche ?
			case 'search' :
				return isset($_GET['search']);
		}
	}

	/**
	 * Retourne l'élément de filtre $item.
	 *
	 * @return string
	 */
	public function getFilter($item)
	{
		switch ($item)
		{
			case 'section_link' :
				return utils::genURL(
					preg_replace(
						'`/(?:(?:date|ip|user)/.+$|pending)`',
						'',
						admin::$sectionRequest)
				);

			case 'text' :
				if (isset($_GET['date']))
				{
					return __('Commentaires à la date du %s');
				}
				if (isset($_GET['ip']))
				{
					return __('Commentaires en provenance de l\'IP %s');
				}
				if (isset($_GET['status']))
				{
					return __('Commentaires en attente');
				}
				if (isset($_GET['user_id']))
				{
					return __('Commentaires de l\'utilisateur %s');
				}
				break;

			case 'value' :
				if (isset($_GET['date']))
				{
					return utils::localeTime(__('%A %d %B %Y'), $_GET['date']);
				}
				if (isset($_GET['ip']))
				{
					return utils::tplProtect($_GET['ip']);
				}
				if (isset($_GET['user_id']))
				{
					$link = utils::genURL('user/' . $_GET['user_id']);
					$login = utils::tplProtect(users::$usersList[$_GET['user_id']]);
					return '<a href="' . $link . '">' . $login . '</a>';
				}
				break;
		}
	}

	/**
	 * Y a-t-il un prochain commentaire ?
	 * 
	 * @param integer $thumb_size
	 *	Taille des vignettes.
	 * @return boolean
	 */
	public function nextComment($thumb_size)
	{
		static $next = -1;

		$this->_thumbForced =& $thumb_size;

		return template::nextObject(comments::$items, $next);
	}

	/**
	 * S'agit-il d'une recherche ?
	 *
	 * @return boolean
	 */
	public function disSearch()
	{
		return comments::$searchInit;
	}



	/**
	 * L'élément de commentaire $item doit-il être affiché ?
	 *
	 * @param string $item
	 * @param array $i
	 * @param string $pref
	 * @return boolean
	 */
	protected function _disComment($item, $i, $pref)
	{
		switch ($item)
		{
			// Lien vers la galerie ?
			case 'gallery_link' :
				return $this->disComment('publish')
					&& utils::$config['images_direct_link'] == 0;

			// Invité ?
			case 'guest' :
				return $i['user_id'] == 2;

			// Statut.
			case 'deactivate' :
				return $i[$pref . '_status'] == '0';

			case 'pending' :
				return $i[$pref . '_status'] == '-1';

			case 'publish' :
				return $i[$pref . '_status'] == '1';
		}
	}

	/**
	 * Retourne l'élément $item du commentaire.
	 *
	 * @param string $item
	 * @return integer|string
	 */
	protected function _getComment($item, $i, $section, $pref)
	{
		switch ($item)
		{
			// Auteur du commentaire.
			case 'author' :
				return ($i['user_id'] == 2)
					? utils::tplProtect($i[$pref . '_author'])
					: utils::tplProtect($i['user_login']);

			// Lien vers la page des commentaires de la date de création.
			case 'comment_date_link' :
				return utils::genURL('comments-' . $section . '/date/'
					. date('Y-m-d', strtotime($i[$pref . '_crtdt'])));

			// Lien vers la page des commentaires de l'IP.
			case 'comment_ip_link' :
				return utils::genURL('comments-' . $section . '/ip/' . $i[$pref . '_ip']);

			// Lien vers la page des commentaires de l'utilisateur.
			case 'comment_user_link' :
				return utils::genURL('comments-' . $section . '/user/' . $i['user_id']);

			// Date d'ajout.
			case 'crtdt' :
				return utils::tplProtect(
					utils::localeTime(__('%A %d %B %Y à %H:%M:%S'), $i[$pref . '_crtdt'])
				);

			// Courriel.
			case 'email' :
				if (isset($_POST[$i[$pref . '_id']]['email']))
				{
					return utils::tplProtect($_POST[$i[$pref . '_id']]['email']);
				}
				return ($i['user_id'] != 2 || empty($i[$pref . '_email']))
					? ''
					: utils::tplProtect($i[$pref . '_email']);

			// Courriel avec lien.
			case 'email_link' :
				if ($i['user_id'] != 2 || empty($i[$pref . '_email']))
				{
					return '/';
				}
				$email = utils::tplProtect($i[$pref . '_email']);
				return '<a class="ex" href="mailto:' . $email . '">' . $email . '</a>';

			// Identifiant du commentaire.
			case 'id' :
				return (int) $i[$pref . '_id'];

			// IP du posteur.
			case 'ip' :
				return utils::tplProtect($i[$pref . '_ip']);

			// Date de dernière modification.
			case 'lastupddt' :
				return ($i[$pref . '_lastupddt'] == '0000-00-00 00:00:00')
					? $this->getComment('crtdt')
					: utils::tplProtect(
						utils::localeTime(__('%A %d %B %Y à %H:%M:%S'), $i[$pref . '_lastupddt'])
					);

			// Message.
			case 'message' :
				return utils::tplProtect($i[$pref . '_message']);

			// Aperçu du message.
			case 'message_preview' :
				$message = preg_replace('`[\r\n]+`', ' ', $i[$pref . '_message']);
				return utils::tplProtect(mb_strimwidth($message, 0, 50));

			// Type d'objet + identifiant.
			case 'object_type' :
				return sprintf(__('commentaire %s'), $this->getComment('id'));

			// Statut du commentaire.
			case 'status_msg' :
				switch ($i[$pref . '_status'])
				{
					case '-1' :
						return __('en attente');

					case '0' :
						return __('non publié');

					case '1' :
						return __('publié');
				}
				break;

			// Vignette de l'avatar de l'utilisateur.
			case 'user_avatar_src' :
				return ($i['user_avatar'])
					? CONF_GALLERY_PATH . '/users/avatars/user'
						. (int) $i['user_id'] . '_thumb.jpg'
					: $this->getAdmin('style_path') . '/avatar-default.png';

			// Identifiant de l'utilisateur qui a posté le commentaire.
			case 'user_id' :
				return (int) $i['user_id'];

			// Auteur du commentaire, avec lien.
			case 'user_link' :
				return ($i['user_id'] == 2)
					? ''
					: utils::genURL('user/' . $i['user_id']);

			// Site Web.
			case 'website' :
				if (isset($_POST[$i[$pref . '_id']]['website']))
				{
					return utils::tplProtect($_POST[$i[$pref . '_id']]['website']);
				}
				return ($i['user_id'] != 2 || empty($i[$pref . '_website']))
					? ''
					: utils::tplProtect($i[$pref . '_website']);

			// Site Web avec lien.
			case 'website_link' :
				if ($i['user_id'] != 2 || empty($i[$pref . '_website']))
				{
					return '/';
				}
				$com_website = utils::tplProtect($i[$pref . '_website']);
				return '<a class="ex" href="' . $com_website . '">' . $com_website . '</a>';
		}
	}

	/**
	 * Retourne l'élément des options d'affichage $item.
	 *
	 * @param string $item
	 * @return string
	 */
	protected function _getOptions($item, $section)
	{
		switch ($item)
		{
			// Ordre de tri.
			case 'orderby' :
				$p = array(
					'ASC' => __('croissant'),
					'DESC' => __('décroissant')
				);
				return $this->_displayOptionsList($section, $item, 'DESC', $p);

			// Nombre d'objets par page.
			case 'nb_per_page':
				return (int) auth::$infos['user_prefs'][$section][$item];

			// Critère de tri.
			case 'sortby' :
				$p = array(
					'crtdt' => __('date d\'ajout'),
					'lastupddt' => __('date de dernière modification')
				);
				return $this->_displayOptionsList($section, $item, 'crtdt', $p);

			// Statut.
			case 'status' :
				$p = array(
					'all' => __('tous les commentaires'),
					'activate' => __('les commentaires publiés'),
					'deactivate' => __('les commentaires non publiés'),
					'pending' => __('les commentaires en attente')
				);
				return $this->_displayOptionsList($section, $item, 'all', $p);
		}
	}

	/**
	 * Retourne l'élément de recherche $item.
	 *
	 * @param string $item
	 * @return string
	 */
	protected function _getCommentSearch($item)
	{
		switch ($item)
		{
			// Options : cases à cocher.
			case 'com_author' :
			case 'guestbook_author' :
			case 'com_email' :
			case 'guestbook_email' :
			case 'com_website' :
			case 'guestbook_website' :
			case 'com_ip' :
			case 'guestbook_ip' :
			case 'date' :
				return (isset($_GET['search_' . $item]))
					? ' checked="checked"'
					: '';

			// Options : cases à cocher avec option cochée par défaut.
			case 'com_message' :
			case 'guestbook_message' :
				return (!isset($_GET['search_query']) || isset($_GET['search_' . $item]))
					? ' checked="checked"'
					: '';

			// Champs de recherche pour la date.
			case 'date_field_com_crtdt' :
			case 'date_field_guestbook_crtdt' :
			case 'date_field_com_lastupddt' :
			case 'date_field_guestbook_lastupddt' :
				return ((!isset($_GET['search_query']) && substr($item, -5) == 'crtdt')
					|| (isset($_GET['search_date_field'])
					&& $_GET['search_date_field'] == str_replace('date_field_', '', $item)))
					? ' checked="checked"'
					: '';

			// Statut.
			case 'status' :
				$status = array(
					'publish' => __('publié'),
					'unpublish' => __('non publié'),
					'pending' => __('en attente')
				);
				$selected = (!isset($_GET['search_status'])
					|| !isset($status[$_GET['search_status']]))
					? ' selected="selected"'
					: '';
				$list = '<option' . $selected . ' value="all">*' . __('tous') . '</option>';
				foreach ($status as $value => &$text)
				{
					$selected = (isset($_GET['search_status'])
						&& $_GET['search_status'] == $value)
						? ' selected="selected"'
						: '';
					$list .= '<option' . $selected . ' value="' . $value . '">'
						. $text . '</option>';
				}
				return $list;

			default :
				return $this->_getSearch($item);
		}
	}
}

/**
 * Méthodes de template pour la page de gestion des commentaires du livre d'or.
 */
class tplCommentsGuestbook extends tplComments
{
	/**
	 * L'élément de commentaire $item doit-il être affiché ?
	 *
	 * @param string $item
	 * @return boolean
	 */
	public function disComment($item)
	{
		$i = current(guestbook::$items);

		switch ($item)
		{
			// Note.
			case 'rate' :
				$notes = array('1', '2', '3', '4', '5');
				return in_array($i['guestbook_rate'], $notes)
					|| (isset($_POST[$i['guestbook_id']]['rate'])
						&& in_array($_POST[$i['guestbook_id']]['rate'], $notes));

			default :
				return $this->_disComment($item, $i, 'guestbook');
		}
	}

	/**
	 * Retourne l'élément $item du commentaire.
	 *
	 * @param string $item
	 * @return integer|string
	 */
	public function getComment($item)
	{
		$i = current(guestbook::$items);

		// Note.
		$rate = $i['guestbook_rate'];
		$notes = array('1', '2', '3', '4', '5');
		if (isset($_POST[$i['guestbook_id']]['rate'])
		&& in_array($_POST[$i['guestbook_id']]['rate'], $notes))
		{
			$rate = $_POST[$i['guestbook_id']]['rate'];
		}

		switch ($item)
		{
			// Note.
			case 'rate' :
				return (int) $rate;

			case 'rate_select' :
				$list = '<option value="0">' . __('aucune') . '</option>';
				for ($n = 1; $n < 6; $n++)
				{
					$selected = ($n == $rate)
						? ' selected="selected"'
						: '';
					$list .= '<option' . $selected . ' value="' . $n . '">' . $n . '</option>';
				}
				return $list;

			case 'rate_visual' :
				return template::visualRate(
					$rate,
					$this->getAdmin('style_path'),
					'-small'
				);

			default :
				return $this->_getComment($item, $i, 'guestbook', 'guestbook');
		}
	}

	/**
	 * Retourne l'élément des options d'affichage $item.
	 *
	 * @param string $item
	 * @return string
	 */
	public function getOptions($item)
	{
		return $this->_getOptions($item, 'guestbook');
	}

	/**
	 * Retourne l'élément de recherche $item.
	 *
	 * @param string $item
	 * @return string
	 */
	public function getSearch($item)
	{
		switch ($item)
		{
			// Note.
			case 'rate' :
				$notes = array(
					'null' => __('aucune'),
					'1' => '1',
					'2' => '2',
					'3' => '3',
					'4' => '4',
					'5' => '5'
				);
				$selected = (!isset($_GET['search_rate'])
					|| !isset($notes[$_GET['search_rate']]))
					? ' selected="selected"'
					: '';
				$list = '<option' . $selected . ' value="all">*' . __('toutes') . '</option>';
				foreach ($notes as $value => &$text)
				{
					$selected = (isset($_GET['search_rate'])
						&& $_GET['search_rate'] == $value)
						? ' selected="selected"'
						: '';
					$list .= '<option' . $selected . ' value="' . $value . '">'
						. $text . '</option>';
				}
				return $list;

			default :
				return $this->_getCommentSearch($item);
		}
	}
}

/**
 * Méthodes de template pour la page de gestion des commentaires des images.
 */
class tplCommentsImages extends tplComments
{
	/**
	 * Retourne la liste des catégories commentées.
	 *
	 * @return string
	 */
	public function getMap()
	{
		// Filtre de recherche.
		$filter = '';
		if (isset($_GET['date']))
		{
			$filter = '/date/' . $_GET['date'];
		}
		if (isset($_GET['ip']))
		{
			$filter = '/ip/' . $_GET['ip'];
		}
		if (isset($_GET['search']))
		{
			$filter = '/search/' . $_GET['search'];
		}
		if (isset($_GET['status']))
		{
			$filter = '/' . $_GET['status'];
		}
		if (isset($_GET['user_id']))
		{
			$filter = '/user/' . $_GET['user_id'];
		}
		$filter = utils::tplProtect($filter);

		return template::mapSelect(albums::$mapCategories, array(
			'class_selected' => TRUE,
			'selected' => (isset($_GET['object_id']))
				? ($_GET['object_type'] == 'category')
					? $_GET['object_id']
					: comments::$objectInfos['cat_id']
				: 1,
			'value_tpl' => 'comments-images/category/{ID}' . $filter
		));
	}

	/**
	 * L'élément de commentaire $item doit-il être affiché ?
	 *
	 * @param string $item
	 * @return boolean
	 */
	public function disComment($item)
	{
		$i = current(comments::$items);

		switch ($item)
		{
			default :
				return $this->_disComment($item, $i, 'com');
		}
	}

	/**
	 * Retourne l'élément $item du commentaire.
	 *
	 * @param string $item
	 * @return integer|string
	 */
	public function getComment($item)
	{
		$i = current(comments::$items);

		switch ($item)
		{
			// Lien de l'album.
			case 'album_link' :
				return utils::genURL('album/' . $i['cat_id']);

			// Album dans lequel se trouve l'image
			// sur laquelle a été posté le commentaire.
			case 'album_title' :
				return utils::tplProtect(utils::getLocale($i['cat_name']));

			// Identifiant de l'album dans lequel se trouve
			// l'image sur laquelle a été posté le commentaire.
			case 'cat_id' :
				return (int) $i['cat_id'];

			// Lien vers la page des commentaires de l'album.
			case 'comment_album_link' :
				return utils::genURL('comments-images/category/' . $i['cat_id']);

			// Lien vers la page des commentaires de l'image.
			case 'comment_image_link' :
				return utils::genURL('comments-images/image/' . $i['image_id']);

			// Identifiant de l'image sur laquelle a été posté le commentaire.
			case 'image_id' :
				return (int) $i['image_id'];

			// Lien vers l'édition de l'image.
			case 'image_link' :
				return utils::genURL('image/' . $i['image_id']);

			// Titer de l'image sur laquelle a été posté le commentaire
			case 'image_title' :
				return utils::tplProtect(utils::getLocale($i['image_name']));

			// Lien vers la galerie.
			case 'gallery_link' :
				return $this->getGalleryLink('image/'
					. $i['image_id'] . '-' . $i['image_url'])
					. '#co' . $i['com_id'];

			// Paramètres de la vignette.
			case 'thumb_center' :
			case 'thumb_size' :
			case 'thumb_src' :
				return $this->_getThumbImage($item, $i, $this->_thumbForced);

			default :
				return $this->_getComment($item, $i, 'images', 'com');
		}
	}

	/**
	 * Doit-on afficher la liste des images commentées ?
	 * 
	 * @return string
	 */
	public function disImagesList()
	{
		return comments::$images
			|| (isset($_GET['object_type']) && $_GET['object_type'] == 'image');
	}

	/**
	 * Retourne la liste des images commentées.
	 * 
	 * @return string
	 */
	public function getImagesList()
	{
		// Filtre de recherche.
		$filter = '';
		if (isset($_GET['date']))
		{
			$filter = '/date/' . $_GET['date'];
		}
		if (isset($_GET['ip']))
		{
			$filter = '/ip/' . $_GET['ip'];
		}
		if (isset($_GET['search']))
		{
			$filter = '/search/' . $_GET['search'];
		}
		if (isset($_GET['status']))
		{
			$filter = '/' . $_GET['status'];
		}
		if (isset($_GET['user_id']))
		{
			$filter = '/user/' . $_GET['user_id'];
		}
		$filter = utils::tplProtect($filter);

		$images = '<option class="all" value="comments-images/category/'
			. (int) comments::$objectInfos['cat_id'] . $filter
			. '">*' . __('toutes') . '</option>';

		foreach (comments::$images as &$i)
		{
			$selected = ($_GET['object_id'] == $i['image_id'])
				? ' class="selected" selected="selected"'
				: '';
			$images .= '<option' . $selected . ' value="comments-images/image/'
				. (int) $i['image_id'] . $filter . '">'
				. utils::tplProtect($i['image_name']) . '</option>';
		}

		return $images;
	}

	/**
	 * Retourne l'élément des options d'affichage $item.
	 *
	 * @param string $item
	 * @return string
	 */
	public function getOptions($item)
	{
		return $this->_getOptions($item, 'comments');
	}

	/**
	 * Retourne les liens de la barre de position (fil d'ariane).
	 *
	 * @return string
	 */
	public function getPosition()
	{
		// Image.
		if (isset($_GET['object_type']) && $_GET['object_type'] == 'image')
		{
			$position = template::getPosition(
				'',
				'comments-images/category',
				'comments-images/image',
				'comments-images/category',
				TRUE,
				FALSE,
				__('galerie'),
				albums::$parents,
				albums::$infos,
				NULL,
				' / ',
				TRUE
			);
		}

		// Catégorie.
		else
		{
			$position = template::getPosition(
				'',
				'comments-images/category',
				'comments-images/category',
				'',
				TRUE,
				FALSE,
				__('galerie'),
				albums::$parents,
				albums::$infos,
				NULL,
				' / ',
				isset($_GET['object_id']) && $_GET['object_id'] > 1
			);
		}

		// Filtres de recherche.
		$filters = array(
			'date' => 'date/',
			'ip' => 'ip/',
			'status' => '',
			'search' => 'search/',
			'user_id' => 'user/'
		);
		foreach ($filters as $f => $u)
		{
			if (isset($_GET[$f]))
			{
				$position = preg_replace('`(\?q=comments-images/(?:category|image)/\d+)`',
					'$1/' . $u . $_GET[$f], $position);
			}
		}

		return $position;
	}

	/**
	 * Retourne l'élément de recherche $item.
	 *
	 * @param string $item
	 * @return string
	 */
	public function getSearch($item)
	{
		switch ($item)
		{
			default :
				return $this->_getCommentSearch($item);
		}
	}
}

/**
 * Méthodes de template pour la page des options des commentaires.
 */
class tplCommentsOptions extends tplAdmin
{
	/**
	 * Retourne l'option de commentaire $item.
	 *
	 * @param string $item
	 * @return string
	 */
	public function getOption($item)
	{
		switch ($item)
		{
			// Cases à cocher.
			case 'comments_required_email' :
			case 'comments_required_website' :
			case 'comments_smilies' :
			case 'comments_words_limit' :
			case 'comments_convert_urls' :
				return utils::$config[$item]
					? ' checked="checked"'
					: '';

			// Champs integer.
			case 'comments_maxurls' :
			case 'comments_antiflood' :
			case 'comments_maxchars' :
			case 'comments_maxlines' :
			case 'comments_words_maxlength' :
			case 'comments_links_maxlength' :
				return (int) utils::$config[$item];

			// Listes.
			case 'comments_order' :
				$list = array(
				 'ASC' => __('Du plus ancien au plus récent'),
				 'DESC' => __('Du plus récent au plus ancien')
				);
				$options = '';
				foreach ($list as $o => &$text)
				{
					$selected = (utils::$config[$item] == $o)
						? ' selected="selected"'
						: '';
					$options .= '<option' . $selected
						. ' value="' . $o . '">' . $text . '</option>';
				}
				return $options;

			// Modération des commentaires.
			case 'comments_moderate' :
				if (utils::$config['users'])
				{
					return ' disabled="disabled"';
				}
				return utils::$config[$item]
					? ' checked="checked"'
					: '';

			// Jeu d'icônes pour les smilies.
			case 'comments_smilies_icons_pack' :
				$options = '';
				foreach (scandir(GALLERY_ROOT . '/images/smilies') as $dirname)
				{
					if (preg_match('`^[-a-z0-9_]{1,64}$`i', $dirname) &&
					file_exists(GALLERY_ROOT . '/images/smilies/' . $dirname . '/icons.php'))
					{
						$d = utils::tplProtect($dirname);
						$selected = '';
						if (utils::$config[$item] == $d)
						{
							$selected = ' selected="selected"';
						}
						$options .= '<option' . $selected
							. ' value="' . $d . '">' . $d . '</option>';
					}
				}
				return $options;
		}
	}
}

/**
 * Méthodes de template pour le tableau de bord.
 */
class tplDashboard extends tplAdmin
{
	/**
	 * Taille de vignette forcée, communiquée par le template.
	 *
	 * @see function nextLastImages
	 * @var integer
	 */
	private $_thumbForced = 0;



	/**
	 * L'élément du tableay de board $item doit-il être affiché ?
	 *
	 * @param string $item
	 * @return boolean
	 */
	public function disDashboard($item = '')
	{
		switch ($item)
		{
			// Commentaires en attente de validation.
			case 'comments_pending' :
				return !empty(admin::$infos['nb_comments_pending'])
					&& (auth::$perms['admin']['perms']['comments_edit']
					 || auth::$perms['admin']['perms']['all']);

			// Images en attente de validation.
			case 'images_pending' :
				return !empty(admin::$infos['nb_images_pending'])
					&& (auth::$perms['admin']['perms']['albums_pending']
					 || auth::$perms['admin']['perms']['all']);

			// Incidents.
			case 'incidents' :
				return !empty(admin::$infos['errors']);

			// Commentaires dans le livre d'or en attente de validation.
			case 'guestbook_pending' :
				return !empty(admin::$infos['nb_guestbook_pending'])
					&& (auth::$perms['admin']['perms']['comments_edit']
					 || auth::$perms['admin']['perms']['all']);

			// Derniers commentaires.
			case 'lastcomments' :
				return !utils::$config['admin_dashboard_start_message']
					&& utils::$config['comments']
					&& !isset(admin::$tplDisabledConfig['comments'])
					&& (auth::$perms['gallery']['perms']['read_comments']
					 || auth::$infos['user_id'] == 1);

			// Derniers utilisateurs.
			case 'lastusers' :
				return !utils::$config['admin_dashboard_start_message']
					&& utils::$config['users']
					&& !isset(admin::$tplDisabledConfig['users']);

			// Objets en attente de validation.
			case 'pending' :
				return $this->disDashboard('comments_pending')
					|| $this->disDashboard('guestbook_pending')
					|| $this->disDashboard('images_pending')
					|| $this->disDashboard('users_pending');

			// Message de démarrage.
			case 'start_message' :
				return (bool) utils::$config['admin_dashboard_start_message'];

			// Informations système.
			case 'sysinfos' :
				return auth::$infos['user_id'] == 1;

			// Utilisateurs en attente de validation.
			case 'users_pending' :
				return !empty(admin::$infos['nb_users_pending'])
					&& (auth::$perms['admin']['perms']['users_members']
					 || auth::$perms['admin']['perms']['all']);
		}
	}

	/**
	 * Retourne le nombre d'incidents enregistrés.
	 *
	 * @return string
	 */
	public function getIncidents()
	{
		return (admin::$infos['errors'] > 1)
			? sprintf(__('%s incidents'), admin::$infos['errors'])
			: sprintf(__('%s incident'), admin::$infos['errors']);
	}

	/**
	 * Retourne l'élément $item du bloc "Derniers commentaires".
	 *
	 * @param string $item
	 * @return string
	 */
	public function getLastComments($item)
	{
		if (empty(admin::$infos['lastcomments']))
		{
			return;
		}

		$i = current(admin::$infos['lastcomments']);

		switch ($item)
		{
			// Informations sur le commentaire.
			case 'infos' :

				// Auteur.
				$author = utils::tplProtect($i['author']);
				$user = '<span>' . $author . '</span>';
				if ($i['user_id'] != 2)
				{
					if ($this->disPerm('users_members'))
					{
						$user = '<a href="' . utils::genURL('user/'
							. $i['user_id']) . '">' . $author . '</a>';
					}
					else if (utils::$config['users'])
					{
						$user = '<a href="' .  $this->getGalleryLink('user/'
							. $i['user_id']) . '">' . $author . '</a>';
					}
				}

				// Date.
				$date = utils::localeTime(__('%A %d %B %Y'), $i['com_crtdt']);

				// Commentaire dans le livre d'or.
				if ($i['cat_id'] == 'guestbook')
				{
					$link = ($this->disPerm('comments_edit'))
						? utils::genURL('comments-guestbook')
						: $this->getGalleryLink('guestbook');
					$infos = sprintf(
						__('Posté le %s par %s dans le %s.'),
						'<span>' . mb_strtolower($date) . '</span>',
						$user,
						'<a href="' . $link . '">' . __('livre d\'or') . '</a>'
					);
				}

				// Commentaire sur une image.
				else
				{
					$image_link = (($this->disPerm('albums_edit')
						|| $this->disPerm('albums_modif')) && $this->_checkPerm($i))
						? utils::genURL('image/' . $i['image_id'])
						: $this->getGalleryLink(
							'album/' . $i['image_id'] . '-' . $i['image_url']
						  );
					$album_link = (($this->disPerm('albums_edit')
						|| $this->disPerm('albums_modif')) && $this->_checkPerm($i))
						? utils::genURL('album/' . $i['cat_id'])
						: $this->getGalleryLink('album/' . $i['cat_id'] . '-' . $i['cat_url']);
					$infos = sprintf(
						__('Posté le %s par %s sur l\'image %s de l\'album %s.'),
						'<span>' . mb_strtolower($date) . '</span>',
						$user,
						'<a href="' . $image_link . '">'
							. utils::tplProtect(utils::getLocale($i['image_name'])) . '</a>',
						'<a href="' . $album_link . '">'
							. utils::tplProtect(utils::getLocale($i['cat_name'])) . '</a>'
					);
				}

				return $infos;

			// Message.
			case 'message' :
				return strip_tags(template::formatComment(
					$i['com_message'],
					admin::$infos['smilies'],
					utils::$config['comments_smilies_icons_pack']
				), '<br><img>');

			// Nombre total de commentaires.
			case 'nb_items' :
				return count(admin::$infos['lastcomments']);
		}
	}

	/**
	 * Y a-t-il un prochain commentaire ?
	 * 
	 * @return boolean
	 */
	public function nextLastComments()
	{
		static $next = -1;

		return template::nextObject(admin::$infos['lastcomments'], $next);
	}

	/**
	 * Retourne l'élément $item du bloc "Dernières images".
	 *
	 * @param string $item
	 * @return string
	 */
	public function getLastImages($item)
	{
		if (empty(admin::$infos['lastimages']))
		{
			return;
		}

		$i = current(admin::$infos['lastimages']);

		switch ($item)
		{
			// Centrage de la vignette par CSS.
			case 'center' :
				$tb = img::getThumbSize($i, 'img', $this->_thumbForced);
				return img::thumbCenter('img', $tb['width'],
					$tb['height'], $this->_thumbForced);

			// Identifiant.
			case 'id' :
				return utils::tplProtect($i['image_id']);

			// Lien vers l'édition de l'image.
			case 'image_link' :
				if (($this->disPerm('albums_edit') || $this->disPerm('albums_modif'))
				&& $this->_checkPerm($i))
				{
					return utils::genURL('image/' . $i['image_id']);
				}
				return (utils::$config['images_direct_link'])
					? CONF_GALLERY_PATH . '/image.php?id=' . (int) $i['image_id']
					: $this->getGalleryLink(
						'image/' . $i['image_id'] . '-' . $i['image_url']
					  );

			// Informations sur l'image.
			case 'infos' :
				$album_link = (($this->disPerm('albums_edit') || $this->disPerm('albums_modif'))
					&& $this->_checkPerm($i))
					? utils::genURL('album/' . $i['cat_id'])
					: $this->getGalleryLink('album/' . $i['cat_id'] . '-' . $i['cat_url']);
				$user_login = utils::tplProtect($i['user_login']);
				$user = '<span>' . $user_login . '</span>';
				if ($i['user_id'] != 2)
				{
					if ($this->disPerm('users_members'))
					{
						$user = '<a href="' . utils::genURL('user/'
							. $i['user_id']) . '">' . $user_login . '</a>';
					}
					else if (utils::$config['users'])
					{
						$user = '<a href="' .  $this->getGalleryLink('user/'
							. $i['user_id']) . '">' . $user_login . '</a>';
					}
				}
				$date = utils::localeTime(__('%A %d %B %Y'), $i['image_adddt']);
				return sprintf(
					__('Ajoutée le %s par %s dans l\'album %s.'),
					'<span>' . mb_strtolower($date) . '</span>',
					$user,
					'<a href="' . $album_link . '">'
						. utils::tplProtect(utils::getLocale($i['cat_name'])) . '</a>'
				);

			// Nombre total d'images.
			case 'nb_items' :
				return count(admin::$infos['lastimages']);

			// Emplacement de la vignette.
			case 'src' :
				return utils::tplProtect(template::getThumbSrc('img', $i));

			// Dimensions de la vignette.
			case 'thumb_size' :
				$tb = img::getThumbSize($i, 'img', $this->_thumbForced);
				return 'width="' . $tb['width'] . '" height="' . $tb['height'] . '"';

			// Titre.
			case 'title' :
				return utils::tplProtect($i['image_name']);
		}
	}

	/**
	 * Y a-t-il une prochaine image ?
	 * 
	 * @param integer $thumb_size
	 *	Taille des vignettes.
	 * @return boolean
	 */
	public function nextLastImages($thumb_size)
	{
		static $next = -1;

		$this->_thumbForced =& $thumb_size;

		return template::nextObject(admin::$infos['lastimages'], $next);
	}

	/**
	 * L'élément du bloc des derniers utilisateurs $item doit-il être affiché ?
	 *
	 * @param string $item
	 * @return boolean
	 */
	public function disLastUsers($item = '')
	{
		switch ($item)
		{
			case 'avatar' :
				return (bool) utils::$config['avatars'];
		}
	}

	/**
	 * Retourne l'élément $item du bloc "Derniers utilisateurs".
	 *
	 * @param string $item
	 * @return string
	 */
	public function getLastUsers($item)
	{
		if (empty(admin::$infos['lastusers']))
		{
			return;
		}

		$i = current(admin::$infos['lastusers']);

		switch ($item)
		{
			// Emplacement de l'avatar.
			case 'avatar_src' :
				return ($i['user_avatar'])
					? CONF_GALLERY_PATH . '/users/avatars/user'
						. (int) $i['user_id'] . '_thumb.jpg'
					: $this->getAdmin('style_path') . '/avatar-default.png';

			// Informations sur l'utilisateur.
			case 'infos' :
				$user = '<a href="' . $this->getLastUsers('user_link') . '">'
					. $this->getLastUsers('user_login') . '</a>';
				$date = utils::localeTime(__('%A %d %B %Y'), $i['user_crtdt']);
				return sprintf(
					__('%s, inscrit le %s.'),
					$user,
					'<span>' . mb_strtolower($date) . '</span>'
				);

			// Nombre total d'utilisateurs.
			case 'nb_items' :
				return count(admin::$infos['lastusers']);

			// Lien vers le profil utilisateur.
			case 'user_link' :
				return ($this->disPerm('users_members'))
					? utils::genURL('user/' . (int) $i['user_id'])
					: $this->getGalleryLink('user/' . (int) $i['user_id']);

			// Nom d'utilisateur.
			case 'user_login' :
				return utils::tplProtect($i['user_login']);
		}
	}

	/**
	 * Y a-t-il un prochain utilisateur ?
	 * 
	 * @return boolean
	 */
	public function nextLastUsers()
	{
		static $next = -1;

		return template::nextObject(admin::$infos['lastusers'], $next);
	}

	/**
	 * Retourne l'élément $item du bloc "En attente de validation".
	 *
	 * @param string $item
	 * @return string
	 */
	public function getPending($item)
	{
		switch ($item)
		{
			// Nombre de commentaires en attente de validation.
			case 'comments_pending' :
				$nb_items = (int) admin::$infos['nb_' . $item];
				$message = ($nb_items > 1)
					? __('%s commentaires')
					: __('%s commentaire');
				$message .= ' (' . __('images') . ')';
				return '<a href="' . utils::genURL('comments-images/pending') . '">'
					. sprintf($message, $nb_items) . '</a>';

			// Nombre de commentaires dans le livre d'or en attente de validation.
			case 'guestbook_pending' :
				$nb_items = (int) admin::$infos['nb_' . $item];
				$message = ($nb_items > 1)
					? __('%s commentaires')
					: __('%s commentaire');
				$message .= ' (' . __('livre d\'or') . ')';
				return '<a href="' . utils::genURL('comments-guestbook/pending') . '">'
					. sprintf($message, $nb_items) . '</a>';

			// Nombre d'images en attente de validation.
			case 'images_pending' :
				$nb_items = (int) admin::$infos['nb_' . $item];
				$message = ($nb_items > 1)
					? __('%s images')
					: __('%s image');
				return '<a href="' . utils::genURL('images-pending') . '">'
					. sprintf($message, $nb_items) . '</a>';

			// Nombre d'utilisateurs en attente de validation.
			case 'users_pending' :
				$nb_items = (int) admin::$infos['nb_' . $item];
				$message = ($nb_items > 1)
					? __('%s utilisateurs')
					: __('%s utilisateur');
				return '<a href="' . utils::genURL('users/pending') . '">'
					. sprintf($message, $nb_items) . '</a>';
		}
	}

	/**
	 * L'élément des statistiques de la galerie $item doit-il être affiché ?
	 *
	 * @param string $item
	 * @return boolean
	 */
	public function disStats($item)
	{
		switch ($item)
		{
			case 'nb_comments' :
				return utils::$config['comments']
					&& !isset(admin::$tplDisabledConfig['comments'])
					&& (auth::$perms['gallery']['perms']['read_comments']
					 || auth::$infos['user_id'] == 1);

			case 'nb_tags' :
				return utils::$config['tags']
					&& !isset(admin::$tplDisabledConfig['tags']);

			case 'nb_votes' :
				return utils::$config['votes']
					&& !isset(admin::$tplDisabledConfig['votes']);

			case 'nb_favorites' :
			case 'nb_members' :
				return utils::$config['users']
					&& !isset(admin::$tplDisabledConfig['users']);
		}
	}

	/**
	 * Retourne l'élément $item du bloc "Statistiques de la galerie".
	 *
	 * @param string $item
	 * @return string
	 */
	public function getStats($item)
	{
		switch ($item)
		{
			// Nombre d'administrateurs.
			case 'nb_admins' :
				$nb = admin::$infos[$item];
				$nb = ($nb === NULL) ? 0 : $nb;
				return ($nb > 1)
					? sprintf(__('%s administrateurs'), '<span>' . (int) $nb . '</span>')
					: sprintf(
						__('%s administrateur'),
						'<span>' . utils::tplProtect($nb) . '</span>'
					);

			// Nombre d'albums.
			case 'nb_albums' :
				$nb = admin::$infos[$item];
				$nb = ($nb === NULL) ? 0 : $nb;
				return ($nb > 1)
					? sprintf(__('%s albums'), '<span>' . (int) $nb . '</span>')
					: sprintf(__('%s album'), '<span>' . utils::tplProtect($nb) . '</span>');

			// Nombre de commentaires.
			// Nombre de commentaires en attente de validation.
			case 'nb_comments' :
			case 'nb_comments_pending' :
				$nb = admin::$infos[$item];
				$nb = ($nb === NULL) ? 0 : $nb;
				return ($nb > 1)
					? sprintf(__('%s commentaires'), '<span>' . (int) $nb . '</span>')
					: sprintf(__('%s commentaire'), '<span>' . utils::tplProtect($nb) . '</span>');

			// Nombre de favoris.
			case 'nb_favorites' :
				$nb = admin::$infos[$item];
				$nb = ($nb === NULL) ? 0 : $nb;
				return ($nb > 1)
					? sprintf(__('%s favoris'), '<span>' . (int) $nb . '</span>')
					: sprintf(__('%s favori'), '<span>' . utils::tplProtect($nb) . '</span>');

			// Nombre de groupes.
			case 'nb_groups' :
				$nb = admin::$infos[$item];
				$nb = ($nb === NULL) ? 0 : $nb;
				return ($nb > 1)
					? sprintf(__('%s groupes'), '<span>' . (int) $nb . '</span>')
					: sprintf(__('%s groupe'), '<span>' . utils::tplProtect($nb) . '</span>');

			// Nombre de visites.
			case 'nb_hits' :
				$nb = admin::$infos[$item];
				$nb = ($nb === NULL) ? 0 : $nb;
				return ($nb > 1)
					? sprintf(__('%s visites'), '<span>' . (int) $nb . '</span>')
					: sprintf(__('%s visite'), '<span>' . utils::tplProtect($nb) . '</span>');

			// Nombre d'images.
			// Nombre d'images en attente de validation.
			case 'nb_images' :
			case 'nb_images_pending' :
				$nb = admin::$infos[$item];
				$nb = ($nb === NULL) ? 0 : $nb;
				return ($nb > 1)
					? sprintf(__('%s images'), '<span>' . (int) $nb . '</span>')
					: sprintf(__('%s image'), '<span>' . utils::tplProtect($nb) . '</span>');

			// Nombre de membres.
			case 'nb_members' :
				$nb = admin::$infos[$item];
				$nb = ($nb === NULL) ? 0 : $nb;
				return ($nb > 1)
					? sprintf(__('%s membres'), '<span>' . (int) $nb . '</span>')
					: sprintf(__('%s membre'), '<span>' . utils::tplProtect($nb) . '</span>');

			// Nombre de tags.
			case 'nb_tags' :
				$nb = admin::$infos[$item];
				$nb = ($nb === NULL) ? 0 : $nb;
				return ($nb > 1)
					? sprintf(__('%s tags'), '<span>' . (int) $nb . '</span>')
					: sprintf(__('%s tag'), '<span>' . utils::tplProtect($nb) . '</span>');

			// Nombre de votes.
			case 'nb_votes' :
				$nb = admin::$infos[$item];
				$nb = ($nb === NULL) ? 0 : $nb;
				return ($nb > 1)
					? sprintf(__('%s votes'), '<span>' . (int) $nb . '</span>')
					: sprintf(__('%s vote'), '<span>' . utils::tplProtect($nb) . '</span>');

			// Nombre d'utilisateurs en attente de validation.
			case 'nb_users_pending' :
				$nb = admin::$infos[$item];
				$nb = ($nb === NULL) ? 0 : $nb;
				return ($nb > 1)
					? sprintf(__('%s utilisateurs'), '<span>' . (int) $nb . '</span>')
					: sprintf(__('%s utilisateur'), '<span>' . utils::tplProtect($nb) . '</span>');
		}
	}

	/**
	 * Retourne l'élément $item du bloc "Informations système".
	 *
	 * @param string $item
	 * @return string
	 */
	public function getSysInfos($item)
	{
		switch ($item)
		{
			case 'gallery_version' :
				return sprintf(
					__('Version de %s : %s'),
					'iGalerie',
					'<span>' . system::$galleryVersion . '</span>'
				);

			case 'gd_version' :
				return sprintf(
					__('Version de %s : %s'),
					'GD',
					'<span>' . utils::tplProtect(system::getGDVersion()) . '</span>'
				);

			case 'mysql_version' :
				return sprintf(
					__('Version de %s : %s'),
					'MySQL',
					'<span>' . utils::tplProtect(admin::$infos[$item]) . '</span>'
				);

			case 'os' :
				$os = (system::getOSDetails('sr') != '?')
					? system::getOSDetails('sr')
					: system::getOS();
				return sprintf(
					__('Système d\'exploitation : %s'),
					'<span>' . utils::tplProtect($os) . '</span>'
				);

			case 'php_version' :
				return sprintf(
					__('Version de %s : %s'),
					'PHP',
					'<span>' . system::getPHPVersion() . '</span>'
				);
		}
	}



	/**
	 * L'utilisateur a-t-il la permission d'administrer l'objet ?
	 *
	 * @param array $i
	 * @return boolean
	 */
	private function _checkPerm(&$i)
	{
		if ($i['cat_password'] === NULL)
		{
			return TRUE;
		}
		else
		{
			switch (auth::$perms['admin']['perms']['access_mode'])
			{
				case 1 :
					return TRUE;
					break;

				case 2 :
					$password_id = preg_replace('`^(\d+):.*`', '$1', $i['cat_password']);
					foreach (auth::$perms['admin']['albums_access'] as $id => &$v)
					{
						if ($password_id == $id)
						{
							return TRUE;
						}
					}
					break;
			}
		}

		return FALSE;
	}
}

/**
 * Méthodes de template pour la page d'ajout d'images par FTP.
 */
class tplFTP extends tplAdmin
{
	/**
	 * L'élément de rapport $item doit-il être affiché ?
	 *
	 * @param string $item
	 * @return boolean
	 */
	public function disFTPReport($item)
	{
		switch ($item)
		{
			// Message d'erreur.
			case 'error' :
				return ftp::$msgError !== NULL;

			// Rapport détaillé.
			case 'report_details' :
				return count(ftp::$report['errors']) > 0
					|| ftp::$report['alb_add']
					|| ftp::$report['alb_update']
					|| ftp::$report['cat_reject']
					|| ftp::$report['img_reject'];

			// Rapport résumé.
			case 'report_sum' :
				return isset($_POST['action']) && $_POST['action'] == 'scan';

			// Message indiquant que la durée limite du scan a été dépassée.
			case 'time_exceeded' :
				return ftp::$msgTimeExceeded !== NULL;
		}
	}

	/**
	 * Retourne l'élément de rapport $item.
	 *
	 * @param string $item
	 * @return string
	 */
	public function getFTPReport($item)
	{
		switch ($item)
		{
			// Message d'erreur.
			case 'error' :
				return utils::tplProtect(ftp::$msgError);

			// Identifiants des groupes à exclure pour la notification
			// par courriel lors du prochain scan.
			case 'notify_groups_exclude' :
				return (is_array(ftp::$notifyGroups))
					? utils::tplProtect(implode('-', ftp::$notifyGroups))
					: '';

			// Rapport détaillé.
			case 'report_details' :
				$report_details = '';

				// Erreurs.
				if (count(ftp::$report['errors']) > 0)
				{
					$report_details .= '<div class="report_details report_details_errors">'
						. '<table>' . "\n" . '<tr><th>'
						. __('Objet') . '</th><th>'
						. __('Erreur') . '</th></tr>' . "\n";
					foreach (ftp::$report['errors'] as &$v)
					{
						$report_details .= '<tr><td class="object">'
							. nl2br(utils::tplProtect(wordwrap($v[0], 50, "\n", TRUE)))
							. '</td><td>' . $v[1] . '</td></tr>';
					}
					$report_details .= '</table></div>' . "\n";
				}

				// Albums ajoutés.
				if (ftp::$report['alb_add'])
				{
					$report_details .= '<div class="report_details">'
						. '<table>' . "\n" . '<tr><th>'
						. __('Album ajouté') . '</th><th>'
						. __('Nombre d\'images') . '</th><th>'
						. __('Poids') . '</th></tr>' . "\n";
					foreach (ftp::$report['alb_add'] as &$v)
					{
						$report_details .= '<tr><td class="object">'
							. nl2br(utils::tplProtect(wordwrap($v[0], 50, "\n", TRUE)))
							. '</td><td class="number">' . $v[1] . '</td><td class="number">'
							. utils::filesize($v[2]) . '</td></tr>';
					}
					$report_details .= '</table></div>' . "\n";
				}

				// Albums mis à jour.
				if (ftp::$report['alb_update'])
				{
					$report_details .= '<div class="report_details">'
						. '<table>' . "\n" . '<tr><th>'
						. __('Album mis à jour') . '</th><th>'
						. __('Nombre d\'images supplémentaires') . '</th><th>'
						. __('Poids supplémentaire') . '</th></tr>' . "\n";
					foreach (ftp::$report['alb_update'] as &$v)
					{
						$report_details .= '<tr><td class="object">'
							. nl2br(utils::tplProtect(wordwrap($v[0], 50, "\n", TRUE)))
							. '</td><td class="number">' . $v[1] . '</td><td class="number">'
							. utils::filesize($v[2]) . '</td></tr>';
					}
					$report_details .= '</table></div>' . "\n";
				}

				// Catégories rejetées.
				if (ftp::$report['cat_reject'])
				{
					$report_details .= '<div class="report_details report_details_warning">'
						. '<table>' . "\n" . '<tr><th>'
						. __('Catégorie rejetée') . '</th><th>'
						. __('Cause') . '</th></tr>' . "\n";
					foreach (ftp::$report['cat_reject'] as &$v)
					{
						$report_details .= '<tr><td class="object">'
							. nl2br(utils::tplProtect(wordwrap($v[0], 50, "\n", TRUE)))
							. '</td><td>' . $v[1] . '</td></tr>';
					}
					$report_details .= '</table></div>' . "\n";
				}

				// Images rejetées.
				if (ftp::$report['img_reject'])
				{
					$report_details .= '<div class="report_details report_details_warning">'
						. '<table>' . "\n" . '<tr><th>'
						. __('Image rejetée') . '</th><th>'
						. __('Album') . '</th><th>'
						. __('Cause') . '</th></tr>' . "\n";
					foreach (ftp::$report['img_reject'] as &$v)
					{
						$report_details .= '<tr><td class="object">'
							. nl2br(utils::tplProtect(wordwrap($v[0], 50, "\n", TRUE)))
							. '</td><td class="object">'
							. nl2br(utils::tplProtect(wordwrap($v[1], 50, "\n", TRUE)))
							. '</td><td>' . $v[2] . '</td></tr>';
					}
					$report_details .= '</table></div>' . "\n";
				}

				return $report_details;

			// Rapport résumé.
			case 'report_sum' :
				$report_sum = '';

				// Erreurs.
				if (ftp::$report['errors'])
				{
					$message = (count(ftp::$report['errors']) > 1)
						? __('%s erreurs se sont produites.')
						: __('%s erreur s\'est produite.');

					$report_sum .= '<p class="report_msg report_error">'
						. sprintf($message, count(ftp::$report['errors']))
						. '</p>' . "\n";
				}

				// Albums et images ajoutés.
				if ((count(ftp::$report['alb_add']) + ftp::$report['img_add']) > 0)
				{
					if (count(ftp::$report['alb_add']) > 0)
					{
						$albums = (count(ftp::$report['alb_add']) > 1)
							? __('%s albums')
							: __('%s album');
						$albums = sprintf($albums, count(ftp::$report['alb_add']));
						$images = (ftp::$report['img_add'] > 1)
							? __('%s images')
							: __('%s image');
						$images = sprintf($images, ftp::$report['img_add']);
						$message = sprintf(
							__('%s et %s ont été ajoutés à la base de données.'),
							$albums, $images
						);
					}
					else
					{
						$message = (ftp::$report['img_add'] > 1)
							? __('%s images ont été ajoutées à la base de données.')
							: __('%s image a été ajoutée à la base de données.');
						$message = sprintf($message, ftp::$report['img_add']);
					}
					$report_sum .= '<p class="report_msg report_success">'
						. $message . '</p>' . "\n";
				}
				else
				{
					$report_sum .= '<p class="report_msg report_info">'
						. __('Aucun nouvel album et aucune nouvelle'
						. ' image n\'a été détecté.') . '</p>' . "\n";
				}

				// Albums et images mis à jour.
				if ((count(ftp::$report['alb_update']) + ftp::$report['img_update']) > 0)
				{
					if (ftp::$report['img_update'] > 0)
					{
						$albums = (count(ftp::$report['alb_update']) > 1)
							? __('%s albums')
							: __('%s album');
						$albums = sprintf($albums, count(ftp::$report['alb_update']));
						$images = (ftp::$report['img_update'] > 1)
							? __('%s images')
							: __('%s image');
						$images = sprintf($images, ftp::$report['img_update']);
						$message = sprintf(
							__('%s et %s ont été mis à jour.'),
							$albums, $images
						);
					}
					else
					{
						$message = (count(ftp::$report['alb_update']) > 1)
							? __('%s albums ont été mis à jour.')
							: __('%s album a été mis à jour.');
						$message = sprintf($message, count(ftp::$report['alb_update']));
					}
					$report_sum .= '<p class="report_msg report_success">'
						. $message . '</p>' . "\n";
				}
				else
				{
					$message = __('Aucun album n\'a été mis à jour.');
					$report_sum .= '<p class="report_msg report_info">'
						. $message . '</p>' . "\n";
				}

				// Rejets.
				if ((count(ftp::$report['cat_reject']) + count(ftp::$report['img_reject'])) > 0)
				{
					if (count(ftp::$report['cat_reject']) > 0
					&& count(ftp::$report['img_reject']) > 0)
					{
						$categories = (count(ftp::$report['cat_reject']) > 1)
							? __('%s catégories')
							: __('%s catégorie');
						$categories = sprintf($categories, count(ftp::$report['cat_reject']));
						$images = (count(ftp::$report['img_reject']) > 1)
							? __('%s images')
							: __('%s image');
						$images = sprintf($images, count(ftp::$report['img_reject']));
						$message = sprintf(
							__('%s et %s ont été rejetées.'),
							$categories, $images
						);
					}
					elseif (count(ftp::$report['cat_reject']) > 0)
					{
						$message = (count(ftp::$report['cat_reject']) > 1)
							? __('%s catégories ont été rejetées.')
							: __('%s catégorie a été rejetée.');
						$message = sprintf($message, count(ftp::$report['cat_reject']));
					}
					else
					{
						$message = (count(ftp::$report['img_reject']) > 1)
							? __('%s images ont été rejetées.')
							: __('%s image a été rejetée.');
						$message = sprintf($message, count(ftp::$report['img_reject']));
					}
					$report_sum .= '<p class="report_msg report_warning">'
						. $message . '</p>' . "\n";
				}

				return $report_sum;

			// Message indiquant que la durée limite du scan a été dépassée.
			case 'time_exceeded' :
				return ftp::$msgTimeExceeded;
		}
	}
}

/**
 * Méthodes de template pour les pages de paramétrage des fonctionnalités.
 */
class tplFunctions extends tplAdmin
{
	/**
	 * La fonctionnalité $item est-elle disponible ?
	 *
	 * @param string $item
	 * @return boolean
	 */
	public function disFunction($item)
	{
		switch ($item)
		{
			case 'exif' :
				return system::$phpExtensions['exif'];

			case 'zip' :
				return system::$phpExtensions['zip'];
		}
	}

	/**
	 * Retourne le paramètre de fonctionnalité $item.
	 *
	 * @param string $item
	 * @return string
	 */
	public function getFunction($item)
	{
		switch ($item)
		{
			// Cases à cocher.
			case 'basket' :
			case 'comments' :
			case 'diaporama' :
			case 'diaporama_auto_start' :
			case 'diaporama_carousel' :
			case 'diaporama_hits' :
			case 'diaporama_resize_gd' :
			case 'download_zip_albums' :
			case 'exif' :
			case 'exif_crtdt' :
			case 'exif_camera' :
			case 'exif_gps' :
			case 'geoloc' :
			case 'iptc' :
			case 'iptc_description' :
			case 'iptc_keywords' :
			case 'iptc_title' :
			case 'rss' :
			case 'search' :
			case 'search_advanced' :
			case 'tags' :
			case 'users' :
			case 'votes' :
			case 'watermark' :
			case 'watermark_categories' :
			case 'watermark_users' :
			case 'xmp' :
			case 'xmp_crtdt' :
			case 'xmp_description' :
			case 'xmp_keywords' :
			case 'xmp_title' :
			case 'xmp_priority' :
				return utils::$config[$item]
					? ' checked="checked"'
					: '';

			// Entiers.
			case 'basket_max_filesize' :
			case 'basket_max_images' :
			case 'diaporama_resize_gd_height' :
			case 'diaporama_resize_gd_quality' :
			case 'diaporama_resize_gd_width' :
			case 'rss_max_items' :
				return (int) utils::$config[$item];

			// Boutons radios.
			case 'rss_notify_albums_0' :
			case 'rss_notify_albums_1' :
				return (utils::$config['rss_notify_albums'] == $item[strlen($item)-1])
					? ' checked="checked"'
					: '';

			// Géolocalisation : type de carte.
			case 'geoloc_type' :
				$options = '';
				$types = array(
					'ROADMAP' => __('Plan'),
					'TERRAIN' => __('Plan avec relief'),
					'SATELLITE' => __('Satellite'),
					'HYBRID' => __('Satellite avec légendes')
				);
				foreach ($types as $type => $text)
				{
					$selected = (utils::$config['geoloc_type'] == $type)
						? ' selected="selected"'
						: '';
					$options .= '<option' . $selected . ' value="' . $type . '">'
						. $text . '</option>';
				}
				return $options;
		}
	}
}

/**
 * Méthodes de template pour la section "incidents".
 */
class tplIncidents extends tplAdmin
{
	/**
	 * L'élément des incidents $item doit-il être affiché ?
	 *
	 * @param string $item
	 * @return boolean
	 */
	public function disIncidents($item = '')
	{
		switch ($item)
		{
			case 'export' :
				return class_exists('ZipArchive', FALSE);

			default :
				return !empty(admin::$infos);
		}
	}

	/**
	 * Retourne l'information de l'incident courant $item.
	 *
	 * @param string $item
	 * @return array|string
	 */
	public function getIncident($item)
	{
		$i = current(admin::$infos);

		switch ($item)
		{
			// Date.
			case 'date' :
				return utils::tplprotect($i->date);

			// Details.
			case 'details' :
				$details = $i->details;
				$details = urldecode($details);
				if (utils::isSerializedArray($details))
				{
					$details = unserialize($details);
				}
				return $details;

			// Nom de fichier XML de l'erreur.
			case 'error_file' :
				return utils::tplprotect(substr($i['error_file'], 0, -4));

			// Fichier.
			case 'file' :
				return utils::tplprotect(urldecode($i->file));

			// Ligne.
			case 'line' :
				return utils::tplprotect(urldecode($i->line));

			// Message.
			case 'message' :
				$message = urldecode($i->message);
				$message = preg_replace('` \[<a href=[^\]]+function\.[^\]]+</a>\]`',
					'', $message);
				$message = str_replace(GALLERY_ROOT, '', $message);
				return nl2br(utils::tplprotect($message));

			// Page.
			case 'page' :
				return ($i->q)
					? utils::tplprotect(urldecode($i->q))
					: '?';

			// Type.
			case 'type' :
				return utils::tplprotect($i->type);

			// Version d'iGalerie.
			case 'version' :
				return ($i->q)
					? utils::tplprotect(urldecode($i->version))
					: '?';
		}
	}

	/**
	 * Retourne le nombre d'incidents.
	 *
	 * @return string
	 */
	public function getNbIncidents()
	{
		return count(admin::$infos);
	}

	/**
	 * Y a-t-il un prochain incident ?
	 * 
	 * @return boolean
	 */
	public function nextIncident()
	{
		static $next = -1;

		return template::nextObject(admin::$infos, $next);
	}
}

/**
 * Méthodes de template pour la page des statistiques des objets de la galerie.
 */
class tplGalleryStats extends tplAdmin
{
	/**
	 * Retourne l'information $item correspondant au statut "activé".
	 *
	 * @param string $item
	 * @return string
	 */
	public function getActivate($item)
	{
		return utils::tplProtect($this->_getActivate($item, 'activate'));
	}

	/**
	 * Retourne l'information $item correspondant au statut "désactivé".
	 *
	 * @param string $item
	 * @return string
	 */
	public function getDeactivate($item)
	{
		return utils::tplProtect($this->_getActivate($item, 'deactivate'));
	}

	/**
	 * Retourne l'information $item correspondant au statut "en attente".
	 *
	 * @param string $item
	 * @return string
	 */
	public function getPending($item)
	{
		return utils::tplProtect($this->_getActivate($item, 'pending'));
	}

	/**
	 * Retourne l'information $item correspondant au statut "protégé".
	 *
	 * @param string $item
	 * @return string
	 */
	public function getProtected($item)
	{
		return utils::tplProtect($this->_getActivate($item, 'protected'));
	}

	/**
	 * Retourne l'information $item correspondant au total.
	 *
	 * @param string $item
	 * @return string
	 */
	public function getTotal($item)
	{
		switch ($item)
		{
			// Note moyenne.
			case 'average_rate' :
				return utils::tplProtect(
					number_format(round(admin::$infos['total'][$item], 1), 1, __(','), '')
				);

			// Poids moyen des images.
			case 'filesize_average' :
				$nb_images = admin::$infos['activate']['nb_images']
					+ admin::$infos['deactivate']['nb_images'];
				return utils::filesize(
					($nb_images > 0)
						? (admin::$infos['activate']['filesize_total'] +
						   admin::$infos['deactivate']['filesize_total']) / $nb_images
						: 0
				);

			// Poids total.
			case 'filesize_total' :
				return utils::filesize(
					admin::$infos['activate'][$item] +
					admin::$infos['deactivate'][$item]
				);

			// Nombre de favoris par utilisateur.
			case 'nb_favorites_per_user' :
				$nb_users = admin::$infos['activate']['nb_admins']
					+ admin::$infos['activate']['nb_members']
					+ admin::$infos['deactivate']['nb_admins']
					+ admin::$infos['deactivate']['nb_members'];
				return number_format(round(
					($nb_users > 0)
						? (admin::$infos['activate']['nb_favorites'] +
						   admin::$infos['deactivate']['nb_favorites']) / $nb_users
						: 0,
					3
				), 3, __(','), '');

			// Nombre de commentaires, de visites, de tags et de votes par image.
			case 'nb_comments_per_image' :
			case 'nb_hits_per_image' :
			case 'nb_votes_per_image' :
				$item = explode('_', $item);
				$nb_images = admin::$infos['activate']['nb_images']
					+ admin::$infos['deactivate']['nb_images'];
				return number_format(round(
					($nb_images > 0)
						? (admin::$infos['activate']['nb_' . $item[1]] +
						   admin::$infos['deactivate']['nb_' . $item[1]] ) / $nb_images
						: 0,
					3
				), 3, __(','), '');

			case 'nb_tags_per_image' :
				$nb_images = admin::$infos['activate']['nb_images']
					+ admin::$infos['deactivate']['nb_images'];
				return number_format(round(
					($nb_images > 0)
						? (admin::$infos['activate']['nb_tags_images'] +
						   admin::$infos['deactivate']['nb_tags_images']) / $nb_images
						: 0,
					3
				), 3, __(','), '');

			// Autres informations.
			case 'nb_admins' :
			case 'nb_albums' :
			case 'nb_categories' :
			case 'nb_comments' :
			case 'nb_favorites' :
			case 'nb_hits' :
			case 'nb_images' :
			case 'nb_images_comments' :
			case 'nb_images_favorites' :
			case 'nb_images_hits' :
			case 'nb_images_tags' :
			case 'nb_images_votes' :
			case 'nb_members' :
			case 'nb_tags' :
			case 'nb_votes' :
				return utils::tplProtect(
					$this->_getActivate($item, 'activate') +
					$this->_getActivate($item, 'deactivate')
				);
		}
	}



	/**
	 * Retourne l'information $item correspondant au statut $status.
	 *
	 * @param string $item
	 * @return string
	 */
	private function _getActivate($item, $status)
	{
		switch ($item)
		{
			// Note moyenne.
			case 'average_rate' :
				return utils::tplProtect(
					number_format(round(admin::$infos[$status][$item], 1), 1, __(','), '')
				);

			// Poids moyen des images.
			case 'filesize_average' :
				$nb_images = admin::$infos[$status]['nb_images'];
				return utils::filesize(
					($nb_images > 0)
						? admin::$infos[$status]['filesize_total'] / $nb_images
						: 0
				);

			// Poids total des images.
			case 'filesize_total' :
				return utils::filesize(
					admin::$infos[$status][$item]
				);

			// Nombre de favoris par utilisateur.
			case 'nb_favorites_per_user' :
				$nb_users = admin::$infos[$status]['nb_admins']
					+ admin::$infos[$status]['nb_members'];
				return number_format(round(
					($nb_users > 0)
						? admin::$infos[$status]['nb_favorites'] / $nb_users
						: 0,
					3
				), 3, __(','), '');

			// Nombre de commentaires, de visites, de tags et de votes par image.
			case 'nb_comments_per_image' :
			case 'nb_hits_per_image' :
			case 'nb_votes_per_image' :
				$item = explode('_', $item);
				$nb_images = admin::$infos[$status]['nb_images'];
				return number_format(round(
					($nb_images > 0)
						? admin::$infos[$status]['nb_' . $item[1]] / $nb_images
						: 0,
					3
				), 3, __(','), '');

			case 'nb_tags_per_image' :
				$nb_images = admin::$infos[$status]['nb_images'];
				return number_format(round(
					($nb_images > 0)
						? admin::$infos[$status]['nb_tags_images'] / $nb_images
						: 0,
					3
				), 3, __(','), '');

			// Autres informations.
			case 'nb_admins' :
			case 'nb_albums' :
			case 'nb_categories' :
			case 'nb_comments' :
			case 'nb_favorites' :
			case 'nb_groups' :
			case 'nb_hits' :
			case 'nb_images' :
			case 'nb_images_comments' :
			case 'nb_images_favorites' :
			case 'nb_images_hits' :
			case 'nb_images_tags' :
			case 'nb_images_votes' :
			case 'nb_members' :
			case 'nb_tags' :
			case 'nb_votes' :
				return isset(admin::$infos[$status][$item])
					? (int) admin::$infos[$status][$item]
					: 0;
		}
	}
}

/**
 * Méthodes de template pour les pages d'édition et de création d'un groupe.
 */
class tplGroup extends tplAdmin
{
	/**
	 * L'élément de groupe $item doit-il être affiché ?
	 *
	 * @param string $item
	 * @return boolean
	 */
	public function disGroup($item)
	{
		switch ($item)
		{
			// Permissions admin.
			case 'admin_perms' :
				return users::$infos['group_id'] != 1
					&& users::$infos['group_id'] != 2
					&& auth::$infos['user_id'] == 1;

			// Affichage des permissions admin.
			case 'admin_perms_show' :
				return users::isAdminPerms(users::$infos['group_perms']['admin']['perms']);

			// Permissions d'accès aux albums protégés.
			case 'admin_albums_allowed' :
				return !empty(users::$catAccess['admin']['allowed']);

			case 'admin_albums_forbidden' :
				return !empty(users::$catAccess['admin']['forbidden']);

			case 'gallery_albums_allowed' :
				return !empty(users::$catAccess['gallery']['allowed']);

			case 'gallery_albums_forbidden' :
				return !empty(users::$catAccess['gallery']['forbidden']);

			// Permission galerie.
			case 'gallery_perms' :
				return users::$infos['group_id'] != 1;
		}
	}

	/**
	 * Retourne l'élément $item du groupe.
	 *
	 * @param string $item
	 * @return string
	 */
	public function getGroup($item)
	{
		switch ($item)
		{
			// Nom de groupe.
			case 'name' :
				return (utils::isEmpty(users::$infos['group_name']))
					? admin::getL10nGroupName(users::$infos['group_id'])
					: utils::tplProtect(utils::getLocale(
						users::$infos['group_' . $item], utils::$userLang
					));

			// Informations pour édition.
			case 'desc_edit' :
			case 'name_edit' :
			case 'title_edit' :
				$item = str_replace('_edit', '', $item);
				$info = (isset($_POST[$item])
					&& ($_GET['section'] == 'group' && $item == 'name') === FALSE)
					? $_POST[$item][$this->getLang('code')]
					: utils::getLocale(
						users::$infos['group_' . $item], $this->getLang('code')
					);
				if (users::$infos['group_id'] == 0
				 || users::$infos['group_id'] > 3 || !empty($info))
				{
					return utils::tplProtect($info);
				}
				break;

			// Permissions admin.
			case 'all' :
			case 'ftp' :
			case 'albums_modif' :
			case 'albums_edit' :
			case 'albums_pending' :
			case 'albums_add' :
			case 'comments_edit' :
			case 'comments_options' :
			case 'admin_votes' :
			case 'tags' :
			case 'users_members' :
			case 'users_options' :
			case 'settings_pages' :
			case 'settings_widgets' :
			case 'settings_functions' :
			case 'settings_options' :
			case 'settings_themes' :
			case 'settings_maintenance' :
			case 'infos_incidents' :
				return (isset($_POST['admin']['perms'][$item]) || 
					users::$infos['group_perms']['admin']['perms'][$item])
					? ' checked="checked"'
					: '';

			// Permissions galerie.
			case 'adv_search' :
			case 'alert_email' :
			case 'add_comments' :
			case 'read_comments' :
			case 'create_albums' :
			case 'edit' :
			case 'edit_owner' :
			case 'image_original' :
			case 'members_list' :
			case 'options' :
			case 'upload' :
			case 'upload_create_owner' :
			case 'votes' :
				return (isset($_POST['gallery']['perms'][$item]) || 
					users::$infos['group_perms']['gallery']['perms'][$item])
					? ' checked="checked"'
					: '';

			// Mode d'envoi des commentaires.
			case 'add_comments_mode_0' :
			case 'add_comments_mode_1' :
				$post_add_comments_mode = isset($_POST['gallery']['perms']['add_comments_mode'])
					? $_POST['gallery']['perms']['add_comments_mode']
					: '';
				$add_comments_mode
					= users::$infos['group_perms']['gallery']['perms']['add_comments_mode'];
				return ('add_comments_mode_' . $post_add_comments_mode == $item
					|| 'add_comments_mode_' . $add_comments_mode == $item)
					? ' checked="checked"'
					: '';

			// Mode d'envoi des images.
			case 'upload_mode_0' :
			case 'upload_mode_1' :
				$post_upload_mode = isset($_POST['gallery']['perms']['upload_mode'])
					? $_POST['gallery']['perms']['upload_mode']
					: '';
				$upload_mode = users::$infos['group_perms']['gallery']['perms']['upload_mode'];
				return ('upload_mode_' . $post_upload_mode == $item
					|| 'upload_mode_' . $upload_mode == $item)
					? ' checked="checked"'
					: '';

			// Mode d'accès aux albums protégés en admin.
			case 'admin_access_mode_0' :
			case 'admin_access_mode_1' :
			case 'admin_access_mode_2' :
				$post_access_mode = isset($_POST['admin']['access_mode'])
					? $_POST['admin']['access_mode']
					: '';
				$access_mode = users::$infos['group_perms']['admin']['perms']['access_mode'];
				return ('admin_access_mode_' . $post_access_mode == $item
					|| 'admin_access_mode_' . $access_mode == $item)
					? ' checked="checked"'
					: '';

			// Mode d'accès aux albums protégés de la galerie.
			case 'gallery_access_mode_0' :
			case 'gallery_access_mode_1' :
			case 'gallery_access_mode_2' :
				$post_access_mode = isset($_POST['gallery']['access_mode'])
					? $_POST['gallery']['access_mode']
					: '';
				$access_mode = users::$infos['group_perms']['gallery']['perms']['access_mode'];
				return ('gallery_access_mode_' . $post_access_mode == $item
					|| 'gallery_access_mode_' . $access_mode == $item)
					? ' checked="checked"'
					: '';

			// Permissions d'accès autorisées aux albums protégés en admin.
			case 'admin_albums_allowed' :
				$options = '';
				foreach (users::$catAccess['admin']['allowed'] as $id => &$path)
				{
					$options .= '<option value="' . (int) $id . '">'
						. utils::tplProtect(utils::getLocale($path)) . '</option>';
				}
				return $options;

			// Permissions d'accès non autorisées aux albums protégés en admin.
			case 'admin_albums_forbidden' :
				$options = '';
				foreach (users::$catAccess['admin']['forbidden'] as $id => &$path)
				{
					$options .= '<option value="' . (int) $id . '">'
						. utils::tplProtect(utils::getLocale($path)) . '</option>';
				}
				return $options;

			// Permissions d'accès autorisées aux albums protégés dans la galerie.
			case 'gallery_albums_allowed' :
				$options = '';
				foreach (users::$catAccess['gallery']['allowed'] as $id => &$path)
				{
					$options .= '<option value="' . (int) $id . '">'
						. utils::tplProtect(utils::getLocale($path)) . '</option>';
				}
				return $options;

			// Permissions d'accès non autorisées aux albums protégés dans la galerie.
			case 'gallery_albums_forbidden' :
				$options = '';
				foreach (users::$catAccess['gallery']['forbidden'] as $id => &$path)
				{
					$options .= '<option value="' . (int) $id . '">'
						. utils::tplProtect(utils::getLocale($path)) . '</option>';
				}
				return $options;

			// Cases à cocher de vérouillage / déverrouillage.
			case 'admin_allow' :
			case 'admin_forbid' :
			case 'gallery_allow' :
			case 'gallery_forbid' :
				$item = explode('_', $item);
				return (isset($_POST[$item[0]][$item[1]])
				&& !self::disReport('success'))
					? ' checked="checked"'
					: '';
		}
	}
}

/**
 * Méthodes de template pour la page de gestion des groupes.
 */
class tplGroups extends tplAdmin
{
	/**
	 * L'élément de groupe $item doit-il être affiché ?
	 *
	 * @param string $item
	 * @return boolean
	 */
	public function disGroup($item)
	{
		$i = current(users::$items);

		switch ($item)
		{
			case 'special' :
				return $i['group_id'] == 1 || $i['group_id'] == 2 || $i['group_id'] == 3;
		}
	}

	/**
	 * Retourne l'élément $item du groupe.
	 *
	 * @param string $item
	 * @return string
	 */
	public function getGroup($item)
	{
		$i = current(users::$items);

		switch ($item)
		{
			// Informations.
			case 'id' :
				return utils::tplProtect($i['group_id']);

			// Description.
			case 'desc' :
				return ($i['group_id'] > 3 || $i['group_desc'] != '')
					? nl2br(utils::tplProtect(
						utils::getLocale($i['group_desc'])
					))
					: admin::getL10nGroupDesc($i['group_id']);

			// Date de création.
			case 'crtdt' :
				return utils::tplProtect(
					utils::localeTime(__('%A %d %B %Y à %H:%M:%S'), $i['group_crtdt'])
				);

			// Nom.
			case 'name' :
				return ($i['group_id'] > 3 || $i['group_name'] != '')
					? utils::tplProtect(utils::getLocale($i['group_name']))
					: admin::getL10nGroupName($i['group_id']);

			// Nombre de membres faisant partie du groupe.
			case 'nb_members' :
				if ($i['group_id'] == 2)
				{
					return '/';
				}
				if ($i['nb_users'] == 0)
				{
					return sprintf(__('%s membre'), 0);
				}
				$link = utils::genURL('users/group/' . (int) $i['group_id']);
				$users = ($i['nb_users'] > 1)
					? __('%s membres')
					: __('%s membre');
				$users = sprintf($users, (int) $i['nb_users']);
				return '<a href="' . $link . '">' . $users . '</a>';

			// Titre.
			case 'title' :
				return ($i['group_id'] > 3 || $i['group_title'] != '')
					? utils::tplProtect(utils::getLocale($i['group_title']))
					: admin::getL10nGroupTitle($i['group_id']);
		}
	}

	/**
	 * Y a-t-il un prochain groupe ?
	 * 
	 * @return boolean
	 */
	public function nextGroup()
	{
		static $next = -1;

		return template::nextObject(users::$items, $next);
	}
}

/**
 * Méthodes de template pour la page d'édition des images.
 */
class tplImage extends tplAlbums
{
	/**
	 * L'élément de l'image courante $item doit-il être affiché ?
	 *
	 * @param string $item
	 * @return boolean
	 */
	public function disImageInfo($item)
	{
		$i =& albums::$infos;

		switch ($item)
		{
			case 'password' :
				return $i['cat_password'] !== NULL;

			case 'password_groups' :
				return utils::$config['users'] == 1
					&& utils::$config['users_locked_albums_access_mode'] == 'groups';

			case 'publish' :
				return (bool) $i['image_status'];
		}
	}

	/**
	 * Retourne l'information $item de l'image courante.
	 *
	 * @return string
	 */
	public function getImageInfo($item)
	{
		$i =& albums::$infos;
		$thumb_forced = 60;

		return $this->_getImageInfo($item, $i, $thumb_forced);
	}

	/**
	 * L'élément d'édition $item doit-il être affiché ?
	 *
	 * @param string $item
	 * @return boolean
	 */
	public function disEdit($item)
	{
		switch ($item)
		{
			case 'restore' :
				return file_exists(GALLERY_ROOT . '/' . img::filepath('im_backup',
					albums::$infos['image_path'], albums::$infos['image_id'],
					albums::$infos['image_adddt']));
		}
	}

	/**
	 * L'élément d'image $item doit-il être affiché ?
	 *
	 * @param string $item
	 * @return boolean
	 */
	public function disImage($item)
	{
		switch ($item)
		{
			// Image activée ?
			case 'publish' :
				return (bool) albums::$infos['image_status'];
		}
	}

	/**
	 * Retourne l'élément $item de l'image.
	 *
	 * @param string $item
	 * @return string
	 */
	public function getImagePreview($item)
	{
		return $this->_getImagePreview($item, 'img', CONF_THUMBS_IMG_METHOD);
	}

	/**
	 * Retourne la liste des images de l'album sous forme de balises <option>.
	 *
	 * @return string
	 */
	public function getImagesList()
	{
		return $this->_getImagesList($_GET['section']);
	}

	/**
	 * Retourne les liens de la barre de position (fil d'ariane).
	 *
	 * @return string
	 */
	public function getPosition()
	{
		return template::getPosition(
			'',
			'category',
			'image',
			'album',
			TRUE,
			FALSE,
			__('galerie'),
			albums::$parents,
			albums::$infos,
			albums::$parentPage,
			' / ',
			TRUE
		);
	}
}

/**
 * Méthodes de template pour la page de gestion des images en attente.
 */
class tplImagesPending extends tplAlbums
{
	/**
	 * L'élément de navigation entre les pages $item doit-il être affiché ?
	 *
	 * @param string $item
	 * @return boolean
	 */
	public function disNavigation($item = '')
	{
		return template::disNavigation($item, albums::$nbPages);
	}

	/**
	 * Retourne la liste des catégories.
	 *
	 * @return string
	 */
	public function getCategoriesList()
	{
		return template::mapSelect(albums::$mapCategories, array(
			'class_type' => TRUE,
			'nolines_category' => TRUE
		));
	}

	/**
	 * Retourne l'élément de navigation entre les pages $item.
	 *
	 * @param string $item
	 * @return string
	 */
	public function getNavigation($item)
	{
		return template::getNavigation($item, albums::$nbPages, admin::$sectionRequest);
	}

	/**
	 * Retourne l'élément des options d'affichage $item.
	 *
	 * @param string $item
	 * @return string
	 */
	public function getOptions($item)
	{
		return $this->_getDisplayOptions('pending', $item, array(
			'name' => __('titre'),
			'adddt' => __('date d\'ajout'),
			'path' => __('nom de fichier')
		));
	}

	/**
	 * Retourne le nombre d'images en attente.
	 *
	 * @return string
	 */
	public function getPosition()
	{
		$message = (albums::$nbItems > 1)
			? __('%s images en attente')
			: __('%s image en attente');
		return sprintf($message, albums::$nbItems);
	}

	/**
	 * Retourne l'élément $item de l'image.
	 *
	 * @param string $item
	 * @return integer|string
	 */
	public function getImage($item)
	{
		$i = current(albums::$items);

		switch ($item)
		{
			// Identifiant de l'album destination.
			case 'album_id' :
				return (int) $i['cat_id'];

			// Lien vers la page de gestion de l'album.
			case 'album_link' :
				$name = utils::strLimit($this->getImage('album_name'), 50);
				if (!$this->disPerm('albums_edit') && !$this->disPerm('albums_modif'))
				{
					return $name;
				}

				$link = $this->getLink('album/' . $this->getImage('album_id'));
				return '<a href="' . $link . '">' . $name . '</a>';

			// Nom de l'album destination.
			case 'album_name' :
				return utils::tplProtect($i['cat_name']);

			// Date d'ajout.
			case 'date' :
				return utils::tplProtect(
					utils::localeTime(__('%A %d %B %Y à %H:%M:%S'), $i['up_adddt'])
				);

			// Description.
			case 'description' :
				return utils::tplProtect(utils::getLocale($i['up_desc']));

			// Description dans la langue courante.
			case 'description_lang' :
				return utils::tplProtect(
					utils::getLocale($i['up_desc'], $this->getLang('code'))
				);

			// Poids de l'image.
			case 'filesize' :
				return utils::filesize($i['up_filesize']);

			// Identifiant.
			case 'id' :
				return (int) $i['up_id'];

			// Adresse IP de l'utilisateur.
			case 'ip' :
				return utils::tplProtect($i['up_ip']);

			// Type d'objet + identifiant.
			case 'object_type' :
				return sprintf(__('image %s'), $this->getImage('id'));

			// Dimensions de l'image.
			case 'size' :
				return sprintf('%s x %s', (int) $i['up_width'], (int) $i['up_height']);

			// Emplacement de l'image.
			case 'src_image' :
				return utils::tplProtect(
					CONF_GALLERY_PATH . utils::$purlDir
						. '/image.php?id=' . $i['up_id'] . '&type=pending'
				);

			// Message de statut.
			case 'status_msg' :
				return __('en attente');

			// Centrage de la vignette par CSS.
			case 'thumb_center' :
				$tb = img::getThumbSize($i, 'img', $this->_thumbForced);
				return img::thumbCenter('img', $tb['width'],
					$tb['height'], $this->_thumbForced);

			// Dimensions de la vignette.
			case 'thumb_size' :
				$tb = img::getThumbSize($i, 'img', $this->_thumbForced);
				return 'width="' . $tb['width'] . '" height="' . $tb['height'] . '"';

			// Emplacement de la vignette.
			case 'thumb_src' :
				return utils::tplProtect(template::getThumbSrc('pen', $i));

			// Titre.
			case 'title' :
				return utils::tplProtect(utils::getLocale($i['up_name']));

			// Titre dans la langue courante.
			case 'title_lang' :
				return utils::tplProtect(
					utils::getLocale($i['up_name'], $this->getLang('code'))
				);

			// Type.
			case 'type' :
				switch ($i['up_type'])
				{
					case 1 :
						return 'gif';

					case 2 :
						return 'jpeg';

					case 3 :
						return 'png';
				}
				return __('inconnu');

			// Identifiant d'utilisateur.
			case 'user_id' :
				return (int) $i['user_id'];

			// Lien vers la page de profil dde l'utilisateur.
			case 'user_link' :
				$user = '/';
				if ($i['user_id'] != 2)
				{
					$login = utils::strLimit($this->getImage('user_login'), 30);

					// On ne met pas de lien pour les utilisateurs
					// qui n'ont pas la permission d'accès à la page
					// de gestion des utilisateurs.
					if (!$this->disPerm('users_members'))
					{
						return $login;
					}

					$user = utils::genURL('user/' . (int) $i['user_id']);
					$user = '<a href="' . $user . '">' . $login . '</a>';
				}
				return $user;

			// Nom d'utilisateur.
			case 'user_login' :
				return utils::tplProtect($i['user_login']);
		}
	}

	/**
	 * Y a-t-il une prochaine image ?
	 * 
	 * @param integer $thumb_size
	 *	Taille des vignettes.
	 * @return boolean
	 */
	public function nextImage($thumb_size)
	{
		static $next = -1;

		$this->_thumbForced =& $thumb_size;

		return template::nextObject(albums::$items, $next);
	}
}

/**
 * Méthodes de template pour la page des logs d'activité des utilisateurs.
 */
class tplLogs extends tplAdmin
{
	/**
	 * Tableau POST de l'netrée courante.
	 *
	 * @var array
	 */
	public $post = array();



	/**
	 * Doit-on afficher l'élément $item de l'entrée courante ?
	 *
	 * @param string $item
	 * @return string
	 */
	public function disEntry($item)
	{
		$i = current(logs::$logs);

		switch ($item)
		{
			// Tableau POST.
			case 'post' :
				if ($i['log_post'] === NULL)
				{
					return FALSE;
				}
				$this->post = unserialize($i['log_post']);
				return is_array($this->post);

			// Action rejetée.
			case 'reject' :
				return strstr($i['log_action'], 'reject');

			// Nom d'utilisateur.
			case 'user_member' :
				return $i['user_id'] != 0 && $i['user_id'] != 2;
		}
	}

	/**
	 * Retourne l'élément $item de l'entrée courante.
	 *
	 * @param string $item
	 * @return string
	 */
	public function getEntry($item)
	{
		$i = current(logs::$logs);

		switch ($item)
		{
			// Action.
			case 'action' :
				$action = preg_replace('`_(accept|failure|reject|success).*$`',
					'', $i['log_action']);
				$text = (isset(logs::$actions[$action]))
					? logs::$actions[$action]
					: '?';
				switch ($action)
				{
					case 'avatar_change' :
					case 'avatar_delete' :
						$icon = 'avatar';
						break;

					case 'basket_add' :
					case 'basket_empty' :
					case 'basket_remove' :
						$icon = 'basket';
						break;

					case 'comment' :
						$icon = 'comment';
						$text .= (preg_match('`guestbook$`', $i['log_page']))
							? ' (' . __('livre d\'or') . ')'
							: ' (' . __('image') . ')';
						break;

					case 'contact' :
						$icon = 'contact';
						break;

					case 'category_create' :
						$icon = 'add_category';
						break;

					case 'category_edit' :
					case 'image_edit' :
						$icon = 'edit';
						break;

					case 'favorites_add' :
					case 'favorites_remove' :
						$icon = 'favorites';
						break;

					case 'image_delete' :
						$icon = 'delete';
						break;

					case 'forgot' :
					case 'login_admin' :
					case 'login_gallery' :
					case 'logout_admin' :
					case 'logout_gallery' :
						$icon = 'login';
						break;

					case 'profile_change' :
						$icon = 'profile';
						break;

					case 'register' :
						$icon = 'user';
						break;

					case 'tags_add' :
					case 'tags_remove' :
						$icon = 'tags';
						break;

					case 'upload_images_direct' :
						$icon = 'add_images';
						break;

					case 'upload_images_pending' :
						$icon = 'add_images';
						break;

					case 'vote_add' :
						$icon = 'vote';
						break;

					case 'vote_change' :
						$icon = 'vote';
						break;

					case 'watermark_change' :
						$icon = 'watermark';
						break;

					default :
						$icon = '';
						break;
				}
				return '<span class="icon icon_' . $icon . '">' . $text . '</span>';

			// Date de l'entrée.
			case 'date' :
				return '<span title="'
					. utils::tplProtect(utils::localeTime(__('%A %d %B %Y'), $i['log_date']))
					. '">'
					. utils::tplProtect(utils::localeTime(__('%d/%m/%Y'), $i['log_date']))
					. '</span>';

			// Identifiant de l'entrée.
			case 'id' :
				return (int) $i['log_id'];

			// Lien pour afficher toutes les entrées de la date.
			case 'logs_date_link' :
				return utils::genURL('logs/date/' . date('Y-m-d', strtotime($i['log_date'])));

			// Lien pour afficher toutes les entrées de l'IP.
			case 'logs_ip_link' :
				return utils::genURL('logs/ip/' . $i['log_ip']);

			// Lien pour afficher toutes les entrées de l'utilisateur.
			case 'logs_user_link' :
				return utils::genURL('logs/user/' . $i['user_id']);

			// Page.
			case 'page' :
				$page = GALLERY_HOST;

				if ($i['log_page'] === NULL
				 || $i['log_page'] === '')
				{
					if (strstr($i['log_action'], '_admin'))
					{
						$page .= utils::genURL();
					}
					else
					{
						$page .= $this->getGalleryLink();
					}
				}
				else
				{
					$page .= (strstr($i['log_action'], '_admin'))
						? str_replace('?q=', 'connexion.php?q=', utils::genURL($i['log_page']))
						: $this->getGalleryLink($i['log_page']);
				}

				return '<a title="' . $page . '" href="' . $page . '">'
					. utils::strLimit($page, 75) . '</a>';

			// Cause du rejet.
			case 'reject_cause' :
				switch (preg_replace('`^.+_reject_(.*)$`', '$1', $i['log_action']))
				{
					case 'antiflood' :
						$txt = 'antiflood';
						break;

					case 'blacklist_emails' :
						$txt = sprintf(__('liste noire "%s"'), __('adresses de courriel'));
						break;

					case 'blacklist_names' :
						$txt = sprintf(__('liste noire "%s"'), __('noms d\'utilisateur'));
						break;

					case 'blacklist_ips' :
						$txt = sprintf(__('liste noire "%s"'), __('adresses IP'));
						break;

					case 'blacklist_words' :
						$txt = sprintf(__('liste noire "%s"'), __('mots'));
						break;

					case 'captcha' :
						$txt = 'captcha';
						break;

					case 'maxchars' :
						$txt = __('nombre de caractères');
						break;

					case 'maxlines' :
						$txt = __('nombre de lignes');
						break;

					case 'maxurls' :
						$txt = __('nombre d\'URLs');
						break;

					default :
						$txt = '?';
						break;
				}
				return $txt;

			// Element trouvé pour rejeter l'action.
			case 'reject_match' :
				return utils::tplProtect($i['log_match']);

			// Résultat.
			case 'result' :
				return strstr($i['log_action'], 'reject')
					|| strstr($i['log_action'], 'failure')
					? '<span class="icon icon_reject">' . __('rejeté') . '</span>'
					: '<span class="icon icon_accept">' . __('accepté') . '</span>';

			// Heure de l'entrée.
			case 'time' :
				return utils::tplProtect(
					utils::localeTime(__('%H:%M:%S'), $i['log_date'])
				);

			// Emplacement de l'avatar de l'utilisateur.
			case 'user_avatar_src' :
				return ($i['user_avatar'])
					? CONF_GALLERY_PATH . '/users/avatars/user'
						. (int) $i['user_id'] . '_thumb.jpg'
					: $this->getAdmin('style_path') . '/avatar-default.png';

			// Lien vers le profil utilisateur.
			case 'user_link' :
				return ($i['user_id'] == 0 || $i['user_id'] == 2)
					? ''
					: utils::genURL('user/' . (int) $i['user_id']);

			// Nom d'utilisateur.
			case 'user_login' :
				if ($i['user_id'] == 2)
				{
					return __('invité');
				}
				return utils::tplProtect($i['user_login']);

			// Adresse IP de l'utilisateur.
			case 'ip' :
				return utils::tplProtect($i['log_ip']);
		}
	}

	/**
	 * Y a-t-il une prochaine entrée ?
	 * 
	 * @return boolean
	 */
	public function nextEntry()
	{
		static $next = -1;

		return template::nextObject(logs::$logs, $next);
	}

	/**
	 * Retourne une valeur d'une propriété de la classe logs.
	 *
	 * @param array|string $property
	 * @return mixed
	 */
	public function getInfo($property)
	{
		return (is_array($property))
			? utils::tplProtect(logs::${$property[0]}[$property[1]])
			: utils::tplProtect(logs::${$property});
	}

	/**
	 * Y a-t-il des votes ?
	 *
	 * @return boolean
	 */
	public function disLogs()
	{
		return !empty(logs::$logs);
	}

	/**
	 * L'élément de navigation entre les pages $item doit-il être affiché ?
	 *
	 * @param string $item
	 * @return boolean
	 */
	public function disNavigation($item = '')
	{
		return template::disNavigation($item, logs::$nbPages);
	}

	/**
	 * Retourne l'élément de navigation entre les pages $item.
	 *
	 * @param string $item
	 * @return string
	 */
	public function getNavigation($item)
	{
		return template::getNavigation($item, logs::$nbPages, admin::$sectionRequest);
	}

	/**
	 * Retourne l'élément des options d'affichage $item.
	 *
	 * @param string $item
	 * @return string
	 */
	public function getOptions($item)
	{
		switch ($item)
		{
			// Action.
			case 'action' :
				return $this->_displayOptionsList('logs', $item, 'all', logs::$actions);

			// Nombre d'objets par page.
			case 'nb_per_page':
				return (int) auth::$infos['user_prefs']['logs'][$item];

			// Ordre de tri.
			case 'orderby' :
				$p = array(
					'ASC' => __('croissant'),
					'DESC' => __('décroissant')
				);
				return $this->_displayOptionsList('logs', $item, 'DESC', $p);

			// Résultat.
			case 'result' :
				$result = array(
					'all' => '*' . __('tous'),
					'accept' => __('accepté'),
					'reject' => __('rejeté')
				);
				return $this->_displayOptionsList('logs', $item, 'all', $result);

			// Critère de tri.
			case 'sortby' :
				$p = array(
					'date' => __('Date')
				);
				return $this->_displayOptionsList('logs', $item, 'date', $p);
		}
	}

	/**
	 * Retourne l'élément $item de l'entrée courante.
	 *
	 * @param string $item
	 * @return string
	 */
	public function getPost($item)
	{
		switch ($item)
		{
			// Paramètres.
			case 'param' :
				return utils::tplProtect(key($this->post));

			// Valeur.
			case 'value' :
				return nl2br(utils::tplProtect(current($this->post)));
		}
	}

	/**
	 * Y a-t-il un prochain paramètre POST ?
	 * 
	 * @return boolean
	 */
	public function nextPost()
	{
		static $next = -1;

		return template::nextObject($this->post, $next);
	}

	/**
	 * Retourne le nom de l'utilisateur courant.
	 *
	 * @return string
	 */
	public function getPosition()
	{
		if (isset($_GET['user_id']))
		{
			switch ($_GET['user_id'])
			{
				case 2 :
					return __('invités');

				default :
					return utils::tplProtect(users::$usersList[$_GET['user_id']]);
			}
		}

		return __('tous');
	}

	/**
	 * Doit-on afficher l'élément de barre de position $item ?
	 *
	 * @return boolean
	 */
	public function disPosition($item)
	{
		switch ($item)
		{
			// Barre de position pour les filtres ?
			case 'filter' :
				return isset($_GET['date'])
					|| isset($_GET['ip']);

			// Barre de position normale ?
			case 'normal' :
				return !$this->disPosition('filter')
					&& !$this->disPosition('search');

			// Barre de position pour la recherche ?
			case 'search' :
				return isset($_GET['search']);
		}
	}

	/**
	 * Retourne l'élément de filtre $item.
	 *
	 * @param string $item
	 * @return string
	 */
	public function getFilter($item)
	{
		switch ($item)
		{
			case 'section_link' :
				return utils::genURL(
					preg_replace('`/(?:(?:date|ip)/.+$)`', '', admin::$sectionRequest)
				);

			case 'text' :
				if (isset($_GET['date']))
				{
					return __('Activité à la date du %s');
				}
				if (isset($_GET['ip']))
				{
					return __('Activité en provenance de l\'IP %s');
				}
				break;

			case 'value' :
				if (isset($_GET['date']))
				{
					return utils::localeTime(__('%A %d %B %Y'), $_GET['date']);
				}
				if (isset($_GET['ip']))
				{
					return utils::tplProtect($_GET['ip']);
				}
				break;
		}
	}

	/**
	 * Retourne l'élément de recherche $item.
	 *
	 * @param string $item
	 * @return string
	 */
	public function getSearch($item)
	{
		switch ($item)
		{
			// Options : action.
			case 'action' :
				$list = '';
				foreach (logs::$actions as $action => &$text)
				{
					$selected = ((!isset($_GET['search_action']) && $action == 'all')
					|| (isset($_GET['search_action']) && $_GET['search_action'] == $action))
						? ' selected="selected"'
						: '';
					$list .= '<option' . $selected . ' value="'
						. utils::tplProtect($action) . '">'
						. utils::tplProtect($text) . '</option>';
				}
				return $list;

			// Options : cases à cocher.
			case 'date' :
				return (isset($_GET['search_' . $item]))
					? ' checked="checked"'
					: '';

			// Options : cases à cocher avec option cochée par défaut.
			case 'log_ip' :
				return (!isset($_GET['search_query']) || isset($_GET['search_' . $item]))
					? ' checked="checked"'
					: '';

			// Résultat.
			case 'result' :
				$result = array('accept' => __('accepté'), 'reject' => __('rejeté'));
				$selected = (!isset($_GET['search_result']) || $_GET['search_result'] == 'all')
					? ' selected="selected"'
					: '';
				$list = '<option' . $selected . ' value="all">*' . __('tous') . '</option>';
				foreach ($result as $r => &$text)
				{
					$selected = (isset($_GET['search_result']) && $_GET['search_result'] == $r)
						? ' selected="selected"'
						: '';
					$list .= '<option' . $selected . ' value="'
						. utils::tplProtect($r) . '">'
						. utils::tplProtect($text) . '</option>';
				}
				return $list;

			default :
				return $this->_getSearch($item);
		}
	}

	/**
	 * Retourne la liste des utilisateurs sous forme d'éléments
	 * <option> pour parcours des entrées selon l'utilisateur choisi.
	 *
	 * @return string
	 */
	public function getUsersBrowse()
	{
		// Filtres.
		$filters = '';
		foreach (array('date', 'ip', 'search') as $c)
		{
			if (isset($_GET[$c]))
			{
				$filters .= '/' . $c . '/' . utils::tplProtect($_GET[$c]);
			}
		}

		$selected = (!isset($_GET['user_id']))
			? ' class="selected" selected="selected"'
			: '';
		$users = '<option' . $selected . ' value="' . $filters . '">*'
			. __('tous') . '</option>';

		if ($this->disLogs())
		{
			foreach (logs::$users as &$i)
			{
				$selected = (isset($_GET['user_id']) && $_GET['user_id'] == $i['user_id'])
					? ' class="selected" selected="selected"'
					: '';
				switch ($i['user_id'])
				{
					case 2 :
						$user_login = '*' . __('invité');
						break;

					default :
						$user_login = utils::tplProtect($i['user_login']);
						break;
				}
				$users .= '<option' . $selected
					. ' value="/user/' . (int) $i['user_id'] . $filters . '">'
					. $user_login . '</option>';
			}
		}

		return $users;
	}
}

/**
 * Méthodes de template pour la page de maintenance.
 */
class tplMaintenance extends tplAdmin
{
	/**
	 * L'élément de rapport $item doit-il être affiché ?
	 *
	 * @return boolean
	 */
	public function disDetailsReport()
	{
		return maintenance::$statsReportCount > 0;
	}

	/**
	 * Retourne l'élément de rapport $item.
	 *
	 * @return string
	 */
	public function getDetailsReport()
	{
		$details = '<div class="report_details">' . "\n"
			. '<table>' . "\n"
			. '<tr><th>' . __('Objet') . '</th>'
			. '<th>' . __('Colonne') . '</th>'
			. '<th>' . __('Valeur erronée') . '</th>'
			. '<th>' . __('Valeur corrigée') . '</th></tr>' . "\n";
		foreach (maintenance::$statsReportDetails as $object => &$infos)
		{
			switch ($infos['type'])
			{
				case 'album' :
					$object = sprintf(__('album %s'), (int) $infos['id']);
					break;

				case 'category' :
					$object = sprintf(__('catégorie %s'), (int) $infos['id']);
					break;

				case 'image' :
					$object = sprintf(__('image %s'), (int) $infos['id']);
					break;
			}
			$object = '<a href="' . utils::genURL($infos['type']
				. '/' . $infos['id']) . '">' . $object . '</a>';
			foreach ($infos['report'] as $col => &$val)
			{
				$details .= '<tr><td>' . $object . '</td>'
					. '<td>' . utils::tplProtect($col) . '</td>'
					. '<td>' . utils::tplProtect($val['before']) . '</td>'
					. '<td>' . utils::tplProtect($val['after']) . '</td></tr>';
			}
		}
		$details .= '</table></div>';

		return $details;
	}
}

/**
 * Méthodes de template pour la page d'édition en masse des images d'un album.
 */
class tplMassEdit extends tplAlbums
{
	/**
	 * Retourne l'élément $item de la page d'édition en masse.
	 *
	 * @param string $item
	 * @return string
	 */
	public function getMassEdit($item)
	{
		switch ($item)
		{
			// Date de création.
			case 'crtdt' :
				$date_select = preg_replace(
					'`(<option class="date_title" )selected="selected" (value="00">00</option>)`',
					'$1$2',
					template::dateSelect(NULL, 1900, 'crtdt_%s', TRUE));
				foreach (array('day', 'month', 'year', 'hour', 'minute', 'second') as $i => $u)
				{
					$r1 = ($i > 2) ? '$1' : '$1$2';
					$r1 = ($i == 3) ? '<br />' . $r1 : $r1;
					$r2 = ($i > 2) ? '$2' : '';
					$date_select = preg_replace(
						'`(<select class="' . $u . '" name="crtdt_' . $u . '">)'
							. '(<option[^>]*class="date_title"[^>]*>[^<]*</option>)`',
						$r1 . '<option selected="selected" value="{' . strtoupper($u)
							. '}">{' . strtoupper($u) . '}</option>' . $r2,
						$date_select);
				}
				return $date_select;
		}
	}

	/**
	 * Retourne l'élément des options d'affichage $item.
	 *
	 * @param string $item
	 * @return string
	 */
	public function getOptions($item)
	{
		return $this->_getDisplayOptions('album', $item, array(
			'name' => __('titre'),
			'adddt' => __('date d\'ajout'),
			'path' => __('nom de fichier'),
			'position' => __('tri manuel')
		));
	}
}

/**
 * Méthodes de template pour les pages de gestion des métadonnées.
 */
class tplMetadata extends tplAdmin
{
	/**
	 * L'élément de l'information Exif $item doit-il être affiché ?
	 *
	 * @param string $item
	 * @return boolean
	 */
	public function disInfo($item = '')
	{
		$i = current(utils::$config[$_GET['section'] . '_order']);

		switch ($item)
		{
			// Statut.
			case 'activate' :
				return (bool) utils::$config[$_GET['section'] . '_params'][$i]['status'];

			// Format.
			case 'format' :
				return isset(utils::$config[$_GET['section'] . '_params'][$i]['format']);
		}
	}

	/**
	 * Retourne l'élement d'information Exif $item.
	 *
	 * @param string $item
	 * @return array|string
	 */
	public function getInfo($item)
	{
		$i = current(utils::$config[$_GET['section'] . '_order']);

		switch ($item)
		{
			// Identifiant (position).
			case 'id' :
				return (int) key(utils::$config[$_GET['section'] . '_order']);

			// Format.
			case 'format' :
				return utils::tplprotect(
					utils::$config[$_GET['section'] . '_params'][$i]['format']
				);

			// Nom de l'information.
			case 'name' :
				switch ($_GET['section'])
				{
					case 'exif' :
						return utils::tplprotect(metadata::getExifLocale($i));

					case 'iptc' :
						return utils::tplprotect(metadata::getIptcLocale($i));

					case 'xmp' :
						return utils::tplprotect(metadata::getXmpLocale($i));
				}

			// Paramètre Exif.
			case 'param' :
				return utils::tplprotect($i);
		}
	}

	/**
	 * Y a-t-il une prochiane information Exif ?
	 * 
	 * @return boolean
	 */
	public function nextInfo()
	{
		static $next = -1;

		return template::nextObject(utils::$config[$_GET['section'] . '_order'], $next);
	}
}

/**
 * Méthodes de template pour la page du choix d'une nouvelle vignette.
 */
class tplNewThumb extends tplAlbums
{
	/**
	 * La liste des catégorie doit-elle être affichée ?
	 *
	 * @return boolean
	 */
	public function disSubMap()
	{
		return is_array(albums::$subMapCategories)
			&& count(albums::$subMapCategories) > 1;
	}

	/**
	 * Retourne la liste des catégories enfants de la catégorie courante.
	 *
	 * @return string
	 */
	public function getSubMap()
	{
		return template::mapSelect(albums::$subMapCategories, array(
			'cat_one' => FALSE,
			'class_selected' => TRUE,
			'selected' => (isset($_GET['cat_id']))
				? $_GET['cat_id']
				: $_GET['object_id'],
			'value_tpl' => 'new-thumb/' . $_GET['object_id'] . '/cat/{ID}'
		), $_GET['object_id']);
	}

	/**
	 * L'élément de navigation entre les pages $item doit-il être affiché ?
	 *
	 * @param string $item
	 * @return boolean
	 */
	public function disNavigation($item = '')
	{
		return template::disNavigation($item, albums::$nbPages);
	}

	/**
	 * Retourne l'élément de navigation entre les pages $item.
	 *
	 * @param string $item
	 * @return string
	 */
	public function getNavigation($item)
	{
		return template::getNavigation($item, albums::$nbPages, admin::$sectionRequest);
	}

	/**
	 * Retourne l'élément de vignette $item.
	 *
	 * @param string $item
	 * @return string
	 */
	public function getThumb($item)
	{
		$i = current(albums::$items);

		switch ($item)
		{
			// Centrage de la vignette par CSS.
			case 'center' :
				$tb = img::getThumbSize($i, 'img', $this->_thumbForced);
				return img::thumbCenter('img', $tb['width'],
					$tb['height'], $this->_thumbForced);

			// Identifiant.
			case 'id' :
				return utils::tplProtect($i['image_id']);

			// Emplacement de la vignette.
			case 'src' :
				return utils::tplProtect(template::getThumbSrc('img', $i));

			// Dimensions de la vignette.
			case 'thumb_size' :
				$tb = img::getThumbSize($i, 'img', $this->_thumbForced);
				return 'width="' . $tb['width'] . '" height="' . $tb['height'] . '"';

			// Titre.
			case 'title' :
				return utils::tplProtect($i['image_name']);
		}
	}

	/**
	 * Y a-t-il une prochaine image ?
	 * 
	 * @param integer $thumb_size
	 *	Taille des vignettes.
	 * @return boolean
	 */
	public function nextThumb($thumb_size)
	{
		static $next = -1;

		$this->_thumbForced =& $thumb_size;

		return template::nextObject(albums::$items, $next);
	}
}

/**
 * Méthodes de template pour la page des options avancées.
 */
class tplOptionsAdvanced extends tplAdmin
{
	/**
	 * L'option $item doit-elle être affichée ?
	 *
	 * @param string $item
	 * @return boolean
	 */
	public function disOption($item)
	{
		switch ($item)
		{
			case 'admin_dashboard_errors' :
			case 'anticsrf_token_unique' :
			case 'db_close_template' :
			case 'exec_time' :
			case 'debug_sql' :
				return (bool) utils::$config[$item];

			case 'debug' :
			case 'errors_display' :
			case 'errors_display_now' :
			case 'errors_display_trace' :
			case 'errors_log' :
			case 'errors_mail' :
			case 'errors_trace_args' :
				return (bool) settings::$configValues['CONF_' . strtoupper($item)];
		}
	}

	/**
	 * Retourne l'option $item.
	 *
	 * @param string $item
	 * @return integer|string
	 */
	public function getOption($item)
	{
		switch ($item)
		{
			case 'anticsrf_token_expire' :
			case 'passwords_usleep' :
			case 'sessions_expire' :
			case 'users_password_minlength' :
				return (int) utils::$config[$item];

			case 'errors_log_max' :
				return (int) settings::$configValues['CONF_' . strtoupper($item)];
		}
	}
}

/**
 * Méthodes de template pour la page des listes noires.
 */
class tplOptionsBlacklists extends tplAdmin
{
	/**
	 * Retourne la liste $item.
	 * 
	 * @param string $item
	 * @return string
	 */
	public function getBlacklist($item)
	{
		switch ($item)
		{
			case 'emails' :
			case 'ips' :
			case 'names' :
			case 'words' :
				return utils::tplProtect(utils::$config['blacklist_' . $item]);
		}
	}
}

/**
 * Méthodes de template pour la page des modèles de descriptions.
 */
class tplOptionsDescriptions extends tplAdmin
{
	/**
	 * Retourne l'option $item.
	 *
	 * @param string $item
	 * @return string
	 */
	public function getOption($item)
	{
		switch ($item)
		{
			// Cases à cocher.
			case 'desc_template_categories_active' :
			case 'desc_template_images_active' :
				return utils::$config[$item]
					? ' checked="checked"'
					: '';

			// Champs textes.
			case 'desc_template_categories_text' :
			case 'desc_template_images_text' :
				return utils::tplProtect(
					utils::getLocale(utils::$config[$item], $this->getLang('code'))
				);
		}
	}
}

/**
 * Méthodes de template pour la page des options de la galerie.
 */
class tplOptionsGallery extends tplAdmin
{
	/**
	 * L'option $item doit-elle être affichée ?
	 *
	 * @param string $item
	 * @return boolean
	 */
	public function disOption($item)
	{
		switch ($item)
		{
			// Bannières.
			case 'gallery_banner_file' :
				$dir = GALLERY_ROOT . '/images/banners/';
				foreach (scandir($dir) as $filename)
				{
					if (!preg_match('`^[-a-z0-9_]{1,64}\.(?:gif|jpg|png)$`i', $filename))
					{
						continue;
					}

					$i = getimagesize($dir . $filename);
					if ($i === FALSE || !in_array($i[2], array(1, 2, 3)))
					{
						continue;
					}

					return TRUE;
				}
				return FALSE;

			// Langues disponibles.
			case 'available_langs' :
				$locale_path = GALLERY_ROOT . '/locale';
				$files = scandir($locale_path);
				foreach ($files as &$f)
				{
					if (!is_dir($locale_path . '/' . $f) || $f[0] == '.'
					|| !preg_match('`[a-z]{2}_[A-Z]{2}`', $f)
					|| isset(utils::$config['locale_langs'][$f]))
					{
						continue;
					}
					return TRUE;
				}
				return FALSE;

			// Langues installées.
			case 'installed_langs' :
				return (bool) count(utils::$config['locale_langs']);
		}
	}

	/**
	 * Retourne l'option $item.
	 *
	 * @param string $item
	 * @return string
	 */
	public function getOption($item)
	{
		switch ($item)
		{
			// Bannière.
			case 'gallery_banner' :
				return utils::$config['gallery_banner']['banner']
					? ' checked="checked"'
					: '';

			case 'gallery_banner_file' :
				$options = '';
				$dir = GALLERY_ROOT . '/images/banners/';
				foreach (scandir($dir) as $filename)
				{
					// Vérification du nom de fichier.
					if (!preg_match('`^[-a-z0-9_]{1,64}\.(?:gif|jpg|png)$`i', $filename))
					{
						continue;
					}

					// Vérification du type de fichier.
					$i = getimagesize($dir . $filename);
					if ($i === FALSE || !in_array($i[2], array(1, 2, 3)))
					{
						continue;
					}

					$selected = (utils::$config['gallery_banner']['src'] == $filename)
						? ' selected="selected"'
						: '';
					$f = utils::tplProtect($filename);
					$options .= '<option' . $selected
						. ' value="' . $f . '">' . $f . '</option>';
				}
				return $options;

			// Cases à cocher.
			case 'gallery_closure' :
			case 'html_filter' :
			case 'lang_client' :
			case 'lang_switch' :
			case 'nohits_useragent' :
			case 'recaptcha_comments' :
			case 'recaptcha_comments_guest_only' :
			case 'recaptcha_contact' :
			case 'recaptcha_inscriptions' :
			case 'upload_report_all_files' :
			case 'upload_update_images' :
			case 'upload_update_thumb_id' :
				return utils::$config[$item]
					? ' checked="checked"'
					: '';

			// Intégration de la galerie.
			case 'gallery_integrated' :
				return settings::$configValues['CONF_INTEGRATED']
					? ' checked="checked"'
					: '';

			// Gestion de la transparence GD.
			case 'gd_transparency' :
				return settings::$configValues['CONF_GD_TRANSPARENCY']
					? ' checked="checked"'
					: '';

			// Champs textes.
			case 'level_separator' :
			case 'nohits_useragent_list' :
			case 'recaptcha_private_key' :
			case 'recaptcha_public_key' :
				return utils::tplProtect(utils::$config[$item]);

			case 'gallery_closure_message' :
			case 'gallery_description' :
			case 'gallery_description_guest' :
			case 'gallery_footer_message' :
			case 'gallery_title' :
				return utils::tplProtect(
					utils::getLocale(utils::$config[$item], $this->getLang('code'))
				);

			// Langues disponibles.
			case 'available_langs' :
				$locale_path = GALLERY_ROOT . '/locale';
				$files = scandir($locale_path);
				$options = '';
				foreach ($files as &$f)
				{
					if (!is_dir($locale_path . '/' . $f) || $f[0] == '.'
					|| !preg_match('`[a-z]{2}_[A-Z]{2}`', $f)
					|| isset(utils::$config['locale_langs'][$f]))
					{
						continue;
					}
					include($locale_path . '/' . $f . '/lang.php');
					$options .= '<option value="' . utils::tplProtect($f) . '">'
						. utils::tplProtect($name) . '</option>';
				}
				return $options;

			// Langue par défaut.
			case 'default_langs' :
				$options = '';
				foreach (utils::$config['locale_langs'] as $lang => $name)
				{
					$selected = (settings::$configValues['CONF_DEFAULT_LANG'] == $lang)
						? ' selected="selected"'
						: '';
					$options .= '<option' . $selected . ' value="'
						. utils::tplProtect($lang) . '">'
						. utils::tplProtect($name) . '</option>';
				}
				return $options;

			// Langues installées.
			case 'installed_langs' :
				$options = '';
				foreach (utils::$config['locale_langs'] as $lang => $name)
				{
					$options .= '<option value="' . utils::tplProtect($lang) . '">'
						. utils::tplProtect($name) . '</option>';
				}
				return $options;

			// Barres de navigation.
			case 'nav_bar' :
				$options = '';
				$values = array(
					'bottom' => __('barre du bas'),
					'top' => __('barre du haut'),
					'top_bottom' => __('barre du haut et barre du bas')
				);
				foreach ($values as $o => &$l)
				{
					$selected = (utils::$config['nav_bar'] == $o)
						? ' selected="selected"'
						: '';
					$options .= '<option' . $selected . ' value="'
						. $o . '">' . $l . '</option>';
				}
				return $options;

			// Fuseaux horaires.
			case 'tz_list' :
				$tz = (isset($_POST['tz_default']))
					? $_POST['tz_default']
					: CONF_DEFAULT_TZ;
				return str_replace(
					'value="' . $tz . '"',
					'value="' . $tz . '" selected="selected"',
					file_get_contents(GALLERY_ROOT . '/includes/tz.html')
				);

			// URL rewriting.
			case 'url_rewriting' :
				return settings::$configValues['CONF_URL_REWRITE']
					? ' checked="checked"'
					: '';
		}
	}
}

/**
 * Méthodes de template pour la page des options des images.
 */
class tplOptionsImages extends tplAdmin
{
	/**
	 * Retourne l'option $item.
	 *
	 * @param string $item
	 * @return string
	 */
	public function getOption($item)
	{
		switch ($item)
		{
			// Cases à cocher.
			case 'images_anti_copy' :
			case 'images_direct_link' :
			case 'images_orientation' :
			case 'images_resize' :
			case 'recent_images' :
			case 'recent_images_nb' :
				return utils::$config[$item]
					? ' checked="checked"'
					: '';

			// Méthode de redimensionnement des images.
			case 'images_resize_gd' :
				return utils::$config['images_resize_method'] == 2
					? ' checked="checked"'
					: '';

			case 'images_resize_html' :
				return utils::$config['images_resize_method'] == 1
					? ' checked="checked"'
					: '';

			// Durée de nouveauté des images récentes
			// et limites de redimensionnement des images.
			case 'recent_images_time' :
			case 'images_orientation_quality' :
			case 'images_resize_gd_height' :
			case 'images_resize_gd_quality' :
			case 'images_resize_gd_width' :
			case 'images_resize_html_height' :
			case 'images_resize_html_width' :
				return (int) utils::$config[$item];
		}
	}
}

/**
 * Méthodes de template pour la page des options de courriel.
 */
class tplOptionsEmail extends tplAdmin
{
	/**
	 * Retourne l'option $item.
	 *
	 * @param string $item
	 * @return string
	 */
	public function getOption($item)
	{
		switch ($item)
		{
			// Cases à cocher.
			case 'mail_auto_bcc' :
				return utils::$config[$item]
					? ' checked="checked"'
					: '';

			// Champs textes.
			case 'mail_auto_primary_recipient_address' :
			case 'mail_auto_sender_address' :
			case 'mail_notify_comment_message' :
			case 'mail_notify_comment_subject' :
			case 'mail_notify_comment_auth_message' :
			case 'mail_notify_comment_auth_subject' :
			case 'mail_notify_comment_pending_subject' :
			case 'mail_notify_comment_pending_message' :
			case 'mail_notify_comment_pending_auth_subject' :
			case 'mail_notify_comment_pending_auth_message' :
			case 'mail_notify_comment_follow_subject' :
			case 'mail_notify_comment_follow_message' :
			case 'mail_notify_comment_follow_auth_subject' :
			case 'mail_notify_comment_follow_auth_message' :
			case 'mail_notify_guestbook_message' :
			case 'mail_notify_guestbook_subject' :
			case 'mail_notify_guestbook_auth_message' :
			case 'mail_notify_guestbook_auth_subject' :
			case 'mail_notify_guestbook_pending_subject' :
			case 'mail_notify_guestbook_pending_message' :
			case 'mail_notify_guestbook_pending_auth_subject' :
			case 'mail_notify_guestbook_pending_auth_message' :
			case 'mail_notify_images_message' :
			case 'mail_notify_images_subject' :
			case 'mail_notify_images_pending_subject' :
			case 'mail_notify_images_pending_message' :
			case 'mail_notify_inscription_message' :
			case 'mail_notify_inscription_subject' :
			case 'mail_notify_inscription_pending_subject' :
			case 'mail_notify_inscription_pending_message' :
				return utils::tplProtect(utils::$config[$item]);

			case 'mail_php' :
				return settings::$configValues['CONF_SMTP_MAIL']
					? ''
					: ' checked="checked"';

			case 'mail_smtp' :
				return settings::$configValues['CONF_SMTP_MAIL']
					? ' checked="checked"'
					: '';

			case 'mail_smtp_anonym' :
				return settings::$configValues['CONF_SMTP_AUTH']
					? ''
					: ' checked="checked"';

			case 'mail_smtp_port' :
				return utils::tplProtect(settings::$configValues['CONF_SMTP_PORT']);

			case 'mail_smtp_server' :
				return utils::tplProtect(settings::$configValues['CONF_SMTP_SERV']);

			case 'mail_smtp_user' :
				return utils::tplProtect(settings::$configValues['CONF_SMTP_USER']);

			case 'mail_smtp_pass' :
				return settings::$configValues['CONF_SMTP_PASS'] === ''
					? ''
					: '**********';
		}
	}
}

/**
 * Méthodes de template pour la page des options de vignettes.
 */
class tplOptionsThumbs extends tplAdmin
{
	/**
	 * L'option $item doit-elle être affichée ?
	 *
	 * @param string $item
	 * @return boolean
	 */
	public function disOption($item)
	{
		switch ($item)
		{
			// Distinction catégories/albums.
			case 'thumbs_cat_type_albums' :
				return utils::$config['sql_categories_order_by_type'] == 'type DESC,'
					? ' checked="checked"'
					: '';

			case 'thumbs_cat_type_categories' :
				return utils::$config['sql_categories_order_by_type'] == 'type ASC,'
					? ' checked="checked"'
					: '';

			case 'thumbs_cat_type_none' :
				return utils::$config['sql_categories_order_by_type'] != 'type ASC,'
				    && utils::$config['sql_categories_order_by_type'] != 'type DESC,'
					? ' checked="checked"'
					: '';

			// Informations sous les vignettes.
			case 'thumbs_stats_albums' :
			case 'thumbs_stats_category_title' :
			case 'thumbs_stats_comments' :
			case 'thumbs_stats_date' :
			case 'thumbs_stats_filesize' :
			case 'thumbs_stats_hits' :
			case 'thumbs_stats_images' :
			case 'thumbs_stats_image_title' :
			case 'thumbs_stats_size' :
			case 'thumbs_stats_votes' :
				return utils::$config[$item]
					? ' checked="checked"'
					: '';

			// Protection des vignettes aux accès direct.
			case 'thumbs_protect' :
				return (bool) settings::$configValues['CONF_' . strtoupper($item)];
		}
	}

	/**
	 * Retourne l'option $item.
	 *
	 * @param string $item
	 * @return string
	 */
	public function getOption($item)
	{
		switch ($item)
		{
			// Critères de tri.
			case 'thumbs_cat_order_by_1' :
			case 'thumbs_cat_order_by_2' :
			case 'thumbs_cat_order_by_3' :
				$options = array(
					'cat_position' => __('tri manuel'),
					'cat_name' => __('titre'),
					'cat_path' => __('nom de répertoire'),
					'cat_crtdt' => __('date de création'),
					'cat_lastadddt' => __('date de mise à jour'),
					'cat_a_size' => __('poids'),
					'cat_a_images' => __('nombre d\'images')
				);
				return $this->_orderby(
					$options,
					'([^\s]+)[^,]+,([^\s]+)[^,]+,([^\s]+)[^,]+,',
					utils::$config['sql_categories_order_by'],
					$item
				);

			case 'thumbs_cat_ascdesc_1' :
			case 'thumbs_cat_ascdesc_2' :
			case 'thumbs_cat_ascdesc_3' :
				$options = array(
					'ASC' => __('croissant'),
					'DESC' => __('décroissant')
				);
				return $this->_orderby(
					$options,
					'[^,]+(ASC|DESC),[^,]+(ASC|DESC),[^,]+(ASC|DESC),',
					utils::$config['sql_categories_order_by'],
					$item
				);

			case 'thumbs_img_order_by_1' :
			case 'thumbs_img_order_by_2' :
			case 'thumbs_img_order_by_3' :
				$options = array(
					'image_position' => __('tri manuel'),
					'image_name' => __('titre'),
					'image_path' => __('nom de fichier'),
					'image_size' => __('taille'),
					'image_filesize' => __('poids'),
					'image_hits' => __('nombre de visites'),
					'image_comments' => __('nombre de commentaires'),
					'image_votes' => __('nombre de votes'),
					'image_rate' => __('note moyenne'),
					'image_adddt' => __('date d\'ajout'),
					'image_crtdt' => __('date de création')
				);
				return $this->_orderby(
					$options,
					'([^\s]+)[^,]+,([^\s]+)[^,]+,([^\s]+)[^,]+,',
					utils::$config['sql_images_order_by'],
					$item
				);

			case 'thumbs_img_ascdesc_1' :
			case 'thumbs_img_ascdesc_2' :
			case 'thumbs_img_ascdesc_3' :
				$options = array(
					'ASC' => __('croissant'),
					'DESC' => __('décroissant')
				);
				return $this->_orderby(
					$options,
					'[^,]+(ASC|DESC),[^,]+(ASC|DESC),[^,]+(ASC|DESC),',
					utils::$config['sql_images_order_by'],
					$item
				);

			// Présentation des vignettes des catégories.
			case 'thumbs_cat_extended' :
				$options = '';
				foreach (array('0' => __('compact'), '1' => __('étendue')) as $value => $text)
				{
					$selected = (utils::$config['thumbs_cat_extended'] == $value)
						? ' selected="selected"'
						: '';
					$options .= '<option' . $selected . ' value="' . $value . '">'
						. $text . '</option>';
				}
				return $options;

			// Nombre de vignettes par page.
			case 'thumbs_alb_nb' :
			case 'thumbs_cat_nb' :
				return (int) utils::$config[$item];

			// Taille et qualité des vignettes.
			case 'thumbs_cat_height' :
			case 'thumbs_cat_quality' :
			case 'thumbs_cat_size' :
			case 'thumbs_cat_width' :
			case 'thumbs_img_height' :
			case 'thumbs_img_quality' :
			case 'thumbs_img_size' :
			case 'thumbs_img_width' :
				return (int) settings::$configValues['CONF_' . strtoupper($item)];

			// Méthode de redimensionnement.
			case 'thumbs_cat_method_crop' :
			case 'thumbs_cat_method_prop' :
			case 'thumbs_img_method_crop' :
			case 'thumbs_img_method_prop' :
				return settings::$configValues['CONF_' . strtoupper(substr($item, 0, -5))]
					== substr($item, -4, 4)
					? ' checked="checked"'
					: '';
		}
	}
}

/**
 * Méthodes de template pour l'édition et la création d'une nouvelle page.
 */
class tplPage extends tplAdmin
{
	/**
	 * Y a-t-il des fichiers de contenu pour page ?
	 *
	 * @return boolean
	 */
	public function disContentFiles()
	{
		foreach (scandir(GALLERY_ROOT . '/files/pages/') as $filename)
		{
			if (preg_match('`^[-a-z0-9_]{1,64}\.php$`i', $filename))
			{
				return TRUE;
			}
		}
	}

	/**
	 * Retourne la liste des fichiers de contenu pour page.
	 *
	 * @return string
	 */
	public function getContentFiles()
	{
		$options = '';
		foreach (scandir(GALLERY_ROOT . '/files/pages/') as $filename)
		{
			if (preg_match('`^[-a-z0-9_]{1,64}\.php$`i', $filename))
			{
				$f = utils::tplProtect($filename);
				$selected = '';
				if ($_GET['section'] = 'page'
				&& isset($_GET['perso'])
				&& isset(utils::$config['pages_params']
				['perso_' . $_GET['perso']]['file'])
				&& utils::$config['pages_params']
				['perso_' . $_GET['perso']]['file'] == $f)
				{
					$selected = ' selected="selected"';
				}
				$options .= '<option' . $selected . ' value="' . $f . '">' . $f . '</option>';
			}
		}
		return $options;
	}

	/**
	 * Retourne l'élément de page personnalisée $item.
	 *
	 * @param string $item
	 * @return string
	 */
	public function getPagePerso($item)
	{
		switch ($item)
		{
			// Nombre maximum de caractères du contenu.
			case 'content_maxlength' :
				return (int) utils::$config['widgets_content_maxlength'];
		}

		if ($_GET['section'] == 'new-page')
		{
			return;
		}

		switch ($item)
		{
			// Texte HTML de contenu.
			case 'text' :
				if (!isset(utils::$config['pages_params']['perso_' . $_GET['perso']]['text']))
				{
					return '';
				}
				return utils::tplProtect(utils::getLocale(
					utils::$config['pages_params']['perso_' . $_GET['perso']]['text'],
					$this->getLang('code')
				));

			// Fichier de contenu.
			case 'file' :
				return (utils::$config['pages_params']
					['perso_' . $_GET['perso']]['type'] == 'file')
					? ' checked="checked"'
					: '';

			// Attribut "action" du formulaire.
			case 'form_action' :
				return utils::genURL(str_replace('/new', '', admin::$sectionRequest));

			// Identifiant.
			case 'id' :
				return (int) $_GET['perso'];

			// Titer de la page.
			case 'title' :
				return utils::tplProtect(utils::getLocale(
					utils::$config['pages_params']['perso_' . $_GET['perso']]['title']
				));

			// Titer de la page localisée.
			case 'title_lang' :
				return utils::tplProtect(utils::getLocale(
					utils::$config['pages_params']['perso_' . $_GET['perso']]['title'],
					$this->getLang('code')
				));
		}
	}
}

/**
 * Méthodes de template pour l'édition de la page 'commentaires'.
 */
class tplPageComments extends tplAdmin
{
	/**
	 * Retourne l'élément de la page 'commentaires' $item.
	 *
	 * @param string $item
	 * @return string
	 */
	public function getPageComments($item)
	{
		$i = utils::$config['pages_params']['comments'];

		switch ($item)
		{
			case 'nb_per_page' :
				return (int) $i['nb_per_page'];

			case 'title_default' :
				return __('Commentaires');
		}
	}
}

/**
 * Méthodes de template pour l'édition de la page 'contact'.
 */
class tplPageContact extends tplAdmin
{
	/**
	 * Retourne l'élément de la page 'contact' $item.
	 *
	 * @param string $item
	 * @return string
	 */
	public function getPageContact($item)
	{
		$i = utils::$config['pages_params']['contact'];

		switch ($item)
		{
			case 'email' :
				return utils::tplProtect($i['email']);

			case 'message' :
				return utils::tplProtect(utils::getLocale(
					$i['message'], $this->getLang('code')
				));

			case 'title_default' :
				return __('Contact');
		}
	}
}

/**
 * Méthodes de template pour l'édition de la page 'livre d'or'.
 */
class tplPageGuestbook extends tplAdmin
{
	/**
	 * Retourne l'élément de la page 'livre d'or' $item.
	 *
	 * @param string $item
	 * @return string
	 */
	public function getPageGuestbook($item)
	{
		$i = utils::$config['pages_params']['guestbook'];

		switch ($item)
		{
			case 'message' :
				return utils::tplProtect(utils::getLocale(
					$i['message'], $this->getLang('code')
				));

			case 'nb_per_page' :
				return (int) $i['nb_per_page'];

			case 'title_default' :
				return __('Livre d\'or');
		}
	}
}

/**
 * Méthodes de template pour l'édition de la page 'membres'.
 */
class tplPageMembers extends tplAdmin
{
	/**
	 * L'élément de la page 'members' $item doit-il être affiché ?
	 *
	 * @param string $item
	 * @return string
	 */
	public function disPageMembers($item)
	{
		$i = utils::$config['pages_params']['members'];

		switch ($item)
		{
			case 'show_crtdt' :
			case 'show_lastvstdt' :
			case 'show_title' :
				return (bool) $i[$item];
		}
	}

	/**
	 * Retourne l'élément de la page 'members' $item.
	 *
	 * @param string $item
	 * @return string
	 */
	public function getPageMembers($item)
	{
		$i = utils::$config['pages_params']['members'];

		switch ($item)
		{
			// Nombre d'utiisateurs par page.
			case 'nb_per_page' :
				return (int) $i['nb_per_page'];


			// Critères de tri.
			case 'order_by_1' :
				$options = array(
					'user_login' => __('Nom d\'utilisateur'),
					'user_crtdt' => __('Date d\'inscription'),
					'user_lastvstdt' => __('Date de dernière visite')
				);
				return $this->_orderby(
					$options,
					'([^\s]+)[^,]+',
					$i['order_by'],
					$item
				);

			case 'ascdesc_1' :
				$options = array(
					'ASC' => __('croissant'),
					'DESC' => __('décroissant')
				);
				return $this->_orderby(
					$options,
					'[^,]+(ASC|DESC)',
					$i['order_by'],
					$item
				);

			// Titre de la page.
			case 'title_default' :
				return __('Liste des membres');
		}
	}
}

/**
 * Méthodes de template pour la page de gestion des pages.
 */
class tplPages extends tplAdmin
{
	/**
	 * L'élément de page $item doit-il être affiché ?
	 *
	 * @param string $item
	 * @return string
	 */
	public function disPage($item)
	{
		$i = current(utils::$config['pages_order']);

		switch ($item)
		{
			// Fonctionnalité non diponible avec le template actuel ?
			case 'disabled' :
				switch ($i)
				{
					case 'comments' :
						return isset(admin::$tplDisabledConfig['comments'])
							&& admin::$tplDisabledConfig['comments'] == 0;

					case 'members' :
						return isset(admin::$tplDisabledConfig['users'])
							&& admin::$tplDisabledConfig['users'] == 0;

					default :
						if ($this->disPage('perso'))
						{
							return isset(admin::$tplDisabledConfig['pages_perso'])
								&& admin::$tplDisabledConfig['pages_perso'] == 0;
						}
						else
						{
							return isset(admin::$tplDisabledConfig
									['pages_params'][$i]['status'])
								&& admin::$tplDisabledConfig
									['pages_params'][$i]['status'] == 0;
						}
				}

			// Lien vers paramétrage de la page ?
			case 'link' :
				return $this->disPage('perso')
					|| $i == 'comments'
					|| $i == 'contact'
					|| $i == 'guestbook'
					|| $i == 'members'
					|| $i == 'worldmap';

			// Page personnalisée ?
			case 'perso' :
				return isset(utils::$config['pages_params'][$i]['title']);

			// Statut.
			case 'status' :
				return (bool) utils::$config['pages_params'][$i]['status'];
		}
	}

	/**
	 * Retourne l'élément de page $item.
	 *
	 * @param string $item
	 * @return string
	 */
	public function getPage($item)
	{
		$i = current(utils::$config['pages_order']);

		switch ($item)
		{
			// Identifiant.
			case 'id' :
				return (int) key(utils::$config['pages_order']);

			// Lien vers la page d'édition de la page.
			case 'link' :
				$i = (substr($i, 0, 6) == 'perso_')
					? str_replace('_', '/', $i)
					: str_replace('_', '-', $i);
				return utils::genURL('page/' . $i);

			// Nom de la page.
			case 'name' :
				return utils::tplProtect($i);

			// Titre de la page.
			case 'title' :
				switch ($i)
				{
					case 'basket' :
						return __('Panier');

					case 'cameras' :
						return __('Appareils photos');

					case 'comments' :
						return __('Commentaires');

					case 'contact' :
						return __('Contact');

					case 'guestbook' :
						return __('Livre d\'or');

					case 'history' :
						return __('Historique');

					case 'members' :
						return __('Liste des membres');

					case 'sitemap' :
						return __('Plan de la galerie');

					case 'tags' :
						return __('Tags');

					case 'worldmap' :
						return __('Carte du monde');

					default :
						return utils::tplProtect(utils::getLocale(
							utils::$config['pages_params'][$i]['title']
						));
				}
		}
	}

	/**
	 * Y a-t-il une prochaine page ?
	 *
	 * @return boolean
	 */
	public function nextPage()
	{
		static $next = -1;

		return template::nextObject(utils::$config['pages_order'], $next);
	}
}

/**
 * Méthodes de template pour l'édition de la page 'worldmap'.
 */
class tplPageWorldmap extends tplAdmin
{
	/**
	 * Retourne l'élément de la page 'worldmap' $item.
	 *
	 * @param string $item
	 * @return integer|string
	 */
	public function getPageWorldmap($item)
	{
		$i = utils::$config['pages_params']['worldmap'];

		switch ($item)
		{
			case 'center_lat' :
			case 'center_long' :
			case 'zoom' :
				return (float) $i[$item];

			case 'title_default' :
				return __('Carte du monde');
		}
	}
}

/**
 * Méthodes de template pour la page de tri des images.
 */
class tplSortAlbum extends tplAlbums
{
	/**
	 * Y a-t-il une prochaine image ?
	 * 
	 * @param integer $thumb_size
	 *	Taille des vignettes.
	 * @return boolean
	 */
	public function nextThumb($thumb_size)
	{
		static $next = -1;

		$this->_thumbForced =& $thumb_size;

		return template::nextObject(albums::$items, $next);
	}

	/**
	 * Retourne l'élément de vignette $item.
	 *
	 * @param string $item
	 * @return string
	 */
	public function getThumb($item)
	{
		$i = current(albums::$items);

		switch ($item)
		{
			// Centrage de la vignette par CSS.
			case 'center' :
				$tb = img::getThumbSize($i, 'img', $this->_thumbForced);
				return img::thumbCenter('img', $tb['width'],
					$tb['height'], $this->_thumbForced);

			// Identifiant.
			case 'id' :
				return utils::tplProtect($i['image_id']);

			// Emplacement de la vignette.
			case 'src' :
				return utils::tplProtect(template::getThumbSrc('img', $i));

			// Dimensions de la vignette.
			case 'thumb_size' :
				$tb = img::getThumbSize($i, 'img', $this->_thumbForced);
				return 'width="' . $tb['width'] . '" height="' . $tb['height'] . '"';

			// Titre.
			case 'title' :
				return utils::tplProtect($i['image_name']);
		}
	}
}

/**
 * Méthodes de template pour la page de tri des catégories.
 */
class tplSortCategory extends tplAlbums
{
	/**
	 * Y a-t-il une prochaine catégorie ?
	 * 
	 * @param integer $thumb_size
	 *	Taille des vignettes.
	 * @return boolean
	 */
	public function nextThumb($thumb_size)
	{
		static $next = -1;

		$this->_thumbForced =& $thumb_size;

		return template::nextObject(albums::$items, $next);
	}

	/**
	 * L'élément de vignette $item doit-il être affiché ?
	 *
	 * @param string $item
	 * @return boolean
	 */
	public function disThumb($item)
	{
		$i = current(albums::$items);

		switch ($item)
		{
			case 'deactivate' :
				return $i['cat_status'] == 0;

			case 'empty' :
				return $i['thumb_id'] == -1;
		}
	}

	/**
	 * Retourne l'élément de vignette $item.
	 *
	 * @param string $item
	 * @return string
	 */
	public function getThumb($item)
	{
		$i = current(albums::$items);

		switch ($item)
		{
			// Centrage de la vignette par CSS.
			case 'center' :
				$tb = img::getThumbSize($i, 'cat', $this->_thumbForced);
				return img::thumbCenter('cat', $tb['width'],
					$tb['height'], $this->_thumbForced);

			// Identifiant.
			case 'id' :
				return utils::tplProtect($i['cat_id']);

			// Type d'objet + identifiant.
			case 'object_type' :
				$object = ($i['cat_filemtime'] === NULL)
					? __('catégorie %s')
					: __('album %s');
				return sprintf($object, $this->getThumb('id'));

			// Emplacement de la vignette.
			case 'src' :
				return utils::tplProtect(template::getThumbSrc('cat', $i));

			// Dimensions de la vignette.
			case 'thumb_size' :
				$tb = img::getThumbSize($i, 'cat', $this->_thumbForced);
				return 'width="' . $tb['width'] . '" height="' . $tb['height'] . '"';

			// Titre.
			case 'title' :
				return utils::tplProtect(utils::getLocale($i['cat_name']));
		}
	}
}

/**
 * Méthodes de template pour la page des informations système.
 */
class tplSystem extends tplAdmin
{
	/**
	 * Directives PHP.
	 *
	 * @var array
	 */
	private $_directives;



	/**
	 * Retourne l'élément de directive PHP $item.
	 *
	 * @param string $item
	 * @return string
	 */
	public function getDirective($item)
	{
		$i = current($this->_directives);

		switch ($item)
		{
			// Nom de la directive.
			case 'name' :
				return utils::tplProtect(key($this->_directives));

			// Statut.
			case 'status' :
				return $i['status'];

			// Valeur.
			case 'value' :
				return utils::tplProtect($i['value']);
		}
	}

	/**
	 * Y a-t-il une prochaine directive ?
	 *
	 * @return boolean
	 */
	public function nextDirective()
	{
		static $next = -1;

		if ($next === -1)
		{
			$this->_directives = system::getPHPDirectives();
		}

		return template::nextObject($this->_directives, $next);
	}

	/**
	 * Retourne l'élément d'extension PHP $item.
	 *
	 * @param string $item
	 * @return string
	 */
	public function getExtension($item)
	{
		switch ($item)
		{
			// Nom de l'extension.
			case 'name' :
				return utils::tplProtect(key(system::$phpExtensions));

			// Statut.
			case 'status' :
				switch (key(system::$phpExtensions))
				{
					case 'gd' :
					case 'PDO' :
					case 'pdo_mysql' :
						return current(system::$phpExtensions)
							? 'ok'
							: 'error';

					default :
						return current(system::$phpExtensions)
							? 'ok'
							: 'warning';
				}

			// Valeur.
			case 'value' :
				return current(system::$phpExtensions)
					? __('chargée')
					: __('non chargée');
		}
	}

	/**
	 * Y a-t-il une prochaine extension ?
	 *
	 * @return boolean
	 */
	public function nextExtension()
	{
		static $next = -1;

		if ($next === -1)
		{
			system::getPHPExtensions();
		}

		return template::nextObject(system::$phpExtensions, $next);
	}

	/**
	 * Retourne l'élément de fonction PHP $item.
	 *
	 * @param string $item
	 * @return string
	 */
	public function getFunction($item)
	{
		switch ($item)
		{
			// Nom de la fonction.
			case 'name' :
				return utils::tplProtect(key(system::$phpFunctions) . '()');

			// Statut.
			case 'status' :
				return current(system::$phpFunctions)
					? 'ok'
					: 'warning';

			// Valeur.
			case 'value' :
				return current(system::$phpFunctions)
					? __('activée')
					: __('désactivée');
		}
	}

	/**
	 * Y a-t-il une prochaine fonction ?
	 *
	 * @return boolean
	 */
	public function nextFunction()
	{
		static $next = -1;

		if ($next === -1)
		{
			system::getPHPFunctions();
		}

		return template::nextObject(system::$phpFunctions, $next);
	}

	/**
	 * Retourne l'information système $item.
	 *
	 * @param string $item
	 * @return string
	 */
	public function getSystemInfo($item)
	{
		switch ($item)
		{
			case 'gallery_version' :
				return system::$galleryVersion;

			case 'gallery_errors' :
				return (int) admin::$infos['errors'];

			case 'gallery_errors_status' :
				return (int) admin::$infos['errors']
					? 'error'
					: 'ok';

			case 'history' :
				$upgrade = '<table>';
				foreach (utils::$config['history'] as $k => &$v)
				{
					$upgrade .= '<tr><td>'
						. utils::tplProtect(
							sprintf(__('version %s'), str_replace('upgrade_', '', $k))
						  )
						. '</td><td>' . utils::tplProtect($v) . '</td></tr>';
				}
				$upgrade .= '</table>';
				return $upgrade;

			case 'gd_freetype' :
				$gd_infos = img::gdInfos();
				if ($gd_infos === FALSE || !isset($gd_infos['FreeType Support']))
				{
					return '?';
				}
				return $gd_infos['FreeType Support']
					? __('supporté')
					: __('non supporté');

			case 'gd_freetype_status' :
				$gd_infos = img::gdInfos();
				return ($gd_infos === FALSE
					|| !isset($gd_infos['FreeType Support'])
					|| !$gd_infos['FreeType Support'])
					? 'warning'
					: 'ok';

			case 'gd_version' :
				return utils::tplProtect(system::getGDVersion(TRUE));

			case 'gd_version_compatible' :
				return (system::isGDVersionCompatible())
					? 'ok'
					: 'error';

			case 'gd_gif' :
			case 'gd_jpg' :
			case 'gd_png' :
				return img::supportType(substr($item, 3))
					? __('supporté')
					: __('non supporté');

			case 'gd_gif_status' :
			case 'gd_jpg_status' :
			case 'gd_png_status' :
				return img::supportType(substr($item, 3, 3))
					? 'ok'
					: 'warning';

			case 'mysql_version' :
				return utils::tplProtect(admin::$infos['mysql_version']);

			case 'mysql_version_compatible' :
				return system::isMySQLVersionCompatible()
					? 'ok'
					: 'error';

			case 'mysql_variable_have_innodb' :
			case 'mysql_variable_max_user_connections' :
			case 'mysql_variable_sql_mode' :
				$variable = substr($item, 15);
				return (isset(admin::$infos['mysql_variables'][$variable]))
					? utils::tplProtect(admin::$infos['mysql_variables'][$variable])
					: '?';

			case 'php_version' :
				return utils::tplProtect(system::getPHPVersion(TRUE));

			case 'php_sapi' :
				return utils::tplProtect(system::getPHPSAPI());

			case 'php_version_compatible' :
				return (system::isPHPVersionCompatible())
					? 'ok'
					: 'error';

			case 'server_os' :
				return utils::tplProtect(system::getOSDetails('sr'));

			case 'server_time' :
				return utils::tplProtect(date('Y-m-d H:i:s P (e)'));

			case 'server_type' :
				return utils::tplProtect(system::getServerType());
		}
	}

	/**
	 * Retourne l'élément de permission $item.
	 *
	 * @param string $item
	 * @return string
	 */
	public function getWritePermission($item)
	{
		$i = current(system::$writePermissions);

		switch ($item)
		{
			// Nom de fichier.
			case 'name' :
				return utils::tplProtect(key(system::$writePermissions));

			// Permissions.
			case 'perms' :
				return utils::tplProtect($i[1]);

			// Statut.
			case 'status' :
				return $i[0]
					? 'ok'
					: 'warning';

			// Valeur.
			case 'value' :
				return $i[0]
					? __('oui')
					: __('non');
		}
	}

	/**
	 * Y a-t-il un prochain fichier ?
	 *
	 * @return boolean
	 */
	public function nextWritePermission()
	{
		static $next = -1;

		if ($next === -1)
		{
			system::getWritePermissions();
		}

		return template::nextObject(system::$writePermissions, $next);
	}
}

/**
 * Méthodes de template pour la page de gestion des tags.
 */
class tplTags extends tplAdmin
{
	/**
	 * Retourne une valeur d'une propriété de la classe "tags".
	 *
	 * @param array|string $property
	 * @return mixed
	 */
	public function getInfo($property)
	{
		return (is_array($property))
			? utils::tplProtect(tags::${$property[0]}[$property[1]])
			: utils::tplProtect(tags::${$property});
	}

	/**
	 * L'élément de navigation entre les pages $item doit-il être affiché ?
	 *
	 * @param string $item
	 * @return boolean
	 */
	public function disNavigation($item = '')
	{
		return template::disNavigation($item, tags::$nbPages);
	}

	/**
	 * Retourne l'élément de navigation entre les pages $item.
	 *
	 * @param string $item
	 * @return string
	 */
	public function getNavigation($item)
	{
		return template::getNavigation($item, tags::$nbPages, admin::$sectionRequest);
	}

	/**
	 * Retourne l'élément des options d'affichage $item.
	 *
	 * @param string $item
	 * @return string
	 */
	public function getOptions($item)
	{
		switch ($item)
		{
			// Ordre de tri.
			case 'orderby' :
				$p = array(
					'ASC' => __('croissant'),
					'DESC' => __('décroissant')
				);
				return $this->_displayOptionsList('tags', $item, 'DESC', $p);

			// Nombre d'objets par page.
			case 'nb_per_page':
				return (int) auth::$infos['user_prefs']['tags'][$item];

			// Critère de tri.
			case 'sortby' :
				$p = array(
					'name' => __('Nom'),
					'nb_images' => __('Nombre d\'images liées')
				);
				return $this->_displayOptionsList('tags', $item, 'date', $p);
		}
	}

	/**
	 * Retourne l'élément de tag $item.
	 *
	 * @param string $item
	 * @return string
	 */
	public function getTag($item)
	{
		$i = current(tags::$items);

		switch ($item)
		{
			// Identifiant du tag.
			case 'id' :
				return (int) $i['tag_id'];

			// Lien vers les images liées au tag.
			case 'images_tag_link' :
				return utils::genURL('category/1/tag/' . $i['tag_id']);

			// Nombre d'images liées.
			case 'nb_images' :
				return (int) $i['tag_nb_images'];

			// Nom.
			case 'name' :
				return utils::tplProtect($i['tag_name']);

			// Nom d'URL.
			case 'urlname' :
				return utils::tplProtect($i['tag_url']);
		}
	}

	/**
	 * Y a-t-il un prochain tag ?
	 *
	 * @return boolean
	 */
	public function nextTag()
	{
		static $next = -1;

		return template::nextObject(tags::$items, $next);
	}

	/**
	 * Y a-t-il des tags ?
	 *
	 * @return boolean
	 */
	public function disTags()
	{
		return tags::$nbItems > 0;
	}

	/**
	 * S'agit-il d'une recherche ?
	 *
	 * @return boolean
	 */
	public function disSearch()
	{
		return tags::$searchInit;
	}

	/**
	 * Retourne l'élément de recherche $item.
	 *
	 * @param string $item
	 * @return string
	 */
	public function getSearch($item)
	{
		switch ($item)
		{
			// Options : cases à cocher.
			case 'tag_nb_images' :
				return (isset($_GET['search_' . $item]))
					? ' checked="checked"'
					: '';

			// Options : champs textes.
			case 'tag_nb_images_max' :
			case 'tag_nb_images_min' :
				return (isset($_GET['search_' . $item]))
					? utils::tplProtect($_GET['search_' . $item])
					: '';

			// Options : cases à cocher avec option cochée par défaut.
			case 'tag_name' :
			case 'tag_url' :
				return (!isset($_GET['search_query']) || isset($_GET['search_' . $item]))
					? ' checked="checked"'
					: '';

			default :
				return $this->_getSearch($item);
		}
	}
}

/**
 * Méthodes de template pour la page de gestion des thèmes.
 */
class tplThemes extends tplAdmin
{
	/**
	 * Styles du thème courant.
	 *
	 * @var array
	 */
	private $_styles;



	/**
	 * Retourne le code CSS du style additionnel.
	 *
	 * @return string
	 */
	public function getCSS()
	{
		return utils::tplProtect(utils::$config['theme_css']);
	}

	/**
	 * L'élément de style $item doit-il être affiché ?
	 *
	 * @param string $item
	 * @return boolean
	 */
	public function disStyle($item)
	{
		switch ($item)
		{
			// Style actuel.
			case 'current' :
				if ($this->disTheme('current'))
				{
					return utils::$config['theme_style'] == key($this->_styles);
				}

				$styles = $this->_styles;
				reset($styles);
				return key($this->_styles) == key($styles);
		}
	}

	/**
	 * Retourne l'élément de thème $item.
	 *
	 * @param string $item
	 * @return string
	 */
	public function getStyle($item)
	{
		$i = current($this->_styles);

		switch ($item)
		{
			// Auteur du style courant.
			case 'author' :
				return utils::tplProtect($i->author);

			// Description du style courant.
			case 'description' :
				return nl2br(utils::tplProtect($i->description));

			// Nom du style courant.
			case 'name' :
				return utils::tplProtect(key($this->_styles));
		}
	}

	/**
	 * Y a-t-il un prochain style ?
	 *
	 * @return boolean
	 */
	public function nextStyle()
	{
		static $next = -1;
		$this->_styles = current(admin::$infos);

		return template::nextObject($this->_styles, $next);
	}

	/**
	 * L'élément de thème $item doit-il être affiché ?
	 *
	 * @param string $item
	 * @return boolean
	 */
	public function disTheme($item)
	{
		$i = current(admin::$infos);

		switch ($item)
		{
			// Thème actuel.
			case 'current' :
				return utils::$config['theme_template'] == key(admin::$infos);
		}
	}

	/**
	 * Retourne l'élément de thème $item.
	 *
	 * @param string $item
	 * @return string
	 */
	public function getTheme($item)
	{
		$i = current(admin::$infos);

		switch ($item)
		{
			// Nom du thème courant.
			case 'name' :
				return utils::tplProtect(key(admin::$infos));

			// Capture d'écran du style actuel.
			case 'screenshot' :
				if ($this->disTheme('current'))
				{
					return $this->getAdmin('gallery_path') . '/template/'
						. utils::$config['theme_template'] . '/style/'
						. utils::$config['theme_style'] . '/screenshot.jpg';
				}

				return $this->getAdmin('gallery_path') . '/template/'
					. key(admin::$infos) . '/style/'
					. key($i) . '/screenshot.jpg';

			// Liste des styles disponibles du thème courant.
			case 'styles' :
				$options = '';
				foreach ($i as $style_name => &$infos)
				{
					$selected = ($this->disTheme('current')
						&& utils::$config['theme_style'] == $style_name)
						? ' selected="selected"'
						: '';
					$style_name = utils::tplProtect($style_name);
					$options .= '<option' . $selected . ' value="' . $style_name . '">'
						. $style_name . '</option>';
				}
				return $options;
		}
	}

	/**
	 * Y a-t-il un prochain thème ?
	 *
	 * @return boolean
	 */
	public function nextTheme()
	{
		static $next = -1;

		return template::nextObject(admin::$infos, $next);
	}
}

/**
 * Méthodes de template pour la page de modifcation
 * de la vigentte des catégories.
 */
class tplThumbCategory extends tplAlbums
{
	/**
	 * Retourne les paramètres de configuration des vignettes.
	 *
	 * @param string $item
	 * @return integer
	 */
	public function getConf($item)
	{
		switch ($item)
		{
			case 'thumb_height' :
				return (CONF_THUMBS_CAT_METHOD == 'crop')
					? (int) CONF_THUMBS_CAT_HEIGHT
					: (int) CONF_THUMBS_CAT_SIZE;

			case 'thumb_width' :
				return (CONF_THUMBS_CAT_METHOD == 'crop')
					? (int) CONF_THUMBS_CAT_WIDTH
					: (int) CONF_THUMBS_CAT_SIZE;

			case 'thumb_ratio' :
				return (CONF_THUMBS_CAT_METHOD == 'crop')
					? round($this->getConf('thumb_width') / $this->getConf('thumb_height'), 2)
					: 0;
		}
	}

	/**
	 * Retourne l'élément $item de l'image.
	 *
	 * @param string $item
	 * @return string
	 */
	public function getImagePreview($item)
	{
		return $this->_getImagePreview($item, 'cat', CONF_THUMBS_CAT_METHOD);
	}

	/**
	 * Retourne l'élément $item de la vignette actuelle.
	 *
	 * @param string $item
	 * @return string
	 */
	public function getThumb($item)
	{
		return $this->_getThumb($item, 'cat');
	}
}

/**
 * Méthodes de template pour la page de modifcation
 * de la vigentte des images.
 */
class tplThumbImage extends tplImage
{
	/**
	 * Retourne les paramètres de configuration des vignettes.
	 *
	 * @param string $item
	 * @return integer
	 */
	public function getConf($item)
	{
		switch ($item)
		{
			case 'thumb_height' :
				return (CONF_THUMBS_IMG_METHOD == 'crop')
					? (int) CONF_THUMBS_IMG_HEIGHT
					: (int) CONF_THUMBS_IMG_SIZE;

			case 'thumb_width' :
				return (CONF_THUMBS_IMG_METHOD == 'crop')
					? (int) CONF_THUMBS_IMG_WIDTH
					: (int) CONF_THUMBS_IMG_SIZE;

			case 'thumb_ratio' :
				return (CONF_THUMBS_IMG_METHOD == 'crop')
					? round($this->getConf('thumb_width') / $this->getConf('thumb_height'), 2)
					: 0;
		}
	}

	/**
	 * Retourne l'élément $item de la vignette actuelle.
	 *
	 * @param string $item
	 * @return string
	 */
	public function getThumb($item)
	{
		return $this->_getThumb($item, 'img');
	}
}

/**
 * Méthodes de template pour les pages de gestion d'un utilisateur.
 */
class tplUser extends tplAdmin
{
	/**
	 * L'élément $item de l'utilisateur doit-il être affiché ?
	 *
	 * @param string $item
	 * @return boolean
	 */
	public function disUser($item)
	{
		switch ($item)
		{
			// L'utilisateur a-t-il un avatar ?
			case 'avatar' :
				return (bool) users::$infos['user_avatar'];

			// L'administrateur possède-t-il l'autorisation
			// d'éditer les informations du profil ?
			case 'edit' :
				return (auth::$infos['user_id'] != 1
					 && auth::$infos['user_id'] != users::$infos['user_id']
					 && users::$infos['group_admin'] == 1) === FALSE;

			// L'administrateur possède-t-il l'autorisation
			// de modifier le compte ?
			case 'modif' :
				return auth::$infos['user_id'] != users::$infos['user_id']
					&& (auth::$infos['user_id'] == 1
					 || auth::$infos['user_id'] != 1 && users::$infos['group_admin'] != 1);

			// L'utilisateur a-t-il une adresse de courriel ?
			case 'sendmail' :
				return !empty(users::$infos['user_email']);

			default :
				return $this->_disUser($item, users::$infos);
		}
	}

	/**
	 * Retourne l'information $item de l'utilisateur.
	 *
	 * @param string $item
	 * @return string
	 */
	public function getUser($item)
	{
		switch ($item)
		{
			// Lien retour.
			case 'back' :
				$group = (isset($_GET['group_id']))
					? '/group/' . $_GET['group_id']
					: '';
				$filter = '';
				if (isset($_GET['search']))
				{
					$filter = '/search/' . $_GET['search'];
				}
				if (isset($_GET['status']))
				{
					$filter = '/' . $_GET['status'];
				}
				$page = (users::$parentPage !== NULL)
					? '/page/' . users::$parentPage
					: '';
				return 'users' . $group . $filter . $page;

			// Attribut "action" du formulaire.
			case 'form_action' :
				return utils::genURL(str_replace('/new', '', admin::$sectionRequest));

			default :
				return $this->_getUser($item, users::$infos);
		}
	}

	/**
	 * Retourne la liste des utilisateurs.
	 *
	 * @return string
	 */
	public function getUsersList()
	{
		$list = '';
		foreach (users::$usersList as &$i)
		{
			$selected = (users::$infos['user_id'] == $i['user_id'])
				? ' selected="selected" class="selected"'
				: '';
			$list .= '<option' . $selected . ' value="' . $_GET['section']
				. '/' . (int) $i['user_id'] . '">'
				. utils::tplProtect($i['user_login']) . '</option>';
		}
		return $list;
	}
}

/**
 * Méthodes de template pour les pages d'édition
 * et de création d'un utilisateur.
 */
class tplUserProfile extends tplUser
{
	/**
	 * Y-a-t-il des informations de profil personnalisées ?
	 *
	 * @return boolean
	 */
	public function disProfilePerso()
	{
		return !empty(utils::$config['users_profile_infos']['perso']);
	}

	/**
	 * Retourne l'information personnalisée $item du profil.
	 *
	 * @param string $item
	 * @return string
	 */
	public function getProfilePerso($item)
	{
		switch ($item)
		{
			// Identifiant.
			case 'id' :
				return utils::tplProtect(key(utils::$config['users_profile_infos']['perso']));

			// Nom de l'information.
			case 'name' :
				$i = current(utils::$config['users_profile_infos']['perso']);
				return utils::tplProtect(sprintf(__('%s :'), utils::getLocale($i['name'])));

			// Valeur de l'information pour l'utilisateur courant.
			case 'value' :
				$id = $this->getProfilePerso('id');
				if (isset($_POST[$id]))
				{
					return utils::tplProtect($_POST[$id]);
				}
				elseif (array_key_exists($id, users::$infos['user_other']))
				{
					return utils::tplProtect(users::$infos['user_other'][$id]);
				}
				return '';
		}
	}

	/**
	 * Y a-t-il une prochaine information personnalisée du profil ?
	 *
	 * @return boolean
	 */
	public function nextProfilePerso()
	{
		static $next = -1;

		return template::nextObject(utils::$config['users_profile_infos']['perso'], $next);
	}

	/**
	 * L'élément de profil $item doit-il être affiché ?
	 *
	 * @param string $item
	 * @return boolean
	 */
	public function disProfile($item)
	{
		switch ($item)
		{
			case 'alert_comments' :
				return users::$infos['group_perms']['admin']['perms']['comments_edit']
					|| users::$infos['group_perms']['admin']['perms']['all'];

			case 'alert_inscriptions' :
				return users::$infos['group_perms']['admin']['perms']['users_members']
					|| users::$infos['group_perms']['admin']['perms']['all'];

			case 'alert_images_pending' :
				return users::$infos['group_perms']['admin']['perms']['albums_pending']
					|| users::$infos['group_perms']['admin']['perms']['all'];
		}
	}

	/**
	 * Retourne l'information $item du profil.
	 *
	 * @param string $item
	 * @return string
	 */
	public function getProfile($item)
	{
		switch ($item)
		{
			// Informations de l'utilisateur.
			case 'id' :
				return $this->getUser($item);

			// Notifications par courriel.
			case 'alert_inscriptions' :
				$alert = (!empty($_POST))
					? isset($_POST['alert'][0])
					: users::$infos['user_alert'][0];
				return ($alert)
					? ' checked="checked"'
					: '';

			case 'alert_comments' :
				$alert = (!empty($_POST))
					? isset($_POST['alert'][1])
					: users::$infos['user_alert'][1];
				return ($alert)
					? ' checked="checked"'
					: '';

			case 'alert_comments_follow' :
				$alert = (!empty($_POST))
					? isset($_POST['alert'][5])
					: users::$infos['user_alert'][5];
				return ($alert)
					? ' checked="checked"'
					: '';

			case 'alert_comments_pending' :
				$alert = (!empty($_POST))
					? isset($_POST['alert'][2])
					: users::$infos['user_alert'][2];
				return ($alert)
					? ' checked="checked"'
					: '';

			case 'alert_images' :
				$alert = (!empty($_POST))
					? isset($_POST['alert'][3])
					: users::$infos['user_alert'][3];
				return ($alert)
					? ' checked="checked"'
					: '';

			case 'alert_images_pending' :
				$alert = (!empty($_POST))
					? isset($_POST['alert'][4])
					: users::$infos['user_alert'][4];
				return ($alert)
					? ' checked="checked"'
					: '';

			// Poids maximum de l'avatar.
			case 'avatar_maxfilesize' :
				return 1024 * (int) utils::$config['avatars_maxfilesize'];

			// Dimensions maximum de l'avatar.
			case 'avatar_maxsize' :
				return 1000;

			// Dimensions de l'avatar.
			case 'avatar_size' :
				$file = (users::$infos['user_avatar'])
					? GALLERY_ROOT . '/users/avatars/user' . users::$infos['user_id'] . '.jpg'
					: GALLERY_ROOT . '/' . $this->getAdmin('admin_dir') . '/template/'
						. utils::$config['admin_template'] . '/style/'
						. utils::$config['admin_style'] . '/avatar-default.png';
				$i = img::getImageSize($file);
				return 'width="' . $i['width'] . '" height="' . $i['height'] . '"';

			// Emplacement de l'avatar.
			case 'avatar_src' :
				$rand = (empty($_POST)) ? '' : '?' . mt_rand();
				return (users::$infos['user_avatar'])
					? CONF_GALLERY_PATH . '/users/avatars/user'
						. (int) users::$infos['user_id'] . '.jpg' . $rand
					: $this->getAdmin('style_path') . '/avatar-default.png';

			// Date de naissance.
			case 'birthdate' :
				$birthdate = (!empty($_POST['day']) && !empty($_POST['month'])
					&& !empty($_POST['year']))
					? $_POST['year'] . '-' . $_POST['month'] . '-' . $_POST['day']
					: users::$infos['user_birthdate'];
				return template::dateSelect($birthdate);

			// Nombre de caractères maxium du champ description.
			case 'desc_maxlength' :
				return (int) utils::$config['users_desc_maxlength'];

			// Groupe.
			case 'groups_list' :
				$group_id = (isset($_POST['group']))
					? $_POST['group']
					: users::$infos['group_id'];
				$groups = '';
				foreach (users::$groups as $id => &$i)
				{
					if ($id < 3
					|| (auth::$infos['user_id'] != 1 && $i['group_admin'] == 1))
					{
						continue;
					}
					$group_name = ($id > 3 || $i['group_name'] != '')
						? utils::tplProtect(utils::getLocale($i['group_name']))
						: admin::getL10nGroupName($id);
					$selected = ($id == $group_id)
						? ' selected="selected"'
						: '';
					$groups .= '<option' . $selected . ' value="' . (int) $id . '">'
						. $group_name . '</option>';
				}
				return $groups;

			// Langue.
			case 'lang' :
				return template::langSelect(
					isset($_POST['lang']) ? $_POST['lang'] : users::$infos['user_lang']
				);

			// Nom d'utilisateur.
			case 'login' :
				$login = ($_GET['section'] == 'new-user' && isset($_POST['login']))
					? $_POST['login']
					: users::$infos['user_login'];
				return utils::tplProtect($login);

			// Ne pas comptabiliser les visites.
			case 'nohits' :
				$nohits = (!empty($_POST))
					? isset($_POST['nohits'])
					: users::$infos['user_nohits'];
				return ($nohits)
					? ' checked="checked"'
					: '';

			// Longueur minimum du mot de passe.
			case 'password_minlength' :
				return (int) utils::$config['users_password_minlength'];

			// Sexe.
			case 'sex' :
				$s = (isset($_POST['sex']))
					? $_POST['sex']
					: users::$infos['user_sex'];
				$s = ($s != 'F' && $s != 'M') ? 'u' : strtolower($s);
				$u = $f = $m = '';
				$$s = ' selected="selected"';
				return '
					<option value="?"' . $u . '>?</option>
					<option value="F"' . $f . '>' . __('Femme') . '</option>
					<option value="M"' . $m . '>' . __('Homme') . '</option>
				';

			// Statut.
			case 'status_list' :
				$status = array(
					0 => __('suspendu'),
					1 => __('activé')
				);
				$s = (isset($_POST['status']) && array_key_exists($_POST['status'], $status))
					? $_POST['status']
					: users::$infos['user_status'];
				if ($s == -1)
				{
					$status = array(-1 => __('en attente')) + $status;
				}
				$options = '';
				foreach ($status as $k => $v)
				{
					$selected = ($k == $s)
						? ' selected="selected"'
						: '';
					$options .= '<option' . $selected
						. ' value="' . $k . '">' . $v . '</option>';
				}
				return $options;

			// Fuseaux horaires.
			case 'tz' :
				$tz = (isset($_POST['tz']))
					? $_POST['tz']
					: users::$infos['user_tz'];
				return str_replace(
					'value="' . $tz . '"',
					'value="' . utils::tplProtect($tz) . '" selected="selected"',
					file_get_contents(GALLERY_ROOT . '/includes/tz.html')
				);

			// Autres informations.
			case 'desc' :
			case 'email' :
			case 'firstname' :
			case 'loc' :
			case 'name' :
			case 'website' :
				$item = (isset($_POST[$item]))
					? $_POST[$item]
					: users::$infos['user_' . $item];
				return utils::tplProtect($item);
		}
	}
}

/**
 * Méthodes de template pour la page du filigrane utilisateur.
 */
class tplUserSendmail extends tplUser
{
	/**
	 * Retourne l'information $item.
	 *
	 * @param string $item
	 * @return string
	 */
	public function getInfo($item)
	{
		switch ($item)
		{
			case 'email' :
				if (auth::$infos['user_id'] == users::$infos['user_id'])
				{
					return;
				}
				return (isset($_POST['email']))
					? $_POST['email']
					: utils::tplProtect(auth::$infos['user_email']);

			case 'login' :
				return (isset($_POST['name']))
					? $_POST['name']
					: utils::tplProtect(auth::$infos['user_login']);

			case 'message' :
			case 'subject' :
				return (isset($_POST[$item]))
					? $_POST[$item]
					: '';
		}
	}
}

/**
 * Méthodes de template pour la page de gestion des utilisateurs.
 */
class tplUsers extends tplAdmin
{
	/**
	 * Taille de vignette forcée, communiquée par le template.
	 *
	 * @see function nextUser
	 * @var integer
	 */
	private $_thumbForced = 0;



	/**
	 * Retourne la liste des groupes sous forme d'éléments <option>
	 * pour parcours des utilisateurs selon le groupe choisi.
	 *
	 * @return string
	 */
	public function getGroupsBrowse()
	{
		// Filtres de recherche.
		$filter = '';
		if (isset($_GET['search']))
		{
			$filter = '/search/' . $_GET['search'];
		}
		if (isset($_GET['status']))
		{
			$filter = '/' . $_GET['status'];
		}
		$filter = utils::tplProtect($filter);

		// Liste des groupes.
		$selected = (!isset($_GET['group_id']))
			? ' class="selected" selected="selected"'
			: '';
		$groups = '<option' . $selected
			. ' value="' . $filter . '">*' . __('tous') . '</option>';
		if ($this->disUsers())
		{
			foreach (users::$groups as $id => &$i)
			{
				if ($i['nb_users'] < 1)
				{
					continue;
				}
				$selected = (isset($_GET['group_id']) && $_GET['group_id'] == $id)
					? ' class="selected" selected="selected"'
					: '';
				$group_name = ($id > 3 || $i['group_name'] != '')
					? utils::tplProtect(utils::getLocale($i['group_name']))
					: admin::getL10nGroupName($id);
				$groups .= '<option' . $selected
					. ' value="/group/' . (int) $id . $filter . '">'
					. $group_name . '</option>';
			}
		}

		return $groups;
	}

	/**
	 * Retourne la liste des groupes sous forme d'éléments <option>
	 * pour changement de groupe des utilisateurs sélectionnés.
	 *
	 * @return string
	 */
	public function getGroupsChange()
	{
		$groups = '';
		foreach (users::$groups as $id => &$i)
		{
			if ($id < 3
			|| (auth::$infos['user_id'] != 1 && $i['group_admin'] == 1))
			{
				continue;
			}
			$group_name = ($id > 3 || $i['group_name'] != '')
				? utils::tplProtect(utils::getLocale($i['group_name']))
				: admin::getL10nGroupName($id);
			$groups .= '<option value="' . (int) $id . '">' . $group_name . '</option>';
		}

		return $groups;
	}

	/**
	 * Retourne une valeur d'une propriété de la classe users.
	 *
	 * @param array|string $property
	 * @return mixed
	 */
	public function getInfo($property)
	{
		return (is_array($property))
			? utils::tplProtect(users::${$property[0]}[$property[1]])
			: utils::tplProtect(users::${$property});
	}

	/**
	 * L'élément de navigation entre les pages $item doit-il être affiché ?
	 *
	 * @param string $item
	 * @return boolean
	 */
	public function disNavigation($item = '')
	{
		return template::disNavigation($item, users::$nbPages);
	}

	/**
	 * Retourne l'élément de navigation entre les pages $item.
	 *
	 * @param string $item
	 * @return string
	 */
	public function getNavigation($item)
	{
		return template::getNavigation($item, users::$nbPages, admin::$sectionRequest);
	}

	/**
	 * Retourne l'élément des options d'affichage $item.
	 *
	 * @param string $item
	 * @return string
	 */
	public function getOptions($item)
	{
		switch ($item)
		{
			// Ordre de tri.
			case 'orderby' :
				$p = array(
					'ASC' => __('croissant'),
					'DESC' => __('décroissant')
				);
				return $this->_displayOptionsList('users', $item, 'DESC', $p);

			// Nombre d'objets par page.
			case 'nb_per_page':
				return (int) auth::$infos['user_prefs']['users'][$item];

			// Critère de tri.
			case 'sortby' :
				$p = array(
					'login' => __('Nom d\'utilisateur'),
					'crtdt' => __('Date d\'inscription'),
					'lastvstdt' => __('Date de dernière visite')
				);
				return $this->_displayOptionsList('users', $item, 'crtdt', $p);
		}
	}

	/**
	 * Retourne l'information de profil $item.
	 *
	 * @param string $item
	 * @return string
	 */
	public function getProfileInfo($item)
	{
		$v = current(utils::$config['users_profile_infos']['infos']);
		$k = key(utils::$config['users_profile_infos']['infos']);

		switch ($item)
		{
			case 'name' :
				switch ($k)
				{
					case 'birthdate' :
						return __('Date de naissance');

					case 'desc' :
						return __('Description');

					case 'email' :
						return __('Courriel');

					case 'firstname' :
						return __('Prénom');

					case 'loc' :
						return __('Localisation');

					case 'name' :
						return __('Nom');

					case 'sex' :
						return __('Sexe');

					case 'website' :
						return __('Site Web');

					default :
						return '?';
				}

			case 'value' :
				$i = current(users::$items);
				switch ($k)
				{
					case 'birthdate' :
						return $i['user_birthdate']
							? utils::tplProtect(
								utils::localeTime(__('%A %d %B %Y'), $i['user_birthdate'])
							  )
							: '/';

					case 'desc' :
						return $i['user_desc']
							? nl2br(utils::tplProtect(wordwrap($i['user_desc'], 75, "\n")))
							: '/';

					case 'email' :
						return $i['user_email']
							? '<a class="ex" href="mailto:'
								. utils::tplProtect($i['user_email']) . '">'
								. utils::tplProtect($i['user_email']) . '</a>'
							: '/';

					case 'sex' :
						switch ($i['user_sex'])
						{
							case 'F' :
								return __('Femme');

							case 'M' :
								return __('Homme');

							default :
								return __('inconnu');
						}

					case 'website' :
						return $i['user_website']
							? '<a class="ex" href="'
								. utils::tplProtect($i['user_website']) . '">'
								. utils::tplProtect($i['user_website']) . '</a>'
							: '/';

					default :
						return $i['user_' . $k]
							? utils::tplProtect($i['user_' . $k])
							: '/';
				}
		}
	}

	/**
	 * Y a-t-il une prochaine information de profil ?
	 * 
	 * @return boolean
	 */
	public function nextProfileInfo()
	{
		static $next = -1;

		while ($r = template::nextObject(utils::$config['users_profile_infos']['infos'], $next))
		{
			$current = current(utils::$config['users_profile_infos']['infos']);
			$param = key(utils::$config['users_profile_infos']['infos']);
			if ($param == 'email' || $param == 'website' || $current['activate'])
			{
				break;
			}
		}

		return $r;
	}

	/**
	 * Retourne l'information de profil personnalisée $item.
	 *
	 * @param string $item
	 * @return string
	 */
	public function getProfilePerso($item)
	{
		$v = current(utils::$config['users_profile_infos']['perso']);

		switch ($item)
		{
			case 'name' :
				return utils::tplProtect(utils::getLocale($v['name']));

			case 'value' :
				$i = current(users::$items);
				$val = $i['user_other'][key(utils::$config['users_profile_infos']['perso'])];
				return $val
					? utils::tplProtect($val)
					: '/';
		}
	}

	/**
	 * Y a-t-il une prochaine information de profil personnalisée ?
	 * 
	 * @return boolean
	 */
	public function nextProfilePerso()
	{
		static $next = -1;

		while ($r = template::nextObject(utils::$config['users_profile_infos']['perso'], $next))
		{
			$current = current(utils::$config['users_profile_infos']['perso']);
			if ($current['activate'])
			{
				break;
			}
		}

		return $r;
	}

	/**
	 * Retourne l'élément de filtre $item.
	 *
	 * @return string
	 */
	public function getFilter($item)
	{
		switch ($item)
		{
			case 'section_link' :
				return utils::genURL(str_replace('/pending', '', admin::$sectionRequest));
		}
	}

	/**
	 * S'agit-il d'une recherche ?
	 *
	 * @return boolean
	 */
	public function disSearch()
	{
		return users::$searchInit;
	}

	/**
	 * Retourne l'élément de recherche $item.
	 *
	 * @param string $item
	 * @return string
	 */
	public function getSearch($item)
	{
		switch ($item)
		{
			// Options : cases à cocher.
			case 'user_email' :
			case 'user_website' :
			case 'user_crtip' :
			case 'user_lastvstip' :
			case 'date' :
				return (isset($_GET['search_' . $item]))
					? ' checked="checked"'
					: '';

			// Options : cases à cocher avec option cochée par défaut.
			case 'user_login' :
				return (!isset($_GET['search_query']) || isset($_GET['search_' . $item]))
					? ' checked="checked"'
					: '';

			// Champs de recherche pour la date.
			case 'date_field_user_crtdt' :
			case 'date_field_user_lastvstdt' :
				return ((!isset($_GET['search_query']) && $item == 'date_field_user_crtdt')
					|| (isset($_GET['search_date_field'])
					&& $_GET['search_date_field'] == str_replace('date_field_', '', $item)))
					? ' checked="checked"'
					: '';

			// Statut.
			case 'status' :
				$status = array(
					'activate' => __('activé'),
					'deactivate' => __('suspendu'),
					'pending' => __('en attente')
				);
				$selected = (!isset($_GET['search_status'])
					|| !isset($status[$_GET['search_status']]))
					? ' selected="selected"'
					: '';
				$list = '<option' . $selected . ' value="all">*' . __('tous') . '</option>';
				foreach ($status as $value => &$text)
				{
					$selected = (isset($_GET['search_status'])
						&& $_GET['search_status'] == $value)
						? ' selected="selected"'
						: '';
					$list .= '<option' . $selected . ' value="' . $value . '">'
						. $text . '</option>';
				}
				return $list;

			default :
				return $this->_getSearch($item);
		}
	}

	/**
	 * L'élément $item de l'utilisateur doit-il être affiché ?
	 *
	 * @param string $item
	 * @return boolean
	 */
	public function disUser($item)
	{
		$i = current(users::$items);

		switch ($item)
		{
			// L'utilisateur est-il sélectionnable  ?
			case 'selectable' :
				return $i['user_id'] != 1
					&& $i['user_id'] != auth::$infos['user_id']
					&& (auth::$infos['user_id'] == 1
					|| (auth::$infos['user_id'] != 1 && $i['group_admin'] != 1));

			// Informations de profil.
			case 'profile_birthdate' :
			case 'profile_desc' :
			case 'profile_email' :
			case 'profile_firstname' :
			case 'profile_loc' :
			case 'profile_name' :
			case 'profile_sex' :
			case 'profile_website' :
				$item = explode('_', $item);
				return (bool) utils::$config['users_profile_infos']['infos'][$item[1]]['activate'];

			default :
				return $this->_disUser($item, $i);
		}
	}

	/**
	 * Retourne l'information $item de l'utilisateur.
	 *
	 * @param string $item
	 * @return string
	 */
	public function getUser($item)
	{
		$i = current(users::$items);

		switch ($item)
		{
			// Lien vers le profil de l'utilisateur.
			case 'link' :
				$filter = '';
				if (isset($_GET['search']))
				{
					$filter = '/search/' . $_GET['search'];
				}
				if (isset($_GET['status']))
				{
					$filter = '/' . $_GET['status'];
				}
				$group = (isset($_GET['group_id']))
					? '/group/' . $_GET['group_id']
					: '';
				return utils::genURL('user/' . $i['user_id'] . $group . $filter);

			default :
				return $this->_getUser($item, $i);
		}
	}

	/**
	 * Y a-t-il un prochain utilisateur ?
	 * 
	 * @param integer $thumb_size
	 *	Taille des vignettes.
	 * @return boolean
	 */
	public function nextUser($thumb_size)
	{
		static $next = -1;

		$this->_thumbForced =& $thumb_size;

		return template::nextObject(users::$items, $next);
	}

	/**
	 * Existe-il des utilisateurs correspondant aux critères de recherche ?
	 *
	 * @return boolean
	 */
	public function disUsers()
	{
		return users::$nbItems > 0;
	}

	/**
	 * Retourne le nom du groupe courant.
	 *
	 * @return string
	 */
	public function getPosition()
	{
		if (isset($_GET['group_id']) && $this->disUsers())
		{
			return (in_array($_GET['group_id'], array(1, 2, 3))
				&& utils::isEmpty(users::$groups[$_GET['group_id']]['group_name']))
				? admin::getL10nGroupName($_GET['group_id'])
				: utils::tplProtect(utils::getLocale(
					users::$groups[$_GET['group_id']]['group_name']
				));
		}

		return __('tous');
	}
}

/**
 * Méthodes de template pour la page des options utilisateurs.
 */
class tplUsersOptions extends tplAdmin
{
	/**
	 * Retourne le poids maximum des fichiers
	 * autorisé par le serveur en upload.
	 *
	 * @return string
	 */
	public function getUploadMaxFilesize()
	{
		return utils::filesize(utils::uploadMaxFilesize(), 'k');
	}

	/**
	 * Retourne l'option utilisateur $item.
	 *
	 * @param string $item
	 * @return string
	 */
	public function getOption($item)
	{
		switch ($item)
		{
			// Cases à cocher.
			case 'avatars' :
			case 'upload_categories_empty' :
			case 'upload_resize' :
			case 'users_inscription' :
			case 'users_inscription_autocat' :
			case 'users_inscription_by_mail' :
			case 'users_inscription_by_password' :
			case 'users_inscription_moderate' :
			case 'users_only_members' :
			case 'users_log_activity' :
			case 'users_log_activity_delete' :
				return utils::$config[$item]
					? ' checked="checked"'
					: '';

			// Mot de passe.
			case 'users_inscription_password' :
				return (strlen(utils::$config[$item]) == 0)
					? ''
					: '**********';

			// Champs de type 'integer'.
			case 'avatars_maxfilesize' :
			case 'avatars_maxsize' :
			case 'upload_maxfilesize' :
			case 'upload_maxwidth' :
			case 'upload_maxheight' :
			case 'upload_resize_maxwidth' :
			case 'upload_resize_maxheight' :
			case 'upload_resize_quality' :
			case 'users_desc_maxlength' :
			case 'users_log_activity_delete_days' :
				return (int) utils::$config[$item];

			// Textes localisés.
			case 'users_inscription_autocat_title' :
			case 'users_inscription_password_text' :
				return utils::tplProtect(utils::getLocale(
					utils::$config[$item], $this->getLang('code')
				));

			// Listes déroulantes.
			case 'users_locked_albums_access_mode' :
				$options = '';
				$modes = array(
					'groups' => __('permissions de groupes'),
					'password' => __('mot de passe')
				);
				foreach ($modes as $val => $text)
				{
					$selected = (utils::$config[$item] == $val)
						? ' selected="selected"'
						: '';
					$options .= '<option' . $selected . ' value="' . $val . '">'
						. $text . '</option>';
				}
				return $options;
			case 'users_inscription_autocat_category' :
				return template::mapSelect(albums::$mapCategories, array(
					'class_selected' => TRUE,
					'ignore_albums' => TRUE,
					'selected' => (int) utils::$config[$item]
				));
			case 'users_inscription_autocat_type' :
				$options = '';
				$modes = array(
					'album' => __('album'),
					'category' => __('catégorie')
				);
				foreach ($modes as $val => $text)
				{
					$selected = (utils::$config[$item] == $val)
						? ' selected="selected"'
						: '';
					$options .= '<option' . $selected . ' value="' . $val . '">'
						. $text . '</option>';
				}
				return $options;
		}
	}

	/**
	 * Retourne l'information de profil $item.
	 *
	 * @param string $item
	 * @return string
	 */
	public function getProfileInfo($item)
	{
		$i = current(utils::$config['users_profile_infos']['infos']);
		$id = key(utils::$config['users_profile_infos']['infos']);

		switch ($item)
		{
			case 'activate' :
			case 'required' :
				return $i[$item]
					? ' checked="checked"'
					: '';

			case 'name' :
				switch ($id)
				{
					case 'birthdate' :
						return __('Date de naissance');

					case 'desc' :
						return __('Description');

					case 'email' :
						return __('Courriel');

					case 'firstname' :
						return __('Prénom');

					case 'loc' :
						return __('Localisation');

					case 'name' :
						return __('Nom');

					case 'sex' :
						return __('Sexe');

					case 'website' :
						return __('Site Web');

					default :
						return '?';
				}

			case 'id' :
				return utils::tplProtect($id);
		}
	}

	/**
	 * Exiete-t-il des informations de profil personnalisée ?
	 *
	 * @return boolean
	 */
	public function disProfilePerso()
	{
		return !empty(utils::$config['users_profile_infos']['infos']);
	}

	/**
	 * Retourne l'information de profil personnalisée $item.
	 *
	 * @param string $item
	 * @return string
	 */
	public function getProfilePerso($item)
	{
		$i = current(utils::$config['users_profile_infos']['perso']);
		$id = key(utils::$config['users_profile_infos']['perso']);

		switch ($item)
		{
			case 'activate' :
			case 'required' :
				return $i[$item]
					? ' checked="checked"'
					: '';

			case 'id' :
				return utils::tplProtect($id);

			case 'name' :
				return utils::tplProtect(utils::getLocale($i['name'], $this->getLang('code')));
		}
	}

	/**
	 * Y a-t-il une prochaine information de profil ?
	 * 
	 * @return boolean
	 */
	public function nextProfileInfo()
	{
		static $next = -1;

		return template::nextObject(utils::$config['users_profile_infos']['infos'], $next);
	}

	/**
	 * Y a-t-il une prochaine information de profil personnalisée ?
	 * 
	 * @return boolean
	 */
	public function nextProfilePerso()
	{
		static $next = -1;

		return template::nextObject(utils::$config['users_profile_infos']['perso'], $next);
	}
}

/**
 * Méthodes de template pour la page "envoyer un message".
 */
class tplUsersSendmail extends tplAdmin
{
	/**
	 * Retourne la liste des groupes.
	 *
	 * @return string
	 */
	public function getGroups()
	{
		$options = '';
		foreach (users::$groups as $group_id => &$infos)
		{
			$selected = (isset($_POST['groups_list'])
				&& in_array($group_id, $_POST['groups_list']))
				? ' selected="selected"'
				: '';
			$group_name = (utils::isEmpty($infos['group_name']))
				? admin::getL10nGroupName($group_id)
				: utils::tplProtect(utils::getLocale($infos['group_name'], utils::$userLang));
			$options .= '<option' . $selected . ' value="' . (int) $group_id . '">'
				. $group_name . '</option>';
		}
		return $options;
	}

	/**
	 * Retourne l'information $item.
	 *
	 * @param string $item
	 * @return string
	 */
	public function getInfo($item)
	{
		switch ($item)
		{
			case 'email' :
				return (isset($_POST['email']))
					? $_POST['email']
					: utils::tplProtect(auth::$infos['user_email']);

			case 'login' :
				return (isset($_POST['name']))
					? $_POST['name']
					: utils::tplProtect(auth::$infos['user_login']);

			case 'message' :
			case 'subject' :
				return (isset($_POST[$item]))
					? $_POST[$item]
					: '';

			case 'users_all' :
			case 'users_activate' :
			case 'users_deactivate' :
			case 'users_pending' :
			case 'users_select' :
			case 'groups_select' :
				return (isset($_POST[$item]))
					? ' checked="checked"'
					: '';
		}
	}

	/**
	 * Retourne la liste des utilisateurs.
	 *
	 * @return string
	 */
	public function getUsers()
	{
		$options = '';
		foreach (users::$usersList as $user_id => &$infos)
		{
			$selected = (isset($_POST['users_list'])
				&& in_array($user_id, $_POST['users_list']))
				? ' selected="selected"'
				: '';
			$options .= '<option' . $selected . ' value="' . (int) $user_id . '">'
				. utils::tplProtect($infos['user_login']) . ' ('
				. utils::tplProtect($infos['user_email']) . ')</option>';
		}
		return $options;
	}
}

/**
 * Méthodes de template pour la page "activité des utilisateurs".
 */
class tplUsersStats extends tplAdmin
{
	/**
	 * L'élément d'utilisateur $item doit-il être affiché ?
	 *
	 * @param string $item
	 * @return string
	 */
	public function disUser($item)
	{
		$i = current(admin::$infos);

		switch ($item)
		{
			case 'admin' :
				return $i['group_admin'] == '1';

			case 'superadmin' :
				return $i['user_id'] == '1';
		}
	}

	/**
	 * Retourne l'élément d'utilisateur $item.
	 *
	 * @param string $item
	 * @return string
	 */
	public function getUser($item)
	{
		$i = current(admin::$infos);

		switch ($item)
		{
			case 'link' :
				return utils::genURL('user/' . $i['user_id']);

			// Nom d'utilisateur.
			case 'login' :
				return utils::tplProtect($i['user_login']);

			case 'nb_images' :
			case 'nb_comments' :
			case 'nb_votes' :
			case 'nb_favorites' :
				return (int) $i[$item];

			// Statut.
			case 'status' :
				switch ($i['user_status'])
				{
					case '-1' :
						return __('en attente');
					case '0' :
						return __('suspendu');
					case '1' :
						return __('activé');
				}
		}
	}

	/**
	 * Y a-t-il un prochain utilisateur ?
	 * 
	 * @return boolean
	 */
	public function nextUser()
	{
		static $next = -1;

		return template::nextObject(admin::$infos, $next);
	}
}

/**
 * Méthodes de template pour la page de gestion des votes.
 */
class tplVotes extends tplAdmin
{
	/**
	 * Taille de vignette forcée, communiquée par le template.
	 *
	 * @see function nextVote
	 * @var integer
	 */
	private $_thumbForced = 0;



	/**
	 * Doit-on afficher la liste des images votées ?
	 * 
	 * @return string
	 */
	public function disImagesList()
	{
		return votes::$images;
	}

	/**
	 * Retourne la liste des images votées.
	 * 
	 * @return string
	 */
	public function getImagesList()
	{
		// Critères de recherche.
		$v = '';
		foreach (array('date', 'ip', 'user') as $c)
		{
			if (isset($_GET[$c]))
			{
				$v .= '/' . $c . '/' . utils::tplProtect($_GET[$c]);
			}
		}

		$images = '<option class="all" value="votes/category/'
			. (int) votes::$objectInfos['cat_id'] . $v
			. '">*' . __('toutes') . '</option>';

		foreach (votes::$images as &$i)
		{
			$selected = ($_GET['object_id'] == $i['image_id'])
				? ' class="selected" selected="selected"'
				: '';
			$images .= '<option' . $selected . ' value="votes/image/'
				. (int) $i['image_id'] . $v . '">'
				. utils::tplProtect(utils::getLocale($i['image_name'])). '</option>';
		}

		return $images;
	}

	/**
	 * Retourne une valeur d'une propriété de la classe votes.
	 *
	 * @param array|string $property
	 * @return mixed
	 */
	public function getInfo($property)
	{
		return (is_array($property))
			? utils::tplProtect(votes::${$property[0]}[$property[1]])
			: utils::tplProtect(votes::${$property});
	}

	/**
	 * Retourne la liste des catégories votées.
	 *
	 * @return string
	 */
	public function getMap()
	{
		// Critères de recherche.
		$v = '';
		foreach (array('date', 'ip', 'search', 'user') as $c)
		{
			if (isset($_GET[$c]))
			{
				$v .= '/' . $c . '/' . utils::tplProtect($_GET[$c]);
			}
		}

		return template::mapSelect(albums::$mapCategories, array(
			'class_selected' => TRUE,
			'selected' => (isset($_GET['object_id']))
				? ($_GET['object_type'] == 'category')
					? $_GET['object_id']
					: votes::$objectInfos['cat_id']
				: 1,
			'value_tpl' => 'votes/category/{ID}' . $v
		));
	}

	/**
	 * L'élément de navigation entre les pages $item doit-il être affiché ?
	 *
	 * @param string $item
	 * @return boolean
	 */
	public function disNavigation($item = '')
	{
		return template::disNavigation($item, votes::$nbPages);
	}

	/**
	 * Retourne l'élément de navigation entre les pages $item.
	 *
	 * @param string $item
	 * @return string
	 */
	public function getNavigation($item)
	{
		return template::getNavigation($item, votes::$nbPages, admin::$sectionRequest);
	}

	/**
	 * Retourne l'élément des options d'affichage $item.
	 *
	 * @param string $item
	 * @return string
	 */
	public function getOptions($item)
	{
		switch ($item)
		{
			// Ordre de tri.
			case 'orderby' :
				$p = array(
					'ASC' => __('croissant'),
					'DESC' => __('décroissant')
				);
				return $this->_displayOptionsList('votes', $item, 'DESC', $p);

			// Nombre d'objets par page.
			case 'nb_per_page':
				return (int) auth::$infos['user_prefs']['votes'][$item];

			// Critère de tri.
			case 'sortby' :
				$p = array(
					'date' => __('date'),
					'rate' => __('note')
				);
				return $this->_displayOptionsList('votes', $item, 'date', $p);
		}
	}

	/**
	 * Doit-on afficher l'élément de barre de position $item ?
	 *
	 * @return boolean
	 */
	public function disPosition($item)
	{
		switch ($item)
		{
			// Barre de position pour les filtres ?
			case 'filter' :
				return isset($_GET['date'])
					|| isset($_GET['ip'])
					|| isset($_GET['user_id']);

			// Barre de position normale ?
			case 'normal' :
				return !$this->disPosition('filter')
					&& !$this->disPosition('search');

			// Barre de position pour la recherche ?
			case 'search' :
				return isset($_GET['search']);
		}
	}

	/**
	 * Retourne les liens de la barre de position (fil d'ariane).
	 *
	 * @return string
	 */
	public function getPosition()
	{
		// Image.
		if (isset($_GET['object_type']) && $_GET['object_type'] == 'image')
		{
			$position = template::getPosition(
				'',
				'votes/category',
				'votes/image',
				'votes/category',
				TRUE,
				FALSE,
				__('galerie'),
				albums::$parents,
				albums::$infos,
				NULL,
				' / ',
				TRUE
			);
		}

		// Catégorie.
		else
		{
			$position = template::getPosition(
				'',
				'votes/category',
				'votes/category',
				'',
				TRUE,
				FALSE,
				__('galerie'),
				albums::$parents,
				albums::$infos,
				NULL,
				' / ',
				isset($_GET['object_id']) && $_GET['object_id'] > 1
			);
		}

		// Filtres de recherche.
		$filters = array(
			'date' => 'date/',
			'ip' => 'ip/',
			'search' => 'search/',
			'user_id' => 'user/'
		);
		foreach ($filters as $f => $u)
		{
			if (isset($_GET[$f]))
			{
				$position = preg_replace('`(\?q=votes/(?:category|image)/\d+)`',
					'$1/' . $u . $_GET[$f], $position);
			}
		}

		return $position;
	}

	/**
	 * Retourne l'élément de filtre $item.
	 *
	 * @return string
	 */
	public function getFilter($item)
	{
		switch ($item)
		{
			case 'section_link' :
				return utils::genURL(
					preg_replace(
						'`/(?:date|ip|user)/.+$`',
						'',
						admin::$sectionRequest)
				);

			case 'text' :
				if (isset($_GET['date']))
				{
					return __('Votes à la date du %s');
				}
				if (isset($_GET['ip']))
				{
					return __('Votes en provenance de l\'IP %s');
				}
				if (isset($_GET['user_id']))
				{
					return __('Votes de l\'utilisateur %s');
				}
				break;

			case 'value' :
				if (isset($_GET['date']))
				{
					return utils::localeTime(__('%A %d %B %Y'), $_GET['date']);
				}
				if (isset($_GET['ip']))
				{
					return utils::tplProtect($_GET['ip']);
				}
				if (isset($_GET['user_id']))
				{
					$link = utils::genURL('user/' . $_GET['user_id']);
					$login = utils::tplProtect(users::$usersList[$_GET['user_id']]);
					return '<a href="' . $link . '">' . $login . '</a>';
				}
				break;
		}
	}

	/**
	 * Y a-t-il des votes ?
	 *
	 * @return boolean
	 */
	public function disVotes()
	{
		return !empty(votes::$items);
	}

	/**
	 * Doit-on afficher l'élément de vote $item ?
	 *
	 * @param string $item
	 * @return string
	 */
	public function disVote($item)
	{
		$i = current(votes::$items);

		switch ($item)
		{
			case 'guest' :
				return $i['user_id'] == 2;
		}
	}

	/**
	 * Retourne l'élément de vote $item.
	 *
	 * @param string $item
	 * @return string
	 */
	public function getVote($item)
	{
		$i = current(votes::$items);

		switch ($item)
		{
			// Lien vers la page d'édition de l'album.
			case 'album_edit_link' :
				return utils::genURL('album/' . $i['cat_id']);

			// Identifiant de l'album.
			case 'album_id' :
				return (int) $i['cat_id'];

			// Nom de l'album.
			case 'album_name' :
				return utils::tplProtect(utils::getLocale($i['cat_name']));

			// Date du vote.
			case 'date' :
				return '<span title="'
					. utils::tplProtect(utils::localeTime(__('%A %d %B %Y'), $i['vote_date']))
					. '">'
					. utils::tplProtect(utils::localeTime(__('%d/%m/%Y'), $i['vote_date']))
					. '</span>';

			// Identifiant du vote.
			case 'id' :
				return (int) $i['vote_id'];

			// Lien vers la page d'édition de l'image.
			case 'image_edit_link' :
				return utils::genURL('image/' . $i['image_id']);

			// Nom de l'image.
			case 'image_name' :
				return utils::tplProtect(utils::getLocale($i['image_name']));

			// Adresse IP de l'utilisateur.
			case 'ip' :
				return utils::tplProtect($i['vote_ip']);

			// Note.
			case 'rate' :
				return (int) $i['vote_rate'];

			case 'rate_visual' :
				return template::visualRate(
					$i['vote_rate'],
					$this->getAdmin('style_path'),
					'-small'
				);

			// Paramètres de la vignette.
			case 'thumb_center' :
			case 'thumb_size' :
			case 'thumb_src' :
				return $this->_getThumbImage($item, $i, $this->_thumbForced);

			// Heure du vote.
			case 'time' :
				return utils::tplProtect(
					utils::localeTime(__('%H:%M:%S'), $i['vote_date'])
				);

			// Lien vers la page d'édition du profile de l'utilisateur.
			case 'user_edit_link' :
				return utils::genURL('user/' . $i['user_id']);

			// Identifiant de l'utilisateur.
			case 'user_id' :
				return (int) $i['user_id'];

			// Nom de l'utilisateur.
			case 'user_name' :
				return ($i['user_id'] != 2)
					? utils::tplProtect($i['user_login'])
					: __('invité');

			// Lien vers l'affichage des votes de l'album.
			case 'vote_album_link' :
				return utils::genURL('votes/category/' . $i['cat_id']);

			// Lien vers l'affichage des votes de la date.
			case 'vote_date_link' :
				$date = explode(' ', $i['vote_date']);
				return utils::genURL('votes/date/' . $date[0]);

			// Lien vers l'affichage des votes de l'image.
			case 'vote_image_link' :
				return utils::genURL('votes/image/' . $i['image_id']);

			// Lien vers l'affichage des votes de l'IP.
			case 'vote_ip_link' :
				return utils::genURL('votes/ip/' . $i['vote_ip']);

			// Lien vers l'affichage des votes de l'utilisateur.
			case 'vote_user_link' :
				return utils::genURL('votes/user/' . $i['user_id']);
		}
	}

	/**
	 * Y a-t-il un prochain vote ?
	 * 
	 * @param integer $thumb_size
	 *	Taille des vignettes.
	 * @return boolean
	 */
	public function nextVote($thumb_size)
	{
		static $next = -1;

		$this->_thumbForced =& $thumb_size;

		return template::nextObject(votes::$items, $next);
	}

	/**
	 * Retourne l'élément de recherche $item.
	 *
	 * @param string $item
	 * @return string
	 */
	public function getSearch($item)
	{
		switch ($item)
		{
			// Options : cases à cocher.
			case 'date' :
				return (isset($_GET['search_' . $item]))
					? ' checked="checked"'
					: '';

			// Options : cases à cocher avec option cochée par défaut.
			case 'vote_ip' :
				return (!isset($_GET['search_query']) || isset($_GET['search_' . $item]))
					? ' checked="checked"'
					: '';

			// Champs de recherche pour la date.
			case 'date_field_com_crtdt' :
			case 'date_field_com_lastupddt' :
				return ((!isset($_GET['search_query']) && $item == 'date_field_com_crtdt')
					|| (isset($_GET['search_date_field'])
					&& $_GET['search_date_field'] == str_replace('date_field_', '', $item)))
					? ' checked="checked"'
					: '';

			// Recherche par note.
			case 'rate' :
				$selected = (!isset($_GET['search_rate'])
					|| !preg_match('`^[1-5]$`', $_GET['search_rate']))
					? ' selected="selected"'
					: '';
				$list = '<option' . $selected . ' value="all">*' . __('toutes') . '</option>';
				for ($i = 1; $i <= 5; $i++)
				{
					$selected = (isset($_GET['search_rate']) && $_GET['search_rate'] == $i)
						? ' selected="selected"'
						: '';
					$list .= '<option' . $selected . ' value="' . $i . '">' . $i . '</option>';
				}
				return $list;

			default :
				return $this->_getSearch($item);
		}
	}
}

/**
 * Méthodes de template pour la gestion du widget 'géolocalisation'.
 */
class tplWidgetGeoloc extends tplAdmin
{
	/**
	 * Retourne l'élément de widget $item.
	 *
	 * @param string $item
	 * @return string
	 */
	public function getWidgetGeoloc($item)
	{
		$i = utils::$config['widgets_params']['geoloc'];

		switch ($item)
		{
			case 'title' :
				return utils::tplProtect(
					utils::getLocale($i['title'], $this->getLang('code'))
				);

			case 'title_default' :
				return __('Géolocalisation');
		}
	}
}

/**
 * Méthodes de template pour la gestion du widget 'image'.
 */
class tplWidgetImage extends tplAdmin
{
	/**
	 * L'élément de widget $item doit-il être affiché ?
	 *
	 * @param string $item
	 * @return string
	 */
	public function disWidgetImage($item)
	{
		switch ($item)
		{
			case 'fixed' :
			case 'last' :
			case 'random' :
				return utils::$config['widgets_params']['image']['params']['mode'] == $item;
		}
	}

	/**
	 * Retourne l'élément du widget $item.
	 *
	 * @param string $item
	 * @return string
	 */
	public function getWidgetImage($item)
	{
		$i = utils::$config['widgets_params']['image'];

		switch ($item)
		{
			case 'albums' :
			case 'images' :
				return utils::tplProtect(implode(',', $i['params'][$item]));

			case 'nb_images' :
				return (int) $i['params']['nb_thumbs'];

			case 'title' :
				return utils::tplProtect(
					utils::getLocale($i['title'], $this->getLang('code'))
				);

			case 'thumbs_wid_height' :
			case 'thumbs_wid_quality' :
			case 'thumbs_wid_size' :
			case 'thumbs_wid_width' :
				return (int) widgets::$configValues['CONF_' . strtoupper($item)];

			case 'thumbs_wid_method_crop' :
			case 'thumbs_wid_method_prop' :
				return widgets::$configValues['CONF_' . strtoupper(substr($item, 0, -5))]
					== substr($item, -4, 4)
					? ' checked="checked"'
					: '';

			case 'title_default' :
				return __('Bloc image');

		}
	}
}

/**
 * Méthodes de template pour la gestion du widget 'liens externes'.
 */
class tplWidgetLinks extends tplAdmin
{
	/**
	 * L'élément de lien $item doit-il être affiché ?
	 *
	 * @param string $item
	 * @return string
	 */
	public function disWidgetLink($item)
	{
		$i = current(utils::$config['widgets_params']['links']['items']);

		switch ($item)
		{
			case 'activate' :
				return (bool) $i['activate'];
		}
	}

	/**
	 * Retourne l'élément de lien $item.
	 *
	 * @param string $item
	 * @return string
	 */
	public function getWidgetLink($item)
	{
		$i = current(utils::$config['widgets_params']['links']['items']);

		switch ($item)
		{
			case 'desc' :
				return utils::tplProtect(utils::getLocale($i['desc']));

			case 'desc_lang' :
				return utils::tplProtect(
					utils::getLocale($i['desc'], $this->getLang('code'))
				);

			case 'id' :
				return (int) key(utils::$config['widgets_params']['links']['items']);

			case 'title' :
				return utils::tplProtect(utils::getLocale($i['title']));

			case 'title_lang' :
				return utils::tplProtect(
					utils::getLocale($i['title'], $this->getLang('code'))
				);

			case 'url' :
				return utils::tplProtect($i['url']);
		}
	}

	/**
	 * Y a-t-il un prochain lien ?
	 *
	 * @return boolean
	 */
	public function nextWidgetLink()
	{
		static $next = -1;

		return template::nextObject(utils::$config['widgets_params']['links']['items'], $next);
	}

	/**
	 * Retourne l'élément du widget $item.
	 *
	 * @param string $item
	 * @return string
	 */
	public function getWidgetLinks($item)
	{
		$i = utils::$config['widgets_params']['links'];

		switch ($item)
		{
			case 'title' :
				return utils::tplProtect(
					utils::getLocale($i['title'], $this->getLang('code'))
				);

			case 'title_default' :
				return __('Liens externes');
		}
	}
}

/**
 * Méthodes de template pour la gestion du widget 'navigation'.
 */
class tplWidgetNavigation extends tplAdmin
{
	/**
	 * L'élément de widget $item doit-il être affiché ?
	 *
	 * @param string $item
	 * @return string
	 */
	public function disWidgetNavigation($item)
	{
		switch ($item)
		{
			case 'categories' :
			case 'neighbours' :
			case 'search' :
				return (bool) utils::$config['widgets_params']
					['navigation']['items'][$item];
		}
	}

	/**
	 * Retourne l'élément de widget $item.
	 *
	 * @param string $item
	 * @return string
	 */
	public function getWidgetNavigation($item)
	{
		$i = utils::$config['widgets_params']['navigation'];

		switch ($item)
		{
			case 'title' :
				return utils::tplProtect(
					utils::getLocale($i['title'], $this->getLang('code'))
				);

			case 'title_default' :
				return __('Navigation');
		}
	}
}

/**
 * Méthodes de template pour la gestion du widget 'qui est en ligne ?'.
 */
class tplWidgetOnlineUsers extends tplAdmin
{
	/**
	 * Retourne l'élément de widget $item.
	 *
	 * @param string $item
	 * @return string
	 */
	public function getWidgetOnlineUsers($item)
	{
		$i = utils::$config['widgets_params']['online_users'];

		switch ($item)
		{
			case 'duration' :
				return (int) $i['params']['duration'];

			case 'title' :
				return utils::tplProtect(
					utils::getLocale($i['title'], $this->getLang('code'))
				);

			case 'title_default' :
				return __('Utilisateurs en ligne');
		}
	}
}

/**
 * Méthodes de template pour la gestion du widget 'options d'affichage'.
 */
class tplWidgetOptions extends tplAdmin
{
	/**
	 * L'élément de widget $item doit-il être affiché ?
	 *
	 * @param string $item
	 * @return string
	 */
	public function disWidgetOptions($item)
	{
		switch ($item)
		{
			case 'image_size' :
			case 'nb_thumbs' :
			case 'order_by' :
			case 'recent' :
			case 'styles' :
			case 'thumbs_albums' :
			case 'thumbs_category_title' :
			case 'thumbs_comments' :
			case 'thumbs_date' :
			case 'thumbs_filesize' :
			case 'thumbs_hits' :
			case 'thumbs_image_title' :
			case 'thumbs_images' :
			case 'thumbs_size' :
			case 'thumbs_votes' :
				return (bool) utils::$config['widgets_params']
					['options']['items'][$item];
		}
	}

	/**
	 * Retourne l'élément de widget $item.
	 *
	 * @param string $item
	 * @return string
	 */
	public function getWidgetOptions($item)
	{
		$i = utils::$config['widgets_params']['options'];

		switch ($item)
		{
			case 'title' :
				return utils::tplProtect(
					utils::getLocale($i['title'], $this->getLang('code'))
				);

			case 'title_default' :
				return __('Options d\'affichage');
		}
	}
}

/**
 * Méthodes de template pour la gestion des widgets personnalisés.
 */
class tplWidgetPerso extends tplAdmin
{
	/**
	 * Y a-t-il des fichiers de contenu pour widget ?
	 *
	 * @return boolean
	 */
	public function disContentFiles()
	{
		foreach (scandir(GALLERY_ROOT . '/files/widgets/') as $filename)
		{
			if (preg_match('`^[-a-z0-9_]{1,64}\.php$`', $filename))
			{
				return TRUE;
			}
		}
	}

	/**
	 * Retourne la liste des fichiers de contenu pour widget.
	 *
	 * @return string
	 */
	public function getContentFiles()
	{
		$options = '';
		foreach (scandir(GALLERY_ROOT . '/files/widgets/') as $filename)
		{
			if (preg_match('`^[-a-z0-9_]{1,64}\.php$`', $filename))
			{
				$f = utils::tplProtect($filename);
				$selected = '';
				if ($_GET['section'] = 'widget'
				&& isset($_GET['perso'])
				&& isset(utils::$config['widgets_params']
				['perso_' . $_GET['perso']]['file'])
				&& utils::$config['widgets_params']
				['perso_' . $_GET['perso']]['file'] == $f)
				{
					$selected = ' selected="selected"';
				}
				$options .= '<option' . $selected . ' value="' . $f . '">' . $f . '</option>';
			}
		}
		return $options;
	}

	/**
	 * Retourne l'élément de widget personnalisé $item.
	 *
	 * @param string $item
	 * @return string
	 */
	public function getWidgetPerso($item)
	{
		switch ($item)
		{
			// Nombre maximum de caractères du contenu.
			case 'content_maxlength' :
				return (int) utils::$config['widgets_content_maxlength'];
		}

		if ($_GET['section'] == 'new-widget')
		{
			return;
		}

		switch ($item)
		{
			// Texte HTML de contenu.
			case 'text' :
				return utils::tplProtect(utils::getLocale(
					utils::$config['widgets_params']['perso_' . $_GET['perso']]['text'],
					$this->getLang('code')
				));

			// Fichier de contenu.
			case 'file' :
				return (utils::$config['widgets_params']
					['perso_' . $_GET['perso']]['type'] == 'file')
					? ' checked="checked"'
					: '';

			// Attribut "action" du formulaire.
			case 'form_action' :
				return utils::genURL(str_replace('/new', '', admin::$sectionRequest));

			// Identifiant.
			case 'id' :
				return (int) $_GET['perso'];

			// Titer du widget.
			case 'title' :
				return utils::tplProtect(utils::getLocale(
					utils::$config['widgets_params']['perso_' . $_GET['perso']]['title']
				));

			// Titer du widget localisé.
			case 'title_lang' :
				return utils::tplProtect(utils::getLocale(
					utils::$config['widgets_params']['perso_' . $_GET['perso']]['title'],
					$this->getLang('code')
				));
		}
	}
}

/**
 * Méthodes de template pour la gestion du widget 'statistiques des catégories'.
 */
class tplWidgetStatsCategories extends tplAdmin
{
	/**
	 * L'élément de widget $item doit-il être affiché ?
	 *
	 * @param string $item
	 * @return string
	 */
	public function disWidgetStatsCategories($item)
	{
		switch ($item)
		{
			case 'images' :
			case 'albums' :
			case 'filesize' :
			case 'recents' :
			case 'hits' :
			case 'comments' :
			case 'votes' :
				return (bool) utils::$config['widgets_params']
					['stats_categories']['items'][$item];
		}
	}

	/**
	 * Retourne l'élément de widget $item.
	 *
	 * @param string $item
	 * @return string
	 */
	public function getWidgetStatsCategories($item)
	{
		$i = utils::$config['widgets_params']['stats_categories'];

		switch ($item)
		{
			case 'title' :
				return utils::tplProtect(
					utils::getLocale($i['title'], $this->getLang('code'))
				);

			case 'title_default' :
				return __('Statistiques des catégories');
		}
	}
}

/**
 * Méthodes de template pour la gestion du widget 'statistiques des images'.
 */
class tplWidgetStatsImages extends tplAdmin
{
	/**
	 * L'élément de widget $item doit-il être affiché ?
	 *
	 * @param string $item
	 * @return string
	 */
	public function disWidgetStatsImages($item)
	{
		switch ($item)
		{
			case 'added_by' :
			case 'added_date' :
			case 'comments' :
			case 'created_date' :
			case 'favorites' :
			case 'filesize' :
			case 'hits' :
			case 'size' :
			case 'votes' :
				return (bool) utils::$config['widgets_params']
					['stats_images']['items'][$item];
		}
	}

	/**
	 * Retourne l'élément de widget $item.
	 *
	 * @param string $item
	 * @return string
	 */
	public function getWidgetStatsImages($item)
	{
		$i = utils::$config['widgets_params']['stats_images'];

		switch ($item)
		{
			case 'title' :
				return utils::tplProtect(
					utils::getLocale($i['title'], $this->getLang('code'))
				);

			case 'title_default' :
				return __('Statistiques des images');
		}
	}
}

/**
 * Méthodes de template pour la gestion du widget 'tags'.
 */
class tplWidgetTags extends tplAdmin
{
	/**
	 * Retourne l'élément de widget $item.
	 *
	 * @param string $item
	 * @return string
	 */
	public function getWidgetTags($item)
	{
		$i = utils::$config['widgets_params']['tags'];

		switch ($item)
		{
			case 'max_tags' :
				return (int) $i['params']['max_tags'];

			case 'title' :
				return utils::tplProtect(
					utils::getLocale($i['title'], $this->getLang('code'))
				);

			case 'title_default' :
				return __('Tags');
		}
	}
}

/**
 * Méthodes de template pour la gestion du widget 'console utilisateur'.
 */
class tplWidgetUser extends tplAdmin
{
	/**
	 * Retourne l'élément de widget $item.
	 *
	 * @param string $item
	 * @return string
	 */
	public function getWidgetUser($item)
	{
		$i = utils::$config['widgets_params']['user'];

		switch ($item)
		{
			case 'title' :
				return utils::tplProtect(
					utils::getLocale($i['title'], $this->getLang('code'))
				);

			case 'title_default' :
				return __('Console utilisateur');
		}
	}
}

/**
 * Méthodes de template pour les options du filigrane de catégorie.
 */
class tplWatermarkCategory extends tplAlbums
{
}

/**
 * Méthodes de template pour la gestion des widgets.
 */
class tplWidgets extends tplAdmin
{
	/**
	 * L'élément de widget $item doit-il être affiché ?
	 *
	 * @param string $item
	 * @return string
	 */
	public function disWidget($item)
	{
		$i = current(utils::$config['widgets_order']);

		switch ($item)
		{
			// Widget non disponible ?
			case 'disabled' :
				switch ($i)
				{
					case 'user' :
						return isset(admin::$tplDisabledConfig['users'])
							&& admin::$tplDisabledConfig['users'] == 0;

					default :
						if ($this->disWidget('perso'))
						{
							return isset(admin::$tplDisabledConfig['widgets_perso'])
								&& admin::$tplDisabledConfig['widgets_perso'] == 0;
						}
						else
						{
							return isset(admin::$tplDisabledConfig
									['widgets_params'][$i]['status'])
								&& admin::$tplDisabledConfig
									['widgets_params'][$i]['status'] == 0;
						}
				}

			// Widget fixe ?
			case 'fixed' :
				return isset(admin::$tplDisabledConfig['widgets_fixed'])
					&& in_array($i, admin::$tplDisabledConfig['widgets_fixed']);

			// Widget personnalisé ?
			case 'perso' :
				return isset(utils::$config['widgets_params'][$i]['type']);

			// Statut.
			case 'status' :
				return (bool) utils::$config['widgets_params'][$i]['status'];
		}
	}

	/**
	 * Retourne l'élément de widget $item.
	 *
	 * @param string $item
	 * @return string
	 */
	public function getWidget($item)
	{
		$i = current(utils::$config['widgets_order']);

		switch ($item)
		{
			// Identifiant.
			case 'id' :
				return (int) key(utils::$config['widgets_order']);

			// Lien vers la page d'édition du widget.
			case 'link' :
				$i = (substr($i, 0, 6) == 'perso_')
					? str_replace('_', '/', $i)
					: str_replace('_', '-', $i);
				return utils::genURL('widget/' . $i);

			// Nom du widget.
			case 'name' :
				return utils::tplProtect($i);

			// Titre du widget.
			case 'title' :
				switch ($i)
				{
					case 'geoloc' :
						return __('Géolocalisation');

					case 'image' :
						return __('Bloc image');

					case 'links' :
						return __('Liens externes');

					case 'navigation' :
						return __('Navigation');

					case 'online_users' :
						return __('Utilisateurs en ligne');

					case 'options' :
						return __('Options d\'affichage');

					case 'stats_categories' :
						return __('Statistiques des catégories');

					case 'stats_images' :
						return __('Statistiques des images');

					case 'tags' :
						return __('Tags');

					case 'user' :
						return __('Console utilisateur');

					default :
						return utils::tplProtect(utils::getLocale(
							utils::$config['widgets_params'][$i]['title']
						));
				}
		}
	}

	/**
	 * Y a-t-il un prochain widget ?
	 *
	 * @return boolean
	 */
	public function nextWidget()
	{
		static $next = -1;

		return template::nextObject(utils::$config['widgets_order'], $next);
	}
}
?>