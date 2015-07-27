<?php

namespace Core\Service;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\ServiceManager;
use Core\Constants\Setup;

final class SetupService implements ServiceLocatorAwareInterface
{
	
    use ServiceLocatorAwareTrait;
    
    /**
     * Class constructor
     * @param ServiceLocatorInterface $serviceLocator
     */
    public function __construct(ServiceLocatorInterface $serviceLocator)
    {

    	$this->serviceLocator = $serviceLocator;
    }
    
    /**
     * Format output for console environment
     * @param string $message 
     */
    private function formatConsoleOutput($message)
    {
    	return str_replace("<br>","\n",$message);
    }
    
    /**
     * Load database options
     * 
     * @param string $moduleName Name of the module
     * @return array
     */
    private function loadDbOptions($moduleName)
    {
    	$config = $this->getServiceLocator()->get('Config');
    	$dbpars = $config['doctrine']['connection']['orm_default']['params'];
    	$dbhost = $dbpars['host'];
    	$dbport = $dbpars['port'];
    	$dbuser = $dbpars['user'];
    	$dbpass = $dbpars['password'];
    	$dbname = $dbpars['dbname'];

    	if (!file_exists(__DIR__ . '/../../../../' . ucfirst($moduleName))) {
    		throw new \FileNotFoundException(sprintf("Module \"%s\" not found",ucfirst($moduleName)));
    	}
    	 
    	if (!file_exists(__DIR__ . '/../../../../' . ucfirst($moduleName) . '/db/')) {
    		throw new \FileNotFoundException(sprintf("Folder \"%s\" not found, it's needed for loading database-related stuff",ucfirst($moduleName) . "/db"));
    	}
    	if (!file_exists(__DIR__ . '/../../../../' . ucfirst($moduleName) . '/db/setup.xml')) {
    		throw new \FileNotFoundException(sprintf("Phing file \"%s\" not found, it's needed for loading database-related stuff",ucfirst($moduleName) . "/db/setup.xml"));
    	}
    	
    	return array('buildFile' => __DIR__.'/../../../../' . ucfirst($moduleName) . '/db/setup.xml',
    			'properties' => array(
    					'basePath' => __DIR__."/../../../../" . ucfirst($moduleName) . "/db/",
    					'db.project.host' => $dbhost,
    					'db.project.dbname' => $dbname,
    					'db.project.username' => $dbuser,
    					'db.project.password' => $dbpass,
    					'moduleName' => ucfirst($moduleName),
    					'applicationPath' => APPLICATION_PATH
    			)
    	);
    }
    
    /**
     * Drop if exists, and create the database from the sql dump
     * 
     * @param string $moduleName Name of the module
     * @return boolean State of execution
     */
    private function createDatabase($moduleName)
    {
    	try {
    		$options = $this->loadDbOptions($moduleName);
    	
	    	// Create / rebuild database service
    		$buildResult = $this->getServiceLocator()->get('BsbPhingService')->build('recreate-db',$options);
    	 
    		if ($buildResult->getExitCode() > 0) {
    			echo "[Error] - ".$this->formatConsoleOutput($buildResult->getErrorOutput()) . "\n\n";
    			return false;
    		} else {
    			return true;
    		}
    	} catch (\FileNotFoundException $e) {
    		echo "[Error] - ".$this->formatConsoleOutput($e->getMessage())."\n\n";
    		return false;
    	}
    }
    
    /**
     * Load all the setup scripts into the database
     * 
     * @param string $moduleName Name of the module
     * @return boolean State of execution
     */
    private function loadSeeds($moduleName)
    {
    	try {
			$options = $this->loadDbOptions($moduleName);
		
			// Insert seeds in db
			$buildResult = $this->getServiceLocator()->get('BsbPhingService')->build('seed-db',$options);
			
			if ($buildResult->getExitCode() > 0) {
				echo "[Error] - ".$this->formatConsoleOutput($buildResult->getErrorOutput()) . "\n\n";
				return false;
			} else {
				return true;
			}
		} catch (\FileNotFoundException $e) {
			echo "[Error] - ".$this->formatConsoleOutput($e->getMessage())."\n\n";
			return false;
		}
    }
    
    /**
     * Load all the fixtures/test data into the database
     *
     * @param string $moduleName Name of the module
     * @return boolean State of execution
     */
    private function loadTestFixtures($moduleName)
    {
    	try {
    		$options = $this->loadDbOptions($moduleName);
    
    		// Insert seeds in db
    		$buildResult = $this->getServiceLocator()->get('BsbPhingService')->build('test-fixtures',$options);
    			
    		if ($buildResult->getExitCode() > 0) {
    			echo "[Error] - ".$this->formatConsoleOutput($buildResult->getErrorOutput()) . "\n\n";
    			return false;
    		} else {
    			return true;
    		}
    	} catch (\FileNotFoundException $e) {
    		echo "[Error] - ".$this->formatConsoleOutput($e->getMessage())."\n\n";
    		return false;
    	}
    }
    
    /**
     * Generate entities in the specified module
     * 
     * @param string $moduleName Name of the module
     * @return boolean State of execution
     */
    private function generateEntities($moduleName)
    {
    	
    	try {
	    	$options = $this->loadDbOptions($moduleName);
	    	
	    	$buildResult = $this->getServiceLocator()->get('BsbPhingService')->build('generate-doctrine-entities',$options);
	    	
	    	if ($buildResult->getExitCode() > 0) {
	    		echo "[Error] - ".$this->formatConsoleOutput($buildResult->getErrorOutput()) . "\n\n";
	    		return false;
	    	} else {
	    		echo "[Info] - ".$this->formatConsoleOutput($buildResult->getOutput()).'\n\n';
	    		$hh = scandir( __DIR__.'/../../../../' . ucfirst($moduleName) . '/src/' . ucfirst($moduleName) . '/Entity');
	    		foreach ($hh as $k=>$entry) {
	    			if ($entry == "." || $entry == "..") {
	    			} else {
	    				$reponame = $entry;
	    				$reponame = str_replace(".php", "Repo", $reponame);
	    				 
	    				$contents = file_get_contents(__DIR__.'/../../../../' . ucfirst($moduleName) . '/src/' . ucfirst($moduleName) . '/Entity/'.$entry);
	    				$contents = str_replace('@ORM\Entity','@ORM\Entity(repositoryClass="'.ucfirst($moduleName).'\Entity\Repository\\'.$reponame.'")',$contents);
	    				file_put_contents(__DIR__.'/../../../../' . ucfirst($moduleName) . '/src/' . ucfirst($moduleName) . '/Entity/'.$entry,$contents);
	    			}
	    		}
	    		
				return true;
	    	}
    	} catch (\FileNotFoundException $e) {
    		echo "[Error] - ".$this->formatConsoleOutput($e->getMessage())."\n\n";
    		return false;
    	}
    }
    
    /**
     * Generate repositories in the specified module
     * 
     * @param string $moduleName Name of the module
     * @return boolean State of execution
     */
    private function generateRepositories($moduleName)
    {
    	try {
    		$options = $this->loadDbOptions($moduleName);
    	
    		$buildResult = $this->getServiceLocator()->get('BsbPhingService')->build('generate-doctrine-repos',$options);
    		if ($buildResult->getExitCode() > 0) {
    			 echo "[Error] - ".$this->formatConsoleOutput($buildResult->getErrorOutput()) . "\n\n";
    			 return false;
    		} else {
    			echo "[Info] - ".$this->formatConsoleOutput($buildResult->getOutput()) . "\n\n";
    			return true;
			}
    	} catch (\FileNotFoundException $e) {
    		echo "[Error] - ".$this->formatConsoleOutput($e->getMessage())."\n\n";
    		return false;
    	}
    }
    
    /**
     * Execute the setup process based on module and action required
     * 
     * @param string $moduleName Name of the module that will 
     * 
     */
    public function execute($moduleName,$action)
    {
    	switch ($action) {
    		case Setup::ACTION_DATABASE:
    			return $this->createDatabase($moduleName);
    			break;
    		case Setup::ACTION_SEED:
    			return $this->loadSeeds($moduleName);
    			break;
    		case Setup::ACTION_FIXTURES:
    			return $this->loadTestFixtures($moduleName);
    			break;
    		case Setup::ACTION_ENTITIES;
    			return $this->generateEntities($moduleName);
    			break;
    		case Setup::ACTION_REPOSITORIES:
    			return $this->generateRepositories($moduleName);
    			break;
    		default:
    			echo "[Error] - Action " . $action . " is not set\n\n";
    			return false;
    	}
    } 
}