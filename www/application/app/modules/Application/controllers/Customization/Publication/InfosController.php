<?php

class Application_Customization_Publication_InfosController extends Application_Controller_Default {

    public function indexAction() {
        $this->loadPartials();

        if($this->getRequest()->isXmlHttpRequest()) {
            $html = array('html' => $this->getLayout()->getPartial('content_editor')->toHtml());
            $this->getLayout()->setHtml(Zend_Json::encode($html));
        }
    }

    public function saveAction() {

        if($data = $this->getRequest()->getPost()) {

            try {

                if(!empty($data["name"])) {
                    if(is_numeric(substr($data["name"], 0, 1))) {
                        throw new Exception("Ce champ ne peut pas commencer par un chiffre");
                    }
                    $this->getApplication()->setName($data['name'])->save();
                } else if(!empty($data['description'])) {
                    if(strlen($data['description']) < 200) throw new Exception($this->_('The description must be at least 200 characters'));
                    $this->getApplication()->setDescription($data['description'])->save();
                } else if(!empty($data['keywords'])) {
                    $this->getApplication()->setKeywords($data['keywords'])->save();
                } else if(!empty($data['bundle_id'])) {
                    if(count(explode('.', $data['bundle_id'])) < 2) {
                        throw new Exception($this->_('The entered bundle id is incorrect, it should be like: com.siberiancms.app'));
                    }
                    $this->getApplication()->setBundleId($data['bundle_id'])->save();
                } else if(isset($data['main_category_id'])) {
                    if(empty($data['main_category_id'])) throw new Exception($this->_('The field is required'));
                    else $this->getApplication()->setMainCategoryId($data['main_category_id'])->save();
                } else if(isset($data['secondary_category_id'])) {
                    $this->getApplication()->setSecondaryCategoryId($data['secondary_category_id'])->save();
                }

                $html = array('success' => '1');

            }
            catch(Exception $e) {
                $html = array(
                    'message' => $e->getMessage()
                );
            }

            $this->_sendHtml($html);

        }

    }

    public function downloadsourceAction() {

        if($data = $this->getRequest()->getParams()) {

            try {

                $application = $this->getApplication();

                $device = $application->getDevice($data["device_id"]);
                $device->setApplication($application);
                $zip = $device->getResources();

                $path = explode('/', $zip);
                end($path);

                $this->_download($zip, current($path), 'application/octet-stream');

            }
            catch(Exception $e) {
                Zend_Debug::dump($e);
                die;
                $this->getSession()->addError($e->getMessage());
                $this->_redirect('application/backoffice_view', array("app_id" => 1));
            }

        }

    }

}
