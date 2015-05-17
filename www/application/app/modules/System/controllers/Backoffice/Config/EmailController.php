<?php

class System_Backoffice_Config_EmailController extends System_Controller_Backoffice_Default {

    protected $_codes = array("support_name", "support_email", "support_link");

    public function loadAction() {

        $html = array(
            "title" => $this->_("Communications"),
            "icon" => "fa-exchange",
        );

        $this->_sendHtml($html);

    }

    public function findallAction() {

        $data = parent::_findconfig();
        $this->_sendHtml($data);

    }

}
