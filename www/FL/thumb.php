<?php
/**
 * Création des vignettes.
 *
 * @license http://www.gnu.org/licenses/gpl.html
 * @link http://www.igalerie.org/
 */

$gets = array('i', 'c', 'e', 'w', 'p', 't', 'k', 's');
require_once(dirname(__FILE__) . '/includes/prepend.php');

if (!isset($_GET['t']) || !preg_match('`^[a-f0-9]{32}\.(?:gif|jpe?g|png)$`i', $_GET['t'])
 || !isset($_GET['k']) || !preg_match('`^[a-f0-9]{32}$`i', $_GET['k']))
{
	die('[' . __LINE__ . '] You are not allowed here.');
}

// Protection des vignettes aux accès direct.
if (CONF_THUMBS_PROTECT)
{
	if (!isset($_GET['s']))
	{
		die('[' . __LINE__ . '] You are not allowed here.');
	}

	// Identifiant de session.
	utils::$cookieSession = new cookie('igal_session', 8640000, CONF_GALLERY_PATH);
	$session = utils::$cookieSession->read('token');

	// Vérification.
	if (md5($_GET['k'] . '|s|' . CONF_KEY . '|' . $session) != $_GET['s'])
	{
		die('[' . __LINE__ . '] You are not allowed here.');
	}
}

// Types de vignettes.
// La vérification avec md5() et CONF_KEY sert à éviter
// les manipulations malicieuses des vignettes.
if (isset($_GET['i'])
&& md5($_GET['i'] . '|i|' . CONF_KEY . '|' . $_GET['t']) == $_GET['k'])
{
	$img_file = GALLERY_ROOT . '/' . CONF_ALBUMS_DIR . '/' . $_GET['i'];
	$tb_file = GALLERY_ROOT . '/cache/tb_img/' . $_GET['t'];
	$conf = img::thumbConf('img');
	$thumbs_quality = CONF_THUMBS_IMG_QUALITY;
}
else if (isset($_GET['c'])
&& md5($_GET['c'] . '|c|' . CONF_KEY . '|' . $_GET['t']) == $_GET['k'])
{
	$img_file = GALLERY_ROOT . '/' . CONF_ALBUMS_DIR . '/' . $_GET['c'];
	$tb_file = GALLERY_ROOT . '/cache/tb_cat/' . $_GET['t'];
	$conf = img::thumbConf('cat');
	$thumbs_quality = CONF_THUMBS_CAT_QUALITY;
}
else if (isset($_GET['e'])
&& md5($_GET['e'] . '|e|' . CONF_KEY . '|' . $_GET['t']) == $_GET['k'])
{
	$img_file = GALLERY_ROOT . '/cache/im_external/' . $_GET['e'];
	$tb_file = GALLERY_ROOT . '/cache/tb_cat/' . $_GET['t'];
	$conf = img::thumbConf('cat');
	$thumbs_quality = CONF_THUMBS_CAT_QUALITY;
}
else if (isset($_GET['w'])
&& md5($_GET['w'] . '|w|' . CONF_KEY . '|' . $_GET['t']) == $_GET['k'])
{
	$img_file = GALLERY_ROOT . '/' . CONF_ALBUMS_DIR . '/' . $_GET['w'];
	$tb_file = GALLERY_ROOT . '/cache/tb_wid/' . $_GET['t'];
	$conf = img::thumbConf('wid');
	$thumbs_quality = CONF_THUMBS_WID_QUALITY;
}
else if (isset($_GET['p'])
&& md5($_GET['p'] . '|p|' . CONF_KEY . '|' . $_GET['t']) == $_GET['k'])
{
	$img_file = GALLERY_ROOT . '/users/uploads/' . $_GET['p'];
	$tb_file = GALLERY_ROOT . '/cache/tb_img/' . $_GET['t'];
	$conf = img::thumbConf('img');
	$thumbs_quality = CONF_THUMBS_IMG_QUALITY;
}

if (empty($tb_file))
{
	die('Invalid filename.');
}

// Si la vignette n'existe pas, on la crée.
if (!file_exists($tb_file))
{
	// On vérifie la présence de GD.
	if (!img::gdActive())
	{
		die('GD is not activated.');
	}

	// On vérifie l'existence du fichier.
	if (!file_exists($img_file))
	{
		die('File does not exist.');
	}

	$i = img::getImageSize($img_file);
	$src_img = img::gdCreateImage($img_file, $i['filetype']);
	if (is_string($src_img))
	{
		die($src_img);
	}
	if (is_bool($src_img))
	{
		$message = 'Cannot create image (%s).';
		die(sprintf($message, $i['filetype']));
	}

	// Redimensionnement.
	if ($conf['mode'] == 'crop')
	{
		if ($i['width'] <= $conf['crop_width'] && $i['height'] <= $conf['crop_height'])
		{
			$resize = array(
				'h' => $i['height'],
				'w' => $i['width'],
				'x' => 0,
				'y' => 0
			);
		}
		else
		{
			$resize = img::resizeCrop($i['width'], $i['height'],
				$conf['crop_width'], $conf['crop_height']);
		}
		$dst_img = img::gdResize($src_img, $resize['x'], $resize['y'],
			$resize['w'], $resize['h'], 0, 0, $conf['crop_width'], $conf['crop_height']);
	}
	else
	{
		if ($i['width'] <= $conf['width'] && $i['height'] <= $conf['height'])
		{
			$resize = array(
				'height' => $i['height'],
				'width' => $i['width']
			);
		}
		else
		{
			$resize = img::resizeProp($i['width'], $i['height'],
				$conf['width'], $conf['height']);
		}
		$dst_img = img::gdResize($src_img, 0, 0,
			$i['width'], $i['height'], 0, 0, $resize['width'], $resize['height']);
	}

	// Création et affichage du fichier.
	img::gdCreateFile($dst_img, $tb_file, $i['filetype'], $thumbs_quality);
}

// On affiche la vignette.
utils::headersNoCache();
img::displayFile($tb_file);
?>