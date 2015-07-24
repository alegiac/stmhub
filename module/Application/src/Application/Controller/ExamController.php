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
	/**
	 * Ingresso nella funzione di accesso all'esame.
	 * Questa action verifica la presenza del token utente (inviato via email).
	 * In caso di consistenza, verifica se un utente può partecipare all'esame:
	 * - se non c'è token, esce con un 404
	 * - se c'è token:
	 * 	-- esame da iniziare -> carica esame e via
	 *  -- esame già in corso (legge sessione e invalida eventuale altra) --> carica macchina a stati
	 *  -- esame finito ---> carica pagina di notifica esame completato (o 404)
	 *  
	 *  
	 * @see \Zend\Mvc\Controller\AbstractActionController::indexAction()
	 */
	public function indexAction() 
	{
		// 1 - Verifica presenza di token
		$stmt = $this->params('stmt');
		if (!isset($stmt)) {
			$this->redirect()->toUrl('http://www.smiletomove.it');
		}
		// 2 - Verifica stato token
		$tokenValid = $this->getServiceLocator()->get('ExamService')->;
		 
		// Analyze the action requested
		$action = $request->getParam('action');
	
		switch ($action) {
			case 'setup':
	
				// Create database (drop previous if any)
				$buildResult = $this->getServiceLocator()->get('BsbPhingService')->build('recreate-db',$options);
					
	
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
