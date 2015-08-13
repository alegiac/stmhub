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
use Application\Entity\StudentHasAnsweredToItem;

use Application\Entity\Repository\StudentHasCourseHasExamRepo;
use Application\Entity\Repository\StudentRepo;
use Application\Entity\Repository\ExamHasItemRepo;

use Core\Exception\MalformedRequest;
use Core\Exception\ObjectNotFound;
use Core\Exception\ObjectNotEnabled;
use Core\Exception\InconsistentContent;
use Application\Entity\Repository\StudentHasAnsweredToItemRepo;
use Application\Entity\Exam;
use Application\Entity\Course;
use Application\Entity\Repository\ExamRepo;
use Application\Entity\Repository\ItemoptionRepo;

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
	 * Acquisizione repository student_has_answered_to_item
	 * @return StudentHasAnsweredToItemRepo
	 */
    private function getStudentHasAnsweredToItemRepo()
    {
    	return $this->getEntityManager()->getRepository('Application\Entity\StudentHasAnsweredToItem');
    }
    
    /**
     * Acquisizione repository exam
     * @return ExamRepo
     */
    private function getExamRepo()
    {
    	return $this->getEntityManager()->getRepository('Application\Entity\Exam');
    }
    
    /**
     * Acquisizione repository itemoption
     * @return ItemoptionRepo
     */
    private function getItemoptionRepo()
    {
    	return $this->getEntityManager()->getRepository('Application\Entity\Itemoption');
    }
    
    /**
     * Acquisizione di tutti gli item per un esame
     * @param Exam $exam
     * @return array
     */
    private function getExamItems(Exam $exam) 
    {
    	$retval = array();
    	
    	// Acquisizione items
    	$gwItems = $this->getExamHasItemRepo();
    	$items = $gwItems->findByExam($exam);
    	if (!is_null($items)) {
    		foreach ($items as $eitem) {
    			/* @var $eitem ExamHasItem */
    			$retval[$eitem->getProgressive()] = array(
    					'id' => $eitem->getItem()->getId(),
    					'question' => $eitem->getItem()->getQuestion(),
    					'media' => $eitem->getItem()->getImage(),
    					'maxsecs' => $eitem->getItem()->getMaxsecs(),
    					'maxtries' => $eitem->getItem()->getMaxtries(),
    					'type' => $eitem->getItem()->getItemtype()->getId(),
    					'media' => $this->getExamItemMedia($eitem->getItem()),
    					'options' => $this->getExamItemOptions($eitem->getItem()),
    			);
    	
    		}
    	}
    	return $retval;
    }
    
    /**
     * Acquisizione di tutti i media di un item
     * @param Item $item
     * @return multitype:|multitype:multitype:string number
     */
    private function getExamItemMedia(Item $item)
    {
    	$retval = array();
    	
    	$images = $item->getImage();
    	if (count($images)) {
    		foreach ($images as $image) {
    			/* @var $image Image */
    			$retval[] = array(
    				'url' => $image->getUrl(),
    				'type' => $image->getMediatype()->getId(),
    			);
    		}
    	}
    	return $retval;
    }
    
    /**
     * Acquisizione di tutte le opzioni di un item
     * @param Item $item
     * @return unknown
     */
    private function getExamItemOptions(Item $item)
    {
    	$retval = array();
    	
    	// Carica opzioni
    	$options = $item->getItemoption();
    	
    	if (count($options)) {
    		
    		foreach ($options->getValues() as $option) {
    			
    			/* @var $option Itemoption */
    			$retval[] = array(
    				'id' => $option->getId(),
    				'value' => $option->getName()
    			);
    		}
    	}	
    	return $retval;
    }

    /** 
     * Acquisizione del massimo di punti ottenibili per un item
     * 
     * @param Item $item
     * @return number
     */
    private function getItemMaxPoints(Item $item)
    {
    	$totPoints = 0;
    	$options = $item->getItemoption();
    	
    	if (count($options)) {
    		foreach ($options->getValues() as $option) {
    		
    			/* @var $option Itemoption */
    			if ($option->getCorrect() === 1) $totPoints += $option->getPoints();
    		}
    	}
    	
    	return $totPoints;
    }
    
    /**
     * Acquisizione del massimo di punti ottenibili per un esame
     * 
     * @param Exam $exam
     * @return number
     */
    private function getExamMaxPoints(Exam $exam)
    {
    	$totPoints = 0;
    	
    	$gw = $this->getExamHasItemRepo();
    	$items = $gw->findByExam($exam);
    	
    	if (count($items)) {
    		foreach ($items as $item) {
    			/* @var $item ExamHasItem */
    			$totPoints += $this->getItemMaxPoints($item->getItem());
    		}
    	}
    	return $totPoints;
    }
    
    /**
     * Acquisizione del massimo di punti ottenibili per un corso
     * 
     * @param Course $course
     * @return number
     */
    private function getCourseMaxPoints(Course $course)
    {
    	$totPoints = 0;
    	
    	$gwExams = $this->getExamRepo();
    	$exams = $gwExams->findByCourse($course);

    	if (count($exams)) {
    		foreach ($exams as $exam) {
    			/* @var $exam Exam */
    			$totPoints += $this->getExamMaxPoints($exam);
    		}
    	}
    	
    	return $totPoints;
    }
    
    /**
     * Acquisizione delle statistiche per studente, a partire da una sessione di esame
     * 
     * @param StudentHasCourseHasExam $studentCourseExam
     * @return array
     */
    private function getStatsForStudent(StudentHasCourseHasExam $studentCourseExam)
    {
    	// Inizializzazione array di ritorno
    	$retval = array();
    	$retval['exams_completed'] = 0;
    	$retval['exams_points'] = 0;
    	// Calcolo punti totali possibili per il corso
    	$retval['course_total_points'] = $this->getCourseMaxPoints($studentCourseExam->getStudentHasCourse()->getCourse());
    	$retval['course_max_possible_points'] = $retval['course_total_points'];
    	
    	// Numero totale di esami sostenuti per il corso corrente E punteggio totale raggiunto nel corso
    	$gw_shche = $this->getStudentHasCourseHasExamRepo();
    	$gw_shati = $this->getStudentHasAnsweredToItemRepo();
    	$gw_itemoptions = $this->getItemoptionRepo();
    	
    	$allExamsForStudent = $gw_shche->findByStudentOnCourse($studentCourseExam->getStudentHasCourse());
    	// Per tutti gli esemi sostenuti dallo studente leggo i punti e li sommo.
    	// Se poi l'esame è completato, si aggiunge alla lista degli esami corso completato
    	if (count($allExamsForStudent)) {
    		foreach ($allExamsForStudent as $examForStudent) {
    			/* @var $examForStudent StudentHasCourseHasExam */
    			$retval['exams_points'] += $examForStudent->getPoints();
    			if ($examForStudent->getCompleted()) $retval['exams_completed']++;
    			// Controlliamo se lo studente ha risposto a questa domanda
    			$answers = $gw_shati->findByStudentCourseExam($examForStudent);
    			if (count($answers)) {
    				// L'utente ha risposto agli item dell'esame: confronto la risposta.
    				foreach ($answers as $answer) {
    					/* @var $answer StudentHasAnsweredToItem */
    					$realOption = null;
    					$itemOptions = $answer->getItem()->getItemoption()->getValues();
    					if (count($itemOptions)) {
    						foreach ($itemOptions as $option) {
    							if ($option->getCorrect() === 1) {
    								$realOption = $option;
    								break;
    							}
    						}
    					}
    					/* @var $actualOption Itemoption */
    					$actualOption = $gw_itemoptions->find($answer->getOptionId());
    					if ($realOption != $actualOption) {
    						$points = $realOption->getPoints();
    						$actualPoints = $actualOption->getPoints();
    						$exceedingPoints = $points-$actualPoints;
    						$retval['course_max_possible_points'] -= $exceedingPoints;
    					}
    				}
    			}		
    		}
    	}
    	return $retval;
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
    			'items' => $this->getExamItems($session->getExam()),
    	);
    	
    	$retval = array(
    		'id' => $session->getId(),
    		'session' => $examData,
    		'student' => array(
    			'id' => $session->getStudentHasCourse()->getStudent()->getId(),
    			'firstname' => $session->getStudentHasCourse()->getStudent()->getFirstname(),
    			'lastname' => $session->getStudentHasCourse()->getStudent()->getLastname(),
    		),
    		'stats' => $this->getStatsForStudent($session),
    		'message' => $message
    	);
    	
    	return $retval;
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
    public function getExamSessionByToken($token)
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
    	if ($course->getActivationstatus()->getId() != ActivationStatus::STATUS_ENABLED) {
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