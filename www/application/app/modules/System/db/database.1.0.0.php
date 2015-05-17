<?php

$this->query("
    CREATE TABLE `system_config` (
        `config_id` INT(11) unsigned NOT NULL AUTO_INCREMENT,
        `code` VARCHAR(100) NOT NULL,
        `label` VARCHAR(100) NOT NULL,
        `value` TEXT NULL DEFAULT NULL,
        PRIMARY KEY (`config_id`),
        UNIQUE  `UNIQUE_CODE` ( `code`)
    ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
");


$data = array(
    array(
        "code" => "company_name",
        "label" => "Name"
    ),
    array(
        "code" => "company_phone",
        "label" => "Phone"
    ),
    array(
        "code" => "company_address",
        "label" => "Address"
    ),
    array(
        "code" => "company_country",
        "label" => "Country"
    ),
    array(
        "code" => "company_vat_number",
        "label" => "VAT Number"
    ),
    array(
        "code" => "system_territory",
        "label" => "Timezone"
    ),
    array(
        "code" => "system_timezone",
        "label" => "Timezone"
    ),
    array(
        "code" => "system_currency",
        "label" => "Currency"
    ),

    array(
        "code" => "support_email",
        "label" => "Support Email Address"
    ),
    array(
        "code" => "support_link",
        "label" => "Support Link"
    ),

    array(
        "code" => "logo",
        "label" => "Logo"
    ),
    array(
        "code" => "favicon",
        "label" => "Favicon"
    ),
);

foreach($data as $configData) {
    $config = new System_Model_Config();
    $config->find($configData["code"], "code")
        ->setData($configData)
        ->save()
    ;
}