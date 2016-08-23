<?php

namespace Application\Controller;

use Facebook\Facebook;

class AuthController extends \Zend\Mvc\Controller\AbstractActionController
{
	const REGULAR_REGISTRATION = 1;
	const FACEBOOK_REGISTRATION = 2;
	
	private $fbAppId = "asdasdasdasdasdasdas";
	private $fbAppSecret = "ssdfsdfsdfisdjfisjdfisjdfisjdf";
	
	public function signupAction()
	{
		$showFacebookLogin = 0;
		
		$form = new \Application\Form\StudentSignup();
	
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
			$facebookLoginUrl = $helper->getLoginUrl('http://'.$_SERVER['SERVER_NAME'].'/auth/signup',$permissions);
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
		}

		$request = $this->getRequest();
		
		if ($request->isPost()) {
			$showError = "";
			$registerRequest = array();
			$registerRequest['firstname'] = $this->getRequest()->getPost()['first_name'];
			$registerRequest['lastname'] = $this->getRequest()->getPost()['last_name'];
			$registerRequest['email'] = $this->getRequest()->getPost()['email'];
			$registerRequest['token'] = $this->getRequest()->getPost()['token'];
			
			$result = $this->getServiceLocator()->get('StudentService')->insert($registerRequest);
			if ($result['success'] === 1) {
				if ($result['to_landing'] === 1) {
					// A landing page
				} else {
					// A sessione di esame
				}
			} else {
				$showError = $result['errormessage'];
			}
		}
		
		return array(
			'form' => $form,
			'showFacebookLogin' => $showFacebookLogin,
			'showError' => $showError
		)
	}
}