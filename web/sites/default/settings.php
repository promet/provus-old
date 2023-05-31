<?php

if (file_exists($app_root . '/' . $site_path . '/settings.provus.php')) {
  include $app_root . '/' . $site_path . '/settings.provus.php';
}

if (file_exists($app_root . '/' . $site_path . '/settings.pantheon.php')) {
  include $app_root . '/' . $site_path . '/settings.pantheon.php';
}

if (file_exists($app_root . '/' . $site_path . '/settings.local.php')) {
  include $app_root . '/' . $site_path . '/settings.local.php';
}

$settings['config_sync_directory'] = '../config/default';

// Docksal DB connection settings.
$databases['default']['default'] = array (
  'database' => 'default',
  'username' => 'user',
  'password' => 'user',
  'prefix' => '',
  'host' => 'db',
  'port' => '3306',
  'namespace' => 'Drupal\\Core\\Database\\Driver\\mysql',
  'driver' => 'mysql',
);
