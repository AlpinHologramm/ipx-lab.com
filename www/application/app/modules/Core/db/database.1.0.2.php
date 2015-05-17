<?php

$this->query("ALTER TABLE `log` ADD `value_id` INT(11) UNSIGNED NOT NULL AFTER `app_id`;");

$this->query("ALTER TABLE `log` ADD INDEX `IDX_VALUE_ID` (`value_id`);");

$this->query("ALTER TABLE `log` DROP `uri`;");
