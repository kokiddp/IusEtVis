<?php

/**
 * Submenu page Force Email Dispatch
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
	<?php screen_icon('tools');	?>
	<h2><?php _e( 'Automated Email Dispatch', 'iusetvis' ) ?></h2>
	<p><?php _e( 'The menu allows to force the programmed dispatch of queued emails, such as accreditation notifications etc...', 'iusetvis' ) ?><br /><a style='margin-top: 20px;' class='button-primary' href='#'><?php _e( 'Send Emails', 'iusetvis' ) ?></a></p>
</div>