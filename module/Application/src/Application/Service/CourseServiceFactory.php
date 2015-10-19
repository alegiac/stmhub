<?php

namespace Application\Service;

use Application\Service\CourseService;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class CourseServiceFactory implements FactoryInterface
{
    /**
     * Service factory
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return CourseService
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
    	return new CourseService($serviceLocator);
    }
}