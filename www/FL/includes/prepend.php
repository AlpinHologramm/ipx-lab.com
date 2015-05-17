<?php
/**
 * Paramètres communs à toute l'application.
 *
 * @license http://www.gnu.org/licenses/gpl.html
 * @link http://www.igalerie.org/
 */

if (!function_exists('version_compare') || !defined('PHP_VERSION')
 || !version_compare(PHP_VERSION, 5.2, '>='))
{
	die('PHP 5.2 or greater is required.');
}

define('GALLERY_ROOT', dirname(dirname(__FILE__)));
define('GALLERY_HTTPS', (isset($_SERVER['HTTPS'])
	&& (strtolower($_SERVER['HTTPS']) == 'on' || $_SERVER['HTTPS'] == 1)));
define('GALLERY_HOST', (GALLERY_HTTPS ? 'https://' : 'http://') . $_SERVER['HTTP_HOST']);

// Chargement du fichier de configuration.
if (file_exists(GALLERY_ROOT . '/config/conf.php'))
{
	require_once(GALLERY_ROOT . '/config/conf.php');
}
else
{
	require_once(GALLERY_ROOT . '/config/conf_default.php');
}

// Chargement des classes.
function __autoload($class)
{
	require_once(GALLERY_ROOT . '/includes/classes/' . $class . '.class.php');
}

// Gestion des erreurs et de la mémoire.
error_reporting(-1);
if (function_exists('ini_set'))
{
	//ini_set('memory_limit', '128M');
	ini_set('display_errors', CONF_ERRORS_DISPLAY ? 1 : 0);
}
set_error_handler(array('error', 'phpError'));
set_exception_handler(array('error', 'exception'));

// Gestion du temps.
define('START_TIME', microtime(TRUE));
if (function_exists('ignore_user_abort'))
{
	ignore_user_abort(TRUE);
}
if (function_exists('set_time_limit'))
{
	//set_time_limit(30);
}
date_default_timezone_set(CONF_DEFAULT_TZ);

// Annulation de la fonction magic_quotes_gpc.
function strip_magic_quotes(&$valeur)
{
	$valeur = stripslashes($valeur);
}
if (get_magic_quotes_gpc())
{
	array_walk_recursive($_GET, 'strip_magic_quotes');
	array_walk_recursive($_POST, 'strip_magic_quotes');
	array_walk_recursive($_COOKIE, 'strip_magic_quotes');
	array_walk_recursive($_REQUEST, 'strip_magic_quotes');
}

// Désactivation de la fonction magic_quotes_runtime.
if (get_magic_quotes_runtime() && function_exists('set_magic_quotes_runtime'))
{
	set_magic_quotes_runtime(0);
}

// Supression de tout paramètre GET non utilisé.
$gets = (isset($gets)) ? $gets : array('q');
foreach ($_GET as $name => $value)
{
	if (!in_array($name, $gets))
	{
		unset($_GET[$name]);
	}
}
unset($_REQUEST);

// Jeu de caractères pour les fonctions mbstring.
mb_internal_encoding(CONF_CHARSET);
mb_regex_encoding(CONF_CHARSET);

// Compression de la page.
if (!CONF_DEBUG && extension_loaded('zlib') && !utils::getIniBool('zlib.output_compression'))
{
	ob_start('ob_gzhandler');
}

if (empty($no_header))
{
	header('Content-Type: text/html; charset=' . CONF_CHARSET);
}



/**
 * Localisation.
 *
 * @param string $str
 * @global array $L10N
 * @return string
 */
function __($str)
{
	global $L10N;

	if (isset($L10N[$str]))
	{
		$L10N[$str] = trim($L10N[$str]);
		return ($L10N[$str] == '')
			? $str
			: $L10N[$str];
	}
	else
	{
		if (CONF_DEBUG)
		{
			trigger_error("Localization not found: \"$str\"", E_USER_NOTICE);
		}
		return $str;
	}
}

/**
 * Extraction des paramètres de la requête.
 *
 * @param array $params
 * @return void
 */
function extract_request($params)
{
	if (empty($_GET['q']))
	{
		return;
	}

	// Sécurité : supprime les caractères spéciaux sensibles.
	$_GET['q'] = str_replace(chr(0), '', $_GET['q']);
	$_GET['q'] = str_replace(array('<', '>', '&', '"', "'", "\\"), '?', $_GET['q']);
	$_GET['q'] = preg_replace("`[\n\r\s\t]+`", '_', $_GET['q']);

	// Détermine la section.
	preg_match('`^([^/]+)(?:/(.*))?$`', $_GET['q'], $m_q);
	if (!isset($params[$m_q[1]]) || !is_array($params[$m_q[1]]))
	{
		return;
	}
	$_GET['section'] = $m_q[1];
	if (!isset($m_q[2]) && empty($params[$m_q[1]]))
	{
		return;
	}

	// Vérifie la requête par regexp.
	$q2 = isset($m_q[2]) ? $m_q[2] : '';
	foreach ($params[$m_q[1]] as $regexp => &$gets)
	{
		if (preg_match('`^' . $regexp . '$`', $q2, $m))
		{
			array_shift($m);
			for ($i = 0, $count = count($m); $i < $count; $i++)
			{
				$_GET[$gets[$i]] = $m[$i];
			}
			return;
		}
	}

	// Si la page demandée n'existe pas, on supprime tout.
	$_GET = array();
}
?>