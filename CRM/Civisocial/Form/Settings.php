<?php

require_once 'CRM/Core/Form.php';

/**
 * Form controller class
 *
 * @see http://wiki.civicrm.org/confluence/display/CRMDOC43/QuickForm+Reference
 */
class CRM_Civisocial_Form_Settings extends CRM_Admin_Form_Setting {
	
	public $_settings = array(
		'enable_facebook' => 'CiviSocial Account Credentials',
		'facebook_app_id' => 'CiviSocial Account Credentials',
		'facebook_secret' => 'CiviSocial Account Credentials',
		'enable_googleplus' => 'CiviSocial Account Credentials',
		'google_plus_key' => 'CiviSocial Account Credentials',
		'google_plus_secret' => 'CiviSocial Account Credentials',
		'enable_twitter' => 'CiviSocial Account Credentials',
		'twitter_consumer_key' => 'CiviSocial Account Credentials',
		'twitter_consumer_secret' => 'CiviSocial Account Credentials',
	);

	/**
     * Build the settings form
     */
	public function buildQuickForm() {
		CRM_Utils_System::setTitle(ts('CiviSocial OAuth Credential Preferences'));

		parent::buildQuickForm();
	}

}
