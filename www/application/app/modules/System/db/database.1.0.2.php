<?php

$data = array(
    "code" => "platform_name",
    "label" => "Platform Name"
);

$config = new System_Model_Config();
$config->find($data["code"], "code")
    ->setData($data)
    ->save();

