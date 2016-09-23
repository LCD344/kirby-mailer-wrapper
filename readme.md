# Kirby Mailer Wrapper

![Version](https://img.shields.io/badge/version-0.2.0-green.svg) ![License](https://img.shields.io/badge/license-MIT-green.svg) ![Kirby Version](https://img.shields.io/badge/Kirby-2.3%2B-red.svg)

*Version 0.2.0*

A wrapper around the kirby mailer, with two new email drivers included.

## Installation

Use one of the alternatives below.

### 1. Kirby CLI

If you are using the [Kirby CLI](https://github.com/getkirby/cli) you can install this plugin by running the following commands in your shell:

```
$ kirby plugin:install lcd344/kirby-mailer-wrapper
```

### 2. Clone or download

1. [Clone](https://github.com/LCD344/kirby-mailer-wrapper) or [download](https://github.com/LCD344/kirby-mailer-wrapper/archive/master.zip)  this repository.
2. Unzip the archive if needed and rename the folder to `mailer`.

**Make sure that the plugin folder structure looks like this:**



### 3. Git Submodule

If you know your way around Git, you can download this plugin as a submodule:

```
$ git submodule add https://github.com/LCD344/kirby-mailer-wrapper site/plugins/mailer
```

## Usage```
        site/plugins/mailer/
        ```

First this includes two kirby mail drivers

1) Log Driver - This will just output the email you created to:
```
site/logs/mailer.log
```

Usage
```php
$email = email([
  'to'      => 'mail@example.com',
  'from'    => 'john@doe.com',
  'subject' => 'Yay, Kirby sends mails',
  'body'    => 'Hey, this is a test email!', 
  'attachments' => [file1,file2],
  'service' => 'log'
));

```

2) PHPMailer driver - This will use PHPMailer to send your email

Usage
```php
$email->send([
  'to'      => 'mail@example.com',
  'from'    => 'john@doe.com',
  'fromName' => 'John Doe',
  'replyTo' => 'jane@doe.com'
  'cc' => ['john@doe1.com','john@doe2.com']
  'bcc' => ['john@doe3.com']
  'subject' => 'Yay, Kirby sends mails',
  'body'    => 'Hey, this is a test email!', 
  'service' => 'log',
  'attachments' => [file1,file2],
  'service' => 'phpmailer',
  'options' => [
    'host' => 'hostname',
    'username' => 'username',
    'password' => 'password',
    'protocol' => 'tls' //optional defaults to ssl
    'port' => '465' //optional defaults to 465
    smptoptions => [
        'ssl' => [
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true
        ]
    ] // optional defaults to the options written here
  ]
]);
```

3) A wrapper around all all the drivers for kirby mail.

Usage

```php
	$mailer = new lcd344\Mailer(serviceName,[options]); // the options is an array that depends on the service you use
	
    $mailer->to('john@doe.com);
    $mailer->from('jane@doe.com','Jane Doe'); //second variable only used with phpmailer
    $mailer->replyTo('jane@doe.org');
    $mailer->cc('jane2@doe.com'); //Only phpmailer, can be called multiple times for multiple cc addresses and/or called with an array
    $mailer->bcc('jane3@doe.com'); //Only phpmailer, can be called multiple times for multiple bcc addressess and/or called with an array
    $mailer->attach($page->file('bla.log')->root()); //Only phpmailer and log, can be called multiple times for multiple attachments and/or called with an array
    $mailer->send("subject","lorem ipsum text");
```

## Options

The following options will be used as defaults in the wrapper class (not in drivers) if you define them in your `/site/config/config.php` file:

```php
c::set('mailer.from', 'john@doe.com'); // default from email
c::set('mailer.fromName', 'john doe'); // default from name - phpmailer only
c::set('mailer.replyTo', 'john@doe1.com'); // default reply to address
c::set('mailer.service', 'phpmailer'); // default mailer service

c::set('mailer.amazon.key', 'key'); // default amazon key
c::set('mailer.amazon.secret', 'secret'); // default amazon secret
c::set('mailer.amazon.host', 'host'); // default amazon host

c::set('mailer.postmark.key', 'key'); // default postmark key

c::set('mailer.mailgun.key', 'key'); // default mailgun key
c::set('mailer.mailgun.domain', 'domain'); // default mailgun domain

c::set('mailer.phpmailer.host', 'host'); default phpmailer host
c::set('mailer.phpmailer.username', 'username'); default phpmailer username
c::set('mailer.phpmailer.password', 'password'); default phpmailer password
c::set('mailer.phpmailer.protocol', 'ssl;); default phpmailer port
c::set('mailer.phpmailer.port', 465); default phpmailer port
c::set('mailer.phpmailer.smptoptions', []); default phpmailer smtp options
```

Any parameters given to the wrapper will override those defaults.

## Requirements

- [**Kirby**](https://getkirby.com/) 2.3+

## Disclaimer

This plugin is provided "as is" with no guarantee. Use it at your own risk and always test it yourself before using it in a production environment. If you find any issues, please [create a new issue](https://github.com/LCD344/kirby-mailer-wrapper/issues/new).

## License

[MIT](https://opensource.org/licenses/MIT)