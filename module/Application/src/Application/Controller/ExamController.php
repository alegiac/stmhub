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
use Application\Constants\MediaType;
use Application\Constants\ItemType;
use Application\Form\ExamSelect;
use Application\Form\ExamInput;

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
			$res = $this->getExamService()->getExamSessionByToken($stmt);

			// Salvataggio token in sessione
			$this->session->data = $res;
			// 3- Verifica risultato: inviare a pagina di stop o prosegue?
			if (strlen($res['message']) > 0 && is_null($res['id'])) {
				$this->redirect()->toRoute('exam_error');
			} else {
				//$this->session->exam = $this->getExamService()->getUserExamData($res['id']);
				$this->redirect()->toRoute('exam_participate');
			}
		} catch (\Exception $e) {
			// 4 - Gestione eccezioni
			$this->session->exception = "Impossibile accedere alla sessione di esame per inconsistenza dei dati.";
			$this->redirect()->toRoute('exam_exception');
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
		echo "errore";die();
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
		
		// Visualizzazione 
		$vm = new ViewModel();
		$vm->disableCssMM = 1;
		$vm->firstName = $this->session->data['student']['firstname'];
		$vm->lastName = $this->session->data['student']['lastname'];
		$vm->courseName = $this->session->data['session']['course']['name'];
		$vm->courseDesc = $this->session->data['session']['course']['description'];
		$vm->examName = $this->session->data['session']['exam']['name'];
		$vm->examDesc = $this->session->data['session']['exam']['description'];
		$vm->totalItems = $this->session->data['session']['exam']['totalitems'];
		
		// Domanda
		$itemProg = (int)$this->session->data['session']['progress']+1;
		$vm->itemProgressive = $itemProg;
		$item = $this->session->data['session']['items'][$itemProg];
		$vm->itemQuestion = $item['question'];
		
		// Media
		$tmpMedia = "";
		if (count($item['media'])) {
			foreach ($item['media'] as $media) {
				switch ($media['type']) {
					case MediaType::TYPE_IMAGE:
						$tmpMedia .= 
								'<div>
									<img src="'.$media['url'].'" alt="" style="width:50%;"/>
								</div>
								<br>';
						break;
					case MediaType::TYPE_VIDEO:
						$tmpMedia .= 
								'<div class="tv-body">
									<div class="embed-responsive embed-responsive-16by9 m-b-20">
										<iframe class="embed-responsive-item" src="'.$media['url'].'"></iframe>
									</div>
								</div>
								<br>';
						break;
					case MediaType::TYPE_SLIDESHOW:
						$tmpMedia .= '<div class="lightbox row">';
						$explodedMedia = explode("|",$media['url']);
						foreach ($explodedMedia as $media) {
							$tmpMedia .= 
								'<div data-src="'.$media.'" class="col-md-3 col-sm-4 col-xs-6">
									<div class="ligthtbox-item p-item">
										<img src="'.$media.'" alt=""/>
									</div>
								</div>';
						}
						$tmpMedia .= '</div>';	
						break;
				}
			}
		}
		$vm->media = $tmpMedia;
		
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
				$form = new ExamInput();
				break;
			case ItemType::TYPE_TRUEFALSE;
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
