<?php

// TODO: Impostazioni PHPINI
ini_set('display_errors', true);
ini_set('date.timezone','Europe/Rome');

use Zend\Stdlib\ArrayUtils;

use Zend\Console\Adapter\AdapterInterface as Console;
use Zend\Console\ColorInterface as Color;
use ZF\Console\Application;
use ZF\Console\Dispatcher;
use Application\Service\SetupServiceFactory;

chdir(dirname(__DIR__));

if (!defined('APPLICATION_PATH')) {
	define('APPLICATION_PATH', realpath(__DIR__ . '/../'));
}

$env = $_SERVER['argv'][2];

if ($env == "production") $env = "application";

$appConfig = include APPLICATION_PATH . '/config/'.$env.'.config.php';

// Require application base configuration
require 'init_autoloader.php';

$application = Zend\Mvc\Application::init($appConfig);

// Load the request services
$dispatcher = new Dispatcher();

$services    = $application->getServiceManager();
$setupService = $services->get('Core\\SetupService');

$dispatcher->map('datasetup', function ($route, $console) use ($setupService) {
	$opts = $route->getMatches();
	$result = $setupService->execute($opts['module'], $opts['action']);
	
	if ($result === false) {
		$console->writeLine('Error executing command ' . $opts['action'] . ' in module ' . $opts['module'], Color::WHITE, Color::RED);
		return 1;
	}

	$console->writeLine('Finished executing command ' . $opts['action']. ' in module ' . $opts['module'], Color::BLACK, Color::GREEN);
	return 0;
});

$app = new Application (
		'Module Data Setup', 
		'1.0.0', 
		array(
			array(
				'name' => 'datasetup',
				'route' => 'datasetup <env> <module> <action>',
				'description' => 'Setup database infrastructure for the specified module',
				'short_description' => 'Setup database schema in the module',
				'options_descriptions' => array(
					'env' => 'Environment name',
					'module' => 'Target of the current script execution',
					'action' => 'Action to execute for setup (allowed "seed", "database", "entities")',
				),
				'defaults' => array(),
			),
		),
		$console,
		$dispatcher
);

$app->setDebug(true);
$app->setBanner('
 @@@@@@   @@@@@@@@  @@@@@@@   @@@  @@@  @@@   @@@@@@@  @@@@@@@@     @@@  @@@  @@@  @@@  @@@@@@@
@@@@@@@   @@@@@@@@  @@@@@@@@  @@@  @@@  @@@  @@@@@@@@  @@@@@@@@     @@@  @@@  @@@  @@@  @@@@@@@@
!@@       @@!       @@!  @@@  @@!  @@@  @@!  !@@       @@!          @@!  @@@  @@!  @@@  @@!  @@@
!@!       !@!       !@!  @!@  !@!  @!@  !@!  !@!       !@!          !@!  @!@  !@!  @!@  !@   @!@
!!@@!!    @!!!:!    @!@!!@!   @!@  !@!  !!@  !@!       @!!!:!       @!@!@!@!  @!@  !@!  @!@!@!@
 !!@!!!   !!!!!:    !!@!@!    !@!  !!!  !!!  !!!       !!!!!:       !!!@!!!!  !@!  !!!  !!!@!!!!
     !:!  !!:       !!: :!!   :!:  !!:  !!:  :!!       !!:          !!:  !!!  !!:  !!!  !!:  !!!
    !:!   :!:       :!:  !:!   ::!!:!   :!:  :!:       :!:          :!:  !:!  :!:  !:!  :!:  !:!
:::: ::    :: ::::  ::   :::    ::::     ::   ::: :::   :: ::::     ::   :::  ::::: ::   :: ::::
:: : :    : :: ::    :   : :     :      :     :: :: :  : :: ::       :   : :   : :  :   :: : ::

');
// $app->setBanner('                                                                                                  
//    _____ __  _________    ______   __________     __  _______ _    ________
//   / ___//  |/  /  _/ /   / ____/  /_  __/ __ \   /  |/  / __ \ |  / / ____/
//   \__ \/ /|_/ // // /   / __/      / / / / / /  / /|_/ / / / / | / / __/   
//  ___/ / /  / // // /___/ /___     / / / /_/ /  / /  / / /_/ /| |/ / /___   
// /____/_/  /_/___/_____/_____/    /_/  \____/  /_/  /_/\____/ |___/_____/   
                                                                                                                                                                            
// ');

$exit = $app->run();
exit($exit);