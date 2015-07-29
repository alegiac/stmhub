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
use Zend\Session\Container;

class ExamController extends AbstractActionController
{
	/**
	 * Oggetto sessione exam
	 * 
	 * @var Container
	 */
	private $session;
	
	/**
	 * Ingresso nella funzione di accesso all'esame.
	 * Questa action verifica la presenza del token utente (inviato via email).
	 * In caso di consistenza, verifica se un utente puo' partecipare all'esame:
	 * - se non c'e' token o non si verificano le condizioni di partecipazione, carica una pagina di errore
	 * - altrimenti invia la richiesta utente al quesito
	 *  
	 * @return void	
	 * @see \Zend\Mvc\Controller\AbstractActionController::indexAction()
	 */
	public function indexAction() 
	{
		$this->init();
		
		// 1 - Verifica presenza di token
		$stmt = $this->params('tkn');
		
		try {
			// 2 - Verifica stato token
			$res = $this->getExamService()->getExamSessionIdByToken($stmt);
			
			// Salvataggio token in sessione
			$this->session->student = $res['student'];
			
			$this->session->examId = $res['id'];
			$this->session->progress = $res['progress'];
			$this->session->message = $res['message'];
			
			// 3- Verifica risultato: inviare a pagina di stop o prosegue?
			if (strlen($res['message']) > 0 && is_null($res['id'])) {
				$this->redirect()->toRoute('exam_error');
			} else {
				$this->redirect()->toRoute('exam_participate');
			}
		} catch (\Exception $e) {
			// 4 - Gestione eccezioni
			$this->getServiceLocator()->get("Logger")->err("Exception loading exam/student data. Message: ".$e->getMessage());
			$this->getServiceLocator()->get("Logger")->info("Stack Trace: ".$e->getTraceAsString());
			$this->session->message = "Impossibile accedere alla sessione di esame per inconsistenza dei dati.";
			$this->redirect()->toRoute('exam_error');
		}
	}
	
	/**
	 * Visualizzazione pagina di errore da inviare all'utente finale in caso di errore, eccezione o problema
	 * che in generale sia bloccante per l'esecuzione del task
	 * 
	 * @return void
	 * @see \Zend\Mvc\Controller\AbstractActionController
	 */
	public function errorAction()
	{
		
	}
	
	/**
	 * Visualizzazione pagina di partecipazione al concorso: acquisisce lo step attuale, il relativo item 
	 * e lo visualizza per ottenere la risposta dall'utente.
	 * 
	 * @return void
	 * @see \Zend\Mvc\Controller\AbstractActionController
	 */
	public function participateAction()
	{
		
		$this->init();
		
		// Acquisizione di tutti gli elementi di esame
		$examStudentId = $this->session->examId;
		
		try {
			$data = $this->getExamService()->getUserExamData($examStudentId);
			
			// Visualizzazione 
		
		
			$vm = new ViewModel();
			$vm->firstName = $this->session->student['firstname'];
			$vm->lastName = $this->session->student['lastname'];
		
			if (strlen($this->session->message) > 0) {
				$vm->enableMessage = true;
				$vm->message = $this->session->message;
			} else {
				$vm->enableMessage = false;
				$vm->message = "";
			}
		
			return $vm;
		} catch (\Exception $e) {
			
		}
	}
	
	protected function init()
	{
		$this->session = new Container('exam');
	}
	
	/**
	 * @return Application\Service\ExamService
	 */
	private function getExamService()
	{
		return $this->getServiceLocator()->get('ExamService');
	}
}
