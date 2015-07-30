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
		
		// Visualizzazione 
		$vm = new ViewModel();
		$vm->firstName = $this->session->data['student']['firstname'];
		$vm->lastName = $this->session->data['student']['lastname'];
		$vm->courseName = $this->session->data['session']['course']['name'];
		$vm->courseDesc = $this->session->data['session']['course']['description'];
		$vm->examName = $this->session->data['session']['exam']['name'];
		$vm->examDesc = $this->session->data['session']['exam']['description'];
		
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
						$tmpMedia .= '<div><img src="'.$media['url'].'" alt="" style="width:50%;"></div><br>';
						break;
					case MediaType::TYPE_VIDEO:
						$tmpMedia .= '<div class="tv-body"><div class="embed-responsive embed-responsive-16by9 m-b-20">
								<iframe class="embed-responsive-item" src="'.$media['url'].'"></iframe></div></div><br>';
						break;
					case MediaType::TYPE_SLIDESHOW:
						$tmpMedia .= '<div class="lightbox row">
                                    <div data-src="media/gallery/1.jpg" class="col-md-3 col-sm-4 col-xs-6">
                                        <div class="lightbox-item p-item">
                                            <img src="media/gallery/thumbs/1.jpg" alt="">
                                        </div>
                                    </div>
                                    
                                    <div data-src="media/gallery/2.jpg" class="col-md-3 col-sm-4 col-xs-6">
                                        <div class="lightbox-item p-item">
                                            <img src="media/gallery/thumbs/2.jpg" alt="">
                                        </div>
                                    </div>
                                    
                                    <div data-src="media/gallery/3.jpg" class="col-md-3 col-sm-4 col-xs-6">
                                        <div class="lightbox-item p-item">
                                            <img src="media/gallery/thumbs/3.jpg" alt="">
                                        </div>
                                    </div>
                                    
                                    <div data-src="media/gallery/4.jpg" class="col-md-3 col-sm-4 col-xs-6">
                                        <div class="lightbox-item p-item">
                                            <img src="media/gallery/thumbs/4.jpg" alt="">
                                        </div>
                                    </div>
                                    
                                    <div data-src="media/gallery/5.jpg" class="col-md-3 col-sm-4 col-xs-6">
                                        <div class="lightbox-item p-item">
                                            <img src="media/gallery/thumbs/5.jpg" alt="">
                                        </div>
                                    </div>
                                    <div data-src="media/gallery/6.jpg" class="col-md-3 col-sm-4 col-xs-6">
                                        <div class="lightbox-item p-item">
                                            <img src="media/gallery/thumbs/6.jpg" alt="">
                                        </div>
                                    </div>
                                    <div data-src="media/gallery/7.jpg" class="col-md-3 col-sm-4 col-xs-6">
                                        <div class="lightbox-item p-item">
                                            <img src="media/gallery/thumbs/7.jpg" alt="">
                                        </div>
                                    </div>
                                    <div data-src="media/gallery/8.jpg" class="col-md-3 col-sm-4 col-xs-6">
                                        <div class="lightbox-item p-item">
                                            <img src="media/gallery/thumbs/8.jpg" alt="">
                                        </div>
                                    </div>
                                    <div data-src="media/gallery/9.jpg" class="col-md-3 col-sm-4 col-xs-6">
                                        <div class="lightbox-item p-item">
                                            <img src="media/gallery/thumbs/9.jpg" alt="">
                                        </div>
                                    </div>
                                    <div data-src="media/gallery/10.jpg" class="col-md-3 col-sm-4 col-xs-6">
                                        <div class="lightbox-item p-item">
                                            <img src="media/gallery/thumbs/10.jpg" alt="">
                                        </div>
                                    </div>
                                    <div data-src="media/gallery/11.jpg" class="col-md-3 col-sm-4 col-xs-6">
                                        <div class="lightbox-item p-item">
                                            <img src="media/gallery/thumbs/11.jpg" alt="">
                                        </div>
                                    </div>
                                    <div data-src="media/gallery/12.jpg" class="col-md-3 col-sm-4 col-xs-6">
                                        <div class="lightbox-item p-item">
                                            <img src="media/gallery/thumbs/12.jpg" alt="">
                                        </div>
                                    </div>
                                    <div data-src="media/gallery/13.jpg" class="col-md-3 col-sm-4 col-xs-6">
                                        <div class="lightbox-item p-item">
                                            <img src="media/gallery/thumbs/13.jpg" alt="">
                                        </div>
                                    </div>
                                    <div data-src="media/gallery/14.jpg" class="col-md-3 col-sm-4 col-xs-6">
                                        <div class="lightbox-item p-item">
                                            <img src="media/gallery/thumbs/14.jpg" alt="">
                                        </div>
                                    </div>
                                    <div data-src="media/gallery/15.jpg" class="col-md-3 col-sm-4 col-xs-6">
                                        <div class="lightbox-item p-item">
                                            <img src="media/gallery/thumbs/15.jpg" alt="">
                                        </div>
                                    </div>
                                    <div data-src="media/gallery/16.jpg" class="col-md-3 col-sm-4 col-xs-6">
                                        <div class="lightbox-item p-item">
                                            <img src="media/gallery/thumbs/16.jpg" alt="">
                                        </div>
                                    </div>
                                    <div data-src="media/gallery/17.jpg" class="col-md-3 col-sm-4 col-xs-6">
                                        <div class="lightbox-item p-item">
                                            <img src="media/gallery/thumbs/7.jpg" alt="">
                                        </div>
                                    </div>
                                    <div data-src="media/gallery/18.jpg" class="col-md-3 col-sm-4 col-xs-6">
                                        <div class="lightbox-item p-item">
                                            <img src="media/gallery/thumbs/18.jpg" alt="">
                                        </div>
                                    </div>
                                    <div data-src="media/gallery/19.jpg" class="col-md-3 col-sm-4 col-xs-6">
                                        <div class="lightbox-item p-item">
                                            <img src="media/gallery/thumbs/19.jpg" alt="">
                                        </div>
                                    </div>
                                    <div data-src="media/gallery/20.jpg" class="col-md-3 col-sm-4 col-xs-6">
                                        <div class="lightbox-item p-item">
                                            <img src="media/gallery/thumbs/20.jpg" alt="">
                                        </div>
                                    </div>
                                    <div data-src="media/gallery/21.jpg" class="col-md-3 col-sm-4 col-xs-6">
                                        <div class="lightbox-item p-item">
                                            <img src="media/gallery/thumbs/21.jpg" alt="">
                                        </div>
                                    </div>
                                    <div data-src="media/gallery/22.jpg" class="col-md-3 col-sm-4 col-xs-6">
                                        <div class="lightbox-item p-item">
                                            <img src="media/gallery/thumbs/22.jpg" alt="">
                                        </div>
                                    </div>
                                    <div data-src="media/gallery/23.jpg" class="col-md-3 col-sm-4 col-xs-6">
                                        <div class="lightbox-item p-item">
                                            <img src="media/gallery/thumbs/23.jpg" alt="">
                                        </div>
                                    </div>
                                    <div data-src="media/gallery/24.jpg" class="col-md-3 col-sm-4 col-xs-6">
                                        <div class="lightbox-item p-item">
                                            <img src="media/gallery/thumbs/24.jpg" alt="">
                                        </div>
                                    </div>
                                </div>'
						break;
				}
				
						
			}
		}
		switch ($item['']
		
		
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
