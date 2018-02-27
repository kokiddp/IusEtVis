(function( $ ) {
	'use strict';

	/**
	 * All of the code for your public-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */

	$(document).ready(function() {

		var user_id = $("#hidden_user_id:hidden").text().replace(/\s/g,"");
		var course_id = $("#hidden_course_id:hidden").text().replace(/\s/g,"");

		var actions_response_field = $("#actions_response_field");

	    $('#diploma').click(function() {
	    	actions_response_field.text("");
	    	$('#diploma').prop('disabled', true);
	        $.ajax({
	        	dataType: 'native',
		        url: pdf_print_diploma_ajax.ajaxurl,
		        xhrFields: {
				    responseType: 'blob'
				  },
		        type: 'POST',
		        data: {
		            'action': 'pdf_print_diploma',
		            'user_id': user_id,
		            'course_id': course_id
		        },
		        success: function(blob) {
		        	if (blob.size > 0) {
		        		console.log(blob);
			            var link=document.createElement('a');
					    link.href=window.URL.createObjectURL(blob);
					    link.download="Crediti.pdf";
					    link.click();
		        	}
		        	else {
		        		actions_response_field.text("Error: unable to download the pdf.");
		        	}
		        	$('#diploma').prop('disabled', false);
		        },
		        error: function(error) {
		            actions_response_field.text(error.status + ' ( ' + error.statusText + ' )');
		            $('#diploma').prop('disabled', false);
		        }
		    });

	    });

	    $('#notice').click(function() {
	    	actions_response_field.text("");
	    	$('#notice').prop('disabled', true);
	        $.ajax({
	        	dataType: 'native',
		        url: pdf_print_notice_ajax.ajaxurl,
		        xhrFields: {
				    responseType: 'blob'
				  },
		        type: 'POST',
		        data: {
		            'action': 'pdf_print_notice',
		            'user_id': user_id,
		            'course_id': course_id
		        },
		        success: function(blob) {
		        	if (blob.size > 0) {
		        		console.log(blob);
			            var link=document.createElement('a');
					    link.href=window.URL.createObjectURL(blob);
					    link.download="Notifica.pdf";
					    link.click();
		        	}
		        	else {
		        		actions_response_field.text("Error: unable to download the pdf.");
		        	}
		        	$('#notice').prop('disabled', false);
		        },
		        error: function(error) {
		            actions_response_field.text(error.status + ' ( ' + error.statusText + ' )');
		            $('#notice').prop('disabled', false);
		        }
		    });

	    });

	    $('#bill').click(function() {
	    	actions_response_field.text("");
	    	$('#bill').prop('disabled', true);
	        $.ajax({
	        	dataType: 'native',
		        url: pdf_print_bill_ajax.ajaxurl,
		        xhrFields: {
				    responseType: 'blob'
				  },
		        type: 'POST',
		        data: {
		            'action': 'pdf_print_bill',
		            'user_id': user_id,
		            'course_id': course_id
		        },
		        success: function(blob) {
		        	if (blob.size > 0) {
		        		console.log(blob);
			            var link=document.createElement('a');
					    link.href=window.URL.createObjectURL(blob);
					    link.download="Conto.pdf";
					    link.click();
		        	}
		        	else {
		        		actions_response_field.text("Error: unable to download the pdf.");
		        	}
		        	$('#bill').prop('disabled', false);
		        },
		        error: function(error) {
		            actions_response_field.text(error.status + ' ( ' + error.statusText + ' )');
		            $('#bill').prop('disabled', false);
		        }
		    });

	    });

	    $('#subscribe').click(function() {
	    	actions_response_field.text("");
	    	$('#subscribe').prop('disabled', true);
	        $.ajax({
	        	dataType: 'native',
		        url: course_subscribe_ajax.ajaxurl,
		        type: 'POST',
		        data: {
		            'action': 'course_subscribe',
		            'user_id': user_id,
		            'course_id': course_id
		        },
		        success: function(response) {
		        	actions_response_field.text(response);
		        	$('#subscribe').prop('disabled', true);
		        },
		        error: function(error) {
		            actions_response_field.text(error.status + ' ( ' + error.statusText + ' )');
		            $('#subscribe').prop('disabled', false);
		        }
		    });

	    });

	    $('#unsubscribe').click(function() {
	    	actions_response_field.text("");
	    	$('#unsubscribe').prop('disabled', true);
	        $.ajax({
	        	dataType: 'native',
		        url: course_unsubscribe_ajax.ajaxurl,
		        type: 'POST',
		        data: {
		            'action': 'course_unsubscribe',
		            'user_id': user_id,
		            'course_id': course_id
		        },
		        success: function(response) {
		        	actions_response_field.text(response);
		        	$('#unsubscribe').prop('disabled', true);
		        },
		        error: function(error) {
		            actions_response_field.text(error.status + ' ( ' + error.statusText + ' )');
		            $('#unsubscribe').prop('disabled', false);
		        }
		    });

	    });

	    $('#subscribe-waiting-list').click(function() {
	    	actions_response_field.text("");
	    	$('#subscribe-waiting-list').prop('disabled', true);
	        $.ajax({
	        	dataType: 'native',
		        url: course_waiting_list_subscribe_ajax.ajaxurl,
		        type: 'POST',
		        data: {
		            'action': 'course_waiting_list_subscribe',
		            'user_id': user_id,
		            'course_id': course_id
		        },
		        success: function(response) {
		        	actions_response_field.text(response);
		        	$('#subscribe-waiting-list').prop('disabled', true);
		        },
		        error: function(error) {
		            actions_response_field.text(error.status + ' ( ' + error.statusText + ' )');
		            $('#subscribe-waiting-list').prop('disabled', false);
		        }
		    });

	    });

	});

})( jQuery );
