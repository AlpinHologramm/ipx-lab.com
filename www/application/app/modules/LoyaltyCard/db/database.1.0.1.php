<?php

$this->query("ALTER TABLE `loyalty_card_password` ADD `app_id` int(11) UNSIGNED NOT NULL AFTER `password_id`");

$this->query("
    ALTER TABLE `loyalty_card_password`
        ADD FOREIGN KEY `FK_APP_ID` (`app_id`) REFERENCES `application` (`app_id`) ON DELETE CASCADE ON UPDATE CASCADE;
");
