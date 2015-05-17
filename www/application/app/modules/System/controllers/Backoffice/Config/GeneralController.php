<?php

class System_Backoffice_Config_GeneralController extends System_Controller_Backoffice_Default {

    protected $_codes = array("platform_name", "company_name", "company_phone", "company_address", "company_country", "company_vat_number", "system_timezone", "system_currency", "system_default_language");

    public function loadAction() {

        $html = array(
            "title" => $this->_("General"),
            "icon" => "fa-home",
        );

        $this->_sendHtml($html);

    }

    public function findallAction() {

        $data = parent::_findconfig();

        $timezones = DateTimeZone::listIdentifiers();
        if(empty($timezones)) {
            $locale = Zend_Registry::get("Zend_Locale");
            $timezones = $locale->getTranslationList('TimezoneToTerritory');
        }
        foreach($timezones as $timezone) {
            $data["territories"][$timezone] = $timezone;
        }

        foreach(Core_Model_Language::getCountriesList() as $country) {
            $data["currencies"][$country->getCode()] = $country->getName() . " ({$country->getSymbol()})";
        }

        $countries = $countries = Zend_Registry::get('Zend_Locale')->getTranslationList('Territory', null, 2);;
        asort($countries, SORT_LOCALE_STRING);
        $data["countries"] = $countries;

        $languages = array();
        foreach(Core_Model_Language::getLanguages() as $language) {
            $languages[$language->getCode()] = $language->getName();
        }
        if(!empty($languages) AND count($languages) > 1) {
            $data["languages"] = $languages;
        }

        $this->_sendHtml($data);

    }

}
