<?php

// Code HTML pour l'affichage des images.
$image_html = "\n\t" . '<a href="%1$s"><img width="%2$s"'
	. ' height="%3$s" alt="%4$s" src="%5$s" /></a>' . "\n";

// Clause WHERE de la requête SQL.
$sql_where = '';

// Clause ORDER BY de la requête SQL.
$sql_order_by = 'RAND()'; #images choisies aléatoirement
#$sql_order_by = 'img.image_hits DESC'; #images les plus vues
#$sql_order_by = 'img.image_id DESC'; #dernières images ajoutées à la galerie

// Clause LIMIT de la requête SQL.
$sql_limit = '1';



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

// Récupération des informations utiles.
$sql = 'SELECT *
		  FROM ' . CONF_DB_PREF . 'images AS img
	 LEFT JOIN ' . CONF_DB_PREF . 'categories AS cat USING (cat_id)
		 WHERE img.image_status = "1"
		   AND cat.cat_password IS NULL
			   ' . $sql_where . '
	  ORDER BY ' . $sql_order_by . '
		 LIMIT ' . $sql_limit;
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

	// Largeur de la vignette.
	$image_width = (int) $i['image_width'];

	// Hauteur de la vignette.
	$image_height = (int) $i['image_height'];

	// Emplacement de l'image.
	$image_src = utils::tplProtect(CONF_GALLERY_PATH . '/image.php?id=' . $i['image_id']);

	printf($image_html, $image_href, $image_width, $image_height, $image_title, $image_src);
}
?>