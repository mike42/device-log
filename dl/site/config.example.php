<?php

/* Database connection options */
$config['database']['user'] = "device-log";
$config['database']['pass'] = "...";
$config['database']['host'] = "localhost";
$config['database']['db'] = "device-log";
$config['login'] = array(
	'url' => 'ldap://localhost',
	'domain' => "dc=example,dc=com");
$config['ReceiptPrinter'] = array( // Receipt printer, or 0.0.0.0 for no printer
			'ip' => '0.0.0.0',
			'port' => '9100',
			'header' => 'ExampleCorp Ltd.',
			'footer' => 'A short blurb about processes'
		);
