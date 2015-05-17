<?php

class Padlock_Mobile_ViewController extends Application_Controller_Mobile_Default {

    public function findAction() {

        $html = array("page_title" => $this->getCurrentOptionValue()->getTabbarName());

        $this->_sendHtml($html);
    }

}