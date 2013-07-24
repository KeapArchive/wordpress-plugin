<?php

class Infusionsoft_Settings {
	public function __construct() {
		add_action( 'admin_init', array( $this, 'init' ) );
		add_action( 'admin_menu', array( $this, 'menu' ) );
	}

	/**
	 * Sets up the plugin by adding the settings link on the GF Settings page
	 */
	public function init() {
		register_setting( 'infusionsoft_settings', 'infusionsoft_settings', array( $this, 'validate' ) );

		add_settings_section( 'infusionsoft_api', 'API Settings', array( $this, 'api_settings' ), 'infusionsoftwp' );
		add_settings_field( 'subdomain', 'Subdomain', array( $this, 'subdomain' ), 'infusionsoftwp', 'infusionsoft_api' );
		add_settings_field( 'api_key', 'API Key', array( $this, 'api_key' ), 'infusionsoftwp', 'infusionsoft_api' );

		if ( ! is_plugin_active( 'infusionsoft/infusionsoft.php' ) ) {
			add_settings_section( 'infusionsoft_integration', 'Integration Settings', array( $this, 'integration_settings' ), 'infusionsoftwp' );
			add_settings_field( 'gf_integration', 'Gravity Forms', array( $this, 'integration_gf' ), 'infusionsoftwp', 'infusionsoft_integration' );
		}
	}

	/**
	 * Adds a link to the Infusionsoft to the Settings menu
	 */
	public function menu() {
		add_options_page( 'Infusionsoft', 'Infusionsoft', 'manage_options', 'infusionsoft', array( $this, 'settings_page' ) );
	}

	/**
	 * Callback for the API settings section, which is left blank
	 */
	public function api_settings() { }

	/**
	 * Displays the subdomain settings field
	 */
	public function subdomain() {
		$settings = (array) get_option( 'infusionsoft_settings' );
		if ( isset( $settings['subdomain'] ) ) {
			$subdomain = $settings['subdomain'];
		} else {
			$subdomain = null;
		}
		echo '<input type="text" size="40" name="infusionsoft_settings[subdomain]" value="' . esc_attr( $subdomain ) . '" />';
	}

	/**
	 * Displays the API key settings field
	 */
	public function api_key() {
		$settings = (array) get_option( 'infusionsoft_settings' );
		if ( isset( $settings['api_key'] ) ) {
			$api_key = $settings['api_key'];
		} else {
			$api_key = null;
		}
		echo '<input type="text" size="40" name="infusionsoft_settings[api_key]" value="' . esc_attr( $api_key ) . '" />';
	}

	/**
	 * Callback for the integration settings section, which is left blank
	 */
	public function integration_settings() { }

	/**
	 * Displays the checkbox for Gravity Forms integration
	 */
	public function integration_gf() {
		$settings = (array) get_option( 'infusionsoft_settings' );
		$checked = ( isset( $settings['gf_integration'] ) && $settings['gf_integration'] == '1' ) ? 'checked="checked"' : '';
		echo '<input type="checkbox" name="infusionsoft_settings[gf_integration]" value="1" ' . $checked . ' />';
	}

	/**
	 * Validates the user input
	 * @param  array $input POST data
	 * @return array        Sanitized POST data
	 */
	public function validate( $input ) {
		$input['subdomain'] = esc_html( $input['subdomain'] );
		$input['api_key'] = esc_html( $input['api_key'] );
		$input['gf_integration'] = ( isset( $input['gf_integration'] ) && $input['gf_integration'] == 1 );
		return $input;
	}

	/**
	 * Output the main settings page with the title and form
	 */
	public function settings_page() {
		?>
		<div class="wrap">
			<div id="icon-options-general" class="icon32"><br></div>
			<h2>Infusionsoft Developer</h2>
			<form method="post" action="options.php">
				<?php settings_fields( 'infusionsoft_settings' ); ?>
				<?php do_settings_sections( 'infusionsoftwp' ); ?>
				<?php submit_button(); ?>
			</form>
		</div>
		<?php
	}
}

$infusionsoft_settings = new Infusionsoft_Settings;