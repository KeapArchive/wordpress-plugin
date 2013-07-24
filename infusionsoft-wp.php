<?php
/*
Plugin Name: Infusionsoft Developer Plugin
Plugin URI: http://infusionsoft.com/wordpress/
Description: The core application that serves Infusionsoft API hooks for developers
Version: 0.1
Author: Infusionsoft
Author URI: http://infusionsoft.com
License: GPL2
*/

global $infusionsoft;

require_once plugin_dir_path( __FILE__ ) . 'infusionsoft.php';
require_once plugin_dir_path( __FILE__ ) . 'infusionsoft-examples.php';
require_once plugin_dir_path( __FILE__ ) . 'infusionsoft-gravityforms.php';
require_once plugin_dir_path( __FILE__ ) . 'infusionsoft-settings.php';
require_once ABSPATH . 'wp-admin/includes/plugin.php';

// Load main Infusionsoft API
$settings = (array) get_option( 'infusionsoft_settings' );
if ( isset( $settings['subdomain'] ) && isset( $settings['api_key'] ) && isset( $settings['gf_integration'] ) ) {
	$infusionsoft = new Infusionsoft( $settings['subdomain'], $settings['api_key'] );

	// Make sure Infusionsoft connected
	if ( is_wp_error( $infusionsoft->error ) ) {
		$error = $infusionsoft->error->get_error_message();
		add_action( 'admin_notices', create_function( '$error', 'echo "<div class=\"error\"><p><strong>Infusionsoft Error:</strong> ' . $error . '</p></div>";' ) );
	}

}

class Infusionsoft_WP {
	/**
	 * Calls all actions and hooks used by the plugin
	 */
	public function __construct() {
		$settings = (array) get_option( 'infusionsoft_settings' );

		// Load Gravity Forms integration if enabled
		if ( isset( $settings['gf_integration'] ) && $settings['gf_integration'] && ! is_plugin_active( 'infusionsoft/infusionsoft.php' ) ) {
			$infusionsoft_gravityforms = new Infusionsoft_GravityForms;
		}
	}
}

// Start the plugin
$infusionsoft_wp = new Infusionsoft_WP;