<?php
/**
 * The public functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @link       http://devrix.com
 * @since      1.0.0
 *
 * @package    DX_Students
 * @subpackage DX_Students/includes/classes
 * @author     DevriX <contact@devrix.com>
 */
namespace DXS;

class Common {

	/**
	 * The ID of this plugin.
	 *
	 * @since  1.0.0
	 * @access private
	 * @var    string $plugin_name The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since 1.0.0
	 * @param string $plugin_name The name of the plugin.
	 */
	public function __construct( $plugin_name ) {

		$this->plugin_name = $plugin_name;
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since 1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Plugin_Name_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Plugin_Name_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, DXS_URL . 'assets/dist/public/css/master.css', array(), filemtime( DXS_DIR . 'assets/dist/public/css/master.css' ), 'all' );
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since 1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Plugin_Name_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Plugin_Name_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, DXS_URL . 'assets/dist/public/js/dx-students-public.min.js', array( 'jquery' ), filemtime( DXS_DIR . 'assets/dist/public/js/dx-students-public.min.js' ), false );
		wp_localize_script( $this->plugin_name, 'ajax_posts', array(
			'ajaxurl' => admin_url( 'admin-ajax.php' ),
		));
	}
}
