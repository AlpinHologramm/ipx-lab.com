<?php

class Front_MobileController extends Application_Controller_Mobile_Default {

    public function styleAction() {
        $html = $this->getLayout()->addPartial('style', 'core_view_mobile_default', 'page/css.phtml')->toHtml();
        $this->getLayout()->setHtml($html);
    }

    public function backgroundimageAction() {

        $urls = array("standard" => "", "hd" => "", "tablet" => "");
        $option = $this->getCurrentOptionValue();

        if($option->hasBackgroundImage() AND $option->getBackgroundImage() != "no-image") {
            $urls = array(
                "standard" => $option->getBackgroundImageUrl(),
                "hd" => $option->getBackgroundImageUrl(),
                "tablet" => $option->getBackgroundImageUrl(),
            );
        } else if($option->getIsHomepage() OR $this->getApplication()->getUseHomepageBackgroundImageInSubpages()) {
            $urls = array(
                "standard" => $this->getApplication()->getHomepageBackgroundImageUrl(),
                "hd" => $this->getApplication()->getHomepageBackgroundImageUrl("hd"),
                "tablet" => $this->getApplication()->getHomepageBackgroundImageUrl("tablet"),
            );
        }

        $this->_sendHtml($urls);

    }

    protected function _getBackgroundImage() {

        $url = "";
        $option = $this->getCurrentOptionValue();

        if($option->getIsHomepage()) {
            $url = $this->getApplication()->getBackgroundImageUrl("retina4");
        } else if($option->getHasBackgroundImage()) {
            $url = $option->getBackgroundImageUrl();
        } else if($option->getUseHomepageBackgroundImage()) {
            $url = $this->getApplication()->getHomepageBackgroundImageUrl("retina");
        }

        return $url;
    }

}