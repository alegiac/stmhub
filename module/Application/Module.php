<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\Session\Container;
use Zend\Session\Config\SessionConfig;
use Zend\Session\SessionManager;

class Module 

{
    public function onBootstrap(MvcEvent $e)
    {
        $eventManager = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
        
        $config = $e->getApplication()->getServiceManager()->get('Configuration');
        
        $sessionConfig = new SessionConfig();
        $sessionConfig->setOptions($config['session']);
        $sessionManager = new SessionManager($sessionConfig);
        $sessionManager->start();
        
        /**
         * Optional: If you later want to use namespaces, you can already store the
         * Manager in the shared (static) Container (=namespace) field
         */
        Container::setDefaultManager($sessionManager);
        
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array (
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }
}