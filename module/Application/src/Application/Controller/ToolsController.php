<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Session\Container;
use Application\Constants\MediaType;
use Application\Constants\ItemType;
use Application\Form\ExamSelect;
use Application\Form\ExamInput;
use Application\Service\ExamService;
use EddieJaoude\Zf2Logger\Log\Logger;
use Core\Exception\MalformedRequest;
use Core\Exception\InconsistentContent;
use Application\Form\ExamEmpty;
use Zend\Form\Form;
use Application\Form\ExamMultisubmit;
use Application\Form\ExamDragDrop;
use Application\Service\StudentService;
use Application\Service\CourseService;

class ToolsController extends AbstractActionController
{
	/**
	 * Oggetto sessione exam
	 * 
	 * @var Container
	 */
	private $session;
	
	/**
	 * Oggetto configurazione di sistema
	 * 
	 * @var Zend\Config
	 */
	private $config;
	
	/**
	 * Logger
	 * 
	 * @var Logger
	 */
	private $logger;
	
	
	public function indexAction() 
	{}
	
	public function demAction()
	{
		$this->init();
		$this->getStudentService()->rollEmailForSessions();
		echo "Done";die();
	}
	
	public function structureallAction()
	{
		set_time_limit(0);
		$this->init();
		$course = $this->getCourseService()->findById($this->params()->fromRoute('course'));
		echo $this->getStudentService()->associateAllStudentsToCourse($course, new \DateTimeImmutable());
		die();
		
		
	}
	public function structureAction()
	{
		$this->init();
		$studentId = $this->params()->fromRoute('user');
		$courseId = $this->params()->fromRoute('course');
		$student = $this->getStudentService()->findById($studentId);
		$course = $this->getCourseService()->findById($courseId);
		echo $this->getStudentService()->associateStudentToCourse($student, $course, new \DateTimeImmutable());
		die();
	}
	
	/**
	 * Impostazioni di default (acquisizione sessione, impostazione config e logger).
	 * Verifica la presenza dei dati di accesso in sessione, altrimenti invalida e 
	 * re-invia l'utente a STM
	 * 
	 * @return void
	 */
	protected function init()
	{
		$this->session = new Container('exam');
		$this->config = $this->getServiceLocator()->get('Config');
		$this->logger = $this->getServiceLocator()->get('Logger');
	}
	
	/**
	 * @return CourseService
	 */
	private function getCourseService()
	{
		return $this->getServiceLocator()->get('CourseService');
	}
	
	/**
	 * @return StudentService
	 */
	private function getStudentService()
	{
		return $this->getServiceLocator()->get('StudentService');
	}
}
