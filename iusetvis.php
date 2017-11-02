<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://www.google.it
 * @since             1.0.0
 * @package           Iusetvis
 *
 * @wordpress-plugin
 * Plugin Name:       IusEtVis
 * Plugin URI:        http://www.iusetvis.it
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            Gabriele Coquillard, Stefano Bitto
 * Author URI:        http://www.google.it
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       iusetvis
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'PLUGIN_NAME_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-iusetvis-activator.php
 */
function activate_iusetvis() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-iusetvis-activator.php';
	Iusetvis_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-iusetvis-deactivator.php
 */
function deactivate_iusetvis() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-iusetvis-deactivator.php';
	Iusetvis_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_iusetvis' );
register_deactivation_hook( __FILE__, 'deactivate_iusetvis' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-iusetvis.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_iusetvis() {

	$plugin = new Iusetvis();
	$plugin->run();

}
run_iusetvis();
