<?php

$layout = new Application_Model_Layout_Homepage();
$layouts = $layout->findAll();

foreach($layouts as $key => $layout) {
    ++$key;
    $layout->setCode("layout_".$key)
        ->setName("Layout ".$key)
        ->save();
}

$layout = new Application_Model_Layout_Homepage();
$layout->setData(array(
    'code' => 'layout_9',
    'name' => 'Layout 9',
    'preview' => '/customization/layout/homepage/layout_9.png',
    'use_more_button' => 0,
    'position' => 80
))->save();

$this->query("
    ALTER TABLE `application_layout_homepage`
        ADD `visibility` VARCHAR(10) NOT NULL DEFAULT 'homepage' AFTER `layout_id`,
        ADD position VARCHAR(10) NOT NULL DEFAULT 'bottom' AFTER `number_of_displayed_icons`
    ;
");

$this->query("
    ALTER TABLE `application`
        ADD `layout_visibility` VARCHAR(10) NOT NULL DEFAULT 'homepage' AFTER `layout_id`;
");

$layout = new Application_Model_Layout_Homepage();
$layouts = $layout->findAll();

foreach($layouts as $key => $layout) {
    switch($layout->getId()) {
        case 1: $layout->setVisibility(Application_Model_Layout_Homepage::VISIBILITY_ALWAYS)->setPosition("bottom"); break;
        case 7: $layout->setPosition("left"); break;
        case 9: $layout->setVisibility(Application_Model_Layout_Homepage::VISIBILITY_TOGGLE)->setPosition("left"); break;
        default: $layout->setVisibility(Application_Model_Layout_Homepage::VISIBILITY_HOMEPAGE)->setPosition("bottom"); break;
    }

    $layout->save();
}

