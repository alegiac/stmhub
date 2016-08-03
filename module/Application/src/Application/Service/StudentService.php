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

use Zend\Mail\Message;
use Zend\Mime\Message as MimeMessage;
use Zend\Mime\Part as MimePart;
use Application\Entity\Client;
use Mandrill;


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
    
	public function associateAllStudentsToCourse(Course $course,\DateTimeImmutable $startDate)
	{
		$em = $this->getEntityManager();
		$connection = $em->getConnection();
		
		try {
			$connection->query('SET FOREIGN_KEY_CHECKS=0');
			$connection->query('DELETE FROM student_has_answered_to_item');
			$connection->query('DELETE FROM student_has_course_has_exam_has_item');
			$connection->query('DELETE FROM student_has_course_has_exam');
			$connection->query('DELETE FROM student_has_course');
			$connection->query('SET FOREIGN_KEY_CHECKS=1');
		} catch (\Exception $e) {
			print_r($e);die();
		}
		
		$studs = $this->getStudentRepo()->findAll();
		foreach ($studs as $stud) {
			$this->associateStudentToCourse($stud, $course, $startDate);
		}
	}
	
	public function associateStudentToCourse(Student $student,Course $course,Client $client)
	{	
		// Determine date start
		$clientCourse = $this->getClientHasCourseRepo()->findByCourseAndClient($course, $client);
		if (is_null($clientCourse->getStartDate())) {
			$startDate = new \DateTimeImmutable();
		} else {
			$sdformatted = $clientCourse->getStartDate()->format('Y-m-d H:i:s');
			$startDate = \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $sdformatted);
		}
		
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
			shuffle($itemsForChallenge);
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
		
			$this->getEntityManager()->persist($session);
			
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
			
			$this->getEntityManager()->flush();
			$this->getEntityManager()->detach($session);
		}
		$ss = 0;
		
		// For session logic, only mandatory exams will be used
		
		
		if (is_null($clientCourse->getStartDate())) {
			$startDate = new \DateTimeImmutable();
		} else {
			$sdformatted = $clientCourse->getStartDate()->format('Y-m-d H:i:s');
			$startDate = \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $sdformatted);
		}
		
		$exams = $this->getExamRepo()->findMandatoriesByCourse($course);
		$numOfSessions = ceil($durationWeek/$periodicityWeek);
		
		$totalItems = 0;
		$assignedReserved = 0;
		$totalAssignedSessions = 0;
		$arrSave = array();
		
		// Calculate the total item number and initialize the reserved sessions array
		foreach ($exams as $exam) {
			$totalItems += $exam->getTotalitems();
			$arrSave[$exam->getId()] = 0;
		}

		$avgItemNumberPerSession = ceil($totalItems/$numOfSessions);

		// Reserve a session for each short exam
		foreach ($exams as $exam) {
			if ($exam->getTotalitems() <= $avgItemNumberPerSession + 5) {
				$arrSave[$exam->getid()] = 1;
				$assignedReserved = $assignedReserved + 1;
				$totalAssignedSessions = $totalAssignedSessions + 1;
			}
		}
		
		$remainingSessions = $numOfSessions - $assignedReserved;
		$baseForExam = floor($remainingSessions/(count($exams)-$assignedReserved));
		
		// Assign the minimum sessions for each non-reserved exam
		foreach($exams as $exam) {
			if ($arrSave[$exam->getId()] == 0) {
				$arrSave[$exam->getId()] = $baseForExam;
				$totalAssignedSessions += $baseForExam;
			}
		}
		
		// Now, remaining session should be 0 or a value <= to the non-reserved exam number
		$remainingSessions = $numOfSessions - $totalAssignedSessions;
		
		while ($remainingSessions > 0) {
			foreach ($arrSave as $k=>$value) {
				if ($value != 1 && $remainingSessions > 0) {
					$arrSave[$k] = $value + 1;
					$remainingSessions--;
				}
			}
		}
		
		// Determined the final number of sessions for each exam, cycle for creating the records
		$lastDateStart = clone($startDate);
		$lastDateEnd = $lastDateStart->add(new \DateInterval('P'.$periodicityWeek.'W'));
		
		$currentSession = 0;
		
		foreach ($exams as $exam) {
			
			$sessionsForExam = $arrSave[$exam->getId()];
			
			// All the items connected to an exam. Pick up and randomize
			$itemsForExam = $this->getExamHasItemRepo()->findByExam($exam);
			shuffle($itemsForExam);
		
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
		
		return "Completato: studente ".$student->getFirstname()." ".$student->getLastname()." assegnato a corso ".$course->getName();
	}
	
	/**
	 * The purpose of this function is to send a test email to the given email
	 * address, leaving intact the notification value
	 * 
	 * @param integer $sessionId
	 * @param string $email
	 */
	public function rollEmailForSessionAndEmail($sessionId,$email)
	{
		$session = $this->getStudentHasCourseHasExamRepo()->find($sessionId);
		/* @var $session StudentHasCourseHasExam */
		
		// Get student info
		$student = $session->getStudentHasCourse()->getStudent();
		$course = $session->getStudentHasCourse()->getCourse();
		
		$firstname = $student->getFirstname();
		$lastname = $student->getLastname();
		//$email = $student->getEmail();
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
		$apikey = $cfg['smtp_password'];
		$from = $cfg['from'];
		$subject = $cfg['subject'];
		$bcc = $cfg['bcc'];
		
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
		
	        $mandrill = new Mandrill($apikey);
                $message = array(
                    'html' => $template,
                    'text' => 'Train to Action',
                    'subject' => $subject,
                    'from_name' => 'TrainToAction',
                    'from_email' => $from,
                    'to' => array(
                        array(
                            'email' => $email,
                            'type' => 'to'
                        )
                    ),
                    'bcc_address' => $bcc,
                );
                
                $result = $mandrill->messages->send($message,false);
                print_r($result);
		//$session->setNotifiedDate(new \DateTime());
		//$this->getEntityManager()->persist($session);
		//$this->getEntityManager()->flush();
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
				$apikey = $cfg['smtp_password'];
                                
				$from = $cfg['from'];
				$subject = $cfg['subject'];
				$bcc = $cfg['bcc'];
			
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
				
                                $mandrill = new Mandrill($apikey);
                                $message = array(
                                    'html' => $template,
                                    'text' => 'Train to Action',
                                    'subject' => $subject,
                                    'from_name' => 'TrainToAction',
                                    'from_email' => $from,
                                    'to' => array(
                                        array(
                                            'email' => $email,
                                            'type' => 'to'
                                        )
                                    ),
                                    'bcc_address' => $bcc,
                                );
				

				$result = $mandrill->messages->send($message,false);
				$session->setNotifiedDate(new \DateTime());
				$this->getEntityManager()->persist($session);
				$this->getEntityManager()->flush();
				
			}
		}
	}
} 
