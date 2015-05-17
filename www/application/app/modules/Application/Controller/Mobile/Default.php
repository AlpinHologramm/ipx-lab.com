<?php

class Application_Controller_Mobile_Default extends Core_Controller_Default {

    protected static $_device;
    protected $_current_option_value;
    protected $_layout_id;

    public function init() {

        parent::init();

        // Test si un id de value est passé en paramètre
        $id = $this->getRequest()->getParam('option_value_id');
        if (!$id) $id = $this->getRequest()->getParam('value_id');
        if (!$id) {
            try {
                $data = Zend_Json::decode($this->getRequest()->getRawBody());
                if ($data && !empty($data['value_id'])) $id = $data['value_id'];
            } catch(Zend_Json_Exception $e) {
                $id = null;
            } catch(Exception $e) {
                $id = null;
            }
        }

        if($id) {
            // Créé et charge l'objet
            $this->_current_option_value = new Application_Model_Option_Value();

            if($id != "homepage") {
                $this->_current_option_value->find($id);
                // Récupère le layout de l'option_value en cours
                if($this->_current_option_value->getLayoutId()) {
                    $this->_layout_id = $this->_current_option_value->getLayoutId();
                }
            } else {
                $this->_current_option_value->setIsHomepage(true);
            }

        }

        //        $excluded = '/('.join(')|(',
        //            array(
        //                'front_mobile_home_view',
        //                'front_mobile_home_template',
        //                'application_device_check',
        //                'customer_mobile_account',
        //                'customer_mobile_account_autoconnect',
        //                'push_mobile_list',
        //                'push_mobile_count',
        //                'application_mobile_customization_colors',
        //                'application_mobile_previewer_infos',
        //                'front_mobile_gmaps_view',
        //                'mcommerce_mobile_cart_view',
        //                'findall',
        //                'find',
        //                'backgroundimage'
        //            )
        //        ).')/';
        //
        //        Zend_Debug::dump($excluded);die;
        //
        //        if(!$this->_current_option_value AND !preg_match($excluded, $this->getFullActionName('_'))) {
        //            $this->_redirect('/');
        //            return $this;
        //        }
        //        else
        if($this->getFullActionName('_') == 'front_mobile_home_view') {
            $this->_layout_id = $this->getApplication()->getLayoutId();
        }
        else {
            $this->_layout_id = 1;//$this->_current_option_value->getLayout()->getCode();
        }

        Core_View_Mobile_Default::setCurrentOption($this->_current_option_value);

        $this->_log();

        return $this;
    }

    public function getDevice() {
        return self::$_device;
    }

    public static function setDevice($device) {
        self::$_device = $device;
    }

    public function isOverview() {
        return $this->getSession()->isOverview;
    }

    /**
     * @depecrated
     */
    public function viewAction() {
        $option = $this->getCurrentOptionValue();
        $this->loadPartials($this->getFullActionName('_').'_l'.$this->_layout_id, false);
        $html = array('html' => mb_convert_encoding($this->getLayout()->render(), 'UTF-8', 'UTF-8'), 'title' => $option->getTabbarName());
        if($url = $option->getBackgroundImageUrl()) $html['background_image_url'] = $url;
        $html['use_homepage_background_image'] = (int) $option->getUseHomepageBackgroundImage() && !$option->getHasBackgroundImage();
        $this->getLayout()->setHtml(Zend_Json::encode($html));
    }

    public function indexAction() {
        $this->forward('index', 'index', 'Front', $this->getRequest()->getParams());
    }

    public function templateAction() {
        $partialId = $this->getFullActionName('_').'_l'.$this->_layout_id;
        $this->loadPartials($partialId, false);
    }

    public function backgroundimageAction() {

        $urls = array("standard" => "", "hd" => "", "tablet" => "");
        $option = $this->getCurrentOptionValue();
        if($option->getUseHomepageBackgroundImage()) {
            $urls = array(
                "standard" => $this->getApplication()->getHomepageBackgroundImageUrl(),
                "hd" => $this->getApplication()->getHomepageBackgroundImageUrl("hd"),
                "tablet" => $this->getApplication()->getHomepageBackgroundImageUrl("tablet"),
            );
        }
        if($option->getHasBackgroundImage()) {
            $url = $option->getBackgroundImageUrl();
            $urls = array(
                "standard" => $url,
                "hd" => $url,
                "tablet" => $url,
            );
        }

        $this->_sendHtml($urls);

    }

    public function getCurrentOptionValue() {
        return $this->_current_option_value;
    }

    protected function _prepareHtml() {

        $option = $this->getCurrentOptionValue();
        $this->loadPartials($this->getFullActionName('_').'_l'.$this->_layout_id, false);
        $html = array('html' => mb_convert_encoding($this->getLayout()->render(), 'UTF-8', 'UTF-8'), 'title' => $option->getTabbarName());
        if($url = $option->getBackgroundImageUrl()) $html['background_image_url'] = $url;
        $html['use_homepage_background_image'] = (int) $option->getUseHomepageBackgroundImage() && !$option->getHasBackgroundImage();
        return $html;

    }

    protected function _sendHtml($html) {

        $this->getResponse()->setHeader('Content-type', 'application/json');

        $encodedHtml = Zend_Json::encode($html);

        if(!$encodedHtml) {

            $errorMessage = "";

            switch (json_last_error()) {
                case JSON_ERROR_NONE: $errorMessage = ' - No errors'; break;
                case JSON_ERROR_DEPTH: $errorMessage = ' - Maximum stack depth exceeded'; break;
                case JSON_ERROR_STATE_MISMATCH: $errorMessage = ' - Underflow or the modes mismatch'; break;
                case JSON_ERROR_CTRL_CHAR: $errorMessage = ' - Unexpected control character found'; break;
                case JSON_ERROR_SYNTAX: $errorMessage = ' - Syntax error, malformed JSON'; break;
                case JSON_ERROR_UTF8: $errorMessage = ' - Malformed UTF-8 characters, possibly incorrectly encoded'; break;
                default: $errorMessage = ' - Unknown error'; break;
            }

            $logger = Zend_Registry::get("logger");
            $logger->sendException($errorMessage."\n\n".debugbacktrace(false), "json_exception_", false);

            $html = array(
                "error" => 1,
                "message" => $this->_("An error occurred while loading. Please try again later.")
            );
            $encodedHtml = Zend_Json::encode($html);

        }

        if(is_array($html) AND !empty($html['error'])) {
            $this->getResponse()->setHttpResponseCode(400);
        }

        $this->getLayout()->setHtml($encodedHtml);

    }

    protected function _log() {

        if($this->getRequest()->isGet() &&
            $this->getFullActionName("/") == "front/mobile/backgroundimage" &&
            $this->getDevice()->isNative()
        ) {

            $log = new Core_Model_Log();
            $detect = new Mobile_Detect();

            $host = !empty($_SERVER['REMOTE_HOST']) ? $_SERVER['REMOTE_HOST'] : '';
            $user_agent = !empty($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
            $other = array(
                'user_agent' => $user_agent,
                'host' => $host
            );

            $value_id = $this->getCurrentOptionValue()->getId() | 0;

            if($this->getSession()->getCustomerId()) $log->setCustomerId($this->getSession()->getCustomerId());
            $log->setCustomerId($this->getSession()->getCustomerId())
                ->setAppId($this->getApplication()->getId())
                ->setValueId($value_id)
                ->setDeviceName($detect->getDeviceName())
                ->setOther(serialize($other))
                ->save()
                ;

        }

        return $this;
    }

    protected function _durationSince($entry) {

        $date = new Zend_Date($entry);
        // $date = new Zend_Date($entry->getTimestamp());
        $now = Zend_Date::now();
        $difference = $now->sub($date);

        $seconds = $difference->toValue() % 60; 
        $allMinutes = ($difference->toValue() - $seconds) / 60;
        $minutes = $allMinutes % 60; 
        $allHours = ($allMinutes - $minutes) / 60;
        $hours =  $allHours % 24; 
        $allDays = ($allHours - $hours) / 24;
        $allDays.= ' ';
        $hours.= ' ';
        $minutes.= ' ';

        if($allDays > 0) {
            $allDays .= $this->_('day');
            if($allDays > 1) {
                $allDays .= "s";
            }
        } else {
            $allDays = '';
        }

        if($hours > 0) {
            $hours .= $this->_('hour');
            if($hours > 1) {
                $hours .= "s";
            }
        } else {
            $hours = '';
        }

        if($minutes > 0) {
            $minutes .= $this->_('minute');
            if($minutes > 1) {
                $minutes .= "s";
            }
        } else {
            $minutes = '';
        }

        $updated_at = '';
        if($allDays != '') {
            $updated_at = $allDays;
        } elseif($hours != '') {
            $updated_at = $hours;
        } elseif($minutes != '') {
            $updated_at = $minutes;
        } else {
            $updated_at = $this->_('seconds');
        }

        return $this->_('%s ago', $updated_at);
    }
}
