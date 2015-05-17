<?php

$this->query("
    CREATE TABLE `backoffice_user` (
        `user_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
        `email` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
        `password` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
        `created_at` datetime NOT NULL,
        `updated_at` datetime NOT NULL,
        PRIMARY KEY (`user_id`)
    ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
");
