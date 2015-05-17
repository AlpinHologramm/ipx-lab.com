<?php

class Application_Model_Device_Android extends Core_Model_Default {

    const SOURCE_FOLDER = "/var/apps/android/Siberian";
    const DEST_FOLDER = "/var/tmp/applications/android/%s/Siberian";

    protected $_current_version = '1.0';
    protected $_formatted_name = '';
    protected $_formatted_bundle_name = '';
    protected $_dst;
    protected $_sources_dst;
    protected $_base_dst;
    protected $_zipname;
    protected $_package_name;

    public function getCurrentVersion() {
        return $this->_current_version;
    }

    public function getStoreName() {
        return "Google Play";
    }

    public function getBrandName() {
        return "Google";
    }

    public function getResources() {

        $umask = umask(0);

        $src = $this->prepareResources();

        umask($umask);

        return $src;

    }

    public function prepareResources() {

        $this->_package_name = $this->getApplication()->getBundleId();

        $this->_generatePasswords();
        $this->_cpFolder();
        $this->_prepareFiles();
        $this->_copyImages();
        $zip = $this->_zipFolder();
        if($this->getDevice()->getDownloadType() != "apk") {
            return $zip;
        }

        $apk = $this->_apkFolder();
        if(!$apk) {
            $file = $zip;
        } else {
            $file = $apk;
        }


        return $file;
    }

    protected function _generatePasswords() {

        $save = false;
        $device = $this->getDevice();
        if(!$device->getStorePass()) {
            $device->setStorePass(Core_Model_Lib_String::generate(8));
            $save = true;
        }
        if(!$device->getKeyPass()) {
            $device->setKeyPass(Core_Model_Lib_String::generate(8));
            $save = true;
        }
        if(!$device->getAlias()) {
            $device->setAlias(Core_Model_Lib_String::format($this->getApplication()->getName(), true));
            $save = true;
        }

        if($save) {
            $device->save();
        }

        return $this;

    }

    protected function _cpFolder() {

        if(is_dir(Core_Model_Directory::getBasePathTo(self::SOURCE_FOLDER."/app/src/main/java/com/siberiancms"))) {
            Core_Model_Directory::delete(Core_Model_Directory::getBasePathTo(self::SOURCE_FOLDER . "/app/src/main/java/com/siberiancms"));
        }

        $this->_formatted_name = $this->getDevice()->getAlias();
        $this->_formatted_bundle_name = $this->_formatted_name;

        $src = Core_Model_Directory::getBasePathTo(self::SOURCE_FOLDER);
        $dst = Core_Model_Directory::getBasePathTo(self::DEST_FOLDER);
        $dst = sprintf($dst, $this->_formatted_name."-".$this->getApplication()->getId());

        // Supprime le dossier s'il existe puis le créé
        if(is_dir($dst)) Core_Model_Directory::delete($dst);
        mkdir($dst, 0777, true);

        // Copie les sources
        Core_Model_Directory::duplicate($src, $dst);

        $this->_zipname = $this->_formatted_name.'_android_source';;

        $this->_dst = $dst;

        $this->_sources_dst = "$dst/app/src/main";


        $src = $this->_sources_dst.'/java/com/appsmobilecompany/base';
        $dst = $this->_sources_dst.'/java/'.str_replace(".", "/", $this->_package_name);
//        $dst = $src; // $this->_sources_dst.'/java/com/'.$this->_formatted_bundle_name.'/'.$this->_formatted_name;
//        $this->_package_name = 'com.'.$this->_formatted_bundle_name.'.'.$this->_formatted_name;

        Core_Model_Directory::move($src, $dst);
        Core_Model_Directory::delete($this->_sources_dst.'/java/com/appsmobilecompany');

        return $this;

    }

    protected function _prepareFiles() {

//        $source = $this->_sources_dst.'/java/com/'.$this->_formatted_bundle_name.'/'.$this->_formatted_name;
        $source = $this->_sources_dst.'/java/'.str_replace(".", "/", $this->_package_name);
        $links = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($source, 4096), RecursiveIteratorIterator::SELF_FIRST);
        $allowed_extensions = array("java", "xml");

        if(!$links) return $this;

        foreach($links as $link) {

            if(!$link->isDir()) {

                $info = pathinfo($link->getPathName());
                $extension = $info["extension"];

                if(in_array($extension, $allowed_extensions)) {
                    if (strpos($link, 'CommonUtilities.java') !== false) {
                        $this->__replace(array(
                            'String SENDER_ID = ""' => 'String SENDER_ID = "' . Push_Model_Certificate::getAndroidSenderId() . '"',
                            'String APP_ID = ""' => 'String APP_ID = "' . $this->getApplication()->getId() . '"',
                            'SERVEUR_URL = "http://base.appsmobilecompany.com/";' => 'SERVEUR_URL = "' . $this->getUrl() . '";'
                        ), $link);
                    }
                }
            }
        }

        $replacements = array('com.appsmobilecompany.base' => $this->_package_name);
        $source = $this->_dst;
        $links = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($source, 4096), RecursiveIteratorIterator::SELF_FIRST);
        foreach($links as $link) {
            if($link->isDir()) continue;
            $this->__replace($replacements, $link->getRealPath());
        }
        $this->__replace($replacements, $this->_sources_dst.'/AndroidManifest.xml');
        $this->__replace($replacements, $this->_sources_dst.'/../../build.gradle');


        $name = str_replace("'", "\\'", $this->getApplication()->getName());
        // Retrieve the available languages
        $languages = Core_Model_Language::getLanguageCodes();
        // Check if all the available languages exist in the Android source
        foreach($languages as $lang) {
            if($lang == "en") continue;
            // If not, create them out of the English one.
            if (!file_exists($this->_sources_dst . '/res/values-' . $lang . '/strings.xml')) {
                mkdir($this->_sources_dst . '/res/values-' . $lang, 0777);
                copy($this->_sources_dst . '/res/values/strings.xml', $this->_sources_dst . '/res/values-' . $lang . '/strings.xml');
            }
        }

        $replacements = array(
            'http://localhost/overview' => $this->getApplication()->getUrl(null, array(), 'en', false),
            '<string name="app_name">Apps Mobile Company</string>' => '<string name="app_name"><![CDATA['.$name.']]></string>',
        );

        $this->__replace($replacements, $this->_sources_dst.'/res/values/strings.xml');

        foreach($languages as $lang) {

            if($lang == "en") continue;

            $replacements = array(
                'http://localhost/overview' => $this->getApplication()->getUrl(null, array(), $lang, false),
                '<string name="app_name">SiberianCMS</string>' => '<string name="app_name"><![CDATA['.$name.']]></string>',
                '<string name="app_name">Apps Mobile Company</string>' => '<string name="app_name"><![CDATA['.$name.']]></string>',
            );

            $this->__replace($replacements, $this->_sources_dst . "/res/values-{$lang}/strings.xml");

        }

        if (file_exists($this->_sources_dst . '/res/values-fr/strings.xml')) {
            $this->__replace($replacements, $this->_sources_dst . '/res/values-fr/strings.xml');
        }

        $version = explode(".", $this->getDevice()->getVersion());
        $version_code = !empty($version[0]) ? $version[0] : 1;
        $version_name = !empty($version[0]) && !empty($version[1]) ? $version[0].".".$version[1] : "1.0";

        if($version_code != 1 || $version_name != "1.0") {
            $replacements = array(
                "versionCode 1" => "versionCode {$version_code}",
                'versionName "1.0"' => 'versionName "'.$version_name.'"',
            );

            $this->__replace($replacements, $this->_sources_dst."/../../build.gradle");
            $this->__replace($replacements, $this->_sources_dst."/../../build.gradle.save");

        }

        return $this;

    }

    protected function _copyImages() {

        $application = $this->getApplication();
        $icons = array(
            $this->_sources_dst.'/res/drawable-mdpi/app_icon.png'    => $application->getIcon(48, null, true),
            $this->_sources_dst.'/res/drawable-mdpi/push_icon.png'   => $application->getIcon(24, null, true),
            $this->_sources_dst.'/res/drawable-hdpi/app_icon.png'    => $application->getIcon(72, null, true),
            $this->_sources_dst.'/res/drawable-hdpi/push_icon.png'   => $application->getIcon(36, null, true),
            $this->_sources_dst.'/res/drawable-xhdpi/app_icon.png'   => $application->getIcon(96, null, true),
            $this->_sources_dst.'/res/drawable-xhdpi/push_icon.png'  => $application->getIcon(48, null, true),
            $this->_sources_dst.'/res/drawable-xxhdpi/app_icon.png'  => $application->getIcon(144, null, true),
            $this->_sources_dst.'/res/drawable-xxhdpi/push_icon.png' => $application->getIcon(72, null, true),
            $this->_dst.'/app_icon.png' => $application->getIcon(512, null, true),
        );

        foreach($icons as $icon_dst => $icon_src) {
            if(!@copy($icon_src, $icon_dst)) {
                throw new Exception($this->_('An error occured while copying your app icon. Please check the icon, try to send it again and try again.'));
            }
        }

        return $this;
    }

    protected function _zipFolder() {

        $src = $this->_dst;

        Core_Model_Directory::zip($src, $src.'/'.$this->_zipname.'.zip');

        if(!file_exists($src.'/'.$this->_zipname.'.zip')) {
            throw new Exception('Une erreur est survenue lors de la création de l\'archive ('.$src.'/'.$this->_zipname.'.zip)');
        }

        return $src.'/'.$this->_zipname.'.zip';

    }

    protected function _apkFolder() {

        $store_pass = $this->getDevice()->getStorePass();
        $key_pass = $this->getDevice()->getKeyPass();
        $appId = $this->getApplication()->getId();
        return Core_Model_Directory::apk($this->_formatted_name, $this->_package_name, $appId, $store_pass, $key_pass);
    }

    private function __replace($replacement, $in, $print = false) {

        $contents = @file_get_contents($in);
        if(!$contents) {
            throw new Exception($this->_('An error occurred while editing file (%s).', $in));
        }

        if($print) {
            Zend_Debug::dump(filesize($in));
            Zend_Debug::dump($in);
            Zend_Debug::dump($contents);
            die;
        }
        foreach($replacement as $that => $with) {
            if($print) {
                Zend_Debug::dump($that);
                Zend_Debug::dump($with);
            }
            $contents = str_replace($that, $with, $contents);
//            if($print) Zend_Debug::dump($contents);
        }
        $file = fopen($in, 'w');
        fwrite($file, $contents);
        fclose($file);
        if($print) die;
        return $this;
    }

}
