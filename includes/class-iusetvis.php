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

		// metaboxes
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

	}

	/**
	 * Register all of the hooks related both to the public-facing and to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_common_hooks() {

		// post type and taxonomies
		$this->loader->add_action( 'init', $this, 'register_post_type', 0 );
		$this->loader->add_action( 'init', $this, 'register_taxonomy_category', 0 );
		$this->loader->add_action( 'init', $this, 'register_taxonomy_tag', 0 );
		$this->loader->add_action( 'init', $this, 'register_taxonomy_location', 0 );

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
		$course_subs_dead_end = ! isset( $meta['course_subs_dead_end'][0] ) ? '' : $meta['course_subs_dead_end'][0];

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
					<label for="course_subs_dead_end" style="font-weight: bold;"><?php _e( 'Subscriptions dead end Time and Date', 'iusetvis' ); ?>
					</label>
				</td>
				<td colspan="4">
					<input type="datetime-local" name="course_subs_dead_end" class="regular-text" value="<?php echo date( 'Y-m-d\TH:i', $course_subs_dead_end ); ?>">
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
		$meta['course_subs_dead_end'] = ( isset( $_POST['course_subs_dead_end'] ) ? strtotime( esc_textarea( $_POST['course_subs_dead_end'] ) ) : '' );

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
					<input type="text" name="course_price_assoc" class="regular-text" value="<?php echo $course_price_assoc; ?>">
					<p class="description"><?php _e( 'Example: 10€', 'iusetvis' ); ?></p>
				</td>
			</tr>

			<tr>
				<td class="course_meta_box_td" colspan="1">
					<label for="course_price_reg" style="font-weight: bold;"><?php _e( 'Regular price', 'iusetvis' ); ?>
					</label>
				</td>
				<td colspan="4">
					<input type="text" name="course_price_reg" class="regular-text" value="<?php echo $course_price_reg; ?>">
					<p class="description"><?php _e( 'Example: 25€', 'iusetvis' ); ?></p>
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
					<input type="text" name="course_rel_title_<?php echo $i ?>" class="regular-text" value="<?php echo $course_rel_title[$i]; ?>">
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

}
