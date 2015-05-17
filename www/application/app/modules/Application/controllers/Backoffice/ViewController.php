<?php

class Application_Backoffice_ViewController extends Backoffice_Controller_Default
{

    public function loadAction() {

        $html = array(
            "title" => $this->_("Application"),
            "icon" => "fa-mobile",
        );

        $this->_sendHtml($html);

    }

    public function findAction() {

        $application = Application_Model_Application::getInstance();

        $admin = $application->getOwner();
        $admin = array(
            "name" => $admin->getFirstname() . " " . $admin->getLastname(),
            "email" => $admin->getEmail(),
            "company" => $admin->getCompany(),
            "phone" => $admin->getPhone()
        );

        $store_categories = Application_Model_Device_Ios::getStoreCategeories();
        $devices = array();
        foreach($application->getDevices() as $device) {
            $device->setName($device->getName());
            $devices[] = $device->getData();
        }

        $data = array(
            'admin' => $admin,
            'app_store_icon' => $application->getAppStoreIcon(),
            'google_play_icon' => $application->getGooglePlayIcon(),
            'devices' => $devices,
            'url' => $application->getUrl(),
            'has_ios_certificate' => Push_Model_Certificate::getiOSCertificat($application->getId()) !== null
        );

        foreach($store_categories as $name => $store_category) {
            if($store_category->getId() == $application->getMainCategoryId()) $data['main_category_name'] = $name;
            else if($store_category->getId() == $application->getSecondaryCategoryId()) $data['secondary_category_name'] = $name;
        }

        $appName = $application->getDevice(2)->getAlias()."-".$application->getId();
        $apk_base_path = Core_Model_Directory::getBasePathTo("var/tmp/applications/android/$appName/Siberian/app/build/outputs/apk/app-release.apk");
        $apk_path = null;
        $date_mod = null;
        if(file_exists($apk_base_path)) {
            $apk_path = Core_Model_Directory::getPathTo("var/tmp/applications/android/$appName/Siberian/app/build/outputs/apk/app-release.apk");
            $date = new Zend_Date(filemtime($apk_base_path),Zend_Date::TIMESTAMP);
            $date_mod = $date->toString($this->_("MM/dd/y 'at' hh:mm a"));
        }

        $data["bundle_id"] = $application->getBundleId();
        $data["is_active"] = $application->isActive();
        $data["is_locked"] = $application->isLocked();
        $apk = array(
            "link" => $apk_path,
            'date' => $date_mod
            );
        $data["apk"] = $apk;
        $application->addData($data);
        $data = array(
            "application" => $application->getData(),
            'statuses' => Application_Model_Device::getStatuses()
        );


        $this->_sendHtml($data);

    }

    public function saveAction() {

        if($data = Zend_Json::decode($this->getRequest()->getRawBody())) {

            try {

                if(empty($data["app_id"])) {
                    throw new Exception($this->_("An error occurred while saving. Please try again later."));
                }

                $application = new Application_Model_Application();
                $application->find($data["app_id"]);

                if(!$application->getId()) {
                    throw new Exception($this->_("An error occurred while saving. Please try again later."));
                }

                if(!empty($data["key"])) {
                    $dummy = new Application_Model_Application();
                    $dummy->find($data["key"], "key");
                    if($dummy->getId() AND $dummy->getId() != $application->getId()) {
                        throw new Exception($this->_("The key is already used by another application."));
                    }
                } else {
                    throw new Exception($this->_("The key cannot be empty."));
                }

                if(!empty($data["domain"])) {

                    $data["domain"] = str_replace(array("http://", "https://"), "", $data["domain"]);

                    if(!Zend_Uri::check("http://".$data["domain"])) {
                        throw new Exception($this->_("Please enter a valid URL"));
                    }

                    $dummy = new Application_Model_Application();
                    $dummy->find($data["domain"], "domain");
                    if($dummy->getId() AND $dummy->getId() != $application->getId()) {
                        throw new Exception("The domain is already used by another application.");
                    }

                }

                $application->addData($data)->save();

                $data = array(
                    "success"   => 1,
                    "message"   => $this->_("Info successfully saved"),
                    "bundle_id" => $application->getBundleId(),
                    "url"       => $application->getUrl(),
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


    public function savedeviceAction() {

        if($data = Zend_Json::decode($this->getRequest()->getRawBody())) {

            try {

                if(empty($data["app_id"]) OR !is_array($data["devices"]) OR empty($data["devices"])) {
                    throw new Exception($this->_("An error occurred while saving. Please try again later."));
                }

                $application = new Application_Model_Application();
                $application->find($data["app_id"]);

                if(!$application->getId()) {
                    throw new Exception($this->_("An error occurred while saving. Please try again later."));
                }

                foreach($data["devices"] as $device_data) {
                    if(!empty($device_data["store_url"])) {
                        if(stripos($device_data["store_url"], "http") === false) {
                            $device_data["store_url"] = "http://".$device_data["store_url"];
                        }
                        if(!Zend_Uri::check($device_data["store_url"])) {
                            throw new Exception($this->_("Please enter a correct URL for the %s store", $device_data["name"]));
                        }
                    } else {
                        $device_data["store_url"] = null;
                    }

                    $device = $application->getDevice($device_data["type_id"]);
                    $device->addData($device_data)->save();
                }

                $data = array(
                    "success" => 1,
                    "message" => $this->_("Info successfully saved")
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

    public function downloadsourceAction() {

        if($data = $this->getRequest()->getParams()) {

            try {

                $application = new Application_Model_Application();

                if(empty($data['app_id']) OR empty($data['device_id'])) {
                    throw new Exception($this->_('This application does not exist'));
                }

                $application->find($data['app_id']);
                if(!$application->getId()) {
                    throw new Exception($this->_('This application does not exist'));
                }

                $device = $application->getDevice($data["device_id"]);
                $device->setApplication($application);
                $device->setDownloadType($this->getRequest()->getParam("type"));
                $zip = $device->getResources();

                if($this->getRequest()->getParam("type") != "apk") {
                    $path = explode('/', $zip);
                    end($path);

                    $this->_download($zip, current($path), 'application/octet-stream');
                } else {
                    die;
                }

            }
            catch(Exception $e) {
                Zend_Registry::get("logger")->sendException("Error When Generating Android: \n".print_r($e, true), "source_generator_", false);
                if($application->getId()) {
                    $this->_redirect('application/backoffice_view', array("app_id" => $application->getId()));
                } else {
                    $this->_redirect('application/backoffice_list');
                }
            }

        }

    }

    public function uploadcertificateAction() {

        if($app_id = $this->getRequest()->getParam("app_id")) {

            try {

                if (empty($_FILES) || empty($_FILES['file']['name'])) {
                    throw new Exception("No file has been sent");
                }

                $application = new Application_Model_Application();
                $application->find($app_id);

                if(!$application->getId()) {
                    throw new Exception($this->_("An error occurred while saving. Please try again later."));
                }

                $base_path = Core_Model_Directory::getBasePathTo("var/apps/iphone/");
                $path = Core_Model_Directory::getPathTo("var/apps/iphone/");
                $adapter = new Zend_File_Transfer_Adapter_Http();
                $adapter->setDestination(Core_Model_Directory::getTmpDirectory(true));

                if ($adapter->receive()) {

                    $file = $adapter->getFileInfo();

                    $certificat = new Push_Model_Certificate();
                    $certificat->find(array('type' => 'ios', 'app_id' => $app_id));

                    if(!$certificat->getId()) {
                        $certificat->setType("ios")
                            ->setAppId($app_id)
                        ;
                    }

                    $new_name = uniqid("cert_").".pem";
                    if(!rename($file["file"]["tmp_name"], $base_path.$new_name)) {
                        throw new Exception($this->_("An error occurred while saving. Please try again later."));
                    }

                    $certificat->setPath($path.$new_name)
                        ->save()
                    ;

                    $data = array(
                        "success" => 1,
                        "message" => $this->_("The file has been successfully uploaded")
                    );

                } else {
                    $messages = $adapter->getMessages();
                    if (!empty($messages)) {
                        $message = implode("\n", $messages);
                    } else {
                        $message = $this->_("An error occurred during the process. Please try again later.");
                    }

                    throw new Exception($message);
                }
            } catch (Exception $e) {
                $data = array(
                    "error" => 1,
                    "message" => $e->getMessage()
                );
            }

            $this->_sendHtml($data);

        }

    }

}
