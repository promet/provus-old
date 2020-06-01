<?php

assert_options(ASSERT_ACTIVE, TRUE);
\Drupal\Component\Assertion\Handle::register();

// Workaround for permission issues with NFS shares
$settings['file_chmod_directory'] = 0777;
$settings['file_chmod_file'] = 0666;

# File system settings.
$config['system.file']['path']['temporary'] = '/tmp';

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

if (file_exists($app_root . '/' . $site_path . '/services.local.yml')) {
  $settings['container_yamls'][] = $app_root . '/' . $site_path . '/services.local.yml';
}
