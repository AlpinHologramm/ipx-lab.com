<?php

// Code HTML pour l'affichage du titre de l'album.
#$album_html = '<dd style="width:%3$spx;"><a href="%1$s">%2$s</a></dd>';

// Code HTML pour l'affichage des vignettes.
$thumbs_html = "\n\t" . '<dl><dt style="width:%3$spx">'
	. '<a class="thumb_link" style="width:%3$spx;height:%4$spx;" title="%1$s" href="%2$s">'
	. '<img %5$s style="padding:%6$s" alt="%1$s" src="%7$s" />'
	. '</a></dt>%8$s</dl>' . "\n";

// Nombre maximum de vignettes à afficher.
$thumbs_max = 1;

// Taille maximum des vignettes, en pixels.
// (0 pour ignorer cette option.)
$thumbs_size = 0;

// Critère de tri des vignettes, 3 valeurs possibles :
// 'rand' : images choisies aléatoirement.
// 'hits' : images les plus vues.
// 'last' : dernières images ajoutées à la galerie.
$thumbs_sort = 'last';

// Type de vignette, 3 valeurs possibles :
// 'cat' : vignette de catégorie.
// 'img' : vignette d'image.
// 'wid' : vignette de widget "Bloc image".
$thumbs_type = 'img';



// Composants de base.
if (!defined('GALLERY_ROOT'))
{
	define('GALLERY_ROOT', dirname(dirname(__FILE__)));
}
require_once(dirname(__FILE__) . '/config/conf.php');
require_once(dirname(__FILE__) . '/includes/classes/img.class.php');
require_once(dirname(__FILE__) . '/includes/classes/db.class.php');
require_once(dirname(__FILE__) . '/includes/classes/utils.class.php');

// Connexion à la base de données.
if (!is_object(utils::$db) || utils::$db->connexion === NULL)
{
	utils::$db = new db();
}
if (utils::$db->connexion === NULL)
{
	die('Unable to connect to the database.');
}

// Clause ORDER BY de la requête SQL.
switch ($thumbs_sort)
{
	case 'hits' :
		$sql_order_by = 'img.image_hits DESC';
		break;

	case 'rand' :
		$sql_order_by = 'RAND()';
		break;

	default :
		$sql_order_by = 'img.image_id DESC';
		break;
}

// Récupération des informations utiles.
$sql = 'SELECT *
		  FROM ' . CONF_DB_PREF . 'images AS img
	 LEFT JOIN ' . CONF_DB_PREF . 'categories AS cat USING (cat_id)
		 WHERE img.image_status = "1"
		   AND cat.cat_password IS NULL
	  ORDER BY ' . $sql_order_by . '
		 LIMIT ' . $thumbs_max;
$fetch_style = array('fetch' => PDO::FETCH_ASSOC, 'column' => 'image_id');
if (utils::$db->query($sql, $fetch_style) === FALSE)
{
	die;
}
$images = utils::$db->queryResult;

// Fermeture de la connexion à la base de données.
if (is_object(utils::$db))
{
	utils::$db->connexion = NULL;
}

// Fabrication des vignettes.
foreach ($images as $id => &$i)
{
	// Titre de l'image.
	$image_title = utils::tplProtect($i['image_name']);

	// Lien vers l'image.
	$image_href = (utils::$config['images_direct_link'])
		? utils::tplProtect(CONF_GALLERY_PATH . '/image.php?id=' . $i['image_id'])
		: utils::genURL('image/' . $i['image_id'] . '-' . $i['image_url']);

	// Largeur et hauteur de la vignette.
	if ($thumbs_size > 0)
	{
		$image_width = $image_height = $thumbs_size;
	}
	else
	{
		$image_width = (CONF_THUMBS_IMG_METHOD == 'crop')
			? (int) CONF_THUMBS_IMG_WIDTH
			: (int) CONF_THUMBS_IMG_SIZE;
		$image_height = (CONF_THUMBS_IMG_METHOD == 'crop')
			? (int) CONF_THUMBS_IMG_HEIGHT
			: (int) CONF_THUMBS_IMG_SIZE;
	}

	// Dimensions de la vignette.
	$tb = img::getThumbSize($i, $thumbs_type, $thumbs_size);
	$image_size = 'width="' . $tb['width'] . '" height="' . $tb['height'] . '"';

	// Emplacement de la vignette.
	$image_src = img::filepath('tb_' . $thumbs_type,
		$i['image_path'], $i['image_id'], $i['image_adddt']);
	$image_src = (file_exists(GALLERY_ROOT . '/' . $image_src))
		? CONF_GALLERY_PATH . '/' . $image_src
		: CONF_GALLERY_PATH . '/' . 'thumb.php?' . $thumbs_type[0] . '=' . $i['image_path']
			. '&t=' . basename($image_src)
			. '&k=' . md5($i['image_path'] . '|' . $thumbs_type[0] . '|'
			. CONF_KEY . '|' . basename($image_src));
	$image_src = utils::tplProtect($image_src);

	// Centrage de la vignette.
	$image_center = img::thumbCenter($thumbs_type, $tb['width'], $tb['height'], $thumbs_size);

	if (isset($album_html))
	{
		// Lien vers l'album.
		$album_link = utils::genURL('album/' . $i['cat_id'] . '-' . $i['cat_url']);

		// Titre de l'album.
		$album_title = utils::tplProtect($i['cat_name']);

		$album = sprintf($album_html, $album_link, $album_title, $image_width);
	}
	else
	{
		$album = '';
	}

	printf($thumbs_html, $image_title, $image_href,
		$image_width, $image_height,
		$image_size, $image_center, $image_src,
		$album);
}
?>