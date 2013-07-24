<?php

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