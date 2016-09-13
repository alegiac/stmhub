<?php

// TODO: Impostazioni PHPINI
ini_set('display_errors', true);
ini_set('date.timezone','Europe/Rome');
ini_set('memory_limit', '2048M');

set_time_limit(0);

/**
 * This makes our life easier when dealing with paths. Everything is relative
 * to the application root now.
 */
chdir(dirname(__DIR__));

// Decline static file requests back to the PHP built-in webserver
if (php_sapi_name() === 'cli-server' && is_file(__DIR__ . parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH))) {
    return false;
}

// Setup autoloading
require 'init_autoloader.php';

if (!defined('APPLICATION_PATH')) {
	define('APPLICATION_PATH', realpath(__DIR__ . '/../'));
}

if (!defined('APPLICATION_ENV')) {
	define('APPLICATION_ENV',$_SERVER['APPLICATION_ENV']);
}

$cfgName = APPLICATION_ENV;
if ($cfgName == "production") $cfgName = "application";

$appConfig = include APPLICATION_PATH . '/config/'.$cfgName.'.config.php';

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods', 'PUT, GET, POST, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Authorization, WWW-Authenticate, Origin, X-Requested-With, Content-Type, Accept');

// Run the application!
Zend\Mvc\Application::init($appConfig)->run();


// Run the application!
//Zend\Mvc\Application::init(require 'config/application.config.php')->run();
