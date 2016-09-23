<?php

	email::$services['phpmailer'] = function ($email) {

		$mail = new PHPMailer;

		$mail->isSMTP();                                      // Set mailer to use SMTP
		$mail->SMTPAuth = true;                               // Enable SMTP authentication

		$mail->Host = $email->options['host'];  // Specify main and backup SMTP servers
		$mail->Username = $email->options['username'];  // SMTP username
		$mail->Password = $email->options['password'];  // SMTP password
		if (isset($email->options['protocol']) && $email->options['protocol'] != '') {
			$mail->SMTPSecure = $email->options['protocol']; // Enable TLS encryption, `ssl` also accepted
		} else {
			$mail->SMTPSecure = 'ssl';                            // Enable TLS encryption, `ssl` also accepted}
		}
		if (isset($email->options['port']) && $email->options['port'] != '') {
			$mail->Port = $email->options['port']; // Enable TLS encryption, `ssl` also accepted
		} else {
			$mail->Port = 465;                                    // TCP port to connect to
		}
		if (isset($email->options['smptoptions']) && $email->options['smptoptions'] != '') {
			$mail->SMTPOptions = $email->options['smptoptions']; // Enable TLS encryption, `ssl` also accepted
		} else {
			$mail->SMTPOptions = [
				'ssl' => [
					'verify_peer' => false,
					'verify_peer_name' => false,
					'allow_self_signed' => true
				]
			];
		}

		$mail->CharSet = 'UTF-8';
		$mail->IsHTML(true);

		$mail->setFrom($email->from, $email->fromName);
		$mail->addReplyTo($email->replyTo);
		$mail->addAddress($email->to);
		foreach($email->cc as $recipient){
			$mail->addCC($recipient);
		}
		foreach($email->bcc as $recipient){
			$mail->addBCC($recipient);
		}

		foreach ($email->attachments as $attachment){
			$mail->addAttachment($attachment);
		}

		$mail->Subject = $email->subject;
		$mail->Body    = $email->body;
		if(!$mail->send()) {
			throw new Error($mail->ErrorInfo);
		};
	};