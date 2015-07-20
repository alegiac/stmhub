<?php
namespace Platform\V1\Rpc\GetMenuItems;

use Zend\Mvc\Controller\AbstractActionController;
use Platform\Service\NavigationServiceTrait;
use Zend\View\Helper\ViewModel;

class GetMenuItemsController extends AbstractActionController
{
	use NavigationServiceTrait;
	
    public function getMenuItemsAction()
    {
    	echo json_encode($this->getNavigationService()->getMenus());
    	exit;
    }
}
