<?php

set_time_limit(120);
ini_set('max_execution_time', 120);
umask(0);

setlocale(LC_MONETARY, 'en_US');

//defined('BASE_PATH')
//    || define('BASE_PATH', realpath(dirname(__FILE__)));

defined('DS')
    || define('DS', DIRECTORY_SEPARATOR);

defined('APPLICATION_PATH')
    || define('APPLICATION_PATH', realpath(dirname(__FILE__) . DS . 'app'));

// Define application environment
defined('APPLICATION_ENV')
    || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production'));

// Ensure lib/ is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
    get_include_path(),
    realpath(APPLICATION_PATH . DS . 'modules'),
    realpath(APPLICATION_PATH . DS . '..' . DS . 'lib'),
)));

/** Zend_Application */
require_once 'Zend/Application.php';

// Create application
$ini = file_exists(APPLICATION_PATH . DS . 'configs' . DS . 'app.ini') ? APPLICATION_PATH.DS.'configs'.DS.'app.ini' : APPLICATION_PATH.DS.'configs'.DS.'app.sample.ini';
$application = new Zend_Application(
    APPLICATION_ENV,
    $ini
);

session_cache_limiter(false);

// Run
$application->bootstrap()->run();
