<?php
require_once 'CRM/Core/Page.php';
require_once 'CRM/Civisocial/BAO/CivisocialUser.php';
require_once 'CRM/Civisocial/Backend/Twitter.php';

class CRM_Civisocial_Page_TwitterCallback extends CRM_Core_Page {

	function get_response($apiURL, $node, $is_post, $params) {
		$url = $apiURL . "/" . $node;
		$urlparams = "";
		foreach ($params as $key => $value) {
			$urlparams .= $key . "=" . $value . "&";
		}
		if ($is_post == FALSE) {
			$url = $url . "?" . $urlparams;
		}
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		if ($is_post == TRUE) {
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $urlparams);
		} else {
			curl_setopt($ch, CURLOPT_POST, 0);
		}
		$response = curl_exec($ch);
		curl_close($ch);

		return json_decode($response, true);
	}

	function run() {
		$session = CRM_Core_Session::singleton();
		$request_origin = $session->get("civisocialredirect");
		if (!$request_origin) {
			$request_origin = CRM_Utils_System::url('civicrm', NULL, TRUE);
		}
		
		$twitter_consumer_key = civicrm_api3('setting', 'getvalue', array('group' => 'CiviSocial Account Credentials', 'name' => 'twitter_consumer_key'));
		$twitter_consumer_secret = civicrm_api3('setting', 'getvalue', array('group' => 'CiviSocial Account Credentials', 'name' => 'twitter_consumer_secret'));

		
		$currentPath = CRM_Utils_System::currentPath();
		$currentUrl = CRM_Utils_System::url($path, NULL, TRUE);

		$twitter = new Twitter($twitter_consumer_key, $twitter_consumer_secret, $currentUrl);

		if ($twitter->isAuthenticated()) {
			$userProfile = $twitter->getUserProfile();
			var_dump($userProfile);
			$contact_id = CRM_Civisocial_BAO_CivisocialUser::handle_twitter_data($userProfile, "");
			$this->assign('status', $contact_id);
			$session->set('userID', $contact_id);
			$session->set('backend', "Twitter");
			CRM_Core_Session::setStatus(ts('Login via Twitter successfull'), ts('Login Successfull'), 'success');
		} else {
			exit("Error processing your request");
		}

		return CRM_Utils_System::redirect($request_origin);
	}
}
