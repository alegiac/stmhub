<?php

namespace Application\Service;

final class CourseService extends BaseService
{
	
	/**
	 * Acquisizione entity
	 * @param integer $id
	 * @return \Application\Entity\Course
	 */
	public function findById($id)
	{
		return $this->getCourseRepo()->find($id);
	}
	
	/**
	 * Acquisizione associazione cliente - corso
	 * @param integer $id
	 * @return \Application\Entity\ClientHasCourse
	 */
	public function findAssociation($id)
	{
		return $this->getClientHasCourseRepo()->find($id);
	}
}