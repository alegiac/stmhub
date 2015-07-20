<?php
namespace Platform\V1\Rpc\GetWidgets;

use Zend\Mvc\Controller\AbstractActionController;
use Platform\Service\NavigationServiceTrait;

class GetWidgetsController extends AbstractActionController
{
	use NavigationServiceTrait;
	
    public function getWidgetsAction()
    {
    	echo json_encode($this->getNavigationService()->getWidgets());
    	exit();
    }
}