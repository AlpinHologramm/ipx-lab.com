<?php
/**
 * Mise à jour de l'application.
 *
 * @license http://www.gnu.org/licenses/gpl.html
 * @link http://www.igalerie.org/
 */

require_once(dirname(__FILE__) . '/includes/prepend.php');

upgrade::init();

// Création de l'objet de template.
$tpl = new tpl();

// Fermeture de la connexion.
if (is_object(utils::$db))
{
	utils::$db->connexion = NULL;
}

/**
 * Opérations de mise à jour de l'application.
 */
class upgrade
{
	/**
	 * L'application est-elle déjà à jour ?
	 *
	 * @var boolean
	 */
	public static $already = FALSE;

	/**
	 * La mise à jour a-t-elle réussie ?
	 *
	 * @var boolean
	 */
	public static $success = FALSE;



	/**
	 * Paramètres de la table "config" mis à jour.
	 *
	 * @var array
	 */
	private static $_config;



	/**
	 * Initialisation.
	 *
	 * @return void
	 */
	public static function init()
	{
		// Connexion à la base de données.
		utils::$db = new db();
		if (utils::$db->connexion === NULL)
		{
			die('Unable to connect to the database.');
		}

		// Récupération de la configuration de la galerie.
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
		utils::$config['exif_order'] = unserialize(utils::$config['exif_order']);
		utils::$config['exif_params'] = unserialize(utils::$config['exif_params']);
		utils::$config['history'] = unserialize(utils::$config['history']);
		utils::$config['pages_order'] = unserialize(utils::$config['pages_order']);
		utils::$config['pages_params'] = unserialize(utils::$config['pages_params']);
		utils::$config['watermark_params'] = unserialize(utils::$config['watermark_params']);
		utils::$config['widgets_order'] = unserialize(utils::$config['widgets_order']);
		utils::$config['widgets_params'] = unserialize(utils::$config['widgets_params']);

		// Chargement du fichier de langue.
		utils::$config['locale_langs'] = unserialize(utils::$config['locale_langs']);
		utils::detectClientLang();
		utils::locale();

		// L'application doit-elle être mise à jour ?
		if (isset(utils::$config['version'])
		&& utils::$config['version'] == system::$galleryVersionDate
		&& isset(utils::$config['history'])
		&& isset(utils::$config['history'][system::$galleryVersion]))
		{
			self::$already = TRUE;
			return;
		}

		self::$_config = utils::$config;

		// Mise à jour.
		if (!empty($_POST['upgrade']))
		{
			self::_upgrade();
		}
	}



	/**
	 * Ajoute des nouveaux paramètres de configuration.
	 *
	 * @param array $params
	 * @return void
	 */
	private static function _addConfigParams($params)
	{
		foreach ($params as $name => &$value)
		{
			if (!isset(self::$_config[$name]))
			{
				self::$_config[$name] = $value;
			}
		}
	}

	/**
	 * Ajoute une nouvelle information Exif.
	 *
	 * @param string $name
	 * @param array $params
	 * @param string $order_after
	 * @return void
	 */
	private static function _addExifInfo($name, $params, $order_after = NULL)
	{
		if (!in_array($name, self::$_config['exif_params']))
		{
			self::$_config['exif_params'][$name] = $params;
		}
		if (!in_array($name, self::$_config['exif_order']))
		{
			if ($order_after
			&& ($offset = array_search($order_after, self::$_config['exif_order'])) !== FALSE)
			{
				self::$_config['exif_order'] = array_merge(
					array_slice(self::$_config['exif_order'], 0, $offset + 1),
					array($name),
					array_slice(self::$_config['exif_order'], $offset + 1)
				);
			}
			else
			{
				array_unshift(self::$_config['exif_order'], $name);
			}
		}
	}

	/**
	 * Ajoute des permissions de groupe.
	 *
	 * @param array $array
	 * @param array $add
	 * @return void
	 */
	private static function _addGroupsPerms(&$array, $add = array())
	{
		foreach ($add as $type => $perms)
		{
			foreach ($perms as $perm => $value)
			{
				if (!isset($array[$type]['perms'][$perm]))
				{
					$array[$type]['perms'][$perm] = $value;
				}
			}
		}
	}

	/**
	 * Ajoute de nouvelles lignes au fichier .htaccess.
	 *
	 * @param array $params
	 *	Lignes à ajouter au fichier .htaccess.
	 * @return boolean
	 *  FALSE en cas d'erreur
	 *  TRUE sinon
	 */
	private static function _addToHtaccess($params)
	{
		$file = dirname(__FILE__) . '/.htaccess';

		// Si le fichier existe, on récupère le contenu.
		if (!file_exists($file))
		{
			return TRUE;
		}
		if (($file_content_temp = files::fileGetContents($file)) === FALSE)
		{
			return FALSE;
		}

		// Les nouvelles lignes ont-elles déjà été
		// ajoutées au fichier ?
		if (preg_match('`' . $params['test'] . '`i', $file_content_temp))
		{
			return TRUE;
		}

		// On ajoute les nouvelles lignes au fichier.
		$file_content = array();
		$file_content_temp = preg_split('`\r?\n`', $file_content_temp);
		foreach ($file_content_temp as $line)
		{
			$file_content[] = rtrim($line);
			if (preg_match('`' . $params['after'] . '`i', $line))
			{
				foreach ($params['lines'] as $add_line)
				{
					if (!in_array($add_line, $file_content_temp))
					{
						$file_content[] = $add_line;
					}
				}
			}
		}

		// On enregistre le nouveau contenu dans le fichier.
		if (files::filePutContents($file, implode("\n", $file_content)) === FALSE)
		{
			return FALSE;
		}

		return TRUE;
	}

	/**
	 * Supprime des permissions de groupe.
	 *
	 * @param array $array
	 * @param array $delete
	 * @return void
	 */
	private static function _deleteGroupsPerms(&$array, $delete = array())
	{
		foreach ($delete as $type => $perms)
		{
			foreach ($perms as $perm)
			{
				if (isset($array[$type]['perms'][$perm]))
				{
					unset($array[$type]['perms'][$perm]);
				}
			}
		}
	}

	/**
	 * Supprime des paramètres de configuration.
	 *
	 * @param array $config
	 * @return void
	 */
	private static function _deleteConfigParams($params)
	{
		foreach ($params as &$name)
		{
			if (isset(self::$_config[$name]))
			{
				unset(self::$_config[$name]);
			}
		}
	}

	/**
	 * Modifie les permissions de groupes.
	 *
	 * @param array $add
	 * @param array $delete
	 * @return boolean
	 */
	private static function _modifyGroupsPerms($add = array(), $delete = array())
	{
		// Récupération des permissions des groupes de la galerie.
		$sql = 'SELECT group_id,
					   group_perms
				  FROM ' . CONF_DB_PREF . 'groups';
		$fetch_style = array('column' => array('group_id', 'group_perms'));
		if (utils::$db->query($sql, $fetch_style) === FALSE)
		{
			return FALSE;
		}
		$groups_perms = utils::$db->queryResult;

		// Modifications des permissions des groupes.
		$params = array();
		foreach ($groups_perms as $group_id => &$group_perms)
		{
			$group_perms = unserialize($group_perms);

			self::_addGroupsPerms($group_perms, $add);
			self::_deleteGroupsPerms($group_perms, $delete);

			ksort($group_perms['admin']['perms']);
			ksort($group_perms['gallery']['perms']);

			$params[] = array(
				'group_id' => $group_id,
				'group_perms' => serialize($group_perms)
			);
		}

		// Mise à jour de la table des groupes.
		$sql = 'UPDATE ' . CONF_DB_PREF . 'groups
				   SET group_perms = :group_perms
				 WHERE group_id = :group_id';
		$sql = array(array('sql' => $sql, 'params' => $params));
		if (utils::$db->exec($sql, FALSE) === FALSE)
		{
			return FALSE;
		}

		// Modifications des permissions de groupe par défaut.
		self::$_config['admin_group_perms_default']
			= unserialize(self::$_config['admin_group_perms_default']);
		self::_addGroupsPerms(self::$_config['admin_group_perms_default'], $add);
		self::_deleteGroupsPerms(self::$_config['admin_group_perms_default'], $delete);
		self::$_config['admin_group_perms_default']
			= serialize(self::$_config['admin_group_perms_default']);

		return TRUE;
	}

	/**
	 * Mise à jour.
	 *
	 * @return void
	 */
	private static function _upgrade()
	{
		// Mises à jour vers la version courante.
		if (!self::_upgradeVersion20beta1()
		 || !self::_upgradeVersion20beta2()
		 || !self::_upgradeVersion20beta3()
		 || !self::_upgradeVersion202()
		 || !self::_upgradeVersion21beta1()
		 || !self::_upgradeVersion22beta1()
		 || !self::_upgradeVersion22()
		 || !self::_upgradeVersion222()
		 || !self::_upgradeVersion23beta1())
		{
			return;
		}

		// Date de version et historique.
		self::$_config['version'] = system::$galleryVersionDate;
		self::$_config['history'][system::$galleryVersion] = date('Y-m-d H:i:s', time());

		// Supprime les doublons.
		self::$_config['widgets_order'] = array_unique(self::$_config['widgets_order']);

		// Mise à jour de la table "config".
		$config_values = '';
		$config_params = array();
		foreach (self::$_config as $k => &$v)
		{
			if (is_array($v))
			{
				$v = serialize($v);
			}
			$config_values .= '("'. $k . '", :' . $k . '), ';
			$config_params[$k] = $v;
		}

		// Début de la transaction.
		if (!utils::$db->transaction())
		{
			return;
		}

		// Suppression du contenu de la table.
		// DELETE et non TRUNCATE car transaction !
		$sql = 'DELETE FROM ' . CONF_DB_PREF . 'config';
		if (utils::$db->exec($sql, FALSE) === FALSE
		 || utils::$db->nbResult === 0)
		{
			return;
		}

		// Insertion de la nouvelle configuration.
		$sql = 'INSERT INTO ' . CONF_DB_PREF . 'config
				(conf_name, conf_value) VALUES ' . substr($config_values, 0, -2);
		if (utils::$db->prepare($sql) === FALSE
		 || utils::$db->executeExec($config_params) === FALSE
		 || utils::$db->nbResult === 0)
		{
			return;
		}

		// Exécution de la transaction.
		if (!utils::$db->commit())
		{
			return;
		}

		self::$success = TRUE;
	}

	/**
	 * Mise à jour vers la version 2.0 bêta 1.
	 *
	 * @return boolean
	 */
	private static function _upgradeVersion20beta1()
	{
		// Suppression du paramètre "date d'installation",
		// qui fusionne avec l'historique des mises à jour.
		if (isset(self::$_config['history']['install']))
		{
			self::$_config['history'] = array(
				'2.0-alpha-1' => self::$_config['history']['install']
			);
			unset(self::$_config['history']['install']);
		}

		// Nouveaux paramètres de la table "config".
		self::_addConfigParams(array(
			'admin_dashboard_errors' => 0,
			'gallery_description_guest' => '',
			'diaporama_resize_gd_height' => 750,
			'diaporama_resize_gd_width' => 1000,
			'diaporama_resize_gd_quality' => 85,
			'upload_categories_empty' => 1
		));
		if (!isset(self::$_config['gallery_description']))
		{
			$sql = 'SELECT cat_desc
					  FROM ' . CONF_DB_PREF . 'categories
					 WHERE cat_id = "1"
					 LIMIT 1';
			if (utils::$db->query($sql, 'value') === FALSE)
			{
				return FALSE;
			}
			self::$_config['gallery_description'] = utils::$db->queryResult;
		}
		if (!isset(self::$_config['thumbs_alb_nb']))
		{
			if (isset(self::$_config['thumbs_alb_columns'])
			 && isset(self::$_config['thumbs_alb_lines']))
			{
				self::$_config['thumbs_alb_nb'] = (int) self::$_config['thumbs_alb_columns']
					* (int) self::$_config['thumbs_alb_lines'];
			}
			else
			{
				self::$_config['thumbs_alb_nb'] = 18;
			}
		}
		if (!isset(self::$_config['thumbs_cat_nb']))
		{
			if (isset(self::$_config['thumbs_cat_columns'])
			 && isset(self::$_config['thumbs_cat_lines']))
			{
				self::$_config['thumbs_cat_nb'] = (int) self::$_config['thumbs_cat_columns']
					* (int) self::$_config['thumbs_cat_lines'];
			}
			else
			{
				self::$_config['thumbs_cat_nb'] = 8;
			}
		}

		// Suppression de paramètres de la table "config".
		self::_deleteConfigParams(array(
			'geoloc_key', 'thumbs_alb_columns', 'thumbs_alb_lines',
			'thumbs_cat_columns', 'thumbs_cat_lines'
		));

		// Modification de paramètres de la table "config".
		self::$_config['admin_group_perms_default']
			= 'a:2:{s:5:"admin";a:2:{s:13:"albums_access";a:0:{}s:5:"perms";a:'
			. '21:{s:11:"access_mode";i:0;s:10:"albums_add";i:0;s:11:"albums_e'
			. 'dit";i:0;s:12:"albums_modif";i:0;s:14:"albums_pending";i:0;s:11'
			. ':"admin_votes";i:0;s:3:"all";i:0;s:13:"comments_edit";i:0;s:16:'
			. '"comments_options";i:0;s:3:"ftp";i:0;s:15:"infos_incidents";i:0'
			. ';s:15:"settings_config";i:0;s:18:"settings_functions";i:0;s:20:'
			. '"settings_maintenance";i:0;s:16:"settings_options";i:0;s:14:"se'
			. 'ttings_pages";i:0;s:15:"settings_themes";i:0;s:16:"settings_wid'
			. 'gets";i:0;s:4:"tags";i:0;s:13:"users_members";i:0;s:13:"users_o'
			. 'ptions";i:0;}}s:7:"gallery";a:2:{s:13:"albums_access";a:0:{}s:5'
			. ':"perms";a:15:{s:11:"access_mode";i:0;s:12:"add_comments";i:0;s'
			. ':17:"add_comments_mode";i:0;s:11:"alert_email";i:0;s:10:"adv_se'
			. 'arch";i:0;s:13:"create_albums";i:0;s:4:"edit";i:0;s:10:"edit_ow'
			. 'ner";i:0;s:12:"members_list";i:0;s:7:"options";i:0;s:13:"read_c'
			. 'omments";i:0;s:5:"votes";i:0;s:6:"upload";i:0;s:19:"upload_crea'
			. 'te_owner";i:0;s:11:"upload_mode";i:0;}}}';

		// Suppression du champ "fav_id" de la table "favorites".
		$sql = 'SHOW COLUMNS FROM ' . CONF_DB_PREF . 'favorites';
		$fetch_style = array('fetch' => PDO::FETCH_ASSOC, 'column' => 'Field');
		if (utils::$db->query($sql, $fetch_style) === FALSE
		|| utils::$db->nbResult === 0)
		{
			return FALSE;
		}
		$favorites_columns = utils::$db->queryResult;
		if (isset($favorites_columns['fav_id']))
		{
			$sql = 'ALTER TABLE ' . CONF_DB_PREF . 'favorites
					DROP COLUMN fav_id';
			if (utils::$db->exec($sql, FALSE) === FALSE)
			{
				return FALSE;
			}
		}

		// Valeur par défaut pour le style de toutes les catégories.
		$sql = 'UPDATE ' . CONF_DB_PREF . 'categories
				   SET cat_style = NULL
				 WHERE cat_style IS NOT NULL';
		if (utils::$db->exec($sql, FALSE) === FALSE)
		{
			return FALSE;
		}

		return TRUE;
	}

	/**
	 * Mise à jour vers la version 2.0 bêta 2.
	 *
	 * @return boolean
	 */
	private static function _upgradeVersion20beta2()
	{
		$sql = 'SHOW INDEX FROM ' . CONF_DB_PREF . 'users';
		$fetch_style = array('fetch' => PDO::FETCH_ASSOC, 'column' => 'Key_name');
		if (utils::$db->query($sql, $fetch_style) === FALSE
		|| utils::$db->nbResult === 0)
		{
			return FALSE;
		}

		// Ajout d'une clé UNIQUE sur la colonne 'session_id' de la table 'users'.
		if (!isset(utils::$db->queryResult[CONF_DB_PREF . 'uk2_users']))
		{
			$sql = 'ALTER TABLE ' . CONF_DB_PREF . 'users
				 ADD CONSTRAINT ' . CONF_DB_PREF . 'uk2_users UNIQUE (session_id)';
			if (utils::$db->exec($sql, FALSE) === FALSE)
			{
				return FALSE;
			}
		}
		if (isset(utils::$db->queryResult['uk2_users']))
		{
			$sql = 'ALTER TABLE ' . CONF_DB_PREF . 'users
					 DROP INDEX uk2_users';
			if (utils::$db->exec($sql, FALSE) === FALSE)
			{
				return FALSE;
			}
		}

		return TRUE;
	}

	/**
	 * Mise à jour vers la version 2.0 bêta 3.
	 *
	 * @return boolean
	 */
	private static function _upgradeVersion20beta3()
	{
		if (!isset(self::$_config['comments_moderate']))
		{
			self::$_config['comments_moderate'] = 0;
		}

		return TRUE;
	}

	/**
	 * Mise à jour vers la version 2.0.2.
	 *
	 * @return boolean
	 */
	private static function _upgradeVersion202()
	{
		// Modification du type des colonnes 'image_path' et 'cat_path'.
		$dbname = preg_replace('`^.*dbname=([^$]+)$`', '$1', CONF_DB_DSN);
		$fetch_style = array('fetch' => PDO::FETCH_ASSOC, 'column' => 'Field');

		foreach (array('images' => 'image_path', 'categories' => 'cat_path')
		as $table_name => $col_name)
		{
			$sql = 'SHOW COLUMNS FROM `' . $dbname . '`.`' . CONF_DB_PREF . $table_name . '`';
			if (utils::$db->query($sql, $fetch_style) === FALSE
			|| utils::$db->nbResult === 0)
			{
				return FALSE;
			}
			$columns = utils::$db->queryResult;

			if ($columns[$col_name]['Type'] == 'varchar(255)')
			{
				$sql = 'ALTER TABLE ' . CONF_DB_PREF . $table_name . '
							 CHANGE ' . $col_name . ' ' . $col_name . ' VARBINARY(255) NOT NULL';
				if (utils::$db->exec($sql, FALSE) === FALSE)
				{
					return FALSE;
				}
			}
		}

		// Ajout d'une nouvelle option pour la fonctionnalité RSS.
		self::_addConfigParams(array('rss_notify_albums' => 0));

		// Ajout de nouveaux réglages pour la page des membres.
		if (!isset(self::$_config['pages_params']['members']['show_crtdt']))
		{
			self::$_config['pages_params']['members']['show_crtdt'] = 1;
		}
		if (!isset(self::$_config['pages_params']['members']['show_lastvstdt']))
		{
			self::$_config['pages_params']['members']['show_lastvstdt'] = 0;
		}
		if (!isset(self::$_config['pages_params']['members']['show_title']))
		{
			self::$_config['pages_params']['members']['show_title'] = 1;
		}

		return TRUE;
	}

	/**
	 * Mise à jour vers la version 2.1 bêta 1.
	 *
	 * @return boolean
	 */
	private static function _upgradeVersion21beta1()
	{
		// Création de la table 'basket'.
		$dbname = preg_replace('`^.*dbname=([^$]+)$`', '$1', CONF_DB_DSN);
		$sql = 'SHOW TABLES FROM `' . $dbname . '`';
		$fetch_style = array('fetch' => PDO::FETCH_ASSOC, 'column' => 'Tables_in_' . $dbname);
		if (utils::$db->query($sql, $fetch_style) === FALSE
		|| utils::$db->nbResult === 0)
		{
			return FALSE;
		}
		if (!isset(utils::$db->queryResult[CONF_DB_PREF . 'basket']))
		{
			$sql = array(
				'CREATE TABLE IF NOT EXISTS ' . CONF_DB_PREF . 'basket (
					user_id SMALLINT NOT NULL,
					session_id SMALLINT NOT NULL DEFAULT 0,
					image_id INTEGER NOT NULL,
					basket_date DATETIME NOT NULL
					) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;',
				'ALTER TABLE ' . CONF_DB_PREF . 'basket
				   ADD CONSTRAINT ' . CONF_DB_PREF . 'uk1_basket
					   UNIQUE (user_id, session_id, image_id);',
				'ALTER TABLE ' . CONF_DB_PREF . 'basket
				   ADD CONSTRAINT ' . CONF_DB_PREF . 'fk1_basket
					   FOREIGN KEY (image_id) REFERENCES ' . CONF_DB_PREF . 'images (image_id)
					   ON DELETE CASCADE,
				   ADD CONSTRAINT ' . CONF_DB_PREF . 'fk2_basket
					   FOREIGN KEY (user_id) REFERENCES ' . CONF_DB_PREF . 'users (user_id)
					   ON DELETE CASCADE;'
			);
			if (utils::$db->exec($sql, FALSE) === FALSE)
			{
				return;
			}
		}

		// Modification du type de la colonne 'conf_name' de la table 'config'.
		$sql = 'SHOW COLUMNS FROM ' . CONF_DB_PREF . 'config';
		$fetch_style = array('fetch' => PDO::FETCH_ASSOC, 'column' => 'Field');
		if (utils::$db->query($sql, $fetch_style) === FALSE
		|| utils::$db->nbResult === 0)
		{
			return FALSE;
		}
		if (utils::$db->queryResult['conf_name']['Type'] != 'varchar(60)')
		{
			$sql = 'ALTER TABLE ' . CONF_DB_PREF . 'config
					     CHANGE conf_name conf_name VARCHAR(60) NOT NULL';
			if (utils::$db->exec($sql, FALSE) === FALSE)
			{
				return FALSE;
			}
		}

		// Nouveaux paramètres de configuration.
		if (!isset(self::$_config['mail_auto_sender_address']))
		{
			$sql = 'SELECT user_email
					  FROM ' . CONF_DB_PREF . 'users
					 WHERE user_id = 1';
			if (utils::$db->query($sql, 'value') === FALSE
			|| utils::$db->nbResult === 0)
			{
				return FALSE;
			}
			self::$_config['mail_auto_sender_address'] = utils::$db->queryResult;
		}
		self::_addConfigParams(array(
			'basket' => 0,
			'basket_max_filesize' => 51200,
			'basket_max_images' => 100,
			'desc_template_categories_active' => 0,
			'desc_template_categories_text' => '{DESCRIPTION}',
			'desc_template_images_active' => 0,
			'desc_template_images_text' => '{DESCRIPTION}',
			'download_zip_albums' => 0,
			'mail_notify_comment_subject' => 'nouveau commentaire dans la galerie',
			'mail_notify_comment_message' => 'Un nouveau commentaire a été posté sur'
				. ' une image de la galerie {GALLERY_URL}.',
			'mail_notify_comment_auth_subject' => 'nouveau commentaire dans la galerie',
			'mail_notify_comment_auth_message' => 'Un nouveau commentaire a été posté sur'
				. ' une image de la galerie {GALLERY_URL}.',
			'mail_notify_comment_pending_subject' => 'nouveau commentaire en attente dans'
				. ' la galerie',
			'mail_notify_comment_pending_message' => 'Un nouveau commentaire posté sur une image'
				. ' de la galerie {GALLERY_URL} a été mis en attente de validation.',
			'mail_notify_comment_pending_auth_subject' => 'nouveau commentaire en attente'
				. ' dans la galerie',
			'mail_notify_comment_pending_auth_message' => 'Un nouveau commentaire posté sur'
				. ' une image de la galerie {GALLERY_URL} a été mis en attente de validation.',
			'mail_notify_images_subject' => 'nouvelles images dans la galerie',
			'mail_notify_images_message' => 'De nouvelles images ont été ajoutées'
				. ' à la galerie {GALLERY_URL}.',
			'mail_notify_images_pending_subject' => 'nouvelles images en attente dans la galerie',
			'mail_notify_images_pending_message' => 'De nouvelles images ont été mises'
				. ' en attente de validation dans la galerie {GALLERY_URL}.',
			'mail_notify_inscription_subject' => 'nouvelle inscription dans la galerie',
			'mail_notify_inscription_message' => 'Un nouvel utilisateur vient de s\'enregistrer'
				. ' ({USER_LOGIN}).\nVous pouvez consulter son profil ici : {USER_URL}',
			'mail_notify_inscription_pending_subject' => 'nouvelle inscription dans la galerie',
			'mail_notify_inscription_pending_message' => 'Un nouvel utilisateur vient de'
				. ' s\'enregistrer ({USER_LOGIN}), et est en attente de validation'
				. ' par un administrateur.',
			'thumbs_cat_extended' => 0,
			'upload_resize' => 0,
			'upload_resize_maxheight' => 768,
			'upload_resize_maxwidth' => 1024,
			'upload_resize_quality' => 85
		));

		// Suppression du format pour l'information Exif 'ExposureTime'.
		if (isset(self::$_config['exif_params']['ExposureTime']['format']))
		{
			unset(self::$_config['exif_params']['ExposureTime']['format']);
		}

		// Ajout de la nouvelle page 'basket'.
		if (!in_array('basket', self::$_config['pages_order']))
		{
			array_unshift(self::$_config['pages_order'], 'basket');
			self::$_config['pages_params']['basket'] = array('status' => 1);
		}

		// Ajout d'une nouvelle permission de groupe.
		if (!self::_modifyGroupsPerms(array('gallery' => array('image_original' => 1))))
		{
			return FALSE;
		}

		// Changement du type de la colonne 'image_rotation' de la table 'images'.
		$sql = 'SHOW COLUMNS FROM ' . CONF_DB_PREF . 'images';
		$fetch_style = array('fetch' => PDO::FETCH_ASSOC, 'column' => 'Field');
		if (utils::$db->query($sql, $fetch_style) === FALSE
		|| utils::$db->nbResult === 0)
		{
			return FALSE;
		}
		if (strlen(utils::$db->queryResult['image_rotation']['Type']) < 20)
		{
			$sql = array(
				'UPDATE ' . CONF_DB_PREF . 'images
					SET image_rotation = "1"',
				'ALTER TABLE ' . CONF_DB_PREF . 'images
					  CHANGE image_rotation image_rotation
						ENUM("1","2","3","4","5","6","7","8") DEFAULT "1"'
			);
			if (utils::$db->exec($sql, FALSE) === FALSE)
			{
				return FALSE;
			}
		}

		// Ajout des colonnes 'cat_uploadable' et 'cat_creatable' à la table 'categories'.
		$sql = 'SHOW COLUMNS FROM ' . CONF_DB_PREF . 'categories';
		$fetch_style = array('fetch' => PDO::FETCH_ASSOC, 'column' => 'Field');
		if (utils::$db->query($sql, $fetch_style) === FALSE
		|| utils::$db->nbResult === 0)
		{
			return FALSE;
		}
		if (!isset(utils::$db->queryResult['cat_uploadable']))
		{
			$sql = 'ALTER TABLE ' . CONF_DB_PREF . 'categories
					  ADD COLUMN cat_creatable ENUM("0","1") NOT NULL DEFAULT "1"
						   AFTER cat_commentable,
					  ADD COLUMN cat_uploadable ENUM("0","1") NOT NULL DEFAULT "1"
						   AFTER cat_commentable';
			if (utils::$db->exec($sql, FALSE) === FALSE)
			{
				return FALSE;
			}
		}

		return TRUE;
	}

	/**
	 * Mise à jour vers la version 2.2 bêta 1.
	 *
	 * @return boolean
	 */
	private static function _upgradeVersion22beta1()
	{
		// Changement du type de la colonne 'tag_name' de la table 'tags'.
		$sql = 'SHOW COLUMNS FROM ' . CONF_DB_PREF . 'tags';
		$fetch_style = array('fetch' => PDO::FETCH_ASSOC, 'column' => 'Field');
		if (utils::$db->query($sql, $fetch_style) === FALSE
		|| utils::$db->nbResult === 0)
		{
			return FALSE;
		}
		if (!strstr(strtolower(utils::$db->queryResult['tag_name']['Type']), 'varbinary'))
		{
			$sql = 'ALTER TABLE ' . CONF_DB_PREF . 'tags
				   CHANGE tag_name tag_name VARBINARY(255) NOT NULL';
			if (utils::$db->exec($sql, FALSE) === FALSE)
			{
				return FALSE;
			}
		}

		// Changement du type de la colonne 'image_crtdt' de la table 'images'.
		$sql = 'SHOW COLUMNS FROM ' . CONF_DB_PREF . 'images';
		$fetch_style = array('fetch' => PDO::FETCH_ASSOC, 'column' => 'Field');
		if (utils::$db->query($sql, $fetch_style) === FALSE
		|| utils::$db->nbResult === 0)
		{
			return FALSE;
		}
		if (!strstr(strtolower(utils::$db->queryResult['image_crtdt']['Type']), 'datetime'))
		{
			$sql = 'ALTER TABLE ' . CONF_DB_PREF . 'images
				   CHANGE image_crtdt image_crtdt DATETIME';
			if (utils::$db->exec($sql, FALSE) === FALSE)
			{
				return FALSE;
			}
		}

		// Ajout de la colonne 'cat_orderby' sur la table 'categories'.
		$sql = 'SHOW COLUMNS FROM ' . CONF_DB_PREF . 'categories';
		$fetch_style = array('fetch' => PDO::FETCH_ASSOC, 'column' => 'Field');
		if (utils::$db->query($sql, $fetch_style) === FALSE
		|| utils::$db->nbResult === 0)
		{
			return FALSE;
		}
		if (!isset(utils::$db->queryResult['cat_orderby']))
		{
			$sql = 'ALTER TABLE ' . CONF_DB_PREF . 'categories
					  ADD COLUMN cat_orderby VARCHAR(255)
						   AFTER cat_style';
			if (utils::$db->exec($sql, FALSE) === FALSE)
			{
				return FALSE;
			}
		}

		// Nouveaux paramètres de configuration.
		self::_addConfigParams(array(
			'admin_dashboard_start_message' => 0,
			'blacklist_emails' => '',
			'diaporama_carousel' => 0,
			'geoloc_type' => 'HYBRID',
			'recaptcha_private_key' => '6LeBhNESAAAAAO7e39CcymBz0DNdiX7wSw7vpw34',
			'recaptcha_public_key' => '6LeBhNESAAAAADtWlBjlrPg7Xjab8bgstcaa3gOM',
			'recaptcha_comments' => 0,
			'recaptcha_contact' => 0,
			'recaptcha_inscriptions' => 0,
			'users_locked_albums_access_mode' => 'groups',
			'users_only_members' => 0
		));

		// Ajout de nouveaux réglages pour la page 'carte du monde'.
		if (!isset(self::$_config['pages_params']['worldmap']['center_lat']))
		{
			self::$_config['pages_params']['worldmap']['center_lat'] = 25;
		}
		if (!isset(self::$_config['pages_params']['worldmap']['center_long']))
		{
			self::$_config['pages_params']['worldmap']['center_long'] = 5;
		}
		if (!isset(self::$_config['pages_params']['worldmap']['zoom']))
		{
			self::$_config['pages_params']['worldmap']['zoom'] = 2;
		}

		// Ajout du widget "utilisateurs en ligne".
		if (!in_array('online_users', self::$_config['widgets_order']))
		{
			array_push(self::$_config['widgets_order'], 'online_users');
			self::$_config['widgets_params']['online_users'] = array(
				'params' => array(
					'duration' => 300,
					'order_by' => 'user_login ASC'
				),
				'status' => 0,
				'title' => ''
			);
		}

		// Ajout du paramètre de configuration 'CONF_THUMBS_PROTECT'.
		if (!defined('CONF_THUMBS_PROTECT'))
		{
			files::addConfig(array(array(
				'after' => 'CONF_THUMBS_DIR',
				'name' => 'CONF_THUMBS_PROTECT',
				'value' => 0
			)));
		}

		// Ajout de nouvelles règles de réécriture d'URL au fichier .htaccess.
		$params = array(
			'after' => '^[#\s]*RewriteEngine',
			'test' => 'RewriteRule image/',
			'lines' => array(
				"\t" . 'RewriteRule ^sitemap\.xml$ sitemap.php [L,NC]',
				"\t" . 'RewriteRule image/[^/]+/(\d+)-.*'
					 . '\.(gif|jpe?g|png)$ image.php?id=$1 [L,NC]'
			)
		);
		self::_addToHtaccess($params);

		return TRUE;
	}

	/**
	 * Mise à jour vers la version 2.2.
	 *
	 * @return boolean
	 */
	private static function _upgradeVersion22()
	{
		// Nouveaux paramètres de configuration.
		self::_addConfigParams(array(
			'mail_notify_comment_follow_subject'
				=> 'nouveau commentaire sur une image où vous avez posté',
			'mail_notify_comment_follow_message'
				=> 'Un invité ({AUTHOR}) a posté un nouveau commentaire sur l\'image {IMAGE_URL}'
				 . ' que vous suivez.\n\nCe message vous a été envoyé automatiquement car vous'
				 . ' avez activé dans votre profil l\'option de notification "Nouveaux'
				 . ' commentaires sur les images où j\'ai posté".',
			'mail_notify_comment_follow_auth_subject'
				=> 'nouveau commentaire sur une image où vous avez posté',
			'mail_notify_comment_follow_auth_message'
				=> 'Un membre ({USER_LOGIN}) a posté un nouveau commentaire sur l\'image'
				 . ' {IMAGE_URL} que vous suivez.\n\nCe message vous a été envoyé automatiquement'
				 . ' car vous avez activé dans votre profil l\'option de notification "Nouveaux'
				 . ' commentaires sur les images où j\'ai posté".',
			'recaptcha_comments_guest_only' => 1
		));

		// Ajout du paramètre Exif pour l'objectif.
		self::_addExifInfo(
			'UndefinedTag:0xA434',
			array('status' => 1),
			'Model'
		);

		// Ajout de l'option de notification de suivi de commentaires.
		$sql = 'SHOW COLUMNS FROM ' . CONF_DB_PREF . 'users';
		$fetch_style = array('fetch' => PDO::FETCH_ASSOC, 'column' => 'Field');
		if (utils::$db->query($sql, $fetch_style) === FALSE
		|| utils::$db->nbResult === 0)
		{
			return FALSE;
		}
		$columns = utils::$db->queryResult;
		if (!strstr(strtolower($columns['user_alert']['Type']), 'char(6)'))
		{
			$sql = array(
				'ALTER TABLE ' . CONF_DB_PREF . 'users
					  CHANGE user_alert user_alert CHAR(6) NOT NULL DEFAULT "000000"',
				'UPDATE ' . CONF_DB_PREF . 'users
					SET user_alert = CONCAT(user_alert, "0")'
			);
			if (utils::$db->exec($sql, FALSE) === FALSE)
			{
				return FALSE;
			}
		}

		return TRUE;
	}

	/**
	 * Mise à jour vers la version 2.2.2.
	 *
	 * @return boolean
	 */
	private static function _upgradeVersion222()
	{
		// Ajout du paramètre Exif "FocalLengthIn35mmFilm".
		self::_addExifInfo(
			'FocalLengthIn35mmFilm',
			array(
				'status' => 0,
				'format' => '%2.2f mm'
			),
			'FocalLength'
		);

		return TRUE;
	}

	/**
	 * Mise à jour vers la version 2.3 bêta 1.
	 *
	 * @return boolean
	 */
	private static function _upgradeVersion23beta1()
	{
		// Nouveaux paramètres de configuration.
		self::_addConfigParams(array(
			'images_anti_copy' => '0',
			'mail_auto_bcc' => '1',
			'mail_auto_primary_recipient_address' => '',
			'mail_notify_guestbook_subject'
				=> 'nouveau commentaire dans le livre d\'or de la galerie',
			'mail_notify_guestbook_message'
				=> 'Un nouveau commentaire a été ajouté au livre d\'or'
				. ' de la galerie {GALLERY_URL}.',
			'mail_notify_guestbook_auth_subject'
				=> 'nouveau commentaire dans le livre d\'or de la galerie',
			'mail_notify_guestbook_auth_message'
				=> 'Un nouveau commentaire a été ajouté au livre d\'or'
				. ' de la galerie {GALLERY_URL}.',
			'mail_notify_guestbook_pending_subject'
				=> 'nouveau commentaire en attente dans le livre d\'or de la galerie',
			'mail_notify_guestbook_pending_message' =>
				'Un nouveau commentaire ajouté au livre d\'or de la galerie'
				. ' {GALLERY_URL} a été mis en attente de validation.',
			'mail_notify_guestbook_pending_auth_subject'
				=> 'nouveau commentaire en attente dans le livre d\'or de la galerie',
			'mail_notify_guestbook_pending_auth_message'
				=> 'Un nouveau commentaire ajouté au livre d\'or de la galerie'
				. ' {GALLERY_URL} a été mis en attente de validation.',
			'nohits_useragent' => '0',
			'nohits_useragent_list' => '',
			'users_inscription_autocat' => '0',
			'users_inscription_autocat_category' => '1',
			'users_inscription_autocat_title' => '{USER_LOGIN}',
			'users_inscription_autocat_type' => 'album',
			'users_log_activity' => '1',
			'users_log_activity_delete' => '0',
			'users_log_activity_delete_days' => '90',
			'watermark_categories' => '0',
			'watermark_params_default' => serialize(array(
				'background_active' => TRUE,
				'background_alpha' => 50,
				'background_color' => '#ffffff',
				'background_large' => TRUE,
				'background_padding' => 1,
				'border_active' => 0,
				'border_alpha' => 0,
				'border_color' => '#304b62',
				'border_size' => 1,
				'image_active' => FALSE,
				'image_file' => '',
				'image_file_md5' => '',
				'image_opacity' => 100,
				'image_size_pct' => 10,
				'image_size_type' => 'fixed',
				'image_position' => 'bottom right',
				'image_x' => 10,
				'image_y' => 10,
				'quality' => 85,
				'text' => '',
				'text_active' => FALSE,
				'text_alpha' => 0,
				'text_color' => '#000000',
				'text_external' => FALSE,
				'text_font' => 'Veranda.ttf',
				'text_position' => 'bottom right',
				'text_shadow_active' => FALSE,
				'text_shadow_alpha' => 0,
				'text_shadow_color' => '#959595',
				'text_shadow_size' => 2,
				'text_size_fixed' => 10,
				'text_size_pct' => 30,
				'text_size_type' => 'fixed',
				'text_x' => 10,
				'text_y' => 10,
				'watermark' => 'default'
			))
		));

		// Changement du type de la colonne 'com_lastupddt' de la table 'comments'.
		$sql = 'SHOW COLUMNS FROM ' . CONF_DB_PREF . 'comments';
		$fetch_style = array('fetch' => PDO::FETCH_ASSOC, 'column' => 'Field');
		if (utils::$db->query($sql, $fetch_style) === FALSE
		|| utils::$db->nbResult === 0)
		{
			return FALSE;
		}
		if (strstr(strtolower(utils::$db->queryResult['com_lastupddt']['Type']),
		'current_timestamp'))
		{
			$sql = 'ALTER TABLE ' . CONF_DB_PREF . 'comments
				   CHANGE com_lastupddt com_lastupddt DATETIME NOT NULL';
			if (utils::$db->exec($sql, FALSE) === FALSE)
			{
				return FALSE;
			}
		}

		// Liste des tables existantes.
		$dbname = preg_replace('`^.*dbname=([^$]+)$`', '$1', CONF_DB_DSN);
		$sql = 'SHOW TABLES FROM `' . $dbname . '`';
		$fetch_style = array('fetch' => PDO::FETCH_ASSOC, 'column' => 'Tables_in_' . $dbname);
		if (utils::$db->query($sql, $fetch_style) === FALSE
		|| utils::$db->nbResult === 0)
		{
			return FALSE;
		}

		// Création de la table 'guestbook'.
		if (!isset(utils::$db->queryResult[CONF_DB_PREF . 'guestbook']))
		{
			$sql = array(
				'CREATE TABLE IF NOT EXISTS ' . CONF_DB_PREF . 'guestbook (
					guestbook_id INTEGER NOT NULL,
					user_id SMALLINT NOT NULL DEFAULT 0,
					guestbook_crtdt DATETIME NOT NULL,
					guestbook_lastupddt DATETIME NOT NULL,
					guestbook_author VARCHAR(255) NOT NULL,
					guestbook_email VARCHAR(255),
					guestbook_website VARCHAR(255),
					guestbook_ip VARCHAR(39) NOT NULL,
					guestbook_message TEXT NOT NULL,
					guestbook_rate TINYINT,
					guestbook_status ENUM("-1","0","1") NOT NULL DEFAULT "1"
					) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;',
				'ALTER TABLE ' . CONF_DB_PREF . 'guestbook
					MODIFY guestbook_id INTEGER NOT NULL AUTO_INCREMENT,
					ADD CONSTRAINT ' . CONF_DB_PREF . 'pk1_guestbook PRIMARY KEY (guestbook_id);',
				'ALTER TABLE ' . CONF_DB_PREF . 'guestbook
					ADD FOREIGN KEY (user_id) REFERENCES ' . CONF_DB_PREF . 'users (user_id);'
			);
			if (utils::$db->exec($sql, FALSE) === FALSE)
			{
				return;
			}
		}

		// Création de la table 'users_logs'.
		if (!isset(utils::$db->queryResult[CONF_DB_PREF . 'users_logs']))
		{
			$sql = array(
				'CREATE TABLE IF NOT EXISTS ' . CONF_DB_PREF . 'users_logs (
					log_id BIGINT NOT NULL,
					user_id SMALLINT NOT NULL,
					log_page TEXT NOT NULL,
					log_date DATETIME NOT NULL,
					log_action VARCHAR(64) NOT NULL,
					log_match VARCHAR(255),
					log_post TEXT,
					log_ip VARCHAR(39) NOT NULL
					) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;',
				'ALTER TABLE ' . CONF_DB_PREF . 'users_logs
					MODIFY log_id BIGINT NOT NULL AUTO_INCREMENT,
					ADD CONSTRAINT ' . CONF_DB_PREF . 'pk1_logs PRIMARY KEY (log_id);'
			);
			if (utils::$db->exec($sql, FALSE) === FALSE)
			{
				return;
			}
		}

		// Ajout des colonnes 'image_exif', 'image_iptc' et 'image_xmp' à la table 'images'.
		$sql = 'SHOW COLUMNS FROM ' . CONF_DB_PREF . 'images';
		$fetch_style = array('fetch' => PDO::FETCH_ASSOC, 'column' => 'Field');
		if (utils::$db->query($sql, $fetch_style) === FALSE
		|| utils::$db->nbResult === 0)
		{
			return FALSE;
		}
		if (!isset(utils::$db->queryResult['image_exif']))
		{
			$sql = 'ALTER TABLE ' . CONF_DB_PREF . 'images
					 ADD COLUMN image_exif TEXT
						  AFTER image_filesize,
					 ADD COLUMN image_iptc TEXT
						  AFTER image_exif,
					 ADD COLUMN image_xmp TEXT
						  AFTER image_iptc';
			if (utils::$db->exec($sql, FALSE) === FALSE)
			{
				return FALSE;
			}
		}

		// Ajout des colonnes 'up_exif', 'up_iptc' et 'up_xmp' à la table 'uploads'.
		$sql = 'SHOW COLUMNS FROM ' . CONF_DB_PREF . 'uploads';
		$fetch_style = array('fetch' => PDO::FETCH_ASSOC, 'column' => 'Field');
		if (utils::$db->query($sql, $fetch_style) === FALSE
		|| utils::$db->nbResult === 0)
		{
			return FALSE;
		}
		if (!isset(utils::$db->queryResult['up_exif']))
		{
			$sql = 'ALTER TABLE ' . CONF_DB_PREF . 'uploads
					 ADD COLUMN up_exif TEXT
						  AFTER up_filesize,
					 ADD COLUMN up_iptc TEXT
						  AFTER up_exif,
					 ADD COLUMN up_xmp TEXT
						  AFTER up_iptc';
			if (utils::$db->exec($sql, FALSE) === FALSE)
			{
				return FALSE;
			}
		}

		// Ajout de la colonne 'cat_watermark' à la table 'categories'.
		$sql = 'SHOW COLUMNS FROM ' . CONF_DB_PREF . 'categories';
		$fetch_style = array('fetch' => PDO::FETCH_ASSOC, 'column' => 'Field');
		if (utils::$db->query($sql, $fetch_style) === FALSE
		|| utils::$db->nbResult === 0)
		{
			return FALSE;
		}
		if (!isset(utils::$db->queryResult['cat_watermark']))
		{
			$sql = 'ALTER TABLE ' . CONF_DB_PREF . 'categories
					 ADD COLUMN cat_watermark TEXT
						  AFTER cat_password';
			if (utils::$db->exec($sql, FALSE) === FALSE)
			{
				return FALSE;
			}
		}

		// Changement du type de la colonne 'user_watermark' de la table 'users'.
		$sql = 'SHOW COLUMNS FROM ' . CONF_DB_PREF . 'users';
		$fetch_style = array('fetch' => PDO::FETCH_ASSOC, 'column' => 'Field');
		if (utils::$db->query($sql, $fetch_style) === FALSE
		|| utils::$db->nbResult === 0)
		{
			return FALSE;
		}
		if (!strstr(strtolower(utils::$db->queryResult['user_watermark']['Type']), 'text'))
		{
			$sql = 'ALTER TABLE ' . CONF_DB_PREF . 'users
				   CHANGE user_watermark user_watermark TEXT';
			if (utils::$db->exec($sql, FALSE) === FALSE)
			{
				return FALSE;
			}
		}

		// Mise à jour des paramètres du filigrane.
		if (!isset(self::$_config['watermark_params']['watermark']))
		{
			$booleans = array(
				'background_active',
				'background_large',
				'image_active',
				'text_active',
				'text_external',
				'text_shadow_active'
			);
			foreach (self::$_config['watermark_params'] as $k => &$v)
			{
				if (in_array($k, $booleans))
				{
					$v = (bool) $v;
				}
			}
			self::$_config['watermark_params']['watermark'] = 'default';
		}

		// Ajout de la page 'guestbook'.
		if (!in_array('guestbook', self::$_config['pages_order']))
		{
			array_unshift(self::$_config['pages_order'], 'guestbook');
			self::$_config['pages_params']['guestbook'] = array(
				'nb_per_page' => 20,
				'message' => '',
				'status' => 0
			);
		}

		// Mise à jour de la colonne 'user_watermark' de la table 'users'.
		$sql = 'SELECT user_id,
					   user_watermark
				  FROM ' . CONF_DB_PREF . 'users
				 WHERE user_watermark IS NOT NULL';
		$fetch_style = array('fetch' => PDO::FETCH_ASSOC, 'column' => 'user_id');
		if (utils::$db->query($sql, $fetch_style) === FALSE)
		{
			return FALSE;
		}
		if (utils::$db->nbResult > 0)
		{
			$users = utils::$db->queryResult;
			$params = array();
			foreach ($users as &$infos)
			{
				if (utils::isSerializedArray($infos['user_watermark']))
				{
					continue;
				}
				$watermark_params = self::$_config['watermark_params'];
				$watermark_params['text'] = $infos['user_watermark'];
				$watermark_params['text_active'] = TRUE;
				$watermark_params['watermark'] = 'specific';
				$params[] = array(
					'user_id' => $infos['user_id'],
					'user_watermark' => serialize($watermark_params)
				);
			}
			if (count($params))
			{
				$sql = 'UPDATE ' . CONF_DB_PREF . 'users
						   SET user_watermark = :user_watermark
						 WHERE user_id = :user_id';
				$sql = array(array('sql' => $sql, 'params' => $params));
				if (utils::$db->exec($sql, FALSE) === FALSE)
				{
					return FALSE;
				}
			}
		}

		// Ajout des paramètres Exif "LightSource", "ExposureBiasValue",
		// "MeteringMode", "ColorSpace", "Copyright" et "Artist".
		self::_addExifInfo(
			'LightSource',
			array(
				'status' => 0
			),
			'DateTimeOriginal'
		);
		self::_addExifInfo(
			'ExposureBiasValue',
			array(
				'status' => 0,
				'format' => '%+2.2F Ev'
			),
			'ExposureTime'
		);
		self::_addExifInfo(
			'MeteringMode',
			array(
				'status' => 0
			),
			'ISOSpeedRatings'
		);
		self::_addExifInfo(
			'ColorSpace',
			array(
				'status' => 0
			),
			'YResolution'
		);
		self::_addExifInfo(
			'Copyright',
			array(
				'status' => 0
			),
			'Software'
		);
		self::_addExifInfo(
			'Artist',
			array(
				'status' => 0
			),
			'Software'
		);

		// Ajout du format pour les informations Exif "XResolution" et "YResolution".
		self::$_config['exif_params']['XResolution']['format'] = '%d';
		self::$_config['exif_params']['YResolution']['format'] = '%d';

		return TRUE;
	}
}



/**
 * Méthodes de template.
 */
class tpl
{
	/**
	 * L'élément de mise à jour $item doit-il être affiché ?
	 *
	 * @param string $item
	 * @return boolean
	 */
	public function disUpgrade($item)
	{
		switch ($item)
		{
			case 'already' :
				return (bool) upgrade::$already;

			case 'success' :
				return (bool) upgrade::$success;
		}
	}

	/**
	 * Retourne l'élément de mise à jour $item.
	 *
	 * @param string $item
	 * @return string
	 */
	public function getUpgrade($item)
	{
		switch ($item)
		{
			case 'charset' :
				return CONF_CHARSET;

			case 'lang_current_code' :
				return utils::$userLang;

			case 'version' :
				return utils::tplprotect(system::$galleryVersion);
		}
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo substr($tpl->getUpgrade('lang_current_code'), 0, 2); ?>" lang="<?php echo substr($tpl->getUpgrade('lang_current_code'), 0, 2); ?>" dir="ltr">


<head>

<title><?php echo __('Mise à jour'); ?> - iGalerie</title>

<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $tpl->getUpgrade('charset'); ?>" />

<style type="text/css">
h1,p,form,body,html {
	border: 0;
	margin: 0;
	padding: 0;
}
html {
	font-size: 100%;
}
body {
	color: black;
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: .8em;
	background: #fafafa;
}
#global {
	text-align: center;
}
a {
	color: black;
	text-decoration: none;
	border-bottom: 1px solid #884C2E;
	position: relative;
}
a:hover {
	border-width: 2px;
	height: auto; /* Opera Fix */
}
h1 {
	font-size: 200%;
	font-weight: bold;
	border-bottom: 3px solid #858055;
	padding: 20px 15px 5px 22px;
	color: #555;
	font-family: Georgia, "Times New Roman", serif;
	letter-spacing: .02em;
	background: #F6F4E5 url(admin/template/default/style/default/background.png);
}
p {
	margin-bottom: 15px;
}
strong {
	font-size: 110%;
}
#upgrade {
	border: 1px solid #808080;
	width: 520px;
	margin: 25px auto;
	text-align: left;
	background: white;
	box-shadow: 0 0 14px #989898;
	-moz-box-shadow: 0 0 14px #989898;
	-webkit-box-shadow: 0 0 14px #989898;
}
#content {
	padding: 35px 20px 1px;
}
#footer {
	text-align: center;
	border-top: 2px solid #858055;
	background: #F6F4E5 url(admin/template/default/style/default/background.png);
	padding: 5px 0;
	height: 20px;
	margin: 30px 0 0;
}
.icon {
	padding: 1px 0 0 26px;
	background-repeat: no-repeat;
	background-position: 0 0;
	min-height: 18px;
}
.icon span {
	font-weight: bold;
	color: #333;
}
.icon_ok {
	background-image: url(admin/template/default/style/default/icons/18x18/success.png);
}
.icon_error {
	background-image: url(admin/template/default/style/default/icons/18x18/error.png);
}
.icon_information {
	background-image: url(admin/template/default/style/default/icons/18x18/information.png);
}
input.submit {
	border: 1px solid silver;
	outline: 0;
	background: url(admin/template/default/style/default/submit.png) repeat-x center;
	margin: 20px 0 0 3px;
	padding: 0 6px 2px;
	height: 26px;
	white-space: nowrap;
	vertical-align: middle;
	display: inline-block;
	overflow: visible;
	font-family: Arial, Verdana, sans-serif;
	border-radius: 5px;
}
input.submit:hover {
	background: url(admin/template/default/style/default/submit-hover.png) repeat-x top left;
	box-shadow: 0 0 2px gray;
}
</style>

</head>


<body>

<div id="global">

	<div id="upgrade">
		<h1><?php echo __('Mise à jour'); ?></h1>
		<div id="content">
<?php if ($tpl->disUpgrade('already')) : ?>
			<p class="icon icon_information"><span><?php echo __('iGalerie est déjà à jour.'); ?></span></p>
<?php elseif (empty($_POST['upgrade'])) : ?>
			<p><?php printf(__('Cliquez sur ce bouton pour mettre à jour iGalerie vers la version %s.'), '<strong>' . $tpl->getUpgrade('version') . '</strong>'); ?></p>
			<form action="" method="post">
				<div>
					<input name="upgrade" class="submit" value="<?php echo __('Mettre à jour iGalerie'); ?>" type="submit" />
				</div>
			</form>
<?php elseif ($tpl->disUpgrade('success')) : ?>
			<p class="icon icon_ok"><span><?php echo __('Mise à jour effectuée avec succès !'); ?></span></p>
			<p><?php printf(__('N\'oubliez pas de supprimer le fichier %s avant de poursuivre.'), basename(__FILE__)); ?></p>
<?php else : ?>
			<p class="icon icon_error"><span><?php echo __('La mise à jour a échouée.'); ?></span></p>
<?php endif; ?>
		</div>
		<p id="footer"><a href="http://www.igalerie.org/">www.igalerie.org</a></p>
	</div>

</div>

</body>


</html>