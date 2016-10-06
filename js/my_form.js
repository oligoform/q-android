/**
 * This is where we handle our form submission, using the "LiveQuery" webservice.
 * This my_form.js file is included in js/functions.js dependencies.
 * The corresponding test form's HTML is defined in page.html template (form#my-form).
 */
define( [ 'jquery', 'core/theme-app' ], function ( $, App ) {

	$( "#app-layout" ).on( "submit", "#my-form", function ( e ) {

		e.preventDefault();

		//Retrieve values from form:
		var name = $( "#my-form input[name='name']" ).val();
		var email = $( "#my-form input[name='email']" ).val();

		//Define your custom "Live query", that you will retrieve on server side
		//using the 'wpak_live_query' hook:
		var live_query_args = {
			my_action: 'submit-my-form',
			my_name: name,
			my_email: email
		};

		//Define live query options:
		var live_query_options = {
			auto_interpret_result: false, //This is to tell WP-AppKit that we're doing our own custom query
			success: function ( answer ) {
				var form_result = answer.my_form_result;
				if ( form_result.ok === 1 ) { //Form was handled with no error on server side
					$( '#my-form-feedback' ).html( 'Form submitted ok :) <br><small>Name: ' + form_result.data.name_received + '<br>Email: ' + form_result.data.email_received +'</small>');
				} else { //An error occured while handling the form on server side: display the error message:
					$( '#my-form-feedback' ).html( 'Form error: <br>' + form_result.message );
				}
			},
			error: function ( error ) {
				//This is if the web service call failed (ajax error)
				$( '#my-form-feedback' ).html( 'Ajax error' );
			}
		};

		//Send our form submission query to the server:
		App.liveQuery( live_query_args, live_query_options );

	} );

} );
