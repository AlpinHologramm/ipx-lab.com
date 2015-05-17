<?php

class Translation_Backoffice_ListController extends Backoffice_Controller_Default
{

    public function loadAction() {

        $html = array(
            "title" => $this->_("Translations"),
            "icon" => "fa-language",
        );

        $this->_sendHtml($html);

    }

    public function findallAction() {

        $languages = Core_Model_Language::getLanguages();
        $data = array();

        foreach($languages as $lang) {

            if($lang->getCode() == "en") continue;

            $data[] = array(
                "id" => base64_encode($lang->getCode()),
                "code" => $lang->getCode(),
                "name" => $lang->getName()
            );
        }

        $this->_sendHtml($data);
    }

//    public function deleteAction() {
//
//        if($data = Zend_Json::decode($this->getRequest()->getRawBody())) {
//
//            try {
//
//                if (empty($data["admin_id"])) {
//                    throw new Exception($this->_("An error occurred while saving. Please try again later."));
//                }
//
//                $admin = new Admin_Model_Admin();
//                $admin->find($data["user_id"]);
//
//                if (!$admin->getId()) {
//                    throw new Exception($this->_("An error occurred while saving. Please try again later."));
//                }
//
//                if ($admin->findAll()->count() <= 1) {
//                    throw new Exception($this->_("How do you want to access the backoffice if you remove the only user remaining?"));
//                }
//
//                $admin->delete();
//
//                $data = array(
//                    "success" => 1,
//                    "message" => $this->_("Admin successfully deleted")
//                );
//            } catch(Exception $e) {
//                $data = array(
//                    "error" => 1,
//                    "message" => $e->getMessage()
//                );
//            }
//
//            $this->_sendHtml($data);
//
//        }
//
//    }

}
