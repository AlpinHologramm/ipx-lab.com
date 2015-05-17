<?php

$this->query("ALTER TABLE `api_provider` ADD `icon` varchar(50) NULL DEFAULT NULL AFTER `name`;");

$provider_codes = array(
    "instagram" => "fa-instagram",
    "facebook" => "fa-facebook-square",
    "youtube" => "fa-youtube"
);

foreach($provider_codes as $provider_code => $icon_name) {

    $provider = new Api_Model_Provider();
    $provider->find($provider_code, "code");

    if($provider->getId()) {
        $provider->setIcon($icon_name)->save();
    }

}