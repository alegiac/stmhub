<?php

namespace Application\Entity\Repository;

use Application\Entity\StudentHasClientHasCourse;
use Application\Entity\StudentHasClientHasCourseHasExam;
use Doctrine\Common\Collections\Criteria;
use Application\Entity\Exam;

/**
 * StudentHasClientHasCourseHasExamRepo
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class StudentHasClientHasCourseHasExamRepo extends \Doctrine\ORM\EntityRepository
{
	
	public function getTimingForStudent(StudentHasClientHasCourse $studentInCourse)
	{
		$query = $this->getEntityManager()->getConnection()->prepare('SELECT SUM(TIMESTAMPDIFF(SECOND,real_start_date,end_date)) AS timing FROM student_has_client_has_course_has_exam WHERE student_has_client_has_course_id = '.$studentInCourse->getId().' GROUP BY student_has_client_has_course_id');
		$query->execute();
		return $query->fetch();
	}
	
	public function getAllByPoints()
	{
		$query = $this->getEntityManager()->getConnection()->prepare('SELECT SUM(points) AS tot,student_has_course_id, SUM(TIMESTAMPDIFF(SECOND,real_start_date,end_date)) AS timing FROM student_has_client_has_course_has_exam GROUP BY student_has_client_has_course_id ORDER BY tot DESC,timing ASC');
		$query->execute();
		return $query->fetchAll();
	}
	
	public function sumStudentPoints(StudentHasClientHasCourse $studentInCourse)
	{
		$qb = $this->createQueryBuilder("sum");
		$qb->add('select',"SUM(r.points)")
		->add('from', 'Application\Entity\StudentHasClientHasCourseHasExam r')
		->add('where', 'r.studentHasClientHasCourse = :shc');
		$query = $qb->getQuery()->setParameter('shc', $studentInCourse);
		return $query->getResult()[0][1];
	}
	
	public function countSessions(StudentHasClientHasCourse $studentInCourse)
	{
		$qb = $this->createQueryBuilder("count");
		$qb->add('select','COUNT(r.id)')
		->add('from','Application\Entity\StudentHasClientHasCourseHasExam r')
		->add('where','r.studentHasClientHasCourse = :shc')->andWhere('r.mandatory = 1');
		$query = $qb->getQuery()->setParameter('shc', $studentInCourse);
		
		return $query->getResult()[0][1];
	}
	
	/**
	 * Acquisizione unico record sessione per identificativo
	 *
	 * @param string $identifier Identificativo sessione
	 * @return StudentHasClientHasCourseHasExam
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
	public function findByExam(StudentHasClientHasCourse $session, Exam $exam)
	{
		$criteria = Criteria::create()->where(Criteria::expr()->eq('exam',$exam))->andWhere(Criteria::expr()->eq('studentHasClientHasCourse',$session));
		$result = $this->matching($criteria);		
		if ($result->count()) {
			
			return $result->getValues();
		}
		return array();
	}
	
	/**
	 * Load all the challenges for a given student
	 * 
	 * @param StudentHasClientHasCourse $studentCourse
	 * @return array
	 */
	public function findChallengesByStudentOnCourse(StudentHasClientHasCourse $studentCourse)
	{
		$criteria = Criteria::create()->where(Criteria::expr()->eq('studentHasClientHasCourse',$studentCourse))
			->andWhere(Criteria::expr()->eq('mandatory', 0));
		
		$result = $this->matching($criteria);
		if ($result->count()) return $result->getValues();
		return array();
	}
	/**
	 * Acquisizione di tutte le sessioni di esame per il corso indicato
	 * 
	 * @param StudentHasClientHasCourse $studentCourse
	 * @return array
	 */
	public function findByStudentOnCourse(StudentHasClientHasCourse $studentCourse,$onlyMandatory=true)
	{
		$criteria = Criteria::create()->where(Criteria::expr()->eq('studentHasClientHasCourse',$studentCourse));
		
		if ($onlyMandatory === true) {
			$criteria->andWhere(Criteria::expr()->eq('mandatory', 1));
		}
		
		$result = $this->matching($criteria);
		if ($result->count()) return $result->getValues();
		return array();
	}
}
