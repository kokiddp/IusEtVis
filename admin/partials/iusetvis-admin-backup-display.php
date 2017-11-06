<?php

/**
 * Main Menu page Backup
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
	<h2><?php _e( 'Database Backup', 'iusetvis' ) ?></h2>
	<p><?php _e( 'The menu allows to download a DB copy on your local computer.', 'iusetvis' ) ?><br /><a style='margin-top: 20px;' class='button-primary' href='#'><?php _e( 'Download', 'iusetvis' ) ?></a></p>
</div>