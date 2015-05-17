<?php

class Core_Model_Directory
{
    protected static $_path;

    protected static $_base_path;

    protected static $_design_path;

    public static function getPathTo($path = '') {
        if(substr($path, 0, 1) !== '/') $path = '/'.$path;
        return self::$_path.$path;
    }

    public static function getBasePathTo($path = '') {
        if(substr($path, 0, 1) !== '/') $path = '/'.$path;
        if(stripos($path, self::$_base_path) === false) {
            $path = self::$_base_path.$path;
        }
        return $path;
    }

    public static function getDesignPath($base = false, $path = null, $application_type = null) {

        $design_path = self::$_design_path;
        $design_codes = Zend_Registry::get("design_codes");
        if($application_type AND $application_type != APPLICATION_TYPE AND !empty($design_codes[$application_type])) {
            $design_path = str_replace("/".APPLICATION_TYPE."/", "/".$application_type."/", $design_path);
            $design_path = str_replace("/".DESIGN_CODE, "/".$design_codes[$application_type], $design_path);
        }

        if($path AND substr($path, 0, 1) != "/") $path = "/$path";

        $design_path = $base ? self::getBasePathTo($design_path.$path) : self::getPathTo($design_path.$path);

        return $design_path;
    }

    public static function getSessionDirectory($base = false) {
        return $base ? self::getBasePathTo('/var/session') : self::getPathTo('/var/session');
    }

    public static function getTmpDirectory($base = false) {
        return $base ? self::getBasePathTo('/var/tmp') : self::getPathTo('/var/tmp');
    }

    public static function getCacheDirectory($base = false) {
        return $base ? self::getBasePathTo('/var/cache') : self::getPathTo('/var/cache');
    }

    public static function getImageCacheDirectory($base = false) {
        return $base ? self::getBasePathTo('/var/cache/images') : self::getPathTo('/var/cache/images');
    }

    public static function setPath($path = '') {
        self::$_path = $path;
    }

    public static function setBasePath($path = '') {
        self::$_base_path = $path;
    }

    public static function setDesignPath($path = '') {
        self::$_design_path = $path;
    }

    public static function delete($dir) {
        $files = array_diff(scandir($dir), array('.','..'));
        foreach ($files as $file) {
            $dir_todel = $dir.'/'.$file;
            // echo $dir_todel.'<br/>';
            (is_dir($dir_todel)) ? self::delete($dir_todel) : unlink($dir_todel);
        }
        return rmdir($dir);
    }

    public static function move($src, $dst) {

        $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($src), RecursiveIteratorIterator::SELF_FIRST);

        foreach($files as $file) {
            if($file->isDir()) {
                if(!is_dir($dst."/".$file->getFileName())) {
                    mkdir($dst."/".$file->getFileName(), 0775, true);
                }
            } else {
                $basepath = $dst.str_replace($src, '', $file->getPath());
                if(!is_dir($basepath)) {
                    mkdir($basepath, 0775, true);
                }
                copy($file->getRealpath(), $basepath.'/'.$file->getFilename());
            }

        }

        self::delete($src);

    }

    public static function duplicate($src, $dst, $permission = 0777) {

        $src = new DirectoryIterator($src);

        foreach($src as $file) {
            if($file->isDot()) continue;
            else if($file->isDir()) {
                mkdir($dst.'/'.$file->getFileName(), $permission);
                self::duplicate($file->getRealPath(), $dst.'/'.$file->getFileName());
            } else if($file->isFile()) {
                copy($file->getRealPath(), $dst.'/'.$file->getFileName());
            }
        }

    }

    public static function zip($source, $destination) {

        $zip = new ZipArchive();
        if (!$zip->open($destination, ZIPARCHIVE::CREATE)) {
            return false;
        }

        if (is_dir($source) === true) {
            // 4096 <-> RecursiveDirectoryIterator::SKIP_DOTS
            // RecursiveDirectoryIterator::SKIP_DOTS doesn't exist in PHP < 5.3
            $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($source, 4096), RecursiveIteratorIterator::SELF_FIRST);
            foreach ($files as $file) {

                $basepath = $file->getRealpath();
                $path = str_replace($source.'/', '', $basepath);
                if ($file->isDir()) {
                    $zip->addEmptyDir($path);
                }
                else if($file->isFile()) {
                    $zip->addFromString($path, file_get_contents($basepath));
                }
            }
        }

        $zip->close();

        return $zip;

    }

    public static function apk($name, $packageName, $appId, $store_pass, $key_pass) {

        $config = new System_Model_Config();
        $tryApk = $config->getValueFor('application_try_apk');
        $output = array();
        if(empty($tryApk) OR $tryApk != 'no') {

            //////////////////////////////////////////////////////////////////
            //////////////////////// build parameters ////////////////////////
            //////////////////////////////////////////////////////////////////
            $my_storePassword = $store_pass;
            $my_keyAlias = $name;
            $my_keyPassword = $key_pass;
            $src = "var/tmp/applications/android/{$name}-{$appId}/Siberian";

            ///////////////////////////////////////////
            //           generation key              //
            ///////////////////////////////////////////
            $my_keystore_path = Core_Model_Directory::getBasePathTo('var/apps/android/keystore/').$appId.'.pks';
            if(!file_exists($my_keystore_path)) {
                $path = $my_keystore_path;
                $organization = System_Model_Config::getValueFor("company_name");
                if (!$organization) $organization = "Default";
                exec('keytool -genkey -noprompt -alias ' . $my_keyAlias . ' -dname "CN=' . $organization . ', O=' . $organization . '" -keystore ' . $path . ' -storepass ' . $my_storePassword . ' -keypass ' . $my_keyPassword . ' -validity 36135 2>&1', $output);
            }
            ///////////////////////////////////////////
            //        adding the redirect url        //
            ///////////////////////////////////////////
            $gradlew_path = Core_Model_Directory::getBasePathTo("$src/app/gradlew");
            $gradlew_content = file_get_contents($gradlew_path);
            $url = Core_Model_Url::create("application/device/apkisgenerated", array("app_name" => "{$name}-{$appId}"));
            $gradlew_content .= 'wget "'.$url.'"';
            file_put_contents($gradlew_path, $gradlew_content);

            ///////////////////////////////////////////
            // local.properties for path sdk android //
            ///////////////////////////////////////////
            $data = 'sdk.dir=' . Core_Model_Directory::getBasePathTo("var/apps/android/sdk");
            file_put_contents("$src/local.properties", $data);

            ///////////////////////////////////////////
            //            build.gradle               //
            ///////////////////////////////////////////
            $data_build = file_get_contents("$src/app/build.gradle.save");
            $arraySearch = array('my_storePassword', 'my_keyAlias', 'my_keyPassword', 'my_packageName', 'my_keystore_path');
            $arrayReplace = array($my_storePassword, $my_keyAlias, $my_keyPassword, $packageName, $my_keystore_path);
            $data_build = str_replace($arraySearch, $arrayReplace, $data_build);
            file_put_contents("$src/app/build.gradle", $data_build);

            chdir(Core_Model_Directory::getBasePathTo("$src/app"));
            putenv('GRADLE_USER_HOME=' . Core_Model_Directory::getBasePathTo("var/tmp/applications/android/gradle"));
            exec('bash gradlew build 2>&1', $output);
        }


        if (in_array('BUILD SUCCESSFUL', $output)) {
            return Core_Model_Directory::getBasePathTo("$src/app/build/outputs/apk/app-release.apk");
        } else {
            Zend_Registry::get("logger")->sendException(print_r($output, true), "apk_generation_", false);
            return false;
        }
    }

}