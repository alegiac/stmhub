<?php
namespace Platform\V1\Rpc\GetPages;

use Zend\Mvc\Controller\AbstractActionController;
use Platform\Service\NavigationServiceTrait;

class GetPagesController extends AbstractActionController
{
	use NavigationServiceTrait;
	
    public function getPagesAction()
    {
    	echo json_encode($this->getNavigationService()->getPages());
    	exit();
    }
}