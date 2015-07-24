<?php

namespace Core\Service;

use Core\Service\SetupService;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class SetupServiceFactory implements FactoryInterface
{
    /**
     * Service factory
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return SetupService
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
    	return new SetupService($serviceLocator);
    }
}