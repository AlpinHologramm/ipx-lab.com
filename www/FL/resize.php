<?php
/**
 * Crée et affiche une image redimensionnée.
 *
 * @license http://www.gnu.org/licenses/gpl.html
 * @link http://www.igalerie.org/
 */

if (!isset($_GET['id']) || !preg_match('`^\d{1,12}$`', $_GET['id']))
{
	die('[' . __LINE__ . '] You are not allowed here.');
}
$gets = array('id', 'diaporama', 'nohits');
require_once(dirname(__FILE__) . '/includes/prepend.php');

$object_infos = array(
	'admin' => FALSE,
	'sql' => NULL,
	'type' => 'image'
);
require_once(dirname(__FILE__) . '/includes/object_infos.php');

if (((utils::$config['images_resize'] == 1 && utils::$config['images_resize_method'] == 2
	&& !isset($_GET['diaporama']))
|| (utils::$config['diaporama_resize_gd'] == 1 && isset($_GET['diaporama']))) === FALSE)
{
	die('[' . __LINE__ . '] You are not allowed here.');
}

// Cookie des préférences utilisateur.
utils::$cookiePrefs = new cookie('igal_prefs', 315360000, CONF_GALLERY_PATH);

// Type d'image redimensionnée.
if (isset($_GET['diaporama'])
	&& utils::$config['diaporama_resize_gd_height']
	 . utils::$config['diaporama_resize_gd_width']
	 . utils::$config['diaporama_resize_gd_quality']
	!= utils::$config['images_resize_gd_height']
	 . utils::$config['images_resize_gd_width']
	 . utils::$config['images_resize_gd_quality'])
{
	$config = 'diaporama';
	$type = 'im_diaporama';
}
else
{
	$config = 'images';
	$type = 'im_resize';
}

// Si l'image n'est pas trop grande, on ne la redimensionne pas.
if ($object_infos['result']['image_width'] <= utils::$config[$config . '_resize_gd_width']
 && $object_infos['result']['image_height'] <= utils::$config[$config . '_resize_gd_height'])
{
	die('[' . __LINE__ . '] You are not allowed here.');
}

// Rotation.
$image_infos = img::rotation($object_infos['result']);

// Nombre de visites.
if (!(isset($_GET['nohits']) && $_GET['nohits'] == md5('nohits|' . CONF_KEY . '|' . $_GET['id'])))
{
	$user_infos = (isset($object_infos['user_infos'])) ? $object_infos['user_infos'] : array();
	alb::imageHits($user_infos, $object_infos['result']);
}

// Fermeture de la connexion à la base de données.
utils::$db->connexion = NULL;

// Chemin du fichier de l'image redimensionnée.
$file_path = GALLERY_ROOT . '/' . img::filepath(
	$type, $image_infos['image_path'], $_GET['id'], $image_infos['image_adddt']
);

// Filigrane.
$watermark = FALSE;
if (($watermark_params = watermark::getParams($image_infos)) !== FALSE)
{
	$str = md5(serialize($watermark_params) . '|' . $image_infos['image_adddt']);
	$file_path = GALLERY_ROOT . '/' . img::filepath(
		$type . '_watermark', $object_infos['file_path'], $image_infos['image_id'], $str
	);

	$watermark = TRUE;
}

// Si l'image redimensionnée n'existe pas, on la crée.
if (!file_exists($file_path))
{
	$i = img::getImageSize($object_infos['file_path']);
	$resize = img::imageResize($i['width'], $i['height'],
		utils::$config[$config . '_resize_gd_width'],
		utils::$config[$config . '_resize_gd_height']);

	$image = img::gdCreateImage($object_infos['file_path'], $i['filetype']);
	if (is_string($image))
	{
		die($image);
	}
	if (is_bool($image))
	{
		die(sprintf('Cannot create image (%s).', $i['filetype']));
	}

	// On désactive la gestion de la transparence si
	// un texte de filigrane doit être ajouté sur une image JPEG.
	if ($watermark && $watermark_params['text_active']
	&& $watermark_params['text'] !== '' && $i['filetype'] == 2)
	{
		img::$gdTransparency = FALSE;
	}

	$image = img::gdResize($image, 0, 0,
		$i['width'], $i['height'], 0, 0, $resize['width'], $resize['height']);

	// Enregistrement de l'image dans un fichier.
	if ($watermark)
	{
		$watermark = new watermark($image, $watermark_params);

		img::gdCreateFile($watermark->gdImage, $file_path,
			$i['filetype'], $watermark_params['quality']);
	}
	else
	{
		img::gdCreateFile($image, $file_path, $i['filetype'],
			utils::$config[$config . '_resize_gd_quality']);
	}
}

// Écriture des données du cookie des préférences.
utils::$cookiePrefs->write();

// On affiche l'image.
utils::headersNoCache();
img::displayFile($file_path, $object_infos['mime_type'],
	preg_replace('`(\.[a-z]{3,4})$`', '_resize$1', basename($object_infos['file_path'])));
?>