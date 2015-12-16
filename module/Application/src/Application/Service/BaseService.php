<?php

namespace Application\Service;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\ServiceManager;

use Application\Entity\Repository\StudentHasCourseRepo;
use Application\Entity\Student;
use Application\Entity\StudentHasCourse;
use Application\Entity\Repository\ActivationstatusRepo;
use Application\Entity\Activationstatus;
use Application\Entity\Repository\ExamRepo;
use Application\Entity\Repository\ExamHasItemRepo;
use Application\Entity\Repository\CourseRepo;
use Application\Entity\Repository\StudentHasCourseHasExamRepo;
use Application\Entity\Repository\StudentRepo;
use Application\Entity\Repository\PrizeRepo;
use Application\Entity\Repository\ClientHasCourseRepo;

abstract class BaseService implements ServiceLocatorAwareInterface
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
     * Gets repository for activationstatus table
     * @return ActivationstatusRepo
     */
    protected function getActivationstatusRepo()
    {
    	return $this->getEntityManager()->getRepository('Application\Entity\Activationstatus');
    }
    
    /**
     * Acquisizione repository student
     * @return StudentRepo
     */
    protected function getStudentRepo()
    {
    	return $this->getEntityManager()->getRepository('Application\Entity\Student');
    }
    
    /**
     * Acquisizione repository course
     * @return CourseRepo
     */
    protected function getCourseRepo()
    {
    	return $this->getEntityManager()->getRepository('Application\Entity\Course');
    }
    /**
     * Acquisizione repository student_has_course_has_exam
     * @return StudentHasCourseHasExamRepo
     */
    protected function getStudentHasCourseHasExamRepo()
    {
    	return $this->getEntityManager()->getRepository('Application\Entity\StudentHasCourseHasExam');
    }
    
    /**
     * Get repository for student_has_course
     * @return StudentHasCourseRepo
     */
    protected function getStudentHasCourseRepo()
    {
    	return $this->getEntityManager()->getRepository('Application\Entity\StudentHasCourse');
    }
    
    /**
     * Get repository for prize 
     * @return PrizeRepo
     */
    protected function getPrizeRepo()
    {
    	return $this->getEntityManager()->getRepository('Application\Entity\Prize');
    }
    
    /**
     * Get repository for client_has_course
     * @return ClientHasCourseRepo
     */
    protected function getClientHasCourseRepo()
    {
    	return $this->getEntityManager()->getRepository('Application\Entity\ClientHasCourse');
    }
    
    /**
     * Acquisizione repository exam_has_item
     * @return ExamHasItemRepo
     */
    protected function getExamHasItemRepo()
    {
    	return $this->getEntityManager()->getRepository('Application\Entity\ExamHasItem');
    }
     
    /**
     * Acquisizione repository student_has_answered_to_item
     * @return StudentHasAnsweredToItemRepo
     */
    protected function getStudentHasAnsweredToItemRepo()
    {
    	return $this->getEntityManager()->getRepository('Application\Entity\StudentHasAnsweredToItem');
    }
    
    /**
     * Acquisizione repository exam
     * @return ExamRepo
     */
    protected function getExamRepo()
    {
    	return $this->getEntityManager()->getRepository('Application\Entity\Exam');
    }
    
    /**
     * Acquisizione repository item
     * @return ItemRepo
     */
    protected function getItemRepo()
    {
    	return $this->getEntityManager()->getRepository('Application\Entity\Item');
    }
    
    /**
     * Acquisizione repository itemoption
     * @return ItemoptionRepo
     */
    protected function getItemoptionRepo()
    {
    	return $this->getEntityManager()->getRepository('Application\Entity\Itemoption');
    }
    
    /**
     * Get an activationStatus record for the selected id
     * @param int $id
     * @return Activationstatus
     */
    protected function getActivationStatusRecord($id)
    {
    	return $this->getActivationstatusRepo()->find($id);
    }         
}