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

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/iusetvis-public.js', array( 'jquery' ), $this->version, false );

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

	/**
	 * PDF print the Diploma Template.
	 *
	 * @since    1.0.0
	 */
	public function pdf_print_diploma( $user_id, $course_id ) {
		// import and initialize the mpdf library
		require plugin_dir_path( dirname( __FILE__ ) ) . 'vendor/autoload.php';
		$mpdf = new \Mpdf\Mpdf();

		// get data
		$user_meta = get_user_meta( $user_id );
		$course_title = get_the_title($course_id);
		$course_meta = get_post_meta( $course_id );
		$course_term = get_post_terms( $course_id, 'course_location' );
		$course_term_meta = get_term_meta( $course_term[0]->term_id )

		// build object
		$data = array(
			'user_title'			=>	$user_meta['title'], // to be added
			'user_first_name'		=>	$user_meta['first_name'],
			'user_last_name'		=>	$user_meta['last_name'],
			'user_birth_place'		=>	$user_meta['birth_place'], // to be added // to be converted
			'user_birth_date'		=>	$user_meta['birth_date'], // to be added // to be converted
			'user_forum'			=>	$user_meta['forum'], // to be added
			'course_name'			=>	$course_title,
			'course_place'			=>	$course_term[0]->name,
			'course_place_extra'	=>	$course_term[0]->extra, // to be added
			'course_institution'	=>	$course_meta['course_credits_inst'],
			'course_credits_val'	=>	$course_meta['course_credits_val'],
			'course_subject'		=>	$course_meta['course_credits_subj'],
			'course_end_date'		=>	$course_meta['course_end_time'], // to be added // to be converted
			'course_credits_text'	=>	$course_meta['course_credits_text'] // to be added
		);

		// the template
		$html = '
			<html>
				<head>
					<style>
					body {
						font-family: Times, Arial, sans-serif;
						font-size: 9pt;
						background: transparent url(\'asset/sfondo.png\') no-repeat left top;
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
							  		'.$data['user_title'].' '.strtoupper($data['user_first_name']).' '.strtoupper($data['user_last_name']).'
							  	</span>
						  	</td>
						</tr>						
						<tr>
							<td colspan="4" width="100%" height="10"></td>
						</tr>						
						<tr>
						  	<td colspan="4" width="100%" style="text-align:center;"><span class="largo">Nato a '.strtoupper($data['user_birth_place']).' il '.($data['user_birth_date']).' del foro di '.strtoupper($data['user_forum']).'</span></td>
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
				          		tenutosi a  '.$data['course_place'].' '.$data['course_place_extra'].'<br /><br/>accreditato da '.$data['course_institution'].' in ragione di n. '.$data['course_credits_val'].' crediti formativi'.$data['course_credits_subj'].'
							</td>
						</tr>					
						<tr>
							<td colspan="4" width="100%" height="20"></td>
						</tr>				
						<tr>
					  		<td colspan="4" align="left" style="padding-top:10px;padding-left:190px">Monza, li '.$data['course_end_date'].'</td>
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
		$mpdf->Output('Credito_' . urlencode( $data['course_name'] ) , 'D');

	}

}
