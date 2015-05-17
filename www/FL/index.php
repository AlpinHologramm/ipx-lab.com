<?php
/**
 * Traitement de toutes les requêtes de la galerie.
 *
 * @license http://www.gnu.org/licenses/gpl.html
 * @link http://www.igalerie.org/
 */

require_once(dirname(__FILE__) . '/includes/prepend.php');

// Si la galerie n'est pas installée, on redirige vers le script d'installation.
if (!CONF_INSTALL)
{
	header('Location: ./install/');
	die;
}

// On redirige vers la bonne URL si l'URL demandée
// ne correspond pas à l'option sur l'URL rewriting choisie.
if (isset($_GET['q']) && isset($_SERVER['REQUEST_URI']))
{
	$gallery_base_url = (CONF_URL_REWRITE)
		? utils::genURL('')
		: substr(utils::genURL('1'), 0, -1);
	if (urlencode(urldecode($_SERVER['REQUEST_URI']))
	!== urlencode($gallery_base_url . $_GET['q']))
	{
		utils::redirect($_GET['q'], FALSE, 301);
	}
}

$q = !empty($_GET['q']);

// Extraction de la requête.
extract_request(array(
	'album' => array
	(
		'(\d{1,11})-[^/]{1,255}(?:/(pass))?' =>
			array('object_id', 'section'),
		'(\d{1,11})-[^/]{1,255}/page/(\d{1,11})(?:/(pass))?' =>
			array('object_id', 'page', 'section')
	),
	'avatar' => array(),
	'basket' => array
	(
		'(?:page/(\d{1,11}))?' =>
			array('page')
	),
	'camera-brand' => array
	(
		'(\d{1,11})-[^/]{1,255}(?:/page/(\d{1,11}))?' =>
			array('camera_id', 'page'),
		'(\d{1,11})-[^/]{1,255}/(album|category)/(\d{1,11})-[^/]{1,255}(?:/page/(\d{1,11}))?' =>
			array('camera_id', 'section_b', 'cat_id', 'page')
	),
	'camera-model' => array
	(
		'(\d{1,11})-[^/]{1,255}(?:/page/(\d{1,11}))?' =>
			array('camera_id', 'page'),
		'(\d{1,11})-[^/]{1,255}/(album|category)/(\d{1,11})-[^/]{1,255}(?:/page/(\d{1,11}))?' =>
			array('camera_id', 'section_b', 'cat_id', 'page')
	),
	'cameras' => array
	(
		'(?:(\d{1,11})-[^/]{1,255})?' =>
			array('object_id')
	),
	'category' => array
	(
		'(\d{1,11})-[^/]{1,255}(?:/(pass))?' =>
			array('object_id', 'section'),
		'(\d{1,11})-[^/]{1,255}/page/(\d{1,11})(?:/(pass))?' =>
			array('object_id', 'page', 'section')
	),
	'comments' => array
	(
		'(?:page/(\d{1,11}))?' =>
			array('page'),
		'(\d{1,11})-[^/]{1,255}(?:/page/(\d{1,11}))?' =>
			array('object_id', 'page')
	),
	'comments-stats' => array
	(
		'(\d{1,11})-[^/]{1,255}(?:/page/(\d{1,11}))?' =>
			array('object_id', 'page')
	),
	'contact' => array(),
	'date-added' => array
	(
		'(\d{4}(?:-\d{2}){0,2})(?:/page/(\d{1,11}))?' =>
			array('date', 'page'),
		'(\d{4}(?:-\d{2}){0,2})/(album|category)/(\d{1,11})-[^/]{1,255}(?:/page/(\d{1,11}))?' =>
			array('date', 'section_b', 'object_id', 'page')
	),
	'date-created' => array
	(
		'(\d{4}(?:-\d{2}){0,2})(?:/page/(\d{1,11}))?' =>
			array('date', 'page'),
		'(\d{4}(?:-\d{2}){0,2})/(album|category)/(\d{1,11})-[^/]{1,255}(?:/page/(\d{1,11}))?' =>
			array('date', 'section_b', 'object_id', 'page')
	),
	'forgot' => array(),
	'guestbook' => array
	(
		'(?:page/(\d{1,11}))?' =>
			array('page')
	),
	'history' => array
	(
		'(?:(\d{1,11})-[^/]{1,255})?' =>
			array('object_id')
	),
	'hits' => array
	(
		'(\d{1,11})-[^/]{1,255}(?:/page/(\d{1,11}))?' =>
			array('object_id', 'page')
	),
	'image' => array
	(
		'(\d{1,11})-[^/]{1,255}(?:/(pass))?' =>
			array('object_id', 'section'),
		'(\d{1,11})-[^/]{1,255}/(basket)(?:/(pass))?' =>
			array('object_id', 'section_b', 'section'),
		'(\d{1,11})-[^/]{1,255}/(camera-(?:brand|model))/(\d{1,11})-[^/]{1,255}(?:/(pass))?' =>
			array('object_id', 'section_b', 'camera_id', 'section'),
		'(\d{1,11})-[^/]{1,255}/(camera-(?:brand|model))/(\d{1,11})-[^/]{1,255}/'
			. '(album|category)/(\d{1,11})-[^/]{1,255}(?:/(pass))?' =>
			array('object_id', 'section_b', 'camera_id', 'section_c', 'cat_id', 'section'),
		'(\d{1,11})-[^/]{1,255}/(comments-stats|hits|images'
			. '|recent-images|votes)/(\d{1,11})-[^/]{1,255}(?:/(pass))?' =>
			array('object_id', 'section_b', 'cat_id', 'section'),
		'(\d{1,11})-[^/]{1,255}/(date-(?:added|created))/(\d{4}(?:-\d{2}){0,2})(?:/(pass))?' =>
			array('object_id', 'section_b', 'date', 'section'),
		'(\d{1,11})-[^/]{1,255}/(date-(?:added|created))/(\d{4}(?:-\d{2}){0,2})/'
			. '(album|category)/(\d{1,11})-[^/]{1,255}(?:/(pass))?' =>
			array('object_id', 'section_b', 'date', 'section_c', 'cat_id', 'section'),
		'(\d{1,11})-[^/]{1,255}/search/([\dA-Za-z]{12})(?:/(pass))?' =>
			array('object_id', 'search', 'section'),
		'(\d{1,11})-[^/]{1,255}/(tag)/(\d{1,11})-[^/]{1,255}(?:/(pass))?' =>
			array('object_id', 'section_b', 'tag_id', 'section'),
		'(\d{1,11})-[^/]{1,255}/(tag)/(\d{1,11})-[^/]{1,255}/'
			. '(album|category)/(\d{1,11})-[^/]{1,255}(?:/(pass))?' =>
			array('object_id', 'section_b', 'tag_id', 'section_c', 'cat_id', 'section'),
		'(\d{1,11})-[^/]{1,255}/(user-(?:favorites|images))/(\d{1,11})(?:/(pass))?' =>
			array('object_id', 'section_b', 'user_id', 'section'),
		'(\d{1,11})-[^/]{1,255}/(user-(?:favorites|images))/(\d{1,11})/'
			. '(album|category)/(\d{1,11})-[^/]{1,255}(?:/(pass))?' =>
			array('object_id', 'section_b', 'user_id', 'section_c', 'cat_id', 'section')
	),
	'images' => array
	(
		'(\d{1,11})-[^/]{1,255}(?:/page/(\d{1,11}))?' =>
			array('object_id', 'page')
	),
	'login' => array(),
	'members' => array
	(
		'(?:page/(\d{1,11}))?' =>
			array('page'),
		'group/(\d{1,11})(?:/page/(\d{1,11}))?' =>
			array('group_id', 'page'),
	),
	'new-category' => array(),
	'new-password' => array(),
	'page' => array
	(
		'(\d{1,11})-[^/]{1,255}' =>
			array('object_id'),
	),
	'profile' => array(),
	'recent-images' => array
	(
		'(\d{1,11})-[^/]{1,255}(?:/page/(\d{1,11}))?' =>
			array('object_id', 'page')
	),
	'register' => array(),
	'search' => array
	(
		'([\dA-Za-z]{12})(?:/page/(\d{1,11}))?' =>
			array('search', 'page')
	),
	'search-advanced' => array(),
	'sitemap' => array(),
	'tag' => array
	(
		'(\d{1,11})-[^/]{1,255}(?:/page/(\d{1,11}))?' =>
			array('tag_id', 'page'),
		'(\d{1,11})-[^/]{1,255}/(album|category)/(\d{1,11})-[^/]{1,255}(?:/page/(\d{1,11}))?' =>
			array('tag_id', 'section_b', 'object_id', 'page')
	),
	'tags' => array
	(
		'(?:(\d{1,11})-[^/]{1,255})?' =>
			array('object_id')
	),
	'upload' => array
	(
		'(?:(\d{1,11}))?' =>
			array('object_id')
	),
	'user' => array
	(
		'(\d{1,11})' =>
			array('object_id')
	),
	'user-comments' => array
	(
		'(\d{1,11})(?:/page/(\d{1,11}))?' =>
			array('user_id', 'page'),
		'(\d{1,11})/(album|category)/(\d{1,11})-[^/]{1,255}(?:/page/(\d{1,11}))?' =>
			array('user_id', 'section_b', 'object_id', 'page')
	),
	'user-favorites' => array
	(
		'(\d{1,11})(?:/page/(\d{1,11}))?' =>
			array('user_id', 'page'),
		'(\d{1,11})/(album|category)/(\d{1,11})-[^/]{1,255}(?:/page/(\d{1,11}))?' =>
			array('user_id', 'section_b', 'object_id', 'page')
	),
	'user-images' => array
	(
		'(\d{1,11})(?:/page/(\d{1,11}))?' =>
			array('user_id', 'page'),
		'(\d{1,11})/(album|category)/(\d{1,11})-[^/]{1,255}(?:/page/(\d{1,11}))?' =>
			array('user_id', 'section_b', 'object_id', 'page')
	),
	'validation' => array(),
	'votes' => array
	(
		'(\d{1,11})-[^/]{1,255}(?:/page/(\d{1,11}))?' =>
			array('object_id', 'page')
	),
	'watermark' => array(),
	'worldmap' => array()
));

// Valeurs par défaut.
if (!isset($_GET['section']))
{
	$_GET['section'] = $q ? '404' : 'category';
}
if (!isset($_GET['object_id']))
{
	$_GET['object_id'] = 1;
}
if (!isset($_GET['page']))
{
	$_GET['page'] = 1;
}

// Indique si l'on se trouve sur une page de type album,
// c'est à dire une page de vignettes d'images.
$_GET['album_page'] =
	$_GET['section'] == 'album' ||
	$_GET['section'] == 'basket' ||
	$_GET['section'] == 'camera-brand' ||
	$_GET['section'] == 'camera-model' ||
	$_GET['section'] == 'date-added' ||
	$_GET['section'] == 'date-created' ||
	$_GET['section'] == 'comments-stats' ||
	$_GET['section'] == 'hits' ||
	$_GET['section'] == 'images' ||
	$_GET['section'] == 'recent-images' ||
	$_GET['section'] == 'search' ||
	$_GET['section'] == 'tag' ||
	$_GET['section'] == 'user-favorites' ||
	$_GET['section'] == 'user-images' ||
	$_GET['section'] == 'votes';

// Initialisation de la galerie.
gallery::init();

// Traitement de la requête.
switch ($_GET['section'])
{
	// Albums.
	case 'album' :
	case 'basket' :
	case 'camera-brand' :
	case 'camera-model' :
	case 'comments-stats' :
	case 'date-added' :
	case 'date-created' :
	case 'hits' :
	case 'images' :
	case 'recent-images' :
	case 'tag' :
	case 'votes' :
		switch ($_GET['section'])
		{
			case 'basket' :
				if (utils::$config['pages_params']['basket']['status'] != 1
				 || utils::$config['basket'] != 1)
				{
					gallery::notFound();
					break 2;
				}
				album::basketEmpty();
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
					gallery::notFound();
					break 2;
				}
				break;

			case 'comments-stats' :
				if (utils::$config['comments'] != 1)
				{
					gallery::notFound();
					break 2;
				}
				break;

			case 'recent-images' :
				if (utils::$config['recent_images'] != 1
				 && utils::$config['widgets_params']['options']['items']['recent'] != 1)
				{
					gallery::notFound();
					break 2;
				}
				break;

			case 'tag' :
				if (utils::$config['tags'] != 1)
				{
					gallery::notFound();
					break 2;
				}
				if (tags::getTagInfos() === 0)
				{
					gallery::notFound();
					break 2;
				}
				break;

			case 'votes' :
				if (utils::$config['votes'] != 1)
				{
					gallery::notFound();
					break 2;
				}
				break;
		}

		// Récupération des informations de la catégorie.
		$r = category::getCategoryInfos();
		if ($r === 0)
		{
			gallery::notFound();
			break;
		}
		if ($r === -1)
		{
			gallery::error();
			break;
		}

		album::countImages();

		// Récupération des vignettes.
		$r = album::getImages();
		if ($r === 0)
		{
			gallery::notFound();
			break;
		}
		if ($r === -1)
		{
			gallery::error();
			break;
		}

		album::recentImages();

		gallery::getParents(category::$infos['cat_path']);
		gallery::style();
		category::getNeighbours();

		if ($_GET['section'] != 'basket')
		{
			tags::getCategoryTags(TRUE);
		}

		if (is_array(album::$thumbs))
		{
			reset(album::$thumbs);
		}

		if ($_GET['section'] == 'camera-brand')
		{
			gallery::getCameraInfos('brand');
			gallery::$pageTitle = gallery::$cameraInfos['camera_brand_name'];
		}
		if ($_GET['section'] == 'camera-model')
		{
			gallery::getCameraInfos('model');
			gallery::$pageTitle = gallery::$cameraInfos['camera_model_name'];
		}

		gallery::$tplFile = 'album';

		final class tpl extends tplAlbum{};
		break;

	// Modification de l'avatar.
	case 'avatar' :
		if (utils::$config['users'] != 1
		|| utils::$config['avatars'] != 1 || !users::$auth)
		{
			gallery::notFound();
			break;
		}

		users::avatar();

		gallery::$tplFile = 'avatar';
		gallery::$pageTitle = __('avatar');

		final class tpl extends tplProfile{};
		break;

	// Liste des marques et modèles d'appareil.
	case 'cameras' :
		if (utils::$config['pages_params']['cameras']['status'] != 1)
		{
			gallery::notFound();
			break;
		}

		// Récupération des informations de la catégorie.
		$r = category::getCategoryInfos();
		if ($r === 0)
		{
			gallery::notFound();
			break;
		}
		if ($r === -1)
		{
			gallery::error();
			break;
		}

		gallery::getCameras();

		gallery::$tplFile = 'cameras';
		gallery::$pageTitle = sprintf(
			__('appareils photos de %s'),
			strip_tags(category::$infos['type_html'])
		);

		final class tpl extends tplCameras{};
		break;

	// Catégories.
	case 'category' :

		$r = category::getCategoryInfos();
		if ($r === 0)
		{
			gallery::notFound();
			break;
		}
		if ($r === -1)
		{
			gallery::error();
			break;
		}

		category::pages();

		if (category::getCategories() === 0)
		{
			if (isset($_GET['cat_id']) && $_GET['cat_id'] > 1)
			{
				gallery::notFound();
				break;
			}
		}
		else if (category::$thumbs === NULL)
		{
			gallery::error();
			break;
		}

		category::recentImages();
		category::stats();

		gallery::getParents(category::$infos['cat_path']);
		gallery::style();
		category::getNeighbours();

		tags::getCategoryTags(TRUE);

		if (is_array(category::$thumbs))
		{
			reset(category::$thumbs);
		}

		gallery::$tplFile = 'category';

		final class tpl extends tplCategory{};
		break;

	// Commentaires.
	case 'comments' :
		if (utils::$config['comments'] != 1
		|| utils::$config['pages_params']['comments']['status'] != 1)
		{
			gallery::notFound();
			break;
		}

		// Récupération des commentaires.
		$r = comments::getCommentsPage();
		if ($r === 0)
		{
			gallery::notFound();
			break;
		}
		if ($r === -1)
		{
			gallery::error();
			break;
		}

		gallery::$tplFile = 'comments';
		gallery::$pageTitle = sprintf(
			__('commentaires de %s'),
			utils::getLocale(comments::$catInfos['object_name'])
		);

		final class tpl extends tplComments{};
		break;

	// Page contact.
	case 'contact' :
		if (utils::$config['pages_params']['contact']['status'] != 1)
		{
			gallery::notFound();
			break;
		}

		gallery::contact();

		gallery::$tplFile = 'contact';
		gallery::$pageTitle = mb_strtolower(__('Contact'));

		final class tpl extends tplContact{};
		break;

	// Récupération de mot de passe.
	case 'forgot' :
		if (utils::$config['users'] != 1 || users::$auth)
		{
			gallery::notFound();
			break;
		}

		users::forgot();

		gallery::$tplFile = 'forgot';
		gallery::$pageTitle = __('mot de passe oublié');

		final class tpl extends tplForgot{};
		break;

	// Livre d'or.
	case 'guestbook' :
		if (utils::$config['pages_params']['guestbook']['status'] != 1)
		{
			gallery::notFound();
			break;
		}

		guestbook::addComment();
		$r = guestbook::getComments();
		if ($r === 0)
		{
			gallery::notFound();
			break;
		}
		if ($r === -1)
		{
			gallery::error();
			break;
		}

		gallery::$tplFile = 'guestbook';
		gallery::$pageTitle = mb_strtolower(__('Livre d\'or'));

		final class tpl extends tplGuestbook{};
		break;

	// Historique.
	case 'history' :
		if (utils::$config['pages_params']['history']['status'] != 1)
		{
			gallery::notFound();
			break;
		}

		// Récupération des informations de la catégorie.
		$r = category::getCategoryInfos();
		if ($r === 0)
		{
			gallery::notFound();
			break;
		}
		if ($r === -1)
		{
			gallery::error();
			break;
		}

		gallery::history();

		gallery::$tplFile = 'history';
		gallery::$pageTitle = sprintf(
			mb_strtolower(__('Historique des images de %s')),
			strip_tags(category::$infos['type_html'])
		);

		final class tpl extends tplHistory{};
		break;

	// Images.
	case 'image' :
		if (utils::$config['images_direct_link'])
		{
			gallery::notFound();
			break;
		}

		// Récupération des informations de l'image.
		$r = image::getImageInfos();
		if ($r === 0)
		{
			gallery::notFound();
			break;
		}
		if ($r === -1)
		{
			gallery::error();
			break;
		}

		// Récupération des informations de l'utilisateur.
		if (isset($_GET['section_b']) && substr($_GET['section_b'], 0, 4) == 'user')
		{
			$r = users::getUser($_GET['user_id'], TRUE);
			if ($r === 0)
			{
				gallery::notFound();
				break;
			}
			if ($r === -1)
			{
				gallery::error();
				break;
			}
		}
		else if (isset($_GET['section_b']) && substr($_GET['section_b'], 0, 6) == 'camera')
		{
			gallery::getCameraInfos(substr($_GET['section_b'], 7));
		}
		else if (isset($_GET['tag_id']))
		{
			tags::getTagInfos();
		}

		gallery::getParents(image::$infos['image_path']);
		gallery::style();

		// Récupération des informations de la catégorie.
		$r = image::getCategoryInfos();
		if ($r === 0)
		{
			gallery::notFound();
			break;
		}
		if ($r === -1)
		{
			gallery::error();
			break;
		}

		alb::imageHits(users::$infos, image::$infos);

		// image::images() doit être appelé après image::hits() afin de
		// calculer la bonne page parente pour la section spéciale "hits".
		$r = image::images();
		if ($r === 0)
		{
			gallery::notFound();
			break;
		}
		if ($r === -1)
		{
			gallery::error();
			break;
		}

		image::$resize = img::imageSize(
			image::$infos['image_width'], image::$infos['image_height']);

		comments::addComment();
		comments::getCommentsImage();

		tags::getImageTags();

		gallery::$tplFile = 'image';

		final class tpl extends tplImage{};
		break;

	// Login.
	case 'login' :
		if (utils::$config['users'] != 1)
		{
			gallery::notFound();
			break;
		}

		gallery::$tplFile = 'login';
		gallery::$pageTitle = __('identification');

		final class tpl extends tplGallery{};
		break;

	// Plan.
	case 'sitemap' :
		if (utils::$config['pages_params']['sitemap']['status'] != 1)
		{
			gallery::notFound();
			break;
		}

		map::getMap();

		gallery::$tplFile = 'sitemap';
		gallery::$pageTitle = mb_strtolower(__('Plan de la galerie'));

		final class tpl extends tplGallery{};
		break;

	// Liste des membres.
	case 'members' :
		if (utils::$config['users'] != 1
		|| utils::$config['pages_params']['members']['status'] != 1)
		{
			gallery::notFound();
			break;
		}

		$r = users::getMembers();
		if ($r === 0)
		{
			gallery::notFound();
			break;
		}
		if ($r === -1)
		{
			gallery::error();
			break;
		}

		gallery::$tplFile = 'members';
		gallery::$pageTitle = mb_strtolower(__('Liste des membres'));

		final class tpl extends tplMembers{};
		break;

	// Nouvelle catégorie.
	case 'new-category' :
		if (utils::$config['users'] != 1 || !users::$auth
		|| !users::$perms['gallery']['perms']['create_albums'])
		{
			gallery::notFound();
			break;
		}

		users::newCategory();
		map::getMap(TRUE, (empty($_POST)) ? FALSE : TRUE);

		gallery::$tplFile = 'new_category';
		gallery::$pageTitle = __('nouvelle catégorie');

		final class tpl extends tplNewCategory{};
		break;

	// Nouveau mot de passe.
	case 'new-password' :
		if (utils::$config['users'] != 1 || users::$auth)
		{
			gallery::notFound();
			break;
		}

		users::newPassword();

		gallery::$tplFile = 'new_password';
		gallery::$pageTitle = __('nouveau mot de passe');

		final class tpl extends tplNewPassword{};
		break;

	// Pages.
	case 'page' :
		if (!isset(utils::$config['pages_params']['perso_' . $_GET['object_id']])
		|| !utils::$config['pages_params']['perso_' . $_GET['object_id']]['status'])
		{
			gallery::notFound();
			break;
		}

		gallery::$tplFile = 'page';
		gallery::$pageTitle = utils::getLocale(
			utils::$config['pages_params']['perso_' . $_GET['object_id']]['title']
		);

		final class tpl extends tplPage{};
		break;

	// Mot de passe pour entrer dans une section protégée.
	case 'pass' :
		if (gallery::checkPassword() == -1)
		{
			gallery::error();
			break;
		}

		gallery::$tplFile = 'password';
		gallery::$pageTitle = __('mot de passe requis');

		final class tpl extends tplGallery{};
		break;

	// Édition de profil utilisateur.
	case 'profile' :
		if (utils::$config['users'] != 1 || !users::$auth)
		{
			gallery::notFound();
			break;
		}

		users::profile();

		gallery::$tplFile = 'profile';
		gallery::$pageTitle = __('profil');

		final class tpl extends tplProfile{};
		break;

	// Création de compte utilisateur.
	case 'register' :
		if (utils::$config['users'] != 1
		 || utils::$config['users_inscription'] != 1
		 || users::$auth)
		{
			gallery::notFound();
			break;
		}

		users::register();

		gallery::$tplFile = 'register';
		gallery::$pageTitle = __('créer un compte');

		final class tpl extends tplRegister{};
		break;

	// Moteur de recherche.
	case 'search' :
		if (utils::$config['search'] != 1)
		{
			gallery::notFound();
			break;
		}

		// Recherche de catégories.
		album::searchCategories();

		// Recherche d'images.
		$sql_where = search::getImagesSQLWhere(users::$perms);
		if (is_array($sql_where))
		{
			$sql_where['sql'] .= ' AND ';
			album::countImages($sql_where['sql'], $sql_where['params']);
			$r = album::getImages($sql_where['sql'], $sql_where['params']);
			if ($r === 0)
			{
				gallery::notFound();
				break;
			}
			if ($r === -1)
			{
				gallery::error();
				break;
			}
		}

		gallery::$tplFile = 'album';
		gallery::$pageTitle = __('résultats de la recherche');

		final class tpl extends tplAlbum{};
		break;

	// Recherche avancée.
	case 'search-advanced' :
		if (utils::$config['search'] != 1
		 || utils::$config['search_advanced'] != 1)
		{
			gallery::notFound();
			break;
		}

		map::getMap();

		gallery::$tplFile = 'search';
		gallery::$pageTitle = mb_strtolower(__('Recherche avancée'));

		final class tpl extends tplSearchAdvanced{};
		break;

	// Nuage de tags.
	case 'tags' :
		if (utils::$config['tags'] != 1)
		{
			gallery::notFound();
			break;
		}

		// Récupération des informations de la catégorie.
		$r = category::getCategoryInfos();
		if ($r === 0)
		{
			gallery::notFound();
			break;
		}
		if ($r === -1)
		{
			gallery::error();
			break;
		}

		tags::getCategoryTags();

		gallery::$tplFile = 'tags';
		gallery::$pageTitle = __('nuage de tags');

		final class tpl extends tplTags{};
		break;

	// Ajout d'images par HTTP.
	case 'upload' :
		if (utils::$config['users'] != 1 || !users::$auth
		|| !users::$perms['gallery']['perms']['upload'])
		{
			gallery::notFound();
			break;
		}

		if (isset($_POST['files']))
		{
			utils::$config['anticsrf_token_expire'] =
				((int) utils::$config['anticsrf_token_expire'] < 7200)
				? 7200 : utils::$config['anticsrf_token_expire'];
		}

		users::upload();
		map::getMap(FALSE, (empty($_POST)) ? FALSE : TRUE);

		gallery::$tplFile = 'upload';
		gallery::$pageTitle = __('ajouter des images');

		final class tpl extends tplUpload{};
		break;

	// Fiche utilisateur.
	case 'user' :
		if (utils::$config['users'] != 1
		|| $_GET['object_id'] == 2)
		{
			gallery::notFound();
			break;
		}

		$r = users::getUser($_GET['object_id']);
		if ($r === 0)
		{
			gallery::notFound();
			break;
		}
		if ($r === -1)
		{
			gallery::error();
			break;
		}

		gallery::$tplFile = 'user';
		gallery::$pageTitle = users::$profile['user_login'];

		final class tpl extends tplUser{};
		break;

	// Commentaires de l'utilisateur.
	case 'user-comments' :
		if (utils::$config['users'] != 1
		|| utils::$config['comments'] != 1
		|| utils::$config['pages_params']['comments']['status'] != 1)
		{
			gallery::notFound();
			break;
		}

		// Récupération des informations de la catégorie.
		$r = category::getCategoryInfos();
		if ($r === 0)
		{
			gallery::notFound();
			break;
		}
		if ($r === -1)
		{
			gallery::error();
			break;
		}

		// Récupération des commentaires.
		$r = comments::getCommentsPage();
		if ($r === 0)
		{
			gallery::notFound();
			break;
		}
		if ($r === -1)
		{
			gallery::error();
			break;
		}

		album::getUserSectionCategories();

		gallery::$tplFile = 'comments';
		gallery::$pageTitle = sprintf(
			__('commentaires postés par %s dans %s'),
			comments::$catInfos['object_name'],
			strip_tags(category::$infos['type_html'])
		);

		final class tpl extends tplComments{};
		break;

	// Favoris de l'utilisateur.
	case 'user-favorites' :
		if (utils::$config['users'] != 1
		 || utils::$config['images_direct_link'] == 1)
		{
			gallery::notFound();
			break;
		}

		// Récupération des informations de la catégorie.
		$r = category::getCategoryInfos();
		if ($r === 0)
		{
			gallery::notFound();
			break;
		}
		if ($r === -1)
		{
			gallery::error();
			break;
		}

		$r = users::getUser($_GET['user_id'], TRUE);
		if ($r === 0)
		{
			gallery::notFound();
			break;
		}
		if ($r === -1)
		{
			gallery::error();
			break;
		}

		album::countImages();

		// Récupération des vignettes.
		$r = album::getImages();
		if ($r === 0)
		{
			gallery::notFound();
			break;
		}
		if ($r === -1)
		{
			gallery::error();
			break;
		}

		album::getUserSectionCategories();

		if (is_array(album::$thumbs))
		{
			reset(album::$thumbs);
		}

		gallery::$tplFile = 'album';
		gallery::$pageTitle = sprintf(
			mb_strtolower(__('Favoris de %s dans %s')),
			users::$profile['user_login'],
			strip_tags(category::$infos['type_html'])
		);

		final class tpl extends tplAlbum{};
		break;

	// Images de l'utilisateur.
	case 'user-images' :
		if (utils::$config['users'] != 1)
		{
			gallery::notFound();
			break;
		}

		// Récupération des informations de la catégorie.
		$r = category::getCategoryInfos();
		if ($r === 0)
		{
			gallery::notFound();
			break;
		}
		if ($r === -1)
		{
			gallery::error();
			break;
		}

		$r = users::getUser($_GET['user_id'], TRUE);
		if ($r === 0)
		{
			gallery::notFound();
			break;
		}
		if ($r === -1)
		{
			gallery::error();
			break;
		}

		album::countImages();

		// Récupération des vignettes.
		$r = album::getImages();
		if ($r === 0)
		{
			gallery::notFound();
			break;
		}
		if ($r === -1)
		{
			gallery::error();
			break;
		}

		album::getUserSectionCategories();

		if (is_array(album::$thumbs))
		{
			reset(album::$thumbs);
		}

		gallery::$tplFile = 'album';
		gallery::$pageTitle = sprintf(
			mb_strtolower(__('Images de %s dans %s')),
			users::$profile['user_login'],
			strip_tags(category::$infos['type_html'])
		);

		final class tpl extends tplAlbum{};
		break;

	// Validation de compte utilisateur par courriel.
	case 'validation' :
		if (utils::$config['users'] != 1
		|| utils::$config['users_inscription_by_mail'] != 1
		|| users::$auth)
		{
			gallery::notFound();
			break;
		}

		users::validation();

		gallery::$tplFile = 'validation';
		gallery::$pageTitle = __('Validation de compte');

		final class tpl extends tplValidation{};
		break;

	// Page du filigrane utilisateur.
	case 'watermark' :
		if (utils::$config['watermark_users'] != 1)
		{
			gallery::notFound();
			break;
		}

		users::watermark();

		gallery::$tplFile = 'watermark';
		gallery::$pageTitle = mb_strtolower('Filigrane');

		final class tpl extends tplWatermark{};
		break;

	// Carte du monde pour géolocalisation.
	case 'worldmap' :
		if (utils::$config['geoloc'] != 1)
		{
			gallery::notFound();
			break;
		}

		gallery::worldmap();

		gallery::$tplFile = 'worldmap';
		gallery::$pageTitle = mb_strtolower('Carte du monde');

		final class tpl extends tplWorldmap{};
		break;

	// Page non trouvée.
	default :
		gallery::notFound();
}

// Fermeture de la connexion à la base de données.
if (is_object(utils::$db) && utils::$config['db_close_template'])
{
	utils::$db->connexion = NULL;
}

// Création de l'objet de template.
if ($_GET['section'] == '404' || $_GET['section'] == 'error')
{
	final class tpl extends tplGallery{};
}
$tpl = new tpl();

// Nouveau jeton anti-CSRF.
utils::antiCSRFTokenNew(utils::$cookiePrefs);

// Écriture des données du cookie des préférences.
utils::$cookiePrefs->write();

// Chargement du template.
if (!CONF_INTEGRATED)
{
	require_once(GALLERY_ROOT . '/template/'
		. utils::filters(utils::$config['theme_template'], 'dir') . '/index.tpl.php');
}



/**
 * Opérations concernant la galerie.
 */
class gallery
{
	/**
	 * Informations sur l'appareil courant.
	 *
	 * @var array
	 */
	public static $cameraInfos;

	/**
	 * Liste des appareils photos de la galerie.
	 *
	 * @var array
	 */
	public static $cameras;

	/**
	 * Les commentaires sont-ils autorisés pour l'objet courant ?
	 *
	 * @var boolean
	 */
	public static $catCommentable = TRUE;

	/**
	 * La création de catégorie est-elle autorisée pour la catégorie courante ?
	 *
	 * @var boolean
	 */
	public static $catCreatable = TRUE;

	/**
	 * Style de la catégorie courante.
	 *
	 * @var string
	 */
	public static $catStyle;

	/**
	 * Les votes sont-ils autorisés pour la catégorie courante ?
	 *
	 * @var boolean
	 */
	public static $catVotable = TRUE;

	/**
	 * L'ajout d'images est-elle autorisée pour la catégorie courante ?
	 *
	 * @var boolean
	 */
	public static $catUploadable = TRUE;

	/**
	 * Nom du champ de formulaire pour lequel il y a une erreur.
	 *
	 * @var string
	 */
	public static $fieldError;

	/**
	 * Dates d'ajout.
	 *
	 * @var array
	 */
	public static $historyAdddt = array();

	/**
	 * Dates de création.
	 *
	 * @var array
	 */
	public static $historyCrtdt = array();

	/**
	 * Nom de la page courante.
	 *
	 * @var string
	 */
	public static $pageTitle;

	/**
	 * Liste des catégories parentes.
	 *
	 * @var array
	 */
	public static $parents;

	/**
	 * Instance du gestionnaire de cookie pour les préférences utilisateurs.
	 *
	 * @var object
	 */
	public static $prefs;

	/**
	 * Timestamp correspondant à une date passée et permettant de considérer
	 * les images ajoutées après cette date comme récentes.
	 *
	 * @var integer
	 */
	public static $recentImagesLimit;

	/**
	 * Rapport des actions effectuées.
	 *
	 * @var array
	 */
	public static $report = array();

	/**
	 * Contenu du paramètre GET "q", mais sans le numéro de page.
	 * Si la page demandée est "album/5-photos/page/2",
	 * $sectionRequest contiendra "album/5-photos".
	 *
	 * @var string
	 */
	public static $sectionRequest;

	/**
	 * Liste des différentes feuilles de style disponibles.
	 *
	 * @var array
	 */
	public static $styles;

	/**
	 * Date et heure  de la requête
	 * (le moment où l'utilisateur demande une page).
	 *
	 * @var integer
	 */
	public static $time;

	/**
	 * Nom du fichier de template à inclure.
	 *
	 * @var string
	 */
	public static $tplFile;

	/**
	 * Informations utiles pour le widget "image".
	 *
	 * @var array
	 */
	public static $widgetImage = array();

	/**
	 * Informations utiles pour le widget "qui est en ligne ?".
	 *
	 * @var array
	 */
	public static $widgetOnlineUsers = array();

	/**
	 * Informations utiles des images et catégories à géolocaliser.
	 *
	 * @var array
	 */
	public static $worldmap = array();



	/**
	 * L'utilisateur a-t-il été authentifié pour
	 * l'accès à la catégorie courante ?
	 *
	 * @var boolean
	 */
	protected static $_passwordAuth = FALSE;



	/**
	 * Page non trouvée ou accès interdit.
	 *
	 * @return void
	 */
	public static function notFound()
	{
		header('HTTP/1.0 404 Not Found');
		$_GET['section'] = '404';
		gallery::style();
		gallery::$pageTitle = __('page non trouvée !');
		gallery::$tplFile = '404';
	}

	/**
	 * Page d'erreur.
	 *
	 * @return void
	 */
	public static function error()
	{
		trigger_error('Gallery error', E_USER_WARNING);
		$_GET['section'] = 'error';
		gallery::style();
		gallery::$pageTitle = __('Oups !');
		gallery::$tplFile = 'error';
	}

	/**
	 * Récupère les informations de l'appareil courant.
	 *
	 * @return void
	 */
	public static function getCameraInfos($type)
	{
		$camera_id = isset($_GET['camera_id'])
			? $_GET['camera_id']
			: $_GET['object_id'];
		$sql = 'SELECT *
				  FROM ' . CONF_DB_PREF . 'cameras_' . $type . 's
				 WHERE camera_' . $type . '_id = ' . (int) $camera_id;
		if (utils::$db->query($sql, 'row') !== FALSE)
		{
			self::$cameraInfos = utils::$db->queryResult;
		}
	}

	/**
	 * Récupère tous les modèles d'appareils photos
	 * et le nombre d'images liés à ces appareils.
	 *
	 * @return void
	 */
	public static function getCameras()
	{
		$cat_path = (category::$infos['cat_id'] > 1)
			? category::$infos['cat_path'] . '/'
			: '';
		$params = array(
			'cat_path' => $cat_path
		);
		$sql = 'SELECT COUNT(*)
				  FROM ' . CONF_DB_PREF . 'cameras_models_images AS sub_cam_mi
			 LEFT JOIN ' . CONF_DB_PREF . 'images AS img
					ON sub_cam_mi.image_id = img.image_id
			 LEFT JOIN ' . CONF_DB_PREF . 'categories AS cat
					ON img.cat_id = cat.cat_id
			     WHERE %1$s
				   AND image_path LIKE CONCAT(:cat_path, "%%")
				   AND sub_cam_mi.camera_model_id = cam_mi.camera_model_id';
		$sql = 'SELECT cam_m.camera_model_id,
					   cam_m.camera_model_name,
					   cam_m.camera_model_url,
					   cam_b.camera_brand_id,
					   cam_b.camera_brand_name,
					   cam_b.camera_brand_url,
					   (' . $sql . ') AS nb_images
				  FROM ' . CONF_DB_PREF . 'cameras_models AS cam_m
			 LEFT JOIN ' . CONF_DB_PREF . 'cameras_brands AS cam_b
					ON cam_m.camera_brand_id = cam_b.camera_brand_id
			 LEFT JOIN ' . CONF_DB_PREF . 'cameras_models_images AS cam_mi
					ON cam_m.camera_model_id = cam_mi.camera_model_id
			 LEFT JOIN ' . CONF_DB_PREF . 'images AS img
					ON cam_mi.image_id = img.image_id
			 LEFT JOIN ' . CONF_DB_PREF . 'categories AS cat
					ON img.cat_id = cat.cat_id
				 WHERE %1$s
				   AND image_path LIKE CONCAT(:cat_path, "%%")
			  ORDER BY cam_b.camera_brand_name ASC,
					   cam_m.camera_model_name ASC';
		$fetch_style = array('fetch' => PDO::FETCH_ASSOC, 'column' => 'camera_model_id');
		$result = sql::sqlProtect('image', $sql, $fetch_style, FALSE, $params);
		if ($result !== FALSE && $result['nb_result'] > 0)
		{
			self::$cameras = $result['query_result'];
		}
	}

	/**
	 * Vérification du mot de passe entré par l'utilisateur
	 * pour l'accès à une partie protégée de la galerie.
	 *
	 * @return integer
	 */
	public static function checkPassword()
	{
		try
		{
			// Récupération du mot de passe.
			if ($_GET['q'][0] == 'i')
			{
				$sql = 'SELECT cat_password AS password
						  FROM ' . CONF_DB_PREF . 'images
					 LEFT JOIN ' . CONF_DB_PREF . 'categories USING(cat_id)
						 WHERE image_id = ' . (int) $_GET['object_id'] . '
						   AND cat_password IS NOT NULL
						 LIMIT 1';
			}
			else
			{
				$sql = 'SELECT cat_password AS password
						  FROM ' . CONF_DB_PREF . 'categories
						 WHERE cat_id = ' . (int) $_GET['object_id'] . '
						   AND cat_password IS NOT NULL
						 LIMIT 1';
			}
			if (utils::$db->query($sql, 'value') === FALSE)
			{
				throw new Exception();
			}

			// Si la catégorie n'est pas protégée par un mot de passe,
			// inutile de rester sur cette page.
			if (utils::$db->nbResult === 0)
			{
				utils::redirect($_GET['q'], TRUE);
			}

			$password = utils::$db->queryResult;

			// Si l'utilisateur a déjà entré le bon mot de passe,
			// inutile de rester sur cette page.
			if (self::_passwordSession($password, FALSE))
			{
				utils::redirect(preg_replace('`/pass$`', '', $_GET['q']), TRUE);
			}

			if (!array_key_exists('password', $_POST))
			{
				return 1;
			}

			// Vérification du mot de passe entré par l'utilisateur.
			$password = explode(':', $password);
			if (utils::hashPassword($_POST['password'], $password[0]) != $password[1])
			{
				// Prévention contre les attaques par force brute.
				usleep((int) utils::$config['passwords_usleep']);

				return 1;
			}

			// Début de la transaction.
			if (!utils::$db->transaction())
			{
				throw new Exception();
			}

			$session = user::getSession();

			// On donne la permission d'accès à la catégorie demandée
			// pour la session actuelle.
			$sql = 'INSERT INTO ' . CONF_DB_PREF . 'passwords (
					session_id,
					password
				) VALUES (
					:session_id,
					:password
				)';
			$params = array(
				'session_id' => $session['session_id'],
				'password' => $password[1]
			);
			if (utils::$db->prepare($sql) === FALSE
			|| utils::$db->executeExec($params) === FALSE
			|| utils::$db->nbResult === 0)
			{
				throw new Exception();
			}

			// Exécution de la transaction.
			if (!utils::$db->commit())
			{
				throw new Exception();
			}

			// Enregistrement de l'identifiant de session
			// dans le cookie de l'utilisateur.
			utils::$cookieSession->delete();
			utils::$cookieSession->add('token', $session['session_token']);
			utils::$cookieSession->write();

			// Redirection vers la page demandée.
			utils::redirect(preg_replace('`/pass$`', '', $_GET['q']), TRUE);
		}

		// Erreur SQL.
		catch (Exception $e)
		{
			return -1;
		}
	}

	/**
	 * Gestion de la page contact.
	 *
	 * @return void
	 */
	public static function contact()
	{
		// reCAPTCHA.
		if (utils::$config['recaptcha_contact'] && !self::_recaptcha('contact'))
		{
			return;
		}

		// Vérification de la présence de champs.
		if (!isset($_POST['name']) || !isset($_POST['email'])
		|| !isset($_POST['subject']) || !isset($_POST['message']))
		{
			return;
		}

		// Petite vérification antispam.
		if (!isset($_POST['f_email']) || $_POST['f_email'] !== '')
		{
			return;
		}

		// Vérifications par listes noires.
		$r = self::_checkBlacklists($_POST['name'], $_POST['email'], $_POST['message']);
		if (is_array($r))
		{
			// Log d'activité.
			self::_logUserActivity('contact_reject_blacklist_' . $r['list'], NULL, $r['match']);

			return;
		}

		$message_max_length = 5000;

		try
		{
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

				// On vérifie la longueur de chaque champ.
				if ($field == 'message' && mb_strlen($_POST[$field] > $message_max_length))
				{
					self::$fieldError = 'message';
					throw new Exception(
						'warning:' . sprintf(
							__('Votre message ne doit pas dépasser %s caractères.'),
							$message_max_length
						)
					);
				}
				else if ($field != 'message' && mb_strlen($_POST[$field]) > 255)
				{
					return;
				}
			}

			// On vérifie le format de l'adresse de courriel.
			if (!preg_match('`^' . utils::regexpEmail() . '$`i', $_POST['email']))
			{
				self::$fieldError = 'email';
				throw new Exception(
					'warning:' . __('Format de l\'adresse de courriel invalide.')
				);
			}

			// On vérifie le format de l'adresse de courriel de destination.
			$to = utils::$config['pages_params']['contact']['email'];
			if (!preg_match('`^' . utils::regexpEmail() . '$`i', $to))
			{
				throw new Exception('error');
			}

			// Message.
			$message = $_POST['message'];
			$message .= "\n\n" . '-- ' . "\n";
			$message .= __('Ce message vous a été envoyé par '
				. 'le formulaire de contact d\'iGalerie.') . "\n";
			$message .= sprintf(__('IP expéditeur : %s'), $_SERVER['REMOTE_ADDR']) . "\n";

			// Envoi du mail.
			$mail = new mail();
			$mail->messages[] = array(
				'to' => $to,
				'name' => $_POST['name'],
				'from' => $_POST['email'],
				'subject' => $_POST['subject'],
				'message' => $message,
				'bcc' => ''
			);
			if (!$mail->send())
			{
				throw new Exception('error');
			}

			// Log d'activité.
			self::_logUserActivity('contact_accept');

			// Confirmation.
			self::report('success:' . __('Votre message a été envoyé.'));
		}
		catch (Exception $e)
		{
			self::report($e);
		}
	}

	/**
	 * Récupération des dates d'ajouts et de création de toutes les images.
	 *
	 * @return void
	 */
	public static function history()
	{
		$cat_path = (category::$infos['cat_id'] > 1)
			? category::$infos['cat_path'] . '/'
			: '';
		$params = array(
			'cat_path' => $cat_path
		);
		$fetch_style = array(
			'column' => array('date', 'nb_images')
		);

		// Dates d'ajout.
		$sql = 'SELECT DISTINCT SUBSTRING(image_adddt, 1, 10) AS date,
								COUNT(*) AS nb_images
				  FROM ' . CONF_DB_PREF . 'images
			 LEFT JOIN ' . CONF_DB_PREF . 'categories USING (cat_id)
			     WHERE %s
				   AND image_path LIKE CONCAT(:cat_path, "%%")
			  GROUP BY date
			  ORDER BY date DESC';
		$result = sql::sqlProtect('image', $sql, $fetch_style, FALSE, $params);
		if ($result !== FALSE && $result['nb_result'] !== 0)
		{
			foreach ($result['query_result'] as $date => &$nb_images)
			{
				$year = substr($date, 0, 4);
				$month = substr($date, 5, 2);
				$day = substr($date, 8, 2);
				if (!isset(self::$historyAdddt[$year]))
				{
					self::$historyAdddt[$year] = array();
				}
				if (!isset(self::$historyAdddt[$year][$month]))
				{
					self::$historyAdddt[$year][$month] = array();
				}
				self::$historyAdddt[$year][$month][$day] = $nb_images;
			}
		}

		// Dates de création.
		$sql = 'SELECT DISTINCT SUBSTRING(image_crtdt, 1, 10) AS date,
								COUNT(*) AS nb_images
				  FROM ' . CONF_DB_PREF . 'images
			 LEFT JOIN ' . CONF_DB_PREF . 'categories USING (cat_id)
			     WHERE %s
				   AND image_path LIKE CONCAT(:cat_path, "%%")
			  GROUP BY date
			  ORDER BY date DESC';
		$result = sql::sqlProtect('image', $sql, $fetch_style, FALSE, $params);
		if ($result !== FALSE && $result['nb_result'] !== 0)
		{
			foreach ($result['query_result'] as $date => &$nb_images)
			{
				if (empty($date))
				{
					continue;
				}

				$year = substr($date, 0, 4);
				$month = substr($date, 5, 2);
				$day = substr($date, 8, 2);
				if (!isset(self::$historyCrtdt[$year]))
				{
					self::$historyCrtdt[$year] = array();
				}
				if (!isset(self::$historyCrtdt[$year][$month]))
				{
					self::$historyCrtdt[$year][$month] = array();
				}
				self::$historyCrtdt[$year][$month][$day] = $nb_images;
			}
		}
	}

	/**
	 * Initialisation de la galerie.
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
		utils::getConfig();
		utils::$config['recent_images_by_cat'] = 0;
		utils::$config['users_profile_infos'] = users::profileInfos();

		// Identifiant de session : chargement cookie.
		utils::$cookieSession = new cookie('igal_session', 8640000, CONF_GALLERY_PATH);

		// Cookie des préférences utilisateur.
		utils::$cookiePrefs = new cookie('igal_prefs', 315360000, CONF_GALLERY_PATH);

		// Date et heure de la requête.
		self::$time = (int) $_SERVER['REQUEST_TIME'];

		// Authentification utilisateur.
		users::auth();

		// Chargement du fichier de langue.
		self::_langDetect();
		utils::locale();

		// La galerie est-elle fermée ?
		if (utils::$config['gallery_closure'])
		{
			$lang_code = substr(utils::$userLang, 0, 2);
			echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"';
			echo ' "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">' . "\n";
			echo '<html xmlns="http://www.w3.org/1999/xhtml"';
			echo ' xml:lang="' . $lang_code . '" lang="' . $lang_code . '">' . "\n";
			echo '<head>' . "\n";
			echo '<title>' . __('galerie fermée') . ' - iGalerie</title>' . "\n";
			echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />' . "\n";
			echo '</head>' . "\n";
			echo '<body>' . "\n";
			echo '<div>' . "\n";
			echo nl2br(utils::tplHTMLFilter(
				utils::getLocale(utils::$config['gallery_closure_message'])
			)) . "\n";
			echo '</div>' . "\n";
			echo '</body>' . "\n";
			echo '</html>';
			die;
		}

		// Paramètres d'URL sans le numéro de page.
		self::$sectionRequest = (isset($_GET['q']))
			? preg_replace('`/page/\d+$`', '', $_GET['q'])
			: 'category/1-' . __('galerie');

		// Moteur de recherche.
		if (utils::$config['search'])
		{
			search::galleryPostGet();
		}

		// Récupération des différentes feuilles de styles disponibles.
		self::_getStyles();

		// Widget pour l'image aléatoire.
		self::_widgetImage();

		// Récupération des utilisateurs en ligne.
		self::_widgetOnlineUsers();

		// Opérations de base de données à effectuer une fois par jour.
		self::_dbDailyUpdate();

		// Modération des commentaires.
		utils::$config['comments_moderate'] = (utils::$config['users'])
			? users::$perms['gallery']['perms']['add_comments_mode'] == 0
			: (bool) utils::$config['comments_moderate'];

		// Récupération de toute l'arborescence de la galerie.
		if (utils::$config['widgets_params']['navigation']['status']
		 && utils::$config['widgets_params']['navigation']['items']['categories'])
		{
			map::getMap();
		}

		// Désactivation ou activation de certaines fonctionnalités
		// quand la permission d'accès à l'image originale est refusée.
		if ((utils::$config['users'] && !users::$perms['gallery']['perms']['image_original']))
		{
			utils::$config['basket'] = 0;
			utils::$config['diaporama_resize_gd'] = 0;
			utils::$config['download_zip_albums'] = 0;
			utils::$config['images_resize_method'] = 2;
			utils::$config['thumbs_stats_filesize'] = 0;
			utils::$config['thumbs_stats_size'] = 0;
			utils::$config['widgets_params']['options']['items']['image_size'] = 0;
			utils::$config['widgets_params']['options']['items']['thumbs_filesize'] = 0;
			utils::$config['widgets_params']['options']['items']['thumbs_size'] = 0;
			utils::$config['widgets_params']['stats_categories']['items']['filesize'] = 0;
		}

		// Préférences utilisateur.
		self::_preferences();

		// Suppression des pages à ne pas afficher.
		foreach (utils::$config['pages_order'] as $i => &$page)
		{
			switch ($page)
			{
				// Page du panier.
				case 'basket' :
					if (!utils::$config['pages_params'][$page]['status']
					 || !utils::$config['basket'])
					{
						unset(utils::$config['pages_order'][$i]);
					}
					break;

				// Page des commentaires.
				case 'comments' :
					if (!utils::$config['pages_params'][$page]['status']
					 || !utils::$config['comments'])
					{
						unset(utils::$config['pages_order'][$i]);
					}
					break;

				// Page du livre d'or.
				case 'guestbook' :
					if (!utils::$config['pages_params'][$page]['status'])
					{
						unset(utils::$config['pages_order'][$i]);
					}
					break;

				// Page des membres.
				case 'members' :
					if (!utils::$config['pages_params'][$page]['status']
					 || !utils::$config['users'])
					{
						unset(utils::$config['pages_order'][$i]);
					}
					break;

				// Page des tags.
				case 'tags' :
					if (!utils::$config['pages_params'][$page]['status']
					 || !utils::$config['tags'])
					{
						unset(utils::$config['pages_order'][$i]);
					}
					break;

				// Géolocalisation.
				case 'worldmap' :
					if (!utils::$config['pages_params'][$page]['status']
					 || !utils::$config['geoloc'])
					{
						unset(utils::$config['pages_order'][$i]);
					}
					break;

				default :
					if (!utils::$config['pages_params'][$page]['status'])
					{
						unset(utils::$config['pages_order'][$i]);
					}
					break;
			}
		}
	}

	/**
	 * Récupère depuis la base de données les informations utiles sur les
	 * catégories parentes à partir du chemin $path d'une catégorie.
	 *
	 * @param string $path
	 *	Chemin de la catégorie.
	 * @return void
	 */
	public static function getParents($path)
	{
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
						   cat_url,
						   cat_commentable,
						   cat_creatable,
						   cat_votable,
						   cat_uploadable,
						   cat_a_subalbs + cat_a_subcats AS cat_subs,
						   cat_style,
						   cat_orderby
					  FROM ' . CONF_DB_PREF . 'categories
					 WHERE ' . $sql . '
					   AND cat_status = "1"
				  ORDER BY LENGTH(cat_path) ASC';
			utils::$db->prepare($sql);
			utils::$db->executeQuery($params, PDO::FETCH_ASSOC);
			self::$parents = utils::$db->queryResult;

			// Détermine si les commentaires et les votes, ainsi
			// que l'ajout d'images et la création de catégories
			// sont désactivés pour au moins un parent.
			foreach (self::$parents as &$i)
			{
				if ($i['cat_commentable'] == 0)
				{
					self::$catCommentable = FALSE;
				}
				if ($i['cat_creatable'] == 0)
				{
					self::$catCreatable = FALSE;
				}
				if ($i['cat_style'])
				{
					self::$catStyle = $i['cat_style'];
				}
				if ($i['cat_votable'] == 0)
				{
					self::$catVotable = FALSE;
				}
				if ($i['cat_uploadable'] == 0)
				{
					self::$catUploadable = FALSE;
				}
			}
		}
	}

	/**
	 * Crée un rapport sur les actions effectuées.
	 *
	 * @param object|string $msg
	 * @return void
	 */
	public static function report($msg)
	{
		$msg = (is_object($msg))
			? explode(':', $msg->getMessage(), 2)
			: explode(':', $msg, 2);

		// On remplace les messages d'erreurs par un message générique.
		if ($msg[0] == 'error' || strstr($msg[1], 'error:'))
		{
			self::$report['error'] = __('Une erreur s\'est produite durant le'
				. ' traitement de votre requête. Vous êtes invité à signaler'
				. ' cette erreur à un administrateur afin que le problème soit'
				. ' corrigé dans les plus brefs délais.');

			trigger_error('Gallery error', E_USER_WARNING);
			return;
		}

		switch ($msg[0])
		{
			case 'warning' :
				self::$report[$msg[0]][] = $msg[1];
				break;

			default :
				self::$report[$msg[0]] = $msg[1];
		}
	}

	/**
	 * Détermine le style pour la catégorie ou l'image courante.
	 *
	 * @return void
	 */
	public static function style()
	{
		$style = FALSE;

		// Priorité aux préférences utilisateurs.
		if (utils::$config['widgets_params']['options']['status']
		 && utils::$config['widgets_params']['options']['items']['styles']
		 && self::_checkStyle(utils::$cookiePrefs->read('css')))
		{
			return;
		}

		// La catégorie ou l'image courante a-t-elle un style défini ?
		if (category::$infos !== NULL)
		{
			$infos =& category::$infos;
		}
		else if (image::$infos !== NULL)
		{
			$infos =& image::$infos;
		}
		else
		{
			return;
		}

		if (self::$catStyle)
		{
			$style = self::$catStyle;
		}

		self::_checkStyle($style);
	}

	/**
	 * Récupère les informations utiles des images et catégories
	 * à placer sur la carte du monde.
	 *
	 * @return void
	 */
	public static function worldmap()
	{
		// Récupération des images à géolocaliser.
		$sql = 'SELECT image_id,
					   image_path,
					   image_url,
					   image_desc,
					   image_width,
					   image_height,
					   image_tb_infos AS tb_infos,
					   image_lat,
					   image_long,
					   image_place,
					   image_name,
					   image_adddt,
					   cat_url
				  FROM ' . CONF_DB_PREF . 'images
			 LEFT JOIN ' . CONF_DB_PREF . 'categories USING (cat_id)
				 WHERE %s
				   AND image_lat IS NOT NULL
				   AND image_long IS NOT NULL';
		$result = sql::sqlProtect('image', $sql, PDO::FETCH_ASSOC);
		if ($result !== FALSE && $result['nb_result'] !== 0)
		{
			$coords = array();
			foreach ($result['query_result'] as &$infos)
			{
				if (!isset($coords[$infos['image_lat'] . ' ' . $infos['image_long']]))
				{
					$coords[$infos['image_lat'] . ' ' . $infos['image_long']] = array();
				}
				$coords[$infos['image_lat'] . ' ' . $infos['image_long']][] = $infos;
			}
			self::$worldmap['images'] = $coords;
		}

		// Récupération des catégories à géolocaliser.
		$sql = 'SELECT cat.cat_id,
					   thumb_id,
					   cat_path,
					   cat_name,
					   cat_desc,
					   cat_url,
					   cat_lat,
					   cat_long,
					   cat_place,
					   cat_tb_infos AS tb_infos,
					   cat_crtdt,
					   cat_filemtime,
					   image_id,
					   image_path,
					   image_width,
					   image_height,
					   image_adddt
				  FROM ' . CONF_DB_PREF . 'categories AS cat
			 LEFT JOIN ' . CONF_DB_PREF . 'images AS img
					ON cat.thumb_id = img.image_id
				 WHERE %s
				   AND cat.cat_id != 1
				   AND cat_lat IS NOT NULL
				   AND cat_long IS NOT NULL';
		$result = sql::sqlProtect('cat', $sql, PDO::FETCH_ASSOC);
		if ($result !== FALSE && $result['nb_result'] !== 0)
		{
			$coords = array();
			foreach ($result['query_result'] as &$infos)
			{
				if (!isset($coords[$infos['cat_lat'] . ' ' . $infos['cat_long']]))
				{
					$coords[$infos['cat_lat'] . ' ' . $infos['cat_long']] = array();
				}
				$coords[$infos['cat_lat'] . ' ' . $infos['cat_long']][] = $infos;
			}
			self::$worldmap['categories'] = $coords;
		}
	}



	/**
	 * Vérifie si un nom d'utilisateur, un mot ou une adresse IP
	 * se trouve dans une des listes noires.
	 *
	 * @param string $name
	 * @param string $email
	 * @param string $message
	 * @return array|boolean
	 */
	protected static function _checkBlacklists($name, $email = NULL, $message = NULL)
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
			return FALSE;
		}
		$blacklists = utils::$db->queryResult;

		// Vérification de l'adresse email.
		if (($r = self::_checkBlacklist(utils::removeAccents($email),
		$blacklists['blacklist_emails'])) !== TRUE)
		{
			self::report('warning:' . __('Cette adresse de courriel est bannie.'));
			return array('list' => 'emails', 'match' => $r);
		}

		// Vérification de l'adresse IP.
		if (($r = self::_checkBlacklist($_SERVER['REMOTE_ADDR'],
		$blacklists['blacklist_ips'])) !== TRUE)
		{
			self::report('warning:' . __('Votre adresse IP est bannie.'));
			return array('list' => 'ips', 'match' => $r);
		}

		// Vérification du nom d'utilisateur.
		if (($r = self::_checkBlacklist(utils::removeAccents($name),
		$blacklists['blacklist_names'])) !== TRUE)
		{
			self::report('warning:' . __('Ce nom d\'utilisateur est banni.'));
			return array('list' => 'names', 'match' => $r);
		}

		// Vérification du message.
		if ($message !== NULL && ($r = self::_checkBlacklist(utils::removeAccents($message),
		$blacklists['blacklist_words'], TRUE)) !== TRUE)
		{
			self::report('warning:' . __('Vous n\'êtes pas autorisé à envoyer ce message.'));
			return array('list' => 'words', 'match' => $r);
		}

		return TRUE;
	}

	/**
	 * Enregistre l'activité de l'utilisateur.
	 *
	 * @param string $action
	 * @param integer $user_id
	 * @param string $match
	 * @param array $post
	 * @return void
	 */
	protected static function _logUserActivity($action,
	$user_id = NULL, $match = NULL, $post = NULL)
	{
		// Identifiant de l'utilisateur.
		if ($user_id === NULL)
		{
			$user_id = users::$auth ? users::$infos['user_id'] : 2;
		}

		// Nouvelle catégorie.
		if ($action == 'category_create')
		{
			$post = array(
				'category' => isset($_POST['category'])
					? (int) $_POST['category'] : NULL,
				'desc' => isset($_POST['desc'])
					? utils::strLimit($_POST['desc'], 5000) : NULL,
				'name' => isset($_POST['name'])
					? utils::strLimit($_POST['name'], 128) : NULL,
				'type' => isset($_POST['type'])
					? utils::strLimit($_POST['type'], 3) : NULL
			);
		}

		// Ajout d'un commentaire.
		if (substr($action, 0, 7) == 'comment')
		{
			if (isset($_POST['preview']))
			{
				return;
			}

			$post = array();

			if (!users::$auth)
			{
				$post['author'] = isset($_POST['author'])
					? utils::strLimit($_POST['author'], 255) : NULL;
				$post['email'] = isset($_POST['email'])
					? utils::strLimit($_POST['email'], 255) : NULL;
				$post['website'] = isset($_POST['website'])
					? utils::strLimit($_POST['website'], 255) : NULL;
			}

			$post['message'] = isset($_POST['message'])
				? utils::strLimit($_POST['message'], 5000) : NULL;

			if (isset($_POST['rate'])
			&& in_array($_POST['rate'], array('1', '2', '3', '4', '5')))
			{
				$post['rate'] = (int) $_POST['rate'];
			}
		}

		// Page 'contact'.
		if (substr($action, 0, 7) == 'contact')
		{
			$post = array(
				'email' => isset($_POST['email'])
					? utils::strLimit($_POST['email'], 255) : NULL,
				'message' => isset($_POST['message'])
					? utils::strLimit($_POST['message'], 5000) : NULL,
				'name' => isset($_POST['name'])
					? utils::strLimit($_POST['name'], 255) : NULL,
				'subject' => isset($_POST['subject'])
					? utils::strLimit($_POST['subject'], 255) : NULL
			);
		}

		// Création de compte.
		if (substr($action, 0, 8) == 'register')
		{
			$post = array(
				'email' => isset($_POST['email'])
					? utils::strLimit($_POST['email'], 255) : NULL,
				'login' => isset($_POST['login'])
					? utils::strLimit($_POST['login'], 255) : NULL
			);
		}

		sql::logUserActivity($action, $user_id, $match, $post);
	}

	/**
	 * Pour des catégories protégées par mot de passe, vérifie que
	 * l'utilisateur possède le bon identifiant de session
	 * pour l'accès à l'objet courant.
	 * Effectue également la déconnexion aux catégories protégées.
	 *
	 * @param null|string $pwd
	 *	Hash du mot de passe de l'objet.
	 * @param boolean $redirect
	 *	Doit-on rediriger vers la page de demande de mot de passe ?
	 * @return boolean
	 */
	protected static function _passwordSession($pwd, $redirect = TRUE)
	{
		// Aucun mot de passe ou gestion de membres activée.
		if ($pwd === NULL || (utils::$config['users'] == 1
		&& utils::$config['users_locked_albums_access_mode'] === 'groups'))
		{
			return TRUE;
		}

		try
		{
			$pwd = explode(':', $pwd);

			// Récupération de l'identifiant de session que possède l'utilisateur.
			if (($session_token = user::getSessionCookieToken()) === FALSE)
			{
				throw new Exception();
			}

			// Déconnexion.
			if (isset($_POST['deconnect_object']))
			{
				$sql = 'DELETE
						  FROM ' . CONF_DB_PREF . 'passwords
						 USING ' . CONF_DB_PREF . 'passwords,
						       ' . CONF_DB_PREF . 'sessions
						 WHERE ' . CONF_DB_PREF . 'sessions.session_id
						     = ' . CONF_DB_PREF . 'passwords.session_id
						   AND password = :password
						   AND session_token = :session_token';
				$params = array('password' => $pwd[1], 'session_token' => $session_token);
				if (utils::$db->prepare($sql) === FALSE
				|| utils::$db->executeExec($params) === FALSE
				|| utils::$db->nbResult !== 1)
				{
					throw new Exception();
				}
			}

			// L'identifiant de session que possède l'utilisateur est-il valide ?
			$sql = 'SELECT 1
					  FROM ' . CONF_DB_PREF . 'passwords
				 LEFT JOIN ' . CONF_DB_PREF . 'sessions USING(session_id)
					 WHERE password = :password
					   AND session_token = :session_token
					   AND session_expire > NOW()';
			$params = array(
				'password' => $pwd[1],
				'session_token' => $session_token
			);
			if (utils::$db->prepare($sql) === FALSE
			|| utils::$db->executeQuery($params) === FALSE
			|| utils::$db->nbResult !== 1)
			{
				throw new Exception();
			}

			return self::$_passwordAuth = TRUE;
		}
		catch (Exception $e)
		{
			if ($redirect)
			{
				utils::redirect($_GET['q'] . '/pass', TRUE);
			}
			return FALSE;
		}
	}

	/**
	 * Vérification d'un formulaire avec reCAPTCHA.
	 *
	 * @param string $action
	 * @param boolean $check
	 *	Doit-on effectuer la vérification ?
	 * @return mixed
	 *	NULL : aucun code entré
	 *	TRUE : code entré correct
	 *	FALSE : code entré incorrect
	 */
	protected static function _recaptcha($action, $check = TRUE)
	{
		require_once(dirname(__FILE__) . '/includes/recaptcha.php');

		if (!$check)
		{
			return TRUE;
		}

		if (isset($_POST['recaptcha_challenge_field'])
		 && isset($_POST['recaptcha_response_field']))
		{
			$resp = recaptcha_check_answer(
				utils::$config['recaptcha_private_key'],
				$_SERVER['REMOTE_ADDR'],
				$_POST['recaptcha_challenge_field'],
				$_POST['recaptcha_response_field']
			);

			if (!$resp->is_valid)
			{
				self::report('warning:' . __('Code captcha invalide.'));

				// Log d'activité.
				self::_logUserActivity($action . '_reject_captcha');
			}

			return $resp->is_valid;
		}
	}




	/**
	 * Vérifie si une portion de chaîne correspond à un motif d'une liste noire.
	 *
	 * @param string $str
	 *	Chaîne à vérifier.
	 * @param array $list
	 *	Liste noire.
	 * @param boolean $word
	 *	La liste noire porte-t-elle sur les mots d'un texte ?
	 * @return boolean|string
	 *  Retourne TRUE si aucune entrée de la liste noire n'a été
	 *  trouvée dans la chaîne, ou l'entrée de la liste noire sinon.
	 */
	private static function _checkBlacklist($str, &$list, $word = FALSE)
	{
		$list = preg_split('`[\r\n]+`', $list, -1, PREG_SPLIT_NO_EMPTY);

		foreach ($list as &$entry)
		{
			$ent = preg_quote($entry);
			$ent = str_replace(array('\\*', '\\?'), array('.*', '.'), $ent);
			$ent = utils::removeAccents($ent);

			$regex = ($word)
				? '(?:^|\W)' . $ent . '(?:$|\W)'
				: '^' . $ent . '$';
			if (mb_eregi($regex, $str))
			{
				return $entry;
			}
		}

		return TRUE;
	}

	/**
	 * Vérifie si le style $style existe bien
	 * et change la configuration en conséquence.
	 *
	 * @param string $style
	 * @return boolean
	 */
	private static function _checkStyle($style)
	{
		if (empty($style))
		{
			return FALSE;
		}

		self::_getStyles(TRUE);

		if (!in_array($style, self::$styles))
		{
			return FALSE;
		}

		utils::$config['theme_style'] = $style;
		return TRUE;
	}

	/**
	 * Récupération des différents styles disponibles.
	 *
	 * @param boolean $forced
	 *	Forcer la récupération des styles ?
	 * @return void
	 */
	private static function _getStyles($forced = FALSE)
	{
		if (utils::$config['widgets_params']['options']['items']['styles'] == 0 && !$forced)
		{
			return;
		}

		if (self::$styles !== NULL)
		{
			return;
		}

		self::$styles = utils::getStyles();
	}

	/**
	 * Détecte la langue de l'utilisateur selon
	 * 1) le choix de l'utilisateur parmi une liste (post)
	 * 2) la langue enregistrée dans un cookie (cookie)
	 * 3) la langue du navigateur (client)
	 *
	 * @return void
	 */
	private static function _langDetect()
	{
		if (utils::$config['users'] && users::$auth)
		{
			return;
		}

		// Post.
		if (utils::$config['lang_switch']
		&& isset($_POST['change_lang']) && isset($_POST['new_lang'])
		&& preg_match('`^[a-z]{2}_[A-Z]{2}$`', $_POST['new_lang'])
		&& isset(utils::$config['locale_langs'][$_POST['new_lang']]))
		{
			utils::$userLang = $_POST['new_lang'];
			utils::$cookiePrefs->add('lang', $_POST['new_lang']);
		}

		// Cookie.
		else if (utils::$config['lang_switch']
		&& preg_match('`^[a-z]{2}_[A-Z]{2}$`', utils::$cookiePrefs->read('lang'))
		&& isset(utils::$config['locale_langs'][utils::$cookiePrefs->read('lang')]))
		{
			utils::$userLang = utils::$cookiePrefs->read('lang');
		}

		// Client.
		else if (utils::$config['lang_client'])
		{
			utils::detectClientLang();
		}
	}

	/**
	 * Préférences concernant les cases à cocher.
	 *
	 * @param array $prefs
	 *	Liste des paramètres utiles à cette opération.
	 * @return void
	 */
	private static function _prefCheckbox($prefs)
	{
		foreach ($prefs as $pref => &$infos)
		{
			// Si l'autorisation pour personnaliser cette option
			// n'est pas accordée, on passe à la suivante.
			if (!utils::$config['widgets_params']['options']['items'][$infos['allow']])
			{
				continue;
			}

			$p = FALSE;

			// Lecture de l'information envoyée par POST.
			if (isset($_POST['thumbs_infos']))
			{
				$p = (isset($_POST[$pref])) ? '1' : '0';
			}

			// Lecture de l'information envoyée par COOKIE.
			if ($p === FALSE)
			{
				$p = utils::$cookiePrefs->read($infos['cookie']);
				if (!preg_match('`^[01]$`', $p))
				{
					$p = FALSE;
				}
			}

			// Si l'option en provenance de POST ou de COOKIE
			// a été validée, on modifie l'option dans la config.
			if ($p !== FALSE)
			{
				utils::$config[$infos['pref']] = $p;
				if (isset($_POST['thumbs_infos']))
				{
					utils::$cookiePrefs->add($infos['cookie'], $p);
				}
			}
		}
	}

	/**
	 * Change les valeurs de certains paramètres de la config
	 * selon les préférences utilisateurs.
	 *
	 * @return void
	 */
	private static function _preferences()
	{
		if (utils::$config['widgets_params']['options']['status'])
		{
			// Cases à cocher.
			$prefs = array();
			if ($_GET['album_page']
			|| $_GET['section'] == 'category')
			{
				if ($_GET['album_page'])
				{
					$prefs = array(
						'comments' => 'sm',
						'date' => 'sd',
						'filesize' => 'sf',
						'hits' => 'sh',
						'image_title' => 'st',
						'size' => 'ss',
						'votes' => 'sv'
					);
				}
				else
				{
					$prefs = array(
						'albums' => 'sa',
						'category_title' => 'sc',
						'comments' => 'sm',
						'filesize' => 'sf',
						'hits' => 'sh',
						'images' => 'si',
						'votes' => 'sv'
					);
				}
				foreach ($prefs as $pref => $cookie)
				{
					$prefs['thumbs_' . $pref] = array(
						'pref' => 'thumbs_stats_' . $pref,
						'allow' => 'thumbs_' . $pref,
						'cookie' => $cookie
					);
					unset($prefs[$pref]);
				}
			}
			if ($_GET['album_page']
			|| $_GET['section'] == 'category'
			|| $_GET['section'] == 'sitemap')
			{
				$prefs = array_merge($prefs, array(
					'thumbs_recent' => array(
						'pref' => 'recent_images',
						'allow' => 'recent',
						'cookie' => 'sr'
					)
				));
				self::_prefCheckbox($prefs);
			}

			// Champs textes, boutons radios et style.
			self::_prefText(array(
				'thumbs_alb_nb' => array(
					'regex' => '(?:\d{1,4})',
					'pref' => 'thumbs_alb_nb',
					'allow' => 'nb_thumbs',
					'cookie' => 'tc'
				),
				'recent_days' => array(
					'regex' => '(?:[1-9]|\d{2,3})',
					'pref' => 'recent_images_time',
					'allow' => 'recent',
					'cookie' => 'rd'
				),
				'image_size' => array(
					'regex' => '[01]',
					'pref' => 'images_resize',
					'allow' => 'image_size',
					'cookie' => 'im'
				),
				'image_height' => array(
					'regex' => '(?:[1-9]|\d{2,6})',
					'pref' => 'images_resize_html_height',
					'allow' => 'image_size',
					'cookie' => 'ih'
				),
				'image_width' => array(
					'regex' => '(?:[1-9]|\d{2,6})',
					'pref' => 'images_resize_html_width',
					'allow' => 'image_size',
					'cookie' => 'iw'
				),
				'style' => array(
					'regex' => '(?:\*|[a-z_-]{1,48})',
					'pref' => 'theme_style',
					'allow' => 'styles',
					'cookie' => 'css'
				)
			));

			// Critères de tri des images.
			sql::prefOrderBy(utils::$cookiePrefs);
		}

		// Durée limite des images récentes.
		self::$recentImagesLimit =
			self::$time - ((int) utils::$config['recent_images_time'] * 86400);
	}

	/**
	 * Préférences concernant les champs textes.
	 *
	 * @param array $prefs
	 *	Liste des paramètres utiles à cette opération.
	 * @return void
	 */
	private static function _prefText($prefs)
	{
		foreach ($prefs as $pref => &$infos)
		{
			// Si l'autorisation pour personnaliser cette option
			// n'est pas accordée, on passe à la suivante.
			if (!utils::$config['widgets_params']['options']['items'][$infos['allow']])
			{
				continue;
			}

			$p = FALSE;

			// Lecture de l'information envoyée par POST.
			if (isset($_POST[$pref]))
			{
				$p = $_POST[$pref];
				if (!preg_match('`^' . $infos['regex'] . '$`', $p))
				{
					$p = FALSE;
				}
			}

			// Lecture de l'information envoyée par COOKIE.
			if ($p === FALSE)
			{
				$p = utils::$cookiePrefs->read($infos['cookie']);
				if (!preg_match('`^' . $infos['regex'] . '$`', $p))
				{
					$p = FALSE;
				}
			}

			// Si l'option en provenance de POST ou de COOKIE
			// a été validée, on modifie l'option dans la config.
			if ($p !== FALSE)
			{
				if ($p !== '*')
				{
					utils::$config[$infos['pref']] = $p;
				}
				if (!empty($_POST))
				{
					utils::$cookiePrefs->add($infos['cookie'], $p);
				}
			}
		}
	}

	/**
	 * Nettoyage de la base de données et autres
	 * opérations à n'effectuer qu'une fois par jour.
	 *
	 * @return void
	 */
	private static function _dbDailyUpdate()
	{
		if (utils::$config['db_daily_update'] == date('Y-m-d'))
		{
			return;
		}

		$sql = array
		(
			// Historique.
			'INSERT IGNORE INTO ' . CONF_DB_PREF . 'history (
				history_date, history_albums, history_images, history_size,
				history_hits, history_comments, history_votes, history_rate,
				history_favorites, history_tags, history_admins, history_members)
				SELECT ADDDATE(NOW(), -1),
					   cat_a_albums + cat_d_albums,
					   cat_a_images + cat_d_images,
					   cat_a_size + cat_d_size,
					   cat_a_hits + cat_d_hits,
					   cat_a_comments + cat_d_comments,
					   cat_a_votes + cat_d_votes,
					   cat_a_rate + cat_d_rate,
					   (SELECT COUNT(*)
						  FROM ' . CONF_DB_PREF . 'favorites),
					   (SELECT COUNT(*)
						  FROM ' . CONF_DB_PREF . 'tags),
					   (SELECT COUNT(*)
						  FROM ' . CONF_DB_PREF . 'users AS u,
							   ' . CONF_DB_PREF . 'groups AS g
						 WHERE u.group_id = g.group_id
						   AND group_admin = "1"
						   AND user_id != "2"),
					   (SELECT COUNT(*)
						  FROM ' . CONF_DB_PREF . 'users AS u,
							   ' . CONF_DB_PREF . 'groups AS g
						 WHERE u.group_id = g.group_id
						   AND group_admin = "0"
						   AND user_id != "2")
				  FROM ' . CONF_DB_PREF . 'categories
				 WHERE cat_id = "1"',

			// Suppression des sessions périmées.
			'DELETE
			   FROM ' . CONF_DB_PREF . 'sessions
			  WHERE session_expire < NOW()',

			// Suppression des utilisateurs en attente dont
			// la date de validation par courriel a expirée.
			'DELETE
			   FROM ' . CONF_DB_PREF . 'users
			  WHERE user_status = "-2"
				AND ADDDATE(user_crtdt, 1) < NOW()',

			// Suppression des recherches périmées.
			'DELETE
			   FROM ' . CONF_DB_PREF . 'search
			  WHERE ADDDATE(search_date, 1) < NOW()',

			// Mise à jour de la date de dernière mise à jour.
			'UPDATE ' . CONF_DB_PREF . 'config
				SET conf_value = "' . date('Y-m-d') . '"
			  WHERE conf_name = "db_daily_update"
			  LIMIT 1'
		);

		// Suppression des logs utilisateurs.
		if (utils::$config['users_log_activity_delete'])
		{
			$days = (int) utils::$config['users_log_activity_delete_days'];
			$sql[] = 'DELETE
						FROM ' . CONF_DB_PREF . 'users_logs
					   WHERE TO_DAYS(NOW()) - TO_DAYS(log_date) > ' . $days;
		}

		utils::$db->exec($sql);
	}

	/**
	 * Récupération des informations utiles pour le widget "image".
	 *
	 * @return void
	 */
	private static function _widgetImage()
	{
		$widget_image =& utils::$config['widgets_params']['image'];

		// Widget désactivé ?
		if (!$widget_image['status'])
		{
			return;
		}

		$sql_where = '';

		// Dernière(s) image(s).
		if ($widget_image['params']['mode'] == 'last')
		{
			$sql_where .= ' ORDER BY image_adddt DESC';
		}

		// Image(s) "aléatoire".
		else if ($widget_image['params']['mode'] == 'random')
		{
			// Limitation de la recherche dans les albums.
			if (is_array($widget_image['params']['albums'])
			&& !empty($widget_image['params']['albums'][0]))
			{
				$albums = array_map('intval', $widget_image['params']['albums']);
				$sql_where = ' AND (cat_id IN (' . implode(', ', $albums) . '))';
			}

			$sql_where .= ' ORDER BY RAND()';
		}

		// Image(s) "fixe".
		else if ($widget_image['params']['mode'] == 'fixed'
		&& !empty($widget_image['params']['images']))
		{
			$images = array_map('intval', $widget_image['params']['images']);
			$sql_where .= ' AND image_id IN (' . implode(', ', $images) . ')';
			$sql_where .= ' ORDER BY FIELD(image_id, ' . implode(', ', $images) . ')';
		}

		// On récupère les informations de l'image
		// et de l'album dans lequel elle se trouve.
		$sql = 'SELECT image_id,
					   image_path,
					   image_width,
					   image_height,
					   image_name,
					   image_url,
					   image_adddt,
					   cat.cat_id,
					   cat.cat_url,
					   cat.cat_name
				  FROM ' . CONF_DB_PREF . 'images AS img
			 LEFT JOIN ' . CONF_DB_PREF . 'categories AS cat USING (cat_id)
				 WHERE %s '. $sql_where . '
				 LIMIT ' . (int) $widget_image['params']['nb_thumbs'];
		$result = sql::sqlProtect('image', $sql, PDO::FETCH_ASSOC);
		if ($result !== FALSE && $result['nb_result'] !== 0)
		{
			self::$widgetImage = $result['query_result'];
		}
	}

	/**
	 * Récupération des informations utiles pour le widget "qui est en ligne ?".
	 *
	 * @return void
	 */
	private static function _widgetOnlineUsers()
	{
		$online_users =& utils::$config['widgets_params']['online_users'];

		// Doit-on afficher le widget ?
		if (!utils::$config['users']
		 || !users::$perms['gallery']['perms']['members_list']
		 || !$online_users['status'])
		{
			return;
		}

		// Critère de tri.
		$sql_order_by = utils::filters($online_users['params']['order_by'], 'order_by');
		$sql_order_by = explode(' ', $sql_order_by);
		$sql_order_by[0] = 'LOWER(' . $sql_order_by[0] . ')';

		// Récupération des utilisateurs.
		$sql = 'SELECT user_id,
					   user_login,
					   user_lastvstdt
				  FROM ' . CONF_DB_PREF . 'users
			 LEFT JOIN ' . CONF_DB_PREF . 'sessions USING(session_id)
				 WHERE session_expire > NOW()
				   AND user_lastvstdt >= NOW()-' . (int) $online_users['params']['duration'] . '
				   AND user_status = "1"
			  ORDER BY ' . implode(' ', $sql_order_by) . '
			     LIMIT 1000';
		if (utils::$db->query($sql, PDO::FETCH_ASSOC) !== FALSE
		&& utils::$db->nbResult > 0)
		{
			self::$widgetOnlineUsers = utils::$db->queryResult;
		}
	}
}

/**
 * Opérations concernant les albums et
 * les sections utilisant la page des albums.
 */
class album extends gallery
{
	/**
	 * Informations utiles de catégories parentes,
	 * indexées sur la colonne 'cat_path'.
	 *
	 * @var array
	 */
	public static $categoriesParents = array();

	/**
	 * Poids des images de la section.
	 *
	 * @var integer
	 */
	public static $filesize;

	/**
	 * Informations utiles d'une liste d'éléments.
	 *
	 * @var array
	 */
	public static $items = array();

	/**
	 * Nombre d'images de la section.
	 *
	 * @var integer
	 */
	public static $nbImages;

	/**
	 * Nombre de pages de la section.
	 *
	 * @var integer
	 */
	public static $nbPages;

	/**
	 * Nombre de vignettes par page.
	 *
	 * @var integer
	 */
	public static $nbThumbs;

	/**
	 * Informations utiles des albums trouvés lors de la recherche.
	 *
	 * @var array
	 */
	public static $searchAlbums = array();

	/**
	 * Informations utiles des catégories trouvées lors de la recherche.
	 *
	 * @var array
	 */
	public static $searchCategories = array();

	/**
	 * Informations utiles des vignettes.
	 *
	 * @var array
	 */
	public static $thumbs;

	/**
	 * Indique s'il y a des informations à afficher pour les vignettes.
	 *
	 * @var boolean
	 */
	public static $thumbsInfos = FALSE;



	/**
	 * Premier argument de la clause LIMIT pour la récupération des
	 * informations des vignettes.
	 *
	 * @var integer
	 */
	private static $_thumbsSqlStart;



	/**
	 * Supprime toutes les images du panier de l'utilisateur.
	 *
	 * @return void
	 */
	public static function basketEmpty()
	{
		if (empty($_POST['basket_empty'])
		|| !utils::antiCSRFTokenCheck(utils::$cookiePrefs))
		{
			return;
		}

		try
		{
			// Début de la transaction.
			if (!utils::$db->transaction())
			{
				throw new Exception();
			}

			// Vidage du panier.
			if (users::$auth)
			{
				$sql = 'DELETE
						  FROM ' . CONF_DB_PREF . 'basket
						 WHERE user_id = ' . (int) users::$infos['user_id'];
			}
			else if (user::getSessionCookieToken())
			{
				$sql = 'DELETE
						  FROM ' . CONF_DB_PREF . 'basket
						 USING ' . CONF_DB_PREF . 'basket,
							   ' . CONF_DB_PREF . 'sessions
						 WHERE ' . CONF_DB_PREF . 'basket.session_id
							 = ' . CONF_DB_PREF . 'sessions.session_id
						   AND ' . CONF_DB_PREF . 'sessions.session_token
							 = "' . user::getSessionCookieToken() . '"';
			}
			else
			{
				return;
			}
			if (!utils::$db->exec($sql))
			{
				throw new Exception();
			}

			// Log d'activité.
			self::_logUserActivity('basket_empty');

			// Exécution de la transaction.
			if (!utils::$db->commit())
			{
				throw new Exception();
			}
		}
		catch (Exception $e)
		{
			self::report('error');
		}
	}

	/**
	 * Détermine le nombre d'images contenues dans la section courante.
	 *
	 * @param string $sql_where
	 *	Clause WHERE de la requête SQL.
	 * @param array $params
	 *	Paramètres pour la requête préparée.
	 * @return void
	 */
	public static function countImages($sql_where = NULL, $params = array())
	{
		// Pour la section de date d'ajout, si la date de mise à jour de la catégorie est
		// la même que celle de sa création, toutes les images de la catégorie ont été
		// ajoutées le même jour, donc inutile d'effectuer une requête SQL.
		if ($_GET['section'] == 'album'
		|| ($_GET['section'] == 'date-added'
		&& category::$infos['cat_crtdt'] == category::$infos['cat_lastadddt']
		&& $_GET['date'] == substr(category::$infos['cat_crtdt'], 0, 10)))
		{
			self::$nbImages = (int) category::$infos['cat_a_images'];
			return;
		}

		$sql_where = ($sql_where)
			? $sql_where
			: sql::thumbsSQLWhere(category::$infos, users::$infos);
		$sql = 'SELECT COUNT(*)
				  FROM ' . sql::imagesSQLFrom('section') . '
				 WHERE ' . str_replace('%', '%%', $sql_where)
						 . ' %s';
		$result = sql::sqlProtect('image', $sql, 'value', self::$_passwordAuth, $params);
		self::$nbImages = (int) $result['query_result'];
	}

	/**
	 * Récupération de la liste des catégories dans lesquels se trouvent des images
	 * présentes dans les favoris de l'utilisateur.
	 *
	 * @return void
	 */
	public static function getUserSectionCategories()
	{
		switch ($_GET['section'])
		{
			case 'user-comments' :
				$sql_table = 'com';
				break;

			case 'user-favorites' :
				$sql_table = 'fav';
				break;

			case 'user-images' :
				$sql_table = 'img';
				break;
		}
		$sql = 'SELECT DISTINCT(cat.cat_id),
					   cat_name,
					   cat_url,
					   cat_path,
					   cat_filemtime
				  FROM ' . sql::imagesSQLFrom('section') . '
				 WHERE ' . $sql_table . '.user_id = ' . (int) $_GET['user_id'] . ' AND %s
			  ORDER BY cat_path ASC';
		$fetch_style = array('fetch' => PDO::FETCH_ASSOC, 'column' => 'cat_id');
		$categories = sql::sqlProtect('cat', $sql, $fetch_style);
		self::$items = $categories['query_result'];

		// Récupération des informations des parents des catégories.
		self::_getCategoriesParents(self::$items);

		$list = array();
		foreach ($categories['query_result'] as &$infos)
		{
			$parents = '';
			if (isset(self::$categoriesParents[dirname($infos['cat_path'])]))
			{
				$parents = self::$categoriesParents[dirname($infos['cat_path'])]['cat_name'];
				if (!isset($list[$parents]))
				{
					$list[$parents] = self::$categoriesParents[dirname($infos['cat_path'])];
				}
				$parents .= utils::$config['level_separator'];
			}
			$list[$parents . $infos['cat_name']] = $infos;
		}
		ksort($list);
		self::$items = $list;
	}

	/**
	 * Recherche de catégories.
	 *
	 * @return void
	 */
	public static function searchCategories()
	{
		// On ne fait pas de recherche de catégories
		// si l'un des critères suivant à été activé.
		if (isset($_GET['search_date'])
		 || isset($_GET['search_filesize'])
		 || isset($_GET['search_size'])
		 || isset($_GET['search_brands'])
		 || isset($_GET['search_models']))
		{
			return;
		}

		// Clause WHERE.
		if (isset($_GET['search_image_name']))
		{
			$_GET['search_cat_name'] = 1;
		}
		if (isset($_GET['search_image_desc']))
		{
			$_GET['search_cat_desc'] = 1;
		}
		$sql_where = search::getSQLWhere(array('cat_desc', 'cat_name'));
		if (!is_array($sql_where))
		{
			return;
		}

		// Recherche par catégories.
		$sql_where['sql'] = str_replace('image_path LIKE', 'cat_path LIKE', $sql_where['sql']);

		// Récupération des catégories.
		$sql = 'SELECT cat_id,
					   cat_name,
					   cat_url,
					   cat_path,
					   cat_filemtime
				  FROM ' . CONF_DB_PREF . 'categories
				 WHERE %s
				   AND ' . $sql_where['sql'] . '
				   AND cat_id > 1
			  ORDER BY LOWER(cat_name) ASC';
		$fetch_style = array('fetch' => PDO::FETCH_ASSOC, 'column' => 'cat_id');
		$categories = sql::sqlProtect('cat', $sql, $fetch_style, FALSE, $sql_where['params']);
		if ($categories === FALSE || $categories['nb_result'] === 0)
		{
			return;
		}
		foreach ($categories['query_result'] as &$infos)
		{
			if ($infos['cat_filemtime'] === NULL)
			{
				self::$searchCategories[] = $infos;
			}
			else
			{
				self::$searchAlbums[] = $infos;
			}
		}

		// Récupération des informations des parents des catégories.
		self::_getCategoriesParents($categories['query_result']);
	}

	/**
	 * Paramètres pour la gestion des pages.
	 *
	 * @return void
	 */
	public static function pages()
	{
		// Nombre de vignettes par page.
		self::$nbThumbs = (int) utils::$config['thumbs_alb_nb'];

		// Nombre de pages de la section.
		self::$nbPages = ceil((int) self::$nbImages / self::$nbThumbs);

		// Premier argument de la clause LIMIT pour la récupération
		// des informations des vignettes, qui est fonction de la page courante.
		self::$_thumbsSqlStart = self::$nbThumbs * ($_GET['page'] - 1);
	}

	/**
	 * Récupère les informations utiles des images
	 * pour l'affichage des vignettes.
	 *
	 * @param string $sql_where
	 *	Clause WHERE de la requête SQL.
	 * @param array $params
	 *	Paramètres pour la requête préparée.
	 * @return integer
	 */
	public static function getImages($sql_where = NULL, $params = array())
	{
		// Détermine tous les chiffres concernant les vignettes.
		self::pages();

		// Récupération des informations des images.
		$sql_select = '';
		if (utils::$config['users'])
		{
			$params['user_id'] = (int) users::$infos['user_id'];
			$sql_select .= ',
				(SELECT 1
				   FROM ' . CONF_DB_PREF . 'favorites
				  WHERE image_id = img.image_id
				    AND user_id = :user_id
				  LIMIT 1) AS in_favorites';
		}
		if (utils::$config['basket'])
		{
			if (users::$auth)
			{
				$sql_select .= ',
					(SELECT 1
					   FROM ' . CONF_DB_PREF . 'basket
					  WHERE image_id = img.image_id
					    AND user_id = :user_id
					  LIMIT 1) AS in_basket';
			}
			else
			{
				$sql_select .= ',
					(SELECT 1
					   FROM ' . CONF_DB_PREF . 'basket AS b
				  LEFT JOIN ' . CONF_DB_PREF . 'sessions AS s USING (session_id)
					  WHERE image_id = img.image_id
					    AND session_token = "' . user::getSessionCookieToken() . '"
					  LIMIT 1)
						 AS in_basket';
			}
		}
		$sql_where = ($sql_where)
			? $sql_where
			: sql::thumbsSQLWhere(category::$infos, users::$infos);
		$sql = 'SELECT img.image_id,
					   image_path,
					   image_url,
					   image_width,
					   image_height,
					   image_tb_infos AS tb_infos,
					   image_filesize,
					   image_rotation,
					   image_name,
					   image_desc,
					   image_adddt,
					   image_hits,
					   image_comments,
					   image_votes,
					   image_rate,
					   cat_url'
					   . $sql_select . '
				  FROM ' . sql::imagesSQLFrom('section') . '
				 WHERE ' . str_replace('%', '%%', $sql_where) . ' %s
			  ORDER BY ' . sql::imagesSQLOrder(category::$infos) . '
				 LIMIT ' . self::$_thumbsSqlStart . ',' . self::$nbThumbs;
		$fetch_style = array('fetch' => PDO::FETCH_ASSOC, 'column' => 'image_id');
		$auth = ($_GET['section'] == 'album' && category::$infos['cat_password'] == '')
			? TRUE
			: self::$_passwordAuth;
		$result = sql::sqlProtect('image', $sql, $fetch_style, $auth, $params);

		// Erreur.
		if ($result === FALSE)
		{
			return -1;
		}

		// Aucun résultat, première page et page d'album.
		if ($result['nb_result'] === 0 && $_GET['page'] == 1 && $_GET['section'] == 'album')
		{
			return 0;
		}

		// Si aucun résultat et page autre que la première, on redirige vers
		// la première page de la section courante.
		// Ce cas de figure peut arriver lorsqu'un utilisateur modifie le
		// nombre de vignettes par page ou lorsqu'il demande un numéro de
		// page plus grand que le nombre de pages que contient la section.
		if ($result['nb_result'] === 0 && $_GET['page'] > 1)
		{
			utils::redirect(self::$sectionRequest, TRUE);
		}

		// Tout s'est bien passé : on récupère le résultat de la requête.
		self::$thumbs = $result['query_result'];

		// Y a-t-il des informations à afficher pour chaque vignette ?
		$display_date_added = utils::$config['thumbs_stats_date'];
		$display_hits = utils::$config['thumbs_stats_hits'];
		$display_comments = utils::$config['thumbs_stats_comments']
			&& utils::$config['comments'];
		$display_votes = utils::$config['thumbs_stats_votes']
			&& utils::$config['votes'];

		switch ($_GET['section'])
		{
			case 'comments-stats' :
				$display_comments = TRUE;
				break;

			case 'hits' :
				$display_hits = TRUE;
				break;

			case 'recent-images' :
				$display_date_added = TRUE;
				break;

			case 'votes' :
				$display_votes = TRUE;
				break;
		}

		if ($display_comments
		|| $display_date_added
		|| utils::$config['thumbs_stats_filesize']
		|| $display_hits
		|| $display_votes
		|| utils::$config['thumbs_stats_size']
		|| utils::$config['thumbs_stats_image_title'])
		{
			self::$thumbsInfos = TRUE;
		}

		// Récupération du poids du panier.
		if (utils::$config['basket'] && $_GET['section'] == 'basket')
		{
			if (users::$auth)
			{
				$sql = 'SELECT SUM(image_filesize)
						  FROM ' . CONF_DB_PREF . 'basket AS b,
							   ' . CONF_DB_PREF . 'images AS i
						 WHERE b.image_id = i.image_id
						   AND b.user_id = ' . (int) users::$infos['user_id'];
			}
			else
			{
				$sql = 'SELECT SUM(image_filesize)
						  FROM ' . CONF_DB_PREF . 'basket AS b,
							   ' . CONF_DB_PREF . 'images AS i,
							   ' . CONF_DB_PREF . 'sessions AS s
						 WHERE b.image_id = i.image_id
						   AND s.session_id = b.session_id
						   AND s.session_token = "' . user::getSessionCookieToken() . '"';
			}
			if (utils::$db->query($sql, 'value') !== FALSE
			&& utils::$db->nbResult === 1)
			{
				self::$filesize =(int) utils::$db->queryResult;
			}
		}

		return 1;
	}

	/**
	 * Détermine le nombre d'images récentes de l'album.
	 *
	 * @return void
	 */
	public static function recentImages()
	{
		category::$infos['cat_recent_images'] = 0;

		// S'il ne faut pas mettre en évidence les images récentes
		// ou s'il ne faut pas indiquer le nombre d'images récentes dans les statistiques
		// ou si l'album ne contient aucune nouvelle image, on arrête là.
		if ($_GET['section'] != 'album'
		|| utils::$config['recent_images'] != 1
		|| utils::$config['widgets_params']['stats_categories']['items']['recents'] != 1
		|| strtotime(category::$infos['cat_lastadddt']) < self::$recentImagesLimit)
		{
			return;
		}

		// Si l'album ne possède qu'une page,
		// inutile d'effectuer une requête SQL.
		if (self::$nbPages == 1)
		{
			foreach (self::$thumbs as &$i)
			{
				if (strtotime($i['image_adddt']) > self::$recentImagesLimit)
				{
					category::$infos['cat_recent_images']++;
				}
			}
			return;
		}

		// Requête SQL pour déterminer le nombre d'images
		// récemment ajoutées dans l'album.
		$limit = date('Y-m-d H:i:s', self::$recentImagesLimit);
		$sql = 'SELECT COUNT(*)
				  FROM ' . CONF_DB_PREF . 'images
				 WHERE cat_id = ' . (int) category::$infos['cat_id'] . '
				   AND image_adddt > "' . $limit . '"
				   AND image_status = "1"';
		if (utils::$db->query($sql, 'value') !== FALSE
		&& utils::$db->nbResult === 1)
		{
			category::$infos['cat_recent_images'] = utils::$db->queryResult;
		}
	}



	/**
	 * Récupère les informations utiles des catégories parentes de $categories.
	 *
	 * @param array $categories
	 *	Informations utiles des catégories.
	 * @return void
	 */
	private static function _getCategoriesParents($categories)
	{
		// On récupère le titre des catégories parentes de chaque catégorie trouvée.
		$parents = array();
		foreach ($categories as &$infos)
		{
			$path = dirname($infos['cat_path']);
			while ($path != '.')
			{
				$parents[$path] = 1;
				$path = dirname($path);
			}
		}
		if (count($parents) > 0)
		{
			$params = array_keys($parents);
			$sql = 'SELECT cat_id,
						   cat_path,
						   cat_name,
						   cat_url,
						   cat_filemtime
					  FROM ' . CONF_DB_PREF . 'categories
					 WHERE cat_path IN (?' . str_repeat(', ?', count($params) - 1) . ')
					   AND cat_id > 1
				  ORDER BY LENGTH(cat_path) DESC';
			$fetch_style = array('fetch' => PDO::FETCH_ASSOC, 'column' => 'cat_path');
			if (utils::$db->prepare($sql) === FALSE
			|| utils::$db->executeQuery($params, $fetch_style) === FALSE
			|| utils::$db->nbResult === 0)
			{
				return;
			}
			self::$categoriesParents = utils::$db->queryResult;

			foreach (self::$categoriesParents as $cat_path => &$infos)
			{
				$path = dirname($cat_path);
				$path_name = utils::getLocale($infos['cat_name']);

				while ($path != '.')
				{
					$path_name =
						utils::getLocale(self::$categoriesParents[$path]['cat_name'])
						. utils::$config['level_separator']
						. $path_name;
					$path = dirname($path);
				}

				$infos['cat_name'] = $path_name;
			}
		}
	}
}

/**
 * Opérations concernant les catégories.
 */
class category extends gallery
{
	/**
	 * Informations de la catégorie.
	 *
	 * @var array
	 */
	public static $infos;

	/**
	 * Nombre de pages de la catégorie.
	 *
	 * @var integer
	 */
	public static $nbPages;

	/**
	 * Nombre de vignettes par page.
	 *
	 * @var integer
	 */
	public static $nbThumbs;

	/**
	 * Informations utiles des catégories voisines.
	 *
	 * @var array
	 */
	public static $neighbours;

	/**
	 * Position de la catégorie actuelle par rapport à ses voisines.
	 *
	 * @var integer
	 */
	public static $neighboursPosition;

	/**
	 * Page de la catégorie parente où se situe la catégorie actuelle.
	 *
	 * @var integer
	 */
	public static $parentPage;

	/**
	 * Informations utiles des vignettes.
	 *
	 * @var array
	 */
	public static $thumbs;

	/**
	 * Détermine s'il y a des informations à afficher pour les vignettes.
	 *
	 * @var boolean
	 */
	public static $thumbsInfos = FALSE;

	/**
	 * Identifiant et nom de la catégorie servant pour la création
	 * du paramètre d'URL.
	 * Exemple : 5-Nom de la catégorie, qui deviendra
	 * category/5-nom-de-la-categorie
	 *
	 * @var string
	 */
	public static $purlId;



	/**
	 * Premier argument de la clause LIMIT pour la récupération des
	 * informations des vignettes.
	 *
	 * @var integer
	 */
	private static $_thumbsSqlStart;



	/**
	 * Construit la clause ORDER BY pour la récupération des catégories.
	 *
	 * @return string
	 */
	public static function categoriesSQLOrder($cat_orderby = NULL)
	{
		$cat_orderby = ($cat_orderby !== NULL)
			? $cat_orderby
			: utils::$config['sql_categories_order_by_type'];
		return utils::filters(
			$cat_orderby .
			str_replace(
				array('cat_name', 'cat_'),
				array('LOWER(cat_name)', 'cat.cat_'),
				utils::$config['sql_categories_order_by']
			),
			'order_by'
		) . ' cat.cat_id DESC';
	}

	/**
	 * Récupération des informations utiles des catégories contenues
	 * dans la catégorie courante pour l'affichage des vignettes.
	 *
	 * @return void
	 */
	public static function getCategories()
	{
		$sql = 'SELECT cat.thumb_id,
					   cat.cat_id,
					   cat.cat_path,
					   cat.cat_name,
					   cat.cat_url,
					   cat.cat_desc,
					   cat.cat_tb_infos AS tb_infos,
					   cat.cat_a_size,
					   cat.cat_a_albums,
					   cat.cat_a_images,
					   cat.cat_a_hits,
					   cat.cat_a_comments,
					   cat.cat_a_votes,
					   cat.cat_a_rate,
					   cat.cat_lastadddt,
					   cat.cat_crtdt,
					   cat.cat_filemtime,
					   cat.cat_filemtime/cat.cat_filemtime AS type,
					   cat.cat_password,
					   cat.cat_place,
					   img.image_id,
					   img.image_path,
					   img.image_width,
					   img.image_height,
					   img.image_adddt,
					   CASE WHEN cat_password IS NULL THEN 1 ELSE %s END AS auth
				  FROM ' . CONF_DB_PREF . 'categories AS cat
			 LEFT JOIN ' . CONF_DB_PREF . 'images AS img
					ON cat.thumb_id = img.image_id
				 WHERE cat.parent_id = ' . (int) $_GET['object_id'] . '
				   AND cat.cat_id != 1
				   AND cat.cat_status = "1" %s
			  ORDER BY ' . self::categoriesSQLOrder(self::$infos['cat_orderby']) . '
				 LIMIT ' . (int) self::$_thumbsSqlStart . ',' . (int) self::$nbThumbs;
		$fetch_style = array('fetch' => PDO::FETCH_ASSOC, 'column' => 'cat_id');

		// Permissions d'accès aux albums protégés.
		if (utils::$config['users'] == 1
		&& utils::$config['users_locked_albums_access_mode'] === 'groups')
		{
			$sql = sprintf($sql, 1, sql::$groupAlbumsAccess);
			$result = utils::$db->query($sql, $fetch_style);
		}
		else
		{
			if (($session_token = user::getSessionCookieToken()) !== FALSE)
			{
				$sub = '(SELECT 1
						   FROM ' . CONF_DB_PREF . 'sessions
	 				  LEFT JOIN ' . CONF_DB_PREF . 'passwords USING(session_id)
						  WHERE cat_password LIKE CONCAT("%:", password)
						    AND session_token = :session_token
						    AND session_expire > NOW()
						  LIMIT 1)';
				$params = array(
					'session_token' => $session_token
				);
				$result = utils::$db->prepare(sprintf($sql, $sub, ''))
					&& utils::$db->executeQuery($params, $fetch_style);
			}
			else
			{
				$result = utils::$db->query(sprintf($sql, 0, ''), $fetch_style);
			}
		}

		// Erreur.
		if ($result === FALSE)
		{
			return -1;
		}

		// Aucun résultat et première page.
		if (utils::$db->nbResult === 0 && $_GET['page'] == 1)
		{
			return 0;
		}

		// Si aucun résultat et page autre que la première, on redirige vers
		// la première page de la section courante.
		// Ce cas de figure peut arriver lorsqu'un utilisateur demande un
		// numéro de page plus grand que le nombre de pages que contient
		// la section.
		if (utils::$db->nbResult === 0 && $_GET['page'] > 1)
		{
			utils::redirect(self::$sectionRequest, TRUE);
		}

		// Tout s'est bien passé et on récupère le résultat de la requête.
		self::$thumbs = utils::$db->queryResult;

		// Y a-t-il des informations à afficher pour chaque vignette ?
		if (utils::$config['thumbs_stats_albums']
		|| (utils::$config['thumbs_stats_comments'] && utils::$config['comments'])
		|| utils::$config['thumbs_stats_filesize']
		|| utils::$config['thumbs_stats_hits']
		|| utils::$config['thumbs_stats_images']
		|| utils::$config['thumbs_stats_votes']
		|| utils::$config['thumbs_stats_category_title'])
		{
			self::$thumbsInfos = TRUE;
		}
	}

	/**
	 * Récupération des informations de la catégorie.
	 *
	 * @return integer
	 */
	public static function getCategoryInfos()
	{
		$cat_id = (isset($_GET['cat_id']))
			? (int) $_GET['cat_id']
			: (int) $_GET['object_id'];

		$sql = 'SELECT cat_id,
					   user_id,
					   cat_path,
					   cat_name,
					   cat_url,
					   cat_desc,
					   cat_lat,
					   cat_long,
					   cat_place,
					   cat_a_size,
					   cat_a_subalbs,
					   cat_a_subcats,
					   cat_a_albums,
					   cat_a_images,
					   cat_a_hits,
					   cat_a_comments,
					   cat_a_votes,
					   cat_a_rate,
					   cat_lastadddt,
					   cat_uploadable,
					   cat_crtdt,
					   cat_filemtime,
					   cat_password,
					   cat_style,
					   cat_orderby
				  FROM ' . CONF_DB_PREF . 'categories
				 WHERE %s
				   AND cat_id = ' . (int) $cat_id;
		$result = sql::sqlProtect('cat', $sql, 'row');
		if ($result === FALSE)
		{
			return -1;
		}
		if ($result['nb_result'] === 0)
		{
			if ($cat_id > 1)
			{
				// Si la catégorie est protégée par un mot de passe,
				// on redirige vers la page de demande de mot de passe
				// si nécessaire.
				if (utils::$config['users'] != 1 || (utils::$config['users'] == 1
				&& utils::$config['users_locked_albums_access_mode'] == 'password'))
				{
					$sql = 'SELECT cat_password
							  FROM ' . CONF_DB_PREF . 'categories
							 WHERE cat_id = ' . (int) $cat_id;
					if (utils::$db->query($sql, 'row') === FALSE)
					{
						return -1;
					}
					else if (utils::$db->nbResult === 0)
					{
						return 0;
					}
					else if (utils::$db->queryResult['cat_password'] !== NULL)
					{
						utils::redirect($_GET['q'] . '/pass', TRUE);
					}
				}
				else
				{
					return 0;
				}
			}
			return 1;
		}
		self::$infos = $result['query_result'];

		// Catégorie ou album ?
		self::$infos['cat_type'] = (self::$infos['cat_filemtime'] === NULL)
			? 'category'
			: 'album';
		if ($_GET['section'] == 'category' && self::$infos['cat_type'] != 'category')
		{
			return 0;
		}

		// L'utilisateur a-t-il entré le bon mot de passe ?
		self::_passwordSession(self::$infos['cat_password']);

		// Titre de la page.
		self::$pageTitle = utils::getLocale(self::$infos['cat_name']);

		// Nom de la catégorie.
		self::$infos['cat_name'] = (self::$infos['cat_id'] == 1)
			? __('la galerie')
			: self::$pageTitle;

		// Description pour catégorie 1 (accueil).
		if ($cat_id == 1)
		{
			// Description pour invités.
			self::$infos['cat_desc'] = (utils::$config['users'] && !users::$auth)
				? utils::$config['gallery_description_guest']
				: utils::$config['gallery_description'];
		}

		// Identifiant et nom de la catégorie servant pour la création d'URL.
		self::$infos['cat_url'] = (self::$infos['cat_id'] == 1)
			? __('galerie')
			: self::$infos['cat_url'];
		self::$purlId = self::$infos['cat_id'] . '-' . self::$infos['cat_url'];

		self::setPositionCategory(self::$infos);

		return 1;
	}

	/**
	 * Récupération des informations utiles des catégories voisines.
	 *
	 * @return void
	 */
	public static function getNeighbours()
	{
		$neighbours = utils::$config['widgets_params']['navigation']['items']['neighbours'];

		// On n'effectue pas de requête pour la page d'accueil
		// ainsi que pour les pages spéciales si l'affichage des
		// catégories voisines n'a pas été activée.
		if ($_GET['object_id'] == 1
		|| ($neighbours == 0 && $_GET['section'] != 'category' && $_GET['section'] != 'album'))
		{
			return;
		}

		// Nombre de vignettes par page.
		$nb_thumbs = (int) utils::$config['thumbs_cat_nb'];

		// Identifiant de la catégorie parente.
		if (is_array(self::$parents))
		{
			$parent = end(self::$parents);

			// Si l'option des catégorie voisines est activée et
			// que la catégorie parente ne contient qu'une seule catégorie,
			// ou bien que cette option n'est pas activée et que le nombre
			// de catégories de la catégorie parente n'est pas supérieur
			// au nombre de vignettes par page, inutile d'aller plus loin.
			if (($neighbours && $parent['cat_subs'] == 1)
			|| (!$neighbours && $parent['cat_subs'] <= $nb_thumbs))
			{
				return;
			}

			$cat_id = $parent['cat_id'];
		}
		else
		{
			$cat_id = 1;
		}

		// Récupération des informations utiles de toutes les catégories
		// qui ont le même parent que la catégorie courante.
		$sql = 'SELECT cat_id,
					   cat_name,
					   cat_url,
					   cat_filemtime,
					   cat_filemtime/cat_filemtime AS type
				  FROM ' . CONF_DB_PREF . 'categories AS cat
				 WHERE %s
				   AND parent_id = ' . (int) $cat_id . '
				   AND cat_id != 1
			  ORDER BY ' . self::categoriesSQLOrder(
						      isset($parent) ? $parent['cat_orderby'] : NULL
						   );

		// On met TRUE à l'argument $auth de sqlProtect pour, lorsque la
		// gestion de membres n'est pas activée, générer une liste de toutes
		// les catégories, y compris celles protégées par mot de passe, ceci
		// afin de calculer le bon $parentPage.
		$result = sql::sqlProtect('cat', $sql, PDO::FETCH_ASSOC, TRUE);
		self::$neighbours = $result['query_result'];

		// Position de la catégorie actuelle par rapport à ses voisines.
		for ($i = 0, $count = utils::$db->nbResult; $i < $count; $i++)
		{
			if (self::$neighbours[$i]['cat_id'] == $_GET['object_id'])
			{
				self::$neighboursPosition = $i + 1;
				break;
			}
		}

		// Page de la catégorie parente où se situe la catégorie actuelle.
		$parent_page = ceil(self::$neighboursPosition / $nb_thumbs);
		if ($parent_page > 1)
		{
			self::$parentPage = $parent_page;
		}
	}

	/**
	 * Paramètres pour la gestion des pages.
	 *
	 * @return void
	 */
	public static function pages()
	{
		// Nombre de vignettes par page.
		self::$nbThumbs = (int) utils::$config['thumbs_cat_nb'];

		// Nombre de pages de la section.
		self::$nbPages =
			ceil((self::$infos['cat_a_subalbs'] + self::$infos['cat_a_subcats'])
			/ self::$nbThumbs);

		// Premier argument de la clause LIMIT pour la récupération
		// des informations des vignettes, qui est fonction de la page courante.
		self::$_thumbsSqlStart = self::$nbThumbs * ($_GET['page'] - 1);
	}

	/**
	 * Génère le code HTML du type de catégorie avec lien sur le nom de la catégorie,
	 * pour barre de position.
	 *
	 * @param array $i
	 *	Informations de la catégorie.
	 * @return void
	 */
	public static function setPositionCategory(&$i)
	{
		$type_html = '<span class="current"><a href="%s">'
			. utils::tplProtect(utils::getLocale($i['cat_name'])) . '</a></span>';
		if ($i['cat_filemtime'] === NULL)
		{
			$type_html = sprintf($type_html, utils::genURL('category/'
				. $i['cat_id'] . '-' . $i['cat_url']));
			$type_html = ($i['cat_id'] == 1)
				? '<span class="current"><a href="' . utils::genURL() . '">'
					. __('la galerie') . '</a></span>'
				: sprintf(__('la catégorie %s'), $type_html);
		}
		else
		{
			$type_html = sprintf($type_html, utils::genURL('album/'
				. $i['cat_id'] . '-' . $i['cat_url']));
			$type_html = sprintf(__('l\'album %s'), $type_html);
		}

		$i['type_html'] = $type_html;
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
		if (utils::$config['users'] != 1 ||
		utils::$config['users_locked_albums_access_mode'] == 'password' ||
		users::$perms['gallery']['perms']['access_mode'] == 1)
		{
			return;
		}

		// On récupère les infos utiles de toutes les catégories protégées
		// non déverrouillées par les permissions de l'utilisateur.
		$sql = '';
		$params = array();
		if (users::$perms['gallery']['perms']['access_mode'] == 2
		&& !empty(users::$perms['gallery']['albums_access']))
		{
			$params = array_keys(users::$perms['gallery']['albums_access']);
			$sql = ' AND cat_id NOT IN (?' . str_repeat(', ?', count($params) - 1) . ')';
		}
		$sql = 'SELECT cat_path,
					   cat_a_size,
					   cat_a_albums,
					   cat_a_images,
					   cat_a_hits,
					   cat_a_comments,
					   cat_a_votes,
					   cat_a_rate,
					   cat_filemtime
				  FROM ' . CONF_DB_PREF . 'categories
				 WHERE cat_status = "1"
				   AND cat_password LIKE CONCAT(cat_id, ":%")
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

		// On réduit les statistiques de la catégorie courante.
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

			// Correction pour le nombre d'albums.
			$s['cat_a_albums'] = ($s['cat_filemtime'] === NULL)
				? $s['cat_a_albums']
				: 1;

			// On recalcule la note moyenne.
			if (self::$infos['cat_a_votes'] - $s['cat_a_votes'] > 0)
			{
				self::$infos['cat_a_rate'] = ((self::$infos['cat_a_rate']
					* self::$infos['cat_a_votes']) - ($s['cat_a_rate'] * $s['cat_a_votes']))
					/ (self::$infos['cat_a_votes'] - $s['cat_a_votes']);
			}
			else
			{
				self::$infos['cat_a_rate'] = 0;
			}

			// On réduit le nombre de sous-albums et de sous-catégories.
			self::$infos['cat_a_subalbs'] -= ($s['cat_filemtime'] !== NULL
				&& preg_match('`^' . $c_path . '[^/]+$`', $path))
				? 1
				: 0;
			self::$infos['cat_a_subcats'] -= ($s['cat_filemtime'] === NULL
				&& preg_match('`^' . $c_path . '[^/]+$`', $path))
				? 1
				: 0;

			// Réduction des autres stats.
			self::$infos['cat_a_size'] -= $s['cat_a_size'];
			self::$infos['cat_a_albums'] -= $s['cat_a_albums'];
			self::$infos['cat_a_images'] -= $s['cat_a_images'];
			self::$infos['cat_a_hits'] -= $s['cat_a_hits'];
			self::$infos['cat_a_comments'] -= $s['cat_a_comments'];
			self::$infos['cat_a_votes'] -= $s['cat_a_votes'];
		}

		// On recalcule les bons paramètres de pages.
		self::pages();

		// Si aucun vignette, inutile d'aller plus loin.
		if (empty(self::$thumbs))
		{
			return;
		}

		// On réduit les statistiques des vignettes.
		$temp_paths = array();
		foreach (self::$thumbs as $id => &$i)
		{
			foreach ($protected as $path => &$s)
			{
				if (strpos($path, $i['cat_path'] . '/', 0) !== FALSE)
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

					// On recalcule la note moyenne.
					if ($i['cat_a_votes'] - $s['cat_a_votes'] > 0)
					{
						$i['cat_a_rate'] = (($i['cat_a_rate'] * $i['cat_a_votes'])
							- ($s['cat_a_rate'] * $s['cat_a_votes']))
							/ ($i['cat_a_votes'] - $s['cat_a_votes']);
					}
					else
					{
						$i['cat_a_rate'] = 0;
					}

					// Réduction des autres stats.
					$i['cat_a_size'] -= $s['cat_a_size'];
					$i['cat_a_albums'] -= $s['cat_a_albums'];
					$i['cat_a_images'] -= $s['cat_a_images'];
					$i['cat_a_hits'] -= $s['cat_a_hits'];
					$i['cat_a_comments'] -= $s['cat_a_comments'];
					$i['cat_a_votes'] -= $s['cat_a_votes'];
				}
			}
		}
	}

	/**
	 * Détermine le nombre d'images récentes de la catégorie.
	 *
	 * @return void
	 */
	public static function recentImages()
	{
		self::$infos['cat_recent_images'] = 0;

		// S'il ne faut pas mettre en évidence les images récentes
		// ou s'il n'y a aucune nouvelle image, on arrête là.
		if (utils::$config['recent_images'] != 1
		|| strtotime(self::$infos['cat_lastadddt']) < self::$recentImagesLimit)
		{
			return;
		}

		$limit = date('Y-m-d H:i:s', self::$recentImagesLimit);

		// On effectue une requête SQL afin de déterminer
		// le nombre total de nouvelles images dans la catégorie.
		// S'il n'y a qu'une seule page, on n'exécute pas cette requête,
		// on additionnera plus loin le nombre de nouvelles images
		// de chaque objet de la catégorie, sauf si l'information du
		// nombre d'images récentes sous les vignettes a été désactivée.
		if (utils::$config['widgets_params']['stats_categories']['items']['recents'] == 1
		&& (self::$nbPages > 1 || utils::$config['recent_images_nb'] != 1))
		{
			$sql = 'SELECT COUNT(*)
					  FROM ' . CONF_DB_PREF . 'images
				 LEFT JOIN ' . CONF_DB_PREF . 'categories USING (cat_id)
					 WHERE %s
					   AND image_path LIKE CONCAT(:path, "%%")
					   AND image_adddt > :limit
					   AND image_status = "1"';
			$params = array(
				'limit' => $limit,
				'path' => (self::$infos['cat_id'] == 1)
					? ''
					: self::$infos['cat_path'] . '/'
			);
			$result = sql::sqlProtect('image', $sql, 'value', FALSE, $params);
			if ($result !== FALSE)
			{
				self::$infos['cat_recent_images'] = $result['query_result'];
			}
		}

		// Si l'on ne doit pas indiquer le nombre de nouvelles images
		// pour chaque objet, ou s'il n'y a aucune vignette, on arrête là.
		if ((utils::$config['recent_images_nb'] != 1
		  && utils::$config['recent_images_by_cat'] != 1)
		|| !is_array(self::$thumbs))
		{
			return;
		}

		// On détermine le nombre de nouvelles images pour chaque sous-catégorie.
		foreach (self::$thumbs as &$i)
		{
			// Si la catégorie ne contient pas d'images récentes,
			// inutile d'aller plus loin.
			if (strtotime($i['cat_lastadddt']) < self::$recentImagesLimit)
			{
				continue;
			}

			// On effectue la requête pour récupérer
			// le nombre d'images récentes de la catégorie.
			$sql = 'SELECT COUNT(*)
					  FROM ' . CONF_DB_PREF . 'images
				 LEFT JOIN ' . CONF_DB_PREF . 'categories USING (cat_id)
					 WHERE %s
					   AND image_path LIKE CONCAT(:path, "/%%")
					   AND image_adddt > :limit
					   AND image_status = "1"';
			$params = array(
				'limit' => $limit,
				'path' => $i['cat_path']
			);
			$auth = ($i['cat_password'] == '')
				? TRUE
				: FALSE;
			$result = sql::sqlProtect('image', $sql, 'value', $auth, $params);
			if ($result === FALSE)
			{
				return;
			}
			if (self::$nbPages == 1)
			{
				self::$infos['cat_recent_images'] += $result['query_result'];
			}
			self::$thumbs[$i['cat_id']]['cat_recent_images'] = $result['query_result'];
		}
	}
}

/**
 * Opérations concernant les commentaires.
 */
class comments extends gallery
{
	/**
	 * Indique si un commentaire a été enregistré avec succès.
	 *
	 * @var integer
	 */
	public static $addComment = FALSE;

	/**
	 * Informations utiles de la catégorie courante.
	 *
	 * @var array
	 */
	public static $catInfos = array();

	/**
	 * Informations utiles des commentaires de la page courante.
	 *
	 * @var array
	 */
	public static $items = array();

	/**
	 * Nombre de commentaires.
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
	 * Identifiant du commentaire prévisualisé.
	 *
	 * @var integer
	 */
	public static $previewId;

	/**
	 * Liste des smilies disponibles.
	 *
	 * @var array
	 */
	public static $smilies = array();



	/**
	 * Enregistre un commentaire.
	 *
	 * @return void
	 */
	public static function addComment()
	{
		// Les commentaires doivent être activés et
		// l'utilisateur doit posséder la permission de poster.
		if (!utils::$config['comments']
		|| !image::$infos['cat_commentable']
		|| (utils::$config['users'] == 1
			&& (!users::$perms['gallery']['perms']['add_comments']
			 || !users::$perms['gallery']['perms']['read_comments'])))
		{
			return;
		}

		if (($status = self::_commonAddComment()) === FALSE)
		{
			return;
		}

		try
		{
			// Début de la transaction.
			if (utils::$db->transaction() === FALSE)
			{
				throw new Exception();
			}

			// Enregistrement du commentaire.
			$sql = 'INSERT INTO ' . CONF_DB_PREF . 'comments (
					com_id, user_id, image_id, com_crtdt, com_lastupddt, com_author,
					com_email, com_website, com_ip, com_message, com_status
				) VALUES (
					:com_id, :user_id, :image_id, NOW(), NOW(), :com_author,
					:com_email, :com_website, :com_ip, :com_message, :com_status
				)';
			$params = array(
				'com_id' => (isset($_POST['preview'])) ? -1 : 0,
				'user_id' => users::$auth
					? users::$infos['user_id']
					: 2,
				'image_id' => image::$infos['image_id'],
				'com_author' => users::$auth
					? users::$infos['user_login']
					: $_POST['author'],
				'com_email' => users::$auth
					? NULL
					: $_POST['email'],
				'com_website' => users::$auth
					? NULL
					: $_POST['website'],
				'com_ip' => $_SERVER['REMOTE_ADDR'],
				'com_message' => $_POST['message'],
				'com_status' => $status
			);
			if (utils::$db->prepare($sql) === FALSE
			|| utils::$db->executeExec($params) === FALSE
			|| utils::$db->nbResult !== 1)
			{
				throw new Exception();
			}

			// Si le commentaire est prévisualisé, on arrête là.
			if (isset($_POST['preview']))
			{
				self::$previewId = utils::$db->connexion->lastInsertId();
				return;
			}

			// Mise à jour du nombre de commentaires pour
			// l'image et ses catégories parentes, mais seulement
			// si le commentaire est immédiatement validé.
			if ($status == 1)
			{
				$parent_ids = array(1);
				foreach (gallery::$parents as &$i)
				{
					$parent_ids[] = (int) $i['cat_id'];
				}
				$sql = array(
					'UPDATE ' . CONF_DB_PREF . 'images
						SET image_comments = image_comments + 1
					  WHERE image_id = ' . (int) image::$infos['image_id'] . '
					  LIMIT 1',

					'UPDATE ' . CONF_DB_PREF . 'categories
						SET cat_a_comments = cat_a_comments + 1
					  WHERE cat_id IN (' . implode(',', $parent_ids) . ')'
				);
				if (utils::$db->exec($sql, FALSE) === FALSE)
				{
					throw new Exception();
				}
			}

			// Log d'activité.
			self::_logUserActivity('comment_accept');

			// Exécution de la transaction.
			if (!utils::$db->commit())
			{
				throw new Exception();
			}

			// On indique que le commentaire a bien été enregistré.
			self::$addComment = TRUE;

			// On incrémente le nombre de commentaires sur l'image,
			// mais seulement si le commentaire est publié immédiatement.
			if ($status == 1)
			{
				image::$infos['image_comments']++;
			}

			// On enregistre dans un cookie un md5 du message afin d'éviter les double post.
			utils::$cookiePrefs->add(
				'com_md5',
				md5($_GET['object_id'] . '|' . $_POST['message'])
			);

			// Enregistrement des informations du posteur dans un cookie
			// mais uniquement pour les invités.
			if (!isset($_POST['preview']) && !users::$auth)
			{
				if (isset($_POST['remember']))
				{
					utils::$cookiePrefs->add('com_author', $_POST['author']);
					utils::$cookiePrefs->add('com_email', $_POST['email']);
					utils::$cookiePrefs->add('com_website', $_POST['website']);
				}
				else
				{
					utils::$cookiePrefs->delete('com_author');
					utils::$cookiePrefs->delete('com_email');
					utils::$cookiePrefs->delete('com_website');
				}
			}

			// Notification par courriel,
			// pour les commentaires ajoutés immédiatement
			// et pour ceux mis en attente de validation.
			if ($status == 1 || $status == -1)
			{
				$type = ($status == 1) ? 'comment' : 'comment-pending';
				$album = array(image::$infos['cat_path']);
				$infos = array();

				if ($type != 'comment-pending')
				{
					$infos = array_merge($infos, array(
						'image_id' => $_GET['object_id'],
						'image_url' => image::$infos['image_url']
					));
				}

				if (users::$auth)
				{
					$user_id = users::$infos['user_id'];
					$infos = array_merge($infos, array(
						'user_id' => users::$infos['user_id'],
						'user_login' => users::$infos['user_login']
					));
				}
				else
				{
					$user_id = 0;
					$infos = array_merge($infos, array(
						'author' => $_POST['author'],
						'email' => $_POST['email'],
						'website' => $_POST['website']
					));
				}

				// Suivi de commentaires.
				if (utils::$config['users'] && $status == 1)
				{
					$mail = new mail();
					$mail->notify('comment-follow', $album, $user_id, $infos);
					$mail->send();
				}

				// Notifications pour admins.
				$mail = new mail();
				$mail->notify($type, $album, $user_id, $infos);
				$mail->send();
			}

			unset($_POST['message']);

			if (utils::$config['comments_moderate'])
			{
				self::report('success:' . __('Votre commentaire'
					. ' sera affiché après validation par un administrateur.'));
			}
		}
		catch (Exception $e)
		{
			self::report('error');
		}
	}

	/**
	 * Récupère les commentaires d'une image.
	 *
	 * @return void
	 */
	public static function getCommentsImage()
	{
		// Les commentaires doivent être activés et
		// l'utilisateur doit posséder la permission de les lire.
		// S'il n'y a aucun commentaire, inutile d'effectuer une requête.
		if (!utils::$config['comments']
		|| (image::$infos['image_comments'] == 0 && !self::$addComment && !self::$previewId))
		{
			return;
		}

		$sql = (in_array(utils::$config['comments_order'], array('ASC', 'DESC')))
			? utils::$config['comments_order']
			: 'ASC';
		$sql = 'SELECT com_id,
					   com_crtdt,
					   com_message,
					   c.user_id,
					   g.group_admin,
					   CASE WHEN c.user_id = 2
						  THEN com_author
						  ELSE user_login
						  END AS author,
					   CASE WHEN c.user_id = 2
						  THEN com_website
						  ELSE user_website
						  END AS com_website,
					   CASE WHEN c.user_id = 2
						  THEN 0
						  ELSE user_avatar
						  END AS avatar
				  FROM ' . CONF_DB_PREF . 'comments AS c,
					   ' . CONF_DB_PREF . 'users AS u,
					   ' . CONF_DB_PREF . 'groups AS g
				 WHERE image_id = ' . (int) image::$infos['image_id'] . '
				   AND (com_status = "1" OR com_id = -1)
				   AND c.user_id = u.user_id
				   AND u.group_id = g.group_id
			  ORDER BY com_crtdt ' . $sql;
		if (utils::$db->query($sql, PDO::FETCH_ASSOC) === FALSE
		|| utils::$db->nbResult === 0)
		{
			return;
		}
		self::$items = utils::$db->queryResult;

		if (isset($_POST['preview']))
		{
			utils::$db->rollBack();
		}
	}

	/**
	 * Récupération des commentaires pour la page des commentaires.
	 *
	 * @return integer
	 */
	public static function getCommentsPage()
	{
		$sql_where = '';
		$no_pass = FALSE;

		// Récupération des informations utiles de la catégorie.
		if ($_GET['section'] == 'comments')
		{
			$sql = 'SELECT cat_id,
						   cat_name,
						   cat_path,
						   cat_url,
						   cat_filemtime,
						   cat_password
					  FROM ' . CONF_DB_PREF . 'categories
					 WHERE cat_id = ' . (int) $_GET['object_id'];
			if (utils::$db->query($sql, 'row') === FALSE)
			{
				return -1;
			}
			if (utils::$db->nbResult === 0)
			{
				return 0;
			}
			self::$catInfos = utils::$db->queryResult;
			category::setPositionCategory(self::$catInfos);

			if (self::$catInfos['cat_id'] > 1)
			{
				$sql_where .= ' AND img.image_path LIKE "'
					. utils::filters(self::$catInfos['cat_path'], 'path') . '/%%"';
			}
		}

		// Récupération des informations utiles de l'utilisateur.
		else if ($_GET['section'] == 'user-comments')
		{
			$sql = 'SELECT user_login
					  FROM ' . CONF_DB_PREF . 'users
					 WHERE user_id = ' . (int) $_GET['user_id'];
			if (utils::$db->query($sql, 'row') === FALSE)
			{
				return -1;
			}
			if (utils::$db->nbResult === 0)
			{
				return 0;
			}
			self::$catInfos = utils::$db->queryResult;
			self::$catInfos['object_name'] = self::$catInfos['user_login'];

			// Catégorie.
			if (category::$infos['cat_id'] == 1)
			{
				$path = '';
			}
			else if (category::$infos['cat_filemtime'] === NULL)
			{
				$path = utils::filters(category::$infos['cat_path'], 'path');
				$path = ' AND image_path LIKE "' . $path . '/%%"';
			}
			else
			{
				$path = ' AND cat.cat_id = ' . (int) category::$infos['cat_id'];
			}

			$sql_where .= $path . ' AND u.user_id = ' . (int) $_GET['user_id'];
		}

		if ($_GET['section'] == 'comments')
		{
			// Nom de la catégorie courante.
			if ($_GET['object_id'] == 1)
			{
				self::$catInfos['object_name'] = __('la galerie');
			}
			else
			{
				$object = (comments::$catInfos['cat_filemtime'] === NULL)
					? __('la catégorie %s')
					: __('l\'album %s');
				self::$catInfos['object_name']
					= sprintf($object, comments::$catInfos['cat_name']);
			}

			// Si la catégorie courante est un album
			// et qu'il n'est pas protégé par un mot de passe,
			// on évite une sous-requête inutile avec sqlProtect().
			$no_pass = $_GET['object_id'] > 1
				&& self::$catInfos['cat_filemtime'] !== NULL
				&& self::$catInfos['cat_password'] == '';
		}

		// Nombre de commentaires.
		if ($_GET['section'] == 'user-comments')
		{
			$sql = 'SELECT COUNT(*)
					  FROM ' . CONF_DB_PREF . 'comments AS com,
						   ' . CONF_DB_PREF . 'images AS img,
						   ' . CONF_DB_PREF . 'categories AS cat,
						   ' . CONF_DB_PREF . 'users AS u
					 WHERE %s
					   AND com.com_status = "1"
					   AND com.image_id = img.image_id
					   AND img.cat_id = cat.cat_id
					   AND com.user_id = u.user_id'
						 . $sql_where;
		}
		else
		{
			$sql = 'SELECT COUNT(*)
					  FROM ' . CONF_DB_PREF . 'comments AS com,
						   ' . CONF_DB_PREF . 'images AS img,
						   ' . CONF_DB_PREF . 'categories AS cat
					 WHERE %s
					   AND com.com_status = "1"
					   AND com.image_id = img.image_id
					   AND img.cat_id = cat.cat_id'
						 . $sql_where;
		}
		$result = sql::sqlProtect('image', $sql, 'value', $no_pass);
		if ($result === FALSE)
		{
			return -1;
		}
		if ($result['nb_result'] === 0)
		{
			return 0;
		}
		self::$nbItems = utils::$db->queryResult;

		// Nombre de pages.
		$comments_per_page = (int) utils::$config['pages_params']['comments']['nb_per_page'];
		self::$nbPages = ceil(self::$nbItems / $comments_per_page);
		$sql_limit_start = $comments_per_page * ($_GET['page'] - 1);

		// Récupération des commentaires.
		$sql = 'SELECT com.com_id,
					   com.com_crtdt,
					   com.com_message,
					   img.image_id,
					   img.image_name,
					   img.image_url,
					   img.image_comments,
					   img.image_path,
					   img.image_adddt,
					   img.image_width,
					   img.image_height,
					   img.image_tb_infos AS tb_infos,
					   cat.cat_id,
					   cat.cat_name,
					   u.user_id,
					   g.group_admin,
					   CASE WHEN u.user_id = 2
						  THEN com_author
						  ELSE user_login
						  END AS author,
					   CASE WHEN u.user_id = 2
						  THEN com_website
						  ELSE user_website
						  END AS com_website,
					   CASE WHEN u.user_id = 2
						  THEN 0
						  ELSE user_avatar
						  END AS avatar
				  FROM ' . CONF_DB_PREF . 'comments AS com,
					   ' . CONF_DB_PREF . 'images AS img,
					   ' . CONF_DB_PREF . 'categories AS cat,
					   ' . CONF_DB_PREF . 'users AS u,
					   ' . CONF_DB_PREF . 'groups AS g
				 WHERE %s
				   AND com.com_status = "1"
				   AND com.image_id = img.image_id
				   AND com.user_id = u.user_id
				   AND u.group_id = g.group_id
				   AND img.cat_id = cat.cat_id
				   ' . $sql_where . '
			  ORDER BY com_crtdt DESC
			     LIMIT ' . $sql_limit_start . ',' . $comments_per_page;
		$fetch_style = array('fetch' => PDO::FETCH_ASSOC, 'column' => 'com_id');
		$result = sql::sqlProtect('image', $sql, $fetch_style, $no_pass);

		// Si erreur, on redirige vers la page d'accueil.
		if ($result === FALSE)
		{
			return -1;
		}

		// Si aucun résultat et page autre que la première, on redirige vers
		// la première page de la section courante.
		if ($result['nb_result'] === 0 && $_GET['page'] > 1)
		{
			utils::redirect(self::$sectionRequest, TRUE);
		}

		self::$items = utils::$db->queryResult;

		// Chargement des smilies.
		if (utils::$config['comments_smilies'])
		{
			$smilies = array();
			include_once(GALLERY_ROOT . '/images/smilies/'
				. utils::$config['comments_smilies_icons_pack'] . '/icons.php');
			self::$smilies =& $smilies;
		}

		return 1;
	}



	/**
	 * Vérifie un commentaire.
	 *
	 * @return integer
	 *	Statut du commentaire :
	 *	-3 si refusé.
	 *	-1 si mis en attente de validation.
	 *	 1 si publié immédiatement.
	 */
	private static function _checkComment()
	{
		// Vérification de chaque champ.
		if (!self::_checkCommentMessage()
		 || !self::_checkCommentEmail()
		 || !self::_checkCommentWebsite()
		 || !self::_checkCommentAuthor())
		{
			return -3;
		}

		// Vérifications des contraintes.
		if (!self::_checkCommentAntiflood())
		{
			return -3;
		}

		// Vérifications par listes noires.
		$r = self::_checkBlacklists($_POST['author'], $_POST['email'], $_POST['message']);
		if (is_array($r))
		{
			// Log d'activité.
			self::_logUserActivity('comment_reject_blacklist_' . $r['list'], NULL, $r['match']);

			return -3;
		}

		return utils::$config['comments_moderate'] ? -1 : 1;
	}

	/**
	 * Anti-flood.
	 *
	 * @return boolean
	 */
	private static function _checkCommentAntiflood()
	{
		$antiflood_time = self::$time - (int) utils::$config['comments_antiflood'];

		// Vérification par 'user_id' pour les membres authentifiés.
		if (users::$auth)
		{
			$sql = 'SELECT UNIX_TIMESTAMP(com_crtdt)
					  FROM ' . CONF_DB_PREF . 'comments
					 WHERE user_id = :user_id
					   AND UNIX_TIMESTAMP(com_crtdt) > :antiflood_time
				  ORDER BY com_crtdt DESC
					 LIMIT 1';
			$params = array(
				'antiflood_time' => $antiflood_time,
				'user_id' => users::$infos['user_id']
			);
		}

		// Vérification par IP pour les invités.
		else
		{
			$sql = 'SELECT UNIX_TIMESTAMP(com_crtdt)
					  FROM ' . CONF_DB_PREF . 'comments
					 WHERE com_ip = :ip
					   AND UNIX_TIMESTAMP(com_crtdt) > :antiflood_time
				  ORDER BY com_crtdt DESC
					 LIMIT 1';
			$params = array(
				'antiflood_time' => $antiflood_time,
				'ip' => $_SERVER['REMOTE_ADDR']
			);
		}
		if (utils::$db->prepare($sql) === FALSE
		|| utils::$db->executeQuery($params, 'value') === FALSE)
		{
			self::report('error');
			return FALSE;
		}

		if (utils::$db->nbResult == 1)
		{
			$time = (self::$time - $antiflood_time) - (self::$time - utils::$db->queryResult);
			self::report('warning:' . sprintf('Vous devez patienter encore %s '
				. 'secondes avant de pouvoir poster un nouveau commentaire.', $time));

			// Log d'activité.
			self::_logUserActivity('comment_reject_antiflood',
				NULL, (int) utils::$config['comments_antiflood']);

			return FALSE;
		}

		return TRUE;
	}

	/**
	 * Vérifie l'auteur du commentaire.
	 *
	 * @return boolean
	 */
	private static function _checkCommentAuthor()
	{
		try
		{
			// Si l'utilisateur est authentifié,
			// on ne doit pas tenir compte de ce champ.
			if (users::$auth)
			{
				$_POST['author'] = NULL;
				return TRUE;
			}

			// Si non identifié ou gestion de membres désactivée,
			// le champ 'author' doit être présent.
			if (!isset($_POST['author']))
			{
				throw new Exception();
			}

			$_POST['author'] = trim($_POST['author']);

			// Longueur.
			if (mb_strlen($_POST['author']) > 255)
			{
				return FALSE;
			}

			// Vide ?
			if ($_POST['author'] == '')
			{
				throw new Exception();
			}

			return TRUE;
		}
		catch (Exception $e)
		{
			self::report('warning:' . __('Le nom de l\'auteur du commentaire est vide.'));
			return FALSE;
		}
	}

	/**
	 * Vérifie l'adresse de courriel pour un commentaire.
	 *
	 * @return boolean
	 */
	private static function _checkCommentEmail()
	{
		try
		{
			// Si l'utilisateur est authentifié,
			// on ne doit pas tenir compte de ce champ.
			if (users::$auth)
			{
				$_POST['email'] = NULL;
				return TRUE;
			}

			// Champ non présent ou vide.
			if (!isset($_POST['email']) || $_POST['email'] == '')
			{
				// Si le champ 'email' est requis, il doit être présent et non vide.
				if (utils::$config['comments_required_email'])
				{
					throw new Exception();
				}

				$_POST['email'] = NULL;
				return TRUE;
			}

			$_POST['email'] = trim($_POST['email']);

			// Vérification de la longueur.
			if (strlen($_POST['email']) > 255)
			{
				throw new Exception();
			}

			// Vérification du format.
			if (!preg_match('`^' . utils::regexpEmail() . '$`i', $_POST['email']))
			{
				throw new Exception();
			}

			return TRUE;
		}
		catch (Exception $e)
		{
			self::report('warning:' . __('Format de l\'adresse de courriel invalide.'));
			return FALSE;
		}
	}

	/**
	 * Vérifie le message d'un commentaire.
	 *
	 * @return boolean
	 */
	private static function _checkCommentMessage()
	{
		// Le message doit être présent dans tous les cas !
		if (!isset($_POST['message']))
		{
			return FALSE;
		}

		$_POST['message'] = trim($_POST['message']);

		// Le message est-il vide ?
		if (utils::isEmpty($_POST['message']))
		{
			self::report('warning:' . __('Le message que vous avez envoyé est vide.'));
			return FALSE;
		}

		// Vérification de la longueur du message.
		$max = (utils::$config['comments_maxchars'] > 5000)
			? 5000
			: (int) utils::$config['comments_maxchars'];
		if (mb_strlen($_POST['message']) > $max)
		{
			self::report('warning:' . sprintf(
				__('Votre message ne doit pas comporter plus de %s caractères.'), $max)
			);

			// Log d'activité.
			self::_logUserActivity('comment_reject_maxchars', NULL,
				(int) utils::$config['comments_maxchars']);

			return FALSE;
		}

		// Vérification du nombre de lignes du message.
		$test = preg_split('`(?:\r\n|[\r\n])`', $_POST['message']);
		if (count($test) > (int) utils::$config['comments_maxlines'])
		{
			self::report('warning:' . sprintf(
				__('Votre message ne doit pas comporter plus de %s lignes.'),
					(int) utils::$config['comments_maxlines']));

			// Log d'activité.
			self::_logUserActivity('comment_reject_maxlines', NULL,
				(int) utils::$config['comments_maxlines']);

			return FALSE;
		}

		// Vérification du nombre d'URLs dans le message.
		$max = (int) utils::$config['comments_maxurls'];
		$regexp = '(?:(?:ht|f)tp://(?:' . utils::regexpURL('domain')
			. utils::regexpURL('tld') . '|' . utils::regexpURL('ip') . '))';
		$regexp = '`(?:[^$]*?' . $regexp . '[^$]*?){' . ($max + 1) . '}`i';
		if (preg_match($regexp, $_POST['message']))
		{
			self::report('warning:' . sprintf(__('Votre message'
				. ' ne doit pas contenir plus de %s URL.'), $max));

			// Log d'activité.
			self::_logUserActivity('comment_reject_maxurls', NULL,
				(int) utils::$config['comments_maxurls']);

			return FALSE;
		}

		return TRUE;
	}

	/**
	 * Vérifie l'adresse du site Web pour un commentaire.
	 *
	 * @return boolean
	 */
	private static function _checkCommentWebsite()
	{
		try
		{
			// Si l'utilisateur est authentifié,
			// on ne doit pas tenir compte de ce champ.
			if (users::$auth)
			{
				$_POST['website'] = NULL;
				return TRUE;
			}

			// Champ non présent ou vide.
			if (!isset($_POST['website'])
			|| $_POST['website'] == '' || $_POST['website'] == 'http://')
			{
				// Si le champ 'website' est requis, il doit être présent et non vide.
				if (utils::$config['comments_required_website'])
				{
					throw new Exception();
				}

				$_POST['website'] = NULL;
				return TRUE;
			}

			$_POST['website'] = trim($_POST['website']);

			// Vérification de la longueur.
			if (strlen($_POST['website']) > 255)
			{
				throw new Exception();
			}

			// Vérification du format.
			if (!preg_match('`^' . utils::regexpURL() . '$`i', $_POST['website']))
			{
				throw new Exception();
			}

			return TRUE;
		}
		catch (Exception $e)
		{
			self::report('warning:' . __('Format de l\'adresse du site Web invalide.'));
			return FALSE;
		}
	}



	/**
	 * Code commun pour l'ajout d'un commentaire.
	 *
	 * @return mixed
	 */
	protected static function _commonAddComment()
	{
		// Chargement des smilies.
		$smilies_file = GALLERY_ROOT . '/images/smilies/'
				. utils::$config['comments_smilies_icons_pack'] . '/icons.php';
		if (utils::$config['comments_smilies'] && file_exists($smilies_file))
		{
			$smilies = array();
			include_once($smilies_file);
			self::$smilies =& $smilies;
		}

		// reCAPTCHA.
		if ((utils::$config['recaptcha_comments'] && ((utils::$config['users'] && !users::$auth
		&& utils::$config['recaptcha_comments_guest_only'])
		|| (utils::$config['users'] && users::$auth
		&& !utils::$config['recaptcha_comments_guest_only']) || !utils::$config['users']))
		&& !self::_recaptcha('comment', !isset($_POST['preview'])))
		{
			return FALSE;
		}

		if (empty($_POST))
		{
			return FALSE;
		}

		// Petite vérification anti-spam.
		if (!isset($_POST['f_email']) || $_POST['f_email'] !== '')
		{
			return FALSE;
		}

		// Anti-double post.
		if (isset($_POST['message'])
		&& md5($_GET['object_id'] . '|' . $_POST['message'])
		== utils::$cookiePrefs->read('com_md5'))
		{
			$_POST = array();
			return FALSE;
		}

		// Vérification du commentaire.
		if (($status = self::_checkComment()) == -3)
		{
			if (isset($_POST['preview']))
			{
				unset($_POST['preview']);
			}
			return FALSE;
		}

		return $status;
	}
}

/**
 * Opérations concernant le livre d'or.
 */
class guestbook extends comments
{
	/**
	 * Enregistre un commentaire.
	 *
	 * @return void
	 */
	public static function addComment()
	{
		// Le livre d'or doit être activé.
		if (utils::$config['pages_params']['guestbook']['status'] != 1)
		{
			return;
		}

		if (($status = self::_commonAddComment()) === FALSE)
		{
			return;
		}

		try
		{
			// Début de la transaction.
			if (utils::$db->transaction() === FALSE)
			{
				throw new Exception();
			}

			// Enregistrement du commentaire.
			$sql = 'INSERT INTO ' . CONF_DB_PREF . 'guestbook (
					guestbook_id, user_id, guestbook_crtdt, guestbook_lastupddt,
					guestbook_author, guestbook_email, guestbook_website, guestbook_ip,
					guestbook_message, guestbook_rate, guestbook_status
				) VALUES (
					:id, :user_id, NOW(), NOW(), :author,
					:email, :website, :ip, :message, :rate, :status
				)';
			$params = array(
				'id' => (isset($_POST['preview'])) ? -1 : 0,
				'user_id' => users::$auth
					? users::$infos['user_id']
					: 2,
				'author' => users::$auth
					? users::$infos['user_login']
					: $_POST['author'],
				'email' => users::$auth
					? NULL
					: $_POST['email'],
				'website' => users::$auth
					? NULL
					: $_POST['website'],
				'ip' => $_SERVER['REMOTE_ADDR'],
				'message' => $_POST['message'],
				'rate' => (isset($_POST['rate'])
					&& in_array($_POST['rate'], array('1', '2', '3', '4', '5')))
					? $_POST['rate']
					: NULL,
				'status' => $status
			);
			if (utils::$db->prepare($sql) === FALSE
			|| utils::$db->executeExec($params) === FALSE
			|| utils::$db->nbResult !== 1)
			{
				throw new Exception();
			}

			// Si le commentaire est prévisualisé, on arrête là.
			if (isset($_POST['preview']))
			{
				self::$previewId = utils::$db->connexion->lastInsertId();
				return;
			}

			// Log d'activité.
			self::_logUserActivity('comment_accept');

			// Exécution de la transaction.
			if (!utils::$db->commit())
			{
				throw new Exception();
			}

			// On indique que le commentaire a bien été enregistré.
			self::$addComment = TRUE;

			// On enregistre dans un cookie un md5 du message afin d'éviter les double post.
			utils::$cookiePrefs->add('com_md5', md5('guestbook|' . $_POST['message']));

			// Enregistrement des informations du posteur dans un cookie
			// mais uniquement pour les invités.
			if (!isset($_POST['preview']) && !users::$auth)
			{
				if (isset($_POST['remember']))
				{
					utils::$cookiePrefs->add('com_author', $_POST['author']);
					utils::$cookiePrefs->add('com_email', $_POST['email']);
					utils::$cookiePrefs->add('com_website', $_POST['website']);
				}
				else
				{
					utils::$cookiePrefs->delete('com_author');
					utils::$cookiePrefs->delete('com_email');
					utils::$cookiePrefs->delete('com_website');
				}
			}

			// Notification par courriel,
			// pour les commentaires ajoutés immédiatement
			// et pour ceux mis en attente de validation.
			if ($status == 1 || $status == -1)
			{
				$type = ($status == 1) ? 'guestbook' : 'guestbook-pending';
				$infos = array();

				if (users::$auth)
				{
					$user_id = users::$infos['user_id'];
					$infos = array_merge($infos, array(
						'user_id' => users::$infos['user_id'],
						'user_login' => users::$infos['user_login']
					));
				}
				else
				{
					$user_id = 0;
					$infos = array_merge($infos, array(
						'author' => $_POST['author'],
						'email' => $_POST['email'],
						'website' => $_POST['website']
					));
				}

				// Notifications pour admins.
				$mail = new mail();
				$mail->notify($type, array(), $user_id, $infos);
				$mail->send();
			}

			unset($_POST['message']);

			if (utils::$config['comments_moderate'])
			{
				self::report('success:' . __('Votre commentaire'
					. ' sera affiché après validation par un administrateur.'));
			}
		}
		catch (Exception $e)
		{
			self::report('error');
		}
	}

	/**
	 * Récupère les commentaires du livre d'or.
	 *
	 * @return void
	 */
	public static function getComments()
	{
		// Le livre d'or doit être activé.
		if (utils::$config['pages_params']['guestbook']['status'] != 1)
		{
			return 0;
		}

		// Récupération du nombre total de commentaires du livre d'or.
		$sql = 'SELECT COUNT(*)
				  FROM ' . CONF_DB_PREF . 'guestbook
				 WHERE guestbook_status = "1"
					OR guestbook_id = -1';
		if (utils::$db->query($sql, 'value') === FALSE
		|| utils::$db->nbResult === 0)
		{
			return -1;
		}
		self::$nbItems = utils::$db->queryResult;
		if (self::$nbItems == 0)
		{
			return 1;
		}

		// Nombre de pages.
		$comments_per_page = (int) utils::$config['pages_params']['guestbook']['nb_per_page'];
		self::$nbPages = ceil(self::$nbItems / $comments_per_page);
		$sql_limit_start = $comments_per_page * ($_GET['page'] - 1);

		// Récupération des commentaires.
		$sql = 'SELECT guestbook_id,
					   guestbook_crtdt,
					   guestbook_message,
					   guestbook_rate,
					   c.user_id,
					   g.group_admin,
					   CASE WHEN c.user_id = 2
						  THEN guestbook_author
						  ELSE user_login
						  END AS author,
					   CASE WHEN c.user_id = 2
						  THEN guestbook_website
						  ELSE user_website
						  END AS guestbook_website,
					   CASE WHEN c.user_id = 2
						  THEN 0
						  ELSE user_avatar
						  END AS avatar
				  FROM ' . CONF_DB_PREF . 'guestbook AS c,
					   ' . CONF_DB_PREF . 'users AS u,
					   ' . CONF_DB_PREF . 'groups AS g
				 WHERE (guestbook_status = "1" OR guestbook_id = -1)
				   AND c.user_id = u.user_id
				   AND u.group_id = g.group_id
			  ORDER BY guestbook_crtdt DESC
				 LIMIT ' . $sql_limit_start . ',' . $comments_per_page;
		if (utils::$db->query($sql, PDO::FETCH_ASSOC) === FALSE)
		{
			return -1;
		}

		// Si aucun résultat et page autre que la première, on redirige vers
		// la première page de la section courante.
		if (utils::$db->nbResult === 0 && $_GET['page'] > 1)
		{
			utils::redirect(self::$sectionRequest, TRUE);
		}

		self::$items = utils::$db->queryResult;

		if (isset($_POST['preview']))
		{
			utils::$db->rollBack();
		}

		return 1;
	}
}

/**
 * Opérations concernant les images.
 */
class image extends gallery
{
	/**
	 * Informations de la catégorie.
	 *
	 * @var array
	 */
	public static $catInfos;

	/**
	 * Position de l'image par rapport à toutes celles de la section courante.
	 *
	 * @var integer
	 */
	public static $currentImage;

	/**
	 * Informations utiles de toutes les images de la section courante.
	 *
	 * @var array
	 */
	public static $images;

	/**
	 * Informations utiles de l'image courante.
	 *
	 * @var array
	 */
	public static $infos;

	/**
	 * Metadonnées.
	 *
	 * @var array
	 */
	public static $metadata;

	/**
	 * Nombre d'images de la section courante.
	 *
	 * @var integer
	 */
	public static $nbImages;

	/**
	 * Page de l'album parent où se situe l'image courante.
	 *
	 * @var integer
	 */
	public static $parentPage;

	/**
	 * Liens de navigation entres les pages.
	 *
	 * @var array
	 */
	public static $navLinks = array();

	/**
	 * Dimensions de l'image redimensionnée.
	 *
	 * @var array
	 */
	public static $resize = array();



	/**
	 * Récupère les informations de la catégorie de la section courante.
	 *
	 * @return integer
	 */
	public static function getCategoryInfos()
	{
		if (!isset($_GET['cat_id']) || $_GET['cat_id'] == 1)
		{
			self::$catInfos['cat_id'] = 1;
			self::$catInfos['cat_name'] = '';
			self::$catInfos['cat_path'] = '';
			self::$catInfos['cat_url'] = '';
			self::$catInfos['cat_filemtime'] = NULL;
			category::setPositionCategory(self::$catInfos);
			return 1;
		}

		// Récupération des informations de la catégorie demandée.
		// Si celle-ci n'existe pas, on redirige vers la même image mais
		// sans autres paramètres.
		$sql = 'SELECT cat_id,
					   cat_name,
					   cat_path,
					   cat_url,
					   cat_filemtime
				  FROM ' . CONF_DB_PREF . 'categories
				 WHERE cat_id = ' . (int) $_GET['cat_id'] . '
				   AND cat_status = "1"';
		if (utils::$db->query($sql, 'row') === FALSE)
		{
			return -1;
		}
		if (utils::$db->nbResult === 0)
		{
			return 0;
		}
		self::$catInfos = utils::$db->queryResult;
		category::setPositionCategory(self::$catInfos);

		// Si la catégorie demandée ne fait pas partie des parents de l'image,
		// alors il y a incohérence et donc on redirige vers la même image mais
		// sans autres paramètres.
		$test = FALSE;
		for ($i = 0, $count = count(self::$parents); $i < $count; $i++)
		{
			if (self::$parents[$i]['cat_id'] == $_GET['cat_id'])
			{
				$test = TRUE;
				break;
			}
		}
		if (!$test)
		{
			utils::redirect('image/' . self::$infos['image_id']
				. '-' . self::$infos['image_url'], TRUE);
		}

		return 1;
	}

	/**
	 * Récupère les informations utiles des images de la section courante.
	 *
	 * @return integer
	 */
	public static function images()
	{
		$params = array();

		// Section.
		$section = (isset($_GET['section_b']))
			? 'section_b'
			: 'section';

		// Moteur de recherche.
		if (isset($_GET['search']))
		{
			$sql_where = search::getImagesSQLWhere(users::$perms);
			$params = $sql_where['params'];
			$sql_where = $sql_where['sql'] . ' AND ';
		}
		else
		{
			$cat_infos = (isset($_GET['cat_id']) || isset($_GET['section_b']))
				? self::$catInfos
				: self::$infos;
			$sql_where = sql::thumbsSQLWhere($cat_infos, users::$infos, $_GET[$section]);
		}

		// Récupération des images.
		$sql = 'SELECT img.image_id,
					   image_url
				  FROM ' . sql::imagesSQLFrom($section) . '
				 WHERE ' . str_replace('%', '%%', $sql_where) . ' %s
			  ORDER BY ' . sql::imagesSQLOrder(self::$infos);
		$result = sql::sqlProtect('image', $sql, PDO::FETCH_ASSOC, FALSE, $params);
		if ($result === FALSE)
		{
			return -1;
		}
		if ($result['nb_result'] === 0)
		{
			return 0;
		}
		self::$images = $result['query_result'];

		// Nombre d'images.
		self::$nbImages = count(self::$images);

		// Position de l'image.
		self::$currentImage = array_search(
			array(
				'image_id' => self::$infos['image_id'],
				'image_url' => self::$infos['image_url']
			),
			self::$images
		) + 1;

		// On détermine la page de l'album parent où se situe l'image actuelle.
		if (!isset($_GET['section_b']))
		{
			$nb_thumbs = (int) utils::$config['thumbs_alb_nb'];
			$parent_page = ceil(self::$currentImage / $nb_thumbs);
			if ($parent_page > 1)
			{
				self::$parentPage = $parent_page;
			}
		}

		// Liens de navigation entre les pages.
		self::navLinks();

		return 1;
	}

	/**
	 * Récupère les informations utiles de l'image courante.
	 *
	 * @return void
	 */
	public static function getImageInfos()
	{
		$sql = '';
		$params = array();

		// L'image est-elle dans les favoris de l'utilisateur ?
		if (utils::$config['users'])
		{
			$params['user_id'] = (int) users::$infos['user_id'];
			$sql .= ', (SELECT 1
						  FROM ' . CONF_DB_PREF . 'favorites
					     WHERE image_id = img.image_id
						   AND user_id = :user_id
						 LIMIT 1) AS in_favorites';
		}

		// L'image est-elle dans le panier de l'utilisateur ?
		if (utils::$config['basket'])
		{
			if (users::$auth)
			{
				$sql .= ', (SELECT 1
							  FROM ' . CONF_DB_PREF . 'basket
							 WHERE image_id = img.image_id
							   AND user_id = :user_id
							 LIMIT 1) AS in_basket';
			}
			else
			{
				$sql .= ', (SELECT 1
							  FROM ' . CONF_DB_PREF . 'basket AS b
						 LEFT JOIN ' . CONF_DB_PREF . 'sessions AS s USING (session_id)
							 WHERE image_id = img.image_id
							   AND session_token = "' . user::getSessionCookieToken() . '"
							 LIMIT 1)
								AS in_basket';
			}
		}

		// Note de l'utilisateur.
		if (utils::$config['votes'])
		{
			if (users::$auth)
			{
				$sql .= ', (SELECT vote_rate
							  FROM ' . CONF_DB_PREF . 'votes
							 WHERE image_id = img.image_id
							   AND user_id = :user_id
							 LIMIT 1) AS user_rate';
			}
			else
			{
				$vote_cookie = utils::$cookiePrefs->read('rate');
				if (preg_match('`^[a-z0-9]{32}$`i', $vote_cookie))
				{
					$sql .= ', (SELECT vote_rate
								  FROM ' . CONF_DB_PREF . 'votes
								 WHERE image_id = img.image_id
								   AND vote_cookie = :vote_cookie
								 LIMIT 1) AS user_rate';
					$params['vote_cookie'] = $vote_cookie;
				}
			}
		}

		// Nombre de favoris.
		if (utils::$config['users']
		 && utils::$config['widgets_params']['stats_images']['items']['favorites'])
		{
			$sql .= ', (SELECT COUNT(*)
						  FROM ' . CONF_DB_PREF . 'users
					 LEFT JOIN ' . CONF_DB_PREF . 'favorites USING (user_id)
						 WHERE user_status = "1"
						   AND image_id = img.image_id) AS nb_favorites';
		}

		// Informations utiles de l'image.
		$sql = 'SELECT img.image_id,
					   img.cat_id,
					   image_path,
					   image_width,
					   image_height,
					   image_filesize,
					   image_exif,
					   image_iptc,
					   image_xmp,
					   image_lat,
					   image_long,
					   image_place,
					   image_name,
					   image_url,
					   image_desc,
					   image_adddt,
					   image_crtdt,
					   image_hits,
					   image_votes,
					   image_rate,
					   image_rotation,
					   image_comments,
					   image_status,
					   cat_filemtime,
					   cat_password,
					   cat_commentable,
					   cat_votable,
					   cat_path,
					   cat_url,
					   cat_style,
					   cat_orderby,
					   cat_watermark,
					   u.user_id,
					   u.user_login,
					   u.user_avatar,
					   u.user_status,
					   u.user_watermark
					   ' . $sql . '
				  FROM ' . CONF_DB_PREF . 'images AS img
		     LEFT JOIN ' . CONF_DB_PREF . 'users AS u
					ON img.user_id = u.user_id
			 LEFT JOIN ' . CONF_DB_PREF . 'categories AS cat
					ON img.cat_id = cat.cat_id
				 WHERE img.image_id = :image_id
				   AND img.image_status = "1" ';
		$params['image_id'] = (int) $_GET['object_id'];
		if (utils::$config['users'] == 1
		&& utils::$config['users_locked_albums_access_mode'] === 'groups')
		{
			$sql .= sql::$groupAlbumsAccess;
		}
		if (utils::$db->prepare($sql) === FALSE
		|| utils::$db->executeQuery($params, 'row') === FALSE)
		{
			return -1;
		}
		if (utils::$db->nbResult === 0)
		{
			return 0;
		}
		self::$infos = utils::$db->queryResult;

		// Si l'image n'est pas dans les favoris alors que l'on est sur la page
		// des images des favoris, on redirige vers l'image.
		if (isset($_GET['section_b']) && $_GET['section_b'] == 'user-favorites'
		&& !self::$infos['in_favorites'])
		{
			utils::redirect(
				'image/' . self::$infos['image_id'] . '-' . self::$infos['image_url'],
				TRUE
			);
		}

		// Si l'image n'est pas dans le panier alors que l'on est sur la page
		// des images du panier, on redirige vers l'image.
		if (isset($_GET['section_b']) && $_GET['section_b'] == 'basket'
		&& !self::$infos['in_basket'])
		{
			utils::redirect(
				'image/' . self::$infos['image_id'] . '-' . self::$infos['image_url'],
				TRUE
			);
		}

		// L'utilisateur a-t-il entré le bon mot de passe ?
		self::_passwordSession(self::$infos['cat_password']);

		// Titre de la page.
		self::$pageTitle = utils::getLocale(self::$infos['image_name']);

		// Rotation de l'image.
		img::rotation(self::$infos);

		// On recalcule les dimensions et le poids de l'image si
		// l'utilisateur n'a pas la permission d'accès à l'image originale.
		$resize = FALSE;
		$watermark = watermark::getParams(self::$infos);
		if (utils::$config['users'] && !users::$perms['gallery']['perms']['image_original'])
		{
			// Dimensions.
			$max_width = (int) utils::$config['images_resize_gd_width'];
			$max_height = (int) utils::$config['images_resize_gd_height'];
			if (self::$infos['image_width'] > $max_width
			|| self::$infos['image_height'] > $max_height)
			{
				$resize = img::imageResize(self::$infos['image_width'],
					self::$infos['image_height'], $max_width, $max_height);
				self::$infos['image_width_original'] = $resize['width'];
				self::$infos['image_height_original'] = $resize['height'];
			}

			// Poids.
			if (!$watermark)
			{
				$resize_file = GALLERY_ROOT . '/' . img::filepath('im_resize',
					self::$infos['image_path'], self::$infos['image_id'],
					self::$infos['image_adddt']);
				self::$infos['image_filesize'] = (file_exists($resize_file)
				&& ($filesize = filesize($resize_file)) !== FALSE)
					? $filesize
					: NULL;
			}
		}

		// Si la fonctionnalité "filigrane" est activée,
		// on récupère le poids de l'image avec filigrane (si elle existe).
		if ($watermark)
		{
			$watermark_str = md5(serialize($watermark) . '|' . self::$infos['image_adddt']);
			$watermark_type = ($resize) ? 'im_resize_watermark' : 'im_watermark';
			$watermark_file = GALLERY_ROOT . '/' . img::filepath($watermark_type,
				self::$infos['image_path'], self::$infos['image_id'], $watermark_str);
			self::$infos['image_filesize'] = (file_exists($watermark_file)
				&& ($filesize = filesize($watermark_file)) !== FALSE)
				? $filesize
				: NULL;
		}

		// Récupération des métadonnées.
		if (utils::$config['exif'] || utils::$config['iptc'] || utils::$config['xmp'])
		{
			image::$metadata = new metadata(NULL, self::$infos);

			// Informations Exif.
			if (utils::$config['exif'])
			{
				image::$metadata->getExif();

				// Informations Exif en base de données.
				if (empty(image::$metadata->exif)
				&& self::$infos['image_exif'] !== NULL)
				{
					image::$metadata->getExif(unserialize(self::$infos['image_exif']));
				}
			}

			// Informations IPTC.
			if (utils::$config['iptc'])
			{
				image::$metadata->getIptc();

				// Informations IPTC en base de données.
				if (empty(image::$metadata->iptc)
				&& self::$infos['image_iptc'] !== NULL)
				{
					image::$metadata->getIptc(unserialize(self::$infos['image_iptc']));
				}
			}

			// Informations XMP.
			if (utils::$config['xmp'])
			{
				image::$metadata->getXmp();

				// Informations XMP en base de données.
				if (empty(image::$metadata->xmp)
				&& self::$infos['image_xmp'] !== NULL)
				{
					image::$metadata->getXmp(self::$infos['image_xmp']);
				}
			}
		}
	}

	/**
	 * Construit les liens de navigation entre les pages de la section courante.
	 *
	 * @return void
	 */
	public static function navLinks()
	{
		if (self::$nbImages < 2)
		{
			return;
		}

		self::$navLinks['next_active'] = TRUE;
		self::$navLinks['next_inactive'] = FALSE;
		self::$navLinks['prev_active'] = TRUE;
		self::$navLinks['prev_inactive'] = FALSE;
		self::$navLinks['first_link'] = '';
		self::$navLinks['prev_link'] = '';
		self::$navLinks['last_link'] = '';
		self::$navLinks['next_link'] = '';

		$cat_url = (self::$catInfos['cat_id'] == 1)
			? __('galerie')
			: self::$catInfos['cat_url'];
		$cat_url = self::$catInfos['cat_id'] . '-' . $cat_url;

		$section_url = '';
		if (isset($_GET['section_b']))
		{
			switch ($_GET['section_b'])
			{
				case 'basket' :
					$section_url .= '/basket';
					break;

				case 'camera-brand' :
					$section_url .= '/' . $_GET['section_b'] . '/' . $_GET['camera_id']
						. '-' . gallery::$cameraInfos['camera_brand_name'];
					break;

				case 'camera-model' :
					$section_url .= '/' . $_GET['section_b'] . '/' . $_GET['camera_id']
						. '-' . gallery::$cameraInfos['camera_model_name'];
					break;

				case 'date-added' :
				case 'date-created' :
					$section_url .= '/' . $_GET['section_b'] . '/' . $_GET['date'];
					break;

				case 'user-favorites' :
				case 'user-images' :
					$section_url .= '/' . $_GET['section_b'] . '/' . $_GET['user_id'];
					break;

				case 'tag' :
					$section_url .= '/' . $_GET['section_b'] . '/' . $_GET['tag_id']
						. '-' . tags::$tagInfos['tag_name'];
					break;

				default :
					$section_url .= '/' . $_GET['section_b'] . '/' . $cat_url;
			}
		}

		if (isset($_GET['section_c']))
		{
			switch ($_GET['section_c'])
			{
				default :
					$section_url .= '/' . $_GET['section_c'] . '/' . $cat_url;
			}
		}

		if (isset($_GET['search']))
		{
			$section_url .= '/search/' . $_GET['search'];
		}

		for ($i = 0, $count = self::$nbImages; $i < $count; $i++)
		{
			if ($_GET['object_id'] == self::$images[$i]['image_id'])
			{
				if ($i == 0)
				{
					self::$navLinks['prev_active'] = FALSE;
					self::$navLinks['prev_inactive'] = TRUE;
				}
				else
				{
					self::$navLinks['first_link'] =
						utils::genURL('image/' . self::$images[0]['image_id']
						. '-' . self::$images[0]['image_url']
						. $section_url);
					self::$navLinks['prev_link'] =
						utils::genURL('image/' . self::$images[$i - 1]['image_id']
						. '-' . self::$images[$i - 1]['image_url']
						. $section_url);
				}
				if ($i == self::$nbImages - 1)
				{
					self::$navLinks['next_active'] = FALSE;
					self::$navLinks['next_inactive'] = TRUE;
				}
				else
				{
					self::$navLinks['next_link'] =
						utils::genURL('image/' . self::$images[$i + 1]['image_id']
						. '-' . self::$images[$i + 1]['image_url']
						. $section_url);
					self::$navLinks['last_link'] =
						utils::genURL('image/' . self::$images[self::$nbImages - 1]['image_id']
						. '-' . self::$images[self::$nbImages - 1]['image_url']
						. $section_url);
				}
				break;
			}
		}
	}
}

/**
 * Opérations concernant le plan de la galerie.
 */
class map extends gallery
{
	/**
	 * Liste de toutes les catégories de la galerie.
	 *
	 * @var array
	 */
	public static $categories;



	/**
	 * Récupération des informations pour le plan.
	 *
	 * @param boolean $no_albums
	 *	Indique si l'on doit récupérer seulement les catégories.
	 * @param boolean $forced
	 *	Indique si l'on doit forcer la récupération des informations des catégories.
	 * @return void
	 */
	public static function getMap($no_albums = FALSE, $forced = FALSE)
	{
		// On évite d'exécuter deux fois le même code,
		// notamment pour la page "plan".
		static $already = FALSE;
		if (!$forced && $already)
		{
			return;
		}
		$already = TRUE;

		// Quand la gestion de membre n'est pas activée, on récupère toujours
		// les "catégories mères" avec mot de passe. Seules les sous-catégories
		// d'une catégorie protégée feront l'objet d'une vérification pour
		// déterminer si l'utilisateur a le droit d'accès à ces sous-catégories.
		$sql_password = (utils::$config['users'] != 1)
			? ' OR cat_password LIKE CONCAT(cat_id, ":%%")'
			: '';

		// On ne récupère pas les albums ?
		$sql_no_albums = ($no_albums)
			? ' AND cat_filemtime IS NULL'
			: '';

		// Récupération de toutes les catégories activées de la galerie et
		// dont l'accès est autorisé, ainsi que des catégories non activées
		// mais dont l'utilisateur courant est propriétaire.
		$sql = 'SELECT cat_id,
					   user_id,
					   parent_id,
					   cat_name,
					   cat_url,
					   cat_a_size,
					   cat_a_images,
					   cat_d_images,
					   cat_lastadddt,
					   cat_filemtime,
					   cat_filemtime/cat_filemtime AS type,
					   cat_password,
					   cat_creatable,
					   cat_uploadable,
					   cat_status
				  FROM ' . CONF_DB_PREF . 'categories AS cat
				 WHERE ((%s)' . $sql_password . ')
					   ' . $sql_no_albums . '
			  ORDER BY ' . category::categoriesSQLOrder();
		$fetch_style = array('fetch' => PDO::FETCH_ASSOC, 'column' => 'cat_id');
		$result = sql::sqlProtect('cat', $sql, $fetch_style, FALSE, array(), TRUE);
		if ($result === FALSE || $result['nb_result'] === 0)
		{
			return;
		}
		self::$categories = $result['query_result'];
	}
}

/**
 * Gestion des tags.
 */
class tags extends gallery
{
	/**
	 * Tags de la catégorie courante.
	 *
	 * @var array
	 */
	public static $categoryTags;

	/**
	 * Tags liés à l'image courante.
	 *
	 * @var array
	 */
	public static $imageTags = array();

	/**
	 * Informations sur le tag courant.
	 *
	 * @var array
	 */
	public static $tagInfos;

	/**
	 * Tags de la catégorie courante pour le widget.
	 *
	 * @var array
	 */
	public static $widgetTags;



	/**
	 * Récupération des tags d'une catégorie.
	 * @params boolean $widget
	 *	Doit-on récupérer les tags pour le widget ?
	 * @return void
	 */
	public static function getCategoryTags($widget = FALSE)
	{
		// La fonctionnalité "tags" doit être activée.
		if (!utils::$config['tags'])
		{
			return;
		}

		// Doit-on limiter le nombre de tags à récupérer ?
		$limit = '';
		if ($widget)
		{
			$limit = ' LIMIT ' . (int) utils::$config
				['widgets_params']['tags']['params']['max_tags'];
		}

		// Récupération des informations des tags
		// et du nombre d'images liées à chaque tag,
		// et en tenant compte des permissions de l'utilisateur.
		$path = utils::filters(category::$infos['cat_path'], 'path');
		$path = ($path == '')
			? ''
			: $path . '/';

		$sql = 'SELECT DISTINCT t.*,
					   COUNT(*) AS tag_nb_images
				  FROM ' . CONF_DB_PREF . 'tags AS t,
					   ' . CONF_DB_PREF . 'tags_images AS ti,
					   ' . CONF_DB_PREF . 'images AS img,
					   ' . CONF_DB_PREF . 'categories AS cat
				 WHERE %s
				   AND t.tag_id = ti.tag_id
				   AND ti.image_id = img.image_id
				   AND img.cat_id = cat.cat_id
				   AND img.image_path LIKE "' . $path . '%%"
			  GROUP BY t.tag_id
			  ORDER BY tag_nb_images DESC, t.tag_id ASC'
				  . $limit;
		$fetch_style = array('fetch' => PDO::FETCH_ASSOC, 'column' => 'tag_name');
		$result = sql::sqlProtect('image', $sql, $fetch_style);
		if ($result === FALSE || $result['nb_result'] < 1)
		{
			return;
		}
		$tags = $result['query_result'];

		// On détermine le "poids" de chaque tag, compris entre 1 et 10.
		$big = current($tags);
		$big = $big['tag_nb_images'];
		$small = end($tags);
		$small = $small['tag_nb_images'];
		reset($tags);
		$diff = $big - $small;
		$increment = ($diff === 0) ? 1 : $diff / 9;
		foreach ($tags as $tag_id => &$tag_infos)
		{
			$tag_infos['tag_weight'] = intval(
				($tag_infos['tag_nb_images'] - $small) / $increment
			) + 1;
		}

		// On tri les tags.
		uksort($tags, 'utils::alphaSort');

		// On remplit le tableau adéquat selon que les tags
		// sont pour le widget ou pour la page du nuage de tags.
		if ($widget)
		{
			self::$widgetTags = &$tags;
		}
		else
		{
			self::$categoryTags = &$tags;
		}
	}

	/**
	 * Récupération des tags liés à l'image.
	 *
	 * @return void
	 */
	public static function getImageTags()
	{
		// La fonctionnalité "tags" doit être activée.
		if (!utils::$config['tags'])
		{
			return;
		}

		$sql = 'SELECT t.*
				  FROM ' . CONF_DB_PREF . 'tags AS t,
					   ' . CONF_DB_PREF . 'tags_images AS ti
				 WHERE t.tag_id = ti.tag_id
				   AND ti.image_id = ' . (int) image::$infos['image_id'];
		$fetch_style = array('fetch' => PDO::FETCH_ASSOC, 'column' => 'tag_name');
		if (utils::$db->query($sql, $fetch_style) !== FALSE)
		{
			self::$imageTags = utils::$db->queryResult;
		}

		uksort(self::$imageTags, 'utils::alphaSort');
	}

	/**
	 * Récupère les informations du tag courant.
	 *
	 * @return integer
	 */
	public static function getTagInfos()
	{
		$sql = 'SELECT *
				  FROM ' . CONF_DB_PREF . 'tags
				 WHERE tag_id = ' . (int) $_GET['tag_id'];
		if (utils::$db->query($sql, 'row') !== FALSE)
		{
			self::$tagInfos = utils::$db->queryResult;
		}
		if (utils::$db->nbResult === 0)
		{
			return 0;
		}

		return 1;
	}
}

/**
 * Gestion des utilisateurs.
 */
class users extends gallery
{
	/**
	 * Indique si l'utilisateur est authentifié.
	 *
	 * @var boolean
	 */
	public static $auth = FALSE;

	/**
	 * Informations de l'utilisateur authentifié.
	 *
	 * @var array
	 */
	public static $infos;

	/**
	 * Informations des éléments de la page courante.
	 *
	 * @var array
	 */
	public static $items;

	/**
	 * Nombre de pages.
	 *
	 * @var integer
	 */
	public static $nbPages;

	/**
	 * Nombre d'utilisateurs.
	 *
	 * @var integer
	 */
	public static $nbUsers;

	/**
	 * Nouveau mot de passe.
	 *
	 * @var string
	 */
	public static $newPassword;

	/**
	 * Droits utilisateur pour la gestion de membres.
	 *
	 * @var array
	 */
	public static $perms;

	/**
	 * Informations d'un profil utilisateur.
	 *
	 * @var array
	 */
	public static $profile;

	/**
	 * Indique si l'enregistrement de l'utilisateur a réussi.
	 *
	 * @var boolean
	 */
	public static $register = FALSE;

	/**
	 * Paramètres de filigrane.
	 *
	 * @var array
	 */
	public static $watermarkParams;



	/**
	 * Modification de l'avatar.
	 *
	 * @return void
	 */
	public static function avatar()
	{
		if (empty($_POST['action']) || !utils::antiCSRFTokenCheck(utils::$cookiePrefs))
		{
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
	 * Authentification utilisateur.
	 *
	 * @return void
	 */
	public static function auth()
	{
		// La gestion de membres doit être activée.
		if (utils::$config['users'] != 1)
		{
			return;
		}

		// Si l'utilisateur n'est pas identifié, on applique les droits invité.
		if (!self::_authCookie())
		{
			// Accès à la galerie interdit pour les invités ?
			if (utils::$config['users_only_members'])
			{
				// Redirection vers la page d'identification.
				$s = array('forgot', 'login', 'new-password', 'register', 'validation');
				if (!in_array($_GET['section'], $s))
				{
					utils::redirect('login');
					die;
				}

				// Désactivation des widgets.
				foreach (utils::$config['widgets_params'] as $widget => &$v)
				{
					if ($widget != 'user')
					{
						$v['status'] = 0;
					}
				}
			}

			// Récupération des permissions pour les invités.
			$sql = 'SELECT group_perms
					  FROM ' . CONF_DB_PREF . 'groups
					 WHERE group_id = 2
					 LIMIT 1';
			if (utils::$db->query($sql, 'value') !== FALSE
			&& utils::$db->nbResult === 1)
			{
				$perms = utils::$db->queryResult;
				if (utils::isSerializedArray($perms))
				{
					self::$perms = unserialize($perms);
				}
			}

			self::_authForm();
		}

		// Si l'utilisateur n'a pas la permission d'accès à tous les
		// albums, on force la récupération du nombre d'images
		// récentes pour chaque catégorie afin de déterminer, grâce
		// aux permissions d'accès dans les requêtes SQL, s'il y a
		// des images récentes que l'utilisateur a l'autoriadtion de
		// voir. Et ce, de manière à notifier correctement de la
		// présence d'images récentes sur chaque vignette (cadre vert).
		if (utils::$config['users_locked_albums_access_mode'] != 'groups'
		|| (utils::$config['users_locked_albums_access_mode'] == 'groups'
			&& self::$perms['gallery']['perms']['access_mode'] != 1))
		{
			utils::$config['recent_images_by_cat'] = 1;
		}

		// Désactivation des fonctionnalités en fonction
		// des permissions de l'utilisateur.
		if (!self::$perms['gallery']['perms']['read_comments'])
		{
			utils::$config['comments'] = 0;
			utils::$config['pages_params']['guestbook']['status'] = 0;
		}
		if (!self::$perms['gallery']['perms']['options'])
		{
			utils::$config['widgets_params']['options']['status'] = 0;
		}
		if (!self::$perms['gallery']['perms']['adv_search'])
		{
			utils::$config['search_advanced'] = 0;
		}
		if (!self::$perms['gallery']['perms']['members_list'])
		{
			utils::$config['pages_params']['members']['status'] = 0;
			utils::$config['widgets_params']['online_users']['status'] = 0;
		}

		// Mode d'accès aux albums.
		sql::albumsAccess(self::$perms['gallery']);
	}

	/**
	 * Envoi d'e-mail pour la récupération d'un nouveau mot de passe.
	 *
	 * @return void
	 */
	public static function forgot()
	{
		if (!isset($_POST['login']) || !isset($_POST['email']))
		{
			return;
		}

		$page = GALLERY_HOST . utils::genURL('new-password');
		if (!user::forgot($_POST['login'], $_POST['email'], $page))
		{
			self::report('warning:' . __('Informations incorrectes.'));
			return;
		}

		self::report('success:' . __('Un courriel avec les indications'
			. ' à suivre vous a été envoyé.'));
	}

	/**
	 * Récupération de la liste des membres.
	 *
	 * @return void
	 */
	public static function getMembers()
	{
		// Groupe.
		$sql_group = (isset($_GET['group_id']))
			? 'AND u.group_id = ' . (int) $_GET['group_id']
			: '';

		// Nombre d'utilisateurs.
		$sql = 'SELECT COUNT(*)
				  FROM ' . CONF_DB_PREF . 'users AS u
				 WHERE user_status = "1"
				   AND user_id != "2"'
					 . $sql_group;
		if (utils::$db->query($sql, 'value') === FALSE)
		{
			if ($_GET['page'] > 1)
			{
				utils::redirect('members');
			}
			return;
		}
		self::$nbUsers = utils::$db->queryResult;

		// Nombre de pages.
		$users_per_page = (int) utils::$config['pages_params']['members']['nb_per_page'];
		self::$nbPages = ceil(self::$nbUsers / $users_per_page);
		$sql_limit_start = $users_per_page * ($_GET['page'] - 1);

		// Critère de tri.
		$sql_order_by = utils::filters(
			utils::$config['pages_params']['members']['order_by'],
			'order_by'
		);
		$sql_order_by = explode(' ', $sql_order_by);
		$sql_order_by[0] = 'LOWER(' . $sql_order_by[0] . ')';

		// Récupération des membres pour la page courante.
		$sql = 'SELECT user_id,
					   user_login,
					   user_avatar,
					   user_crtdt,
					   user_lastvstdt,
					   g.group_id,
					   g.group_title,
					   g.group_name
				  FROM ' . CONF_DB_PREF . 'users AS u
			 LEFT JOIN ' . CONF_DB_PREF . 'groups AS g USING (group_id)
				 WHERE user_status = "1"
				   AND user_id != "2"
					 ' . $sql_group . '
			  ORDER BY ' . implode(' ', $sql_order_by) . '
			     LIMIT ' . $sql_limit_start . ',' . $users_per_page;
		$fetch_style = array('fetch' => PDO::FETCH_ASSOC, 'column' => 'user_id');
		if (utils::$db->query($sql, $fetch_style) === FALSE)
		{
			return -1;
		}
		if (utils::$db->nbResult === 0 && $_GET['page'] == 1)
		{
			return 0;
		}
		if (utils::$db->nbResult === 0 && $_GET['page'] > 1)
		{
			utils::redirect(self::$sectionRequest, TRUE);
		}

		self::$items = utils::$db->queryResult;
	}

	/**
	 * Récupération des informations d'un utilisateur.
	 *
	 * @params integer $user_id
	 *	Identifiant de l'utilisateur.
	 * @params boolean $basics
	 *	Informations basiques ou complètes ?
	 * @return integer
	 */
	public static function getUser($user_id, $basics = FALSE)
	{
		if (!users::$perms['gallery']['perms']['members_list']
		&& users::$infos['user_id'] != $user_id)
		{
			return 0;
		}

		if ($basics)
		{
			$sql = 'SELECT user_login
					  FROM ' . CONF_DB_PREF . 'users';
		}
		else
		{
			$sql = 'SELECT g.group_id,
						   g.group_title,
						   u.*
					  FROM ' . CONF_DB_PREF . 'users AS u
				 LEFT JOIN ' . CONF_DB_PREF . 'groups AS g USING(group_id)';
		}
		$sql .= ' WHERE user_id = ' . (int) $user_id . '
					AND user_id != 2
				    AND user_status = "1"
				  LIMIT 1';
		if (utils::$db->query($sql, 'row') === FALSE)
		{
			return -1;
		}
		if (utils::$db->nbResult !== 1)
		{
			return 0;
		}
		users::$profile = utils::$db->queryResult;

		if ($basics)
		{
			return 1;
		}

		users::$profile['user_other'] = utils::isSerializedArray(users::$profile['user_other'])
			? unserialize(users::$profile['user_other'])
			: array();

		// Nombre de favoris.
		$sql = 'SELECT COUNT(*)
				  FROM ' . CONF_DB_PREF . 'favorites AS fav
			 LEFT JOIN ' . CONF_DB_PREF . 'images AS img
					ON fav.image_id = img.image_id
			 LEFT JOIN ' . CONF_DB_PREF . 'categories AS cat
					ON img.cat_id = cat.cat_id
				 WHERE %s
				   AND image_status = "1"
				   AND fav.user_id = ' . (int) $user_id;
		$result = sql::sqlProtect('image', $sql, 'value');
		if ($result === FALSE || $result['nb_result'] !== 1)
		{
			return -1;
		}
		users::$profile['nb_favorites'] = $result['query_result'];

		// Nombre d'images.
		$sql = 'SELECT COUNT(*)
				  FROM ' . CONF_DB_PREF . 'images AS img
			 LEFT JOIN ' . CONF_DB_PREF . 'users AS u
					ON img.user_id = u.user_id
			 LEFT JOIN ' . CONF_DB_PREF . 'categories AS cat
					ON img.cat_id = cat.cat_id
				 WHERE %s
				   AND image_status = "1"
				   AND img.user_id = ' . (int) $user_id;
		$result = sql::sqlProtect('image', $sql, 'value');
		if ($result === FALSE || $result['nb_result'] !== 1)
		{
			return -1;
		}
		users::$profile['nb_images'] = $result['query_result'];

		// Nombre de commentaires.
		$sql = 'SELECT COUNT(*)
				  FROM ' . CONF_DB_PREF . 'comments AS com
			 LEFT JOIN ' . CONF_DB_PREF . 'images AS img
					ON com.image_id = img.image_id
			 LEFT JOIN ' . CONF_DB_PREF . 'categories AS cat
					ON img.cat_id = cat.cat_id
				 WHERE %s
				   AND image_status = "1"
				   AND com.user_id = ' . (int) $user_id;
		$result = sql::sqlProtect('image', $sql, 'value');
		if ($result === FALSE || $result['nb_result'] !== 1)
		{
			return -1;
		}
		users::$profile['nb_comments'] = $result['query_result'];

		return 1;
	}

	/**
	 * Création d'une nouvelle catégorie.
	 *
	 * @return void
	 */
	public static function newCategory()
	{
		if (!isset($_POST['category']) || !isset($_POST['type'])
		|| !isset($_POST['name']) || !isset($_POST['desc'])
		|| !utils::antiCSRFTokenCheck(utils::$cookiePrefs))
		{
			return;
		}

		// Récupération des informations utiles de la catégorie parente
		// dans laquelle sera créée la nouvelle catégorie.
		$sql = 'SELECT cat_id,
					   cat_path,
					   cat_password
				 FROM ' . CONF_DB_PREF . 'categories
				WHERE cat_id = ' . (int) $_POST['category'] . '
				  AND cat_creatable = "1"';

		// L'utilisateur doit-il être propriétaire de la catégorie parente ?
		$sql .= (users::$perms['gallery']['perms']['upload_create_owner'])
			? ' AND user_id = ' . (int) users::$infos['user_id']
			: '';

		// La création de catégories dans les catégories vides est-elle autorisée ?
		$sql .= (utils::$config['upload_categories_empty'])
			? ' AND (cat_status = "1" OR (cat_status = "0" AND cat_d_images = 0))'
			: ' AND cat_status = "1"';

		if (utils::$db->query($sql, 'row') === FALSE)
		{
			self::report('error');
			return;
		}
		if (utils::$db->nbResult === 0)
		{
			return;
		}
		$cat_infos = utils::$db->queryResult;

		// La création de catégorie est-elle autorisée ?
		self::getParents($cat_infos['cat_path']);
		if (!self::$catCreatable)
		{
			return;
		}

		// Création de la catégorie.
		$r = alb::create(
			$cat_infos['cat_id'],
			$cat_infos['cat_path'],
			$cat_infos['cat_password'],
			$_POST['type'],
			array('name' => $_POST['name'], 'desc' => $_POST['desc']),
			self::$infos['user_id']
		);

		// Rapport.
		if ($r !== TRUE)
		{
			self::report($r);
			return;
		}

		// Log d'activité.
		self::_logUserActivity('category_create');

		self::report('success:' . __('La catégorie a été créée.'));
	}

	/**
	 * Génère un nouveau mot de passe pour les utilisateurs qui l'ont oublié.
	 *
	 * @return void
	 */
	public static function newPassword()
	{
		if (!isset($_POST['login']) || !isset($_POST['email']) || !isset($_POST['code']))
		{
			return;
		}

		$password = user::newPassword($_POST['login'], $_POST['email'], $_POST['code']);
		if ($password === FALSE)
		{
			self::report('warning:' . __('Informations incorrectes.'));
			return;
		}

		self::$newPassword = $password;
		self::report('success:' . __('Le nouveau mot de passe que'
			. ' vous avez demandé a été créé :'));
	}

	/**
	 * Modification ou création d'un profil utilisateur.
	 *
	 * @return void
	 */
	public static function profile()
	{
		$profile_infos = utils::$config['users_profile_infos'];

		// Informations personnalisées.
		users::$infos['user_other'] = utils::isSerializedArray(users::$infos['user_other'])
			? unserialize(users::$infos['user_other'])
			: array();

		// Validation par captcha.
		if ($_GET['section'] == 'register'
		&& utils::$config['recaptcha_inscriptions'] && !self::_recaptcha('register'))
		{
			return;
		}

		if (empty($_POST))
		{
			return;
		}

		// Petite vérification antispam.
		if (!isset($_POST['f_email']) || $_POST['f_email'] !== '')
		{
			return;
		}

		// Vérification du jeton anti-CSRF pour l'édition du profil.
		if ($_GET['section'] == 'profile' && !utils::antiCSRFTokenCheck(utils::$cookiePrefs))
		{
			return;
		}

		// Vérifications par listes noires pour les inscriptions.
		if ($_GET['section'] == 'register')
		{
			if (!isset($_POST['login']))
			{
				return;
			}

			$email = isset($_POST['email']) ? $_POST['email'] : '';
			$r = self::_checkBlacklists($_POST['login'], $email);
			if (is_array($r))
			{
				// Log d'activité.
				self::_logUserActivity('register_reject_blacklist_' . $r['list'],
					NULL, $r['match']);

				return;
			}
		}

		try
		{
			$columns = array();
			$params = array();
			$required_message = __('Certains champs n\'ont pas été renseignés.');

			// Mot de passe actuel.
			if ($_GET['section'] == 'profile')
			{
				if (!isset($_POST['current_pwd']) || self::$infos['user_password'] !=
				utils::hashPassword($_POST['current_pwd'], self::$infos['user_crtdt']))
				{
					self::$fieldError = 'current_pwd';
					throw new Exception(__('Mot de passe incorrect.'));
				}
			}

			// Validation par mot de passe.
			if ($_GET['section'] == 'register'
			&& utils::$config['users_inscription_by_password'])
			{
				if (empty($_POST['pwd_validate'])
				|| utils::hashPassword($_POST['pwd_validate'], 'validate')
				!= utils::$config['users_inscription_password'])
				{
					self::$fieldError = 'pwd_validate';
					throw new Exception(__('Mot de passe de validation incorrect.'));
				}
			}

			// Nom d'utilisateur.
			if ($_GET['section'] == 'register')
			{
				if (($check = user::checkForm('login', $_POST['login'])) === TRUE)
				{
					$columns[] = 'user_login = :user_login';
					$params['user_login'] = $_POST['login'];
				}
				else
				{
					self::$fieldError = 'login';
					throw new Exception($check);
				}
			}

			// Mot de passe.
			if ($_GET['section'] == 'register' || !empty($_POST['pwd']))
			{
				// Vérification de la confirmation du mot de passe.
				if ($_POST['pwd_confirm'] != $_POST['pwd'])
				{
					self::$fieldError = 'pwd_confirm';
					throw new Exception(
						__('Les mots de passe ne correspondent pas.')
					);
				}

				// Vérification du mot de passe.
				if (($check = user::checkForm('pwd', $_POST['pwd'])) === TRUE)
				{
					$columns[] = 'user_password = :user_password';
					$crtdt = ($_GET['section'] == 'register')
						? date('Y-m-d H:i:s', self::$time)
						: self::$infos['user_crtdt'];
					$params['user_password'] = utils::hashPassword($_POST['pwd'], $crtdt);
				}
				else
				{
					self::$fieldError = 'pwd';
					throw new Exception($check);
				}
			}

			// Sexe.
			if (isset($_POST['sex'])
			&& ($_POST['sex'] == '?' || $_POST['sex'] == 'F' || $_POST['sex'] == 'M')
			&& $profile_infos['infos']['sex']['activate'])
			{
				$_POST['sex'] = ($_POST['sex'] == '?') ? NULL : $_POST['sex'];
				if ($_POST['sex'] != self::$infos['user_sex'])
				{
					$columns[] = 'user_sex = :user_sex';
					$params['user_sex'] = $_POST['sex'];
				}
			}

			// Nom.
			if (isset($_POST['name']) && $_POST['name'] != self::$infos['user_name']
			&& $profile_infos['infos']['name']['activate'])
			{
				$columns[] = 'user_name = :user_name';
				$params['user_name'] = (utils::isEmpty($_POST['name']))
					? NULL
					: $_POST['name'];
			}

			// Prénom.
			if (isset($_POST['firstname'])
			&& $_POST['firstname'] != self::$infos['user_firstname']
			&& $profile_infos['infos']['firstname']['activate'])
			{
				$columns[] = 'user_firstname = :user_firstname';
				$params['user_firstname'] = (utils::isEmpty($_POST['firstname']))
					? NULL
					: $_POST['firstname'];
			}

			// Date de naissance.
			if (isset($_POST['day']) && isset($_POST['month']) && isset($_POST['year'])
			&& $profile_infos['infos']['birthdate']['activate'])
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
			if (isset($_POST['loc']) && $_POST['loc'] != self::$infos['user_loc']
			&& $profile_infos['infos']['loc']['activate'])
			{
				$columns[] = 'user_loc = :user_loc';
				$params['user_loc'] = (utils::isEmpty($_POST['loc']))
					? NULL
					: $_POST['loc'];
			}

			// Courriel.
			if (isset($_POST['email']) && $_POST['email'] != self::$infos['user_email']
			&& $profile_infos['infos']['email']['activate'])
			{
				$user_id = ($_GET['section'] == 'register')
					? 0
					: self::$infos['user_id'];

				if ($_POST['email'] == ''
				|| ($check = user::checkForm('email', $_POST['email'], $user_id)) === TRUE)
				{
					$columns[] = 'user_email = :user_email';
					$params['user_email'] = ($_POST['email'] == '')
						? NULL
						: $_POST['email'];
				}
				else
				{
					self::$fieldError = 'email';
					throw new Exception($check);
				}
			}

			// Site Web.
			if (isset($_POST['website']) && $_POST['website'] != self::$infos['user_website']
			&& $profile_infos['infos']['website']['activate'])
			{
				if ($_POST['website'] == ''
				|| ($check = user::checkForm('website', $_POST['website'])) === TRUE)
				{
					$columns[] = 'user_website = :user_website';
					$params['user_website'] =
						($_POST['website'] == '') ? NULL : $_POST['website'];
				}
				else
				{
					self::$fieldError = 'website';
					throw new Exception($check);
				}
			}

			// Description.
			if (isset($_POST['desc']) && $_POST['desc'] != self::$infos['user_desc']
			&& $profile_infos['infos']['desc']['activate'])
			{
				if ($_POST['desc'] == ''
				|| ($check = user::checkForm('desc', $_POST['desc'])) === TRUE)
				{
					$columns[] = 'user_desc = :user_desc';
					$params['user_desc'] = (utils::isEmpty($_POST['desc']))
						? NULL
						: $_POST['desc'];
				}
				else
				{
					self::$fieldError = 'desc';
					throw new Exception($check);
				}
			}

			// Langue.
			if ($_GET['section'] == 'register')
			{
				$columns[] = 'user_lang = :user_lang';
				if (isset($_POST['lang']) && user::checkForm('lang', $_POST['lang']))
				{
					$params['user_lang'] = $_POST['lang'];
				}
				else
				{
					$params['user_lang'] = CONF_DEFAULT_LANG;
				}
			}
			else
			{
				if (isset($_POST['lang']) && $_POST['lang'] != self::$infos['user_lang']
				&& user::checkForm('lang', $_POST['lang']))
				{
					$columns[] = 'user_lang = :user_lang';
					$params['user_lang'] = $_POST['lang'];
				}
			}

			// Fuseau horaire.
			if ($_GET['section'] == 'register')
			{
				$columns[] = 'user_tz = :user_tz';
				if (isset($_POST['tz']) && user::checkForm('tz', $_POST['tz']))
				{
					$params['user_tz'] = $_POST['tz'];
				}
				else
				{
					$params['user_tz'] = CONF_DEFAULT_TZ;
				}
			}
			else
			{
				if (isset($_POST['tz']) && $_POST['tz'] != self::$infos['user_tz']
				&& user::checkForm('tz', $_POST['tz']))
				{
					$columns[] = 'user_tz = :user_tz';
					$params['user_tz'] = $_POST['tz'];
				}
			}

			// Uniquement pour la modification du profil.
			if ($_GET['section'] != 'register')
			{
				// Notifications par courriel.
				$user_alert = self::$infos['user_alert'];
				for ($i = 0, $l = strlen($user_alert); $i < $l; $i++)
				{
					// Vérification des permissions.
					switch ($i)
					{
						case 0 :
							if (!users::$perms['admin']['perms']['users_members']
							 && !users::$perms['admin']['perms']['all'])
							{
								continue 2;
							}
							break;

						case 1 :
						case 2 :
							if (!users::$perms['admin']['perms']['comments_edit']
							 && !users::$perms['admin']['perms']['all'])
							{
								continue 2;
							}
							break;

						case 3 :
						case 5 :
							if (!users::$perms['gallery']['perms']['alert_email'])
							{
								continue 2;
							}
							break;

						case 4 :
							if (!users::$perms['admin']['perms']['albums_pending']
							 && !users::$perms['admin']['perms']['all'])
							{
								continue 2;
							}
							break;

						default :
							return;
					}

					// Changement des notifications.
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
				if (self::$infos['group_admin'])
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
			}

			// Informations personnalisées.
			$user_other = self::$infos['user_other'];
			foreach ($profile_infos['perso'] as $id => &$p)
			{
				if ($p['required']
				&& (!isset($_POST[$id]) || utils::isEmpty(trim($_POST[$id]))))
				{
					self::$fieldError = $id;
					reset($profile_infos['perso']);
					throw new Exception($required_message);
				}
				if (isset($_POST[$id]) && $p['activate'])
				{
					if (!isset($user_other[$id]) || $_POST[$id] != $user_other[$id])
					{
						$user_other[$id] = $_POST[$id];
					}
				}
			}
			reset($profile_infos['perso']);
			if ($user_other != self::$infos['user_other'])
			{
				$columns[] = 'user_other = :user_other';
				$params['user_other'] = serialize($user_other);
			}

			// Champs obligatoires.
			foreach ($profile_infos['infos'] as $id => $i)
			{
				if (!$i['activate'] || !$i['required'])
				{
					continue;
				}
				if ($id == 'sex' && isset($_POST['sex'])
				&& ($_POST['sex'] == 'F' || $_POST['sex'] == 'M'))
				{
					continue;
				}
				if ($id == 'birthdate'
				&& isset($_POST['year']) && $_POST['month'] && $_POST['day'])
				{
					$birthdate = $_POST['year'] . '-' . $_POST['month'] . '-' . $_POST['day'];
					$regexp = '`^(?:(?!0000)\d{4}-(?!00)\d{2}-(?!00)\d{2})$`';
					if (preg_match($regexp, $birthdate))
					{
						continue;
					}
				}
				if (isset($_POST[$id]) && trim($_POST[$id]) != '')
				{
					continue;
				}

				// Champ non renseigné.
				self::$fieldError = $id;
				throw new Exception($required_message);
			}
			reset($profile_infos['infos']);

			// Aucun changement.
			if (empty($columns))
			{
				return;
			}

			// On effectue la création ou la mise à jour de l'utilisateur.
			if ($_GET['section'] == 'register')
			{
				$params['user_crtdt'] = date('Y-m-d H:i:s', self::$time);
				$params['user_crtip'] = $_SERVER['REMOTE_ADDR'];
				$params['user_lastvstip'] = '';

				// Statut et validations.
				if (utils::$config['users_inscription_by_mail'])
				{
					$params['user_status'] = '-2';
					$register_success = __('Pour valider votre compte, '
						. 'vous devez suivre la procédure indiquée dans '
						. 'le courriel qui vient de vous être envoyé.');
					$params['user_rkey'] = utils::genKey();
				}
				else if (utils::$config['users_inscription_moderate'])
				{
					$params['user_status'] = '-1';
					$register_success = __('Votre compte est en attente de'
						. ' validation par un administrateur. Vous serez prévenu par'
						. ' courriel dès que votre inscription sera validée.');
				}
				else
				{
					$params['user_status'] = '1';
					$register_success = __('Votre compte a été créé.'
						. ' Vous êtes maintenant identifié.');
				}

				$columns = array_keys($params);
				$sql = 'INSERT INTO ' . CONF_DB_PREF . 'users
									(' . implode(', ', $columns) . ')
							 VALUES (:' . implode(', :', $columns) . ')';
			}
			else
			{
				$sql = 'UPDATE ' . CONF_DB_PREF . 'users
						   SET ' . implode(', ', $columns) . '
						 WHERE user_id = ' . (int) self::$infos['user_id'];
			}
			if (utils::$db->prepare($sql) === FALSE
			|| utils::$db->executeExec($params) === FALSE)
			{
				self::report('error');
				return;
			}

			// Mise à jour du tableau d'informations.
			foreach ($params as $k => $v)
			{
				self::$infos[$k] = $v;
			}
			self::$infos['user_other'] = $user_other;

			// Sauvegarde de la langue dans un cookie.
			if (isset($params['user_lang']))
			{
				utils::$cookiePrefs->add('lang', $_POST['lang']);
			}

			// Confirmation.
			if ($_GET['section'] != 'register')
			{
				// Log d'activité.
				self::_logUserActivity('profile_change');

				self::report('success:' . __('Votre profil a été mis à jour.'));
				return;
			}

			// Identifiant de l'utilisateur créé.
			$user_id = utils::$db->connexion->lastInsertId();

			// Log d'activité.
			self::_logUserActivity('register_accept');

			self::$register = TRUE;
			self::report('success:' . $register_success);

			return $user_id;
		}
		catch (Exception $e)
		{
			self::report('warning:' . $e->getMessage());
		}
	}

	/**
	 * Paramétres des informations de profil.
	 *
	 * @return array
	 */
	public static function profileInfos()
	{
		$profile_infos = unserialize(utils::$config['users_profile_infos']);
		$profile_infos['required'] = 0;
		foreach ($profile_infos['perso'] as $id => $i)
		{
			if ($i['activate'] == 0)
			{
				unset($profile_infos['perso'][$id]);
				continue;
			}
			if ($i['required'] == 1)
			{
				$profile_infos['required'] = 1;
			}
		}
		foreach ($profile_infos['infos'] as $id => $i)
		{
			if ($i['required'] == 1)
			{
				$profile_infos['required'] = 1;
			}
		}
		reset($profile_infos['perso']);
		reset($profile_infos['infos']);

		// Courriel obligatoire si validation de l'inscription
		// par courriel ou par un administrateur.
		if (utils::$config['users_inscription_by_mail']
		 || utils::$config['users_inscription_moderate'])
		{
			if ($_GET['section'] == 'register')
			{
				$profile_infos['infos']['email']['required'] = 1;
			}
			$profile_infos['infos']['email']['activate'] = 1;
		}

		return $profile_infos;
	}

	/**
	 * Création d'un nouveau compte.
	 *
	 * @return void
	 */
	public static function register()
	{
		// Valeurs vierges pour les informations de profil.
		self::$infos = array(
			'user_login' => '',
			'user_name' => '',
			'user_firstname' => '',
			'user_sex' => '',
			'user_birthdate' => '',
			'user_loc' => '',
			'user_desc' => '',
			'user_email' => '',
			'user_website' => '',
			'user_lang' => utils::$userLang,
			'user_tz' => CONF_DEFAULT_TZ,
			'user_other' => array()
		);
		$profile_infos = utils::$config['users_profile_infos'];
		foreach ($profile_infos['perso'] as $id => $i)
		{
			self::$infos['user_other'][$id] = '';
		}
		self::$infos['user_other'] = serialize(self::$infos['user_other']);

		// Création du profil.
		$user_id = self::profile();
		if (!self::$register || (int) $user_id < 3)
		{
			return;
		}

		$mail = new mail();

		// Courriel de validation.
		if (utils::$config['users_inscription_by_mail'])
		{
			// Sujet.
			$subject = __('Validation de votre inscription à la galerie');

			// Message.
			$message = __('Pour valider votre inscription à la galerie %s,'
				. ' vous devez vous rendre à cette page :') . "\n\n";
			$message .= '%s' . "\n\n";
			$message .= __('et fournir le code suivant :') . "\n\n";
			$message .= '%s' . "\n\n";
			$message .= __('Ce code n\'est valide que pendant 24 heures.'
				. ' Passé ce délai, vous devrez vous réinscrire.');
			$message = sprintf(
				$message,
				GALLERY_HOST . utils::genURL(),
				GALLERY_HOST . utils::genURL('validation'),
				self::$infos['user_rkey']
			);

			// Préparation du courriel.
			$mail->messages[] = array(
				'to' => htmlspecialchars(self::$infos['user_email']),
				'subject' => $subject,
				'message' => $message
			);
		}

		// Notification de l'inscription aux administrateurs.
		else
		{
			$i = array(
				'user_id' => $user_id,
				'user_login' => self::$infos['user_login']
			);
			$mail->notify('inscription', NULL, 0, $i);
		}

		$mail->send();

		// Si les inscriptions ne sont validées ni par mail ni par un admin,
		// on connecte directement l'utilisateur.
		if (!utils::$config['users_inscription_moderate']
		 && !utils::$config['users_inscription_by_mail'])
		{
			// Création d'une catégorie lors de l'inscription.
			user::createCategory($user_id, self::$infos['user_login']);

			$_POST['auth_login'] = $_POST['login'];
			$_POST['auth_password'] = $_POST['pwd'];
			self::_authForm(FALSE);
			self::_authCookie();
		}
	}

	/**
	 * Validation de compte par courriel.
	 *
	 * @return void
	 */
	public static function validation()
	{
		if (empty($_POST) || !isset($_POST['login'])
		|| !isset($_POST['password']) || !isset($_POST['code']))
		{
			return;
		}

		// Récupération de la date de création du compte.
		$sql = 'SELECT user_crtdt
				  FROM ' . CONF_DB_PREF . 'users
				 WHERE user_login = :login';
		$params = array('login' => $_POST['login']);
		if (utils::$db->prepare($sql) === FALSE
		|| utils::$db->executeQuery($params, 'value') === FALSE)
		{
			self::report('error');
			return;
		}
		if (utils::$db->nbResult !== 1)
		{
			self::report('warning:' . __('Informations incorrectes.'));
			return;
		}
		$crtdt = utils::$db->queryResult;

		// Changement du statut de l'utilisateur s'il
		// a entré les bonnes informations et que le
		// compte a été créé dans les dernières 24 heures.
		$sql = 'UPDATE ' . CONF_DB_PREF . 'users
				   SET user_status = :status,
				       user_rkey = NULL
				 WHERE user_login = :login
				   AND user_password = :password
				   AND user_rkey = :code
				   AND ADDDATE(user_crtdt, 1) > NOW()
				   AND user_status = "-2"
				 LIMIT 1';
		$params = array(
			'status' => utils::$config['users_inscription_moderate'] ? -1 : 1,
			'login' => $_POST['login'],
			'password' => utils::hashPassword($_POST['password'], $crtdt),
			'code' => $_POST['code']
		);
		if (utils::$db->prepare($sql) === FALSE
		|| utils::$db->executeExec($params) === FALSE)
		{
			self::report('error');
			return;
		}
		if (utils::$db->nbResult !== 1)
		{
			self::report('warning:' . __('Informations incorrectes.'));
			return;
		}

		self::$register = TRUE;

		// Notification de l'inscription aux administrateurs.
		$sql = 'SELECT user_id
				  FROM ' . CONF_DB_PREF . 'users
				 WHERE user_login = :login';
		$params = array('login' => $_POST['login']);
		if (utils::$db->prepare($sql) === FALSE
		|| utils::$db->executeQuery($params, 'value') === FALSE)
		{
			self::report('error');
		}
		if (utils::$db->nbResult === 1)
		{
			$user_id = utils::$db->queryResult;
			$i = array(
				'user_id' => $user_id,
				'user_login' => $_POST['login']
			);
			$mail = new mail();
			$mail->notify('inscription', NULL, 0, $i);
			$mail->send();
		}

		// Message de confirmation.
		if (utils::$config['users_inscription_moderate'])
		{
			$report = 'success:' . __('Votre compte est en attente de'
				. ' validation par un administrateur. Vous serez prévenu par'
				. ' courriel dès que votre inscription sera validée.');
		}

		// Si les inscriptions ne sont pas validées par un admin,
		// on connecte directement l'utilisateur.
		else
		{
			// Création d'une catégorie lors de l'inscription.
			user::createCategory($user_id, $_POST['login']);

			$_POST['auth_login'] = $_POST['login'];
			$_POST['auth_password'] = $_POST['password'];
			self::_authForm(FALSE);
			self::_authCookie();

			$report = 'success:' . __('Votre compte a été validé.'
				. ' Vous êtes maintenant identifié.');
		}

		self::report($report);
	}

	/**
	 * Envoi d'images.
	 *
	 * @return void
	 */
	public static function upload()
	{
		if (empty($_POST['tempdir']) || !utils::antiCSRFTokenCheck(utils::$cookiePrefs)
		|| !utils::isSha1($_POST['tempdir']) || !isset($_POST['cat_id']))
		{
			return;
		}

		try
		{
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
					self::report('warning:' . urldecode($msg));
				}
			}

			// Répertoire temporaire.
			$temp_dir = GALLERY_ROOT . '/cache/up_temp/' . $_POST['tempdir'];
			if (!empty($_POST['files']) && !file_exists($temp_dir))
			{
				throw new Exception();
			}

			// Nouvelles images.
			if (isset($_POST['files']) && is_array($_POST['files']))
			{
				// Récupération du chemin de l'album destination.
				$sql = 'SELECT cat_path
						  FROM ' . CONF_DB_PREF . 'categories
						 WHERE cat_id = ' . (int) $_POST['cat_id'] . '
						   AND cat_filemtime IS NOT NULL
						   AND cat_uploadable = "1"';

				// L'utilisateur doit-il être propriétaire de la catégorie ?
				$sql .= (users::$perms['gallery']['perms']['upload_create_owner'])
					? ' AND user_id = ' . (int) users::$infos['user_id']
					: '';

				// L'envoi d'images dans des albums vides est-il autorisé ?
				$sql .= (utils::$config['upload_categories_empty'])
					? ' AND (cat_status = "1" OR (cat_status = "0" AND cat_d_images = 0))'
					: ' AND cat_status = "1"';

				$result = sql::sqlProtect('cat', $sql, 'value');
				if ($result === FALSE)
				{
					throw new Exception();
				}
				if ($result['nb_result'] === 0)
				{
					return;
				}
				$cat_path = $result['query_result'];

				// L'ajout d'images est-il autorisé pour cet album ?
				self::getParents($cat_path);
				if (!self::$catUploadable)
				{
					return;
				}

				$add_images = array_map('urldecode', $_POST['files']);
				$files = scandir($temp_dir);
			}

			// Si aucune image à ajouter, inutile d'aller plus loin.
			if (empty($add_images))
			{
				return;
			}

			// Upload direct.
			if (users::$perms['gallery']['perms']['upload_mode'])
			{
				// On déplace les images vers l'album destination.
				foreach ($add_images as &$file)
				{
					if (!in_array($file, $files))
					{
						continue;
					}

					$oldname = $temp_dir . '/' . $file;
					$newname = GALLERY_ROOT . '/'
						. CONF_ALBUMS_DIR . '/' . $cat_path . '/' . $file;
					if (file_exists($oldname) && !file_exists($newname))
					{
						files::renameFile($oldname, $newname);
					}
				}

				self::_uploadDirect($add_images, $cat_path);
			}

			// Mise en attente.
			else
			{
				// On déplace les images vers le répertoire d'attente.
				foreach ($add_images as &$file)
				{
					if (!in_array($file, $files))
					{
						continue;
					}

					$dest = GALLERY_ROOT . '/users/uploads/';
					$ext = preg_replace('`^.+(\.[^\.]+)$`', '$1', $file);

					// On modifie le nom du fichier si un fichier de
					// même nom existe dans le répertoire d'attente.
					$test = $file;
					$n = 1;
					while (file_exists($dest . '/' . utils::hashImages($test) . $ext))
					{
						if ($n > 99)
						{
							$file = NULL;
							continue 2;
						}
						$test = preg_replace('`^(.+)\.([^\.]+)$`', '\1_' . $n . '.\2', $file);
						$n++;
					}

					$oldname = $temp_dir . '/' . $file;
					$newname = $dest . '/' . utils::hashImages($test) . $ext;
					if (!files::renameFile($oldname, $newname))
					{
						$file = NULL;
						continue;
					}

					$file = $test;
				}

				self::_uploadPending($add_images, $cat_path);
			}

			// On supprime le répertoire temporaire.
			files::rmdir($temp_dir);
		}
		catch (Exception $e)
		{
			self::report('error');
		}
	}

	/**
	 * Options du filigrane.
	 *
	 * @return void
	 */
	public static function watermark()
	{
		self::$watermarkParams = utils::$config['watermark_params_default']
			= unserialize(utils::$config['watermark_params_default']);
		if (utils::isSerializedArray(self::$infos['user_watermark']))
		{
			self::$watermarkParams = self::$infos['user_watermark']
				= unserialize(self::$infos['user_watermark']);
		}

		$image_dir = 'images/watermarks/users/' . (int) self::$infos['user_id'] . '/';

		// Modification des options du filigrane.
		$r = watermark::changeOptions(self::$watermarkParams, $image_dir);
		if (self::$watermarkParams != self::$infos['user_watermark']
		&& (self::$infos['user_watermark'] === NULL
		&& self::$watermarkParams == utils::$config['watermark_params_default']) === FALSE)
		{
			// On effectue la mise à jour des paramètres.
			$sql = 'UPDATE ' . CONF_DB_PREF . 'users
					   SET user_watermark = :watermark_params
					 WHERE user_id = :user_id
					 LIMIT 1';
			$params = array(
				'user_id' => (int) self::$infos['user_id'],
				'watermark_params' => serialize(self::$watermarkParams)
			);
			if (utils::$db->prepare($sql) === FALSE
			 || utils::$db->executeExec($params) === FALSE)
			{
				self::report('error:' . utils::$db->msgError);
				return;
			}

			// Log d'activité.
			self::_logUserActivity('watermark_change');

			self::report('success:' . __('Modifications enregistrées.'));
			self::report('success_p:' . __('Les autres modifications ont été enregistrées.'));
		}

		// Rapport du changement de l'image de filigrane.
		if (is_string($r))
		{
			self::report($r);
		}

		// Chemin de l'image de filigrane.
		$image_file = $image_dir . self::$watermarkParams['image_file'];
		self::$watermarkParams['image_file'] = (self::$watermarkParams['image_file']
		&& file_exists(GALLERY_ROOT . '/' . $image_file)
		&& is_file(GALLERY_ROOT . '/' . $image_file))
			? $image_file
			: NULL;
	}



	/**
	 * Authentification utilisateur par cookie.
	 *
	 * @return boolean
	 */
	private static function _authCookie()
	{
		// Récupération de l'identifiant de session que possède l'utilisateur.
		if (($session_token = user::getSessionCookieToken()) === FALSE)
		{
			return FALSE;
		}

		// Récupération d'informations supplémentaires pour l'édition du profil.
		$sql = (in_array($_GET['section'], array('avatar', 'profile', 'watermark')))
			? 'user_nohits,
			   user_firstname,
			   user_sex,
			   user_password,
			   user_birthdate,
			   user_loc,
			   user_desc,
			   user_email,
			   user_website,
			   user_other,
			   user_watermark,
			   user_alert,
			   user_crtdt,'
			: '';

		// Récupération des permissions utilisateur
		// si identifiant de session valide.
		$sql = 'SELECT g.group_id,
					   g.group_title,
					   g.group_perms,
					   g.group_admin,
					   user_id,
					   user_name,
					   user_login,
					   ' . $sql . '
					   user_avatar,
					   user_nohits,
					   user_lang,
					   user_tz,
					   user_lastvstdt
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
		if (utils::$db->prepare($sql) === FALSE
		|| utils::$db->executeQuery($params, 'row') === FALSE)
		{
			return FALSE;
		}
		if (utils::$db->nbResult > 1)
		{
			trigger_error('Session error', E_USER_WARNING);
			return FALSE;
		}
		$infos = utils::$db->queryResult;
		$perms = $infos['group_perms'];
		unset($infos['group_perms']);
		if (!utils::isSerializedArray($perms))
		{
			return FALSE;
		}

		// Déconnexion.
		if (isset($_POST['deconnect_user']) && utils::antiCSRFTokenCheck(utils::$cookiePrefs)
		&& user::deconnect($infos['user_id'], $session_token))
		{
			utils::redirect();
			return FALSE;
		}

		// On ne reste pas sur la page de login.
		if (isset($_GET['section']) && $_GET['section'] == 'login')
		{
			utils::redirect();
		}

		self::$infos = $infos;
		self::$perms = unserialize($perms);

		// Langue et fuseau horaire de l'utilisateur.
		if (isset(utils::$config['locale_langs'][$infos['user_lang']]))
		{
			utils::$userLang = $infos['user_lang'];
		}
		utils::$userTz = $infos['user_tz'];

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

		return self::$auth = TRUE;
	}

	/**
	 * Identification utilisateur par formulaire.
	 *
	 * @param boolean $redirect
	 * @return void
	 */
	private static function _authForm($redirect = TRUE)
	{
		// Vérification des informations.
		if (!isset($_POST['auth_login']) || !isset($_POST['auth_password']))
		{
			return;
		}
		$session_token = user::auth($_POST['auth_login'], $_POST['auth_password']);
		if (!$session_token)
		{
			return;
		}

		// Date d'expiration du cookie.
		if (!isset($_POST['auth_remember']))
		{
			utils::$cookieSession->expire = 0;
		}

		// Enregistrement de l'identifiant de session
		// dans le cookie de l'utilisateur.
		utils::$cookieSession->delete();
		utils::$cookieSession->add('token', $session_token);
		utils::$cookieSession->write();

		// Redirection.
		if ($redirect)
		{
			utils::redirect
			(
				isset($_GET['section']) && $_GET['section'] == 'login'
					? NULL
					: $_GET['q']
			);
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

		$change = avatar::change($_FILES['new'], self::$infos['user_id'],
			self::$infos['user_avatar'], '');

		if ($change === FALSE)
		{
			return;
		}

		if ($change === TRUE)
		{
			self::$infos['user_avatar'] = 1;
			self::report('success:' . __('Votre avatar a été changé.'));
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
		self::report('success:' . __('Votre avatar a été supprimé.'));
	}

	/**
	 * Envoi direct des images.
	 *
	 * @param array $add_images
	 *	Nom de fichiers des images.
	 * @param string $cat_path
	 *	Chemin de l'album destination.
	 * @return void
	 */
	private static function _uploadDirect($add_images, $cat_path)
	{
		try
		{
			// Initialisation du scan.
			$upload = new upload();
			if ($upload->getInit === FALSE)
			{
				throw new Exception();
			}

			// Options de scan.
			$upload->setHttp = TRUE;
			$upload->setHttpImages = array_flip($add_images);
			$upload->setHttpOriginalDir = GALLERY_ROOT
				. '/cache/up_temp/' . $_POST['tempdir'] . '/original';
			$upload->setReportAllFiles = FALSE;
			$upload->setUpdateImages = FALSE;
			$upload->setUpdateThumbId = (bool) utils::$config['upload_update_thumb_id'];
			$upload->setUserId = self::$infos['user_id'];
			$upload->setUserLogin = self::$infos['user_login'];

			// Scan du répertoire de l'album courant.
			if ($upload->getAlbums($cat_path) === FALSE)
			{
				throw new Exception();
			}

			// Rapport.
			$errors = 0;
			if (!empty($upload->getReport['errors']))
			{
				$errors = count($upload->getReport['errors']);
				foreach ($upload->getReport['errors'] as $e)
				{
					self::report('warning:' . $e[0] . ': ' . $e[1]);
				}
			}
			$img_reject = 0;
			if (!empty($upload->getReport['img_reject']))
			{
				$img_reject = count($upload->getReport['img_reject']);
				foreach ($upload->getReport['img_reject'] as $i)
				{
					self::report('warning:' . $i[0] . ': ' . $i[2]);
				}
			}
			$img_add = 0;
			if ($upload->getReport['img_add'] > 0)
			{
				$img_add = $upload->getReport['img_add'];
				$message = ($upload->getReport['img_add'] > 1)
					? __('%s images ont été ajoutées à l\'album.')
					: __('%s image a été ajoutée à l\'album.');
				$message = sprintf($message, $upload->getReport['img_add']);
				self::report('success:' . $message);
				self::report('success_p:' . $message);
			}

			// Log d'activité.
			self::_logUserActivity('upload_images_direct', NULL, NULL,
				array('errors' => $errors, 'img_reject' => $img_reject, 'img_add' => $img_add));
		}
		catch (Exception $e)
		{
			self::report('error');
		}
	}

	/**
	 * Mise en attente des images envoyées.
	 *
	 * @param array $add_images
	 *	Nom de fichiers des images.
	 * @param string $cat_path
	 *	Chemin de l'album destination.
	 * @return void
	 */
	private static function _uploadPending($add_images, $cat_path)
	{
		$pending_path = GALLERY_ROOT . '/users/uploads/';

		try
		{
			// On insert les images dans la table des images en attente.
			$sql = 'INSERT INTO ' . CONF_DB_PREF . 'uploads (
					cat_id, user_id, up_file, up_type, up_filesize,
					up_exif, up_iptc, up_xmp,
					up_name, up_height, up_width, up_adddt, up_ip
				) VALUES (
					:cat_id, :user_id, :up_file, :up_type, :up_filesize,
					:up_exif, :up_iptc, :up_xmp,
					:up_name, :up_height, :up_width, NOW(), :up_ip
				)';
			$params = array();

			// Paramètres de la requête préparée.
			$n = 0;
			foreach ($add_images as &$file)
			{
				$ext = preg_replace('`^.+(\.[^\.]+)$`', '$1', $file);
				$filehash = utils::hashImages($file) . $ext;

				$i = img::getImageSize($pending_path . $filehash);
				if ($i === FALSE)
				{
					continue;
				}

				// Récupération des métadonnées si l'image
				// a été redimensionnée.
				$image_exif = NULL;
				$image_iptc = NULL;
				$image_xmp = NULL;
				$original = GALLERY_ROOT . '/cache/up_temp/'
					. $_POST['tempdir'] . '/original/' . $file;
				if (file_exists($original))
				{
					$metadata = new metadata($original);
					$image_exif = $metadata->getExifDB();
					$image_iptc = $metadata->getIptcDB();
					$image_xmp = $metadata->getXmpDB();
				}

				$params[] = array(
					'cat_id' => (int) $_POST['cat_id'],
					'user_id' => (int) self::$infos['user_id'],
					'up_file' => $file,
					'up_type' => (int) $i['filetype'],
					'up_filesize' => (int) filesize($pending_path . $filehash),
					'up_exif' => $image_exif,
					'up_iptc' => $image_iptc,
					'up_xmp' => $image_xmp,
					'up_name' => img::imageName($file),
					'up_height' => (int) $i['height'],
					'up_width' => (int) $i['width'],
					'up_ip' => $_SERVER['REMOTE_ADDR']
				);

				$n++;
			}
			$sql = array(array('sql' => $sql, 'params' => $params));
			if (utils::$db->exec($sql) === FALSE || utils::$db->nbResult === 0)
			{
				throw new Exception();
			}

			// Aucune image.
			if ($n < 1)
			{
				return;
			}

			// Notification par e-mail.
			$mail = new mail();
			$i = array(
				'user_id' => users::$infos['user_id'],
				'user_login' => users::$infos['user_login']
			);
			$mail->notify('images-pending', array($cat_path), users::$infos['user_id'], $i);
			$mail->send();

			// Log d'activité.
			self::_logUserActivity('upload_images_pending', NULL, NULL, array('img_add' => $n));

			// Rapport.
			$message = ($n > 1)
				? __('%s images ont été mises en attente de validation.')
				: __('%s image a été mise en attente de validation.');
			$message = sprintf($message, $n);
			self::report('success:' . $message);
			self::report('success_p:' . $message);
		}
		catch (Exception $e)
		{
			self::report('error');

			// On tente de supprimer les images du disque.
			foreach ($add_images as &$file)
			{
				if (file_exists($pending_path . $file))
				{
					unlink($pending_path . $file);
				}
			}
		}
	}
}



/**
 * Méthodes de template pour l'ensemble de la galerie.
 */
class tplGallery
{
	/**
	 * Numéro du commentaire.
	 *
	 * @see function nextComment
	 * @var integer
	 */
	protected $_commentNum = -1;



	/**
	 * Plan de la galerie.
	 *
	 * @see function _constructMap
	 * @var array
	 */
	private $_map;

	/**
	 * Identifiant d'un widget utilisateur.
	 *
	 * @see function getDefaultWidget
	 * @see function getWidgets
	 * @var string
	 */
	private $_userWidget;



	/**
	 * Option "se souvenir de moi ?".
	 *
	 * @return string
	 */
	public function getAddCommentRemember()
	{
		return utils::$cookiePrefs->read('com_author') || isset($_POST['remember'])
			? ' checked="checked"'
			: '';
	}

	/**
	 * L'élément $item des liens externes doit-il être affiché ?
	 *
	 * @param string $item
	 * @return boolean
	 */
	public function disExternalLinks($item = '')
	{
		$i = current(utils::$config['widgets_params']['links']['items']);

		switch ($item)
		{
			// Éléments des liens.
			case 'desc' :
			case 'title' :
			case 'url' :
				return trim($i[$item]) != '';

			// Titre du widget.
			case 'widget_title' :
				return utils::getLocale(
					utils::$config['widgets_params']['links']['title']
				) != '';

			// Le lien courant est-il activé ?
			default :
				return (bool) $i['activate'];
		}
	}

	/**
	 * Retourne l'élément de lien externe $item.
	 *
	 * @param string $item
	 * @return string
	 */
	public function getExternalLinks($item)
	{
		$i = current(utils::$config['widgets_params']['links']['items']);

		switch ($item)
		{
			// Éléments des liens.
			case 'desc' :
			case 'title' :
				return utils::tplProtect(utils::getLocale($i[$item]));

			case 'url' :
				return utils::tplProtect($i[$item]);

			// Titre du widget.
			case 'widget_title' :
				return utils::tplProtect(utils::getLocale(
					utils::$config['widgets_params']['links']['title']
				));
		}
	}

	/**
	 * Y a-t-il un prochain lien externe ?
	 *
	 * @return boolean
	 */
	public function nextExternalLinks()
	{
		static $next = -1;

		return template::nextObject(utils::$config['widgets_params']['links']['items'], $next);
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
			case 'adv_search' :
			case 'alert_email' :
			case 'comments' :
			case 'create_albums' :
			case 'edit' :
			case 'members_list' :
			case 'options' :
			case 'upload' :
			case 'votes' :
				return (bool) users::$perms['gallery']['perms'][$item];
		}
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
	 * Informations détaillées des requêtes SQL.
	 *
	 * @return string
	 */
	public function getDebugSQL()
	{
		return template::debugSQL();
	}

	/**
	 * L'élément de la galerie $item doit-il être affiché ?
	 *
	 * @param string $item
	 * @return boolean
	 */
	public function disGallery($item)
	{
		switch ($item)
		{
			// Configuration.
			case 'avatars' :
			case 'basket' :
			case 'comments' :
			case 'comments_moderate' :
			case 'diaporama' :
			case 'debug_sql' :
			case 'exec_time' :
			case 'geoloc' :
			case 'rss' :
			case 'search' :
			case 'tags' :
			case 'thumbs_cat_extended' :
			case 'users' :
			case 'users_inscription' :
			case 'users_only_members' :
			case 'watermark_users' :
				return (bool) utils::$config[$item];

			// Lien canonique pour la page des images.
			case 'canonical_image' :
				if (!isset($_SERVER['REQUEST_URI']))
				{
					return FALSE;
				}

				return $_GET['section'] == 'image'
					&& GALLERY_HOST . $_SERVER['REQUEST_URI'] != $this->getImage('canonical');

			// Commentaires dans la section courante.
			case 'comments_category' :
				return ($_GET['album_page'] || $_GET['section'] == 'category')
					&& category::$infos['cat_a_comments'] > 0
					&& utils::$config['pages_params']['comments']['status'];

			// Page des commentaires.
			case 'comments_page' :
				return utils::$config['pages_params']['comments']['status']
					&& utils::$config['comments'];

			// Lien de téléchargement de l'image.
			case 'download_image' :
				return (!utils::$config['users'] || (utils::$config['users']
					&& users::$perms['gallery']['perms']['image_original']))
					&& !$this->disGallery('images_anti_copy');

			// Lien de téléchargement de l'album.
			case 'download_zip_albums' :
				return utils::$config['download_zip_albums']
					&& ($_GET['section'] == 'album' || $_GET['section'] == 'images')
					&& category::$infos['cat_filemtime'] !== NULL;

			// Message de pied de page.
			case 'footer_message' :
				return $this->getGallery('footer_message') !== '';

			// Page d'accueil de la galerie.
			case 'home' :
				return $_GET['section'] == 'category'
					&& $_GET['object_id'] == 1
					&& $_GET['page'] == 1;

			// Anti-copie des images.
			case 'images_anti_copy' :
				return (utils::$config['images_anti_copy']
					 && !utils::$config['users'])
					|| (utils::$config['images_anti_copy']
					 && utils::$config['users']
					 && !users::$perms['gallery']['perms']['image_original']);

			// Switch de langues.
			case 'lang_switch' :
				return utils::$config['lang_switch']
					&& !$this->disAuthUser();

			// Liste des membres.
			case 'members_list' :
				return utils::$config['users']
					&& (utils::$config['pages_params']['members']['status']
					|| ($_GET['section'] == 'user'
						&& users::$infos['user_id'] == $_GET['object_id']));

			// Contenu pour balise <meta name="description"...>.
			case 'meta_description' :
				switch ($_GET['section'])
				{
					case 'album' :
					case 'category' :
						return isset(category::$infos['cat_desc'])
							&& trim(strip_tags(utils::getLocale(
								category::$infos['cat_desc']))) !== '';

					case 'image' :
						return isset(image::$infos['image_desc'])
							&& trim(strip_tags(utils::getLocale(
								image::$infos['image_desc']))) !== '';

					default :
						return FALSE;
				}

			// Éléments de navigation.
			case 'nav_categories' :
				return (bool) utils::$config['widgets_params']
					['navigation']['items'][substr($item, 4)];

			case 'nav_neighbours' :
				return utils::$config['widgets_params']
					['navigation']['items'][substr($item, 4)]
					&& category::$neighbours !== NULL;

			// Moteur de recherche.
			case 'nav_search' :
				return utils::$config['search']
					&& utils::$config['widgets_params']
						['navigation']['items'][substr($item, 4)];

			// Titre de la page.
			case 'page_title' :
				switch ($_GET['section'])
				{
					case 'basket' :
					case 'date-added' :
					case 'date-created' :
					case 'images' :
					case 'hits' :
					case 'recent-images' :
					case 'tag' :
						return TRUE;

					default :
						return !empty(gallery::$pageTitle);
				}

			// Recherche avancée.
			case 'search_advanced' :
				return utils::$config[$item]
					&& (utils::$config['users'] &&
					   !users::$perms['gallery']['perms']['adv_search']) === FALSE;

			// Widget "Statistiques des images".
			case 'widget_stats_images' :
				return utils::$config['widgets_params']['stats_images']['status']
					&& template::disImageStats();
		}
	}

	/**
	 * Retourne l'élément de la galerie $item.
	 *
	 * @param string $item
	 * @return string
	 */
	public function getGallery($item)
	{
		switch ($item)
		{
			// Nouveau jeton anti-CSRF.
			case 'anticsrf' :
				return utils::tplProtect(utils::$anticsrfToken);

			// Chemin du répertoire des avatars.
			case 'avatars_path' :
				return $this->getGallery('gallery_path') . '/users/avatars';

			// Jeu de caractères.
			case 'charset' :
				return utils::tplProtect(CONF_CHARSET);

			// Temps d'exécution.
			case 'exec_time' :
				$total_time = microtime(TRUE) - START_TIME;
				$message = ($total_time > 1)
					? __('page générée en %.3f secondes')
					: __('page générée en %.3f seconde');
				return utils::numeric(sprintf($message, $total_time));

			// Message de pied de page.
			case 'footer_message' :
				return nl2br(utils::tplHTMLFilter(
					utils::getLocale(utils::$config['gallery_footer_message'])
				));

			// Chemin absolu de la galerie.
			case 'gallery_abs_path' :
				return utils::tplProtect(GALLERY_ROOT);

			// Base pour tout URL de la galerie.
			case 'gallery_base_url' :
				return (CONF_URL_REWRITE)
					? utils::genURL('')
					: substr(utils::genURL('1'), 0, -1);

			// Description de la galerie.
			case 'gallery_description' :
			case 'gallery_description_guest' :
				return utils::tplProtect(utils::getLocale(utils::$config[$item]));

			// Chemin de la galerie.
			case 'gallery_path' :
				return utils::tplProtect(CONF_GALLERY_PATH);

			// Titre de la galerie.
			case 'gallery_title' :
				return utils::tplProtect(utils::getLocale(utils::$config[$item]));

			// Titre de la galerie ou bannière.
			case 'gallery_title_banner' :
				$title = $this->getGallery('gallery_title');
				$banner = unserialize(utils::$config['gallery_banner']);
				if ($banner['banner'])
				{
					$size = ($banner['width'] && $banner['height'])
						? ' width="' . (int) $banner['width']
							. '" height="' . (int) $banner['height'] . '"'
						: '';
					return '<img src="' . $this->getGallery('gallery_path')
						. '/images/banners/' . utils::tplProtect($banner['src'])
						. '" alt="' . $title . '"' . $size . ' />';
				}
				return $title;

			// Géolocalisation : clé Google pour l'API Google Maps.
			case 'geoloc_key' :
				return utils::tplProtect(utils::$config['geoloc_key']);

			// Géolocalisation : type de carte.
			case 'geoloc_type' :
				return utils::tplProtect(utils::$config['geoloc_type']);

			// Code de la langue de l'utilisateur courant.
			case 'lang_current_code' :
				return utils::tplProtect(utils::$userLang);

			// Nom de la langue de l'utilisateur courant.
			case 'lang_current_name' :
				return utils::tplProtect(utils::$config['locale_langs'][utils::$userLang]);

			// Code de la langue par défaut.
			case 'lang_default_code' :
				return utils::tplProtect(CONF_DEFAULT_LANG);

			// Séparateur de catégorie.
			case 'level_separator' :
				return utils::tplProtect(utils::$config['level_separator']);

			// Contenu pour balise <meta name="description"...>.
			case 'meta_description' :
				switch ($_GET['section'])
				{
					case 'album' :
					case 'category' :
						$desc = category::$infos['cat_desc'];
						break;

					case 'image' :
						$desc = image::$infos['image_desc'];
						break;

					default :
						return;
				}

				return utils::tplProtect(
					preg_replace('`[\r\n]+`', ' ', strip_tags(utils::getLocale($desc)))
				);

			// Nom du fichier de template pour la page actuelle.
			case 'page_filename' :
				return (gallery::$tplFile !== NULL)
					? gallery::$tplFile
					: 'category';

			// Titre de la section.
			case 'page_title' :

				// Numéro de page.
				switch ($_GET['section'])
				{
					case 'category' :
						$add_page_num = category::$nbPages > 1;
						break;

					case 'comments' :
					case 'user-comments' :
						$add_page_num = comments::$nbPages > 1;
						break;

					case 'members' :
						$add_page_num = users::$nbPages > 1;
						break;

					default :
						$add_page_num = $_GET['album_page'] && album::$nbPages > 1;
				}
				$page_num = ($add_page_num)
					? sprintf(' / ' . __('page %s'), $_GET['page']) : '';

				// Titre de la page.
				$second = '';
				switch ($_GET['section'])
				{
					case 'basket' :
						$message = __('panier');
						break;

					case 'date-added' :
						$second = $this->_getDateLocale();
						$message = (strlen($_GET['date']) == 10)
							? mb_strtolower(__('Images de %s ajoutées le %s'))
							: mb_strtolower(__('Images de %s ajoutées en %s'));
						break;

					case 'date-created' :
						$second = $this->_getDateLocale();
						$message = (strlen($_GET['date']) == 10)
							? mb_strtolower(__('Images de %s créées le %s'))
							: mb_strtolower(__('Images de %s créées en %s'));
						break;

					case 'images' :
						$message = __('images de %s');
						break;

					case 'hits' :
						$message = __('images les plus visitées de %s');
						break;

					case 'recent-images' :
						$message = __('images les plus récentes de %s');
						break;

					case 'tag' :
						$message = tags::$tagInfos['tag_name'];
						break;

					default :
						return utils::tplProtect(gallery::$pageTitle . $page_num);
				}

				$cat_name = strip_tags(category::$infos['type_html']);
				return sprintf($message . $page_num, $cat_name, $second);

			// Propulsé par.
			case 'powered_by' :
				return sprintf(__('propulsé par %s'),
					'<a class="ex" href="http://www.igalerie.org/">iGalerie</a>');

			// Paramètre d'URL "q".
			case 'q' :
				return isset($_GET['q']) ? utils::php2js($_GET['q']) : '';

			// MD5 servant à garantir l'authenticité du paramètre "q".
			case 'q_md5' :
				return md5('key:' . CONF_KEY . '|q:' . $this->getGallery('q'));

			// Requête de la recherche.
			case 'search_query' :
				return (isset($_GET['search_query']))
					? utils::tplProtect($_GET['search_query'])
					: '';

			// Style additionnel.
			case 'style_additional' :
				return utils::tplProtect(utils::$config['theme_css']);

			// Nom de la feuille de style CSS.
			case 'style_name' :
				return utils::tplProtect(utils::$config['theme_style']);

			// Chemin du fichier de la feuille de style CSS.
			case 'style_file' :
				return $this->getGallery('style_path') . '/'
					. utils::tplProtect(utils::$config['theme_style']) . '.css';

			// Chemin du répertoire de la feuille de style CSS.
			case 'style_path' :
				return $this->getGallery('template_path') . utils::tplProtect(
					'/style/' . utils::$config['theme_style']
				);

			// Nom du template.
			case 'template_name' :
				return utils::tplProtect(utils::$config['theme_template']);

			// Chemin du template.
			case 'template_path' :
				return utils::tplProtect(CONF_GALLERY_PATH
					. '/template/'
					. utils::$config['theme_template']
				);

			// Valeur formatée de la directive PHP 'upload_max_filesize'.
			case 'upload_max_filesize_formated' :
				return utils::tplProtect(
					utils::filesize($this->getGallery('upload_max_filesize_value'))
				);

			// Valeur entière de la directive PHP 'upload_max_filesize'.
			case 'upload_max_filesize_value' :
				return utils::tplProtect(utils::uploadMaxFilesize());
		}
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
	 * L'élément $item des utilisateurs en ligne doit-il être affiché ?
	 *
	 * @param string $item
	 * @return boolean
	 */
	public function disOnlineUsers($item = '')
	{
		switch ($item)
		{
			// Titre du widget.
			case 'widget_title' :
				return utils::getLocale(
					utils::$config['widgets_params']['online_users']['title']
				) != '';
		}
	}

	/**
	 * Retourne l'élément des utilisateurs en ligne $item.
	 *
	 * @param string $item
	 * @return string
	 */
	public function getOnlineUsers($item)
	{
		$i = current(gallery::$widgetOnlineUsers);

		switch ($item)
		{
			// Dernière visite.
			case 'last_visited' :
				return sprintf(
					__('Dernière visite à %s'),
					utils::tplProtect(substr($i['user_lastvstdt'], 11))
				);

			// Lien vers la page de profil de l'utilisateur.
			case 'user_link' :
				return utils::genURL('user/' . (int) $i['user_id']);

			// Nom d'utilisateur.
			case 'user_login' :
				return utils::tplProtect($i['user_login']);

			// Titre du widget.
			case 'widget_title' :
				return utils::tplProtect(utils::getLocale(
					utils::$config['widgets_params']['online_users']['title']
				));
		}
	}

	/**
	 * Y a-t-il un prochain utilisateur en ligne ?
	 *
	 * @return boolean
	 */
	public function nextOnlineUsers()
	{
		static $next = -1;

		return template::nextObject(gallery::$widgetOnlineUsers, $next);
	}

	/**
	 * Y a-t-il des liens activés ?
	 *
	 * @return boolean
	 */
	public function disPageLinks()
	{
		return count(utils::$config['pages_order']) > 0;
	}

	/**
	 * Retourne l'élément de page $item.
	 *
	 * @param string $item
	 * @return boolean
	 */
	public static function getPageLink($item)
	{
		$i = current(utils::$config['pages_order']);

		switch ($item)
		{
			// Identifiant de la page.
			case 'id' :
				return utils::tplProtect($i);

			// Lien vers la page.
			case 'link' :
				switch ($i)
				{
					case 'basket' :
					case 'cameras' :
					case 'comments' :
					case 'contact' :
					case 'guestbook' :
					case 'history' :
					case 'members' :
					case 'sitemap' :
					case 'tags' :
					case 'worldmap' :
						return utils::genURL($i);

					default :
						if (!isset(utils::$config['pages_params'][$i]['url']))
						{
							return;
						}
						return utils::genURL(
							'page/' . str_replace('perso_', '', $i) . '-'
							. utils::$config['pages_params'][$i]['url']
						);
				}

			// Titre du lien.
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
						if (!isset(utils::$config['pages_params'][$i]['title']))
						{
							return;
						}
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
	public function nextPageLink()
	{
		static $next = -1;

		return template::nextObject(utils::$config['pages_order'], $next);
	}

	/**
	 * Doit-on afficher une liste d'albums ?
	 *
	 * @return boolean
	 */
	public function disPositionAlbumsList()
	{
		return substr($_GET['section'], 0, 4) == 'user';
	}

	/**
	 * Retourne la liste des albums dans lesquels se trouvent
	 * les images de la section courante.
	 *
	 * @return string
	 */
	public function getPositionAlbumsList()
	{
		$value = utils::genURL($_GET['section'] . '/' . $_GET['user_id'], TRUE);
		$selected = ($_GET['object_id'] == 1)
			? ' selected="selected" class="selected"'
			: '';
		$options = '<option ' . $selected . ' value="' . $value . '">'
			. __('galerie') . '</option>';
		foreach (album::$items as $k => &$infos)
		{
			$type = ($infos['cat_filemtime'] === NULL)
				? 'category'
				: 'album';
			$value = utils::genURL(
				$_GET['section'] . '/' . $_GET['user_id'] . '/' .
				$type . '/' . $infos['cat_id'] . '-' . $infos['cat_url'],
				TRUE
			);
			$selected = ($infos['cat_id'] == $_GET['object_id'])
				? ' selected="selected" class="selected"'
				: '';
			$options .= '<option ' . $selected . ' value="'
				. $value . '">&nbsp;&nbsp;&nbsp;|-- ' . $k . '</option>';
		}

		return $options;
	}

	/**
	 * Retourne la valeur du paramètre $pref
	 * contenu dans le cookie des préférences.
	 *
	 * @param string $pref
	 * @return string
	 */
	public function getCookiePref($pref)
	{
		return utils::tplProtect(utils::$cookiePrefs->read($pref));
	}

	/**
	 * L'élément de gestion de membre $item doit-il être affiché ?
	 *
	 * @param string $item
	 * @return boolean
	 */
	public function disAuthUser($item = '')
	{
		switch ($item)
		{
			// Utilisateur authentifié (membre) ?
			case '' :
				return (bool) users::$auth;

			// L'utilisateur est-il un administrateur ?
			case 'admin' :
				return $this->disAuthUser()
					&& users::$infos['group_admin'];

			// L'utilisateur est-il super-administrateur ?
			case 'superadmin' :
				return $this->disAuthUser()
					&& users::$infos['user_id'] == 1;

			// Permissions pour l'utilisateur.
			default :
				if (substr($item, 0, 5) == 'perm_'
				&& isset(users::$perms['gallery']['perms'][substr($item, 5)]))
				{
					return (bool) users::$perms['gallery']['perms'][substr($item, 5)];
				}
		}
	}

	/**
	 * Retourne l'élément de l'utilisateur authentifié $item.
	 *
	 * @param string $item
	 * @return mixed
	 */
	public function getAuthUser($item)
	{
		if (utils::$config['users'] != 1)
		{
			return FALSE;
		}

		switch ($item)
		{
			// Lien vers l'administration.
			case 'admin_link' :
				return $this->disAuthUser('admin')
					? CONF_GALLERY_PATH . '/' . CONF_ADMIN_DIR . '/'
					: CONF_GALLERY_PATH . '/';

			// Emplacement de l'avatar.
			case 'avatar_src' :
				$rand = (empty($_POST)) ? '' : '?' . mt_rand();
				return (users::$infos['user_avatar'])
					? $this->getGallery('avatars_path') . '/user'
						. users::$infos['user_id'] . '_thumb.jpg' . $rand
					: $this->getGallery('style_path') . '/avatar-default.png';

			// Identifiant.
			case 'id' :
				return (int) users::$infos['user_id'];

			// Liens vers les pages utilisateurs.
			case 'link_avatar' :
			case 'link_new_category' :
			case 'link_profile' :
			case 'link_upload' :
				return utils::genURL(str_replace('_', '-', substr($item, 5)));

			// Lien vers les objets liés à l'utilisateur.
			case 'link_comments' :
			case 'link_favorites' :
			case 'link_images' :
				return utils::genURL(
					'user-' . substr($item, 5) . '/' . users::$infos['user_id']
				);

			// Informations utilisateur.
			case 'login' :
			case 'name' :
				return utils::tplProtect(users::$infos['user_' . $item]);

			// Jeton de session.
			case 'session_token' :
				return utils::tplProtect(utils::$cookieSession->read('token'));
		}
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
	 * Retourne le lien pour la section $section.
	 *
	 * @param string $section
	 *	Section à convertir en URL.
	 * @return string
	 */
	public function getLink($section)
	{
		switch ($section)
		{
			// Page des commentaires de la catégorie courante.
			case 'comments_category' :
				return (category::$infos['cat_id'] == 1)
					? utils::genURL('comments')
					: utils::genURL('comments/' . category::$purlId);

			default :
				return utils::genURL($section);
		}
	}

	/**
	 * Retourne le plan de la galerie.
	 *
	 * @param string $item
	 *	Format du plan à retourner.
	 * @return string
	 */
	public function getMap($item)
	{
		switch ($item)
		{
			// Plan sous la forme <ul>...</ul>.
			case 'list' :
				return $this->_mapList();

			// Plan sous la forme <select>...</select>.
			case 'select' :
				$cat_id = 0;
				if (category::$infos !== NULL)
				{
					$cat_id = category::$infos['cat_id'];
				}
				else if (image::$infos !== NULL)
				{
					$cat_id = image::$infos['cat_id'];
				}
				return template::mapSelect(map::$categories, array(
					'class_selected' => TRUE,
					'selected' => $cat_id,
					'status' => array(1),
					'value_url' => TRUE
				));
		}
	}

	/**
	 * Retourne les catégories voisines, sous forme de liste déroulante.
	 *
	 * @return string
	 */
	public function getNeighbours()
	{
		$options = '';
		$nb =& category::$neighbours;
		for ($i = 0, $count = count($nb); $i < $count; $i++)
		{
			if (($_GET['section'] == 'category' && $nb[$i]['cat_filemtime'] === NULL)
			|| ($_GET['section'] == 'album' && $nb[$i]['cat_filemtime'] !== NULL))
			{
				$type = ($nb[$i]['cat_filemtime'] === NULL)
					? 'category'
					: 'album';
				$selected = (category::$infos['cat_id'] == $nb[$i]['cat_id'])
					? ' selected="selected" class="selected"'
					: '';
				$value = $type . '/' . $nb[$i]['cat_id'] . '-'
					. $nb[$i]['cat_url'];

				$options .= '<option' . $selected
					. ' value="' . utils::tplProtect($value) . '">'
					. utils::tplProtect(utils::getLocale($nb[$i]['cat_name'])) . '</option>';
			}
		}
		return $options;
	}

	/**
	 * L'élément de flux RSS $item doit-il être affiché ?
	 *
	 * @param string $item
	 * @return boolean
	 */
	public function disRSS($item = '')
	{
		if (!$this->disGallery('rss'))
		{
			return FALSE;
		}

		$page_category = ($_GET['section'] == 'category' || $_GET['section'] == 'album')
			&& empty(category::$infos['cat_password']);

		$page_image = $_GET['section'] == 'image'
			&& empty(image::$infos['cat_password']);

		switch ($item)
		{
			// Flux des commentaires.
			case 'comments' :
				return $this->disGallery('comments')
					&& ($page_category || $page_image);

			// Flux des images.
			case 'images' :
				return $page_category;

			// Flux RSS.
			default :
				return $this->disRSS('comments') || $this->disRSS('images');
		}
	}

	/**
	 * Retourne l'élément $item des flux RSS.
	 *
	 * @param string $item
	 * @return string
	 */
	public function getRSS($item)
	{
		switch ($item)
		{
			// Description du flux RSS des commentaires.
			case 'comments_desc' :
				switch ($_GET['section'])
				{
					case 'album' :
						return __('Fil RSS 2.0 des commentaires de cet album');

					case 'category' :
						return ($_GET['object_id'] > 1)
							? __('Fil RSS 2.0 des commentaires de cette catégorie')
							: __('Fil RSS 2.0 des commentaires de la galerie');

					case 'image' :
						return __('Fil RSS 2.0 des commentaires de cette image');
				}
				break;

			case 'comments_desc_head' :
				return __('Fil RSS 2.0 des commentaires de la galerie');

			// Emplacement du flux RSS des commentaires.
			case 'comments_url' :
				$q = ($_GET['section'] == 'image')
					? 'image/' . image::$infos['image_id']
						. '-' . image::$infos['image_url']
					: gallery::$sectionRequest;
				return utils::tplProtect(
					CONF_GALLERY_PATH . '/rss.php?q=' . $q
					. '&type=comments'
					. '&lang=' . utils::$userLang
					. '&key=' . md5('rsscomments|' . CONF_KEY)
				);

			case 'comments_url_head' :
				return utils::tplProtect(
					CONF_GALLERY_PATH . '/rss.php?q=category/1-' . __('galerie')
					. '&type=comments'
					. '&lang=' . utils::$userLang
					. '&key=' . md5('rsscomments|' . CONF_KEY)
				);

			// Description du flux RSS des images.
			case 'images_desc' :
				switch ($_GET['section'])
				{
					case 'album' :
						return __('Fil RSS 2.0 des images de cet album');

					case 'category' :
						return ($_GET['object_id'] > 1)
							? __('Fil RSS 2.0 des images de cette catégorie')
							: __('Fil RSS 2.0 des images de la galerie');
				}
				break;

			case 'images_desc_head' :
				return __('Fil RSS 2.0 des images de la galerie');

			// Emplacement du flux RSS des images.
			case 'images_url' :
				return utils::tplProtect(
					CONF_GALLERY_PATH . '/rss.php?q=' . gallery::$sectionRequest
					. '&type=images'
					. '&lang=' . utils::$userLang
					. '&key=' . md5('rssimages|' . CONF_KEY)
				);

			case 'images_url_head' :
				return utils::tplProtect(
					CONF_GALLERY_PATH . '/rss.php?q=category/1-' . __('galerie')
					. '&type=images'
					. '&lang=' . utils::$userLang
					. '&key=' . md5('rssimages|' . CONF_KEY)
				);
		}
	}

	/**
	 * L'élément de géolocalisation $item doit-il être affiché ?
	 *
	 * @param string $item
	 * @return boolean
	 */
	public function disWidgetGeoloc($item = '')
	{
		switch ($item)
		{
			// Titre.
			case 'title' :
				return utils::$config['widgets_params']['geoloc']['title'] != '';
		}
	}

	/**
	 * Retourne l'élément $item du widget de géolocalisation.
	 *
	 * @param string $item
	 * @return string
	 */
	public function getWidgetGeoloc($item)
	{
		switch ($item)
		{
			// Titre.
			case 'title' :
				return utils::tplProtect(utils::getLocale(
					utils::$config['widgets_params']['geoloc']['title']
				));
		}
	}

	/**
	 * L'élément du widget "tags" $item doit-il être affiché ?
	 *
	 * @param string $item
	 * @return boolean
	 */
	public function disWidgetTags($item)
	{
		switch ($item)
		{
			case 'title' :
				return utils::getLocale(
					utils::$config['widgets_params']['tags']['title']
				) != '';
		}
	}

	/**
	 * Retourne l'élément $item du widget "tags".
	 *
	 * @param string $item
	 * @return boolean
	 */
	public function getWidgetTags($item)
	{
		switch ($item)
		{
			case 'all_tags_link' :
				return (category::$infos['cat_id'] == 1)
					? utils::genURL('tags')
					: utils::genURL('tags/' . category::$purlId);

			case 'title' :
				return utils::tplProtect(utils::getLocale(
					utils::$config['widgets_params']['tags']['title']
				));
		}
	}

	/**
	 * Retourne l'élément de tag $item.
	 *
	 * @param string $item
	 * @return string|integer
	 */
	public function getWidgetTag($item)
	{
		return $this->_getTag($item, tags::$widgetTags);
	}

	/**
	 * Y a-t-il une prochaine tag ?
	 *
	 * @return boolean
	 */
	public function nextWidgetTag()
	{
		return $this->_nextTag(tags::$widgetTags);
	}

	/**
	 * Y a-t-il une prochaine vignette ?
	 *
	 * @return boolean
	 */
	public function nextWidgetImage()
	{
		static $next = -1;

		return $this->_disWidgets('image')
			&& template::nextObject(gallery::$widgetImage, $next);
	}

	/**
	 * Retourne l'élément $item de l'image aléatoire.
	 *
	 * @param string $item
	 * @return string|integer
	 */
	public function getWidgetImage($item)
	{
		$i = current(gallery::$widgetImage);

		switch ($item)
		{
			// Lien vers l'album.
			case 'album_link' :
				return utils::genURL('album/' . $i['cat_id'] . '-' . $i['cat_url']);

			// Titre de l'album.
			case 'album_title' :
				return utils::tplProtect(utils::getLocale($i['cat_name']));

			// CSS de centrage de la vignette.
			case 'center' :
				$tb = img::thumbSize('wid', $i['image_width'], $i['image_height']);
				return img::thumbCenter('wid', $tb['width'], $tb['height']);

			// Hauteur de la vignette.
			case 'height' :
				$height = (CONF_THUMBS_WID_METHOD == 'crop')
					? CONF_THUMBS_WID_HEIGHT
					: CONF_THUMBS_WID_SIZE;
				return (int) $height;

			// Lien vers l'image.
			case 'link' :
				if (utils::$config['images_direct_link'])
				{
					return (CONF_URL_REWRITE)
						? utils::tplProtect(
							CONF_GALLERY_PATH . '/image/' . $i['cat_url'] . '/'
								. $i['image_id'] . '-' . strtolower(basename($i['image_path']))
						  )
						: utils::tplProtect(
							CONF_GALLERY_PATH . '/image.php?id=' . $i['image_id']
								. '&file=' . basename($i['image_path'])
						  );
				}
				else
				{
					return utils::genURL('image/' . $i['image_id'] . '-' . $i['image_url']);
				}

			// Emplacement de la vignette.
			case 'thumb' :
				$img_height = (CONF_THUMBS_IMG_METHOD == 'crop')
					? CONF_THUMBS_IMG_HEIGHT
					: CONF_THUMBS_IMG_SIZE;
				$img_width = (CONF_THUMBS_IMG_METHOD == 'crop')
					? CONF_THUMBS_IMG_WIDTH
					: CONF_THUMBS_IMG_SIZE;

				// Si les paramètres de vignettes pour l'image aléatoire sont
				// les mêmes que ceux pour les vignettes des images, alors on
				// utilise les vignettes des images plutôt que d'en créer de nouvelles.
				if ($this->getWidgetImage('height') != $img_height
				|| $this->getWidgetImage('width') != $img_width
				|| CONF_THUMBS_WID_METHOD != CONF_THUMBS_IMG_METHOD)
				{
					$thumb = template::getThumbSrc('wid', $i);
				}
				else
				{
					$thumb = template::getThumbSrc('img', $i);
				}

				return utils::tplProtect($thumb);

			// Dimensions HTML de la vignette.
			case 'thumb_size' :
				$tb = img::thumbSize('wid', $i['image_width'], $i['image_height']);
				return 'width="' . $tb['width'] . '" height="' . $tb['height'] . '"';

			// Titre de l'image.
			case 'title' :
				return utils::tplProtect(utils::getLocale($i['image_name']));

			// Largeur de la vignette.
			case 'width' :
				$width = (CONF_THUMBS_WID_METHOD == 'crop')
					? CONF_THUMBS_WID_WIDTH
					: CONF_THUMBS_WID_SIZE;
				return (int) $width;
		}
	}

	/**
	 * Retourne l'élément de console utilisateur $item.
	 *
	 * @param string $item
	 * @return string
	 */
	public function getWidgetUser($item)
	{
		switch ($item)
		{
			// Titre du widget.
			case 'widget_title' :
				return utils::tplProtect(utils::getLocale(
					utils::$config['widgets_params']['user']['title']
				));
		}
	}

	/**
	 * L'un des widgets $widgets doit-il être affiché ?
	 *
	 * @param string $widgets
	 *	Liste de widgets séparés par une virgule.
	 * @return boolean
	 */
	public function disWidgets($widgets)
	{
		$widgets_get = explode(',', $widgets);
		for ($i = 0, $count = count($widgets_get); $i < $count; $i++)
		{
			if ($this->_disWidgets($widgets_get[$i]))
			{
				return TRUE;
			}
		}
		return FALSE;
	}

	/**
	 * Charge les widgets $widgets.
	 *
	 * @param string $widgets
	 *	Liste de widgets séparés par une virgule.
	 * @return void
	 */
	public function getWidgets($widgets)
	{
		$widgets_order = utils::$config['widgets_order'];
		$widgets_get = explode(',', $widgets);
		for ($i = 0, $count = count($widgets_order); $i < $count; $i++)
		{
			// Widgets prédéfinis.
			if (in_array($widgets_order[$i], $widgets_get))
			{
				if (!$this->_disWidgets($widgets_order[$i]))
				{
					continue;
				}
				$this->inc($this->getGallery('gallery_abs_path') . '/template/'
					. utils::filters($this->getGallery('template_name'), 'dir')
					. '/widget_' . $widgets_order[$i] . '.tpl.php');
			}

			// Widgets utilisateurs.
			else if (substr($widgets_order[$i], 0, 6) == 'perso_'
			&& in_array('default', $widgets_get))
			{
				if (!utils::$config['widgets_params'][$widgets_order[$i]]['status'])
				{
					continue;
				}
				$this->_userWidget = substr($widgets_order[$i], 6, strlen($widgets_order[$i]));
				$this->inc($this->getGallery('gallery_abs_path') . '/template/'
					. utils::filters($this->getGallery('template_name'), 'dir')
					. '/widget_default.tpl.php');
			}
		}
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
				$inc = $tpl->getGallery('gallery_abs_path') . '/template/'
					. $tpl->getGallery('template_name') . '/'
					. $tpl->getGallery('page_filename') . '.tpl.php';
				break;

			case 'style_header' :
				$inc = $tpl->getGallery('gallery_abs_path') . '/template/'
					. $tpl->getGallery('template_name') . '/style/'
					. $tpl->getGallery('style_name') . '/head.php';
				break;
		}
		if (is_file($inc))
		{
			include $inc;
		}
	}

	/**
	 * Retourne l'élément $item du widget utilisateur.
	 *
	 * @param string $item
	 * @return string
	 */
	public function getDefaultWidget($item)
	{
		$i = utils::$config['widgets_params']['perso_' . $this->_userWidget];

		switch ($item)
		{
			case 'content' :
				if ($i['type'] == 'file'
				&& preg_match('`^[-a-z0-9_]{1,64}\.php$`', $i['file']))
				{
					$this->inc(
						$this->getGallery('gallery_abs_path')
						. '/files/widgets/' . $i['file']
					);
					return;
				}
				return nl2br(utils::tplHTMLFilter(utils::getLocale($i['text'])));

			case 'title' :
				return utils::tplProtect(utils::getLocale($i['title']));
		}
	}

	/**
	 * L'option $item doit-elle être affichée ?
	 *
	 * @param string $item
	 * @return boolean
	 */
	public function disOptions($item)
	{
		switch ($item)
		{
			// Options spécifiques aux albums.
			case 'nb_thumbs' :
			case 'order_by' :
			case 'thumbs_date' :
			case 'thumbs_image_title' :
			case 'thumbs_size' :
				return utils::$config['widgets_params']['options']['items'][$item]
					&& album::$thumbs !== NULL;

			// Options spécifiques aux catégories.
			case 'thumbs_albums' :
			case 'thumbs_category_title' :
			case 'thumbs_images' :
				return utils::$config['widgets_params']['options']['items'][$item]
					&& category::$thumbs !== NULL;

			// Options spécifiques aux albums et aux catégories.
			case 'thumbs_comments' :
			case 'thumbs_votes' :
				return utils::$config['widgets_params']['options']['items'][$item]
					&& utils::$config[substr($item, 7)]
					&& (album::$thumbs !== NULL
					|| category::$thumbs !== NULL);

			case 'thumbs_filesize' :
			case 'thumbs_hits' :
				return utils::$config['widgets_params']['options']['items'][$item]
					&& (album::$thumbs !== NULL
					|| category::$thumbs !== NULL);

			// Options spécifiques aux images.
			case 'image_size' :
				return utils::$config['widgets_params']['options']['items'][$item]
					&& image::$infos !== NULL;

			// Options spécifiques aux albums, catégories et au plan.
			case 'thumbs_recent' :
				return utils::$config['widgets_params']['options']['items']['recent']
					&& (album::$thumbs !== NULL
					|| category::$thumbs !== NULL
					|| $_GET['section'] == 'sitemap');

			// Options disponibles pour toute la galerie.
			case 'styles' :
				return (bool) utils::$config['widgets_params']['options']['items'][$item];

			case 'thumbs_infos' :
				return $this->disOptions('thumbs_albums')
					|| $this->disOptions('thumbs_category_title')
					|| $this->disOptions('thumbs_comments')
					|| $this->disOptions('thumbs_date')
					|| $this->disOptions('thumbs_filesize')
					|| $this->disOptions('thumbs_hits')
					|| $this->disOptions('thumbs_image_title')
					|| $this->disOptions('thumbs_images')
					|| $this->disOptions('thumbs_votes')
					|| $this->disOptions('thumbs_recent')
					|| $this->disOptions('thumbs_size');

			// Titre.
			case 'title' :
				return utils::getLocale(
					utils::$config['widgets_params']['options']['title']
				) != '';
		}
	}

	/**
	 * Retourne l'option $item.
	 *
	 * @param string $item
	 * @return string
	 */
	public function getOptions($item)
	{
		switch ($item)
		{
			// Dimensions de l'image : taille définie par l'utilisateur.
			case 'fixed_size' :
				return (utils::$config['images_resize'] == 1)
					? ' checked="checked"'
					: '';

			// Hauteur maximum de l'image.
			case 'image_height' :
				return (int) utils::$config['images_resize_html_height'];

			// Largeur maximum de l'image.
			case 'image_width' :
				return (int) utils::$config['images_resize_html_width'];

			// Tri des images.
			case 'images_order_by' :
				$order_by_conf = preg_split('`[\s,]`',
					utils::$config['sql_images_order_by'], 2, PREG_SPLIT_NO_EMPTY);
				$order_by_conf = (strstr($order_by_conf[0], '*'))
					? 'image_size'
					: $order_by_conf[0];
				$order_by = array();
				$order_by['position'] = __('*par défaut');
				$order_by['name'] = __('Titre');
				$order_by['path'] = __('Nom de fichier');
				$order_by['filesize'] = __('Poids');
				$order_by['size'] = __('Dimensions');
				$order_by['adddt'] = __('Date d\'ajout');
				$order_by['crtdt'] = __('Date de création');
				$order_by['hits'] = __('Nombre de visites');
				$order_by['comments'] = __('Nombre de commentaires');
				$order_by['votes'] = __('Nombre de votes');
				$order_by['rate'] = __('Note moyenne');
				$options = '';
				foreach ($order_by as $value => &$l10n)
				{
					$selected = ('image_' . $value == $order_by_conf)
						? ' selected="selected" class="selected"'
						: '';
					$options .= '<option' . $selected
						. ' value="' . $value . '">' . $l10n . '</option>';
				}
				return $options;

			// Sens du tri des images : croissant ou décroissant.
			case 'images_asc_desc' :
				$asc_desc_conf = preg_split('`[\s,]`',
					utils::$config['sql_images_order_by'], 3, PREG_SPLIT_NO_EMPTY);
				$asc_desc_conf = $asc_desc_conf[1];
				$asc_desc = array();
				$asc_desc['ASC'] = __('croissant');
				$asc_desc['DESC'] = __('décroissant');
				$options = '';
				foreach ($asc_desc as $value => &$l10n)
				{
					$selected = ($value == $asc_desc_conf)
						? ' selected="selected" class="selected"'
						: '';
					$options .= '<option' . $selected
						. ' value="' . $value . '">' . $l10n . '</option>';
				}
				return $options;

			// Nombre de vignettes par page.
			case 'thumbs_alb_nb' :
				return (int) utils::$config[$item];

			// Dimensions de l'image : taille originale.
			case 'original_size' :
				return (utils::$config['images_resize'] == 0)
					? ' checked="checked"'
					: '';

			// Durée de nouveauté pour considérer une image comme récente.
			case 'recent_days' :
				return utils::tplProtect(utils::$config['recent_images_time']);

			// Styles disponibles.
			case 'styles' :
				$styles = '';
				if (utils::$config['widgets_params']['options']['items']['styles'] == 1)
				{
					for ($i = 0, $count = count(gallery::$styles); $i < $count; $i++)
					{
						$selected = (gallery::$styles[$i] == utils::$config['theme_style'])
							? ' selected="selected" class="selected"'
							: '';
						$styles .= '<option' . $selected
							. ' value="' . gallery::$styles[$i] . '">'
							. str_replace('_', ' ', gallery::$styles[$i])
							. '</option>';
					}
				}
				return $styles;

			// Informations de chaque vignettes.
			case 'recent_images' :
			case 'thumbs_stats_albums' :
			case 'thumbs_stats_category_title' :
			case 'thumbs_stats_comments' :
			case 'thumbs_stats_date' :
			case 'thumbs_stats_filesize' :
			case 'thumbs_stats_hits' :
			case 'thumbs_stats_image_title' :
			case 'thumbs_stats_images' :
			case 'thumbs_stats_size' :
			case 'thumbs_stats_votes' :
				return (utils::$config[$item])
					? ' checked="checked"'
					: '';

			// Titre.
			case 'title' :
				return utils::tplProtect(utils::getLocale(
					utils::$config['widgets_params']['options']['title']
				));
		}
	}

	/**
	 * L'information $item des statistiques doit-elle être affichée ?
	 *
	 * @param string $item
	 * @return boolean
	 */
	public function disStats($item = '')
	{
		$active = TRUE;

		switch ($item)
		{
			// Nombre d'albums.
			case 'albums' :
				$active = $_GET['section'] != 'album';
				break;

			// Nombre de commentaires.
			case 'comments' :
				$active = utils::$config['comments'];
				break;

			// Nombre d'images récentes.
			case 'recents' :
				$active = utils::$config['recent_images'];
				break;

			// Titre.
			case 'title' :
				return utils::getLocale(
					utils::$config['widgets_params']['stats_categories']['title']
				) != '';

			// Nombre de votes.
			case 'votes' :
				$active = utils::$config['votes'];
				break;

			// Au moins l'une des stats.
			case '' :
				foreach (utils::$config['widgets_params']['stats_categories']['items']
				as $name => &$status)
				{
					if ($this->disStats($name))
					{
						return TRUE;
					}
				}
				return FALSE;
		}

		return $active
			&& utils::$config['widgets_params']['stats_categories']['items'][$item];
	}

	/**
	 * Retourne l'information des statistiques $item.
	 *
	 * @param string $item
	 * @return string|integer
	 */
	public function getStats($item)
	{
		$i = category::$infos;

		switch ($item)
		{
			// Nombre d'albums.
			case 'albums' :
				$albums = (int) $i['cat_a_albums'];
				return ($albums > 1)
					? sprintf(__('%s albums'), $albums)
					: sprintf(__('%s album'), $albums);

			// Nombre de commentaires.
			case 'comments' :
				$comments = (int) $i['cat_a_comments'];
				$comments = ($comments > 1)
					? sprintf(__('%s commentaires'), $comments)
					: sprintf(__('%s commentaire'), $comments);
				if ($comments > 0)
				{
					$link = utils::genURL('comments-stats/' . category::$purlId);
					$comments = '<a href="' . $link . '">' . $comments . '</a>';
				}
				return $comments;

			// Poids de la catégorie.
			case 'filesize' :
				return utils::filesize($i['cat_a_size']);

			// Nombre de visites.
			case 'hits' :
				$hits = (int) $i['cat_a_hits'];
				$hits = ($hits > 1)
					? sprintf(__('%s visites'), $hits)
					: sprintf(__('%s visite'), $hits);
				if ($hits > 0)
				{
					$link = utils::genURL('hits/' . category::$purlId);
					$hits = '<a href="' . $link . '">' . $hits . '</a>';
				}
				return $hits;

			// Nombre d'images.
			case 'images' :
				$images = (int) $i['cat_a_images'];
				$images = ($images > 1)
					? sprintf(__('%s images'), $images)
					: sprintf(__('%s image'), $images);
				if ($images > 0 && $i['cat_filemtime'] === NULL)
				{
					$link = utils::genURL('images/' . category::$purlId);
					$images = '<a href="' . $link . '">' . $images . '</a>';
				}
				return $images;

			// Nombre d'images récentes.
			case 'recents' :
				$recent_images = (int) $i['cat_recent_images'];
				$recent_images = ($recent_images > 1)
					? sprintf(__('%s nouvelles images'), $recent_images)
					: sprintf(__('%s nouvelle image'), $recent_images);
				if ($i['cat_recent_images'] > 0)
				{
					$link = utils::genURL('recent-images/' . category::$purlId);
					$recent_images = '<a href="' . $link . '">' . $recent_images . '</a>';
				}
				return $recent_images;

			// Titre.
			case 'title' :
				return utils::tplProtect(utils::getLocale(
					utils::$config['widgets_params']['stats_categories']['title']
				));

			// Nombre de votes.
			case 'votes' :
				$votes = (int) $i['cat_a_votes'];
				$votes = ($votes > 1)
					? sprintf(__('%s votes'), $votes)
					: sprintf(__('%s vote'), $votes);
				if ($i['cat_a_votes'] > 0)
				{
					$link = utils::genURL('votes/' . category::$purlId);
					$votes = '<a href="' . $link . '">' . $votes . '</a>';
				}
				return $votes;
		}
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
				return !empty(gallery::$report[$item]);

			case 'success' :

				// Succès total.
				if ((empty(gallery::$report['error'])
				|| empty(gallery::$report['warning']))
				&& isset(gallery::$report['success']))
				{
					return TRUE;
				}

				// Succès partiel.
				if ((!empty(gallery::$report['error'])
				|| !empty(gallery::$report['warning']))
				&& isset(gallery::$report['success_p']))
				{
					return TRUE;
				}

				return FALSE;

			default :
				return TRUE;
				return self::disReport('error')
					|| self::disReport('warning')
					|| self::disReport('success');
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
		$i =& gallery::$report[$item];
		$message = is_array($i) ? current($i) : $i;

		switch ($item)
		{
			// Erreur.
			case 'error' :
				return nl2br(utils::tplProtect(gallery::$report['error']));

			// Avertissements.
			case 'warning' :
				return nl2br(utils::tplProtect($message));

			// Succès.
			case 'success' :

				// Succès total.
				if ((empty(gallery::$report['error'])
				|| empty(gallery::$report['warning']))
				&& isset(gallery::$report['success']))
				{
					return nl2br(utils::tplProtect(gallery::$report['success']));
				}

				// Succès partiel.
				if ((!empty(gallery::$report['error'])
				|| !empty(gallery::$report['warning']))
				&& isset(gallery::$report['success_p']))
				{
					return nl2br(utils::tplProtect(gallery::$report['success_p']));
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
			case 'warning' :
				return template::nextObject(gallery::$report[$item], $next);
		}
	}


	/**
	 * Construction du plan.
	 *
	 * @param array $m
	 * @return void
	 */
	private function _constructMap(&$m)
	{
		if (!is_array($m))
		{
			return;
		}
		foreach ($m as $id => &$v)
		{
			if (is_array($v))
			{
				if (isset($this->_map[$id]))
				{
					$v = $this->_map[$id];
					unset($this->_map[$id]);
				}
				$this->_constructMap($v);
			}
		}
	}

	/**
	 * Le widget $widget doit-il être chargé ?
	 *
	 * @param string $widget
	 * @return boolean
	 */
	private function _disWidgets($widget)
	{
		switch ($widget)
		{
			// Widgets personnalisés.
			case 'default' :
				for ($i = 1; $i < 10; $i++)
				{
					if (isset(utils::$config['widgets_params']['perso_' . $i])
					&& utils::$config['widgets_params']['perso_' . $i]['status'])
					{
						return TRUE;
					}
				}
				return FALSE;

			case 'image' :
				return count(gallery::$widgetImage) > 0
					&& utils::$config['widgets_params'][$widget]['status'];

			case 'navigation' :
			case 'user' :
				return (bool) utils::$config['widgets_params'][$widget]['status'];

			case 'geoloc' :
				return $this->disGallery('geoloc')
					&& utils::$config['widgets_params']['geoloc']['status']
					&& (($_GET['section'] == 'image'
						&& is_array(image::$infos)
						&& !empty(image::$infos['image_lat'])
						&& !empty(image::$infos['image_long']))
					 || (($_GET['section'] == 'category' || $_GET['section'] == 'album')
						&& is_array(category::$infos)
						&& !empty(category::$infos['cat_lat'])
						&& !empty(category::$infos['cat_long'])));

			case 'links' :
				return utils::$config['widgets_params']['links']['status']
					&& !empty(utils::$config['widgets_params']['links']['items']);

			case 'online_users' :
				return utils::$config['users']
					&& (bool) utils::$config['widgets_params'][$widget]['status'];

			case 'options' :
				return utils::$config['widgets_params'][$widget]['status']
					&& ($this->disOptions('nb_thumbs')
					 || $this->disOptions('order_by')
					 || $this->disOptions('thumbs_infos')
					 || $this->disOptions('image_size')
					 || $this->disOptions('thumbs_recent')
					 || $this->disOptions('styles'));

			case 'stats_categories' :
				return (($_GET['section'] == 'category' || $_GET['section'] == 'album')
					&& utils::$config['widgets_params'][$widget]['status']
					&& $this->disStats());

			case 'stats_images' :
				return ($_GET['section'] == 'image'
					&& utils::$config['widgets_params'][$widget]['status']
					&& $this->disImageStats());

			case 'tags' :
				return (bool) utils::$config['widgets_params'][$widget]['status']
					&& tags::$widgetTags !== NULL;
		}
	}

	/**
	 * Construit un plan sous forme de liste.
	 *
	 * @param integer $n
	 *	Niveau de profondeur.
	 * @param array $m
	 *	Portion de plan.
	 * @return string
	 */
	private function _mapList($n = 1, $m = array())
	{
		static $list;

		if (!is_array(map::$categories))
		{
			return;
		}

		if ($n == 1)
		{
			$this->_map = array();
			$list = '<ul>' . "\n";

			// Construction du plan.
			foreach (map::$categories as $id => &$infos)
			{
				if ($id != 1)
				{
					$this->_map[$infos['parent_id']][$id] = ($infos['cat_filemtime'] === NULL)
						? array()
						: NULL;
				}
			}
			$this->_constructMap($this->_map[1]);
			$m = &$this->_map[1];
		}

		$level = str_repeat("\t", $n);

		foreach ($m as $id => &$v)
		{
			if (!isset(map::$categories[$id]))
			{
				continue;
			}

			// On ignore les catégories qui ne sont pas activées.
			if (map::$categories[$id]['cat_status'] != '1')
			{
				continue;
			}

			$type = (map::$categories[$id]['cat_filemtime'] === NULL) ? 'category' : 'album';

			// Lien.
			$link = utils::genURL($type . '/' . $id . '-' . map::$categories[$id]['cat_url']);
			$link = '<a href="' . $link . '">' . utils::tplProtect(
				utils::getLocale(map::$categories[$id]['cat_name'])) . '</a>';

			$cat_infos = '';

			// Images.
			if (utils::$config['map_nb_images'])
			{
				// Message.
				$images = (map::$categories[$id]['cat_a_images'] > 1)
					? __('%s images')
					: __('%s image');
				$images = sprintf($images, (int) map::$categories[$id]['cat_a_images']);

				// Lien.
				$images_link = utils::genURL('images/' . $id
					. '-' . map::$categories[$id]['cat_url']);
				$images = '<a href="' . $images_link . '">' . $images . '</a>';

				$cat_infos .= ' <span class="nb_images">- ' . $images . '</span>';
			}

			// Date du dernier ajout.
			if (utils::$config['map_last_update'])
			{
				// Message.
				$format = utils::tplProtect(__('%d/%m/%Y'));
				$cat_lastadddt = utils::tplProtect(map::$categories[$id]['cat_lastadddt']);
				$lastadddt = __('mis à jour le %s');
				$lastadddt = sprintf($lastadddt, utils::localeTime($format, $cat_lastadddt));

				// Lien.
				$date = date('Y-m-d', strtotime($cat_lastadddt));
				$lastadddt_link = utils::genURL('date-added/' . $date . '/' . $type
					. '/' . $id . '-' . map::$categories[$id]['cat_url']);
				$lastadddt = '<a href="' . $lastadddt_link . '">' . $lastadddt . '</a>';

				$cat_infos .= ' <span class="lastadddt">- ' . $lastadddt . '</span>';
			}

			// La catégorie contient-elle des images récentes ?
			$class = (utils::$config['recent_images']
				&& strtotime(map::$categories[$id]['cat_lastadddt'])
				> gallery::$recentImagesLimit)
				? ' recent'
				: '';

			// La catégorie est-elle protégée ?
			$class = (!utils::$config['users'] && map::$categories[$id]['cat_password'])
				? ' protect'
				: $class;

			if (is_array($v))
			{
				$list .= $level . '<li class="cat' . $class . '">';
				$list .= '<span class="cat_link">' . $link . $cat_infos . '</span>' . "\n";
				$list .= $level . '<ul>' . "\n";
				$this->_mapList($n + 1, $v);
				$list .= $level . '</ul>' . "\n";
				$list .= $level . '</li>' . "\n";
			}
			else
			{
				$list .= $level . '<li class="alb' . $class . '">'
					. $link . $cat_infos . '</li>' . "\n";
			}
		}

		if ($n == 1)
		{
			return $list . '</ul>' . "\n";
		}
	}



	/**
	 * L'élément d'ajout de commentaire $item doit-il être affiché ?
	 *
	 * @param string $item
	 * @return boolean
	 */
	protected function _disAddComment($item = '')
	{
		switch ($item)
		{
			case 'captcha' :
				return utils::$config['recaptcha_comments']
					&& ((utils::$config['users'] && !users::$auth
							&& utils::$config['recaptcha_comments_guest_only'])
						|| (utils::$config['users'] && users::$auth
							&& !utils::$config['recaptcha_comments_guest_only'])
						|| !utils::$config['users']);

			case 'required_email' :
			case 'required_website' :
			case 'smilies' :
				return (bool) utils::$config['comments_' . $item];
		}
	}

	/**
	 * Retourne l'élément d'ajout de commentaire $item.
	 *
	 * @return string
	 */
	protected function _getAddComment($item)
	{
		switch ($item)
		{
			case 'author' :
			case 'email' :
			case 'website' :
				if (isset($_POST[$item]))
				{
					if (isset($_POST['preview']) || isset($_POST['remember']))
					{
						return utils::tplProtect($_POST[$item]);
					}
				}
				else if (utils::$cookiePrefs->read('com_' . $item))
				{
					return utils::tplProtect(utils::$cookiePrefs->read('com_' . $item));
				}
				return '';

			// Captcha.
			case 'captcha' :
				return recaptcha_get_html(utils::$config['recaptcha_public_key']);

			// Smilies.
			case 'smilies' :
				$smilies = '';
				$path = $this->getGallery('gallery_path') . '/images/smilies/'
					. utils::$config['comments_smilies_icons_pack'] . '/';
				$icons = array();
				foreach (comments::$smilies as $code => $file)
				{
					if (in_array($file, $icons))
					{
						continue;
					}
					$smilies .= '<img src="' . $path . utils::tplProtect($file)
						. '" title="' . utils::tplProtect($code)
						. '" alt="' . utils::tplProtect($code) . '" />';
					$icons[] = $file;
				}
				unset($icons);
				return $smilies;

			// Commentaire qui vient d'être posté,
			// en cas de refus, d'erreur ou de prévisualisation.
			case 'message' :
				return (isset($_POST['message']))
					? utils::tplProtect($_POST['message'])
					: '';
		}
	}

	/**
	 * L'élément de commentaire $item doit-il être affiché ?
	 *
	 * @param string $item
	 * @return boolean
	 */
	protected function _disComment($i, $item, $pref)
	{
		switch ($item)
		{
			// Admin ?
			case 'admin' :
				return $i['group_admin'] == 1;

			// Invité ?
			case 'guest' :
				return $i['user_id'] == 2;

			// Superadmin ?
			case 'superadmin' :
				return $i['user_id'] == 1;

			// Site Web de l'utilisateur qui a posté le commentaire.
			case 'website' :
				return (bool) $i[$pref . '_website'];
		}
	}

	/**
	 * Retourne l'élément de commentaire $item.
	 *
	 * @param array $i
	 * @param string $item
	 * @param string $pref
	 * @return integer|string
	 */
	protected function _getComment($i, $item, $pref)
	{
		switch ($item)
		{
			// Auteur du commentaire.
			case 'author' :
				return utils::tplProtect($i['author']);

			// Auteur du commentaire + site Web.
			case 'author_and_website' :
				$author = '';
				if ($this->_disComment($i, 'website', $pref))
				{
					$author = ' <span class="website">(<a href="'
						. $this->_getComment($i, 'website', $pref) . '">'
						. __('site') . '</a>)</span>';
				}
				return $this->_getComment($i, 'author', $pref) . $author;

			// Avatar de l'utilisateur.
			case 'avatar' :
				return ($i['avatar'])
					? $this->getGallery('avatars_path') . '/user'
						. (int) $i['user_id'] . '_thumb.jpg'
					: $this->getGallery('style_path') . '/avatar-default.png';

			// Date du commentaire.
			case 'date' :
				return utils::localeTime(__('%A %d %B %Y'), $i[$pref . '_crtdt']);

			// Identifiant du commentaire.
			case 'id' :
				return (int) $i[$pref . '_id'];

			// Message.
			case 'message' :
				$smilies = (utils::$config['comments_smilies'])
					? comments::$smilies
					: FALSE;
				return template::formatComment(
					$i[$pref . '_message'],
					$smilies,
					utils::$config['comments_smilies_icons_pack']
				);

			// Numéro du commentaire.
			case 'num' :
				return $this->_commentNum;

			// Heure du commentaire.
			case 'time' :
				return utils::localeTime('%H:%M:%S', $i[$pref . '_crtdt']);

			// Date brute du commentaire, au format SQL DATETIME.
			case 'timestamp' :
				return utils::tplProtect($i[$pref . '_crtdt']);

			// Identifiant de l'utilisateur qui a posté le commentaire.
			case 'user_id' :
				return (int) $i['user_id'];

			// Site Web de l'utilisateur qui a posté le commentaire.
			case 'website' :
				return utils::tplProtect($i[$pref . '_website']);
		}
	}

	/**
	 * Retourne la date localisée dans le format correspondant.
	 *
	 * @return string
	 */
	protected function _getDateLocale()
	{
		switch (strlen($_GET['date']))
		{
			case 10 :
				$format = __('%A %d %B %Y');
				break;

			case 7 :
				$format = '%B %Y';
				break;

			case 4 :
				return $_GET['date'];
				break;
		}
		return utils::localeTime($format, $_GET['date']);
	}

	/**
	 * Retourne l'élément $item du diaporama.
	 *
	 * @param string $item
	 * @return string
	 */
	protected function _getDiaporama($item)
	{
		switch ($item)
		{
			case 'date_select' :
				return template::dateSelect(NULL, 1900);

			// Préférences.
			case 'prefs' :
				$cookie = utils::$cookiePrefs->read('diaporama');
				$prefs = '{"autoStart": '
					. (utils::$config['diaporama_auto_start'] ? 'true' : 'false');

				// Préférences utilisateur.
				$regexp = '`^(false|true),(\d{1,2}(?:\.\d{1,2})?),'
					. '(false|true),(false|true),(false|true),(\d{1,4}),'
					. '(curtainX|curtainY|fade|none|puff|random|'
					. 'slideX|slideXLeft|slideY|slideYBottom|zoom)$`';
				if (preg_match($regexp, $cookie, $m))
				{
					$prefs .= ', "animate":' . $m[1]
						. ', "autoDuration":' . $m[2]
						. ', "autoLoop":' . $m[3]
						. ', "carousel":' . $m[4]
						. ', "hideControlBars":' . $m[5]
						. ', "transitionDuration":' . $m[6]
						. ', "transitionEffect":"' . $m[7] . '"';
				}

				// Valeurs par défaut.
				else
				{
					$prefs .= ', "carousel":'
						. (utils::$config['diaporama_carousel'] ? 'true' : 'false');
				}

				return $prefs . '}';

			// Paramètres pour la requête Ajax.
			case 'query' :
				$q = $_GET['q'];

				// On supprime l'identifiant de l'image et le numéro de page, si présents.
				$q = preg_replace('`/?(image|page)/\d{1,11}(?:-[^/]+)?`', '', $q);

				// On ajoute l'identifiant de l'album, si non présent,
				// sauf pour la recherche.
				if ($_GET['section'] == 'image'
				&& !isset($_GET['section_b']) && !isset($_GET['search']))
				{
					$q .= '/album/' . image::$infos['cat_id'] . '-' . image::$infos['cat_url'];
				}

				// On ajoute la position de l'image depuis laquelle démarrera le diaporama.
				$position = ($_GET['section'] == 'image')
					? (int) image::$currentImage
					: (($_GET['page'] - 1) * (int) utils::$config['thumbs_alb_nb']) + 1;
				$q .= '/position/' . $position;

				// On supprime le premier slash.
				$q = preg_replace('`^/`', '', $q);

				return utils::tplProtect($q);
		}
	}

	/**
	 * Détermine si l'utilisateur a la permission d'accès
	 * pour l'administration de la catégorie courante.
	 *
	 * @return boolean
	 */
	protected function _isAdminCategoryAccess()
	{
		$cat_password = (image::$infos)
			? image::$infos['cat_password']
			: category::$infos['cat_password'];
		if ($cat_password)
		{
			$cat_password = explode(':', $cat_password);

			if (users::$perms['admin']['perms']['access_mode'] == 1
			|| (users::$perms['admin']['perms']['access_mode'] == 2
				&& isset(users::$perms['admin']['albums_access'][$cat_password[0]])))
			{
				return TRUE;
			}
		}
		else
		{
			return TRUE;
		}

		return FALSE;
	}

	/**
	 * Retourne l'élément de tag $item.
	 *
	 * @param string $item
	 * @return string|integer
	 */
	protected function _getTag($item, &$tags)
	{
		$i = current($tags);

		switch ($item)
		{
			// Identifiant.
			case 'id' :
				return (int) $i['tag_id'];

			// Lien vers la page des images liées au tag.
			case 'link' :
				$cat_url = '';
				if ($_GET['object_id'] > 1)
				{
					$cat_url = '/' . category::$infos['cat_type'] . '/' . category::$purlId;
				}
				return utils::genURL('tag/' . $i['tag_id'] . '-' . $i['tag_url'] . $cat_url);

			// Nom du tag.
			case 'name' :
				return utils::tplProtect($i['tag_name']);

			// Nombre d'images liées au tag.
			case 'nb_images' :
				return (int) $i['tag_nb_images'];

			// Titre du lien sur le tag = nombre d'images.
			case 'title' :
				$message = ($i['tag_nb_images'] > 1)
					? __('%s images')
					: __('%s image');
				return sprintf($message, (int) $i['tag_nb_images']);

			// Nom d'URL du tag.
			case 'urlname' :
				return utils::tplProtect($i['tag_url']);

			// Poids du tag.
			case 'weight' :
				return (int) $i['tag_weight'];
		}
	}

	/**
	 * Y a-t-il une prochaine tag ?
	 *
	 * @return boolean
	 */
	protected function _nextTag(&$tags)
	{
		static $next = -1;

		return template::nextObject($tags, $next);
	}
}

/**
 * Méthodes de template communes
 * à la page des albums et à la page des catégories.
 */
class tplAlbums extends tplGallery
{
	/**
	 * Doit-on afficher le lien d'administration ?
	 *
	 * @return boolean
	 */
	public function disAdminLink()
	{
		return users::$auth
			&& ($_GET['section'] == 'basket'
				|| $_GET['section'] == 'user-favorites'
				|| ($this->disCategory() && $this->_isAdminCategoryAccess()))
			&& (users::$perms['admin']['perms']['all'] ||
				users::$perms['admin']['perms']['albums_edit'] ||
				users::$perms['admin']['perms']['albums_modif']);
	}

	/**
	 * Le lien de déconnexion doit-il être affiché ?
	 *
	 * @return boolean
	 */
	public function disDeconnect()
	{
		return (utils::$config['users'] == 1
			&& utils::$config['users_locked_albums_access_mode'] == 'groups') === FALSE
			&& category::$infos['cat_password'] !== NULL;
	}

	/**
	 * L'élément $item de la catégorie doit-il être affiché ?
	 *
	 * @return boolean
	 */
	public function disCategory($item = '')
	{
		switch ($item)
		{
			case 'desc' :
				return $this->disCategory()
					&& !(utils::$config['thumbs_cat_extended'] == 1 && $_GET['object_id'] > 1)
					&& $this->getCategory('desc') !== ''
					&& $_GET['page'] == 1;

			default :
				return $_GET['section'] == 'album' || $_GET['section'] == 'category';
		}
	}

	/**
	 * Retourne l'élément $item de la catégorie.
	 *
	 * @param string $item
	 * @return mixed
	 */
	public function getCategory($item)
	{
		switch ($item)
		{
			// Lien vers les modèles d'appareils photos de la catégorie.
			case 'cameras_link' :
				return utils::genURL('cameras/' . category::$purlId);

			// Description.
			case 'desc' :
				return template::desc('cat', category::$infos);

			case 'desc_lang' :
				return utils::tplProtect(
					utils::getLocale(category::$infos['cat_desc'], $this->getLang('code'))
				);

			// Lien vers l'historique de la catégorie.
			case 'history_link' :
				return utils::genURL('history/' . category::$purlId);

			// Latitude.
			case 'latitude' :
				return utils::tplProtect(category::$infos['cat_lat']);

			// Lien vers la catégorie.
			case 'link' :
				$type = (category::$infos['cat_filemtime'] === NULL) ? 'category' : 'album';
				return utils::genURL($type . '/' . category::$purlId);

			// Longitude.
			case 'longitude' :
				return utils::tplProtect(category::$infos['cat_long']);

			// Lieu.
			case 'place' :
				return (utils::getLocale(category::$infos['cat_place']) !== NULL)
					? utils::tplProtect(utils::getLocale(category::$infos['cat_place']))
					: '?';

			// Nombre de pages de la catégorie.
			case 'nb_pages' :
				return ($_GET['section'] == 'category')
					? (int) category::$nbPages
					: (int) album::$nbPages;

			// Titre.
			case 'title' :
				return utils::tplProtect(category::$infos['cat_name']);

			case 'title_lang' :
				return utils::tplProtect(category::$infos['cat_name'], $this->getLang('code'));

			// Nom d'URL.
			case 'urlname' :
				return utils::tplProtect(category::$infos['cat_url']);
		}
	}

	/**
	 * L'outil d'édition doit-il être affiché ?
	 *
	 * @param string $forced
	 * @return boolean
	 */
	public function disEdit($forced = FALSE)
	{
		return $this->disAuthUser()
			&& users::$perms['gallery']['perms']['edit']
			&& ($this->disCategory() || $forced)
			&& (!users::$perms['gallery']['perms']['edit_owner']
				|| (users::$perms['gallery']['perms']['edit_owner']
				&& users::$infos['user_id'] == category::$infos['user_id']));
	}

	/**
	 * L'outil d'édition des tags doit-il être affiché ?
	 *
	 * @return boolean
	 */
	public function disTagsEdit()
	{
		return $this->disGallery('tags')
			&& !users::$perms['gallery']['perms']['edit_owner']
			&& $this->disEdit(TRUE);
	}
}

/**
 * Méthodes de template pour la page des albums.
 */
class tplAlbum extends tplAlbums
{
	/**
	 * Retourne le lien d'administration.
	 *
	 * @return string
	 */
	public function getAdminLink()
	{
		switch ($_GET['section'])
		{
			case 'basket' :
				$link = 'category/1/user-basket/' . users::$infos['user_id'];
				break;

			case 'user-favorites' :
				$link = 'category/1/user-favorites/' . users::$infos['user_id'];
				break;

			default :
				$link = 'album/' . category::$infos['cat_id'];
				break;
		}

		return CONF_GALLERY_PATH . '/' . CONF_ADMIN_DIR . '/?q='
			. utils::genURL($link, TRUE);
	}

	/**
	 * Retourne l'élément $item du diaporama.
	 *
	 * @param string $item
	 * @return string
	 */
	public function getDiaporama($item)
	{
		return $this->_getDiaporama($item);
	}

	/**
	 * L'élément de navigation entre les pages $item doit-il être affiché ?
	 *
	 * @param string $item
	 * @return boolean
	 */
	public function disNavigation($item = '')
	{
		return template::disNavigation($item, album::$nbPages);
	}

	/**
	 * Retourne l'élément de navigation entre les pages $item.
	 *
	 * @param string $item
	 * @return string
	 */
	public function getNavigation($item)
	{
		return template::getNavigation($item, album::$nbPages, gallery::$sectionRequest);
	}

	/**
	 * Retourne l'élément de la barre de position (fil d'ariane) $item.
	 *
	 * @param string $item
	 * @return string
	 */
	public function getPosition($item = '')
	{
		// Position pour les sections spéciales.
		if ($_GET['section'] != 'album')
		{
			return $this->_getSpecialesPosition($item);
		}

		return template::getPosition(
			$item,
			'category',
			'album',
			'',
			FALSE,
			TRUE,
			__('Accueil'),
			gallery::$parents,
			category::$infos,
			category::$parentPage,
			utils::$config['level_separator'],
			$_GET['object_id'] > 1
		);
	}

	/**
	 * Doit-on afficher l'élément de recherche $item ?
	 *
	 * @param string $item
	 * @return boolean
	 */
	public function disSearchResult($item)
	{
		if (!isset($_GET['search']) || $_GET['page'] > 1)
		{
			return FALSE;
		}

		switch ($item)
		{
			case 'albums' :
				return !empty(album::$searchAlbums);

			case 'categories' :
				return !empty(album::$searchCategories);

			case 'images' :
				return album::$nbImages > 0;
		}
	}

	/**
	 * Retourne l'élément de recherche $item.
	 *
	 * @param string $item
	 * @return string
	 */
	public function getSearchResult($item)
	{
		switch ($item)
		{
			// Indique le nombre d'albums trouvés.
			case 'nb_albums' :
				$message = count(album::$searchAlbums) > 1
					? __('%s albums trouvés')
					: __('%s album trouvé');
				return sprintf($message, count(album::$searchAlbums));

			// Indique le nombre de catégories trouvées.
			case 'nb_categories' :
				$message = count(album::$searchCategories) > 1
					? __('%s catégories trouvées')
					: __('%s catégorie trouvée');
				return sprintf($message, count(album::$searchCategories));

			// Indique le nombre d'images trouvées.
			case 'nb_images' :
				$message = album::$nbImages > 1
					? __('%s images trouvées')
					: __('%s image trouvée');
				return sprintf($message, album::$nbImages);
		}
	}

	/**
	 * Retourne l'élément de catégorie trouvée $item.
	 *
	 * @param string $item
	 * @return string
	 */
	public function getSearchResultAlbum($item)
	{
		$i = current(album::$searchAlbums);

		return self::_getSearchResultCategory($item, $i);
	}

	/**
	 * Y a-t-il une prochaine catégorie trouvée par la recherche ?
	 *
	 * @return boolean
	 */
	public function nextSearchResultAlbum()
	{
		static $next = -1;

		return template::nextObject(album::$searchAlbums, $next);
	}

	/**
	 * Retourne l'élément de catégorie trouvée $item.
	 *
	 * @param string $item
	 * @return string
	 */
	public function getSearchResultCategory($item)
	{
		$i = current(album::$searchCategories);

		return self::_getSearchResultCategory($item, $i);
	}

	/**
	 * Y a-t-il une prochaine catégorie trouvée par la recherche ?
	 *
	 * @return boolean
	 */
	public function nextSearchResultCategory()
	{
		static $next = -1;

		return template::nextObject(album::$searchCategories, $next);
	}

	/**
	 * L'élément de vignette $item doit-il être affiché ?
	 *
	 * @param string $item
	 * @return boolean
	 */
	public function disThumb($item)
	{
		if (!is_array(album::$thumbs))
		{
			return FALSE;
		}

		$i = current(album::$thumbs);

		switch ($item)
		{
			// Nombre de commentaires.
			case 'comments' :
				return ($_GET['section'] == 'comments-stats')
					? TRUE
					: utils::$config['comments']
						&& (bool) utils::$config['thumbs_stats_comments'];

			// Page spéciale : nombre de commentaires.
			case 'comments_special' :
				return $_GET['section'] == 'comments-stats';

			// Date d'ajout.
			case 'added_date' :
				return ($_GET['section'] == 'recent-images')
					? TRUE
					: (bool) utils::$config['thumbs_stats_date'];

			// Page spéciale : date d'ajout.
			case 'added_date_special' :
				return $_GET['section'] == 'recent-images';

			// Poids.
			case 'filesize' :
				return (bool) utils::$config['thumbs_stats_filesize'];

			// Nombre de visites.
			case 'hits' :
				return ($_GET['section'] == 'hits')
					? TRUE
					: (bool) utils::$config['thumbs_stats_hits'];

			// Page spéciale : nombre de visites.
			case 'hits_special' :
				return $_GET['section'] == 'hits';

			// Dimensions.
			case 'image_size' :
				return (bool) utils::$config['thumbs_stats_size'];

			// L'image se trouve-t-elle dans les favoris ou le panier de l'utilisateur ?
			case 'in_basket' :
			case 'in_favorites' :
				return (bool) $i[$item];

			// Informations.
			case 'infos' :
				return (bool) album::$thumbsInfos;

			// Image récente.
			case 'recent' :
				return utils::$config['recent_images']
					&& strtotime($i['image_adddt']) > gallery::$recentImagesLimit;

			// Note moyenne.
			case 'rate' :
				return ($_GET['section'] == 'votes')
					? TRUE
					: utils::$config['votes']
						&& (bool) utils::$config['thumbs_stats_votes'];

			// Page spéciale : note moyenne.
			case 'rate_special' :
				return $_GET['section'] == 'votes';

			// Titre.
			case 'title' :
				return (bool) utils::$config['thumbs_stats_image_title'];
		}
	}

	/**
	 * La page courante contient-elle des vignettes ?
	 *
	 * @return boolean
	 */
	public function disThumbs()
	{
		return (bool) count(album::$thumbs);
	}

	/**
	 * Retourne l'élément de vignette $item.
	 *
	 * @param string $item
	 * @return string|integer
	 */
	public function getThumb($item)
	{
		$i = current(album::$thumbs);

		// Détermine la position de l'mage dans la section courante.
		static $position = -1;
		static $position_id = 0;
		if ($position == -1)
		{
			$position = ($_GET['page'] - 1) * album::$nbThumbs;
		}
		if ($i['image_id'] != $position_id)
		{
			$position_id = $i['image_id'];
			$position++;
		}

		switch ($item)
		{
			// Date d'ajout.
			case 'added_date' :
				return utils::localeTime(
					utils::tplProtect(__('%d/%m/%Y')),
					utils::tplProtect($i['image_adddt'])
				);

			// Centrage de la vignette par CSS.
			case 'center' :
				$tb = img::getThumbSize($i, 'img');
				return img::thumbCenter('img', $tb['width'], $tb['height']);

			// Nombre de commentaires.
			case 'comments' :
				$comments = ($i['image_comments'] > 1)
					? __('%s commentaires')
					: __('%s commentaire');
				return sprintf($comments, (int) $i['image_comments']);

			// Description.
			case 'desc' :
				return template::desc('image', $i);

			// Lien direct vers l'image.
			case 'direct_link' :
				return (CONF_URL_REWRITE)
					? utils::tplProtect(
						CONF_GALLERY_PATH . '/image/' . $i['cat_url'] . '/'
							. $i['image_id'] . '-' . strtolower(basename($i['image_path']))
					  )
					: utils::tplProtect(
						CONF_GALLERY_PATH . '/image.php?id=' . $i['image_id']
						. '&file=' . basename($i['image_path'])
					  );

			// Poids de l'image.
			case 'filesize' :
				return utils::filesize($i['image_filesize']);

			// Hauteur de la vignette.
			case 'height' :
				return (CONF_THUMBS_IMG_METHOD == 'crop')
					? (int) CONF_THUMBS_IMG_HEIGHT
					: (int) CONF_THUMBS_IMG_SIZE;

			// Nombre de visites.
			case 'hits' :
				$hits = ($i['image_hits'] > 1)
					? __('%s visites')
					: __('%s visite');
				return sprintf($hits, (int) $i['image_hits']);

			// Identifiant de l'image.
			case 'id' :
				return (int) $i['image_id'];

			// Chemin de l'image.
			case 'image_path' :
				return utils::tplProtect($i['image_path']);

			// Dimensions de l'image.
			case 'image_size' :
				return sprintf('%s x %s', (int) $i['image_width'], (int) $i['image_height']);

			// Lien vers l'image.
			case 'link' :
				if (utils::$config['images_direct_link'])
				{
					return $this->getThumb('direct_link');
				}
				else
				{
					$link = ($_GET['section'] == 'album')
						? ''
						: '/' . gallery::$sectionRequest;
					return utils::genURL(
						'image/' . $i['image_id'] . '-' . $i['image_url'] . $link
					);
				}

			// Position de l'image dans la section courante.
			case 'position' :
				return $position;

			// Note moyenne.
			case 'rate' :
				return sprintf(
					__('note moyenne : %s'),
					number_format((float) $i['image_rate'], 1, __(','), '')
				);

			case 'rate_visual' :
				return template::visualRate(
					$i['image_rate'],
					$this->getGallery('style_path'),
					'-small'
				);

			// Message pour les images récentes.
			case 'recent_title' :
				if (utils::$config['recent_images'])
				{
					if (strtotime($i['image_adddt']) > gallery::$recentImagesLimit)
					{
						$days = utils::$config['recent_images_time'];
						$message = ($days > 1)
							? __('Cette image a été ajoutée il y a moins de %s jours')
							: __('Cette image a été ajoutée il y a moins de %s jour');
						return sprintf($message, (int) $days);
					}
				}
				break;

			// Dimensions de la vignette.
			case 'size' :
				$tb = img::getThumbSize($i, 'img');
				return 'width="' . $tb['width'] . '" height="' . $tb['height'] . '"';

			// Emplacement de la vignette.
			case 'src' :
				return utils::tplProtect(template::getThumbSrc('img', $i));

			// Titre de l'image.
			case 'title' :
				return utils::tplProtect(utils::getLocale($i['image_name']));

			// Nombre de votes.
			case 'votes' :
				$comments = ($i['image_votes'] > 1)
					? __('%s votes')
					: __('%s vote');
				return sprintf($comments, (int) $i['image_votes']);

			// Largeur de la vignette.
			case 'width' :
				return (CONF_THUMBS_IMG_METHOD == 'crop')
					? (int) CONF_THUMBS_IMG_WIDTH
					: (int) CONF_THUMBS_IMG_SIZE;
		}
	}

	/**
	 * Retourne le nombre de lignes occupés par les informations de vignettes.
	 *
	 * @return integer
	 */
	public function getThumbLinesNumber()
	{
		$n = 0;

		foreach (array('comments', 'added_date', 'filesize', 'hits', 'image_size') as $i)
		{
			$n += ($this->disThumb($i)) ? 1 : 0;
		}

		foreach (array('title', 'rate') as $i)
		{
			$n += ($this->disThumb($i)) ? 2 : 0;
		}

		return $n;
	}

	/**
	 * Y a-t-il une prochaine vignette ?
	 *
	 * @return boolean
	 */
	public function nextThumb()
	{
		static $next = -1;

		return template::nextObject(album::$thumbs, $next);
	}

	/**
	 * L'ajout d'images est-il autorisé pour cet album ?
	 *
	 * @return boolean
	 */
	public function disUpload()
	{
		return $_GET['section'] == 'album'
			&& $this->disAuthUser()
			&& gallery::$catUploadable
			&& users::$perms['gallery']['perms']['upload']
			&& (!users::$perms['gallery']['perms']['upload_create_owner']
				|| (users::$perms['gallery']['perms']['upload_create_owner']
				&& users::$infos['user_id'] == category::$infos['user_id']));
	}



	/**
	 * Retourne l'élément de catégorie trouvée $item.
	 *
	 * @param string $item
	 * @return string
	 */
	private function _getSearchResultCategory($item, &$i)
	{
		switch ($item)
		{
			case 'link' :
				$type = ($i['cat_filemtime'] === NULL) ? 'category' : 'album';
				return utils::genURL($type . '/' . $i['cat_id'] . '-' . $i['cat_url']);

			case 'parents' :
				return (isset(album::$categoriesParents[dirname($i['cat_path'])]))
					? utils::tplProtect(
						album::$categoriesParents[dirname($i['cat_path'])]['cat_name']
						. utils::$config['level_separator']
					  )
					: '';

			case 'title' :
				return utils::tplProtect(utils::getLocale($i['cat_name']));
		}
	}

	/**
	 * Retourne la position des sections spéciales.
	 *
	 * @param string $item
	 * @return string
	 */
	private function _getSpecialesPosition($item)
	{
		if ($item !== 'album' && $item !== '')
		{
			return;
		}

		// Moteur de recherche.
		if (isset($_GET['search_query']))
		{
			$message = (album::$nbImages > 0
			|| count(album::$searchAlbums) > 0
			|| count(album::$searchCategories) > 0)
				? __('Résultats de la recherche : %s')
				: __('Aucun élément trouvé pour la recherche : %s');

			return sprintf(
				$message,
				'<span class="current">' . utils::tplProtect($_GET['search_query']) . '</span>'
			);
		}
		if (isset($_GET['search']))
		{
			return __('Recherche périmée.');
		}

		// Favoris ou images de l'utilisateur.
		if (substr($_GET['section'], 0, 4) == 'user')
		{
			return $this->_getUserPosition();
		}

		if (($i = category::$infos) == NULL)
		{
			return __('La catégorie demandée n\'existe pas.');
		}

		$gallery = '1-' . __('galerie');

		// Nom de l'objet.
		$cat_name = $i['type_html'];

		// Marque de l'appareil.
		if ($_GET['section'] == 'camera-brand')
		{
			$link = utils::genURL('camera-brand/' . gallery::$cameraInfos['camera_brand_id']
				. '-' . gallery::$cameraInfos['camera_brand_url']);
			$brand = '<span class="current"><a href="' . $link . '">'
				. utils::tplProtect(gallery::$cameraInfos['camera_brand_name']) . '</a></span>';

			// Il y a plus d'une image.
			if (album::$nbImages > 1)
			{
				$message = __('Les %s images de %s prises avec un appareil photo de marque %s');
				$s = sprintf($message, album::$nbImages, $cat_name, $brand);
			}

			// Il n'y a qu'une seule image.
			else if (album::$nbImages > 0)
			{
				$message = __('La seule image de %s prise avec un appareil photo de marque %s');
				$s = sprintf($message, $cat_name, $brand);
			}

			// Il n'y a aucune image.
			else
			{
				$message = __('Aucune image de %s prise avec un appareil photo de marque %s');
				$s = sprintf($message, $cat_name, $brand);
			}

			return $s;
		}

		// Modèle de l'appareil.
		if ($_GET['section'] == 'camera-model')
		{
			$link = utils::genURL('camera-model/' . gallery::$cameraInfos['camera_model_id']
				. '-' . gallery::$cameraInfos['camera_model_url']);
			$model = '<span class="current"><a href="' . $link . '">'
				. utils::tplProtect(gallery::$cameraInfos['camera_model_name']) . '</a></span>';

			// Il y a plus d'une image.
			if (album::$nbImages > 1)
			{
				$message = __('Les %s images de %s prises avec le modèle d\'appareil photo %s');
				$s = sprintf($message, album::$nbImages, $cat_name, $model);
			}

			// Il n'y a qu'une seule image.
			else if (album::$nbImages > 0)
			{
				$message = __('La seule image de %s prise avec le modèle d\'appareil photo %s');
				$s = sprintf($message, $cat_name, $model);
			}

			// Il n'y a aucune image.
			else
			{
				$message = __('Aucune image de %s prise avec le modèle d\'appareil photo %s');
				$s = sprintf($message, $cat_name, $model);
			}

			return $s;
		}

		// Images récentes.
		if ($_GET['section'] == 'recent-images')
		{
			$days = (utils::$config['recent_images_time'] > 1)
				? __('de moins de %s jours')
				: __('de moins de %s jour');
			$days = sprintf($days, (int) utils::$config['recent_images_time']);
			$link = utils::genURL('recent-images/' . $gallery);
			$recent = '<span class="current"><a href="' . $link . '">'
				. $days . '</a></span>';

			// Il y a plus d'une image.
			if (album::$nbImages > 1)
			{
				$message = __('Classement des %s images %s de %s');
				$s = sprintf($message, album::$nbImages, $recent, $cat_name);
			}

			// Il n'y a qu'une seule image.
			else if (album::$nbImages > 0)
			{
				$message = __('La seule image %s de %s');
				$s = sprintf($message, $recent, $cat_name);
			}

			// Il n'y a aucune image.
			else
			{
				$message = __('Aucune image %s dans %s');
				$s = sprintf($message, $recent, $cat_name);
			}
		}

		// Images ajoutées le.
		if ($_GET['section'] == 'date-added')
		{
			$link = utils::genURL('date-added/' . $_GET['date']);
			$date = '<span class="current"><a href="' . $link . '">'
				. $this->_getDateLocale() . '</a></span>';

			// Il y a plus d'une image.
			if (album::$nbImages > 1)
			{
				$message = (strlen($_GET['date']) == 10)
					? __('Les %s images de %s ajoutées le %s')
					: __('Les %s images de %s ajoutées en %s');
				$s = sprintf($message, album::$nbImages, $cat_name, $date);
			}

			// Il n'y a qu'une seule image.
			else if (album::$nbImages > 0)
			{
				$message = (strlen($_GET['date']) == 10)
					? __('La seule image de %s ajoutée le %s')
					: __('La seule image de %s ajoutée en %s');
				$s = sprintf($message, $cat_name, $date);
			}

			// Il n'y a aucune image.
			else
			{
				$message = (strlen($_GET['date']) == 10)
					? __('Aucune image de %s ajoutée le %s')
					: __('Aucune image de %s ajoutée en %s');
				$s = sprintf($message, $cat_name, $date);
			}
		}

		// Images créées le.
		if ($_GET['section'] == 'date-created')
		{
			$link = utils::genURL('date-created/' . $_GET['date']);
			$date = '<span class="current"><a href="' . $link . '">'
				. $this->_getDateLocale() . '</a></span>';

			// Il y a plus d'une image.
			if (album::$nbImages > 1)
			{
				$message = (strlen($_GET['date']) == 10)
					? __('Les %s images de %s créées le %s')
					: __('Les %s images de %s créées en %s');
				$s = sprintf($message, album::$nbImages, $cat_name, $date);
			}

			// Il n'y a qu'une seule image.
			else if (album::$nbImages > 0)
			{
				$message = (strlen($_GET['date']) == 10)
					? __('La seule image de %s créée le %s')
					: __('La seule image de %s créée en %s');
				$s = sprintf($message, $cat_name, $date);
			}

			// Il n'y a aucune image.
			else
			{
				$message = (strlen($_GET['date']) == 10)
					? __('Aucune image de %s créée le %s')
					: __('Aucune image de %s créée en %s');
				$s = sprintf($message, $cat_name, $date);
			}
		}

		// Nombre de commentaires, de votes ou de visites.
		if ($_GET['section'] == 'hits'
		 || $_GET['section'] == 'comments-stats'
		 || $_GET['section'] == 'votes')
		{
			switch ($_GET['section'])
			{
				case 'comments-stats' :
					$text = (album::$nbImages > 1)
						? __('les plus commentées')
						: __('commentée');
					break;

				case 'hits' :
					$text = (album::$nbImages > 1)
						? __('les plus visitées')
						: __('visitée');
					break;

				case 'votes' :
					$text = (album::$nbImages > 1)
						? __('les mieux notées')
						: __('notée');
			}

			$link = utils::genURL($_GET['section'] . '/' . $gallery);
			$link = '<span class="current"><a href="' . $link . '">'
				. $text . '</a></span>';

			// Il y a plus d'une image.
			if (album::$nbImages > 1)
			{
				$message = __('Classement des %s images %s de %s');
				$s = sprintf($message, album::$nbImages, $link, $cat_name);
			}

			// Il n'y a qu'une seule image.
			else if (album::$nbImages == 1)
			{
				$s = sprintf(__('La seule image %s de %s'), $link, $cat_name);
			}

			// Il n'y a aucune image.
			else
			{
				$s = sprintf(__('Aucune image %s dans %s'), $link, $cat_name);
			}
		}

		// Images de la catégorie.
		if ($_GET['section'] == 'images')
		{
			// Il y a plus d'une image.
			if (album::$nbImages > 1)
			{
				$s = sprintf(__('Les %s images de %s'), album::$nbImages, $cat_name);
			}

			// Il n'y a qu'une seule image.
			else if (album::$nbImages == 1)
			{
				$s = sprintf(__('La seule image de %s'), $cat_name);
			}

			// Il n'y a aucune image.
			else
			{
				$s = sprintf(__('Aucune image dans %s'), $cat_name);
			}
		}

		// Images associées à un tag.
		if ($_GET['section'] == 'tag')
		{
			$link = utils::genURL('tag/' . tags::$tagInfos['tag_id']
				. '-' . tags::$tagInfos['tag_url']);
			$tag = '<span class="current"><a href="' . $link . '">'
				. utils::tplProtect(tags::$tagInfos['tag_name']) . '</a></span>';

			// Il y a plus d'une image.
			if (album::$nbImages > 1)
			{
				$message = __('Les %s images de %s liées au tag %s');
				$s = sprintf($message, album::$nbImages, $cat_name, $tag);
			}

			// Il n'y a qu'une seule image.
			else if (album::$nbImages > 0)
			{
				$message = __('La seule image de %s liée au tag %s');
				$s = sprintf($message, $cat_name, $tag);
			}

			// Il n'y a aucune image.
			else
			{
				$message = __('Aucune image liée au tag %s dans %s');
				$s = sprintf($message, $tag, $cat_name);
			}
		}

		// Panier.
		if ($_GET['section'] == 'basket')
		{
			$basket = '<span class="current"><a href="'
				. utils::genURL('basket') . '">' . __('panier') . '</a></span>';

			$basket_filesize = ' (' . utils::filesize(album::$filesize) . ')';

			// Capacité du panier.
			$limits = ' <span id="basket_limit">' . __('Capacité du panier :');
			$limits .= ' <span class="basket_limit">';
			$limits .= sprintf(__('%s images'), (int) utils::$config['basket_max_images']);
			$limits .= '</span>, <span class="basket_limit">';
			$limits .= utils::filesize(utils::$config['basket_max_filesize'] * 1024); 
			$limits .= '</span></span>';

			// Il y a plus d'une image.
			if (album::$nbImages > 1)
			{
				$s = sprintf(__('Les %s images de votre %s'),
					album::$nbImages, $basket . $basket_filesize . $limits);
			}

			// Il n'y a qu'une seule image.
			else if (album::$nbImages > 0)
			{
				$s = sprintf(__('La seule image de votre %s'),
					$basket . $basket_filesize . $limits);
			}

			// Il n'y a aucune image.
			else
			{
				$s = sprintf(__('Votre %s ne contient aucune image'), $basket) . $limits;
			}
		}

		return $s;
	}

	/**
	 * Retourne le texte de description
	 * pour les favoris et images de l'utilisateur.
	 *
	 * @return string
	 */
	private function _getUserPosition()
	{
		$user_name = '<span class="current"><a href="'
			. utils::genURL('user/' . $_GET['user_id']) . '">'
			. utils::tplProtect(users::$profile['user_login']) . '</a></span>';

		$object_link = category::$infos['type_html'];

		// Favoris.
		if ($_GET['section'] == 'user-favorites')
		{
			// Il y a plus d'une image.
			if (album::$nbImages > 1)
			{
				$s = sprintf(__('Les %s favoris de %s dans %s'),
					album::$nbImages, $user_name, $object_link);
			}

			// Il n'y a qu'une seule image.
			else if (album::$nbImages == 1)
			{
				$s = sprintf(__('Le seul favori de %s dans %s'), $user_name, $object_link);
			}

			// Aucune image.
			else
			{
				$s = sprintf(__('%s n\'a aucun favori dans %s'), $user_name, $object_link);
			}
		}

		// Images.
		if ($_GET['section'] == 'user-images')
		{
			// Il y a plus d'une image.
			if (album::$nbImages > 1)
			{
				$s = sprintf(__('Les %s images de %s dans %s'),
					album::$nbImages, $user_name, $object_link);
			}

			// Il n'y a qu'une seule image.
			else if (album::$nbImages == 1)
			{
				$s = sprintf(__('La seule image de %s dans %s'), $user_name, $object_link);
			}

			// Aucune image.
			else
			{
				$s = sprintf(__('%s n\'a aucune image dans %s'), $user_name, $object_link);
			}
		}

		return $s;
	}
}

/**
 * Méthodes de template pour la page des modèles d'appareils photos.
 */
class tplCameras extends tplGallery
{
	/**
	 * La galerie contient-elle des modèles d'appareil photos ?
	 *
	 * @return boolean
	 */
	public function disCameras()
	{
		return count(gallery::$cameras) > 0;
	}

	/**
	 * Retourne l'élément de modèle d'appareil $item.
	 *
	 * @param string $item
	 * @return string|integer
	 */
	public function getCamera($item)
	{
		$i = current(gallery::$cameras);

		$cat_url = (empty(category::$infos))
			? ''
			: '/' . category::$infos['cat_type'] . '/' . category::$purlId;

		switch ($item)
		{
			case 'brand_name' :
				return utils::tplProtect($i['camera_brand_name']);

			case 'brand_link' :
				return utils::genURL('camera-brand/'
					. $i['camera_brand_id'] . '-' . $i['camera_brand_url'] . $cat_url);

			case 'model_name' :
				return utils::tplProtect($i['camera_model_name']);

			case 'model_link' :
				return utils::genURL('camera-model/'
					. $i['camera_model_id'] . '-' . $i['camera_model_url'] . $cat_url);

			case 'nb_images' :
				return utils::tplProtect($i['nb_images']);
		}
	}

	/**
	 * Y a-t-il un prochain modèle d'appareil ?
	 *
	 * @return boolean
	 */
	public function nextCamera()
	{
		static $next = -1;

		return template::nextObject(gallery::$cameras, $next);
	}

	/**
	 * Retourne le nombre d'appareils.
	 *
	 * @return string
	 */
	public function getPosition()
	{
		$object_link = category::$infos['type_html'];

		$nb_cameras = count(gallery::$cameras);

		if ($nb_cameras > 1)
		{
			return sprintf(__('Les %s modèles d\'appareils photos de %s'),
				$nb_cameras, $object_link);
		}
		else if ($nb_cameras > 0)
		{
			return sprintf(__('Le seul modèle d\'appareil photos de %s'), $object_link);
		}
		else
		{
			return sprintf(__('Aucun modèle d\'appareil photos dans %s'), $object_link);
		}
	}
}

/**
 * Méthodes de template pour la page des catégories.
 */
class tplCategory extends tplAlbums
{
	/**
	 * Retourne le lien d'administration.
	 *
	 * @return string
	 */
	public function getAdminLink()
	{
		return CONF_GALLERY_PATH . '/' . CONF_ADMIN_DIR . '/?q='
			. utils::genURL('category/' . category::$infos['cat_id'], TRUE);
	}

	/**
	 * L'élément de navigation entre les pages $item doit-il être affiché ?
	 *
	 * @param string $item
	 * @return boolean
	 */
	public function disNavigation($item = '')
	{
		return template::disNavigation($item, category::$nbPages);
	}

	/**
	 * Retourne l'élément de navigation entre les pages $item.
	 *
	 * @param string $item
	 * @return string
	 */
	public function getNavigation($item)
	{
		return template::getNavigation($item, category::$nbPages, gallery::$sectionRequest);
	}

	/**
	 * Retourne l'élément de la barre de position (fil d'ariane) $item.
	 *
	 * @param string $item
	 * @return string
	 */
	public function getPosition($item = '')
	{
		return template::getPosition(
			$item,
			'category',
			'category',
			'',
			FALSE,
			TRUE,
			__('Accueil'),
			gallery::$parents,
			category::$infos,
			category::$parentPage,
			utils::$config['level_separator'],
			$_GET['object_id'] > 1
		);
	}

	/**
	 * L'élément de vignette $item doit-il être affiché ?
	 *
	 * @param string $item
	 * @return boolean
	 */
	public function disThumb($item)
	{
		if (!is_array(category::$thumbs))
		{
			return;
		}

		$i = current(category::$thumbs);

		switch ($item)
		{
			// Est-ce un album ?
			case 'album' :
				return $i['cat_filemtime'] !== NULL;

			// Nombre d'albums.
			case 'albums' :
				return (bool) utils::$config['thumbs_stats_albums'];

			// L'autorisation d'accès à la catégorie est-elle accordée ?
			case 'auth' :
				return (bool) $i['auth'];

			// Est-ce une catégorie ?
			case 'category' :
				return $i['cat_filemtime'] === NULL;

			// Nombre de commentaires.
			case 'comments' :
				return utils::$config['comments']
					&& utils::$config['thumbs_stats_comments'];

			// Description.
			case 'desc' :
				return utils::$config['thumbs_cat_extended']
					&& (bool) trim(nl2br(utils::tplHTMLFilter(
						utils::getLocale($i['cat_desc']))));

			// Poids.
			case 'filesize' :
				return (bool) utils::$config['thumbs_stats_filesize'];

			// Nombre de visites.
			case 'hits' :
				return (bool) utils::$config['thumbs_stats_hits'];

			// Nombre d'images.
			case 'images' :
				return (bool) utils::$config['thumbs_stats_images'];

			// Informations.
			case 'infos' :
				return (bool) category::$thumbsInfos;

			// Lieu.
			case 'place' :
				return $i['cat_place'] != '';

			// Note moyenne.
			case 'rate' :
				return utils::$config['votes']
					&& utils::$config['thumbs_stats_votes'];

			// Images récentes.
			case 'recent' :
				return utils::$config['recent_images']
					&& ((strtotime($i['cat_lastadddt']) > gallery::$recentImagesLimit
						&& !isset($i['cat_recent_images']))
					|| (strtotime($i['cat_lastadddt']) > gallery::$recentImagesLimit
						&& isset($i['cat_recent_images']) && $i['cat_recent_images'] > 0))
					&& $i['cat_a_images'] > 0;

			// Nombre d'images récentes.
			case 'recent_images' :
				return !empty($i['cat_recent_images'])
					&& utils::$config['recent_images_nb'];

			// Titre de la catégorie.
			case 'title' :
				return (bool) utils::$config['thumbs_stats_category_title'];
		}
	}

	/**
	 * Retourne l'élément de vignette $item.
	 *
	 * @param string $item
	 * @return string|integer
	 */
	public function getThumb($item)
	{
		$i = current(category::$thumbs);

		switch ($item)
		{
			// Date de création.
			case 'created_date' :
				return utils::localeTime(
					utils::tplProtect(__('%d/%m/%Y')),
					utils::tplProtect($i['cat_crtdt'])
				);

			// Nombre d'albums.
			case 'albums' :
				$albums = (int) $i['cat_a_albums'];
				$albums = ($albums == 0 && $i['cat_filemtime'] !== NULL)
					? 1
					: $albums;
				return ($albums > 1)
					? sprintf(__('%s albums'), $albums)
					: sprintf(__('%s album'), $albums);

			// Nombre de commentaires.
			case 'comments' :
				$comments = ($i['cat_a_comments'] > 1)
					? __('%s commentaires')
					: __('%s commentaire');
				return sprintf($comments, (int) $i['cat_a_comments']);

			// Nombre de commentaires, avec lien.
			case 'comments_linked' :
				return ($i['cat_a_comments'] < 1)
					? $this->getThumb('comments')
					: '<a href="' . utils::genURL('comments-stats/'
					. $i['cat_id'] . '-' . $i['cat_url']) . '">'
					. $this->getThumb('comments') . '</a>';

			// Description.
			case 'desc' :
				return template::desc('cat', $i);

			// Poids.
			case 'filesize' :
				return utils::filesize($i['cat_a_size']);

			// Nombre de visites.
			case 'hits' :
				$hits = ($i['cat_a_hits'] > 1)
					? __('%s visites')
					: __('%s visite');
				return sprintf($hits, (int) $i['cat_a_hits']);

			// Nombre de visites, avec lien.
			case 'hits_linked' :
				return ($i['cat_a_hits'] < 1)
					? $this->getThumb('hits')
					: '<a href="' . utils::genURL('hits/'
					. $i['cat_id'] . '-' . $i['cat_url']) . '">'
					. $this->getThumb('hits') . '</a>';

			// Identifiant de la catégorie.
			case 'id' :
				return (int) $i['cat_id'];

			// Nombre d'images.
			case 'images' :
				$images = ($i['cat_a_images'] > 1)
					? __('%s images')
					: __('%s image');
				return sprintf($images, (int) $i['cat_a_images']);

			// Nombre d'images, avec lien.
			case 'images_linked' :
				return ($i['cat_a_images'] < 1)
					? $this->getThumb('images')
					: '<a href="' . utils::genURL('images/'
					. $i['cat_id'] . '-' . $i['cat_url']) . '">'
					. $this->getThumb('images') . '</a>';

			// Date de dernier ajout.
			case 'last_added_date' :
				return utils::localeTime(
					utils::tplProtect(__('%d/%m/%Y')),
					utils::tplProtect($i['cat_lastadddt'])
				);

			// Lien.
			case 'link' :
				$type = ($i['cat_filemtime'] === NULL) ? 'category' : 'album';
				return $this->disThumb('auth')
					? utils::genURL($type . '/' . $i['cat_id'] . '-' . $i['cat_url'])
					: utils::genURL($type . '/' . $i['cat_id'] . '-' . $i['cat_url'] . '/pass');

			// Lieu.
			case 'place' :
				return utils::tplProtect($i['cat_place']);

			// Nombre d'images récentes.
			case 'recent_images' :
				$nb = sprintf('[%s]', (int) $i['cat_recent_images']);
				$link = utils::genURL('recent-images/'
					. $i['cat_id'] . '-' . $i['cat_url']);
				$title = sprintf(__('Afficher les images récentes de \'%s\''),
					utils::tplProtect(utils::getLocale($i['cat_name'])));
				return '<a title="' . $title . '" href="'
					. $link . '">' . $nb . '</a>';

			// Note moyenne.
			case 'rate' :
				return sprintf(
					__('note moyenne : %s'),
					number_format((float) $i['cat_a_rate'], 1, __(','), '')
				);
			case 'rate_visual' :
				return template::visualRate(
					$i['cat_a_rate'],
					$this->getGallery('style_path')
				);
			case 'rate_visual_small' :
				return template::visualRate(
					$i['cat_a_rate'],
					$this->getGallery('style_path'),
					'-small'
				);

			// Centrage de la vignette par CSS.
			case 'thumb_center' :
				$tb = img::getThumbSize($i, 'cat');
				return img::thumbCenter('cat', $tb['width'], $tb['height']);

			// Hauteur de la vignette.
			case 'thumb_height' :
				return (CONF_THUMBS_CAT_METHOD == 'crop')
					? (int) CONF_THUMBS_CAT_HEIGHT
					: (int) CONF_THUMBS_CAT_SIZE;

			// Dimensions de la vignette.
			case 'thumb_size' :
				$tb = img::getThumbSize($i, 'cat');
				return 'width="' . $tb['width'] . '" height="' . $tb['height'] . '"';

			// Emplacement de la vignette.
			case 'thumb_src' :
				return utils::tplProtect(template::getThumbSrc('cat', $i));

			// Largeur de la vignette.
			case 'thumb_width' :
				return (CONF_THUMBS_CAT_METHOD == 'crop')
					? (int) CONF_THUMBS_CAT_WIDTH
					: (int) CONF_THUMBS_CAT_SIZE;

			// Titre.
			case 'title' :
				return utils::tplProtect(utils::getLocale($i['cat_name']));

			// Nombre de votes.
			case 'votes' :
				$comments = ($i['cat_a_votes'] > 1)
					? __('%s votes')
					: __('%s vote');
				return sprintf($comments, (int) $i['cat_a_votes']);

			// Nombre de votes, avec lien.
			case 'votes_linked' :
				return ($i['cat_a_votes'] < 1)
					? $this->getThumb('votes')
					: '<a href="' . utils::genURL('votes/'
					. $i['cat_id'] . '-' . $i['cat_url']) . '">'
					. $this->getThumb('votes') . '</a>';
		}
	}

	/**
	 * Retourne le nombre de lignes occupés par les informations de vignettes.
	 *
	 * @return integer
	 */
	public function getThumbLinesNumber()
	{
		$n = 0;

		foreach (array('albums', 'comments', 'filesize', 'hits', 'images') as $i)
		{
			$n += ($this->disThumb($i)) ? 1 : 0;
		}

		foreach (array('rate', 'title') as $i)
		{
			$n += ($this->disThumb($i)) ? 2 : 0;
		}

		return $n;
	}

	/**
	 * Y a-t-il une prochaine vignette ?
	 *
	 * @return boolean
	 */
	public function nextThumb()
	{
		static $next = -1;

		return template::nextObject(category::$thumbs, $next);
	}
}

/**
 * Méthodes de template pour la page des commentaires.
 */
class tplComments extends tplGallery
{
	/**
	 * Taille de vignette forcée, communiquée par le template.
	 *
	 * @see function nextComment
	 * @var integer
	 */
	private $_thumbForced = 0;



	/**
	 * L'élément de commentaire $item doit-il être affiché ?
	 *
	 * @param string $item
	 * @return boolean
	 */
	public function disComment($item = '')
	{
		$i = current(comments::$items);

		switch ($item)
		{
			// Afficher les commentaires ?
			case '' :
				return !empty(comments::$items);

			// Paramètres communs avec d'autres pages.
			default :
				return $this->_disComment($i, $item, 'com');
		}
	}

	/**
	 * Retourne l'élément de commentaire $item.
	 *
	 * @param string $item
	 * @return integer|string
	 */
	public function getComment($item)
	{
		$i = current(comments::$items);

		switch ($item)
		{
			// Identifiant de l'album.
			case 'album_id' :
				return (int) $i['cat_id'];

			// Lien vers l'album.
			case 'album_link' :
				return utils::genURL('album/' . $i['cat_id'] . '-' . $i['cat_url']);

			// Titre de l'album.
			case 'album_title' :
				return utils::tplProtect(utils::getLocale($i['cat_name']));

			// Nombre de commentaires sur l'image.
			case 'image_comments' :
				return (int) $i['image_comments'];

			// Lien vers l'image.
			case 'image_link' :
				return utils::genURL('image/' . $i['image_id'] . '-' . $i['image_url']);

			// Titre de l'image.
			case 'image_title' :
				return utils::tplProtect(utils::getLocale($i['image_name']));

			// Lien vers le commentaire.
			case 'link' :
				return $this->getComment('image_link') . '#co' . $this->getComment('id');

			// Numéro du commentaire.
			case 'num' :
				$nb_per_page = (int) utils::$config['pages_params']['comments']['nb_per_page'];
				$pages = ($_GET['page'] > 1)
					? ($_GET['page'] - 1) * $nb_per_page
					: 0;
				return (int) (comments::$nbItems - $this->_commentNum - $pages);

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
				return utils::tplProtect(template::getThumbSrc('img', $i));

			// Heure du commentaire.
			case 'time' :
				return utils::localeTime('%H:%M', $i['com_crtdt']);

			// Paramètres communs avec d'autres pages.
			default :
				return $this->_getComment($i, $item, 'com');
		}
	}

	/**
	 * Retourne la valeur de la propriété comments::${$property}.
	 *
	 * @param array|string $property
	 * @return mixed
	 */
	public function getCommentsProperty($property)
	{
		return (is_array($property))
			? utils::tplProtect(comments::${$property[0]}[$property[1]])
			: utils::tplProtect(comments::${$property});
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
		$this->_commentNum++;

		return template::nextObject(comments::$items, $next);
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
		return template::getNavigation($item, comments::$nbPages, gallery::$sectionRequest);
	}

	/**
	 * Retourne le nombre de commentaires de l'objet courant.
	 *
	 * @return string
	 */
	public function getPosition()
	{
		return ($_GET['section'] == 'user-comments')
			? $this->_getPositionUser()
			: $this->_getPositionCategory();
	}



	/**
	 * Retourne le nombre de commentaires de l'utilisateur courant.
	 *
	 * @return string
	 */
	private function _getPositionUser()
	{
		$i =& comments::$catInfos;

		$user = '<span class="current"><a href="'
			. utils::genURL('user/' . $_GET['object_id']) . '">'
			. utils::tplProtect($i['user_login']) . '</a></span>';

		if (comments::$nbItems > 1)
		{
			return sprintf(__('Les %s commentaires postés par %s dans %s'),
				comments::$nbItems, $user, category::$infos['type_html']);
		}
		if (comments::$nbItems == 1)
		{
			return sprintf(__('Le seul commentaire posté par %s dans %s'),
				$user, category::$infos['type_html']);
		}

		return sprintf(__('%s n\'a posté aucun commentaire dans %s'),
			$user, category::$infos['type_html']);
	}

	/**
	 * Retourne le nombre de commentaires dans la catégorie courante.
	 *
	 * @return string
	 */
	private function _getPositionCategory()
	{
		$object_link = comments::$catInfos['type_html'];

		if (comments::$nbItems > 1)
		{
			return sprintf(__('Les %s commentaires de %s'), comments::$nbItems, $object_link);
		}
		if (comments::$nbItems == 1)
		{
			return sprintf(__('Le seul commentaire de %s'), $object_link);
		}

		return sprintf(__('Aucun commentaire dans %s'), $object_link);
	}
}

/**
 * Méthodes de template pour la page contact.
 */
class tplContact extends tplGallery
{
	/**
	 * Doit-on afficher l'élément de page contact $item ?
	 *
	 * @param string $item
	 * @return boolean
	 */
	public function disContact($item)
	{
		switch ($item)
		{
			// Captcha.
			case 'captcha' :
				return (bool) utils::$config['recaptcha_contact'];

			// Nom du champ pour lequel il y a une erreur.
			case 'field_error' :
				return (bool) gallery::$fieldError;

			// Message.
			case 'message' :
				return trim(utils::$config['pages_params']['contact']['message']) !== '';
		}
	}

	/**
	 * Retourne l'élément de profil $item.
	 *
	 * @param string $item
	 * @return string
	 */
	public function getContact($item)
	{
		switch ($item)
		{
			// Captcha.
			case 'captcha' :
				return recaptcha_get_html(utils::$config['recaptcha_public_key']);

			// Nom du champ our lequel il y a une erreur.
			case 'field_error' :
				return utils::tplProtect(gallery::$fieldError);

			// Message.
			case 'message' :
				return nl2br(utils::tplHTMLFilter(
					utils::getLocale(utils::$config['pages_params']['contact']['message']
				)));
		}
	}
}

/**
 * Méthodes de template pour la page de récupération de mot de passe.
 */
class tplForgot extends tplGallery {}

/**
 * Méthodes de template pour la page du livre d'or.
 */
class tplGuestbook extends tplGallery
{
	/**
	 * L'élément d'ajout de commentaire $item doit-il être affiché ?
	 *
	 * @param string $item
	 * @return boolean
	 */
	public function disAddComment($item = '')
	{
		switch ($item)
		{
			case '' :
				return utils::$config['users'] != 1
					|| users::$perms['gallery']['perms']['add_comments'];

			default :
				return $this->_disAddComment($item);
		}
	}

	/**
	 * Retourne l'élément d'ajout de commentaire $item.
	 *
	 * @return string
	 */
	public function getAddComment($item)
	{
		switch ($item)
		{
			case 'form_action' :
				return utils::genURL(gallery::$sectionRequest);

			// Note.
			case 'rate' :
				$list = '<option>' . __('aucune') . '</option>';
				for ($i = 1; $i < 6; $i++)
				{
					$selected = (isset($_POST['rate']) && $_POST['rate'] == $i)
						? ' selected="selected"'
						: '';
					$list .= '<option' . $selected . ' value="' . $i . '">' . $i . '</option>';
				}
				return $list;

			default :
				return $this->_getAddComment($item);
		}
	}

	/**
	 * L'élément de commentaire $item doit-il être affiché ?
	 *
	 * @param string $item
	 * @return boolean
	 */
	public function disComment($item = '')
	{
		$i = current(guestbook::$items);

		switch ($item)
		{
			// Afficher les commentaires ?
			case '' :
				return !empty(guestbook::$items);

			// Note.
			case 'rate' :
				return in_array($i['guestbook_rate'], array('1', '2', '3', '4', '5'));

			// Paramètres communs avec d'autres pages.
			default :
				return $this->_disComment($i, $item, 'guestbook');
		}
	}

	/**
	 * Retourne l'élément de commentaire $item.
	 *
	 * @param string $item
	 * @return integer|string
	 */
	public function getComment($item)
	{
		$i = current(guestbook::$items);

		switch ($item)
		{
			// Note.
			case 'rate' :
				return sprintf(__('note : %s'), (int) $i['guestbook_rate']);

			case 'rate_visual' :
				return template::visualRate(
					$i['guestbook_rate'],
					$this->getGallery('style_path'),
					'-small'
				);

			// Paramètres communs avec d'autres pages.
			default :
				return $this->_getComment($i, $item, 'guestbook');
		}
	}

	/**
	 * Retourne l'élément $item des commentaires.
	 *
	 * @param string $item
	 * @return integer|string
	 */
	public function getComments($item)
	{
		$i = current(guestbook::$items);

		switch ($item)
		{
			// Nombre de pages.
			case 'nb_pages' :
				return (int) guestbook::$nbPages;

			// Nombre de commentaires.
			case 'nb_comments' :
				return (int) guestbook::$nbItems;

			// Texte du nombre de commentaires
			case 'nb_comments_text' :
				$nb_comments = $this->getComments('nb_comments');
				$text = ($nb_comments > 1)
					? __('Le livre d\'or contient %s commentaires')
					: __('Le livre d\'or contient %s commentaire');
				return sprintf($text, $nb_comments);
		}
	}

	/**
	 * Y a-t-il un prochain commentaire ?
	 *
	 * @return boolean
	 */
	public function nextComment()
	{
		static $next = -1;

		if ($this->_commentNum === -1)
		{
			$this->_commentNum = count(guestbook::$items);
		}
		else
		{
			$this->_commentNum--;
		}

		return template::nextObject(guestbook::$items, $next);
	}

	/**
	 * L'élément $item du livre d'or doit-il être affiché ?
	 *
	 * @param string $item
	 * @return boolean
	 */
	public function disGuestbook($item)
	{
		switch ($item)
		{
			// Message.
			case 'message' :
				return trim(utils::$config['pages_params']['guestbook']['message']) !== '';
		}
	}

	/**
	 * Retourne l'élément $item du livre d'or.
	 *
	 * @param string $item
	 * @return integer|string
	 */
	public function getGuestbook($item)
	{
		switch ($item)
		{
			// Message.
			case 'message' :
				return nl2br(utils::tplHTMLFilter(
					utils::getLocale(utils::$config['pages_params']['guestbook']['message']
				)));
		}
	}

	/**
	 * L'élément de navigation entre les pages $item doit-il être affiché ?
	 *
	 * @param string $item
	 * @return boolean
	 */
	public function disNavigation($item = '')
	{
		return template::disNavigation($item, guestbook::$nbPages);
	}

	/**
	 * Retourne l'élément de navigation entre les pages $item.
	 *
	 * @param string $item
	 * @return string
	 */
	public function getNavigation($item)
	{
		return template::getNavigation($item, guestbook::$nbPages, gallery::$sectionRequest);
	}
}

/**
 * Méthodes de template pour la page de l'historique.
 */
class tplHistory extends tplGallery
{
	/**
	 * Jour.
	 *
	 * @var string
	 */
	private $_day;

	/**
	 * Mois.
	 *
	 * @var string
	 */
	private $_month;

	/**
	 * Année.
	 *
	 * @var string
	 */
	private $_year;



	/**
	 * Retourne l'élément de date d'ajout $item.
	 *
	 * @param string $item
	 * @return integer|string
	 */
	public function getHistoryAdddt($item)
	{
		$date = $this->_year . $this->_month . $this->_day;

		$cat_url = (empty(category::$infos))
			? ''
			: '/' . category::$infos['cat_type'] . '/' . category::$purlId;

		switch ($item)
		{
			case 'date' :
				return utils::tplProtect(utils::localeTime(__('%A %d'), $date));

			case 'day' :
				return utils::tplProtect($this->_day);

			case 'day_link' :
				return utils::genURL('date-added/' . $this->_year
					. '-' . $this->_month . '-' . $this->_day . $cat_url);

			case 'month' :
				return utils::tplProtect($this->_month);

			case 'month_link' :
				return utils::genURL('date-added/' . $this->_year
					. '-' . $this->_month . $cat_url);

			case 'month_name' :
				$date = substr_replace($date, '01', 6, 2);
				return utils::tplProtect(utils::localeTime(__('%B %Y'), $date));

			case 'nb_images' :
				$nb_images = current(gallery::$historyAdddt[$this->_year][$this->_month]);
				$message = ($nb_images > 1)
					? __('%s images')
					: __('%s image');
				return utils::tplProtect(sprintf($message, $nb_images));

			case 'year' :
				return utils::tplProtect($this->_year);

			case 'year_link' :
				return utils::genURL('date-added/' . $this->_year . $cat_url);
		}
	}

	/**
	 * Y a-t-il une prochaine date d'ajout ?
	 *
	 * @return boolean
	 */
	public function nextHistoryAdddt($type)
	{
		static $next_day = -1;
		static $next_month = -1;
		static $next_year = -1;

		switch ($type)
		{
			case 'day' :
				$next = template::nextObject(
					gallery::$historyAdddt[$this->_year][$this->_month],
					$next_day
				);
				$this->_day = key(gallery::$historyAdddt[$this->_year][$this->_month]);
				return $next;

			case 'month' :
				$next = template::nextObject(
					gallery::$historyAdddt[$this->_year],
					$next_month
				);
				$this->_month = key(gallery::$historyAdddt[$this->_year]);
				return $next;

			case 'year' :
				$next = template::nextObject(
					gallery::$historyAdddt,
					$next_year
				);
				$this->_year = key(gallery::$historyAdddt);
				return $next;
		}
	}

	/**
	 * Doit-on afficher les dates d'ajout ?
	 *
	 * @return boolean
	 */
	public function disHistoryAdddt()
	{
		return !empty(gallery::$historyAdddt);
	}

	/**
	 * Doit-on afficher les dates de création ?
	 *
	 * @return boolean
	 */
	public function disHistoryCrtdt()
	{
		return !empty(gallery::$historyCrtdt);
	}

	/**
	 * Retourne l'élément de date de création $item.
	 *
	 * @param string $item
	 * @return integer|string
	 */
	public function getHistoryCrtdt($item)
	{
		$date = $this->_year . $this->_month . $this->_day;

		$cat_url = '';
		if (!empty(category::$infos))
		{
			$type = (category::$infos['cat_filemtime'] === NULL)
				? 'category'
				: 'album';
			$cat_url = '/' . $type . '/' . category::$purlId;
		}

		switch ($item)
		{
			case 'date' :
				return utils::tplProtect(utils::localeTime(__('%A %d'), $date));

			case 'day' :
				return utils::tplProtect($this->_day);

			case 'day_link' :
				return utils::genURL('date-created/' . $this->_year
					. '-' . $this->_month . '-' . $this->_day . $cat_url);

			case 'month' :
				return utils::tplProtect($this->_month);

			case 'month_link' :
				return utils::genURL('date-created/' . $this->_year
					. '-' . $this->_month . $cat_url);

			case 'month_name' :
				$date = substr_replace($date, '01', 6, 2);
				return utils::tplProtect(utils::localeTime(__('%B %Y'), $date));

			case 'nb_images' :
				$nb_images = current(gallery::$historyCrtdt[$this->_year][$this->_month]);
				$message = ($nb_images > 1)
					? __('%s images')
					: __('%s image');
				return utils::tplProtect(sprintf($message, $nb_images));

			case 'year' :
				return utils::tplProtect($this->_year);

			case 'year_link' :
				return utils::genURL('date-created/' . $this->_year . $cat_url);
		}
	}

	/**
	 * Y a-t-il une prochaine date de création ?
	 *
	 * @return boolean
	 */
	public function nextHistoryCrtdt($type)
	{
		static $next_day = -1;
		static $next_month = -1;
		static $next_year = -1;

		switch ($type)
		{
			case 'day' :
				$next = template::nextObject(
					gallery::$historyCrtdt[$this->_year][$this->_month],
					$next_day
				);
				$this->_day = key(gallery::$historyCrtdt[$this->_year][$this->_month]);
				return $next;

			case 'month' :
				$next = template::nextObject(
					gallery::$historyCrtdt[$this->_year],
					$next_month
				);
				$this->_month = key(gallery::$historyCrtdt[$this->_year]);
				return $next;

			case 'year' :
				$next = template::nextObject(
					gallery::$historyCrtdt,
					$next_year
				);
				$this->_year = key(gallery::$historyCrtdt);
				return $next;
		}
	}

	/**
	 * Barre de position.
	 *
	 * @return string
	 */
	public function getPosition()
	{
		return sprintf(
			__('Historique des images de %s'),
			category::$infos['type_html']
		);
	}
}

/**
 * Méthodes de template pour la page des images.
 */
class tplImage extends tplGallery
{
	/**
	 * Doit-on afficher le lien d'administration ?
	 *
	 * @return boolean
	 */
	public function disAdminLink()
	{
		return users::$auth
			&& $this->_isAdminCategoryAccess()
			&& (users::$perms['admin']['perms']['all'] ||
				users::$perms['admin']['perms']['albums_modif']);
	}

	/**
	 * Retourne le lien d'administration.
	 *
	 * @return string
	 */
	public function getAdminLink()
	{
		return CONF_GALLERY_PATH . '/' . CONF_ADMIN_DIR . '/?q='
			. utils::genURL('image/' . image::$infos['image_id'], TRUE);
	}

	/**
	 * L'élément de commentaire $item doit-il être affiché ?
	 *
	 * @param string $item
	 * @return boolean
	 */
	public function disComment($item = '')
	{
		$i = current(comments::$items);

		switch ($item)
		{
			// Afficher les commentaires ?
			case '' :
				return !empty(comments::$items);

			// Paramètres communs avec d'autres pages.
			default :
				return $this->_disComment($i, $item, 'com');
		}
	}

	/**
	 * L'élément d'ajout de commentaire $item doit-il être affiché ?
	 *
	 * @param string $item
	 * @return boolean
	 */
	public function disAddComment($item = '')
	{
		switch ($item)
		{
			case '' :
				return gallery::$catCommentable
					&& (utils::$config['users'] != 1
					 || users::$perms['gallery']['perms']['add_comments']);

			case 'closed' :
				return !gallery::$catCommentable
					&& (utils::$config['users'] != 1
					 || users::$perms['gallery']['perms']['add_comments']);

			default :
				return $this->_disAddComment($item);
		}
	}

	/**
	 * Retourne l'élément d'ajout de commentaire $item.
	 *
	 * @return string
	 */
	public function getAddComment($item)
	{
		switch ($item)
		{
			default :
				return $this->_getAddComment($item);
		}
	}

	/**
	 * Retourne l'élément de commentaire $item.
	 *
	 * @param string $item
	 * @return integer|string
	 */
	public function getComment($item)
	{
		$i = current(comments::$items);

		switch ($item)
		{
			// Paramètres communs avec d'autres pages.
			default :
				return $this->_getComment($i, $item, 'com');
		}
	}

	/**
	 * Y a-t-il un prochain commentaire ?
	 *
	 * @return boolean
	 */
	public function nextComment()
	{
		static $next = -1;

		if ($this->_commentNum === -1)
		{
			$this->_commentNum = (utils::$config['comments_order'] == 'ASC')
				? 1
				: count(comments::$items);
		}
		else
		{
			$this->_commentNum = (utils::$config['comments_order'] == 'ASC')
				? $this->_commentNum + 1
				: $this->_commentNum - 1;
		}

		return template::nextObject(comments::$items, $next);
	}

	/**
	 * Doit-on afficher l'outil de suppression de l'image ?
	 *
	 * @return string
	 */
	public function disDelete()
	{
		return users::$auth
			&& (users::$infos['user_id'] == 1
			 || users::$infos['user_id'] == image::$infos['user_id']);
	}

	/**
	 * Retourne l'élément $item du diaporama.
	 *
	 * @param string $item
	 * @return string
	 */
	public function getDiaporama($item)
	{
		return $this->_getDiaporama($item);
	}

	/**
	 * Doit-on afficher les informations Exif ?
	 *
	 * @return string
	 */
	public function disExif()
	{
		return utils::$config['exif']
			&& !empty(image::$metadata->exif);
	}

	/**
	 * Retourne l'élément de l'information Exif $item.
	 *
	 * @param string $item
	 * @return string
	 */
	public function getExif($item)
	{
		$i = current(image::$metadata->exif);
		$k = key(image::$metadata->exif);

		switch ($item)
		{
			// Nom du tag.
			case 'info' :
				return utils::tplProtect($k);

			// Nom localisé de l'information.
			case 'name' :
				return utils::tplProtect($i['name']);

			// Valeur.
			case 'value' :
				$value = trim(utils::tplProtect($i['value']));
				if ($k == 'Make' && isset(image::$infos['image_camera']))
				{
					$link = utils::genURL(
						'camera-brand/' . image::$infos['image_camera']['camera_brand_id']
						. '-' . image::$infos['image_camera']['camera_brand_url']
					);
					return '<a href="' . $link . '">' . $value . '</a>';
				}
				else if ($k == 'Model' && isset(image::$infos['image_camera']))
				{
					$link = utils::genURL(
						'camera-model/' . image::$infos['image_camera']['camera_model_id']
						. '-' . image::$infos['image_camera']['camera_model_url']
					);
					return '<a href="' . $link . '">' . $value . '</a>';
				}
				else
				{
					return nl2br($value);
				}
		}
	}

	/**
	 * Y a-t-il une prochaine information Exif ?
	 *
	 * @return boolean
	 */
	public function nextExif()
	{
		static $next = -1;

		return template::nextObject(image::$metadata->exif, $next);
	}

	/**
	 * Doit-on afficher les informations Iptc ?
	 *
	 * @return string
	 */
	public function disIptc()
	{
		return utils::$config['iptc']
			&& !empty(image::$metadata->iptc);
	}

	/**
	 * Retourne l'élément de l'information IPTC $item.
	 *
	 * @param string $item
	 * @return string
	 */
	public function getIptc($item)
	{
		$i = current(image::$metadata->iptc);

		switch ($item)
		{
			// Nom de l'information.
			case 'name' :
				return utils::tplProtect($i['name']);

			// Valeur.
			case 'value' :
				return nl2br(trim(utils::tplProtect($i['value'])));
		}
	}

	/**
	 * Y a-t-il une prochaine information IPTC ?
	 *
	 * @return boolean
	 */
	public function nextIptc()
	{
		static $next = -1;

		return template::nextObject(image::$metadata->iptc, $next);
	}

	/**
	 * Doit-on afficher les informations Xmp ?
	 *
	 * @return string
	 */
	public function disXmp()
	{
		return utils::$config['xmp']
			&& !empty(image::$metadata->xmp);
	}

	/**
	 * Retourne l'élément de l'information XMP $item.
	 *
	 * @param string $item
	 * @return string
	 */
	public function getXmp($item)
	{
		$i = current(image::$metadata->xmp);

		switch ($item)
		{
			// Nom de l'information.
			case 'name' :
				return utils::tplProtect($i['name']);

			// Valeur.
			case 'value' :
				return nl2br(trim(utils::tplProtect($i['value'])));
		}
	}

	/**
	 * Y a-t-il une prochaine information XMP ?
	 *
	 * @return boolean
	 */
	public function nextXmp()
	{
		static $next = -1;

		return template::nextObject(image::$metadata->xmp, $next);
	}

	/**
	 * L'outil d'édition doit-il être affiché ?
	 *
	 * @return boolean
	 */
	public function disEdit()
	{
		return $this->disAuthUser()
			&& users::$perms['gallery']['perms']['edit']
			&& (!users::$perms['gallery']['perms']['edit_owner']
				|| (users::$perms['gallery']['perms']['edit_owner']
				&& users::$infos['user_id'] == image::$infos['user_id']));
	}

	/**
	 * Le lien de déconnexion doit-il être affiché ?
	 *
	 * @return boolean
	 */
	public function disDeconnect()
	{
		return (utils::$config['users'] == 1
			&& utils::$config['users_locked_albums_access_mode'] == 'groups') === FALSE
			&& image::$infos['cat_password'] !== NULL;
	}

	/**
	 * Retourne l'élément dde l'album courant $item.
	 *
	 * @param string $item
	 * @return integer|string
	 */
	public function getAlbum($item)
	{
		switch ($item)
		{
			case 'cameras_link' :
				return utils::genURL('cameras/' . image::$infos['cat_id']
					. '-' . image::$infos['cat_url']);

			case 'current_image' :
				return (int) image::$currentImage;

			case 'history_link' :
				return utils::genURL('history/' . image::$infos['cat_id']
					. '-' . image::$infos['cat_url']);

			case 'nb_images' :
				return (int) image::$nbImages;
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
			// Description ?
			case 'desc' :
				return !utils::isEmpty($this->getImage('desc'));

			// Redimensionnement par HTML ?
			case 'html_resize' :
				return !empty(image::$resize)
					&& utils::$config['images_resize'] == 1
					&& utils::$config['images_resize_method'] == 1;

			// L'image est-elle dans les favoris ou le panier de l'utilisateur ?
			case 'in_basket' :
			case 'in_favorites' :
				return !empty(image::$infos[$item]);

			case 'resize' :
				return $this->disGallery('download_image')
					&& !empty(image::$resize);
		}
	}

	/**
	 * Retourne l'élément d'image $item.
	 *
	 * @param string $item
	 * @return integer|string
	 */
	public function getImage($item)
	{
		$i = image::$infos;

		switch ($item)
		{
			// Lien canonique.
			case 'canonical' :
				return GALLERY_HOST . utils::genURL(
					'image/' . $i['image_id'] . '-' . $i['image_url']
				);

			// Description.
			case 'desc' :
				return template::desc('image', $i);

			case 'desc_lang' :
				return utils::tplProtect(
					utils::getLocale($i['image_desc'], $this->getLang('code'))
				);

			// Nom de fichier.
			case 'filename' :
				return utils::tplProtect(basename($i['image_path']));

			// Hauteur de l'image affichée.
			case 'height' :
				return (empty(image::$resize))
					? (int) $i['image_height']
					: image::$resize['height'];

			// Identifiant de l'image.
			case 'image_id' :

			// Hauteur et largeur de l'image.
			case 'image_height' :
			case 'image_width' :
				return (int) $i[$item];

			// Latitude.
			case 'latitude' :
				return utils::tplProtect($i['image_lat']);

			// Longitude.
			case 'longitude' :
				return utils::tplProtect($i['image_long']);

			// Lien vers l'image taille réelle.
			case 'link' :
				return (CONF_URL_REWRITE)
					? utils::tplProtect(
						CONF_GALLERY_PATH . '/image/' . $i['cat_url'] . '/'
							. $i['image_id'] . '-' . strtolower(basename($i['image_path']))
					  )
					: utils::tplProtect(
						CONF_GALLERY_PATH . '/image.php?id=' . $i['image_id']
							. '&file=' . basename($i['image_path'])
					  );

			// Lieu.
			case 'place' :
				return utils::tplProtect($i['image_place']);

			// Emplacement de l'image.
			case 'src' :
				return (!empty(image::$resize)
					&& utils::$config['images_resize'] == 1
					&& utils::$config['images_resize_method'] == 2)
					? utils::tplProtect(CONF_GALLERY_PATH . '/resize.php?id='
						. $i['image_id'])
					: $this->getImage('link');

			// Tags.
			case 'tags' :
				$tags = array();
				foreach (tags::$imageTags as &$tag_infos)
				{
					$tags[] = $tag_infos['tag_name'];
				}
				return utils::tplProtect(implode(', ', $tags));

			// Titre de l'image.
			case 'title' :
				return utils::tplProtect(
					utils::getLocale($i['image_name'])
				);

			case 'title_lang' :
				return utils::tplProtect(
					utils::getLocale($i['image_name'], $this->getLang('code'))
				);

			// Nom d'URL.
			case 'urlname' :
				return utils::tplProtect($i['image_url']);

			// Emplacement de l'avatar de l'utilisateur.
			case 'user_avatar' :
				return ($i['user_avatar'])
					? $this->getGallery('avatars_path') . '/user'
						. $this->getImage('user_id') . '_thumb.jpg'
					: $this->getGallery('style_path') . '/avatar-default.png';

			// Identifiant de l'utilisateur.
			case 'user_id' :
				return (int) $i['user_id'];

			// Lien vers la page de profil de l'utilisateur.
			case 'user_link' :
				return utils::genURL('user/' . (int) $i['user_id']);

			// Nom d'utilisateur de l'utilisateur.
			case 'user_login' :
				return utils::tplProtect($i['user_login']);

			// Largeur de l'image affichée.
			case 'width' :
				return (empty(image::$resize))
					? (int) $i['image_width']
					: image::$resize['width'];
		}
	}

	/**
	 * L'information $item de l'image doit-elle être affichée ?
	 *
	 * @param string $item
	 * @return boolean
	 */
	public function disImageStats($item = '')
	{
		return template::disImageStats($item);
	}

	/**
	 * Retourne l'information $item de l'image.
	 *
	 * @param string $item
	 * @return string
	 */
	public function getImageStats($item)
	{
		$i = image::$infos;

		return template::getImageStats($item, $i);
	}

	/**
	 * L'image est-elle liée à des tags ?
	 *
	 * @return boolean
	 */
	public function disImageTags()
	{
		return count(tags::$imageTags) > 0;
	}

	/**
	 * Retourne l'élément de tags de l'image $item.
	 *
	 * @param string $item
	 * @return integer|string
	 */
	public function getImageTags($item)
	{
		$i = current(tags::$imageTags);

		switch ($item)
		{
			case 'link' :
				return utils::genURL('tag/' . $i['tag_id'] . '-' . $i['tag_url']);

			case 'name' :
				return utils::tplProtect($i['tag_name']);

		}
	}

	/**
	 * Y a-t-il un prochain tag ?
	 *
	 * @return boolean
	 */
	public function nextImageTags()
	{
		static $next = -1;

		return template::nextObject(tags::$imageTags, $next);
	}

	/**
	 * L'élément de navigation entre les pages $item doit-il être affiché ?
	 *
	 * @param string $item
	 * @return boolean
	 */
	public function disNavigation($item = '')
	{
		switch ($item)
		{
			case 'next_active' :
			case 'next_inactive' :
			case 'prev_active' :
			case 'prev_inactive' :
				return image::$navLinks[$item];

			case 'top' :
				return image::$nbImages > 1
					&& (utils::$config['nav_bar'] == 'top'
					|| utils::$config['nav_bar'] == 'top_bottom');

			case 'bottom' :
				return image::$nbImages > 1
					&& (utils::$config['nav_bar'] == 'bottom'
					|| utils::$config['nav_bar'] == 'top_bottom');

			default :
				return image::$nbImages > 1;
		}
	}

	/**
	 * Retourne l'élément de navigation entre les pages $item.
	 *
	 * @param string $item
	 * @return string
	 */
	public function getNavigation($item)
	{
		switch ($item)
		{
			case 'first' :
				return '<<';

			case 'last' :
				return '>>';

			case 'next' :
				return '>';

			case 'prev' :
				return '<';

			case 'first_link' :
			case 'last_link' :
			case 'next_link' :
			case 'prev_link' :
				return image::$navLinks[$item];
		}
	}

	/**
	 * Retourne l'élément de la barre de position (fil d'ariane) $item.
	 *
	 * @param string $item
	 * @return string
	 */
	public function getPosition($item = '')
	{
		$pos = template::getPosition(
			$item,
			'category',
			'image',
			'album',
			FALSE,
			TRUE,
			__('Accueil'),
			gallery::$parents,
			image::$infos,
			image::$parentPage,
			utils::$config['level_separator'],
			TRUE
		);

		// L'image est-elle une des favorites de l'utilisateur ?
		return (utils::$config['users'] && !empty(image::$infos['in_favorites']))
			? str_replace('class="current"', 'class="current favorite"', $pos)
			: $pos;
	}

	/**
	 * La position de la section spéciale doit-elle être affichée ?
	 *
	 * @return boolean
	 */
	public function disPositionSpecial()
	{
		if (isset($_GET['section_b']))
		{
			// Si l'image n'est plus récente, inutile de l'indiquer comme telle.
			if ($_GET['section_b'] == 'recent-images'
			&& (date('Y-m-d H:i:s', gallery::$recentImagesLimit)
			< image::$infos['image_adddt']) === FALSE)
			{
				return FALSE;
			}

			// Si la date d'ajout demandée n'est pas la même que celle
			// de l'image, inutile de l'indiquer comme telle.
			else if ($_GET['section_b'] == 'date-added'
			&& $_GET['date'] != substr(image::$infos['image_adddt'], 0, strlen($_GET['date'])))
			{
				return FALSE;
			}

			// Si la date de création demandée n'est pas la même que celle
			// de l'image, inutile de l'indiquer comme telle.
			else if ($_GET['section_b'] == 'date-created'
			&& $_GET['date'] != substr(image::$infos['image_crtdt'], 0, strlen($_GET['date'])))
			{
				return FALSE;
			}
		}

		return isset($_GET['section_b']) || isset($_GET['search_query']);
	}

	/**
	 * Retourne la position de la section spéciale.
	 *
	 * @return string
	 */
	public function getPositionSpecial()
	{
		if (($i = image::$catInfos) === NULL)
		{
			return;
		}

		// Moteur de recherche.
		if (isset($_GET['search_query']))
		{
			$search_query = '<span class="current"><a href="'
				. utils::genURL('search/' . $_GET['search']) . '">'
				. utils::tplProtect($_GET['search_query']) . '</a></span>';
			return sprintf(__('Résultats de la recherche : %s'), $search_query);
		}

		$cat_id = ($i['cat_id'] == 1)
			? '1-' . __('galerie')
			: $i['cat_id'] . '-' . $i['cat_url'];
		$parent_page = (image::$parentPage === NULL)
			? ''
			: '/page/' . image::$parentPage;

		// Nom de l'objet.
		$cat_name = $i['type_html'];

		// Images du panier.
		if ($_GET['section_b'] == 'basket')
		{
			$basket = '<span class="current"><a href="'
				. utils::genURL('basket') . '">' . __('panier') . '</a></span>';
			$special = sprintf(__('Images de votre %s'), $basket);
		}

		// Images de la marque.
		if ($_GET['section_b'] == 'camera-brand')
		{
			$link = utils::genURL('camera-brand/' . gallery::$cameraInfos['camera_brand_id']
				. '-' . gallery::$cameraInfos['camera_brand_url']);
			$brand = '<span class="current"><a href="' . $link . '">'
				. utils::tplProtect(gallery::$cameraInfos['camera_brand_name']) . '</a></span>';
			$special = sprintf(__('Images de %s prises avec un appareil photo de marque %s'),
				$cat_name, $brand);
		}

		// Images du modèle.
		if ($_GET['section_b'] == 'camera-model')
		{
			$link = utils::genURL('camera-model/' . gallery::$cameraInfos['camera_model_id']
				. '-' . gallery::$cameraInfos['camera_model_url']);
			$model = '<span class="current"><a href="' . $link . '">'
				. utils::tplProtect(gallery::$cameraInfos['camera_model_name']) . '</a></span>';
			$special = sprintf(__('Images de %s prises avec le modèle d\'appareil photo %s'),
				$cat_name, $model);
		}

		// Images récentes.
		if ($_GET['section_b'] == 'recent-images')
		{
			$days = (utils::$config['recent_images_time'] > 1)
				? __('de moins de %s jours')
				: __('de moins de %s jour');
			$days = sprintf($days, (int) utils::$config['recent_images_time']);
			$link = utils::genURL('recent-images/' . $cat_id . $parent_page);
			$link = '<span class="current"><a href="' . $link . '">'
				. $days . '</a></span>';
			$special = sprintf(__('Images %s de %s'), $link, $cat_name);
		}

		// Images les plus commentées.
		if ($_GET['section_b'] == 'comments-stats')
		{
			$link = utils::genURL('comments-stats/' . $cat_id . $parent_page);
			$link = '<span class="current"><a href="' . $link . '">'
				. __('les plus commentées') . '</a></span>';
			$special = sprintf(__('Images %s de %s'), $link, $cat_name);
		}

		// Images les plus visitées.
		if ($_GET['section_b'] == 'hits')
		{
			$link = utils::genURL('hits/' . $cat_id . $parent_page);
			$link = '<span class="current"><a href="' . $link . '">'
				. __('les plus visitées') . '</a></span>';
			$special = sprintf(__('Images %s de %s'), $link, $cat_name);
		}

		// Images de la catégorie.
		if ($_GET['section_b'] == 'images')
		{
			$link = utils::genURL('images/' . $cat_id . $parent_page);
			$link = '<span class="current"><a href="' . $link . '">'
				. __('Images') . '</a></span>';
			$special = sprintf(__('%s de %s'), $link, $cat_name);
		}

		// Images ajoutées le.
		if ($_GET['section_b'] == 'date-added')
		{
			$category = ($i['cat_id'] == 1) ? '' : '/' . $type . '/' . $cat_id;
			$link = utils::genURL('date-added/' . $_GET['date'] . $category . $parent_page);
			$date = '<span class="current"><a href="' . $link . '">'
				. $this->_getDateLocale() . '</a></span>';
			$message = (strlen($_GET['date']) == 10)
				? __('Images de %s ajoutées le %s')
				: __('Images de %s ajoutées en %s');
			$special = sprintf($message, $cat_name, $date);
		}

		// Images créées le.
		if ($_GET['section_b'] == 'date-created')
		{
			$category = ($i['cat_id'] == 1) ? '' : '/' . $type . '/' . $cat_id;
			$link = utils::genURL('date-created/' . $_GET['date'] . $category . $parent_page);
			$date = '<span class="current"><a href="' . $link . '">'
				. $this->_getDateLocale() . '</a></span>';
			$message = (strlen($_GET['date']) == 10)
				? __('Images de %s créées le %s')
				: __('Images de %s créées en %s');
			$special = sprintf($message, $cat_name, $date);
		}

		// Favoris.
		if ($_GET['section_b'] == 'user-favorites')
		{
			$user_name = '<span class="current"><a href="'
				. utils::genURL('user/' . $_GET['user_id']) . '">'
				. utils::tplProtect(users::$profile['user_login']) . '</a></span>';
			$special = sprintf(__('Favoris de %s dans %s'), $user_name, $cat_name);
		}

		// Images.
		if ($_GET['section_b'] == 'user-images')
		{
			$user_name = '<span class="current"><a href="'
				. utils::genURL('user/' . $_GET['user_id']) . '">'
				. utils::tplProtect(users::$profile['user_login']) . '</a></span>';
			$special = sprintf(__('Images de %s dans %s'), $user_name, $cat_name);
		}

		// Images les mieux notées.
		if ($_GET['section_b'] == 'votes')
		{
			$link = utils::genURL('votes/' . $cat_id . $parent_page);
			$link = '<span class="current"><a href="' . $link . '">'
				. __('les mieux notées') . '</a></span>';
			$special = sprintf(__('Images %s de %s'), $link, $cat_name);
		}

		// Images associées à un tag.
		if ($_GET['section_b'] == 'tag')
		{
			$link = utils::genURL('tag/' . tags::$tagInfos['tag_id']
				. '-' . tags::$tagInfos['tag_url']);
			$tag = '<span class="current"><a href="' . $link . '">'
				. utils::tplProtect(tags::$tagInfos['tag_name']) . '</a></span>';
			$special = sprintf(__('Images de %s liées au tag %s'), $cat_name, $tag);
		}

		return $special;
	}

	/**
	 * L'élément de vote $item doit-il être affiché ?
	 *
	 * @param string $item
	 * @return boolean
	 */
	public function disVote($item = '')
	{
		switch ($item)
		{
			case 'closed' :
				return !gallery::$catVotable
					&& (utils::$config['users'] != 1
					 || users::$perms['gallery']['perms']['votes']);

			default :
				return utils::$config['votes']
					&& gallery::$catVotable
					&& (utils::$config['users'] != 1
					 || users::$perms['gallery']['perms']['votes']);
		}
	}

	/**
	 * Retourne le système de vote visuel, et la note de l'utilisateur.
	 *
	 * @return string
	 */
	public function getVote()
	{
		$user_rate = (isset(image::$infos['user_rate']))
			? (int) image::$infos['user_rate']
			: 0;
		return template::visualRate($user_rate, $this->getGallery('style_path'));
	}
}

/**
 * Méthodes de template pour la page de la liste des membres.
 */
class tplMembers extends tplGallery
{
	/**
	 * Retourne la valeur de la propriété users::${$property}.
	 *
	 * @param array|string $property
	 * @return mixed
	 */
	public function getMembersProperty($property)
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
		return template::getNavigation($item, users::$nbPages, gallery::$sectionRequest);
	}

	/**
	 * L'élément de la liste des membres $item doit-il être affiché ?
	 *
	 * @param string $item
	 * @return boolean
	 */
	public function disMember($item = '')
	{
		switch ($item)
		{
			case 'crtdt' :
			case 'lastvstdt' :
			case 'title' :
				return (bool) utils::$config['pages_params']['members']['show_' . $item];
		}
	}

	/**
	 * Retourne l'élément de membre $item.
	 *
	 * @param string $item
	 * @return integer|string
	 */
	public function getMember($item)
	{
		$i = current(users::$items);

		switch ($item)
		{
			// Emplacement de l'avatar.
			case 'avatar_src' :
				return ($i['user_avatar'])
					? $this->getGallery('avatars_path') . '/user'
						. $i['user_id'] . '_thumb.jpg'
					: $this->getGallery('style_path') . '/avatar-default.png';

			// Date d'inscription et date de dernière visite.
			case 'crtdt' :
			case 'lastvstdt' :
				if (empty($i['user_' . $item]))
				{
					return '/';
				}
				return utils::tplProtect(utils::localeTime(
					__('%A %d %B %Y'),
					$i['user_' . $item]
				));

			// Identifiant du groupe de l'utilisateur.
			case 'group_id' :
				return (int) $i['group_id'];

			// Lien vers la page des membres correspondant
			// au groupe de l'utilisateur.
			case 'group_link' :
				return utils::genURL('members/group/' . $this->getMember('group_id'));

			// Titre.
			case 'group_title' :
				return ($this->getMember('group_id') > 3 || $i['group_title'] !== '')
					? utils::tplProtect(utils::getLocale(
						$i['group_title']
					  ))
					: ($this->getMember('group_id') == 1
						? __('Super-administrateur')
						: __('Membre'));

			// Lien vers le profil.
			case 'link' :
				return utils::genURL('user/' . $i['user_id']);

			// Nom d'utilisateur.
			case 'login' :
				return utils::tplProtect($i['user_login']);
		}
	}

	/**
	 * Retourne le nombre de membres.
	 *
	 * @return string
	 */
	public function getPosition()
	{
		// Nombre de membres pour un groupe donné.
		if (isset($_GET['group_id']))
		{
			$i = current(users::$items);
			$group_name = ($_GET['group_id'] > 3 || $i['group_name'] !== '')
				? utils::tplProtect(utils::getLocale($i['group_name']))
				: ($_GET['group_id'] == 1
					? __('Super-administrateur')
					: __('Nouveaux membres'));
			$link = utils::genURL('members/group/' . $_GET['group_id']);
			$link = '<span class="current"><a href="' . $link . '">'
				. $group_name . '</a></span>';

			return (users::$nbUsers > 1)
				? sprintf(__('Les %s membres du groupe %s'), users::$nbUsers, $link)
				: sprintf(__('Le seul membre du groupe %s'), $link);
		}

		// Nombre de membres de la galerie.
		$link = '<span class="current"><a href="' . utils::genURL() . '">'
			. __('la galerie') . '</a></span>';

		return (users::$nbUsers > 1)
			? sprintf(__('Les %s membres de %s'), users::$nbUsers, $link)
			: sprintf(__('Le seul membre de %s'), $link);
	}

	/**
	 * Y a-t-il un prochain membre ?
	 *
	 * @return boolean
	 */
	public function nextMember()
	{
		static $next = -1;

		return template::nextObject(users::$items, $next);
	}
}

/**
 * Méthodes de template pour la page de création d'une nouvelle catégorie.
 */
class tplNewCategory extends tplGallery
{
	/**
	 * Doit-on afficher le menu d'édition de profil ?
	 *
	 * @return boolean
	 */
	public function disUserTools()
	{
		return TRUE;
	}

	/**
	 * Génère une liste de toutes les catégories.
	 *
	 * @param integer $parent_id
	 *	Identifiant de la catégorie parente.
	 * @param integer $n
	 *	Niveau de profondeur.
	 * @return string
	 */
	public function getCategoriesList($parent_id = 1, $n = 1)
	{
		static $list = '';

		if ($n == 1)
		{
			if  (!users::$perms['gallery']['perms']['upload_create_owner'])
			{
				$list .= '<option class="category" value="1">' . __('galerie'). '</option>';
			}
		}

		if (!is_array(map::$categories))
		{
			return $list;
		}

		$level = str_repeat('&nbsp;', $n * 3);

		foreach (map::$categories as $id => &$infos)
		{
			if ($infos['parent_id'] != $parent_id || $id == 1)
			{
				continue;
			}

			if ($infos['cat_filemtime'] !== NULL
			|| !$infos['cat_creatable'])
			{
				continue;
			}

			// Pour les catégories désactivées...
			if ($infos['cat_status'] != '1')
			{
				// ...on ignore les catégories non vides.
				if ($infos['cat_d_images'] > 0)
				{
					continue;
				}

				// ...doit-on ignorer les catégories vides ?
				if (!utils::$config['upload_categories_empty'])
				{
					continue;
				}
			}

			// L'utilisateur doit-il être propriétaire de la catégorie ?
			if  ((users::$perms['gallery']['perms']['upload_create_owner']
			&& $infos['user_id'] != users::$infos['user_id']) === FALSE)
			{
				$list .= '<option class="category" value="' . $id . '">' . $level . '|-- ';
				$list .= utils::tplProtect(utils::getLocale($infos['cat_name']));
				$list .= '</option>';
			}

			// Si c'est une catégorie, on la parcours.
			if ($infos['cat_filemtime'] === NULL)
			{
				$this->getCategoriesList($id, $n + 1);
			}
		}

		if ($n == 1)
		{
			return $list;
		}
	}
}

/**
 * Méthodes de template pour la page de génération de nouveau mot de passe.
 */
class tplNewPassword extends tplGallery
{
	/**
	 * Retourne le nouveau mot de passe.
	 *
	 * @return string
	 */
	public function getNewPassword()
	{
		return utils::tplprotect(users::$newPassword);
	}
}

/**
 * Méthodes de template pour la page des pages personnalisées.
 */
class tplPage extends tplGallery
{
	/**
	 * Retourne le contenu de la page.
	 *
	 * @return string
	 */
	public function getContent()
	{
		$i = utils::$config['pages_params']['perso_' . $_GET['object_id']];

		// Texte enregistré.
		if ($i['type'] == 'text')
		{
			return nl2br(utils::tplHTMLFilter(utils::getLocale($i['text'])));
		}

		// Fichier.
		if (preg_match('`^[-a-z0-9_]{1,64}\.php$`', $i['file']))
		{
			$this->inc(
				$this->getGallery('gallery_abs_path')
				. '/files/pages/' . $i['file']
			);
		}
	}
}

/**
 * Méthodes de template pour la page d'édition du profil utilisateur.
 */
class tplProfile extends tplGallery
{
	/**
	 * Doit-on afficher le menu d'édition de profil ?
	 *
	 * @return boolean
	 */
	public function disUserTools()
	{
		return TRUE;
	}

	/**
	 * Doit-on afficher l'élément de profil $item ?
	 *
	 * @param string $item
	 * @return boolean
	 */
	public function disProfile($item)
	{
		switch ($item)
		{
			// Notifications par courriel.
			case 'alert_inscriptions' :
				return users::$infos['user_id'] == 1
					|| users::$perms['admin']['perms']['users_members'];

			case 'alert_comments' :
				return utils::$config['comments']
					&& (users::$infos['user_id'] == 1
					 || users::$perms['admin']['perms']['comments_edit']);

			case 'alert_comments_follow' :
				return utils::$config['comments']
					&& (users::$infos['user_id'] == 1
					 || users::$perms['gallery']['perms']['alert_email']);

			case 'alert_images' :
				return users::$infos['user_id'] == 1
					|| users::$perms['gallery']['perms']['alert_email'];

			case 'alert_images_pending' :
				return users::$infos['user_id'] == 1
					|| users::$perms['admin']['perms']['albums_pending'];

			// L'utilisateur a-t-il un avatar ?
			case 'avatar' :
				return (bool) users::$infos['user_avatar'];

			// Nom du champ pour lequel il y a une erreur.
			case 'field_error' :
				return (bool) gallery::$fieldError;

			// Informations de profil.
			case 'infos' :
				foreach (utils::$config['users_profile_infos']['infos'] as $info)
				{
					if ($info['activate'])
					{
						return TRUE;
					}
				}
				foreach (utils::$config['users_profile_infos']['perso'] as $info)
				{
					if ($info['activate'])
					{
						return TRUE;
					}
				}
				return FALSE;

			case 'birthdate' :
			case 'desc' :
			case 'email' :
			case 'firstname' :
			case 'loc' :
			case 'name' :
			case 'sex' :
			case 'website' :
				return (bool) utils::$config['users_profile_infos']['infos'][$item]['activate'];

			// Champs obligatoire.
			case 'required_fields' :
				return (bool) utils::$config['users_profile_infos']['required'];

			case 'required_birthdate' :
			case 'required_desc' :
			case 'required_email' :
			case 'required_firstname' :
			case 'required_loc' :
			case 'required_name' :
			case 'required_sex' :
			case 'required_website' :
				$item = explode('_', $item);
				return (bool) utils::$config['users_profile_infos']
					['infos'][$item[1]]['required'];
		}
	}

	/**
	 * Doit-on affiché l'élement de profile personnalisé $item ?
	 *
	 * @param string $item
	 * @return boolean
	 */
	public function disProfilePerso($item = '')
	{
		switch ($item)
		{
			case 'required' :
				$i = current(utils::$config['users_profile_infos']['perso']);
				return (bool) $i['required'];

			case '' :
				return !empty(utils::$config['users_profile_infos']['perso']);
		}
	}

	/**
	 * Retourne l'élément de profil $item.
	 *
	 * @param string $item
	 * @return string
	 */
	public function getProfile($item)
	{
		switch ($item)
		{
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
					: GALLERY_ROOT . '/template/'
						. utils::$config['theme_template'] . '/style/'
						. utils::$config['theme_style'] . '/avatar-default.png';
				$i = img::getImageSize($file);
				return 'width="' . $i['width'] . '" height="' . $i['height'] . '"';

			// Emplacement de l'avatar.
			case 'avatar_src' :
				$rand = (empty($_POST)) ? '' : '?' . mt_rand();
				return (users::$infos['user_avatar'])
					? $this->getGallery('avatars_path') . '/user'
						. (int) users::$infos['user_id'] . '.jpg' . $rand
					: $this->getGallery('style_path') . '/avatar-default.png';

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

			// Nom du champ our lequel il y a une erreur.
			case 'field_error' :
				return utils::tplProtect(gallery::$fieldError);

			// Identifiant.
			case 'id' :
				return (int) users::$infos['user_id'];

			// Identifiant du groupe de l'utilisateur.
			case 'group_id' :
				return (int) users::$infos['group_id'];

			// Lien vers la page des membres correspondant
			// au groupe de l'utilisateur.
			case 'group_link' :
				return utils::genURL('members/group/' . users::$infos['group_id']);

			// Titre du groupe auquel fait partie l'utilisateur.
			case 'group_title' :
				return ($this->getProfile('group_id') > 3
					|| users::$infos['group_title'] !== '')
					? utils::tplProtect(utils::getLocale(
						users::$infos['group_title']
					  ))
					: ($this->getProfile('group_id') == 1
						? __('Super-administrateur')
						: __('Membre'));

			// Langue.
			case 'lang' :
				$lang = (isset($_POST['lang']))
					? $_POST['lang']
					: users::$infos['user_lang'];
				return template::langSelect(utils::tplProtect($lang));

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
				$$s = ' selected="selected" class="selected"';
				return '
					<option value="?"' . $u . '>?</option>
					<option value="F"' . $f . '>' . __('Femme') . '</option>
					<option value="M"' . $m . '>' . __('Homme') . '</option>
				';

			// Fuseau horaire.
			case 'tz' :
				$tz = (isset($_POST['tz']))
					? $_POST['tz']
					: users::$infos['user_tz'];
				return str_replace(
					'value="' . $tz . '"',
					'value="' . $tz . '" selected="selected" class="selected"',
					file_get_contents(GALLERY_ROOT . '/includes/tz.html')
				);

			// Autres informations.
			case 'desc' :
			case 'email' :
			case 'firstname' :
			case 'loc' :
			case 'login' :
			case 'name' :
			case 'website' :
				$item = (isset($_POST[$item]))
					? $_POST[$item]
					: users::$infos['user_' . $item];
				return utils::tplProtect($item);
		}
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
					return $_POST[$id];
				}
				else if (array_key_exists($id, users::$infos['user_other']))
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
}

/**
 * Méthodes de template pour la page de création de compte.
 */
class tplRegister extends tplGallery
{
	/**
	 * Doit-on afficher l'information $item ?
	 *
	 * @param string $item
	 * @return boolean
	 */
	public function disRegister($item = '')
	{
		switch ($item)
		{
			// Nom du champ our lequel il y a une erreur.
			case 'field_error' :
				return (bool) gallery::$fieldError;

			// Informations de profil.
			case 'birthdate' :
			case 'desc' :
			case 'email' :
			case 'firstname' :
			case 'loc' :
			case 'name' :
			case 'sex' :
			case 'website' :
				return (bool) utils::$config['users_profile_infos']['infos'][$item]['activate'];

			// Informations de profil.
			case 'infos' :
				foreach (utils::$config['users_profile_infos']['infos'] as $info)
				{
					if ($info['activate'])
					{
						return TRUE;
					}
				}
				foreach (utils::$config['users_profile_infos']['perso'] as $info)
				{
					if ($info['activate'])
					{
						return TRUE;
					}
				}
				return FALSE;

			// Validation de l'inscription par captcha.
			case 'captcha_validate' :
				return (bool) utils::$config['recaptcha_inscriptions'];

			// Validation de l'inscription par mot de passe.
			case 'password_validate' :
				return (bool) utils::$config['users_inscription_by_password'];

			// Indice pour le mot de passe.
			case 'password_validate_text' :
				return (bool) utils::$config['users_inscription_password_text'];

			// Champs obligatoire.
			case 'required_fields' :
				return (bool) utils::$config['users_profile_infos']['required'];

			case 'required_birthdate' :
			case 'required_desc' :
			case 'required_email' :
			case 'required_firstname' :
			case 'required_loc' :
			case 'required_name' :
			case 'required_sex' :
			case 'required_website' :
				$item = explode('_', $item);
				return (bool) utils::$config['users_profile_infos']
					['infos'][$item[1]]['required'];

			// Enregistrement de l'utilisateur réussi ?
			case '' :
				return (bool) users::$register;
		}
	}

	/**
	 * Retourne l'information $item.
	 *
	 * @param string $item
	 * @return string|integer
	 */
	public function getRegister($item)
	{
		switch ($item)
		{
			// Date de naissance.
			case 'birthdate' :
				$birthdate = (!empty($_POST['day']) && !empty($_POST['month'])
					&& !empty($_POST['year']))
					? $_POST['year'] . '-' . $_POST['month'] . '-' . $_POST['day']
					: '0000-00-00';
				return template::dateSelect($birthdate);

			// Captcha.
			case 'captcha' :
				return recaptcha_get_html(utils::$config['recaptcha_public_key']);

			// Nombre de caractères maxium du champ description.
			case 'desc_maxlength' :
				return (int) utils::$config['users_desc_maxlength'];

			// Nom du champ our lequel il y a une erreur.
			case 'field_error' :
				return utils::tplProtect(gallery::$fieldError);

			// Longueur minimum du mot de passe.
			case 'password_minlength' :
				return (int) utils::$config['users_password_minlength'];

			// Validation de l'inscription par mot de passe.
			case 'password_validate_text' :
				return utils::tplProtect(utils::getLocale(
					utils::$config['users_inscription_password_text']
				));

			// Langue.
			case 'lang' :
				$lang = (isset($_POST['lang']))
					? $_POST['lang']
					: utils::$userLang;
				return template::langSelect(utils::tplProtect($lang));

			// Sexe.
			case 'sex' :
				$s = (isset($_POST['sex'])) ? $_POST['sex'] : 'u';
				$s = ($s != 'F' && $s != 'M') ? 'u' : strtolower($s);
				$u = $f = $m = '';
				$$s = ' selected="selected"';
				return '
					<option value="?"' . $u . '>?</option>
					<option value="F"' . $f . '>' . __('Femme') . '</option>
					<option value="M"' . $m . '>' . __('Homme') . '</option>
				';

			// Fuseau horaire.
			case 'tz' :
				$tz = (isset($_POST['tz']))
					? $_POST['tz']
					: CONF_DEFAULT_TZ;
				return str_replace(
					'value="' . $tz . '"',
					'value="' . $tz . '" selected="selected" class="selected"',
					file_get_contents(GALLERY_ROOT . '/includes/tz.html')
				);

			// Autres informations.
			case 'desc' :
			case 'email' :
			case 'loc' :
			case 'login' :
			case 'name' :
			case 'website' :
				return utils::tplProtect(
					(isset($_POST[$item])) ? $_POST[$item] : ''
				);
		}
	}

	/**
	 * Doit-on affiché l'élement de profile personnalisé $item ?
	 *
	 * @param string $item
	 * @return boolean
	 */
	public function disRegisterPerso($item = '')
	{
		switch ($item)
		{
			case 'required' :
				$i = current(utils::$config['users_profile_infos']['perso']);
				return (bool) $i['required'];

			case '' :
				return !empty(utils::$config['users_profile_infos']['perso']);
		}
	}

	/**
	 * Retourne l'information personnalisée $item du profil.
	 *
	 * @param string $item
	 * @return string
	 */
	public function getRegisterPerso($item)
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

			// Valeur de l'information.
			case 'value' :
				$id = $this->getRegisterPerso('id');
				return (isset($_POST[$id])) ? $_POST[$id] : '';
		}
	}

	/**
	 * Y a-t-il une prochaine information personnalisée du profil ?
	 *
	 * @return boolean
	 */
	public function nextRegisterPerso()
	{
		static $next = -1;

		return template::nextObject(utils::$config['users_profile_infos']['perso'], $next);
	}
}

/**
 * Méthodes de template pour la page de consultation d'un profil utilisateur.
 */
class tplUser extends tplGallery
{
	/**
	 * Doit-on afficher les outils utilisateur ?
	 *
	 * @return boolean
	 */
	public function disUserTools()
	{
		return users::$auth
			&& users::$infos['user_login'] == users::$profile['user_login'];
	}

	/**
	 * Doit-on afficher l'élément de profil $item ?
	 *
	 * @param string $item
	 * @return boolean
	 */
	public function disUser($item)
	{
		switch ($item)
		{
			// Nombre de favoris.
			case 'nb_favorites' :
				return (bool) utils::$config['images_direct_link'] != 1;

			case 'birthdate' :
			case 'desc' :
			case 'email' :
			case 'firstname' :
			case 'loc' :
			case 'name' :
			case 'sex' :
			case 'website' :
				return (bool) utils::$config['users_profile_infos']['infos'][$item]['activate'];

			case 'infos' :
				foreach (utils::$config['users_profile_infos']['infos'] as $info => &$params)
				{
					if ($info != 'email' && $params['activate'])
					{
						return TRUE;
					}
				}
				foreach (utils::$config['users_profile_infos']['perso'] as &$params)
				{
					if ($params['activate'])
					{
						return TRUE;
					}
				}
				return FALSE;
		}
	}

	/**
	 * Retourne l'élément de profil $item.
	 *
	 * @param string $item
	 * @return string
	 */
	public function getUser($item)
	{
		switch ($item)
		{
			// Dimensions de l'avatar.
			case 'avatar_size' :
				$file = (users::$profile['user_avatar'])
					? GALLERY_ROOT . '/users/avatars/user' . users::$profile['user_id'] . '.jpg'
					: GALLERY_ROOT . '/template/'
						. utils::$config['theme_template'] . '/style/'
						. utils::$config['theme_style'] . '/avatar-default.png';
				$i = img::getImageSize($file);
				return 'width="' . $i['width'] . '" height="' . $i['height'] . '"';

			// Emplacement de l'avatar.
			case 'avatar_src' :
				return (users::$profile['user_avatar'])
					? $this->getGallery('avatars_path') . '/user'
						. users::$profile['user_id'] . '.jpg'
					: $this->getGallery('style_path') . '/avatar-default.png';

			// Date de naissance.
			case 'birthdate' :
				return (users::$profile['user_birthdate'] === NULL)
					? '/'
					: utils::tplProtect(utils::localeTime(
						__('%A %d %B %Y'),
						users::$profile['user_birthdate']
					));

			// Date de création.
			case 'crtdt' :
				return utils::tplProtect(utils::localeTime(
					__('%A %d %B %Y'),
					users::$profile['user_crtdt']
				));

			// Description.
			case 'desc' :
				return (empty(users::$profile['user_' . $item]))
					? '/'
					: nl2br(utils::tplProtect(users::$profile['user_' . $item]));

			// Adresse de courriel.
			case 'email' :
				if (empty(users::$profile['user_email']))
				{
					return '/';
				}
				$email = preg_replace('`^(.+)@([^.]+)\.(.+)$`', '$1 at $2 dot $3',
					users::$profile['user_email']);
				return utils::tplProtect($email);

			// Identifiant du groupe de l'utilisateur.
			case 'group_id' :
				return (int) users::$profile['group_id'];

			// Lien vers la page des membres correspondant
			// au groupe de l'utilisateur.
			case 'group_link' :
				return utils::genURL('members/group/' . $this->getUser('group_id'));

			// Titre du groupe auquel fait partie l'utilisateur.
			case 'group_title' :
				return ($this->getUser('group_id') > 3
				 || users::$profile['group_title'] !== '')
					? utils::tplProtect(utils::getLocale(
						users::$profile['group_title']
					  ))
					: ($this->getUser('group_id') == 1
						? __('Super-administrateur')
						: __('Membre'));

			// Date de dernière visite.
			case 'lastvstdt' :
				return utils::tplProtect(utils::localeTime(
					__('%A %d %B %Y'),
					users::$profile['user_lastvstdt']
				));

			// Nombre de commentaires, de favoris et d'images.
			case 'nb_comments' :
			case 'nb_favorites' :
			case 'nb_images' :
				if (users::$profile[$item] > 0)
				{
					$link = substr($item, 3, strlen($item));
					$link = utils::genURL('user-' . $link . '/' . (int) $_GET['object_id']);
					return '<a href="' . $link . '">'
						. (int) users::$profile[$item]
						. '</a>';
				}
				return '0';

			// Sexe.
			case 'sex' :
				switch (users::$profile['user_sex'])
				{
					case 'F' :
						return __('Femme');

					case 'M' :
						return __('Homme');

					default :
						return '/';
				}

			// Adresse de site Web.
			case 'website' :
				return (empty(users::$profile['user_website']))
					? '/'
					: utils::linkify(users::$profile['user_website'], 40);

			// Autres informations.
			case 'firstname' :
			case 'loc' :
			case 'login' :
			case 'name' :
				return (empty(users::$profile['user_' . $item]))
					? '/'
					: utils::tplProtect(users::$profile['user_' . $item]);
		}
	}

	/**
	 * Y a-t-il des informations de profil personnalisées ?
	 *
	 * @param string $item
	 * @return boolean
	 */
	public function disUserPerso()
	{
		return !empty(utils::$config['users_profile_infos']['perso']);
	}

	/**
	 * Retourne l'information personnalisée $item du profil.
	 *
	 * @param string $item
	 * @return string
	 */
	public function getUserPerso($item)
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
				$id = $this->getUserPerso('id');
				return (array_key_exists($id, users::$profile['user_other'])
					&& users::$profile['user_other'][$id] !== '')
					? utils::tplProtect(users::$profile['user_other'][$id])
					: '/';
		}
	}

	/**
	 * Y a-t-il une prochaine information personnalisée du profil ?
	 *
	 * @return boolean
	 */
	public function nextUserPerso()
	{
		static $next = -1;

		return template::nextObject(utils::$config['users_profile_infos']['perso'], $next);
	}
}

/**
 * Méthodes de template pour la page de la recherche avancée.
 */
class tplSearchAdvanced extends tplGallery
{
	/**
	 * Retourne l'élément de recherche avancé $item.
	 *
	 * @param string $item
	 * @return string|integer
	 */
	public function getSearchAdvanced($item)
	{
		switch ($item)
		{
			case 'categories' :
				return preg_replace(
					'`value="[a-z]+/(\d+).+?"`', 'value="$1"', $this->getMap('select')
				);

			case 'date_end' :
			case 'date_start' :
				$options = template::dateSelect(
					date('Y-m-d'), 1900, 'search_options[' . $item . '_%s]'
				);
				return preg_replace(
					'`<option class="date_title"[^<]+</option>`', '', $options
				);
		}
	}
}

/**
 * Méthodes de template pour la page du nuage de tags.
 */
class tplTags extends tplGallery
{
	/**
	 * Retourne le nombre de tags de la catégorie courante.
	 *
	 * @param string $item
	 * @return string
	 */
	public function getPosition()
	{
		$nb_tags = count(tags::$categoryTags);

		if ($_GET['object_id'] == 1)
		{
			$link = utils::genURL();
			$category = '<span class="current"><a href="'
				. $link . '">' . __('la galerie') . '</a></span>';
		}
		else
		{
			$link = utils::genURL(category::$infos['cat_type'] . '/' . category::$purlId);
			$category = (category::$infos['cat_type'] == 'album')
				? __('l\'album %s')
				: __('la catégorie %s');
			$cat_name = utils::tplProtect(category::$infos['cat_name']);
			$cat_name = '<span class="current"><a href="'
				. $link . '">' . $cat_name . '</a></span>';
			$category = sprintf($category, $cat_name);
		}

		return ($nb_tags > 1)
			? sprintf(__('Les %s tags de %s'), $nb_tags, $category)
			: sprintf(__('Le seul tag de %s'), $category);
	}

	/**
	 * La galerie contient-elle des tags ?
	 *
	 * @return boolean
	 */
	public function disTags()
	{
		return count(tags::$categoryTags) > 0;
	}

	/**
	 * Retourne l'élément de tag $item.
	 *
	 * @param string $item
	 * @return string|integer
	 */
	public function getTag($item)
	{
		return $this->_getTag($item, tags::$categoryTags);
	}

	/**
	 * Y a-t-il un prochaine tag ?
	 *
	 * @return boolean
	 */
	public function nextTag()
	{
		return $this->_nextTag(tags::$categoryTags);
	}
}

/**
 * Méthodes de template pour la page d'envoi d'images.
 */
class tplUpload extends tplGallery
{
	/**
	 * Doit-on afficher les outils utilisateur ?
	 *
	 * @return boolean
	 */
	public function disUserTools()
	{
		return TRUE;
	}

	/**
	 * Les images envoyées sont-elles validées par un administrateur ?
	 *
	 * @return boolean
	 */
	public function disValidate()
	{
		return !users::$perms['gallery']['perms']['upload_mode'];
	}

	/**
	 * Génère une liste de tous les albums autorisés à l'upload.
	 *
	 * @param integer $parent_id
	 *	Identifiant de la catégorie parente.
	 * @param integer $n
	 *	Niveau de profondeur.
	 * @return string
	 */
	public function getAlbumsList($parent_id = 1, $n = 1)
	{
		static $list = array();

		if ($n == 1)
		{
			$list[] = '1:{t:\'' . utils::tplProtect(__('galerie')) . '\'}';
		}

		if (!is_array(map::$categories))
		{
			return 'var albums_list = {' . implode(',', $list) . '};';
		}

		$i = 0;
		foreach (map::$categories as $id => &$infos)
		{
			if ($infos['parent_id'] != $parent_id || $id == 1)
			{
				continue;
			}

			// L'utilisateur doit-il être propriétaire de l'album ?
			if ($infos['cat_filemtime'] !== NULL
			&& users::$perms['gallery']['perms']['upload_create_owner']
			&& $infos['user_id'] != users::$infos['user_id'])
			{
				continue;
			}

			// L'autorisation d'ajout d'images doit être activée.
			if ($infos['cat_uploadable'] != '1')
			{
				continue;
			}

			// Pour les catégories désactivées...
			if ($infos['cat_status'] != '1')
			{
				// ...on ignore les catégories non vides.
				if ($infos['cat_d_images'] > 0)
				{
					continue;
				}

				// ...doit-on ignorer les catégories vides ?
				if (!utils::$config['upload_categories_empty'])
				{
					continue;
				}
			}

			// Ajoute la catégorie à la liste.
			$type = ($infos['cat_filemtime'] === NULL)
				? '1'
				: '0';
			$title = utils::tplProtect(utils::getLocale($infos['cat_name']));
			$list[$id] = $id . ':{p:' . $parent_id . ',t:\'' . $title . '\',c:\'' . $type . '\'}';

			// Si c'est une catégorie, on la parcours.
			if ($infos['cat_filemtime'] === NULL)
			{
				if (!$this->getAlbumsList($id, $n + 1))
				{
					continue;
				}
			}

			$i++;
		}

		if ($i == 0)
		{
			unset($list[$parent_id]);
		}

		return ($n == 1)
			? 'var albums_list = {' . implode(',', $list) . '};'
			: $i;
	}

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
	 * Génère un nom de répertoire temporaire.
	 *
	 * @return string
	 */
	public function getTempDir()
	{
		static $tempdir;

		return ($tempdir)
			? $tempdir
			: $tempdir = utils::tplProtect(utils::genKey());
	}
}

/**
 * Méthodes de template pour la page de validation de compte par courriel.
 */
class tplValidation extends tplGallery {}

/**
 * Méthodes de template pour la page de carte de monde.
 */
class tplWatermark extends tplGallery
{
	/**
	 * Doit-on afficher les outils utilisateur ?
	 *
	 * @return boolean
	 */
	public function disUserTools()
	{
		return TRUE;
	}

	/**
	 * L'option de filigrane $item doit-elle être affichée ?
	 *
	 * @param string $item
	 * @return boolean
	 */
	public function disWatermarkOption($item)
	{
		return template::disWatermarkOption($item, users::$watermarkParams);
	}

	/**
	 * Retourne le paramètre de filigrane $item.
	 *
	 * @param string $item
	 * @return string
	 */
	public function getWatermarkOption($item)
	{
		return template::getWatermarkOption($item, users::$watermarkParams);
	}
}

/**
 * Méthodes de template pour la page de carte de monde.
 */
class tplWorldmap extends tplGallery
{
	/**
	 * Catégories correspondantes aux coordonnées courantes.
	 *
	 * @var array
	 */
	private $_categories;

	/**
	 * Images correspondantes aux coordonnées courantes.
	 *
	 * @var array
	 */
	private $_images;



	/**
	 * Retourne l'élément $item des catégories.
	 *
	 * @param string $item
	 * @return integer|string
	 */
	public function getCategories($item = '')
	{
		switch ($item)
		{
			case 'nb_categories' :
				return (int) count($this->_categories);
		}
	}

	/**
	 * Y a-t-il des catégories à géolocaliser ?
	 *
	 * @param string $item
	 * @return boolean
	 */
	public function disCategories($item = '')
	{
		switch ($item)
		{
			default :
				return !empty(gallery::$worldmap['categories']);
		}
	}

	/**
	 * Doit-on afficher l'élément de catégorie $item ?
	 *
	 * @param string $item
	 * @return boolean
	 */
	public function disCategory($item)
	{
		$i = current($this->_categories);

		switch ($item)
		{
			case 'place' :
				return !empty($i['cat_place']);
		}
	}

	/**
	 * Retourne l'élément de catégorie $item.
	 *
	 * @param string $item
	 * @return string|integer
	 */
	public function getCategory($item)
	{
		$i = current($this->_categories);

		switch ($item)
		{
			// Description.
			case 'description' :
				return template::desc('cat', $i);

			// Latitude.
			case 'latitude' :
				return utils::tplProtect($i['cat_lat']);

			// Lien vers la catégorie.
			case 'link' :
				$type = ($i['cat_filemtime'] === NULL) ? 'category' : 'album';
				return utils::genURL($type . '/' . $i['cat_id'] . '-' . $i['cat_url']);

			// Longitude.
			case 'longitude' :
				return utils::tplProtect($i['cat_long']);

			// Lieu.
			case 'place' :
				return (utils::getLocale($i['cat_place']) !== NULL)
					? utils::tplProtect(utils::getLocale($i['cat_place']))
					: '?';

			// Centrage de la vignette par CSS.
			case 'thumb_center' :
				$tb = img::getThumbSize($i, 'cat');
				return img::thumbCenter('cat', $tb['width'], $tb['height']);

			// Hauteur maximale de la vignette.
			case 'thumb_height' :
				return (CONF_THUMBS_CAT_METHOD == 'crop')
					? (int) CONF_THUMBS_CAT_HEIGHT
					: (int) CONF_THUMBS_CAT_SIZE;

			// Dimensions de la vignette.
			case 'thumb_size' :
				$tb = img::getThumbSize($i, 'cat');
				return 'width="' . $tb['width'] . '" height="' . $tb['height'] . '"';

			// Emplacement de la vignette.
			case 'thumb_src' :
				return utils::tplProtect(template::getThumbSrc('cat', $i));

			// Largeur maximale de la vignette.
			case 'thumb_width' :
				return (CONF_THUMBS_CAT_METHOD == 'crop')
					? (int) CONF_THUMBS_CAT_WIDTH
					: (int) CONF_THUMBS_CAT_SIZE;

			// Titre de l'image.
			case 'title' :
				return utils::tplProtect(utils::getLocale($i['cat_name']));

			// Album ou catégorie ?
			case 'type' :
				return ($i['cat_filemtime'] === NULL)
					? 'category'
					: 'album';
		}
	}

	/**
	 * Y a-t-il une prochaine catégorie ?
	 *
	 * @return boolean
	 */
	public function nextCategory()
	{
		static $next = -1;

		return template::nextObject($this->_categories, $next);
	}

	/**
	 * Y a-t-il de prochaines coordonnées de catégorie ?
	 *
	 * @return boolean
	 */
	public function nextCategoryCoords()
	{
		static $next = -1;

		$return = template::nextObject(gallery::$worldmap['categories'], $next);
		$this->_categories = current(gallery::$worldmap['categories']);

		return $return;
	}

	/**
	 * Doit-on afficher l'élément $item des images ?
	 *
	 * @param string $item
	 * @return boolean
	 */
	public function disImages($item = '')
	{
		switch ($item)
		{
			default :
				return !empty(gallery::$worldmap['images']);
		}
	}

	/**
	 * Retourne l'élément $item des images.
	 *
	 * @param string $item
	 * @return integer|string
	 */
	public function getImages($item = '')
	{
		switch ($item)
		{
			case 'nb_images' :
				return (int) count($this->_images);
		}
	}

	/**
	 * Doit-on afficher l'élément d'image $item ?
	 *
	 * @param string $item
	 * @return boolean
	 */
	public function disImage($item)
	{
		$i = current($this->_images);

		switch ($item)
		{
			case 'place' :
				return !empty($i['image_place']);
		}
	}

	/**
	 * Retourne l'élément de l'image $item.
	 *
	 * @param string $item
	 * @return string|integer
	 */
	public function getImage($item)
	{
		$i = current($this->_images);

		switch ($item)
		{
			// Description.
			case 'description' :
				return nl2br(utils::tplHTMLFilter(
					utils::getLocale($i['image_desc'])
				));

			// Latitude.
			case 'latitude' :
				return utils::tplProtect($i['image_lat']);

			// Lien vers l'image.
			case 'link' :
				if (utils::$config['images_direct_link'])
				{
					return (CONF_URL_REWRITE)
						? utils::tplProtect(
							CONF_GALLERY_PATH . '/image/' . $i['cat_url'] . '/'
								. $i['image_id'] . '-' . strtolower(basename($i['image_path']))
						  )
						: utils::tplProtect(
							CONF_GALLERY_PATH . '/image.php?id=' . $i['image_id']
							. '&file=' . basename($i['image_path'])
						  );
				}
				else
				{
					return utils::genURL(
						'image/' . $i['image_id'] . '-' . $i['image_url']
					);
				}

			// Longitude.
			case 'longitude' :
				return utils::tplProtect($i['image_long']);

			// Lieu.
			case 'place' :
				return (utils::getLocale($i['image_place'] !== NULL))
					? utils::tplProtect(utils::getLocale($i['image_place']))
					: '?';

			// Centrage de la vignette par CSS.
			case 'thumb_center' :
				$tb = img::getThumbSize($i, 'img');
				return img::thumbCenter('img', $tb['width'], $tb['height']);

			// Hauteur maximale de la vignette.
			case 'thumb_height' :
				return (CONF_THUMBS_IMG_METHOD == 'crop')
					? (int) CONF_THUMBS_IMG_HEIGHT
					: (int) CONF_THUMBS_IMG_SIZE;

			// Dimensions de la vignette.
			case 'thumb_size' :
				$tb = img::getThumbSize($i, 'img');
				return 'width="' . $tb['width'] . '" height="' . $tb['height'] . '"';

			// Emplacement de la vignette.
			case 'thumb_src' :
				return utils::tplProtect(template::getThumbSrc('img', $i));

			// Largeur maximale de la vignette.
			case 'thumb_width' :
				return (CONF_THUMBS_IMG_METHOD == 'crop')
					? (int) CONF_THUMBS_IMG_WIDTH
					: (int) CONF_THUMBS_IMG_SIZE;

			// Titre de l'image.
			case 'title' :
				return utils::tplProtect(utils::getLocale($i['image_name']));
		}
	}

	/**
	 * Y a-t-il une prochaine image ?
	 *
	 * @return boolean
	 */
	public function nextImage()
	{
		static $next = -1;

		return template::nextObject($this->_images, $next);
	}

	/**
	 * Y a-t-il de prochaines coordonnées d'image ?
	 *
	 * @return boolean
	 */
	public function nextImageCoords()
	{
		static $next = -1;

		$return = template::nextObject(gallery::$worldmap['images'], $next);
		$this->_images = current(gallery::$worldmap['images']);

		return $return;
	}

	/**
	 * Retourne l'option $item.
	 *
	 * @param string $item
	 * @return integer
	 */
	public function getOption($item)
	{
		$i = utils::$config['pages_params']['worldmap'];

		switch ($item)
		{
			case 'center_lat' :
			case 'center_long' :
			case 'zoom' :
				return (float) $i[$item];
		}
	}
}
?>