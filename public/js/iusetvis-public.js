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

	    $('#download').click(function() {
	    	actions_response_field.text("");
	    	$('#download').prop('disabled', true);
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
		        	$('#download').prop('disabled', false);
		        },
		        error: function(error) {
		            actions_response_field.text(error);
		            $('#download').prop('disabled', false);
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
		        	$('#subscribe').prop('disabled', false);
		        },
		        error: function(error) {
		            actions_response_field.text(error);
		            $('#subscribe').prop('disabled', false);
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
		        	$('#subscribe-waiting-list').prop('disabled', false);
		        },
		        error: function(error) {
		            actions_response_field.text(error);
		            $('#subscribe-waiting-list').prop('disabled', false);
		        }
		    });

	    });

	});

})( jQuery );
