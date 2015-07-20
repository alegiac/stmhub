<?php

namespace Platform\V1\Rpc\GetPageByName;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Helper\ViewModel;
use Platform\Service\NavigationServiceTrait;

class GetPageByNameController extends AbstractActionController
{
	use NavigationServiceTrait;
	
    public function getPageByNameAction()
    {
    	echo json_encode($this->getNavigationService()->getPages($this->params('code')));
    	exit();
    }
}