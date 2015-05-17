<?php

$this->query("ALTER TABLE `push_apns_devices` ADD `app_id` INT(11) UNSIGNED NOT NULL AFTER `device_id`;");
$this->query("ALTER TABLE `push_gcm_devices` ADD `app_id` INT(11) UNSIGNED NOT NULL AFTER `device_id`;");
$this->query("ALTER TABLE `push_messages` ADD `app_id` INT(11) UNSIGNED NOT NULL AFTER `message_id`;");

$this->query("
    ALTER TABLE `push_apns_devices`
        ADD FOREIGN KEY `FK_APPLICATION_APP_ID` (`app_id`) REFERENCES `application` (`app_id`) ON DELETE CASCADE ON UPDATE CASCADE;
");
$this->query("
    ALTER TABLE `push_gcm_devices`
        ADD FOREIGN KEY `FK_APPLICATION_APP_ID` (`app_id`) REFERENCES `application` (`app_id`) ON DELETE CASCADE ON UPDATE CASCADE;
");
$this->query("
    ALTER TABLE `push_messages`
        ADD FOREIGN KEY `FK_APPLICATION_APP_ID` (`app_id`) REFERENCES `application` (`app_id`) ON DELETE CASCADE ON UPDATE CASCADE;
");
