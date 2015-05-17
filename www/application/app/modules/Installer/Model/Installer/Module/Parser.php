<?php

class Installer_Model_Installer_Module_Parser extends Core_Model_Default
{

    protected $_tmp_file;
    protected $_tmp_directory;
    protected $_package_details;
    protected $_module_name;
    protected $_files;
    protected $__ftp;
    protected $_files_to_delete = array();
    protected $_errors;

    public function __construct($config = array()) {
        parent::__construct($config);
    }

    public function setFile($file) {
        $this->_tmp_file = $file;
        $infos = pathinfo($this->_tmp_file);
        $this->_module_name = $infos['filename'];
        $this->_tmp_directory = Core_Model_Directory::getTmpDirectory(true).'/'.$this->_module_name;
        $this->_files = array();
        return $this;
    }

    public function extract() {

        $zip = new ZipArchive();
        if($zip->open($this->_tmp_file)) {
            $tmp_dir = Core_Model_Directory::getTmpDirectory(true).'/';
            if(!is_writable($tmp_dir)) {
                throw new Exception($this->_("The folder %s is not writable. Please fix this issue and try again.", $tmp_dir));
            } else {

                if(is_dir($this->_tmp_directory)) {
                    Core_Model_Directory::delete($this->_tmp_directory);
                }
                mkdir($this->_tmp_directory, 0777);

                if($zip->extractTo($this->_tmp_directory)) {
                    $zip->close();
                    return $this->_tmp_directory;
                } else {
                    throw new Exception($this->_("Unable to open the file. Please, make sure that you sent a valid archive."));
                }
            }

        } else {
            throw new Exception($this->_("Unable to open the archive."));
        }

    }

    public function checkDependencies() {

        $package = $this->getPackageDetails();
        $dependencies = $package->getDependencies();

        if(!empty($dependencies) AND is_array($dependencies)) {

            foreach($dependencies as $type => $dependency) {

                switch($type) {

                    case "system":

                        if(strtolower($dependency["type"]) != strtolower(Siberian_Version::TYPE)) {
                            throw new Exception($this->_("This update is designed for the %s, you can't install it in your %s.", $package->getName(), Siberian_Version::NAME));
                        }

                        // If the current version of Siberian equals the package's version
                        if(version_compare(Siberian_Version::VERSION, $package->getVersion()) >= 0) {
                            throw new Exception($this->_("You already have installed this update."));
                        // If the current version is too old
                        } else {

                            $compare = version_compare(Siberian_Version::VERSION, $dependency["version"]);
                            if($compare == -1) {
                                throw new Exception($this->_("Please update your system to the %s version before installing this update.", $dependency["version"]));
                            } elseif($compare == 1) {
                                throw new Exception($this->_("You already have installed this update."));
                            }

                        }

                        break;

                    case "module":

                        /**
                         * @todo Test the module installs' / updates' dependencies
                         */
//                        $module = new Installer_Model_Installer_Module();
//                        $module->prepare($dependency["name"]);
//                        $version = $module->getVersion() ? $module->getVersion() : Installer_Model_Installer_Module::DEFAULT_VERSION;
//                        $compare = version_compare($version, $dependency["version"]);
//
//                        if($compare == 1) {
//                            throw new Exception($this->_("You already have installed this update."));
//                        } else if($compare == -1) {
//                            throw new Exception($this->_("Please update the module to the %s version before installing this update.", $dependency["version"]));
//                        }

                        break;
                }
            }

        }

    }

    public function getPackageDetails() {

        if(!$this->_package_details) {

            $this->_package_details = new Core_Model_Default();
            $package_file = $this->_tmp_directory."/package.json";
            if(!file_exists($package_file)) {
                throw new Exception($this->_("The package you have uploaded is invalid."));
            }

            try {
                $content = Zend_Json::decode(file_get_contents($package_file));
            } catch(Zend_Json_Exception $e) {
                Zend_Registry::get("logger")->sendException(print_r($e, true), "siberian_update_", false);
                throw new Exception($this->_("The package you have uploaded is invalid."));
            }

            $this->_package_details->setData($content);

        }

        return $this->_package_details;
    }

    public function copy() {

        $this->_parse();
        $this->_prepareFilesToDelete();

        if(!$this->_delete()) return false;

        if(!$this->_copy()) return false;

        Core_Model_Directory::delete($this->_tmp_directory);

        return true;

    }

    public function checkPermissions() {

        $this->_parse();
        $this->_prepareFilesToDelete();

//        $errors = Installer_Model_Installer::checkPermissions();

        foreach($this->_files as $file) {
            $info = pathinfo($file['destination']);
            $dirname = $info['dirname'];
            if(is_dir($dirname) AND !is_writable($dirname)) {
                $dirname = str_replace(Core_Model_Directory::getBasePathTo(), '', $dirname);
                $errors[] = $dirname;
            }
            if(is_file($file['destination']) AND !is_writable($file['destination'])) {
                $filename = str_replace(Core_Model_Directory::getBasePathTo(), '', $file["destination"]);
                $errors[] = $filename;
            }
        }

        foreach($this->_files_to_delete as $file) {
            if(is_file($file) AND !is_writable($file)) {
                $filename = str_replace(Core_Model_Directory::getBasePathTo(), '', $file);
                $errors[] = $filename;
            }
        }

        if(!empty($errors)) {
            $errors = array_unique($errors);
//            if(count($errors) > 1) {
            $message = "- ".implode('<br /> - ', $errors);
//                $message = $this->_("The following files and folders are not writable: <br /> - %s", $errors);
//            } else {
//                $error = current($errors);
//                $message = $this->_("The file %s is not writable.", $error);
//            }

            $this->_addError($message);

            return false;

        }

        return true;
    }

    public function getErrors() {
        return $this->_errors;
    }

    protected function _addError($error) {
        $this->_errors[] = $error;
        return $this;
    }

    protected function _extract() {

        $zip = new ZipArchive();
        if($zip->open($this->_tmp_file)) {
            $tmp_dir = Core_Model_Directory::getTmpDirectory(true).'/';
            if(!is_writable($tmp_dir)) {
                $this->_addError($this->_("The folder %s is not writable. Please fix this issue and try again.", $tmp_dir));
            } else {

                if(is_dir($this->_tmp_directory)) {
                    Core_Model_Directory::delete($this->_tmp_directory);
                }
                mkdir($this->_tmp_directory, 0777);

                if($zip->extractTo($tmp_dir.$this->_module_name)) {
                    return true;
                } else {
                    $this->_addError($this->_("Unable to extract the archive."));
                }

                $zip->close();
            }
        } else {
            $this->_addError($this->_("Unable to open the archive."));
        }

        return false;
    }

    protected function _parse($dirIterator = null) {

        if(is_null($dirIterator)) $dirIterator = new DirectoryIterator($this->_tmp_directory);

        foreach($dirIterator as $element) {
            if($element->isDot()) {
                continue;
            }

            if($element->isDir()) {
                $this->_parse(new DirectoryIterator($element->getRealPath()));
            } else if($element->isFile()) {
                if($element->getRealPath() == $this->_tmp_directory."/package.json") {
                    continue;
                }

                $this->_files[] = array(
                    'source' => $element->getRealPath(),
                    'destination' => str_replace($this->_tmp_directory."/", Core_Model_Directory::getBasePathTo(), $element->getRealPath())
                );

            }
        }

    }

    protected function _prepareFilesToDelete() {

        $files = $this->getPackageDetails()->getFilesToDelete();

        foreach($files as $file) {
            $this->_files_to_delete[] = $file;
        }

        return $this;

    }

    protected function _delete() {

        foreach($this->_files_to_delete as $file) {
            @unlink(Core_Model_Directory::getBasePathTo($file));
        }

        return true;

    }

    protected function _copy() {

        $errors = array();
        foreach($this->_files as $file) {
            $info = pathinfo($file['destination']);
            if(!is_dir($info['dirname'])) {
                if(!@mkdir($info['dirname'], 0775, true)) {
                    if($this->__getFtp()) {
                        if (!$this->__getFtp()->createDirectory($file)) {
                            $errors[] = $info['dirname'];
                        }
                    }
                }
            }
        }

        if(!empty($errors)) {
            $errors = array_unique($errors);
            if(count($errors) > 1) {
                $errors = implode('<br /> - ', $errors);
                $message = $this->_("The following folders are not writable: <br /> - %s", $errors);
            } else {
                $error = current($errors);
                $message = $this->_("The folder %s is not writable.", $error);
            }

            $this->_addError($message);

            return false;

        } else {

            foreach($this->_files as $file) {

                if(!@copy($file['source'], $file['destination'])) {

                    $src = $file['source'];
                    $dst = str_replace(Core_Model_Directory::getBasePathTo(""), "", $file['destination']);

                    if($this->__getFtp()) {
                        $this->__getFtp()->addFile($src, $dst);
                    }
                }
            }

            if($this->__getFtp()) {
                $this->__getFtp()->send();
            }

        }

        return true;

    }

    private function __getFtp() {

        if(!$this->__ftp) {
            $host = System_Model_Config::getValueFor("ftp_host");
            if($host) {
                $user = System_Model_Config::getValueFor("ftp_username");
                $password = System_Model_Config::getValueFor("ftp_password");
                $port = System_Model_Config::getValueFor("ftp_port");
                $path = System_Model_Config::getValueFor("ftp_path");
                $this->__ftp = new Siberian_Ftp($host, $user, $password, $port, $path);
            }
        }

        return $this->__ftp;

    }

}