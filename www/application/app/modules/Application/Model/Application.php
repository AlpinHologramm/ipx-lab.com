<?php

class Application_Model_Application extends Core_Model_Default {

    const PATH_IMAGE = '/images/application';
    const OVERVIEW_PATH = 'overview';

    protected static $_instance;
    protected $_startup_image;
    protected $_customers;
    protected $_options;
    protected $_pages;
    protected $_uses_user_account;
    protected $_layout;
    protected $_devices;
    protected $_design;
    protected $_design_blocks;
    protected $_admin_ids = array();

    public static function getInstance() {
        if(!self::$_instance) {
            self::$_instance = new self();
            self::$_instance->find(1);
        }
        return self::$_instance;
    }

    public function __construct($params = array()) {
        parent::__construct($params);
        $this->_db_table = 'Application_Model_Db_Table_Application';
    }

    public function findByHost($host, $path = null) {

        if(!empty($path)) {
            $uri = explode('/', ltrim($path, '/'));
            $i = 0;
            while($i <= 1) {
                if(!empty($uri[$i])) {
                    $value = $uri[$i];
                    $this->find($value, 'tmp_key');
                    if($this->getId()) {
                        $this->setUseTmpKey('1');
                        break;
                    }
                }
                $i++;
            }
        }

        if(!$this->getId()) {

            if(!in_array($host[0], array('www'))) {
                $this->find($host, 'domain');
            }
        }

        return $this;

    }

    public function findAllByAdmin($admin_id) {
        return $this->getTable()->findAllByAdmin($admin_id);
    }

    public function findAllToPublish() {
        return $this->getTable()->findAllToPublish();
    }

    public function getOwner() {

        $admin = new Admin_Model_Admin();
        $admin->find($this->getAdminId());
        return $admin;

    }

    public function save() {

        if(!$this->getId()) {
            $this->setLayoutId(1)->setKey(uniqid());
        }

        parent::save();

        if(!empty($this->_admin_ids)) {
            foreach($this->_admin_ids as $admin_id) {
                $this->getTable()->addAdmin($this->getId(), $admin_id);
            }
        }

        return $this;

    }

    public function addAdmin($admin) {

        if($this->getId()) {
            $is_allowed_to_add_pages = $admin->hasIsAllowedToAddPages() ? $admin->getIsAllowedToAddPages() : true;
            $this->getTable()->addAdmin($this->getId(), $admin->getId(), $is_allowed_to_add_pages);
        } else {
            if (!in_array($admin->getId(), $this->_admin_ids)) {
                $this->_admin_ids[] = $admin->getId();
            }
        }

        return $this;

    }

    public function removeAdmin($admin) {
        $this->getTable()->removeAdmin($this->getId(), $admin->getId());
        return $this;
    }

    public function getDevices() {

        $device_ids = array_keys(Application_Model_Device::getAllIds());
        foreach($device_ids as $device_id) {
            if(empty($this->_devices[$device_id])) {
                $this->getDevice($device_id);
            }
        }

        return $this->_devices;

    }

    public function getDevice($device_id) {

        if(empty($this->_devices[$device_id])) {
            $device = new Application_Model_Device();
            $device->find(array("app_id" => $this->getId(), "type_id" => $device_id));
            if(!$device->getId()) {
                $device->loadDefault($device_id);
                $device->setAppId($this->getId());
            }
            $this->_devices[$device_id] = $device;
        }

        return $this->_devices[$device_id];

    }

    public function getDesign() {

        if(!$this->_design) {
            $this->_design = new Template_Model_Design();
            if($this->getDesignId()) {
                $this->_design->find($this->getDesignId());
            }
        }

        return $this->_design;

    }

    public function setDesign($design) {

        $image_name = uniqid().'.png';
        $relative_path = '/homepage_image/bg/';
        $lowres_relative_path = '/homepage_image/bg_lowres/';

        if(!is_dir(self::getBaseImagePath().$lowres_relative_path)) {
            mkdir(self::getBaseImagePath().$lowres_relative_path, 0777, true);
        }
        if(!@copy($design->getBackgroundImage(true), self::getBaseImagePath().$lowres_relative_path.$image_name)) {
            throw new Exception($this->_('An error occurred while saving'));
        }

        if(!is_dir(self::getBaseImagePath().$relative_path)) {
            mkdir(self::getBaseImagePath().$relative_path, 0777, true);
        }
        if(!@copy($design->getBackgroundImageRetina(true), self::getBaseImagePath().$relative_path.$image_name)) {
            throw new Exception($this->_('An error occurred while saving'));
        }

        foreach($design->getBlocks() as $block) {
            $block->setAppId($this->getId())->save();
        }

        $this->setDesignId($design->getId())
            ->setLayoutId($design->getLayoutId())
            ->setHomepageBackgroundImageRetinaLink($relative_path.$image_name)
            ->setHomepageBackgroundImageLink($lowres_relative_path.$image_name)
        ;

        return $this;

    }

    public function getBlocks() {

        $block = new Template_Model_Block();
        if(empty($this->_design_blocks)) {
            $this->_design_blocks = $block->findAll(array('app_id' => $this->getId()), 'position ASC');

            if(!empty($this->_design_blocks)) {
                foreach($this->_design_blocks as $block) {
                    $block->setApplication($this);
                }
            }
        }

        return $this->_design_blocks;
    }

    public function getBlock($code) {

        $blocks = $this->getBlocks();

        foreach($blocks as $block) {
            if($block->getCode() == $code) return $block;
        }

        return;
    }

    public function setBlocks($blocks) {
        $this->_design_blocks = $blocks;
        return $this;
    }

    public function getLayout() {

        if(!$this->_layout) {
            $this->_layout = new Application_Model_Layout_Homepage();
            $this->_layout->find($this->getLayoutId());
        }

        return $this->_layout;

    }

    public function getKey() {
        return self::OVERVIEW_PATH;
    }

    public function getBundleId() {

        $bundle_id = $this->getData("bundle_id");
        $bundle_id_parts = explode(".", $bundle_id);

        if(count($bundle_id_parts) != count(array_filter($bundle_id_parts))) {
//            $formatted_name = Core_Model_Lib_String::format($this->getName(), true);
//            if(!empty($formatted_name)) {
//                $bundle_id = "com.{$formatted_name}-{$this->getId()}.$formatted_name";
//            } else {
            $url = Zend_Uri::factory(parent::getUrl(""))->getHost();
            $url = array_reverse(explode(".", $url));
            $url[] = "app".$this->getKey();
            foreach($url as $k => $part) {
                if(is_numeric(substr($part, 0, 1))) {
                    $url[$k] = Core_Model_Lib_String::format("a".$part);
                }
            }
            $bundle_id = implode(".", $url);
//            }
        }

        if($bundle_id != $this->getData("bundle_id")) {
            $this->setBundleId($bundle_id)->save();
        }

        return $bundle_id;
    }

    public function isActive() {
        return (bool) $this->getData("is_active");
    }

    public function isLocked() {
        return (bool) $this->getData("is_locked");
    }

    public function getCustomers() {

        if(is_null($this->_customers)) {
            $customer = new Customer_Model_Customer();
            $this->_customers = $customer->findAll(array("app_id" => $this->getId()));
        }

        return $this->_customers;

    }

    public function getOptions() {

        if(empty($this->_options)) {
            $option = new Application_Model_Option_Value();
            $this->_options = $option->findAll(array("a.app_id" => $this->getId(), "is_visible" => 1));
        }

        return $this->_options;

    }

    public function getOptionIds() {

        $option_ids = array();
        $options = $this->getOptions();
        foreach($options as $option) {
            $option_ids[] = $option->getOptionId();
        }

        return $option_ids;

    }

    public function getOption($code) {

        $option_sought = new Application_Model_Option();
        $dummy = new Application_Model_Option();
        $dummy->find($code, 'code');
        foreach($this->getOptions() as $page) {
            if($page->getOptionId() == $dummy->getId()) $option_sought = $page;
        }

        return $option_sought;

    }

    public function getPages($samples = 0) {

        if(empty($this->_pages)) {
            $option = new Application_Model_Option_Value();
            $this->_pages = $option->findAll(array("a.app_id" => $this->getId(), 'remove_folder' => new Zend_Db_Expr('folder_category_id IS NULL'), 'is_visible' => 1/*, '`aov`.`is_active`' => 1*/));
        }
        if($this->_pages->count() == 0 AND $samples > 0) {
            $dummy = Application_Model_Option_Value::getDummy();
            for($i = 0; $i < $samples; $i++) {
                $this->_pages->addRow($this->_pages->count(), $dummy);
            }
        }

        return $this->_pages;

    }

    public function getPage($code) {

        $dummy = new Application_Model_Option();
        $dummy->find($code, 'code');

        $page_sought = new Application_Model_Option_Value();
        return $page_sought->find(array('app_id' => $this->getId(), 'option_id' => $dummy->getId()));

    }

    public function getFirstActivePage() {
        foreach($this->getPages() as $page) {
            if($page->isActive()) {
                if($page->getCode() != "padlock" AND (!$page->isLocked() OR $this->getSession()->getCustomer()->canAccessLockedFeatures())) {
                    return $page;
                }
            }
        }
        return new Application_Model_Option_Value();
    }

    public function getTabbarAccountName() {
        if($this->hasTabbarAccountName()) return $this->getData('tabbar_account_name');
        else return $this->_('My account');
    }

    public function getShortTabbarAccountName() {
        return Core_Model_Lib_String::formatShortName($this->getTabbarAccountName());
    }

    public function getTabbarMoreName() {
        if($this->hasTabbarMoreName()) return $this->getData('tabbar_more_name');
        else return $this->_('More');
    }

    public function getShortTabbarMoreName() {
        return Core_Model_Lib_String::formatShortName($this->getTabbarMoreName());
    }

    public function usesUserAccount() {

        if(is_null($this->_uses_user_account)) {
            $this->_uses_user_account = false;
            $codes = array('newswall', 'discount', 'loyalty');
            foreach($codes as $code) {
                $option = $this->getOption($code);
                if($option->getId() AND $option->isActive()) $this->_uses_user_account = true;
            }
        }

        return $this->_uses_user_account;
    }

    public function getCountryCode() {
        $code = $this->getData('country_code');
        if(is_null($code)) {
            $code = Core_Model_Language::getCurrentLocaleCode();
        }
        return $code;
    }

    public function isPublished() {

        foreach($this->getDevices() as $device) {
            if($device->isPublished()) return true;
        }

        return false;

    }

    public function getQrcode($uri = null, $params = array()) {
        $qrcode = new Core_Model_Lib_Qrcode();
        $url = "";
        if(filter_var($uri, FILTER_VALIDATE_URL, FILTER_FLAG_PATH_REQUIRED)) {
            $url = $uri;
        }
        else {
            $url = $this->getUrl($uri);
        }

        return $qrcode->getImage($this->getName(), $url, $params);
    }

    public static function getImagePath() {
        return Core_Model_Directory::getPathTo(self::PATH_IMAGE);
    }
    public static function getBaseImagePath() {
        return Core_Model_Directory::getBasePathTo(self::PATH_IMAGE);
    }

    public function getLogo() {
        $logo = self::getImagePath().$this->getData('logo');
        $base_logo = self::getBaseImagePath().$this->getData('logo');
        if(is_file($base_logo) AND file_exists($base_logo)) return $logo;
        else return self::getImagePath().'/placeholder/no-image.png';
    }

    public function getIcon($size = null, $name = null, $base = false) {

        if(!$size) $size = 114;

        $icon = self::getBaseImagePath().$this->getData('icon');
        if(!is_file($icon) OR !file_exists($icon)) $icon = self::getBaseImagePath().'/placeholder/no-image.png';

        if(empty($name)) $name = sha1($icon.$size);
        $name .= '_'.filesize($icon);

        $newIcon = new Core_Model_Lib_Image();
        $newIcon->setId($name)
            ->setPath($icon)
            ->setWidth($size)
            ->crop()
        ;
        return $newIcon->getUrl($base);
    }

    public function getAppStoreIcon($base = false) {
        return $this->getIcon(1024, 'touch_icon_'.$this->getId(). '_1024', $base);
    }

    public function getGooglePlayIcon($base = false) {
        return $this->getIcon(512, 'touch_icon_'.$this->getId(). '_512', $base);
    }

    public function getStartupImageUrl($type = "standard", $base = false) {

        try {
            $image = '';

            if($type == "standard") $image_name = $this->getData('startup_image');
            else $image_name = $this->getData('startup_image_'.$type);

            if(!empty($image_name) AND file_exists(self::getBaseImagePath().$image_name)) {
                $image = $base ? self::getBaseImagePath().$image_name : self::getImagePath().$image_name;
            }
        }
        catch(Exception $e) {
            $image = '';
        }

        if(empty($image)) {
            $image = $this->getNoStartupImageUrl($type, $base);
        }

        return $image;
    }

    public function getNoStartupImageUrl($type = 'normal', $base = false) {
        $path = $base ? self::getBaseImagePath() : self::getImagePath();
        return $type == 'normal' ? $path.'/placeholder/no-startupimage.png' : $path.'/placeholder/no-startupimage_retina.png';
    }

    public function getShortName() {

        if($name = $this->getName()) {
            if(mb_strlen($name, 'UTF-8') > 11) $name = trim(mb_substr($name, 0, 10, "UTF-8")) . '...';
        }

        return $name;

    }

    public function getFacebookId() {
        return Api_Model_Key::findKeysFor('facebook')->getAppId();
    }

    public function getFacebookKey() {
        return Api_Model_Key::findKeysFor('facebook')->getSecretKey();
    }

    public function updateOptionValuesPosition($positions) {
        $this->getTable()->updateOptionValuesPosition($positions);
        return $this;
    }

    public function isAvailableForPublishing() {
        $errors = array();
        if($this->getPages()->count() < 3) $errors[] = $this->_("At least, 4 pages in the application");
        if(!$this->getData('background_image')) $errors[] = $this->_("The homepage image");
        if(!$this->getStartupImage()) $errors[] = $this->_("The startup image");
        if(!$this->getData('icon')) $errors[] = $this->_("The desktop icon");
        if(!$this->getName()) $errors[] = $this->_("The application name");
        if(!$this->getBundleId()) $errors[] = $this->_("The bundle id");

        return $errors;
    }

    public function getBackgroundImageUrl($type = 'normal') {

        try {
            $backgroundImage = '';
            if($background_image = $this->getData('background_image')) {
                if($type == 'normal') $background_image .= '.jpg';
                else if($type == 'retina') $background_image .= '@2x.jpg';
                else if($type == 'retina4') $background_image .= '-568h@2x.jpg';
                if(file_exists(self::getBaseImagePath().$background_image)) {
                    $backgroundImage = self::getImagePath().$background_image;
                }
            }
        }
        catch(Exception $e) {
            $backgroundImage = '';
        }

        if(empty($backgroundImage)) {
            $backgroundImage = $this->getNoBackgroundImageUrl($type);
        }

        return $backgroundImage;
    }

    public function getHomepageBackgroundImageUrl($type = '') {

        try {

            $image = '';

            switch($type) {
                case "hd": $image_name = $this->getData('background_image_hd'); break;
                case "tablet": $image_name = $this->getData('background_image_tablet'); break;
                case "standard":
                default: $image_name = $this->getData('background_image'); break;
            }

            if(!empty($image_name) AND file_exists(self::getBaseImagePath().$image_name)) {
                $image = self::getImagePath().$image_name;
            }
        }
        catch(Exception $e) {
            $image = '';
        }

        if(empty($image)) {
            $image = $this->getNoBackgroundImageUrl($type);
        }

        return $image;
    }

    public function getNoBackgroundImageUrl($type = 'normal') {
        return $type == 'normal' ? self::getImagePath().'/placeholder/no-background.jpg' : self::getImagePath().'/placeholder/no-background_retina.jpg';
    }

    public function getUrl($url = '', array $params = array(), $locale = null, $forceKey = false) {

        $request = Zend_Controller_Front::getInstance()->getRequest();
        if(!$this->getDomain()) $forceKey = true;

        if($forceKey) {
            $use_key = $request->useApplicationKey();
            $request->useApplicationKey(true);
            $url = Core_Model_Url::create($url, $params, $locale);
            $request->useApplicationKey($use_key);
        } else {
            $url = Core_Model_Url::createCustom('http://'.$this->getDomain(), $url, $params, $locale);
        }

        if(substr($url, strlen($url) -1, 1) != "/") {
            $url .= "/";
        }

        return $url;

    }

    public function getPath($uri = '', array $params = array(), $locale = null) {

        $request = Zend_Controller_Front::getInstance()->getRequest();
        $useKey = (bool) $request->useApplicationKey();
        $request->useApplicationKey(true);
        if($this->getValueId()) {
            $param["value_id"] = $this->getValueId();
        }
        $url = parent::getPath($uri, $params, $locale);
        $request->useApplicationKey($useKey);

        return $url;

    }

    public function requireToBeLoggedIn() {
        return $this->getData('require_to_be_logged_in');
    }

}
