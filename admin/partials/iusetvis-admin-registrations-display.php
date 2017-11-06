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