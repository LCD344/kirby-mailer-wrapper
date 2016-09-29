<?php

	namespace lcd344\Mailer\Panel\Router;

	if (class_exists('panel')) {

		require __DIR__ . '/filters.php'; //import filters

		$routes = require __DIR__ . '/routes.php'; //import routes
		$router = new \Router($routes); // start router


		$router->filter('auth',authFilter()); //add filter
		$router->filter('isInstalled',isInstalledFilter());  //add filter

		$route = $router->run(kirby()->path());
		// Return if we didn't define a matching route to allow Kirby's router to process the request
		if (is_null($route)) return;
		// Call the route

		\lcd344\Mailer\Panel\Helpers\loadPlugins(['mailer']); // load rest of plugins - important, this has to be AFTER we know our router is going to work

		$controller = new $route->controller(); // instantiate the controller

		$response = call([$controller,$route->action()], $route->arguments()); // call controller method
		// $response is the return value of the route's action, but we won't need that
		// Exit execution to stop Kirby from displaying the error page
		exit;
	}
	?>