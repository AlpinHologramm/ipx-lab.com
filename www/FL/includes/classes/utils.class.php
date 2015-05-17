<?php
/**
 * Méthodes utilitaires.
 *
 * @license http://www.gnu.org/licenses/gpl.html
 * @link http://www.igalerie.org/
 */
class utils
{
	/**
	 * Nouveau jeton anti-CSRF.
	 *
	 * @var string
	 */
	public static $anticsrfToken;

	/**
	 * Ensemble des paramètres de configuration de la table "config".
	 *
	 * @var array
	 */
	public static $config;

	/**
	 * Cookie des préférences de l'utilisateur.
	 *
	 * @var object
	 */
	public static $cookiePrefs;

	/**
	 * Cookie de session de l'utilisateur.
	 *
	 * @var object
	 */
	public static $cookieSession;

	/**
	 * Timestamp de la date fournie comme argument à la méthode localeTime.
	 *
	 * @var integer
	 */
	public static $date;

	/**
	 * Instance du gestionnaire de base de données.
	 *
	 * @var object
	 */
	public static $db;

	/**
	 * Répertoire complémentaire pour les chemins d'accès générés
	 * par la méthode purl().
	 *
	 * @var string
	 */
	public static $purlDir = '';

	/**
	 * Nom du fichier d'accès à la page souhaitée.
	 *
	 * @var string
	 */
	public static $purlFile = '';

	/**
	 * Autorise-t-on l'URL rewriting (voir la méthode purl()) ?
	 *
	 * @var boolean
	 */
	public static $purlUrlRewrite = TRUE;

	/**
	 * Langue de l'utilisateur courant.
	 *
	 * @var string
	 */
	public static $userLang = CONF_DEFAULT_LANG;

	/**
	 * Fuseau horaire de l'utilisateur courant.
	 *
	 * @var string
	 */
	public static $userTz = CONF_DEFAULT_TZ;



	/**
	 * Liste des attributs de balises (HTML 4, HTML 5 et XHTML 1) autorisés.
	 * Retourne la regexp pour la valeur de l'attribut $attr.
	 *
	 * @param string $attr
	 * @return string|boolean
	 */
	public static function allowedAttrs($attr = FALSE)
	{
		$allowed_attrs = array(
			'alt' => '[-_\w\d\s,;:.%]+',
			'cite' => '[-_\w\d\s,;:.%]+',
			'class' => '[-_a-z0-9\s]+',
			'datetime' => '[-0-9]+', # HTML 5
			'height' => '\d{1,6}',
			'href' => '[-_\w\d@&=+?%~./,;:*#]+',
			'hreflang' => '[-a-z]{2,5}',
			'id' => '[_a-z0-9]+',
			'lang' => '[-a-z]{2,5}',
			'max' => '[-+0-9e.]+', # HTML 5
			'pubdate' => '[-0-9]+', # HTML 5
			'rel' => '[-_a-z0-9]+',
			'src' => '[-_a-z0-9./:=\?]+', # filtrage spécifique dans méthode _checkHTML()
			'style' => '[-_a-z0-9\s;:#,%.]+',
			'target' => '[-_a-z0-9]+',
			'title' => '[-_\w\d\s@&=+!?%°~./,;:*#()\[\]\|]+',
			'value' => '[-+0-9e.]+', # HTML 5
			'width' => '\d{1,6}'
		);

		if ($attr === FALSE)
		{
			return $allowed_attrs;
		}

		return (isset($allowed_attrs[$attr]))
			? $allowed_attrs[$attr]
			: FALSE;
	}

	/**
	 * Retourne la liste des balises (HTML 4, HTML 5 et XHTML 1) autorisées.
	 *
	 * @return array
	 */
	public static function allowedTags()
	{
		// Toutes les balises de formulaires, d'objets (script, ...)
		// et de structure de page (body, ...) ne sont pas autorisées.
		return array(
			'a', 'abbr', 'acronym', 'address', 'article', 'aside',
			'blockquote', 'cite', 'code', 'del', 'details', 'dfn', 'dialog',
			'div', 'dd', 'dl', 'dt', 'em', 'figure', 'footer', 'h2',
			'h3', 'h4', 'h5', 'h6', 'header', 'hr', 'img', 'ins', 'kbd',
			'legend', 'li', 'nav', 'ol', 'p', 'pre', 'progress', 'q', 'rt',
			'samp', 'section', 'span', 'strong', 'sub', 'sup', 'table', 'td',
			'th', 'time', 'tr', 'ul', 'var'
		);
	}

	/**
	 * Méthode de tri par ordre alphabétique croissant
	 * pour fonctions de tri PHP nécessitant l'utilisation
	 * d'une fonction utilisateur (uasort, uksort, usort).
	 *
	 * @return integer
	 */
	public static function alphaSort($a, $b)
	{
		foreach (array('a', 'b') as $c)
		{
			if (preg_match('`[^\x00-\x7F]`', $$c))
			{
				$$c = self::removeAccents($$c);
			}
		}

		return strcasecmp($a, $b);
	}

	/**
	 * Vérifie le jeton anti-CSRF.
	 *
	 * @param object $cookie
	 * @return boolean
	 */
	public static function antiCSRFTokenCheck($cookie)
	{
		// Si pas de données en POST, inutile de vérifier le jeton.
		if ($_POST === array())
		{
			return TRUE;
		}

		// Vérification du jeton.
		if (isset($_POST['anticsrf']) && self::isSha1($_POST['anticsrf'])
		&& $_POST['anticsrf'] === $cookie->read('anticsrf')
		&& ((int) $cookie->read('anticsrf_expire') - time()) > 0)
		{
			return TRUE;
		}

		// Si le jeton n'est pas valide, on efface toutes les données en POST.
		if (CONF_DEBUG)
		{
			trigger_error('anticsrf error', E_USER_WARNING);
		}
		$_POST = array();
		return FALSE;
	}

	/**
	 * Génère un nouveau jeton anti-CSRF.
	 *
	 * @param object $cookie
	 * @return void
	 */
	public static function antiCSRFTokenNew($cookie)
	{
		if (self::$config['anticsrf_token_unique']
		|| (!self::$config['anticsrf_token_unique']
		&& ((int) $cookie->read('anticsrf_expire') - time()) > 0) === FALSE)
		{
			self::$anticsrfToken = self::genKey();
			$cookie->add('anticsrf', self::$anticsrfToken);
		}
		else if (!self::$config['anticsrf_token_unique']
		&& ((int) $cookie->read('anticsrf_expire') - time()) > 0)
		{
			self::$anticsrfToken = $cookie->read('anticsrf');
		}
		$cookie->add(
			'anticsrf_expire',
			time() + (int) self::$config['anticsrf_token_expire']
		);
	}

	/**
	 * Implémentation de la fonction PHP >=5.3 array_replace_recursive().
	 *
	 * @author kyle [dot] florence [@t] gmail [dot] com
	 *	http://php.net/manual/en/function.array-replace-recursive.php
	 * @return array
	 */
	public static function arrayJoin()
	{
		$arrays = func_get_args();

		$original = array_shift($arrays);

		foreach ($arrays as $array)
		{
			foreach ($array as $key => $value)
			{
				if (is_array($value) && isset($original[$key]))
				{
					$original[$key] = self::arrayJoin($original[$key], $array[$key]);
				}
				else
				{
					$original[$key] = $value;
				}
			}
		}

		return $original;
	}

	/**
	 * Permet de connaître l'emplacement d'une
	 * instruction return en mode débogage.
	 *
	 * @return null
	 */
	public static function debugReturn()
	{
		if (CONF_DEBUG)
		{
			$trace = debug_backtrace();

			$file = (strpos($trace[0]['file'], GALLERY_ROOT) === 0)
				? substr($trace[0]['file'], strlen(GALLERY_ROOT) + 1)
				: $trace[0]['file'];

			trigger_error('Debug return in ' . $file . ':' . $trace[0]['line'], E_USER_NOTICE);
		}
	}

	/**
	 * Convertit un poids en octets en un multiple plus court.
	 *
	 * @param integer $size
	 *	Poids en octets.
	 * @param string $u
	 *	Force l'affichage dans l'unité choisie (o, k, m, g).
	 * @return string
	 */
	public static function filesize($size, $u = NULL)
	{
		$size = (float) $size;

		$ko = 1024;
		$mo = 1024 * $ko;
		$go = 1024 * $mo;
		$to = 1024 * $go;

		if (($size < $ko && !$u) || $u === 'o')
		{
			$size = ($size > 0)
				? sprintf(__('%s octets'), $size)
				: sprintf(__('%s octet'), $size);
		}
		elseif (($size < $mo && !$u) || $u === 'k')
		{
			$size = sprintf(__('%s Ko'), round($size/$ko, 2));
		}
		elseif (($size < $go && !$u) || $u === 'm')
		{
			$size = sprintf(__('%s Mo'), round($size/$mo, 2));
		}
		elseif (($size < $to && !$u) || $u === 'g')
		{
			$size = sprintf(__('%s Go'), round($size/$go, 2));
		}
		else
		{
			$size = sprintf(__('%s To'), round($size/$to, 2));
		}

		return self::numeric($size);
	}

	/**
	 * Ensemble de filtres pour des chaînes.
	 *
	 * @param string $str
	 *	Chaîne à vérifier.
	 * @param string $type
	 *	Type de chaîne à vérifier.
	 * @return string
	 *	Chaîne vide si $str possède un caractère non autorisé, sinon $str.
	 */
	public static function filters($str, $type)
	{
		switch ($type)
		{
			case 'dir' :
				$regex = '`[^-_a-z0-9]`i';
				break;

			case 'order_by' :
				$regex = '`[^_a-z\s,.\(\)\*]`i';
				break;

			case 'path' :
				$regex = '`[^-_a-z0-9/]`i';
				break;
		}

		return preg_match($regex, $str) ? '' : $str;
	}

	/**
	 * Génère une chaîne de caractères aléatoire.
	 *
	 * @param boolean|string $hash
	 * @param integer $length
	 * @return string
	 */
	public static function genKey($hash = 'sha1', $length = 8)
	{
		$key = '';

		if ($hash)
		{
			for ($i = 0; $i < 128; $i++)
			{
				$key .= chr(mt_rand(33, 126));
			}
			$key = hash_hmac($hash, uniqid($key, TRUE), time() * mt_rand(13, 666));
		}
		else
		{
			for ($i = 0; $i < $length; $i++)
			{
				switch (rand(1, 3))
				{
					case 1 :
						$key .= chr(mt_rand(48, 57));
						break;

					case 2 :
						$key .= chr(mt_rand(65, 90));
						break;

					case 3 :
						$key .= chr(mt_rand(97, 122));
						break;
				}
			}
		}

		return $key;
	}

	/**
	 * Génère le chemin d'accès à une page de la galerie
	 * depuis la racine du site.
	 *
	 * @param string $page
	 * @return string
	 */
	public static function genGalleryURL($page = NULL)
	{
		// Sauvegarde des paramètres d'URL.
		$purl_dir = self::$purlDir;
		$url_rewrite = self::$purlUrlRewrite;

		// Paramètres galerie.
		self::$purlDir = '';
		self::$purlUrlRewrite = CONF_URL_REWRITE;

		// On génère l'URL.
		$link = self::genURL($page);

		// On rétablit les paramètres d'URL initiaux.
		self::$purlDir = $purl_dir;
		self::$purlUrlRewrite = $url_rewrite;

		return $link;
	}

	/**
	 * Génère un mot de passe aléatoire.
	 *
	 * @param integer $length
	 * @return string
	 */
	public static function genPassword($length = 16)
	{
		$password = '';
		$chars = '#?%*/$!+=:;_&-0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

		for ($i = 0; $i < $length; $i++)
		{
			$password .= $chars[mt_rand(0, strlen($chars)-1)];
		}

		return $password;
	}

	/**
	 * Génère le chemin d'accès à une page de l'application
	 * depuis la racine du site.
	 *
	 * @param string $page
	 * @param boolean $p_only
	 * @return string
	 */
	public static function genURL($page = NULL, $p_only = FALSE)
	{
		// Protection.
		if ($page !== NULL)
		{
			self::htmlspecialchars($page);
		}

		// Valeur du paramètre d'URL seulement.
		if ($p_only)
		{
			return $page;
		}

		// Si URL rewriting ou aucune page, on ne met pas "?q=".
		$q = ((CONF_URL_REWRITE && self::$purlUrlRewrite) || strlen($page) === 0)
			? ''
			: '?q=';

		return CONF_GALLERY_PATH . self::$purlDir . '/'
			. self::$purlFile . $q . $page;
	}

	/**
	 * Génère le nom d'URL d'un objet.
	 *
	 * @param string $name
	 *	Nom de l'objet.
	 * @return string
	 */
	public static function genURLName($name)
	{
		$name = str_replace('/', '', $name);
		$name = mb_ereg_replace('[^-\d\w_]', ' ', $name);
		$name = trim($name);
		$name = mb_ereg_replace('[-\s]+', '-', $name);
		$name = self::removeAccents($name);
		$name = mb_strtolower($name);

		return $name;
	}

	/**
	 * Récupération de la configuration de la galerie.
	 *
	 * @return void
	 */
	public static function getConfig()
	{
		// Récupération de la configuration de la galerie.
		$sql = 'SELECT *
				  FROM ' . CONF_DB_PREF . 'config
				 WHERE conf_name NOT LIKE "admin%"
				   AND conf_name NOT LIKE "blacklist%"';
		$fetch_style = array(
			'column' => array('conf_name', 'conf_value')
		);
		if (self::$db->query($sql, $fetch_style) === FALSE
		|| self::$db->nbResult === 0)
		{
			throw new Exception('Missing data in the database.');
		}
		self::$config = self::$db->queryResult;

		// On reconstruit les tableaux sérialisés.
		self::$config['locale_langs'] = unserialize(self::$config['locale_langs']);
		self::$config['pages_order'] = unserialize(self::$config['pages_order']);
		self::$config['pages_params'] = unserialize(self::$config['pages_params']);
		self::$config['widgets_order'] = unserialize(self::$config['widgets_order']);
		self::$config['widgets_params'] = unserialize(self::$config['widgets_params']);

		// Modification de la config par le template.
		$config = array();
		include_once(GALLERY_ROOT . '/template/'
			. self::filters(self::$config['theme_template'], 'dir') . '/_config.php');
		self::$config = self::arrayJoin(self::$config, $config);

		// Si le mode de redimensionnement des images se fait par GD,
		// on interdit à l'utilisateur la possibilité de choisir
		// le mode de redimensionnement, quel que soit le choix admin.
		if (self::$config['images_resize'] == 1
		 && self::$config['images_resize_method'] == 2)
		{
			self::$config['widgets_params']['options']['items']['image_size'] = 0;
		}

		// Si la fonctionnalité "filigrane" est activée,
		// on désactive l'affichage du poids dans les statistiques.
		if (self::$config['watermark'])
		{
			self::$config['thumbs_stats_filesize'] = 0;
			self::$config['widgets_params']['options']['items']['thumbs_filesize'] = 0;
			self::$config['widgets_params']['stats_categories']['items']['filesize'] = 0;
		}

		// Si les images sont affichées directement,
		// on force la désactivation de certaines fonctionnalités.
		if (self::$config['images_direct_link'])
		{
			self::$config['comments'] = 0;
			self::$config['votes'] = 0;
		}

		// Y a-t-il au moins une page active ?
		self::$config['pages'] = 0;
		foreach (self::$config['pages_params'] as $infos)
		{
			if ($infos['status'] == 1)
			{
				self::$config['pages'] = 1;
				break;
			}
		}
	}

	/**
	 * Retourne la valeur booléenne d'un paramètre de configuration PHP.
	 *
	 * @param string $varname
	 * @return boolean
	 */
	public static function getIniBool($varname)
	{
		$value = ini_get($varname);

		switch (strtolower($value))
		{
			case 'on':
			case 'yes':
			case 'true':
				return 'assert.active' !== $varname;

			case 'stdout':
			case 'stderr':
				return 'display_errors' === $varname;

			default:
				return (bool) (int) $value;
		}
	}

	/**
	 * Retourne la valeur numérique entière équivalente de la
	 * variable $val.
	 * Equivalent proche de la fonction PHP intval() mais non
	 * limitant par rapport à la valeur entière maximale
	 * du système.
	 * http://www.php.net/manual/fr/function.intval.php
	 *
	 * @param string $val
	 * @return integer|string
	 */
	public static function getIntVal($val)
	{
		// La valeur doit être de type numérique
		// ou chaîne de caractères.
		if (!is_numeric($val) && !is_string($val))
		{
			return ((is_array($val) && count($val) > 1)
				 || (is_bool($val)) && $val === TRUE
				 || (is_object($val)))
				? 1
				: 0;
		}

		// Convertit le type en chaîne de caractères
		// et supprime les espaces de début et de fin.
		$val = trim($val);

		// Convertit la notation scientifique en notation classique.
		if (preg_match('`^([-+]?\d+)(?:\.(\d+))?E\+?(\d+)$`i', $val, $m))
		{
			$m[1] = str_replace('+', '', $m[1]);
			$l = $m[3] - strlen($m[2]);
			$val = ($l > 0)
				? $m[1] . $m[2] . str_repeat('0', $l)
				: substr($m[1] . $m[2], 0, $l);
		}
		$val = preg_replace('`^([-+]?\d+)(?:\.\d+)?(?:E\+?\d+)?$`i', '$1', $val);

		// On tente de convertir le type en nombre entier.
		// Fonctionne seulement si la valeur ne dépasse pas
		// la valeur entière maximale du système.
		if ($val == (int) $val)
		{
			return (int) $val;
		}

		// Si ça ne fonctionne pas, on retourne
		// la valeur telle quelle si elle ne contient que
		// des caractères constituants un nombre entier,
		// sinon on retourne 0.
		return (preg_match('`^[-+]?\d+(?:\.\d+)?(?:E\+?\d+)?$`i', $val)) ? $val : 0;
	}

	/**
	 * Retourne le texte dans la langue demandée
	 * (pour les champs de base de données avec gestion de localisation).
	 *
	 * @param string $str
	 * @param string $lang
	 * @return string
	 */
	public static function getLocale($str, $lang = NULL)
	{
		if ($lang === NULL)
		{
			$lang = self::$userLang;
		}

		return (($start = strpos($str, '<' . $lang . '>')) === FALSE
			   || ($end = strpos($str, '</' . $lang . '>')) === FALSE)
			? self::removeTagsLangs($str)
			: substr($str, $start + 7, $end - $start - 7);
	}

	/**
	 * Récupération des différents feuilles de styles CSS disponibles.
	 *
	 * @return array
	 */
	public static function getStyles()
	{
		$dir = GALLERY_ROOT . '/template/' . self::$config['theme_template'] . '/style';
		$styles = scandir($dir);
		for ($i = 0, $count = count($styles); $i < $count; $i++)
		{
			if (!preg_match('`^[-_a-z0-9]{1,48}$`i', $styles[$i]))
			{
				unset($styles[$i]);
			}
		}
		sort($styles);

		return $styles;
	}

	/**
	 * Retourne le chemin du répertoire utilisé pour les fichiers temporaires.
	 *
	 * @return string
	 */
	public static function getTempDir()
	{
		if (function_exists('sys_get_temp_dir')
		&& ($temp_dir = realpath(sys_get_temp_dir())))
		{
			return $temp_dir;
		}

		return realpath(dirname(__FILE__) . '/../../cache');
	}

	/**
	 * Hashage pour noms de fichier des images intermédiaires.
	 *
	 * @param string|integer $image
	 *	Identifiant ou nom de l'image.
	 * @param string $str
	 *	Chaîne supplémentaire à ajouter au hash.
	 * @return string
	 */
	public static function hashImages($image, $str = NULL)
	{
		return md5((string) $image . '|' . CONF_KEY . '|' . (string) $str);
	}

	/**
	 * Hashage pour mots de passe.
	 *
	 * @param string $pwd
	 *	Mot de passe à hasher.
	 * @param string $key
	 *	Clé unique pour éviter les signatures identiques
	 *	sur les mots de passe identiques.
	 * @return string
	 */
	public static function hashPassword($pwd, $key)
	{
		$key = CONF_KEY . '|' . $key . '|' . self::$config['key'];
		return hash_hmac('sha1', (string) $pwd, (string) $key);
	}

	/**
	 * Empêche la mise en cache de la page par le navigateur.
	 *
	 * @return void
	 */
	public static function headersNoCache()
	{
		header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
		header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
		header('Pragma: no-cache');
	}

	/**
	 * Applique la fonction htmlspecialchars à une chaîne
	 * ou aux valeurs d'un tableau.
	 *
	 * @param array|string $s
	 * @return void
	 */
	public static function htmlspecialchars(&$s)
	{
		if (is_string($s))
		{
			$s = defined('ENT_DISALLOWED')
				? htmlspecialchars($s, ENT_DISALLOWED | ENT_QUOTES, 'UTF-8')
				: htmlspecialchars($s, ENT_QUOTES, 'UTF-8');
		}
		else if (is_array($s))
		{
			array_walk($s, array('utils', 'htmlspecialchars'));
		}
	}

	/**
	 * Détermine si une chaîne est vide.
	 *
	 * @param string $str
	 * @return string
	 */
	public static function isEmpty($str)
	{
		return preg_match('`^[­ \s]*$`', trim($str));
	}

	/**
	 * Détermine si une chaîne est un tableau linéarisé.
	 * (très approximatif !)
	 *
	 * @param string $data
	 * @return string
	 */
	public static function isSerializedArray($data)
	{
		return is_string($data) && preg_match('`^a:\d+:\{[^$]*\}$`', $data);
	}

	/**
	 * Détermine si une chaîne ressemble à une signature SHA1.
	 *
	 * @param string $data
	 * @return string
	 */
	public static function isSha1($data)
	{
		return is_string($data) && preg_match('`^[a-z\d]{40}$`', $data);
	}

	/**
	 * Détecte la langue du client.
	 *
	 * @return void
	 */
	public static function detectClientLang()
	{
		if (!isset($_SERVER['HTTP_ACCEPT_LANGUAGE']))
		{
			return;
		}

		preg_match_all(
			'/([a-z]{1,2}(?:-[a-z]{1,2})?)\s*(?:;\s*q\s*=\s*(1|0\.[0-9]+))?/i',
			$_SERVER['HTTP_ACCEPT_LANGUAGE'],
			$lang_parse
		);
		if (count($lang_parse[1]))
		{
			$langs = array_map(
				create_function('$q', 'return $q === "" ? 1 : $q;'),
				array_combine($lang_parse[1], $lang_parse[2])
			);
			arsort($langs, SORT_NUMERIC);
			foreach ($langs as $lang => &$val)
			{
				foreach (self::$config['locale_langs'] as $code => &$name)
				{
					$test = (strlen($lang) == 2) ? substr($code, 0, 2) : $code;
					if (strtolower($lang) == strtolower($test))
					{
						self::$userLang = $code;
						return;
					}
				}
			}
		}
	}

	/**
	 * Convertit les URLs d'une chaîne en liens HTML.
	 *
	 * @param string $str
	 * @param integer $limit
	 *	Nombre maximum de caractères du lien.
	 * @return string
	 */
	public static function linkify($str, $limit = 0)
	{
		$str = self::tplProtect($str);
		$str = preg_replace('`(' . self::regexpURL() . ')`i', '<a href="$1">$1</a>', $str);

		return ($limit)
			? mb_ereg_replace('(<a[^>]+>)(.{' . $limit . '}).*(</a>)', '\\1\\2...\\3', $str)
			: $str;
	}

	/**
	 * Définition des paramètres de localisation et de fuseau horaire.
	 *
	 * @return void
	 */
	public static function locale()
	{
		global $L10N;
		$L10N = array();

		// Chargement du fichier de langue.
		include_once(GALLERY_ROOT . '/locale/' . self::$userLang . '/igalerie.php');

		// Fuseau horaire.
		date_default_timezone_set(self::$userTz);
	}

	/**
	 * Localisation de la date $date selon le format $format
	 * et le décalage horaire self::$userTz.
	 *
	 * @param string $format
	 * @param integer|string $date
	 * @return string
	 */
	public static function localeTime($format, $date = NULL)
	{
		date_default_timezone_set(CONF_DEFAULT_TZ);
		self::$date = ($date === NULL)
			? strtotime(date('Y-m-d H:i:s'))
			: strtotime($date);
		date_default_timezone_set(self::$userTz);
		$format = preg_replace_callback(
			'`(%[aAbB])`',
			create_function(
				'$m',
				'switch ($m[1])
				{
					case "%a" : return __(date("D", utils::$date));
					case "%A" : return __(date("l", utils::$date));
					case "%b" : return __(date("M", utils::$date));
					case "%B" : return __(date("F", utils::$date));
				}'
			),
			$format
		);

		return strftime($format, self::$date);
	}

	/**
	 * Localisation du caractère de séparation décimale.
	 *
	 * @param float|string $float
	 *	Chaîne ou flottant à localiser.
	 * @return string
	 */
	public static function numeric($float)
	{
		return preg_replace(
			'`^(.*)(\d+)\D(\d+)(.*)$`',
			'$1$2' . __(',') . '$3$4',
			(string) $float
		);
	}

	/**
	 * Convertit une chaîne PHP en chaîne Javascript
	 * afin d'éviter toute erreur de l'interpréteur JS.
	 *
	 * @author Olivier Miakinen
	 * @param string $str
	 * @return string
	 */
	public static function php2js($str)
	{
		return str_replace(
			array("\\"  , "'"   , '"' , "\r", "\n", "\xE2\x80\xA8", "\xE2\x80\xA9"),
			array('\\\\', '\\\'', '\"', '\r', '\n', '\u2028'      , '\u2029'      ),
			$str
		);
	}

	/**
	 * Redirige vers une autre page de la galerie.
	 *
	 * @param string $page
	 *	Page vers laquelle rediriger.
	 * @param boolean $no_debug
	 *	Doit-on ne pas tenir compte du mode debug ?
	 * @param integer $http_response_code
	 *	Code de réponse HTTP.
	 * @return void
	 */
	public static function redirect($page = NULL, $no_debug = FALSE, $http_response_code = 302)
	{
		// Permet de connaître toutes les redirections en mode débogage.
		if (CONF_DEBUG)
		{
			$trace = debug_backtrace();
			$file = (strpos($trace[0]['file'], GALLERY_ROOT) === 0)
				? substr($trace[0]['file'], strlen(GALLERY_ROOT) + 1)
				: $trace[0]['file'];
			trigger_error('Debug redirect in ' . $file . ':' . $trace[0]['line'], E_USER_NOTICE);
		}

		// Redirige si le mode débogage n'est pas activé ou bien
		// si la redirection a été forcée en ignorant le mode débogage.
		if (!CONF_DEBUG || $no_debug)
		{
			// On enregistre les données de cookies avant de rediriger.
			if (is_object(self::$cookiePrefs))
			{
				self::$cookiePrefs->write();
			}
			if (is_object(self::$cookieSession))
			{
				self::$cookieSession->write();
			}

			// Vérification du format de l'URL générée.
			$url = GALLERY_HOST . self::genURL($page);
			$url = str_replace('../', '', $url);
			if (!preg_match('`^' . utils::regexpURL() . '`i', $url))
			{
				trigger_error('No valid URL: ' . $url, E_USER_WARNING);
				return;
			}

			// Redirection.
			header('Location: ' . $url, TRUE, $http_response_code);
			die;
		}
	}

	/**
	 * Permet de trouver n'importe quel caractère identique avec ou sans accent.
	 *
	 * @param string $str
	 * @return string
	 */
	public static function regexpAccents($str)
	{
		$str = mb_ereg_replace('[éèeêë]', '[éèeêë]', $str);
		$str = mb_ereg_replace('[aäâàáåã]', '[aäâàáåã]', $str);
		$str = mb_ereg_replace('[iïîìí]', '[iïîìí]', $str);
		$str = mb_ereg_replace('[uüûùú]', '[uüûùú]', $str);
		$str = mb_ereg_replace('[oöôóòõ]', '[oöôóòõ]', $str);
		$str = mb_ereg_replace('[cç]', '[cç]', $str);
		$str = mb_ereg_replace('[nñ]', '[nñ]', $str);
		$str = mb_ereg_replace('[yÿý]', '[yÿý]', $str);

		return $str;
	}

	/**
	 * Retourne la regexp pour les adresses de courriel.
	 *
	 * @param integer $limit
	 *	Taille maximum de l'adresse.
	 * @return string
	 */
	public static function regexpEmail($limit = 255)
	{
		return '(?!.{' . ($limit + 1) . '})'
			. '(?:[-a-z\d!#$%&\'*+/=?^_\`{|}~]+\.?)+(?<!\.)(?<!.{65})@(?!.{256})' 
			. self::regexpURL('domain') . self::regexpURL('tld');
	}

	/**
	 * Retourne la regexp pour la partie d'URL $p demandée.
	 *
	 * @param integer $p
	 * @return string
	 */
	public static function regexpURL($p = 'url')
	{
		// Protocoles.
		$protocol  = '(?:https?|ftp)://';

		// IP.
		$ipv4      = '(?:(?:(?:0?\d?\d|1\d{2}|2[0-4]\d|25[0-5])\.){3}';
		$ipv4     .= '(?:0?\d?\d|1\d{2}|2[0-4]\d|25[0-5]))';
		$ipv6      = '(?:(?:[\da-f]{0,4}:){7}[\da-f]{0,4})';
		$ip        = '(?:' . $ipv4 . '|' . $ipv6 . ')';

		// Nom d'utilisateur et mot de passe.
		$user_pass = '(?:[-\w]+:[-\w]+@)?';

		// Nom de domaine.
		$domain    = '(?:[a-z\d][-a-z\d]{0,62}(?<!-)\.)+';

		// TLD.
		$tld       = '[a-z]{2,12}';

		// Numéro de port.
		$port      = '(?::(?:6553[0-5]|655[0-2]\d|65[0-4]\d\d|';
		$port     .= '6[0-4]\d{3}|[1-5]\d{4}|[1-9]\d{1,3}|\d))?';

		// Chemin.
		$path      = '(?:/[-@&=+?%~./,;*a-z\d_#]*)?';

		// URL complète.
		$url       = $protocol . $user_pass;
		$url      .= '(?:' . $ip . '|localhost|(?:' . $domain . $tld . ')(?<![^/@]{256}))';
		$url      .= $port . $path;
		$url      .= '(?<![]).,;:!?])';

		return ${$p};
	}

	/**
	 * Retire les accents d'une chaîne.
	 *
	 * @param string $str
	 * @return string
	 */
	public static function removeAccents($str)
	{
		return str_replace(
			array('É','È','Ë','Ê','À','Ä','Â','Á','Å','Ã','Ï','Î','Ì','Í','Ö',
				  'Ô','Ò','Ó','Õ','Ø','Ù','Û','Ü','Ú','Ÿ','Ý','¥','Ç','Ñ','Š',
				  'Ž','é','è','ë','ê','à','ä','â','á','å','ã','ï','î','ì','í',
				  'ö','ô','ò','ó','õ','ø','ù','û','ü','ú','ÿ','ý','ç','ñ','š',
				  'ž','Ð','ß','Œ','Æ','œ','æ'),
			array('E','E','E','E','A','A','A','A','A','A','I','I','I','I','O',
				  'O','O','O','O','O','U','U','U','U','Y','Y','Y','C','N','S',
				  'Z','e','e','e','e','a','a','a','a','a','a','i','i','i','i',
				  'o','o','o','o','o','o','u','u','u','u','y','y','c','n','s',
				  'z','D','b','OE','AE','oe','ae'),
			$str
		);
	}

	/**
	 * Retire tous les tags de langues d'une chaine
	 * (ainsi que le contenu de ces tags).
	 *
	 * @param string $str
	 * @return string
	 */
	public static function removeTagsLangs($str)
	{
		return (strstr($str, '<'))
			? preg_replace('`<[a-z]{2}_[A-Z]{2}>[^$]*$`', '', $str)
			: $str;
	}

	/**
	 * Retourne un texte formaté en plusieurs langues.
	 *
	 * @param array $post_data
	 * @param string $current_data
	 * @param integer $max_length
	 * @param boolean $no_empty
	 * @return array
	 */
	public static function setLocaleText(&$post_data, &$current_data, $max_length = 0,
	$no_empty = FALSE)
	{
		$r = array(
			'change' => FALSE,
			'empty' => FALSE
		);

		if (!is_array($post_data) || !isset($post_data[CONF_DEFAULT_LANG]))
		{
			return $r;
		}

		// $current_data peut provenir de la base de données.
		if ($current_data === NULL)
		{
			$current_data = '';
		}

		$new_data = preg_replace('`\r`', '', self::removeTagsLangs(
			$post_data[CONF_DEFAULT_LANG]
		));

		// Vérification de la longueur.
		if ($max_length > 0)
		{
			$new_data = mb_strimwidth($new_data, 0, $max_length);
		}
		if ($no_empty && (mb_strlen($new_data) < 1 || self::isEmpty($new_data)))
		{
			$r['empty'] = TRUE;
			return $r;
		}

		if (count(self::$config['locale_langs']) > 1)
		{
			$current_data_default = self::removeTagsLangs($current_data);
			$new_data_default = $new_data;
			unset($post_data[CONF_DEFAULT_LANG]);
			foreach ($post_data as $code => &$text)
			{
				$text = preg_replace('`\r`', '', self::removeTagsLangs($text));
				if (isset(self::$config['locale_langs'][$code])
				&& $text !== $new_data_default
				&& $text !== $current_data_default)
				{
					// Vérification de la longueur.
					if ($max_length > 0)
					{
						$text = mb_strimwidth($text, 0, $max_length);
					}
					if ($no_empty && (mb_strlen($text) < 1 || self::isEmpty($text)))
					{
						$r['empty'] = TRUE;
						continue;
					}

					$new_data .= '<' . $code . '>' . $text . '</' . $code . '>';
				}
			}
			$post_data[CONF_DEFAULT_LANG] = $new_data_default;
		}

		if ($new_data === $current_data)
		{
			return $r;
		}

		return array(
			'change' => TRUE,
			'empty' => FALSE,
			'data' => $current_data = $new_data
		);
	}

	/**
	 * Limite la longueur d'une chaîne de caractères.
	 *
	 * @param string $str
	 * @param integer $limit
	 * @return string
	 */
	public static function strLimit($str, $limit)
	{
		return mb_strimwidth($str, 0, $limit, '...');
	}

	/**
	 * Filtre le code HTML d'une chaîne.
	 * C'est à dire protège la chaîne, puis autorise uniquement
	 * les éléments de langage HTML définis dans les méthodes
	 * allowedAttrs et allowedTags.
	 *
	 * @param string $str
	 * @return string
	 */
	public static function tplHTMLFilter($str)
	{
		self::htmlspecialchars($str);

		return (self::$config['html_filter'])
			? preg_replace_callback('`&lt;(?:(?!&gt;).){1,255}&gt;`',
				'utils::_checkHTML', $str)
			: $str;
	}

	/**
	 * Protège ou décode une chaîne de sortie (à destination du navigateur).
	 *
	 * @param string|integer $str
	 * @param boolean $decode
	 *	Doit-on décoder la chaîne ?
	 * @return string
	 */
	public static function tplProtect($str, $decode = FALSE)
	{
		if ($decode)
		{
			return htmlspecialchars_decode($str, ENT_QUOTES);
		}

		self::htmlspecialchars($str);
		return $str;
	}

	/**
	 * Retourne la limite de poids pour l'envoi de fichiers par POST, en octets.
	 *
	 * @return integer
	 */
	public static function uploadMaxFilesize()
	{
		$ini_get = create_function(
			'$directive',
			'$val = strtolower(ini_get($directive));
			switch (substr($val, -1, 1))
			{
				case "g" : return utils::getIntVal(((float) $val) * 1024 * 1024 * 1024);
				case "m" : return utils::getIntVal(((float) $val) * 1024 * 1024);
				case "k" : return utils::getIntVal(((float) $val) * 1024);
				default  : return utils::getIntVal($val);
			}'
		);

		$limits = array(
			$ini_get('memory_limit'),
			$ini_get('post_max_size'),
			$ini_get('upload_max_filesize')
		);
		sort($limits, SORT_NUMERIC);

		return $limits[0];
	}

	/**
	 * Convertit une chaîne en UTF-8.
	 *
	 * @param string $str
	 *	Chaîne à convertir.
	 * @return string
	 */
	public static function UTF8($str)
	{
		// On retourne la chaîne telle quelle si elle est déjà en UTF-8.
		// http://www.w3.org/International/questions/qa-forms-utf-8.en.php
		// Ajout obligatoire d'un quantificateur "+" sur la première ligne,
		// sinon plantage sur certaines chaînes (bug PHP ?).
		if (preg_match('`^(?:
			  [\x09\x0A\x0D\x20-\x7E]+			# ASCII
			| [\xC2-\xDF][\x80-\xBF]			# non-overlong 2-byte
			| \xE0[\xA0-\xBF][\x80-\xBF]		# excluding overlongs
			| [\xE1-\xEC\xEE\xEF][\x80-\xBF]{2}	# straight 3-byte
			| \xED[\x80-\x9F][\x80-\xBF]		# excluding surrogates
			| \xF0[\x90-\xBF][\x80-\xBF]{2}		# planes 1-3
			| [\xF1-\xF3][\x80-\xBF]{3}			# planes 4-15
			| \xF4[\x80-\x8F][\x80-\xBF]{2}		# plane 16
		)*$`xs', $str))
		{
			return $str;
		}
		else
		{
			$str_iconv = iconv('ISO-8859-15', 'UTF-8', $str);
			return ($str_iconv === FALSE)
				? $str
				: $str_iconv;
		}
	}

	/**
	 * Convertit un tableau en UTF-8.
	 *
	 * @param string $str
	 *	Tableau à convertir.
	 * @return void
	 */
	public static function UTF8Array(&$arr)
	{
		foreach ($arr as &$i)
		{
			if (is_array($i))
			{
				self::UTF8Array($i);
			}
			if (is_string($i))
			{
				$i = self::UTF8($i);
			}
		}
	}

	/**
	 * Crée et envoi une archive Zip.
	 *
	 * @param string $zip_filename
	 *	Nom de fichier de l'archive.
	 * @param array $files
	 *	Fichiers à inclure dans l'archive.
	 * @return void
	 */
	public static function zipArchive($zip_filename, &$files)
	{
		try
		{
			// L'extension ZipArchive doit être chargée.
			if (!extension_loaded('zip') || !class_exists('ZipArchive', FALSE))
			{
				throw new Exception('ZipArchive is required.');
			}

			// Fichier temporaire.
			$zip_temp_file = tempnam(self::getTempDir(), 'zip');
			if ($zip_temp_file === FALSE)
			{
				throw new Exception('ZipArchive tempnam error.');
			}

			// Création d'un objet ZipArchive.
			$zip_archive = new ZipArchive();
			$r = $zip_archive->open($zip_temp_file, ZipArchive::OVERWRITE);
			if ($r !== TRUE)
			{
				throw new Exception('ZipArchive error : ' . $r . '.');
			}

			// On vérifie que tous les fichiers existent bien
			// et on gére les noms de fichiers doublons.
			$files_temp = array();
			foreach ($files as &$f)
			{
				// On vérifie que le fichier existe bien sur le disque.
				if (!file_exists($f))
				{
					continue;
				}

				// On renomme le fichier si un fichier de même nom a déjà été ajouté à l'archive.
				$filename = basename($f);
				$test = $filename;
				$n = 2;
				while (in_array($test, $files_temp))
				{
					$test = preg_replace('`^(.+)\.([^\.]+)$`', '\1_' . $n . '.\2', $filename);
					$n++;
				}
				$filename = $test;
				if (!in_array($filename, $files_temp))
				{
					$files_temp[] = $filename;
				}

				// On ajoute le fichier à l'archive.
				if (!$zip_archive->addFile($f, $filename))
				{
					throw new Exception('ZipArchive addFile error.');
				}
			}
			unset($files_temp);

			// Fermeture de l'archive.
			if (!$zip_archive->close())
			{
				throw new Exception('ZipArchive close error.');
			}

			// Options de configuration.
			if (function_exists('apache_setenv'))
			{
				apache_setenv('no-gzip', 1);
			}
			if (function_exists('ini_set'))
			{
				ini_set('zlib.output_compression', 0);
			}
			if (function_exists('set_time_limit'))
			{
				set_time_limit(300);
			}

			// Paramètres du fichier.
			$mtime = ($mtime = filemtime($zip_temp_file)) ? $mtime : gmtime();
			$filesize = intval(sprintf('%u', filesize($zip_temp_file)));

			// En-têtes.
			header('Content-Type: application/octet-stream');
			header('Content-Disposition: attachment; filename="' . $zip_filename
				. '"; modification-date="' . date('r', $mtime) . '";');

			// Envoi de l'archive.
			$chunksize = 1 * (1024 * 1024);
			if ($filesize > $chunksize)
			{
				$handle = fopen($zip_temp_file, 'rb');
				if ($handle === FALSE)
				{
					throw new Exception('ZipArchive fopen error.');
				}
				while (!feof($handle))
				{
					$r = fread($handle, $chunksize);
					if ($r === FALSE)
					{
						throw new Exception('ZipArchive fread error.');
					}
					echo $r;
					ob_flush();
					flush();
				}
				fclose($handle);
			}
			else if (readfile($zip_temp_file) === FALSE)
			{
				throw new Exception('ZipArchive readfile error.');
			}
		}
		catch (Exception $e)
		{
			trigger_error($e->getMessage(), E_USER_WARNING);
		}

		// Suppression du fichier temporaire.
		if (file_exists($zip_temp_file))
		{
			unlink($zip_temp_file);
		}

		exit;
	}



	/**
	 * Décode les éléménts HTML autorisés.
	 *
	 * @param array $str
	 * @return string
	 */
	private static function _checkHTML($str)
	{
		// "\" forcément plus que suspect.
		if (strstr($str[0], '\\'))
		{
			return $str[0];
		}

		// Suppression des chevrons ouvrant et fermant
		// pour les balises ouvrantes et fermantes.
		$s = preg_replace('`^&lt;/?|&gt;$`', '', $str[0]);

		// Récupèration du nom de la balise.
		$tag = explode(' ', $s, 2);

		// Création d'un tableau contenant les attributs de la balise.
		$s = trim(preg_replace('`^' . $tag[0] . ' `', '', $s));
		$attrs = array_map('trim', preg_split('`(?<=&quot;) `', $s, 15));

		$ok = FALSE;

		// Est-ce une balise autorisée ?
		if (in_array($tag[0], self::allowedTags()))
		{
			$ok = TRUE;

			// Si la balise contient des attributs, on les analyse un à un.
			if (isset($attrs[1]) || $attrs[0] != $tag[0])
			{
				// Suppression du slash de fermeture pour les balises atomiques.
				if (($tag[0] == 'img' || $tag[0] == 'hr')
				&& $attrs[count($attrs) - 1] == '/')
				{
					unset($attrs[count($attrs) - 1]);
				}

				// Vérification de chaque attribut.
				for ($i = 0, $count = count($attrs); $i < $count; $i++)
				{
					$attr = explode('=', $attrs[$i], 2);

					// Est-ce un attribut autorisé ?
					if (self::allowedAttrs($attr[0]) === FALSE)
					{
						$ok = FALSE;
						break;
					}

					// Filtrage de l'attribut "href".
					if ($attr[0] == 'href')
					{
						if (preg_match('`^&quot;\s*javascript:..+&quot;$`i', $attr[1]))
						{
							$ok = FALSE;
							break;
						}
					}

					// Filtrage de l'attribut "src".
					if ($attr[0] == 'src')
					{
						$regex = '(?:' . preg_quote(GALLERY_HOST) . ')?';
						$regex .= preg_quote(CONF_GALLERY_PATH);
						$regex .= '/(?:image\.php\?id=\d{1,11}|';
						$regex .= 'images/[/0-9a-z_-]+\.(?:jpe?g|gif|png))';
						$regex = '`^&quot;' . $regex . '&quot;$`i';

						if (!preg_match($regex, $attr[1]))
						{
							$ok = FALSE;
							break;
						}
					}

					// Vérification de la présence d'entités HTML autres que &#039; et &amp;
					$value = preg_replace('`^' . $attr[0]
						. '=&quot;|&quot;$`', '', $attrs[$i]);
					$test = str_replace(array('&#039;', '&amp;'), '', $value);
					if (strstr($test, '&'))
					{
						$ok = FALSE;
						break;
					}

					// Vérification de la valeur de l'attribut.
					$regex = '^' . self::allowedAttrs($attr[0]) . '$';
					if (!mb_eregi($regex, $value))
					{
						$ok = FALSE;
						break;
					}
				}
			}
		}

		// Si la balise est OK, on décode les caractères convertis par
		// htmlspecialchars(), sinon on renvoi la chaîne sans la modifier.
		return ($ok)
			? htmlspecialchars_decode($str[0], ENT_QUOTES)
			: $str[0];
	}
}
?>