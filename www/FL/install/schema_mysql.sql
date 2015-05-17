-- DROP TABLE

DROP TABLE IF EXISTS igal2_basket;

DROP TABLE IF EXISTS igal2_config;

DROP TABLE IF EXISTS igal2_history;

DROP TABLE IF EXISTS igal2_search;

DROP TABLE IF EXISTS igal2_cameras_models_images;

DROP TABLE IF EXISTS igal2_cameras_models;

DROP TABLE IF EXISTS igal2_cameras_brands;

DROP TABLE IF EXISTS igal2_comments;

DROP TABLE IF EXISTS igal2_guestbook;

DROP TABLE IF EXISTS igal2_favorites;

DROP TABLE IF EXISTS igal2_users_logs;

DROP TABLE IF EXISTS igal2_passwords;

DROP TABLE IF EXISTS igal2_tags_images;

DROP TABLE IF EXISTS igal2_tags;

DROP TABLE IF EXISTS igal2_uploads;

DROP TABLE IF EXISTS igal2_votes;

DROP TABLE IF EXISTS igal2_images;

DROP TABLE IF EXISTS igal2_categories;

DROP TABLE IF EXISTS igal2_users;

DROP TABLE IF EXISTS igal2_groups;

DROP TABLE IF EXISTS igal2_sessions;



-- CREATE TABLE

CREATE TABLE igal2_basket (
	user_id SMALLINT NOT NULL,
	session_id SMALLINT NOT NULL DEFAULT 0,
	image_id INTEGER NOT NULL,
	basket_date DATETIME NOT NULL
	) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE igal2_cameras_brands (
	camera_brand_id SMALLINT NOT NULL,
	camera_brand_name VARCHAR(255) NOT NULL,
	camera_brand_url VARCHAR(255) NOT NULL
	) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE igal2_cameras_models (
	camera_model_id SMALLINT NOT NULL,
	camera_brand_id SMALLINT NOT NULL,
	camera_model_name VARCHAR(255) NOT NULL,
	camera_model_url VARCHAR(255) NOT NULL
	) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE igal2_cameras_models_images (
	camera_model_id SMALLINT NOT NULL,
	image_id INTEGER NOT NULL
	) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE igal2_categories (
	cat_id INTEGER NOT NULL,
	user_id SMALLINT NOT NULL,
	thumb_id INTEGER NOT NULL DEFAULT 0,
		-- Valeurs de thumb_id :
		-- 		-1 : catégorie vide
		--  	 0 : vignette externe
		--  	sinon, identifiant de l'image servant de vignette
	parent_id INTEGER NOT NULL DEFAULT 0,
	cat_path VARBINARY(255) NOT NULL,
	cat_name TEXT NOT NULL,
	cat_url VARCHAR(255) NOT NULL,
	cat_desc TEXT,
	cat_lat DECIMAL(17,15),
	cat_long DECIMAL(18,15),
	cat_place VARCHAR(100),
	cat_tb_infos VARCHAR(60),
		-- Format de cat_tb_infos :
		-- 	Wtb.Htb.x,y,w,h (informations pour 'vignette interne')
		-- 	Wtb.Htb.x,y,w,h.Wimg.Himg.type (informations pour 'vignette externe')
	cat_a_size BIGINT NOT NULL DEFAULT 0,
	cat_a_subalbs INTEGER NOT NULL DEFAULT 0,
	cat_a_subcats INTEGER NOT NULL DEFAULT 0,
	cat_a_albums INTEGER NOT NULL DEFAULT 0,
	cat_a_images INTEGER NOT NULL DEFAULT 0,
	cat_a_hits INTEGER NOT NULL DEFAULT 0,
	cat_a_comments INTEGER NOT NULL DEFAULT 0,
	cat_a_votes INTEGER NOT NULL DEFAULT 0,
	cat_a_rate DOUBLE PRECISION NOT NULL DEFAULT 0,
	cat_d_size BIGINT NOT NULL DEFAULT 0,
	cat_d_subalbs INTEGER NOT NULL DEFAULT 0,
	cat_d_subcats INTEGER NOT NULL DEFAULT 0,
	cat_d_albums INTEGER NOT NULL DEFAULT 0,
	cat_d_images INTEGER NOT NULL DEFAULT 0,
	cat_d_hits INTEGER NOT NULL DEFAULT 0,
	cat_d_comments INTEGER NOT NULL DEFAULT 0,
	cat_d_votes INTEGER NOT NULL DEFAULT 0,
	cat_d_rate DOUBLE PRECISION NOT NULL DEFAULT 0,
	cat_votable ENUM("0","1") NOT NULL DEFAULT "1",
	cat_commentable ENUM("0","1") NOT NULL DEFAULT "1",
	cat_uploadable ENUM("0","1") NOT NULL DEFAULT "1",
	cat_creatable ENUM("0","1") NOT NULL DEFAULT "1",
	cat_crtdt DATETIME NOT NULL,
	cat_lastadddt DATETIME,
	cat_filemtime DATETIME,
	cat_style VARCHAR(48),
	cat_orderby VARCHAR(255),
	cat_password VARCHAR(52),
	cat_watermark TEXT,
	cat_status ENUM("0","1") NOT NULL DEFAULT "1",
	cat_position INTEGER NOT NULL DEFAULT 0
	) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE igal2_comments (
	com_id INTEGER NOT NULL,
	user_id SMALLINT NOT NULL DEFAULT 0,
	image_id INTEGER NOT NULL,
	com_crtdt DATETIME NOT NULL,
	com_lastupddt DATETIME NOT NULL,
	com_author VARCHAR(255) NOT NULL,
	com_email VARCHAR(255),
	com_website VARCHAR(255),
	com_ip VARCHAR(39) NOT NULL,
	com_message TEXT NOT NULL,
	com_status ENUM("-1","0","1") NOT NULL DEFAULT "1"
		-- Valeurs de com_status :
		-- 		-1 : en attente de validation
		--  	 0 : non publié
		--  	 1 : publié
	) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE igal2_config (
	conf_name VARCHAR(60) NOT NULL,
	conf_value TEXT
	) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE igal2_favorites (
	user_id SMALLINT NOT NULL,
	image_id INTEGER NOT NULL,
	fav_date DATETIME NOT NULL
	) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE igal2_guestbook (
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
		-- Valeurs de guestbook_status :
		-- 		-1 : en attente de validation
		--  	 0 : non publié
		--  	 1 : publié
	) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE igal2_groups (
	group_id SMALLINT NOT NULL,
	group_name TEXT NOT NULL,
	group_title TEXT NOT NULL,
	group_desc TEXT,
	group_crtdt DATETIME NOT NULL,
	group_perms TEXT NOT NULL,
	group_admin ENUM("0","1") NOT NULL DEFAULT "0"
	) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE igal2_history (
	history_date DATE NOT NULL,
	history_albums INTEGER NOT NULL,
	history_images INTEGER NOT NULL,
	history_size BIGINT NOT NULL,
	history_hits INTEGER NOT NULL,
	history_comments INTEGER NOT NULL,
	history_votes INTEGER NOT NULL,
	history_rate DOUBLE PRECISION NOT NULL,
	history_favorites INTEGER NOT NULL,
	history_tags INTEGER NOT NULL,
	history_admins SMALLINT NOT NULL,
	history_members SMALLINT NOT NULL
	) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE igal2_images (
	image_id INTEGER NOT NULL,
	user_id SMALLINT NOT NULL,
	cat_id INTEGER NOT NULL,
	image_path VARBINARY(255) NOT NULL,
	image_url VARCHAR(255) NOT NULL,
	image_width SMALLINT NOT NULL,
	image_height SMALLINT NOT NULL,
	image_tb_infos VARCHAR(35),
	image_filesize INTEGER NOT NULL,
	image_exif TEXT,
	image_iptc TEXT,
	image_xmp TEXT,
	image_rotation ENUM("1","2","3","4","5","6","7","8") DEFAULT "1",
	image_lat DECIMAL(17,15),
	image_long DECIMAL(18,15),
	image_place VARCHAR(100),
	image_name TEXT NOT NULL,
	image_desc TEXT,
	image_adddt DATETIME NOT NULL,
	image_crtdt DATETIME,
	image_hits INTEGER NOT NULL DEFAULT 0,
	image_comments INTEGER NOT NULL DEFAULT 0,
	image_votes INTEGER NOT NULL DEFAULT 0,
	image_rate DOUBLE PRECISION NOT NULL DEFAULT 0,
	image_status ENUM("0","1") NOT NULL DEFAULT "1",
	image_position INTEGER NOT NULL DEFAULT 0
	) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE igal2_users_logs (
	log_id BIGINT NOT NULL,
	user_id SMALLINT NOT NULL,
	log_page TEXT NOT NULL,
	log_date DATETIME NOT NULL,
	log_action VARCHAR(64) NOT NULL,
	log_match VARCHAR(255),
	log_post TEXT,
	log_ip VARCHAR(39) NOT NULL
	) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE igal2_passwords (
	session_id SMALLINT NOT NULL,
	password CHAR(40) NOT NULL
	) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE igal2_search (
	search_id CHAR(12) NOT NULL,
	search_query VARCHAR(255) NOT NULL,
	search_options TEXT,
	search_date TIMESTAMP ON UPDATE CURRENT_TIMESTAMP DEFAULT CURRENT_TIMESTAMP
	) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE igal2_sessions (
	session_id SMALLINT NOT NULL,
	session_token CHAR(40) NOT NULL,
	session_expire DATETIME NOT NULL
	) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE igal2_tags (
	tag_id SMALLINT NOT NULL,
	tag_name VARBINARY(255) NOT NULL,
	tag_url VARCHAR(255) NOT NULL
	) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE igal2_tags_images (
	tag_id SMALLINT NOT NULL,
	image_id INTEGER NOT NULL
	) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE igal2_uploads (
	up_id SMALLINT NOT NULL,
	cat_id INTEGER NOT NULL,
	user_id SMALLINT NOT NULL,
	up_file VARCHAR(255) NOT NULL,
	up_type VARCHAR(1) NOT NULL,
	up_filesize INTEGER NOT NULL,
	up_exif TEXT,
	up_iptc TEXT,
	up_xmp TEXT,
	up_name VARCHAR(255),
	up_desc TEXT,
	up_height SMALLINT NOT NULL,
	up_width SMALLINT NOT NULL,
	up_adddt DATETIME NOT NULL,
	up_ip VARCHAR(39) NOT NULL
	) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE igal2_users (
	user_id SMALLINT NOT NULL,
		-- Les deux premiers identifiants sont réservés :
		-- 	1 : superadmin
		-- 	2 : invité (guest)
	group_id SMALLINT NOT NULL DEFAULT 3,
	session_id SMALLINT,
	user_login VARCHAR(64) NOT NULL,
	user_password CHAR(40) NOT NULL,
	user_name VARCHAR(255),
	user_firstname VARCHAR(255),
	user_sex ENUM("F","M"),
	user_birthdate DATE,
	user_loc VARCHAR(255),
	user_desc TEXT,
	user_email VARCHAR(255),
	user_website VARCHAR(255),
	user_other TEXT,
	user_prefs TEXT,
	user_watermark TEXT,
	user_avatar ENUM("0","1") NOT NULL DEFAULT "0",
	user_nohits ENUM("0","1") NOT NULL DEFAULT "0",
	user_alert CHAR(6) NOT NULL DEFAULT "000000",
		-- Valeurs pour user_alert :
		-- 	Chaque chiffre ne peut prendre que deux valeurs possibles : 0 (désactivé) ou 1 (activé).
		-- 	Premier		: notification pour chaque nouvelle inscription (admins seulement)
		-- 	Second		: notification pour chaque nouveau commentaire (admins seulement)
		-- 	Troisième	: notification pour chaque nouveau commentaire en attente de validation (admins seulement)
		-- 	Quatrième	: notification pour chaque nouvelle image ajoutée à la galerie
		-- 	Cinquième	: notification pour chaque nouvelle image en attente de validation
		-- 	Sixième		: notification pour le suivi de nouveaux commentaires
	user_lang CHAR(5) NOT NULL,
	user_tz VARCHAR(32) NOT NULL,
	user_status ENUM("-2","-1","0","1") NOT NULL DEFAULT "1",
		-- Valeurs pour user_status :
		-- 	-2 : en attente de validation par courriel
		-- 	-1 : en attente de validation par un admin
		--   0 : suspendu
		--   1 : activé
	user_crtdt DATETIME NOT NULL,
	user_crtip VARCHAR(39) NOT NULL,
	user_lastvstdt DATETIME,
	user_lastvstip VARCHAR(39),
	user_rkey CHAR(40),
	user_rdate DATETIME
	) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE igal2_votes (
	vote_id INTEGER NOT NULL,
	user_id SMALLINT NOT NULL DEFAULT 0,
	image_id INTEGER NOT NULL,
	vote_rate INTEGER NOT NULL,
	vote_date DATETIME NOT NULL,
	vote_ip VARCHAR(39) NOT NULL,
	vote_cookie CHAR(32) NOT NULL
	) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;



-- AUTO_INCREMENT
-- PRIMARY KEY
-- UNIQUE

ALTER TABLE igal2_basket
	ADD CONSTRAINT igal2_uk1_basket UNIQUE (user_id, session_id, image_id);

ALTER TABLE igal2_cameras_brands
	MODIFY camera_brand_id SMALLINT NOT NULL AUTO_INCREMENT,
	ADD CONSTRAINT igal2_pk1_cameras_brands PRIMARY KEY (camera_brand_id),
	ADD CONSTRAINT igal2_uk1_cameras_brands UNIQUE (camera_brand_name);

ALTER TABLE igal2_cameras_models
	MODIFY camera_model_id SMALLINT NOT NULL AUTO_INCREMENT,
	ADD CONSTRAINT igal2_pk1_cameras_models PRIMARY KEY (camera_model_id),
	ADD CONSTRAINT igal2_uk1_cameras_models UNIQUE (camera_model_name);

ALTER TABLE igal2_cameras_models_images
	ADD CONSTRAINT igal2_uk1_cameras_models_images UNIQUE (image_id);

ALTER TABLE igal2_categories
	MODIFY cat_id INTEGER NOT NULL AUTO_INCREMENT,
	ADD CONSTRAINT igal2_pk1_categories PRIMARY KEY (cat_id),
	ADD CONSTRAINT igal2_uk1_categories UNIQUE (cat_path);

ALTER TABLE igal2_comments
	MODIFY com_id INTEGER NOT NULL AUTO_INCREMENT,
	ADD CONSTRAINT igal2_pk1_comments PRIMARY KEY (com_id);

ALTER TABLE igal2_config
	ADD CONSTRAINT igal2_uk1_config UNIQUE (conf_name);

ALTER TABLE igal2_favorites
	ADD CONSTRAINT igal2_uk1_favorites UNIQUE (user_id, image_id);

ALTER TABLE igal2_guestbook
	MODIFY guestbook_id INTEGER NOT NULL AUTO_INCREMENT,
	ADD CONSTRAINT igal2_pk1_guestbook PRIMARY KEY (guestbook_id);

ALTER TABLE igal2_groups
	MODIFY group_id SMALLINT NOT NULL AUTO_INCREMENT,
	ADD CONSTRAINT igal2_pk1_groups PRIMARY KEY (group_id);

ALTER TABLE igal2_history
	ADD CONSTRAINT igal2_pk1_history PRIMARY KEY (history_date);

ALTER TABLE igal2_images
	MODIFY image_id INTEGER NOT NULL AUTO_INCREMENT,
	ADD CONSTRAINT igal2_pk1_images PRIMARY KEY (image_id),
	ADD CONSTRAINT igal2_uk1_images UNIQUE (image_path);

ALTER TABLE igal2_users_logs
	MODIFY log_id BIGINT NOT NULL AUTO_INCREMENT,
	ADD CONSTRAINT igal2_pk1_users_logs PRIMARY KEY (log_id);

ALTER TABLE igal2_passwords
	ADD CONSTRAINT igal2_uk1_passwords UNIQUE (session_id, password);

ALTER TABLE igal2_search
	ADD CONSTRAINT igal2_uk1_search UNIQUE (search_id);

ALTER TABLE igal2_sessions
	MODIFY session_id SMALLINT NOT NULL AUTO_INCREMENT,
	ADD CONSTRAINT igal2_pk1_sessions PRIMARY KEY (session_id),
	ADD CONSTRAINT igal2_uk1_sessions UNIQUE (session_token);

ALTER TABLE igal2_tags
	MODIFY tag_id SMALLINT NOT NULL AUTO_INCREMENT,
	ADD CONSTRAINT igal2_pk1_tags PRIMARY KEY (tag_id),
	ADD CONSTRAINT igal2_uk1_tags UNIQUE (tag_name);

ALTER TABLE igal2_tags_images
	ADD CONSTRAINT igal2_uk1_tags_images UNIQUE (tag_id, image_id);

ALTER TABLE igal2_users
	MODIFY user_id SMALLINT NOT NULL AUTO_INCREMENT,
	ADD CONSTRAINT igal2_pk1_users PRIMARY KEY (user_id),
	ADD CONSTRAINT igal2_uk1_users UNIQUE (user_login),
	ADD CONSTRAINT igal2_uk2_users UNIQUE (session_id);

ALTER TABLE igal2_uploads
	MODIFY up_id SMALLINT NOT NULL AUTO_INCREMENT,
	ADD CONSTRAINT igal2_pk1_uploads PRIMARY KEY (up_id),
	ADD CONSTRAINT igal2_uk1_uploads UNIQUE (up_file, cat_id),
	ADD CONSTRAINT igal2_uk2_uploads UNIQUE (up_name, cat_id);

ALTER TABLE igal2_votes
	MODIFY vote_id INTEGER NOT NULL AUTO_INCREMENT,
	ADD CONSTRAINT igal2_pk1_votes PRIMARY KEY (vote_id);



-- FOREIGN KEY

ALTER TABLE igal2_basket
	ADD CONSTRAINT igal2_fk1_basket
		FOREIGN KEY (image_id) REFERENCES igal2_images (image_id)
		ON DELETE CASCADE,
	ADD CONSTRAINT igal2_fk2_basket
		FOREIGN KEY (user_id) REFERENCES igal2_users (user_id)
		ON DELETE CASCADE;

ALTER TABLE igal2_cameras_models
	ADD CONSTRAINT igal2_fk1_cameras_models
		FOREIGN KEY (camera_brand_id) REFERENCES igal2_cameras_brands (camera_brand_id)
		ON DELETE CASCADE;

ALTER TABLE igal2_cameras_models_images
	ADD CONSTRAINT igal2_fk1_cameras_models_images
		FOREIGN KEY (camera_model_id) REFERENCES igal2_cameras_models (camera_model_id)
		ON DELETE CASCADE,
	ADD CONSTRAINT igal2_fk2_cameras_models_images
		FOREIGN KEY (image_id) REFERENCES igal2_images (image_id)
		ON DELETE CASCADE;

ALTER TABLE igal2_categories
	ADD FOREIGN KEY (user_id) REFERENCES igal2_users (user_id),
	ADD CONSTRAINT igal2_fk1_categories
		FOREIGN KEY (parent_id) REFERENCES igal2_categories (cat_id)
		ON DELETE CASCADE;

ALTER TABLE igal2_comments
	ADD CONSTRAINT igal2_fk1_comments
		FOREIGN KEY (image_id) REFERENCES igal2_images (image_id)
		ON DELETE CASCADE,
	ADD FOREIGN KEY (user_id) REFERENCES igal2_users (user_id);

ALTER TABLE igal2_favorites
	ADD CONSTRAINT igal2_fk1_favorites
		FOREIGN KEY (image_id) REFERENCES igal2_images (image_id)
		ON DELETE CASCADE,
	ADD CONSTRAINT igal2_fk2_favorites
		FOREIGN KEY (user_id) REFERENCES igal2_users (user_id)
		ON DELETE CASCADE;

ALTER TABLE igal2_guestbook
	ADD FOREIGN KEY (user_id) REFERENCES igal2_users (user_id);

ALTER TABLE igal2_images
	ADD FOREIGN KEY (user_id) REFERENCES igal2_users (user_id),
	ADD CONSTRAINT igal2_fk1_images
		FOREIGN KEY (cat_id) REFERENCES igal2_categories (cat_id)
		ON DELETE CASCADE;

ALTER TABLE igal2_passwords
	ADD CONSTRAINT igal2_fk1_passwords
		FOREIGN KEY (session_id) REFERENCES igal2_sessions (session_id)
		ON DELETE CASCADE;

ALTER TABLE igal2_tags_images
	ADD CONSTRAINT igal2_fk1_tags_images
		FOREIGN KEY (tag_id) REFERENCES igal2_tags (tag_id)
		ON DELETE CASCADE,
	ADD CONSTRAINT igal2_fk2_tags_images
		FOREIGN KEY (image_id) REFERENCES igal2_images (image_id)
		ON DELETE CASCADE;

ALTER TABLE igal2_users
	ADD FOREIGN KEY (group_id) REFERENCES igal2_groups (group_id),
	ADD CONSTRAINT igal2_fk1_users
		FOREIGN KEY (session_id) REFERENCES igal2_sessions (session_id)
		ON DELETE SET NULL;

ALTER TABLE igal2_uploads
	ADD CONSTRAINT igal2_fk1_uploads
		FOREIGN KEY (user_id) REFERENCES igal2_users (user_id)
		ON DELETE CASCADE,
	ADD CONSTRAINT igal2_fk2_uploads
		FOREIGN KEY (cat_id) REFERENCES igal2_categories (cat_id)
		ON DELETE CASCADE;

ALTER TABLE igal2_votes
	ADD CONSTRAINT igal2_fk1_votes
		FOREIGN KEY (image_id) REFERENCES igal2_images (image_id)
		ON DELETE CASCADE,
	ADD FOREIGN KEY (user_id) REFERENCES igal2_users (user_id);



-- INSERT

INSERT INTO igal2_groups VALUES (1, '', '', NULL, NOW(), 'a:2:{s:5:"admin";a:2:{s:13:"albums_access";a:0:{}s:5:"perms";a:21:{s:11:"access_mode";i:1;s:10:"albums_add";i:0;s:11:"albums_edit";i:0;s:12:"albums_modif";i:0;s:14:"albums_pending";i:0;s:11:"admin_votes";i:0;s:3:"all";i:1;s:13:"comments_edit";i:0;s:16:"comments_options";i:0;s:3:"ftp";i:0;s:15:"infos_incidents";i:0;s:15:"settings_config";i:0;s:18:"settings_functions";i:0;s:20:"settings_maintenance";i:0;s:16:"settings_options";i:0;s:14:"settings_pages";i:0;s:15:"settings_themes";i:0;s:16:"settings_widgets";i:0;s:4:"tags";i:0;s:13:"users_members";i:0;s:13:"users_options";i:0;}}s:7:"gallery";a:2:{s:13:"albums_access";a:0:{}s:5:"perms";a:16:{s:11:"access_mode";i:1;s:12:"add_comments";i:1;s:17:"add_comments_mode";i:1;s:10:"adv_search";i:1;s:11:"alert_email";i:1;s:13:"create_albums";i:1;s:4:"edit";i:1;s:10:"edit_owner";i:0;s:14:"image_original";i:1;s:12:"members_list";i:1;s:7:"options";i:1;s:13:"read_comments";i:1;s:5:"votes";i:1;s:6:"upload";i:1;s:19:"upload_create_owner";i:0;s:11:"upload_mode";i:1;}}}', '1');

INSERT INTO igal2_groups VALUES (2, '', '', NULL, NOW(), 'a:2:{s:5:"admin";a:2:{s:13:"albums_access";a:0:{}s:5:"perms";a:21:{s:11:"access_mode";i:0;s:10:"albums_add";i:0;s:11:"albums_edit";i:0;s:12:"albums_modif";i:0;s:14:"albums_pending";i:0;s:11:"admin_votes";i:0;s:3:"all";i:0;s:13:"comments_edit";i:0;s:16:"comments_options";i:0;s:3:"ftp";i:0;s:15:"infos_incidents";i:0;s:15:"settings_config";i:0;s:18:"settings_functions";i:0;s:20:"settings_maintenance";i:0;s:16:"settings_options";i:0;s:14:"settings_pages";i:0;s:15:"settings_themes";i:0;s:16:"settings_widgets";i:0;s:4:"tags";i:0;s:13:"users_members";i:0;s:13:"users_options";i:0;}}s:7:"gallery";a:2:{s:13:"albums_access";a:0:{}s:5:"perms";a:16:{s:11:"access_mode";i:0;s:12:"add_comments";i:0;s:17:"add_comments_mode";i:0;s:10:"adv_search";i:0;s:11:"alert_email";i:0;s:13:"create_albums";i:0;s:4:"edit";i:0;s:10:"edit_owner";i:0;s:14:"image_original";i:1;s:12:"members_list";i:0;s:7:"options";i:0;s:13:"read_comments";i:0;s:5:"votes";i:0;s:6:"upload";i:0;s:19:"upload_create_owner";i:0;s:11:"upload_mode";i:0;}}}', '0');

INSERT INTO igal2_groups VALUES (3, '', '', NULL, NOW(), 'a:2:{s:5:"admin";a:2:{s:13:"albums_access";a:0:{}s:5:"perms";a:21:{s:11:"access_mode";i:0;s:10:"albums_add";i:0;s:11:"albums_edit";i:0;s:12:"albums_modif";i:0;s:14:"albums_pending";i:0;s:11:"admin_votes";i:0;s:3:"all";i:0;s:13:"comments_edit";i:0;s:16:"comments_options";i:0;s:3:"ftp";i:0;s:15:"infos_incidents";i:0;s:15:"settings_config";i:0;s:18:"settings_functions";i:0;s:20:"settings_maintenance";i:0;s:16:"settings_options";i:0;s:14:"settings_pages";i:0;s:15:"settings_themes";i:0;s:16:"settings_widgets";i:0;s:4:"tags";i:0;s:13:"users_members";i:0;s:13:"users_options";i:0;}}s:7:"gallery";a:2:{s:13:"albums_access";a:0:{}s:5:"perms";a:16:{s:11:"access_mode";i:0;s:12:"add_comments";i:0;s:17:"add_comments_mode";i:0;s:10:"adv_search";i:0;s:11:"alert_email";i:0;s:13:"create_albums";i:0;s:4:"edit";i:0;s:10:"edit_owner";i:0;s:14:"image_original";i:1;s:12:"members_list";i:0;s:7:"options";i:0;s:13:"read_comments";i:0;s:5:"votes";i:0;s:6:"upload";i:0;s:19:"upload_create_owner";i:0;s:11:"upload_mode";i:0;}}}', '0');

INSERT INTO igal2_users (user_id, group_id, user_login, user_password, user_lang, user_tz, user_crtdt, user_crtip) VALUES (1, 1, 'admin', '0000000000000000000000000000000000000000', 'fr_FR', 'Europe/Paris', NOW(), '127.0.0.1');

INSERT INTO igal2_users (user_id, group_id, user_login, user_password, user_lang, user_tz, user_crtdt, user_crtip) VALUES (2, 2, 'guest', '0000000000000000000000000000000000000000', '', '', NOW(), '');

INSERT INTO igal2_categories (cat_id, user_id, parent_id, cat_path, cat_name, cat_url, cat_crtdt) VALUES (1, 1, 1, '.', '', '', NOW());

INSERT INTO igal2_config (conf_name, conf_value) VALUES
		--
		-- Paramètres de la galerie
		('version', ''),
		('history', ''),
		('key', ''),
		('html_filter', '1'),
		('level_separator', ' / '),
		('nav_bar', 'top'),
		('gallery_closure', '0'),
		('gallery_closure_message', ''),
		('gallery_title', ''),
		('gallery_description', ''),
		('gallery_description_guest', ''),
		('gallery_footer_message', ''),
		('gallery_banner', 'a:4:{s:6:"banner";i:0;s:3:"src";s:0:"";s:5:"width";s:0:"";s:6:"height";s:0:"";}'),
		('debug_sql', '0'),
		('exec_time', '0'),
		('db_daily_update', ''), -- Date des dernières opérations de base de données effectuées une fois par jour.
		('db_close_template', '1'), -- Fermeture de la connexion à la base de données avant le chargement du template.
		('nohits_useragent', '0'),
		('nohits_useragent_list', ''),
		--
		-- Paramètres de sécurité.
		('sessions_expire', '172800'),
		('passwords_usleep', '2000000'),
		('anticsrf_token_expire', '1800'),
		('anticsrf_token_unique', '0'),
		--
		-- Listes noires.
		('blacklist_emails', ''),
		('blacklist_ips', ''),
		('blacklist_names', '*best price*\n*casino*\n*cialis*\n*viagra*'),
		('blacklist_words', '<embed\n<html\n<img\n<meta\n<object\n<script\n<style\n[email\n[img\n[url\nhref =\nhref=\nsrc =\nsrc='),
		--
		-- Paramètres de localisations.
		('locale_langs', ''),
		('lang_switch', '0'),
		('lang_client', '1'),
		--
		-- reCaptcha.
		('recaptcha_private_key', '6LeBhNESAAAAAO7e39CcymBz0DNdiX7wSw7vpw34'),
		('recaptcha_public_key', '6LeBhNESAAAAADtWlBjlrPg7Xjab8bgstcaa3gOM'),
		('recaptcha_comments', '0'),
		('recaptcha_comments_guest_only', '1'),
		('recaptcha_contact', '0'),
		('recaptcha_inscriptions', '0'),
		--
		-- Gestion des erreurs.
		('errors_last_mail', '0'), -- Contrôle de l'envoi des notifications par e-mail.
		--
		-- Options d'administration.
		('admin_dashboard_start_message', '1'),
		('admin_dashboard_errors', '0'), -- Notifier les erreurs sur le tableau de bord ?
		('admin_template', 'default'),
		('admin_style', 'default'),
		('admin_group_perms_default', 'a:2:{s:5:"admin";a:2:{s:13:"albums_access";a:0:{}s:5:"perms";a:21:{s:11:"access_mode";i:0;s:10:"albums_add";i:0;s:11:"albums_edit";i:0;s:12:"albums_modif";i:0;s:14:"albums_pending";i:0;s:11:"admin_votes";i:0;s:3:"all";i:0;s:13:"comments_edit";i:0;s:16:"comments_options";i:0;s:3:"ftp";i:0;s:15:"infos_incidents";i:0;s:15:"settings_config";i:0;s:18:"settings_functions";i:0;s:20:"settings_maintenance";i:0;s:16:"settings_options";i:0;s:14:"settings_pages";i:0;s:15:"settings_themes";i:0;s:16:"settings_widgets";i:0;s:4:"tags";i:0;s:13:"users_members";i:0;s:13:"users_options";i:0;}}s:7:"gallery";a:2:{s:13:"albums_access";a:0:{}s:5:"perms";a:16:{s:11:"access_mode";i:0;s:12:"add_comments";i:0;s:17:"add_comments_mode";i:0;s:10:"adv_search";i:0;s:11:"alert_email";i:0;s:13:"create_albums";i:0;s:4:"edit";i:0;s:10:"edit_owner";i:0;s:14:"image_original";i:1;s:12:"members_list";i:0;s:7:"options";i:0;s:13:"read_comments";i:0;s:5:"votes";i:0;s:6:"upload";i:0;s:19:"upload_create_owner";i:0;s:11:"upload_mode";i:0;}}}'),
		--
		-- Gestion de membres.
		('users', '0'),
		('users_profile_infos', 'a:3:{s:7:"counter";i:0;s:5:"infos";a:8:{s:9:"birthdate";a:2:{s:8:"activate";i:0;s:8:"required";i:0;}s:4:"desc";a:2:{s:8:"activate";i:0;s:8:"required";i:0;}s:5:"email";a:2:{s:8:"activate";i:0;s:8:"required";i:0;}s:9:"firstname";a:2:{s:8:"activate";i:0;s:8:"required";i:0;}s:3:"loc";a:2:{s:8:"activate";i:0;s:8:"required";i:0;}s:4:"name";a:2:{s:8:"activate";i:0;s:8:"required";i:0;}s:3:"sex";a:2:{s:8:"activate";i:0;s:8:"required";i:0;}s:7:"website";a:2:{s:8:"activate";i:0;s:8:"required";i:0;}}s:5:"perso";a:0:{}}'),
		('users_desc_maxlength', '500'),
		('users_password_minlength', '6'),
		('users_locked_albums_access_mode', 'groups'),
		('users_log_activity', '1'),
		('users_log_activity_delete', '0'),
		('users_log_activity_delete_days', '90'),
		('users_only_members', '0'),
		--
		-- Gestion de membres : inscriptions.
		('users_inscription', '1'),
		('users_inscription_by_mail', '1'),
		('users_inscription_by_password', '0'),
		('users_inscription_password', ''),
		('users_inscription_password_text', ''),
		('users_inscription_moderate', '0'),
		('users_inscription_autocat', '0'),
		('users_inscription_autocat_category', '1'),
		('users_inscription_autocat_title', '{USER_LOGIN}'),
		('users_inscription_autocat_type', 'album'),
		--
		-- Gestion de membres : envoi d'images.
		('upload_maxfilesize', '500'),
		('upload_maxwidth', '2000'),
		('upload_maxheight', '2000'),
		('upload_resize', '0'),
		('upload_resize_maxwidth', '1024'),
		('upload_resize_maxheight', '768'),
		('upload_resize_quality', '85'),
		('upload_categories_empty', '1'), -- Autoriser l'envoi d'images dans les albums vides et la création de catégories dans les catégories vides ?
		--
		-- Gestion de membres : avatars.
		('avatars', '1'),
		('avatars_maxfilesize', '300'),
		('avatars_maxsize', '200'),
		('avatars_thumbsize', '50'),
		--
		-- Panier.
		('basket', '0'),
		('basket_max_images', '100'),
		('basket_max_filesize', '51200'),
		--
		-- Commentaires.
		('comments', '0'),
		('comments_maxchars', '1000'),
		('comments_maxlines', '20'),
		('comments_moderate', '0'),
		('comments_required_email', '0'),
		('comments_required_website', '0'),
		('comments_order', 'ASC'),
		('comments_antiflood', '15'),
		('comments_smilies', '0'),
		('comments_smilies_icons_pack', 'Fugue'),
		('comments_maxurls', '1'),
		('comments_convert_urls', '1'),
		('comments_words_limit', '1'),
		('comments_words_maxlength', '40'),
		('comments_links_maxlength', '40'),
		--
		-- Diaporama.
		('diaporama', '0'),
		('diaporama_auto_start', '0'),
		('diaporama_carousel', '1'),
		('diaporama_hits', '0'),
		('diaporama_resize_gd', '1'),
		('diaporama_resize_gd_height', '750'),
		('diaporama_resize_gd_width', '1000'),
		('diaporama_resize_gd_quality', '85'),
		--
		-- Votes.
		('votes', '0'),
		--
		-- Téléchargement d'archives Zip.
		('download_zip_albums', '0'),
		--
		-- Tags.
		('tags', '0'),
		--
		-- Métadonnées EXIF.
		('exif', '0'), -- Affichage des informations ?
		('exif_crtdt', '1'),
		('exif_camera', '1'),
		('exif_gps', '1'),
		('exif_order', 'a:26:{i:0;s:4:"Make";i:1;s:5:"Model";i:2;s:19:"UndefinedTag:0xA434";i:3;s:16:"DateTimeOriginal";i:4;s:11:"LightSource";i:5;s:5:"Flash";i:6;s:7:"FNumber";i:7;s:15:"ExposureProgram";i:8;s:12:"ExposureTime";i:9;s:17:"ExposureBiasValue";i:10;s:11:"FocalLength";i:11;s:21:"FocalLengthIn35mmFilm";i:12;s:15:"ISOSpeedRatings";i:13;s:12:"MeteringMode";i:14;s:11:"Orientation";i:15;s:12:"WhiteBalance";i:16;s:16:"MaxApertureValue";i:17;s:13:"SensingMethod";i:18;s:14:"ResolutionUnit";i:19;s:11:"XResolution";i:20;s:11:"YResolution";i:21;s:10:"ColorSpace";i:22;s:8:"Software";i:23;s:6:"Artist";i:24;s:9:"Copyright";i:25;s:11:"ExifVersion";}'),
		('exif_params', 'a:26:{s:6:"Artist";a:1:{s:6:"status";i:0;}s:10:"ColorSpace";a:1:{s:6:"status";i:0;}s:9:"Copyright";a:1:{s:6:"status";i:0;}s:16:"DateTimeOriginal";a:2:{s:6:"status";i:1;s:6:"format";s:18:"%d %B %Y, %H:%M:%S";}s:11:"ExifVersion";a:1:{s:6:"status";i:0;}s:17:"ExposureBiasValue";a:2:{s:6:"status";i:0;s:6:"format";s:9:"%+2.2F Ev";}s:15:"ExposureProgram";a:1:{s:6:"status";i:0;}s:12:"ExposureTime";a:1:{s:6:"status";i:1;}s:5:"Flash";a:1:{s:6:"status";i:1;}s:7:"FNumber";a:2:{s:6:"status";i:1;s:6:"format";s:4:"f/%s";}s:11:"FocalLength";a:2:{s:6:"status";i:1;s:6:"format";s:8:"%2.2F mm";}s:21:"FocalLengthIn35mmFilm";a:2:{s:6:"status";i:0;s:6:"format";s:8:"%2.2F mm";}s:15:"ISOSpeedRatings";a:1:{s:6:"status";i:1;}s:11:"LightSource";a:1:{s:6:"status";i:0;}s:4:"Make";a:1:{s:6:"status";i:1;}s:16:"MaxApertureValue";a:2:{s:6:"status";i:0;s:6:"format";s:8:"%2.2F mm";}s:12:"MeteringMode";a:1:{s:6:"status";i:0;}s:5:"Model";a:1:{s:6:"status";i:1;}s:11:"Orientation";a:1:{s:6:"status";i:0;}s:14:"ResolutionUnit";a:1:{s:6:"status";i:0;}s:13:"SensingMethod";a:1:{s:6:"status";i:0;}s:8:"Software";a:1:{s:6:"status";i:0;}s:19:"UndefinedTag:0xA434";a:1:{s:6:"status";i:1;}s:12:"WhiteBalance";a:1:{s:6:"status";i:0;}s:11:"XResolution";a:2:{s:6:"status";i:0;s:6:"format";s:2:"%d";}s:11:"YResolution";a:2:{s:6:"status";i:0;s:6:"format";s:2:"%d";}}'),
		--
		-- Métadonnées IPTC.
		('iptc', '0'), -- Affichage des informations ?
		('iptc_description', '1'),
		('iptc_keywords', '1'),
		('iptc_title', '1'),
		('iptc_order', 'a:32:{i:0;s:3:"005";i:1;s:3:"007";i:2;s:3:"010";i:3;s:3:"015";i:4;s:3:"020";i:5;s:3:"025";i:6;s:3:"026";i:7;s:3:"027";i:8;s:3:"030";i:9;s:3:"035";i:10;s:3:"040";i:11;s:3:"055";i:12;s:3:"060";i:13;s:3:"065";i:14;s:3:"070";i:15;s:3:"075";i:16;s:3:"080";i:17;s:3:"085";i:18;s:3:"090";i:19;s:3:"092";i:20;s:3:"095";i:21;s:3:"100";i:22;s:3:"101";i:23;s:3:"103";i:24;s:3:"105";i:25;s:3:"110";i:26;s:3:"115";i:27;s:3:"116";i:28;s:3:"118";i:29;s:3:"120";i:30;s:3:"122";i:31;s:3:"130";}'),
		('iptc_params', 'a:32:{s:3:"005";a:1:{s:6:"status";i:0;}s:3:"007";a:1:{s:6:"status";i:0;}s:3:"010";a:1:{s:6:"status";i:0;}s:3:"015";a:1:{s:6:"status";i:0;}s:3:"020";a:1:{s:6:"status";i:0;}s:3:"025";a:1:{s:6:"status";i:1;}s:3:"026";a:1:{s:6:"status";i:0;}s:3:"027";a:1:{s:6:"status";i:0;}s:3:"030";a:1:{s:6:"status";i:0;}s:3:"035";a:1:{s:6:"status";i:0;}s:3:"040";a:1:{s:6:"status";i:0;}s:3:"055";a:1:{s:6:"status";i:1;}s:3:"060";a:1:{s:6:"status";i:0;}s:3:"065";a:1:{s:6:"status";i:0;}s:3:"070";a:1:{s:6:"status";i:0;}s:3:"075";a:1:{s:6:"status";i:0;}s:3:"080";a:1:{s:6:"status";i:1;}s:3:"085";a:1:{s:6:"status";i:0;}s:3:"090";a:1:{s:6:"status";i:1;}s:3:"092";a:1:{s:6:"status";i:0;}s:3:"095";a:1:{s:6:"status";i:0;}i:100;a:1:{s:6:"status";i:0;}i:101;a:1:{s:6:"status";i:0;}i:103;a:1:{s:6:"status";i:0;}i:105;a:1:{s:6:"status";i:1;}i:110;a:1:{s:6:"status";i:1;}i:115;a:1:{s:6:"status";i:1;}i:116;a:1:{s:6:"status";i:1;}i:118;a:1:{s:6:"status";i:1;}i:120;a:1:{s:6:"status";i:1;}i:122;a:1:{s:6:"status";i:0;}i:130;a:1:{s:6:"status";i:0;}}'),
		--
		-- Métadonnées XMP.
		('xmp', '0'), -- Affichage des informations ?
		('xmp_crtdt', '1'),
		('xmp_description', '1'),
		('xmp_keywords', '1'),
		('xmp_title', '1'),
		('xmp_priority', '1'), -- XMP prioritaire sur IPTC et EXIF ?
		('xmp_order', 'a:15:{i:0;s:14:"dc:contributor";i:1;s:11:"dc:coverage";i:2;s:10:"dc:creator";i:3;s:7:"dc:date";i:4;s:14:"dc:description";i:5;s:9:"dc:format";i:6;s:13:"dc:identifier";i:7;s:11:"dc:language";i:8;s:12:"dc:publisher";i:9;s:11:"dc:relation";i:10;s:9:"dc:rights";i:11;s:9:"dc:source";i:12;s:10:"dc:subject";i:13;s:8:"dc:title";i:14;s:7:"dc:type";}'),
		('xmp_params', 'a:15:{s:14:"dc:contributor";a:1:{s:6:"status";i:0;}s:11:"dc:coverage";a:1:{s:6:"status";i:0;}s:10:"dc:creator";a:1:{s:6:"status";i:1;}s:7:"dc:date";a:1:{s:6:"status";i:1;}s:14:"dc:description";a:1:{s:6:"status";i:1;}s:9:"dc:format";a:1:{s:6:"status";i:0;}s:13:"dc:identifier";a:1:{s:6:"status";i:0;}s:11:"dc:language";a:1:{s:6:"status";i:0;}s:12:"dc:publisher";a:1:{s:6:"status";i:0;}s:11:"dc:relation";a:1:{s:6:"status";i:0;}s:9:"dc:rights";a:1:{s:6:"status";i:1;}s:9:"dc:source";a:1:{s:6:"status";i:0;}s:10:"dc:subject";a:1:{s:6:"status";i:0;}s:8:"dc:title";a:1:{s:6:"status";i:1;}s:7:"dc:type";a:1:{s:6:"status";i:0;}}'),
		--
		-- Géolocalisation.
		('geoloc', '0'),
		('geoloc_type', 'HYBRID'),
		--
		-- Thèmes.
		('theme_css', ''),
		('theme_template', 'default'),
		('theme_style', 'blue'),
		--
		-- Plan.
		('map_nb_images', '1'),
		('map_last_update', '0'),
		--
		-- Paramètres des vignettes.
		('thumbs_alb_nb', '18'),
		('thumbs_cat_nb', '8'),
		('thumbs_cat_extended', '0'),
		--
		-- Statistiques sous les vignettes.
		('thumbs_stats_date', '0'),
		('thumbs_stats_albums', '0'),
		('thumbs_stats_comments', '0'),
		('thumbs_stats_filesize', '0'),
		('thumbs_stats_hits', '0'),
		('thumbs_stats_images', '1'),
		('thumbs_stats_votes', '0'),
		('thumbs_stats_size', '0'),
		('thumbs_stats_category_title', '1'),
		('thumbs_stats_image_title', '0'),
		--
		-- Ordre d'affichage des images et des catégories.
		('sql_images_order_by', 'image_adddt DESC,image_name ASC,image_crtdt DESC,'),
		('sql_categories_order_by', 'cat_name ASC,cat_lastadddt DESC,cat_crtdt DESC,'),
		('sql_categories_order_by_type', ''),
		--
		-- Courriel.
		('mail_auto_bcc', '1'),
		('mail_auto_primary_recipient_address', ''),
		('mail_auto_sender_address', ''),
		--
		-- Notifications : nouveau commentaire.
		('mail_notify_comment_subject', 'nouveau commentaire dans la galerie'),
		('mail_notify_comment_message', 'Un nouveau commentaire a été posté sur une image de la galerie {GALLERY_URL}.'),
		('mail_notify_comment_auth_subject', 'nouveau commentaire dans la galerie'),
		('mail_notify_comment_auth_message', 'Un nouveau commentaire a été posté sur une image de la galerie {GALLERY_URL}.'),
		('mail_notify_comment_pending_subject', 'nouveau commentaire en attente dans la galerie'),
		('mail_notify_comment_pending_message', 'Un nouveau commentaire posté sur une image de la galerie {GALLERY_URL} a été mis en attente de validation.'),
		('mail_notify_comment_pending_auth_subject', 'nouveau commentaire en attente dans la galerie'),
		('mail_notify_comment_pending_auth_message', 'Un nouveau commentaire posté sur une image de la galerie {GALLERY_URL} a été mis en attente de validation.'),
		--
		-- Notifications : suivi de commentaire.
		('mail_notify_comment_follow_subject', 'nouveau commentaire sur une image où vous avez posté'),
		('mail_notify_comment_follow_message', 'Un invité ({AUTHOR}) a posté un nouveau commentaire sur l\'image {IMAGE_URL} que vous suivez.\n\nCe message vous a été envoyé automatiquement car vous avez activé dans votre profil l\'option de notification "Nouveaux commentaires sur les images où j\'ai posté".'),
		('mail_notify_comment_follow_auth_subject', 'nouveau commentaire sur une image où vous avez posté'),
		('mail_notify_comment_follow_auth_message', 'Un membre ({USER_LOGIN}) a posté un nouveau commentaire sur l\'image {IMAGE_URL} que vous suivez.\n\nCe message vous a été envoyé automatiquement car vous avez activé dans votre profil l\'option de notification "Nouveaux commentaires sur les images où j\'ai posté".'),
		--
		-- Notifications : livre d'or.
		('mail_notify_guestbook_subject', 'nouveau commentaire dans le livre d\'or de la galerie'),
		('mail_notify_guestbook_message', 'Un nouveau commentaire a été ajouté au livre d\'or de la galerie {GALLERY_URL}.'),
		('mail_notify_guestbook_auth_subject', 'nouveau commentaire dans le livre d\'or de la galerie'),
		('mail_notify_guestbook_auth_message', 'Un nouveau commentaire a été ajouté au livre d\'or de la galerie {GALLERY_URL}.'),
		('mail_notify_guestbook_pending_subject', 'nouveau commentaire en attente dans le livre d\'or de la galerie'),
		('mail_notify_guestbook_pending_message', 'Un nouveau commentaire ajouté au livre d\'or de la galerie {GALLERY_URL} a été mis en attente de validation.'),
		('mail_notify_guestbook_pending_auth_subject', 'nouveau commentaire en attente dans le livre d\'or de la galerie'),
		('mail_notify_guestbook_pending_auth_message', 'Un nouveau commentaire ajouté au livre d\'or de la galerie {GALLERY_URL} a été mis en attente de validation.'),
		--
		-- Notifications : nouvelles images.
		('mail_notify_images_subject', 'nouvelles images dans la galerie'),
		('mail_notify_images_message', 'De nouvelles images ont été ajoutées à la galerie {GALLERY_URL}.'),
		('mail_notify_images_pending_subject', 'nouvelles images en attente dans la galerie'),
		('mail_notify_images_pending_message', 'De nouvelles images ont été mises en attente de validation dans la galerie {GALLERY_URL}.'),
		--
		-- Notifications : inscription.
		('mail_notify_inscription_subject', 'nouvelle inscription dans la galerie'),
		('mail_notify_inscription_message', 'Un nouvel utilisateur vient de s\'enregistrer ({USER_LOGIN}).\nVous pouvez consulter son profil ici : {USER_URL}'),
		('mail_notify_inscription_pending_subject', 'nouvelle inscription dans la galerie'),
		('mail_notify_inscription_pending_message', 'Un nouvel utilisateur vient de s\'enregistrer ({USER_LOGIN}), et est en attente de validation par un administrateur.'),
		--
		-- Modèles de description.
		('desc_template_categories_active', '0'),
		('desc_template_categories_text', '{DESCRIPTION}'),
		('desc_template_images_active', '0'),
		('desc_template_images_text', '{DESCRIPTION}'),
		--
		-- Moteur de recherche.
		('search', '1'),
		('search_advanced', '0'),
		--
		-- Paramètres d'upload.
		('upload_report_all_files', '0'),
		('upload_update_images', '0'),
		('upload_update_thumb_id', '0'),
		--
		-- Paramètres des images.
		('images_anti_copy', '0'),
		('images_direct_link', '0'),
		('images_orientation', '0'),
		('images_orientation_quality', '85'),
		('images_resize', '1'),
		('images_resize_method', '1'), -- 1 : html, 2 : gd
		('images_resize_html_height', '700'),
		('images_resize_html_width', '700'),
		('images_resize_gd_height', '700'),
		('images_resize_gd_width', '700'),
		('images_resize_gd_quality', '85'),
		--
		-- Images récentes.
		('recent_images', '1'),
		('recent_images_time', '20'),
		('recent_images_nb', '1'),
		--
		-- Flux RSS.
		('rss', '0'),
		('rss_max_items', '20'),
		('rss_notify_albums', '0'),
		--
		-- Filigrane.
		('watermark', '0'),
		('watermark_categories', '0'),
		('watermark_params', 'a:36:{s:17:"background_active";b:1;s:16:"background_alpha";i:50;s:16:"background_color";s:7:"#ffffff";s:16:"background_large";b:1;s:18:"background_padding";i:1;s:13:"border_active";i:0;s:12:"border_alpha";i:0;s:12:"border_color";s:7:"#304b62";s:11:"border_size";i:1;s:12:"image_active";b:0;s:10:"image_file";s:0:"";s:14:"image_file_md5";s:0:"";s:13:"image_opacity";i:100;s:14:"image_size_pct";i:10;s:15:"image_size_type";s:5:"fixed";s:14:"image_position";s:12:"bottom right";s:7:"image_x";i:10;s:7:"image_y";i:10;s:7:"quality";i:85;s:4:"text";s:0:"";s:11:"text_active";b:0;s:10:"text_alpha";i:0;s:10:"text_color";s:7:"#000000";s:13:"text_external";b:0;s:9:"text_font";s:11:"Veranda.ttf";s:13:"text_position";s:12:"bottom right";s:18:"text_shadow_active";b:0;s:17:"text_shadow_alpha";i:0;s:17:"text_shadow_color";s:7:"#959595";s:16:"text_shadow_size";i:2;s:15:"text_size_fixed";i:10;s:13:"text_size_pct";i:30;s:14:"text_size_type";s:5:"fixed";s:6:"text_x";i:10;s:6:"text_y";i:10;s:9:"watermark";s:7:"default";}'),
		('watermark_params_default', 'a:36:{s:17:"background_active";b:1;s:16:"background_alpha";i:50;s:16:"background_color";s:7:"#ffffff";s:16:"background_large";b:1;s:18:"background_padding";i:1;s:13:"border_active";i:0;s:12:"border_alpha";i:0;s:12:"border_color";s:7:"#304b62";s:11:"border_size";i:1;s:12:"image_active";b:0;s:10:"image_file";s:0:"";s:14:"image_file_md5";s:0:"";s:13:"image_opacity";i:100;s:14:"image_size_pct";i:10;s:15:"image_size_type";s:5:"fixed";s:14:"image_position";s:12:"bottom right";s:7:"image_x";i:10;s:7:"image_y";i:10;s:7:"quality";i:85;s:4:"text";s:0:"";s:11:"text_active";b:0;s:10:"text_alpha";i:0;s:10:"text_color";s:7:"#000000";s:13:"text_external";b:0;s:9:"text_font";s:11:"Veranda.ttf";s:13:"text_position";s:12:"bottom right";s:18:"text_shadow_active";b:0;s:17:"text_shadow_alpha";i:0;s:17:"text_shadow_color";s:7:"#959595";s:16:"text_shadow_size";i:2;s:15:"text_size_fixed";i:10;s:13:"text_size_pct";i:30;s:14:"text_size_type";s:5:"fixed";s:6:"text_x";i:10;s:6:"text_y";i:10;s:9:"watermark";s:7:"default";}'),
		('watermark_users', '0'),
		--
		-- Pages.
		('pages_order', 'a:10:{i:0;s:7:"sitemap";i:1;s:7:"members";i:2;s:8:"comments";i:3;s:4:"tags";i:4;s:7:"cameras";i:5;s:7:"history";i:6;s:8:"worldmap";i:7;s:6:"basket";i:8;s:9:"guestbook";i:9;s:7:"contact";}'),
		('pages_params', 'a:10:{s:6:"basket";a:1:{s:6:"status";i:1;}s:7:"cameras";a:1:{s:6:"status";i:0;}s:8:"comments";a:2:{s:11:"nb_per_page";i:20;s:6:"status";i:1;}s:7:"contact";a:3:{s:5:"email";s:0:"";s:7:"message";s:0:"";s:6:"status";i:0;}s:9:"guestbook";a:3:{s:11:"nb_per_page";i:20;s:7:"message";s:0:"";s:6:"status";i:0;}s:7:"history";a:1:{s:6:"status";i:0;}s:7:"members";a:6:{s:11:"nb_per_page";i:20;s:8:"order_by";s:15:"user_crtdt DESC";s:10:"show_crtdt";i:1;s:14:"show_lastvstdt";i:0;s:10:"show_title";i:1;s:6:"status";i:1;}s:7:"sitemap";a:1:{s:6:"status";i:0;}s:4:"tags";a:1:{s:6:"status";i:1;}s:8:"worldmap";a:4:{s:10:"center_lat";i:25;s:11:"center_long";i:5;s:6:"status";i:1;s:4:"zoom";i:2;}}'),
		--
		-- Widgets.
		('widgets_order', 'a:10:{i:0;s:10:"navigation";i:1;s:5:"image";i:2;s:4:"user";i:3;s:7:"options";i:4;s:6:"geoloc";i:5;s:5:"links";i:6;s:4:"tags";i:7;s:12:"stats_images";i:8;s:16:"stats_categories";i:9;s:12:"online_users";}'),
		('widgets_params', 'a:10:{s:6:"geoloc";a:2:{s:6:"status";i:1;s:5:"title";s:0:"";}s:5:"image";a:3:{s:6:"params";a:4:{s:6:"albums";a:0:{}s:6:"images";a:0:{}s:4:"mode";s:5:"fixed";s:9:"nb_thumbs";i:1;}s:6:"status";i:0;s:5:"title";s:0:"";}s:5:"links";a:3:{s:5:"items";a:0:{}s:6:"status";i:0;s:5:"title";s:0:"";}s:10:"navigation";a:3:{s:5:"items";a:3:{s:10:"categories";i:1;s:10:"neighbours";i:0;s:6:"search";i:1;}s:6:"status";i:1;s:5:"title";s:0:"";}s:12:"online_users";a:3:{s:6:"params";a:2:{s:8:"duration";i:300;s:8:"order_by";s:14:"user_login ASC";}s:6:"status";i:0;s:5:"title";s:0:"";}s:7:"options";a:3:{s:5:"items";a:15:{s:10:"image_size";i:1;s:9:"nb_thumbs";i:1;s:8:"order_by";i:1;s:6:"recent";i:0;s:6:"styles";i:1;s:13:"thumbs_albums";i:0;s:21:"thumbs_category_title";i:0;s:15:"thumbs_comments";i:1;s:11:"thumbs_date";i:0;s:15:"thumbs_filesize";i:1;s:11:"thumbs_hits";i:1;s:18:"thumbs_image_title";i:1;s:13:"thumbs_images";i:1;s:11:"thumbs_size";i:0;s:12:"thumbs_votes";i:1;}s:6:"status";i:0;s:5:"title";s:0:"";}s:16:"stats_categories";a:3:{s:5:"items";a:7:{s:6:"albums";i:1;s:8:"comments";i:1;s:8:"filesize";i:1;s:4:"hits";i:1;s:6:"images";i:1;s:7:"recents";i:1;s:5:"votes";i:1;}s:6:"status";i:1;s:5:"title";s:0:"";}s:12:"stats_images";a:3:{s:5:"items";a:9:{s:8:"added_by";i:1;s:10:"added_date";i:1;s:8:"comments";i:1;s:12:"created_date";i:1;s:9:"favorites";i:1;s:8:"filesize";i:1;s:4:"hits";i:1;s:4:"size";i:1;s:5:"votes";i:1;}s:6:"status";i:1;s:5:"title";s:0:"";}s:4:"tags";a:3:{s:6:"params";a:1:{s:8:"max_tags";i:15;}s:6:"status";i:1;s:5:"title";s:0:"";}s:4:"user";a:2:{s:6:"status";i:1;s:5:"title";s:0:"";}}');
