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

<div class='wrap'>
	<h2><?php _e( 'Perfect subscription', 'iusetvis' ) ?></h2>
	<p><input type='submit' style='margin-top: 20px;' class='button-primary' id='perfect-subscription' value='<?php _e( 'Perfect subscription', 'iusetvis' ) ?>'></p>
</div>

<div class='wrap'>
	<h2><?php _e( 'Unperfect subscription', 'iusetvis' ) ?></h2>
	<p><input type='submit' style='margin-top: 20px;' class='button-primary' id='unperfect-subscription' value='<?php _e( 'Unperfect subscription', 'iusetvis' ) ?>'></p>
</div>

<div class='wrap'>
	<h2><?php _e( 'Confirm attendance', 'iusetvis' ) ?></h2>
	<p><input type='submit' style='margin-top: 20px;' class='button-primary' id='confirm-attendance' value='<?php _e( 'Confirm attendance', 'iusetvis' ) ?>'></p>
</div>

<div class='wrap'>
	<h2><?php _e( 'Delete attendance', 'iusetvis' ) ?></h2>
	<p><input type='submit' style='margin-top: 20px;' class='button-primary' id='delete-attendance' value='<?php _e( 'Delete attendance', 'iusetvis' ) ?>'></p>
</div>

<h3 id="actions_response_field"></h3>