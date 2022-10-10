<?php
/**
 * The core plugin class.
 *
 * This is used to define attributes, functions, internationalization used across
 * both the admin-specific hooks, and public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @link       http://devrix.com
 * @since      1.0.0
 *
 * @package    DX_Students
 * @subpackage DX_Students/includes/classes
 * @author     DevriX <contact@devrix.com>
 */

namespace DXS;

class DX_Students {

	/**
	 * The loader that's responsible for maintaining and registering all hooks
	 * that power the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Plugin_Name_Loader    $loader    Maintains and registers all hooks for the plugin.
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
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		$this->plugin_name = 'dxs';

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
	 * - Plugin_Name_Loader. Orchestrates the hooks of the plugin.
	 * - Plugin_Name_i18n. Defines internationalization functionality.
	 * - Plugin_Name_Admin. Defines all hooks for the admin area.
	 * - Plugin_Name_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		if ( function_exists( 'spl_autoload_register' ) ) {
			spl_autoload_register(
				function( $class ) {
					$class      = str_replace( '_', '-', strtolower( $class ) );
					$path_array = explode( '\\', $class );

					if ( $this->plugin_name === $path_array[0] ) {
						array_shift( $path_array );
						$index                = count( $path_array ) - 1;
						$path_array[ $index ] = 'class-' . $path_array[ $index ];
						$class_path           = implode( DIRECTORY_SEPARATOR, $path_array );
						$classpath            = DXS_DIR . 'includes/classes' . DIRECTORY_SEPARATOR . $class_path . '.php';

						if ( file_exists( $classpath ) ) {
							include_once $classpath;
						}
					}
				}
			);
			// Run the loader.
			$this->loader = new Loader();
		}

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Plugin_Name_I18n class in order to set the domain and to
	 * register the hook with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new i18n();

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

		$plugin_admin = new Admin( $this->get_plugin_name() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		$plugin_students = new Students();
		$this->loader->add_action( 'add_meta_boxes', $plugin_students, 'add_meta_boxes' );
		$this->loader->add_action( 'save_post', $plugin_students, 'save_meta_boxes', 10, 2 );
		$this->loader->add_action( 'admin_init', $plugin_students, 'register_settings' );
		$this->loader->add_action( 'admin_menu', $plugin_students, 'students_option_page' );
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		// Instantiates a new object of the class Plugin_Name_Public.
		$plugin_public = new Common( $this->get_plugin_name() );
		// This is where the loader's add_action() hooks the callback function of the class object.
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		// Another action is passed to the class object.
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
		//Creating instance of Students CPT class
		$plugin_students = new Students();
		//Registering Students CPT
		$this->loader->add_action( 'init', $plugin_students, 'register_students_type' );
		//Including theme files from plugin
		$this->loader->add_filter( 'template_include', $plugin_students, 'students_template');
		//Modifying archive query
		$this->loader->add_action( 'pre_get_posts', $plugin_students, 'students_template' );
		//REST API Hook
		$this->loader->add_action( 'rest_api_init', $plugin_students, 'students_register_rest_routes' );

		//Basic Auth
		$this->loader->add_filter( 'determine_current_user', $plugin_students, 'json_basic_auth_handler' );
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
	 * @return    Plugin_Name_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}
}
