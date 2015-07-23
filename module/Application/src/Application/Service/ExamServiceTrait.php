<?php

namespace Application\Service;

trait ExamServiceTrait
{

    /**
     * @var ExamService
     */
    protected $examService;

    /**
     * @param \Application\Service\ExamService $examService
     */
    public function setExamService(\Application\Service $examService)
    {
    	$this->examService = $examService;
    }

    /**
     * @return \Application\Service\SetupService
     */
    public function getExamService()
    {
    	if (!$this->examService)
    		$this->setExamService($this->getServiceLocator()->get("ExamService"));
    	return $this->examService;
    }
}