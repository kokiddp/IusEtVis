<?php

/**
 * Main Menu page Registrations
 *
 * @link       https://github.com/kokiddp/IusEtVis
 * @since      1.0.0
 *
 * @package    Iusetvis
 * @subpackage Iusetvis/admin/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<?php
	if (!current_user_can('edit_pages'))  {
		wp_die( __('You do not have sufficient permissions to access this page.') );
	}
?>

<h1><?php _e( 'Demo', 'iusetvis' ) ?></h1>

<div class='wrap'>
	<label for="user-id"><?php _e( 'User ID', 'iusetvis' ) ?></label>
	<input type="number" name="user-id" id="user_id" value="1">
	<label for="course-id"><?php _e( 'Course ID', 'iusetvis' ) ?></label>
	<input type="number" name ="course-id" id="course_id" value="52">

	<h3 id="actions_response_field"></h3>

	<h2><?php _e( 'Perfect subscription', 'iusetvis' ) ?></h2>
	<p><input type='submit' style='margin-bottom: 20px;' class='button-primary' id='perfect-subscription' value='<?php _e( 'Perfect subscription', 'iusetvis' ) ?>'></p>

	<h2><?php _e( 'Unperfect subscription', 'iusetvis' ) ?></h2>
	<p><input type='submit' style='margin-bottom: 20px;' class='button-primary' id='unperfect-subscription' value='<?php _e( 'Unperfect subscription', 'iusetvis' ) ?>'></p>

	<h2><?php _e( 'Confirm attendance', 'iusetvis' ) ?></h2>
	<p><input type='submit' style='margin-bottom: 20px;' class='button-primary' id='confirm-attendance' value='<?php _e( 'Confirm attendance', 'iusetvis' ) ?>'></p>

	<h2><?php _e( 'Delete attendance', 'iusetvis' ) ?></h2>
	<p><input type='submit' style='margin-bottom: 20px;' class='button-primary' id='delete-attendance' value='<?php _e( 'Delete attendance', 'iusetvis' ) ?>'></p>
</div>