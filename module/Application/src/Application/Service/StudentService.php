<?php

namespace Application\Service;

use Application\Entity\Student;
use Application\Constants\ActivationStatus;
use Application\Entity\Course;
use Application\Entity\StudentHasCourse;
use Application\Entity\Exam;
use Application\Entity\StudentHasCourseHasExam;
use Application\Entity\Item;
use Application\Entity\ExamHasItem;

final class StudentService extends BaseService
{
	/**
	 * Generate user identifier
	 * @return string
	 */
	private function generateIdentifier()
	{
		return rand(1111,9999).time();
	}
	
	/**
	 * Generate session token
	 * @return string
	 */
	private function generateSessionToken($studentId,$examId,$sessionNumber)
	{
		return sha1($sessionNumber.$examId.$studentId);
	}
	
	public function findById($id)
	{
		return $this->getStudentRepo()->find($id);
	}
	
	/**
     * Creating new student
     * @param array $payload Student properties
     */
    public function insertStudent(array $payload)
    {
    	$student = $this->getStudentRepo()->findBy(array("email" => $payload['email']));
    	if ($student) {
    		// Student is already in db
    		return false;
    	}
    	
    	$student = new Student();
    	$student->setFirstname($payload['firstname']);
    	$student->setLastname($payload['lastname']);
    	$student->setEmail($payload['email']);
    	$student->setPasswordsha1($payload['passwordsha1']);
    	$student->setIdentifier($this->generateIdentifier());
    	$student->setSex($payload['sex']);
    	$student->setActivationstatus($this->getActivationStatusRecord(ActivationStatus::STATUS_ENABLED));
    	
    	$this->getEntityManager()->persist($student);
    	$this->getEntityManager()->flush();
    }
    
    public function associateStudentToCourse(Student $student,Course $course,\DateTimeImmutable $startDate)
    {
	   	$this->getEntityManager()->beginTransaction();
    	
    	// Create association
    	$studentHasCourse = new StudentHasCourse();
    	$studentHasCourse->setStudent($student);
    	$studentHasCourse->setCourse($course);
    	$studentHasCourse->setInsertDate(new \DateTime());
    	$studentHasCourse->setActivationstatus($this->getActivationStatusRecord(ActivationStatus::STATUS_ENABLED));
    	
    	$this->getEntityManager()->persist($studentHasCourse);
    	$this->getEntityManager()->flush();
    	
    	// Gets the exam time definition from course
    	$durationWeek = $course->getDurationweek();
    	$periodicityWeek = $course->getPeriodicityweek();
    	
    	$lastDateStart = $startDate;
    	$lastDateEnd = $lastDateStart->add(new \DateInterval('P'.$periodicityWeek.'W'));
    	$lastDateChallangeEnd = $lastDateStart->add(new \DateInterval('P'.$durationWeek.'W'));
    	
    	// For challenge logic, only non-mandatory exams will be used
    	$challenges = $this->getExamRepo()->findNotMandatoriesByCourse($course);
    	$sessionsForChallenge = 1;
    	
    	foreach ($challenges as $challenge) {
    		/* @var $challenge Exam */
    		$itemsForChallenge = $this->getExamHasItemRepo()->findByExam($challenge);
    		shuffle(shuffle(shuffle($itemsForChallenge)));
    		$numItemsForChallenge = count($itemsForChallenge);
    		
    		// Create the universal token
    		$token = $this->generateSessionToken($student->getId(), $challenge->getId(), 1);
    		
    		// Create an entry in the session table
    		$session = new StudentHasCourseHasExam();
    		$session->setCompleted(0);
    		$session->setExam($challenge);
    		$session->setMandatory(0);
    		$session->setExpectedEndDate($lastDateChallangeEnd);
    		$session->setInsertDate(new \DateTime());
    		$session->setPoints(0);
    		$session->setProgressive(0);
    		$session->setStartDate($lastDateStart);
    		$session->setStudentHasCourse($studentHasCourse);
    		$session->setToken($token);
    		
    		$arrayItemsForSession = array();
    		 
    		for($j=0;$j<$numItemsForChallenge;$j++) {
    			if (count($itemsForChallenge) == 0) break;
    			 
    			// Pop an item from the global-exam-items array
    			// Check if it has a parent dependency
    			$theItem = array_pop($itemsForChallenge);
    			echo "count ".count($itemsForChallenge)."<br>";
    			echo "ciclo ".$j." - tolto elemento da array ".count($itemsForChallenge)."<br>";
    		
    			/* @var $theItem ExamHasItem */
    			if ($theItem->getItem()->getItem() != null) {
    				echo "item trovato <br>";
    				$found = false;
    				foreach ($session->getItem() as $itemIn) {
    					/* @var $itemIn Item */
    					echo "in ciclo sub";
    					if ($itemIn == $theItem->getItem()->getItem()) {
    						$found = true; break;
    					}
    				}
    				if (!$found) {
    					// Has parent dependency but his parent is not in.
    					array_push($itemsForExam, $theItem);
    					shuffle($itemsForExam);
    				}
    			}
    		
    			$session->addItem($theItem->getItem());
    		}
    		
    		$this->getEntityManager()->persist($session);
    		$this->getEntityManager()->flush();
    	}
    	
    	// For session logic, only mandatory exams will be used 
    	$exams = $this->getExamRepo()->findMandatoriesByCourse($course);
    	$sessionsForExam = $durationWeek/count($exams);
    	
    	$lastDateStart = $startDate;
    	$lastDateEnd = $lastDateStart->add(new \DateInterval('P'.$periodicityWeek.'W'));
    	
    	foreach ($exams as $exam) {
    		
    		/* @var $exam Exam */
    		// All the items connected to an exam. Pick up and randomize
    		$itemsForExam = $this->getExamHasItemRepo()->findByExam($exam);
    		shuffle(shuffle(shuffle($itemsForExam)));
    		
    		$numItemsForSession = ceil(count($itemsForExam)/$sessionsForExam);
    		$upto = count($itemsForExam);
    		
    		for($i=0;$i<$sessionsForExam;$i++) {
    			
    			// Create an universal token
    			$token = $this->generateSessionToken($student->getId(), $exam->getId(), $i);
    			
    			// Create an entry in the session-for-the-student table
    			$session = new StudentHasCourseHasExam();
    			$session->setCompleted(0);
    			$session->setExam($exam);
    			$session->setMandatory(1);
	    		$session->setExpectedEndDate($lastDateEnd);
	    		$session->setInsertDate(new \DateTime());
	    		$session->setPoints(0);
	    		$session->setProgressive(0);
	    		$session->setStartDate($lastDateStart);
	    		$session->setStudentHasCourse($studentHasCourse);
	    		$session->setToken($token);
	    		
	    		
	    		
	    		// Extend dates
	    		$next = new \DateInterval('P'.$periodicityWeek.'W');
	    		$lastDateStart = $lastDateStart->add($next);
	    		$lastDateEnd = $lastDateEnd->add($next);
	    		
	    		$arrayItemsForSession = array();
	    		
	    		for($j=0;$j<$numItemsForSession;$j++) {
	    			if (count($itemsForExam) == 0) break;
	    				
	    			// Pop an item from the global-exam-items array
	    			// Check if it has a parent dependency
	    			$theItem = array_pop($itemsForExam);
	    			echo "count ".count($itemsForExam)."<br>";
	    			echo "ciclo ".$j." - tolto elemento da array ".count($itemsForExam)."<br>";
	    			
	    			/* @var $theItem ExamHasItem */
	    			if ($theItem->getItem()->getItem() != null) {
	    				echo "item trovato <br>";
	    				$found = false;
	    				foreach ($session->getItem() as $itemIn) {
	    					/* @var $itemIn Item */
	    					echo "in ciclo sub";
	    					if ($itemIn == $theItem->getItem()->getItem()) {
	    						$found = true; break;
	    					}
	    				}
	    				if (!$found) {
	    					// Has parent dependency but his parent is not in.
	    					array_push($itemsForExam, $theItem);
	    					shuffle($itemsForExam);
	    				}
	    			}
	    			
	    			$session->addItem($theItem->getItem());
	    		}
	    			
	    		$this->getEntityManager()->persist($session);
	    		$this->getEntityManager()->flush();
	    	}
    	}
    	$this->getEntityManager()->commit();
    	
    	return "Completed";
	}  
}