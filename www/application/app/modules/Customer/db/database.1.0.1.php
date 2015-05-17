<?php

$this->query("ALTER TABLE `customer` ADD `app_id` INT(11) NOT NULL AFTER `customer_id`;");
