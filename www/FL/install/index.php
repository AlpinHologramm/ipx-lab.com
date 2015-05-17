<?php
/**
 * Installation de l'application.
 *
 * @license http://www.gnu.org/licenses/gpl.html
 * @link http://www.igalerie.org/
 */

require_once(dirname(__FILE__) . '/../includes/prepend.php');

// Extraction de la requête.
extract_request(array(
	'step' => array
	(
		'([123])' =>
			array('step')
	)
));

// Initialisation.
install::init();

// Section par défaut.
if (!isset($_GET['section']))
{
	$_GET['section'] = 'introduction';
}

// Traitement de la requête.
switch ($_GET['section'])
{
	case 'installed' :
		install::$tplFile = 'installed';
		break;

	case 'introduction' :
		install::$prefs->add('test', '123456789');
		install::$tplFile = 'introduction';
		break;

	case 'step' :
		switch ($_GET['step'])
		{
			case 1 :
				install::step1();
				break;

			case 2 :
				install::step2();
				break;

			case 3 :
				install::step3();
				break;
		}
		install::$tplFile = 'step_' . $_GET['step'];
		break;
}

// Fermeture de la connexion.
if (is_object(utils::$db))
{
	utils::$db->connexion = NULL;
}

// Création de l'objet de template.
$tpl = new tpl();

// Écriture des données de cookie.
install::$prefs->write();

// Chargement du template.
utils::headersNoCache();
require_once(GALLERY_ROOT . utils::$purlDir . '/template/index.tpl.php');



/**
 * Traitements pour chaque section.
 */
class install
{
	/**
	 * Nom du champ de formulaire pour lequel il y a une erreur.
	 *
	 * @var array
	 */
	public static $fieldsError = array();

	/**
	 * Informations diverses à destination du template.
	 *
	 * @var array
	 */
	public static $infos = array();

	/**
	 * Instance du gestionnaire de cookie pour les préférences utilisateurs.
	 *
	 * @var object
	 */
	public static $prefs;

	/**
	 * Rapport détaillé des mises à jour.
	 *
	 * @var array
	 */
	public static $report = array();

	/**
	 * Nom du fichier de template à inclure.
	 *
	 * @var string
	 */
	public static $tplFile;



	/**
	 * Initialisation.
	 *
	 * @return void
	 */
	public static function init()
	{
		// On ne teste pas les permissions d'écritures sur le fichier
		// "config/conf.php" car il sera créé au cours de l'installation.
		unset(system::$writePermissions['config/conf.php']);

		utils::$config = array();
		utils::$config['users_password_minlength'] = 6;

		// Paramètres pour les URL.
		utils::$purlUrlRewrite = FALSE;
		utils::$purlDir = '/' . basename(dirname(__FILE__));
		utils::$purlFile = 'index.php';

		// Langue d'installation.
		self::_installLang();

		// La galerie est-elle déjà installée ?
		if (CONF_INSTALL)
		{
			$_GET['section'] = 'installed';
			return;
		}

		// Vérification système pour chaque étape supérieure à la 1.
		if (isset($_GET['step']) && $_GET['step'] > 1
		&& !self::isSystemCompatible())
		{
			$_GET['section'] = 'step';
			$_GET['step'] = '1';
			return;
		}

		// Si l'étape 2 à déjà été passée avec succès,
		// on force  le passage à l'étape 3.
		if ((isset($_GET['step']) && $_GET['step'] > 2) === FALSE
		&& (isset($_GET['step']) && $_GET['step'] == 2 && !empty($_POST)) === FALSE
		&& CONF_KEY != '' && md5(CONF_KEY) == self::$prefs->read('key'))
		{
			$_GET['section'] = 'step';
			$_GET['step'] = '3';
			return;
		}

		if (is_object(utils::$db))
		{
			utils::$db->msgFailure = __('L\'installation a échouée car'
				. ' une erreur de base de données est survenue.');
		}
	}

	/**
	 * Crée un rapport sur les actions effectuées.
	 *
	 * @param object|string $msg
	 * @return void
	 */
	public static function report($msg)
	{
		$msg = (is_object($msg))
			? explode(':', $msg->getMessage(), 2)
			: explode(':', $msg, 2);
		$message = $msg[1];
		self::$report[$msg[0]] = $message;
	}

	/**
	 * Étape 1 : vérification système.
	 *
	 * @return void
	 */
	public static function step1()
	{
		// Extensions.
		system::getPHPExtensions();
	}

	/**
	 * Étape 2 : informations MySQL.
	 *
	 * @return void
	 */
	public static function step2()
	{
		// Quelques vérifications.
		if (!isset($_POST['server']) || strlen($_POST['server']) > 256
		 || !isset($_POST['user']) || strlen($_POST['user']) > 128
		 || !isset($_POST['password']) || strlen($_POST['password']) > 128
		 || !isset($_POST['database']) || strlen($_POST['database']) > 128
		 || !isset($_POST['prefix']) || strlen($_POST['prefix']) > 32)
		{
			return;
		}

		try
		{
			self::$infos['mysql_schema_create'] = TRUE;
			self::$infos['config_file_create'] = TRUE;
			self::$infos['config_file_update'] = TRUE;

			// Certains champs sont obligatoires.
			$field_empty = FALSE;
			foreach (array('server', 'user', 'database') as $field)
			{
				if (utils::isEmpty($_POST[$field]))
				{
					self::$fieldsError[] = $field;
					$field_empty = TRUE;
				}
			}
			if ($field_empty)
			{
				throw new Exception('warning:'
					. __('Certains champs n\'ont pas été renseignés.'));
			}

			// Vérification du champ "préfixe des tables".
			if (!preg_match('`^[a-z0-9_]{0,32}$`i', $_POST['prefix']))
			{
				self::$fieldsError[] = 'prefix';
				throw new Exception('warning:'
					. __('Le préfixe des tables ne doit comporter que des caractères'
					. ' alphanumériques ou le caractère de soulignement (_).'));
			}

			// On supprime le protocole dans l'adresse du serveur.
			$_POST['server'] = preg_replace('`^http://`', '', $_POST['server']);

			// On vérifie si l'on peut se connecter à la base de données.
			$_POST['dsn'] = 'mysql:host=' . $_POST['server'] . ';dbname=' . $_POST['database'];
			utils::$db = new db($_POST['dsn'], $_POST['user'], $_POST['password']);
			if (utils::$db->connexion === NULL)
			{
				throw new Exception('warning:'
					. __('Impossible de se connecter à la base de données.')
					. utils::$db->msgError);
			}

			// Version de MySQL.
			self::$infos['mysql_version'] = system::getMySQLVersion();
			self::$infos['mysql_version_compatible'] = system::isMySQLVersionCompatible();
			if (!self::$infos['mysql_version_compatible'])
			{
				return;
			}

			// Création des tables.
			$sql_file = dirname(__FILE__) . '/schema_mysql.sql';
			if (($sql_content = files::getSQLFileContent($sql_file)) === FALSE)
			{
				self::$infos['mysql_schema_create'] = FALSE;
				self::$infos['mysql_schema_error'] = __('Impossible de lire le fichier SQL.');
				return;
			}
			$sql_content = str_replace('igal2_', $_POST['prefix'], $sql_content);
			foreach ($sql_content as $k => $sql)
			{
				if (utils::$db->exec($sql, FALSE) === FALSE)
				{
					self::$infos['mysql_schema_create'] = FALSE;
					self::$infos['mysql_schema_error'] =
						__('Une erreur s\'est produite durant la création des tables :')
						. ' [#' . ($k + 1) . ' - ' . mb_strimwidth($sql, 0, 50, '...') . '] '
						. utils::$db->msgError;
					return;
				}
			}

			// Création du fichier de configuration.
			$config_default_file = GALLERY_ROOT . '/config/conf_default.php';
			$config_file = GALLERY_ROOT . '/config/conf.php';
			if (!file_exists($config_file))
			{
				if (!file_exists($config_default_file)
				|| files::copyFile($config_default_file, $config_file) === FALSE
				|| !file_exists($config_file))
				{
					self::$infos['config_file_create'] = FALSE;
					return;
				}
			}

			// Mise à jour du fichier de configuration.
			$_POST['key'] = utils::genKey(FALSE, 40);
			$_POST['errors_log'] = 1;
			$const = array(
				'CONF_DB_USER' => 'user',
				'CONF_DB_PASS' => 'password',
				'CONF_DB_DSN' => 'dsn',
				'CONF_DB_PREF' => 'prefix',
				'CONF_ERRORS_LOG' => 'errors_log',
				'CONF_KEY' => 'key'
			);
			$config_values = array();
			if (files::changeConfig($const, $config_values) === FALSE)
			{
				self::$infos['config_file_update'] = FALSE;
				return;
			}

			// Ajout de la clé de la galerie au cookie d'installation.
			self::$prefs->add('key', md5($_POST['key']));
		}
		catch (Exception $e)
		{
			self::report($e);
		}
	}

	/**
	 * Étape 3 : informations galerie.
	 *
	 * @return void
	 */
	public static function step3()
	{
		// On ne doit pas pouvoir accèder à cette étape
		// sans passer par la précédente !
		if (CONF_KEY == '')
		{
			header('Location: ./');
			return;
		}

		// Quelques vérifications.
		if (!isset($_POST['login']) || strlen($_POST['login']) > 64
		 || !isset($_POST['password']) || strlen($_POST['password']) > 128
		 || !isset($_POST['password_confirm']) || strlen($_POST['password_confirm']) > 128
		 || !isset($_POST['email']) || strlen($_POST['email']) > 128
		 || !isset($_POST['title']) || strlen($_POST['title']) > 128
		 || !isset($_POST['url']) || strlen($_POST['url']) > 256
		 || !isset($_POST['lang_default'])
		 || !user::checkForm('lang', $_POST['lang_default'])
		 || !isset($_POST['tz_default'])
		 || !user::checkForm('tz', $_POST['tz_default']))
		{
			return;
		}

		try
		{
			// Certains champs sont obligatoires.
			$field_empty = FALSE;
			$fields = array('login', 'password', 'password_confirm', 'email', 'title', 'url');
			foreach ($fields as &$field)
			{
				if (utils::isEmpty($_POST[$field]))
				{
					self::$fieldsError[] = $field;
					$field_empty = TRUE;
				}
			}
			if ($field_empty)
			{
				throw new Exception('warning:'
					. __('Certains champs n\'ont pas été renseignés.'));
			}

			// Nom d'utilisateur.
			if (!preg_match('`^[-a-z0-9@_.]+$`i', $_POST['login']))
			{
				self::$fieldsError[] = 'login';
				throw new Exception('warning:'
					. __('Le nom d\'utilisateur ne doit comporter'
					. ' aucun espace, caractère spécial ou accentué.'));
			}

			// Mot de passe.
			if (($check = user::checkForm('pwd', $_POST['password'])) !== TRUE)
			{
				self::$fieldsError[] = 'password';
				throw new Exception('warning:' . $check);
			}

			// Confirmation du mot de passe.
			if ($_POST['password'] !== $_POST['password_confirm'])
			{
				self::$fieldsError[] = 'password_confirm';
				throw new Exception('warning:'
					. __('Les mots de passe ne correspondent pas.'));
			}

			// Adresse de courriel.
			if (!preg_match('`^' . utils::regexpEmail() . '$`i', $_POST['email']))
			{
				self::$fieldsError[] = 'email';
				throw new Exception('warning:'
					. __('Format de l\'adresse de courriel incorrect.'));
			}

			// URL de la galerie
			if (!preg_match('`^http://.+/.+\..+$`i', $_POST['url']))
			{
				self::$fieldsError[] = 'url';
				throw new Exception('warning:'
					. __('Format de l\'URL de la galerie incorrect.'));
			}

			// Connexion à la base de données.
			utils::$db = new db();
			if (utils::$db->connexion === NULL)
			{
				throw new Exception('error:'
					. __('Impossible de se connecter à la base de données.'));
			}

			// Récupération de la configuration de la galerie.
			$sql = 'SELECT *
					  FROM ' . CONF_DB_PREF . 'config
					 WHERE conf_name NOT LIKE "blacklist%"';
			$fetch_style = array(
				'column' => array('conf_name', 'conf_value')
			);
			if (utils::$db->query($sql, $fetch_style) === FALSE
			|| utils::$db->nbResult === 0)
			{
				throw new Exception('error:Missing data in the database.');
			}
			$config = utils::$db->queryResult;
			$config['locale_langs'] = utils::$config['locale_langs'];
			utils::$config =& $config;
			utils::$config['pages_params'] = unserialize(utils::$config['pages_params']);

			// On démarre une transaction.
			if (!utils::$db->transaction())
			{
				throw new Exception('error:' . utils::$db->msgError);
			}

			utils::$config['key'] = utils::genKey(FALSE, 40);
			$current_date = date('Y-m-d H:i:s', time());

			// Mise à jour du compte super-administrateur.
			self::$infos['db_update'] = FALSE;
			$sql = 'UPDATE ' . CONF_DB_PREF . 'users
					   SET user_login = :user_login,
						   user_password = :user_password,
						   user_email = :user_email,
						   user_lang = :user_lang,
						   user_tz = :user_tz,
						   user_crtdt = :user_crtdt,
						   user_crtip = :user_crtip
					 WHERE user_id = 1
					 LIMIT 1';
			$params = array(
				'user_login' => $_POST['login'],
				'user_password' => utils::hashPassword($_POST['password'], $current_date),
				'user_email' => $_POST['email'],
				'user_lang' => $_POST['lang_default'],
				'user_tz' => $_POST['tz_default'],
				'user_crtdt' => $current_date,
				'user_crtip' => $_SERVER['REMOTE_ADDR']
			);
			if (utils::$db->prepare($sql) === FALSE
			|| utils::$db->executeExec($params) === FALSE
			|| utils::$db->nbResult === 0)
			{
				self::$infos['db_update_error'] = utils::$db->msgError;
				return;
			}

			// Ajout de l'adresse de courriel à la page "contact".
			utils::$config['pages_params']['contact']['email'] = $_POST['email'];

			// Adresse de courriel pour les notifications.
			$mail_auto_sender_address = ($_SERVER['HTTP_HOST'] == 'localhost'
				   || $_SERVER['HTTP_HOST'] == '127.0.0.1')
				? $_POST['email']
				: 'igalerie@' . preg_replace('`^www\.`', '', $_SERVER['HTTP_HOST']);

			// Mise à jour de la table 'config'.
			$columns = array(
				'"gallery_title" THEN :gallery_title',
				'"history" THEN :history',
				'"key" THEN :key',
				'"locale_langs" THEN :locale_langs',
				'"mail_auto_sender_address" THEN :mail_auto_sender_address',
				'"pages_params" THEN :pages_params',
				'"version" THEN :version',
			);
			$sql = 'UPDATE ' . CONF_DB_PREF . 'config
					   SET conf_value = CASE conf_name
						   WHEN ' . implode(' WHEN ', $columns) . '
						   ELSE conf_value END';
			$params = array(
				'gallery_title' => $_POST['title'],
				'history' => serialize(array(system::$galleryVersion => $current_date)),
				'key' => utils::$config['key'],
				'locale_langs' => serialize(utils::$config['locale_langs']),
				'mail_auto_sender_address' => $mail_auto_sender_address,
				'pages_params' => serialize(utils::$config['pages_params']),
				'version' => system::$galleryVersionDate
			);
			if (utils::$db->prepare($sql) === FALSE
			|| utils::$db->executeExec($params) === FALSE)
			{
				self::$infos['db_update_error'] = utils::$db->msgError;
				return;
			}

			// Chemin de la galerie.
			$_POST['path'] = preg_replace('`^http://[^/]+(/.+)$`i', '$1', $_POST['url']);
			$_POST['path'] = str_replace(' ', '%20', $_POST['path']);
			$_POST['path'] = dirname($_POST['path']);
			$_POST['path'] = (preg_match('`^[./]*$`', $_POST['path'])) ? '' : $_POST['path'];
			$_POST['path'] = preg_replace('`(?:[\x5c]|/+$)`', '', $_POST['path']);

			// Mise à jour du fichier de configuration.
			self::$infos['config_file_update'] = TRUE;
			$_POST['install'] = 1;
			$const = array(
				'CONF_INSTALL' => 'install',
				'CONF_GALLERY_PATH' => 'path',
				'CONF_DEFAULT_LANG' => 'lang_default',
				'CONF_DEFAULT_TZ' => 'tz_default'
			);
			$config_values = array();
			if (files::changeConfig($const, $config_values) === FALSE)
			{
				self::$infos['config_file_update'] = FALSE;
				return;
			}

			// Modification de la directive RewriteBase et création du fichier .htaccess.
			$htaccess_file = dirname(__FILE__) . '/../htaccess';
			if (file_exists($htaccess_file))
			{
				$htaccess_content = files::fileGetContents($htaccess_file);
				if ($htaccess_content !== FALSE)
				{
					$htaccess_content = preg_replace(
						'`#(\s+RewriteBase\s+)/`',
						'$1' . $_POST['path'] . '/',
						$htaccess_content
					);
					files::filePutContents($htaccess_file, $htaccess_content);
				}
				files::renameFile($htaccess_file, dirname(__FILE__) . '/../.htaccess');
			}

			// Exécution de la transaction.
			if ((self::$infos['db_update'] = utils::$db->commit()) === FALSE)
			{
				self::$infos['db_update_error'] = utils::$db->msgError;
				return;
			}

			self::$infos['install_success'] = TRUE;
		}
		catch (Exception $e)
		{
			self::report($e);
		}
	}

	/**
	 * Détermine si le système est compatible avec la galerie.
	 *
	 * @return boolean
	 */
	public static function isSystemCompatible()
	{
		// Version de PHP et de GD.
		if (!system::isPHPVersionCompatible()
		 || !system::isGDVersionCompatible())
		{
			return FALSE;
		}

		// Extensions PDO, pdo_mysql et SimpleXML.
		system::getPHPExtensions();
		if (!system::$phpExtensions['PDO']
		 || !system::$phpExtensions['pdo_mysql']
		 || !system::$phpExtensions['SimpleXML'])
		{
			return FALSE;
		}

		// Permissions d'accès en écriture.
		system::getWritePermissions();
		foreach (system::$writePermissions as &$i)
		{
			if (!$i[0])
			{
				return FALSE;
			}
		}

		return TRUE;
	}



	/**
	 * Détermine la langue d'installation.
	 *
	 * @return void
	 */
	private static function _installLang()
	{
		// Cookie d'installation.
		self::$prefs = new cookie('igal_install', 604800);

		// Récupération des langues disponibles.
		$locale_path = GALLERY_ROOT . '/locale';
		$files = scandir($locale_path);
		foreach ($files as &$f)
		{
			if (!is_dir($locale_path . '/' . $f)
			 || !preg_match('`^[a-z]{2}_[A-Z]{2}$`', $f)
			 || !file_exists($locale_path . '/' . $f . '/lang.php'))
			{
				continue;
			}

			include($locale_path . '/' . $f . '/lang.php');
			utils::$config['locale_langs'][$f] = $name;
		}

		// Post.
		if (isset($_POST['lang'])
		&& preg_match('`^[a-z]{2}_[A-Z]{2}$`', $_POST['lang'])
		&& isset(utils::$config['locale_langs'][$_POST['lang']]))
		{
			utils::$userLang = $_POST['lang'];
			self::$prefs->add('lang', $_POST['lang']);
		}

		// Cookie.
		else if (preg_match('`^[a-z]{2}_[A-Z]{2}$`', self::$prefs->read('lang'))
		&& isset(utils::$config['locale_langs'][self::$prefs->read('lang')]))
		{
			utils::$userLang = self::$prefs->read('lang');
		}

		// Client.
		else
		{
			utils::$config['locale_langs'] = utils::$config['locale_langs'];
			utils::detectClientLang();
		}

		// Chargement du fichier de langue.
		utils::locale();
	}
}



/**
 * Méthodes de template.
 */
class tpl
{
	/**
	 * Indique que l'on peut afficher les erreurs à partir de maintenant.
	 *
	 * @return void
	 */
	public function displayErrors()
	{
		error::displayErrors();
	}

	/**
	 * Retourne l'élément de l'installation $item.
	 *
	 * @param string $item
	 * @return string
	 */
	public function getInstall($item)
	{
		switch ($item)
		{
			case 'admin_link' :
				$path = (isset($_POST['path']))
					? $_POST['path']
					: CONF_GALLERY_PATH;
				return $path . '/' . CONF_ADMIN_DIR . '/';

			case 'charset' :
				return CONF_CHARSET;

			case 'gallery_version' :
				return system::$galleryVersion;

			case 'lang_current_code' :
				return utils::$userLang;

			case 'langs_switch' :
				$langs = '';
				foreach (utils::$config['locale_langs'] as $code => &$name)
				{
					$selected = ($code == utils::$userLang)
						? ' selected="selected"'
						: '';
					$langs .= '<option' . $selected . ' value="'
						. utils::tplProtect($code) . '">'
						. utils::tplProtect($name) . '</option>';
				}
				return $langs;
		}
	}

	/**
	 * Inclusion de fichier de template.
	 *
	 * @return void
	 */
	public function includePage()
	{
		$tpl =& $this;
		include_once(GALLERY_ROOT
			. '/install/template/' . install::$tplFile . '.tpl.php'
		);
	}

	/**
	 * Doit-on afficher l'élément $item de l'étape 3 ?
	 *
	 * @param string $item
	 * @return boolean
	 */
	public function disGalleryStep($item = '')
	{
		switch ($item)
		{
			case 'form' :
				return empty(install::$infos);

			case 'install_success' :
				return isset(install::$infos['install_success']);
		}
	}

	/**
	 * Retourne l'élément $item de l'étape 3.
	 *
	 * @param string $item
	 * @return string
	 */
	public function getGalleryStep($item)
	{
		switch ($item)
		{
			case 'error' :
				if (isset(install::$infos['db_update_error']))
				{
					return utils::tplProtect(install::$infos['db_update_error']);
				}
				if (isset(install::$infos['config_file_create'])
				&& install::$infos['config_file_create'] === FALSE)
				{
					return __('Impossible de créer le fichier de configuration.');
				}
				if (isset(install::$infos['config_file_update'])
				&& install::$infos['config_file_update'] === FALSE)
				{
					return __('Impossible de modifier le fichier de configuration.');
				}
				return 'unknown error';

			case 'email' :
			case 'password' :
			case 'password_confirm' :
				return (isset($_POST[$item]))
					? utils::tplProtect($_POST[$item])
					: '';

			case 'langs_list' :
				$lang = CONF_DEFAULT_LANG;
				if (isset($_POST['lang_default']))
				{
					$lang = $_POST['lang_default'];
				}
				else if (install::$prefs->read('lang') !== FALSE)
				{
					$lang = install::$prefs->read('lang');
				}

				return template::langSelect($lang);

			case 'login' :
				return (isset($_POST['login']))
					? utils::tplProtect($_POST['login'])
					: '';

			case 'password_minlength' :
				return (int) utils::$config['users_password_minlength'];

			case 'title' :
				return (isset($_POST['title']))
					? utils::tplProtect($_POST['title'])
					: __('Ma galerie');

			case 'tz_list' :
				$tz = (isset($_POST['tz_default']))
					? $_POST['tz_default']
					: CONF_DEFAULT_TZ;
				return str_replace(
					'value="' . $tz . '"',
					'value="' . $tz . '" selected="selected"',
					file_get_contents(GALLERY_ROOT . '/includes/tz.html')
				);

			case 'url' :
				$script_name = preg_replace('`^http://[^/]+/`', '/', $_SERVER['SCRIPT_NAME']);
				$gallery_path = dirname(dirname($script_name));
				$gallery_path = (preg_match('`^[./]*$`', $gallery_path))
					? ''
					: preg_replace('`(?:[\x5c]|/+$)`', '', $gallery_path);
				return (isset($_POST['url']))
					? utils::tplProtect($_POST['url'])
					: GALLERY_HOST . $gallery_path . '/index.php';
		}
	}

	/**
	 * Doit-on afficher l'élément $item de l'étape 2 ?
	 *
	 * @param string $item
	 * @return boolean
	 */
	public function disMySQLStep($item = '')
	{
		switch ($item)
		{
			case 'form' :
				return !isset(install::$infos['mysql_version']);
		}
	}

	/**
	 * Retourne l'élément $item de l'étape 2.
	 *
	 * @param string $item
	 * @return string
	 */
	public function getMySQLStep($item)
	{
		switch ($item)
		{
			case 'config_file' :
				return (install::$infos['config_file_update'])
					? __('réussie')
					: __('échouée');

			case 'config_file_status' :
				return (install::$infos['config_file_update'])
					? 'ok'
					: 'error';

			case 'database' :
				return (isset($_POST['database']))
					? utils::tplProtect($_POST['database'])
					: '';

			case 'mysql_schema_create' :
				return (install::$infos['mysql_schema_create'])
					? __('réussie')
					: __('échouée');

			case 'mysql_schema_error' :
				return utils::tplProtect(install::$infos['mysql_schema_error']);

			case 'mysql_schema_status' :
				return (install::$infos['mysql_schema_create'])
					? 'ok'
					: 'error';

			case 'password' :
				return (isset($_POST['password']))
					? utils::tplProtect($_POST['password'])
					: '';

			case 'prefix' :
				return (isset($_POST['prefix']))
					? utils::tplProtect($_POST['prefix'])
					: 'igal2_';

			case 'server' :
				return (isset($_POST['server']))
					? utils::tplProtect($_POST['server'])
					: '';

			case 'user' :
				return (isset($_POST['user']))
					? utils::tplProtect($_POST['user'])
					: '';
		}
	}

	/**
	 * Retourne l'information système $item.
	 *
	 * @param string $item
	 * @return string
	 */
	public function getSystemInfo($item)
	{
		switch ($item)
		{
			case 'cookies' :
				return (install::$prefs->read('test') == '123456789')
					? __('activés')
					: __('désactivés');

			case 'cookies_status' :
				return (install::$prefs->read('test') == '123456789')
					? 'ok'
					: 'warning';

			case 'extension_exif' :
				return (system::$phpExtensions['exif'])
					? __('chargée')
					: __('non chargée');

			case 'extension_exif_status' :
				return (system::$phpExtensions['exif'])
					? 'ok'
					: 'warning';

			case 'extension_mbstring' :
				return (system::$phpExtensions['mbstring'])
					? __('chargée')
					: __('non chargée');

			case 'extension_mbstring_status' :
				return (system::$phpExtensions['mbstring'])
					? 'ok'
					: 'error';

			case 'extension_pdo' :
				return (system::$phpExtensions['PDO'])
					? __('chargée')
					: __('non chargée');

			case 'extension_pdo_status' :
				return (system::$phpExtensions['PDO'])
					? 'ok'
					: 'error';

			case 'extension_pdo_mysql' :
				return (system::$phpExtensions['pdo_mysql'])
					? __('chargée')
					: __('non chargée');

			case 'extension_pdo_mysql_status' :
				return (system::$phpExtensions['pdo_mysql'])
					? 'ok'
					: 'error';

			case 'extension_zip' :
				return (system::$phpExtensions['zip'])
					? __('chargée')
					: __('non chargée');

			case 'extension_zip_status' :
				return (system::$phpExtensions['zip'])
					? 'ok'
					: 'warning';

			case 'extension_simplexml' :
				return (system::$phpExtensions['SimpleXML'])
					? __('chargée')
					: __('non chargée');

			case 'extension_simplexml_status' :
				return (system::$phpExtensions['SimpleXML'])
					? 'ok'
					: 'error';

			case 'gallery_version' :
				return system::$galleryVersion;

			case 'gd_version' :
				return utils::tplProtect(system::getGDVersion());

			case 'gd_version_compatible' :
				return (system::isGDVersionCompatible())
					? 'ok'
					: 'error';

			case 'gd_version_min' :
				return system::$minGDVersion;

			case 'mysql_version' :
				return utils::tplProtect(install::$infos['mysql_version']);

			case 'mysql_version_compatible' :
				return install::$infos['mysql_version_compatible']
					? 'ok'
					: 'error';

			case 'mysql_version_min' :
				return system::$minMySQLVersion;

			case 'php_version' :
				return utils::tplProtect(system::getPHPVersion(TRUE));

			case 'php_version_compatible' :
				return (system::isPHPVersionCompatible())
					? 'ok'
					: 'error';

			case 'php_version_min' :
				return system::$minPHPVersion;

			case 'write_permissions_status':
				foreach (system::$writePermissions as $f => &$i)
				{
					if (!$i[0])
					{
						return 'error';
					}
				}
				return 'ok';
		}
	}

	/**
	 * L'élément de rapport $item doit-il être affiché ?
	 *
	 * @param string $item
	 * @return boolean
	 */
	public function disReport($item = '')
	{
		switch ($item)
		{
			case 'error' :
			case 'warning' :
				return !empty(install::$report[$item]);

			default :
				return $this->disReport('error')
					|| $this->disReport('warning');

			case 'field_error' :
				return !empty(install::$fieldsError);
		}
	}

	/**
	 * Retourne l'élément de rapport $item.
	 *
	 * @param string $item
	 * @return string
	 */
	public function getReport($item)
	{
		switch ($item)
		{
			// Avertissements et erreurs.
			case 'error' :
			case 'warning' :
				return nl2br(utils::tplProtect(install::$report[$item]));

			case 'field_error' :
				return '["' . implode('", "', install::$fieldsError) . '"]';
		}
	}

	/**
	 * Retourne l'élément de permission $item.
	 *
	 * @param string $item
	 * @return string
	 */
	public function getWritePermission($item)
	{
		$i = current(system::$writePermissions);

		switch ($item)
		{
			// Nom de fichier.
			case 'name' :
				return utils::tplProtect(key(system::$writePermissions));

			// Permissions.
			case 'perms' :
				return utils::tplProtect($i[1]);

			// Statut.
			case 'status' :
				return $i[0]
					? 'ok'
					: 'error';

			// Valeur.
			case 'value' :
				return $i[0]
					? __('oui')
					: __('non');
		}
	}

	/**
	 * Y a-t-il un prochain fichier ?
	 *
	 * @return boolean
	 */
	public function nextWritePermission()
	{
		static $next = -1;

		if ($next === -1)
		{
			system::getWritePermissions();
		}

		return template::nextObject(system::$writePermissions, $next);
	}
}
?>