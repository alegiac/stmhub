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
	 * In caso di consistenza, verifica se un utente puo' partecipare all'esame:
	 * - se non c'e' token, esce con un 404
	 * - se c'e' token:
	 * 	-- esame da iniziare -> carica esame e via
	 *  -- esame gia' in corso (legge sessione e invalida eventuale altra) --> carica macchina a stati
	 *  -- esame finito ---> carica pagina di notifica esame completato (o 404)
	 *  
	 *  
	 * @see \Zend\Mvc\Controller\AbstractActionController::indexAction()
	 */
	public function indexAction() 
	{
		$cfg = $this->getServiceLocator()->get('Config');
		
		// 1 - Verifica presenza di token
		$stmt = $this->params('tkn');
		
		if (!isset($stmt)) {
			$this->redirect()->toUrl($cfg['corporateurl']);
		}
		
		try {
			// 2 - Verifica stato token
			print_r($this->getExamService()->getExamSessionIdByToken($stmt));
			die();
		} catch (\Exception $e) {
			echo $e->getMessage();die();
		}
	}
	
	/**
	 * Visualizzazione pagina di errore da inviare all'utente finale in caso di errore, eccezione o problema
	 * che in generale sia bloccante per l'esecuzione del task
	 * 
	 * @
	 */
	/**
	 * @return Application\Service\ExamService
	 */
	private function getExamService()
	{
		return $this->getServiceLocator()->get('ExamService');
	}
}
