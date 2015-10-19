<?php

namespace Application\Service;

use Application\Service\ExamService;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class StudentServiceFactory implements FactoryInterface
{
    /**
     * Service factory
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return StudentService
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
    	return new StudentService($serviceLocator);
    }
}