<?php
namespace Core\V1\Rpc\Heartbeat;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Helper\ViewModel;

class HeartbeatController extends AbstractActionController
{
	public function heartbeatAction()
    {
    	echo (time());
    	exit;
    }
}
