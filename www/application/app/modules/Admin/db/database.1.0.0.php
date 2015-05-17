<?php

$this->query("
    CREATE TABLE `admin` (
        `admin_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
        `parent_id` INT(11) UNSIGNED NULL DEFAULT NULL,
        `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
        `password` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
        `company` VARCHAR(100) NULL DEFAULT NULL,
        `firstname` VARCHAR(100) NULL DEFAULT NULL,
        `lastname` VARCHAR(100) NULL DEFAULT NULL,
        `address` TEXT NULL DEFAULT NULL,
        `country_code` VARCHAR(10) NULL DEFAULT NULL,
        `country` VARCHAR(100) NULL DEFAULT NULL,
        `phone` VARCHAR(20) NULL DEFAULT NULL,
        `vat_number` VARCHAR(20) NULL DEFAULT NULL,
        `created_at` datetime NOT NULL,
        `updated_at` datetime NOT NULL,
        KEY `KEY_PARENT_ID` (`parent_id`),
        PRIMARY KEY (`admin_id`)
    ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

");
