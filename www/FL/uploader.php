<?php
/**
 * Récupération d'images envoyées par HTTP.
 *
 * @license http://www.gnu.org/licenses/gpl.html
 * @link http://www.igalerie.org/
 */

if (empty($_FILES['Filedata']) || !isset($_POST['id']) || !isset($_POST['session_token'])
|| !preg_match('`^[a-z\d]{40}$`', $_POST['session_token']) || !isset($_POST['tempdir'])
|| !preg_match('`^[a-z\d]{40}$`', $_POST['tempdir'])
|| !isset($_POST['from']) || !in_array($_POST['from'], array('admin', 'gallery')))
{
	die('[' . __LINE__ . '] You are not allowed here.');
}

require_once(dirname(__FILE__) . '/includes/prepend.php');

// Connexion à la base de données.
utils::$db = new db();
if (utils::$db->connexion === NULL)
{
	die('Unable to connect to the database.');
}

// Récupération des informations de configuration utiles de la galerie.
$sql = 'SELECT *
		  FROM ' . CONF_DB_PREF . 'config
		 WHERE conf_name NOT LIKE "admin\_%"';
$fetch_style = array(
	'column' => array('conf_name', 'conf_value')
);
if (utils::$db->query($sql, $fetch_style) === FALSE || utils::$db->nbResult === 0)
{
	throw new Exception('Missing data in the database.');
}
utils::$config = utils::$db->queryResult;

// Récupération des permissions utilisateur.
$sql = 'SELECT user_id,
			   user_lang,
			   user_tz,
			   group_perms
		  FROM ' . CONF_DB_PREF . 'sessions AS s,
			   ' . CONF_DB_PREF . 'groups AS g,
			   ' . CONF_DB_PREF . 'users AS u
		 WHERE u.session_id = s.session_id
		   AND u.group_id = g.group_id
		   AND user_status = "1"
		   AND session_token = :session_token
		   AND session_expire > NOW()';
$params = array(
	'session_token' => $_POST['session_token']
);
if (utils::$db->prepare($sql) === FALSE
|| utils::$db->executeQuery($params, 'row') === FALSE
|| utils::$db->nbResult !== 1)
{
	die('Session expired.');
}
$user_infos = utils::$db->queryResult;
$perms = $user_infos['group_perms'];
if (!utils::isSerializedArray($perms))
{
	die;
}
$perms = unserialize($perms);
$perms = $perms[$_POST['from']];
if ($_POST['from'] == 'admin')
{
	$perms['perms']['upload'] = ($perms['perms']['all'])
		? 1
		: $perms['perms']['albums_add'];
}

// Permissions d'accès.
if (!$perms['perms']['upload'])
{
	die('[' . __LINE__ . '] You are not allowed here.');
}

// Mode d'accès aux albums.
$perms['sql_albums_access'] = ' AND cat_password IS NULL';
switch ($perms['perms']['access_mode'])
{
	// Toutes les catégories.
	case 1 :
		$perms['sql_albums_access'] = '';
		break;

	// Catégories sélectionnées.
	case 2 :
		if (!empty($perms['albums_access']))
		{
			$perms['sql_albums_access'] = ' AND (cat_password IS NULL';
			foreach ($perms['albums_access'] as $id => &$v)
			{
				$perms['sql_albums_access'] .= ' OR cat_password LIKE "' . (int) $id . ':%"';
			}
			$perms['sql_albums_access'] .= ')';
		}
		break;
}

// On récupère les informations utiles de l'album, et on
// vérifie que l'utilisateur a la permission d'accès à cet album.
$sql = 'SELECT cat_path
		  FROM ' . CONF_DB_PREF . 'categories
		 WHERE cat_id = ' . (int) $_POST['id'] . '
		   ' . $perms['sql_albums_access'];
if (utils::$db->query($sql, 'row') === FALSE)
{
	die;
}
if (utils::$db->nbResult === 0)
{
	die('[' . __LINE__ . '] You are not allowed here.');
}
$infos = utils::$db->queryResult;

// Chargement du fichier de langue.
utils::$userLang = $user_infos['user_lang'];
utils::$userTz = $user_infos['user_tz'];
utils::locale();

// Si le répertoire temporaire d'upload n'existe pas, on le crée.
$tempdir = GALLERY_ROOT . '/cache/up_temp/' . $_POST['tempdir'];
if (!is_dir($tempdir))
{
	files::mkdir($tempdir);
}

// On déplace l'image vers le répertoire temporaire.
$i =& $_FILES['Filedata'];
$r = alb::uploadFile($i['name'], $i['tmp_name'], $i['error'], $tempdir, $infos['cat_path']);
if ($r === FALSE)
{
	die;
}
if (is_array($r))
{
	echo $r[0] . ':' . $r[1];
}
if (is_string($r))
{
	echo 'success:' . $r;
}
?>