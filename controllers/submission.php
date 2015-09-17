<?php

// Exit if accessed directly

if ( !defined( 'ABSPATH' ) ) exit;

// Check if class already exists

if ( !class_exists("NNR_Newsletter_Integrations_Submission_v1") ):

/* ================================================================================
 *
 * This is the class for Newsletter Integration to submit emails to the different
 * service providers.
 *
 ================================================================================ */

class NNR_Newsletter_Integrations_Submission_v1 {

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
	 * text_domain
	 *
	 * (default value: '')
	 *
	 * @var string
	 * @access public
	 */
	public $table_name = '';

	/**
	 * Called when the object is first created
	 *
	 * @access public
	 * @param mixed $prefix
	 * @return void
	 */
	function __construct( $table_name = '' ) {

		$this->table_name = $table_name;

	}

	/**
	 * Creates the Stats table if it does not exist
	 *
	 * @access public
	 * @static
	 * @return void
	 */
	function create_table(){

		global $wpdb;

		$wpdb->query("
			CREATE TABLE IF NOT EXISTS " . $this->get_table_name() . " (
				`id` int(11) NOT NULL AUTO_INCREMENT,
				`date` datetime NOT NULL,
				`data_id` int(11) NOT NULL,
				`email` varchar(255) NOT NULL UNIQUE,
				`first_name` varchar(50) NOT NULL DEFAULT '',
				`last_name` varchar(50) NOT NULL DEFAULT '',
				PRIMARY KEY (`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;
		");

	}

	/**
	 * Adds data into the table as a new row
	 *
	 * @access public
	 * @param array $data (default: array())
	 * @return void
	 */
	function add_data( $data = array() ) {

		global $wpdb;

		$today = date('Y-m-d');

		$result = $wpdb->query($wpdb->prepare('INSERT IGNORE INTO ' . $this->get_table_name() . ' (
			`date`,
			`data_id`,
			`email`,
			`first_name`,
			`last_name`
			) VALUES (%s, %d, %s, %s, %s)',
			$today,
			$data['data_id'],
			$data['email'],
			$data['first_name'],
			$data['last_name']
		));

		// Return the recently created id for this entry

		return $wpdb->insert_id;

	}

	/**
	 * Get the emails over a date range in all time
	 *
	 * @access public
	 * @static
	 * @param mixed $start
	 * @param mixed $end
	 * @param mixed $id (default: null)
	 * @return void
	 */
	function get_emails($start = null, $end = null, $email = null, $select = '*') {

		$query = null;

		global $wpdb;

		// All Emails, All Time

		if ($start == null && $end == null && $email == null) {
			$query = 'SELECT ' . $select . ' FROM ' . $this->get_table_name();
		}

		// All Emails, After Date

		else if ($start != null && $end == null && $email == null) {
			$query = $wpdb->prepare('SELECT ' . $select . ' FROM ' . $this->get_table_name() . ' WHERE `date` = %s', $start);
		}

		// All Emails, Date Range

		else if ($start != null && $end != null && $email == null) {
			$query = $wpdb->prepare('SELECT ' . $select . ' FROM ' . $this->get_table_name() . ' WHERE `date` >= %s AND `date` <= %s', $start, $end);
		}

		// Single Email

		else if ($start == null && $end == null && $email != null) {
			$query = $wpdb->prepare('SELECT ' . $select . ' FROM ' . $this->get_table_name() . ' WHERE `email` = %d', $email);
		}

		// No query was created

		if (!isset($query)) {
			return false;
		}

		$result = $wpdb->get_results($query, 'ARRAY_A');

		return $result;
	}

	/**
	 * Deletes the stats for a optin fire when the optin fire is deleted
	 *
	 * @access public
	 * @static
	 * @return void
	 */
	function delete_email($email) {

		// Return false if data_id is not set

		if ( !isset($email) ) {
			return false;
		}

		global $wpdb;

		// Delete Email

		$result = $wpdb->query($wpdb->prepare('DELETE FROM ' . $this->get_table_name() . ' WHERE `email` = %d', $email));

		return $result;

	}

	/**
	 * Returns the proper table name based off of site ID
	 *
	 * @access public
	 * @static
	 * @return void
	 */
	function get_table_name(){

		global $wpdb;

		return '`' . $wpdb->prefix . $this->table_name . '`';
	}

}

add_action( 'wp_ajax_nnr_new_int_add_email', 			'nnr_new_int_add_email_v1');
add_action( 'wp_ajax_nopriv_nnr_new_int_add_email', 	'nnr_new_int_add_email_v1');

/**
 * Record an impression made by a optin fire
 * This function is called via PHP.
 *
 * @access public
 * @static
 * @return void
 */
function nnr_new_int_add_email_v1() {

	// No First Name

	if ( !isset($_POST['first_name']) ) {
		$_POST['first_name'] = '';
	}

	// No Last Name

	if ( !isset($_POST['last_name']) ) {
		$_POST['last_name'] = '';
	}

	// Could not find Data ID

	if ( !isset($_POST['data_id']) || $_POST['data_id'] == '' ) {
		echo json_encode(array(
			'id'		=> $_POST['data_id'],
			'status'	=> 'warning',
			'message'	=> __('Could not find Data ID.', $_POST['text_domain'])
		));
		die();
	}

	// Get all newsletter data for this data instance

	$data_manager = new NNR_Data_Manager_v1( $_POST['table_name'] );
	$data_instance = $data_manager->get_data_from_id($_POST['data_id']);

	$success_action = isset($data_instance['args']['newsletter']['success_action']) ? stripcslashes($data_instance['args']['newsletter']['success_action']) : 'message';
	$success_mesage = isset($data_instance['args']['newsletter']['success_message']) ? stripcslashes($data_instance['args']['newsletter']['success_message']) : __('Welcome to the community!', $_POST['text_domain']);
	$success_url 	= isset($data_instance['args']['newsletter']['success_url']) ? stripcslashes($data_instance['args']['newsletter']['success_url']) : '';

	// No Email

	if ( !isset($_POST['email']) || $_POST['email'] == '' ) {
		echo json_encode(array(
			'id'		=> $_POST['data_id'],
			'status'	=> 'warning',
			'message'	=> __('No Email address provided.', $_POST['text_domain'])
		));
		die();
	}

	// Invalid Email Address

	if ( !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) ) {
		echo json_encode(array(
			'id'		=> $_POST['data_id'],
			'status'	=> 'warning',
			'message'	=> __('Invalid Email provided.', $_POST['text_domain'])
		));
		die();
	}

	// WordPress

	if ($_POST['type'] == 'wordpress') {

		$newsletter_db = new NNR_Newsletter_Integrations_Submission_v1( $_POST['news_table_name'] );

		$result = $newsletter_db->add_data(array(
			'data_id' 		=> $_POST['data_id'],
			'email'			=> $_POST['email'],
			'first_name'	=> $_POST['first_name'],
			'last_name'		=> $_POST['last_name'],
		));

		if ($result) {

			echo json_encode(array(
				'id'				=> $_POST['data_id'],
				'status'			=> 'check',
				'success_action'	=> $success_action,
				'url'				=> $success_url,
				'message'			=> $success_mesage,
			));

			die();

		} else {
			echo json_encode(array(
				'id'		=> $_POST['data_id'],
				'status'	=> 'check',
				'message'	=> __('We already have your email!', $_POST['text_domain']),
			));

			die();
		}
	}

	// MailChimp

	else if ($_POST['type'] == 'mailchimp') {

		require_once(dirname(dirname(__FILE__)) . '/services/mailchimp/MailChimp.php');

		if (!isset($data_instance['args']['newsletter']['mailchimp']['api_key']) || $data_instance['args']['newsletter']['mailchimp']['api_key'] == '') {
			echo json_encode(array(
				'id'		=> $_POST['data_id'],
				'status'	=> 'warning',
				'message'	=> __('MailChimp account is not setup properly.', $_POST['text_domain']),
			));

			die();
		}

		if (!isset($data_instance['args']['newsletter']['mailchimp']['list']) || $data_instance['args']['newsletter']['mailchimp']['list'] == '') {
			echo json_encode(array(
				'id'		=> $_POST['data_id'],
				'status'	=> 'warning',
				'message'	=> __('MailChimp: No list specified.', $_POST['text_domain']),
			));

			die();
		}

		if ( !isset($data_instance['args']['newsletter']['mailchimp']['optin']) ) {
			$data_instance['args']['newsletter']['mailchimp']['optin'] = true;
		}

		$MailChimp = new NNR_New_Int_MailChimp($data_instance['args']['newsletter']['mailchimp']['api_key']);
		$result = $MailChimp->call('lists/subscribe', array(
            'id'                => $data_instance['args']['newsletter']['mailchimp']['list'],
            'email'             => array('email'=>$_POST['email']),
            'merge_vars'        => array('FNAME'=>$_POST['first_name'], 'LNAME'=>$_POST['last_name']),
            'double_optin'      => $data_instance['args']['newsletter']['mailchimp']['optin'],
            'update_existing'   => false,
            'replace_interests' => false,
            'send_welcome'      => true,
        ));

        if ($result) {

            if (isset($result['email'])) {

				echo json_encode(array(
					'id'				=> $_POST['data_id'],
					'status'			=> 'check',
					'success_action'	=> $success_action,
					'url'				=> $success_url,
					'message'			=> $success_mesage,
				));

				die();
            }

            else if (isset($result['status']) && $result['status'] == 'error') {
				echo json_encode(array(
					'id'			=> $_POST['data_id'],
					'status'		=> 'warning',
					'message'		=> $result['error'],
				));

				die();
            }
        } else {

            echo json_encode(array(
	            'id'		=> $_POST['data_id'],
				'status'	=> 'warning',
				'message'	=> __('Unable to subscribe.', $_POST['text_domain']),
			));

			die();
        }
	}

	// Add email to Aweber

	else if ($_POST['type'] == 'aweber') {

		require_once(dirname(dirname(__FILE__)) . '/services/aweber/aweber_api.php');

		$aweber = new AWeberAPI($data_instance['args']['newsletter']['aweber']['consumer_key'], $data_instance['args']['newsletter']['aweber']['consumer_secret']);

		try {
			$account = $aweber->getAccount($data_instance['args']['newsletter']['aweber']['access_key'], $data_instance['args']['newsletter']['aweber']['access_secret']);
			$list = $account->loadFromUrl('/accounts/' . $account->id . '/lists/' . $data_instance['args']['newsletter']['aweber']['list_id']);

			$subscriber = array(
				'email' 	=> $_POST['email'],
				'name'		=> $_POST['first_name'] . ' ' . $_POST['last_name'],
				'ip' 		=> $_SERVER['REMOTE_ADDR']
			);

			$newSubscriber = $list->subscribers->create($subscriber);

			echo json_encode(array(
				'id'				=> $_POST['data_id'],
				'status'			=> 'check',
				'success_action'	=> $success_action,
				'url'				=> $success_url,
				'message'			=> $success_mesage,
			));

			die();

		} catch (AWeberAPIException $exc) {
			echo json_encode(array(
				'id'		=> $_POST['data_id'],
				'status'	=> 'warning',
				'message'	=> $exc->message,
			));

			die();
		}
	}

	// Add email to Get Response

	else if ($_POST['type'] == 'getresponse') {

		require_once(dirname(dirname(__FILE__)) . '/services/getresponse/jsonRPCClient.php');

		$api = new jsonRPCClient('http://api2.getresponse.com');

		try {
			$api->add_contact(
				$data_instance['args']['newsletter']['getresponse']['api_key'],
			    array (
			        'campaign'  => $data_instance['args']['newsletter']['getresponse']['campaign'],
			        'name'      => $_POST['first_name'] . ' ' . $_POST['last_name'],
			        'email'     => $_POST['email'],
			    )
			);

			echo json_encode(array(
				'id'				=> $_POST['data_id'],
				'status'			=> 'check',
				'success_action'	=> $success_action,
				'url'				=> $success_url,
				'message'			=> $success_mesage,
			));

			die();

		} catch (RuntimeException $exc) {

			$msg = $exc->getMessage();
			$msg = substr($msg, 0, strpos($msg, ";"));

			echo json_encode(array(
				'id'		=> $_POST['data_id'],
				'status'	=> 'warning',
				'message'	=> $msg,
			));

			die();
		}
	}

	// Add email to Campaign Monitor

	else if ($_POST['type'] == 'campaignmonitor') {

		require_once(dirname(dirname(__FILE__)) . '/services/campaignmonitor/csrest_subscribers.php');

		$wrap = new CS_REST_Subscribers($data_instance['args']['newsletter']['campaignmonitor']['list'], $data_instance['args']['newsletter']['campaignmonitor']['api_key']);

		// Check if subscriber is already subscribed

		$result = $wrap->get($_POST['email']);

		if ($result->was_successful()) {
			echo json_encode(array(
				'id'		=> $_POST['data_id'],
				'status'	=> 'warning',
				'message'	=> __('You are already subscribed to this list.', $_POST['text_domain']),
			));

			die();
		}

		$result = $wrap->add(array(
			'EmailAddress' 	=> $_POST['email'],
			'Name' 			=> $_POST['first_name'] . ' ' . $_POST['last_name'],
			'Resubscribe' 	=> true
		));

		if ($result->was_successful()) {

			echo json_encode(array(
				'id'				=> $_POST['data_id'],
				'status'			=> 'check',
				'success_action'	=> $success_action,
				'url'				=> $success_url,
				'message'			=> $success_mesage,
			));

			die();

		} else {

			echo json_encode(array(
				'id'		=> $_POST['data_id'],
				'status'	=> 'warning',
				'message'	=> $result->response->Message,
			));

			die();
		}
	}

	// Add email to Mad Mimi

	else if ($_POST['type'] == 'madmimi') {

		require_once(dirname(dirname(__FILE__)) . '/services/madmimi/MadMimi.class.php');

		$mailer = new MadMimi($data_instance['args']['newsletter']['madmimi']['username'], $data_instance['args']['newsletter']['madmimi']['api_key']);

		try {

			// Check if user is already in list

			$result = $mailer->Memberships($_POST['email']);
			$lists  = new SimpleXMLElement($result);

			if ($lists->list) {
				foreach ($lists->list as $l) {
					if ($l->attributes()->{'name'}->{0} == $data_instance['args']['newsletter']['madmimi']['list']) {

						echo json_encode(array(
							'id'		=> $_POST['data_id'],
							'status'		=> 'check',
							'message'		=> __('You are already subscribed to this list.', $_POST['text_domain']),
						));

						die();
					}
				}
		    }

			$result = $mailer->AddMembership($data_instance['args']['newsletter']['madmimi']['list'], $_POST['email'], array(
				'first_name'	=> $_POST['first_name'],
				'last_name'		=> $_POST['last_name'],
			));

			echo json_encode(array(
				'id'				=> $_POST['data_id'],
				'status'			=> 'check',
				'success_action'	=> $success_action,
				'url'				=> $success_url,
				'message'			=> $success_mesage,
			));

			die();

		} catch (RuntimeException $exc) {

			echo json_encode(array(
				'id'		=> $_POST['data_id'],
				'status'	=> 'warning',
				'message'	=> $msg,
			));

			die();
		}
	}

	// Add email to Infusionsoft

	else if ($_POST['type'] == 'infusionsoft') {

		require_once( dirname(dirname(__FILE__)) . '/services/infusionsoft/isdk.php' );

		try {
			$infusion_app = new iSDK();
			$infusion_app->cfgCon( $data_instance['args']['newsletter']['infusionsoft']['app_id'], $data_instance['args']['newsletter']['infusionsoft']['api_key'], 'throw' );
		} catch( iSDKException $e ){

			echo json_encode(array(
				'id'		=> $_POST['data_id'],
				'status'	=> 'warning',
				'message'	=> $e->getMessage(),
			));

			die();
		}

		if ( empty( $error_message ) ) {

			$contact_data = $infusion_app->dsQuery( 'Contact', 1, 0, array( 'Email' => $_POST['email'] ), array( 'Id', 'Groups' ) );

			// Check if contact already exists

			if ( 0 < count( $contact_data ) ) {

				if ( false === strpos( $contact_data[0]['Groups'], $data_instance['args']['newsletter']['infusionsoft']['list'] ) ) {

					$infusion_app->grpAssign( $contact_data[0]['Id'], $data_instance['args']['newsletter']['infusionsoft']['list'] );

					echo json_encode(array(
						'id'				=> $_POST['data_id'],
						'status'			=> 'check',
						'success_action'	=> $success_action,
						'url'				=> $success_url,
						'message'			=> $success_mesage,
					));

					die();

				} else {

					echo json_encode(array(
						'id'			=> $_POST['data_id'],
						'status'		=> 'check',
						'message'		=> __('You are already subscribed to this list.', $_POST['text_domain']),
					));

					die();
				}

			} else {

				$new_contact_id = $infusion_app->dsAdd( 'Contact', array(
					'FirstName' => $_POST['first_name'],
					'LastName'  => $_POST['last_name'],
					'Email'     => $_POST['email'],
				) );

				$infusion_app->grpAssign( $new_contact_id, $data_instance['args']['newsletter']['infusionsoft']['list'] );

				echo json_encode(array(
					'id'				=> $_POST['data_id'],
					'status'			=> 'check',
					'success_action'	=> $success_action,
					'url'				=> $success_url,
					'message'			=> $success_mesage,
				));

				die();
			}
		}

	}

	// Add email to MyMail

	else if ($_POST['type'] == 'mymail') {

		// Check if plugin is activated

		if ( !function_exists('mymail') ) {

			echo json_encode(array(
				'id'			=> $_POST['data_id'],
				'status'		=> 'warning',
				'message'		=> __('MyMail is not activated.', $_POST['text_domain']),
			));

			die();

		}

		// Add subscriber

		$subscriber_id = mymail('subscribers')->add(array(
			'email' 	=> $_POST['email'],
			'firstname'	=> $_POST['first_name'],
			'lastname'	=> $_POST['last_name'],
			'referer'	=> 'Optin Fire',
		), false);

		// Add to List

        if ( !is_wp_error($subscriber_id) ) {

            mymail('subscribers')->assign_lists($subscriber_id, array($data_instance['args']['newsletter']['mymail']['list']));

			echo json_encode(array(
				'id'				=> $_POST['data_id'],
				'status'			=> 'check',
				'success_action'	=> $success_action,
				'url'				=> $success_url,
				'message'			=> $success_mesage,
			));

			die();

        } else {

        	echo json_encode(array(
				'id'			=> $_POST['data_id'],
				'status'		=> 'check',
				'message'		=> __('You are already subscribed to this list.', $_POST['text_domain']),
			));

			die();

        }

	}

	// Add email to Active Campaign

	else if ($_POST['type'] == 'activecampaign') {

		require_once( dirname(dirname(__FILE__)) . '/services/activecampaign/ActiveCampaign.class.php' );

		$ac = new ActiveCampaign($data_instance['args']['newsletter']['activecampaign']['app_url'], $data_instance['args']['newsletter']['activecampaign']['api_key']);

		if ( !(int)$ac->credentials_test() ) {

			echo json_encode(array(
				'id'		=> $_POST['data_id'],
				'status'	=> 'warning',
				'message'	=> __('Unable to to connect to Active Campaign.', $_POST['text_domain'])
			));

			die();
		}

		// Add subscriber

		$contact_sync = $ac->api("contact/add", array(
			"email"             													=> $_POST['email'],
			"first_name"         													=> $_POST['first_name'],
			"last_name"          													=> $_POST['last_name'],
			"p[" . $data_instance['args']['newsletter']['activecampaign']['list'] . "]"      	=> $data_instance['args']['newsletter']['activecampaign']['list'],
			"status[" . $data_instance['args']['newsletter']['activecampaign']['list'] . "]" 	=> 1,
		));

		if ( (int) $contact_sync->success ) {

			echo json_encode(array(
				'id'				=> $_POST['data_id'],
				'status'			=> 'check',
				'success_action'	=> $success_action,
				'url'				=> $success_url,
				'message'			=> $success_mesage,
			));

			die();

		} else {

			echo json_encode(array(
				'id'		=> $_POST['data_id'],
				'status'	=> 'warning',
				'message'	=> $contact_sync->error,
			));

			die();
		}
	}

	echo json_encode(array(
		'id'		=> $_POST['data_id'],
		'status'	=> 'warning',
		'message'	=> __('Unable to subscribe user. Newsletter not setup properly.', $_POST['text_domain'])
	));

	die(); // this is required to terminate immediately and return a proper response
}

endif;