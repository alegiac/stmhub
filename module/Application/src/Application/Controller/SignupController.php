<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\Session\Container;
use Application\Service\ExamService;
use EddieJaoude\Zf2Logger\Log\Logger;

class SignupController extends AbstractActionController
{
    /**
     * Flag registrazione manuale
     */
    const REGULAR_REGISTRATION = 1;
    
    /**
     * Flag registrazione facebook
     */
    const FACEBOOK_REGISTRATION = 2;

    /**
     * Facebook app id
     * @var type String
     */
    private $fbAppId = "170229993385237";
    
    /**
     * Facebook app secret
     * @var type String
     */
    private $fbAppSecret = "d72fde344d658a7e82e9d3ea789676c0";

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

    public function prepareAction()
    {
        $clientCourseId = $this->params()->fromRoute('clientcourse');
        $t = time();
        $crc = crc32($t."|".$clientCourseId);
        
        $url = "http://".$_SERVER['HTTP_HOST']."/signup/form/".$t."/".$clientCourseId."/".$crc;
        
        echo $url;
        die();
    }

    public function formAction()
    {
        
        $this->init();
        
        // Acquisizione parametri
        $time = $this->params()->fromRoute('time');
        $cc = $this->params()->fromRoute('clientcourse');
        $crc = $this->params()->fromRoute('crc');
        
        // Verifica accessibilità
        if (crc32($time."|".$cc) != $crc) {
            $this->error("I parametri passati con ".$cc.",".$time.",".$crc." non sono coerenti");
            $this->getResponse()->setStatusCode(400);
            return $this->getResponse();
        } 
        
        // Verifica logica
        $clientCourse = $this->getCourseService()->findAssociation($cc);
        if ($clientCourse->getAllowSignup() !== 1) {
            $this->logger->error("Il record con id ".$cc." non permette la registrazione da configurazione db");
            $this->getResponse()->setStatusCode(400);
            return $this->getResponse();
        }        
        
        
        $showExtraFields = $clientCourse->getEnableExtrafields();
        
        $showFacebookLogin = 0;
        
        $showExtraFields === 1 ? $flag = true : $flag = false;
        
	$form = new \Application\Form\StudentSignup($flag);
        
	$fb = new \Facebook\Facebook([
            'app_id' => $this->fbAppId,
            'app_secret' => $this->fbAppSecret,
            'default_graph_version' => 'v2.6',
            'persistent_data_handler' => 'session'
        ]);
		
        $helper = $fb->getRedirectLoginHelper();
		
        if (isset($_SESSION['fb_access_token'])) {
            $accessToken = $_SESSION['fb_access_token'];
	} else {
            try {
                $accessToken = $helper->getAccessToken();
            } catch (Facebook\Exceptions\FacebookResponseException $e) {
                echo 'Facebook graph error: '.$e->getMessage();
		exit;
            } catch (Facebook\Exceptions\FacebookSDKException $e) {
                echo "Facebook SDK error: ".$e->getMessage();
                exit;
            }
        }

        if (!isset($accessToken)) {

            // No access token
            $permissions = ['email'];
            $facebookLoginUrl = $helper->getLoginUrl('http://'.$_SERVER['SERVER_NAME'].'/signup/form',$permissions);
            $showFacebookLogin = 1;
        } else {
            $oAuth2Client = $fb->getOAuth2Client();
            $tokenMetadata = $oAuth2Client->debugToken($accessToken);
            $tokenMetadata->validateAppId($this->fbAppId);
            $tokenMetadata->validateExpiration();

            if (! $accessToken->isLongLived() ) {
                try {
                    $accessToken = $oAuth2Client->getLongLivedAccessToken($accessToken);
                } catch (Facebook\Exceptions\FacebookSDKException $e) {
                    echo "Error getting long-lived access token: ".$e->getMessage();
                    exit;
                }
            }

            $_SESSION['fb_access_token'] = $accessToken;

            $me = $fb->get('/me?field=id,first_name,last_name,picture,email',$accessToken);
            $plainOldArray = $me->getDecodedBody();
            $givenFirstName = $plainOldArray['first_name'];
            $givenLastName = $plainOldArray['last_name'];
            $givenEmailAddress = $plainOldArray['email'];
            
            $form->get('firstname')->setValue($givenFirstName);
            $form->get('lastname')->setValue($givenLastName);
            $form->get('email')->setValue($givenEmailAddress);
            
        }
        
        $request = $this->getRequest();
        if ($request->isPost()) {
            
            // Gestione post
            $postData = array_merge_recursive(
                $request->getPost()->toArray(),
                $request->getFiles()->toArray()
            );
            
            $form->setData($postData);
            
            if ($form->isValid()) {
                $showError = "";
                $registerRequest = array();
                $registerRequest['firstname'] = $this->getRequest()->getPost()['firstname'];
                $registerRequest['lastname'] = $this->getRequest()->getPost()['lastname'];
                $registerRequest['email'] = $this->getRequest()->getPost()['email'];
                
                $registerRequest['token'] = $_SESSION['fb_access_token'];
 
                if (null !== $this->getRequest()->getPost()['internal']) {
                    $registerRequest['internal'] = $this->getRequest()->getPost()['internal'];
                }
                
                if (null != $this->getRequest()->getPost()['role']) {
                    $registerRequest['role'] = $this->getRequest()->getPost()['role'];
                }
                
                $result = $this->getStudentService()->insertStudent($registerRequest);
                
                if ($result['success'] === 1) {
                    if ($result['to_landing'] === 1) {
                        // A landing page
                        $this->redirect()->toRoute('signup_landing');
                    } else {
                        // A sessione di esame: acquisire il token di esame e passare a redirect
                    }
                } else {
                    $showError = $result['errormessage'];
                }
            }
        }
	
        return array(
            'form' => $form,
            'showExtraFields' => $showExtraFields,
            'showFacebookLogin' => $showFacebookLogin,
            'showError' => $showError
        );
    }

    /**
     * @return ExamService
     */
    private function getExamService()
    {
        return $this->getServiceLocator()->get('ExamService');
    }
    
    /**
     * @return \Application\Service\CourseService
     */
    private function getCourseService()
    {
        return $this->getServiceLocator()->get('CourseService');
    }
    
    /**
     * @return \Application\Service\StudentService
     */
    private function getStudentService()
    {
        return $this->getServiceLocator()->get('StudentService');
    }
}
