<?php

namespace Application\Service;

trait SetupServiceTrait
{

    /**
     * @var SetupService
     */
    protected $setupService;

    /**
     * @param \Application\Service\SetupService $setupService
     */
    public function setSetupService($setupService)
    {
    	$this->setupService = $setupService;
    }

    /**
     * @return \Application\Service\SetupService
     */
    public function getSetupService()
    {
    	if (!$this->setupService)
    		$this->setSetupService($this->getServiceLocator()->get("SetupService"));
    	return $this->setupService;
    }
}