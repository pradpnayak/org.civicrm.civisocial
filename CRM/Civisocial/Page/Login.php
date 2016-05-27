<?php

require_once 'CRM/Core/Page.php';
require_once 'CRM/Civisocial/Backend/Twitter.php';

class CRM_Civisocial_Page_Login extends CRM_Core_Page {

	function getBackendURI() {
		$backendURI = NULL;
		$path = CRM_Utils_System::currentPath();
		if (false !== strpos($path, '..')) {
			die("SECURITY FATAL: the url can't contain '..'. Please report the issue on the forum at civicrm.org");
		}
		$path = split('/', $path);

		if (!CRM_Utils_Array::value(3, $path)) {
			die("BACKEND ERROR: No backend found in request");
		} else {
			$backend = CRM_Utils_Array::value(3, $path);
			switch ($backend) {
				case "facebook":
					$enabled = civicrm_api3('setting', 'getvalue', array('group' => 'CiviSocial Account Credentials', 'name' => 'enable_facebook'));
					if ($enabled) {
						$facebook_client_id = civicrm_api3('setting', 'getvalue', array('group' => 'CiviSocial Account Credentials', 'name' => 'facebook_app_id'));
						$backendURI = "https://www.facebook.com/dialog/oauth?";
						$backendURI .= "client_id=" . $facebook_client_id;
						$backendURI .= "&redirect_uri=" . $this->getRedirectURI("facebook");
					}
					break;

				case "googleplus":
					$enabled = civicrm_api3('setting', 'getvalue', array('group' => 'CiviSocial Account Credentials', 'name' => 'enable_googleplus'));
					if ($enabled) {
						$googleplus_client_id = civicrm_api3('setting', 'getvalue', array('group' => 'CiviSocial Account Credentials', 'name' => 'google_plus_key'));
						$backendURI = "https://accounts.google.com/o/oauth2/auth?scope=email%20profile&response_type=code&";
						$backendURI .= "client_id=" . $googleplus_client_id;
						$backendURI .= "&redirect_uri=" . $this->getRedirectURI("googleplus");
					}
					break;

				case "twitter":
					$enabled = civicrm_api3('setting', 'getvalue', array('group' => 'CiviSocial Account Credentials', 'name' => 'enable_twitter'));
					if ($enabled) {
						$twitter_consumer_key = civicrm_api3('setting', 'getvalue', array('group' => 'CiviSocial Account Credentials', 'name' => 'twitter_consumer_key'));
						$twitter_consumer_secret = civicrm_api3('setting', 'getvalue', array('group' => 'CiviSocial Account Credentials', 'name' => 'twitter_consumer_secret'));

						$twitter = new Twitter($twitter_consumer_key, $twitter_consumer_secret, $this->getRedirectURI("twitter"));

						$backendURI = $twitter->getLoginURL();
					}
					break;
			}
		}
		return $backendURI;
	}

	function getRedirectURI($backend) {
		$redirectURI = NULL;
		if (!$backend) {
			die("BACKEND ERROR: No backend found in request");
		} else {
			$redirectURI = rawurldecode(CRM_Utils_System::url("civicrm/civisocial/" . $backend . "callback", NULL, TRUE));
		}
		return $redirectURI;
	}

	function run() {
		$session = CRM_Core_Session::singleton();
		if (array_key_exists("redirect", $_GET)) {
			$session->set("civisocialredirect", $_GET["redirect"]);
		}
		$redirectTo = $this->getBackendURI();
		//$session->set("userID", "2");
		if ($redirectTo) {
			return CRM_Utils_System::redirect($redirectTo);
		}
		$this->assign('status', "Backend Not Supported");
		parent::run();
	}

	/**
	* Encodes the string or array passed in a way compatible with OAuth.
	* If an array is passed each array value will will be encoded.
	*
	* @param mixed $data the scalar or array to encode
	* @return $data encoded in a way compatible with OAuth
	*/
	private function safe_encode($data) {
		if (is_array($data)) {
			return array_map(array($this, 'safe_encode'), $data);
		} else if (is_scalar($data)) {
			return str_ireplace(
				array('+', '%7E'),
				array(' ', '~'),
				rawurlencode($data)
			);
		} else {
			return '';
		}
	}
}