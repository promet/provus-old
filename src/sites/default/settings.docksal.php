<?php

// @codingStandardsIgnoreFile

// Docksal DB connection settings.
$databases['default']['default'] = array (
  'database' => 'default',
  'username' => 'user',
  'password' => 'user',
  'host' => 'db',
  'driver' => 'mysql',
);

// Reverse proxy configuration (Docksal vhost-proxy)
if (PHP_SAPI !== 'cli') {
	$settings['reverse_proxy'] = TRUE;
	$settings['reverse_proxy_addresses'] = array($_SERVER['REMOTE_ADDR']);
	// HTTPS behind reverse-proxy
	if (
		isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https' &&
		!empty($settings['reverse_proxy']) && in_array($_SERVER['REMOTE_ADDR'], $settings['reverse_proxy_addresses'])
	) {
		$_SERVER['HTTPS'] = 'on';
		// This is hardcoded because there is no header specifying the original port.
		$_SERVER['SERVER_PORT'] = 443;
	}
}
