<?php

	//In case you need a custom "form" (any page on kirby tthat you edit like pages or users is a form)


	use lcd344\Mailer\Panel\Models\MailerModel;

	return function () {
		// this part is like a blueprint, defined in an array. I suspect it has exactly the same rules

		if(!$drivers = c::get('mailer.panel.drivers',false)){
			$drivers = ['mail' => 'PHP Mail'];
			if (c::get('mailer.amazon.key',false) && c::get('mailer.amazon.secret',false) && c::get('mailer.amazon.host',false)) {
				$drivers['amazon'] = "Amazon";
			}
			if (c::get('mailer.postmark.key',false)) {
				$drivers['postmark'] = "Postmark";
			}
			if (c::get('mailer.mailgun.key',false) && c::get('mailer.mailgun.domain',false)) {
				$drivers['mailgun'] = "Mailgun";
			}
			if (c::get('mailer.phpmailer.host',false) && c::get('mailer.phpmailer.username',false) && c::get('mailer.phpmailer.password',false)) {
				$drivers['phpmailer'] = "PHPMailer";
			}
			$drivers['log'] = 'Log';
		}

		$fields = array(

			'driver' => [
				'label' => 'Driver',
				'type' => 'select',
				'required' => true,
				'options' => $drivers
			],

			'to' => [
				'label' => 'Email',
				'type' => 'tags',
				'required' => true
			],

			'cc' => [
				'label' => 'CC:',
				'type' => 'tags',
				'width' => '1'
			],

			'bcc' => [
				'label' => 'BCC',
				'type' => 'tags',
				'width' => '1'
			],

			'subject' => [
				'label' => 'Subject',
				'type' => 'text',
				'required' => true,
				'autocomplete' => false
			],

			'body' => [
				'label' => 'Body',
				'required' => true,
				'type' => \c::get('mailer.editor','textarea'),
				'model' => new MailerModel(),
				'width' => '1'
			]

		);


		// setup the form with all fields
		$form = new Kirby\Panel\Form($fields, []);

		// setup the url for the cancel button
		$form->cancel("");
		$form->buttons->submit->value = "Send";

		return $form;

	};
