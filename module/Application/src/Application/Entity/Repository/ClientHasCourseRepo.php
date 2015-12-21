<?php

namespace Application\Entity\Repository;

use Application\Entity\Course;
/**
 * ClientHasCourseRepo
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ClientHasCourseRepo extends \Doctrine\ORM\EntityRepository
{
	public function findByCourse(Course $course)
	{
		$result = $this->createQueryBuilder('p')
		->select('p')
		->getQuery()
		->getResult();
		
		return $result;
		
		
		$q = $this->createQueryBuilder('cc')->select('cc')->where('course = :course')->getQuery();
		$q->setParameter('course', $course);
		
		return $q->getResult();
		
	}
}
