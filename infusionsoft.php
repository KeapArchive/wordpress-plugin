<?php
/*
Plugin Name: 	Infusionsoft Developers Plugin
Plugin URI: 	http://wordpress.org/plugins/infusionsoft-for-developers/
Description: 	This plugin is primarily designed for developers adding Infusionsoft API hooks for use in WP. It only provides a basic feature set for the average WordPress user.
Version: 		0.2
Author: 		Infusionsoft
Author URI: 	http://infusionsoft.com
License: 		GPLv2 or later
License URI: 	http://www.gnu.org/licenses/gpl-2.0.html

Copyright 2014 Infusionsoft (email : info@infusionsoft.com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301 USA
*/

// Include WordPress libraries to handle XML-RPC
require_once ABSPATH . '/wp-includes/class-IXR.php';
require_once ABSPATH . '/wp-includes/class-wp-http-ixr-client.php';

class Infusionsoft {
	public $api_key;
	public $error = FALSE;
	public $subdomain;

	public function __construct( $subdomain = NULL, $api_key = NULL ) {
		$this->subdomain = $subdomain;
		$this->api_key = $api_key;

		if ( empty( $this->subdomain ) || empty( $this->api_key ) ) {
			$this->error = new WP_Error( 'invalid-request', __( 'You must provide a subdomain and API key for your Infusionsoft application.', 'infusionsoftwp' ) );
		}
	}

	public function __call( $name, $arguments ) {
		// Make sure no error already exists
		if ( $this->error ) {
			return new WP_Error( 'invalid-request', __( 'You must provide a subdomain and API key for your Infusionsoft application.', 'infusionsoftwp' ) );
		}

		// Get the full method name with the service and method
		$method = ucfirst( $name ) . 'Service' . '.' . array_shift( $arguments );
		$arguments = array_merge( array( $method, $this->api_key ), $arguments );

		// Initialize the client
		$client = new WP_HTTP_IXR_Client( 'https://' . $this->subdomain . '.infusionsoft.com/api/xmlrpc' );

		// Call the function and return any error that happens
		if ( ! call_user_func_array( array( $client, 'query' ), $arguments ) ) {
			return new WP_Error( 'invalid-request', $client->getErrorMessage() );
		}

		// Pass the response directly to the user
		return $client->getResponse();
	}
}