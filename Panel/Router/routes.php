<?php
	$controllerName = \lcd344\Mailer\Panel\Controllers\MailerController::class; //controller class

	return [
		// This route is needed to make the text area form field work - this took a while to figure out. you need to extend any route that will include a text area with it.
		[
			'pattern' => 'mailer/field/(:any)/(:all)/(:all)',
			'action' => 'fields', // function name on class
			'controller' => $controllerName, // class name
			'filter' => 'auth',
			'method' => 'GET|POST'
		],
		// register any other routes you need
		[
			'pattern' => 'mailer',
			'action' => 'index', // function name on class
			'controller' => $controllerName, // class name
			'method' => 'GET|POST',
			'filter' => ['auth']
		]
	]

?>