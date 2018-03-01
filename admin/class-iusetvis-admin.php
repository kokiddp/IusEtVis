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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/iusetvis-admin.css', array( ), time()/*$this->version*/, 'all' );
		//BERSI
		wp_enqueue_style( 'datatables', plugin_dir_url( __FILE__ ) . 'js/DataTables/datatables.min.css', array( ), time()/*$this->version*/, 'all' );


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
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/iusetvis-admin.js', array( 'jquery', 'jquery-ajax-native' ), time()/*$this->version*/, false );
		wp_localize_script( $this->plugin_name, 'perfect_user_subscription_ajax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
		wp_localize_script( $this->plugin_name, 'unperfect_user_subscription_ajax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
		wp_localize_script( $this->plugin_name, 'confirm_user_attendance_ajax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
		wp_localize_script( $this->plugin_name, 'delete_user_attendance_ajax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
		wp_localize_script( $this->plugin_name, 'upload_csv_ajax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );

		wp_localize_script( $this->plugin_name, 'pdf_print_diploma_ajax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
		wp_localize_script( $this->plugin_name, 'course_subscribe_ajax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
		wp_localize_script( $this->plugin_name, 'course_unsubscribe_ajax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
		wp_localize_script( $this->plugin_name, 'course_waiting_list_subscribe_ajax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
		//Bersi
		//chiusura corsi
		wp_localize_script( $this->plugin_name, 'course_ended_ajax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
		wp_enqueue_script( 'datatables', plugin_dir_url( __FILE__ ) . 'js/DataTables/datatables.min.js' );

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
			__( 'IusEtVis', 'iusetvis' ),
			__( 'IusEtVis', 'iusetvis' ),
			'edit_pages',
			$this->plugin_name . '_registrations_page',
			array( $this, 'display_registrations_page' ),
			'dashicons-welcome-learn-more'
		);


		// Submenu page general settings
		add_submenu_page(
			$this->plugin_name . "_registrations_page",
			__( 'General Settings', 'iusetvis' ),
			__( 'General Settings', 'iusetvis' ),
			'edit_pages',
			'iusetvis_options_page',
			array( $this, 'render_options_page' )
		);


		// Submenu page Upload
		add_submenu_page(
			NULL,
			__( 'Upload', 'iusetvis' ),
			__( 'Upload', 'iusetvis' ),
			'edit_pages',
			$this->plugin_name . '_upload_file',
			array( $this, 'upload_file' )
		);

		// Subscribed users list table
		add_submenu_page(
			NULL,
			__( 'Course Subscribed Users List Table', 'iusetvis' ),
			__( 'Course Subscribed Users List Table', 'iusetvis' ),
			'edit_pages',
			$this->plugin_name . '_course_subscribed_list_table',
			array( $this, 'course_subscribed_list_table' )
		);

		// Scarica CSV iscritti
		/*add_submenu_page(
			NULL,
			__( 'Download Subscribed Users List Table', 'iusetvis' ),
			__( 'Download Subscribed Users List Table', 'iusetvis' ),
			'edit_pages',
			$this->plugin_name . '_csv_file',
			array( $this, 'csv_subscribed_file' )
		);
		*/


		// Waiting users list table
		add_submenu_page(
			NULL,
			__( 'Course Waiting Users List Table', 'iusetvis' ),
			__( 'Course Waiting Users List Table', 'iusetvis' ),
			'edit_pages',
			$this->plugin_name . '_course_waiting_list_table',
			array( $this, 'course_waiting_list_table' )
		);

		/*

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
		*/
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
	 * Init the settings fields for the settings admin area.
	 *
	 * @since    1.0.0
	 */
	public function init_settings(  ) {

		register_setting( 'iusetvis_options_page', 'iusetvis_settings' );

		add_settings_section(
			'iusetvis_options_page_section_general',
			__( 'General Settings', 'iusetvis' ),
			array( $this, 'iusetvis_options_page_section_general_callback' ),
			'iusetvis_options_page'
		);

		add_settings_field(
			'iusetvis_president',
			__( 'President name', 'iusetvis' ),
			array( $this, 'iusetvis_president_render' ),
			'iusetvis_options_page',
			'iusetvis_options_page_section_general'
		);

		add_settings_field(
			'iusetvis_signature',
			__( 'President\'s signature', 'iusetvis' ),
			array( $this, 'iusetvis_signature_render' ),
			'iusetvis_options_page',
			'iusetvis_options_page_section_general'
		);

		add_settings_field(
			'iusetvis_logo',
			__( 'Association Logo', 'iusetvis' ),
			array( $this, 'iusetvis_logo_render' ),
			'iusetvis_options_page',
			'iusetvis_options_page_section_general'
		);

		add_settings_field(
			'iusetvis_header',
			__( 'Header text', 'iusetvis' ),
			array( $this, 'iusetvis_header_render' ),
			'iusetvis_options_page',
			'iusetvis_options_page_section_general'
		);

		// Bersi
		add_settings_field(
			'iusetvis_email_course_ended',
			__( 'Email for closed course', 'iusetvis' ),
			array( $this, 'iusetvis_email_course_ended_render' ),
			'iusetvis_options_page',
			'iusetvis_options_page_section_general'
		);

	}

	/**
	 * template per mail fine corso
	 * @return boolean [description]
	 */
	function iusetvis_email_course_ended_render( ){
		$options = get_option( $this->plugin_name . '_settings' );
		$iusetvis_email_course_ended = ! isset( $options['iusetvis_email_course_ended'] ) ? '' : $options['iusetvis_email_course_ended'];

		?>

		<textarea style="clear:top;width:100%" name="iusetvis_settings[iusetvis_email_course_ended]" ><?= $iusetvis_email_course_ended ?></textarea>
		<p>
			<?php _e("Email text for course closed",'iusetvis');?>
		</p>
		<?php
	}

	/**
	 * Add the description for the general section of the settings area.
	 *
	 * @since    1.0.0
	 */
	function iusetvis_options_page_section_general_callback(  ) {

		?>

		<p><?php echo __( 'Theese are the general settings for IusEtVis.', 'iusetvis' ); ?></p>

		<?php

	}

	/**
	 * Add the President text area for the settings page.
	 *
	 * @since    1.0.0
	 */
	function iusetvis_president_render(  ) {

		$options = get_option( $this->plugin_name . '_settings' );
		$iusetvis_president = ! isset( $options['iusetvis_president'] ) ? '' : $options['iusetvis_president'];

		?>

		<input type="text" name="iusetvis_settings[iusetvis_president]" value="<?= $iusetvis_president ?>">

		<?php

	}

	/**
	 * Add the Signature media uploader for the settings page.
	 *
	 * @since    1.0.0
	 */
	function iusetvis_signature_render(  ) {

		$options = get_option( $this->plugin_name . '_settings' );
		$iusetvis_signature = ! isset( $options['iusetvis_signature'] ) ? '' : $options['iusetvis_signature'];
		$iusetvis_signature_src = $iusetvis_signature == '' ? '' : wp_get_attachment_url( $iusetvis_signature );

		wp_enqueue_media();

		?>

		<div class="image-preview-wrapper">
			<img id="signature-preview" src="<?= $iusetvis_signature_src ?>" style="height: 100px; max-width: 500px;">
		</div>
		<input id="upload_signature_button" type="button" class="button" value="<?php _e( 'Upload image', 'iusetvis' ); ?>" />
		<input type="hidden" name="iusetvis_settings[iusetvis_signature]" id="signature_attachment_id" value="<?= $iusetvis_signature ?>"">

		<?php

	}

	/**
	 * Add the Logo media uploader for the settings page.
	 *
	 * @since    1.0.0
	 */
	function iusetvis_logo_render(  ) {

		$options = get_option( $this->plugin_name . '_settings' );
		$iusetvis_logo = ! isset( $options['iusetvis_logo'] ) ? '' : $options['iusetvis_logo'];
		$iusetvis_logo_src = $iusetvis_logo == '' ? '' : wp_get_attachment_url( $iusetvis_logo );

		wp_enqueue_media();

		?>

		<div class="image-preview-wrapper">
			<img id="logo-preview" src="<?= $iusetvis_logo_src ?>" style="height: 200px; max-width: 500px;">
		</div>
		<input id="upload_logo_button" type="button" class="button" value="<?php _e( 'Upload image', 'iusetvis' ); ?>" />
		<input type="hidden" name="iusetvis_settings[iusetvis_logo]" id="logo_attachment_id" value="<?= $iusetvis_logo ?>"">

		<?php

	}

	/**
	 * Add the Header text area for the settings page.
	 *
	 * @since    1.0.0
	 */
	function iusetvis_header_render(  ) {

		$options = get_option( $this->plugin_name . '_settings' );
		$iusetvis_header = ! isset( $options['iusetvis_header'] ) ? '' : $options['iusetvis_header'];

		?>

		<textarea name="iusetvis_settings[iusetvis_header]"><?= $iusetvis_header ?></textarea>

		<?php

	}

	/**
	 * Render the settings area.
	 *
	 * @since    1.0.0
	 */
	function render_options_page(  ) {

		?>
		<form action='options.php' method='post'>

			<h2><?php _e( 'IusEtVis Settings', 'iusetvis' ) ?></h2>

			<?php
			settings_fields( $this->plugin_name . '_options_page' );
			do_settings_sections( $this->plugin_name . '_options_page' );
			submit_button();
			?>

		</form>
		<?php

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

		$perfected_users = !isset( $course_meta['perfected_users'][0] ) ? array() : maybe_unserialize( $course_meta['perfected_users'][0] );

		$course_price = ! isset( $course_meta['course_price_reg'][0] ) ? '0' : $course_meta['course_price_reg'][0];
		if ( isset( $user_meta['association_state'][0] ) &&
			isset( $user_meta['association_end'][0] ) &&
			isset( $course_meta['course_start_time'][0] ) &&
			$user_meta['association_state'][0] == 1  &&
			$user_meta['association_end'][0] >= $course_meta['course_start_time'][0] ) {
			$course_price = ! isset( $course_meta['course_price_assoc'][0] ) ? $course_price : $course_meta['course_price_assoc'][0];
		}

		// if the user is subscribed to the course
		if ( in_array( $user_id, $subscribed_users ) ) {

			// if the user hans't perfected his subscription to the course
		 	if ( !in_array( $course_id, $perfected_subscriptions ) ) {

		 		array_push( $perfected_subscriptions, $course_id );
				array_push( $perfected_users, array($user_id, $course_price) );
				update_user_meta( $user_id, 'perfected_subscriptions', $perfected_subscriptions );
				update_post_meta( $course_id, 'perfected_users', $perfected_users );
				echo __( 'User registration to this course succesfully perfected.', 'iusetvis' );
				wp_clear_scheduled_hook( 'action_unsubscribe_cron', array( $user_id, $course_id ) );
				//mail
				$user_info = get_userdata($user_id);
				wp_mail( $user_info->user_email, 'Iusetvis', 'Iscrizione al corso '.get_the_title( $course_id ).' confermata' );
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
		$course_end_time = ! isset( $course_meta['course_end_time'][0] ) ? '' : $course_meta['course_end_time'][0];

		// if the user is subscribed to the course
		if ( in_array( $user_id, $subscribed_users ) ) {

			// if the user has perfected his subscription to the course
		 	if ( in_array( $course_id, $perfected_subscriptions ) ) {

			 	// if the course is finished
			 	if ( $course_end_time <= time() ) {

			 		// if the admin hasn't confirmed user attendance to this course
			 		if ( !in_array( $course_id, $confirmed_attendances ) ) {

				 		array_push( $confirmed_attendances, $course_id );
						update_user_meta( $user_id, 'confirmed_attendances', $confirmed_attendances );
						echo __( 'User attendance to this course succesfully confirmed.', 'iusetvis' );
						//mail
						$user_info = get_userdata($user_id);
						wp_mail( $user_info->user_email, 'Iusetvis', 'Partecipazione al corso '.get_the_title( $course_id ).' confermata' );
						die();

					}
					else {
						echo __( 'Error: the user attendance to this course has already been confirmed.', 'iusetvis' );
						die();
					}
				}
				else {
					echo __( 'Error: the course is not finished yet.', 'iusetvis' );
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
	 * AJAX
	 * chiude un corso impostando il meta relativo
	 * @return [string] [description]
	 */
	public function course_ended($_course_id = '0'){
		$course_id = ( isset( $_POST['course_id'] ) ? $_POST['course_id'] : $_course_id );
		if ( $course_id == '0' ) {
			echo __( 'Error: course unset!', 'iusetvis' );
			die();
		}

		update_post_meta( $course_id, 'course_ended', 1 );
		update_post_meta( $course_id, 'course_ended_at', time() );

		//carico utilità
		$util = new Ius_Et_Vis_Util;
		//invio email di chiusura
		$util->send_email_subscribed( $course_id );

		echo __( 'The course is now closed!', 'iusetvis' );
		die();
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

	/**
	 * Set custom course Subscriptions column
	 *
	 * @since    1.0.0
	 */
	public function set_custom_edit_course_columns($columns) {

		$columns['start_date'] = __( 'Start', 'iusetvis' );
		$columns['subs_end_date'] = __( 'Subscriptions end', 'iusetvis' );
	  $columns['subscriptions'] = __( 'Subscriptions', 'iusetvis' );
	  $columns['waiting'] = __( 'Waiting', 'iusetvis' );
		//$columns['closed'] = __( 'Close', 'iusetvis' );

	  return $columns;
	}

	/**
	 * rende sortable le colonne dei corsi
	 * @param [type] $columns [description]
	 */
	public function set_custom_course_sortable_columns($columns){
		$columns['start_date'] = __( 'Start', 'iusetvis' );
		$columns['subscriptions'] = __( 'Subscriptions', 'iusetvis' );
		$columns['subs_end_date'] = __( 'Subscriptions end', 'iusetvis' );
		return $columns;
	}

	/**
	 * Snippet Name: Admin filter for custom taxonomies
	 * Snippet URL: http://www.wpcustoms.net/snippets/admin-filter-for-custom-taxonomies/
	 */
	 // add filtering option
	public function custom_course_add_taxonomy_filters() {
	    global $typenow;
	    // an array of all the taxonomies you want to display. Use the taxonomy name or slug - each item gets its own select box.
	    $taxonomies = array('course-category');
	    // use the custom post type here
	    if( $typenow == 'course' ){
	        foreach ($taxonomies as $tax_slug) {
	            $tax_obj = get_taxonomy($tax_slug);
	            $tax_name = $tax_obj->labels->name;
	            $terms = get_terms($tax_slug);
	            if(count($terms) > 0) {
	                echo "<select name=".$tax_slug." id=".$tax_slug." class=\"postform\">";
	                echo "<option value=''>". __("Show All Course Categories",'iusetvis'). "</option>";
	                foreach ($terms as $term) {
	                echo '<option value="'. $term->slug.'"';
							    if($_GET[$tax_slug] == $term->slug ) echo  ' selected="selected"';
									echo ">". $term->name .' (' . $term->count .')</option>'; }
	                echo "</select>";
	            }
	        }
	    }
	}

	/**
	 * Ordina i course sulla base della colonna cliccata
	 * @param  [type] $query [description]
	 * @return [type]        [description]
	 */
	public function custom_course_sort($query){
		if ( ! is_admin() )  return;

		$orderby = $query->get( 'orderby');
		//ordina per campo
	  if ( 'start_date' == $orderby ) {
	    $query->set( 'meta_key', 'start_date' );
	    $query->set( 'orderby', 'meta_value_num' );
	  }

		if ( 'subscriptions' == $orderby ) {
	    $query->set( 'meta_key', 'subscriptions' );
	    $query->set( 'orderby', 'meta_value_num' );
	  }

		if ( 'subs_end_date' == $orderby ) {
	    $query->set( 'meta_key', 'subs_end_date' );
	    $query->set( 'orderby', 'meta_value_num' );
	  }
	}
	/**
	 * rimuove wordpress da admin bar
	 * @param  [type] $wp_admin_bar [description]
	 * @return [type]               [description]
	 */
	public function remove_wp_logo( $wp_admin_bar ) {
		$wp_admin_bar->remove_node( 'wp-logo' );
	}

	/**
	 * elimina modifica dalle bulk actions
	 * @param  [type] $actions [description]
	 * @return [type]          [description]
	 */
	public function course_bulk_actions( $actions ){
        unset( $actions[ 'edit' ] );
        return $actions;
  }
	/**
	 * Set custom course Subscriptions column content
	 *
	 * @since    1.0.0
	 */
	public function custom_course_column( $column, $post_id ) {

		$course_meta = get_post_custom( $post_id );
		$date_format = get_option( 'date_format' );
		$time_format = get_option( 'time_format' );
		$datetime_format = $date_format . ' - ' . $time_format;

	    switch ( $column ) {

	      case 'start_date' :
							$course_start_time = ! isset( $course_meta['course_start_time'][0] ) ? 0 : $course_meta['course_start_time'][0];
	            if ( isset( $course_start_time ) && $course_start_time > 0 ){
									if((int)$course_meta['course_ended'][0]){
										echo "<span style='color:red'>";
									}
	                echo date( $datetime_format, $course_start_time );
									if((int)$course_meta['course_ended'][0]){
										echo "</span>";
									}
	            } else
	                _e( 'Unable to get the start date', 'iusetvis' );
	            break;

	        case 'subs_end_date' :
							$course_subs_dead_end = ! isset( $course_meta['course_subs_dead_end'][0] ) ? 0 : $course_meta['course_subs_dead_end'][0];
	            if ( isset( $course_subs_dead_end ) && $course_subs_dead_end > 0 ){
								if((int)$course_meta['course_ended'][0]){
									echo "<span style='color:red'>";
								}
	              echo date( $datetime_format, $course_subs_dead_end );
								if((int)$course_meta['course_ended'][0]){
									echo "</span>";
								}
	            } else
	                _e( 'Unable to get the subscriptions dead end date', 'iusetvis' );
	            break;

	        case 'subscriptions' :
	            $subscribed_users = !isset( $course_meta['subscribed_users'][0] ) ? array() : maybe_unserialize( $course_meta['subscribed_users'][0] );
	            if ( isset( $subscribed_users ) && count( $subscribed_users ) >= 0 )
	                echo "<a href='" . site_url('/wp-admin/admin.php?page=iusetvis_course_subscribed_list_table&course_id=') . $post_id . "'>" . count( $subscribed_users ) . "  su " . $course_meta['course_places'][0]. "</a>";
	            else
	                _e( 'Unable to get the suscribers', 'iusetvis' );
	            break;


	        case 'waiting' :
	            $waiting_users = !isset( $course_meta['waiting_users'][0] ) ? array() : maybe_unserialize( $course_meta['waiting_users'][0] );
	            if ( isset( $waiting_users ) && count( $waiting_users ) >= 0 )
	                echo "<a href='" . site_url('/wp-admin/admin.php?page=iusetvis_course_waiting_list_table&course_id=') . $post_id . "'>" . count( $waiting_users ) . "</a>";
	            else
	                _e( 'Unable to get the waiting users', 'iusetvis' );
	            break;

					/*case 'closed' :
									$closed = !isset( $meta['course_ended'][0] ) ? FALSE : maybe_unserialize( $course_meta['course_ended'][0] );
									if ( isset( $closed ) && ( $closed )  )
											echo "SI";
									else
											echo "NO";
									break;
									*/
	    }
	}

	/**
	 * Setup and display course list table
	 *
	 * @since    1.0.0
	 */
	public function course_subscribed_list_table( $_course_id = '0' ) {

		if(!empty($_GET['course_id']))
        {
            $course_id = $_GET['course_id'];
        }

		$table = new Subscribed_Users_List_Table();
        $table->prepare_items();
        ?>
            <div class="wrap">
                <div id="icon-users" class="icon32"></div>
                <h1><a href="post.php?post=<?=$course_id?>&action=edit"><?php echo strtoupper(get_the_title($course_id))?></a>: <?php _e('Subscribed Users List Table Page', 'iusetvis')?></h1>
                <h3 id="actions_response_field"></h3>
                <a class='button' href="./admin.php?page=iusetvis_upload_file&course_id=<?php echo $course_id ?>" class="btn btn-default">Accreditamento CSV</a>
								<a class='button' href="<?php echo admin_url( 'admin.php?page=iusetvis_options_page' ) ?>&action=csv_subscribed_file&amp;course_id=<?php echo $course_id ?>&amp;_wpnonce=<?php echo wp_create_nonce( 'download_csv' )?>"><?php _e("Download CSV of the users for the course",'iusetvis')?></a>
								<?php $table->display(); ?>
            </div>
        <?php
	}


	/**
	 * Scarica un csv degli iscritti
	 *
	 * @since    1.0.0
	 */
	public function csv_subscribed_file() {
		// Check for current user privileges
	 if( !current_user_can( 'manage_options' ) ){ return false; }
	 // Check if we are in WP-Admin
	 if( !is_admin() ){ return false; }
	 // Nonce Check
	 $nonce = isset( $_GET['_wpnonce'] ) ? $_GET['_wpnonce'] : '';
	 if ( ! wp_verify_nonce( $nonce, 'download_csv' ) ) {
			 die( 'Security check error' );
	 }

		$util = new Ius_Et_Vis_Util;
		if(!empty($_GET['course_id']))
				{
						$course_id = $_GET['course_id'];
				}
    		$filename = 'iscritti-' . get_the_title($course_id) . '-' . time() . '.csv';

				$course_meta = get_post_custom( $course_id );
				$subscribed_users = !isset( $course_meta['subscribed_users'][0] ) ? array() : maybe_unserialize( $course_meta['subscribed_users'][0] );

				$data_rows = array();
				$header_row = array(
						 'Utente',
						 'Indirizzo',
						 'Email',
						 'Telefono',
						 'Associato',
						 'Iscrizione perfezionata',
						 'Iscrizione confermata'
				);
				$i=1;
				foreach ( $subscribed_users as $key => $value ) {
						$user_id = $value;

						$user_meta = get_user_meta( $user_id );
						$user_data = get_userdata( $user_id );

						$perfected_subscriptions = !isset( $user_meta['perfected_subscriptions'][0] ) ? array() : maybe_unserialize( $user_meta['perfected_subscriptions'][0] );
						$confirmed_attendances = !isset( $user_meta['confirmed_attendances'][0] ) ? array() : maybe_unserialize( $user_meta['confirmed_attendances'][0] );

						$user_first_name = !isset( $user_meta['first_name'][0] ) ? '' : $user_meta['first_name'][0];
						$user_last_name = !isset( $user_meta['last_name'][0] ) ? '' : $user_meta['last_name'][0];
						$user_name = $user_last_name . ' ' . $user_first_name;
						$user_address = !isset( $user_meta['address'][0] ) ? '' : $user_meta['address'][0];
						$user_email = $user_data->user_email;
						$user_phone = !isset( $user_meta['phone'][0] ) ? '' : $user_meta['phone'][0];
						$user_association_state = !isset( $user_meta['association_state'][0] ) ? false : ( $user_meta['association_state'][0] == 0 ? false : true );
						$user_sub_perfected = in_array( $course_id, $perfected_subscriptions );
						$user_att_confirmed = in_array( $course_id, $confirmed_attendances );

						$row = array(
							$i,
							$user_name,
							$user_address,
							$user_email,
							$user_phone,
							$user_association_state,
							$user_sub_perfected,
							$user_att_confirmed
		        );
						$i++;
		        $data_rows[] = $row;

				}
				//sparo l'utilità che fa CSV
				$util->export_csv($header_row,$data_rows,$filename);
	}

	/**
	 * Setup and display course list table
	 *
	 * @since    1.0.0
	 */
	public function course_waiting_list_table( $_course_id = '0' ) {

		if(!empty($_GET['course_id']))
        {
            $course_id = $_GET['course_id'];
        }

		$table = new Waiting_Users_List_Table();
        $table->prepare_items();
        ?>
            <div class="wrap">
                <div id="icon-users" class="icon32"></div>
                <h1><?php _e('Waiting Users List Table Page', 'iusetvis')?></h1>
                <h3 id="actions_response_field"></h3>
                <?php $table->display(); ?>
            </div>
        <?php
	}

	/**
	 * Upload CSV
	 *
	 * @since    1.0.0
	 */
	public function upload_csv( $_file, $_course_id = '0' ) {

		$file = ( isset( $_POST['file'] ) ? $_POST['file'] : $_file );
		$course_id = ( isset( $_POST['course_id'] ) ? $_POST['course_id'] : $_course_id );

		$strings = preg_split('/\s+/', $file);
		array_pop($strings);

		foreach ($strings as $string) {
			$parts = explode(',', $string);
			$user = get_user_by( 'email', $parts[4] );
			//echo 'User is ' . $user->id . ' '. $user->first_name . ' ' . $user->last_name;

			$course_meta = get_post_custom( $course_id );
			$user_meta = get_user_meta( $user->id );
			$subscribed_users = !isset( $course_meta['subscribed_users'][0] ) ? array() : maybe_unserialize( $course_meta['subscribed_users'][0] );
			$perfected_subscriptions = !isset( $user_meta['perfected_subscriptions'][0] ) ? array() : maybe_unserialize( $user_meta['perfected_subscriptions'][0] );
			$confirmed_attendances = !isset( $user_meta['confirmed_attendances'][0] ) ? array() : maybe_unserialize( $user_meta['confirmed_attendances'][0] );


			if($parts[2] == '1'){
				//perfect_user_subscription
				array_push( $perfected_subscriptions, $course_id );
				update_user_meta( $user->id, 'perfected_subscriptions', $perfected_subscriptions );
				echo __( 'User ' . $user->first_name . ' ' . $user->last_name . ' registration to this course succesfully perfected.', 'iusetvis' );
			}
			else{
				//unperfect_user_subscription
				$key = array_search( $course_id, $perfected_subscriptions );
				array_splice( $perfected_subscriptions, $key, 1 );
				update_user_meta( $user->id, 'perfected_subscriptions', $perfected_subscriptions );
				echo __( 'User ' . $user->first_name . ' ' . $user->last_name . ' registration to this course succesfully unperfected.', 'iusetvis' );
			}

			if($parts[5] == 'SI'){
				//confirm_user_attendance
				array_push( $confirmed_attendances, $course_id );
				update_user_meta( $user->id, 'confirmed_attendances', $confirmed_attendances );
				echo __( 'User ' . $user->first_name . ' ' . $user->last_name . ' attendance to this course succesfully confirmed.', 'iusetvis' );
			}
			else {
				//delete user attendance
				$key = array_search( $course_id, $confirmed_attendances );
				array_splice( $confirmed_attendances, $key, 1 );
				update_user_meta( $user->id, 'confirmed_attendances', $confirmed_attendances );
				echo __( 'User ' . $user->first_name . ' ' . $user->last_name . ' attendance to this course succesfully deleted.', 'iusetvis' );
			}

		}

	}

}
