<?php

// @codingStandardsIgnoreFile

// DB and site specific settings.
if (file_exists($app_root . '/' . $site_path . '/settings.site.php')) {
  include $app_root . '/' . $site_path . '/settings.site.php';
}
// Dev settings.
if (getenv('SITE_ENVIRONMENT') == 'local' && !getenv('SITE_ENVIRONMENT')) {
  include $app_root . '/sites/settings.local.php';
}
// Drupal settings.
if (file_exists($app_root . '/sites/settings.promet.php')) {
  include $app_root . '/sites/settings.promet.php';
}
// Additional local settings.
if (file_exists($app_root . '/' . $site_path . '/settings.local.php')) {
  include $app_root . '/' . $site_path . '/settings.local.php';
}
$settings['install_profile'] = 'minimal';
