<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://github.com/kokiddp/IusEtVis
 * @since      1.0.0
 *
 * @package    Iusetvis
 * @subpackage Iusetvis/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Iusetvis
 * @subpackage Iusetvis/admin
 * @author     Gabriele Coquillard, Stefano Bitto <gabriele.coquillard@gmail.com>
 */
class Iusetvis_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Iusetvis_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Iusetvis_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/iusetvis-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Iusetvis_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Iusetvis_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/iusetvis-admin.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * Add an options page under the Settings submenu
	 *
	 * @since  1.0.0
	 */
	public function add_menu_pages() {
		
		// Main menu page Registrations
		add_menu_page(
			__( 'Courses', 'iusetvis' ),
			__( 'Registrations', 'iusetvis' ),
			'edit_pages',
			$this->plugin_name . '_registrations_page',
			array( $this, 'display_registrations_page' )
		);

		// Submenu page Upload
		add_submenu_page(
			NULL,
			__( 'Courses', 'iusetvis' ),
			__( 'Upload', 'iusetvis' ),
			'edit_pages',
			$this->plugin_name . '_upload_file',
			array( $this, 'upload_file' )
		);

		// Submenu page Email
	    add_submenu_page(
	    	NULL,
	    	__( 'Email', 'iusetvis' ),
	    	__( 'Email', 'iusetvis' ),
	    	'edit_pages',
	    	$this->plugin_name . '_course_mail',
	    	array( $this, 'course_mail' )
	    );

	    // Submenu page Log Email
		add_submenu_page(
			$this->plugin_name . "_registrations_page",
			__( 'Log Email', 'iusetvis' ),
			__( 'Log Email', 'iusetvis' ),
			'edit_pages',
			$this->plugin_name . '_log_email',
			array( $this, 'log_email' )
		);

		// Submenu page Force Email Dispatch
		add_submenu_page(
			$this->plugin_name . "_registrations_page",
			__( 'Force Email Dispatch', 'iusetvis' ),
			__( 'Force Email Dispatch', 'iusetvis' ),
			'edit_pages',
			$this->plugin_name . '_force_email_dispatch',
			array( $this, 'force_email_dispatch' )
		);

		// Main menu page Backup
		add_menu_page(
			__( 'Backup', 'iusetvis' ),
			__( 'Backup', 'iusetvis' ),
			'edit_pages',
			$this->plugin_name . '_backup_page',
			array( $this, 'display_backup_page' )
		);

	}

	/**
	 * Render the Registrations page for plugin
	 *
	 * @since  1.0.0
	 */
	public function display_registrations_page() {
		include_once 'partials/iusetvis-admin-registrations-display.php';
	}

	/**
	 * Render the Upload page for plugin
	 *
	 * @since  1.0.0
	 */
	public function upload_file() {
		include_once 'partials/iusetvis-admin-upload-display.php';
	}

	/**
	 * Render the Email page for plugin
	 *
	 * @since  1.0.0
	 */
	public function course_mail() {
		include_once 'partials/iusetvis-admin-course-mail-display.php';
	}

	/**
	 * Render the Log Email page for plugin
	 *
	 * @since  1.0.0
	 */
	public function log_email() {
		include_once 'partials/iusetvis-admin-log-email-display.php';
	}

	/**
	 * Render the Force Email Dispatch page for plugin
	 *
	 * @since  1.0.0
	 */
	public function force_email_dispatch() {
		include_once 'partials/iusetvis-admin-force-email-dispatch-display.php';
	}

	/**
	 * Render the Registrations page for plugin
	 *
	 * @since  1.0.0
	 */
	public function display_backup_page() {
		include_once 'partials/iusetvis-admin-backup-display.php';
	}

	/**
	 * Add the required Roles and Capabilities
	 *
	 * @since  1.0.0
	 */
	public function add_role_and_capabilities() {
		add_role(
			__( 'associate', 'iusetvis' ),
			__( 'Associate', 'iusetvis' ),
			array( __( 'associate', 'iusetvis' ) )
		);
		$role = & get_role( __( 'associate', 'iusetvis' ) );
		$role->add_cap('associate_rate');
		$role->add_cap( 'read_private_pages' );
	}

}
