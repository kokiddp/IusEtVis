<?php

/**
 * Submenu page Upload
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
	<h1><?php echo get_the_title($_GET['course_id']) ?></h1>

	<h3 id="actions_response_field"></h3>

	<h2><?php _e( 'Upload CSV of credits', 'iusetvis' ) ?></h2>
	<p>
		<input type="file" name="csv" accept=".csv" id="csv_file">
		<input type='submit' style='margin-bottom: 20px;' class='button-primary' id='upload-csv' value='<?php _e( 'Upload CSV', 'iusetvis' ) ?>'>
	</p>
	<img src="<?= str_replace( 'partials/', '', plugin_dir_url( __FILE__ ) ) ?>img/csv.jpg">
</div>
