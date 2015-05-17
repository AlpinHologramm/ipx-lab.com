<?php

$data = array(
    "code" => "system_default_language",
    "label" => "Default Languages"
);

$config = new System_Model_Config();
$config->find($data["code"], "code")
    ->setData($data)
    ->save();

