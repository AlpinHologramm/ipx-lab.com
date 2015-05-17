<?php

$this->query("
    ALTER TABLE `application_layout_homepage`
        ADD `code` VARCHAR(10) NOT NULL AFTER `layout_id`;
");

$layout = new Application_Model_Layout_Homepage();
$layouts = $layout->findAll();

foreach($layouts as $key => $layout) {
    $layout->setCode("layout_".$key)
        ->setName("Layout ".$key)
        ->save();
}

$layout = new Application_Model_Layout_Homepage();
$layout->setData(array(
    'code' => 'layout_8',
    'name' => 'Layout 8',
    'preview' => '/customization/layout/homepage/layout_8.png',
    'use_more_button' => 0,
    'position' => 80
))->save();

