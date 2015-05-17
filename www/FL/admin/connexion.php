<?php
/**
 * Console de connexion au panneau d'administration.
 *
 * @license http://www.gnu.org/licenses/gpl.html
 * @link http://www.igalerie.org/
 */

require_once(dirname(__FILE__) . '/../includes/prepend.php');

// Extraction de la requête.
extract_request(array(
	'forgot' => array(),
	'new-password' => array(),
	'session-expire' => array()
));

// Section par défaut.
if (!isset($_GET['section']))
{
	$_GET['section'] = 'login';
}

// Initialisation.
connexion::init();

// Traitement de la requête.
switch ($_GET['section'])
{
	case 'forgot' :
		connexion::forgot();
		connexion::$tplFile = 'forgot';
		break;

	case 'login' :
		connexion::login();
		connexion::$tplFile = 'login';
		break;

	case 'new-password' :
		connexion::newPassword();
		connexion::$tplFile = 'new_password';
		break;

	case 'session-expire' :
		connexion::report('warning:' . __('Votre session a expiré.'));
		connexion::login();
		connexion::$tplFile = 'login';
		break;
}

// Fermeture de la connexion.
utils::$db->connexion = NULL;

// Création de l'objet de template.
$tpl = new tpl();

// Chargement du template.
utils::headersNoCache();
require_once(GALLERY_ROOT . utils::$purlDir . '/template/'
	. utils::$config['admin_template'] . '/connexion.tpl.php');



/**
 * Traitements pour chaque section.
 */
class connexion
{
	/**
	 * Nouveau mot de passe.
	 *
	 * @var string
	 */
	public static $newPassword;

	/**
	 * Rapport des actions effectuées.
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
	 * Récupération d'un nouveau mot de passe.
	 *
	 * @return void
	 */
	public static function forgot()
	{
		if (!isset($_POST['login']) || !isset($_POST['email']))
		{
			return;
		}

		if (!user::forgot($_POST['login'], $_POST['email']))
		{
			self::report('warning:' . __('Informations incorrectes.'));
			return;
		}

		self::report('success:' . __('Un courriel avec les indications'
			. ' à suivre vous a été envoyé.'));
	}

	/**
	 * Initialisation.
	 *
	 * @return void
	 */
	public static function init()
	{
		// Paramètres pour les URL.
		utils::$purlUrlRewrite = FALSE;
		utils::$purlDir = '/' . basename(dirname(__FILE__));
		utils::$purlFile = 'connexion.php';

		// Connexion à la base de données.
		utils::$db = new db();
		if (utils::$db->connexion === NULL)
		{
			die('Unable to connect to the database.');
		}

		// Récupération des paramètres de configuration.
		$sql = 'SELECT *
				  FROM ' . CONF_DB_PREF . 'config';
		$fetch_style = array(
			'column' => array('conf_name', 'conf_value')
		);
		if (utils::$db->query($sql, $fetch_style) === FALSE
		|| utils::$db->nbResult === 0)
		{
			throw new Exception('Missing data in the database.');
		}
		utils::$config = utils::$db->queryResult;
		utils::$config['locale_langs'] = unserialize(utils::$config['locale_langs']);

		// Cookie des préférences utilisateur.
		utils::$cookiePrefs = new cookie('igal_prefs', 31536000, CONF_GALLERY_PATH);

		// Si l'utilisateur est un administrateur avec session valide,
		// alors on redirige vers le tableau de bord.
		utils::$cookieSession = new cookie('igal_session', 8640000, CONF_GALLERY_PATH);
		if (($session_token = user::getSessionCookieToken()) !== FALSE)
		{
			// Récupération des informations et permissions utilisateur
			// si identifiant de session valide.
			$sql = 'SELECT 1
					  FROM ' . CONF_DB_PREF . 'sessions AS s,
						   ' . CONF_DB_PREF . 'groups AS g,
						   ' . CONF_DB_PREF . 'users AS u
					 WHERE u.session_id = s.session_id
					   AND u.group_id = g.group_id
					   AND user_status = "1"
					   AND session_token = :session_token
					   AND session_expire > NOW()
					   AND group_admin = "1"';
			$params = array(
				'session_token' => $session_token
			);
			if (utils::$db->prepare($sql) !== FALSE
			&& utils::$db->executeQuery($params) !== FALSE
			&& utils::$db->nbResult === 1)
			{
				utils::$purlFile = '';
				utils::redirect(NULL, TRUE);
			}
		}

		// Détermine la langue de l'utilisateur.
		if (preg_match('`^[a-z]{2}_[A-Z]{2}$`', utils::$cookiePrefs->read('lang'))
		&& isset(utils::$config['locale_langs'][utils::$cookiePrefs->read('lang')]))
		{
			utils::$userLang = utils::$cookiePrefs->read('lang');
		}
		else if (utils::$config['lang_client'])
		{
			utils::detectClientLang();
		}

		// Chargement du fichier de langue.
		utils::locale();
	}

	/**
	 * Authentification.
	 *
	 * @return void
	 */
	public static function login()
	{
		if (!isset($_POST['login']) || !isset($_POST['password']))
		{
			return;
		}

		try
		{
			// Vérification des informations.
			if (($session_token = user::auth($_POST['login'], $_POST['password'])) === FALSE)
			{
				throw new Exception('warning:' . __('Informations incorrectes.'));
			}

			// Écriture du cookie de session.
			if (!isset($_POST['remember']))
			{
				utils::$cookieSession->expire = 0;
			}
			utils::$cookieSession->delete();
			utils::$cookieSession->add('token', $session_token);

			// Écriture du cookie de préférences.
			utils::$cookiePrefs->add('lang', utils::$userLang);

			// Redirection vers l'administration.
			utils::$purlFile = '';
			utils::redirect(NULL, TRUE);
		}
		catch (Exception $e)
		{
			self::report($e->getMessage());
		}
	}

	/**
	 * Génère un nouveau mot de passe.
	 *
	 * @return void
	 */
	public static function newPassword()
	{
		if (!isset($_POST['login']) || !isset($_POST['email']) || !isset($_POST['code']))
		{
			return;
		}

		$password = user::newPassword($_POST['login'], $_POST['email'], $_POST['code']);
		if ($password === FALSE)
		{
			self::report('warning:' . __('Informations incorrectes.'));
			return;
		}

		self::$newPassword = $password;
		self::report('success:' . __('Le nouveau mot de passe que'
			. ' vous avez demandé a été créé :'));
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

		self::$report[$msg[0]] = $msg[1];
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
	 * Retourne l'élément $item.
	 *
	 * @param string $item
	 * @return string
	 */
	public function getConnexion($item)
	{
		switch ($item)
		{
			// Nom du répertoire d'administration.
			case 'admin_dir' :
				return basename(dirname(__FILE__));

			// Chemin absolu du répertoire d'administration.
			case 'admin_path' :
				return utils::tplProtect(GALLERY_ROOT) . '/' . $this->getConnexion('admin_dir');

			// Jeu de caractères.
			case 'charset' :
				return utils::tplProtect(CONF_CHARSET);

			// Chemin de la galerie.
			case 'gallery_path' :
				return utils::tplProtect(CONF_GALLERY_PATH);

			// Code de la langue de l'utilisateur courant.
			case 'lang_current' :
				return utils::tplProtect(utils::$userLang);

			// Nom de la feuille de style CSS.
			case 'style_name' :
				return utils::tplProtect(utils::$config['admin_style']);

			// Chemin du fichier de la feuille de style CSS.
			case 'style_file' :
				return $this->getConnexion('style_path') . '/'
					. utils::tplProtect(utils::$config['admin_style']) . '.css';

			// Chemin du répertoire de la feuille de style CSS.
			case 'style_path' :
				return $this->getConnexion('template_path') . utils::tplProtect(
					'/style/' . utils::$config['admin_style']
				);

			// Nom du template.
			case 'template_name' :
				return utils::tplProtect(utils::$config['admin_template']);

			// Chemin du template.
			case 'template_path' :
				return utils::tplProtect(CONF_GALLERY_PATH
					. '/' . $this->getConnexion('admin_dir')
					. '/template/'
					. utils::$config['admin_template']
				);
		}
	}

	/**
	 * Génère un lien.
	 *
	 * @param string $section Section à convertir en URL
	 * @return string
	 */
	public function getLink($section = '')
	{
		return utils::genURL($section);
	}

	/**
	 * L'élément de rapport $item doit-il être affiché ?
	 *
	 * @param string $item
	 * @return boolean
	 */
	public function disReport($item)
	{
		switch ($item)
		{
			case 'error' :
			case 'warning' :
			case 'success' :
				return !empty(connexion::$report[$item]);
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
		$i =& connexion::$report[$item];
		$message = (is_array($i)) ? current($i) : $i;

		switch ($item)
		{
			// Avertissements et erreurs.
			case 'error' :
			case 'warning' :
			case 'success' :
				return nl2br(utils::tplProtect($message));
		}
	}

	/**
	 * Inclusion libre.
	 *
	 * @param string $inc Page à inclure
	 * @return void
	 */
	public function inc($inc)
	{
		$tpl =& $this;
		include $inc;
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
			. '/' . $this->getConnexion('admin_dir')
			. '/template/' . $this->getConnexion('template_name')
			. '/' . connexion::$tplFile . '.tpl.php'
		);
	}

	/**
	 * Retourne le nouveau mot de passe.
	 *
	 * @return string
	 */
	public function getNewPassword()
	{
		return utils::tplprotect(connexion::$newPassword);
	}
}
?>