<?php

$this->query("
    ALTER TABLE `application`
        DROP `background_image`,
        DROP `background_image_color`,
        DROP `homepage_background_image_id`
    ;
");

$this->query("
    ALTER TABLE `application`
        ADD `background_image_tablet` varchar(255) DEFAULT NULL AFTER `homepage_background_image_retina_link`;
");

$this->query("
    ALTER TABLE `application`
        CHANGE `homepage_background_image_link` `background_image` varchar(255) DEFAULT NULL,
        CHANGE `homepage_background_image_retina_link` `background_image_hd` varchar(255) DEFAULT NULL
    ;
");

$this->query("
    ALTER TABLE `application`
        ADD `startup_image_iphone_6` varchar(255) DEFAULT NULL AFTER `startup_image_retina`,
        ADD `startup_image_iphone_6_plus` varchar(255) DEFAULT NULL AFTER `startup_image_iphone_6`,
        ADD `startup_image_ipad_retina` varchar(255) DEFAULT NULL AFTER `startup_image_iphone_6_plus`
    ;
");
