<?php
	/**
	 * Created by PhpStorm.
	 * User: lcd34
	 * Date: 22/9/2016
	 * Time: 5:55 PM
	 */

	namespace lcd344;


	use Underscore\Types\Arrays;

	class Mailer {

		private $mailer;
		private $to;
		private $from;
		private $fromName;
		private $replyTo;
		private $attachments;
		private $cc;
		private $bcc;
		private $data;

		public function __construct($service = false, $options = false) {

			$this->data = [];
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

			return $this;
		}

		public function from($from,$fromName = '') {
			$this->from = $from;
			$this->fromName = $fromName;

			return $this;
		}

		public function replyTo($replyTo) {
			$this->replyTo = $replyTo;
			return $this;
		}

		public function cc($cc) {
			if (is_array($cc)){
				$this->cc = array_merge($this->cc,$cc);
			} else {
				$this->cc[] = $cc;
			}

			return $this;
		}

		public function bcc($bcc) {
			if (is_array($bcc)){
				$this->bcc = array_merge($this->bcc,$bcc);
			} else {
				$this->bcc[] = $bcc;
			}
			return $this;
		}

		public function attach($file) {
			$this->attachments[] = $file;
			return $this;
		}

		public function bind($data){
			$this->data = Arrays::flatten($data);
			return $this;
		}


		private function bindData($text){
			foreach ($this->data as $key => $value){
				$text = str_replace("{{{$key}}}",$value,$text);
			}

			return $text;
		}

		public function send($subject, $body) {
			$body = $this->bindData($body);
			$this->mailer->attachments = $this->attachments;
			$this->mailer->arrayCC = $this->cc;
			$this->mailer->cc = isset($this->cc[0]) ? $this->cc[0] : null;
			$this->mailer->arrayBCC = $this->bcc;
			$this->mailer->bcc = isset($this->bcc[0]) ? $this->bcc[0] : null;
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