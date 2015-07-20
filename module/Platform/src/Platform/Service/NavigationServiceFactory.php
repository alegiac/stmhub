<?php

namespace Platform\Service;

use Platform\Service\NavigationService;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class NavigationServiceFactory implements FactoryInterface
{
    /**
     * Service factory
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return NavigationService
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
    	return new NavigationService($serviceLocator);
    }
}