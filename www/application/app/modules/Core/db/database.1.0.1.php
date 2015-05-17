<?php

$this->_db->query("TRUNCATE TABLE `log`");
$this->query("ALTER TABLE `log` ADD `app_id` int(11) UNSIGNED NOT NULL AFTER `log_id`;");

