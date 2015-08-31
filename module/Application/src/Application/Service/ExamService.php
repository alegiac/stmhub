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
use Core\Constants\Errorcode;
use Application\Entity\Repository\ItemRepo;

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
     * Acquisizione repository item
     * @return ItemRepo
     */
    private function getItemRepo()
    {
    	return $this->getEntityManager()->getRepository('Application\Entity\Item');
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
    	$optionGw = $this->getItemoptionRepo();
    	$options = $optionGw->findByItem($item);
    	if (count($options)) {
    		
    		foreach ($options as $option) {
    			
    			/* @var $option Itemoption */
    			$retval[] = array(
    				'id' => $option->getId(),
    				'value' => $option->getName(),
    				'correct' => $option->getCorrect(),
    				'points' => $option->getPoints()
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
    	$options = $this->getItemoptionRepo()->findByItem($item);
    	
    	if (count($options)) {
    		foreach ($options as $option) {
    		
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
    	$retval['exam_max_possible_points'] = $this->getExamMaxPoints($studentCourseExam->getExam());
    	
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
    					$itOptions = $this->getItemoptionRepo()->findByItem($answer->getItem());
    					if (count($itOptions)) {
    						foreach ($itOptions as $option) {
    							if ($option->getCorrect() === 1) {
    								$realOption = $option;
    								break;
    							}
    						}
    					}
    					/* @var $actualOption Itemoption */
    					if ($answer->getOptionId() != null) {
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
    	}
    	return $retval;
    }
    
    private function composeAnswer(StudentHasCourseHasExam $session,$isError = false,$message = "")
    {
    	$retval = array();
    	$retval['result'] = (int)!$isError;
    	$retval['message'] = $message;
    	
    	if ($session) {
    		$retval['course'] = array(
    			'id' => $session->getStudentHasCourse()->getCourse()->getId(),
    			'name' => $session->getStudentHasCourse()->getCourse()->getName(),
    			'description' => $session->getStudentHasCourse()->getCourse()->getDescription(),
    			'durationweek' => $session->getStudentHasCourse()->getCourse()->getDurationweek(),
    			'periodicity' => $session->getStudentHasCourse()->getCourse()->getPeriodicityweek(),
    			'numexams' => $session->getStudentHasCourse()->getCourse()->getTotalexams(),
    			'weekday' => $session->getStudentHasCourse()->getCourse()->getWeekday()->getId()	
    		);
    		
    		$retval['exam'] = array(
    			'id' => $session->getExam()->getId(),
    			'name' => $session->getExam()->getName(),
    			'description' => $session->getExam()->getDescription(),
    			'totalitems' => $session->getExam()->getTotalitems(),
    			'photourl' => $session->getExam()->getImageurl(),
    			'progress' => $session->getExam()->getProgOnCourse(),
    			'totalpoints' => $session->getExam()->getPointsIfCompleted(),
    			'outtimereduction' => $session->getExam()->getReducePercentageOuttime(),
    		);
    		
    		$retval['student'] = array(
    			'id' => $session->getStudentHasCourse()->getStudent()->getId(),
    			'firstname' => $session->getStudentHasCourse()->getStudent()->getFirstname(),
    			'lastname' => $session->getStudentHasCourse()->getStudent()->getLastname(),
    			'sex' => $session->getStudentHasCourse()->getStudent()->getSex()
    		);
    		
    		$retval['session'] = array(
    			'id' => $session->getId(),
    			'answer' => $session->getAnswer(),
    			'completed' => $session->getCompleted(),
    			'expectedenddate' => $session->getExpectedEndDate(),
    			'points' => $session->getPoints(),
    			'progressive' => $session->getProgressive(),
    			'startdate' => $session->getStartDate(),
    		);
    		
    		$retval['stats'] = $this->getStatsForStudent($session);
    
    		$ei = $this->getExamHasItemRepo()->findByExamAndProgressive($session->getExam(),$session->getProgressive());
    		
    		if ($ei) {
    			$retval['current_item'] = array(
    				'examitemid' => $ei->getId(),
	    			'id' => $ei->getItem()->getId(),
    				'question' => $ei->getItem()->getQuestion(),
    				'answer' => $ei->getItem()->getAnswer(),
	    			'context' => $ei->getItem()->getContext(),
	    			'media' => $this->getExamItemMedia($ei->getItem()),
	    			'options' => $this->getExamItemOptions($ei->getItem()),
	    			'type' => $ei->getItem()->getItemtype()->getId(),
	    			'maxsecs' => $ei->getItem()->getMaxsecs(),
	    			'maxtries' => $ei->getItem()->getMaxtries(),
	    		);
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
    				'numexams' => $session->getExam()->getCourse()->getTotalexams(),
    			),
    			'progress' => $session->getProgressive(),
    			'enddate' => $session->getEndDate()->format('d/m/Y'),
    			'items' => $this->getExamItems($session->getExam()),
    	);
    	
    	$retval = array(
    		'id' => $session->getId(),
    		'session' => $examData,
    		'student' => array(
    			'id' => $session->getStudentHasCourse()->getStudent()->getId(),
    			'firstname' => $session->getStudentHasCourse()->getStudent()->getFirstname(),
    			'lastname' => $session->getStudentHasCourse()->getStudent()->getLastname(),
    			'sex' => $session->getStudentHasCourse()->getStudent()->getSex()
    		),
    		'stats' => $this->getStatsForStudent($session),
    		'message' => $message
    	);
    	
    	
    	return $retval;
    }
    
    /**
     * Aggiornamento progress (numero item attivo in quel momento).
     * Decreta l'ingresso dello studente nella domanda
     * 
     * @param int $sessionId Identificativo sessione
     * @param int $number Numero di item
     */
    public function setExamSessionProgress($sessionId,$number)
    {
    	$session = $this->getStudentHasCourseHasExamRepo()->find($sessionId);
    	/* @var $session StudentHasCourseHasExam */
    	$session->setProgressive($number);
    	$this->getEntityManager()->persist($session);
    	$this->getEntityManager()->flush();
    }
    
    /**
     * Verifica veridicità risposta
     * @param int $itemId Identificativo item
     * @param int $optionId Identificativo option
     * 
     * @return boolean
     */
    public function checkOptionCorrect($optionId)
    {
    	$option = $this->getItemoptionRepo()->find($optionId);
    	if (!$option) return false;
    	return ($option->getCorrect() === 1);
    }
    
    /**
     * Memorizzazione di una risposta fornita dall'utente
     * 
     * @param int $sessionId
     * @param int $examId
     * @param int $itemId
     * @param int $optionId
     * 
     * @return integer
     */
    public function responseWithAnOption($sessionId, $examId, $itemId, $optionId)
    {
    	$this->getEntityManager()->beginTransaction();
    	
    	$session = $this->getStudentHasCourseHasExamRepo()->find($sessionId);
    	$exam = $this->getExamRepo()->find($examId);
    	$item = $this->getItemRepo()->find($itemId);
    	$option = $this->getItemoptionRepo()->find($optionId);
    	
    	// Creazione risposta
    	$answer = new StudentHasAnsweredToItem();
    	$answer->setInsertDate(new \DateTime());
    	$answer->setStudentHasCourseHasExam($session);
    	$answer->setItem($item);
    	$answer->setTimeout(0);
    	$answer->setPoints($option->getPoints());
    	$answer->setValue($option->getName());
    	$answer->setCorrect($option->getCorrect());
    	$this->getEntityManager()->persist($answer);
    	
    	// Aggiornamento punti
    	$session->setPoints($session->getPoints()+$option->getPoints());
    	$this->getEntityManager()->persist($session);
    	
    	// Aggiornamento avanzamento
    	$currentProgressive = $session->getProgressive();
    	$session->setProgressive($currentProgressive+1);
    	if ($exam->getTotalitems() == $currentProgressive+1) {
    		$endDate = new \DateTime();
    		$session->setCompleted(1);
    		$session->setEndDate($endDate);
    		if ($endDate > $session->getExpectedEndDate()) {
    			// Decurtare il punteggio finale
    			$perc = $exam->getReducePercentageOuttime();
    			$newPoints = ceil($session->getPoints() - (($session->getPoints()*$perc)/100));
    			$this->getEntityManager()->persist($session);
    			$retval = 1;
    		} else {
    			$retval = 0;
    		}
    	}
    	
    	$this->getEntityManager()->flush();
    	$this->getEntityManager()->commit();
    	
    	return $retval;
    }
    
    /**
     * Gestione timeout (risposta non data in tempo utile)
     * 
     * @param int $sessionId Identificativo sessione
     * @param int $examId Identificativo esame
     * @param int $itemId Identificativo domanda
     */
    public function responseWithATimeout($sessionId,$examId,$itemId)
    {
    	$this->getEntityManager()->beginTransaction();
    	
    	$session = $this->getStudentHasCourseHasExamRepo()->find($sessionId);
    	$exam = $this->getExamRepo()->find($examId);
    	$item = $this->getItemRepo()->find($itemId);
    	
    	$answer = new StudentHasAnsweredToItem();
    	$answer->setInsertDate(new \DateTime());
    	$answer->setStudentHasCourseHasExam($session);
    	$answer->setItem($item);
    	$answer->setTimeout(1);
    	$answer->setPoints(0);
    	$answer->setCorrect(0);
    	$this->getEntityManager()->persist($answer);
    	
    	// Aggiornamento avanzamento
    	$currentProgressive = $session->getProgressive();
    	$session->setProgressive($currentProgressive+1);
    	if ($exam->getTotalitems() == $currentProgressive+1) {
    		$endDate = new \DateTime();
    		$session->setCompleted(1);
    		$session->setEndDate($endDate);
    		if ($endDate > $session->getExpectedEndDate()) {
    			// Decurtare il punteggio finale
    			$perc = $exam->getReducePercentageOuttime();
    			$newPoints = ceil($session->getPoints() - (($session->getPoints()*$perc)/100));
    			$this->getEntityManager()->persist($session);
    			$retval = 1;
    		} else {
    			$retval = 0;
    		}
    	}

    	$this->getEntityManager()->flush();
    	$this->getEntityManager()->commit();
    	
    	return $retval;
    }
    
    public function resetDemo($sessionId)
    {
    	$session = $this->getStudentHasCourseHasExamRepo()->find($sessionId);
    	$session->setCompleted(0);
    	$session->setEndDate(null);
    	$session->setPoints(0);
    	$session->setProgressive(0);
    	$this->getEntityManager()->persist($session);
    	$this->getEntityManager()->flush();
    	
    	
    	
    }
    /**
     * Acquisizione dell'item corrente della session d'esame corrente
     * @param unknown $token
     */
    public function getCurrentExamSessionItemByToken($token)
    {
    	if (strpos($token,".") === false) 
    		throw new MalformedRequest("The \".\" character is missing in the token [".$token."]");
    		 
    	$studentToken = substr($token,0,strpos($token, "."));
    	$sessionToken = substr($token,strpos($token, ".")+1);
    		 
    	if (!$studentToken || strlen($studentToken) == 0)
    		throw new MalformedRequest("The token student part is not valued [".$token."]");
    	if (!$sessionToken || strlen($sessionToken) == 0)
    		throw new MalformedRequest("The token session part is not valued [".$token."]");
    		 
    	// Acquisizione studente
    	$student = $this->getStudentRepo()->findByIdentifier($studentToken);
    	if (!$student)
    		throw new ObjectNotFound("No student found for the token student part [".$studentToken."]",Errorcode::ERRCODE_STUDENT_NOT_FOUND);
    	if ($student->getActivationstatus()->getId() != ActivationStatus::STATUS_ENABLED)
    		throw new ObjectNotEnabled("Not enabled student found [".$student->getId()."] for the token student part [".$studentToken."]",Errorcode::ERRCODE_STUDENT_NOT_ENABLED);
    	
    	// Acquisizione sessione di esame
    	$session = $this->getStudentHasCourseHasExamRepo()->findByIdentifier($sessionToken);
    	if (!$session)
    		throw new ObjectNotFound("No exam session found for the token session part [".$sessionToken."]",Errorcode::ERRCODE_SESSION_NOT_FOUND);
    	
    	// Controllo incrociato studente/sessione
    	if ($session->getStudentHasCourse()->getStudent() != $student)
    		throw new InconsistentContent("Both student and session token part are correct [".$studentToken."], [".$sessionToken."], but not related. Possible hacking trial");
    		 
    	// Da qui il processo di validazione e' completato
    	$course = $session->getStudentHasCourse()->getCourse();
    	if ($course->getActivationstatus()->getId() != ActivationStatus::STATUS_ENABLED) {
    		// Corso disabilitato
    		return $this->composeAnswer($session,true,"Il corso è stato disattivato");
    	}
    		 
    	// Tutti gli esami dello studente
    	$allSessions = $this->getStudentHasCourseHasExamRepo()->findByStudentOnCourse($session->getStudentHasCourse());
   
    	// Sessione corrente gia' completata?
    	if ($session->getCompleted()) {
    			
    		// Esistono sessioni da completare?
    		$remainingSessions = 0;
    			
    		foreach ($allSessions as $sess) {
    			/* @var $sess StudentHasCourseHasExam */
    			if ($sess->getCompleted() === 0) {
    				$remainingSessions++;
    			}
    		}
    		if (!$remainingSessions) {
    			// Tutte le sessioni sono completate
    			return $this->composeAnswer(null,true,"Tutte le sessioni sono state completate");
    		}
    			
    		foreach ($allSessions as $sess) {
    			if ($sess->getCompleted() === 0 && $sess->getStartDate() < new \DateTime()) {
   					// Trovata sessione successiva
   					return $this->composeAnswer($sess,false,"Questa sessione risulta completata. Trovata una sessione successiva da completare");
   				}
   			}
   			// Nessuna sessione disponibile
   			return $this->composeAnswer(null,true,"Nessuna sessione attualmente disponibile");
    			
   		} else {
   			// Prima di mandare l'utente all'esame, verifica se ci sono sessioni precedenti
   			foreach ($allSessions as $sess) {
   				/* @var $sess StudentHasCourseHasExam */
   				if ($sess->getCompleted() === 0 && $sess->getStartDate() < new \DateTime()) {
   					// Trovata sessione precedente
   					if ($sess == $session) {
   						return $this->composeAnswer($sess,false,"");
   					}
   					return $this->composeAnswer($sess,false,'Sessione precedente trovata da completare');
   				}
   			}
		}
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
    		throw new MalformedRequest("The \".\" character is missing in the token [".$token."]");
    	
    	$studentToken = substr($token,0,strpos($token, "."));
    	$sessionToken = substr($token,strpos($token, ".")+1);
    	
    	if (!$studentToken || strlen($studentToken) == 0) 
    		throw new MalformedRequest("The token student part is not valued [".$token."]");
    	if (!$sessionToken || strlen($sessionToken) == 0)
    		throw new MalformedRequest("The token session part is not valued [".$token."]");
    	
    	// Acquisizione studente
    	$student = $this->getStudentRepo()->findByIdentifier($studentToken);
    	if (!$student) 
    		throw new ObjectNotFound("No student found for the token student part [".$studentToken."]",Errorcode::ERRCODE_STUDENT_NOT_FOUND);
    	if ($student->getActivationstatus()->getId() != ActivationStatus::STATUS_ENABLED) 
    		throw new ObjectNotEnabled("Not enabled student found [".$student->getId()."] for the token student part [".$studentToken."]",Errorcode::ERRCODE_STUDENT_NOT_ENABLED);

    	// Acquisizione sessione di esame
    	$session = $this->getStudentHasCourseHasExamRepo()->findByIdentifier($sessionToken);
    	if (!$session)
    		throw new ObjectNotFound("No exam session found for the token session part [".$sessionToken."]",Errorcode::ERRCODE_SESSION_NOT_FOUND);

    	// Controllo incrociato studente/sessione
    	if ($session->getStudentHasCourse()->getStudent() != $student)
    		throw new InconsistentContent("Both student and session token part are correct [".$studentToken."], [".$sessionToken."], but not related. Possible hacking trial");
    	
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