<?php

namespace Core\Service;

trait SetupServiceTrait
{

    /**
     * @var SetupService
     */
    protected $setupService;

    /**
     * @param \Core\Service\SetupService $setupService
     */
    public function setSetupService(\Core\Service $setupService)
    {
    	$this->setupService = $setupService;
    }

    /**
     * @return \Core\Service\SetupService
     */
    public function getSetupService()
    {
    	if (!$this->setupService)
    		$this->setSetupService($this->getServiceLocator()->get("SetupService"));
    	return $this->setupService;
    }
}