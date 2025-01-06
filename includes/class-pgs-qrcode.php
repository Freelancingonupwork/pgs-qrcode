<?php
/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       http://www.potenzaglobalsolutions.com/
 * @since      1.0.0
 *
 * @package    Pgs_Qrcode
 * @subpackage Pgs_Qrcode/includes
 */

use Endroid\QrCode\QrCode;


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
 * @package    Pgs_Qrcode
 * @subpackage Pgs_Qrcode/includes
 * @author    Potenza Global Solutions
 */
class Pgs_Qrcode {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Pgs_Qrcode_Loader    $loader    Maintains and registers all hooks for the plugin.
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
		if ( defined( 'PGS_QRCODE_VERSION' ) ) {
			$this->version = PGS_QRCODE_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'pgs-qrcode';

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
	 * - Pgs_Qrcode_Loader. Orchestrates the hooks of the plugin.
	 * - Pgs_Qrcode_i18n. Defines internationalization functionality.
	 * - Pgs_Qrcode_Admin. Defines all hooks for the admin area.
	 * - Pgs_Qrcode_Public. Defines all hooks for the public side of the site.
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
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-pgs-qrcode-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-pgs-qrcode-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-pgs-qrcode-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-pgs-qrcode-public.php';

		$this->loader = new Pgs_Qrcode_Loader();

		/**
		 * The class responsible for generating QR code widget
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-pgs-qrcode-widget-qrcode.php';
	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Pgs_Qrcode_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Pgs_Qrcode_i18n();

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

		$plugin_admin = new Pgs_Qrcode_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Pgs_Qrcode_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

		$this->loader->add_action( 'widgets_init', $this, 'register_widgets' );

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
	 * @return    Pgs_Qrcode_Loader    Orchestrates the hooks of the plugin.
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
	 * Register widgets
	 *
	 * @since     1.0.0
	 */
	public function register_widgets() {
		register_widget( 'Pgs_Qrcode_Widget' );
	}

	/**
	 * Retrieve the QR Code.
	 *
	 * @since     1.0.0
	 * Parameters:
	 * @param int    $size : Image size in pixels.
	 * @param string $qr_content : Content to generate QR Code.
	 * @param int    $post : Post ID if want to generate loink directly.
	 * @param string $link_type : Link type to generate QR Code based on post.
	 * @return    string    Base64c encoded QR Code image.
	 */
	public static function get_qrcode( $size = 256, $qr_content = '', $post = 0, $link_type = 'guid' ) {

		if ( empty( $qr_content ) ) {
			global $wp;
			$qr_content = home_url( $wp->request );

			// bail early if qr content is blank.
			if ( empty( $qr_content ) ) {
				return false;
			}
		}

		$qr_code = new QrCode( $qr_content );
		$qr_code->setSize( $size );
		$qr_code->setMargin( 0 );

		$output = $qr_code->writeDataUri();
		return $output;
	}

}
