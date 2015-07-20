<?php
namespace Platform\V1\Rpc\GetWidgetByName;

use Zend\Mvc\Controller\AbstractActionController;
use Platform\Service\NavigationServiceTrait;

class GetWidgetByNameController extends AbstractActionController
{
	use NavigationServiceTrait;
	
    public function getWidgetByNameAction()
    {
    	echo json_encode($this->getNavigationService()->getWidgets($this->params('code')));
    	exit;
    }
}
