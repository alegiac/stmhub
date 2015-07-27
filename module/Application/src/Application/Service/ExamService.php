<?php

namespace Application\Service;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\ServiceManager;

use Application\Constants\ActivationStatus;

use Application\Entity\Student;
use Application\Entity\StudentHasCourseHasExam;

use Application\Entity\Repository\StudentHasCourseHasExamRepo;
use Application\Entity\Repository\StudentRepo;

use Core\Exception\MalformedRequest;
use Core\Exception\ObjectNotFound;
use Core\Exception\ObjectNotEnabled;
use Core\Exception\InconsistentContent;
use Zend\Crypt\BlockCipher;
use Zend\Crypt\Symmetric\Mcrypt;

final class ExamService implements ServiceLocatorAwareInterface
{	
    use ServiceLocatorAwareTrait;
    
    /**
     * Costruttore di classe
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
     * Acquisizione dell'id reale di sessione d'esame da far eseguire
     * allo studente che accede al link. 
     *  
     * La funzione verifica:
     * 1) esistenza token utente e di sessione-esame
     * 2) il corretto intervallo temporale di applicazione
     * 3) eventuali sessioni precedenti non completate/iniziate
     * 
     * @param string $token Full request token 
     */
    public function getExamSessionIdByToken($token)
    {
    	// Validazione campi richiesta
    	if (strpos($token, ".") === false) throw new MalformedRequest("Token di richiesta non formattato correttamente");
    	
    	list($studentToken,$sessionToken) = explode(".",$token);
    	if (strlen($studentToken) == 0) throw new MalformedRequest("Token richiesta non valido: subtoken studente non inserito");
    	if (strlen($sessionToken) == 0) throw new MalformedRequest("Token richiesta non valido: subtoken sessione-esame non inserito");

    	// Acquisizione studente
    	$student = $this->getStudentRepo()->findByIdentifier($studentToken);
    	
    	if (is_null($student)) throw new ObjectNotFound(sprintf("Nessuno studente trovato con identificativo %s",$studentToken));
    	if ($student->getActivationstatus()->getId() != ActivationStatus::STATUS_ENABLED) throw new ObjectNotEnabled(sprintf("Studente con identificativo %s trovato ma non abilitato",$studentToken));

    	// Acquisizione sessione di esame
    	$session = $this->getStudentHasCourseHasExamRepo()->findByIdentifier($sessionToken);
    	if (!$session) throw new ObjectNotFound(sprintf("Nessuna sessione d'esame trovata con identificativo %s",$sessionToken));

    	// Controllo incrociato studente/sessione
    	if ($session->getStudentHasCourse()->getStudent() != $student)
    		throw new InconsistentContent(sprintf("Sessione con token %s valida ma non assegnata all'account studente con identificativo %s. Probabile tentativo di hacking",$sessionToken,$studentToken));
    	
    	// Da qui il processo di validazione e' completato, non ritorna piu exception
    	$course = $session->getStudentHasCourse()->getCourse();
    	if ($course->getActivationstatus() != ActivationStatus::STATUS_ENABLED) {
    		// Corso disabilitato
    		return array('id' => null,'message' => 'Corso disabilitato');
    	}
    	
    	// Tutti gli esami dello studente
    	$studentCourse = $session->getStudentHasCourse();
    	
    	$allSessions = $this->getStudentHasCourseHasExamRepo()->findByStudentOnCourse($studentCourse);
    	
    	// Sessione corrente gia' completata?
    	if ($session->getCompleted()) {
			foreach ($allSessions as $sess) {
    			/* @var $sess StudentHasCourseHasExam */
    			if ($sess->getCompleted() === 0 && $sess->getStartDate() < new \DateTime()) {
    				// Trovata sessione successiva
    				return array('id' => $sess->getEx(),'message' => 'Sessione successiva iniziata e non completata');
    			} 
    		} 
    		return array('id' => null,'message' => 'Tutte le sessioni sono terminate');
    	} else {
    		foreach ($allSessions as $sess) {
    			/* @var $sess StudentHasCourseHasExam */
    			if ($sess->getCompleted() === 0 && $sess->getStartDate() < new \DateTime()) {
    				// Trovata sessione successiva
    				if ($sess == $session) return array('id' => $session->getId(),'message' => null); 
    				return array('id' => $sess->getId(),'message' => 'Sessione precedente iniziata e non completata');
    			}
    		}
    		return array('id' => null,'message' => 'Nessuna sessione ancora disponibile');
    	}
	} 
}