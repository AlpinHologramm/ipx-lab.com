<?php
/**
 * Création des images redimensionnées, aperçus pour
 * la page d'édition des images et des vignettes.
 *
 * @license http://www.gnu.org/licenses/gpl.html
 * @link http://www.igalerie.org/
 */

if (!isset($_GET['id']) || !preg_match('`^\d{1,12}$`', $_GET['id']))
{
	die('You are not allowed here.');
}
$gets = array('id', 'type');
require_once(dirname(__FILE__) . '/../includes/prepend.php');

$object_infos = array(
	'admin' => TRUE,
	'sql' => NULL,
	'type' => 'image'
);

// Image externe.
if (isset($_GET['type']) && $_GET['type'] == 'external')
{
	$object_infos['sql']
		= 'SELECT cat_crtdt,
				  cat_tb_infos
			 FROM ' . CONF_DB_PREF . 'categories
			WHERE cat_id = ' . (int) $_GET['id'] . '
			  AND thumb_id = "0"';
	$object_infos['type'] = NULL;
}

require_once(dirname(__FILE__) . '/../includes/object_infos.php');

// Image externe.
if (isset($_GET['type']) && $_GET['type'] == 'external')
{
	$ext = explode('.', $object_infos['result']['cat_tb_infos']);
	$img_resize_file = GALLERY_ROOT . '/' . img::filepath(
		'im_edit', 'i.' . $ext[6], $_GET['id'], $object_infos['result']['cat_crtdt']
	);
	$object_infos['file_path'] = GALLERY_ROOT . '/' . img::filepath(
		'im_external', 'i.' . $ext[6], $_GET['id'], $object_infos['result']['cat_crtdt']
	);
	if (!file_exists($object_infos['file_path']))
	{
		die('File does not exist.');
	}
	$object_infos['mime_type'] = img::getMimeType($ext[6]);
}

// Image "normale".
else
{
	$img_resize_file = GALLERY_ROOT . '/' . img::filepath(
		'im_edit', $object_infos['result']['image_path'], $_GET['id'], $object_infos['result']['image_adddt']
	);
}

// Si l'image redimensionnée n'existe pas, on la crée.
if (!file_exists($img_resize_file))
{
	$i = img::getImageSize($object_infos['file_path']);
	$resize = img::imageResize($i['width'], $i['height'], 400, 400);

	$src_img = img::gdCreateImage($object_infos['file_path'], $i['filetype']);
	if (is_string($src_img))
	{
		die($src_img);
	}
	if (is_bool($src_img))
	{
		die(sprintf('Cannot create image (with: %s).', $i['filetype']));
	}
	$dst_img = img::gdResize($src_img, 0, 0,
		$i['width'], $i['height'], 0, 0, $resize['width'], $resize['height']);

	img::gdCreateFile($dst_img, $img_resize_file, $i['filetype'], 80);
}

// On affiche l'image.
utils::headersNoCache();
img::displayFile($img_resize_file, $object_infos['mime_type']);
?>