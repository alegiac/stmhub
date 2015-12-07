<?php

namespace Application\Entity\Repository;

use Application\Entity\Course;
use Doctrine\Common\Collections\Criteria;

/**
 * ExamRepo
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ExamRepo extends \Doctrine\ORM\EntityRepository
{
	/**
	 * Acquisizione di tutti gli esami per un corso
	 *
	 * @param Course $course
	 * @return array
	 */
	public function findByCourse(Course $course)
	{
		$criteria = Criteria::create()->where(Criteria::expr()->eq('course',$course))->orderBy(array('progOnCourse' => 'ASC'));
		
		$result = $this->matching($criteria);
		if ($result->count()) return $result->getValues();
		return array();
	}
	
	/**
	 * Get all non-mandatory exams (challenges)
	 * @param Course $course
	 * @return array
	 */
	public function findNotMandatoriesByCourse(Course $course)
	{
		$criteria = Criteria::create()->where(Criteria::expr()->eq('course',$course))->andWhere(Criteria::expr()->eq('mandatory',0))->orderBy(array('progOnCourse' => 'ASC'));
		
		$result = $this->matching($criteria);
		if ($result->count()) return $result->getValues();
		return array();
	}
	
	/**
	 * Acquisizione di tutti gli esami obbligatori (da sessione) per un corso
	 *
	 * @param Course $course
	 * @return array
	 */
	public function findMandatoriesByCourse(Course $course)
	{
		$criteria = Criteria::create()->where(Criteria::expr()->eq('course',$course))->andWhere(Criteria::expr()->eq('mandatory',1))->orderBy(array('progOnCourse' => 'ASC'));
	
		$result = $this->matching($criteria);
		if ($result->count()) return $result->getValues();
		return array();
	}
}