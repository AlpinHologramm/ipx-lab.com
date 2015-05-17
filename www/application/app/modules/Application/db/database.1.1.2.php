<?php

$this->query("ALTER TABLE `application` ADD `is_locked` TINYINT(1) UNSIGNED NOT NULL DEFAULT 0 AFTER `facebook_token`;");

$this->query("
    CREATE TABLE `application_device` (
        `device_id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
        `app_id` INT(11) UNSIGNED NOT NULL,
        `type_id` TINYINT(11) UNSIGNED NOT NULL,
        `status_id` TINYINT(11) UNSIGNED NULL DEFAULT 1,
        `version` VARCHAR(10) NOT NULL DEFAULT '0.0.1',
        `store_url` VARCHAR(255) NULL DEFAULT NULL,
        PRIMARY KEY (`device_id`),
        KEY `KEY_APP_ID` (`app_id`)
    ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
");

$this->query("
    ALTER TABLE `application_device`
      ADD CONSTRAINT `APPLICATION_DEVICE_APP_ID` FOREIGN KEY (`app_id`) REFERENCES `application` (`app_id`) ON DELETE CASCADE ON UPDATE CASCADE;
");