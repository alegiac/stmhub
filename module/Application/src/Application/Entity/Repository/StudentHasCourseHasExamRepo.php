<?php

namespace Application\Entity\Repository;

use Application\Entity\StudentHasCourse;
use Application\Entity\StudentHasCourseHasExam;
use Doctrine\Common\Collections\Criteria;
use Application\Entity\Exam;

/**
 * StudentHasCourseHasExamRepo
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class StudentHasCourseHasExamRepo extends \Doctrine\ORM\EntityRepository
{
	/**
	 * Acquisizione unico record sessione per identificativo
	 *
	 * @param string $identifier Identificativo sessione
	 * @return StudentHasCourseHasExam
	 */
	public function findByIdentifier($identifier)
	{
		$criteria = Criteria::create()->where(Criteria::expr()->eq('token', $identifier))->orderBy(array('id'=>'ASC'));
		$result = $this->matching($criteria);
		if ($result->count()) return $result->last();
		return null;
	}
	
	/**
	 * Load all the sessions for a given exam
	 * 
	 * @param Exam $exam
	 * @return array
	 */
	public function findByExam(Exam $exam)
	{
		$criteria = Criteria::create()->where(Criteria::expr()->eq('exam',$exam));
		$result = $this->matching($criteria);
		if ($result->count()) return $result->getValues();
		return array();
	}
	/**
	 * Acquisizione di tutte le sessioni di esame per il corso indicato
	 * 
	 * @param StudentHasCourse $studentCourse
	 * @return array
	 */
	public function findByStudentOnCourse(StudentHasCourse $studentCourse)
	{
		$criteria = Criteria::create()->where(Criteria::expr()->eq('studentHasCourse',$studentCourse));
		$result = $this->matching($criteria);
		if ($result->count()) return $result->getValues();
		return array();
	}
}
