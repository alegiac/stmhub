<?php

namespace Platform\Entity\Repository;

use Doctrine\ORM\EntityRepository;

class PageRepository extends EntityRepository
{	
	/**
	 * @param string $code Page code
	 */
	public function findByCode($code)
	{
		$result = $this->createQueryBuilder('p')
					->select('p')
					->where('p.code = :code')
					->setParameter('code', $code)
					->getQuery()
					->getResult();
		count($result) == 1 ? $retval = $result[0] : $retval = null;
		return $retval;
	}	
}