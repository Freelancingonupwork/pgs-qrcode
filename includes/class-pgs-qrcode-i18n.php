<?php
/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       http://www.potenzaglobalsolutions.com/
 * @since      1.0.0
 *
 * @package    Pgs_Qrcode
 * @subpackage Pgs_Qrcode/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Pgs_Qrcode
 * @subpackage Pgs_Qrcode/includes
 * @author    Potenza Global Solutions
 */
class Pgs_Qrcode_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'pgs-qrcode',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
