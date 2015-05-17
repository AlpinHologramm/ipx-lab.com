<?php

// Créé les tables
$this->query('
    CREATE TABLE IF NOT EXISTS `padlock` (
        `padlock_id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
        `value_id` INT(11) UNSIGNED NOT NULL,
        `created_at` DATETIME NOT NULL,
        `updated_at` DATETIME NOT NULL,
        PRIMARY KEY (`padlock_id`),
        FOREIGN KEY `FK_PADLOCK_VALUE_ID` (`value_id`) REFERENCES `application_option_value` (`value_id`) ON DELETE CASCADE ON UPDATE CASCADE
    ) ENGINE=InnoDB CHARACTER SET=utf8 COLLATE=utf8_unicode_ci;
');
$this->query('
    CREATE TABLE IF NOT EXISTS `padlock_value` (
        `padlock_value_id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
        `app_id` INT(11) UNSIGNED NOT NULL,
        `value_id` INT(11) UNSIGNED NOT NULL,
        PRIMARY KEY (`padlock_value_id`),
        FOREIGN KEY `FK_PADLOCK_VALUE_APP_ID` (`app_id`) REFERENCES `application` (`app_id`) ON DELETE CASCADE ON UPDATE CASCADE
    ) ENGINE=InnoDB CHARACTER SET=utf8 COLLATE=utf8_unicode_ci;
');

$this->query("
    ALTER TABLE `customer`
        ADD `can_access_locked_features` TINYINT(1) UNSIGNED NOT NULL DEFAULT 0 AFTER `show_in_social_gaming`;

    ALTER TABLE `application`
        ADD `require_to_be_logged_in` TINYINT(1) UNSIGNED NOT NULL DEFAULT 0 AFTER `is_active`;
");


// Create the gallery
$library = new Media_Model_Library();
$library->setName('Padlock')->save();

// Create the icon
$data = array('library_id' => $library->getId(), 'link' => "/padlock/padlock.png", 'can_be_colorized' => 1);
$image = new Media_Model_Library_Image();
$image->setData($data)->save();
$icon_id = $image->getId();

// Create and declare the feature
$data = array(
    'library_id' => $library->getId(),
    'icon_id' => $icon_id,
    'code' => 'padlock',
    'name' => 'Padlock',
    'model' => 'Padlock_Model_Padlock',
    'desktop_uri' => 'padlock/application/',
    'mobile_uri' => 'padlock/mobile_view/',
    'only_once' => 0,
    'is_ajax' => 1,
    'position' => 220
);

$option = new Application_Model_Option();
$option->setData($data)->save();

