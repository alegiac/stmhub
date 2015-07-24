<?php

namespace Application\Service;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\ServiceManager;

final class ExamService implements ServiceLocatorAwareInterface
{
	
    use ServiceLocatorAwareTrait;
    
    /**
     * Costruttore di classe
     * @param ServiceLocatorInterface $serviceLocator
     */
    public function __construct(ServiceLocatorInterface $serviceLocator)
    {

    	$this->serviceLocator = $serviceLocator;
    }
    
    /**
     * Verifica validità del token ricevuto via email dall'utente.
     * E' composto dal token utente e dal token di sessione d'esame.
     * 
     * La funzione verifica 
     */
    public function checkExamSessionToken()
    {}
     
}