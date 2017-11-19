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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/iusetvis-admin.css', array( ), $this->version, 'all' );
		

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

		wp_enqueue_script( 'jquery-ajax-native', plugin_dir_url( __FILE__ ) . 'js/jquery-ajax-native.js', array( 'jquery' ) );
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/iusetvis-admin.js', array( 'jquery', 'jquery-ajax-native' ), $this->version, false );
		wp_localize_script( $this->plugin_name, 'perfect_user_subscription_ajax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
		wp_localize_script( $this->plugin_name, 'unperfect_user_subscription_ajax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
		wp_localize_script( $this->plugin_name, 'confirm_user_attendance_ajax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
		wp_localize_script( $this->plugin_name, 'delete_user_attendance_ajax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );	

	}

	/**
	 * Add the shortcodes for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function add_shortcodes() {


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
			array( $this, 'display_registrations_page' ),
			'dashicons-list-view'
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
			array( $this, 'display_backup_page' ),
			'dashicons-backup'
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
		$role = get_role( __( 'associate', 'iusetvis' ) );
		$role->add_cap('associate_rate');
		$role->add_cap( 'read_private_pages' );
	}

	/**
	 * Perfect user subscription
	 *
	 * @since    1.0.0
	 */
	public function perfect_user_subscription( $_user_id = '0', $_course_id = '0' ) {
		
		// retrieve ajax parameters
		$user_id = ( isset( $_POST['user_id'] ) ? $_POST['user_id'] : $_user_id );
		$course_id = ( isset( $_POST['course_id'] ) ? $_POST['course_id'] : $_course_id );
		if ( $user_id == '0' || $course_id == '0' ) {
			echo __( 'Error: user or course unset!', 'iusetvis' );
			die();
		}

		$course_meta = get_post_custom( $course_id );
		$user_meta = get_user_meta( $user_id );
		$subscribed_users = !isset( $course_meta['subscribed_users'][0] ) ? array() : maybe_unserialize( $course_meta['subscribed_users'][0] );
		$perfected_subscriptions = !isset( $user_meta['perfected_subscriptions'][0] ) ? array() : maybe_unserialize( $user_meta['perfected_subscriptions'][0] );

		// if the user is subscribed to the course
		if ( in_array( $user_id, $subscribed_users ) ) {
			
			// if the user hans't perfected his subscription to the course
		 	if ( !in_array( $course_id, $perfected_subscriptions ) ) {
		 		
		 		array_push( $perfected_subscriptions, $course_id );		
				update_user_meta( $user_id, 'perfected_subscriptions', $perfected_subscriptions );			
				echo __( 'User registration to this course succesfully perfected.', 'iusetvis' );
				wp_clear_scheduled_hook( 'action_unsubscribe_cron', array( $user_id, $course_id ) );
				die();		 		

			}
			else {
				echo __( 'Error: the user registration to this course has already been perfected.', 'iusetvis' );
				die();
			}	

		}
		else {
			echo __( 'Error: the user is not subscribed to this course.', 'iusetvis' );
			die();
		}

	}

	/**
	 * Unperfect user subscription
	 *
	 * @since    1.0.0
	 */
	public function unperfect_user_subscription( $_user_id = '0', $_course_id = '0' ) {
		
		// retrieve ajax parameters
		$user_id = ( isset( $_POST['user_id'] ) ? $_POST['user_id'] : $_user_id );
		$course_id = ( isset( $_POST['course_id'] ) ? $_POST['course_id'] : $_course_id );
		if ( $user_id == '0' || $course_id == '0' ) {
			echo __( 'Error: user or course unset!', 'iusetvis' );
			die();
		}

		$plugin_public = new Iusetvis_Public( $this->plugin_name, $this->plugin_version );

		$course_meta = get_post_custom( $course_id );
		$user_meta = get_user_meta( $user_id );
		$subscribed_users = !isset( $course_meta['subscribed_users'][0] ) ? array() : maybe_unserialize( $course_meta['subscribed_users'][0] );
		$perfected_subscriptions = !isset( $user_meta['perfected_subscriptions'][0] ) ? array() : maybe_unserialize( $user_meta['perfected_subscriptions'][0] );

		// if the user is subscribed to the course
		if ( in_array( $user_id, $subscribed_users ) ) {
			
			// if the user han perfected his subscription to the course
		 	if ( in_array( $course_id, $perfected_subscriptions ) ) {

				$key = array_search( $course_id, $perfected_subscriptions );
				array_splice( $perfected_subscriptions, $key, 1 );
				update_user_meta( $user_id, 'perfected_subscriptions', $perfected_subscriptions );			
				echo __( 'User registration to this course succesfully unperfected.', 'iusetvis' );
				$plugin_public->start_unsubscribe_cron( $user_id, $course_id );
				die();		 		

			}
			else {
				echo __( 'Error: the user registration to this course has not been perfected yet.', 'iusetvis' );
				die();
			}	

		}
		else {
			echo __( 'Error: the user is not subscribed to this course.', 'iusetvis' );
			die();
		}

	}

	/**
	 * Confirm user's course attendance
	 *
	 * @since    1.0.0
	 */
	public function confirm_user_attendance( $_user_id = '0', $_course_id = '0' ) {
		
		// retrieve ajax parameters
		$user_id = ( isset( $_POST['user_id'] ) ? $_POST['user_id'] : $_user_id );
		$course_id = ( isset( $_POST['course_id'] ) ? $_POST['course_id'] : $_course_id );
		if ( $user_id == '0' || $course_id == '0' ) {
			echo __( 'Error: user or course unset!', 'iusetvis' );
			die();
		}

		$course_meta = get_post_custom( $course_id );
		$user_meta = get_user_meta( $user_id );
		$subscribed_users = !isset( $course_meta['subscribed_users'][0] ) ? array() : maybe_unserialize( $course_meta['subscribed_users'][0] );
		$perfected_subscriptions = !isset( $user_meta['perfected_subscriptions'][0] ) ? array() : maybe_unserialize( $user_meta['perfected_subscriptions'][0] );
		$confirmed_attendances = !isset( $user_meta['confirmed_attendances'][0] ) ? array() : maybe_unserialize( $user_meta['confirmed_attendances'][0] );

		// if the user is subscribed to the course
		if ( in_array( $user_id, $subscribed_users ) ) {
			
			// if the user has perfected his subscription to the course
		 	if ( in_array( $course_id, $perfected_subscriptions ) ) {

		 		// if the admin hasn't confirmed user attendance to this course
		 		if ( !in_array( $course_id, $confirmed_attendances ) ) {
		 		
			 		array_push( $confirmed_attendances, $course_id );		
					update_user_meta( $user_id, 'confirmed_attendances', $confirmed_attendances );		
					echo __( 'User attendance to this course succesfully confirmed.', 'iusetvis' );
					wp_mail( 'stebitto@gmail.com', 'Iusetvis', 'Partecipazione al corso '.get_the_title( $course_id ).' confermata' );
					die();	

				}
				else {
					echo __( 'Error: the user attendance to this course has already been confirmed.', 'iusetvis' );
					die();
				}	

			}
			else {
				echo __( 'Error: the user registration to this course has not been perfected yet.', 'iusetvis' );
				die();
			}

		}
		else {
			echo __( 'Error: the user is not subscribed to this course.', 'iusetvis' );
			die();
		}

	}

	/**
	 * Delete user's course attendance
	 *
	 * @since    1.0.0
	 */
	public function delete_user_attendance( $_user_id = '0', $_course_id = '0' ) {
		
		// retrieve ajax parameters
		$user_id = ( isset( $_POST['user_id'] ) ? $_POST['user_id'] : $_user_id );
		$course_id = ( isset( $_POST['course_id'] ) ? $_POST['course_id'] : $_course_id );
		if ( $user_id == '0' || $course_id == '0' ) {
			echo __( 'Error: user or course unset!', 'iusetvis' );
			die();
		}

		$course_meta = get_post_custom( $course_id );
		$user_meta = get_user_meta( $user_id );
		$subscribed_users = !isset( $course_meta['subscribed_users'][0] ) ? array() : maybe_unserialize( $course_meta['subscribed_users'][0] );
		$perfected_subscriptions = !isset( $user_meta['perfected_subscriptions'][0] ) ? array() : maybe_unserialize( $user_meta['perfected_subscriptions'][0] );
		$confirmed_attendances = !isset( $user_meta['confirmed_attendances'][0] ) ? array() : maybe_unserialize( $user_meta['confirmed_attendances'][0] );

		// if the user is subscribed to the course
		if ( in_array( $user_id, $subscribed_users ) ) {
			
			// if the user has perfected his subscription to the course
		 	if ( in_array( $course_id, $perfected_subscriptions ) ) {

		 		// if the admin has confirmed user attendance to this course
		 		if ( in_array( $course_id, $confirmed_attendances ) ) {
		 			
			 		$key = array_search( $course_id, $confirmed_attendances );
					array_splice( $confirmed_attendances, $key, 1 );		
					update_user_meta( $user_id, 'confirmed_attendances', $confirmed_attendances );			
					echo __( 'User attendance to this course succesfully deleted.', 'iusetvis' );					
					die();	

				}
				else {
					echo __( 'Error: the user attendance to this course has not been confirmed yet.', 'iusetvis' );
					die();
				}

			}
			else {
				echo __( 'Error: the user registration to this course has not been perfected yet.', 'iusetvis' );
				die();
			}

		}
		else {
			echo __( 'Error: the user is not subscribed to this course.', 'iusetvis' );
			die();
		}

	}

}
