<?php
	/**
	 * Created by PhpStorm.
	 * User: lcd34
	 * Date: 26/9/2016
	 * Time: 9:56 AM
	 */

	namespace lcd344\Mailer\Panel\Models;

	// optional here - extend the kirby panel class you want the model to extend/
	use Obj;

	class MailerModel extends Obj {


		public function topbar($topbar) {
			//add a breadcrumb to topbar
			$topbar->append(purl('mailer'), "Mailer");
		}

		public function form($callback){

			// load model specific form - first argument is path to your form, second is the model to send to the form, last is the callback
			return panel()->form(kirby()->roots->plugins() . DS . "mailer/Panel/Forms/mailerForm.php", $this, $callback);
		}

		// you need this on the mdoel for the textarea options to work)
		public function url ($action){
			return purl("mailer" . DS . $action);
		}
	}