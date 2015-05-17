<?php
/**
 * Affiche une image avec les permissions admin.
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

// Image en attente.
if (isset($_GET['type']) && $_GET['type'] == 'pending')
{
	$object_infos = array(
		'admin' => TRUE,
		'sql' => 'SELECT up_file
					FROM ' . CONF_DB_PREF . 'uploads
			   LEFT JOIN ' . CONF_DB_PREF . 'categories USING (cat_id)
				   WHERE up_id = ' . (int) $_GET['id'],
		'type' => NULL
	);
}

// Image de la galerie.
else
{
	$object_infos = array(
		'admin' => TRUE,
		'sql' => NULL,
		'type' => 'image'
	);
}

require_once(dirname(__FILE__) . '/../includes/object_infos.php');

// Image en attente.
if (isset($_GET['type']) && $_GET['type'] == 'pending')
{
	$file = $object_infos['result']['up_file'];
	$ext = preg_replace('`^.+(\.[^\.]+)$`', '$1', $file);
	$file = utils::hashImages($file) . $ext;
	$object_infos['file_path'] = GALLERY_ROOT . '/users/uploads/' . $file;
	$object_infos['mime_type'] = img::getMimeType($ext);
}

// On affiche l'image.
utils::headersNoCache();
img::displayFile($object_infos['file_path'], $object_infos['mime_type']);
?>