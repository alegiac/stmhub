<?php

namespace Application\Service;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\ServiceManager;
use Zend\Crypt\BlockCipher;
use Zend\Crypt\Symmetric\Mcrypt;

use Application\Constants\ActivationStatus;

use Application\Entity\Student;
use Application\Entity\StudentHasCourseHasExam;
use Application\Entity\ExamHasItem;
use Application\Entity\Item;
use Application\Entity\Image;
use Application\Entity\Itemoption;

use Application\Entity\Repository\StudentHasCourseHasExamRepo;
use Application\Entity\Repository\StudentRepo;
use Application\Entity\Repository\ExamHasItemRepo;

use Core\Exception\MalformedRequest;
use Core\Exception\ObjectNotFound;
use Core\Exception\ObjectNotEnabled;
use Core\Exception\InconsistentContent;

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
     * Acquisizione entity manager
     * @return \Doctrine\ORM\EntityManager
     */
    public function getEntityManager()
    {
    	return $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
    }

    /**
     * Acquisizione repository student
     * @return StudentRepo
     */
    private function getStudentRepo()
    {
    	return $this->getEntityManager()->getRepository('Application\Entity\Student');
    }
    
    /**
     * Acquisizione repository student_has_course_has_exam
     * @return StudentHasCourseHasExamRepo
     */
    private function getStudentHasCourseHasExamRepo()
    {
    	return $this->getEntityManager()->getRepository('Application\Entity\StudentHasCourseHasExam');
    }
    
    /**
     * Acquisizione repository exam_has_item
     * @return ExamHasItemRepo
     */
    private function getExamHasItemRepo()
    {
    	return $this->getEntityManager()->getRepository('Application\Entity\ExamHasItem');
    }
   
    /**
     * Acquisizione di un risultato null/template
     * 
     * @param String $message Messaggio eventuale
     * @return array Array di dati template/nulli
     */
    private function getResponseTemplate($message = "")
    {
  
    }
    
    /**
     * Acquisizione di tutto l'esame corrente per lo studente
     * 
     * @param StudentHasCourseHasExam $session Sessione corrente
     * @return array Array dati sessione d'esame
     */
    private function getUserExamData(StudentHasCourseHasExam $session,$message = "")
    {
    	if (is_null($session)) {
    		return array (
    			'id' => null,
    			'message' => $message
    		);
    	}
    	
    	$examData = array(
    			'exam' => array(
    				'id' => $session->getExam()->getId(),
    				'name' => $session->getExam()->getName(),
    				'description' => $session->getExam()->getDescription(),
    				'totalitems' => $session->getExam()->getTotalitems(),
    				'photourl' => $session->getExam()->getImageurl(),
    				'progress' => $session->getExam()->getProgOnCourse(),
    			),
    			'course' => array(
    				'id' => $session->getExam()->getCourse()->getId(),
    				'name' => $session->getExam()->getCourse()->getName(),
    				'description' => $session->getExam()->getCourse()->getDescription(),
    			),
    			'progress' => $session->getProgressive(),
    			'items' => array(),
    	);
    	
    	// Acquisizione items
    	$gwItems = $this->getExamHasItemRepo();
    	$items = $gwItems->findByExam($session->getExam());
    	$tmpItems = array();
    	if (!is_null($items)) {
    		foreach ($items as $eitem) {
    			/* @var $eitem ExamHasItem */
    			$tmpItems[$eitem->getProgressive()] = array(
    		 		'id' => $eitem->getItem()->getId(),
    		 		'question' => $eitem->getItem()->getQuestion(),
    		 		'media' => $eitem->getItem()->getImage(),
    		 		'maxsecs' => $eitem->getItem()->getMaxsecs(),
    		 		'maxtries' => $eitem->getItem()->getMaxtries(),
    		 		'type' => $eitem->getItem()->getItemtype()->getId(),
    				'media' => array(),
    				'options' => array(),		
    		 	);
    			
    			// Carica immagini/media
    			$images = $eitem->getItem()->getImage();
    			if (count($images)) {
    				foreach ($images as $image) {
    					/* @var $image Image */
    					$tmpItems[$eitem->getProgressive()]['media'][] = array(
    						'url' => $image->getUrl(),
    						'type' => $image->getMediatype()->getId(),
    					);
    				}
    			}
    			// Carica opzioni
    			$options = $eitem->getItem()->getItemoption();
    			if (count($options)) {
    				foreach ($options as $option) {
    					/* @var $option Itemoption */
    					$tmpItems[$eitem->getProgressive()]['options'][] = array('id' => $option->getId(),'value' => $option->getName());
    				}
    			}
    		}
    	}
    	$examData['items'] = $tmpItems;
    	
    	// Acquisizione numero dell'item da processare
    	
    	return array(
    		'id' => $session->getId(),
    		'session' => $examData,
    		'student' => array(
    			'id' => $session->getStudentHasCourse()->getStudent()->getId(),
    			'firstname' => $session->getStudentHasCourse()->getStudent()->getFirstname(),
    			'lastname' => $session->getStudentHasCourse()->getStudent()->getLastname(),
    		),
    		'message' => $message
    	);
    }
    
    /**
     * Acquisizione dell'id reale di sessione d'esame da far eseguire
     * allo studente che accede al link. 
     *  
     * La funzione verifica:
     * 1) esistenza token utente e di sessione-esame
     * 2) il corretto intervallo temporale di applicazione
     * 3) eventuali sessioni precedenti non completate/iniziate
     * 
     * @param string $token Full request token 
     * @return array Array dati studente e associazione esame
     */
    public function getExamSessionIdByToken($token)
    {
    	// Validazione campi richiesta
    	if (strpos($token, ".") === false) 
    		throw new MalformedRequest("Token di richiesta non inserito");
    	
    	$studentToken = substr($token,0,strpos($token, "."));
    	$sessionToken = substr($token,strpos($token, ".")+1);
    	
    	if (!$studentToken || strlen($studentToken) == 0) 
    		throw new MalformedRequest("Token richiesta non valido: subtoken studente non inserito");
    	if (!$sessionToken || strlen($sessionToken) == 0)
    		throw new MalformedRequest("Token richiesta non valido: subtoken sessione-esame non inserito");
    	
    	// Acquisizione studente
    	$student = $this->getStudentRepo()->findByIdentifier($studentToken);
    	if (!$student) 
    		throw new ObjectNotFound(sprintf("Nessuno studente trovato con identificativo %s",$studentToken));
    	if ($student->getActivationstatus()->getId() != ActivationStatus::STATUS_ENABLED) 
    		throw new ObjectNotEnabled(sprintf("Studente con identificativo %s trovato ma non abilitato",$studentToken));

    	// Acquisizione sessione di esame
    	$session = $this->getStudentHasCourseHasExamRepo()->findByIdentifier($sessionToken);
    	if (!$session)
    		throw new ObjectNotFound(sprintf("Nessuna sessione d'esame trovata con identificativo %s",$sessionToken));

    	// Controllo incrociato studente/sessione
    	if ($session->getStudentHasCourse()->getStudent() != $student)
    		throw new InconsistentContent(sprintf("Sessione con token %s valida ma non assegnata all'account studente con identificativo %s. Probabile tentativo di hacking",$sessionToken,$studentToken));
    	
    	// Da qui il processo di validazione e' completato
    	$course = $session->getStudentHasCourse()->getCourse();
    	if ($course->getActivationstatus() != ActivationStatus::STATUS_ENABLED) {
    		// Corso disabilitato
    		return $this->getUserExamData(null,"Corso disabilitato");
    	}
    	
    	// Tutti gli esami dello studente
    	$allSessions = $this->getStudentHasCourseHasExamRepo()->findByStudentOnCourse($session->getStudentHasCourse());
    	
    	// Sessione corrente gia' completata?
    	if ($session->getCompleted()) {

    		foreach ($allSessions as $sess) {
    			/* @var $sess StudentHasCourseHasExam */
    			if ($sess->getCompleted() === 0 && $sess->getStartDate() < new \DateTime()) {
    				// Trovata sessione successiva
    				return $this->getUserExamData($sess,'Sessione successiva iniziata da completare');
    			} 
    		} 
    		return $this->getUserExamData(null,'Tutte le sessioni sono terminate');
    	} else {
    		foreach ($allSessions as $sess) {
    			/* @var $sess StudentHasCourseHasExam */
    			if ($sess->getCompleted() === 0 && $sess->getStartDate() < new \DateTime()) {
    				// Trovata sessione successiva
    				if ($sess == $session) {
    					return $this->getUserExamData($sess);
    				} 
    				return $this->getUserExamData($sess,'Sessione precedente iniziata da completare');
    			}
    		}
    	}
	} 
}