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
	
	
	public function structureAction()
	{
		$this->init();
		$student = $this->getStudentService()->findById(2);
		$course = $this->getCourseService()->findById(2);
	
		echo $this->getStudentService()->associateStudentToCourse($student, $course, new \DateTimeImmutable());
		die();
	}
	
	public function studentsAction()
	{
		$this->init();
		
		$stud1 = array(
			'firstname' => 'Federico',
			'lastname' => 'Cocheo',
			'email' => 'f.cocheo@smiletomove.it',
			'passwordsha1' => sha1("password"),
			'sex' => 'M'
		);
		
		$stud2 = array(
				'firstname' => 'Daniela',
				'lastname' => 'Gomiero',
				'email' => 'd.gomiero@smiletomove.it',
				'passwordsha1' => sha1("password"),
				'sex' => 'F'
		);
		
		$stud3 = array(
				'firstname' => 'Marianna',
				'lastname' => 'Marcuzzo',
				'email' => 'm.marcuzzo@smiletomove.it',
				'passwordsha1' => sha1("password"),
				'sex' => 'F'
		);
		
		$stud4 = array(
				'firstname' => 'Debora',
				'lastname' => 'Oliosi',
				'email' => 'd.oliosi@smiletomove.it',
				'passwordsha1' => sha1("password"),
				'sex' => 'F'
		);
		
		$res = $this->getStudentService()->insertStudent($stud1);
		$res = $this->getStudentService()->insertStudent($stud2);
		$res = $this->getStudentService()->insertStudent($stud3);
		$res = $this->getStudentService()->insertStudent($stud4);
		
		echo "ssss";
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
