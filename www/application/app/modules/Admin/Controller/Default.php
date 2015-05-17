<?php

class Admin_Controller_Default extends Core_Controller_Default {

    protected $_admin;

    public function init() {

        parent::init();

        $this->_admin = $this->getSession()->getAdmin();

        if(!$this->getSession()->isLoggedIn()
            AND !preg_match('/(login)|(forgotpassword)|(change)|(map)|(signuppost)|(check)/', $this->getRequest()->getActionName())
            AND !$this->getRequest()->isInstalling()
            ) {
            $this->_forward('login', 'account', 'admin');
            return $this;
        }

    }

    public function getAdmin() {
        return $this->_admin;
    }

}
