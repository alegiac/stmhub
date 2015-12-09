<?php

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
use Core\Exception\InconsistentContent;
use Application\Form\ExamEmpty;
use Zend\Form\Form;
use Application\Form\ExamMultisubmit;
use Application\Form\ExamDragDrop;
use Zend\Db\Sql\Ddl\Column\Boolean;
use Core\Exception\ObjectNotEnabled;

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
	 * Controller index action.
	 * Nothing really to do here, so we can redirect the user to the corporate site.
	 * {@inheritDoc}
	 * @see \Zend\Mvc\Controller\AbstractActionController::indexAction()
	 */
	public function indexAction()
	{
		return $this->redirect()->toUrl($this->config['corporateurl']);
	}
	
	/**
	 * Richiesta JS di verifica risultato di una risposta utente.
	 * Serve in caso di risposta con piu tentativi
	 * @return ViewModel
	 */
	public function ajcheckanswerAction()
	{
		$this->initExam();
		$checkAgainstValue = false;
		
		// Controllo, se � null-question rispondo ok con la risposta pronta
		if ($this->session->exam['current_item']['type'] === ItemType::TYPE_EMPTY) {
			
			$result = array(
				'result' => 2,
				'points' => 0,
				'answer' => utf8_encode($this->session->exam['current_item']['answer']),
			);
			echo json_encode($result);die();
		} else if ($this->session->exam['current_item']['type'] === ItemType::TYPE_REORDER) {
			$checkAgainstValue = true;
		}
		
		$optionId = $this->params('optionid');
		$this->session->currentSelectedOption = $optionId;
		
		$result = -1;
		
		foreach ($this->session->exam['current_item']['options'] as $i => $option) {
			
			// Check against value?
			if ($checkAgainstValue === true) {
				
				if (strtolower($option['value']) == strtolower($optionId)) {
					
					$result = array(
						'result' => 1,
						'points' => $option['points'],
						'answer' => utf8_encode($this->session->exam['current_item']['answer'])
					);
				} else {
					$result = array('result' => 0);
					$this->session->usedTries++;
					if ($this->session->usedTries >= $this->session->exam['current_item']['maxtries']) {
						$result['tryagain'] = 0;
						$result['points'] = $option['points'];
						$result['answer'] = utf8_encode($this->session->exam['current_item']['answer']);
					} else {
						$result['tryagain'] = 1;
					}
				}	
			} else {
				if ($option['id'] == $optionId) {
					if ($option['correct'] === 1) {
						$result = array(
							'result' => 1,
							'points' => $option['points'],
							'answer' => utf8_encode($this->session->exam['current_item']['answer'])
						);
					} else {
						$result = array('result' => 0);
						$this->session->usedTries++;
						if ($this->session->usedTries >= $this->session->exam['current_item']['maxtries']) {
							$result['tryagain'] = 0;
							$result['points'] = $option['points'];
							$result['answer'] = utf8_encode($this->session->exam['current_item']['answer']);
						} else {
							$result['tryagain'] = 1;
						}
					}
					break;
				}
			}
		}
		echo json_encode($result);die();
	}
	
	
	public function challengesAction()
	{
		$this->initExam();
		$res = $this->getExamService()->getAvailableChallenges($this->session->exam['session']['id']);
		$vm = $this->composeParticipationVM();
		
		if (is_array($res) && count($res) > 0) {
			$btn = "";
			foreach ($res as $ch) {
				$token = $ch['token'];
				$name = $ch['name'];
				$totQuestions = $ch['questions'];
				$totPoints = $ch['maxpoints'];
				
				$text = $name."   [".$totQuestions." quiz - ".$totPoints." punti]";
				
				$btn .= '<a href="/exam/tokenchallenge/'.$token.'" class="btn btn-lg btn-primary">'.$text.'</a><br><br>';
			}
		} else {
			$btn .= "Nessuna sfida disponibile al momento";
		}
		
		$vm->btn = $btn;
		return $vm;
	}
	
	/**
	 * Exam session tarting point: token given by the user (click on email)
	 * retrieve the current session (if any) and related information.
	 * 
	 * @return void
	 * @see \Zend\Mvc\Controller\AbstractActionController::indexAction()
	 */
	public function tokenAction()
	{
		return $this->tokenize(false);
	}
	
	/**
	 * Exam challenge starting point: token given by the user,
	 * the function tries to show the currently available challenges
	 */
	public function tokenchallengeAction()
	{
		return $this->tokenize(true);
	}
	
	
	
	private function tokenize($challenge)
	{
		$this->init();
		$stmt = $this->params('tkn',"");
		
		try {
			// Load session info
			$res = $this->getExamService()->getCurrentExamSessionItemByToken($stmt,$challenge);
			
			if ($res['result'] === 0) {
				
				// No exam available:
				if ($challenge === false) {
				
					// No mandatory exams, try to load challenges
					$this->redirect()->toRoute('exam_challenges');
					return;
				} else {
					
					$this->redirect()->toRoute('exam_nothing');
					return;
				}
			} else {
			
				$this->session->token = $stmt;
				$this->session->exam = $res;
			
				$this->redirect()->toRoute('exam_participate');
				return;
			}
				
		} catch (\Exception $e) {

			$this->logger->warn($e->getMessage());
			$this->logger->info($e->getTraceAsString());
			
			if ($e instanceof MalformedRequest || $e instanceof InconsistentContent) {
				// Richiesta volutamente errata
				$this->logger->notice("Received inconsistant/malfrormed request token [".$stmt."]");
				$this->redirect()->toUrl($this->config['corporateurl']);
			} else if ($e instanceof ObjectNotEnabled) {
				// 
			} else {
				// Errore 500
				$this->session->error_message = "Errore interno del Server (codice: ".$e->getCode().")";
				$this->redirect()->toRoute('exam_error');
			}
		}
	}
	
	
	public function nothingAction()
	{
		$this->initExam();
		return $this->composeParticipationVM();
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
	 * Visualizzazione pagina interstiziale di fine esame. Viene visualizzato al termine di una sessione
	 * 
	 * @return void
	 * @see \Zend\Mvc\Controller\AbstractActionController
	 */
	public function endAction()
	{
		
		$this->initExam();
		
		$terminationValue = $this->params()->fromQuery('termination');

		$vm = new ViewModel();
		// Dati studente
		$vm->firstName = $this->session->exam['student']['firstname'];
		$vm->lastName = $this->session->exam['student']['lastname'];

		switch ($terminationValue) {
			case ExamService::SESSION_TERMINATED:
				$vm->message = $this->session->exam['student']['firstname'].", hai completato questa sessione.";
				break;
			case ExamService::EXAM_TERMINATED:
				$vm->message = "Complimenti ".$this->session->exam['student']['firstname'].", hai completato il livello ".$this->session->exam['exam']['name'];
				break;
			case ExamService::COURSE_TERMINATED:
				$vm->message = "Complimenti ".$this->session->exam['student']['firstname'].", hai completato il corso ".$this->session->exam['course']['name'];
				break;
		}
		// Inizializza variabili d'ambiente
		$this->session->offsetUnset('startedTime');
		$this->session->offsetUnset('usedTries');
		
		
		// Resetta i dati di sessione
		//$this->cleanSessionExamVars();
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
		
		if ($retval !== 0) {
		
			// Finito esame e/o corso
			$this->redirect()->toRoute('exam_end',array('termination' => $retval));
			return;
		} else {
			$res = $this->getExamService()->getCurrentExamSessionItemByToken($this->session->token,$this->session->exam['session']['challenge']);
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
		
		if ($this->session->exam['current_item']['type'] === ItemType::TYPE_REORDER) {
			$optionId = $this->session->exam['current_item']['options'][0]['id'];
			$retval = $this->getExamService()->responseReorder($sessionId, $examId, $itemId, $optionId, $optionValue);
		} else {
			$optionId = $optionValue;
			$retval = $this->getExamService()->responseWithAnOption($sessionId, $examId, $itemId, $optionId);
		}
		
		$this->session->offsetUnset('currentSelectedOption');
		$this->session->offsetUnset('startedTime');
		$this->session->offsetUnset('usedTries');
		
		if ($retval !== 0) {
		
			// Finito esame e/o corso
			$this->redirect()->toRoute('exam_end',array('termination' => $retval));
			return;
		} else {
			$res = $this->getExamService()->getCurrentExamSessionItemByToken($this->session->token,$this->session->exam['session']['challenge']);
			$this->session->exam = $res;
			$this->redirect()->toRoute('exam_participate');
			return;
		}
	}
	
	/**
	 * Question/answer generic page. 
	 * Handles the current step of the session, and composes the view with question and info elements
	 * 
	 * @return void
	 * @see \Zend\Mvc\Controller\AbstractActionController
	 */
	public function participateAction()
	{
		$this->initExam();
		
		//try {
			
			$view = $this->composeParticipationVM();
			$form = $this->composeForm();
			if ($form instanceof ExamDragDrop) {
				$view->scramble = $form->getUlReorderOptions();
			}
			$view->form = $form;
			return $view;
		//} catch (\Exception $e) {
		//	$this->logger->warn($e->getMessage());
		//	$this->logger->info($e->getTraceAsString());
			
		//	if ($e instanceof MalformedRequest || $e instanceof InconsistentContent) {
		//		// Richiesta volutamente errata
		//		$this->redirect()->toUrl($this->config['corporateurl']);
		//	} else {
		//		// Errore 500
		//		$this->session->error_message = "Si &egrave; verificato un errore nella partecipazione all'esame (codice: ".$e->getCode().")";
		//		$this->redirect()->toRoute('exam_500');
		//	}
		//}
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
	 * Initialize page with Verifica la presenza dei dati di accesso in sessione, altrimenti invalida e
	 * re-invia l'utente a STM
	 *
	 * @return void
	 * @see ExamController::init()
	 */
	protected function initExam()
	{
		$this->init();
		
		$this->session->error_message = "";
		if (!$this->session || !$this->session->offsetExists('exam')) {
			// Accesso utente a pagina senza sessione
			$this->logger->notice("Utente ha eseguito l'accesso diretto alla pagina di partecipazione (o affini) senza esame in sessione");
			return $this->redirect()->toUrl($this->config['corporateurl']);
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
						$retval .= '<div style="text-align: center;"><img src="'.$media['url'].'" alt="" style="max-width: 100%;height: auto;"/></div><br>';
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
			case ItemType::TYPE_SELECT:
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
				$options = $item['options'][0]['value'];
				return new ExamDragDrop($options);
				break;
			case ItemType::TYPE_EMPTY:
				return new ExamEmpty();
				break;
		}
	}
	
	protected function composeChallengeList($name,$doShort = false)
	{
		$fontSize = "150%";
		if ($doShort === true) {
			$fontSize = "80%";
		}
	
		$tag = "<ul style=\"list-style-type: none;\">";
		$tag .='<li style="color: black; font-size:'.$fontSize.';"><i class="fa fa-eye fa-fw"></i>&nbsp;&nbsp;'.$name.'</li>';
		$tag .= "</ul>";
		return $tag;
	}
	
	protected function composeExamList($list,$doShort = false)
	{
		$fontSize = "110%";
		if ($doShort === true) {
			$fontSize = "70%";
		}
		
		if ($doShort === true) {
			$tag = "<ul style=\"list-style-type: none;margin-top:5px;\">";
		} else {
			$tag = "<ul style=\"list-style-type: none;margin-top:-20px;\">";
		}

		foreach ($list as $exam) {
			if ($exam['started'] === false) {
				$tag .='<li style="color: lightgrey; font-size:'.$fontSize.';"><i class="fa fa-clock-o fa-fw"></i>&nbsp;&nbsp;'.$exam['name'].'</li>';
			} else {
				if ($exam['completed'] === true) {
					$tag .='<li style="color: green; font-size:'.$fontSize.';"><i class="fa fa-check-circle-o fa-fw"></i>&nbsp;&nbsp;<s>'.$exam['name'].'</s></li>';
				} else {
					$tag .='<li style="color: black; font-size:'.$fontSize.';"><i class="fa fa-eye fa-fw"></i>&nbsp;&nbsp;'.$exam['name'].'</li>';
				}
			}
		}
		$tag .= "</ul>";
		return $tag;
	}
		
	/**
	 * Composizione ViewModel di partecipazione
	 * @return ViewModel
	 */
	protected function composeParticipationVM()
	{
		$tmpMedia = "";
		$vm = new ViewModel();

		if (isset($this->session->exam)) {
		
			// Dati studente
			$vm->firstName = $this->session->exam['student']['firstname'];
			$vm->lastName = $this->session->exam['student']['lastname'];
			$this->session->exam['student']['sex'] == 'F' ? $vm->sexDesc = 'a' : $vm->sexDesc = 'o';
			
			// Dati corso
			$vm->courseName = $this->session->exam['course']['name'];
			
			// Esami (o sfida)
			if ($this->session->exam['session']['challenge'] == 1) {
				$vm->examList = $this->composeChallengeList($this->session->exam['exam']['name']);
				$vm->examListShort = $this->composeChallengeList($this->session->exam['exam']['name'],true);
			} else {
				$vm->examList = $this->composeExamList($this->session->exam['allexams']);
				$vm->examListShort = $this->composeExamList($this->session->exam['allexams'],true);
			}
			
			// Dati sessione
			$vm->expectedEndDate = $this->session->exam['session']['expectedenddate']->format('m/Y');
			$vm->expectedEndDateShort = $this->session->exam['session']['expectedenddate']->format('d/m');
	
			$vm->points = $this->session->exam['session']['points'];
			
			$vm->sessionIndex = $this->session->exam['session']['index'];
			$vm->actualQuestion = $this->session->exam['current_item']['question_number'];
			$vm->totalQuestion = $this->session->exam['current_item']['question_total'];
			
			// Dati esame
			$vm->examName = $this->session->exam['exam']['name'];
			$vm->examDesc = $this->session->exam['exam']['description'];
			$vm->totalItems = $this->session->exam['exam']['totalitems'];
			
			// Domanda corrente (in base a progressivo)
			$itemProg = (int)$this->session->exam['session']['progressive'];
			$item = $this->session->exam['current_item'];
			
			$vm->itemProgressive = $itemProg;
			$vm->itemQuestion = utf8_encode($item['question']);
			
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
		}
		
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
