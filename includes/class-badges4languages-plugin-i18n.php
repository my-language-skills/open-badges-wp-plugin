<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       http://www.badges4languages.org
 * @since      1.0.0
 *
 * @package    Badges4languages_Plugin
 * @subpackage Badges4languages_Plugin/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Badges4languages_Plugin
 * @subpackage Badges4languages_Plugin/includes
 * @author     Alexandre Levacher <alexandre.levacher@insa-rouen.fr>
 */
class Badges4languages_Plugin_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'badges4languages-plugin',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
