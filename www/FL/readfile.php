<?php
/**
 * Lit une image de façon sécurisée.
 *
 * @license http://www.gnu.org/licenses/gpl.html
 * @link http://www.igalerie.org/
 */

if (!isset($_GET['f']) || !preg_match('`^[-a-z0-9_/]{1,80}(\.(?:gif|jpg|png))?$`i', $_GET['f'])
 || !isset($_GET['s']) || !preg_match('`^[a-f0-9]{32}$`i', $_GET['s']))
{
	die('[' . __LINE__ . '] You are not allowed here.');
}

$gets = array('f', 's');
require_once(dirname(__FILE__) . '/includes/prepend.php');

// Identifiant de session.
utils::$cookieSession = new cookie('igal_session', 8640000, CONF_GALLERY_PATH);
$session = utils::$cookieSession->read('token');

// Vérification.
if (md5($_GET['f'] . '|f|' . CONF_KEY . '|s|' . $session) != $_GET['s'])
{
	die('[' . __LINE__ . '] You are not allowed here.');
}

// Récupération du type mime de l'image.
$file = GALLERY_ROOT . '/' . $_GET['f'];
$i = img::getImageSize($file);
if (!is_array($i) || !in_array($i['filetype'], array(1, 2, 3)))
{
	die('[' . __LINE__ . '] You are not allowed here.');
}

// Affichage de l'image.
img::displayFile($file, $i['mime'], basename($file));
?>