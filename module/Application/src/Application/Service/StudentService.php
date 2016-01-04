<?php

namespace Application\Service;

use Application\Entity\Student;
use Application\Constants\ActivationStatus;
use Application\Entity\Course;
use Application\Entity\StudentHasCourse;
use Application\Entity\Exam;
use Application\Entity\StudentHasCourseHasExam;
use Application\Entity\StudentHasAnsweredToItem;
use Application\Entity\Item;
use Application\Entity\ExamHasItem;

use Zend\Mail\Message;
use Zend\Mime\Message as MimeMessage;
use Zend\Mime\Part as MimePart;

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
    		$session->setSessionOnCourse(1);
    		$session->setSessionOnExam("1/1");
    		
    		$arrayItemsForSession = array();
    		 
    		for($j=0;$j<$numItemsForChallenge;$j++) {
    			if (count($itemsForChallenge) == 0) break;
    			 
    			// Pop an item from the global-exam-items array
    			// Check if it has a parent dependency
    			$theItem = array_pop($itemsForChallenge);
    			
    			/* @var $theItem ExamHasItem */
    			if ($theItem->getItem()->getItem() != null) {
    				$found = false;
    				foreach ($session->getItem() as $itemIn) {
    					/* @var $itemIn Item */
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
    	$ss = 0;
    	
    	// For session logic, only mandatory exams will be used 
    	$exams = $this->getExamRepo()->findMandatoriesByCourse($course);
    	$sessionsForExam = $durationWeek/count($exams);
    	
    	$lastDateStart = $startDate;
    	$lastDateEnd = $lastDateStart->add(new \DateInterval('P'.$periodicityWeek.'W'));
    	
    	$currentSession = 0;
    	
    	foreach ($exams as $exam) {
    		
    		/* @var $exam Exam */
    		// All the items connected to an exam. Pick up and randomize
    		$itemsForExam = $this->getExamHasItemRepo()->findByExam($exam);
    		shuffle(shuffle(shuffle($itemsForExam)));
    		
    		$numItemsForSession = ceil(count($itemsForExam)/$sessionsForExam);
    		$upto = count($itemsForExam);
    		
    		for($i=0;$i<$sessionsForExam;$i++) {
    			$currentSession += 1;
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
	    		$session->setSessionOnCourse($currentSession);
	    		$session->setSessionOnExam(($i+1)."/".$sessionsForExam);
	    		
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
	    			
	    			/* @var $theItem ExamHasItem */
	    			if ($theItem->getItem()->getItem() != null) {
	    				$found = false;
	    				foreach ($session->getItem() as $itemIn) {
	    					/* @var $itemIn Item */
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
    	
    	return "Completato: studente ".$student->getFirstname()." ".$student->getLastname()." assegnato a corso ".$course->getName();
	}  
	
	public function newAssociateAllStudentsToCourse(Course $course,\DateTimeImmutable $startDate,$deleteAll)
	{
		$studs = $this->getStudentRepo()->findAll();
		foreach ($studs as $stud) {
			$this->newAssociateStudentToCourse($stud, $course, $startDate);
		}
	}
	
	public function newAssociateStudentToCourse(Student $student,Course $course,\DateTimeImmutable $startDate)
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
			$session->setSessionOnCourse(1);
			$session->setSessionOnExam("1/1");
	
			$arrayItemsForSession = array();
			 
			for($j=0;$j<$numItemsForChallenge;$j++) {
				if (count($itemsForChallenge) == 0) break;
	
				// Pop an item from the global-exam-items array
				// Check if it has a parent dependency
				$theItem = array_pop($itemsForChallenge);
				 
				/* @var $theItem ExamHasItem */
				if ($theItem->getItem()->getItem() != null) {
					$found = false;
					foreach ($session->getItem() as $itemIn) {
						/* @var $itemIn Item */
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
		$ss = 0;
		 
		// For session logic, only mandatory exams will be used
		$examsItemArray = array();
		$countItems = 0;
		$exams = $this->getExamRepo()->findMandatoriesByCourse($course);
		
		// Loading the total number of items, and creating an array for all the exams (items per exam)
		for ($i=0;$i<count($exams);$i++) {
			$countItems += $exams[$i]->getTotalitems();
			$examsItemArray[$i] = $this->getExamHasItemRepo()->findByExam($exams[$i]);
			shuffle(shuffle(shuffle($examsItemArray[$i])));
		}
		
		// Session number = duration/periodicity
		$sessionNumber = ceil($durationWeek/$periodicityWeek);
		
		// Average item number per session = total items / sessions
		$avgItemNumberPerSession = ceil($countItems/$sessionNumber);
		
		$lastDateStart = $startDate;
		$lastDateEnd = $lastDateStart->add(new \DateInterval('P'.$periodicityWeek.'W'));
		$currentSession = 0;
		
		for ($i=1;$i<=$sessionNumber;$i++) {
			
			for ($ii=0;$ii<count($exams);$ii++) {
				// No more items for the current exam.
				$exam = $exams[$ii];
				
				if (count($examsItemArray[$ii]) === 0) {
					continue;
				} else {
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
					$session->setSessionOnCourse($i);
					$session->setSessionOnExam(($i)."/".$sessionNumber);
					 
					// Extend dates
					$next = new \DateInterval('P'.$periodicityWeek.'W');
					$lastDateStart = $lastDateStart->add($next);
					$lastDateEnd = $lastDateEnd->add($next);
					
					$arrayItemsForSession = array();
					
					if (count($examsItemArray[$ii]) < $avgItemNumberPerSession) {
						$top = count($examsItemArray[$ii]); 
					} else {
						$top = $avgItemNumberPerSession;
					}
					for ($j=0;$j<$top;$j++) {
						// Pop an item from the global-exam-items array
						// Check if it has a parent dependency
						$theItem = array_pop($examsItemArray[$ii]);
					
						/* @var $theItem ExamHasItem */
						if ($theItem->getItem()->getItem() != null) {
							$found = false;
							foreach ($session->getItem() as $itemIn) {
								/* @var $itemIn Item */
								if ($itemIn == $theItem->getItem()->getItem()) {
									$found = true; break;
								}
							}
							if (!$found) {
								// Has parent dependency but his parent is not in.
								array_push($examsItemArray[$ii], $theItem);
								shuffle($examsItemArray[$ii]);
							}
						}
					
						$session->addItem($theItem->getItem());
					}
					if (count($examsItemArray[$ii]) < 13) {
						$newtop = count($examsItemArray[$ii]);
					} else {
						$newtop = 0;
					}
					for ($j=0;$j<$newtop;$j++) {
						// Pop an item from the global-exam-items array
						// Check if it has a parent dependency
						$theItem = array_pop($examsItemArray[$ii]);
							
						/* @var $theItem ExamHasItem */
						if ($theItem->getItem()->getItem() != null) {
							$found = false;
							foreach ($session->getItem() as $itemIn) {
								/* @var $itemIn Item */
								if ($itemIn == $theItem->getItem()->getItem()) {
									$found = true; break;
								}
							}
							if (!$found) {
								// Has parent dependency but his parent is not in.
								array_push($examsItemArray[$ii], $theItem);
								shuffle($examsItemArray[$ii]);
							}
						}
							
						$session->addItem($theItem->getItem());
					}
						
					
					
					$this->getEntityManager()->persist($session);
					$this->getEntityManager()->flush();
				}
				break;
			}
		}
		
		$this->getEntityManager()->commit();
		 
		return "Completato: studente ".$student->getFirstname()." ".$student->getLastname()." assegnato a corso ".$course->getName();
	}
	
	public function verynewAssociateStudentToCourse(Student $student,Course $course,\DateTimeImmutable $startDate)
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
			$session->setSessionOnCourse(1);
			$session->setSessionOnExam("1/1");
	
			$arrayItemsForSession = array();
	
			for($j=0;$j<$numItemsForChallenge;$j++) {
				if (count($itemsForChallenge) == 0) break;
	
				// Pop an item from the global-exam-items array
				// Check if it has a parent dependency
				$theItem = array_pop($itemsForChallenge);
					
				/* @var $theItem ExamHasItem */
				if ($theItem->getItem()->getItem() != null) {
					$found = false;
					foreach ($session->getItem() as $itemIn) {
						/* @var $itemIn Item */
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
		$ss = 0;
			
		// For session logic, only mandatory exams will be used
		$exams = $this->getExamRepo()->findMandatoriesByCourse($course);
		$numOfSessions = ceil($durationWeek/$periodicityWeek);
		$totalItems = 0;
		$assignedReserved = 0;
		$totalAssignedSessions = 0;
		$arrSave = array();
		
		foreach ($exams as $exam) {
			$totalItems += $exam->getTotalitems();
			$arrSave[$exam->getId()] = 0;
		}
		
		$avgItemNumberPerSession = ceil($$totalItems/$numOfSessions);
		
		foreach ($exams as $exam) {
			if (($exam->getTotalitems() <= $avgItemNumberPerSession) || ($exam->getTotalitems() > $avgItemNumberPerSession + 5)) {
				$arrSave[$exam->getid()] = 1;
				$assignedReserved++;
				$totalAssignedSessions++;
			}
		}
		
		$remainingSessions = $numOfSessions - $assignedReserved;
		$baseForExam = floor($remainingSessions/(count($items)-$assignedReserved));
		
		foreach($exams as $exam) {
			if ($arrSave[$exam->getId()] == 0) {
				$arrSave[$exam->getId()] = $baseForExam;
				$totalAssignedSessions += $baseForExam;
			}
		}
		
		$remainingSessionsToDistribute = $numOfSessions - $totalAssignedSessions;
		
		
		while ($remainingSessionsToDistribute > 0) {
		
			foreach ($arrSave as $value) {
				if ($value != 1 && $remainingSessionsToDistribute > 0) {
					$value++;
					$remainingSessionsToDistribute--;
				}
			}
			
		}
		
		
		$examsItemArray = array();
		$countItems = 0;
		$exams = $this->getExamRepo()->findMandatoriesByCourse($course);
	
		// Loading the total number of items, and creating an array for all the exams (items per exam)
		for ($i=0;$i<count($exams);$i++) {
			$countItems += $exams[$i]->getTotalitems();
			$examsItemArray[$i] = $this->getExamHasItemRepo()->findByExam($exams[$i]);
			shuffle(shuffle(shuffle($examsItemArray[$i])));
		}
	
		// Session number = duration/periodicity
		$sessionNumber = ceil($durationWeek/$periodicityWeek);
	
		// Average item number per session = total items / sessions
		$avgItemNumberPerSession = ceil($countItems/$sessionNumber);
	
		$lastDateStart = $startDate;
		$lastDateEnd = $lastDateStart->add(new \DateInterval('P'.$periodicityWeek.'W'));
		$currentSession = 0;
	
		for ($i=1;$i<=$sessionNumber;$i++) {
				
			for ($ii=0;$ii<count($exams);$ii++) {
				// No more items for the current exam.
				$exam = $exams[$ii];
	
				if (count($examsItemArray[$ii]) === 0) {
					continue;
				} else {
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
					$session->setSessionOnCourse($i);
					$session->setSessionOnExam(($i)."/".$sessionNumber);
	
					// Extend dates
					$next = new \DateInterval('P'.$periodicityWeek.'W');
					$lastDateStart = $lastDateStart->add($next);
					$lastDateEnd = $lastDateEnd->add($next);
						
					$arrayItemsForSession = array();
						
					if (count($examsItemArray[$ii]) < $avgItemNumberPerSession) {
						$top = count($examsItemArray[$ii]);
					} else {
						$top = $avgItemNumberPerSession;
					}
					for ($j=0;$j<$top;$j++) {
						// Pop an item from the global-exam-items array
						// Check if it has a parent dependency
						$theItem = array_pop($examsItemArray[$ii]);
							
						/* @var $theItem ExamHasItem */
						if ($theItem->getItem()->getItem() != null) {
							$found = false;
							foreach ($session->getItem() as $itemIn) {
								/* @var $itemIn Item */
								if ($itemIn == $theItem->getItem()->getItem()) {
									$found = true; break;
								}
							}
							if (!$found) {
								// Has parent dependency but his parent is not in.
								array_push($examsItemArray[$ii], $theItem);
								shuffle($examsItemArray[$ii]);
							}
						}
							
						$session->addItem($theItem->getItem());
					}
					if (count($examsItemArray[$ii]) < 13) {
						$newtop = count($examsItemArray[$ii]);
					} else {
						$newtop = 0;
					}
					for ($j=0;$j<$newtop;$j++) {
						// Pop an item from the global-exam-items array
						// Check if it has a parent dependency
						$theItem = array_pop($examsItemArray[$ii]);
							
						/* @var $theItem ExamHasItem */
						if ($theItem->getItem()->getItem() != null) {
							$found = false;
							foreach ($session->getItem() as $itemIn) {
								/* @var $itemIn Item */
								if ($itemIn == $theItem->getItem()->getItem()) {
									$found = true; break;
								}
							}
							if (!$found) {
								// Has parent dependency but his parent is not in.
								array_push($examsItemArray[$ii], $theItem);
								shuffle($examsItemArray[$ii]);
							}
						}
							
						$session->addItem($theItem->getItem());
					}
	
						
						
					$this->getEntityManager()->persist($session);
					$this->getEntityManager()->flush();
				}
				break;
			}
		}
	
		$this->getEntityManager()->commit();
			
		return "Completato: studente ".$student->getFirstname()." ".$student->getLastname()." assegnato a corso ".$course->getName();
	}
	
	/**
	 * The purpose of this function is to cycle for incoming sessions
	 * and send an email to each student involved, with the link to start the session 
	 */
	public function rollEmailForSessions()
	{
		$sessions = $this->getStudentHasCourseHasExamRepo()->findStartedNotNotified();
		if ($sessions) {
		
			foreach($sessions as $session) {
				
				/* @var $session StudentHasCourseHasExam */
				
				// Get student info
				$student = $session->getStudentHasCourse()->getStudent();
				$course = $session->getStudentHasCourse()->getCourse();
				
				$firstname = $student->getFirstname();
				$lastname = $student->getLastname();
				$email = $student->getEmail();
				$reference = $student->getIdentifier();
				$studentSessions = $this->getStudentHasCourseHasExamRepo()->findByStudentOnCourse($session->getStudentHasCourse(),false);
				$points = 0;
				foreach ($studentSessions as $ssession) {
					$points += $ssession->getPoints();
				}
				
				// Get session/exam/course info
				$coursename = $course->getName();
				$coursetotexams = $course->getTotalexams();
				$examName = $session->getExam()->getName();
				$examProg = $session->getExam()->getProgOnCourse();
				$sessionEnd = $session->getExpectedEndDate()->format('d M');
				
				// Get config params
				$cfg = $this->getServiceLocator()->get("Config")['app_output']['email'];
				$smtpServer = $cfg['smtp_server'];
				$smtpUser = $cfg['smtp_username'];
				$smtpPassword = $cfg['smtp_password'];
				$from = $cfg['from'];
				$subject = $cfg['subject'];
				$bccs = $cfg['bccs'];
				
				// Compose session link
				$link = $_SERVER['HTTP_HOST']."/exam/token/".$reference.".".$session->getToken();
				
				// Load template 
				$template = file_get_contents($course->getEmailtemplateurl());
				$template = str_replace('%%FIRSTNAME%%', $firstname, $template);
				$template = str_replace('%%LASTNAME%%', $lastname, $template);
				$template = str_replace('%%COURSENAME%%', $coursename, $template);
				$template = str_replace('%%EXAMNAME%%', $examName, $template);
				$template = str_replace('%%PROG%%', $examProg, $template);
				$template = str_replace('%%TOT%%', $coursetotexams, $template);
				$template = str_replace('%%LINK%%', $link, $template);
				$template = str_replace('%%POINTS%%', $points, $template);
				$template = str_replace('%%DUEDATE%%', $sessionEnd, $template);
				
				$html = new MimePart($template);
				$html->type = "text/html";
				
				$body = new MimeMessage();
				$body->setParts(array($html));
				
				$message = new Message();
				$message->setBody($body);
				$message->setSender($from,"SmileToMove");
				$message->addTo('alessandro.giacomella@gmail.com');
				$message->setBcc($bccs);
				$message->setSubject($subject);
				
				$smtpOptions = new \Zend\Mail\Transport\SmtpOptions();
				$smtpOptions->setHost($smtpServer)
				->setPort('465')
				->setConnectionClass('login')
				->setName("smiletomove.it")
				->setConnectionConfig(array(
						'username' => $smtpUser,
						'password' => $smtpPassword,
						'ssl' => 'ssl',
				));
				
				$transport = new \Zend\Mail\Transport\Smtp($smtpOptions);
				//$transport = new \Zend\Mail\Transport\Sendmail();
				$transport->send($message);
				
				$session->setNotifiedDate(new \DateTime());
				$this->getEntityManager()->persist($session);
				$this->getEntityManager()->flush();
				
			}
		}
	}
}