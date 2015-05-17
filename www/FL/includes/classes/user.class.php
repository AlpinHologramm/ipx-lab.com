<?php
/**
 * Méthodes pour la gestion des utilisateurs.
 *
 * @license http://www.gnu.org/licenses/gpl.html
 * @link http://www.igalerie.org/
 */
class user
{
	/**
	 * Authentification par formulaire.
	 *
	 * @param string $login
	 *	Identifiant de connexion.
	 * @param string $password
	 *	Mot de passe.
	 * @return boolean|string
	 *	Nouvel identifiant de session.
	 */
	public static function auth($login, $password)
	{
		// Filtrage simple.
		if (strlen($login) > 128 || strlen($password) > 1024
		|| !preg_match('`^[-a-z0-9@_.]+$`i', $login))
		{
			return FALSE;
		}

		// Admin ou galerie ?
		$admin_gallery = (utils::$purlDir == '/' . CONF_ADMIN_DIR) ? 'admin' : 'gallery';

		// Paramètres pour les deux prochaines requêtes.
		$params = array('login' => $login);

		// Vérification du mot de passe de l'utilisateur.
		$sql = 'SELECT user_crtdt,
					   user_password
				  FROM ' . CONF_DB_PREF . 'users
				 WHERE user_status = "1"
				   AND user_login = :login
				   AND user_id != 2
				   AND group_id != 2';
		if (utils::$db->prepare($sql) === FALSE
		|| utils::$db->executeQuery($params, 'row') === FALSE)
		{
			return FALSE;
		}

		// Échec de l'authentification.
		if (utils::$db->nbResult !== 1
		|| self::_authPassword($password, utils::$db->queryResult) === FALSE)
		{
			// Log d'activité.
			sql::logUserActivity('login_' . $admin_gallery . '_failure',
				2, NULL, array('login' => $login));

			// Prévention contre les attaques par force brute.
			if (function_exists('usleep'))
			{
				usleep((int) utils::$config['passwords_usleep']);
			}

			return FALSE;
		}

		// Récupération des informations utiles.
		$sql = ($admin_gallery == 'admin') ? ' AND group_admin = "1"' : '';
		$sql = 'SELECT user_id,
					   user_lang,
					   user_tz
				  FROM ' . CONF_DB_PREF . 'users
			 LEFT JOIN ' . CONF_DB_PREF . 'groups USING(group_id)
				 WHERE user_login = :login'
				     . $sql;
		if (utils::$db->prepare($sql) === FALSE
		|| utils::$db->executeQuery($params, 'row') === FALSE
		|| utils::$db->nbResult !== 1)
		{
			return FALSE;
		}
		$user_infos = utils::$db->queryResult;

		// Début de la transaction.
		if (!utils::$db->transaction())
		{
			return FALSE;
		}

		// On génère un identifiant de session.
		$session = self::getSession();

		// On place l'identifiant de session dans le compte de l'utilisateur.
		$sql = 'UPDATE ' . CONF_DB_PREF . 'users
				   SET session_id = ' . (int) $session['session_id'] . '
				 WHERE user_id = ' . (int) $user_infos['user_id'];
		if (utils::$db->exec($sql) === FALSE)
		{
			return FALSE;
		}

		// Log d'activité.
		sql::logUserActivity('login_' . $admin_gallery . '_success',
			(int) $user_infos['user_id'], NULL, array('login' => $login));

		// Exécution de la transaction.
		if (!utils::$db->commit())
		{
			return FALSE;
		}

		// Langue et fuseau horaire de l'utilisateur.
		utils::$userLang = $user_infos['user_lang'];
		utils::$userTz = $user_infos['user_tz'];

		// On retourne le jeton de session.
		return $session['session_token'];
	}

	/**
	 * Contrôle des éléments de formulaire.
	 *
	 * @param string $item
	 *	Nom de l'élement à contrôler.
	 * @param string $str
	 *	Chaîne à contrôler.
	 * @param integer $exclude_id
	 *	Identifiant de l'utilisateur à exclure de la vérification.
	 * @return boolean|string
	 */
	public static function checkForm($item, $str, $exclude_id = 0)
	{
		$method = 'checkUser' . ucfirst($item);
		if (method_exists('user', $method))
		{
			return ($exclude_id)
				? self::$method($str, $exclude_id)
				: self::$method($str);
		}
	}

	/**
	 * Contrôle de la description.
	 *
	 * @param string $str
	 *	Chaîne à contrôler.
	 * @return boolean|string
	 */
	public static function checkUserDesc($str)
	{
		$maxlength = utils::$config['users_desc_maxlength'];

		// Vérification de la longueur.
		if (mb_strlen($str) > $maxlength)
		{
			return sprintf(__('La description doit comporter'
				. ' au maximum %s caractères.'), $maxlength);
		}

		return TRUE;
	}

	/**
	 * Contrôle de l'adresse du courriel.
	 *
	 * @param string $str
	 *	Chaîne à contrôler.
	 * @param integer $exclude_id
	 *	Identifiant de l'utilisateur à exclure de la vérification.
	 * @return boolean|string
	 */
	public static function checkUserEmail($str, $exclude_id = 0)
	{
		// Vérification du format.
		if (!preg_match('`^' . utils::regexpEmail() . '$`i', $str))
		{
			return __('Format de l\'adresse de courriel incorrect.');
		}

		// On vérifie s'il existe une adresse identique.
		$sql_where = ($exclude_id)
			? ' AND user_id != ' . (int) $exclude_id
			: '';
		$sql = 'SELECT 1
				  FROM ' . CONF_DB_PREF . 'users
				 WHERE user_email = :user_email'
					 . $sql_where;
		$params = array('user_email' => $str);
		if (utils::$db->prepare($sql) === FALSE
		|| utils::$db->executeQuery($params) === FALSE)
		{
			return 'error:' . utils::$db->msgError;
		}
		if (utils::$db->nbResult !== 0)
		{
			return __('Un utilisateur possède déjà cette adresse de courriel.');
		}

		return TRUE;
	}

	/**
	 * Contrôle de la langue.
	 *
	 * @param string $str
	 *	Chaîne à contrôler.
	 * @return boolean
	 */
	public static function checkUserLang($str)
	{
		// Contrôle du format.
		if (!preg_match('`^[a-z]{2}_[A-Z]{2}$`', $str))
		{
			return FALSE;
		}

		// On vérifie si la langue est disponible.
		return array_key_exists($str, utils::$config['locale_langs']);
	}

	/**
	 * Contrôle de l'identifiant de connexion.
	 *
	 * @param string $str
	 *	Chaîne à contrôler.
	 * @param integer $exclude_id
	 *	Identifiant de l'utilisateur à exclure de la vérification.
	 * @return boolean|string
	 */
	public static function checkUserLogin($str, $exclude_id = 0)
	{
		$minlength = 2;
		$maxlength = 64;

		// Vérification de la longueur.
		if (strlen($str) < $minlength || strlen($str) > $maxlength)
		{
			return sprintf(__('Le nom d\'utilisateur doit'
				. ' contenir entre %s et %s caractères.'), $minlength, $maxlength);
		}

		// Vérification du format.
		if (!preg_match('`^[-a-z0-9@_.]+$`i', $str))
		{
			return __('Le nom d\'utilisateur ne doit comporter'
				. ' aucun espace, caractère spécial ou accentué.');
		}

		// On vérifie s'il existe un login identique, quelle que soit la casse.
		$sql_where = ($exclude_id)
			? ' AND user_id != ' . (int) $exclude_id
			: '';
		$sql = 'SELECT 1
				  FROM ' . CONF_DB_PREF . 'users
				 WHERE LOWER(user_login) = :user_login'
					 . $sql_where;
		$params = array('user_login' => strtolower($str));
		if (utils::$db->prepare($sql) === FALSE
		|| utils::$db->executeQuery($params) === FALSE)
		{
			return 'error:' . utils::$db->msgError;
		}
		if (utils::$db->nbResult !== 0)
		{
			return __('Ce nom d\'utilisateur existe déjà.');
		}

		return TRUE;
	}

	/**
	 * Contrôle du mot de passe.
	 *
	 * @param string $str
	 *	Chaîne à contrôler.
	 * @return boolean|string
	 */
	public static function checkUserPwd($str)
	{
		$min_length = (int) utils::$config['users_password_minlength'];

		// Vérification de la longueur.
		if (strlen($str) < $min_length)
		{
			return sprintf(__('La longueur du mot de passe doit'
				. ' être d\'au moins %s caractères.'), $min_length);
		}

		return TRUE;
	}

	/**
	 * Contrôle du fuseau horaire.
	 *
	 * @param string $str
	 *	Chaîne à contrôler.
	 * @return boolean
	 */
	public static function checkUserTz($str)
	{
		// Contrôle du format.
		return preg_match('`^[-_a-z/]{1,32}$`i', $str);
	}

	/**
	 * Contrôle de l'adresse du site Web.
	 *
	 * @param string $str
	 *	Chaîne à contrôler.
	 * @return boolean|string
	 */
	public static function checkUserWebsite($str)
	{
		// Vérification du format.
		if (!preg_match('`^' . utils::regexpURL() . '$`i', $str))
		{
			return __('Format de l\'adresse du site Web incorrect.');
		}

		return TRUE;
	}

	/**
	 * Crée une catégorie associée à l'utilisateur
	 * lors de son inscription.
	 *
	 * @param integer $user_id
	 *	Identifiant de l'utilisateur.
	 * @param string $user_login
	 *	Nom d'utilisateur.
	 * @return mixed
	 *	TRUE si succès.
	 *	FALSE si échec.
	 *	string si message d'erreur.
	 */
	public static function createCategory($user_id, $user_login)
	{
		// Création d'une catégorie lors de l'inscription ?
		if (!utils::$config['users_inscription_autocat'])
		{
			return TRUE;
		}

		// Récupération des informations utiles de la catégorie parente
		// dans laquelle sera créée la nouvelle catégorie.
		$parent_id = (int) utils::$config['users_inscription_autocat_category'];
		$sql = 'SELECT cat_path,
					   cat_password
				  FROM ' . CONF_DB_PREF . 'categories
				 WHERE cat_id = ' . $parent_id;
		if (utils::$db->query($sql, 'row') === FALSE)
		{
			return 'error:' . utils::$db->msgError;
		}
		if (utils::$db->nbResult !== 1)
		{
			trigger_error('Gallery error', E_USER_WARNING);
			return FALSE;
		}
		$cat_infos = utils::$db->queryResult;

		// Titre de la catégorie.
		$name = str_replace(
			'{USER_LOGIN}',
			$user_login,
			utils::$config['users_inscription_autocat_title']
		);

		// Création de la catégorie.
		return alb::create(
			$parent_id,
			$cat_infos['cat_path'],
			$cat_infos['cat_password'],
			substr(utils::$config['users_inscription_autocat_type'], 0, 3),
			array(
				'desc' => '',
				'filename' => utils::getLocale($name, CONF_DEFAULT_LANG),
				'name' => $name
			),
			$user_id
		);
	}

	/**
	 * Déconnexion d'un utilisateur.
	 *
	 * @param integer $user_id
	 *	Identifiant de l'utilisateur.
	 * @param string $session_token
	 *	Jeton de session.
	 * @return boolean
	 */
	public static function deconnect($user_id, $session_token)
	{
		if (!utils::$db->transaction())
		{
			return FALSE;
		}

		$sql = 'DELETE
				  FROM ' . CONF_DB_PREF . 'sessions
				 WHERE session_token = :session_token';
		if (utils::$db->prepare($sql) === FALSE
		 || utils::$db->executeExec(array('session_token' => $session_token)) === FALSE)
		{
			return FALSE;
		}

		$sql = 'UPDATE ' . CONF_DB_PREF . 'users
				   SET session_id = NULL
				 WHERE user_id = ' . (int) $user_id;
		if (utils::$db->exec($sql, FALSE) === FALSE)
		{
			return FALSE;
		}

		// Admin ou galerie ?
		$admin_gallery = (utils::$purlDir == '/' . CONF_ADMIN_DIR) ? 'admin' : 'gallery';

		// Log d'activité.
		sql::logUserActivity('logout_' . $admin_gallery, $user_id);

		if (!utils::$db->commit())
		{
			return FALSE;
		}

		utils::$cookieSession->expire = 0;
		utils::$cookieSession->delete();
		utils::$cookieSession->write();

		return TRUE;
	}

	/**
	 * Demande de nouveau mot de passe de connexion.
	 *
	 * @param string $login
	 *	Identifiant de connexion.
	 * @param string $email
	 *	Adresse de courriel.
	 * @return boolean
	 */
	public static function forgot($login, $email)
	{
		// Vérification du format des courriel et login.
		if (!utils::regexpEmail($email)
		|| !preg_match('`^[-a-z0-9@_.]{2,255}$`i', $login))
		{
			return FALSE;
		}

		// Clause WHERE.
		$sql_where = 'user_login = :login
				  AND user_email = :email
				  AND user_status = "1"
				  AND (user_rdate IS NULL OR ADDTIME(user_rdate, "01:00:00") < NOW())';

		// Paramètres.
		$params = array(
			'login' => $login,
			'email' => $email
		);

		// Récupération de l'identifiant de l'utilisateur.
		$sql = 'SELECT user_id
				  FROM ' . CONF_DB_PREF . 'users
				 WHERE ' . $sql_where;
		if (utils::$db->prepare($sql) === FALSE
		|| utils::$db->executeQuery($params, 'value') === FALSE)
		{
			return FALSE;
		}
		if (utils::$db->nbResult !== 1)
		{
			// Log d'activité.
			sql::logUserActivity('forgot_failure', 2, NULL, array(
				'login' => $login, 'email' => $email
			));
			return FALSE;
		}
		$user_id = utils::$db->queryResult;

		// Début de la transaction.
		if (!utils::$db->transaction())
		{
			return FALSE;
		}

		// On génère une clé que l'on enverra par courriel à l'utilisateur
		// et que celui-ci devra fournir pour générer aléatoirement
		// un nouveau mot de passe.
		$rkey = utils::genKey();

		// Vérification des informations fournies.
		$sql = 'UPDATE ' . CONF_DB_PREF . 'users
				   SET user_rkey = :rkey,
					   user_rdate = NOW()
				 WHERE ' . $sql_where;
		$params['rkey'] = $rkey;
		if (utils::$db->prepare($sql) === FALSE
		|| utils::$db->executeExec($params) === FALSE
		|| utils::$db->nbResult !== 1)
		{
			return FALSE;
		}

		// Log d'activité.
		sql::logUserActivity('forgot_success', $user_id, NULL, array(
			'login' => $login, 'email' => $email, 'rkey' => $rkey
		));

		// Exécution de la transaction.
		if (!utils::$db->commit())
		{
			return FALSE;
		}
		
		// Envoi du courriel.
		$mail = new mail();
		$message = __('Vous avez effectué la demande d\'un'
			. ' nouveau mot de passe pour la galerie %s.') . "\n";
		$message .= __('Pour générer un nouveau mot de passe, vous devez vous'
			. ' rendre à la page %s et fournir le code suivant :') . "\n\n";
		$message .= '%s' . "\n\n";
		$message .= __('Notez que ce code n\'est valide que pendant 24 heures.');
		$message = sprintf(
			$message,
			GALLERY_HOST . utils::genURL(),
			GALLERY_HOST . utils::genURL('new-password'),
			$rkey
		);
		$mail->messages[] = array(
			'to' => $email,
			'subject' => __('Demande de nouveau mot de passe à la galerie'),
			'message' => $message
		);
		$mail->send();

		return TRUE;
	}

	/**
	 * Retourne le jeton du cookie de session.
	 *
	 * @return boolean|string
	 */
	public static function getSessionCookieToken()
	{
		if (!is_object(utils::$cookieSession)
		|| ($session_token = utils::$cookieSession->read('token')) === FALSE
		|| !utils::isSha1($session_token))
		{
			$session_token = FALSE;
		}

		return $session_token;
	}

	/**
	 * Génère un nouvel identifiant de session utilisateur
	 * et retourne les informations utiles de la session.
	 *
	 * @return array
	 */
	public static function getSession()
	{
		$session_id = 0;
		$session_token = self::getSessionCookieToken();
		$session_valid = FALSE;

		try
		{
			// Si l'utilisateur possède un identifiant de session valide,
			// alors on utilisera la session session_id.
			if ($session_token)
			{
				$sql = 'SELECT session_id
						  FROM ' . CONF_DB_PREF . 'sessions
						 WHERE session_token = :session_token
						   AND session_expire > NOW()';
				$params = array(
					'session_token' => $session_token
				);
				if (utils::$db->prepare($sql) === FALSE
				|| utils::$db->executeQuery($params, 'value') === FALSE)
				{
					throw new Exception();
				}
				if (utils::$db->nbResult === 1)
				{
					$session_id = utils::$db->queryResult;
					$session_valid = TRUE;
				}
			}

			// Pour éviter les attaques par fixation de session, on génère
			// un nouveau jeton de session, que l'utilisateur possède déjà
			// un identifiant de session valide ou non.
			$session_token = utils::genKey();

			// Si l'utilisateur ne possède aucun identifiant de session valide,
			// on en insère un nouveau dans la base de données.
			if ($session_id === 0)
			{
				$sql = 'INSERT INTO ' . CONF_DB_PREF . 'sessions (
						session_token,
						session_expire
					) VALUES (
						:session_token,
						DATE_ADD(NOW(), INTERVAL :expire SECOND)
					)';
				$params = array(
					'expire' => (int) utils::$config['sessions_expire'],
					'session_token' => $session_token
				);
				if (utils::$db->prepare($sql) === FALSE
				|| utils::$db->executeExec($params) === FALSE
				|| utils::$db->nbResult === 0)
				{
					throw new Exception();
				}
				$session_id = utils::$db->connexion->lastInsertId();
			}

			// Sinon on remplace l'actuel identifiant de session par le nouveau.
			else
			{
				$sql = 'UPDATE ' . CONF_DB_PREF . 'sessions
						   SET session_token = :session_token
						 WHERE session_id = ' . (int) $session_id;
				if (utils::$db->prepare($sql) === FALSE
				|| utils::$db->executeExec(array('session_token' => $session_token)) === FALSE
				|| utils::$db->nbResult !== 1)
				{
					throw new Exception();
				}
			}
		}
		catch (Exception $e) {}

		return array(
			'session_id' => $session_id,
			'session_token' => $session_token,
			'session_valid' => $session_valid
		);
	}

	/**
	 * Génère un nouveau mot de passe pour les utilisateurs qui l'ont oublié.
	 *
	 * @param string $login
	 *	Identifiant de connexion.
	 * @param string $email
	 *	Adresse de courriel.
	 * @param string $code
	 *	Code envoyé par courriel.
	 * @return boolean|string
	 *	Nouveau mot de passe.
	 */
	public static function newPassword($login, $email, $code)
	{
		// Quelques vérifications.
		$code = trim($code);
		if (!utils::regexpEmail($email)
		|| !preg_match('`^[-a-z0-9@_.]{2,255}$`i', $login)
		|| !utils::isSha1($code))
		{
			return FALSE;
		}

		// On génère un nouveau mot de passe aléatoirement.
		$password = utils::genPassword(16);

		// Récupération de la date de création du compte.
		$sql = 'SELECT user_crtdt
				  FROM ' . CONF_DB_PREF . 'users
				 WHERE user_login = :login
				   AND user_id != 2
				   AND group_id != 2';
		$params = array('login' => $login);
		if (utils::$db->prepare($sql) === FALSE
		|| utils::$db->executeQuery($params, 'value') === FALSE
		|| utils::$db->nbResult !== 1)
		{
			return FALSE;
		}
		$user_crtdt = utils::$db->queryResult;

		// On met à jour les informations de l'utilisateur.
		$sql = 'UPDATE ' . CONF_DB_PREF . 'users
				   SET user_password = :password,
					   user_rkey = NULL,
					   user_rdate = NULL
				 WHERE user_status = "1"
				   AND user_rkey = :code
				   AND ADDDATE(user_rdate, 1) > NOW()
				   AND user_login = :login
				   AND user_email = :email
				 LIMIT 1';
		$params = array(
			'email' => $email,
			'login' => $login,
			'password' => utils::hashPassword($password, $user_crtdt),
			'code' => $code
		);
		if (utils::$db->prepare($sql) === FALSE
		|| utils::$db->executeExec($params) === FALSE
		|| utils::$db->nbResult !== 1)
		{
			return FALSE;
		}

		return $password;
	}



	/**
	 * Vérification du mot de passe entré par l'utilisateur pour se connecter.
	 *
	 * @param string $post_password
	 *	Mot de passe entré par l'utilisateur.
	 * @param array $user_infos
	 *	Informations utiles sur le compte de l'utilisateur.
	 * @return boolean
	 */
	private static function _authPassword($post_password, $user_infos)
	{
		$post_password = (string) $post_password;

		if (mb_strlen($post_password) > 1024)
		{
			return FALSE;
		}

		// Vérification normale.
		if ($user_infos['user_password']
		=== utils::hashPassword($post_password, $user_infos['user_crtdt']))
		{
			return TRUE;
		}

		// Vérification pour compatibilité avec iGalerie 1.0.
		if (isset(utils::$config['migration_igalerie1'])
		&& $user_infos['user_password']
		=== utils::hashPassword(md5($post_password), $user_infos['user_crtdt']))
		{
			return TRUE;
		}

		return FALSE;
	}
}
?>