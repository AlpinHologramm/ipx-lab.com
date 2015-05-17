<?php

$this->query("
    ALTER TABLE `admin`
        ADD `is_allowed_to_add_pages` TINYINT(1) UNSIGNED NOT NULL DEFAULT 1 AFTER `password`
    ;
");

