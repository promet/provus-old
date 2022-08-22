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