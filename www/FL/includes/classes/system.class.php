<?php
/**
 * Informations système.
 *
 * @license http://www.gnu.org/licenses/gpl.html
 * @link http://www.igalerie.org/
 */
class system
{
	/**
	 * Numéro de version de la galerie.
	 *
	 * @var string
	 */
	public static $galleryVersion = '2.3.4';

	/**
	 * Date de version de la galerie.
	 *
	 * @var string
	 */
	public static $galleryVersionDate = '20140501';

	/**
	 * Version minimum de GD.
	 *
	 * @var string
	 */
	public static $minGDVersion = '2.0';

	/**
	 * Version minimum de MySQL.
	 *
	 * @var string
	 */
	public static $minMySQLVersion = '4.1.2';

	/**
	 * Version minimum de PHP.
	 *
	 * @var string
	 */
	public static $minPHPVersion = '5.2';

	/**
	 * Extensions PHP utilisées par iGalerie.
	 *
	 * @var array
	 */
	public static $phpExtensions = array(
		'exif' => FALSE,
		'gd' => FALSE,
		'iconv' => FALSE,
		'mbstring' => FALSE,
		'pcre' => FALSE,
		'PDO' => FALSE,
		'pdo_mysql' => FALSE,
		'SimpleXML' => FALSE,
		'zip' => FALSE,
		'zlib' => FALSE
	);

	/**
	 * Fonctions utilisées par iGalerie et susceptibles d'être désactivées
	 * par la configuration de PHP.
	 *
	 * @var array
	 */
	public static $phpFunctions = array(
		'chmod' => FALSE,
		'fsockopen' => FALSE,
		'gd_info' => FALSE,
		'get_loaded_extensions' => FALSE,
		'ini_get' => FALSE,
		'ini_set' => FALSE,
		'ignore_user_abort' => FALSE,
		'imagealphablending' => FALSE,
		'imagecolorallocatealpha' => FALSE,
		'imagecolortransparent' => FALSE,
		'imagerotate' => FALSE,
		'imagettfbbox' => FALSE,
		'imagetypes' => FALSE,
		'mail' => FALSE,
		'php_uname' => FALSE,
		'rmdir' => FALSE,
		'set_magic_quotes_runtime' => FALSE,
		'set_time_limit' => FALSE,
		'setlocale' => FALSE,
		'usleep' => FALSE
	);

	/**
	 * Fichiers et répertoires qui doivent être accessibles en écriture.
	 *
	 * @var array
	 */
	public static $writePermissions = array(
		CONF_ALBUMS_DIR => array(FALSE, ''),
		'cache/im_backup' => array(FALSE, ''),
		'cache/im_diaporama' => array(FALSE, ''),
		'cache/im_diaporama_watermark' => array(FALSE, ''),
		'cache/im_edit' => array(FALSE, ''),
		'cache/im_external' => array(FALSE, ''),
		'cache/im_watermark' => array(FALSE, ''),
		'cache/im_resize' => array(FALSE, ''),
		'cache/im_resize_watermark' => array(FALSE, ''),
		'cache/tb_cat' => array(FALSE, ''),
		'cache/tb_img' => array(FALSE, ''),
		'cache/tb_wid' => array(FALSE, ''),
		'cache/up_temp' => array(FALSE, ''),
		'config' => array(FALSE, ''),
		'config/conf.php' => array(FALSE, ''),
		'errors' => array(FALSE, ''),
		'users/avatars' => array(FALSE, ''),
		'users/uploads' => array(FALSE, '')
	);



	/**
	 * Directives PHP importantes.
	 *
	 * @var array
	 */
	private static $_phpDirectives = array(
		'disable_classes' => array('val'),
		'disable_functions' => array('val'),
		'display_errors' => array('bin', 'Off'),
		'file_uploads' => array('bin', 'On'),
		'magic_quotes_gpc' => array('bin', 'Off'),
		'magic_quotes_runtime' => array('bin', 'Off'),
		'magic_quotes_sybase' => array('bin', 'Off'),
		'max_execution_time' => array('val'),
		'memory_limit' => array('val'),
		'open_basedir' => array('val'),
		'post_max_size' => array('val'),
		'safe_mode' => array('bin', 'Off'),
		'upload_max_filesize' => array('val'),
		'zlib.output_compression' => array('bin')
	);



	/**
	 * Retourne la version de GD.
	 *
	 * @param boolean $details
	 *	Doit-on retourner la description complète ?
	 * @return string
	 *	Version de GD.
	 */
	public static function getGDVersion($details = FALSE)
	{
		$infos = img::gdInfos();

		if ($infos === FALSE)
		{
			return '?';
		}

		return ($details)
			? $infos['GD Version']
			: preg_replace('`[^\d.]`', '', $infos['GD Version']);
	}

	/**
	 * Retourne les variables système de MySQL.
	 *
	 * @return array
	 */
	public static function getMySQLSystemVariables()
	{
		static $variables;

		if ($variables === NULL)
		{
			$fetch_style = array('column' => array('Variable_name', 'Value'));
			if (utils::$db->query('SHOW VARIABLES', $fetch_style) !== FALSE)
			{
				$variables = utils::$db->queryResult;
			}
		}

		return $variables;
	}

	/**
	 * Retourne la version de MySQL.
	 *
	 * @param boolean $details
	 *	Doit-on retourner la description complète ?
	 * @return string
	 */
	public static function getMySQLVersion($details = FALSE)
	{
		static $version;

		if ($version === NULL)
		{
			$version = (utils::$db->query('SELECT VERSION()', 'value'))
				? utils::$db->queryResult
				: '?';
		}

		return ($details)
			? $version
			: preg_replace('`[^\d.]`', '', $version);
	}

	/**
	 * Retourne le nom du système d'exploitation.
	 *
	 * @param boolean $details
	 *	Doit-on retourner une description détaillée ?
	 * @return string
	 */
	public static function getOS($details = FALSE)
	{
		if (!defined('PHP_OS'))
		{
			return '?';
		}

		return ($details && function_exists('php_uname'))
			? PHP_OS . ' (' . self::getOSDetails('sr') . ')'
			: PHP_OS;
	}

	/**
	 * Retourne des détails du système d'exploitation.
	 *
	 * @param string $mode
	 * @return string
	 */
	public static function getOSDetails($mode)
	{
		if (!function_exists('php_uname'))
		{
			return '?';
		}

		$infos = '';
		for ($i = 0; $i < strlen($mode); $i++)
		{
			$infos .= php_uname($mode[$i]) . ' ';
		}

		return trim($infos);
	}

	/**
	 * Retourne la liste et la valeur des directives PHP importantes.
	 *
	 * @return array
	 */
	public static function getPHPDirectives()
	{
		$directives_values = array();

		foreach (self::$_phpDirectives as $name => &$params)
		{
			// Valeur.
			if (!function_exists('ini_get'))
			{
				$directives_values[$name]['value'] = '?';
				$directives_values[$name]['status'] = 'info';
				continue;
			}
			else if ($params[0] == 'bin')
			{
				$directives_values[$name]['value'] = (utils::getIniBool($name))
					? 'On'
					: 'Off';
			}
			else
			{
				$directives_values[$name]['value'] = ini_get($name);
			}

			// Valeur recommandée.
			if (!isset($params[1]))
			{
				$directives_values[$name]['status'] = 'info';
			}
			else if (($params[1] == 'On' && $directives_values[$name]['value'] == 'On')
			 || ($params[1] == 'Off' && $directives_values[$name]['value'] == 'Off'))
			{
				$directives_values[$name]['status'] = 'ok';
			}
			else
			{
				$directives_values[$name]['status'] = 'warning';
			}
		}

		return $directives_values;
	}

	/**
	 * Retourne la liste des extensions PHP activées et utilisées par iGalerie.
	 *
	 * @return array
	 */
	public static function getPHPExtensions()
	{
		$loaded_extensions = get_loaded_extensions();

		foreach (self::$phpExtensions as $extension => &$e)
		{
			$e = in_array($extension, $loaded_extensions);
		}

		return self::$phpExtensions;
	}

	/**
	 * Retourne la liste des fonctions utilisées par iGalerie
	 * et susceptibles d'être désactivées par la configuration de PHP.
	 *
	 * @return array
	 */
	public static function getPHPFunctions()
	{
		foreach (self::$phpFunctions as $function => &$e)
		{
			$e = function_exists($function);
		}

		return self::$phpFunctions;
	}

	/**
	 * Retourne le type d'interface utilisé par PHP.
	 *
	 * @return string
	 */
	public static function getPHPSAPI()
	{
		return PHP_SAPI;
	}

	/**
	 * Retourne la version de PHP.
	 *
	 * @param boolean $details
	 *	Doit-on retourner la description complète ?
	 * @return string
	 */
	public static function getPHPVersion($details = FALSE)
	{
		if (!defined('PHP_VERSION'))
		{
			return '?';
		}

		return ($details)
			? PHP_VERSION
			: preg_replace('`[^\d.]`', '', PHP_VERSION);
	}

	/**
	 * Retourne le type de serveur.
	 *
	 * @return string
	 */
	public static function getServerType()
	{
		if (empty($_SERVER['SERVER_SOFTWARE']))
		{
			return '?';
		}

		return strip_tags($_SERVER['SERVER_SOFTWARE']);
	}

	/**
	 * Les fichiers et répertoires importants
	 * sont-ils accessibles en écriture ?
	 *
	 * @return array
	 *	Liste des fichiers et répertoires testés.
	 */
	public static function getWritePermissions()
	{
		foreach (self::$writePermissions as $file => &$i)
		{
			$f = GALLERY_ROOT . '/' . $file;

			if (!file_exists($f))
			{
				$i[0] = FALSE;
				$i[1] = '?';
				continue;
			}

			$i[0] = files::isWritable($f);
			$i[1] = (function_exists('fileperms'))
				? substr(sprintf('%o', fileperms($f)), -4)
				: '????';
		}

		return self::$writePermissions;
	}

	/**
	 * L'application est-elle compatible avec la version actuelle de GD ?
	 *
	 * @return boolean
	 */
	public static function isGDVersionCompatible()
	{
		return version_compare(
			self::getGDVersion(),
			self::$minGDVersion,
			'>='
		);
	}

	/**
	 * L'application est-elle compatible avec la version actuelle de MySQL ?
	 *
	 * @return boolean
	 */
	public static function isMySQLVersionCompatible()
	{
		return version_compare(
			self::getMySQLVersion(),
			self::$minMySQLVersion,
			'>='
		);
	}

	/**
	 * L'application est-elle compatible avec la version actuelle de PHP ?
	 *
	 * @return string
	 */
	public static function isPHPVersionCompatible()
	{
		return version_compare(
			self::getPHPVersion(),
			self::$minPHPVersion,
			'>='
		);
	}
}
?>