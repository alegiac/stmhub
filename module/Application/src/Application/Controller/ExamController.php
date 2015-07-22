<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Console\Request as ConsoleRequest;

class ExamController extends AbstractActionController
{
	public function indexAction() 
	{
		$token = $this->params('kns');
		if (is_null($token)) {
			return $this->redirect()->toRoute('http://www.venetobanca.it');
		}
		
		
		
		
		// 1 - Verifica presenza di token
		// 1.1 ->> Se c'è token, ok
		// 1.2 ->> Non c'è token --> attacco
		// 2 - Acquisizione stato attuale di gestibilità
	}
	
	public function setupdbAction()
	{
		$request = $this->getRequest();
		
		if (!$request instanceof ConsoleRequest) {
			throw new \RuntimeException('You can only use this action from a console!');
		}
		
		$config = $this->getServiceLocator()->get('Config');
	
		$dbpars = $config['doctrine']['connection']['orm_default']['params'];
		$dbhost = $dbpars['host'];
		$dbport = $dbpars['port'];
		$dbuser = $dbpars['user'];
		$dbpass = $dbpars['password'];
		$dbname = $dbpars['dbname'];
		 
		$moduleName = "Platform";
		 
		$options = array('buildFile' => __DIR__.'/../../../Database/setup.xml',
				'properties' => array(
						'basePath' => __DIR__."/../../../Database/",
						'db.project.host' => $dbhost.":".$dbport,
						'db.project.dbname' => $dbname,
						'db.project.username' => $dbuser,
						'db.project.password' => $dbpass,
						'moduleName' => $moduleName,
						'applicationPath' => APPLICATION_PATH
				)
		);
		 
		/* @var $service BsbPhingService */
		$service = $this->getServiceLocator()->get('BsbPhingService');
		 
		// Analyze the action requested
		$action = $request->getParam('action');
	
		switch ($action) {
			case 'setup':
	
				// Create database (drop previous if any)
				$buildResult = $this->getServiceLocator()->get('BsbPhingService')->build('recreate-db',$options);
					
				if ($buildResult->getExitCode() > 0) {
					echo $buildResult->getCommandline();
					return $buildResult->getErrorOutput();
				} else {
	
					// Create doctrine entities
					$buildResult2 = $this->getServiceLocator()->get('BsbPhingService')->build('generate-doctrine-entities',$options);
	
					if ($buildResult2->getExitCode() > 0) {
						echo $buildResult2->getCommandline();
						return $buildResult2->getErrorOutput();
					} else {
							
						$hh = scandir(__DIR__."/../../../Entity");
							
						foreach ($hh as $k=>$entry)
						{
							if ($entry == "." || $entry == "..") continue;
							$reponame = str_replace(".php", "Repo", $entry);
							$contents = str_replace('@ORM\Entity','@ORM\Entity(repositoryClass="'.$moduleName.'\Entity\Repository\\'.$repoName.'")',$contents);
							file_put_contents(__DIR__."/../../../Entity/".$entry,$contents);
						}
	
						// Create doctrine repositories
						$buildResult3 = $this->getServiceLocator()->get('BsbPhingService')->build('generate-doctrine-repos',$options);
							
						if ($buildResult3->getExitCode() > 0) {
							echo $buildResult3->getCommandline();
							echo $buildResult3->getErrorOutput();
						} else {
							echo $buildResult3->getOutput();
						}
					}
				}
				break;
			case 'seed':
				// Seeds initial data
				$buildResult = $this->getServiceLocator()->get('BsbPhingService')->build('insert-seeding',$options);
					
				if ($buildResult->getExitCode() > 0) {
					echo $buildResult->getCommandline();
					echo $buildResult->getErrorOutput();
				} else {
					echo $buildResult->getOutput();
				}
				break;
			default:
				echo sprintf("action %s not found",$action);
		}
		exit;
	}
}
