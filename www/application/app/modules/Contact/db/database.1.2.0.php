<?php

$this->query("ALTER TABLE `contact`
    ADD `latitude` DECIMAL(11,8) NULL DEFAULT NULL AFTER `country`,
    ADD `longitude` DECIMAL(11,8) NULL DEFAULT NULL AFTER `latitude`;
");

$option = new Application_Model_Option();
$option->find("contact", "code");
$option->setMobileUri("contact/mobile_view/")->save();
