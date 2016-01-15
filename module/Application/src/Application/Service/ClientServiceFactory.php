<?php

namespace Application\Service;

use Application\Service\ClientService;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class ClientServiceFactory implements FactoryInterface
{
    /**
     * Service factory
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return ClientService
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
    	return new ClientService($serviceLocator);
    }
}