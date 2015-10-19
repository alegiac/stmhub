<?php

namespace Application\Service;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\ServiceManager;

use Application\Entity\Repository\StudentHasCourseRepo;
use Application\Entity\Student;
use Application\Entity\StudentHasCourse;

final class CourseService extends BaseService
{
	
	public function findById($id)
	{
		return $this->getCourseRepo()->find($id);
	}
}