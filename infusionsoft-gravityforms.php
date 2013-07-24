<?php

class Infusionsoft_GravityForms {
	public function __construct() {
		add_action( 'admin_init', array( $this, 'admin_init' ) );
	}

	/**
	 * Sets up the Gravity Forms form editor with the proper javascript and hooks
	 */
	public function admin_init() {
		add_action( 'gform_editor_js', array( $this, 'editor_script' ) );
		add_action( 'gform_advanced_settings', array( $this, 'advanced_settings' ), 10, 2 );
		add_action( 'gform_field_advanced_settings', array( $this, 'field_advanced_settings' ), 10, 2 );
		add_filter( 'gform_tooltips', array( $this, 'form_tooltips' ) );
	}

	/**
	 * Show helpful tooltips explaining what each field does
	 */
	public function form_tooltips( $tooltips ) {
		$tooltips['field_infusionsoft'] = '<h6>Infusionsoft Field</h6>Choose the field in Infusionsoft that this field should populate.';
		$tooltips['form_infusionsoft_service'] = '<h6>Infusionsoft Service</h6>Choose the service which should receive this form data.';
		$tooltips['form_infusionsoft_method'] = '<h6>Infusionsoft Field</h6>Choose the method that the API should call to insert the data.';
		return $tooltips;
	}

	/**
	 * Output the service and method settings in the 'Advanced' tab for the form
	 */
	public function advanced_settings( $position, $form_id ) {
		if ( $position == 800 ) {
			?>
			<li class="infusionsoft_service">
				<label for="form_infusionsoft_service" style="display: block;">
					Infusionsoft Service
					<?php gform_tooltip( 'form_infusionsoft_service' ); ?>
				</label>
				<select id="form_infusionsoft_service" onchange="SetFormProperty('form_infusionsoft_service', this.options[this.selectedIndex].value);">
					<option value="">Select a service...</option>
					<option value="affiliate">AffiliateService</option>
					<option value="affiliateProgram">AffiliateProgramService</option>
					<option value="contact">ContactService</option>
					<option value="data">DataService</option>
					<option value="discount">DiscountService</option>
					<option value="email">EmailService</option>
					<option value="file">FileService</option>
					<option value="funnel">FunnelService</option>
					<option value="invoice">InvoiceService</option>
					<option value="order">OrderService</option>
					<option value="product">ProductService</option>
					<option value="search">SearchService</option>
					<option value="shipping">ShippingService</option>
					<option value="webForm">WebFormService</option>
				</select>
			</li>
			<li class="infusionsoft_method">
				<label for="form_infusionsoft_method" style="display: block;">
					Infusionsoft Method
					<?php gform_tooltip( 'form_infusionsoft_method' ); ?>
				</label>
				<input type="text" size="30" id="form_infusionsoft_method" onchange="SetFormProperty('form_infusionsoft_method', this.value);" />
			</li>
			<?php
		}
	}

	/**
	 * Output the field setting in the 'Advanced' tab for each field
	 */
	public function field_advanced_settings( $position, $form_id ) {
		if ( $position == 550 ) {
			?>
			<li class="infusionsoft_field field_setting" style="display: list-item;">
				<label for="field_infusionsoft">
					Infusionsoft Field
					<?php gform_tooltip( 'field_infusionsoft' ); ?>
				</label>
				<input type="text" size="30" id="field_infusionsoft" onchange="SetFieldProperty('infusionsoft_field', this.value);" />
			</li>
			<?php
		}
	}

	/**
	 * Output JavaScript on the page to set up and manipulate Gravity Form objects
	 */
	public function editor_script(){
		?>
		<script>
		function SetFormProperty(name, value) {
			if (value) {
				form[name] = value;
			}
		}

		// Adding setting to fields of type "text"
		jQuery(document).ready(function($) {
			$.each(fieldSettings, function(index, value) {
				fieldSettings[index] += ',.infusionsoft_field';
			});
		});

		// Binding to the load field settings event to initialize the checkbox
		jQuery(document).bind('gform_load_field_settings', function(event, field, form){
			jQuery('#field_infusionsoft').val(field['infusionsoft_field']);
		});

		// Auto set the service and method inputs
		jQuery(document).bind('gform_load_form_settings', function(event, form){
			jQuery('#form_infusionsoft_service').val(form['form_infusionsoft_service']);
			jQuery('#form_infusionsoft_method').val(form['form_infusionsoft_method']);
		});
		</script>
		<?php
	}
}