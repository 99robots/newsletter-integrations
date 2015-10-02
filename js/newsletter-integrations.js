/* =====================================================================================
 *
 * This file is used to control all the Newsletter form actions
 *
 * ================================================================================== */

jQuery(document).ready(function($) {

	$('.' + nnr_new_int_form_data.prefix + 'newsletter').submit(function(event){

		if ( $(this).find('.' + nnr_new_int_form_data.prefix + 'submit-spinner').length != 0) {
			return false;
		}

	    $(this).find('.' + nnr_new_int_form_data.prefix + 'submit')
	    	.append('<i style="margin-left: 10px;" class="' + nnr_new_int_form_data.prefix + 'submit-spinner fa fa-spinner fa-spin"></i>');

	    event.preventDefault();

		var data = {
			'action': 'nnr_new_int_add_email',
			'data_id': $(this).attr('data-id'),
			'text_domain': $(this).attr('data-text-domain'),
			'table_name': $(this).attr('data-table-name'),
			'news_table_name': $(this).attr('data-news-table-name'),
			'stats_table_name': $(this).attr('data-stats-table-name'),
			'type': $(this).find('.' + nnr_new_int_form_data.prefix + 'newsletter-type').data('newsletter'),
			'email': $(this).find('.' + nnr_new_int_form_data.prefix + 'email').val(),
			'first_name': $(this).find('.' + nnr_new_int_form_data.prefix + 'first-name').val(),
			'last_name': $(this).find('.' + nnr_new_int_form_data.prefix + 'last-name').val(),
		};

		// Submit Data to the back end

		$.post(nnr_new_int_form_data.ajaxurl, data, function(response) {

			response = jQuery.parseJSON(response);

			if ( typeof(response.conversion) != 'undefined' ) {
				console.log(response.conversion);
			}

			// Success

			if ( response.status == 'check' ) {

				// Redirect

				if (response.success_action == 'redirect') {

					var win = window.open(response.url, '_self');
					win.focus();

				}

				// Message

				else {

					$('.' + nnr_new_int_form_data.prefix + 'newsletter[data-id="' + response.id + '"]')
						.find('.' + nnr_new_int_form_data.prefix + 'message')
						.html('<i class="fa fa-' + response.status + '"></i> <span>' + response.message + '</span>');

					$('.' + nnr_new_int_form_data.prefix + 'newsletter[data-id="' + response.id + '"]')
						.find('.' + nnr_new_int_form_data.prefix + 'message')
						.removeClass('hidden')
						.css('display', 'inline-block');

				}
			}

			// Error

			else {


				$('.' + nnr_new_int_form_data.prefix + 'newsletter[data-id="' + response.id + '"]')
					.find('.' + nnr_new_int_form_data.prefix + 'message')
					.html('<i class="fa fa-' + response.status + '"></i> <span>' + response.message + '</span>');

				$('.' + nnr_new_int_form_data.prefix + 'newsletter[data-id="' + response.id + '"]')
					.find('.' + nnr_new_int_form_data.prefix + 'message')
					.removeClass('hidden')
					.css('display', 'inline-block');
			}

			$('.' + nnr_new_int_form_data.prefix + 'newsletter[data-id="' + response.id + '"]').find('.' + nnr_new_int_form_data.prefix + 'submit-spinner').remove();

		});

	});

});