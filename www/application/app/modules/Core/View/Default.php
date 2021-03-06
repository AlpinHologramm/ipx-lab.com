<?php

class Core_View_Default extends Siberian_View
{

    protected static $_application;
    protected static $_session = array();
    protected static $_device;

    public function __construct($config = array()) {
//        $this->_session = new Core_Model_Session('front');
        parent::__construct($config);
    }

    public function isProduction() {
        return APPLICATION_ENV == 'production';
    }

    public function getSession($type = null) {

        if(is_null($type)) $type = SESSION_TYPE;

        if(isset(self::$_session[$type])) {
            return self::$_session[$type];
        } else {
            $session = new Core_Model_Session($type);
            self::setSession($session, $type);
            return $session;
        }
    }

    public static function setSession($session, $type = 'front') {
        self::$_session[$type] = $session;
    }

    public function getApplication() {
        return Application_Model_Application::getInstance();
    }

    public function getDevice() {
        return self::$_device;
    }

    public static function setDevice($device) {
        self::$_device = $device;
    }

    public function _($text) {
        $args = func_get_args();
        return Core_Model_Translator::translate($text, $args);
    }

    public function isHomePage() {
        return $this->getRequest()->getParam('module') == 'Front' &&
            $this->getRequest()->getParam('controller') == 'index' &&
            $this->getRequest()->getParam('action') == 'index'
        ;
    }

    public function isMobileDevice() {
        return DEVICE_TYPE == 'mobile';
    }

    public function getJs($name) {
        return $this->getRequest()->getMediaUrl().'/app/design/' . APPLICATION_TYPE . '/' . DESIGN_CODE . '/js/' . $name;
    }

    public function getImagePath() {
        return Core_Model_Directory::getDesignPath(false) . '/images';
    }
    public function getBaseImagePath() {
        return Core_Model_Directory::getDesignPath(true) . '/images';
    }

    public function getImage($name, $base = false) {

        if(file_exists($this->getBaseImagePath() . "/" . $name)) {
            $path = $base ? $this->getBaseImagePath() : $this->getImagePath();
            return $path."/".$name;
        }
        else if(file_exists(Media_Model_Library_Image::getBaseImagePathTo($name))) {
            return $base ? Media_Model_Library_Image::getBaseImagePathTo($name) : Media_Model_Library_Image::getImagePathTo($name);
        }

        return "";

    }

    public function getColorizedImage($image_id, $color) {

        $color = str_replace('#', '', $color);
        $id = md5(implode('+', array($image_id, $color)));
        $url = '';

        $image = new Media_Model_Library_Image();
        if(is_numeric($image_id)) {
            $image->find($image_id);
            if(!$image->getId()) return $url;
            if(!$image->getCanBeColorized()) $color = null;
            $path = $image->getLink();
            $path = Media_Model_Library_Image::getBaseImagePathTo($path, $image->getAppId());
        } else if(!Zend_Uri::check($image_id) AND stripos($image_id, Core_Model_Directory::getBasePathTo()) === false) {
            $path = Core_Model_Directory::getBasePathTo($image_id);
        } else {
            $path = $image_id;
        }

        try {
            $image = new Core_Model_Lib_Image();
            $image->setId($id)
                ->setPath($path)
                ->setColor($color)
                ->colorize()
            ;
            $url = $image->getUrl();
        } catch(Exception $e) {
            $url = '';
        }

        return $url;
    }

    public function getUrl($url = '', array $params = array(), $locale = null) {
        return Core_Model_Url::create($url, $params, $locale);
    }

    public function getPath($uri = '', array $params = array(), $locale = null) {
        return Core_Model_Url::createPath($uri, $params);
    }

    public function getBasePath($remove_app = false) {

        $path_info = array_filter(explode("/", $this->getRequest()->getPathInfo()));
        $request_uri = array_filter(explode("/", $this->getRequest()->getRequestUri()));

        if($remove_app AND $this->getRequest()->isApplication() AND $this->getRequest()->useApplicationKey()) {
            $path_info = array_diff($path_info, array(Application_Model_Application::OVERVIEW_PATH));
            $request_uri = array_diff($request_uri, array(Application_Model_Application::OVERVIEW_PATH));
        }

        $base_path = array_diff($request_uri, $path_info);
        $base_path = join("/", $base_path);

        if(!empty($base_path) AND stripos($base_path, "/") !== 0) $base_path = "/$base_path";

        return $base_path;

    }

    public function getCurrentUrl($withParams = true, $locale = null) {
        return Core_Model_Url::current($withParams, $locale);
    }

    protected function _renderZendMenu($xml) {
        $config = new Zend_Config_Xml($xml);
        $this->navigation(new Zend_Navigation($config));
        if(!$this->getPluginLoader('helper')->getPaths(Zend_View_Helper_Navigation::NS)) {
            $this->addHelperPath('Zend/View/Helper/Navigation', 'Zend_View_Helper_Navigation');
        }

        if(!$this->getPluginLoader('helper')->getPaths('Siberian_View_Helper_Navigation')) {
            $this->addHelperPath('Siberian/View/Helper/Navigation', 'Siberian_View_Helper_Navigation');
        }

        $nav = $this->navigation();
        return $nav->menu();
    }

}
