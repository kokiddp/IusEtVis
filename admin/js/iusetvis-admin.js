(function( $ ) {
	'use strict';

	/**
	 * All of the code for your admin-facing JavaScript source
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

	/*
	$(function() {
	 	if ($('#association_state:checkbox:checked')) {
	 		$('#association_end_row').show();
	 	}
	 	else {
	 		$('#association_end_row').hide();
	 	}

		$("#association_state").change(function() {
		    if($(this).is(":checked")) {
		        $('#association_end_row').show();
		    }
		    else {
		    	$('#association_end_row').hide();
		    	$('#association_end').text('');
		    }
		});

	 });
	 */

	$(document).ready(function() {

		var actions_response_field = $("#actions_response_field");

		$('#perfect-subscription').click(function() {
	    	actions_response_field.text("");
	    	var user_id = $("#user_id").val();
			var course_id = $("#course_id").val();
	    	$('#perfect-subscription').prop('disabled', true);
	        $.ajax({
	        	dataType: 'native',
		        url: perfect_user_subscription_ajax.ajaxurl,
		        type: 'POST',
		        data: {
		            'action': 'perfect_user_subscription',
		            'user_id': user_id,
		            'course_id': course_id
		        },
		        success: function(response) {
		        	actions_response_field.text(response);
		        	$('#perfect-subscription').prop('disabled', false);
		        },
		        error: function(error) {
		            actions_response_field.text(error);
		            $('#perfect-subscription').prop('disabled', false);
		        }
		    });

	    });

	    $('#unperfect-subscription').click(function() {
	    	actions_response_field.text("");
	    	var user_id = $("#user_id").val();
			var course_id = $("#course_id").val();
	    	$('#unperfect-subscription').prop('disabled', true);
	        $.ajax({
	        	dataType: 'native',
		        url: unperfect_user_subscription_ajax.ajaxurl,
		        type: 'POST',
		        data: {
		            'action': 'unperfect_user_subscription',
		            'user_id': user_id,
		            'course_id': course_id
		        },
		        success: function(response) {
		        	actions_response_field.text(response);
		        	$('#unperfect-subscription').prop('disabled', false);
		        },
		        error: function(error) {
		            actions_response_field.text(error);
		            $('#unperfect-subscription').prop('disabled', false);
		        }
		    });

	    });

	    $('#confirm-attendance').click(function() {
	    	actions_response_field.text("");
	    	var user_id = $("#user_id").val();
			var course_id = $("#course_id").val();
	    	$('#confirm-attendance').prop('disabled', true);
	        $.ajax({
	        	dataType: 'native',
		        url: confirm_user_attendance_ajax.ajaxurl,
		        type: 'POST',
		        data: {
		            'action': 'confirm_user_attendance',
		            'user_id': user_id,
		            'course_id': course_id
		        },
		        success: function(response) {
		        	actions_response_field.text(response);
		        	$('#confirm-attendance').prop('disabled', false);
		        },
		        error: function(error) {
		            actions_response_field.text(error);
		            $('#confirm-attendance').prop('disabled', false);
		        }
		    });

	    });

	    $('#delete-attendance').click(function() {
	    	actions_response_field.text("");
	    	var user_id = $("#user_id").val();
			var course_id = $("#course_id").val();
	    	$('#delete-attendance').prop('disabled', true);
	        $.ajax({
	        	dataType: 'native',
		        url: delete_user_attendance_ajax.ajaxurl,
		        type: 'POST',
		        data: {
		            'action': 'delete_user_attendance',
		            'user_id': user_id,
		            'course_id': course_id
		        },
		        success: function(response) {
		        	actions_response_field.text(response);
		        	$('#delete-attendance').prop('disabled', false);
		        },
		        error: function(error) {
		            actions_response_field.text(error);
		            $('#delete-attendance').prop('disabled', false);
		        }
		    });

	    });

	    $('.perfected_checkbox').change(function() {
	    	var user_id = $(this).data("user_id");
			var course_id = getParameterByName('course_id');
			actions_response_field.text("");
			var ref = $(this);
			ref.prop('disabled', 'disabled');
			if(ref.is(":checked")) {
				actions_response_field.text("Perfecting subscription...");
				$.ajax({
		        	dataType: 'native',
			        url: perfect_user_subscription_ajax.ajaxurl,
			        type: 'POST',
			        data: {
			            'action': 'perfect_user_subscription',
			            'user_id': user_id,
			            'course_id': course_id
			        },
			        success: function(response) {
			        	actions_response_field.text(response);
			        	if (response.includes('Error: ')) {
			        		ref.attr('checked', false);
			        	}
			        	ref.prop('disabled', false);
			        },
			        error: function(error) {
			            actions_response_field.text(response);
			            ref.attr('checked', false);
			            ref.prop('disabled', false);
			        }
		    	});
			}
			else {
				actions_response_field.text("Unperfecting subscription...");
				$.ajax({
		        	dataType: 'native',
			        url: unperfect_user_subscription_ajax.ajaxurl,
			        type: 'POST',
			        data: {
			            'action': 'unperfect_user_subscription',
			            'user_id': user_id,
			            'course_id': course_id
			        },
			        success: function(response) {
			        	actions_response_field.text(response);
			        	if (response.includes('Error: ')) {
			        		ref.attr('checked', true);
			        	}
			        	ref.prop('disabled', false);
			        },
			        error: function(error) {
			            actions_response_field.text(response);
			            ref.attr('checked', true);
			            ref.prop('disabled', false);
			        }
			    });
			}

	    });

	    $('.confirmed_checkbox').change(function() {
	    	var user_id = $(this).data("user_id");
			var course_id = getParameterByName('course_id');
			actions_response_field.text("");
			var ref = $(this);
			ref.prop('disabled', 'disabled');
			if(ref.is(":checked")) {
				actions_response_field.text("Confirming attendance...");
				$.ajax({
		        	dataType: 'native',
			        url: confirm_user_attendance_ajax.ajaxurl,
			        type: 'POST',
			        data: {
			            'action': 'confirm_user_attendance',
			            'user_id': user_id,
			            'course_id': course_id
			        },
			        success: function(response) {
			        	actions_response_field.text(response);
			        	if (response.includes('Error: ')) {
			        		ref.attr('checked', false);
			        	}
			        	ref.prop('disabled', false);
			        },
			        error: function(error) {
			            actions_response_field.text(response);
			            ref.attr('checked', false);
			            ref.prop('disabled', false);
			        }
		    	});
			}
			else {
				actions_response_field.text("Deleting attendance...");
				$.ajax({
		        	dataType: 'native',
			        url: delete_user_attendance_ajax.ajaxurl,
			        type: 'POST',
			        data: {
			            'action': 'delete_user_attendance',
			            'user_id': user_id,
			            'course_id': course_id
			        },
			        success: function(response) {
			        	actions_response_field.text(response);
			        	if (response.includes('Error: ')) {
			        		ref.attr('checked', true);
			        	}
			        	ref.prop('disabled', false);
			        },
			        error: function(error) {
			            actions_response_field.text(response);
			            ref.attr('checked', true);
			            ref.prop('disabled', false);
			        }
			    });
			}

	    });

	    $('#upload-csv').click(function() {
	    	actions_response_field.text("Uploading csv");

	    	var file = $("#csv_file").prop('files')[0];
	    	var fr = new FileReader();

			var content;

	    	fr.onload = function(event) {
	    		content = event.target.result;

		        $.ajax({
		        	dataType: 'native',
			        url: upload_csv_ajax.ajaxurl,
			        type: 'POST',
			        data: {
			            'action': 'upload_csv',
			            'file' : content
			        },
			        success: function(response) {
			        	actions_response_field.text(response);

			        },
			        error: function(error) {
			            actions_response_field.text(error);

			        }
			    });
	    	}

		    fr.readAsText(file);
			
	    });

	    $('.unsubscribe_button').click(function() {
	    	var user_id = $(this).data("user_id");
			var course_id = getParameterByName('course_id');
	    	actions_response_field.text("");
	    	var ref = $(this);
	    	ref.prop('disabled', true);
	    	actions_response_field.text("Unsubscribing user...");
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
		        	ref.prop('disabled', false);
		        	if (!response.includes('Error: ')) {
		        		location.reload(true);
		        	}		        	
		        },
		        error: function(error) {
		            actions_response_field.text(error);
		            ref.prop('disabled', false);
		        }
		    });

	    });

	});

	function getParameterByName(name, url) {
	    if (!url) url = window.location.href;
	    name = name.replace(/[\[\]]/g, "\\$&");
	    var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
	        results = regex.exec(url);
	    if (!results) return null;
	    if (!results[2]) return '';
	    return decodeURIComponent(results[2].replace(/\+/g, " "));
	}

})( jQuery );
