<?php

$this->query("ALTER TABLE `application_device` ADD `alias` VARCHAR(50) NULL DEFAULT NULL AFTER `key_pass`");