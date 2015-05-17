<?php

class Translation_Backoffice_EditController extends Backoffice_Controller_Default
{

    public function loadAction() {

        $html = array(
            "title" => $this->_("Translations"),
            "icon" => "fa-language",
        );

        $this->_sendHtml($html);

    }

    public function findAction() {

        $data = array();
        if($lang_id = $this->getRequest()->getParam("lang_id")) {

            $lang_id = base64_decode($lang_id);
            $lang_id = explode("_", strtolower($lang_id));
            if(count($lang_id) == 2) {
                $lang_id[1] = strtoupper($lang_id[1]);
            }
            $lang_id = implode("_", $lang_id);

            $data["section_title"] = $this->_("Edit the language: %s", Core_Model_Language::getLanguage($lang_id)->getName());
            $data["is_edit"] = true;
            $user_translation_dir = Core_Model_Directory::getBasePathTo("languages".DS.$lang_id.DS);

        } else {
            $data["section_title"] = $this->_("Create a new language");
            $data["is_edit"] = false;
            $user_translation_dir = "";
        }
        $data["country_code"] = $lang_id;

        $locale = Zend_Registry::get("Zend_Locale");
        $languages = $locale->getTranslationList('language');
        $existing_languages = Core_Model_Language::getLanguageCodes();
        foreach($languages as $k => $language) {
            if(!$locale->isLocale($k) OR in_array($k, $existing_languages)) {
                unset($languages[$k]);
            }
        }

        asort($languages, SORT_LOCALE_STRING);
        $data["country_codes"] = $languages;

        $default_base_path = Core_Model_Directory::getBasePathTo("languages/default");

        $files = new DirectoryIterator($default_base_path);
        $translation_files = array();
        foreach($files as $file) {

            if($file->isDot()) continue;
            $pathinfo = pathinfo($file);
            if(empty($pathinfo["extension"]) OR $pathinfo["extension"] != "csv") continue;

            $translation_files[$file->getFilename()] = $file->getFilename();

            $resource = fopen($file->getRealPath(), "r");
            $translation_files_data[$file->getFilename()] = array();
            while($content = fgetcsv($resource, 1024, ";", '"')) {
                $translation_files_data[$file->getFilename()][$content[0]] = null;
            }
            fclose($resource);

            if(!is_file($user_translation_dir.$file->getFilename())) continue;

            $resource = fopen($user_translation_dir.$file->getFilename(), "r");
            while($content = fgetcsv($resource, 1024, ";", '"')) {
                $translation_files_data[$file->getFilename()][$content[0]] = $content[1];
            }
            fclose($resource);
        }

        ksort($translation_files);
        $data["translation_files"] = $translation_files;
        ksort($translation_files_data);
        $data["translation_files_data"] = $translation_files_data;

        $this->_sendHtml($data);

    }

    public function saveAction() {

        if($data = Zend_Json::decode($this->getRequest()->getRawBody())) {

            try {

                $base_path = Core_Model_Directory::getBasePathTo("languages/");
                $country_code = $data["country_code"];
                $translation_dir = $base_path.$country_code;
                $translation_file = $data["file"];
                $translation_datas = $data["collection"];
                ksort($translation_datas);

                if(empty($country_code)) throw new Exception($this->_("Please, choose a language."));
                if(empty($translation_file) ) throw new Exception($this->_("Please, choose a file."));

                if(!is_dir($translation_dir))
                    mkdir($translation_dir);

                $ressource = fopen($translation_dir . DS . $translation_file, "w");
                foreach($translation_datas as $key => $value) {
                    if(empty($value)) continue;
                    fputcsv($ressource, array($key, $value), ";", '"');
                }
                fclose($ressource);

                $data = array(
                    "success" => 1,
                    "message" => $this->_("Language successfully saved")
                );

            } catch(Exception $e) {
                $data = array(
                    "error" => 1,
                    "message" => $e->getMessage()
                );
            }

            $this->_sendHtml($data);
        }

    }

}
