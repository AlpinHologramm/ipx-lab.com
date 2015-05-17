<?php
/**
 * Fabrication des flux RSS.
 *
 * @license http://www.gnu.org/licenses/gpl.html
 * @link http://www.igalerie.org/
 */

$gets = array('key', 'lang', 'q', 'type');
require_once(dirname(__FILE__) . '/includes/prepend.php');

// Si la galerie n'est pas installée, on redirige vers le script d'installation.
if (!CONF_INSTALL)
{
	header('Location: ./install/');
	die;
}

// Extraction de la requête.
extract_request(array(
	'album' => array
	(
		'(\d{1,11})-[^/]{1,255}' =>
			array('object_id')
	),
	'category' => array
	(
		'(\d{1,11})-[^/]{1,255}' =>
			array('object_id')
	),
	'image' => array
	(
		'(\d{1,11})-[^/]{1,255}' =>
			array('object_id')
	)
));

if (!isset($_GET['section']))
{
	$_GET['section'] = 'category';
}
if (!isset($_GET['object_id']))
{
	$_GET['object_id'] = 1;
}

if (!isset($_GET['type']) || !isset($_GET['lang'])
|| !preg_match('`^[a-z]{2}_[A-Z]{2}$`', $_GET['lang'])
|| !in_array($_GET['type'], array('comments', 'images'))
|| !isset($_GET['key']) || !preg_match('`^[a-f0-9]{32}$`', $_GET['key'])
|| $_GET['key'] != md5('rss' . $_GET['type'] . '|' . CONF_KEY))
{
	die;
}

rss::init();

// Traitement de la requête.
if ($_GET['type'] == 'images')
{
	if ($_GET['section'] == 'image')
	{
		die;
	}

	rss::getObjectInfos();
	if (rss::$objectInfos['cat_filemtime'] == NULL
	&& utils::$config['rss_notify_albums'])
	{
		rss::getAlbums();
	}
	else
	{
		rss::getImages();
	}
	rss::printRSS('images');
}
else
{
	if (!utils::$config['comments'])
	{
		die;
	}

	rss::getObjectInfos();
	rss::getComments();
	rss::printRSS('comments');
}

// Fermeture de la connexion à la bdd.
utils::$db->connexion = NULL;



/**
 * Traitements pour chaque section.
 */
class rss
{
	/**
	 * Informations utiles de l'objet courant (image ou catégorie).
	 *
	 * @var array
	 */
	public static $objectInfos;



	/**
	 * Jeu de caractères du flux.
	 *
	 * @var string
	 */
	private static $_charset = CONF_CHARSET;

	/**
	 * URL de la galerie.
	 *
	 * @var string
	 */
	private static $_galleryURL;

	/**
	 * Informations utiles des éléments qui constituent le flux.
	 *
	 * @var array
	 */
	private static $_items;

	/**
	 * Format de la date du flux RSS.
	 *
	 * @var string
	 */
	private static $_RFC822 = 'D, d M Y H:i:s O';



	/**
	 * Récupération des derniers albums modifiés.
	 *
	 * @return void
	 */
	public static function getAlbums()
	{
		$params = array(
			'path' => self::$objectInfos['path']
		);
		$sql = 'SELECT cat.cat_id,
					   thumb_id,
					   cat_path,
					   cat_name,
					   cat_url,
					   cat_desc,
					   cat_tb_infos AS tb_infos,
					   cat_crtdt,
					   cat_lastadddt,
					   cat_filemtime,
					   image_id,
					   image_path,
					   image_width,
					   image_height,
					   image_adddt
				 FROM ' . CONF_DB_PREF . 'categories AS cat
		    LEFT JOIN ' . CONF_DB_PREF . 'images AS img
				   ON cat.thumb_id = img.image_id
				WHERE cat_status = "1"
				  AND cat_password IS NULL
				  AND cat_filemtime IS NOT NULL
				  AND cat_path LIKE CONCAT(:path, "%")
			 ORDER BY cat_lastadddt DESC,
					  cat_id DESC
				LIMIT ' . (int) utils::$config['rss_max_items'];
		$fetch_style = array('fetch' => PDO::FETCH_ASSOC, 'column' => 'cat_id');
		if (utils::$db->prepare($sql) === FALSE
		|| utils::$db->executeQuery($params, $fetch_style) === FALSE)
		{
			die;
		}
		self::$_items = utils::$db->queryResult;
	}

	/**
	 * Récupération des derniers commentaires.
	 *
	 * @return void
	 */
	public static function getComments()
	{
		$params = array(
			'path' => self::$objectInfos['path']
		);
		$sql = 'SELECT com.com_id,
					   com.com_crtdt,
					   com.com_message,
					   img.image_id,
					   img.image_name,
					   img.image_url,
					   u.user_id,
					   CASE WHEN u.user_id = 2
							THEN com_author
							ELSE user_login
							 END AS author
				  FROM ' . CONF_DB_PREF . 'comments AS com,
					   ' . CONF_DB_PREF . 'images AS img,
					   ' . CONF_DB_PREF . 'categories AS cat,
					   ' . CONF_DB_PREF . 'users AS u
				 WHERE com.com_status = "1"
				   AND img.image_status = "1"
				   AND cat.cat_password IS NULL
				   AND com.image_id = img.image_id
				   AND com.user_id = u.user_id
				   AND img.cat_id = cat.cat_id
				   AND image_path LIKE CONCAT(:path, "%")
			  ORDER BY com_id DESC
				 LIMIT ' . (int) utils::$config['rss_max_items'];
		$fetch_style = array('fetch' => PDO::FETCH_ASSOC, 'column' => 'com_id');
		if (utils::$db->prepare($sql) === FALSE
		|| utils::$db->executeQuery($params, $fetch_style) === FALSE)
		{
			die;
		}
		self::$_items = utils::$db->queryResult;
	}

	/**
	 * Récupération des dernières images.
	 *
	 * @return void
	 */
	public static function getImages()
	{
		$params = array(
			'path' => self::$objectInfos['path']
		);
		$sql = 'SELECT image_id,
					   image_path,
					   image_name,
					   image_url,
					   image_width,
					   image_height,
					   image_tb_infos AS tb_infos,
					   image_desc,
					   image_adddt,
					   cat.cat_id,
					   cat.cat_name,
					   cat.cat_url
				 FROM ' . CONF_DB_PREF . 'images AS img
			LEFT JOIN ' . CONF_DB_PREF . 'categories AS cat USING (cat_id)
				WHERE image_status = "1"
				  AND cat_password IS NULL
				  AND image_path LIKE CONCAT(:path, "%")
			 ORDER BY image_id DESC
				LIMIT ' . (int) utils::$config['rss_max_items'];
		$fetch_style = array('fetch' => PDO::FETCH_ASSOC, 'column' => 'image_id');
		if (utils::$db->prepare($sql) === FALSE
		|| utils::$db->executeQuery($params, $fetch_style) === FALSE)
		{
			die;
		}
		self::$_items = utils::$db->queryResult;
	}

	/**
	 * Récupération des informations utiles de la catégorie.
	 *
	 * @return void
	 */
	public static function getObjectInfos()
	{
		if ($_GET['section'] == 'image')
		{
			$sql = 'SELECT image_path AS path,
						   image_name AS name
					  FROM ' . CONF_DB_PREF . 'images AS img,
						   ' . CONF_DB_PREF . 'categories AS cat
					 WHERE image_id = ' . (int) $_GET['object_id'] . '
					   AND img.cat_id = cat.cat_id
					   AND image_status = "1"
					   AND cat_password IS NULL';
		}
		else
		{
			$sql = 'SELECT cat_path AS path,
						   cat_name AS name,
						   cat_filemtime
					  FROM ' . CONF_DB_PREF . 'categories
					 WHERE cat_id = ' . (int) $_GET['object_id'] . '
					   AND cat_status = "1"
					   AND cat_password IS NULL';
		}
		if (utils::$db->query($sql, 'row') === FALSE)
		{
			die;
		}
		if (utils::$db->nbResult === 0)
		{
			self::_forbidden();
		}
		self::$objectInfos = utils::$db->queryResult;

		if ($_GET['section'] != 'image')
		{
			self::$objectInfos['path'] = (self::$objectInfos['path'] == '.')
				? ''
				: self::$objectInfos['path'] . '/';
		}
	}

	/**
	 * Initialisation.
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

		// Langue.
		utils::$userLang = $_GET['lang'];
		utils::locale();

		// Galerie fermée ou flux RSS désactivés ?
		if (utils::$config['gallery_closure'] || !utils::$config['rss'])
		{
			self::_forbidden();
		}

		self::$_galleryURL = GALLERY_HOST . CONF_GALLERY_PATH;
	}

	/**
	 * Fabrique et imprime le flux RSS.
	 *
	 * @param string $type
	 *	Type de flux : 'images' ou 'comments'.
	 * @return void
	 */
	public static function printRSS($type)
	{
		// Informations.
		$title = utils::getLocale(utils::$config['gallery_title']);
		$language = substr(utils::$userLang, 0, 2);
		$last_build_date = date(self::$_RFC822);

		// Description du flux.
		$object_name = utils::getLocale(self::$objectInfos['name']);
		switch ($_GET['section'])
		{
			case 'album' :
				$object_name = sprintf(__('l\'album %s'), $object_name);
				break;

			case 'category' :
				$object_name = ($_GET['object_id'] > 1)
					? sprintf(__('la catégorie %s'), $object_name)
					: __('la galerie');
				break;

			case 'image' :
				$current_item = current(self::$_items);
				$object_name = sprintf(__('l\'image %s'), $object_name);
				break;
		}
		switch ($type)
		{
			case 'comments' :
				$description = sprintf(__('Derniers commentaires de %s'), $object_name);
				break;

			case 'images' :
				$description = ($_GET['section'] == 'category' && utils::$config['rss_notify_albums'])
					? sprintf(__('Derniers albums ajoutés ou mis à jour de %s'), $object_name)
					: sprintf(__('Dernières images de %s'), $object_name);
				break;
		}

		// Flux RSS.
		$rss = '<?xml version="1.0" encoding="'
			. utils::tplProtect(self::$_charset) . '"?>' . "\n";
		$rss .= '<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">' . "\n";
		$rss .= '<channel>' . "\n";
		$rss .= '<title>' . utils::tplProtect($title) . '</title>' . "\n";
		$rss .= '<link>' . self::$_galleryURL . '/</link>' . "\n";
		$rss .= '<atom:link href="' . GALLERY_HOST . utils::tplProtect($_SERVER['REQUEST_URI'])
			. '" rel="self" type="application/rss+xml" />' . "\n";
		$rss .= '<description>' . utils::tplProtect($description) . '</description>' . "\n";
		$rss .= '<language>' . utils::tplProtect($language) . '</language>' . "\n";
		$rss .= '<generator>http://www.igalerie.org/</generator>' . "\n";
		$rss .= '<lastBuildDate>' . utils::tplProtect($last_build_date)
			. '</lastBuildDate>' . "\n";

		switch ($type)
		{
			case 'comments' :
				$rss .= self::_printRSSComments();
				break;

			case 'images' :
				$rss .= (self::$objectInfos['cat_filemtime'] == NULL
					&& utils::$config['rss_notify_albums'])
					? self::_printRSSAlbums()
					: self::_printRSSImages();
				break;
		}

		$rss  .= '</channel>' . "\n";
		$rss  .= '</rss>';

		// On imprime le flux.
		header('Content-Type: text/xml; charset=' . self::$_charset);
		echo $rss;
	}



	/**
	 * Imprime un message indiquant que l'utilisateur
	 * n'est pas autorisé à accéder à ce flux RSS.
	 *
	 * @return void
	 */
	private static function _forbidden()
	{
		die(__('Vous n\'êtes pas autorisé à accéder à cette ressource.'));
	}

	/**
	 * Fabrique les éléments du flux RSS des commentaires.
	 *
	 * @return void
	 */
	private static function _printRSSComments()
	{
		$rss = '';

		foreach (self::$_items as &$i)
		{
			// Titre.
			$title = utils::tplProtect(
				utils::getLocale($i['image_name']) . ' - ' . $i['author']
			);

			// Lien.
			$link = GALLERY_HOST . utils::genURL('image/' . $i['image_id']
				. '-' . $i['image_url'] . '#co' . $i['com_id']);

			// Date de publication.
			$pub_date = date(self::$_RFC822, strtotime($i['com_crtdt']));

			// Description.
			$description = template::formatComment($i['com_message']);

			// guid.
			$guid = md5(self::$_galleryURL . $link . $pub_date);

			// XML.
			$rss .= '<item>' . "\n";
			$rss .= '<title>' . $title . '</title>' . "\n";
			$rss .= '<link>' . $link . '</link>' . "\n";
			$rss .= '<pubDate>' . $pub_date . '</pubDate>' . "\n";
			$rss .= '<description><![CDATA[' . $description . ']]></description>' . "\n";
			$rss .= '<guid isPermaLink="false">md5:' . $guid . '</guid>' . "\n";
			$rss .= '</item>' . "\n";
		}

		return $rss;
	}

	/**
	 * Fabrique les éléments du flux RSS des albums.
	 *
	 * @return void
	 */
	private static function _printRSSAlbums()
	{
		$rss = '';

		foreach (self::$_items as &$i)
		{
			// Titre.
			$title = utils::tplProtect(
				utils::getLocale($i['cat_name'])
			);

			// Lien.
			$link = GALLERY_HOST . utils::genURL('album/' . $i['cat_id']
				. '-' . $i['cat_url']);

			// Date de publication.
			$pub_date = date(self::$_RFC822, strtotime($i['cat_lastadddt']));

			// Emplacement de la vignette.
			// Emplacement de la vignette.
			if (CONF_THUMBS_PROTECT)
			{
				$description = '';
			}
			else
			{
				if ($i['thumb_id'] == 0)
				{
					$ext = explode('.', $i['tb_infos']);
					$thumb = img::filepath('tb_cat',
						'i.' . $ext[6], $i['cat_id'], $i['cat_crtdt']);
					if (file_exists(GALLERY_ROOT . '/' . $thumb))
					{
						$thumb = self::$_galleryURL . '/' . $thumb;
					}
					else
					{
						$e = img::filepath('im_external',
							'i.' . $ext[6], $i['cat_id'], $i['cat_crtdt']);
						$thumb = self::$_galleryURL . '/thumb.php?e=' . basename($e)
							. '&t=' . basename($thumb)
							. '&k=' . md5(basename($e) . '|e|'
							. CONF_KEY . '|' . basename($thumb));
					}
				}
				else
				{
					$thumb = img::filepath('tb_cat',
						$i['image_path'], $i['cat_id'], $i['cat_crtdt']);
					$thumb = (file_exists(GALLERY_ROOT . '/' . $thumb))
						? self::$_galleryURL . '/' . $thumb
						: self::$_galleryURL . '/thumb.php?c=' . $i['image_path']
							. '&t=' . basename($thumb)
							. '&k=' . md5($i['image_path'] . '|c|'
							. CONF_KEY . '|' . basename($thumb));
				}
				$thumb = utils::tplProtect($thumb);

				// Description.
				$filename = utils::tplProtect(basename($i['cat_path']));
				$size = img::getThumbSize($i, 'cat');
				$description = '<a href="' . $link . '"><img alt="' . $filename
					. '" width="' . $size['width'] . '" height="' . $size['height']
					. '" src="' . $thumb . '" /></a>';
				if (!utils::isEmpty($i['cat_desc']))
				{
					$description .= '<br /><br />' . nl2br(utils::tplHTMLFilter(
						utils::getLocale($i['cat_desc'])
					));
				}
			}

			// guid.
			$guid = md5(self::$_galleryURL . $link . $pub_date);

			// XML.
			$rss .= '<item>' . "\n";
			$rss .= '<title>' . $title . '</title>' . "\n";
			$rss .= '<link>' . $link . '</link>' . "\n";
			$rss .= '<pubDate>' . $pub_date . '</pubDate>' . "\n";
			$rss .= '<description><![CDATA[' . $description . ']]></description>' . "\n";
			$rss .= '<guid isPermaLink="false">md5:' . $guid . '</guid>' . "\n";
			$rss .= '</item>' . "\n";
		}

		return $rss;
	}

	/**
	 * Fabrique les éléments du flux RSS des images.
	 *
	 * @return void
	 */
	private static function _printRSSImages()
	{
		$rss = '';

		foreach (self::$_items as &$i)
		{
			// Titre.
			$title = utils::tplProtect(
				utils::getLocale($i['image_name']) . ' - ' . utils::getLocale($i['cat_name'])
			);

			// Lien.
			$link = GALLERY_HOST . utils::genURL('image/' . $i['image_id']
				. '-' . $i['image_url']);

			// Date de publication.
			$pub_date = date(self::$_RFC822, strtotime($i['image_adddt']));

			// Emplacement de la vignette.
			if (CONF_THUMBS_PROTECT)
			{
				$description = '';
			}
			else
			{
				$thumb = img::filepath('tb_img',
					$i['image_path'], $i['image_id'], $i['image_adddt']);
				$thumb = (file_exists(GALLERY_ROOT . '/' . $thumb))
					? self::$_galleryURL . '/' . $thumb
					: self::$_galleryURL . '/thumb.php?i=' . $i['image_path']
						. '&t=' . basename($thumb)
						. '&k=' . md5($i['image_path'] . '|i|'
						. CONF_KEY . '|' . basename($thumb));
				$thumb = utils::tplProtect($thumb);

				// Description.
				$filename = utils::tplProtect(basename($i['image_path']));
				$size = img::getThumbSize($i, 'img');
				$description = '<a href="' . $link . '"><img alt="' . $filename
					. '" width="' . $size['width'] . '" height="' . $size['height']
					. '" src="' . $thumb . '" /></a>';
				if (!utils::isEmpty($i['image_desc']))
				{
					$description .= '<br /><br />' . nl2br(utils::tplHTMLFilter(
						utils::getLocale($i['image_desc'])
					));
				}
			}

			// guid.
			$guid = md5(self::$_galleryURL . $link . $pub_date);

			// XML.
			$rss .= '<item>' . "\n";
			$rss .= '<title>' . $title . '</title>' . "\n";
			$rss .= '<link>' . $link . '</link>' . "\n";
			$rss .= '<pubDate>' . $pub_date . '</pubDate>' . "\n";
			$rss .= '<description><![CDATA[' . $description . ']]></description>' . "\n";
			$rss .= '<guid isPermaLink="false">md5:' . $guid . '</guid>' . "\n";
			$rss .= '</item>' . "\n";
		}

		return $rss;
	}
}
?>