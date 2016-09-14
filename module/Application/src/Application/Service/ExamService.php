<?php

namespace Application\Service;

use Application\Constants\ActivationStatus;

use Application\Entity\StudentHasClientHasCourseHasExam;
use Application\Entity\Item;
use Application\Entity\Image;
use Application\Entity\Itemoption;
use Application\Entity\StudentHasAnsweredToItem;

use Core\Exception\MalformedRequest;
use Core\Exception\ObjectNotFound;
use Core\Exception\ObjectNotEnabled;
use Core\Exception\InconsistentContent;
use Application\Entity\Exam;
use Application\Entity\Course;
use Core\Constants\Errorcode;
use Application\Entity\StudentHasCourse;

final class ExamService extends BaseService
{	
	const SESSION_NOT_TERMINATED = 0;
	const SESSION_TERMINATED = 1;
	const EXAM_TERMINATED = 2;
	const COURSE_TERMINATED = 3;
	
	/**
	 * Load exams and challenges of the course passed as parameter.
	 * Return a shortlist (name, started, completed by the student) to be 
	 * displayed in the views
	 * 
	 * @param Course $course
	 * @return array
	 */
	private function getExamsForCourse(StudentHasClientHasCourseHasExam $session)
	{
		// Initialize the retval: exams and challenges are under an associative array
		// that use the keys as labels in the view.
		$sessRepo = $this->getStudentHasClientHasCourseHasExamRepo();
		//$sessRepo = $this->getStudentHasCourseHasExamRepo();
		
		$retval = array();
		$retval['Esami'] = array();
		$retval['Sfide'] = array();
		
		$course = $session->getStudentHasClientHasCourse()->getClientHasCourse()->getCourse();
		$exams = $this->getExamRepo()->findMandatoriesByCourse($course);
		
		// Got all exams. Need to cycle over the list, find all the sessions 
		// available and check if the exam is completed by the current user
		foreach ($exams as $exam) {
			/* @var $exam Exam */
			$name = $exam->getName();
			$examStarted = false;
			$examCompleted = true;
			
			// Got all the sessions. 
			$sessionsExam = $sessRepo->findByExam($session->getStudentHasClientHasCourse(),$exam);
			foreach ($sessionsExam as $sesEx) {
				/* @var $sesEx StudentHasCourseHasExam */
				if ($sesEx->getRealStartDate() != null) {
					// At least one session has been started. The exam is started!
					$examStarted = true;
				}
				if ($sesEx->getCompleted() == 0) {
					$examCompleted = false;
					break;
				}
			}
			
			$rv = array('name' => $name);
			$rv['started'] = $examStarted;
			$rv['completed'] = $examCompleted;
			
			$retval['Esami'][] = $rv;
		}
		
		$challenges = $this->getExamRepo()->findNotMandatoriesByCourse($course);
		
		foreach ($challenges as $challenge) {
			/* @var $challenge Exam */
			$name = $challenge->getName();
			$sessRepo = $this->getStudentHasClientHasCourseHasExamRepo();
			$challengeStarted = false;
			$challengeCompleted = true;
			
			// Got all the sessions.
			$sessionsChallenge = $sessRepo->findByExam($session->getStudentHasClientHasCourse(),$challenge);
			
			foreach ($sessionsChallenge as $sesCh) {
				
				// Only started challenges can be seen
				/* @var $sesCh StudentHasClientHasCourseHasExam */
				
				if ($sesCh->getStartDate() <= new \DateTime()) {
					if ($sesCh->getRealStartDate() != null) {
						// At least one session has been started. The challenge is started!
						$challengeStarted = true;
					}
					if ($sesCh->getCompleted() == 0) {
						$challengeCompleted = false;
						break;
					}
					$rv = array('name' => $name);
					$rv['started'] = $challengeStarted;
					$rv['completed'] = $challengeCompleted;
					
					$retval['Sfide'][] = $rv;
				}
			}
		}
		
		return $retval;
	}

    /**
     * Return all the media related to an item, for display
     * in the view
     * 
     * @param Item $item
     * @return array
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
     * Loading all the available options for an item
     *  
     * @param Item $item
     * @return array
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
     * Max possible points for a session
     * @param StudentHasClientHasCourseHasExam $session
     * @return number
     */
    private function getSessionMaxPoints(StudentHasClientHasCourseHasExam $session)
    {
    	$totPoints = 0;
    	
    	$items = $session->getItem();
    	$itvalues = $items->getValues();
    	
    	foreach ($itvalues as $item) {
    		$totPoints += $this->getItemMaxPoints($item);
    	}
    	return $totPoints;
    }
    
    private function getTotalPointsForStudentInCourse(\Application\Entity\StudentHasClientHasCourse $studentCourse)
    {
    	return $this->getStudentHasClientHasCourseHasExamRepo()->sumStudentPoints($studentCourse);
    }
    
    private function getTotalTimeForStudentInCourse(\Application\Entity\StudentHasClientHasCourse $studentCourse)
    {
    	return $this->getStudentHasClientHasCourseHasExamRepo()->getTimingForStudent($studentCourse);
    }
    
    private function getTotalSessionsForStudentInCourse(\Application\Entity\StudentHasClientHasCourse $studentCourse)
    {
    	return $this->getStudentHasClientHasCourseHasExamRepo()->countSessions($studentCourse);
    }
    
    private function getClassificationAndPrizes(\Application\Entity\StudentHasClientHasCourse $studentCourse)
    {
    	$retval = array();
    	$index = 1;
    	$clientCourse = $this->getClientHasCourseRepo()->findOneBy(array('course' => $studentCourse->getClientHasCourse()->getCourse()));
    	
    	$list = $this->getStudentHasCourseHasExamRepo()->getAllByPoints();
    	$prizes = $this->getPrizeRepo()->findBy(array('clientHasCourse' => $clientCourse),array('position' => 'ASC'));
    	$countPrizes = count($prizes);
    	
    	// List is ordered, so getting data in the right order determine the pricing association
    	foreach ($list as $element) {
    		/* @var $studentCourseFound StudentHasCourse */
    		$studentCourseFound = $this->getStudentHasCourseRepo()->find($element['student_has_course_id']);
    		
    		if ($index > $countPrizes) break;
    		
    		$firstName = $studentCourseFound->getStudent()->getFirstname();
    		$lastName = $studentCourseFound->getStudent()->getLastname();
    		$retval[$index] = array();
    		
    		$retval[$index]['student'] = array(
    			'firstname' => $firstName,
    			'lastname' => $lastName,
    			'position' => $index,
    			'points' => $this->getTotalPointsForStudentInCourse($studentCourseFound),
    			'timing' => $this->getTotalTimeForStudentInCourse($studentCourseFound),
    			'is_current' => (int)$studentCourse->getId() == $element['student_has_course_id'],
    		);
    		
    		$retval[$index]['prize'] = array(
    			'name' => $prizes[$index-1]->getName(),
    			'desc' => $prizes[$index-1]->getDescription(),
    			'url' => $prizes[$index-1]->getUrl(),
    		);
    		
    		$index++;
    	}
    	
    	return $retval;	
    }
    
    private function getCurrentPositionAndPrizeForStudentInCourse(StudentHasClientHasCourse $studentCourse)
    {
    	$list = $this->getStudentHasClientHasCourseHasExamRepo()->getAllByPoints();
    	$index = 1;
    	$hasPrize = 0;
    	$prizename = null;
    	
    	foreach ($list as $element) {
    		if ($studentCourse->getId() == $element['student_has_client_has_course_id']) {
    			break;
    		} else {
    			$index++;
    		}
    	}
    	
    	// Get prize
    	$clientCourse = $this->getClientHasCourseRepo()->findOneBy(array('course' => $studentCourse->getClientHasCourse()->getCourse()));
    	
    	$prize = $this->getPrizeRepo()->findOneBy(array('clientHasCourse' => $clientCourse,'position' => $index));
    	
    	if (is_null($prize)) {
    		$hasPrize = 0;	
    	} else {
    		$hasPrize = 1;
    		$prizename = $prize->getName();
    	}
    	
    	return array(
    		'position' => $index,
    		'has_prize' => $hasPrize,
    		'prizename' => $prizename,
    		'totaltime' => 0,
    	);
    }
    
    private function composeAnswer(StudentHasClientHasCourseHasExam $session,$isError = false,$message = "")
    {
    	$retval = array();
    	$retval['result'] = (int)!$isError;
    	$retval['message'] = $message;
    	 
    	if (!is_null($session)) {
    		$retval['course'] = array(
                    'name' => $session->getStudentHasClientHasCourse()->getClientHasCourse()->getCourse()->getName(),
    		);
                
                $clientCourse = $session->getStudentHasClientHasCourse()->getClientHasCourse();
                //$clientCourse = $this->getClientHasCourseRepo()->findByCourse($session->getStudentHasClientHasCourse()->getClientHasCourse()->getCourse());
                //$clientCourse = $session->getStudentHasClientHasCourse()->getClientHasCourse();
    		$retval['course']['logo'] = $clientCourse->getLogoFilename();
    
    		$retval['classification'] = $this->getCurrentPositionAndPrizeForStudentInCourse($session->getStudentHasClientHasCourse());
    		$retval['prizes'] = $this->getClassificationAndPrizes($session->getStudentHasClientHasCourse());
    		
    		$retval['exam'] = array(
    				'id' => $session->getExam()->getId(),
    				'name' => $session->getExam()->getName(),
    				'description' => $session->getExam()->getDescription(),
    				'totalitems' => $session->getExam()->getTotalitems(),
    		);
    		//$retval['allexams'] = $this->getExamsForCourse($session->getStudentHasCourse()->getCourse());
    		$retval['allexams'] = $this->getExamsForCourse($session);
    		$retval['student'] = array(
    				'id' => $session->getStudentHasClientHasCourse()->getStudent()->getId(),
    				'firstname' => $session->getStudentHasClientHasCourse()->getStudent()->getFirstname(),
    				'lastname' => $session->getStudentHasClientHasCourse()->getStudent()->getLastname(),
    				'sex' => $session->getStudentHasClientHasCourse()->getStudent()->getSex()
    		);
    
    		$retval['session'] = array(
    				'id' => $session->getId(),
    				'answer' => $session->getAnswer(),
    				'completed' => $session->getCompleted(),
    				'expectedenddate' => $session->getExpectedEndDate(),
    				'points' => $this->getTotalPointsForStudentInCourse($session->getStudentHasClientHasCourse()),
    				'progressive' => $session->getProgressive(),
    				'startdate' => $session->getStartDate(),
    				'realstartdate' => $session->getRealStartDate(),
    				'challenge' => !$session->getMandatory(),
    				'index' => $session->getSessionOnCourse()."/".$this->getTotalSessionsForStudentInCourse($session->getStudentHasClientHasCourse()),
    		);
    
    		//$retval['stats'] = $this->getStatsForStudent($session);
    		$arr = $session->getItem()->toArray();
    		foreach ($arr as $index => $ei) {
    			if ($index == $session->getProgressive()) {
    				$retval['current_item'] = array(
    						'examitemid' => $ei->getId(),
    						'id' => $ei->getId(),
    						'question' => $ei->getQuestion(),
    						'answer' => $ei->getAnswer(),
    						'context' => $ei->getContext(),
    						'media' => $this->getExamItemMedia($ei),
    						'options' => $this->getExamItemOptions($ei),
    						'type' => $ei->getItemtype()->getId(),
    						'maxsecs' => $ei->getMaxsecs(),
    						'maxtries' => $ei->getMaxtries(),
    						'question_number' => ($index+1),
    						'question_total' => count($session->getItem()),
    				);
    				break;
    			}
    		}
    	}
    	
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
    	$session = $this->getStudentHasClientHasCourseHasExamRepo()->find($sessionId);
    	/* @var $session StudentHasClientHasCourseHasExam */
    	$session->setProgressive($number);
    	$this->getEntityManager()->persist($session);
    	$this->getEntityManager()->flush();
    }
    
    /**
     * Verifica veridicita risposta
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
     * Memorizzazione di una risposta reorder
     * 
     */
    public function responseReorder($sessionId,$examId,$itemId,$optionId,$value)
    {
    	$this->getEntityManager()->beginTransaction();
    	$session = $this->getStudentHasClientHasCourseHasExamRepo()->find($sessionId);
    	$exam = $this->getExamRepo()->find($examId);
    	$item = $this->getItemRepo()->find($itemId);
    	$option = $this->getItemoptionRepo()->find($optionId);
    	
    	// Ri-verifica correttezza
    	if (strtolower($option->getName()) == $value) {
    		$points = $option->getPoints();
    		$correct = 1;
    	} else {
    		$points = 0;
    		$correct = 0;
    	}
    	
    	// Creazione risposta
    	$answer = new StudentHasAnsweredToItem();
    	$answer->setInsertDate(new \DateTime());
        $answer->setStudentHasClientHasCourseHasExam($session);
    	//$answer->setStudentHasCourseHasExam($session);
    	$answer->setItem($item);
    	$answer->setTimeout(0);
    	$answer->setPoints($points);
    	$answer->setValue($value);
    	$answer->setCorrect($correct);
    	$this->getEntityManager()->persist($answer);
    	 
    	// Aggiornamento punti
    	$session->setPoints($session->getPoints()+$points);
    	$this->getEntityManager()->persist($session);
    	
    	// Aggiornamento avanzamento
    	$currentProgressive = $session->getProgressive();
    	$session->setProgressive($currentProgressive+1);
    	
    	if (count($session->getItem()) == $currentProgressive+1) {
    		$endDate = new \DateTime();
    		$session->setCompleted(1);
    		$session->setEndDate($endDate);
    		if ($endDate > $session->getExpectedEndDate()) {
    			// Decurtare il punteggio finale
    			$perc = $exam->getReducePercentageOuttime();
    			$newPoints = ceil($session->getPoints() - (($session->getPoints()*$perc)/100));
    		}
    		$this->getEntityManager()->persist($session);
    	
    		//$index = split("/",$session->getSessionIndex());
    		$exam = $session->getExam();
    		$course = $exam->getCourse();
    		
    		$index = $session->getSessionOnExam();
    		
    		if ($index[0] == $index[1]) {
    			// Session has terminated, and exam has terminated too:
    			// let's check for the course
    			$exam = $session->getExam();
    			$course = $exam->getCourse();
    			if ($exam->getMandatory() === 1 && $exam->getProgOnCourse() == $course->getTotalexams()) {
    				$retval = self::COURSE_TERMINATED;
    			} else {
    				$retval = self::EXAM_TERMINATED;
    			}
    		} else {
    			$retval = self::SESSION_TERMINATED;
    		}
    	} else {
    		$retval = self::SESSION_NOT_TERMINATED;
    	}
    	 
    	$this->getEntityManager()->flush();
    	$this->getEntityManager()->commit();
    	 
    	return $retval;
    }
    /**
     * The answer choosen by the user is being stored in the db table
     * and user's points is updated
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
    	// Transaction
    	$this->getEntityManager()->beginTransaction();
    	
    	// Get session, exam, item and option
    	$session = $this->getStudentHasClientHasCourseHasExamRepo()->find($sessionId);
    	$exam = $session->getExam();
    	//$exam = $this->getExamRepo()->find($examId);
    	$item = $this->getItemRepo()->find($itemId);
    	$option = $this->getItemoptionRepo()->find($optionId);
    	
    	// Generate answer
    	$answer = new StudentHasAnsweredToItem();
    	$answer->setInsertDate(new \DateTime());
    	//$answer->setStudentHasCourseHasExam($session);
        $answer->setStudentHasClientHasCourseHasExam($session);
    	$answer->setItem($item);
    	$answer->setTimeout(0);
    	$answer->setPoints($option->getPoints());
    	$answer->setValue($option->getName());
    	$answer->setCorrect($option->getCorrect());
    	$this->getEntityManager()->persist($answer);
    	
    	// Adding points
    	$session->setPoints($session->getPoints()+$option->getPoints());
    	$this->getEntityManager()->persist($session);
    	
    	// Session progress
    	$currentProgressive = $session->getProgressive();
    	$session->setProgressive($currentProgressive+1);
        
        if (count($session->getItem()) == $currentProgressive+1) {
            	$endDate = new \DateTime();
    		$session->setCompleted(1);
    		$session->setEndDate($endDate);
    		if ($endDate > $session->getExpectedEndDate()) {
    			// Decurtare il punteggio finale
    			$perc = $exam->getReducePercentageOuttime();
    			$newPoints = ceil($session->getPoints() - (($session->getPoints()*$perc)/100));
    		}
                
                $this->getEntityManager()->persist($session);
    		
    		$index = split("/",$session->getSessionOnExam());
    		if ($index[0] == $index[1]) {
    			// Session has terminated, and exam has terminated too:
    			// let's check for the course
    			$exam = $session->getExam();
    			$course = $exam->getCourse();
    			if ($exam->getMandatory() === 1 && $exam->getProgOnCourse() == $course->getTotalexams())	 {
    				$retval = self::COURSE_TERMINATED;
    			} else {
    				$retval = self::EXAM_TERMINATED;
    			}
    		} else {
    			$retval = self::SESSION_TERMINATED;
    		}
    	} else {
    		$retval = self::SESSION_NOT_TERMINATED;
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
    	
    	$session = $this->getStudentHasClientHasCourseHasExamRepo()->find($sessionId);
    	$exam = $this->getExamRepo()->find($examId);
    	$item = $this->getItemRepo()->find($itemId);
    	
    	$answer = new StudentHasAnsweredToItem();
    	$answer->setInsertDate(new \DateTime());
    	//$answer->setStudentHasCourseHasExam($session);
        $answer->setStudentHasClientHasCourseHasExam($session);
    	$answer->setItem($item);
    	$answer->setTimeout(1);
    	$answer->setPoints(0);
    	$answer->setCorrect(0);
    	$this->getEntityManager()->persist($answer);
    	
    	// Aggiornamento avanzamento
    	$currentProgressive = $session->getProgressive();
    	$session->setProgressive($currentProgressive+1);
    	if (count($session->getItem()) == $currentProgressive+1) {
    		$endDate = new \DateTime();
    		$session->setCompleted(1);
    		$session->setEndDate($endDate);
    		if ($endDate > $session->getExpectedEndDate()) {
    			// Decurtare il punteggio finale
    			$perc = $exam->getReducePercentageOuttime();
    			$newPoints = ceil($session->getPoints() - (($session->getPoints()*$perc)/100));
    		}
    		$this->getEntityManager()->persist($session);
    	
    		$index = split("/",$session->getSessionOnExam());
    		
    		if ($index[0] == $index[1]) {
    			// Session has terminated, and exam has terminated too:
    			// let's check for the course
    			$exam = $session->getExam();
    			$course = $exam->getCourse();
    			if ($exam->getMandatory() === 1 && $exam->getProgOnCourse() == $course->getTotalexams()) {
    				$retval = self::COURSE_TERMINATED;
    			} else {
    				$retval = self::EXAM_TERMINATED;
    			}
    		} else {
    			$retval = self::SESSION_TERMINATED;
    		}
    	} else {
    		$retval = self::SESSION_NOT_TERMINATED;
    	}
    	 
    	$this->getEntityManager()->flush();
    	$this->getEntityManager()->commit();
    	
    	return $retval;
    }
    
    /**
     * Get current session information
     * @param string $token
     */
    public function getCurrentExamSessionItemByToken($token,$isChallenge = false)
    {
    	// Start validation process
    	if (strpos($token,".") === false) 
    		throw new MalformedRequest("The \".\" character is missing in the token [".$token."]");
    		 
    	$studentToken = substr($token,0,strpos($token, "."));
    	$sessionToken = substr($token,strpos($token, ".") + 1);
    		 
    	if (!$studentToken || strlen($studentToken) == 0)
    		throw new MalformedRequest("The token student part is not valued [".$token."]");
    	if (!$sessionToken || strlen($sessionToken) == 0)
    		throw new MalformedRequest("The token session part is not valued [".$token."]");
    		 
    	// Get the student info
    	$student = $this->getStudentRepo()->findByIdentifier($studentToken);
    	if (!$student)
    		throw new ObjectNotFound("No student found for the token student part [".$studentToken."]",Errorcode::ERRCODE_STUDENT_NOT_FOUND);
    	if ($student->getActivationstatus()->getId() != ActivationStatus::STATUS_ENABLED)
    		throw new ObjectNotEnabled("Not enabled student found [".$student->getId()."] for the token student part [".$studentToken."]",Errorcode::ERRCODE_STUDENT_NOT_ENABLED);
    	
		// Get the session info
    	$session = $this->getStudentHasClientHasCourseHasExamRepo()->findByIdentifier($sessionToken);
    	
    	if (!$session)
    		throw new ObjectNotFound("No exam session found for the token session part [".$sessionToken."]",Errorcode::ERRCODE_SESSION_NOT_FOUND);
    	
    	// Does the session belong to the student?
    	if ($session->getStudentHasClientHasCourse()->getStudent() != $student)
    		throw new InconsistentContent("Both student and session token part are correct [".$studentToken."], [".$sessionToken."], but not related. Possible hacking trial");
    	
    	// End validation process
    		
    		
    	// Get course information
    	$course = $session->getStudentHasClientHasCourse()->getClientHasCourse()->getCourse();
    	if ($course->getActivationstatus()->getId() != ActivationStatus::STATUS_ENABLED) {
    		return $this->composeAnswer($session,true,"Course has been disabled");
    	}
    	
    	// No propedeutical challenge mechanism. Is it a challenge? Then compose the resulting session data
    	if ($isChallenge === true) {
    		if ($session->getRealStartDate() == null) {
    			$session->setRealStartDate(new \DateTime());
    			$this->getEntityManager()->persist($session);
    			$this->getEntityManager()->flush();
    		}
    		return $this->composeAnswer($session,false,"");
    	}
    	
    	// The mechanism is:
    	// Student accesses here because of an email link. We have no control on "when" the student click 
    	// on the link. For that reason, we need to check if the session has already completed.
    	// Furthermore, if the student clicks the link having previous left open sessions, we need to redirect
    	// him to the right open session.
        $allSessions = $this->getStudentHasClientHasCourseHasExamRepo()->findByStudentOnCourse($session->getStudentHasClientHasCourse());
    	//$allSessions = $this->getStudentHasCourseHasExamRepo()->findByStudentOnCourse($session->getStudentHasCourse());
   		
    	// Current session is completed?
    	if ($session->getCompleted()) {
    			
    		// Other sessions to complete?
    		$remainingSessions = 0;
    			
    		foreach ($allSessions as $sess) {
    			/* @var $sess StudentHasClientHasCourseHasExam */
    			if ($sess->getCompleted() === 0) {
    				$remainingSessions++;
    			}
    		}
    		if (!$remainingSessions) {
    			// All sessions are completed
    			return $this->composeAnswer($session,true,"Tutte le sessioni sono state completate");
    		}
    			
    		foreach ($allSessions as $sess) {
    			if ($sess->getCompleted() === 0 && $sess->getStartDate() < new \DateTime()) {
   					// Segue session found to complete
   					if ($sess->getRealStartDate() == null) {
   						$sess->setRealStartDate(new \DateTime());
   						$this->getEntityManager()->persist($sess);
   						$this->getEntityManager()->flush();
   					}
   					return $this->composeAnswer($sess,false,"Questa sessione risulta completata. Trovata una sessione successiva da completare");
   				}
   			}
   			// No available session
   			return $this->composeAnswer($session,true,"Nessuna sessione attualmente disponibile");
    			
   		} else {
   			// Other sessions to complete "before" the current one?
   			foreach ($allSessions as $sess) {
   				/* @var $sess StudentHasClientHasCourseHasExam */
   				if ($sess->getCompleted() === 0 && $sess->getStartDate() < new \DateTime()) {
   					// Found previous session
   					if ($sess != $session) {
   						if ($sess->getRealStartDate() == null) {
   							$sess->setRealStartDate(new \DateTime());
   							$this->getEntityManager()->persist($sess);
   							$this->getEntityManager()->flush();
   						}
   						return $this->composeAnswer($sess,false,"Sessione precedente trovata da completare");
   					}
   					
   					if ($session->getRealStartDate() == null) {
   						$session->setRealStartDate(new \DateTime());
   						$this->getEntityManager()->persist($session);
   						$this->getEntityManager()->flush();
   					}
   					return $this->composeAnswer($session,false,'Sessione corrente trovata');
   				}
   			}
   			// No available session
   			return $this->composeAnswer($session,true,"Nessuna sessione attualmente disponibile");
   		}
    }
    
   	public function getAvailableChallenges($sessionId)
   	{
		$retval = array();
		/* @var $session Application\Entity\StudentHasClientHasCourseHasExam */
		$session = $this->getStudentHasClientHasCourseHasExamRepo()->find($sessionId);
                $listOfChallenges = $this->getStudentHasClientHasCourseHasExamRepo()->findChallengesByStudentOnCourse($session->getStudentHasClientHasCourse());
                //$listOfChallenges = $this->getStudentHasCourseHasExamRepo()->findChallengesByStudentOnCourse($session->getStudentHasCourse());
		if (count($listOfChallenges) > 0) {
			
			foreach ($listOfChallenges as $challenge)
			{
				/* @var $challenge StudentHasClientHasCourseHasExam */
				if ($challenge->getCompleted() === 0 && $challenge->getStartDate() < new \DateTime()) {
					
					// Load max possible points
					
					$arr = array(
							'id' => $challenge->getId(),
							'token' => $challenge->getStudentHasClientHasCourse()->getStudent()->getIdentifier().".".$challenge->getToken(),
							'name' => $challenge->getExam()->getName(),
							'questions' => count($challenge->getItem()),
							'maxpoints' => $this->getSessionMaxPoints($challenge)
					);
					$retval[] = $arr;
				}
			}
		}
		return $retval;
	}
}