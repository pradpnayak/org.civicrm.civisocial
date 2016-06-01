<?php

require_once 'CRM/Core/Form.php';

/**
 * Civisocial Settings Form controller class
 *
 * @see http://wiki.civicrm.org/confluence/display/CRMDOC43/QuickForm+Reference
 */
class CRM_Civisocial_Form_Settings extends CRM_Admin_Form_Setting {
	protected $_settings = array(
		'enable_facebook' => CRM_Core_BAO_Setting::SYSTEM_PREFERENCES_NAME,  
		'facebook_app_id' => CRM_Core_BAO_Setting::SYSTEM_PREFERENCES_NAME,    
		'facebook_secret' => CRM_Core_BAO_Setting::SYSTEM_PREFERENCES_NAME,           
		'enable_googleplus' => CRM_Core_BAO_Setting::SYSTEM_PREFERENCES_NAME,
		'google_plus_key' => CRM_Core_BAO_Setting::SYSTEM_PREFERENCES_NAME,     
		'google_plus_secret' => CRM_Core_BAO_Setting::SYSTEM_PREFERENCES_NAME,
		'enable_twitter' => CRM_Core_BAO_Setting::SYSTEM_PREFERENCES_NAME,
		'twitter_consumer_key' => CRM_Core_BAO_Setting::SYSTEM_PREFERENCES_NAME,
		'twitter_consumer_secret' => CRM_Core_BAO_Setting::SYSTEM_PREFERENCES_NAME,
	);

  /**
   * Process the form submission.
   */
	public function preProcess() {
		CRM_Utils_System::setTitle(ts('CiviSocial OAuth Credential Preferences')); 
		parent::preProcess();   
	}

	/**
	 * Build the settings form
	 */
	public function buildQuickForm() {
		$formFields = array(
			ts('Facebook') => array('enable_facebook', 'facebook_app_id', 'facebook_secret'),
			ts('Google') => array('enable_googleplus', 'google_plus_key', 'google_plus_secret'),
			ts('Twitter') => array('enable_twitter', 'twitter_consumer_key', 'twitter_consumer_secret'),
		);
		$this->assign('formFields', $formFields);
		parent::buildQuickForm();
	}

	/**
	 * Process the form submission.
	 */
	public function postProcess() {
		parent::postProcess();
		CRM_Core_Session::setStatus(ts('CiviSocial preferences have been saved.'), ts('Saved'), 'success');
	}

}
