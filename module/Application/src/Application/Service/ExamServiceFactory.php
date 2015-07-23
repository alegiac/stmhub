<?php

namespace Application\Service;

use Application\Service\ExamService;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class ExamServiceFactory implements FactoryInterface
{
    /**
     * Service factory
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return ExamService
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
    	return new ExamService($serviceLocator);
    }
}