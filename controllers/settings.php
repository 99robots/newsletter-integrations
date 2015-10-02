<?php

// Exit if accessed directly

if ( !defined( 'ABSPATH' ) ) exit;

// Check if class already exists

if (!class_exists("NNR_Newsletter_Integrations_Settings_v1")):

/* ================================================================================
 *
 * Base is the base class for Display Conditions to help with managing repetitive
 * tasks.
 *
 ================================================================================ */

if ( !class_exists('NNR_Newsletter_Integrations_Base_v1') ) {
	require_once( dirname(dirname(__FILE__)) . '/base.php');
}

/**
 * NNR_Newsletter_Integrations_Settings_v1 class.
 *
 * @extends NNR_Newsletter_Integrations_Base_v1
 */
class NNR_Newsletter_Integrations_Settings_v1 extends NNR_Newsletter_Integrations_Base_v1 {

	/**
	 * prefix
	 *
	 * (default value: '')
	 *
	 * @var string
	 * @access public
	 */
	public $prefix = '';

	/**
	 * text_domain
	 *
	 * (default value: '')
	 *
	 * @var string
	 * @access public
	 */
	public $text_domain = '';

	/**
	 * aweber_app_id
	 *
	 * (default value: '343d929f')
	 *
	 * @var string
	 * @access public
	 * @static
	 */
	private $aweber_app_id = '343d929f';

	/**
	 * Called when the object is first created
	 *
	 * @access public
	 * @param mixed $prefix
	 * @return void
	 */
	function __construct( $prefix = '', $text_domain = '' ) {

		$this->prefix = $prefix;
		$this->text_domain = $text_domain;

		$this->include_scripts();
	}

	/**
	 * Include all scripts needed for the settings
	 *
	 * @access public
	 * @return void
	 */
	function include_scripts() {

		wp_register_style( 'selectize-css', plugins_url( 'css/selectize.bootstrap3.css', dirname(__FILE__)) );
		wp_enqueue_style( 'selectize-css' );

		wp_register_script( 'selectize-js', plugins_url( 'js/selectize.min.js', dirname(__FILE__)), array('jquery') );
		wp_enqueue_script( 'selectize-js' );

		wp_enqueue_style('wp-color-picker');
		wp_enqueue_script('wp-color-picker');

		wp_register_script( 'newsletter-integrations-js', plugins_url( 'js/settings.js', dirname(__FILE__)), array('jquery', 'wp-color-picker') );
		wp_enqueue_script( 'newsletter-integrations-js' );
		wp_localize_script( 'newsletter-integrations-js', 'nnr_new_int_data' , array(
			'prefix'		=> $this->prefix,
			'ajaxurl'       => admin_url( 'admin-ajax.php' ),
		));

	}

	/**
	 * Display All Settings
	 *
	 * @access public
	 * @param mixed $newsletter_settings
	 * @param array array('default' (default: > array())
	 * @param array 'help-text' (default: > array()))
	 * @return void
	 */
	function display_all_settings( $newsletter_settings, $args = array('default' => array(), 'help-text' => array()) ) {

		echo $this->display_newsletter_service($newsletter_settings['newsletter']);
		echo $this->display_mailchimp_optin($newsletter_settings['mailchimp']['optin']);
		echo $this->display_mailchimp_apikey($newsletter_settings['mailchimp']['api_key']);
		echo $this->display_mailchimp_list($newsletter_settings['mailchimp']['list']);

		echo $this->display_aweber_actions($newsletter_settings['aweber']['access_key']);
		echo $this->display_aweber_consumer_key($newsletter_settings['aweber']['consumer_key']);
		echo $this->display_aweber_consumer_secret($newsletter_settings['aweber']['consumer_secret']);
		echo $this->display_aweber_access_key($newsletter_settings['aweber']['access_key']);
		echo $this->display_aweber_access_secret($newsletter_settings['aweber']['access_secret']);
		echo $this->display_aweber_list_id($newsletter_settings['aweber']['list_id']);

		echo $this->display_getresponse_api_key($newsletter_settings['getresponse']['api_key']);
		echo $this->display_getresponse_campaign($newsletter_settings['getresponse']['campaign']);

		echo $this->display_campaignmonitor_api_key($newsletter_settings['campaignmonitor']['api_key']);
		echo $this->display_campaignmonitor_client($newsletter_settings['campaignmonitor']['client']);
		echo $this->display_campaignmonitor_list($newsletter_settings['campaignmonitor']['list']);

		echo $this->display_madmimi_username($newsletter_settings['madmimi']['username']);
		echo $this->display_madmimi_api_key($newsletter_settings['madmimi']['api_key']);
		echo $this->display_madmimi_list($newsletter_settings['madmimi']['list']);

		echo $this->display_infusionsoft_app_id($newsletter_settings['infusionsoft']['app_id']);
		echo $this->display_infusionsoft_api_key($newsletter_settings['infusionsoft']['api_key']);
		echo $this->display_infusionsoft_list($newsletter_settings['infusionsoft']['list']);

		echo $this->display_mymail_list($newsletter_settings['mymail']['list']);

		echo $this->display_activecampaign_app_url($newsletter_settings['activecampaign']['app_url']);
		echo $this->display_activecampaign_api_key($newsletter_settings['activecampaign']['api_key']);
		echo $this->display_activecampaign_list($newsletter_settings['activecampaign']['list']);

		echo $this->display_feedburner_id($newsletter_settings['feedburner']['id']);

		echo $this->display_first_name_placeholder($newsletter_settings['first_name_placeholder']);
		echo $this->display_last_name_placeholder($newsletter_settings['last_name_placeholder']);
		echo $this->display_email_placeholder($newsletter_settings['email_placeholder']);

		echo $this->display_first_name($newsletter_settings['first_name']);
		echo $this->display_last_name($newsletter_settings['last_name']);

		echo $this->display_success_action($newsletter_settings['success_action']);
		echo $this->display_success_message($newsletter_settings['success_message']);
		echo $this->display_success_url($newsletter_settings['success_url']);

		echo $this->display_button_text($newsletter_settings['button_text']);
		echo $this->display_subscribe_icon($newsletter_settings['subscribe_icon']);
		echo $this->display_subscribe_icon_place($newsletter_settings['subscribe_icon_place']);
		echo $this->display_text_color($newsletter_settings['text_color']);
		echo $this->display_bg_color($newsletter_settings['bg_color']);

	}

	/**
	 * Display the setting to change the newsletter service
	 *
	 * @access public
	 * @param mixed $newsletter_service
	 * @param string $default (default: '')
	 * @param mixed $help_text (default: null)
	 * @return void
	 */
	function display_newsletter_service( $newsletter_service, $default = '', $help_text = null ) {

		if ( isset($help_text) ) {
			$help_text = '<em class="help-block">' . __($help_text, $this->text_domain) . '</em>';
		}

		$code = '<!-- Newsletter -->
		<div class="form-group">
			<label for="' . $this->prefix . 'newsletter" class="col-sm-3 control-label">' . __('Newsletter', $this->text_domain) . '</label>
			<div class="col-sm-9">
				<select id="' . $this->prefix . 'newsletter" name="' . $this->prefix . 'newsletter">
					<option value="wordpress" ' . selected('wordpress', $newsletter_service, false) . '>' . __('WordPress', $this->text_domain) . '</option>
					<option value="mailchimp" ' . selected('mailchimp', $newsletter_service, false) . '>' . __('MailChimp', $this->text_domain) . '</option>
					<option value="aweber" ' . selected('aweber', $newsletter_service, false) . '>' . __('Aweber', $this->text_domain) . '</option>
					<option value="getresponse" ' . selected('getresponse', $newsletter_service, false) . '>' . __('Get Response', $this->text_domain) . '</option>
					<option value="campaignmonitor" ' . selected('campaignmonitor', $newsletter_service, false) . '>' . __('Campaign Monitor', $this->text_domain) . '</option>
					<option value="madmimi" ' . selected('madmimi', $newsletter_service, false) . '>' . __('Mad Mimi', $this->text_domain) . '</option>
					<option value="infusionsoft" ' . selected('infusionsoft', $newsletter_service, false) . '>' . __('Infusionsoft', $this->text_domain) . '</option>
					<option value="mymail" ' . selected('mymail', $newsletter_service, false) . '>' . __('MyMail', $this->text_domain) . '</option>
					<option value="activecampaign" ' . selected('activecampaign', $newsletter_service, false) . '>' . __('Active Campaign', $this->text_domain) . '</option>
					<option value="feedburner" ' . selected('feedburner', $newsletter_service, false) . '>' . __('Feedburner', $this->text_domain) . '</option>
				</select>' .
				$help_text .
			'</div>
		</div>';

		return $code;
	}

	/**
	 * Display MailChimp Optin field
	 *
	 * @access public
	 * @param mixed $mailchimp_optin
	 * @param string $default (default: '')
	 * @param mixed $help_text (default: null)
	 * @return void
	 */
	function display_mailchimp_optin( $mailchimp_optin, $default = '', $help_text = null ) {

		if ( isset($help_text) ) {
			$help_text = '<em class="help-block">' . __($help_text, $this->text_domain) . '</em>';
		}

		$code = '<!-- MailChimp Optin In -->
		<div class="form-group ' . $this->prefix . 'newsletter ' . $this->prefix . 'newsletter-mailchimp">
			<label for="' . $this->prefix . 'newsletter-mailchimp-optin" class="col-sm-3 control-label">' . __('MailChimp Double Opt-in', $this->text_domain) . '</label>
			<div class="col-sm-9">
				<input id="' . $this->prefix . 'newsletter-mailchimp-optin" name="' . $this->prefix . 'newsletter-mailchimp-optin" type="checkbox" ' . (isset($mailchimp_optin) && $mailchimp_optin ? 'checked="checked"' : '') . '/>' .
				$help_text .
			'</div>
		</div>';

		return $code;

	}

	/**
	 * Display MailChimp API Key field
	 *
	 * @access public
	 * @param mixed $mailchimp_apikey
	 * @param string $default (default: '')
	 * @param mixed $help_text (default: null)
	 * @return void
	 */
	function display_mailchimp_apikey( $mailchimp_apikey, $default = '', $help_text = null ) {

		if ( isset($help_text) ) {
			$help_text = '<em class="help-block">' . __($help_text, $this->text_domain) . '</em>';
		}

		$code = '<!-- MailChimp API Key -->
		<div class="form-group ' . $this->prefix . 'newsletter ' . $this->prefix . 'newsletter-mailchimp">
			<label for="' . $this->prefix . 'newsletter-mailchimp-api-key" class="col-sm-3 control-label">' . __('MailChimp', $this->text_domain) . ' <a href="http://kb.mailchimp.com/accounts/management/about-api-keys" target="_blank">' . __('API Key', $this->text_domain) . '</a></label>
			<div class="col-sm-9">
				<input class="form-control" id="' . $this->prefix . 'newsletter-mailchimp-api-key" name="' . $this->prefix . 'newsletter-mailchimp-api-key" type="text" value="' . (isset($mailchimp_apikey) ? $mailchimp_apikey : '') . '" />' .
				$help_text .
			'</div>
		</div>';

		return $code;

	}

	/**
	 * Display MailChimp List field
	 *
	 * @access public
	 * @param mixed $mailchimp_list
	 * @param string $default (default: '')
	 * @param mixed $help_text (default: null)
	 * @return void
	 */
	function display_mailchimp_list( $mailchimp_list, $default = '', $help_text = null ) {

		if ( isset($help_text) ) {
			$help_text = '<em class="help-block">' . __($help_text, $this->text_domain) . '</em>';
		}

		$code = '<!-- MailChimp List -->
		<div class="form-group ' . $this->prefix . 'newsletter ' . $this->prefix . 'newsletter-mailchimp">
			<label for="' . $this->prefix . 'newsletter-mailchimp-list" class="col-sm-3 control-label">' . __('MailChimp List', $this->text_domain) . '</label>
			<div class="col-sm-9">
				<select id="' . $this->prefix . 'newsletter-mailchimp-list" name="' . $this->prefix . 'newsletter-mailchimp-list" data-list="' . (isset($mailchimp_list) ? $mailchimp_list : '') . '"></select>' .
				$help_text .
			'</div>
		</div>';

		return $code;

	}

	/**
	 * Display the Aweber Actions
	 *
	 * @access public
	 * @param mixed $aweber_access_key
	 * @return void
	 */
	function display_aweber_actions( $aweber_access_key ) {

		$code = '<!-- Aweber Actions -->
		<div class="form-group ' . $this->prefix . 'newsletter ' . $this->prefix . 'newsletter-aweber">
			<label class="col-sm-3 control-label">' . __('Step 1:', $this->text_domain) . '</label>
			<div class="col-sm-9">
				<a href="https://auth.aweber.com/1.0/oauth/authorize_app/' .  $this->aweber_app_id . '" target="_blank" class="btn btn-default ' . $this->prefix . 'newsletter-aweber-connect">' . (isset($aweber_access_key) && $aweber_access_key == '' ? __('Get Code', $this->text_domain) : __('Reconnect Account', $this->text_domain)) . '</a>
			</div>
		</div>

		<div class="form-group ' . $this->prefix . 'newsletter ' . $this->prefix . 'newsletter-aweber ' . (isset($aweber_access_key) && $aweber_access_key != '' ? 'hidden' : '') . '">
			<label class="col-sm-3 control-label">' . __('Step 2:', $this->text_domain) . '</label>
			<div class="col-sm-9">
				<input class="form-control" id="' . $this->prefix . 'newsletter-aweber-code" name="' . $this->prefix . 'newsletter-aweber-code" type="text" value=""/>
				<em class="help-block">' . __('Copy the authorization code here and wait for lists to populate', $this->text_domain) . '</em>
			</div>
		</div>';

		return $code;

	}

	/**
	 * Display Aweber Customer Key field
	 *
	 * @access public
	 * @param mixed $aweber_consumer_key
	 * @param string $default (default: '')
	 * @param mixed $help_text (default: null)
	 * @return void
	 */
	function display_aweber_consumer_key( $aweber_consumer_key, $default = '', $help_text = null ) {

		if ( isset($help_text) ) {
			$help_text = '<em class="help-block">' . __($help_text, $this->text_domain) . '</em>';
		}

		$code = '<!-- AWeber Customer Key -->
		<div class="hidden form-group ' . $this->prefix . 'newsletter ' . $this->prefix . 'newsletter-aweber">
			<label for="' . $this->prefix . 'newsletter-aweber-consumer-key" class="col-sm-3 control-label">' . __('Customer Key', $this->text_domain) . '</label>
			<div class="col-sm-9">
				<input class="form-control" id="' . $this->prefix . 'newsletter-aweber-consumer-key" name="' . $this->prefix . 'newsletter-aweber-consumer-key" type="text" value="' . (isset($aweber_customer_key) ? $aweber_customer_key :'') . '" />' .
				$help_text .
			'</div>
		</div>';

		return $code;

	}

	/**
	 * Display Aweber Consumer Secret field
	 *
	 * @access public
	 * @param mixed $aweber_consumer_secret
	 * @param string $default (default: '')
	 * @param mixed $help_text (default: null)
	 * @return void
	 */
	function display_aweber_consumer_secret( $aweber_consumer_secret, $default = '', $help_text = null ) {

		if ( isset($help_text) ) {
			$help_text = '<em class="help-block">' . __($help_text, $this->text_domain) . '</em>';
		}

		$code = '<!-- AWeber Customer Secret -->
		<div class="hidden form-group ' . $this->prefix . 'newsletter ' . $this->prefix . 'newsletter-aweber">
			<label for="' . $this->prefix . 'newsletter-aweber-consumer-secret" class="col-sm-3 control-label">' . __('Customer Secret', $this->text_domain) . '</label>
			<div class="col-sm-9">
				<input class="form-control" id="' . $this->prefix . 'newsletter-aweber-consumer-secret" name="' . $this->prefix . 'newsletter-aweber-consumer-secret" type="text" value="' . (isset($aweber_consumer_secret) ? $aweber_consumer_secret :'') . '" />' .
				$help_text .
			'</div>
		</div>';

		return $code;

	}

	/**
	 * Display Aweber Access Key field
	 *
	 * @access public
	 * @param mixed $aweber_access_key
	 * @param string $default (default: '')
	 * @param mixed $help_text (default: null)
	 * @return void
	 */
	function display_aweber_access_key( $aweber_access_key, $default = '', $help_text = null ) {

		if ( isset($help_text) ) {
			$help_text = '<em class="help-block">' . __($help_text, $this->text_domain) . '</em>';
		}

		$code = '<!-- AWeber Access Key -->
		<div class="hidden form-group ' . $this->prefix . 'newsletter ' . $this->prefix . 'newsletter-aweber">
			<label for="' . $this->prefix . 'newsletter-aweber-access-key" class="col-sm-3 control-label">' . __('Access Key', $this->text_domain) . '</label>
			<div class="col-sm-9">
				<input class="form-control" id="' . $this->prefix . 'newsletter-aweber-access-key" name="' . $this->prefix . 'newsletter-aweber-access-key" type="text" value="' . (isset($aweber_access_key) ? $aweber_access_key :'') . '" />' .
				$help_text .
			'</div>
		</div>';

		return $code;

	}

	/**
	 * Display Aweber Access Secret field
	 *
	 * @access public
	 * @param mixed $aweber_access_secret
	 * @param string $default (default: '')
	 * @param mixed $help_text (default: null)
	 * @return void
	 */
	function display_aweber_access_secret( $aweber_access_secret, $default = '', $help_text = null ) {

		if ( isset($help_text) ) {
			$help_text = '<em class="help-block">' . __($help_text, $this->text_domain) . '</em>';
		}

		$code = '<!-- AWeber Access Secret -->
		<div class="hidden form-group ' . $this->prefix . 'newsletter ' . $this->prefix . 'newsletter-aweber">
			<label for="' . $this->prefix . 'newsletter-aweber-access-secret" class="col-sm-3 control-label">' . __('Access Secret', $this->text_domain) . '</label>
			<div class="col-sm-9">
				<input class="form-control" id="' . $this->prefix . 'newsletter-aweber-access-secret" name="' . $this->prefix . 'newsletter-aweber-access-secret" type="text" value="' . (isset($aweber_access_secret) ? $aweber_access_secret :'') . '" />' .
				$help_text .
			'</div>
		</div>';

		return $code;

	}

	/**
	 *  Display Aweber List field
	 *
	 * @access public
	 * @param mixed $aweber_list_id
	 * @param string $default (default: '')
	 * @param mixed $help_text (default: null)
	 * @return void
	 */
	function display_aweber_list_id( $aweber_list_id, $default = '', $help_text = null ) {

		if ( isset($help_text) ) {
			$help_text = '<em class="help-block">' . __($help_text, $this->text_domain) . '</em>';
		}

		$code = '<!-- AWeber List ID -->
		<div class="form-group ' . $this->prefix . 'newsletter ' . $this->prefix . 'newsletter-aweber">
			<label for="' . $this->prefix . 'newsletter-aweber-list-id" class="col-sm-3 control-label">' . __('List ID', $this->text_domain) . '</label>
			<div class="col-sm-9">
				<select id="' . $this->prefix . 'newsletter-aweber-list-id" name="' . $this->prefix . 'newsletter-aweber-list-id" data-list="' . (isset($aweber_list_id) ? $aweber_list_id : '') . '"></select>' .
				$help_text .
			'</div>
		</div>';

		return $code;

	}

	/**
	 * Display Get Response API Key
	 *
	 * @access public
	 * @param mixed $getresponse_api_key
	 * @param string $default (default: '')
	 * @param mixed $help_text (default: null)
	 * @return void
	 */
	function display_getresponse_api_key( $getresponse_api_key, $default = '', $help_text = null ) {

		if ( isset($help_text) ) {
			$help_text = '<em class="help-block">' . __($help_text, $this->text_domain) . '</em>';
		}

		$code = '<!-- Get Response API Key -->
		<div class="form-group ' . $this->prefix . 'newsletter ' . $this->prefix . 'newsletter-getresponse">
			<label for="' . $this->prefix . 'newsletter-getresponse-api-key" class="col-sm-3 control-label">' . __('Get Response', $this->text_domain) . ' <a href="http://www.getresponse.com/learning-center/glossary/api-key.html" target="_blank">' . __('API Key', $this->text_domain) . '</a></label>
			<div class="col-sm-9">
				<input class="form-control" id="' . $this->prefix . 'newsletter-getresponse-api-key" name="' . $this->prefix . 'newsletter-getresponse-api-key" type="text" value="' . (isset($getresponse_api_key) ? $getresponse_api_key :'') . '"/>' .
				$help_text .
			'</div>
		</div>';

		return $code;

	}

	/**
	 * Display Get Response List
	 *
	 * @access public
	 * @param mixed $getresponse_campaign
	 * @param string $default (default: '')
	 * @param mixed $help_text (default: null)
	 * @return void
	 */
	function display_getresponse_campaign( $getresponse_campaign, $default = '', $help_text = null ) {

		if ( isset($help_text) ) {
			$help_text = '<em class="help-block">' . __($help_text, $this->text_domain) . '</em>';
		}

		$code = '<!-- Get Response List -->
		<div class="form-group ' . $this->prefix . 'newsletter ' . $this->prefix . 'newsletter-getresponse">
			<label for="' . $this->prefix . 'newsletter-getresponse-campaign" class="col-sm-3 control-label">' . __('Get Response List', $this->text_domain) . '</label>
			<div class="col-sm-9">
				<select id="' . $this->prefix . 'newsletter-getresponse-campaign" name="' . $this->prefix . 'newsletter-getresponse-campaign" data-campaign="' . (isset($getresponse_campaign) ? $getresponse_campaign : '' ) . '"></select>' .
				$help_text .
			'</div>
		</div>';

		return $code;

	}

	/**
	 * Display Campaign Monitor API Key
	 *
	 * @access public
	 * @param mixed $campaignmonitor_api_key
	 * @param string $default (default: '')
	 * @param mixed $help_text (default: null)
	 * @return void
	 */
	function display_campaignmonitor_api_key( $campaignmonitor_api_key, $default = '', $help_text = null ) {

		if ( isset($help_text) ) {
			$help_text = '<em class="help-block">' . __($help_text, $this->text_domain) . '</em>';
		}

		$code = '<!-- Campaign Monitor API Key -->
		<div class="form-group ' . $this->prefix . 'newsletter ' . $this->prefix . 'newsletter-campaignmonitor">
			<label for="' . $this->prefix . 'newsletter-campaignmonitor-api-key" class="col-sm-3 control-label">' . __('Campaign Monitor', $this->text_domain) . ' <a href="http://help.campaignmonitor.com/topic.aspx?t=206" target="_blank">' . __('API Key', $this->text_domain) . '</a></label>
			<div class="col-sm-9">
				<input class="form-control" id="' . $this->prefix . 'newsletter-campaignmonitor-api-key" name="' . $this->prefix . 'newsletter-campaignmonitor-api-key" type="text" value="' . (isset($campaignmonitor_api_key) ? $campaignmonitor_api_key : '' ) . '" />' .
				$help_text .
			'</div>
		</div>';

		return $code;

	}

	/**
	 * Display Campaign Monitor Campaign
	 *
	 * @access public
	 * @param mixed $campaignmonitor_client
	 * @param string $default (default: '')
	 * @param mixed $help_text (default: null)
	 * @return void
	 */
	function display_campaignmonitor_client( $campaignmonitor_client, $default = '', $help_text = null ) {

		if ( isset($help_text) ) {
			$help_text = '<em class="help-block">' . __($help_text, $this->text_domain) . '</em>';
		}

		$code = '<!-- Campaign Monitor Client -->
		<div class="form-group ' . $this->prefix . 'newsletter ' . $this->prefix . 'newsletter-campaignmonitor">
			<label for="' . $this->prefix . 'newsletter-campaignmonitor-client" class="col-sm-3 control-label">' . __('Campaign Monitor Client', $this->text_domain) . '</label>
			<div class="col-sm-9">
				<select id="' . $this->prefix . 'newsletter-campaignmonitor-client" name="' . $this->prefix . 'newsletter-campaignmonitor-client" data-client="' . (isset($campaignmonitor_client) ? $campaignmonitor_client : '') . '></select>' .
				$help_text .
			'</div>
		</div>';

		return $code;

	}

	/**
	 * Display Campaign Monitor List
	 *
	 * @access public
	 * @param mixed $campaignmonitor_list
	 * @param string $default (default: '')
	 * @param mixed $help_text (default: null)
	 * @return void
	 */
	function display_campaignmonitor_list( $campaignmonitor_list, $default = '', $help_text = null ) {

		if ( isset($help_text) ) {
			$help_text = '<em class="help-block">' . __($help_text, $this->text_domain) . '</em>';
		}

		$code = '<!-- Campaign Monitor List -->
		<div class="form-group ' . $this->prefix . 'newsletter ' . $this->prefix . 'newsletter-campaignmonitor">
			<label for="' . $this->prefix . 'newsletter-campaignmonitor-list" class="col-sm-3 control-label">' . __('Campaign Monitor List', $this->text_domain) . '</label>
			<div class="col-sm-9">
				<select id="' . $this->prefix . 'newsletter-campaignmonitor-list" name="' . $this->prefix . 'newsletter-campaignmonitor-list" data-list="' . (isset($campaignmonitor_list) ? $campaignmonitor_list : '') . '"></select>' .
				$help_text .
			'</div>
		</div>';

		return $code;

	}

	/**
	 * Display Madmimi Username field
	 *
	 * @access public
	 * @param mixed $madmimi_username
	 * @param string $default (default: '')
	 * @param mixed $help_text (default: null)
	 * @return void
	 */
	function display_madmimi_username( $madmimi_username, $default = '', $help_text = null ) {

		if ( isset($help_text) ) {
			$help_text = '<em class="help-block">' . __($help_text, $this->text_domain) . '</em>';
		}

		$code = '<!-- Mad Mimi Username -->
		<div class="form-group ' . $this->prefix . 'newsletter ' . $this->prefix . 'newsletter-madmimi">
			<label for="' . $this->prefix . 'newsletter-madmimi-username" class="col-sm-3 control-label">' . __('Mad Mimi Username / Email', $this->text_domain) . '</label>
			<div class="col-sm-9">
				<input class="form-control" id="' . $this->prefix . 'newsletter-madmimi-username" name="' . $this->prefix . 'newsletter-madmimi-username" type="text" value="' . (isset($madmimi_username) ? $madmimi_username :'') . '" />' .
				$help_text .
			'</div>
		</div>';

		return $code;

	}

	/**
	 * Display Madmimi API Key
	 *
	 * @access public
	 * @param mixed $madmimi_api_key
	 * @param string $default (default: '')
	 * @param mixed $help_text (default: null)
	 * @return void
	 */
	function display_madmimi_api_key( $madmimi_api_key, $default = '', $help_text = null ) {

		if ( isset($help_text) ) {
			$help_text = '<em class="help-block">' . __($help_text, $this->text_domain) . '</em>';
		}

		$code = '<!-- Mad Mimi API Key -->
		<div class="form-group ' . $this->prefix . 'newsletter ' . $this->prefix . 'newsletter-madmimi">
			<label for="' . $this->prefix . 'newsletter-madmimi-api-key" class="col-sm-3 control-label">' . __('Mad Mimi', $this->text_domain) . ' <a href="http://help.madmimi.com/where-can-i-find-my-api-key/" target="_blank">' . __('API Key', $this->text_domain) . '</a></label>
			<div class="col-sm-9">
				<input class="form-control" id="' . $this->prefix . 'newsletter-madmimi-api-key" name="' . $this->prefix . 'newsletter-madmimi-api-key" type="text" value="' . (isset($madmimi_api_key) ? $madmimi_api_key :'') . '" />' .
				$help_text .
			'</div>
		</div>';

		return $code;

	}

	/**
	 * Display Madmimi list field
	 *
	 * @access public
	 * @param mixed $madmimi_list
	 * @param string $default (default: '')
	 * @param mixed $help_text (default: null)
	 * @return void
	 */
	function display_madmimi_list( $madmimi_list, $default = '', $help_text = null ) {

		if ( isset($help_text) ) {
			$help_text = '<em class="help-block">' . __($help_text, $this->text_domain) . '</em>';
		}

		$code = '<!-- Mad Mimi List -->
		<div class="form-group ' . $this->prefix . 'newsletter ' . $this->prefix . 'newsletter-madmimi">
			<label for="' . $this->prefix . 'newsletter-madmimi-list" class="col-sm-3 control-label">' . __('Mad Mimi List', $this->text_domain) . '</label>
			<div class="col-sm-9">
				<select id="' . $this->prefix . 'newsletter-madmimi-list" name="' . $this->prefix . 'newsletter-madmimi-list" data-list="' . (isset($madmimi_list) ? $madmimi_list : '') . '"></select>' .
				$help_text .
			'</div>
		</div>';

		return $code;

	}

	/**
	 * Display Infusionsoft App ID
	 *
	 * @access public
	 * @param mixed $infusionsoft_app_id
	 * @param string $default (default: '')
	 * @param mixed $help_text (default: null)
	 * @return void
	 */
	function display_infusionsoft_app_id( $infusionsoft_app_id, $default = '', $help_text = null ) {

		if ( isset($help_text) ) {
			$help_text = '<em class="help-block">' . __($help_text, $this->text_domain) . '</em>';
		}

		$code = '<!-- Infusionsoft App ID -->
		<div class="form-group ' . $this->prefix . 'newsletter ' . $this->prefix . 'newsletter-infusionsoft">
			<label for="' . $this->prefix . 'newsletter-infusionsoft-app-id" class="col-sm-3 control-label">' . __('Infusionsoft App ID', $this->text_domain) . '</label>
			<div class="col-sm-9">
				<input class="form-control" id="' . $this->prefix . 'newsletter-infusionsoft-app-id" name="' . $this->prefix . 'newsletter-infusionsoft-app-id" type="text" value="' . (isset($infusionsoft_app_id) ? $infusionsoft_app_id :'') . '"/>' .
				$help_text .
			'</div>
		</div>';

		return $code;

	}

	/**
	 * Display Infusionsoft API Key
	 *
	 * @access public
	 * @param mixed $infusionsoft_api_key
	 * @param string $default (default: '')
	 * @param mixed $help_text (default: null)
	 * @return void
	 */
	function display_infusionsoft_api_key( $infusionsoft_api_key, $default = '', $help_text = null ) {

		if ( isset($help_text) ) {
			$help_text = '<em class="help-block">' . __($help_text, $this->text_domain) . '</em>';
		}

		$code = '<!-- Infusionsoft API Key -->
		<div class="form-group ' . $this->prefix . 'newsletter ' . $this->prefix . 'newsletter-infusionsoft">
			<label for="' . $this->prefix . 'newsletter-infusionsoft-api-key" class="col-sm-3 control-label">' . __('Infusionsoft', $this->text_domain) . ' <a href="http://ug.infusionsoft.com/article/AA-00442/0/How-do-I-enable-the-Infusionsoft-API-and-generate-an-API-Key.html" target="_blank">' . __('API Key', $this->text_domain) . '</a></label>
			<div class="col-sm-9">
				<input class="form-control" id="' . $this->prefix . 'newsletter-infusionsoft-api-key" name="' . $this->prefix . 'newsletter-infusionsoft-api-key" type="text" value="' . (isset($infusionsoft_api_key) ? $infusionsoft_api_key :'') . '" />' .
				$help_text .
			'</div>
		</div>';

		return $code;

	}

	/**
	 * Display Infusionsoft List
	 *
	 * @access public
	 * @param mixed $infusionsoft_list
	 * @param string $default (default: '')
	 * @param mixed $help_text (default: null)
	 * @return void
	 */
	function display_infusionsoft_list( $infusionsoft_list, $default = '', $help_text = null ) {

		if ( isset($help_text) ) {
			$help_text = '<em class="help-block">' . __($help_text, $this->text_domain) . '</em>';
		}

		$code = '<!-- Infusionsoft List -->
		<div class="form-group ' . $this->prefix . 'newsletter ' . $this->prefix . 'newsletter-infusionsoft">
			<label for="' . $this->prefix . 'newsletter-infusionsoft-list" class="col-sm-3 control-label">' . __('Infusionsoft List', $this->text_domain) . '</label>
			<div class="col-sm-9">
				<select id="' . $this->prefix . 'newsletter-infusionsoft-list" name="' . $this->prefix . 'newsletter-infusionsoft-list" data-list="' . (isset($infusionsoft_list) ? $infusionsoft_list : '') . '"></select>' .
				$help_text .
			'</div>
		</div>';

		return $code;

	}

	/**
	 * Display MyMail List
	 *
	 * @access public
	 * @param mixed $mymail_list
	 * @param string $default (default: '')
	 * @param mixed $help_text (default: null)
	 * @return void
	 */
	function display_mymail_list( $mymail_list, $default = '', $help_text = null ) {

		if ( isset($help_text) ) {
			$help_text = '<em class="help-block">' . __($help_text, $this->text_domain) . '</em>';
		}

		$code = '<!-- MyMail List -->
		<div class="form-group ' . $this->prefix . 'newsletter ' . $this->prefix . 'newsletter-mymail">
			<label for="' . $this->prefix . 'newsletter-aweber-list-id" class="col-sm-3 control-label">' . __('MyMail List', $this->text_domain) . '</label>
			<div class="col-sm-9">
				<select id="' . $this->prefix . 'newsletter-mymail-list" name="' . $this->prefix . 'newsletter-mymail-list" data-list="' . (isset($mymail_list) ? $mymail_list : '') . '"></select>' .
				$help_text .
			'</div>
		</div>';

		return $code;

	}

	/**
	 * Display Active Campaign APP URL
	 *
	 * @access public
	 * @param mixed $activecampaign_app_url
	 * @param string $default (default: '')
	 * @param mixed $help_text (default: null)
	 * @return void
	 */
	function display_activecampaign_app_url( $activecampaign_app_url, $default = '', $help_text = null ) {

		if ( isset($help_text) ) {
			$help_text = '<em class="help-block">' . __($help_text, $this->text_domain) . '</em>';
		}

		$code = '<!-- Active Campaign App URL -->
		<div class="form-group ' . $this->prefix . 'newsletter ' . $this->prefix . 'newsletter-activecampaign">
			<label for="' . $this->prefix . 'newsletter-activecampaign-app-url" class="col-sm-3 control-label">' . __('Active Campaign', $this->text_domain) . ' <a href="http://www.activecampaign.com/help/using-the-api/" target="_blank">' . __('API URL', $this->text_domain) . '</a></label>
			<div class="col-sm-9">
				<input class="form-control" id="' . $this->prefix . 'newsletter-activecampaign-app-url" name="' . $this->prefix . 'newsletter-activecampaign-app-url" type="text" value="' . (isset($activecampaign_app_url) ? $activecampaign_app_url :'') . '" />' .
				$help_text .
			'</div>
		</div>';

		return $code;

	}

	/**
	 * Display Active Campaign API Key
	 *
	 * @access public
	 * @param mixed $activecampaign_api_key
	 * @param string $default (default: '')
	 * @param mixed $help_text (default: null)
	 * @return void
	 */
	function display_activecampaign_api_key( $activecampaign_api_key, $default = '', $help_text = null ) {

		if ( isset($help_text) ) {
			$help_text = '<em class="help-block">' . __($help_text, $this->text_domain) . '</em>';
		}

		$code = '<!-- Active Campaign API Key -->
		<div class="form-group ' . $this->prefix . 'newsletter ' . $this->prefix . 'newsletter-activecampaign">
			<label for="' . $this->prefix . 'newsletter-activecampaign-api-key" class="col-sm-3 control-label">' . __('Active Campaign', $this->text_domain) . ' <a href="http://www.activecampaign.com/help/using-the-api/" target="_blank">' . __('API Key', $this->text_domain) . '</a></label>
			<div class="col-sm-9">
				<input class="form-control" id="' . $this->prefix . 'newsletter-activecampaign-api-key" name="' . $this->prefix . 'newsletter-activecampaign-api-key" type="text" value="' . (isset($activecampaign_api_key) ? $activecampaign_api_key :'') . '" />' .
				$help_text .
			'</div>
		</div>';

		return $code;

	}

	/**
	 * Display Active Campaign List
	 *
	 * @access public
	 * @param mixed $activecampaign_list
	 * @param string $default (default: '')
	 * @param mixed $help_text (default: null)
	 * @return void
	 */
	function display_activecampaign_list( $activecampaign_list, $default = '', $help_text = null ) {

		if ( isset($help_text) ) {
			$help_text = '<em class="help-block">' . __($help_text, $this->text_domain) . '</em>';
		}

		$code = '<!-- Active Campaign List -->
		<div class="form-group ' . $this->prefix . 'newsletter ' . $this->prefix . 'newsletter-activecampaign">
			<label for="' . $this->prefix . 'newsletter-activecampaign-list" class="col-sm-3 control-label">' . __('Active Campaign List', $this->text_domain) . '</label>
			<div class="col-sm-9">
				<select id="' . $this->prefix . 'newsletter-activecampaign-list" name="' . $this->prefix . 'newsletter-activecampaign-list" data-list="' . (isset($activecampaign_list) ? $activecampaign_list : '') . '"></select>' .
				$help_text .
			'</div>
		</div>';

		return $code;

	}

	/**
	 * Display Feedburner ID
	 *
	 * @access public
	 * @param mixed $feedburner_id
	 * @param string $default (default: '')
	 * @param mixed $help_text (default: null)
	 * @return void
	 */
	function display_feedburner_id( $feedburner_id, $default = '', $help_text = null ) {

		if ( isset($help_text) ) {
			$help_text = '<em class="help-block">' . __($help_text, $this->text_domain) . '</em>';
		}

		$code = '<!-- Feedburner -->
		<div class="form-group ' . $this->prefix . 'newsletter ' . $this->prefix . 'newsletter-feedburner">
			<label for="' . $this->prefix . 'newsletter-aweber-list-id" class="col-sm-3 control-label">' . __('List ID', $this->text_domain) . '</label>
			<div class="col-sm-9">
				<input class="form-control" id="' . $this->prefix . 'newsletter-feedburner-id" name="' . $this->prefix . 'newsletter-feedburner-id" type="text" value="' .  (isset($feedburner_id) ? $feedburner_id :'') . '" />' .
				$help_text .
			'</div>
		</div>';

		return $code;

	}

	/**
	 * Display First Name Placeholder field
	 *
	 * @access public
	 * @param mixed $first_name_placeholder
	 * @param string $default (default: '')
	 * @param mixed $help_text (default: null)
	 * @return void
	 */
	function display_first_name_placeholder( $first_name_placeholder, $default = '', $help_text = null ){

		if ( isset($help_text) ) {
			$help_text = '<em class="help-block">' . __($help_text, $this->text_domain) . '</em>';
		}

		$code = '<!-- First Name Placeholder -->
		<div class="form-group ' . $this->prefix . 'newsletter ' . $this->prefix . 'newsletter-wordpress ' . $this->prefix . 'newsletter-mailchimp ' . $this->prefix . 'newsletter-getresponse ' . $this->prefix . 'newsletter-campaignmonitor ' . $this->prefix . 'newsletter-madmimi ' . $this->prefix . 'newsletter-infusionsoft ' . $this->prefix . 'newsletter-mymail ' . $this->prefix . 'newsletter-activecampaign">
			<label for="' . $this->prefix . 'newsletter-first-name-placeholder" class="col-sm-3 control-label">' . __('First Name Placeholder Text', $this->text_domain) . '</label>
			<div class="col-sm-9">
				<input class="form-control" id="' . $this->prefix . 'newsletter-first-name-placeholder" name="' . $this->prefix . 'newsletter-first-name-placeholder" type="text" value="' . (isset($first_name_placeholder) ? esc_attr($first_name_placeholder) : __('First Name', $this->text_domain)) . '"/>' .
				$help_text .
			'</div>
		</div>';

		return $code;

	}

	/**
	 * Display Last Name Placeholder field
	 *
	 * @access public
	 * @param mixed $last_name_placeholder
	 * @param string $default (default: '')
	 * @param mixed $help_text (default: null)
	 * @return void
	 */
	function display_last_name_placeholder( $last_name_placeholder, $default = '', $help_text = null ){

		if ( isset($help_text) ) {
			$help_text = '<em class="help-block">' . __($help_text, $this->text_domain) . '</em>';
		}

		$code = '<!-- Last Name Placeholder -->
		<div class="form-group ' . $this->prefix . 'newsletter ' . $this->prefix . 'newsletter-wordpress ' . $this->prefix . 'newsletter-mailchimp ' . $this->prefix . 'newsletter-getresponse ' . $this->prefix . 'newsletter-campaignmonitor ' . $this->prefix . 'newsletter-madmimi ' . $this->prefix . 'newsletter-infusionsoft ' . $this->prefix . 'newsletter-mymail ' . $this->prefix . 'newsletter-activecampaign">
			<label for="' . $this->prefix . 'newsletter-last-name-placeholder" class="col-sm-3 control-label">' . __('Last Name Placeholder Text', $this->text_domain) . '</label>
			<div class="col-sm-9">
				<input class="form-control" id="' . $this->prefix . 'newsletter-last-name-placeholder" name="' . $this->prefix . 'newsletter-last-name-placeholder" type="text" value="' .  (isset($last_name_placeholder) ? esc_attr($last_name_placeholder) : __('Last Name', $this->text_domain)) . '"/>' .
				$help_text .
			'</div>
		</div>';

		return $code;

	}

	/**
	 * Display Email Placeholder field
	 *
	 * @access public
	 * @param mixed $email_placeholder
	 * @param string $default (default: '')
	 * @param mixed $help_text (default: null)
	 * @return void
	 */
	function display_email_placeholder( $email_placeholder, $default = '', $help_text = null ){

		if ( isset($help_text) ) {
			$help_text = '<em class="help-block">' . __($help_text, $this->text_domain) . '</em>';
		}

		$code = '<!-- Email Placeholder -->
		<div class="form-group">
			<label for="' . $this->prefix . 'newsletter-email-placeholder" class="col-sm-3 control-label">' . __('Email Placeholder Text', $this->text_domain) . '</label>
			<div class="col-sm-9">
				<input class="form-control" id="' . $this->prefix . 'newsletter-email-placeholder" name="' . $this->prefix . 'newsletter-email-placeholder" type="text" value="' .  (isset($email_placeholder) ? esc_attr($email_placeholder) : __('Email', $this->text_domain)) . '"/>' .
				$help_text .
			'</div>
		</div>';

		return $code;

	}

	/**
	 * Display First Name Field
	 *
	 * @access public
	 * @param mixed $first_name
	 * @param string $default (default: '')
	 * @param mixed $help_text (default: null)
	 * @return void
	 */
	function display_first_name( $first_name, $default = '', $help_text = null ){

		if ( isset($help_text) ) {
			$help_text = '<em class="help-block">' . __($help_text, $this->text_domain) . '</em>';
		}

		$code = '<!-- First Name -->
		<div class="form-group ' . $this->prefix . 'newsletter ' . $this->prefix . 'newsletter-wordpress ' . $this->prefix . 'newsletter-mailchimp ' . $this->prefix . 'newsletter-getresponse ' . $this->prefix . 'newsletter-campaignmonitor ' . $this->prefix . 'newsletter-madmimi ' . $this->prefix . 'newsletter-infusionsoft">
			<label for="' . $this->prefix . 'newsletter-first-name" class="col-sm-3 control-label">' . __('First Name', $this->text_domain) . '</label>
			<div class="col-sm-9">
				<input id="' . $this->prefix . 'newsletter-first-name" name="' . $this->prefix . 'newsletter-first-name" type="checkbox" ' .  (isset($first_name) && $first_name ? 'checked="checked"' : '') . '/>' .
				$help_text .
			'</div>
		</div>';

		return $code;

	}

	/**
	 * Display Last Name Field
	 *
	 * @access public
	 * @param mixed $last_name
	 * @param string $default (default: '')
	 * @param mixed $help_text (default: null)
	 * @return void
	 */
	function display_last_name( $last_name, $default = '', $help_text = null ){

		if ( isset($help_text) ) {
			$help_text = '<em class="help-block">' . __($help_text, $this->text_domain) . '</em>';
		}

		$code = '<!-- Last Name -->
		<div class="form-group ' . $this->prefix . 'newsletter ' . $this->prefix . 'newsletter-wordpress ' . $this->prefix . 'newsletter-mailchimp ' . $this->prefix . 'newsletter-getresponse ' . $this->prefix . 'newsletter-campaignmonitor ' . $this->prefix . 'newsletter-madmimi ' . $this->prefix . 'newsletter-infusionsoft ' . $this->prefix . 'newsletter-mymail ' . $this->prefix . 'newsletter-activecampaign">
			<label for="' . $this->prefix . 'newsletter-last-name" class="col-sm-3 control-label">' . __('Last Name', $this->text_domain) . '</label>
			<div class="col-sm-9">
				<input id="' . $this->prefix . 'newsletter-last-name" name="' . $this->prefix . 'newsletter-last-name" type="checkbox" ' .  (isset($last_name) && $last_name ? 'checked="checked"' : '') . '/>' .
				$help_text .
			'</div>
		</div>';

		return $code;

	}

	/**
	 * Display the Succes Action Field
	 *
	 * @access public
	 * @param mixed $success_action
	 * @param string $default (default: '')
	 * @param mixed $help_text (default: null)
	 * @return void
	 */
	function display_success_action( $success_action, $default = '', $help_text = null ){

		if ( isset($help_text) ) {
			$help_text = '<em class="help-block">' . __($help_text, $this->text_domain) . '</em>';
		}

		$code = '<!-- Success Action -->
		<div class="form-group ' . $this->prefix . 'newsletter-div ' . $this->prefix . 'newsletter-wordPress-div ' . $this->prefix . 'newsletter-mailchimp-div ' . $this->prefix . 'newsletter-aweber-div ' . $this->prefix . 'newsletter-getresponse-div ' . $this->prefix . 'newsletter-campaignmonitor-div ' . $this->prefix . 'newsletter-madmimi-div">
			<label for="' . $this->prefix . 'newsletter-success-action" class="col-sm-3 control-label">' . __('Success Action', $this->text_domain) . '</label>
			<div class="col-sm-9">
				<select id="' . $this->prefix . 'newsletter-success-action" name="' . $this->prefix . 'newsletter-success-action">
					<option value="message" ' . selected('message', $success_action, false) . '>' . __('Show a message', $this->text_domain) . '</option>
					<option value="redirect" ' .  selected('redirect', $success_action, false) . '>' . __('Redirect to another page', $this->text_domain) . '</option>
				</select>' .
				$help_text .
			'</div>
		</div>';

		return $code;

	}

	/**
	 * Display the Success Message Field
	 *
	 * @access public
	 * @param mixed $success_message
	 * @param string $default (default: '')
	 * @param mixed $help_text (default: null)
	 * @return void
	 */
	function display_success_message( $success_message, $default = '', $help_text = null ){

		if ( isset($help_text) ) {
			$help_text = '<em class="help-block">' . __($help_text, $this->text_domain) . '</em>';
		}

		$code = '<!-- Success Message -->
		<div class="form-group ' . $this->prefix . 'newsletter ' . $this->prefix . 'newsletter-wordPress ' . $this->prefix . 'newsletter-mailchimp ' . $this->prefix . 'newsletter-getresponse ' . $this->prefix . 'newsletter-campaignmonitor ' . $this->prefix . 'newsletter-madmimi ' . $this->prefix . 'newsletter-success-action ' . $this->prefix . 'newsletter-success-action-message">
			<label for="' . $this->prefix . 'newsletter-success-message" class="col-sm-3 control-label">' . __('Success Message', $this->text_domain) . '</label>
			<div class="col-sm-9">
				<input class="form-control" id="' . $this->prefix . 'newsletter-success-message" name="' . $this->prefix . 'newsletter-success-message" type="text" value="' .  (isset($success_message) ? esc_attr($success_message) : __('Welcome to the community!', $this->text_domain)) . '" />' .
				$help_text .
			'</div>
		</div>';

		return $code;

	}

	/**
	 * Display the Success URL field
	 *
	 * @access public
	 * @param mixed $success_url
	 * @param string $default (default: '')
	 * @param mixed $help_text (default: null)
	 * @return void
	 */
	function display_success_url( $success_url, $default = '', $help_text = null ){

		if ( isset($help_text) ) {
			$help_text = '<em class="help-block">' . __($help_text, $this->text_domain) . '</em>';
		}

		$code = '<!-- Success URL -->
		<div class="form-group ' . $this->prefix . 'newsletter ' . $this->prefix . 'newsletter-wordPress ' . $this->prefix . 'newsletter-mailchimp ' . $this->prefix . 'newsletter-getresponse ' . $this->prefix . 'newsletter-campaignmonitor ' . $this->prefix . 'newsletter-madmimi ' . $this->prefix . 'newsletter-success-action ' . $this->prefix . 'newsletter-success-action-redirect">
			<label for="' . $this->prefix . 'newsletter-success-url" class="col-sm-3 control-label">' . __('Success URL', $this->text_domain) . '</label>
			<div class="col-sm-9">
				<input class="form-control" id="' . $this->prefix . 'newsletter-success-url" name="' . $this->prefix . 'newsletter-success-url" type="text" value="' .  (isset($success_url) ? esc_url_raw($success_url) : get_site_url()) . '" />' .
				$help_text .
			'</div>
		</div>';

		return $code;

	}

	/**
	 * Display Button Text Field
	 *
	 * @access public
	 * @param mixed $button_text
	 * @param string $default (default: '')
	 * @param mixed $help_text (default: null)
	 * @return void
	 */
	function display_button_text( $button_text, $default = '', $help_text = null ) {

		if ( isset($help_text) ) {
			$help_text = '<em class="help-block">' . __($help_text, $this->text_domain) . '</em>';
		}

		$code = '<!-- Button Text -->
		<div class="form-group">
			<label for="' . $this->prefix . 'newsletter-button-text" class="col-sm-3 control-label">' . __('Button Text', $this->text_domain) . '</label>
			<div class="col-sm-9">
				<input class="form-control" id="' . $this->prefix . 'newsletter-button-text" name="' . $this->prefix . 'newsletter-button-text" type="text" value="' .  (isset($button_text) ? esc_attr($button_text) : __('Subscribe', $this->text_domain)). '"/>' .
				$help_text .
			'</div>
		</div>';

		return $code;

	}

	/**
	 * Display Subscribe Icon Field
	 *
	 * @access public
	 * @param mixed $subscribe_icon
	 * @param string $default (default: '')
	 * @param mixed $help_text (default: null)
	 * @return void
	 */
	function display_subscribe_icon( $subscribe_icon, $default = '', $help_text = null ) {

		if ( isset($help_text) ) {
			$help_text = '<em class="help-block">' . __($help_text, $this->text_domain) . '</em>';
		}

		$code = '<!-- Subscribe Icon -->
		<div class="form-group">
			<label for="' . $this->prefix . 'newsletter-subscribe-icon" class="col-sm-3 control-label">' . __('Subscribe Icon', $this->text_domain) . '</label>
			<div class="col-sm-9">
				<button class="btn btn-default" name="' . $this->prefix . 'newsletter-subscribe-icon" data-iconset="fontawesome" data-icon="' .  (isset($subscribe_icon) ? $subscribe_icon : 'fa-paper-plane') . '" role="iconpicker"></button>' .
				$help_text .
			'</div>
		</div>';

		return $code;

	}

	/**
	 * Display Subscribe Icon Place Field
	 *
	 * @access public
	 * @param mixed $subscribe_icon_place
	 * @param string $default (default: '')
	 * @param mixed $help_text (default: null)
	 * @return void
	 */
	function display_subscribe_icon_place( $subscribe_icon_place, $default = '', $help_text = null ) {

		if ( isset($help_text) ) {
			$help_text = '<em class="help-block">' . __($help_text, $this->text_domain) . '</em>';
		}

		$code = '<!-- Subscribe Icon Place -->
		<div class="form-group">
			<label for="' . $this->prefix . 'newsletter-subscribe-icon-place" class="col-sm-3 control-label">' . __('Subscribe Icon Place', $this->text_domain) . '</label>
			<div class="col-sm-9">
				<div class="btn-group" data-toggle="buttons">
					<label class="btn btn-default ' . (isset($subscribe_icon_place) && $subscribe_icon_place == 'before' ? 'active' : '') . '" for="' . $this->prefix . 'newsletter-subscribe-icon-place">
						<input type="radio" name="' . $this->prefix . 'newsletter-subscribe-icon-place" id="' . $this->prefix . 'newsletter-subscribe-icon-place-before" value="before" ' . (isset($subscribe_icon_place) && $subscribe_icon_place == 'before' ? 'checked="checked"' : '') . '/> <i class="fa fa-outdent"></i> ' . __('Before', $this->text_domain) . '
					</label>
					<label class="btn btn-default ' . (isset($subscribe_icon_place) && $subscribe_icon_place == 'after' ? 'active' : '') . '" for="' . $this->prefix . 'newsletter-subscribe-icon-place">
						<input type="radio" name="' . $this->prefix . 'newsletter-subscribe-icon-place" id="' . $this->prefix . 'newsletter-subscribe-icon-place-after" value="after" ' . (isset($subscribe_icon_place) && $subscribe_icon_place == 'after' ? 'checked="checked"' : '') . '/> ' . __('After', $this->text_domain) . ' <i class="fa fa-indent"></i>
					</label>
				</div>' .
				$help_text .
			'</div>
		</div>';

		return $code;

	}

	/**
	 * Display Text Color Field
	 *
	 * @access public
	 * @param mixed $text_color
	 * @param string $default (default: '')
	 * @param mixed $help_text (default: null)
	 * @return void
	 */
	function display_text_color( $text_color, $default = '', $help_text = null ) {

		if ( isset($help_text) ) {
			$help_text = '<em class="help-block">' . __($help_text, $this->text_domain) . '</em>';
		}

		$code = '<!-- Subscribe Button Text Color -->
		<div class="form-group">
			<label for="' . $this->prefix . 'newsletter-text-color" class="col-sm-3 control-label">' . __('Subscribe Button Text Color', $this->text_domain) . '</label>
			<div class="col-sm-9">
				<input type="text" id="' . $this->prefix . 'newsletter-text-color" name="' . $this->prefix . 'newsletter-text-color" class="nnr-color-input" value="' .  (isset($text_color) ? $text_color : '#ffffff') . '"/>' .
				$help_text .
			'</div>
		</div>';

		return $code;

	}

	/**
	 * Display Backgorund Color Field
	 *
	 * @access public
	 * @param mixed $bg_color
	 * @param string $default (default: '')
	 * @param mixed $help_text (default: null)
	 * @return void
	 */
	function display_bg_color( $bg_color, $default = '', $help_text = null ) {

		if ( isset($help_text) ) {
			$help_text = '<em class="help-block">' . __($help_text, $this->text_domain) . '</em>';
		}

		$code = '<!-- Subscribe Button Color -->
		<div class="form-group">
			<label for="' . $this->prefix . 'newsletter-bg-color" class="col-sm-3 control-label">' . __('Subscribe Button Color', $this->text_domain) . '</label>
			<div class="col-sm-9">
				<input type="text" id="' . $this->prefix . 'newsletter-bg-color" name="' . $this->prefix . 'newsletter-bg-color" class="nnr-color-input" value="' .  (isset($bg_color) ? $bg_color : '#f15928') . '"/>' .
				$help_text .
			'</div>
		</div>';

		return $code;

	}

	/**
	 * Get all the Newsletter data and return it as an array
	 *
	 * @access public
	 * @return void
	 */
	function get_data() {

		return array(
			'newsletter'				=> isset($_POST[$this->prefix . 'newsletter']) ? $this->sanitize_value($_POST[$this->prefix . 'newsletter']) : '',
			'first_name_placeholder'	=> isset($_POST[$this->prefix . 'newsletter-first-name-placeholder']) ? $this->sanitize_value($_POST[$this->prefix . 'newsletter-first-name-placeholder']) : '',
			'last_name_placeholder'		=> isset($_POST[$this->prefix . 'newsletter-last-name-placeholder']) ? $this->sanitize_value($_POST[$this->prefix . 'newsletter-last-name-placeholder']) : '',
			'email_placeholder'			=> isset($_POST[$this->prefix . 'newsletter-email-placeholder']) ? $this->sanitize_value($_POST[$this->prefix . 'newsletter-email-placeholder']) : '',
			'first_name'				=> isset($_POST[$this->prefix . 'newsletter-first-name']) && $_POST[$this->prefix . 'newsletter-first-name'] ? true : false,
			'last_name'					=> isset($_POST[$this->prefix . 'newsletter-last-name']) && $_POST[$this->prefix . 'newsletter-last-name'] ? true : false,
			'success_action'			=> isset($_POST[$this->prefix . 'newsletter-success-action']) ? $this->sanitize_value($_POST[$this->prefix . 'newsletter-success-action']) : '',
			'success_message'			=> isset($_POST[$this->prefix . 'newsletter-success-message']) ? $this->sanitize_value($_POST[$this->prefix . 'newsletter-success-message']) : '',
			'success_url'				=> isset($_POST[$this->prefix . 'newsletter-success-url']) ? $this->sanitize_value($_POST[$this->prefix . 'newsletter-success-url']) : '',
			'button_text'				=> isset($_POST[$this->prefix . 'newsletter-button-text']) ? $this->sanitize_value($_POST[$this->prefix . 'newsletter-button-text']) : '',
			'subscribe_icon'			=> isset($_POST[$this->prefix . 'newsletter-subscribe-icon']) ? $this->sanitize_value($_POST[$this->prefix . 'newsletter-subscribe-icon']) : '',
			'subscribe_icon_place'		=> isset($_POST[$this->prefix . 'newsletter-subscribe-icon-place']) ? $this->sanitize_value($_POST[$this->prefix . 'newsletter-subscribe-icon-place']) : '',
			'text_color'				=> isset($_POST[$this->prefix . 'newsletter-text-color']) ? $this->sanitize_value($_POST[$this->prefix . 'newsletter-text-color']) : '',
			'bg_color'					=> isset($_POST[$this->prefix . 'newsletter-bg-color']) ? $this->sanitize_value($_POST[$this->prefix . 'newsletter-bg-color']) : '',
			'mailchimp'			=> array(
				'optin'			=> isset($_POST[$this->prefix . 'newsletter-mailchimp-optin']) && $_POST[$this->prefix . 'newsletter-mailchimp-optin'] ? true : false,
				'api_key'		=> isset($_POST[$this->prefix . 'newsletter-mailchimp-api-key']) ? $this->sanitize_value($_POST[$this->prefix . 'newsletter-mailchimp-api-key']) : '',
				'list'			=> isset($_POST[$this->prefix . 'newsletter-mailchimp-list']) ? $this->sanitize_value($_POST[$this->prefix . 'newsletter-mailchimp-list']) : '',
			),
			'aweber'			=> array(
				'consumer_key'			=> isset($_POST[$this->prefix . 'newsletter-aweber-consumer-key']) ? $this->sanitize_value($_POST[$this->prefix . 'newsletter-aweber-consumer-key']) : '',
				'consumer_secret'		=> isset($_POST[$this->prefix . 'newsletter-aweber-consumer-secret']) ? $this->sanitize_value($_POST[$this->prefix . 'newsletter-aweber-consumer-secret']) : '',
				'access_key'			=> isset($_POST[$this->prefix . 'newsletter-aweber-access-key']) ? $this->sanitize_value($_POST[$this->prefix . 'newsletter-aweber-access-key']) : '',
				'access_secret'			=> isset($_POST[$this->prefix . 'newsletter-aweber-access-secret']) ? $this->sanitize_value($_POST[$this->prefix . 'newsletter-aweber-access-secret']) : '',
				'list_id'				=> isset($_POST[$this->prefix . 'newsletter-aweber-list-id']) ? $this->sanitize_value($_POST[$this->prefix . 'newsletter-aweber-list-id']) : '',
			),
			'getresponse'			=> array(
				'api_key'			=> isset($_POST[$this->prefix . 'newsletter-getresponse-api-key']) ? $this->sanitize_value($_POST[$this->prefix . 'newsletter-getresponse-api-key']) : '',
				'campaign'			=> isset($_POST[$this->prefix . 'newsletter-getresponse-campaign']) ? $this->sanitize_value($_POST[$this->prefix . 'newsletter-getresponse-campaign']) : '',
			),
			'campaignmonitor'			=> array(
				'api_key'			=> isset($_POST[$this->prefix . 'newsletter-campaignmonitor-api-key']) ? $this->sanitize_value($_POST[$this->prefix . 'newsletter-campaignmonitor-api-key']) : '',
				'client'			=> isset($_POST[$this->prefix . 'newsletter-campaignmonitor-client']) ? $this->sanitize_value($_POST[$this->prefix . 'newsletter-campaignmonitor-client']) : '',
				'list'				=> isset($_POST[$this->prefix . 'newsletter-campaignmonitor-list']) ? $this->sanitize_value($_POST[$this->prefix . 'newsletter-campaignmonitor-list']) : '',
			),
			'madmimi'			=> array(
				'username'			=> isset($_POST[$this->prefix . 'newsletter-madmimi-username']) ? $this->sanitize_value($_POST[$this->prefix . 'newsletter-madmimi-username']) : '',
				'api_key'			=> isset($_POST[$this->prefix . 'newsletter-madmimi-api-key']) ? $this->sanitize_value($_POST[$this->prefix . 'newsletter-madmimi-api-key']) : '',
				'list'				=> isset($_POST[$this->prefix . 'newsletter-madmimi-list']) ? $this->sanitize_value($_POST[$this->prefix . 'newsletter-madmimi-list']) : '',
			),
			'infusionsoft'			=> array(
				'app_id'			=> isset($_POST[$this->prefix . 'newsletter-infusionsoft-app-id']) ? $this->sanitize_value($_POST[$this->prefix . 'newsletter-infusionsoft-app-id']) : '',
				'api_key'			=> isset($_POST[$this->prefix . 'newsletter-infusionsoft-api-key']) ? $this->sanitize_value($_POST[$this->prefix . 'newsletter-infusionsoft-api-key']) : '',
				'list'				=> isset($_POST[$this->prefix . 'newsletter-infusionsoft-list']) ? $this->sanitize_value($_POST[$this->prefix . 'newsletter-infusionsoft-list']) : '',
			),
			'mymail'			=> array(
				'list'				=> isset($_POST[$this->prefix . 'newsletter-mymail-list']) ? $this->sanitize_value($_POST[$this->prefix . 'newsletter-mymail-list']) : '',
			),
			'activecampaign'			=> array(
				'app_url'			=> isset($_POST[$this->prefix . 'newsletter-activecampaign-app-url']) ? $this->sanitize_value($_POST[$this->prefix . 'newsletter-activecampaign-app-url']) : '',
				'api_key'			=> isset($_POST[$this->prefix . 'newsletter-activecampaign-api-key']) ? $this->sanitize_value($_POST[$this->prefix . 'newsletter-activecampaign-api-key']) : '',
				'list'				=> isset($_POST[$this->prefix . 'newsletter-activecampaign-list']) ? $this->sanitize_value($_POST[$this->prefix . 'newsletter-activecampaign-list']) : '',
			),
			'feedburner'			=> array(
				'id'				=> isset($_POST[$this->prefix . 'newsletter-feedburner-id']) ? $this->sanitize_value($_POST[$this->prefix . 'newsletter-feedburner-id']) : '',
			),
		);

	}
}

// AJAX Actions for retieving list data

add_action( 'wp_ajax_nnr_new_int_get_mailchimp_lists', 			'nnr_new_int_get_mailchimp_lists_v1');
add_action( 'wp_ajax_nnr_new_int_get_aweber_lists', 			'nnr_new_int_get_aweber_lists_v1');
add_action( 'wp_ajax_nnr_new_int_get_getresponse_lists', 		'nnr_new_int_get_getresponse_lists_v1');
add_action( 'wp_ajax_nnr_new_int_get_campaignmonitor_lists', 	'nnr_new_int_get_campaignmonitor_lists_v1');
add_action( 'wp_ajax_nnr_new_int_update_campaignmonitor_lists', 'nnr_new_int_update_campaignmonitor_lists_v1');
add_action( 'wp_ajax_nnr_new_int_get_madmimi_lists', 			'nnr_new_int_get_madmimi_lists_v1');
add_action( 'wp_ajax_nnr_new_int_get_infusionsoft_lists', 		'nnr_new_int_get_infusionsoft_lists_v1');
add_action( 'wp_ajax_nnr_new_int_get_mymail_lists', 			'nnr_new_int_get_mymail_lists_v1');
add_action( 'wp_ajax_nnr_new_int_get_activecampaign_lists', 	'nnr_new_int_get_activecampaign_lists_v1');

/**
 * Get all Mailchimp Lists
 *
 * @access public
 * @return void
 */
function nnr_new_int_get_mailchimp_lists_v1() {

	$options = '';

	if (isset($_POST['api_key']) && $_POST['api_key'] != '') {
		require_once(dirname(dirname(__FILE__)) . '/services/mailchimp/MailChimp.php');

		$MailChimp = new NNR_New_Int_MailChimp($_POST['api_key']);
		$lists = $MailChimp->call('lists/list');

		if (isset($lists) && is_array($lists)) {

			foreach ($lists['data'] as $list) {
				$options .= '<option value="' . $list['id'] . '">' .  $list['name'] . '</option>';
			}

			if (isset($_POST['list']) && $_POST['list'] != '') {
				$options = '';

				foreach ($lists['data'] as $list) {
					if ($_POST['list'] == $list['id']) {
						$options .= '<option value="' . $list['id'] . '" selected="selected">' .  $list['name'] . '</option>';
					} else {
						$options .= '<option value="' . $list['id'] . '">' .  $list['name'] . '</option>';
					}
				}
			}
		}
	}

	echo $options;

	die(); // this is required to terminate immediately and return a proper response
}

/**
 * Get the Aweber lists for an account
 *
 * @access public
 * @return void
 */
function nnr_new_int_get_aweber_lists_v1() {

	$options = '';
	$consumerKey = '';
	$consumerSecret = '';
	$accessKey = '';
	$accessSecret = '';

	if (isset($_POST['code']) && $_POST['code'] != '') {

		require_once(dirname(dirname(__FILE__)) . '/services/aweber/aweber_api.php');

		try {
			$credentials = AWeberAPI::getDataFromAweberID($_POST['code']);
			list($consumerKey, $consumerSecret, $accessKey, $accessSecret) = $credentials;


			$consumerKey    = isset($consumerKey) && !empty($consumerKey) ? $consumerKey : '';
			$consumerSecret = isset($consumerSecret) && !empty($consumerSecret) ? $consumerSecret : '';
			$accessKey      = isset($accessKey) && !empty($accessKey) ? $accessKey : '';
			$accessSecret   = isset($accessSecret) && !empty($accessSecret) ? $accessSecret : '';

		} catch (AWeberAPIException $exc) {
			error_log($exc);
		}

		try {

			$aweber = new AWeberAPI($consumerKey, $consumerSecret);
			$account = $aweber->getAccount($accessKey, $accessSecret);
			$lists = $account->loadFromUrl('/accounts/' . $account->id . '/lists/');

			foreach ($lists as $list) {
				$options .= '<option value="' . $list->id . '">' .  $list->name . '</option>';
			}

		} catch (AWeberAPIException $exc) { error_log($exc); }
	}

	if (isset($_POST['list']) && $_POST['list'] != '') {

		$consumerKey     = $_POST['consumer_key'];
		$consumerSecret  = $_POST['consumer_secret'];
		$accessKey       = $_POST['access_key'];
		$accessSecret    = $_POST['access_secret'];

		require_once(dirname(dirname(__FILE__)) . '/services/aweber/aweber_api.php');

		try {

			$aweber = new AWeberAPI($consumerKey, $consumerSecret);
			$account = $aweber->getAccount($accessKey, $accessSecret);
			$lists = $account->loadFromUrl('/accounts/' . $account->id . '/lists/');

			$options = '';
			foreach ($lists as $list) {
				if ($_POST['list'] == $list->id) {
					$options .= '<option value="' . $list->id . '" selected="selected">' .  $list->name . '</option>';
				} else {
					$options .= '<option value="' . $list->id . '">' .  $list->name . '</option>';
				}
			}

		} catch (AWeberAPIException $exc) { error_log($exc); }
	}

	echo json_encode(array(
		'html'               => $options,
		'consumer_key'       => $consumerKey,
		'consumer_secret'    => $consumerSecret,
		'access_key'         => $accessKey,
		'access_secret'      => $accessSecret,
	));

	die(); // this is required to terminate immediately and return a proper response
}

/**
 * Get all Get Repsonse Lists and display in settings
 *
 * @access public
 * @return void
 */
function nnr_new_int_get_getresponse_lists_v1() {

	$options = '';

	if (isset($_POST['api_key']) && $_POST['api_key'] != '') {

		require_once(dirname(dirname(__FILE__)) . '/services/getresponse/jsonRPCClient.php');
		$api = new jsonRPCClient('http://api2.getresponse.com');

		try {
			$result = $api->get_campaigns($_POST['api_key']);
			foreach ((array) $result as $k => $v) {
				$campaigns[] = array('id' => $k, 'name' => $v['name']);
			}
		}

		catch (Exception $e) {}

		if (isset($campaigns) && is_array($campaigns)) {

			foreach ($campaigns as $campaign) {
				$options .= '<option value="' . $campaign['id'] . '">' .  $campaign['name'] . '</option>';
			}

			if (isset($_POST['campaign']) && $_POST['campaign'] != '') {
				$options = '';
				foreach ($campaigns as $campaign) {

					if ($_POST['campaign'] == $campaign['id']) {
						$options .= '<option value="' . $campaign['id'] . '" selected="selected">' .  $campaign['name'] . '</option>';
					} else {
						$options .= '<option value="' . $campaign['id'] . '">' .  $campaign['name'] . '</option>';
					}
				}
			}
		}
	}

	echo $options;

	die(); // this is required to terminate immediately and return a proper response
}

/**
 * Get all Campaign Monitor Lists and display in settings
 *
 * @access public
 * @return void
 */
function nnr_new_int_get_campaignmonitor_lists_v1() {

	$lists = '';
	$clients = '';

	if (isset($_POST['api_key']) && $_POST['api_key'] != '') {

		require_once(dirname(dirname(__FILE__)) . '/services/campaignmonitor/csrest_general.php');
		require_once(dirname(dirname(__FILE__)) . '/services/campaignmonitor/csrest_clients.php');
		$auth = array('api_key' => $_POST['api_key']);
		$wrap = new CS_REST_General($auth);
		$result = $wrap->get_clients();


		if ($result->was_successful()) {

			foreach ($result->response as $client) {
				$clients .= '<option value="' . $client->ClientID . '">' .  $client->Name . '</option>';
			}

			if (isset($_POST['client']) && $_POST['client'] != '') {
				$clients = '';
				foreach ($result->response as $client) {
					if ($_POST['client'] == $client->ClientID) {
						$clients .= '<option value="' . $client->ClientID . '" selected="selected">' .  $client->Name . '</option>';
					} else {
						$clients .= '<option value="' . $client->ClientID . '">' .  $client->Name . '</option>';
					}
				}
			}

			if (isset($_POST['client']) && $_POST['client'] != '') {
				$client_id = $_POST['client'];
			} else {
				$client_id = $result->response[0]->ClientID;
			}

			$wrap = new CS_REST_Clients($client_id, $_POST['api_key']);
			$result = $wrap->get_lists();

			if ($result->was_successful()) {
				foreach ($result->response as $list) {
					$lists .= '<option value="' . $list->ListID . '">' .  $list->Name . '</option>';
				}

				if (isset($_POST['list']) && $_POST['list'] != '') {
					$lists = '';
					foreach ($result->response as $list) {
						if ($_POST['list'] == $list->ListID) {
							$lists .= '<option value="' . $list->ListID . '" selected="selected">' .  $list->Name . '</option>';
						} else {
							$lists .= '<option value="' . $list->ListID . '">' .  $list->Name . '</option>';
						}
					}
				}
			}
		}
	}

	echo json_encode(array('clients' => $clients, 'lists' => $lists));

	die(); // this is required to terminate immediately and return a proper response
}

/**
 * Update all Campaign Monitor Lists and display in settings
 *
 * @access public
 * @return void
 */
function nnr_new_int_update_campaignmonitor_lists_v1() {

	$lists = '';

	if (isset($_POST['api_key']) && $_POST['api_key'] != '' &&
		isset($_POST['client_id']) && $_POST['client_id'] != '') {

		require_once(dirname(dirname(__FILE__)) . '/services/campaignmonitor/csrest_general.php');
		require_once(dirname(dirname(__FILE__)) . '/services/campaignmonitor/csrest_clients.php');


		$wrap = new CS_REST_Clients($_POST['client_id'], $_POST['api_key']);
		$result = $wrap->get_lists();


		if ($result->was_successful()) {
			foreach ($result->response as $list) {
				$lists .= '<option value="' . $list->ListID . '">' .  $list->Name . '</option>';
			}
		}
	}

	echo $lists;

	die(); // this is required to terminate immediately and return a proper response
}

/**
 * Get all Mad Mimi Lists and display in settings
 *
 * @access public
 * @return void
 */
function nnr_new_int_get_madmimi_lists_v1() {

	$options = '';

	if (isset($_POST['api_key']) && $_POST['api_key'] != '' &&
		isset($_POST['username']) && $_POST['username'] != '') {

		require_once(dirname(dirname(__FILE__)) . '/services/madmimi/MadMimi.class.php');

		$mailer = new MadMimi($_POST['username'], $_POST['api_key']);

		if (isset($mailer)) {
			try {
				$lists = $mailer->Lists();
				$lists  = new SimpleXMLElement($lists);

			    if ($lists->list) {
					foreach ($lists->list as $l) {
						$options .= '<option value="' . $l->attributes()->{'name'}->{0} . '">' .  $l->attributes()->{'name'}->{0} . '</option>';
					}
			    }

			    if (isset($_POST['list']) && $_POST['list'] != '') {
				    $options = '';
					foreach ($lists->list as $l) {

						if ($_POST['list'] == $l->attributes()->{'name'}->{0}) {
							$options .= '<option value="' . $l->attributes()->{'name'}->{0} . '" selected="selected">' .  $l->attributes()->{'name'}->{0} . '</option>';
						} else {
							$options .= '<option value="' . $l->attributes()->{'name'}->{0} . '">' .  $l->attributes()->{'name'}->{0} . '</option>';
						}
					}
				}
			} catch (Exception $exc) {}
		}
	}

	echo $options;

	die(); // this is required to terminate immediately and return a proper response
}

/**
 * Get all the infusionsoft lists for specfic account
 *
 * @access public
 * @return void
 */
function nnr_new_int_get_infusionsoft_lists_v1() {

	if ( ! function_exists( 'curl_init' ) ) {
		return __( 'curl_init is not defined ', 'bloom' );
	}

	if ( ! class_exists( 'iSDK' ) ) {
		require_once( dirname(dirname(__FILE__)) . '/services/infusionsoft/isdk.php' );
	}

	$options = '';

	// Make sure the use entered in the api key

	if (isset($_POST['app_id']) && $_POST['app_id'] != '' &&
		isset($_POST['api_key']) && $_POST['api_key'] != '') {

		try {
			$infusion_app = new iSDK();
			$infusion_app->cfgCon( $_POST['app_id'], $_POST['api_key'], 'throw' );
		} catch( iSDKException $e ){
			$error_message = $e->getMessage();
		}

		if ( empty( $error_message ) ) {
			$need_request = true;
			$page = 0;
			$all_lists = array();

			while ( true == $need_request ) {
				$error_message = 'success';
				$lists_data = $infusion_app->dsQuery( 'ContactGroup', 1000, $page, array( 'Id' => '%' ), array( 'Id', 'GroupName' ) );
				$all_lists = array_merge( $all_lists, $lists_data );

				if ( 1000 > count( $lists_data ) ) {
					$need_request = false;
				} else {
					$page++;
				}
			}
		}

		// Get all list information

		if ( ! empty( $all_lists ) ) {
			foreach( $all_lists as $list ) {

				if ($_POST['list'] == $list['Id']) {
					$options .= '<option value="' . $list['Id'] . '" selected="selected">' .  $list['GroupName'] . '</option>';
				} else {
					$options .= '<option value="' . $list['Id'] . '">' .  $list['GroupName'] . '</option>';
				}
			}
		}
	}

	echo $options;

	die(); // this is required to terminate immediately and return a proper response

}

/**
 * Get all the MyMail lists
 *
 * @access public
 * @return void
 */
function nnr_new_int_get_mymail_lists_v1() {

	if ( !function_exists('mymail') ) {
		echo '';
		die();
	}

	$lists = mymail('lists')->get();
	$options = '';

	foreach ( $lists as $list ) {
		if ($_POST['list'] == $list->ID) {
			$options .= '<option value="' . $list->ID . '" selected="selected">' .  $list->name . '</option>';
		} else {
			$options .= '<option value="' . $list->ID . '">' .  $list->name . '</option>';
		}
	}

	echo $options;
	die();
}

/**
 * Get all the Active Campaign lists
 *
 * @access public
 * @return void
 */
function nnr_new_int_get_activecampaign_lists_v1() {

	require_once( dirname(dirname(__FILE__)) . '/services/activecampaign/ActiveCampaign.class.php' );

	$options = '';

	if ( isset($_POST['app_url']) && $_POST['app_url'] != '' &&
		isset($_POST['api_key']) && $_POST['api_key'] != '' ) {

		$ac = new ActiveCampaign($_POST['app_url'], $_POST['api_key']);

		if ( !(int)$ac->credentials_test() ) {

			echo "";
			exit();
		}

		$lists = $ac->api("list/list?ids=all");

		foreach ( $lists as $list ) {

			if ( !isset($list->id) || empty($list->id) ) {
				continue;
			}

			if ($_POST['list'] == $list->id) {
				$options .= '<option value="' . $list->id . '" selected="selected">' .  $list->name . '</option>';
			} else {
				$options .= '<option value="' . $list->id . '">' .  $list->name . '</option>';
			}
		}
	}

	echo $options;
	die();
}

endif;