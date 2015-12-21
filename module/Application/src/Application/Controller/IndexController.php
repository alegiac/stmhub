<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;

class IndexController extends AbstractActionController
{
	/**
	 * Controller index action.
	 * Nothing really to do here, so we can redirect the user to the corporate site.
	 * {@inheritDoc}
	 * @see \Zend\Mvc\Controller\AbstractActionController::indexAction()
	 */
	public function indexAction()
	{
		$config = $this->getServiceLocator()->get("Config");
		return $this->redirect()->toUrl($config['corporateurl']);
	}
}
