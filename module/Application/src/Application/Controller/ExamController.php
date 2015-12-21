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
use Core\Exception\ObjectNotEnabled;
use Core\Exception\ObjectNotFound;

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
				
				$btn .= '<a href="/exam/tokenchallenge/'.$token.'" class="btn btn-lg btn-primary" style="white-space:normal !important; max-width:1000px; margin-right: 10px;margin-left: 10px;margin-top: 10px;">'.$text.'</a><br><br>';
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
	 * @return \Zend\View\Model\ViewModel
	 */
	public function tokenAction()
	{
		return $this->tokenize(false);
	}
	
	/**
	 * Exam challenge starting point: token given by the user (click on a challenge button),
	 * the function tries to show the currently available challenges
	 * 
	 * @return \Zend\View\Model\ViewModel
	 */
	public function tokenchallengeAction()
	{
		return $this->tokenize(true);
	}
	
	/**
	 * Tokenization function: get the token in the query string,
	 * retrieve the session info from the token and redirect the user
	 * to:
	 * - exam_participate --> exam session to start/complete
	 * - exam_challenges --> no exam sessions, go to available challenges
	 * - exam_nothing --> no challenges available, go to nothing to do action
	 * 
	 * @param boolean $isChallenge Flag for challenge token
	 * @return \Zend\View\Model\ViewModel 
	 */
	private function tokenize($isChallenge)
	{
		$this->init();
		$stmt = $this->params('tkn',"");
		
		try {
			
			// Load session info
			$res = $this->getExamService()->getCurrentExamSessionItemByToken($stmt,$isChallenge);
			
			if ($res['result'] === 1) {
				// Session found: set token in session for future interactions in the exam session
				$this->session->token = $stmt;
				$this->session->exam = $res;
				
				$this->redirect()->toRoute('exam_participate');
				return;
			}
			
			// No session available: different behavior for exam and challenge
			if ($isChallenge === false) {
				// Redirect the user to the challenges, if any
				$this->redirect()->toRoute('exam_challenges');
			} else {
				// Nothing left to do
				$this->redirect()->toRoute('exam_nothing');
			}
			
		} catch (\Exception $e) {

			// Exception in loading token data
			$this->logger->err("Request token [".$stmt."] received exception from service of type ".get_class($e)." with message ".$e->getMessage());
			$this->logger->info($e->getTraceAsString());
			
			if ($e instanceof MalformedRequest || $e instanceof InconsistentContent || $e instanceof ObjectNotFound || $e instanceof ObjectNotEnabled) {
				// Richiesta errata 
				$this->redirect()->toUrl($this->config['corporateurl']);
			} else {
				// Errore 500
				$this->session->error_message = "Errore interno del Server";
				$this->redirect()->toRoute('exam_error');
			}
		}
	}
	
	/**
	 * Nothing action
	 * No activity available for the student. Presenting a page with the no-activity
	 * notification.
	 * 
	 * @return \Zend\View\Model\ViewModel
	 */
	public function nothingAction()
	{
		$this->initExam();
		return $this->composeParticipationVM();
	}
	
	/**
	 * Error action
	 * Show a view for any 500 errors. The link in the page should bring the user
	 * to the corporate url.
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
	 * End session page
	 * Shows the closing session message. In case the student has completed
	 * the entire exam (all the sessions) or the course, the message is changed
	 * dynamically.
	 * 
	 * @return \Zend\View\Helper\ViewModel
	 */
	public function endAction()
	{
		
		$this->initExam();
		$terminationValue = $this->session->offsetGet('session_termination');
		
		// TEST: not remove the offsetUnset of termination value here!
		$this->session->offsetUnset('session_termination');

		$vm = $this->composeParticipationVM();
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
		
		return $vm;
	}
	
	/**
	 * Timeout handling action
	 * Handle the timeout event in case of time based questions
	 * 
	 * @return \Zend\View\Helper\ViewModel
	 */
	public function timeoutAction()
	{
		$this->initExam();
		
		$sessionId = $this->session->exam['session']['id'];
		$examId = $this->session->exam['exam']['id'];
		$itemId = $this->session->exam['current_item']['id'];
		
		$this->session->offsetUnset('currentSelectedOption');
		$this->session->offsetUnset('startedTime');
		$this->session->offsetUnset('usedTries');
		
		$retval = $this->getExamService()->responseWithATimeout($sessionId, $examId, $itemId);
		
		// The session is now terminated: redirect to exam_end
		if ($retval !== 0) {
			$this->session->offsetSet('session_termination', $retval);
			$this->redirect()->toRoute('exam_end');
			return;
		}
		
		// The session goes on
		$res = $this->getExamService()->getCurrentExamSessionItemByToken($this->session->token,$this->session->exam['session']['challenge']);
		$this->session->exam = $res;
		$this->redirect()->toRoute('exam_participate');
		return;
	}
	
	/**
	 * Save answer action
	 * The answer needs to be stored in the database.  
	 * 
	 * @return \Zend\View\Helper\ViewModel
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
		
		// The session is now terminated: redirect to exam_end
		if ($retval !== 0) {
			$this->session->offsetSet('session_termination', $retval);
			$this->redirect()->toRoute('exam_end');
			return;
		}
		
		// The session goes on
		$res = $this->getExamService()->getCurrentExamSessionItemByToken($this->session->token,$this->session->exam['session']['challenge']);
		$this->session->exam = $res;
		$this->redirect()->toRoute('exam_participate');
		return;
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
		
		$vm = $this->composeParticipationVM();
		
		$form = $this->composeForm();
		if ($form instanceof ExamDragDrop) {
			$vm->scramble = $form->getUlReorderOptions();
		}
		$vm->form = $form;
		
		return $vm;
	}
	
	/**
	 * Action initialization
	 * Get config, logger, session for any action needs
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
	 * Action initialization under exam control
	 * The exam session needs to be in session, because it is subordinated
	 * to the prosecution of the session question sequence.
	 * If a user tries to access an "under-exam" section without the exam in session,
	 * he is redirected outside, to the corporate url
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
			$this->logger->warn('[initExam] - user has requested a page that needs an exam session without it. Redirecting to corporate URL');
			return $this->redirect()->toUrl($this->config['corporateurl']);
		}
	}
	
	/**
	 * Multimedia composition for view
	 * 
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
		$this->session->offsetUnset('session_termination');
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
	
		$tag = "<br><ul style=\"list-style-type: none;\">";
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
		$tag = "";
		
		foreach ($list as $type=>$exams) {
		
			$tag .= "<center><strong>".$type."</strong></center><br><br>";
		
			if ($doShort === true) {
				$tag .= "<ul style=\"list-style-type: none;margin-top:5px;\">";
			} else {
				$tag .= "<ul style=\"list-style-type: none;margin-top:-20px;\">";
			}
			
			foreach ($exams as $exam) {
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
		}
		
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
		$others ="";
		
		if (isset($this->session->exam)) {
		
			// Dati studente
			$vm->firstName = $this->session->exam['student']['firstname'];
			$vm->lastName = $this->session->exam['student']['lastname'];
			$this->session->exam['student']['sex'] == 'F' ? $vm->sexDesc = 'a' : $vm->sexDesc = 'o';
			
			// Dati classifica
			$vm->position = $this->session->exam['classification']['position'];
			$vm->hasPrize = $this->session->exam['classification']['has_prize'];
			$vm->prizeName = $this->session->exam['classification']['prizename'];
			
			// Premi e classifica
			$vm->goldFirstName = ""; $vm->silverFirstName = ""; $vm->bronzeFirstName = "";
			$vm->goldPrizeUrl = ""; $vm->silverPrizeUrl = ""; $vm->bronzePrizeUrl = "";
			$vm->goldPrizeTitle = ""; $vm->silverPrizeTitle = ""; $vm->bronzePrizeTitle = "";
			$vm->goldPoints = ""; $vm->silverPoints = ""; $vm->bronzePoints = "";
			
			$prizes = $this->session->exam['prizes'];
			if (count($prizes) > 0) {
				$vm->showClassification = 1;
				// Show gold, silver, gold
				$vm->goldFirstName = $prizes[1]['student']['firstname'];
				if (strlen($prizes[1]['prize']['url']) > 0) {
					$vm->goldPrizeUrl = $prizes[1]['prize']['url'];
				} else {
					$vm->goldPrizeUrl = "http://wpitalyplugin.com/wp-content/plugins/InstaBuilder/images/unavailable-200x145.png";
				}
				$vm->goldPrizeTitle = $prizes[1]['prize']['name'];
				$vm->goldPoints = $prizes[1]['student']['points']." p.ti";
				
				if (array_key_exists(2, $prizes)) {
					$vm->silverFirstName = $prizes[2]['student']['firstname'];
					if (strlen($prizes[2]['prize']['url']) > 0) {
						$vm->silverPrizeUrl = $prizes[2]['prize']['url'];
					} else {
						$vm->silverPrizeUrl = "http://wpitalyplugin.com/wp-content/plugins/InstaBuilder/images/unavailable-200x145.png";
					}
					$vm->silverPrizeTitle = $prizes[2]['prize']['name'];
					$vm->silverPoints = $prizes[2]['student']['points']." p.ti";
				}
				
				if (array_key_exists(3, $prizes)) {
					$vm->bronzeFirstName = $prizes[3]['student']['firstname'];
					$vm->bronzePrizeUrl = "http://wpitalyplugin.com/wp-content/plugins/InstaBuilder/images/unavailable-200x145.png";
					if (strlen($prizes[3]['prize']['url']) > 0) {
						$vm->bronzePrizeUrl = $prizes[3]['prize']['url'];
					}
					$vm->bronzePrizeTitle = $prizes[3]['prize']['name'];
					$vm->bronzePoints = $prizes[3]['student']['points']." p.ti";
				}
				
				if (count($prizes) > 3) {
					
					for ($i=4;$i<=count($prizes);$i++) {
						$others.= '<div class="col-xs-4"><center><br>'.$i.'° premio<br>';
						$prizeBorderColor = "white"; $prizeText = "black";
						if ($this->session->exam['classification']['position'] == $i) {
							$prizeBorderColor = "blue";$prizeText = "blue";
						}
						$prizeUrl = "http://wpitalyplugin.com/wp-content/plugins/InstaBuilder/images/unavailable-200x145.png";
						if (strlen($prizes[$i]['url'] > 0)) $prizeUrl = $prizes[$i]['url'];
						
						$others.='<div style="max-width:130px; background-color:'.$prizeBorderColor.';">';
						$others.='<img style="max-width:120px;" src="'.$prizeUrl.'"/>';
						$others.='</div><br><span style="color:'.$prizeText.';>'.$prizes[$i]['student']['firstname'].'</span></center></div>';
					}
				}
				
				$vm->otherPrices = $others;
				
				
			} else {
				$vm->showClassification = 0;
			}
			
			// Dati corso
			$vm->courseName = $this->session->exam['course']['name'];
			
			// Esami (o sfida)
			$vm->examList = $this->composeExamList($this->session->exam['allexams']);
			$vm->examListShort = $this->composeExamList($this->session->exam['allexams'],true);
			
			// Dati sessione
			$vm->expectedEndDate = $this->session->exam['session']['expectedenddate']->format('m/Y');
			$vm->expectedEndDateShort = $this->session->exam['session']['expectedenddate']->format('m/Y');
	
			$vm->points = $this->session->exam['session']['points'];
			
			$vm->sessionIndex = $this->session->exam['session']['index'];
			$vm->actualQuestion = $this->session->exam['current_item']['question_number'];
			$vm->totalQuestion = $this->session->exam['current_item']['question_total'];

			$since_start = $this->session->exam['session']['realstartdate']->diff(new \DateTime());
			
			$secs = $since_start->s;
			$minutes = $since_start->i;
			$hours = $since_start->h;
			
			if ($hours > 0) {
				$vm->minInSession = $since_start->h."h".$since_start->i."m".$since_start->s."s"; 
			} else {
				$vm->minInSession = $since_start->i."m".$since_start->s."s";
			}
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
