<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{

    protected $_request = null;
    protected $_application = null;
    protected $_front_controller = false;

    protected function _initPaths() {

        Zend_Loader_Autoloader::getInstance()->registerNamespace('Core');

        $base_path = '';
        if(isset($_SERVER['SCRIPT_FILENAME'])) $base_path = realpath(dirname($_SERVER['SCRIPT_FILENAME']));
        Core_Model_Directory::setBasePath($base_path);

        $path = '';
        if(isset($_SERVER['SCRIPT_NAME'])) $path = $_SERVER['SCRIPT_NAME'];
        else if(isset($_SERVER['PHP_SELF'])) $path = $_SERVER['PHP_SELF'];
        $path = str_replace('/'.basename($path), '', $path);
        Core_Model_Directory::setPath($path);

    }

    protected function _initErrorMessages() {

        if(APPLICATION_ENV == "production") {
            error_reporting(0);
        } else {
            error_reporting(E_ALL ^ E_STRICT);
        }

    }

    protected function _initHtaccess() {

        $old_htaccess = Core_Model_Directory::getBasePathTo('htaccess.txt');
        $new_htaccess = Core_Model_Directory::getBasePathTo('.htaccess');
        if(!file_exists($new_htaccess) AND is_readable($old_htaccess) AND is_writable(Core_Model_Directory::getBasePathTo())) {
            $content = file_get_contents($old_htaccess);
            $content = str_replace('# ${RewriteBase}', 'RewriteBase '.Core_Model_Directory::getPathTo(), $content);
            $htaccess = fopen($new_htaccess, 'w');
            fputs($htaccess, $content);
            fclose($htaccess);
        }

    }

    protected function _initConnection() {

        $this->bootstrap('db');
        $resource = $this->getResource('db');
        Zend_Registry::set('db', $resource);

        if(Installer_Model_Installer::isInstalled()) {

            try {
                $default = new Core_Model_Db_Table();
                $default->checkConnection();
            } catch(Exception $e) {
                $logger = Zend_Registry::get("logger");
                $logger->sendException("Fatal Error When Connecting to The Database: \n".print_r($e, true));
            }

        }

    }

    /**
     * Permet de garder le nom des modules avec une majuscule et les url en minuscule
     */
    protected function _initDispatcher() {

        $frontController = Zend_Controller_Front::getInstance();
        $frontController->setDispatcher(new Siberian_Controller_Dispatcher_Standard());
        $this->bootstrap('frontController');
        $this->_front_controller = $frontController;
    }

    protected function _initRequest() {

        Core_Model_Language::prepare();
        $frontController = $this->_front_controller;
        $this->_request = new Siberian_Controller_Request_Http();
//        $this->_request->setBackofficeUrl($this->getOption('backofficeUrl'));
        $this->_request->isInstalling(!Installer_Model_Installer::isInstalled());
        $this->_request->setPathInfo();
        $baseUrl = $this->_request->getScheme().'://'.$this->_request->getHttpHost().$this->_request->getBaseUrl();
        $this->_request->setBaseUrl($baseUrl);
        $frontController->setRequest($this->_request);
        Siberian_View::setRequest($this->_request);
        Core_Model_Default::setBaseUrl($this->_request->getBaseUrl());

    }

    protected function _initLogger() {

        if(!$this->_request->isInstalling()) {

            if (!is_dir(Core_Model_Directory::getBasePathTo('var/log'))) {
                mkdir(Core_Model_Directory::getBasePathTo('var/log'), 0777, true);
            }

            $writer = new Zend_Log_Writer_Stream(Core_Model_Directory::getBasePathTo('var/log/output.log'));
            $logger = new Siberian_Log($writer);
            Zend_Registry::set('logger', $logger);

        }

    }

    protected function _initRouter() {

        $front = $this->_front_controller;
        $router = $front->getRouter();
        $router->addRoute('default', new Siberian_Controller_Router_Route_Module(array(), $front->getDispatcher(), $front->getRequest()));

    }

    protected function _initCache() {

        $cache_dir = Core_Model_Directory::getCacheDirectory(true);
        if(is_writable($cache_dir)) {
            $frontendConf = array ('lifetime' => 345600, 'automatic_seralization' => true);
            $backendConf = array ('cache_dir' => $cache_dir);
            $cache = Zend_Cache::factory('Core','File',$frontendConf,$backendConf);
            $cache->setOption('automatic_serialization', true);
            Zend_Locale::setCache($cache);
            Zend_Registry::set('cache', $cache);
        }
    }

    protected function _initDesign() {

        if(!$this->_request->isInstalling()) {
            $this->_prepareBlocks();
        }

        $this->getPluginLoader()->addPrefixPath('Siberian_Application_Resource', 'Siberian/Application/Resource');

        Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer')->setNeverRender(true);

    }

    protected function _initModules() {

        if(APPLICATION_ENV == "development" AND !$this->_request->isInstalling()) {

            $front = $this->_front_controller;
            $module_names = $front->getDispatcher()->getSortedModuleDirectories();
            foreach($module_names as $module_name) {
                $module = new Installer_Model_Installer_Module();
                $module->prepare($module_name);
                if($module->canUpdate()) {
                    $module->install();
                }
            }

        }

    }

    protected function _prepareBlocks() {
        if($this->_request->isApplication()) {
            Core_View_Mobile_Default::setBlocks(Application_Model_Application::getInstance()->getBlocks());
        }
    }

    public function run() {

        $front   = $this->_front_controller;
        $default = $front->getDefaultModule();
        if (null === $front->getControllerDirectory($default)) {
            throw new Zend_Application_Bootstrap_Exception(
                'No default controller directory registered with front controller'
            );
        }

        $front->setParam('bootstrap', $this);
        $request = $front->getRequest();

        $response = $front->dispatch($request);
        if ($front->returnResponse()) {
            return $response;
        }
    }

}

function debugbacktrace($dumpError = true) {

    $errors = debug_backtrace();
    $dump = '';
    foreach($errors as $error) {
        if(!empty($error['file'])) $dump .= 'file : ' . $error['file'];
        if(!empty($error['function'])) $dump .= ':: ' . $error['function'];
        if(!empty($error['line'])) $dump .= ' (l:' . $error['line'] . ')';
        $dump .= '
';
    }

    if($dumpError) {
        Zend_Debug::dump($dump);
    } else {
        return $dump;
    }

}