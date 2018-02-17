<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://github.com/kokiddp/IusEtVis
 * @since      1.0.0
 *
 * @package    Iusetvis
 * @subpackage Iusetvis/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Iusetvis
 * @subpackage Iusetvis/includes
 * @author     Gabriele Coquillard, Stefano Bitto <gabriele.coquillard@gmail.com>
 */
class Iusetvis {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Iusetvis_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * The custom post type.
	 *
	 * @since    1.0.0
	 * @access   public
	 * @var      string    $post_type    The custom post type slug.
	 */
	public $post_type = 'course';

	/**
	 * The custom taxonomies.
	 *
	 * @since    1.0.0
	 * @access   public
	 * @var      array    $taxonomies    The custom taxonomy slug.
	 */
	public $taxonomies = array( 'course-category', 'course-tag', 'course_location' );

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'PLUGIN_NAME_VERSION' ) ) {
			$this->version = PLUGIN_NAME_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'iusetvis';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
		$this->define_common_hooks();
		$this->add_shortcodes();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Iusetvis_Loader. Orchestrates the hooks of the plugin.
	 * - Iusetvis_i18n. Defines internationalization functionality.
	 * - Iusetvis_Admin. Defines all hooks for the admin area.
	 * - Iusetvis_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-iusetvis-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-iusetvis-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-iusetvis-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-iusetvis-public.php';

		/**
		 * The class responsible for adding the custom post type to the At a Glance
		 * dashboard widget
		 */
		if ( ! class_exists( 'Gamajo_Dashboard_Glancer' ) ) {
		    require plugin_dir_path( dirname( __FILE__ ) ). 'includes/class-gamajo-dashboard-glancer.php';
		}

		/**
		 * Composer
		 */
		require plugin_dir_path( dirname( __FILE__ ) ) . 'vendor/autoload.php';

		/**
		 * The class responsible for defining the subscribed users table list
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-iusetvis-list-table.php';

		$this->loader = new Iusetvis_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Iusetvis_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Iusetvis_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Iusetvis_Admin( $this->get_plugin_name(), $this->get_version() );

		// styles and scripts
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		
		// menu and roles
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'add_menu_pages' );
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'add_role_and_capabilities' );
		$this->loader->add_action( 'dashboard_glance_items', $this, 'add_glance_counts' );
		$this->loader->add_action( 'admin_init', $plugin_admin, 'init_settings' );

		// metaboxes courses
		$this->loader->add_action( 'add_meta_boxes', $this, 'course_time_meta_boxes' );
		$this->loader->add_action( 'save_post', $this, 'save_time_meta_boxes', 10, 2 );
		$this->loader->add_action( 'add_meta_boxes', $this, 'course_credits_meta_boxes' );
		$this->loader->add_action( 'save_post', $this, 'save_credits_meta_boxes', 10, 2 );
		$this->loader->add_action( 'add_meta_boxes', $this, 'course_price_meta_boxes' );
		$this->loader->add_action( 'save_post', $this, 'save_price_meta_boxes', 10, 2 );
		$this->loader->add_action( 'add_meta_boxes', $this, 'course_mod_meta_boxes' );
		$this->loader->add_action( 'save_post', $this, 'save_mod_meta_boxes', 10, 2 );
		$this->loader->add_action( 'add_meta_boxes', $this, 'course_rel_meta_boxes' );
		$this->loader->add_action( 'save_post', $this, 'save_rel_meta_boxes', 10, 2 );
		$this->loader->add_action( 'add_meta_boxes', $this, 'course_places_meta_boxes' );
		$this->loader->add_action( 'save_post', $this, 'save_places_meta_boxes', 10, 2 );
		$this->loader->add_action( 'add_meta_boxes', $this, 'course_internal_meta_boxes' );
		$this->loader->add_action( 'save_post', $this, 'save_internal_meta_boxes', 10, 2 );
		// metaboxes courses BERSI
		$this->loader->add_action( 'add_meta_boxes', $this, 'course_address_meta_boxes' );
		$this->loader->add_action( 'save_post', $this, 'save_address_meta_boxes', 10, 2 );

		// meta fields user
		$this->loader->add_action( 'show_user_profile', $this, 'usermeta_form_field_addinfo' );
		$this->loader->add_action( 'show_user_profile', $this, 'usermeta_form_field_association' );
		//BERSI
		$this->loader->add_action( 'show_user_profile', $this, 'usermeta_form_field_codice_fiscale' );

		$this->loader->add_action( 'edit_user_profile_update', $this, 'usermeta_form_field_title_update' );
		$this->loader->add_action( 'edit_user_profile_update', $this, 'usermeta_form_field_birth_place_update' );
		$this->loader->add_action( 'edit_user_profile_update', $this, 'usermeta_form_field_birth_date_update' );
		$this->loader->add_action( 'edit_user_profile_update', $this, 'usermeta_form_field_forum_update' );
		$this->loader->add_action( 'edit_user_profile_update', $this, 'usermeta_form_field_address_update' );
		$this->loader->add_action( 'edit_user_profile_update', $this, 'usermeta_form_field_phone_update' );		
		$this->loader->add_action( 'edit_user_profile_update', $this, 'usermeta_form_field_association_end_update' );
		$this->loader->add_action( 'edit_user_profile_update', $this, 'usermeta_form_field_association_state_update' );
		//BERSI		
		$this->loader->add_action( 'edit_user_profile_update', $this, 'usermeta_form_field_codice_fiscale_update' );
		$this->loader->add_action( 'edit_user_profile_update', $this, 'usermeta_form_field_vat_number_update' );

		// course custom columns
		$this->loader->add_filter( 'manage_course_posts_columns', $plugin_admin, 'set_custom_edit_course_columns' );
		$this->loader->add_action( 'manage_course_posts_custom_column', $plugin_admin, 'custom_course_column', 10, 2 );

		// ajax
		//perfect subscription
		$this->loader->add_action( 'wp_ajax_perfect_user_subscription', $plugin_admin, 'perfect_user_subscription' );
		$this->loader->add_action( 'wp_ajax_unperfect_user_subscription', $plugin_admin, 'unperfect_user_subscription' );
		//confirm attendance
		$this->loader->add_action( 'wp_ajax_confirm_user_attendance', $plugin_admin, 'confirm_user_attendance' );
		$this->loader->add_action( 'wp_ajax_delete_user_attendance', $plugin_admin, 'delete_user_attendance' );
		//upload csv
		$this->loader->add_action( 'wp_ajax_upload_csv', $plugin_admin, 'upload_csv' );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Iusetvis_Public( $this->get_plugin_name(), $this->get_version() );

		// styles and scripts
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

		// templates
		$this->loader->add_filter( 'template_include', $plugin_public, 'course_templates' );

		// meta fields user
		$this->loader->add_action( 'edit_user_profile', $this, 'usermeta_form_field_addinfo' );
		$this->loader->add_action( 'edit_user_profile', $this, 'usermeta_form_field_association' );
		//BERSI
		$this->loader->add_action( 'edit_user_profile', $this, 'usermeta_form_field_codice_fiscale' );

		$this->loader->add_action( 'personal_options_update', $this, 'usermeta_form_field_title_update' );
		$this->loader->add_action( 'personal_options_update', $this, 'usermeta_form_field_birth_place_update' );
		$this->loader->add_action( 'personal_options_update', $this, 'usermeta_form_field_birth_date_update' );
		$this->loader->add_action( 'personal_options_update', $this, 'usermeta_form_field_forum_update' );
		$this->loader->add_action( 'personal_options_update', $this, 'usermeta_form_field_address_update' );
		$this->loader->add_action( 'personal_options_update', $this, 'usermeta_form_field_phone_update' );		
		$this->loader->add_action( 'personal_options_update', $this, 'usermeta_form_field_association_end_update' );
		$this->loader->add_action( 'personal_options_update', $this, 'usermeta_form_field_association_state_update' );
		//BERSI
		$this->loader->add_action( 'personal_options_update', $this, 'usermeta_form_field_codice_fiscale_update' );
		$this->loader->add_action( 'personal_options_update', $this, 'usermeta_form_field_vat_number_update' );

		// ajax
		// print pdf
		$this->loader->add_action( 'wp_ajax_nopriv_pdf_print_diploma', $plugin_public, 'pdf_print_diploma' );
		$this->loader->add_action( 'wp_ajax_nopriv_pdf_print_notice', $plugin_public, 'pdf_print_notice' );
		$this->loader->add_action( 'wp_ajax_nopriv_pdf_print_bill', $plugin_public, 'pdf_print_bill' );
		// subscribe to course
		$this->loader->add_action( 'wp_ajax_nopriv_course_subscribe', $plugin_public, 'course_subscribe' );
		$this->loader->add_action( 'wp_ajax_nopriv_course_waiting_list_subscribe', $plugin_public, 'course_waiting_list_subscribe' );
		//unsubscribe
		$this->loader->add_action( 'wp_ajax_nopriv_course_unsubscribe', $plugin_public, 'course_unsubscribe' );

		// unsunscribe cron
		$this->loader->add_action( 'action_unsubscribe_cron', $plugin_public, 'run_unsubscribe_cron', 0, 2);

	}

	/**
	 * Register all of the hooks related both to the public-facing and to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_common_hooks() {

		$plugin_admin = new Iusetvis_Admin( $this->get_plugin_name(), $this->get_version() );
		$plugin_public = new Iusetvis_Public( $this->get_plugin_name(), $this->get_version() );

		// post type and taxonomies
		$this->loader->add_action( 'init', $this, 'register_post_type', 0 );
		$this->loader->add_action( 'init', $this, 'register_taxonomy_category', 0 );
		//$this->loader->add_action( 'init', $this, 'register_taxonomy_tag', 0 );
		//$this->loader->add_action( 'init', $this, 'register_taxonomy_location', 0 );

		// ajax
		// print pdf
		$this->loader->add_action( 'wp_ajax_pdf_print_diploma', $plugin_public, 'pdf_print_diploma' );
		$this->loader->add_action( 'wp_ajax_pdf_print_notice', $plugin_public, 'pdf_print_notice' );
		$this->loader->add_action( 'wp_ajax_pdf_print_bill', $plugin_public, 'pdf_print_bill' );
		// subscribe to course
		$this->loader->add_action( 'wp_ajax_course_subscribe', $plugin_public, 'course_subscribe' );
		$this->loader->add_action( 'wp_ajax_course_waiting_list_subscribe', $plugin_public, 'course_waiting_list_subscribe' );
		//unsubscribe
		$this->loader->add_action( 'wp_ajax_course_unsubscribe', $plugin_public, 'course_unsubscribe' );

	}

	/**
	 * Add shortcodes
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function add_shortcodes() {

		$plugin_public = new Iusetvis_Public( $this->get_plugin_name(), $this->get_version() );
		$plugin_admin = new Iusetvis_Admin( $this->get_plugin_name(), $this->get_version() );

		$plugin_public->add_shortcodes();
		$plugin_admin->add_shortcodes();

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Iusetvis_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

	/**
	 * Register the custom post type.
	 *
	 * @link http://codex.wordpress.org/Function_Reference/register_post_type
	 */
	public function register_post_type() {
		$labels = array(
			'name'               => __( 'Courses', 'iusetvis' ),
			'singular_name'      => __( 'Course', 'iusetvis' ),
			'add_new'            => __( 'Add Course', 'iusetvis' ),
			'add_new_item'       => __( 'Add Course', 'iusetvis' ),
			'edit_item'          => __( 'Edit Course', 'iusetvis' ),
			'new_item'           => __( 'New Course', 'iusetvis' ),
			'view_item'          => __( 'View Course', 'iusetvis' ),
			'search_items'       => __( 'Search Course', 'iusetvis' ),
			'not_found'          => __( 'No Course found', 'iusetvis' ),
			'not_found_in_trash' => __( 'No Course in the trash', 'iusetvis' ),
		);

		$supports = array(
			'title',
			'editor',
			'thumbnail',
			'revisions',
			'tags',
			'excerpt'
		);

		$args = array(
			'labels'          => $labels,
			'supports'        => $supports,
			'public'          => true,
			'capability_type' => 'post',
			'rewrite'         => array( 'slug' => $this->post_type, ), // Permalinks format
			'menu_position'   => 30,
			'menu_icon'       => 'dashicons-book',
		);

		//filter for altering the args
		$args = apply_filters( 'course_post_type_args', $args );

		register_post_type( $this->post_type, $args );
	}

	/**
	 * Register a taxonomy for Course Categories.
	 *
	 * @link http://codex.wordpress.org/Function_Reference/register_taxonomy
	 */
	public function register_taxonomy_category() {
		$labels = array(
			'name'                       => __( 'Course Categories', 'iusetvis' ),
			'singular_name'              => __( 'Course Category', 'iusetvis' ),
			'menu_name'                  => __( 'Course Categories', 'iusetvis' ),
			'edit_item'                  => __( 'Edit Course Category', 'iusetvis' ),
			'update_item'                => __( 'Update Course Category', 'iusetvis' ),
			'add_new_item'               => __( 'Add New Course Category', 'iusetvis' ),
			'new_item_name'              => __( 'New Course Category Name', 'iusetvis' ),
			'parent_item'                => __( 'Parent Course Category', 'iusetvis' ),
			'parent_item_colon'          => __( 'Parent Course Category:', 'iusetvis' ),
			'all_items'                  => __( 'All Course Categories', 'iusetvis' ),
			'search_items'               => __( 'Search Course Categories', 'iusetvis' ),
			'popular_items'              => __( 'Popular Course Categories', 'iusetvis' ),
			'separate_items_with_commas' => __( 'Separate Course categories with commas', 'iusetvis' ),
			'add_or_remove_items'        => __( 'Add or remove Course categories', 'iusetvis' ),
			'choose_from_most_used'      => __( 'Choose from the most used Course categories', 'iusetvis' ),
			'not_found'                  => __( 'No Course categories found.', 'iusetvis' ),
		);

		$args = array(
			'labels'            => $labels,
			'public'            => true,
			'show_in_nav_menus' => true,
			'show_ui'           => true,
			'show_tagcloud'     => true,
			'hierarchical'      => true,
			'rewrite'           => array( 'slug' => $this->taxonomies[0] ),
			'show_admin_column' => true,
			'query_var'         => true,
		);

		//filter for altering the args
		$args = apply_filters( 'course_category_taxonomy_args', $args );

		register_taxonomy( $this->taxonomies[0], $this->post_type, $args );
	}

	/**
	 * Register a taxonomy for Course Tags.
	 *
	 * @link http://codex.wordpress.org/Function_Reference/register_taxonomy
	 */
	public function register_taxonomy_tag() {
		$labels = array(
			'name'                       => __( 'Course Tags', 'iusetvis' ),
			'singular_name'              => __( 'Course Tag', 'iusetvis' ),
			'menu_name'                  => __( 'Course Tags', 'iusetvis' ),
			'edit_item'                  => __( 'Edit Course Tag', 'iusetvis' ),
			'update_item'                => __( 'Update Course Tag', 'iusetvis' ),
			'add_new_item'               => __( 'Add New Course Tag', 'iusetvis' ),
			'new_item_name'              => __( 'New Course Tag Name', 'iusetvis' ),
			'parent_item'                => null,
			'parent_item_colon'          => null,
			'all_items'                  => __( 'All Course Tags', 'iusetvis' ),
			'search_items'               => __( 'Search Course Tag', 'iusetvis' ),
			'popular_items'              => __( 'Popular Course Tag', 'iusetvis' ),
			'separate_items_with_commas' => __( 'Separate Course Tags with commas', 'iusetvis' ),
			'add_or_remove_items'        => __( 'Add or remove Course Tags', 'iusetvis' ),
			'choose_from_most_used'      => __( 'Choose from the most used Course Tags', 'iusetvis' ),
			'not_found'                  => __( 'No Course Tag found.', 'iusetvis' ),
		);

		$args = array(
			'labels'            => $labels,
			'public'            => true,
			'show_in_nav_menus' => true,
			'show_ui'           => true,
			'show_tagcloud'     => true,
			'hierarchical'      => false,
			'rewrite'           => array( 'slug' => $this->taxonomies[1] ),
			'show_admin_column' => true,
			'query_var'         => true,
		);

		//filter for altering the args
		$args = apply_filters( 'course_tag_taxonomy_args', $args );

		register_taxonomy( $this->taxonomies[1], $this->post_type, $args );
	}

	/**
	 * Register a taxonomy for Course Location.
	 *
	 * @link http://codex.wordpress.org/Function_Reference/register_taxonomy
	 */
	public function register_taxonomy_location() {
		$labels = array(
			'name'                       => __( 'Course Locations', 'iusetvis' ),
			'singular_name'              => __( 'Course Location', 'iusetvis' ),
			'menu_name'                  => __( 'Course Locations', 'iusetvis' ),
			'edit_item'                  => __( 'Edit Course Location', 'iusetvis' ),
			'update_item'                => __( 'Update Course Location', 'iusetvis' ),
			'add_new_item'               => __( 'Add New Course Location', 'iusetvis' ),
			'new_item_name'              => __( 'New Course Location Name', 'iusetvis' ),
			'parent_item'                => null,
			'parent_item_colon'          => null,
			'all_items'                  => __( 'All Course Locations', 'iusetvis' ),
			'search_items'               => __( 'Search Course Location', 'iusetvis' ),
			'popular_items'              => __( 'Popular Course Location', 'iusetvis' ),
			'separate_items_with_commas' => __( 'Separate Course Locations with commas', 'iusetvis' ),
			'add_or_remove_items'        => __( 'Add or remove Course Locations', 'iusetvis' ),
			'choose_from_most_used'      => __( 'Choose from the most used Course Locations', 'iusetvis' ),
			'not_found'                  => __( 'No Course Location found.', 'iusetvis' ),
		);

		$args = array(
			'labels'            => $labels,
			'public'            => true,
			'show_in_nav_menus' => true,
			'show_ui'           => true,
			'show_tagcloud'     => true,
			'hierarchical'      => false,
			'rewrite'           => array( 'slug' => $this->taxonomies[2] ),
			'show_admin_column' => true,
			'query_var'         => true,
			'meta_box_cb' 		=> 'post_categories_meta_box'
		);

		//filter for altering the args
		$args = apply_filters( 'course_location_taxonomy_args', $args );

		register_taxonomy( $this->taxonomies[2], $this->post_type, $args );
	}

	/**
	 * Add counts to "At a Glance" dashboard widget in WP 3.8+
	 *
	 */
	public function add_glance_counts() {
		$glancer = new Gamajo_Dashboard_Glancer;
    	$glancer->add( $this->post_type );
	}

	/**
	 * Register the time metaboxes to be used for the course post type
	 *
	 */
	public function course_time_meta_boxes() {
		add_meta_box(
			'time_fields',
			__( 'Course Time', 'iusetvis' ),
			array( $this, 'render_time_meta_boxes' ),
			$this->post_type,
			'normal',
			'high'
		);
	}

   /**
	* The HTML for the time fields
	*/
	function render_time_meta_boxes( $post ) {

		$meta = get_post_custom( $post->ID );
		$course_start_time = ! isset( $meta['course_start_time'][0] ) ? '' : $meta['course_start_time'][0];
		$course_end_time = ! isset( $meta['course_end_time'][0] ) ? '' : $meta['course_end_time'][0];
		$course_subs_dead_end = ! isset( $meta['course_subs_dead_end'][0] ) ? '' : $meta['course_subs_dead_end'][0];
		$course_perf_days = ! isset( $meta['course_perf_days'][0] ) ? '' : $meta['course_perf_days'][0];

		wp_nonce_field( basename( __FILE__ ), 'time_fields' ); ?>

		<table class="form-table">

			<tr>
				<td class="course_meta_box_td" colspan="1">
					<label for="course_start_time" style="font-weight: bold;"><?php _e( 'Start Time and Date', 'iusetvis' ); ?>
					</label>
				</td>
				<td colspan="4">
					<input type="datetime-local" name="course_start_time" class="regular-text" value="<?php echo date( 'Y-m-d\TH:i', $course_start_time ); ?>">
				</td>
			</tr>

			<tr>
				<td class="course_meta_box_td" colspan="1">
					<label for="course_end_time" style="font-weight: bold;"><?php _e( 'End Date', 'iusetvis' ); ?>
					</label>
				</td>
				<td colspan="4">
					<input type="date" name="course_end_time" class="regular-text" value="<?php echo date( 'Y-m-d', $course_end_time ); ?>">
				</td>
			</tr>

			<tr>
				<td class="course_meta_box_td" colspan="1">
					<label for="course_subs_dead_end" style="font-weight: bold;"><?php _e( 'Subscriptions dead end Time and Date', 'iusetvis' ); ?>
					</label>
				</td>
				<td colspan="4">
					<input type="datetime-local" name="course_subs_dead_end" class="regular-text" value="<?php echo date( 'Y-m-d\TH:i', $course_subs_dead_end ); ?>">
				</td>
			</tr>

			<tr>
				<td class="course_meta_box_td" colspan="1">
					<label for="course_perf_days" style="font-weight: bold;"><?php _e( 'Days available to perfect the registration before being excluded fro the course', 'iusetvis' ); ?>
					</label>
				</td>
				<td colspan="4">
					<input type="number" name="course_perf_days" class="regular-text" value="<?php echo (int)( $course_perf_days / 86400 ); ?>">
				</td>
			</tr>

		</table>

	<?php }

   /**
	* Save time metaboxes
	*
	*/
	function save_time_meta_boxes( $post_id ) {

		global $post;

		// Verify nonce
		if ( !isset( $_POST['time_fields'] ) || !wp_verify_nonce( $_POST['time_fields'], basename(__FILE__) ) ) {
			return $post_id;
		}

		// Check Autosave
		if ( (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) || ( defined('DOING_AJAX') && DOING_AJAX) || isset($_REQUEST['bulk_edit']) ) {
			return $post_id;
		}

		// Don't save if only a revision
		if ( isset( $post->post_type ) && $post->post_type == 'revision' ) {
			return $post_id;
		}

		// Check permissions
		if ( !current_user_can( 'edit_post', $post->ID ) ) {
			return $post_id;
		}

		$meta['course_start_time'] = ( isset( $_POST['course_start_time'] ) ? strtotime( esc_textarea( $_POST['course_start_time'] ) ) : '' );
		$meta['course_end_time'] = ( isset( $_POST['course_end_time'] ) ? strtotime( esc_textarea( $_POST['course_end_time'] ) ) : '' );
		$meta['course_subs_dead_end'] = ( isset( $_POST['course_subs_dead_end'] ) ? strtotime( esc_textarea( $_POST['course_subs_dead_end'] ) ) : '' );
		$meta['course_perf_days'] = ( isset( $_POST['course_perf_days'] ) ? ( esc_textarea( $_POST['course_perf_days'] ) * 86400 ) : '' );

		foreach ( $meta as $key => $value ) {
			update_post_meta( $post->ID, $key, $value );
		}
	}

	/**
	 * Register the credits metaboxes to be used for the course post type
	 *
	 */
	public function course_credits_meta_boxes() {
		add_meta_box(
			'credits_fields',
			__( 'Course Credits', 'iusetvis' ),
			array( $this, 'render_credits_meta_boxes' ),
			$this->post_type,
			'normal',
			'high'
		);
	}

   /**
	* The HTML for the credits fields
	*/
	function render_credits_meta_boxes( $post ) {

		$meta = get_post_custom( $post->ID );
		$course_credits_inst = ! isset( $meta['course_credits_inst'][0] ) ? '' : $meta['course_credits_inst'][0];
		$course_credits_val = ! isset( $meta['course_credits_val'][0] ) ? '' : $meta['course_credits_val'][0];
		$course_credits_subj = ! isset( $meta['course_credits_subj'][0] ) ? '' : $meta['course_credits_subj'][0];
		$course_credits_text = ! isset( $meta['course_credits_text'][0] ) ? '' : $meta['course_credits_text'][0];

		wp_nonce_field( basename( __FILE__ ), 'credits_fields' ); ?>

		<table class="form-table">

			<tr>
				<td class="course_meta_box_td" colspan="1">
					<label for="course_credits_inst" style="font-weight: bold;"><?php _e( 'Institution', 'iusetvis' ); ?>
					</label>
				</td>
				<td colspan="4">
					<input type="text" name="course_credits_inst" class="regular-text" value="<?php echo $course_credits_inst; ?>">
					<p class="description"><?php _e( 'Example: Consiglio dell’Ordine degli Avvocati di Monza', 'iusetvis' ); ?></p>
				</td>
			</tr>

			<tr>
				<td class="course_meta_box_td" colspan="1">
					<label for="course_credits_val" style="font-weight: bold;"><?php _e( 'Credits', 'iusetvis' ); ?>
					</label>
				</td>
				<td colspan="4">
					<input type="text" name="course_credits_val" class="regular-text" value="<?php echo $course_credits_val; ?>">
					<p class="description"><?php _e( 'Example: 5', 'iusetvis' ); ?></p>
				</td>
			</tr>

			<tr>
				<td class="course_meta_box_td" colspan="1">
					<label for="course_credits_subj" style="font-weight: bold;"><?php _e( 'Subject', 'iusetvis' ); ?>
					</label>
				</td>
				<td colspan="4">
					<input type="text" name="course_credits_subj" class="regular-text" value="<?php echo $course_credits_subj; ?>">
					<p class="description"><?php _e( 'Example: Diritto Processuale Penale', 'iusetvis' ); ?></p>
				</td>
			</tr>

			<tr>
				<td class="course_meta_box_td" colspan="1">
					<label for="course_credits_text" style="font-weight: bold;"><?php _e( 'Credits description', 'iusetvis' ); ?>
					</label>
				</td>
				<td colspan="4">
					<input type="text" name="course_credits_text" class="regular-text" value="<?php echo $course_credits_text; ?>">
					<p class="description"><?php _e( 'Example: ***', 'iusetvis' ); ?></p>
				</td>
			</tr>

		</table>

	<?php }

   /**
	* Save credits metaboxes
	*
	*/
	function save_credits_meta_boxes( $post_id ) {

		global $post;

		// Verify nonce
		if ( !isset( $_POST['credits_fields'] ) || !wp_verify_nonce( $_POST['credits_fields'], basename(__FILE__) ) ) {
			return $post_id;
		}

		// Check Autosave
		if ( (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) || ( defined('DOING_AJAX') && DOING_AJAX) || isset($_REQUEST['bulk_edit']) ) {
			return $post_id;
		}

		// Don't save if only a revision
		if ( isset( $post->post_type ) && $post->post_type == 'revision' ) {
			return $post_id;
		}

		// Check permissions
		if ( !current_user_can( 'edit_post', $post->ID ) ) {
			return $post_id;
		}

		$meta['course_credits_inst'] = ( isset( $_POST['course_credits_inst'] ) ? esc_textarea( $_POST['course_credits_inst'] ) : '' );
		$meta['course_credits_val'] = ( isset( $_POST['course_credits_val'] ) ? esc_textarea( $_POST['course_credits_val'] ) : '' );
		$meta['course_credits_subj'] = ( isset( $_POST['course_credits_subj'] ) ? esc_textarea( $_POST['course_credits_subj'] ) : '' );
		$meta['course_credits_text'] = ( isset( $_POST['course_credits_text'] ) ? esc_textarea( $_POST['course_credits_text'] ) : '' );

		foreach ( $meta as $key => $value ) {
			update_post_meta( $post->ID, $key, $value );
		}
	}

	/**
	 * Register the price metaboxes to be used for the course post type
	 *
	 */
	public function course_price_meta_boxes() {
		add_meta_box(
			'price_fields',
			__( 'Course Price', 'iusetvis' ),
			array( $this, 'render_price_meta_boxes' ),
			$this->post_type,
			'normal',
			'high'
		);
	}

   /**
	* The HTML for the price fields
	*/
	function render_price_meta_boxes( $post ) {

		$meta = get_post_custom( $post->ID );
		$course_price_assoc = ! isset( $meta['course_price_assoc'][0] ) ? '' : $meta['course_price_assoc'][0];
		$course_price_reg = ! isset( $meta['course_price_reg'][0] ) ? '' : $meta['course_price_reg'][0];

		wp_nonce_field( basename( __FILE__ ), 'price_fields' ); ?>

		<table class="form-table">

			<tr>
				<td class="course_meta_box_td" colspan="1">
					<label for="course_price_assoc" style="font-weight: bold;"><?php _e( 'Price for associates', 'iusetvis' ); ?>
					</label>
				</td>
				<td colspan="4">
					<input type="number" name="course_price_assoc" class="regular-text" value="<?php echo $course_price_assoc; ?>">€
					<p class="description"><?php _e( 'Example: 10 (numbers only)', 'iusetvis' ); ?></p>
				</td>
			</tr>

			<tr>
				<td class="course_meta_box_td" colspan="1">
					<label for="course_price_reg" style="font-weight: bold;"><?php _e( 'Regular price', 'iusetvis' ); ?>
					</label>
				</td>
				<td colspan="4">
					<input type="number" name="course_price_reg" class="regular-text" value="<?php echo $course_price_reg; ?>">€
					<p class="description"><?php _e( 'Example: 25 (numbers only)', 'iusetvis' ); ?></p>
				</td>
			</tr>

		</table>

	<?php }

   /**
	* Save price metaboxes
	*
	*/
	function save_price_meta_boxes( $post_id ) {

		global $post;

		// Verify nonce
		if ( !isset( $_POST['price_fields'] ) || !wp_verify_nonce( $_POST['price_fields'], basename(__FILE__) ) ) {
			return $post_id;
		}

		// Check Autosave
		if ( (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) || ( defined('DOING_AJAX') && DOING_AJAX) || isset($_REQUEST['bulk_edit']) ) {
			return $post_id;
		}

		// Don't save if only a revision
		if ( isset( $post->post_type ) && $post->post_type == 'revision' ) {
			return $post_id;
		}

		// Check permissions
		if ( !current_user_can( 'edit_post', $post->ID ) ) {
			return $post_id;
		}

		$meta['course_price_assoc'] = ( isset( $_POST['course_price_assoc'] ) ? esc_textarea( $_POST['course_price_assoc'] ) : '' );
		$meta['course_price_reg'] = ( isset( $_POST['course_price_reg'] ) ? esc_textarea( $_POST['course_price_reg'] ) : '' );

		foreach ( $meta as $key => $value ) {
			update_post_meta( $post->ID, $key, $value );
		}
	}
	
	/**
	 * Register the moderator metaboxes to be used for the course post type
	 *
	 */
	public function course_mod_meta_boxes() {
		add_meta_box(
			'moderator_fields',
			__( 'Course Moderator', 'iusetvis' ),
			array( $this, 'render_mod_meta_boxes' ),
			$this->post_type,
			'normal',
			'high'
		);
	}

   /**
	* The HTML for the moderator fields
	*/
	function render_mod_meta_boxes( $post ) {

		$meta = get_post_custom( $post->ID );
		$course_mod_title = ! isset( $meta['course_mod_title'][0] ) ? '' : $meta['course_mod_title'][0];
		$course_mod_name = ! isset( $meta['course_mod_name'][0] ) ? '' : $meta['course_mod_name'][0];
		$course_mod_extra = ! isset( $meta['course_mod_extra'][0] ) ? '' : $meta['course_mod_extra'][0];

		wp_nonce_field( basename( __FILE__ ), 'moderator_fields' ); ?>

		<table class="form-table">

			<tr>
				<td class="course_meta_box_td" colspan="1">
					<label for="course_mod_title" style="font-weight: bold;"><?php _e( 'Title', 'iusetvis' ); ?>
					</label>
				</td>
				<td colspan="4">
					<input type="text" name="course_mod_title" class="regular-text" value="<?php echo $course_mod_title; ?>">
					<p class="description"><?php _e( 'Example: Avv.', 'iusetvis' ); ?></p>
				</td>
			</tr>

			<tr>
				<td class="course_meta_box_td" colspan="1">
					<label for="course_mod_name" style="font-weight: bold;"><?php _e( 'Name', 'iusetvis' ); ?>
					</label>
				</td>
				<td colspan="4">
					<input type="text" name="course_mod_name" class="regular-text" value="<?php echo $course_mod_name; ?>">
					<p class="description"><?php _e( 'Example: Mario Rossi', 'iusetvis' ); ?></p>
				</td>
			</tr>

			<tr>
				<td class="course_meta_box_td" colspan="1">
					<label for="course_mod_extra" style="font-weight: bold;"><?php _e( 'Details', 'iusetvis' ); ?>
					</label>
				</td>
				<td colspan="4">
					<input type="text" name="course_mod_extra" class="regular-text" value="<?php echo $course_mod_extra; ?>">
					<p class="description"><?php _e( 'Example: Avvocato del Foro di Milano', 'iusetvis' ); ?></p>
				</td>
			</tr>

		</table>

	<?php }

   /**
	* Save moderator metaboxes
	*
	*/
	function save_mod_meta_boxes( $post_id ) {

		global $post;

		// Verify nonce
		if ( !isset( $_POST['moderator_fields'] ) || !wp_verify_nonce( $_POST['moderator_fields'], basename(__FILE__) ) ) {
			return $post_id;
		}

		// Check Autosave
		if ( (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) || ( defined('DOING_AJAX') && DOING_AJAX) || isset($_REQUEST['bulk_edit']) ) {
			return $post_id;
		}

		// Don't save if only a revision
		if ( isset( $post->post_type ) && $post->post_type == 'revision' ) {
			return $post_id;
		}

		// Check permissions
		if ( !current_user_can( 'edit_post', $post->ID ) ) {
			return $post_id;
		}

		$meta['course_mod_title'] = ( isset( $_POST['course_mod_title'] ) ? esc_textarea( $_POST['course_mod_title'] ) : '' );
		$meta['course_mod_name'] = ( isset( $_POST['course_mod_name'] ) ? esc_textarea( $_POST['course_mod_name'] ) : '' );
		$meta['course_mod_extra'] = ( isset( $_POST['course_mod_extra'] ) ? esc_textarea( $_POST['course_mod_extra'] ) : '' );

		foreach ( $meta as $key => $value ) {
			update_post_meta( $post->ID, $key, $value );
		}
	}

	/**
	 * Register the relators metaboxes to be used for the course post type
	 *
	 */
	public function course_rel_meta_boxes() {
		add_meta_box(
			'relators_fields',
			__( 'Course Relators', 'iusetvis' ),
			array( $this, 'render_rel_meta_boxes' ),
			$this->post_type,
			'normal',
			'high'
		);
	}

   /**
	* The HTML for the relators fields
	*/
	function render_rel_meta_boxes( $post ) {

		$meta = get_post_custom( $post->ID );
		$course_rel_title = ['', '', '', '', ''];
		$course_rel_name = ['', '', '', '', ''];
		$course_rel_extra = ['', '', '', '', ''];
		$course_rel_title = ! isset( $meta['course_rel_title'][0] ) ? ['', '', '', '', ''] : maybe_unserialize( $meta['course_rel_title'][0] );
		$course_rel_name = ! isset( $meta['course_rel_name'][0] ) ? ['', '', '', '', ''] : maybe_unserialize( $meta['course_rel_name'][0] );
		$course_rel_extra = ! isset( $meta['course_rel_extra'][0] ) ? ['', '', '', '', ''] : maybe_unserialize( $meta['course_rel_extra'][0] );

		wp_nonce_field( basename( __FILE__ ), 'relators_fields' ); ?>

		<table class="form-table">

			<?php for ($i=0; $i < 5 ; $i++) { ?>
			
			<tr>
				<td class="course_meta_box_td" colspan="1">
					<label for="course_rel_title_<?php echo $i ?>" style="font-weight: bold;"><?php _e( 'Title', 'iusetvis' ); ?>
					</label>
				</td>
				<td colspan="2">
					<input type="text" size='10' name="course_rel_title_<?php echo $i ?>" class="regular-text" value="<?php echo $course_rel_title[$i]; ?>">
					<p class="description"><?php _e( 'Example: Avv.', 'iusetvis' ); ?></p>
				</td>

				<td class="course_meta_box_td" colspan="1">
					<label for="course_rel_name_<?php echo $i ?>" style="font-weight: bold;"><?php _e( 'Name', 'iusetvis' ); ?>
					</label>
				</td>
				<td colspan="2">
					<input type="text" name="course_rel_name_<?php echo $i ?>" class="regular-text" value="<?php echo $course_rel_name[$i]; ?>">
					<p class="description"><?php _e( 'Example: Mario Rossi', 'iusetvis' ); ?></p>
				</td>

				<td class="course_meta_box_td" colspan="1">
					<label for="course_rel_extra_<?php echo $i ?>" style="font-weight: bold;"><?php _e( 'Details', 'iusetvis' ); ?>
					</label>
				</td>
				<td colspan="2">
					<input type="text" name="course_rel_extra_<?php echo $i ?>" class="regular-text" value="<?php echo $course_rel_extra[$i]; ?>">
					<p class="description"><?php _e( 'Example: Avvocato del Foro di Milano', 'iusetvis' ); ?></p>
				</td>
			</tr>

			<?php } ?>

		</table>

	<?php }

   /**
	* Save relators metaboxes
	*
	*/
	function save_rel_meta_boxes( $post_id ) {

		global $post;

		// Verify nonce
		if ( !isset( $_POST['relators_fields'] ) || !wp_verify_nonce( $_POST['relators_fields'], basename(__FILE__) ) ) {
			return $post_id;
		}

		// Check Autosave
		if ( (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) || ( defined('DOING_AJAX') && DOING_AJAX) || isset($_REQUEST['bulk_edit']) ) {
			return $post_id;
		}

		// Don't save if only a revision
		if ( isset( $post->post_type ) && $post->post_type == 'revision' ) {
			return $post_id;
		}

		// Check permissions
		if ( !current_user_can( 'edit_post', $post->ID ) ) {
			return $post_id;
		}

		$meta['course_rel_title'] = array();
		$meta['course_rel_name'] = array();
		$meta['course_rel_extra'] = array();

		for ($i=0; $i < 5 ; $i++) { 
			isset( $_POST['course_rel_title_' . $i] ) ?
				array_push( $meta['course_rel_title'], esc_textarea( $_POST['course_rel_title_' . $i] ) ) :
				array_push( $meta['course_rel_title'], '' );
			isset( $_POST['course_rel_name_' . $i] ) ?
				array_push( $meta['course_rel_name'], esc_textarea( $_POST['course_rel_name_' . $i] ) ) :
				array_push( $meta['course_rel_name'], '' );
			isset( $_POST['course_rel_extra_' . $i] ) ?
				array_push($meta['course_rel_extra'], esc_textarea( $_POST['course_rel_extra_' . $i] ) ) :
				array_push( $meta['course_rel_extra'], '' );
		}

		foreach ( $meta as $key => $value ) {
			update_post_meta( $post->ID, $key, $value );
		}
	}


	/*BERSI*/
	/**
	 * Register the address metaboxes to be used for the course post type
	 * This field is for geolocate the course in map
	 */
	public function course_address_meta_boxes() {
		add_meta_box(
			'address_fields',
			__( 'Course Address', 'iusetvis' ),
			array( $this, 'render_address_meta_boxes' ),
			$this->post_type,
			'normal',
			'high'
		);
	}

	/**
	 * The HTML for the address fields
	 * This field is for geolocate the course in map
	 */
	function render_address_meta_boxes( $post ) {

		$meta = get_post_custom( $post->ID );
		$course_address = ! isset( $meta['course_address'][0] ) ? '' : $meta['course_address'][0];

		wp_nonce_field( basename( __FILE__ ), 'address_fields' ); ?>

		<table class="form-table">

			<tr>
				<td class="course_meta_box_td" colspan="1">
					<label for="course_address" style="font-weight: bold;"><?php _e( 'Course address', 'iusetvis' ); ?>
					</label>
				</td>
				<td colspan="4">
					<input type="text" name="course_address" class="regular-text" value="<?php echo $course_address; ?>">
					<p class="description"><?php _e( 'Example: 20121 Milano Via Montenapoleone 4', 'iusetvis' ); ?></p>
				</td>
			</tr>

		</table>

	<?php }

    /**
	 * Save address metaboxes
	 *
	 */
	function save_address_meta_boxes( $post_id ) {

		global $post;

		// Verify nonce
		if ( !isset( $_POST['address_fields'] ) || !wp_verify_nonce( $_POST['address_fields'], basename(__FILE__) ) ) {
			return $post_id;
		}

		// Check Autosave
		if ( (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) || ( defined('DOING_AJAX') && DOING_AJAX) || isset($_REQUEST['bulk_edit']) ) {
			return $post_id;
		}

		// Don't save if only a revision
		if ( isset( $post->post_type ) && $post->post_type == 'revision' ) {
			return $post_id;
		}

		// Check permissions
		if ( !current_user_can( 'edit_post', $post->ID ) ) {
			return $post_id;
		}

		$meta['course_address'] = ( isset( $_POST['course_address'] ) ? esc_textarea( $_POST['course_address'] ) : '' );

		foreach ( $meta as $key => $value ) {
			update_post_meta( $post->ID, $key, $value );
		}
	}
	/*BERSI*/

	/**
	 * Register the address metaboxes to be used for the course post type
	 * This field is for geolocate the course in map
	 */
	public function course_places_meta_boxes() {
		add_meta_box(
			'places_fields',
			__( 'Course available places', 'iusetvis' ),
			array( $this, 'render_places_meta_boxes' ),
			$this->post_type,
			'normal',
			'high'
		);
	}

	/**
	 * The HTML for the address fields
	 * This field is for geolocate the course in map
	 */
	function render_places_meta_boxes( $post ) {

		$meta = get_post_custom( $post->ID );
		$course_places = ! isset( $meta['course_places'][0] ) ? '' : $meta['course_places'][0];

		wp_nonce_field( basename( __FILE__ ), 'places_fields' ); ?>

		<table class="form-table">

			<tr>
				<td class="course_meta_box_td" colspan="1">
					<label for="course_places" style="font-weight: bold;"><?php _e( 'Course available places', 'iusetvis' ); ?>
					</label>
				</td>
				<td colspan="4">
					<input type="number" name="course_places" class="regular-text" value="<?php echo $course_places; ?>">
					<p class="description"><?php _e( 'Example: 20', 'iusetvis' ); ?></p>
				</td>
			</tr>

		</table>

	<?php }

    /**
	 * Save address metaboxes
	 *
	 */
	function save_places_meta_boxes( $post_id ) {

		global $post;

		// Verify nonce
		if ( !isset( $_POST['places_fields'] ) || !wp_verify_nonce( $_POST['places_fields'], basename(__FILE__) ) ) {
			return $post_id;
		}

		// Check Autosave
		if ( (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) || ( defined('DOING_AJAX') && DOING_AJAX) || isset($_REQUEST['bulk_edit']) ) {
			return $post_id;
		}

		// Don't save if only a revision
		if ( isset( $post->post_type ) && $post->post_type == 'revision' ) {
			return $post_id;
		}

		// Check permissions
		if ( !current_user_can( 'edit_post', $post->ID ) ) {
			return $post_id;
		}

		$meta['course_places'] = ( isset( $_POST['course_places'] ) ? esc_textarea( $_POST['course_places'] ) : '' );

		foreach ( $meta as $key => $value ) {
			update_post_meta( $post->ID, $key, $value );
		}
	}

	/**
	 * Register the credits metaboxes to be used for the course post type
	 *
	 */
	public function course_internal_meta_boxes() {
		add_meta_box(
			'internal_fields',
			__( 'Course Internal Settings', 'iusetvis' ),
			array( $this, 'render_internal_meta_boxes' ),
			$this->post_type,
			'normal',
			'high'
		);
	}

   /**
	* The HTML for the internal fields
	*/
	function render_internal_meta_boxes( $post ) {

		$meta = get_post_custom( $post->ID );
		$options = get_option( $this->plugin_name . '_settings' );
		$course_president_name = ! isset( $meta['course_president_name'][0] ) ? $options['iusetvis_president'] : $meta['course_president_name'][0];
		$course_president_signature = ! isset( $meta['course_president_signature'][0] ) ? $options['iusetvis_signature'] : $meta['course_president_signature'][0];
		$course_code = ! isset( $meta['course_code'][0] ) ? $post->ID : $meta['course_code'][0];

		wp_nonce_field( basename( __FILE__ ), 'internal_fields' ); ?>

		<table class="form-table">

			<tr>
				<td class="course_meta_box_td" colspan="1">
					<label for="course_president_name" style="font-weight: bold;"><?php _e( 'President name', 'iusetvis' ); ?>
					</label>
				</td>
				<td colspan="4">
					<input type="text" name="course_president_name" class="regular-text" value="<?php echo $course_president_name; ?>" readonly>
					<input type="hidden" name="course_president_signature" value="<?= $course_president_signature ?>"">
				</td>
			</tr>
			<tr>
				<td class="course_meta_box_td" colspan="1">
					<label for="course_code" style="font-weight: bold;"><?php _e( 'Course code', 'iusetvis' ); ?>
					</label>
				</td>
				<td colspan="4">
					<input type="text" name="course_code" class="regular-text" value="<?php echo $course_code; ?>">
					<p class="description"><?php echo __( 'Example: ', 'iusetvis' ) . $post->ID; ?></p>
				</td>
			</tr>

		</table>

	<?php }

   /**
	* Save internal metaboxes
	*
	*/
	function save_internal_meta_boxes( $post_id ) {

		global $post;

		// Verify nonce
		if ( !isset( $_POST['internal_fields'] ) || !wp_verify_nonce( $_POST['internal_fields'], basename(__FILE__) ) ) {
			return $post_id;
		}

		// Check Autosave
		if ( (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) || ( defined('DOING_AJAX') && DOING_AJAX) || isset($_REQUEST['bulk_edit']) ) {
			return $post_id;
		}

		// Don't save if only a revision
		if ( isset( $post->post_type ) && $post->post_type == 'revision' ) {
			return $post_id;
		}

		// Check permissions
		if ( !current_user_can( 'edit_post', $post->ID ) ) {
			return $post_id;
		}

		$options = get_option( $this->plugin_name . '_settings' );

		$meta['course_president_name'] = ( isset( $_POST['course_president_name'] ) ? esc_textarea( $_POST['course_president_name'] ) : $options['iusetvis_president'] );
		$meta['course_president_signature'] = ( isset( $_POST['course_president_signature'] ) ? esc_textarea( $_POST['course_president_signature'] ) : $options['course_president_signature'] );
		$meta['course_code'] = ( isset( $_POST['course_code'] ) ? esc_textarea( $_POST['course_code'] ) : $post->ID );

		foreach ( $meta as $key => $value ) {
			update_post_meta( $post->ID, $key, $value );
		}
	}


	/**
	 * The address and phone number user meta field on the editing screens.
	 *
	 * @param $user WP_User user object
	 */
	function usermeta_form_field_addinfo($user)
	{
	    ?>
	    <h3><?php _e( 'Additional info', 'iusetvis' ); ?></h3>
	    <table class="form-table">

	        <tr>
	            <th>
	                <label for="title"><?php _e( 'Title', 'iusetvis' ); ?></label>
	            </th>
	            <td>
	                <input type="text"
	                       class="regular-text ltr"
	                       name="title"
	                       value="<?= esc_attr(get_user_meta($user->ID, 'title', true)); ?>">
	                <p class="description">
	                    <?php _e( 'Please enter your title', 'iusetvis' ); ?>
	                </p>
	            </td>
	        </tr>

	        <tr>
	            <th>
	                <label for="birth_place"><?php _e( 'Birth place', 'iusetvis' ); ?></label>
	            </th>
	            <td>
	                <input type="text"
	                       class="regular-text ltr"
	                       name="birth_place"
	                       value="<?= esc_attr(get_user_meta($user->ID, 'birth_place', true)); ?>">
	                <p class="description">
	                    <?php _e( 'Please enter your birth place', 'iusetvis' ); ?>
	                </p>
	            </td>
	        </tr>

	        <tr>
	            <th>
	                <label for="birth_date"><?php _e( 'Birth date', 'iusetvis' ); ?></label>
	            </th>
	            <td>
	            	<input  type="date"
	            			name="birth_date"
	            			class="regular-text ltr"
	            			value="<?= date( 'Y-m-d', esc_attr( get_user_meta( $user->ID, 'birth_date', true) ) ); ?>">
	                <p class="description">
	                    <?php _e( 'Please enter your birth date', 'iusetvis' ); ?>
	                </p>
	            </td>
	        </tr>

	        <tr>
	            <th>
	                <label for="forum"><?php _e( 'Forum', 'iusetvis' ); ?></label>
	            </th>
	            <td>
	                <input type="text"
	                       class="regular-text ltr"
	                       name="forum"
	                       value="<?= esc_attr(get_user_meta($user->ID, 'forum', true)); ?>">
	                <p class="description">
	                    <?php _e( 'Please enter your forum', 'iusetvis' ); ?>
	                </p>
	            </td>
	        </tr>

	        <tr>
	            <th>
	                <label for="address"><?php _e( 'Address', 'iusetvis' ); ?></label>
	            </th>
	            <td>
	                <input type="text"
	                       class="regular-text ltr"
	                       name="address"
	                       value="<?= esc_attr(get_user_meta($user->ID, 'address', true)); ?>">
	                <p class="description">
	                    <?php _e( 'Please enter your address', 'iusetvis' ); ?>
	                </p>
	            </td>
	        </tr>

	        <tr>
	            <th>
	                <label for="phone"><?php _e( 'Phone number', 'iusetvis' ); ?></label>
	            </th>
	            <td>
	                <input type="tel"
	                       class="regular-text ltr"
	                       name="phone"
	                       value="<?= esc_attr(get_user_meta($user->ID, 'phone', true)); ?>">
	                <p class="description">
	                    <?php _e( 'Please enter your telephone number', 'iusetvis' ); ?>
	                </p>
	            </td>
	        </tr>

	    </table>
	    <?php
	}

	/**
	 * The title user meta save action.
	 *
	 * @param $user_id int the ID of the current user.
	 *
	 * @return bool Meta ID if the key didn't exist, true on successful update, false on failure.
	 */
	function usermeta_form_field_title_update($user_id)
	{
	    // check that the current user have the capability to edit the $user_id
	    if (!current_user_can('edit_user', $user_id)) {
	        return false;
	    }
	 
	    // create/update user meta for the $user_id
	    return update_user_meta( $user_id, 'title', $_POST['title'] );
	}

	/**
	 * The birth place user meta save action.
	 *
	 * @param $user_id int the ID of the current user.
	 *
	 * @return bool Meta ID if the key didn't exist, true on successful update, false on failure.
	 */
	function usermeta_form_field_birth_place_update($user_id)
	{
	    // check that the current user have the capability to edit the $user_id
	    if (!current_user_can('edit_user', $user_id)) {
	        return false;
	    }
	 
	    // create/update user meta for the $user_id
	    return update_user_meta( $user_id, 'birth_place', $_POST['birth_place'] );
	}

	/**
	 * The birth date user meta save action.
	 *
	 * @param $user_id int the ID of the current user.
	 *
	 * @return bool Meta ID if the key didn't exist, true on successful update, false on failure.
	 */
	function usermeta_form_field_birth_date_update($user_id)
	{
	    // check that the current user have the capability to edit the $user_id
	    if (!current_user_can('list_users')) {
	        return false;
	    }
	 
	    // create/update user meta for the $user_id
	    return update_user_meta( $user_id, 'birth_date', strtotime( $_POST['birth_date'] ) );
	}

	/**
	 * The address and phone number user meta save action.
	 *
	 * @param $user_id int the ID of the current user.
	 *
	 * @return bool Meta ID if the key didn't exist, true on successful update, false on failure.
	 */
	function usermeta_form_field_address_update($user_id)
	{
	    // check that the current user have the capability to edit the $user_id
	    if (!current_user_can('edit_user', $user_id)) {
	        return false;
	    }
	 
	    // create/update user meta for the $user_id
	    return update_user_meta( $user_id, 'address', $_POST['address'] );
	}

	/**
	 * The phone number user meta save action.
	 *
	 * @param $user_id int the ID of the current user.
	 *
	 * @return bool Meta ID if the key didn't exist, true on successful update, false on failure.
	 */
	function usermeta_form_field_phone_update($user_id)
	{
	    // check that the current user have the capability to edit the $user_id
	    if (!current_user_can('edit_user', $user_id)) {
	        return false;
	    }
	 
	    // create/update user meta for the $user_id
	    return update_user_meta( $user_id, 'phone', $_POST['phone'] );
	}

	/**
	 * The forum number user meta save action.
	 *
	 * @param $user_id int the ID of the current user.
	 *
	 * @return bool Meta ID if the key didn't exist, true on successful update, false on failure.
	 */
	function usermeta_form_field_forum_update($user_id)
	{
	    // check that the current user have the capability to edit the $user_id
	    if (!current_user_can('edit_user', $user_id)) {
	        return false;
	    }
	 
	    // create/update user meta for the $user_id
	    return update_user_meta( $user_id, 'forum', $_POST['forum'] );
	}

	/**
	 * The fiscal code user meta field on the editing screens.
	 *
	 * @param $user WP_User user object
	 */
	function usermeta_form_field_codice_fiscale($user)
	{
	    ?>
	    <h3><?php _e( 'Fiscal data', 'iusetvis' ); ?></h3>
	    <table class="form-table">

	        <tr>
	            <th>
	                <label for="fiscal_code"><?php _e( 'Fiscal code', 'iusetvis' ); ?></label>
	            </th>
	            <td>
	                <input type="text"
	                       class="regular-text ltr"
	                       name="fiscal_code"
	                       value="<?= esc_attr(get_user_meta($user->ID, 'fiscal_code', true)); ?>">
	                <p class="description">
	                    <?php _e( 'Please enter your fiscal code', 'iusetvis' ); ?>
	                </p>
	            </td>
	        </tr>

	        <tr>
	            <th>
	                <label for="vat"><?php _e( 'VAT number', 'iusetvis' ); ?></label>
	            </th>
	            <td>
	                <input type="tel"
	                       class="regular-text ltr"
	                       name="vat"
	                       value="<?= esc_attr(get_user_meta($user->ID, 'vat', true)); ?>">
	                <p class="description">
	                    <?php _e( 'Please enter your VAT number', 'iusetvis' ); ?>
	                </p>
	            </td>
	        </tr>

	    </table>
	    <?php
	}
	 
	/**
	 * The fiscal code user meta save action.
	 *
	 * @param $user_id int the ID of the current user.
	 *
	 * @return bool Meta ID if the key didn't exist, true on successful update, false on failure.
	 */
	function usermeta_form_field_vat_number_update($user_id)
	{
	    // check that the current user have the capability to edit the $user_id
	    if (!current_user_can('edit_user', $user_id)) {
	        return false;
	    }
	 
	    // create/update user meta for the $user_id
	    return update_user_meta( $user_id, 'vat', $_POST['vat'] );
	}


	/**
	 * The fiscal code user meta save action.
	 *
	 * @param $user_id int the ID of the current user.
	 *
	 * @return bool Meta ID if the key didn't exist, true on successful update, false on failure.
	 */
	function usermeta_form_field_codice_fiscale_update($user_id)
	{
	    // check that the current user have the capability to edit the $user_id
	    if (!current_user_can('edit_user', $user_id)) {
	        return false;
	    }
	 
	    // create/update user meta for the $user_id
	    return update_user_meta( $user_id, 'fiscal_code', $_POST['fiscal_code'] );
	}

	/**
	 * The association user meta field on the editing screens.
	 *
	 * @param $user WP_User user object
	 */
	function usermeta_form_field_association($user)
	{
	    ?>
	    <h3><?php _e( 'Association info', 'iusetvis' ); ?></h3>
	    <table class="form-table">

	    	<tr>
	            <th scope="row"><?php _e( 'Associated', 'iusetvis' ); ?></th>
	            <td>
	            	<label for="association_state">
		            	<input 	id='association_state'
		            			type="checkbox"
		            			name="association_state"
		            			<?php if ( !current_user_can( 'list_users' ) ) { echo 'readonly'; } ?>
		            			value="true"
		            			<?php if ( get_user_meta( $user->ID, 'association_state', true ) == '1' ) { echo('checked="checked"'); } ?> >
	                    <?php _e( 'Is the user associated?', 'iusetvis' ); ?>
	                </label>
	            </td>
	        </tr>

	        <tr id='association_end_row'>
	            <th>
	                <label for="association_end"><?php _e( 'Association end', 'iusetvis' ); ?></label>
	            </th>
	            <td>
	            	<input 	id="association_end"
	            			type="date"
	            			name="association_end"
	            			class="regular-text ltr"
	            			<?php if ( !current_user_can( 'list_users' ) ) { echo 'readonly'; } ?>
	            			value="<?= date( 'Y-m-d', esc_attr(get_user_meta($user->ID, 'association_end', true)) ); ?>">
	                <p class="description">
	                    <?php _e( 'The date until which the user is associated', 'iusetvis' ); ?>
	                </p>
	            </td>
	        </tr>

	    </table>
	    <?php
	}
	 
	/**
	 * The association end user meta save action.
	 *
	 * @param $user_id int the ID of the current user.
	 *
	 * @return bool Meta ID if the key didn't exist, true on successful update, false on failure.
	 */
	function usermeta_form_field_association_end_update($user_id)
	{
	    // check that the current user have the capability to edit the $user_id
	    if (!current_user_can('list_users')) {
	        return false;
	    }
	 
	    // create/update user meta for the $user_id
	    return update_user_meta( $user_id, 'association_end', strtotime( $_POST['association_end'] ) + 86399 );
	}

	/**
	 * The association state user meta save action.
	 *
	 * @param $user_id int the ID of the current user.
	 *
	 * @return bool Meta ID if the key didn't exist, true on successful update, false on failure.
	 */
	function usermeta_form_field_association_state_update($user_id)
	{
	    // check that the current user have the capability to edit the $user_id
	    if (!current_user_can('list_users')) {
	        return false;
	    }
	 
	    // create/update user meta for the $user_id
	    if ( isset( $_POST['association_state'] ) && $_POST['association_state'] == true ) {
	    	return update_user_meta( $user_id, 'association_state', true );
	    }
	    else {
	    	return update_user_meta( $user_id, 'association_state', '0' );
	    }
	}

}
