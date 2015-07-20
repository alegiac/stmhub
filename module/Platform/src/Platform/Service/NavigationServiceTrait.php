<?php

namespace Platform\Service;

trait NavigationServiceTrait
{

    /**
     * @var NavigationService
     */
    protected $navigationService;

    /**
     * @param \Platform\Service\NavigationService $navigationService
     */
    public function setNavigationService($navigationService)
    {
    	$this->navigationService = $navigationService;
    }

    /**
     * @return \Platform\Service\NavigationService
     */
    public function getNavigationService()
    {
    	if (!$this->navigationService)
    		$this->setNavigationService($this->getServiceLocator()->get("NavigationService"));
    	return $this->navigationService;
    }
}