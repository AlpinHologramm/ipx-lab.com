<?php
/**
 * Gestionnaire de fichiers.
 *
 * @license http://www.gnu.org/licenses/gpl.html
 * @link http://www.igalerie.org/
 */

class files
{
	/**
	 * Paramètres de configuration du fichier config/conf.php
	 * associés aux valeurs possibles qu'ils peuvent prendre.
	 *
	 * @var array
	 */
	private static $_configParams = array
	(
		'CONF_DB_USER' => array(
			'regexp' => '.+',
			'type' => 'string'
		),
		'CONF_DB_PASS' => array(
			'regexp' => '.*',
			'type' => 'string'
		),
		'CONF_DB_DSN' => array(
			'regexp' => '.+',
			'type' => 'string'
		),
		'CONF_DB_PREF' => array(
			'regexp' => '[a-z0-9_]{0,32}',
			'type' => 'string'
		),
		'CONF_GD_TRANSPARENCY' => array(
			'regexp' => 'bin',
			'type' => 'int'
		),
		'CONF_INSTALL' => array(
			'regexp' => 'bin',
			'type' => 'int'
		),
		'CONF_INTEGRATED' => array(
			'regexp' => 'bin',
			'type' => 'int'
		),
		'CONF_DEBUG' => array(
			'regexp' => 'bin',
			'type' => 'int'
		),
		'CONF_DEFAULT_LANG' => array(
			'regexp' => '[a-z_]{5}',
			'type' => 'string'
		),
		'CONF_DEFAULT_TZ' => array(
			'regexp' => '[-_a-z/]{1,32}',
			'type' => 'string'
		),
		'CONF_ERRORS_DISPLAY' => array(
			'regexp' => 'bin',
			'type' => 'int'
		),
		'CONF_ERRORS_DISPLAY_TRACE' => array(
			'regexp' => 'bin',
			'type' => 'int'
		),
		'CONF_ERRORS_DISPLAY_NOW' => array(
			'regexp' => 'bin',
			'type' => 'int'
		),
		'CONF_ERRORS_TRACE_ARGS' => array(
			'regexp' => 'bin',
			'type' => 'int'
		),
		'CONF_ERRORS_LOG' => array(
			'regexp' => 'bin',
			'type' => 'int'
		),
		'CONF_ERRORS_LOG_MAX' => array(
			'regexp' => '\d{1,5}',
			'type' => 'int'
		),
		'CONF_ERRORS_MAIL' => array(
			'regexp' => 'bin',
			'type' => 'int'
		),
		'CONF_GALLERY_PATH' => array(
			'regexp' => '.+',
			'type' => 'string'
		),
		'CONF_KEY' => array(
			'regexp' => '[a-z-0-9]{40}',
			'type' => 'string'
		),
		'CONF_SMTP_MAIL' => array(
			'regexp' => 'bin',
			'type' => 'string'
		),
		'CONF_SMTP_SERV' => array(
			'regexp' => '[-_\.a-z0-9/]{0,255}',
			'type' => 'string'
		),
		'CONF_SMTP_PORT' => array(
			'regexp' => '\d{1,5}',
			'type' => 'int'
		),
		'CONF_SMTP_AUTH' => array(
			'regexp' => 'bin',
			'type' => 'int'
		),
		'CONF_SMTP_USER' => array(
			'regexp' => '.*',
			'type' => 'string'
		),
		'CONF_SMTP_PASS' => array(
			'regexp' => '.*',
			'type' => 'string'
		),
		'CONF_THUMBS_CAT_METHOD' => array(
			'regexp' => '[cp]rop',
			'type' => 'string'
		),
		'CONF_THUMBS_CAT_WIDTH' => array(
			'regexp' => '(?:[5-9]\d|[1-2]\d{2}|300)',
			'type' => 'int'
		),
		'CONF_THUMBS_CAT_HEIGHT' => array(
			'regexp' => '(?:[5-9]\d|[1-2]\d{2}|300)',
			'type' => 'int'
		),
		'CONF_THUMBS_CAT_SIZE' => array(
			'regexp' => '(?:[5-9]\d|[1-2]\d{2}|300)',
			'type' => 'int'
		),
		'CONF_THUMBS_CAT_QUALITY' => array(
			'regexp' => '(?:\d|[1-9]\d|100)',
			'type' => 'int'
		),
		'CONF_THUMBS_DIR' => array(
			'regexp' => '[a-z0-9_-]{1,99}',
			'type' => 'string'
		),
		'CONF_THUMBS_IMG_METHOD' => array(
			'regexp' => '[cp]rop',
			'type' => 'string'
		),
		'CONF_THUMBS_IMG_WIDTH' => array(
			'regexp' => '(?:[5-9]\d|[1-2]\d{2}|300)',
			'type' => 'int'
		),
		'CONF_THUMBS_IMG_HEIGHT' => array(
			'regexp' => '(?:[5-9]\d|[1-2]\d{2}|300)',
			'type' => 'int'
		),
		'CONF_THUMBS_IMG_SIZE' => array(
			'regexp' => '(?:[5-9]\d|[1-2]\d{2}|300)',
			'type' => 'int'
		),
		'CONF_THUMBS_IMG_QUALITY' => array(
			'regexp' => '(?:\d|[1-9]\d|100)',
			'type' => 'int'
		),
		'CONF_THUMBS_PROTECT' => array(
			'regexp' => 'bin',
			'type' => 'int'
		),
		'CONF_THUMBS_WID_METHOD' => array(
			'regexp' => '[cp]rop',
			'type' => 'string'
		),
		'CONF_THUMBS_WID_WIDTH' => array(
			'regexp' => '(?:[5-9]\d|[1-2]\d{2}|300)',
			'type' => 'int'
		),
		'CONF_THUMBS_WID_HEIGHT' => array(
			'regexp' => '(?:[5-9]\d|[1-2]\d{2}|300)',
			'type' => 'int'
		),
		'CONF_THUMBS_WID_SIZE' => array(
			'regexp' => '(?:[5-9]\d|[1-2]\d{2}|300)',
			'type' => 'int'
		),
		'CONF_THUMBS_WID_QUALITY' => array(
			'regexp' => '(?:\d|[1-9]\d|100)',
			'type' => 'int'
		),
		'CONF_URL_REWRITE' => array(
			'regexp' => 'bin',
			'type' => 'int'
		)
	);



	/**
	 * Ajoute des paramètres au fichier de configuration.
	 *
	 * @param array $params
	 * @return boolean
	 *	TRUE si le fichier a été modifié avec succès,
	 *	FALSE si une erreur est survenue.
	 */
	public static function addConfig($params)
	{
		$config_file = GALLERY_ROOT . '/config/conf.php';

		// Si le fichier existe, on récupère le contenu.
		if (!file_exists($config_file)
		|| ($file_content = self::fileGetContents($config_file)) === FALSE)
		{
			return FALSE;
		}

		// Ajout des paramètres.
		foreach ($params as $infos)
		{
			// Vérification du format.
			if (!isset(self::$_configParams[$infos['name']]))
			{
				continue;
			}
			switch (self::$_configParams[$infos['name']]['regexp'])
			{
				// Valeur binaire.
				case 'bin' :
					break;

				// Vérification par regexp.
				default :
					if (!preg_match('`^' . self::$_configParams[$infos['name']]['regexp'] . '$`i',
					(string) $infos['value']))
					{
						continue 2;
					}
					break;
			}

			// Type de valeur.
			$value = (self::$_configParams[$infos['name']]['type'] === 'string')
				? '\'' . $infos['value'] . '\''
				: $infos['value'];

			// Ajout du paramètre.
			$file_content = preg_replace(
				'`(^\s*define\s*\(\'' . $infos['after'] . '\'\s*,[^)]+\);[^\r\n]*$)`m',
				'$1' . "\n" . 'define(\'' . $infos['name'] . '\', ' . $value . ');',
				$file_content
			);
		}

		// On enregistre les modifications dans le fichier de config.
		return (bool) self::filePutContents($config_file, $file_content);
	}

	/**
	 * Modifie les valeurs des constantes du fichier de configuration,
	 * et uniquement les valeurs (c'est à dire conserve les commentaires,
	 * les sauts de lignes et autres).
	 *
	 * @param array $params
	 *	Tableau associatif avec pour clé le nom de la constante, et pour valeur
	 *	la clé du tableau $_POST contenant la nouvelle valeur de la constante.
	 * @param array $values
	 *	Valeurs courantes des constantes.
	 * @return boolean
	 *	TRUE si le fichier a été modifié avec succès,
	 *	FALSE si une erreur est survenue,
	 *	NULL si aucun changement.
	 */
	public static function changeConfig(&$params, &$values)
	{
		$config_file = GALLERY_ROOT . '/config/conf.php';

		// Si le fichier existe, on récupère le contenu.
		if (!file_exists($config_file)
		|| ($file_content = file($config_file)) === FALSE)
		{
			return FALSE;
		}

		$temp_content = $file_content;

		// On parcours chaque ligne du fichier.
		foreach ($temp_content as &$line)
		{
			// Si la ligne ne contient pas de constante qui se trouve dans
			// le tableau des paramètres à modifier, on passe à la suivante.
			$p = trim(preg_replace('`^\s*define\s*\(\s*\'([^\']+).*$`i', '$1', $line));
			if (!isset($params[$p]))
			{
				continue;
			}

			// Vérification du format de chaque valeur.
			if (!isset(self::$_configParams[$p]))
			{
				continue;
			}
			switch (self::$_configParams[$p]['regexp'])
			{
				// Valeur binaire pour cases à cocher.
				case 'bin' :
					$value = (isset($_POST[$params[$p]])) ? 1 : 0;
					break;

				// Vérification par regexp.
				default :
					if (!isset($_POST[$params[$p]]))
					{
						continue 2;
					}
					$value = (string) $_POST[$params[$p]];
					if (!preg_match('`^' . self::$_configParams[$p]['regexp'] . '$`i', $value))
					{
						continue 2;
					}
					break;
			}

			$values[$p] = $value;

			// Remplacement de la valeur de la constante.
			$current_line = $line;
			$val = (self::$_configParams[$p]['type'] === 'string')
				? '\'' . addcslashes(addcslashes($value, '\\'), '\\\'') . '\''
				: $value;
			$line = preg_replace(
				'`^\s*define\s*\(\s*\'([^\']+)\'\s*,.+\)\s*;(\s*(?:(?:#|//|/\*).*)?)$`i',
				'define(\'$1\', ' . $val . ');$2',
				$line
			);
			if ($line === NULL)
			{
				return FALSE;
			}
		}

		// On enregistre les modifications dans le fichier de config,
		// si cela est nécessaire.
		if ($temp_content != $file_content)
		{
			return (bool) self::filePutContents($config_file, $temp_content);
		}
	}

	/**
	 * Change les permissions d'accès à un répertoire.
	 *
	 * @param string $f
	 *	Chemin du répertoire.
	 * @return boolean
	 */
	public static function chmodDir($f)
	{
		if (self::isWritable($f))
		{
			return TRUE;
		}

		chmod($f, 0775);
		return self::isWritable($f);
	}

	/**
	 * Change les permissions d'accès à un fichier.
	 *
	 * @param string $f
	 *	Chemin du fichier.
	 * @return boolean
	 */
	public static function chmodFile($f)
	{
		if (self::isWritable($f))
		{
			return TRUE;
		}

		chmod($f, 0664);
		return self::isWritable($f);
	}

	/**
	 * Copie un répertoire.
	 *
	 * @param string $f
	 *	Chemin du répertoire.
	 * @param string $f_new
	 *	Nouveau chemin du répertoire.
	 * @return boolean
	 */
	public static function copyDir($f, $f_new)
	{
		self::chmodDir($f);
		self::chmodDir(dirname($f_new));

		if (copy($f, $f_new))
		{
			return TRUE;
		}

		return FALSE;
	}

	/**
	 * Copie un fichier.
	 *
	 * @param string $f
	 *	Chemin du fichier.
	 * @param string $f_new
	 *	Nouveau chemin du fichier.
	 * @return boolean
	 */
	public static function copyFile($f, $f_new)
	{
		self::chmodFile($f);
		self::chmodDir(dirname($f_new));

		if (copy($f, $f_new))
		{
			return TRUE;
		}

		return FALSE;
	}

	/**
	 * Lit le contenu d'un fichier dans une chaîne.
	 *
	 * @param string $f
	 *	Chemin du fichier.
	 * @return mixed
	 *	FALSE en cas d'erreur.
	 */
	public static function fileGetContents($f)
	{
		return file_get_contents($f);
	}

	/**
	 * Écrit un contenu dans un fichier
	 *
	 * @param string $f
	 *	Chemin du fichier.
	 * @param string|array $f_contents
	 *	Contenu à écrire dans le fichier.
	 * @return mixed
	 *	FALSE en cas d'erreur.
	 */
	public static function filePutContents($f, $f_contents)
	{
		self::chmodDir(dirname($f));

		return file_put_contents($f, $f_contents);
	}

	/**
	 * Retourne toutes les requêtes d'un fichier SQL.
	 *
	 * @param string $f
	 *	Chemin du fichier SQL.
	 * @return boolean|array
	 *	FALSE si une erreur est survenue.
	 *	Sinon, un tableau des requêtes SQL.
	 */
	public static function getSQLFileContent($f)
	{
		// On récupère le contenu du fichier SQL.
		if (($sql_content = self::fileGetContents($f)) === FALSE)
		{
			return FALSE;
		}

		// On convertit les formats de sauts de ligne Windows et Mac en format Unix.
		$sql_content = str_replace("\r\n", "\n", $sql_content);
		$sql_content = str_replace("\r", "\n", $sql_content);

		// On crée un tableau des requêtes SQL.
		$sql_content = preg_split('`[\n]{2}`', $sql_content, -1, PREG_SPLIT_NO_EMPTY);

		// On supprime les commentaires.
		foreach ($sql_content as $i => &$sql)
		{
			if (substr(trim($sql), 0, 2) == '--')
			{
				unset($sql_content[$i]);
				continue;
			}

			$sql = preg_replace('`[\n\t\s]*--[^\n]+`', '', $sql);

			// On vérifie qu'il ne reste que des requêtes SQL
			// de mise à jour de la base de données.
			if (!preg_match('`^(?:ALTER|CREATE|DELETE|DROP|INSERT|UPDATE)`', $sql))
			{
				return FALSE;
			}
		}

		return array_values($sql_content);
	}

	/**
	 * Retourne TRUE si le fichier $f est accessible en lecture et en écriture.
	 *
	 * @param string $f
	 *	Chemin du fichier.
	 * @return boolean
	 */
	public static function isWritable($f)
	{
		if (is_readable($f) && is_writable($f))
		{
			return TRUE;
		}

		return FALSE;
	}

	/**
	 * Crée un répertoire.
	 *
	 * @param string $f
	 *	Chemin du répertoire.
	 * @return boolean
	 */
	public static function mkdir($f)
	{
		self::chmodDir(dirname($f));

		if (mkdir($f, 0775))
		{
			self::chmodDir($f);
			return TRUE;
		}

		return FALSE;
	}

	/**
	 * Renomme un répertoire.
	 *
	 * @param string $f
	 *	Chemin du répertoire.
	 * @param string $f_new
	 *	Nouveau chemin du répertoire.
	 * @return boolean
	 */
	public static function renameDir($f, $f_new)
	{
		self::chmodDir(dirname($f_new));
		self::chmodDir($f);

		return rename($f, $f_new);
	}

	/**
	 * Renomme un fichier.
	 *
	 * @param string $f
	 *	Chemin du fichier.
	 * @param string $f_new
	 *	Nouveau chemin du fichier.
	 * @return boolean
	 */
	public static function renameFile($f, $f_new)
	{
		self::chmodDir(dirname($f_new));
		self::chmodFile($f);

		return rename($f, $f_new);
	}

	/**
	 * Supprime un répertoire et/ou le contenu d'un répertoire.
	 *
	 * @param string $f
	 *	Chemin du répertoire.
	 * @param boolean $del_dir
	 *	Doit-on supprimer le répertoire $f ?
	 * @param array $files_excepts
	 *	Fichier à ne pas supprimer.
	 * @return boolean
	 */
	public static function rmdir($f, $del_dir = TRUE, $files_excepts = array())
	{
		if (!self::chmodDir($f))
		{
			return FALSE;
		}

		if ($res = opendir($f))
		{
			while ($ent = readdir($res))
			{
				if ($ent == '.' || $ent == '..')
				{
					continue;
				}

				$sub = $f . '/' . $ent;

				if (is_dir($sub))
				{
					if (!self::rmdir($sub))
					{
						return FALSE;
					}
				}
				else if (is_file($sub))
				{
					if (!in_array($ent, $files_excepts)
					 && !self::unlink($sub))
					{
						return FALSE;
					}
				}
			}
			closedir($res);
		}

		return $del_dir ? rmdir($f) : TRUE;
	}

	/**
	 * Change la date de dernière modification d'un répertoire.
	 *
	 * @param string $f
	 *	Chemin du répertoire.
	 * @param integer $time
	 *	Date de modification souhaitée.
	 * @return boolean
	 */
	public static function touchDir($f, $time = NULL)
	{
		self::chmodDir(dirname($f));
		self::chmodDir($f);

		return touch($f, $time);
	}

	/**
	 * Change la date de dernière modification d'un fichier.
	 *
	 * @param string $f
	 *	Chemin du fichier.
	 * @param integer $time
	 *	Date de modification souhaitée.
	 * @return boolean
	 */
	public static function touchFile($f, $time = NULL)
	{
		self::chmodDir(dirname($f));
		self::chmodFile($f);

		return touch($f, $time);
	}

	/**
	 * Supprime un fichier.
	 *
	 * @param string $f
	 *	Chemin du fichier.
	 * @return boolean
	 */
	public static function unlink($f)
	{
		self::chmodDir(dirname($f));
		self::chmodFile($f);

		if (unlink($f))
		{
			return TRUE;
		}
		return FALSE;
	}
}
?>