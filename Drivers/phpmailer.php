<?php

	email::$services['phpmailer'] = function ($email) {

		$mail = new PHPMailer(true);

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

		$mail->setFrom($email->from, isset($email->fromName) ? $email->fromName : null);
		$mail->addReplyTo($email->replyTo);
		$mail->addAddress($email->to);
		if(isset($email->arrayCC)){
			foreach($email->arrayCC as $recipient){
				$mail->addCC($recipient);
			}
		} else if($email->cc){
			$mail->addCC($email->cc);
		}

		if(isset($email->arrayBCC)){
			foreach($email->arrayBCC as $recipient){
				$mail->addBCC($recipient);
			}
		} else if($email->bcc){
			$mail->addBCC($email->bcc);
		}

		if(isset($email->attachments)){
			foreach ($email->attachments as $attachment){
				if(is_array($attachment)){
					$mail->addAttachment($attachment[0],$attachment[1]);
				} else {
					$mail->addAttachment($attachment);
				}
			}
		}

		$mail->Subject = $email->subject;
		$mail->Body    = $email->body;

		try {
			$mail->send();
		} catch (phpmailerException $e) {
			throw new Exception($e->getMessage());
		} catch (Exception $e){
			throw new Exception($e->getMessage());
		}
	};