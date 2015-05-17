<?php
/**
 * Gestionnaire d'envoi d'e-mail.
 * Permet d'envoyer des e-mails par la fonction mail() de PHP
 * ou par serveur SMTP.
 * Gère également les notifications par e-mail.
 *
 * @license http://www.gnu.org/licenses/gpl.html
 * @link http://www.igalerie.org/
 */
class mail
{
	/**
	 * Paramètres des e-mails à envoyer.
	 *
	 * @var array
	 */
	public $messages = array();



	/**
	 * Connexion au serveur SMTP.
	 *
	 * @var resource
	 */
	private $_fp;



	/**
	 * Récupère l'adresse e-mail et la langue du superadmin.
	 *
	 * @return boolean
	 */
	public static function getAdminInfos()
	{
		$sql = 'SELECT user_email,
					   user_lang
				  FROM ' . CONF_DB_PREF . 'users
				 WHERE user_id = 1
				   AND group_id = 1';
		if (utils::$db->query($sql, 'row') === FALSE
		|| utils::$db->nbResult === 0)
		{
			return FALSE;
		}

		return utils::$db->queryResult;
	}

	/**
	 * Envoi un ou plusieurs e-mail.
	 *
	 * @return boolean
	 */
	public function send()
	{
		if (empty($this->messages))
		{
			return TRUE;
		}

		if (CONF_SMTP_MAIL)
		{
			return $this->_smtpMail();
		}

		$ok = TRUE;
		foreach ($this->messages as &$i)
		{
			if (!is_array($i))
			{
				continue;
			}
			if (!$this->_phpMail($i))
			{
				$ok = FALSE;
				break;
			}
		}

		return $ok;
	}

	/**
	 * Notifications.
	 *
	 * @param string $n
	 *	Notification souhaitée :
	 *	'comments', 'comments-pending', 'images', 'images-pending' ou 'inscription'.
	 * @param array $albums
	 *	Liste des albums concernés par la notification.
	 * @param integer $user_exclude
	 *	Identifiant de l'utilisateur à exclure de la notification.
	 * @param array $infos
	 *	Informations complémentaires pour la notification.
	 * @param array $groups_exclude
	 *	Identifiants des groupes à exclure de la notification.
	 * @return array|boolean
	 *	Retourne un tableau des groupes qui seront notifiés,
	 *	ou FALSE en cas d'erreur.
	 */
	public function notify($n, $albums = array(), $user_exclude = 0, $infos = array(),
	$groups_exclude = array())
	{
		// Récupération des informations utiles de tous les groupes.
		$sql = 'SELECT group_id,
					   group_perms
				  FROM ' . CONF_DB_PREF . 'groups
				 WHERE group_id != 2';
		$fetch_style = array(
			'column' => array('group_id', 'group_perms')
		);
		if (utils::$db->query($sql, $fetch_style) === FALSE
		|| utils::$db->nbResult === 0)
		{
			return FALSE;
		}
		$groups = utils::$db->queryResult;

		// Sélection des id de groupes autorisés pour la notification.
		$g = array();
		foreach ($groups as $id => &$perms)
		{
			// Groupes à exclure.
			if (in_array($id, $groups_exclude))
			{
				continue;
			}

			// Il n'y a pas besoin de vérifier les droits pour le super-admin !
			if ($id == 1)
			{
				$g[] = 1;
				continue;
			}

			$perms = unserialize($perms);
			switch ($n)
			{
				// Nouveau commentaire.
				case 'comment' :
				case 'comment-pending' :
				case 'guestbook' :
				case 'guestbook-pending' :
					if (($perms['admin']['perms']['comments_edit']
					|| $perms['admin']['perms']['all'])
					&& $this->_checkAlbumsAccess($perms['admin'], $albums))
					{
						$g[] = (int) $id;
					}
					break;

				// Suivi de commentaires.
				// Nouvelles images.
				case 'comment-follow' :
				case 'images' :
					if ($perms['gallery']['perms']['alert_email']
					&& $this->_checkAlbumsAccess($perms['gallery'], $albums))
					{
						$g[] = (int) $id;
					}
					break;

				// Nouvelles images en attente.
				case 'images-pending' :
					if (($perms['admin']['perms']['albums_pending']
					|| $perms['admin']['perms']['all'])
					&& $this->_checkAlbumsAccess($perms['gallery'], $albums))
					{
						$g[] = (int) $id;
					}
					break;

				// Nouvel utilisateur.
				case 'inscription' :
					if ($perms['admin']['perms']['users_members']
					|| $perms['admin']['perms']['all'])
					{
						$g[] = (int) $id;
					}
					break;
			}
		}

		// Type de notification.
		switch ($n)
		{
			// Nouveau commentaire.
			case 'comment' :
				if (isset($infos['author']))
				{
					$vars = array('{AUTHOR}', '{GALLERY_TITLE}', '{GALLERY_URL}',
						'{IMAGE_URL}', '{EMAIL}', '{WEBSITE}');
					$msg_subj = self::_notifyMessageSubject($n, $vars, $infos);
				}
				else
				{
					$vars = array('{GALLERY_TITLE}', '{GALLERY_URL}', '{IMAGE_URL}',
						'{USER_LOGIN}', '{USER_URL}');
					$msg_subj = self::_notifyMessageSubject($n . '_auth', $vars, $infos);
				}

				$sql_like = '_1____';
				break;

			// Suivi de commentaires.
			case 'comment-follow' :
				if (isset($infos['author']))
				{
					$vars = array('{AUTHOR}', '{GALLERY_TITLE}', '{GALLERY_URL}',
						'{IMAGE_URL}', '{EMAIL}', '{WEBSITE}');
					$msg_subj = self::_notifyMessageSubject($n, $vars, $infos);
				}
				else
				{
					$vars = array('{GALLERY_TITLE}', '{GALLERY_URL}', '{IMAGE_URL}',
						'{USER_LOGIN}', '{USER_URL}');
					$msg_subj = self::_notifyMessageSubject($n . '_auth', $vars, $infos);
				}

				$sql_like = '_____1';
				break;

			// Nouveau commentaire en attente.
			case 'comment-pending' :
				if (isset($infos['author']))
				{
					$vars = array('{AUTHOR}', '{GALLERY_TITLE}', '{GALLERY_URL}',
						'{EMAIL}', '{WEBSITE}');
					$msg_subj = self::_notifyMessageSubject($n, $vars, $infos);
				}
				else
				{
					$vars = array('{GALLERY_TITLE}', '{GALLERY_URL}',
						'{USER_LOGIN}', '{USER_URL}');
					$msg_subj = self::_notifyMessageSubject($n . '_auth', $vars, $infos);
				}

				$sql_like = '__1___';
				break;

			// Nouveau commentaire dans le livre d'or.
			case 'guestbook' :
				if (isset($infos['author']))
				{
					$vars = array('{AUTHOR}', '{GALLERY_TITLE}', '{GALLERY_URL}',
						'{EMAIL}', '{WEBSITE}');
					$msg_subj = self::_notifyMessageSubject($n, $vars, $infos);
				}
				else
				{
					$vars = array('{GALLERY_TITLE}', '{GALLERY_URL}',
						'{USER_LOGIN}', '{USER_URL}');
					$msg_subj = self::_notifyMessageSubject($n . '_auth', $vars, $infos);
				}

				$sql_like = '_1____';
				break;

			// Nouveau commentaire en attente.
			case 'guestbook-pending' :
				if (isset($infos['author']))
				{
					$vars = array('{AUTHOR}', '{GALLERY_TITLE}', '{GALLERY_URL}',
						'{EMAIL}', '{WEBSITE}');
					$msg_subj = self::_notifyMessageSubject($n, $vars, $infos);
				}
				else
				{
					$vars = array('{GALLERY_TITLE}', '{GALLERY_URL}',
						'{USER_LOGIN}', '{USER_URL}');
					$msg_subj = self::_notifyMessageSubject($n . '_auth', $vars, $infos);
				}

				$sql_like = '__1___';
				break;

			// Nouvelles images.
			case 'images' :
				$vars = array('{GALLERY_TITLE}', '{GALLERY_URL}', '{USER_LOGIN}', '{USER_URL}');
				$msg_subj = self::_notifyMessageSubject($n, $vars, $infos);

				$sql_like = '___1__';
				break;

			// Nouvelles images en attente.
			case 'images-pending' :
				$vars = array('{GALLERY_TITLE}', '{GALLERY_URL}', '{USER_LOGIN}', '{USER_URL}');
				$msg_subj = self::_notifyMessageSubject($n, $vars, $infos);

				$sql_like = '____1_';
				break;

			// Nouvel utilisateur.
			case 'inscription' :
				if (utils::$config['users_inscription_moderate'])
				{
					$vars = array('{GALLERY_TITLE}', '{GALLERY_URL}', '{USER_LOGIN}');
					$msg_subj = self::_notifyMessageSubject($n . '_pending', $vars, $infos);
				}
				else
				{
					$vars = array('{GALLERY_TITLE}', '{GALLERY_URL}',
						'{USER_LOGIN}', '{USER_URL}');
					$msg_subj = self::_notifyMessageSubject($n, $vars, $infos);
				}

				$sql_like = '1_____';
				break;
		}

		$sql_where = '';

		// Utilisateur à exclure de la notification.
		$sql_exclude = (empty($user_exclude))
			? ''
			: 'AND user_id != ' . (int) $user_exclude;

		// Suivi de commentaires.
		if ($n == 'comment-follow' && utils::$config['users'])
		{
			$sql = 'SELECT DISTINCT user_id
					  FROM ' . CONF_DB_PREF . 'comments
					 WHERE user_id != 2
					   ' . $sql_exclude . '
					   AND image_id = ' . (int) $infos['image_id'];
			$fetch_style = array('column' => array('user_id', 'user_id'));
			if (utils::$db->query($sql, $fetch_style) === FALSE
			|| utils::$db->nbResult === 0)
			{
				return FALSE;
			}
			$users_id = utils::$db->queryResult;
			$sql_where .= ' AND user_id IN (' . implode(',', $users_id) . ')';
		}

		// On récupère l'adresse e-mail de tous
		// les utilisateurs autorisés à être notifié.
		$sql = 'SELECT user_email
				  FROM ' . CONF_DB_PREF . 'users
				 WHERE user_alert LIKE "' . $sql_like . '"
				   ' . $sql_exclude . '
				   AND user_email LIKE "%@%"
				   AND user_status = "1"
				   AND group_id IN (' . implode(', ', $g) . ')'
				     . $sql_where;
		$fetch_style = array('column' => array('user_email', 'user_email'));
		if (utils::$db->query($sql, $fetch_style) === FALSE
		|| utils::$db->nbResult === 0)
		{
			return FALSE;
		}
		$emails = utils::$db->queryResult;
		sort($emails);

		$this->messages[] = array(
			'to' => utils::$config['mail_auto_primary_recipient_address'],
			'name' => '',
			'from' => '',
			'subject' => $msg_subj['subject'],
			'message' => $msg_subj['message'],
			'bcc' => implode(', ', $emails)
		);

		return $g;
	}



	/**
	 * Vérifie si l'accès aux albums $albums est autorisé
	 * en fonction des permissions $perms.
	 *
	 * @param array $perms
	 *	Catégories protégées déverrouillées.
	 * @param array $albums
	 *	Albums dans lesquels de nouvelles images ont été ajoutées.
	 * @return boolean
	 *	TRUE si le groupe a la permission d'accès à au moins un des albums.
	 */
	private function _checkAlbumsAccess($perms, $albums)
	{
		static $new;

		if (empty($albums))
		{
			return FALSE;
		}

		// Si le groupe possède la permission d'accès à tous les albums,
		// inutile d'aller plus loin.
		if ($perms['perms']['access_mode'] == 1)
		{
			return TRUE;
		}

		// On détermine si les albums mis à jour sont protégés.
		if (!is_array($new))
		{
			sort($albums);
			$sql = 'SELECT cat_path,
						   CASE WHEN cat_password IS NULL
								THEN 0
								ELSE 1
								 END AS protected
					  FROM ' . CONF_DB_PREF . 'categories
					 WHERE cat_path IN (?' . str_repeat(', ?', count($albums) - 1) . ')';
			$fetch_style = array(
				'column' => array('cat_path', 'protected')
			);
			if (utils::$db->prepare($sql) === FALSE
			|| utils::$db->executeQuery($albums, $fetch_style) === FALSE
			|| utils::$db->nbResult !== count($albums))
			{
				return FALSE;
			}
			$new = utils::$db->queryResult;
		}

		// On détermine si au moins un album mis à jour n'est pas protégé.
		foreach ($new as $path => $protected)
		{
			if ($protected == 0)
			{
				return TRUE;
			}
		}

		// Si le groupe n'a accès à aucune catégorie protégée
		// ou si aucune catégorie protégée n'est déverrouillée,
		// on arrête là.
		if ($perms['perms']['access_mode'] == 0
		|| empty($perms['albums_access']))
		{
			return FALSE;
		}

		// On détermine si l'accès à au moins un album mis à jour
		// est autorisé pour ce groupe.
		$cat_perms = array_keys($perms['albums_access']);
		$sql = 'SELECT cat_path,
					   1
				  FROM ' . CONF_DB_PREF . 'categories
				 WHERE cat_id IN (?' . str_repeat(', ?', count($cat_perms) - 1) . ')';
		$fetch_style = array(
			'column' => array('cat_path', 1)
		);
		if (utils::$db->prepare($sql) === FALSE
		|| utils::$db->executeQuery($cat_perms, $fetch_style) === FALSE
		|| utils::$db->nbResult !== count($cat_perms))
		{
			return FALSE;
		}
		$cat_perms = utils::$db->queryResult;

		foreach ($new as $path_new => &$p)
		{
			foreach ($cat_perms as $path_perm => &$v)
			{
				if (strpos($path_new, $path_perm . '/', 0) !== FALSE)
				{
					return TRUE;
				}
			}
		}

		return FALSE;
	}

	/**
	 * Effectue une commande SMTP.
	 *
	 * @param string $str
	 * @param string $data
	 * @return mixed
	 */
	private function _cmd($str, &$data = '')
	{
		fputs($this->_fp, $str . PHP_EOL);

		$data = fgets($this->_fp, 1024);
		return (substr($data, 0, 1) != 4 && substr($data, 0, 1) != 5)
			? $data
			: FALSE;
	}

	/**
	 * Champ "from".
	 *
	 * @param string $email
	 * @return string
	 */
	private function _from($email)
	{
		if (utils::isEmpty($email) || strlen($email) > 250)
		{
			$email = utils::$config['mail_auto_sender_address'];
		}

		$this->_sanitize($email);

		return $email;
	}

	/**
	 * Récupère l'adresse de courriel du super-administrateur.
	 *
	 * @return string
	 */
	private function _getSuperAdminMail()
	{
		static $email;

		if ($email === NULL)
		{
			$sql = 'SELECT user_email
					  FROM ' . CONF_DB_PREF . 'users
					 WHERE user_id = "1"';
			if (utils::$db->query($sql, 'value') === FALSE)
			{
				return;
			}

			$email = utils::$db->queryResult;
		}

		return $email;
	}

	/**
	 * En-têtes du e-mail.
	 *
	 * @param array $headers
	 * @param string $bcc
	 * @param string $to
	 * @return string
	 */
	private function _headers($headers, $bcc = '', $to = '')
	{
		if ($bcc)
		{
			$this->_sanitize($bcc);
			$headers[] = 'Bcc: ' . $bcc;
		}

		if ($to)
		{
			$this->_sanitize($to);
			$headers[] = 'To: ' . $to;
		}

		$headers[] = 'Date: ' . date('r');
		$headers[] = 'MIME-Version: 1.0';
		$headers[] = 'Content-transfer-encoding: 8bit';
		$headers[] = 'Content-type: text/plain; charset=UTF-8';
		$headers[] = 'X-Mailer: iGalerie/' . $_SERVER['HTTP_HOST'];

		return implode(PHP_EOL, $headers);
	}

	/**
	 * Prépare le message du e-mail.
	 *
	 * @param string $str
	 * @return string
	 */
	private function _message($str)
	{
		$str = str_replace(
			array("\r\n", "\r", "\n"),
			array("\n", "\n", PHP_EOL),
			$str
		);

		$str = wordwrap($str, 70);

		return $str;
	}

	/**
	 * Retourne le message et le sujet d'une notification
	 * avec remplacement des variables correspondantes.
	 *
	 * @param string $n
	 *	Type de notification.
	 * @param array $vars
	 *	Variables à remplacer.
	 * @param array $i
	 *	Informations complémentaires pour la notification.
	 * @return boolean
	 */
	private function _notifyMessageSubject($n, $vars, $i)
	{
		$replace = array();
		foreach ($vars as $v)
		{
			switch ($v)
			{
				case '{AUTHOR}' :
					$replace[] = htmlspecialchars($i['author']);
					break;

				case '{EMAIL}' :
					$replace[] = (utils::isEmpty($i['email']))
						? '/'
						: htmlspecialchars($i['email']);
					break;

				case '{GALLERY_TITLE}' :
					$replace[] = htmlspecialchars(utils::$config['gallery_title']);
					break;

				case '{GALLERY_URL}' :
					$replace[] = GALLERY_HOST . utils::genGalleryURL();
					break;

				case '{IMAGE_URL}' :
					$replace[] = GALLERY_HOST . utils::genGalleryURL(
						'image/' . (int) $i['image_id'] . '-' .  $i['image_url']
					);
					break;

				case '{USER_LOGIN}' :
					$replace[] = htmlspecialchars($i['user_login']);
					break;

				case '{USER_URL}' :
					$replace[] = GALLERY_HOST
						. utils::genGalleryURL('user/' . (int) $i['user_id']);
					break;

				case '{WEBSITE}' :
					$replace[] = (utils::isEmpty($i['website']))
						? '/'
						: htmlspecialchars($i['website']);
					break;
			}
		}

		$r = array('message' => '', 'subject' => '');
		foreach ($r as $k => &$v)
		{
			$v = str_replace(
				$vars,
				$replace,
				utils::$config['mail_notify_' . str_replace('-', '_', $n) . '_' . $k]
			);
		}

		return $r;
	}

	/**
	 * Envoi un e-mail par la fonction mail() de PHP.
	 *
	 * @param array $i
	 *	Paramètres du mail.
	 * @return boolean
	 */
	private function _phpMail(&$i)
	{
		if (!function_exists('mail'))
		{
			trigger_error('Function mail() is not available.', E_USER_NOTICE);
			return FALSE;
		}

		$to = (isset($i['to'])) ? $i['to'] : '';
		$from = (isset($i['from'])) ? $i['from'] : '';
		$name = (isset($i['name'])) ? $i['name'] : '';
		$bcc = (isset($i['bcc'])) ? $i['bcc'] : '';

		// Doit-on ne pas utiliser le champs "Bcc" ?
		if (utils::$config['mail_auto_bcc'] != '1')
		{
			$to = $bcc;
			$bcc = '';
		}

		// Destinataire(s).
		$to = $this->_to($to);

		// Sujet.
		$subject = $this->_subject($i['subject']);

		// Message.
		$message = $this->_message($i['message']);

		// En-têtes.
		$this->_sanitize($name);
		$name = (utils::isEmpty($name)) ? '' : $name . ' ';
		$from = $name . '<' . $this->_from($from) . '>';
		$headers = $this->_headers(array('From: ' . $from), $bcc);

		// Envoi.
		$send = mail($to, $subject, $message, $headers);

		// Debug.
		if (CONF_DEBUG)
		{
			trigger_error('Debug mail() : ' . (int) $send, E_USER_NOTICE);
		}

		return $send;
	}

	/**
	 * Ferme la connexion au serveur SMTP.
	 *
	 * @return void
	 */
	private function _quit()
	{
		if ($this->_fp === NULL)
		{
			return;
		}

		$this->_cmd('QUIT');
		fclose($this->_fp);
		$this->_fp = NULL;
	}

	/**
	 * Nettoie une chaîne.
	 *
	 * @param string $str
	 * @return void
	 */
	private function _sanitize(&$str)
	{
		$str = trim(preg_replace(
			'`(?:to:|b?cc:|from:|content-type:|[\t\r\n\x5C]+)`i', '', $str
		));
	}

	/**
	 * Envoi des e-mails par un seveur SMTP.
	 *
	 * @return boolean
	 */
	private function _smtpMail()
	{
		if (!function_exists('fsockopen'))
		{
			trigger_error('Function fsockopen() is not available.', E_USER_NOTICE);
			return FALSE;
		}

		try
		{
			// Connexion au serveur SMTP.
			$this->_fp = fsockopen(CONF_SMTP_SERV, CONF_SMTP_PORT, $errno, $errstr, 4);
			if (!$this->_fp || !is_resource($this->_fp))
			{
				throw new Exception('Could not connect to SMTP host "'
					. CONF_SMTP_SERV . '" (' . $errno . ': ' . $errstr . ')');
			}
			stream_set_timeout($this->_fp, 4);

			$error_message = 'Unable to send e-mail. '
				. 'Error message reported by the SMTP server: ';

			$data = '';

			// Authentification.
			if (CONF_SMTP_AUTH)
			{
				if (!$this->_cmd('EHLO client', $data)
				|| !$this->_cmd('AUTH LOGIN', $data)
				|| !$this->_cmd(base64_encode(CONF_SMTP_USER), $data)
				|| !$this->_cmd(base64_encode(CONF_SMTP_PASS), $data))
				{
					throw new Exception($error_message . $data);
				}
			}
			else
			{
				if (!$this->_cmd('HELO client', $data))
				{
					throw new Exception($error_message . $data);
				}
			}

			// Envoi des e-mails.
			foreach ($this->messages as &$i)
			{
				if (!is_array($i))
				{
					continue;
				}

				$cmd = array();

				// Expéditeur.
				$from = (isset($i['from'])) ? $i['from'] : '';
				$from = $this->_from($from);
				$cmd[] = 'MAIL FROM: <' . $from . '>';

				// Doit-on ne pas utiliser le champs "Bcc" ?
				if (utils::$config['mail_auto_bcc'] != '1')
				{
					$i['to'] = $i['bcc'];
					$i['bcc'] = '';
				}

				// Destinataire(s).
				if (!empty($i['bcc']))
				{
					$cmd[] = 'RCPT TO: <'
						. str_replace(', ', '>' . PHP_EOL . 'RCPT TO: <', $i['bcc'])
						. '>';
				}
				elseif (!empty($i['to']))
				{
					$cmd[] = 'RCPT TO: <' . $i['to'] . '>';
				}

				$cmd[] = 'DATA';
				foreach ($cmd as &$c)
				{
					if (($resp = $this->_cmd($c, $data)) === FALSE)
					{
						throw new Exception($error_message . $data);
					}
				}

				// En-têtes et message.
				if (isset($i['name']))
				{
					$this->_sanitize($i['name']);
				}
				$headers = array(
					'From: ' . (empty($i['name']) ? '' : $i['name'] . ' ') . '<' . $from . '>',
					'Subject: ' . $this->_subject($i['subject'])
				);
				$headers = $this->_headers($headers, '', $i['to']);
				$cmd = $headers . PHP_EOL . $this->_message($i['message']) . PHP_EOL . '.';

				if (!$this->_cmd($cmd, $data))
				{
					throw new Exception($error_message . $data);
				}
			}

			$this->_quit();
			return TRUE;
		}
		catch (Exception $e)
		{
			$this->_quit();
			trigger_error($e->getMessage(), E_USER_WARNING);
			return FALSE;
		}
	}

	/**
	 * Prépare le sujet du e-mail.
	 *
	 * @param string $str
	 * @return string
	 */
	private function _subject($str)
	{
		$this->_sanitize($str);

		if (preg_match('`[^\x00-\x3C\x3E-\x7E]`', $str))
		{
			$str = '=?' . CONF_CHARSET . '?B?' . base64_encode($str) . '?=';
		}

		return $str;
	}

	/**
	 * Champ "to".
	 *
	 * @param string $to
	 * @return string
	 */
	private function _to($str)
	{
		if (utils::isEmpty($str) || strlen($str) > 250)
		{
			$str = utils::$config['mail_auto_primary_recipient_address'];
		}

		$this->_sanitize($str);

		return $str;
	}
}
?>