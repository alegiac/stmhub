<?php

namespace Application\Service;

final class ClientService extends BaseService
{
	
	public function findById($id)
	{
		return $this->getClientRepo()->find($id);
	}
}