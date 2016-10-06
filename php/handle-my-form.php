<?php

add_filter( 'wpak_live_query', 'handle_my_form', 10, 2 );

/**
 * Handle form data posted with App.liveQuery on app side.
 * The corresponding test form's HTML is defined in page.html template (form#my-form).
 * 
 * @param array $service_answer This is what is sent back to the app once form data has been processed
 * @param array $query_params Form data that we passed in "live_query_args" when calling App.liveQuery() on app side
 * @return array $service_answer Our answer to the app
 */
function handle_my_form( $service_answer, $query_params ) {

	//Check if we're doing the "submit-my-form" action:
	if ( isset( $query_params[ 'my_action' ] ) && $query_params[ 'my_action' ] === 'submit-my-form' ) {

		//Prepare your custom answer:
		//Note: $service_answer already contains some data reserved for core usage,
		//so it is advised to return your form result in your own key (here 'my_form_result'):
		$service_answer[ 'my_form_result' ] = array( 'ok' => 0, 'message' => '', 'data' => array() );

		//Check that sent name is not empty:
		$name = trim( $query_params[ 'name' ] );
		if ( empty( $name ) ) {
			$service_answer[ 'my_form_result' ][ 'message' ] = 'Please provide a name';
			return $service_answer;
		}

		//Check that email is well formatted:
		$email = trim( $query_params[ 'email' ] );
		if ( !filter_var( $email, FILTER_VALIDATE_EMAIL ) ) {
			$service_answer[ 'my_form_result' ][ 'message' ] = 'Please provide a valid email';
			return $service_answer;
		}

		//... Do something with your form data here ...
		//... For example save name and email to database, register email for a newsletter, etc ...
		
		//Just to show that you can return anything in result of your live query,
		//let's return name and email in our answer:
		$service_answer[ 'my_form_result' ][ 'data' ] = array( 'name_received' => $name, 'email_received' => $email );


		//Everything went fine, raise the green flag:
		$service_answer[ 'my_form_result' ][ 'ok' ] = 1;
	}

	return $service_answer;
}
