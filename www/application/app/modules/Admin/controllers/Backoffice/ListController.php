<?php

class Admin_Backoffice_ListController extends Backoffice_Controller_Default
{

    public function loadAction() {

        $html = array(
            "title" => $this->_("Users"),
            "icon" => "fa-users",
        );

        $this->_sendHtml($html);

    }

    public function findallAction() {

        $admin = new Admin_Model_Admin();
        $admins = $admin->findAll();
        $data = array("admins" => array());

        foreach($admins as $admin) {
            $data["admins"][] = array(
                "id" => $admin->getId(),
                "email" => $admin->getEmail(),
                "name" => $admin->getFirstname() . " " . $admin->getLastname(),
                "company" => $admin->getCompany(),
                "key" => sha1($admin->getFirstname() . $admin->getId()),
                "created_at" => $admin->getFormattedCreatedAt($this->_("MM/dd/yyyy"))
            );
        }

        $this->_sendHtml($data);
    }

    public function deleteAction() {

        if($data = Zend_Json::decode($this->getRequest()->getRawBody())) {

            try {

                if (empty($data["admin_id"])) {
                    throw new Exception($this->_("An error occurred while saving. Please try again later."));
                }

                $admin = new Admin_Model_Admin();
                $admin->find($data["user_id"]);

                if (!$admin->getId()) {
                    throw new Exception($this->_("An error occurred while saving. Please try again later."));
                }

                if ($admin->findAll()->count() <= 1) {
                    throw new Exception($this->_("How do you want to access the backoffice if you remove the only user remaining?"));
                }

                $admin->delete();

                $data = array(
                    "success" => 1,
                    "message" => $this->_("Admin successfully deleted")
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

    public function loginasAction() {

        if($admin_id = $this->getRequest()->getParam("admin_id")) {

            $admin = new Admin_Model_Admin();
            $admin->find($admin_id);

            if($admin->getId()) {

                $key = sha1($admin->getFirstname() . $admin->getId());

                if($key == $this->getRequest()->getParam('key', 'aa')) {
                    $front_session = $this->getSession('front');
                    $front_session->resetInstance()->setAdmin($admin);
                    $this->_redirect('');
                    return $this;
                }

            }

        }

    }

}
