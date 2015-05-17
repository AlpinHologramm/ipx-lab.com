<?php

$this->query("

    CREATE TABLE `backoffice_notification` (
        `notification_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
        `original_notification_id` int(11) unsigned NOT NULL,
        `title` varchar(100) NOT NULL,
        `description` varchar(255) NOT NULL,
        `link` varchar(255) NULL DEFAULT NULL,
        `is_high_priority` tinyint(1) NOT NULL DEFAULT 0,
        `is_read` tinyint(1) NOT NULL DEFAULT 0,
        `created_at` datetime NOT NULL,
        `updated_at` datetime NOT NULL,
        PRIMARY KEY (`notification_id`)
    ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

");
