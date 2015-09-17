<?php

// Exit if accessed directly

if ( !defined( 'ABSPATH' ) ) exit;

// Check if class already exists

if (!class_exists("NNR_Newsletter_Integrations_Form_v1")):

/* ================================================================================
 *
 * Base is the base class for Newsletter Integration to help with managing repetitive tasks
 *
 ================================================================================ */

class NNR_Newsletter_Integrations_Form_v1 {

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
	 * table_name
	 *
	 * (default value: '')
	 *
	 * @var string
	 * @access public
	 */
	public $table_name = '';

	/**
	 * newsletter_table_name
	 *
	 * (default value: '')
	 *
	 * @var string
	 * @access public
	 */
	public $newsletter_table_name = '';

	/**
	 * Called when the object is first created
	 *
	 * @access public
	 * @param mixed $prefix
	 * @return void
	 */
	function __construct( $prefix = '', $text_domain = '', $table_name = '', $new_table_name = '' ) {

		$this->prefix = $prefix;
		$this->text_domain = $text_domain;
		$this->table_name = $table_name;
		$this->news_table_name = $new_table_name;

		$this->include_scripts();

	}

	/**
	 * Includes all scripts for the Newsletter Form
	 *
	 * @access public
	 * @return void
	 */
	function include_scripts() {

		wp_register_script( 'newsletter-integrations-form-js', plugins_url( 'js/newsletter-integrations.js', dirname(__FILE__)), array('jquery') );
		wp_enqueue_script( 'newsletter-integrations-form-js' );
		wp_localize_script( 'newsletter-integrations-form-js', 'nnr_new_int_form_data' , array(
			'prefix'		=> $this->prefix,
			'ajaxurl'       => admin_url( 'admin-ajax.php' ),
		));

	}

	/**
	 * Display the form
	 *
	 * @access public
	 * @return void
	 */
	function display_form( $data_id, $newsletter, $args = array() ) {

		// Do not output form if data id is not provided

		if ( !isset($data_id) || empty($data_id) ) {
			return false;
		}

		// Default values for the args parameter

		$args = apply_filters('nnr_new_int_default_args', array(
			'first_name'				=> false,
			'last_name'					=> false,
			'first_name_placeholder'	=> __('First Name', $this->text_domain),
			'last_name_placeholder'		=> __('Last Name', $this->text_domain),
			'email_placeholder'			=> __('Email', $this->text_domain),
			'subscribe_icon_place'		=> 'after',
			'subscribe_icon'			=> 'fa-paper-plane',
			'button_text'				=> __('Subscribe', $this->text_domain),
			'success_action'			=> 'message',
			'success_message'			=> __('Welcome to the community!', $this->text_domain),
			'success_url'				=> '',
		), $args);

		// Create the code output

		$code = '<div class="col-md-12 ' . $this->prefix . 'form-container">';

			if ( $newsletter['newsletter'] == 'feedburner' ) {

				$code .= '<form data-id="' . $data_id . '" data-text-domain="' . $this->text_domain . '" data-table-name="' . $this->table_name . '" data-news-table-name="' . $this->news_table_name . '" class="' . $this->prefix . 'form" role="form" action="http://feedburner.google.com/fb/a/mailverify" method="post" target="' . $this->prefix . 'newsletter-window" onsubmit="window.open(\'http://feedburner.google.com/fb/a/mailverify?uri=' . esc_attr( $newsletter['newsletter']['feedburner']['id'] ) . '\', \'' . $this->prefix . 'newsletter-window\', \'scrollbars=yes,width=550,height=520\');return true">
					<div class="form-group col-md-8">
						<input type="mailinput" class="form-control" name="email" placeholder="' . $args['email_placeholder'] . '"/>
					</div>';

					// Subcribe button

					$code .= '<div class="form-group col-md-4 text-center">';

						$code .= '<button type="submit" class="' . $this->prefix . 'submit btn" name="submit">';

							if (isset($args['subscribe_icon_place']) && $args['subscribe_icon_place'] == 'before') {
								$code .= '<i class="fa ' . $args['subscribe_icon'] . '"></i>';
							}

							$code .= '<span class="' . $this->prefix . 'subscribe-button-text">' . $args['button_text'] . '</span>';

							if (isset($args['subscribe_icon_place']) && $args['subscribe_icon_place'] == 'after') {
								$code .= '<i class="fa ' . $args['subscribe_icon'] . '"></i>';
							}

						$code .= '</button>
					</div>
					</form>
					<script type="text/javascript">
						jQuery(document).ready(function($){
							$(".' . $this->prefix . '-form").submit(function(e){
								e.preventDefault();
								window.open(\'http://feedburner.google.com/fb/a/mailverify?uri=' . esc_attr( $newsletter['newsletter']['feedburner']['id'] ) . '\', \'' . $this->prefix . 'newsletter-window\', \'scrollbars=yes,width=550,height=520\');
								return true;
							});
						});
					</script>';

			} else {

				$code .= '<form data-id="' . $data_id . '" data-text-domain="' . $this->text_domain . '" data-table-name="' . $this->table_name . '" data-news-table-name="' . $this->news_table_name . '" class="' . $this->prefix . 'newsletter ' . $this->prefix . 'form" method="post" role="form" novalidate="true">

					<p class="' . $this->prefix . 'newsletter-type" data-newsletter="' . $newsletter['newsletter'] . '" style="display:none !important;"></p>';

					if ( $args['first_name'] ) {
						$code .= '<input name="' . $this->prefix . 'first-name" type="text" class="' . $this->prefix . 'first-name form-control" placeholder="' . (isset($args['first_name_placeholder']) ? $args['first_name_placeholder'] : __('First Name', $this->text_domain)) . '"/>';
					}

					if ( $args['last_name'] ) {
						$code .= '<input name="' . $this->prefix . 'last-name" type="text" class="' . $this->prefix . 'last-name form-control"  placeholder="' . (isset($args['last_name_placeholder']) ? $args['last_name_placeholder'] : __('Last Name', $this->text_domain)) . '"/>';
					}

					$code .= '<input name="' . $this->prefix . 'email" type="email" class="' . $this->prefix . 'email form-control" placeholder="' . (isset($args['email_placeholder']) ? $args['email_placeholder'] : __('Email', $this->text_domain)) . '"/>';

					// Subcribe button

					$code .= '<button type="submit" class="' . $this->prefix . 'submit btn" name="submit">';

						if (isset($args['subscribe_icon_place']) && $args['subscribe_icon_place'] == 'before') {
							$code .= '<i class="fa ' . $args['subscribe_icon'] . '" style="padding-right: 5px;"></i>';
						}

						$code .= '<span class="' . $this->prefix . 'subscribe-button-text">' . $args['button_text'] . '</span>';

						if (isset($args['subscribe_icon_place']) && $args['subscribe_icon_place'] == 'after') {
							$code .= '<i class="fa ' . $args['subscribe_icon'] . '" style="padding-left: 5px;"></i>';
						}

					$code .= '</button>
					<label class="' . $this->prefix . 'message" style="display:none;"></label>
				</form>';

			}

		$code .= '</div>';

		return $code;

	}

}

endif;