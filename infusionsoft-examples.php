<?php

class Infusionsoft_Examples {
	/**
	 * Handles the post submission and submitting any data to the appropriate service/method
	 */
	public function gravityforms_push( $validation_result ) {
		// Get the form action
		$form = $validation_result['form'];

		// Build an array of fields for the lead
		$fields = array();
		foreach ( $form['fields'] as $field ) {
			if ( isset( $field['infusionsoft_field'] ) ) {
				$fields[$field['infusionsoft_field']] = rgpost('input_' . $field['id']);
			}
		}

		// If no fields are being passed (or no email field) or the GF integration isn't loaded, exit now
		if ( empty( $fields ) OR ! class_exists( 'Infusionsoft_GravityForms' ) ) {
			return $validation_result;
		}

		// Access the $infusionsoft global and call the relevant method with the data provided
		global $infusionsoft;
		if ( isset( $form['form_infusionsoft_method'] ) ) {
			$data = array( $form['form_infusionsoft_method'], $fields );
			call_user_func_array( array( $infusionsoft, $form['form_infusionsoft_service'] ), $data );
		}

		return $validation_result;
	}

	public function user_register( $user_id ) {
		global $infusionsoft;

		// Get the user object and insert relevant data into Infusionsoft
		$user = get_userdata( $user_id );
		$data = array(
			'FirstName' => $user->user_firstname,
			'LastName' => $user->user_lastname,
			'Email' => $user->user_email,
			'Website' => $user->user_url
		);
		$contact_id = $infusionsoft->contact( 'add', $data );

		// Update the user with the contact ID so we can keep track of their Infusionsoft contact iD
		add_user_meta( $user_id, '_infusionsoft_contact_id', $contact_id );
	}

	public function user_update( $user_id ) {
		global $infusionsoft;

		// Get the contact ID from the user meta and call the register hook if they aren't in IFS
		$contact_id = get_user_meta( $user_id, '_infusionsoft_contact_id', true );
		if ( ! $contact_id) {
			$this->user_register( $user_id );
		}

		// Get the user object and update the relevant data into Infusionsoft
		$user = get_userdata( $user_id );
		$data = array(
			'FirstName' => $user->user_firstname,
			'LastName' => $user->user_lastname,
			'Email' => $user->user_email,
			'Website' => $user->user_url
		);
		$contact_id = $infusionsoft->contact( 'update', (int) $contact_id, $data );
	}
}

$infusionsoft_examples = new Infusionsoft_Examples;

add_filter( 'gform_validation', array( $infusionsoft_examples, 'gravityforms_push' ), 10, 4 );

add_action( 'user_register', array( $infusionsoft_examples, 'user_register' ) );
add_action( 'profile_update', array( $infusionsoft_examples, 'user_update' ) );
add_action( 'edit_user_profile_update', array( $infusionsoft_examples, 'user_update' ) );