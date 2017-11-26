<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://github.com/kokiddp/IusEtVis
 * @since      1.0.0
 *
 * @package    Iusetvis
 * @subpackage Iusetvis/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Iusetvis
 * @subpackage Iusetvis/public
 * @author     Gabriele Coquillard, Stefano Bitto <gabriele.coquillard@gmail.com>
 */
class Iusetvis_Public {

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
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/iusetvis-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
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
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/iusetvis-public.js', array( 'jquery', 'jquery-ajax-native' ), $this->version, false );
		wp_localize_script( $this->plugin_name, 'pdf_print_diploma_ajax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
		wp_localize_script( $this->plugin_name, 'course_subscribe_ajax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
		wp_localize_script( $this->plugin_name, 'course_unsubscribe_ajax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
		wp_localize_script( $this->plugin_name, 'course_waiting_list_subscribe_ajax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );

	}

	/**
	 * Add the shortcodes for the public-facing dise of the site.
	 *
	 * @since    1.0.0
	 */
	public function add_shortcodes() {

		add_shortcode("diploma_download", array( $this, 'display_diploma_download_button' ) );
		add_shortcode("course_subscribe", array( $this, 'display_course_subscribe_button' ) );
		add_shortcode("course_unsubscribe", array( $this, 'display_course_unsubscribe_button' ) );
		add_shortcode("course_waiting_list_subscribe", array( $this, 'display_course_waiting_list_subscribe_button' ) );

	}

	/**
	 * Register the Course Template.
	 *
	 * @since    1.0.0
	 */
	public function course_templates( $template ) {
	    $post_types = array( 'course' );

	    if ( is_singular( $post_types ) )
	        $template = plugin_dir_path( __FILE__ ) . 'partials/iusetvis-public-single-course-display.php';

	    return $template;
	}

	/*
	 * Display course diploma download button
	 *
	 * @since    1.0.0
	 */
	public function display_diploma_download_button() {
		$output = "
			<div class='wrap'>
				<h2>" . __( 'Print course test', 'iusetvis' ) . "</h2>
				<p><input type='submit' style='margin-top: 20px;' class='button-primary' id='download' value='" . __( 'Download', 'iusetvis' ) . "'></p>
			</div>"
		;

		return $output;
	}

	/*
	 * Display course subscribe button
	 *
	 * @since    1.0.0
	 */
	public function display_course_subscribe_button() {
		$output = "
			<div class='wrap'>
				<h2>" . __( 'Subscribe to course', 'iusetvis' ) . "</h2>
				<p><input type='submit' style='margin-top: 20px;' class='button-primary' id='subscribe' value='" . __( 'Subscribe', 'iusetvis' ) . "'></p>
			</div>"
		;

		return $output;
	}

	/*
	 * Display course unsubscribe button
	 *
	 * @since    1.0.0
	 */
	public function display_course_unsubscribe_button() {
		$output = "
			<div class='wrap'>
				<h2>" . __( 'Unsubscribe to course', 'iusetvis' ) . "</h2>
				<p><input type='submit' style='margin-top: 20px;' class='button-primary' id='unsubscribe' value='" . __( 'Unsubscribe', 'iusetvis' ) . "'></p>
			</div>"
		;

		return $output;
	}

	/*
	 * Display course waiting list subscribe button
	 *
	 * @since    1.0.0
	 */
	public function display_course_waiting_list_subscribe_button() {
		$output = "
			<div class='wrap'>
				<h2>" . __( 'Subscribe to waiting list', 'iusetvis' ) . "</h2>
				<p><input type='submit' style='margin-top: 20px;' class='button-primary' id='subscribe-waiting-list' value='" . __( 'Subscribe', 'iusetvis' ) . "'></p>
			</div>"
		;

		return $output;
	}

	/**
	 * PDF print the Diploma Template.
	 *
	 * @since    1.0.0
	 */
	public function pdf_print_diploma( $_user_id = '0', $_course_id = '0' ) {
		
		// retrieve ajax parameters
		$user_id = ( isset( $_POST['user_id'] ) ? $_POST['user_id'] : $_user_id );
		$course_id = ( isset( $_POST['course_id'] ) ? $_POST['course_id'] : $_course_id );
		if ( $user_id == '0' || $course_id == '0' ) {
			die();
		}

		// get data
		$user_meta = get_user_meta( $user_id );
		$course_title = get_the_title($course_id);
		$course_meta = get_post_meta( $course_id );

		$subscribed_users = !isset( $course_meta['subscribed_users'][0] ) ? array() : maybe_unserialize( $course_meta['subscribed_users'][0] );
		$perfected_subscriptions = !isset( $user_meta['perfected_subscriptions'][0] ) ? array() : maybe_unserialize( $user_meta['perfected_subscriptions'][0] );
		$confirmed_attendances = !isset( $user_meta['confirmed_attendances'][0] ) ? array() : maybe_unserialize( $user_meta['confirmed_attendances'][0] );

		// checks
		if ( !in_array( $user_id, $subscribed_users ) || !in_array( $course_id, $perfected_subscriptions ) || !in_array( $course_id, $confirmed_attendances ) ) {
		 	die();
		}

		// import and initialize the mpdf library
		require plugin_dir_path( dirname( __FILE__ ) ) . 'vendor/autoload.php';
		$mpdf = new \Mpdf\Mpdf( ['mode' => 'utf-8', 'format' => 'A4-L'] );

		// build object
		$data = array(
			'user_title'			=>	$user_meta['title'][0],
			'user_first_name'		=>	$user_meta['first_name'][0],
			'user_last_name'		=>	$user_meta['last_name'][0],
			'user_birth_place'		=>	$user_meta['birth_place'][0],
			'user_birth_date'		=>	$user_meta['birth_date'][0],
			'user_forum'			=>	$user_meta['forum'][0],
			'course_name'			=>	$course_title,
			'course_address'		=>	$course_meta['course_address'][0],
			'course_institution'	=>	$course_meta['course_credits_inst'][0],
			'course_credits_val'	=>	$course_meta['course_credits_val'][0],
			'course_subject'		=>	$course_meta['course_credits_subj'][0],
			'course_end_date'		=>	$course_meta['course_end_time'][0],
			'course_credits_text'	=>	$course_meta['course_credits_text'][0]
		);

		// the template
		$html = '
			<html>
				<head>
					<style>
					body {
						font-family: Times, Arial, sans-serif;
						font-size: 9pt;
						background: transparent url(\''.plugins_url().'/IusEtVis/public/asset/sfondo.png\') no-repeat left top;
					}
					
					span.blu {
						font-weight:bold;
						color:#235893;
						font-size:44px
					}
					
					span.corso {
						font-size:20px;
						color:#235893;
						font-weight:normal;
						font-style:italic;
						letter-spacing:1.5px;						
					}
					
					span.largo{
						letter-spacing:1.1px;						
					}
					</style>
				</head>
				<body>
					<table width="100%">
						<tr>
							<td colspan="4" width="100%" height="240"></td>
					  	</tr>					
						<tr>
							<td colspan="4" width="100%" style="text-align:center">Si attesta che</td>
					  	</tr>
						<tr>
							<td colspan="4" width="100%" height="10"></td>
						</tr>						
						<tr>
						  	<td colspan="4" width="100%" style="text-align:center;">
							  	<span class="blu">
							  		'.$data['user_title'].' '.strtoupper( $data['user_first_name'] ).' '.strtoupper( $data['user_last_name'] ).'
							  	</span>
						  	</td>
						</tr>						
						<tr>
							<td colspan="4" width="100%" height="10"></td>
						</tr>						
						<tr>
						  	<td colspan="4" width="100%" style="text-align:center;"><span class="largo">Nato a '.strtoupper( $data['user_birth_place'] ).' il '.date( 'd-m-y', $data['user_birth_date'] ).' del foro di '.strtoupper( $data['user_forum'] ).'</span></td>
						</tr>
						<tr>
							<td colspan="4" width="100%" height="10"></td>
						</tr>
						<tr>
							<td colspan="4" width="100%" style="text-align:center">ha partecipato al convegno</td>
						</tr>					
						<tr>
							<td colspan="4" width="100%" height="10"></td>
						</tr>					
						<tr>
						  	<td colspan="4" align="center"><span class="corso">'.$data['course_name'].'</span></td>
						</tr>
						<tr>
							<td colspan="4" width="100%" height="10"></td>
						</tr>				
						<tr>
				          	<td align="center" colspan="4">
				          		tenutosi a '.$data['course_address'].'<br /><br/>accreditato da '.$data['course_institution'].' in ragione di n. '.$data['course_credits_val'].' crediti formativi'.$data['course_credits_subj'].'
							</td>
						</tr>					
						<tr>
							<td colspan="4" width="100%" height="20"></td>
						</tr>				
						<tr>
					  		<td colspan="4" align="left" style="padding-top:10px;padding-left:190px">Monza, li '.date( 'd-m-Y', $data['course_end_date'] ).'</td>
					  	</tr>					
						<tr>
							<td colspan="4" align="center">'.$data['course_credits_text'].'</td>
						</tr>					  
					</table>
				</body>
			</html>
		';
		

		// output the pdf for download
		$mpdf->WriteHTML($html);
		return $mpdf->Output('Credito_' . urlencode( $data['course_name'] ), 'D' );

		die();

	}

	/**
	 * Subscribe to the course
	 *
	 * @since    1.0.0
	 */
	public function course_subscribe( $_user_id = '0', $_course_id = '0' ) {
		
		// retrieve ajax parameters
		$user_id = ( isset( $_POST['user_id'] ) ? $_POST['user_id'] : $_user_id );
		$course_id = ( isset( $_POST['course_id'] ) ? $_POST['course_id'] : $_course_id );
		if ( $user_id == '0' || $course_id == '0' ) {
			echo __( 'Error: user or course unset!', 'iusetvis' );
			die();
		}

		$course_meta = get_post_custom( $course_id );
		$subscribed_users = !isset( $course_meta['subscribed_users'][0] ) ? array() : maybe_unserialize( $course_meta['subscribed_users'][0] );
		$waiting_users = !isset( $course_meta['waiting_users'][0] ) ? array() : maybe_unserialize( $course_meta['waiting_users'][0] );
		$available_places = !isset( $course_meta['course_places'][0] ) ? 0 : ( (int)$course_meta['course_places'][0] - count( $subscribed_users ) );
		$course_subs_dead_end = !isset( $course_meta['course_subs_dead_end'][0] ) ? array() : maybe_unserialize( $course_meta['course_subs_dead_end'][0] );
		
		if ( time() > (int)$course_subs_dead_end ) {
			echo __( 'Error: the subscriptions are closed!', 'iusetvis' );
		 	die();
		}
		else if ( in_array( $user_id, $subscribed_users ) ) {
		 	echo __( 'Error: the user is already subscribed to this course!', 'iusetvis' );
		 	die();
		}
		else if ( in_array( $user_id, $waiting_users ) ) {
		 	echo __( 'Error: the user is already subscribed to this course\'s waiting list!', 'iusetvis' );
		 	die();
		}
		else if ( $available_places <= 0 ) {
			echo __( 'Error: there are no available places in this course!', 'iusetvis' );
		 	die();
		}
		else {
			array_push( $subscribed_users, $user_id );		
			update_post_meta( $course_id, 'subscribed_users', $subscribed_users );
			echo __( 'User succesfully subscribed to this course.', 'iusetvis' );
			$this->start_unsubscribe_cron( $user_id, $course_id );
			//mail
			$user_info = get_userdata($user_id);
			wp_mail( $user_info->user_email, __( 'IusEtVis', 'iusetvis' ), $this->get_subscription_template( $user_id, $course_id) );
		die();
		}

	}

	/**
	 * Subscribe to the course
	 *
	 * @since    1.0.0
	 */
	public function course_unsubscribe( $_user_id = '0', $_course_id = '0' ) {

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
		$waiting_users = !isset( $course_meta['waiting_users'][0] ) ? array() : maybe_unserialize( $course_meta['waiting_users'][0] );
		$perfected_subscriptions = !isset( $user_meta['perfected_subscriptions'][0] ) ? array() : maybe_unserialize( $user_meta['perfected_subscriptions'][0] );

		if ( !in_array( $user_id, $subscribed_users ) && !in_array( $user_id, $waiting_users ) ) {
		 	echo __( 'Error: the user is not subscribed to this course nor to the waiting list!', 'iusetvis' );
		 	die();
		}

		if ( in_array( $course_id, $perfected_subscriptions ) ) {
			echo __( 'Error: the user subscription has already been perfected!', 'iusetvis' );
		 	die();
		}
		
		if ( in_array( $user_id, $subscribed_users ) ) {
			$key = array_search( $user_id, $subscribed_users );
			$unsub = array_splice( $subscribed_users, $key, 1 );
			update_post_meta( $course_id, 'subscribed_users', $subscribed_users );
		 	echo __( 'User succesfully unsubscribed from this course. ', 'iusetvis' );
		 	//email
		 	$user_info = get_userdata($user_id);
			wp_mail( $user_info->user_email, __( 'IusEtVis', 'iusetvis' ), $this->get_unsubscription_template( $user_id, $course_id) );

		 	//Take another one from the waiting list
		 	if( count( $waiting_users ) > 0 ) {
		 		$first_waiting_user = array_splice( $waiting_users, 0, 1 );
		 		update_post_meta( $course_id, 'waiting_users', $waiting_users );
		 		echo __( 'Subscribing first user from waiting list to this course... ', 'iusetvis' );
		 		$this->course_subscribe( $first_waiting_user, $course_id );
		 	}
		 	die();
		}
		else if ( in_array( $user_id, $waiting_users ) ) {
			$key = array_search( $user_id, $waiting_users );
			$new_waiting_users = array_splice( $waiting_users, $key, 1 );
			update_post_meta( $course_id, 'waiting_users', $waiting_users );
			echo __( 'User succesfully unsubscribed to this course\'s waiting list.', 'iusetvis' );
		 	die();
		}
	}

	/**
	 * Subscribe to the course's waiting list
	 *
	 * @since    1.0.0
	 */
	public function course_waiting_list_subscribe( $_user_id = '0', $_course_id = '0' ) {
		
		// retrieve ajax parameters
		$user_id = ( isset( $_POST['user_id'] ) ? $_POST['user_id'] : $_user_id );
		$course_id = ( isset( $_POST['course_id'] ) ? $_POST['course_id'] : $_course_id );
		if ( $user_id == '0' || $course_id == '0' ) {
			echo __( 'Error: user or course unset!', 'iusetvis' );
			die();
		}

		$course_meta = get_post_custom( $course_id );
		$subscribed_users = !isset( $course_meta['subscribed_users'][0] ) ? array() : maybe_unserialize( $course_meta['subscribed_users'][0] );
		$waiting_users = !isset( $course_meta['waiting_users'][0] ) ? array() : maybe_unserialize( $course_meta['waiting_users'][0] );
		$available_places = !isset( $course_meta['course_places'][0] ) ? 0 : ( (int)$course_meta['course_places'][0] - count( $subscribed_users ) );
		$course_subs_dead_end = !isset( $course_meta['course_subs_dead_end'][0] ) ? 0 : maybe_unserialize( $course_meta['course_subs_dead_end'][0] );
		
		if ( time() > (int)$course_subs_dead_end ) {
			echo __( 'Error: the subscriptions are closed!', 'iusetvis' );
		 	die();
		}
		else if ( in_array( $user_id, $waiting_users ) ) {
		 	echo __( 'Error: the user is already subscribed to this course\'s waiting list!', 'iusetvis' );
		 	die();
		}
		else if ( in_array( $user_id, $subscribed_users ) ) {
		 	echo __( 'Error: the user is already subscribed to this course!', 'iusetvis' );
		 	die();
		}
		else if ( $available_places > 0 ) {
			echo __( 'Error: there are still available places in this course!', 'iusetvis' );
		 	die();
		}
		else {
			array_push( $waiting_users, $user_id );		
			update_post_meta( $course_id, 'waiting_users', $waiting_users );			
			echo __( 'User succesfully subscribed to this course\'s waiting list.', 'iusetvis' );
			die();
		}

	}

	/**
	 * Start unsubscribe cron
	 *
	 * @since    1.0.0
	 */
	public function start_unsubscribe_cron( $user_id, $course_id ) {

		$course_meta = get_post_custom( $course_id );
		$user_meta = get_user_meta( $user_id );
		$subscribed_users = !isset( $course_meta['subscribed_users'][0] ) ? array() : maybe_unserialize( $course_meta['subscribed_users'][0] );
		$course_perf_days = !isset( $course_meta['course_perf_days'][0] ) ? 0 : maybe_unserialize( $course_meta['course_perf_days'][0] );
		$course_subs_dead_end = !isset( $course_meta['course_subs_dead_end'][0] ) ? 0 : maybe_unserialize( $course_meta['course_subs_dead_end'][0] );		
		$perfected_subscriptions = !isset( $user_meta['perfected_subscriptions'][0] ) ? array() : maybe_unserialize( $user_meta['perfected_subscriptions'][0] );

		$cron_timer = ( ( (int)time() + $course_perf_days ) < $course_subs_dead_end ? ( (int)time() + $course_perf_days ) : $course_subs_dead_end );

		// if the user is subscribed to the course
		if ( in_array( $user_id, $subscribed_users ) ) {
			
			// if the user hasn't perfected his subscription to the course
		 	if ( !in_array( $course_id, $perfected_subscriptions ) ) {
		 		
		 		wp_clear_scheduled_hook( 'action_unsubscribe_cron', array( $user_id, $course_id ) );
		 		wp_schedule_single_event( $cron_timer, 'action_unsubscribe_cron', array( $user_id, $course_id ) );

			}

		}

	}

	/**
	 * The unsubscribe cron method
	 *
	 * @since    1.0.0
	 */
	public function run_unsubscribe_cron( $user_id, $course_id ) {

		$course_meta = get_post_custom( $course_id );
		$user_meta = get_user_meta( $user_id );
		$subscribed_users = !isset( $course_meta['subscribed_users'][0] ) ? array() : maybe_unserialize( $course_meta['subscribed_users'][0] );
		$perfected_subscriptions = !isset( $user_meta['perfected_subscriptions'][0] ) ? array() : maybe_unserialize( $user_meta['perfected_subscriptions'][0] );

		// if the user is subscribed to the course
		if ( in_array( $user_id, $subscribed_users ) ) {
			
			// if the user hans't perfected his subscription to the course
		 	if ( !in_array( $course_id, $perfected_subscriptions ) ) {
		 		
		 		$this->course_unsubscribe( $user_id, $course_id );
		 		wp_clear_scheduled_hook( 'action_unsubscribe_cron', array( $user_id, $course_id ) );

			}

		}

	}

	/**
	 * The subscription email template
	 *
	 * @since    1.0.0
	 */
	public function get_subscription_template( $user_id, $course_id ) {

		//stub

		$template = '
			<h2> '. __( 'IusEtVis', 'iusetvis' ) . '</h2>
			<p>' . __( 'Subscribed to course ', 'iusetvis' ) . get_the_title( $course_id ) . '</p>
		';

		return $template;

	}

	/**
	 * The unsubscription email template
	 *
	 * @since    1.0.0
	 */
	public function get_unsubscription_template( $user_id, $course_id ) {
		
		//stub

		$template = '
			<h2> '. __( 'IusEtVis', 'iusetvis' ) . '</h2>
			<p>' . __( 'Unsubscribed from course ', 'iusetvis' ) . get_the_title( $course_id ) . '</p>
		';

		return $template;

	}

}
