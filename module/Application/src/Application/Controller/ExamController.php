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
			$res = $this->getExamService()->getExamSessionByToken($stmt);
			$this->session->exam = $res;
			
			// 3- Verifica risultato: inviare a pagina di stop o prosegue?
			if (strlen($res['message']) > 0 && is_null($res['id'])) {
				$this->redirect()->toRoute('exam_error');
			} else {
				
				// Redirect ad inizio esame (se progress è zero)
				if($res['session']['progress'] === 0) {
					$newExam = 1;
					$this->redirect()->toRoute('exam_start');
				} else {
					$newExam = 0;
					$this->redirect()->toRoute('exam_restart');
				}
			}
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
		
		$vm = new ViewModel();
		$this->session->exam['student']['sex'] == 'f' ? $vm->sexDesc = 'a' : $vm->sexDesc = 'o';
		$vm->firstName = $this->session->exam['student']['firstname'];
		$vm->lastName = $this->session->exam['student']['lastname'];
		$vm->courseName = $this->session->exam['session']['course']['name'];
		$vm->courseDesc = $this->session->exam['session']['course']['description'];
		$vm->examName = $this->session->exam['session']['exam']['name'];
		$vm->examDesc = $this->session->exam['session']['exam']['description'];
		$vm->totalItems = $this->session->exam['session']['exam']['totalitems'];
		$vm->examNumber = $this->session->exam['session']['exam']['progress'];
		$vm->totExams = $this->session->exam['session']['course']['numexams'];
		$vm->endDate = $this->session->exam['session']['enddate'];
		$vm->maxPoints = $this->session->exam['stats']['exam_max_possible_points'];
		$this->session->exam['session']['exam']['photourl'] == "" ? $vm->examImage = "" : $vm->examImage = "/static/assets/img/exam/".$this->session->exam['session']['exam']['photourl'];
		return $vm;
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
		
		$vm = new ViewModel();
		$this->session->exam['student']['sex'] == 'f' ? $vm->sexDesc = 'a' : $vm->sexDesc = 'o';
		$vm->firstName = $this->session->exam['student']['firstname'];
		$vm->lastName = $this->session->exam['student']['lastname'];
		$vm->courseName = $this->session->exam['session']['course']['name'];
		$vm->courseDesc = $this->session->exam['session']['course']['description'];
		$vm->examName = $this->session->exam['session']['exam']['name'];
		$vm->examDesc = $this->session->exam['session']['exam']['description'];
		$vm->totalItems = $this->session->exam['session']['exam']['totalitems'];
		$vm->examNumber = $this->session->exam['session']['exam']['progress'];
		$vm->totExams = $this->session->exam['session']['course']['numexams'];
		$vm->endDate = $this->session->exam['session']['enddate'];
		$vm->maxPoints = $this->session->exam['stats']['exam_max_possible_points'];
		$this->session->exam['session']['exam']['photourl'] == "" ? $vm->examImage = "" : $vm->examImage = "/static/assets/img/exam/".$this->session->exam['session']['exam']['photourl'];
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
		
	}
	
	public function timeoutAction()
	{
		$this->initExam();
		
		
		// Domanda
		$itemProg = (int)$this->session->exam['session']['progress'];
		$item = $this->session->exam['session']['items'][$itemProg]['id'];
		
		$this->getExamService()->responseWithATimeout($this->session->exam['id'], $item);
		$this->redirect()->toRoute('exam_participate');
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
		
		$request = $this->getRequest();
		if ($request->isPost()) {
			if ($form->isValid()) {
			}
		}
		
		// Impostazione valore corrente per la sessione d'esame
		$this->getExamService()->setExamSessionProgress($this->session->exam['id'], $itemProg);
		
		return $this->composeParticipationVM();
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
	protected function composeMedia(array $mediaArr)
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
	 * Composizione ViewModel di partecipazione
	 * @return ViewModel
	 */
	protected function composeParticipationVM()
	{
		$tmpMedia = "";
		
		$vm = new ViewModel();
		
		// Dati studente
		$vm->firstName 		= $this->session->exam['student']['firstname'];
		$vm->lastName 		= $this->session->exam['student']['lastname'];
		
		// Dati corso
		$vm->courseName 	= $this->session->exam['session']['course']['name'];
		$vm->courseDesc 	= $this->session->exam['session']['course']['description'];
		
		// Dati esame
		$vm->examName 		= $this->session->exam['session']['exam']['name'];
		$vm->examDesc 		= $this->session->exam['session']['exam']['description'];
		$vm->totalItems 	= $this->session->exam['session']['exam']['totalitems'];
		
		// Domanda corrente (in base a progressivo)
		$itemProg = (int)$this->session->exam['session']['progress']+1;
		$item = $this->session->exam['session']['items'][$itemProg];
		$vm->itemProgressive = $itemProg;
		$vm->itemQuestion = $item['question'];
		$vm->remainingTime = $item['maxsecs'];
		
		// Gestione elementi multimediali
		$vm->media = $this->composeMedia($item['media']);
		
		// Caricamento form in base al tipo di item
		switch ($item['type']) {
			case ItemType::TYPE_INSERT:
				$form = new ExamInput();
				break;
			case ItemType::TYPE_MULTIPLE:
				$arrOptions = array();
				foreach ($item['options'] as $k=>$v)
				{
					$arrOptions[$v['id']] = $v['value'];
				}
				$form = new ExamSelect($arrOptions);
				break;
			case ItemType::TYPE_TRUEFALSE:
				break;
			case ItemType::TYPE_EMPTY:
				$form = new ExamEmpty();
				break;
		}
		$vm->form = $form;
		
		if (strlen($this->session->message) > 0) {
			$vm->enableMessage = true;
			$vm->message = $this->session->message;
		} else {
			$vm->enableMessage = false;
			$vm->message = "";
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
