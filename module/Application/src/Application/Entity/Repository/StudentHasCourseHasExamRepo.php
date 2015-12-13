<?php

namespace Application\Entity\Repository;

use Application\Entity\StudentHasCourse;
use Application\Entity\StudentHasCourseHasExam;
use Doctrine\Common\Collections\Criteria;
use Application\Entity\Exam;
use Doctrine\ORM\QueryBuilder;

/**
 * StudentHasCourseHasExamRepo
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class StudentHasCourseHasExamRepo extends \Doctrine\ORM\EntityRepository
{
	
	public function sumStudentPoints(StudentHasCourse $studentInCourse)
	{
		$qb = $this->createQueryBuilder("sum");
		$qb->add('select',"SUM(r.points)")
		->add('from', 'Application\Entity\StudentHasCourseHasExam r')
		->add('where', 'r.studentHasCourse = :shc');
		$query = $qb->getQuery()->setParameter('shc', $studentInCourse);
		return $query->getResult()[0][1];
	}
	
	public function countSessions(StudentHasCourse $studentInCourse)
	{
		$qb = $this->createQueryBuilder("count");
		$qb->add('select','COUNT(r.id)')
		->add('from','Application\Entity\StudentHasCourseHasExam r')
		->add('where','r.studentHasCourse = :shc')->andWhere('r.mandatory = 1');
		$query = $qb->getQuery()->setParameter('shc', $studentInCourse);
		
		return $query->getResult()[0][1];
	}
	
	/**
	 * Acquisizione unico record sessione per identificativo
	 *
	 * @param string $identifier Identificativo sessione
	 * @return StudentHasCourseHasExam
	 */
	public function findByIdentifier($identifier)
	{
		$criteria = Criteria::create()
			->where(Criteria::expr()->eq('token', $identifier))
			->orderBy(array('id'=>'ASC'));
		$result = $this->matching($criteria);
		if ($result->count()) return $result->last();
		return null;
	}
	
	/**
	 * Get all started sessions for notification
	 * 
	 * @return array
	 */
	public function findStartedNotNotified()
	{
		$crit = Criteria::create()->where(Criteria::expr()->isNull('notifiedDate'))
		->andWhere(Criteria::expr()->eq('mandatory', 1))
		->andWhere(Criteria::expr()->eq('completed',0))
		->andWhere(Criteria::expr()->lte('startDate',new \DateTime()));
		$result = $this->matching($crit);
		if ($result->count()) return $result->getValues();
		return array();
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
	 * Load all the challenges for a given student
	 * 
	 * @param StudentHasCourse $studentCourse
	 * @return array
	 */
	public function findChallengesByStudentOnCourse(StudentHasCourse $studentCourse)
	{
		$criteria = Criteria::create()->where(Criteria::expr()->eq('studentHasCourse',$studentCourse))
			->andWhere(Criteria::expr()->eq('mandatory', 0));
		
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
	public function findByStudentOnCourse(StudentHasCourse $studentCourse,$onlyMandatory=true)
	{
		$criteria = Criteria::create()->where(Criteria::expr()->eq('studentHasCourse',$studentCourse));
		
		if ($onlyMandatory === true) {
			$criteria->andWhere(Criteria::expr()->eq('mandatory', 1));
		}
		
		$result = $this->matching($criteria);
		if ($result->count()) return $result->getValues();
		return array();
	}
}
