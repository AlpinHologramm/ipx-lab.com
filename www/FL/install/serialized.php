<?php

error_reporting(-1);

// Permissions de groupe par défaut.
$admin_group_perms_default = array(
	'admin' => array(
		'albums_access' => array(),				// Identifiants des albums déverrouillés si 'access_mode' == 2.
		'perms' => array(
			// Accès aux albums protégés.
			'access_mode' => 0,					// Mode d'accès aux albums protégés :
												// 0 : aucun
												// 1 : tous
												// 2 : select

			// Albums.
			'albums_add' => 0,					// Ajout d'images par HTTP.
			'albums_edit' => 0,					// Édition des images et catégories :
												// titre, description, tags, date de création, etc.
			'albums_modif' => 0,				// Modification des images et catégories :
												// suppression, désactivation, déplacement, autorisations
												// pour les votes et commentaires, nom de répertoire, tri manuel.
			'albums_pending' => 0,				// Gestion des images en attente de validation.

			// Votes.
			'admin_votes' => 0,					// Gestion des votes.

			// Accès total.
			'all' => 0,

			// Commentaires.
			'comments_edit' => 0,				// Gestion et édition des commentaires.
			'comments_options' => 0,			// Options des commentaires.

			// FTP.
			'ftp' => 0,							// Ajout d'images par FTP.

			// Informations.
			'infos_incidents' => 0,				// Incidents.

			// Réglages.
			'settings_config' => 0,
			'settings_functions' => 0,			// Fonctionnalités.
			'settings_maintenance' => 0,		// Maintenance.
			'settings_options' => 0,			// Options.
			'settings_pages' => 0,				// Pages.
			'settings_themes' => 0,				// Thèmes.
			'settings_widgets' => 0,			// Widgets.

			// Tags.
			'tags' => 0,

			// Utilisateurs.
			'users_members' => 0,				// Gestion des membres.
			'users_options' => 0				// Options utilisateurs.
		)
	),
	'gallery' => array(
		'albums_access' => array(),				// Identifiants des albums déverrouillés si 'access_mode' == 2.
		'perms' => array(
			// Accès aux albums protégés.
			'access_mode' => 0,					// Mode d'accès aux albums protégés :
												// 0 : aucun
												// 1 : tous
												// 2 : select

			// Fonctionnalités de la galerie.
			'add_comments' => 0,				// Ajout de commentaires.
			'add_comments_mode' => 0,			// Mode d'envoi des commentaires :
												// 0 : en attente
												// 1 : direct
			'adv_search' => 0,					// Recherche avancée.
			'alert_email' => 0,					// Notification par courriel des nouvelles images.
			'create_albums' => 0,				// Création d'albums.
			'edit' => 0,						// Édition des catégories et images.
			'edit_owner' => 0,					// Édition des catégories et images uniquement
												// pour celles dont l'utilisateur est propriétaire.
			'image_original' => 1,				// Accès à l'image originale quand l'image est redimensionnée.
			'members_list' => 0,				// Liste des membres.
			'options' => 0,						// Options d'affichage.
			'read_comments' => 0,				// Lecture de commentaires.
			'votes' => 0,						// Votes.
			'upload' => 0,						// Envoi d'images.
			'upload_create_owner' => 0,			// Ajout d'images et création d'albums uniquement
												// dans les catégories dont l'utilisateur est propriétaire.
			'upload_mode' => 0					// Mode d'envoi des images :
												// 0 : en attente
												// 1 : direct
		)
	)
);

// Ordre des informations Exif.
$exif_order = array(
	'Make',
	'Model',
	'UndefinedTag:0xA434',
	'DateTimeOriginal',
	'LightSource',
	'Flash',
	'FNumber',
	'ExposureProgram',
	'ExposureTime',
	'ExposureBiasValue',
	'FocalLength',
	'FocalLengthIn35mmFilm',
	'ISOSpeedRatings',
	'MeteringMode',
	'Orientation',
	'WhiteBalance',
	'MaxApertureValue',
	'SensingMethod',
	'ResolutionUnit',
	'XResolution',
	'YResolution',
	'ColorSpace',
	'Software',
	'Artist',
	'Copyright',
	'ExifVersion'
);

// Paramètres Exif.
$exif_params = array(
	'Artist' => array(
		'status' => 0
	),
	'ColorSpace' => array(
		'status' => 0
	),
	'Copyright' => array(
		'status' => 0
	),
	'DateTimeOriginal' => array(
		'status' => 1,
		'format' => '%d %B %Y, %H:%M:%S'
	),
	'ExifVersion' => array(
		'status' => 0
	),
	'ExposureBiasValue' => array(
		'status' => 0,
		'format' => '%+2.2F Ev'
	),
	'ExposureProgram' => array(
		'status' => 0
	),
	'ExposureTime' => array(
		'status' => 1
	),
	'Flash' => array(
		'status' => 1
	),
	'FNumber' => array(
		'status' => 1,
		'format' => 'f/%s'
	),
	'FocalLength' => array(
		'status' => 1,
		'format' => '%2.2F mm'
	),
	'FocalLengthIn35mmFilm' => array(
		'status' => 0,
		'format' => '%2.2F mm'
	),
	'ISOSpeedRatings' => array(
		'status' => 1
	),
	'LightSource' => array(
		'status' => 0
	),
	'Make' => array(
		'status' => 1
	),
	'MaxApertureValue' => array(
		'status' => 0,
		'format' => '%2.2F mm'
	),
	'MeteringMode' => array(
		'status' => 0
	),
	'Model' => array(
		'status' => 1
	),
	'Orientation' => array(
		'status' => 0
	),
	'ResolutionUnit' => array(
		'status' => 0
	),
	'SensingMethod' => array(
		'status' => 0
	),
	'Software' => array(
		'status' => 0
	),
	'UndefinedTag:0xA434'=> array(
		'status' => 1
	),
	'WhiteBalance' => array(
		'status' => 0
	),
	'XResolution' => array(
		'status' => 0,
		'format' => '%d'
	),
	'YResolution' => array(
		'status' => 0,
		'format' => '%d'
	)
);

// Permissions pour le groupe 1.
$group1_perms = $admin_group_perms_default;
$group1_perms['admin']['perms']['access_mode'] = 1;
$group1_perms['admin']['perms']['all'] = 1;
$group1_perms['gallery']['perms']['access_mode'] = 1;
$group1_perms['gallery']['perms']['add_comments'] = 1;
$group1_perms['gallery']['perms']['add_comments_mode'] = 1;
$group1_perms['gallery']['perms']['alert_email'] = 1;
$group1_perms['gallery']['perms']['adv_search'] = 1;
$group1_perms['gallery']['perms']['create_albums'] = 1;
$group1_perms['gallery']['perms']['edit'] = 1;
$group1_perms['gallery']['perms']['image_original'] = 1;
$group1_perms['gallery']['perms']['members_list'] = 1;
$group1_perms['gallery']['perms']['options'] = 1;
$group1_perms['gallery']['perms']['read_comments'] = 1;
$group1_perms['gallery']['perms']['votes'] = 1;
$group1_perms['gallery']['perms']['upload'] = 1;
$group1_perms['gallery']['perms']['upload_mode'] = 1;

// Permissions pour le groupe 2.
$group2_perms = $admin_group_perms_default;

// Permissions pour le groupe 3.
$group3_perms = $admin_group_perms_default;

// Ordre des informations  Iptc.
$iptc_order = array(
	'005',
	'007',
	'010',
	'015',
	'020',
	'025',
	'026',
	'027',
	'030',
	'035',
	'040',
	'055',
	'060',
	'065',
	'070',
	'075',
	'080',
	'085',
	'090',
	'092',
	'095',
	'100',
	'101',
	'103',
	'105',
	'110',
	'115',
	'116',
	'118',
	'120',
	'122',
	'130'
);

// Paramètres Iptc.
$iptc_params = array(
	'005' => array(
		'status' => 0
	),
	'007' => array(
		'status' => 0
	),
	'010' => array(
		'status' => 0
	),
	'015' => array(
		'status' => 0
	),
	'020' => array(
		'status' => 0
	),
	'025' => array(
		'status' => 1
	),
	'026' => array(
		'status' => 0
	),
	'027' => array(
		'status' => 0
	),
	'030' => array(
		'status' => 0
	),
	'035' => array(
		'status' => 0
	),
	'040' => array(
		'status' => 0
	),
	'055' => array(
		'status' => 1
	),
	'060' => array(
		'status' => 0
	),
	'065' => array(
		'status' => 0
	),
	'070' => array(
		'status' => 0
	),
	'075' => array(
		'status' => 0
	),
	'080' => array(
		'status' => 1
	),
	'085' => array(
		'status' => 0
	),
	'090' => array(
		'status' => 1
	),
	'092' => array(
		'status' => 0
	),
	'095' => array(
		'status' => 0
	),
	'100' => array(
		'status' => 0
	),
	'101' => array(
		'status' => 0
	),
	'103' => array(
		'status' => 0
	),
	'105' => array(
		'status' => 1
	),
	'110' => array(
		'status' => 1
	),
	'115' => array(
		'status' => 1
	),
	'116' => array(
		'status' => 1
	),
	'118' => array(
		'status' => 1
	),
	'120' => array(
		'status' => 1
	),
	'122' => array(
		'status' => 0
	),
	'130' => array(
		'status' => 0
	)
);

// Ordre des pages.
$pages_order = array(
	'sitemap',
	'members',
	'comments',
	'tags',
	'cameras',
	'history',
	'worldmap',
	'basket',
	'guestbook',
	'contact'
);

// Paramètres des pages.
$pages_params = array(
	'basket' => array(
		'status' => 1
	),
	'cameras' => array(
		'status' => 0
	),
	'comments' => array(
		'nb_per_page' => 20,
		'status' => 1
	),
	'contact' => array(
		'email' => '',
		'message' => '',
		'status' => 0
	),
	'guestbook' => array(
		'nb_per_page' => 20,
		'message' => '',
		'status' => 0
	),
	'history' => array(
		'status' => 0
	),
	'members' => array(
		'nb_per_page' => 20,
		'order_by' => 'user_crtdt DESC',
		'show_crtdt' => 1,
		'show_lastvstdt' => 0,
		'show_title' => 1,
		'status' => 1
	),
	'sitemap' => array(
		'status' => 0
	),
	'tags' => array(
		'status' => 1
	),
	'worldmap' => array(
		'center_lat' => 25,
		'center_long' => 5,
		'status' => 1,
		'zoom' => 2
	)
);

// Paramètres de profil utilisateur par défaut.
$users_profile_infos = array(
	'counter' => 0,
	'infos' => array(
		'birthdate' => array(
			'activate' => 0,
			'required' => 0
		),
		'desc' => array(
			'activate' => 0,
			'required' => 0
		),
		'email' => array(
			'activate' => 0,
			'required' => 0
		),
		'firstname' => array(
			'activate' => 0,
			'required' => 0
		),
		'loc' => array(
			'activate' => 0,
			'required' => 0
		),
		'name' => array(
			'activate' => 0,
			'required' => 0
		),
		'sex' => array(
			'activate' => 0,
			'required' => 0
		),
		'website' => array(
			'activate' => 0,
			'required' => 0
		)
	),
	'perso' => array()
);

$watermark_params = array(
	'background_active' => TRUE,				// Doit-on dessiner un fond ?
	'background_alpha' => 50,					// Transparence du fond (entre 0 et 127).
	'background_color' => '#ffffff',			// Couleur du fond (au format HTML).
	'background_large' => TRUE,					// Le fond doit-il occuper toute la largeur de l'image ?
	'background_padding' => 1,					// Marge interne du texte par rapport au fond.
	'border_active' => 0,						// Doit-on dessiner une bordure autour du texte ?
	'border_alpha' => 0,						// Transparence de la bordure (entre 0 et 127).
	'border_color' => '#304b62',				// Couleur de la bordure (au format HTML).
	'border_size' => 1,							// Épaisseur de la bordure.
	'image_active' => FALSE,					// Doit-on fusionner une image de filigrane avec les images de la galerie ?
	'image_file' => '',							// Nom de fichier de l'image de filigrane.
	'image_file_md5' => '',						// MD5 de l'image de filigrane.
	'image_opacity' => 100,						// Opacité de l'image de filigrane (entre 0 et 100).
	'image_size_pct' => 10,						// Pourcentage de l'image que doit occuper le filigrane en largeur.
	'image_size_type' => 'fixed',				// Taille fixe ('fixed') ou taille proportionnelle ('pct').
	'image_position' => 'bottom right',			// Position du filigrane dans l'image.
	'image_x' => 10,							// Position de l'image depuis le bord horizontal.
	'image_y' => 10,							// Position de l'image depuis le bord vertical.
	'quality' => 85,							// Qualité des images créée avec filigrane (texte ou image).
	'text' => '',								// Texte à placer sur les images de la galerie.
	'text_active' => FALSE,						// Doit-on dessiner un texte sur toutes les images de la galerie ?
	'text_alpha' => 0,							// Transparence du texte (entre 0 et 127).
	'text_color' => '#000000',					// Couleur du texte (au format HTML).
	'text_external' => FALSE,					// Doit-on placer le texte à l'extérieur de l'image ?
	'text_font' => 'Veranda.ttf',				// Nom de fichier de la fonte TTF à utiliser pour dessiner le texte.
	'text_position' => 'bottom right',			// Position du texte dans l'image.
	'text_shadow_active' => FALSE,				// Doit-on dessiner une ombre au texte ?
	'text_shadow_alpha' => 0,					// Transparence de l'ombre (entre 0 et 127).
	'text_shadow_color' => '#959595',			// Couleur de l'ombre (au format HTML).
	'text_shadow_size' => 2,					// Épaisseur de l'ombre.
	'text_size_fixed' => 10,					// Taille du texte.
	'text_size_pct' => 30,						// Pourcentage de l'image que doit occuper le texte en largeur.
	'text_size_type' => 'fixed',				// Taille fixe ('fixed') ou taille proportionnelle ('pct').
	'text_x' => 10,								// Position du texte depuis le bord horizontal.
	'text_y' => 10,								// Position du texte depuis le bord vertical.
	'watermark' => 'default'					// Filigrane à utiliser (uniquement pour les filigranes de
												// la catégorie et de l'utilisateur). Valeurs possibles :
												// 'none'     : aucun filigrane
												// 'default'  : filigrane par défaut
												// 'specific' : filigrane de la catégorie ou de l'utilisateur
);

// Ordre des widgets.
$widgets_order = array(
	'navigation',
	'image',
	'user',
	'options',
	'geoloc',
	'links',
	'tags',
	'stats_images',
	'stats_categories',
	'online_users'
);

// Paramètres des widgets.
$widgets_params = array(
	'geoloc' => array(
		'status' => 1,
		'title' => ''
	),
	'image' => array(
		'params' => array(
			'albums' => array(),
			'images' => array(),
			'mode' => 'fixed',
			'nb_thumbs' => 1
		),
		'status' => 0,
		'title' => ''
	),
	'links' => array(
		'items' => array(),
		'status' => 0,
		'title' => ''
	),
	'navigation' => array(
		'items' => array(
			'categories' => 1,
			'neighbours' => 0,
			'search' => 1
		),
		'status' => 1,
		'title' => ''
	),
	'online_users' => array(
		'params' => array(
			'duration' => 300,
			'order_by' => 'user_login ASC'
		),
		'status' => 0,
		'title' => ''
	),
	'options' => array(
		'items' => array(
			'image_size' => 1,
			'nb_thumbs' => 1,
			'order_by' => 1,
			'recent' => 0,
			'styles' => 1,
			'thumbs_albums' => 0,
			'thumbs_category_title' => 0,
			'thumbs_comments' => 1,
			'thumbs_date' => 0,
			'thumbs_filesize' => 1,
			'thumbs_hits' => 1,
			'thumbs_image_title' => 1,
			'thumbs_images' => 1,
			'thumbs_size' => 0,
			'thumbs_votes' => 1
		),
		'status' => 0,
		'title' => ''
	),
	'stats_categories' => array(
		'items' => array(
			'albums' => 1,
			'comments' => 1,
			'filesize' => 1,
			'hits' => 1,
			'images' => 1,
			'recents' => 1,
			'votes' => 1
		),
		'status' => 1,
		'title' => ''
	),
	'stats_images' => array(
		'items' => array(
			'added_by' => 1,
			'added_date' => 1,
			'comments' => 1,
			'created_date' => 1,
			'favorites' => 1,
			'filesize' => 1,
			'hits' => 1,
			'size' => 1,
			'votes' => 1
		),
		'status' => 1,
		'title' => ''
	),
	'tags' => array(
		'params' => array(
			'max_tags' => 15
		),
		'status' => 1,
		'title' => ''
	),
	'user' => array(
		'status' => 1,
		'title' => ''
	)
);

// Ordre des informations XMP.
$xmp_order = array(
	'dc:contributor',
	'dc:coverage',
	'dc:creator',
	'dc:date',
	'dc:description',
	'dc:format',
	'dc:identifier',
	'dc:language',
	'dc:publisher',
	'dc:relation',
	'dc:rights',
	'dc:source',
	'dc:subject',
	'dc:title',
	'dc:type'
);

// Paramètres XMP.
$xmp_params = array(
	'dc:contributor' => array(
		'status' => 0
	),
	'dc:coverage' => array(
		'status' => 0
	),
	'dc:creator' => array(
		'status' => 1
	),
	'dc:date' => array(
		'status' => 1
	),
	'dc:description' => array(
		'status' => 1
	),
	'dc:format' => array(
		'status' => 0
	),
	'dc:identifier' => array(
		'status' => 0
	),
	'dc:language' => array(
		'status' => 0
	),
	'dc:publisher' => array(
		'status' => 0
	),
	'dc:relation' => array(
		'status' => 0
	),
	'dc:rights' => array(
		'status' => 1
	),
	'dc:source' => array(
		'status' => 0
	),
	'dc:subject' => array(
		'status' => 0
	),
	'dc:title' => array(
		'status' => 1
	),
	'dc:type' => array(
		'status' => 0
	)
);

echo '<p>admin_group_perms_default</p>';
echo '<pre>';
print_r(htmlspecialchars(serialize($admin_group_perms_default)));
echo '</pre>';

echo '<br />';
echo '<p>exif_order</p>';
echo '<pre>';
print_r(htmlspecialchars(serialize($exif_order)));
echo '</pre>';

echo '<br />';
echo '<p>exif_params</p>';
echo '<pre>';
print_r(htmlspecialchars(serialize($exif_params)));
echo '</pre>';

echo '<br />';
echo '<p>group1_perms</p>';
echo '<pre>';
print_r(htmlspecialchars(serialize($group1_perms)));
echo '</pre>';

echo '<br />';
echo '<p>group2_perms</p>';
echo '<pre>';
print_r(htmlspecialchars(serialize($group2_perms)));
echo '</pre>';

echo '<br />';
echo '<p>group3_perms</p>';
echo '<pre>';
print_r(htmlspecialchars(serialize($group3_perms)));
echo '</pre>';

echo '<br />';
echo '<p>iptc_order</p>';
echo '<pre>';
print_r(htmlspecialchars(serialize($iptc_order)));
echo '</pre>';

echo '<br />';
echo '<p>iptc_params</p>';
echo '<pre>';
print_r(htmlspecialchars(serialize($iptc_params)));
echo '</pre>';

echo '<br />';
echo '<p>pages_order</p>';
echo '<pre>';
print_r(htmlspecialchars(serialize($pages_order)));
echo '</pre>';

echo '<br />';
echo '<p>pages_params</p>';
echo '<pre>';
print_r(htmlspecialchars(serialize($pages_params)));
echo '</pre>';

echo '<br />';
echo '<p>users_profile_infos</p>';
echo '<pre>';
print_r(htmlspecialchars(serialize($users_profile_infos)));
echo '</pre>';

echo '<br />';
echo '<p>watermark_params</p>';
echo '<pre>';
print_r(htmlspecialchars(serialize($watermark_params)));
echo '</pre>';

echo '<br />';
echo '<p>widgets_order</p>';
echo '<pre>';
print_r(htmlspecialchars(serialize($widgets_order)));
echo '</pre>';

echo '<br />';
echo '<p>widgets_params</p>';
echo '<pre>';
print_r(htmlspecialchars(serialize($widgets_params)));
echo '</pre>';

echo '<br />';
echo '<p>xmp_order</p>';
echo '<pre>';
print_r(htmlspecialchars(serialize($xmp_order)));
echo '</pre>';

echo '<br />';
echo '<p>xmp_params</p>';
echo '<pre>';
print_r(htmlspecialchars(serialize($xmp_params)));
echo '</pre>';

?>