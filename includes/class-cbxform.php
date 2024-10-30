<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       http://codeboxr.com/
 * @since      1.0.0
 *
 * @package    Cbxform
 * @subpackage Cbxform/includes
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
 * @package    Cbxform
 * @subpackage Cbxform/includes
 * @author     http://codeboxr.com/ <info@codeboxr.com>
 */
class Cbxform {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Cbxform_Loader    $loader    Maintains and registers all hooks for the plugin.
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
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

		$this->plugin_name = 'cbxform';
		$this->version = '1.0.0';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Cbxform_Loader. Orchestrates the hooks of the plugin.
	 * - Cbxform_i18n. Defines internationalization functionality.
	 * - Cbxform_Admin. Defines all hooks for the admin area.
	 * - Cbxform_Public. Defines all hooks for the public side of the site.
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
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-cbxform-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-cbxform-i18n.php';
                
		/**
		 * Load Class for meta settings class.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-cbxformmeta-settings.php';
                
		/**
		 * Load Class for settings class.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-cbxform-setting.php';
                
		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-cbxform-admin.php';

		/**
		 * Widgets
		 * of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'widgets/single/cbxformsingle-widget.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-cbxform-public.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/Html2Text.php';
		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/Html2TextException.php';
		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/emogrifier.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-emailtemplate.php';

          
		$this->loader = new Cbxform_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Cbxform_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Cbxform_i18n();

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

		$plugin_admin = new Cbxform_Admin( $this->get_plugin_name(), $this->get_version() );

		//add metabox for custom post type cbxfeedbackform && cbxfeedbackbtn
		$this->loader->add_action('add_meta_boxes', $plugin_admin, 'add_meta_boxes_form');

		//adding the setting action
		$this->loader->add_action('admin_init', $plugin_admin, 'setting_init');
		//add new post type cbxform
		$this->loader->add_action('init', $plugin_admin, 'create_form', 0);
                
		//Add admin menu action hook
		$this->loader->add_action('admin_menu', $plugin_admin, 'add_plugin_admin_menu');

        //add global currency setting sections
        $this->loader->add_filter('cbxform_setting_sections', $plugin_admin, 'add_currency_setting_section');


		//add global currency setting fields
        //$this->loader->add_action('cbxform_setting_fields', $plugin_admin, 'add_currency_setting_fields');

        $this->loader->add_action('cbxform_global_cbxform_currency_fields', $plugin_admin, 'add_currency_setting_fields');

		$this->loader->add_action('wp_ajax_cbxform_enable_disable', $plugin_admin, 'cbxform_enable_disable');
		$this->loader->add_action('wp_ajax_cbxform_import_singleform', $plugin_admin, 'cbxform_import_singleform');
		$this->loader->add_action('wp_ajax_cbxform_import_multipleform', $plugin_admin, 'cbxform_import_multipleform_modal');
		$this->loader->add_action('wp_ajax_plupload_action', $plugin_admin ,'cbxform_import_multipleform_process' );

		//add metabox for feedback button
		$this->loader->add_action('save_post', $plugin_admin, 'save_post_form', 10, 2);
                
		//custom column header in listing for forms
		$this->loader->add_filter('manage_cbxform_posts_columns', $plugin_admin, 'columns_header');
		$this->loader->add_action('manage_cbxform_posts_custom_column', $plugin_admin, 'custom_column_row', 10, 2);
		$this->loader->add_filter('manage_edit-cbxform_sortable_columns', $plugin_admin, 'custom_column_sortable');

		$this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

		// admin actions/filters
		$this->loader->add_action('admin_footer-edit.php', $plugin_admin, 'bulk_admin_footer');
		$this->loader->add_action('load-edit.php',         $plugin_admin, 'bulk_action');
		$this->loader->add_action('admin_notices',         $plugin_admin, 'bulk_admin_notices');


	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Cbxform_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
		$this->loader->add_action( 'template_redirect', $plugin_public, 'process_form');
		$this->loader->add_filter( 'wp_mail_content_type', $plugin_public, 'cbxform_mail_content_type');

		$this->loader->add_action( 'wp_ajax_process_form', $plugin_public, 'process_form');
		$this->loader->add_action( 'wp_ajax_nopriv_process_form', $plugin_public, 'process_form');
		$this->loader->add_action( 'init', $plugin_public, 'init');
		$this->loader->add_filter( 'onCBXFormValidationComplete',$plugin_public,'onCBXFormValidationComplete', 10,2);

		//widget
		$this->loader->add_action('widgets_init', $plugin_public, 'register_widget');

		add_shortcode('cbxform',array($plugin_public,'cbxform_shortcode'));

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
	 * @return    Cbxform_Loader    Orchestrates the hooks of the plugin.
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

}
