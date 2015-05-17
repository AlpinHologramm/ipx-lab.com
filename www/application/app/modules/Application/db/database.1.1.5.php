<?php

$this->query("
    ALTER TABLE `application_device`
        ADD `store_pass` VARCHAR(10) NULL DEFAULT NULL AFTER `store_url`,
        ADD `key_pass` VARCHAR(10) NULL DEFAULT NULL AFTER `store_pass`
    ;
");
