<?php

namespace Application\Service;

use Application\Entity\Student;
use Application\Constants\ActivationStatus;
use Application\Entity\Course;
use Application\Entity\ClientHasCourse;
use Application\Entity\StudentHasClientHasCourse;
use Application\Entity\StudentHasClientHasCourseHasExam;
use Application\Entity\StudentHasCourse;
use Application\Entity\Exam;
use Application\Entity\StudentHasCourseHasExam;
use Application\Entity\Item;
use Application\Entity\ExamHasItem;

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
     * Esegue:
     * 
     * - registrazione nuovo studente
     * - associazione con cliente/corso
     * - roll delle sessioni
     * 
     * @param array $payload Dati studente/cliente/corso
     */
    public function registerUser(array $payload)
    {
        // Preimpostazioni
        $fakeSignup = true;
        $retval = array();
        
        // Transazione
        $this->getEntityManager()->beginTransaction();
        
        $payload['sex'] = 'N';
        $payload['passwordsha1'] = sha1('password_'.$payload['email']);
        
        /* @var $clientCourse \Application\Entity\ClientHasCourse */
        $clientCourse = $this->getClientHasCourseRepo()->find($payload['client_course']);
        
        // Prima cosa, verifica se lo studente esiste 
    	$student = $this->getStudentRepo()->findOneBy(array("email" => $payload['email']));
    	if (!$student) {
            
            $fakeSignup = false;
            
            // Creare utente in db
            $student = new \Application\Entity\Student();
        
            $student->setFirstname($payload['firstname']);
            $student->setLastname($payload['lastname']);
            $student->setEmail($payload['email']);
            $student->setPasswordsha1($payload['passwordsha1']);
            $student->setIdentifier($this->generateIdentifier());
            $student->setSex($payload['sex']);
            $student->setActivationstatus($this->getActivationStatusRecord(ActivationStatus::STATUS_ENABLED));
            
            $extrafields = array();
            if ($payload['internal'] != null) {
                $extrafields['internal'] = $payload['internal'];
            }
            if ($payload['role'] != null) {
                $extrafields['role'] = $payload['role'];
            }
            $student->setExtrafields(json_encode($extrafields));
            
            $this->getEntityManager()->persist($student);
            $this->getEntityManager()->flush();
        }
        
        // Studente in db, è associato al cliente?
        $studentClient = $this->getClientHasStudentRepo()->findByStudentAndClient($student, $clientCourse->getClient());
        if (is_null($studentClient)) {
            
            $fakeSignup = false;
            
            // Crea l'associazione studente-client
            $studentClient = new \Application\Entity\ClientHasStudent();
            $studentClient->setActivationstatus($this->getActivationStatusRecord(ActivationStatus::STATUS_ENABLED));
            $studentClient->setClient($clientCourse->getClient());
            $studentClient->setStudent($student);
            $studentClient->setInsertDate(new \DateTime());
                
            $this->getEntityManager()->persist($studentClient);
            $this->getEntityManager()->flush();
        }
        
        // Studente e cliente sono associati. E' associato al corso?
        $studentClientCourse = $this->getStudentHasClientHasCourseRepo()->findByStudentAndClientCourse($student,$clientCourse);
        if (!is_null($studentClientCourse)) {
            // Studente già assegnato al corso
            $fakeSignup = true;
        } 
        
        if ($fakeSignup === true) {
            $this->getEntityManager()->rollback();
            $retval['result'] = false;
            $retval['already_in'] = true;
            // Se tuttavia l'utente deve essere rediretto all'esame, rispondo true
            if ($clientCourse->getRedirectExam() === 1) {
                $retval['result'] = true;
                $retval['to_landing'] = false;
                $studentClientCourse = $this->getStudentHasClientHasCourseRepo()->findByStudentAndClientCourse($student,$clientCourse);
                $session = $this->getStudentHasClientHasCourseHasExamRepo()->findByStudentOnCourse($studentClientCourse, true, true);
                $retval['redirect'] = $_SERVER['HTTP_HOST']."/exam/token/".$student->getIdentifier().".".$session->getToken();
            }
            $retval['course_name'] = $clientCourse->getName();

            return $retval;
        }
        
        // Alla fine assegno lo studente e creo le sessioni
        $this->associateStudentToClientCourse($student, $clientCourse,true);
        
        $this->getEntityManager()->commit();
        
        $retval['result'] = true;
        $retval['course_name'] = $clientCourse->getName();
        if ($clientCourse->getRedirectExam() === 1) {
            $retval['to_landing'] = false;
            $studentClientCourse = $this->getStudentHasClientHasCourseRepo()->findByStudentAndClientCourse($student,$clientCourse);
            $session = $this->getStudentHasClientHasCourseHasExamRepo()->findByStudentOnCourse($studentClientCourse, true, true);
            $retval['redirect'] = $_SERVER['HTTP_HOST']."/exam/token/".$student->getIdentifier().".".$session->getToken();

        } else {
            $retval['to_landing'] = true;
        }
        
    	return $retval;
    	
    }

    public function associateAllStudentsToClientCourse(ClientHasCourse $clientCourse)
    {
    	$em = $this->getEntityManager();
	$connection = $em->getConnection();
		
		try {
			$connection->query('SET FOREIGN_KEY_CHECKS=0');
			$connection->query('DELETE FROM student_has_answered_to_item');
			$connection->query('DELETE FROM student_has_client_has_course_has_exam_has_item');
			$connection->query('DELETE FROM student_has_client_has_course_has_exam');
			$connection->query('DELETE FROM student_has_client_has_course');
			$connection->query('SET FOREIGN_KEY_CHECKS=1');
		} catch (\Exception $ex) {
			print_r($ex);die();

		}
		
		$studs = $this->getStudentRepo()->findAll();
		foreach ($studs as $stud) {
			$this->associateStudentToClientCourse($stud, $clientCourse);
		}
	}
	
	/**
	 * Associazione studente ad associazione cliente-corso
	 * @param Student $student
	 * @param Course $course
	 * @param Client $client
	 * @return type
	 */
	public function associateStudentToClientCourse(Student $student, ClientHasCourse $clientCourse, $fromSignup = false)
	{
            // Pre-impostazioni
            if (is_null($clientCourse->getStartDate()) || $fromSignup === true) {
                    $startDate = new \DateTimeImmutable();
            } else {
                    $sdformatted = $clientCourse->getStartDate()->format('Y-m-d H:i:s');
                    $startDate = \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $sdformatted);
            }

            // Creazione associazione studente - cliente/corso
            $studentHasClientCourse = new StudentHasClientHasCourse();
            $studentHasClientCourse->setStudent($student);
            $studentHasClientCourse->setClientHasCourse($clientCourse);
            $studentHasClientCourse->setInsertDate(new \DateTime());
            $studentHasClientCourse->setActivationstatus($this->getActivationStatusRecord(ActivationStatus::STATUS_ENABLED));

            $this->getEntityManager()->persist($studentHasClientCourse);
            $this->getEntityManager()->flush();

            // Acquisizione proprietà e definizione esami dal corso
            $durationWeek = $clientCourse->getCourse()->getDurationweek();
            $periodicityWeek = $clientCourse->getCourse()->getPeriodicityweek();

            // Calcolo data finale per esami e sfide
            $lastDateStart = $startDate;
            $lastDateEnd = $lastDateStart->add(new \DateInterval('P'.$periodicityWeek.'W'));
            $lastDatChallengeEnd = $lastDateStart->add(new \DateInterval('P'.$durationWeek.'W'));

            // *********************************************************************
            // Pianificazione sfide
            $challenges = $this->getExamRepo()->findNotMandatoriesByCourse($clientCourse->getCourse());
            $sessionsForChallenge = 1;

            foreach ($challenges as $challenge) {
                    /* @var $challenge Exam */
                    $itemsForChallenge = $this->getExamHasItemRepo()->findByExam($challenge);
                    shuffle($itemsForChallenge);
                    $numItemsForChallenge = count($itemsForChallenge);

                    // Creazione token univoco
                    $token = $this->generateSessionToken($student->getId(), $challenge->getId(),1);

                    // Creazione sessione per la sfida (unica sessione per ogni sfida)
                    $session = new StudentHasClientHasCourseHasExam();
                    $session->setCompleted(0);
                    $session->setExam($challenge);
                    $session->setMandatory(0);
                    $session->setExpectedEndDate($lastDatChallengeEnd);
                    $session->setInsertDate(new \DateTime());
                    $session->setPoints(0);
                    $session->setProgressive(0);
                    $session->setStartDate($lastDateStart);
                    $session->setStudentHasClientHasCourse($studentHasClientCourse);
                    $session->setToken($token);
                    $session->setSessionOnCourse(1);
                    $session->setSessionOnExam("1/1");

                    $this->getEntityManager()->persist($session);

                    $arrayItemsForSession = array();

                    for ($j=0;$j<$numItemsForChallenge;$j++) {
                            if (count($itemsForChallenge) == 0)break;

                            $theItem = array_pop($itemsForChallenge);
                            /* @var $theItem ExamHasItem */
                            if ($theItem->getItem()->getItem() != null) {
                                    $found = false;
                                    foreach ($session->getItem() as $itemIn) {
                                            /* @var $itemIn Item */
                                            if ($itemIn == $theItem->getItem()->getItem()) {
                                                    $found = true;break;
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

            // *********************************************************************
            // Pianificazione esami obbligatori
            $ss = 0;	

            $exams = $this->getExamRepo()->findMandatoriesByCourse($clientCourse->getCourse());
            $numOfSessions = ceil($durationWeek/$periodicityWeek);
            $totalItems = 0; $assignedReserved = 0; $totalAssignedSessions = 0; $arrSave= array();

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
                            $session = new StudentHasClientHasCourseHasExam();
                            $session->setCompleted(0);
                            $session->setExam($exam);
                            $session->setMandatory(1);
                            $session->setExpectedEndDate($lastDateEnd);
                            $session->setInsertDate(new \DateTime());
                            $session->setPoints(0);
                            $session->setProgressive(0);
                            $session->setStartDate($lastDateStart);
                            $session->setStudentHasClientHasCourse($studentHasClientCourse);
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
                }
            }
            $this->getEntityManager()->flush();
            return "Completato: studente ".$student->getFirstname()." ".$student->getLastname()." assegnato a corso ".$clientCourse->getCourse()->getName();
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
		//$session = $this->getStudentHasCourseHasExamRepo()->find($sessionId);
		$session = $this->getStudentHasClientHasCourseHasExamRepo()->find($sessionId);
		/* @var $session StudentHasClientHasCourseHasExam */
		
		// Get student info
		$student = $session->getStudentHasClientHasCourse()->getStudent();
		$course = $session->getStudentHasClientHasCourse()->getClientHasCourse()->getCourse();
		
		$firstname = $student->getFirstname();
		$lastname = $student->getLastname();
		//$email = $student->getEmail();
		$reference = $student->getIdentifier();
		$studentSessions = $this->getStudentHasClientHasCourseHasExamRepo()->findByStudentOnCourse($session->getStudentHasClientHasCourse(),false);
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
        
        public function migrateAnswers()
        {
            set_time_limit(0);
            $repo = $this->getStudentHasAnsweredToItemRepo();
            $answers = $repo->findAll();
            
            foreach ($answers as $answer) {
                /* @var $answer \Application\Entity\StudentHasAnsweredToItem */
                $newSession = $this->getStudentHasClientHasCourseHasExamRepo()->findByStudentCourse($answer->getStudentHasClientHasCourseHasExam());
                $answer->setStudentHasClientHasCourseHasExam($newSession);   
                $this->getEntityManager()->merge($answer);
            }
            $this->getEntityManager()->flush();
        }

        public function migrateSessions()
        {
            set_time_limit(0);
            
            $sessionRepo = $this->getStudentHasCourseHasExamRepo();
            
            $sessions = $sessionRepo->findAll();
            
            foreach ($sessions as $session) {

                /* @var $session StudentHasCourseHasExam */
                $studentHasCourse = $session->getStudentHasCourse();
            
                $studentClientCourse = $this->getStudentHasClientHasCourseRepo()->findByStudentCourse($studentHasCourse);
                
                
                $newSession = new StudentHasClientHasCourseHasExam();
                $newSession->setAnswer($session->getAnswer());
                $newSession->setCompleted($session->getCompleted());
                $newSession->setEndDate($session->getEndDate());
                $newSession->setExam($session->getExam());
                $newSession->setExpectedEndDate($session->getExpectedEndDate());
                $newSession->setInsertDate($session->getInsertDate());
                $newSession->setMandatory($session->getMandatory());
                $newSession->setNotifiedDate($session->getNotifiedDate());
                $newSession->setPoints($session->getPoints());
                $newSession->setProgressive($session->getProgressive());
                $newSession->setRealStartDate($session->getRealStartDate());
                $newSession->setSessionOnCourse($session->getSessionOnCourse());
                $newSession->setSessionOnExam($session->getSessionOnExam());
                $newSession->setStartDate($session->getStartDate());
                $newSession->setStudentHasCourseHasExam($session);
                $newSession->setStudentHasClientHasCourse($studentClientCourse);
                $newSession->setToken($session->getToken());
        
                $items = $session->getItem();
                foreach ($items as $item) {$newSession->addItem ($item);}
                
                
                $this->getEntityManager()->persist($newSession);
            }
            
            $this->getEntityManager()->flush();
            
            
        }
        
    public function migrateStudentCourse()
    {
        $this->getEntityManager()->beginTransaction();
        
        $studentCourses = $this->getStudentHasCourseRepo()->findAll();
        foreach ($studentCourses as $shc) {
            
            echo "<hr>processo studente-corso ".$shc->getId();
            
            /* @var $shc StudentHasCourse */
            $student = $shc->getStudent();
            $course = $shc->getCourse();
            
            echo "<br>trovato studente ".$student->getId();
            echo "<br>trovato corso ".$course->getId();
            
            $clientStudent = $this->getClientHasStudentRepo()->findByStudent($student); 
            /* @var $clientStudent \Application\Entity\ClientHasStudent */
            if ($clientStudent == null) {
                echo "CLIENT NOT FOUND FOR STUDENT-COURSE WITH ID ".$clientStudent->getId();
                $this->getEntityManager()->rollback();
                echo "<br>ABORT";
                die();
            }
            
            echo "<br>trovato cliente-studente ".$clientStudent->getId();
            if (is_array($clientStudent)) {
                $clientStudent = $clientStudent[0];
            }
            $client = $clientStudent->getClient();
            

            /* @var $client Client */
            $clientCourse = $this->getClientHasCourseRepo()->findByCourseAndClient($course, $client);
            if ($client == null) {
                echo " : trovato studente senza cliente ".$student->getId();
            } else {
                if ($clientCourse == null) {
                    
                    $weekday = $this->getWeekdayRepo()->find(1);
                    
                    // Trovato link orfano cliente-corso. Occorre creare l'associazione
                    $nchc = new ClientHasCourse();
                    $nchc->setActivationstatus($client->getActivationstatus());
                    $nchc->setClient($client);
                    $nchc->setCourse($course);
                    $nchc->setDescription("**** migration ****");
                    $nchc->setDurationweek(1);
                    $nchc->setInsertDate(new \DateTime('2015-12-21 00:00:00'));
                    $nchc->setLogoFilename('logo_smile.jpg');
                    $nchc->setName($course->getName());
                    $nchc->setPeriodicityweek(1);
                    $nchc->setStartDate(new \DateTime('2015-12-22 00:00:00'));
                    $nchc->setWeekday($weekday);
                    
                    $this->getEntityManager()->persist($nchc);
                    $this->getEntityManager()->flush();
                    
                    $clientCourse = $nchc;
                } 
            }
            
            // Nuovo record
            $shchc = new StudentHasClientHasCourse();
            $shchc->setStudent($student);
            $shchc->setActivationstatus($shc->getActivationstatus());
            $shchc->setClientHasCourse($clientCourse);
            $shchc->setInsertDate($shc->getInsertDate());
            $shchc->setStudentHasCourse($shc);
            
            $this->getEntityManager()->persist($shchc);
            $this->getEntityManager()->flush();
        }
        $this->getEntityManager()->commit();
    }

	/**
	 * The purpose of this function is to cycle for incoming sessions
	 * and send an email to each student involved, with the link to start the session 
	 */
	public function rollEmailForSessions()
	{
            $sessions = $this->getStudentHasClientHasCourseHasExamRepo()->findStartedNotNotified();
//		$sessions = $this->getStudentHasCourseHasExamRepo()->findStartedNotNotified();

		if ($sessions) {
		
			foreach($sessions as $session) {
				
				/* @var $session StudentHasClientHasCourseHasExam */
				
				// Get student info
				$student = $session->getStudentHasClientHasCourse()->getStudent();
				$course = $session->getStudentHasClientHasCourse()->getClientHasCourse()->getCourse();
				
				$firstname = $student->getFirstname();
				$lastname = $student->getLastname();
				$email = $student->getEmail();
				$reference = $student->getIdentifier();
				$studentSessions = $this->getStudentHasClientHasCourseHasExamRepo()->findByStudentOnCourse($session->getStudentHasCourse(),false);
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
