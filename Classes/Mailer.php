<?php
	/**
	 * Created by PhpStorm.
	 * User: lcd34
	 * Date: 22/9/2016
	 * Time: 5:55 PM
	 */

	namespace lcd344;


	class Mailer {

		private $mailer;
		private $to;
		private $from;
		private $fromName;
		private $replyTo;
		private $attachments;
		private $cc;
		private $bcc;

		public function __construct($service = false, $options = false) {

			$this->cc = [];
			$this->bcc = [];
			$this->to = '';
			$this->from = \c::get('mailer.from', "");
			$this->fromName = \c::get('mailer.fromName', "");
			$this->replyTo = \c::get('mailer.replyTo', "");
			$this->attachments = [];
			if(! $service){
				$service = \c::get('mailer.service','mail');
			}
			if(! $options){
				switch ($service){
					case 'amazon' :
						$options = [
							'key'    => \c::get('mailer.amazon.key'),
							'secret' => \c::get('mailer.amazon.secret'),
							'host'   => \c::get('mailer.amazon.host')
						];
						break;
					case 'postmark' :
						$options = [
							'key'    => \c::get('mailer.postmark.key')
						];
						break;
					case 'mailgun' :
						$options = [
							'key'    => \c::get('mailer.mailgun.key'),
							'domain' => \c::get('mailer.mailgun.domain')
						];
						break;
					case 'phpmailer':
						$options = [
							'host' => \c::get('mailer.phpmailer.host'),
							'username' => \c::get('mailer.phpmailer.username'),
							'password' => \c::get('mailer.phpmailer.password'),
							'protocol' => \c::get('mailer.phpmailer.protocol',''),
							'port' => \c::get('mailer.phpmailer.port',''),
							'smptoptions' => \c::get('mailer.phpmailer.smptoptions','')
						];
						break;
					default:
						$options = [];
				}
			}

			$this->mailer = email([
				'service' => $service,
				'options' => $options
			]);
		}

		public function to($to) {
			$this->to = $to;
		}

		public function from($from,$fromName = '') {
			$this->from = $from;
			$this->fromName = $fromName;
		}

		public function replyTo($replyTo) {
			$this->replyTo = $replyTo;
		}

		public function cc($cc) {
			if (is_array($cc)){
				$this->cc[] = array_merge($this->cc,$cc);
			} else {
				$this->cc[] = $cc;
			}
		}

		public function bcc($bcc) {
			if (is_array($bcc)){
				$this->bcc[] = array_merge($this->bcc,$bcc);
			} else {
				$this->bcc[] = $bcc;
			}
		}

		public function attach($files) {
			if (is_array($files)){
				$this->attachments[] = array_merge($this->attachments[],$files);
			} else {
				$this->attachments[] = $files;
			}
		}

		public function send($subject, $body) {
			$this->mailer->attachments = $this->attachments;
			$this->mailer->cc = $this->cc;
			$this->mailer->bcc = $this->bcc;
			$this->mailer->fromName = $this->fromName;
			return $this->mailer->send([
				'to' => $this->to,
				'replyTo' => $this->replyTo,
				'from' => $this->from,
				'subject' => $subject,
				'body' => $body
			]);
		}
	}