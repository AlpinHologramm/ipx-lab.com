<?php

$this->query("ALTER TABLE `application` ADD `allow_all_customers_to_access_locked_features` tinyint(1) UNSIGNED NOT NULL DEFAULT '0' AFTER `require_to_be_logged_in`;");
