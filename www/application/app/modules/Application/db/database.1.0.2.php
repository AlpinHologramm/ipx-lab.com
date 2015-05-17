<?php

$this->query("
    ALTER TABLE `application`
        ADD `main_category_id` tinyint(1) unsigned DEFAULT NULL AFTER `keywords`,
        ADD `secondary_category_id` tinyint(1) unsigned DEFAULT NULL AFTER `main_category_id`
    ;
");

$this->query("ALTER TABLE `log` ADD FOREIGN KEY `FK_APPLICATION_APPLICATION_ID` (app_id) references `application` (`app_id`) ON UPDATE CASCADE ON DELETE CASCADE;");