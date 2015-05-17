<?php

$this->query("ALTER TABLE `application` ADD `is_active` TINYINT(1) UNSIGNED NOT NULL DEFAULT 1 AFTER `facebook_token`;");
