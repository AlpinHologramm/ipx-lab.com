<?php
/**
 * Gestionnaire d'erreur.
 *
 * @license http://www.gnu.org/licenses/gpl.html
 * @link http://www.igalerie.org/
 */
class error
{
	/**
	 * Décide si les erreurs doivent être affichées au moment où elles se
	 * produisent, ou bien si elles ne doivent l'être que lorsque la méthode
	 * displayErrors() aura été appelée.
	 *
	 * @var integer
	 */
	private static $_displayNow = CONF_ERRORS_DISPLAY_NOW;

	/**
	 * Erreurs enregistrées.
	 *
	 * @var array
	 */
	private static $_errors = array();

	/**
	 * Types d'erreurs PHP.
	 *
	 * @var array
	 */
	private static $_errorType = array(
		   1 => 'ERROR',
		   2 => 'WARNING',
		   4 => 'PARSE',
		   8 => 'NOTICE',
		  16 => 'CORE_ERROR',
		  32 => 'CORE_WARNING',
		  64 => 'COMPILE_ERROR',
		 128 => 'COMPILE_WARNING',
		 256 => 'USER_ERROR',
		 512 => 'USER_WARNING',
		1024 => 'USER_NOTICE',
		2048 => 'STRICT',
		4096 => 'RECOVERABLE_ERROR'
	);



	/**
	 * Récupère les erreurs de base de données.
	 *
	 * @param object $e
	 * @param array $additional
	 * @param boolean $details
	 * @return void
	 */
	public static function dbError($e, $additional, $details = TRUE)
	{
		$message = $e->getMessage();
		$line = $e->getLine();
		$file = self::_file($e->getFile());
		$code = $e->getCode();
		$details = self::_backtrace($e->getTrace());
		utils::htmlspecialchars($additional);
		$details = ($details)
			? array(
				'code' => $code,
				'trace' => $details,
				'additional' => $additional
			  )
			: array();

		self::_display('DB_ERROR', $file, $line, $message, $details);
	}

	/**
	 * Affiche désormais immédiatement les erreurs, ainsi que les
	 * erreurs qui se sont produites avant l'appel à cette méthode.
	 *
	 * @return void
	 */
	public static function displayErrors()
	{
		self::$_displayNow = 1;
		$errors = self::$_errors;
		for ($i = 0, $count = count($errors); $i < $count; $i++)
		{
			self::_echo(
				$errors[$i][0],
				$errors[$i][1],
				$errors[$i][2],
				$errors[$i][3],
				$errors[$i][4]
			);
		}
	}

	/**
	 * Gestion des exceptions.
	 *
	 * @param object $e
	 * @return void
	 */
	public static function exception($e)
	{
		echo $e->getMessage();
		trigger_error($e->getMessage(), E_USER_ERROR);
	}

	/**
	 * Récupère les erreurs PHP.
	 *
	 * @param integer $errno
	 * @param string $errstr
	 * @param string $errfile
	 * @param integer $errline
	 * @return boolean
	 */
	public static function phpError($errno, $errstr, $errfile, $errline)
	{
		$trace = self::_backtrace();

		// Erreurs à ignorer.
		if (!CONF_DEBUG)
		{
			// uploader.php
			if (isset($trace[1]) && strstr($trace[1], 'uploader.php')
			&& strstr($errstr, 'mkdir') && strstr($errstr, 'File exists'))
			{
				return;
			}

			// exif_read_data()
			if (strstr($errstr, 'exif_read_data'))
			{
				return;
			}
		}

		$type = (isset(self::$_errorType[$errno]))
			  ? self::$_errorType[$errno]
			  : $errno;
		$errfile = self::_file($errfile);

		self::_display('PHP_' . $type, $errfile, $errline, $errstr, $trace);

		return TRUE;
	}



	/**
	 * Génère des informations de debogage plus lisible.
	 * Adaptation de la méthode print_backtrace() de Habari :
	 * http://www.habariproject.org/
	 *
	 * @param array $trace
	 * @return array
	 */
	private static function _backtrace($trace = NULL)
	{
		if (!is_array($trace))
		{
			$trace = debug_backtrace();
		}

		$defaults = array(
			'file' => '[core]',
			'line' => '(eval)',
			'class' => '',
			'function' => '',
			'type' => '',
			'args' => array(),
		);

		$bt = array();

		foreach ($trace as $n => &$a)
		{
			$a = array_merge($defaults, $a);

			if ($a['class'] == 'error')
			{
				continue;
			}			

			// Arguments des fonctions.
			if (CONF_ERRORS_TRACE_ARGS)
			{
				$args = array();
				foreach ($a['args'] as $arg)
				{
					// On cache les informations de connexion.
					$args[] = ((($a['class'] == 'db' || $a['class'] == 'PDO')
							&& $a['function'] == '__construct')
						|| (($a['class'] == 'user') && $a['function'] == 'auth'))
						? '\'*****\''
						: htmlspecialchars(var_export($arg, TRUE), ENT_QUOTES, 'UTF-8');
				}
				$args = implode(', ', $args);
				if (strlen($args) > 1024)
				{
					$args = substr($args, 0, 1021) . '...';
				}
			}
			else
			{
				if (count($a['args']) == 0)
				{
					$args = '';
				}
				else
				{
					$args = (count($a['args']) > 1)
						? ' ...%d args... '
						: ' ...%d arg... ';
					$args = sprintf($args, count($a['args']));
				}
			}

			$bt[] = sprintf( 
				"%s line %d:\n%s(%s)\n",
				self::_file($a['file']),
				$a['line'],
				$a['class'] . $a['type'] . $a['function'],
				$args
			);
		}

		return $bt;
	}

	/**
	 * Simplifie le chemin du fichier.
	 *
	 * @param string $file
	 * @return string
	 */
	private static function _file($file)
	{
		if (strpos($file, GALLERY_ROOT) === 0)
		{
			return substr($file, strlen(GALLERY_ROOT) + 1);
		}

		return $file;
	}

	/**
	 * Gestion des logs d'erreurs.
	 *
	 * @staticvar boolean $error_limit
	 *	"Laisser-passé" pour logger l'erreur spéciale
	 *	sur la limite du nombre d'erreurs enregistrées.
	 * @param string $type
	 * @param string $file
	 * @param integer $line
	 * @param string $message
	 * @param array $details
	 * @return void
	 */
	private static function _log($type, $file, $line, $message = '', $details = array())
	{
		if (!CONF_ERRORS_LOG)
		{
			return;
		}

		// Erreurs à ne pas enregistrer.
		if (!CONF_DEBUG)
		{
			// Images corrompues.
			if (strstr($message, 'imagecreatefrom')
			&& (strstr($message, 'is not a valid')
			 || strstr($message, 'recoverable error')))
			{
				return;
			}

			// Accès au disque.
			if (strstr($file, 'files.class.php'))
			{
				return;
			}
		}

		static $error_limit = FALSE;

		$dir_errors = dirname(__FILE__) . '/../../errors/';

		$details = serialize($details);
		$md5 = md5(CONF_KEY . '|' . $type . $file . $line . $message);
		$xml_file = $dir_errors . $type . '_' . $md5 . '.xml';

		if (file_exists($xml_file))
		{
			files::touchFile($xml_file);
		}
		else
		{
			if (!$error_limit)
			{
				// On limite le nombre d'erreurs stockées.
				$files = scandir($dir_errors);
				$xml_nb = count($files) - 2;
				if ($xml_nb >= CONF_ERRORS_LOG_MAX)
				{
					$error_limit = TRUE;
					$message = 'Maximum number of errors reached.';
					trigger_error($message, E_USER_WARNING);
					return;
				}
			}

			self::_XML($md5, $xml_file, $type, $file, $line, $message, $details);
			self::_mail($type, $file, $line, $message, $details);
		}

		$error_limit = FALSE;
	}

	/**
	 * Gère l'affichage des erreurs.
	 *
	 * @param string $type
	 * @param string $file
	 * @param integer $line
	 * @param string $message
	 * @param array $details
	 * @return void
	 */
	private static function _display($type, $file, $line, $message = '', $details = array())
	{
		self::_log($type, $file, $line, $message, $details);
		if (!CONF_ERRORS_DISPLAY)
		{
			return;
		}
		if (self::$_displayNow)
		{
			self::_echo($type, $file, $line, $message, $details);
		}
		else
		{
			self::$_errors[] = array($type, $file, $line, $message, $details);
		}
	}

	/**
	 * Affiche une erreur.
	 *
	 * @param string $type
	 * @param string $file
	 * @param integer $line
	 * @param string $message
	 * @param array $details
	 * @return void
	 */
	private static function _echo($type, $file, $line, $message = '', $details = array())
	{
		static $error_num = 1;

		if ($error_num < 100)
		{
			if (CONF_ERRORS_DISPLAY_TRACE)
			{
				$error_trace_id = "error_trace$error_num";
				$js = "document.getElementById('$error_trace_id')"
					. ".style.display=(document.getElementById('$error_trace_id')"
					. ".style.display=='none')?'':'none';";
				$link = "<a style=\"color:black\" href=\"javascript:void(0);\""
					. " onclick=\"javascript:$js\">[$error_num]</a>";
			}
			else
			{
				$link = "[$error_num]";
			}

			$format = '%s %s in %s on line %s: %s';
			$num = '<span class="error_num">' . $link . '</span>';
			$type = '<strong>' . $type . '</strong>';
			$file = '<span class="error_file"><strong>'
				. htmlspecialchars($file) . '</strong></span>';
			$line = '<span class="error_line"><strong>'
				. htmlspecialchars($line) . '</strong></span>';
			$message = '<span class="error_message">'
				. htmlspecialchars($message) . '</span>';
			$error = sprintf($format, $num, $type, $file, $line, $message);

			echo "\n<br />\n<span style=\"background:white;color:black;padding:2px\"";
			echo "id=\"error$error_num\" class=\"error\">$error</span><br />\n";
			if (CONF_ERRORS_DISPLAY_TRACE)
			{
				echo "<div style=\"background:white;color:black;display:none;";
				echo "border:1px dotted black;padding:5px;\"";
				echo " class=\"error_trace\" id=\"$error_trace_id\">\n";
				echo "details :\n<pre>";
				print_r($details);
				echo "</pre>\n";
				echo "</div>\n";
			}
		}
		$error_num++;
	}

	/**
	 * Envoi une erreur par e-mail au superadmin.
	 *
	 * @param string $error_type
	 * @param string $error_file
	 * @param integer $error_line
	 * @param string $error_msg
	 * @return void
	 */
	private static function _mail($error_type, $error_file, $error_line, $error_msg = '')
	{
		set_error_handler(array('error', 'phpError'));

		// L'option de notification par mail doit être activée,
		// et la connexion à la base de données établie.
		if (!CONF_ERRORS_MAIL || !isset(utils::$config['errors_last_mail']))
		{
			return;
		}

		// On envoi au maximum un e-mail par demi-heure.
		// Ceci afin d'éviter l'envoi de plusieurs e-mails si
		// plusieurs erreurs se produisent en même temps ou
		// dans un laps de temps très rapproché.
		$now = time();
		if ($now - utils::$config['errors_last_mail'] <= 1800)
		{
			return;
		}

		// Récupération des informations utiles du superadmin.
		if (($admin = mail::getAdminInfos()) === FALSE)
		{
			return;
		}

		// Sujet.
		$subject = 'Une erreur s\'est produite dans votre galerie.';

		// URL de la galerie.
		$url = GALLERY_HOST . utils::genURL();

		// Date et heure actuelle.
		$date = date('d/m/Y', $now);
		$time = date('H:i:s', $now);

		// Message.
		$message = 'Une erreur s\'est produite dans la galerie %s le %s à %s.';
		$message = sprintf($message, $url, $date, $time) . "\n\n";
		$message .= sprintf('%s in %s on line %s: %s',
			$error_type, $error_file, $error_line, $error_msg);

		// Mise à jour du paramètre de configuration 'errors_last_mail'.
		$sql = 'UPDATE ' . CONF_DB_PREF . 'config
				   SET conf_value = "' . $now . '"
				 WHERE conf_name = "errors_last_mail"
				 LIMIT 1';
		utils::$db->exec($sql);
		utils::$db->nbResult !== 1;

		utils::$config['errors_last_mail'] = $now;

		// Envoi du e-mail.
		$mail = new mail();
		$mail->messages[] = array(
			'to' => $admin['user_email'],
			'subject' => $subject,
			'message' => $message
		);
		$mail->send();
	}

	/**
	 * Enregistre une erreur dans un fichier XML.
	 *
	 * @param string $md5
	 * @param string $xml_file
	 * @param string $type
	 * @param string $file
	 * @param integer $line
	 * @param string $message
	 * @param array $details
	 * @return void
	 */
	private static function _XML($md5, $xml_file,
	$type, $file, $line, $message = '', $details = array())
	{
		$date = date('Y-m-d H:i:s');

		$xml_data = '<?xml version="1.0" encoding="UTF-8" ?>' . "\n";
		$xml_data .= '<error md5="' . $md5 . '">' . "\n\t";
		$xml_data .= '<version>' . urlencode(system::$galleryVersion) . '</version>' . "\n\t";
		$xml_data .= '<type>' . $type . '</type>' . "\n\t";
		$xml_data .= '<date>' . $date . '</date>' . "\n\t";
		$xml_data .= '<q>' . (isset($_GET['q']) ? urlencode($_GET['q']) : '') . '</q>' . "\n\t";
		$xml_data .= '<file>' . urlencode($file) . '</file>' . "\n\t";
		$xml_data .= '<line>' . urlencode($line) . '</line>' . "\n\t";
		$xml_data .= '<message>' . urlencode($message) . '</message>' . "\n\t";
		$xml_data .= '<details>' . urlencode($details) . '</details>' . "\n";
		$xml_data .= '</error>' . "\n";

		files::filePutContents($xml_file, $xml_data);
	}
}
?>