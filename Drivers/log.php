<?php

	email::$services['log'] = function ($email) {

		$fileName = kirby::instance()->roots()->site() . DS . "logs" . DS . "mailer" . '.log';
		$attachments = "";
		print_r($email->attachments);

		foreach ($email->attachments as $attachment){
			$attachments .= $attachment . " ";
		}

		if (!f::append($fileName,
			"Time: " . Date("Y/m/d h:i:sa") . "\n".
			"From: {$email->from}\n".
			"To: {$email->to}\n" .
			"Reply To: {$email->replyTo}\n" .
			"Attachments : " . $attachments . "\n" .
			"Subject: {$email->subject}\n\n".
			$email->body . "\n\n"
		)) {
			throw new Error('The mail could not be logged');
		}

	};