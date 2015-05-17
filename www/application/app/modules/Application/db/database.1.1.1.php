<?php

$this->query("ALTER TABLE `application` ADD `admin_id` INT(11) UNSIGNED NOT NULL AFTER `app_id`;");