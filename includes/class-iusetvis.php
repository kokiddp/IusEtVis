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
	public $taxonomies = array( 'course-category', 'course-tag' );

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

		// Require the new class (change to your correct path)
		if ( ! class_exists( 'Gamajo_Dashboard_Glancer' ) ) {
		    require plugin_dir_path( dirname( __FILE__ ) ). 'includes/class-gamajo-dashboard-glancer.php';
		}

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

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'add_menu_pages' );
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'add_role_and_capabilities' );
		$this->loader->add_action( 'dashboard_glance_items', $this, 'add_glance_counts' );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_common = new Iusetvis_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

	}

	/**
	 * Register all of the hooks related both to the public-facing and to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_common_hooks() {

		$this->loader->add_action( 'init', $this, 'register_post_type', 0 );
		$this->loader->add_action( 'init', $this, 'register_taxonomy_category', 0 );
		$this->loader->add_action( 'init', $this, 'register_taxonomy_tag', 0 );

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
			'excerpt',
			'comments'
		);

		$args = array(
			'labels'          => $labels,
			'supports'        => $supports,
			'public'          => true,
			'capability_type' => 'post',
			'rewrite'         => array( 'slug' => 'course', ), // Permalinks format
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
			'rewrite'           => array( 'slug' => 'course-category' ),
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
			'all_items'                  => __( 'All Course Categories', 'iusetvis' ),
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
			'rewrite'           => array( 'slug' => 'course-tag' ),
			'show_admin_column' => true,
			'query_var'         => true,
		);

		//filter for altering the args
		$args = apply_filters( 'course_tag_taxonomy_args', $args );

		register_taxonomy( $this->taxonomies[1], $this->post_type, $args );
	}

	/**
	 * Add counts to "At a Glance" dashboard widget in WP 3.8+
	 *
	 */
	public function add_glance_counts() {
		$glancer = new Gamajo_Dashboard_Glancer;
    	$glancer->add( $this->post_type, array( 'publish', 'pending' ) ); // show only published "course" entries
	}

}
