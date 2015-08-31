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
use Zend\Session\Container;
use Application\Constants\MediaType;
use Application\Constants\ItemType;
use Application\Form\ExamSelect;
use Application\Form\ExamInput;
use Application\Service\ExamService;
use EddieJaoude\Zf2Logger\Log\Logger;
use Core\Exception\MalformedRequest;
use Core\Exception\ObjectNotEnabled;
use Core\Exception\ObjectNotFound;
use Core\Exception\InconsistentContent;
use Application\Form\ExamEmpty;
use Zend\Form\Form;
use Application\Form\ExamMultisubmit;
use Application\Form\ExamDragDrop;
use Zend\View\Model\JsonModel;

class ExamController extends AbstractActionController
{
	/**
	 * Oggetto sessione exam
	 * 
	 * @var Container
	 */
	private $session;
	
	/**
	 * Oggetto configurazione di sistema
	 * 
	 * @var Zend\Config
	 */
	private $config;
	
	/**
	 * Logger
	 * 
	 * @var Logger
	 */
	private $logger;
	
	/**
	 * Richiesta JS di verifica risultato di una risposta utente.
	 * Serve in caso di risposta con piu tentativi
	 * @return ViewModel
	 */
	public function ajcheckanswerAction()
	{
		$this->initExam();
		
		$optionId = $this->params('optionid');
		$this->session->currentSelectedOption = $optionId;
		
		$result = -1;
		
		foreach ($this->session->exam['current_item']['options'] as $i => $option) {
			if ($option['id'] == $optionId) {
				if ($option['correct'] === 1) {
					$result = array(
						'result' => 1,
						'points' => $option['points'],
						'answer' => $this->session->exam['current_item']['context']
					);
				} else {
					$result = array('result' => 0);
					$this->session->usedTries++;
					if ($this->session->usedTries >= $this->session->exam['current_item']['maxtries']) {
						$result['tryagain'] = 0;
						$result['points'] = $option['points'];
						$result['answer'] = $this->session->exam['current_item']['context'];
					} else {
						$result['tryagain'] = 1;
					}
				}
				break;
			}
		}
		
		echo json_encode($result);die();
	}
	
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
		
		// *** Verifica token. Se non presente, redirect a url definito in configurazione *** //
		$stmt = $this->params('tkn');
		
		if (strlen($stmt) == 0) {
			$this->logger->notice("Token is missing in /exam/token request. Redirecting to corporate url [".$this->config['corporateurl']."]");
			$this->redirect()->toUrl($this->config['corporateurl']);
			return;
		}
		
		try {
			// ** Acquisizione dati studente/sessione e salvataggio in sessione **//
			//$res = $this->getExamService()->getExamSessionByToken($stmt);
		 	$res = $this->getExamService()->getCurrentExamSessionItemByToken($stmt);
		 	// 3- Verifica risultato: inviare a pagina di stop o prosegue?
			if ($res['result'] === 0) {
				$this->redirect()->toRoute('exam_nothing');
				return;
			}
			$this->session->token = $stmt;
			$this->session->exam = $res;
			
			// Redirect ad inizio esame (se progressive è zero)
			if($res['session']['progressive'] === 0) {
				
				// Alla prima domanda visualizzo la pagina iniziale corso
				$newExam = 1;
				$this->redirect()->toRoute('exam_start');
			} else {
				$newExam = 0;
				$this->redirect()->toRoute('exam_restart');
			}
			
		} catch (\Exception $e) {
			$this->logger->warn($e->getMessage());
			$this->logger->info($e->getTraceAsString());
			
			if ($e instanceof MalformedRequest || $e instanceof InconsistentContent) {
				// Richiesta volutamente errata
				$this->redirect()->toUrl($this->config['corporateurl']);
			} else {
				// Errore 500
				$this->session->error_message = "Errore interno del Server (codice: ".$e->getMessage().")";
				$this->redirect()->toRoute('exam_error');
			}
		}
	}
	public function nothingAction()
	{
		return new ViewModel();
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
		$this->init();
		
		$vm = new ViewModel();
		if ($this->session->offsetExists('error_message')) {
			$vm->error_message = $this->session->error_message;
		} else {
			$vm->error_message = "";
		}
		return $vm;
	}
	
	/**
	 * Visualizzazione pagina interstiziale di inizio esame. Viene visualizzata in fase di inizio esame
	 * 
	 * @return void
	 * @see \Zend\Mvc\Controller\AbstractActionController 
	 */
	public function startAction()
	{
		$this->initExam();
		
		// Inizializza variabili d'ambiente
		$this->session->offsetUnset('startedTime');
		$this->session->offsetUnset('usedTries');
		
		$vm = new ViewModel();
		$this->session->exam['student']['sex'] == 'f' ? $vm->sexDesc = 'a' : $vm->sexDesc = 'o';
		$vm->firstName = $this->session->exam['student']['firstname'];
		$vm->lastName = $this->session->exam['student']['lastname'];
		$vm->courseName = $this->session->exam['course']['name'];
		$vm->courseDesc = $this->session->exam['course']['description'];
		$vm->examName = $this->session->exam['exam']['name'];
		$vm->examDesc = $this->session->exam['exam']['description'];
		$vm->totalItems = $this->session->exam['exam']['totalitems'];
		$vm->examNumber = $this->session->exam['exam']['progress'];
		$vm->totExams = $this->session->exam['course']['numexams'];
		$vm->endDate = $this->session->exam['session']['expectedenddate']->format('d/m/Y');
		$vm->maxPoints = $this->session->exam['stats']['exam_max_possible_points'];
		$this->session->exam['exam']['photourl'] == "" ? $vm->examImage = "" : $vm->examImage = "/static/assets/img/exam/".$this->session->exam['exam']['photourl'];
		return $vm;
	}
	
	public function resetAction()
	{
		$this->init();
		$sessionId = $this->params('id');
		$this->getExamService()->resetDemo($sessionId);
		$this->cleanSessionExamVars();
		echo "Sessione resettata";die();
		
	}
	/**
	 * Visualizzazione pagina interstiziale di inizio esame. Viene visualizzata in fase di ripresa esame
	 * 
	 * @return void
	 * @see \Zend\Mvc\Controller\AbstractActionController 
	 */
	public function restartAction()
	{
		$this->initExam();

		// Inizializza variabili d'ambiente
		$this->session->offsetUnset('startedTime');
		$this->session->offsetUnset('usedTries');
		
		$vm = new ViewModel();
		$this->session->exam['student']['sex'] == 'f' ? $vm->sexDesc = 'a' : $vm->sexDesc = 'o';
		$vm->firstName = $this->session->exam['student']['firstname'];
		$vm->lastName = $this->session->exam['student']['lastname'];
		$vm->courseName = $this->session->exam['course']['name'];
		$vm->courseDesc = $this->session->exam['course']['description'];
		$vm->examName = $this->session->exam['exam']['name'];
		$vm->examDesc = $this->session->exam['exam']['description'];
		$vm->totalItems = $this->session->exam['exam']['totalitems'];
		$vm->examNumber = $this->session->exam['exam']['progress'];
		$vm->totExams = $this->session->exam['course']['numexams'];
		$vm->endDate = $this->session->exam['session']['expectedenddate']->format('d/m/Y');
		$vm->maxPoints = $this->session->exam['stats']['exam_max_possible_points'];
		$this->session->exam['exam']['photourl'] == "" ? $vm->examImage = "" : $vm->examImage = "/static/assets/img/exam/".$this->session->exam['exam']['photourl'];
		return $vm;
	}
	
	/**
	 * Visualizzazione pagina interstiziale di fine esame. Viene visualizzato al termine di una sessione
	 * 
	 * @return void
	 * @see \Zend\Mvc\Controller\AbstractActionController
	 */
	public function endAction()
	{
		
		$this->initExam();
		
		// Inizializza variabili d'ambiente
		$this->session->offsetUnset('startedTime');
		$this->session->offsetUnset('usedTries');
		
		$vm = new ViewModel();
		$this->session->exam['student']['sex'] == 'f' ? $vm->sexDesc = 'a' : $vm->sexDesc = 'o';
		$vm->firstName = $this->session->exam['student']['firstname'];
		$vm->lastName = $this->session->exam['student']['lastname'];
		$vm->courseName = $this->session->exam['course']['name'];
		$vm->courseDesc = $this->session->exam['course']['description'];
		$vm->examName = $this->session->exam['exam']['name'];
		$vm->examDesc = $this->session->exam['exam']['description'];
		$vm->totalItems = $this->session->exam['exam']['totalitems'];
		$vm->examNumber = $this->session->exam['exam']['progress'];
		$vm->totExams = $this->session->exam['course']['numexams'];
		$vm->endDate = $this->session->exam['session']['expectedenddate']->format('d/m/Y');
		$vm->maxPoints = $this->session->exam['stats']['exam_max_possible_points'];
		$this->session->exam['exam']['photourl'] == "" ? $vm->examImage = "" : $vm->examImage = "/static/assets/img/exam/".$this->session->exam['exam']['photourl'];
		return $vm;
	}
	
	public function timeoutAction()
	{
		$this->initExam();
		
		// Domanda
		$sessionId = $this->session->exam['session']['id'];
		$examId = $this->session->exam['exam']['id'];
		$itemId = $this->session->exam['current_item']['id'];
		
		$this->session->offsetUnset('currentSelectedOption');
		$this->session->offsetUnset('startedTime');
		$this->session->offsetUnset('usedTries');
		
		$retval = $this->getExamService()->responseWithATimeout($sessionId, $examId, $itemId);
		
		if ($retval === 1) {
			// Finito esame
			$this->redirect()->toRoute('exam_end');
			return;
		} else {
			$res = $this->getExamService()->getCurrentExamSessionItemByToken($this->session->token);
			$this->session->exam = $res;
			$this->redirect()->toRoute('exam_participate');
			return;
		}
	}
	
	/**
	 * Memorizzazione risultato risposta utente e redirect ad analisi della partecipazione 
	 * seguente.
	 * 
	 * @return void
	 * @see \Zend\Mvc\Controller\AbstractActionController
	 */
	public function saveanswerAction()
	{
		$this->initExam();
		
		$optionValue = $this->params('optionValue');
		
		$sessionId = $this->session->exam['session']['id'];
		$examId = $this->session->exam['exam']['id'];
		$itemId = $this->session->exam['current_item']['id'];
		$optionId = $optionValue;
		
		$this->session->offsetUnset('currentSelectedOption');
		$this->session->offsetUnset('startedTime');
		$this->session->offsetUnset('usedTries');
		
		$retval = $this->getExamService()->responseWithAnOption($sessionId, $examId, $itemId, $optionId);
		if ($retval === 1) {
			// Finito esame
			$this->redirect()->toRoute('exam_end');
			return;
		} else {
			$res = $this->getExamService()->getCurrentExamSessionItemByToken($this->session->token);
			$this->session->exam = $res;
			$this->redirect()->toRoute('exam_participate');
			return;
		}
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
		$this->initExam();
		
		try {
			// Impostazione valore corrente per la sessione d'esame
			
			$view = $this->composeParticipationVM();
			$form = $this->composeForm();
			$view->form = $form;
			return $view;
		} catch (\Exception $e) {
			$this->logger->warn($e->getMessage());
			$this->logger->info($e->getTraceAsString());
			
			if ($e instanceof MalformedRequest || $e instanceof InconsistentContent) {
				// Richiesta volutamente errata
				$this->redirect()->toUrl($this->config['corporateurl']);
			} else {
				// Errore 500
				$this->session->error_message = "Si &egrave; verificato un errore nella partecipazione all'esame (codice: ".$e->getCode().")";
				$this->redirect()->toRoute('exam_500');
			}
		}
	}
	
	/**
	 * Impostazioni di default (acquisizione sessione, impostazione config e logger).
	 * Verifica la presenza dei dati di accesso in sessione, altrimenti invalida e 
	 * re-invia l'utente a STM
	 * 
	 * @return void
	 */
	protected function init()
	{
		$this->session = new Container('exam');
		$this->config = $this->getServiceLocator()->get('Config');
		$this->logger = $this->getServiceLocator()->get('Logger');
	}
	
	/**
	 * Verifica la presenza dei dati di accesso in sessione, altrimenti invalida e
	 * re-invia l'utente a STM
	 *
	 * @return void
	 * @see ExamController::init()
	 */
	protected function initExam()
	{
		$this->init();
		
		$this->session->error_message = "";
		
		if (!$this->session->offsetExists('exam')) {
			// Accesso utente a pagina senza sessione
			$this->logger->notice("Utente ha eseguito l'accesso diretto alla pagina di partecipazione (o affini) senza esame in sessione");
			$this->redirect()->toUrl($this->config['corporateurl']);
		}
	}
	
	/**
	 * Composizione dati multimediali
	 * @param array $mediaArr Array media
	 * @return string
	 */
	protected function composeMedia(array $mediaArr = null)
	{
		$retval = "";
		
		if (count($mediaArr)) {
			foreach ($mediaArr as $media) {
				switch ($media['type']) {
					case MediaType::TYPE_IMAGE:
						$retval .= '<div><img src="'.$media['url'].'" alt="" style="width:50%;"/></div><br>';
						break;
					case MediaType::TYPE_VIDEO:
						$retval .= '<div class="tv-body"><div class="embed-responsive embed-responsive-16by9 m-b-20"><iframe class="embed-responsive-item" src="'.$media['url'].'"></iframe></div></div><br>';
						break;
					case MediaType::TYPE_SLIDESHOW:
						$retval .= '<div class="lightbox row">';
						$explodedMedia = explode("|",$media['url']);
						foreach ($explodedMedia as $media) {
							$retval .= '<div data-src="'.$media.'" class="col-md-3 col-sm-4 col-xs-6"><div class="ligthtbox-item p-item"><img src="'.$media.'" alt=""/></div></div>';
						}
						$retval .= '</div>';
						break;
				}
			}
		}
		
		return $retval;
	}	
	
	/**
	 * Rimozione elementi di sessione usati durante la sessione d'esame
	 * 
	 * @return void
	 */
	protected function cleanSessionExamVars()
	{
		$this->session->offsetUnset('exam');
		$this->session->offsetUnset('currentSelectedOption');
		$this->session->offsetUnset('token');
		$this->session->offsetUnset('startedTime');
		$this->session->offsetUnset('message');
		$this->session->offsetUnset('error_message');
		$this->session->offsetUnset('usedTries');
	}
	
	/**
	 * Composizione form
	 * @return Form
	 */
	protected function composeForm()
	{

		$itemProg = (int)$this->session->exam['session']['progressive'];
		$item = $this->session->exam['current_item'];

		// Caricamento form in base al tipo di item
		switch ($item['type']) {
			case ItemType::TYPE_INSERT:
				return new ExamInput();
				break;
			case ItemType::TYPE_MULTIPLE:
				$arrOptions = array();
				foreach ($item['options'] as $k=>$v) $arrOptions[$v['id']] = $v['value'];
				return new ExamSelect($arrOptions);
				break;
			case ItemType::TYPE_MULTISUBMIT:
				$arrOptions = array();
				foreach ($item['options'] as $k=>$v) $arrOptions[$v['id']]= $v['value'];
				return new ExamMultisubmit($arrOptions);
				break;
			case ItemType::TYPE_REORDER:
				return new ExamDragDrop();
				break;
			case ItemType::TYPE_EMPTY:
				return new ExamEmpty();
				break;
		}
	}
	
	/**
	 * Composizione ViewModel di partecipazione
	 * @return ViewModel
	 */
	protected function composeParticipationVM()
	{
		$tmpMedia = "";
		
		$vm = new ViewModel();
		
		// Dati studente
		$vm->firstName = $this->session->exam['student']['firstname'];
		$vm->lastName = $this->session->exam['student']['lastname'];
		
		// Dati corso
		$vm->courseName = $this->session->exam['course']['name'];
		$vm->courseDesc = $this->session->exam['course']['description'];
		
		// Dati esame
		$vm->examName = $this->session->exam['exam']['name'];
		$vm->examDesc = $this->session->exam['exam']['description'];
		$vm->totalItems = $this->session->exam['exam']['totalitems'];
		
		// Domanda corrente (in base a progressivo)
		$itemProg = (int)$this->session->exam['session']['progressive'];
		$item = $this->session->exam['current_item'];
		
		$vm->itemProgressive = $itemProg;
		$vm->itemQuestion = $item['question'];
		
		// Calcolo di tempo rimanente e tentativi rimanenti basandosi su eventuali dati di sessione
		if ($this->session->startedTime) {
			$now = new \DateTime();
			$diffInSeconds = $now->getTimestamp() - $this->session->startedTime->getTimestamp();
			$vm->remainingTime = $item['maxsecs']-$diffInSeconds;
		} else {
			$vm->remainingTime = $item['maxsecs'];
			$this->session->startedTime = new \DateTime();
		}
		
		if ($this->session->usedTries) {
			$vm->maxTries = $item['maxtries']-$this->session->usedTries;
			$vm->usedTries = $this->session->usedTries;
		} else {
			$vm->maxTries = $item['maxtries'];
			$this->session->usedTries = 0;
			$vm->usedTries = $this->session->usedTries;
		}
		
		// Gestione elementi multimediali
		$vm->media = $this->composeMedia($item['media']);
		
		if (strlen($this->session->message) > 0) {
			$vm->enableMessage 	= true;
			$vm->message 		= $this->session->message;
		} else {
			$vm->enableMessage 	= false;
			$vm->message 		= "";
		}
		
		return $vm;
	}
	
	/**
	 * @return ExamService
	 */
	private function getExamService()
	{
		return $this->getServiceLocator()->get('ExamService');
	}
}
