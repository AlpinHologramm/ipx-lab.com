<?php
/**
 * Redirection d'URL externes depuis l'administration.
 *
 * @license http://www.gnu.org/licenses/gpl.html
 * @link http://www.igalerie.org/
 */

$gets = array('url');
require_once(dirname(__FILE__) . '/includes/prepend.php');

try
{
	// Vérification de l'URL.
	if (!isset($_GET['url']) || !preg_match('`^' . utils::regexpURL() . '$`i', $_GET['url']))
	{
		throw new Exception();
	}

	// Vérification de la session.
	utils::$db = new db();
	if (utils::$db->connexion === NULL)
	{
		throw new Exception();
	}
	utils::$cookieSession = new cookie('igal_session', 8640000, CONF_GALLERY_PATH);
	if (($session_token = user::getSessionCookieToken()) === FALSE)
	{
		throw new Exception();
	}
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
	if (!utils::$db->prepare($sql)
	|| !utils::$db->executeQuery(array('session_token' => $session_token))
	|| utils::$db->nbResult !== 1)
	{
		throw new Exception();
	}
}
catch (Exception $e)
{
	die('You are not allowed here.');
}
utils::$db->connexion === NULL;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr" dir="ltr">
<head>
<title><?php printf(__('redirection vers %s'), utils::tplProtect($_GET['url'])); ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta http-equiv="Refresh" content="0; url=<?php echo utils::tplProtect($_GET['url']); ?>" />
</head>
<body>
	<p>
		<a href="<?php echo utils::tplProtect($_GET['url']); ?>">
			<?php echo utils::tplProtect($_GET['url']); ?>

		</a>
	</p>
</body>
</html>