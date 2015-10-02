/* =====================================================================================
 *
 * This file is used to hide and show the newsletter settings based on what service is
 * selected.  It is will also retrieve all newsletter list data based on what values the
 * user inputs for the API Keys or Tokens.
 *
 * ================================================================================== */

jQuery(document).ready(function($) {

	/* =====================================================================================
	 *
	 * Hide and Show Settings
	 *
	 * ================================================================================== */

	$('.' + nnr_new_int_data.prefix + 'newsletter').hide();
	$("." + nnr_new_int_data.prefix + "newsletter-" + $('#' + nnr_new_int_data.prefix + 'newsletter').val()).show();

  	$('#' + nnr_new_int_data.prefix + 'newsletter').change(function() {

	  	// Show and Hide Newsletter settings

 		$('.' + nnr_new_int_data.prefix + 'newsletter').hide();
 		$("." + nnr_new_int_data.prefix + "newsletter-" + $(this).val()).show();

	});

	$("." + nnr_new_int_data.prefix + "newsletter-success-action").hide();
	$("." + nnr_new_int_data.prefix + "newsletter-success-action-" + $("#" + nnr_new_int_data.prefix + "newsletter-success-action").val()).show();

 	$("#" + nnr_new_int_data.prefix + "newsletter-success-action").change(function() {
 		$("." + nnr_new_int_data.prefix + "newsletter-success-action").hide();
 		$("." + nnr_new_int_data.prefix + "newsletter-success-action-" + $(this).val()).show();

 	});

 	$('.nnr-color-input').wpColorPicker();
 	$('button[role="iconpicker"]').iconpicker().iconpicker('setCols', 10).iconpicker('setRows', 5);

	/* =====================================================================================
	 *
	 * MailChimp
	 *
	 * ================================================================================== */

	$('#' + nnr_new_int_data.prefix + 'newsletter-mailchimp-api-key').keyup(function(){

		// Do nothing if we are already retrieve the lists

		if ($('#' + nnr_new_int_data.prefix + 'mailchimp-get-lists-spinner').length != 0) {
			return;
		}

		$('<i id="' + nnr_new_int_data.prefix + 'mailchimp-get-lists-spinner" class="fa fa-spinner fa-spin" style="margin-left: 10px;"></i>')
			.insertAfter('#' + nnr_new_int_data.prefix + 'newsletter-mailchimp-list');

		var data = {
			'action': 'nnr_new_int_get_mailchimp_lists',
			'api_key': $('#' + nnr_new_int_data.prefix + 'newsletter-mailchimp-api-key').val()
		};

		$.post(ajaxurl, data, function(response) {
			$('#' + nnr_new_int_data.prefix +'mailchimp-get-lists-spinner').remove();
			$('#' + nnr_new_int_data.prefix +'newsletter-mailchimp-list').html(response);
		});
	});

	$('<i id="' + nnr_new_int_data.prefix + 'mailchimp-get-lists-spinner" class="fa fa-spinner fa-spin" style="margin-left: 10px;"></i>')
		.insertAfter('#' + nnr_new_int_data.prefix +'newsletter-mailchimp-list');

	var data = {
		'action': 'nnr_new_int_get_mailchimp_lists',
		'api_key': $('#' + nnr_new_int_data.prefix + 'newsletter-mailchimp-api-key').val(),
		'list': $('#' + nnr_new_int_data.prefix + 'newsletter-mailchimp-list').attr('data-list'),
	};


	$.post(ajaxurl, data, function(response) {
		$('#' + nnr_new_int_data.prefix + 'mailchimp-get-lists-spinner').remove();
		$('#' + nnr_new_int_data.prefix + 'newsletter-mailchimp-list').html(response);
	});

	/* =====================================================================================
	 *
	 * Aweber
	 *
	 * ================================================================================== */

	$('.' + nnr_new_int_data.prefix + 'newsletter-aweber-connect').click(function(){
		$('#' + nnr_new_int_data.prefix + 'newsletter-aweber-code').parent('div').parent('div').removeClass('hidden');
		$('#' + nnr_new_int_data.prefix + 'newsletter-aweber-code').removeClass('hidden');
	});

	$('#' + nnr_new_int_data.prefix + 'newsletter-aweber-code').keyup(function (){

		// Do nothing if we are already retrieve the lists

		if ($('#' + nnr_new_int_data.prefix + 'aweber-get-lists-spinner').length != 0) {
			return;
		}

		// Do nothing if the user did not input a code

		if ($('#' + nnr_new_int_data.prefix + 'newsletter-aweber-code').val() == '') {
			return;
		}

		$('#' + nnr_new_int_data.prefix +'newsletter-aweber-list-id').html('');

		$('<i id="' + nnr_new_int_data.prefix + 'aweber-get-lists-spinner" class="fa fa-spinner fa-spin"></i>').insertAfter('#' + nnr_new_int_data.prefix +'newsletter-aweber-list-id');

		var data = {
			'action': 'nnr_new_int_get_aweber_lists',
			'consumer_key': $('#' + nnr_new_int_data.prefix + 'newsletter-aweber-consumer-key').val(),
			'consumer_secret': $('#' + nnr_new_int_data.prefix + 'newsletter-aweber-consumer-secret').val(),
			'access_key': $('#' + nnr_new_int_data.prefix + 'newsletter-aweber-access-key').val(),
			'access_secret': $('#' + nnr_new_int_data.prefix + 'newsletter-aweber-access-secret').val(),
			'code': $('#' + nnr_new_int_data.prefix + 'newsletter-aweber-code').val(),
			'newsletter_id': getUrlParameter('newsletter_id')
		};

		$.post(ajaxurl, data, function(response) {

			response = $.parseJSON(response);

			$('#' + nnr_new_int_data.prefix +'aweber-get-lists-spinner').remove();
			$('#' + nnr_new_int_data.prefix +'newsletter-aweber-list-id').html(response.html);

			$('#' + nnr_new_int_data.prefix + 'newsletter-aweber-consumer-key').val(response.consumer_key);
			$('#' + nnr_new_int_data.prefix + 'newsletter-aweber-consumer-secret').val(response.consumer_secret);
			$('#' + nnr_new_int_data.prefix + 'newsletter-aweber-access-key').val(response.access_key);
			$('#' + nnr_new_int_data.prefix + 'newsletter-aweber-access-secret').val(response.access_secret);

			if (response.consumer_key != '') {
				$('.' + nnr_new_int_data.prefix + 'newsletter-aweber-code').addClass('hidden');
			}

		});
	});

	$('#' + nnr_new_int_data.prefix +'newsletter-aweber-list-id').html('');

	$('<i id="' + nnr_new_int_data.prefix + 'aweber-get-lists-spinner" class="fa fa-spinner fa-spin"></i>')
		.insertAfter('#' + nnr_new_int_data.prefix +'newsletter-aweber-list-id');

	var data = {
		'action': 'nnr_new_int_get_aweber_lists',
		'consumer_key': $('#' + nnr_new_int_data.prefix + 'newsletter-aweber-consumer-key').val(),
		'consumer_secret': $('#' + nnr_new_int_data.prefix + 'newsletter-aweber-consumer-secret').val(),
		'access_key': $('#' + nnr_new_int_data.prefix + 'newsletter-aweber-access-key').val(),
		'access_secret': $('#' + nnr_new_int_data.prefix + 'newsletter-aweber-access-secret').val(),
		'code': $('#' + nnr_new_int_data.prefix + 'newsletter-aweber-code').val(),
		'newsletter_id': getUrlParameter('newsletter_id'),
		'list': $('#' + nnr_new_int_data.prefix + 'newsletter-aweber-list-id').attr('data-list'),
	};

	$.post(ajaxurl, data, function(response) {

		response = $.parseJSON(response);

		$('#' + nnr_new_int_data.prefix +'aweber-get-lists-spinner').remove();
		$('#' + nnr_new_int_data.prefix +'newsletter-aweber-list-id').html(response.html);

		$('#' + nnr_new_int_data.prefix + 'newsletter-aweber-consumer-key').val(response.consumer_key);
		$('#' + nnr_new_int_data.prefix + 'newsletter-aweber-consumer-secret').val(response.consumer_secret);
		$('#' + nnr_new_int_data.prefix + 'newsletter-aweber-access-key').val(response.access_key);
		$('#' + nnr_new_int_data.prefix + 'newsletter-aweber-access-secret').val(response.access_secret);

		if (response.consumer_key != '') {
			$('.' + nnr_new_int_data.prefix + 'newsletter-aweber-code').addClass('hidden');
		}

	});

	/* =====================================================================================
	 *
	 * Get Response
	 *
	 * ================================================================================== */

	$('#' + nnr_new_int_data.prefix + 'newsletter-getresponse-api-key').keyup(function(){

		// Do nothing if we are already retrieve the lists

		if ($('#' + nnr_new_int_data.prefix + 'getresponse-get-lists-spinner').length != 0) {
			return;
		}

		$('<i id="' + nnr_new_int_data.prefix + 'getresponse-get-lists-spinner" class="fa fa-spinner fa-spin"></i>').insertAfter('#' + nnr_new_int_data.prefix +'newsletter-getresponse-campaign');

		var data = {
			'action': 'nnr_new_int_get_getresponse_lists',
			'api_key': $('#' + nnr_new_int_data.prefix + 'newsletter-getresponse-api-key').val()
		};

		$.post(ajaxurl, data, function(response) {
			$('#' + nnr_new_int_data.prefix +'getresponse-get-lists-spinner').remove();
			$('#' + nnr_new_int_data.prefix +'newsletter-getresponse-campaign').html(response);
		});
	});

	$('<i id="' + nnr_new_int_data.prefix + 'getresponse-get-lists-spinner" class="fa fa-spinner fa-spin"></i>')
		.insertAfter('#' + nnr_new_int_data.prefix +'newsletter-getresponse-campaign');

	var data = {
		'action': 'nnr_new_int_get_getresponse_lists',
		'api_key': $('#' + nnr_new_int_data.prefix + 'newsletter-getresponse-api-key').val(),
		'campaign': $('#' + nnr_new_int_data.prefix + 'newsletter-getresponse-campaign').attr('data-campaign')
	};

	$.post(ajaxurl, data, function(response) {
		$('#' + nnr_new_int_data.prefix +'getresponse-get-lists-spinner').remove();
		$('#' + nnr_new_int_data.prefix +'newsletter-getresponse-campaign').html(response);
	});

	/* =====================================================================================
	 *
	 * Campaign Monitor
	 *
	 * ================================================================================== */

	$('#' + nnr_new_int_data.prefix + 'newsletter-campaignmonitor-api-key').keyup(function(){

		// Do nothing if we are already retrieve the lists

		if ($('.' + nnr_new_int_data.prefix + 'campaignmonitor-get-lists-spinner').length != 0) {
			return;
		}

		$('<i class="' + nnr_new_int_data.prefix + 'campaignmonitor-get-lists-spinner fa fa-spinner fa-spin"></i>').insertAfter('#' + nnr_new_int_data.prefix +'newsletter-campaignmonitor-list');
		$('<i class="' + nnr_new_int_data.prefix + 'campaignmonitor-get-lists-spinner fa fa-spinner fa-spin"></i>').insertAfter('#' + nnr_new_int_data.prefix +'newsletter-campaignmonitor-client');

		var data = {
			'action': 'nnr_new_int_get_campaignmonitor_lists',
			'api_key': $('#' + nnr_new_int_data.prefix + 'newsletter-campaignmonitor-api-key').val(),
		};

		$.post(ajaxurl, data, function(response) {

			response = $.parseJSON(response);

			$('.' + nnr_new_int_data.prefix +'campaignmonitor-get-lists-spinner').remove();
			$('#' + nnr_new_int_data.prefix +'newsletter-campaignmonitor-client').html(response.clients);
			$('#' + nnr_new_int_data.prefix +'newsletter-campaignmonitor-list').html(response.lists);

		});
	});

	$.post(ajaxurl, data, function(response) {

		response = $.parseJSON(response);

		if ( typeof(response) == 'undefined' || response == null ) {
			return;
		}

		$('.' + nnr_new_int_data.prefix +'campaignmonitor-get-lists-spinner').remove();

		if ( typeof(response) != 'undefined' && typeof(response.clients) != 'undefined' ) {
			$('#' + nnr_new_int_data.prefix +'newsletter-campaignmonitor-client').html(response.clients);
		}

		if ( typeof(response) != 'undefined' && typeof(response.lists) != 'undefined' ) {
			$('#' + nnr_new_int_data.prefix +'newsletter-campaignmonitor-list').html(response.lists);
		}

	});

	$('#' + nnr_new_int_data.prefix + 'newsletter-campaignmonitor-client').change(function(){

		// Do nothing if we are already retrieve the lists

		if ($('.' + nnr_new_int_data.prefix + 'campaignmonitor-get-lists-spinner').length != 0) {
			return;
		}

		$('<i class="' + nnr_new_int_data.prefix + 'campaignmonitor-get-lists-spinner fa fa-spinner fa-spin"></i>').insertAfter('#' + nnr_new_int_data.prefix +'newsletter-campaignmonitor-list');

		var data = {
			'action': 'nnr_new_int_update_campaignmonitor_lists',
			'api_key': $('#' + nnr_new_int_data.prefix + 'newsletter-campaignmonitor-api-key').val(),
			'client_id': $(this).val(),
		};

		$.post(ajaxurl, data, function(response) {
			$('.' + nnr_new_int_data.prefix +'campaignmonitor-get-lists-spinner').remove();
			$('#' + nnr_new_int_data.prefix +'newsletter-campaignmonitor-list').html(response);
		});
	});

	$('<i class="' + nnr_new_int_data.prefix + 'campaignmonitor-get-lists-spinner fa fa-spinner fa-spin"></i>')
		.insertAfter('#' + nnr_new_int_data.prefix +'newsletter-campaignmonitor-list');
	$('<i class="' + nnr_new_int_data.prefix + 'campaignmonitor-get-lists-spinner fa fa-spinner fa-spin"></i>')
		.insertAfter('#' + nnr_new_int_data.prefix +'newsletter-campaignmonitor-client');

	var data = {
		'action': 'nnr_new_int_get_campaignmonitor_lists',
		'api_key': $('#' + nnr_new_int_data.prefix + 'newsletter-campaignmonitor-api-key').val(),
		'client': $('#' + nnr_new_int_data.prefix + 'newsletter-campaignmonitor-client').attr('data-client'),
		'list': $('#' + nnr_new_int_data.prefix + 'newsletter-campaignmonitor-list').attr('data-list'),
	};

	/* =====================================================================================
	 *
	 * Mad Mimi
	 *
	 * ================================================================================== */

	$('#' + nnr_new_int_data.prefix + 'newsletter-madmimi-api-key').keyup(function(){

		// Do nothing if we are already retrieve the lists

		if ($('#' + nnr_new_int_data.prefix + 'madmimi-get-lists-spinner').length != 0) {
			return;
		}

		$('<i id="' + nnr_new_int_data.prefix + 'madmimi-get-lists-spinner" class="fa fa-spinner fa-spin"></i>').insertAfter('#' + nnr_new_int_data.prefix +'newsletter-madmimi-list');

		var data = {
			'action': 'nnr_new_int_get_madmimi_lists',
			'api_key': $('#' + nnr_new_int_data.prefix + 'newsletter-madmimi-api-key').val(),
			'username': $('#' + nnr_new_int_data.prefix + 'newsletter-madmimi-username').val(),
			'list': $('#' + nnr_new_int_data.prefix +'newsletter-madmimi-list').val()
		};

		$.post(ajaxurl, data, function(response) {
			$('#' + nnr_new_int_data.prefix +'madmimi-get-lists-spinner').remove();
			$('#' + nnr_new_int_data.prefix +'newsletter-madmimi-list').html(response);
		});
	});

	$('<i id="' + nnr_new_int_data.prefix + 'madmimi-get-lists-spinner" class="fa fa-spinner fa-spin"></i>')
		.insertAfter('#' + nnr_new_int_data.prefix +'newsletter-madmimi-list');

	var data = {
		'action': 'nnr_new_int_get_madmimi_lists',
		'api_key': $('#' + nnr_new_int_data.prefix + 'newsletter-madmimi-api-key').val(),
		'username': $('#' + nnr_new_int_data.prefix + 'newsletter-madmimi-username').val(),
		'list': $('#' + nnr_new_int_data.prefix +'newsletter-madmimi-list').attr('data-list')
	};

	$.post(ajaxurl, data, function(response) {
		$('#' + nnr_new_int_data.prefix +'madmimi-get-lists-spinner').remove();
		$('#' + nnr_new_int_data.prefix +'newsletter-madmimi-list').html(response);
	});

	/* =====================================================================================
	 *
	 * Infusionsoft
	 *
	 * ================================================================================== */

	$('#' + nnr_new_int_data.prefix + 'newsletter-infusionsoft-api-key').keyup(function(){

		// Do nothing if we are already retrieve the lists

		if ($('#' + nnr_new_int_data.prefix + 'infusionsoft-get-lists-spinner').length != 0) {
			return;
		}

		$('<i id="' + nnr_new_int_data.prefix + 'infusionsoft-get-lists-spinner" class="fa fa-spinner fa-spin"></i>').insertAfter('#' + nnr_new_int_data.prefix + 'newsletter-infusionsoft-list');

		var data = {
			'action': 'nnr_new_int_get_infusionsoft_lists',
			'app_id': $('#' + nnr_new_int_data.prefix + 'newsletter-infusionsoft-app-id').val(),
			'api_key': $('#' + nnr_new_int_data.prefix + 'newsletter-infusionsoft-api-key').val(),
			'list': $('#' + nnr_new_int_data.prefix +'newsletter-infusionsoft-list').val()
		};

		$.post(ajaxurl, data, function(response) {
			$('#' + nnr_new_int_data.prefix +'infusionsoft-get-lists-spinner').remove();
			$('#' + nnr_new_int_data.prefix +'newsletter-infusionsoft-list').html(response);
		});
	});

	$('<i id="' + nnr_new_int_data.prefix + 'infusionsoft-get-lists-spinner" class="fa fa-spinner fa-spin"></i>')
		.insertAfter('#' + nnr_new_int_data.prefix +'newsletter-infusionsoft-list');

	var data = {
		'action': 'nnr_new_int_get_infusionsoft_lists',
		'app_id': $('#' + nnr_new_int_data.prefix + 'newsletter-infusionsoft-app-id').val(),
		'api_key': $('#' + nnr_new_int_data.prefix + 'newsletter-infusionsoft-api-key').val(),
		'list': $('#' + nnr_new_int_data.prefix +'newsletter-infusionsoft-list').attr('data-list')
	};

	$.post(ajaxurl, data, function(response) {
		$('#' + nnr_new_int_data.prefix +'infusionsoft-get-lists-spinner').remove();
		$('#' + nnr_new_int_data.prefix +'newsletter-infusionsoft-list').html(response);
	});

	/* =====================================================================================
	 *
	 * MyMail
	 *
	 * ================================================================================== */

	$('#' + nnr_new_int_data.prefix + 'newsletter-mymail-api-key').keyup(function(){

		// Do nothing if we are already retrieve the lists

		if ($('#' + nnr_new_int_data.prefix + 'mymail-get-lists-spinner').length != 0) {
			return;
		}

		$('<i id="' + nnr_new_int_data.prefix + 'mymail-get-lists-spinner" class="fa fa-spinner fa-spin"></i>').insertAfter('#' + nnr_new_int_data.prefix + 'newsletter-mymail-list');

		var data = {
			'action': 'nnr_new_int_get_mymail_lists',
			'list': $('#' + nnr_new_int_data.prefix +'newsletter-mymail-list').val()
		};

		$.post(ajaxurl, data, function(response) {
			$('#' + nnr_new_int_data.prefix +'mymail-get-lists-spinner').remove();
			$('#' + nnr_new_int_data.prefix +'newsletter-mymail-list').html(response);
		});
	});

	$('<i id="' + nnr_new_int_data.prefix + 'mymail-get-lists-spinner" class="fa fa-spinner fa-spin"></i>')
		.insertAfter('#' + nnr_new_int_data.prefix +'newsletter-mymail-list');

	var data = {
		'action': 'nnr_new_int_get_mymail_lists',
		'list': $('#' + nnr_new_int_data.prefix +'newsletter-mymail-list').attr('data-list')
	};

	$.post(ajaxurl, data, function(response) {
		$('#' + nnr_new_int_data.prefix +'mymail-get-lists-spinner').remove();
		$('#' + nnr_new_int_data.prefix +'newsletter-mymail-list').html(response);
	});

	/* =====================================================================================
	 *
	 * Active Campiagn
	 *
	 * ================================================================================== */

	$('#' + nnr_new_int_data.prefix + 'newsletter-activecampaign-api-key').keyup(function(){

		// Do nothing if we are already retrieve the lists

		if ($('#' + nnr_new_int_data.prefix + 'activecampaign-get-lists-spinner').length != 0) {
			return;
		}

		$('<i id="' + nnr_new_int_data.prefix + 'activecampaign-get-lists-spinner" class="fa fa-spinner fa-spin"></i>').insertAfter('#' + nnr_new_int_data.prefix + 'newsletter-activecampaign-list');

		var data = {
			'action': 'nnr_new_int_get_activecampaign_lists',
			'app_url': $('#' + nnr_new_int_data.prefix + 'newsletter-activecampaign-app-url').val(),
			'api_key': $('#' + nnr_new_int_data.prefix + 'newsletter-activecampaign-api-key').val(),
			'list': $('#' + nnr_new_int_data.prefix +'newsletter-activecampaign-list').val()
		};

		$.post(ajaxurl, data, function(response) {
			$('#' + nnr_new_int_data.prefix +'activecampaign-get-lists-spinner').remove();
			$('#' + nnr_new_int_data.prefix +'newsletter-activecampaign-list').html(response);
		});
	});

	$('<i id="' + nnr_new_int_data.prefix + 'activecampaign-get-lists-spinner" class="fa fa-spinner fa-spin"></i>')
		.insertAfter('#' + nnr_new_int_data.prefix +'newsletter-activecampaign-list');

	var data = {
		'action': 'nnr_new_int_get_activecampaign_lists',
		'app_url': $('#' + nnr_new_int_data.prefix + 'newsletter-activecampaign-app-url').val(),
		'api_key': $('#' + nnr_new_int_data.prefix + 'newsletter-activecampaign-api-key').val(),
		'list': $('#' + nnr_new_int_data.prefix +'newsletter-activecampaign-list').attr('data-list')
	};

	$.post(ajaxurl, data, function(response) {
		$('#' + nnr_new_int_data.prefix +'activecampaign-get-lists-spinner').remove();
		$('#' + nnr_new_int_data.prefix +'newsletter-activecampaign-list').html(response);
	});

	/**
	 * Gets an HTTP parameter
	 *
	 * @access public
	 * @param mixed sParam
	 * @return void
	 */
	function getUrlParameter(sParam) {
	    var sPageURL = window.location.search.substring(1);
	    var sURLVariables = sPageURL.split('&');
	    for (var i = 0; i < sURLVariables.length; i++)
	    {
	        var sParameterName = sURLVariables[i].split('=');
	        if (sParameterName[0] == sParam)
	        {
	            return sParameterName[1];
	        }
	    }
	}

});