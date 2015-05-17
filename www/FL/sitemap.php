<?php
/**
 * Fabrication d'un sitemap XML.
 *
 * @license http://www.gnu.org/licenses/gpl.html
 * @link http://www.igalerie.org/
 */

$gets = array('q');
require_once(dirname(__FILE__) . '/includes/prepend.php');
if (!isset($_GET['q']) || !in_array($_GET['q'], array('categories', 'images', 'pages', 'tags')))
{
	$_GET['q'] = '';
}

// Connexion à la base de données.
utils::$db = new db();
if (utils::$db->connexion === NULL)
{
	printXML();
}

// Récupération de la configuration de la galerie.
utils::getConfig();

// Galerie fermée ?
if (utils::$config['gallery_closure'])
{
	printXML();
}

// Les pages sont-elles activées ?
$pages = utils::$config['widgets_params']['navigation']['status'] && utils::$config['pages'];

// Les tags sont-ils activés ?
$tags = (bool) utils::$config['tags'];

switch ($_GET['q'])
{
	// Sitemap des catégories activées et publiques.
	case 'categories' :
		getXMLCategories();
		break;

	// Sitemap des images activées et publiques.
	case 'images' :
		getXMLImages();
		break;

	// Sitemap des pages activées.
	case 'pages' :
		if ($pages)
		{
			getXMLPages();
		}
		else
		{
			getXMLIndex($pages, $tags);
		}
		break;

	// Sitemap des tags.
	case 'tags' :
		if ($tags)
		{
			getXMLTags();
		}
		else
		{
			getXMLIndex($pages, $tags);
		}
		break;

	// Index des sitemaps.
	default :
		getXMLIndex($pages, $tags);
}

/**
 * Génère le code XML du sitemap des catégories.
 *
 * @return void
 */
function getXMLCategories()
{
	$sql = 'SELECT cat_id,
				   cat_url,
				   DATE(cat_lastadddt) AS cat_lastadddt,
				   CASE WHEN cat_filemtime IS NULL
						THEN "category" ELSE "album"
						 END AS type
			  FROM ' . CONF_DB_PREF . 'categories
			 WHERE cat_status = "1"
			   AND cat_password IS NULL
			   AND cat_id > 1
		  ORDER BY cat_name ASC';
	utils::$db->query($sql, array('fetch' => PDO::FETCH_ASSOC, 'column' => 'cat_id'));
	$categories = (array) utils::$db->queryResult;

	$xml = '';
	foreach ($categories as &$i)
	{
		$xml .= "\t" . '<url>' . "\n";
		$xml .= "\t\t" . '<loc>' . GALLERY_HOST . utils::genURL(
			$i['type'] . '/' . $i['cat_id'] . '-' . $i['cat_url']) . '</loc>' . "\n";
		$xml .= "\t\t" . '<lastmod>'
			. utils::tplProtect($i['cat_lastadddt']) . '</lastmod>' . "\n";
		$xml .= "\t" . '</url>' . "\n";
	}

	printXML($xml, FALSE);
}

/**
 * Génère le code XML du sitemap des images.
 *
 * @return void
 */
function getXMLImages()
{
	$sql = 'SELECT image_id,
				   image_url,
				   DATE(image_adddt) AS image_adddt
			  FROM ' . CONF_DB_PREF . 'images AS img
		 LEFT JOIN ' . CONF_DB_PREF . 'categories AS cat USING (cat_id)
			 WHERE image_status = "1"
			   AND cat_password IS NULL
		  ORDER BY image_name ASC';
	utils::$db->query($sql, array('fetch' => PDO::FETCH_ASSOC, 'column' => 'image_id'));
	$images = (array) utils::$db->queryResult;

	$xml = '';
	foreach ($images as &$i)
	{
		$xml .= "\t" . '<url>' . "\n";
		$xml .= "\t\t" . '<loc>' . GALLERY_HOST . utils::genURL(
			'image/' . $i['image_id'] . '-' . $i['image_url']) . '</loc>' . "\n";
		$xml .= "\t\t" . '<lastmod>'
			. utils::tplProtect($i['image_adddt']) . '</lastmod>' . "\n";
		$xml .= "\t" . '</url>' . "\n";
	}

	printXML($xml, FALSE);
}

/**
 * Génère le code XML de l'index sitemap.
 *
 * @params boolean $pages
 *	Doit-on utiliser le sitemap des pages ?
 * @params boolean $tags
 *	Doit-on utiliser le sitemap des tags ?
 * @return void
 */
function getXMLIndex($pages, $tags)
{
	$xml = "\t" . '<sitemap>' . "\n";
	$xml .= "\t\t" . '<loc>' . GALLERY_HOST . utils::tplProtect(CONF_GALLERY_PATH)
		. '/sitemap.php?q=categories' . '</loc>' . "\n";
	$xml .= "\t" . '</sitemap>' . "\n";
	$xml .= "\t" . '<sitemap>' . "\n";
	$xml .= "\t\t" . '<loc>' . GALLERY_HOST . utils::tplProtect(CONF_GALLERY_PATH)
		. '/sitemap.php?q=images' . '</loc>' . "\n";
	$xml .= "\t" . '</sitemap>' . "\n";
	if ($pages)
	{
		$xml .= "\t" . '<sitemap>' . "\n";
		$xml .= "\t\t" . '<loc>' . GALLERY_HOST . utils::tplProtect(CONF_GALLERY_PATH)
			. '/sitemap.php?q=pages' . '</loc>' . "\n";
		$xml .= "\t" . '</sitemap>' . "\n";
	}
	if ($tags)
	{
		$xml .= "\t" . '<sitemap>' . "\n";
		$xml .= "\t\t" . '<loc>' . GALLERY_HOST . utils::tplProtect(CONF_GALLERY_PATH)
			. '/sitemap.php?q=tags' . '</loc>' . "\n";
		$xml .= "\t" . '</sitemap>' . "\n";
	}

	printXML($xml, TRUE);
}

/**
 * Génère le code XML du sitemap des pages.
 *
 * @return string
 */
function getXMLPages()
{
	$xml = '';
	foreach (utils::$config['pages_params'] as $page => &$i)
	{
		if ($i['status'] != 1)
		{
			continue;
		}
		$url = (isset($i['url']))
			? utils::genURL('page/' . str_replace('perso_', '', $page) . '-' . $i['url'])
			: utils::genURL($page);
		$xml .= "\t" . '<url>' . "\n";
		$xml .= "\t\t" . '<loc>' . GALLERY_HOST . $url . '</loc>' . "\n";
		$xml .= "\t" . '</url>' . "\n";
	}

	printXML($xml, FALSE);
}

/**
 * Génère le code XML du sitemap des tags.
 *
 * @return void
 */
function getXMLTags()
{
	$sql = 'SELECT DISTINCT t.*
			  FROM ' . CONF_DB_PREF . 'tags AS t,
				   ' . CONF_DB_PREF . 'tags_images AS ti,
				   ' . CONF_DB_PREF . 'images AS img,
				   ' . CONF_DB_PREF . 'categories AS cat
			 WHERE t.tag_id = ti.tag_id
			   AND ti.image_id = img.image_id
			   AND img.cat_id = cat.cat_id
			   AND cat.cat_password IS NULL
		  ORDER BY t.tag_name ASC';
	utils::$db->query($sql, array('fetch' => PDO::FETCH_ASSOC, 'column' => 'tag_name'));
	$tags = (array) utils::$db->queryResult;

	$xml = '';
	foreach ($tags as &$i)
	{
		$xml .= "\t" . '<url>' . "\n";
		$xml .= "\t\t" . '<loc>' . GALLERY_HOST . utils::genURL(
			'tag/' . $i['tag_id'] . '-' . $i['tag_url']) . '</loc>' . "\n";
		$xml .= "\t" . '</url>' . "\n";
	}

	printXML($xml, FALSE);
}

/**
 * Imprime le code XML du sitemap.
 *
 * @params string $xml
 *	Code XML du sitemap.
 * @params boolean $index
 *	S'agit-il d'un index sitemap ?
 * @return void
 */
function printXML($xml = '', $index = FALSE)
{
	utils::$db->connexion = NULL;
	header('Content-Type: text/xml; charset=UTF-8');
	echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
	if ($index)
	{
		echo '<sitemapindex xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" ' .
			'xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 ' .
			'http://www.sitemaps.org/schemas/sitemap/0.9/siteindex.xsd" ' .
			'xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
		echo $xml;
		echo '</sitemapindex>';
	}
	else
	{
		echo '<urlset xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" ' .
			'xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 ' .
			'http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd" ' .
			'xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
		echo $xml;
		echo '</urlset>';
	}
	die;
}
?>