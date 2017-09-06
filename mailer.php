<?php


	require_once __DIR__ . '/Classes/Mailer.php';
	require_once __DIR__ . '/Drivers/log.php';
	require_once  __DIR__ . '/vendor/autoload.php';
	require_once __DIR__ . '/Drivers/phpmailer.php';

	if (class_exists('panel') && c::get("mailer.panel", false)) {
		require_once __DIR__ . DS ."Panel/Helpers/helpers.php";
		require_once __DIR__ . DS ."Panel/ExtendedClasses/View.php";
		require_once __DIR__ . DS ."Panel/Models/MailerModel.php";
		require_once __DIR__ . DS ."Panel/Controllers/ExtendedBaseController.php";
		require_once __DIR__ . DS ."Panel/Controllers/MailerController.php";
		require_once __DIR__ . DS ."Panel/Router/router.php";
		$kirby->set('widget', 'mailerWidget', __DIR__ .  DS .'Panel/Widgets//mailerWidget');
	}
