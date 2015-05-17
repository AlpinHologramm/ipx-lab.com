<?php

$this->query("
    CREATE TABLE `cms_application_page_block_button` (
        `button_id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
        `value_id` INT(11) UNSIGNED NOT NULL,
        `type_id` ENUM('link','phone') NOT NULL DEFAULT 'phone',
        `content` VARCHAR(256) NULL DEFAULT NULL,
        PRIMARY KEY (`button_id`),
        KEY `KEY_VALUE_ID` (`value_id`)
    ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
");

$data = array(
    'type' => 'button',
    'position' => 5,
    'icon' => 'icon-barcode',
    'title' => 'Button',
    'template' => 'cms/application/page/edit/block/button.phtml',
    'mobile_template' => 'cms/page/%s/view/block/button.phtml'
);

$block = new Cms_Model_Application_Block();
$block->setData($data)->save();
