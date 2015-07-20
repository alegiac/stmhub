<?php
namespace Core\V1\Rpc\ConfigurationDump;

use Zend\Mvc\Controller\AbstractActionController;

class ConfigurationDumpController extends AbstractActionController
{
 	public function configurationDumpAction()
    {
    	$formatOutput = $this->params('format');
    	$config = $this->getServiceLocator()->get('Config');
    	switch ($formatOutput) {
    		case "json":
    			echo json_encode($config);
    			break;
    		case "array":
    			echo print_r($config,true);
    	}
    	exit();	 
    }
}
