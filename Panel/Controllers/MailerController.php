<?php
	/**
	 * Created by PhpStorm.
	 * User: lcd34
	 * Date: 24/9/2016
	 * Time: 12:06 PM
	 */

	namespace lcd344\Mailer\Panel\Controllers;

	use Exception;
	use lcd344\Mailer;
	use lcd344\Mailer\Panel\Models\MailerModel;


	// all new controllers should extend extended base
	class MailerController extends ExtendedBaseController {

		public function index() {

			$self = $this;
			$model = new MailerModel();

			$form = $model->form(function ($form) use ($self) {

				$data = $form->serialize();
				try {
					$fail = [];
					$mailer = new Mailer($data['driver']);
					foreach (explode(',', $data['to']) as $recipient) {
						$mailer->to($recipient);
						if ($data['driver'] == 'phpmailer') {
							if (trim($data['cc'] != '')) {
								$mailer->cc(explode(',', $data['cc']));
							}
							if (trim($data['bcc'] != '')) {
								$mailer->bcc(explode(',', $data['bcc']));
							}
							if (isset($_FILES['file'])) {
								for ($i = 0; $i < count($_FILES['file']['name']); $i++) {
									$mailer->attach([$_FILES['file']['tmp_name'][$i], $_FILES['file']['name'][$i]]);
								}
							}
						}
						if (!$mailer->send($data['subject'], kirbytext($data['body']))) {
							$fail[] = $recipient;
						}
					}
					if (count($fail) == 0) {
						$self->notify('Emails were sent.');
					} else {
						$self->alert("Could not send to the following recipients " . implode(", ", $fail) . ".");
					}

				} catch (Error $e) {
					$self->alert($e->getMessage());
				}
			});

			// this is important - set the action to the form action to the url of the page explicitly.
			$form->action("mailer");
			$form->attr("id", "dropzoneForm");
			$form->attr("class", "dropzone");
			$form->attr("enctype", "multipart/form-data");
			$form->attr("style", "padding-top: 20px");
			$users = panel()->users();
			// load the view, include the name of the view, the model (you need this just for the top bar), and arguments for the view itself
			// if you need the screen to load as a modal - use $this->modal instead and you don't need to pass the second variable.
			echo $this->screen('index', $model, [
				'form' => $form,
				'model' => $model,
				'users' => $users
			]);
		}

	}