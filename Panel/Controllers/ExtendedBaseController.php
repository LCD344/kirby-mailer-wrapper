<?php
	/**
	 * Created by PhpStorm.
	 * User: lcd34
	 * Date: 26/9/2016
	 * Time: 9:48 AM
	 */

	namespace lcd344\Mailer\Panel\Controllers;


	//all your controllers should extend this one
	use lcd344\Mailer\Panel\ExtendedClasses\View;
	use BoilerPlate\Panel\Models\ExampleModel;
	use Exception;
	use Kirby\Panel\Form;
	use Kirby\Panel\Controllers\Base;
	use Router;

	class ExtendedBaseController extends Base  {

		public function __construct() {
			// set up form folders, so when you create a form, it knows where to fetch the fields from
			Form::$root = array(
				'default' => panel()->roots->fields,
				'custom' => panel()->kirby->roots()->fields()
			);
		}

		// Switch default view class with custom view class that loads  views from plugin
		public function view($file, $data = array()) {
			return new View($file, $data);
		}

		public function fields($fieldName, $fieldType, $path) {

			$model = new ExampleModel();

			$form = $model->form(function () {});

			$field = $form->fields()->$fieldName;

			if (!$field or $field->type() !== $fieldType) {
				throw new Exception('Invalid field');
			}

			$routes = $field->routes();
			$router = new Router($routes);

			if ($route = $router->run($path)) {

				if (is_callable($route->action()) and is_a($route->action(), 'Closure')) {
					return call($route->action(), $route->arguments());
				} else {

					$controllerFile = $field->root() . DS . 'controller.php';
					$controllerName = $fieldType . 'FieldController';

					if (!file_exists($controllerFile)) {
						throw new Exception(l('fields.error.missing.controller'));
					}

					require_once($controllerFile);

					if (!class_exists($controllerName)) {
						throw new Exception(l('fields.error.missing.class'));
					}

					$controller = new $controllerName($model, $field);

					echo call([$controller, $route->action()], $route->arguments());

				}

			} else {
				throw new Exception(l('fields.error.route.invalid'));
			}

		}
	}