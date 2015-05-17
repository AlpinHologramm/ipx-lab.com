<?php
/**
 * Affiche une image.
 *
 * @license http://www.gnu.org/licenses/gpl.html
 * @link http://www.igalerie.org/
 */

if (!isset($_GET['id']) || !preg_match('`^\d{1,12}$`', $_GET['id']))
{
	die('[' . __LINE__ . '] You are not allowed here.');
}

$gets = array('id', 'nohits');
require_once(dirname(__FILE__) . '/includes/prepend.php');

$object_infos = array(
	'admin' => FALSE,
	'sql' => NULL,
	'type' => 'image'
);
require_once(dirname(__FILE__) . '/includes/object_infos.php');

// Cookie des préférences utilisateur.
utils::$cookiePrefs = new cookie('igal_prefs', 315360000, CONF_GALLERY_PATH);

// On interdit l'accès à l'image si l'image est redimensionnée
// et que l'utilisateur n'a pas la permission d'accès à l'image originale.
if (utils::$config['users'] && !$object_infos['user_perms']['gallery']['perms']['image_original'])
{
	$image_width = $object_infos['result']['image_width'];
	$image_height = $object_infos['result']['image_height'];
	if (img::imageSize($image_width, $image_height)
	&& (!utils::$config['diaporama'] || (utils::$config['diaporama']
	&& ($image_width > utils::$config['diaporama_resize_gd_width']
	|| $image_height > utils::$config['diaporama_resize_gd_height']))))
	{
		die('[' . __LINE__ . '] You are not allowed here.');
	}
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

// Chemin de l'image.
$file_path = $object_infos['file_path'];

// Filigrane.
if (($watermark_params = watermark::getParams($image_infos)) !== FALSE)
{
	$str = md5(serialize($watermark_params) . '|' . $image_infos['image_adddt']);
	$file_path = GALLERY_ROOT . '/' . img::filepath('im_watermark',
		$object_infos['file_path'], $image_infos['image_id'], $str);

	// Si l'image filigranée n'existe pas, on la crée.
	if (!file_exists($file_path))
	{
		$watermark = new watermark($object_infos['file_path'], $watermark_params);

		img::gdCreateFile($watermark->gdImage, $file_path,
			$watermark->filetype, $watermark_params['quality']);
	}
}

// Écriture des données du cookie des préférences.
utils::$cookiePrefs->write();

// En-têtes.
utils::headersNoCache();

// On affiche ou télécharge l'image.
$content_type = (isset($download_image))
	? 'application/octet-stream'
	: $object_infos['mime_type'];
$content_disposition = (isset($download_image))
	? 'attachment'
	: 'inline';
img::displayFile($file_path, $content_type,
basename($object_infos['file_path']), $content_disposition);

?>