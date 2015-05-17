<?php

$data = array(
    "code" => "support_name",
    "label" => "Name"
);

$config = new System_Model_Config();
$config->find($data["code"], "code")
    ->setData($data)
    ->save();

