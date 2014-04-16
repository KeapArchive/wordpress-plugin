# Infusionsoft WordPress Plugin

*BEFORE YOU CONTINUE:* This plugin is primarily designed for developers, and only provides a basic feature set for the average WordPress user. 

*IF YOU ARE AN INFUSIONSOFT & WORDPRESS USER:* If your plugin says that you'll have Infusionsoft functionality enabled after installing this plugin, please do so, and then set your Infusionsoft API credentials in WordPress settings. You're done!

*IF YOU ARE LOOKING FOR INFUSIONSOFT PLUGINS:* There is probably a better plugin for day to day use than this one. Some include:
- Infusionsoft + Gravity Forms Integration: http://wordpress.org/plugins/infusionsoft/
- Infusionsoft Web Tracking Code: http://wordpress.org/plugins/infusionsoft-web-tracking-code/ or http://wordpress.org/extend/plugins/infusionsoft-web-tracker/
- Infusionsoft Affiliates: http://wordpress.org/plugins/infusionsoft-affiliates/
- Infusionsoft Web Forms: http://wordpress.org/plugins/novak-solutions-javascript-infusionsoft-webform-plugin/

*IF YOU ARE A DEVELOPER:* We hope this plugin can be useful and make integration with the Infusionsoft API a little bit easier. This plugin is designed for you to disassemble - take part or all of it for your implementations and integrate Infusionsoft functionality into your plugin, or instruct your users to install this and use the Infusionsoft global object as defined below.

We do ask that you follow the GPL and give credit where credit is due.


## Using the Infusionsoft API

A global `$infusionsoft` variable is available from the moment the plugin is loaded. This means you can access the Infusionsoft plugin any time after the `plugins_loaded` action is fire, including `init`.

If there is an error with your request (either a missing setting field or a bad request to Infusionsoft), the plugin will return a `WP_Error` object with a message of what went wrong. This allows you to easily check for any errors using `is_wp_error()` and using any of the helper functions for getting the message. Read more about `WP_Error` [here](http://codex.wordpress.org/Class_Reference/WP_Error).

### Formatting Requests

Requests using the `$infusionsoft` object are formatted and then submitted directly to the Infusionsoft API. A request is structured by using the service name as the function (no uppercase first letter), the method as the first argument, and an array of data for the arguments.

For example, to use the [AffiliateService.affSummary](http://help.infusionsoft.com/api-docs/affiliateservice#affSummary) method, the following call would be made:

	$infusionsoft->affiliate( 'affSummary', array(
		'affiliateId' => array( 123, 234, 345 ),
		'filterStartDate' => '2012-12-01',
		'filterEndDate' => '2012-12-31'
	) );

For the [AffiliateProgramService.getAffiliatesByProgram](http://help.infusionsoft.com/api-docs/affiliateprogramservice#getAffiliateByProgram) request, the call would be:

	$infusionsoft->affiliateProgram( 'getAffiliatesByProgram', array( 'programId' => 123 ) );

Note that only the first letter is shifted from uppercase to lowercase.

### Sample Request

	// Load the Infusionsoft API class
	global $infusionsoft;

	// Add a contact using the ContactService.add method
	$contact = $infusionsoft->contact( 'add', array( 'Email' => 'email@example.com' ) );

	// Check whether or not the API returned an error
	if ( ! is_wp_error( $contact ) ) {
		// The $contact variable contains the return value as specified in the documentation
		echo 'The new contact has the ID: ' . $contact;
	} else {
		// There was an error, which uses the WP_Error class
		echo 'There was an error! Message: ' . $contact->get_error_message();
	}

## Gravity Forms Integration
This plugin has basic integration with Gravity Forms built in, primarily to demonstrate functionality with another plugin. This functionality will be turned off if the Infusionsoft Gravity Forms plugin by Zach Katz is also installed.
