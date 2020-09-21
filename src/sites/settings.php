<?php

// @codingStandardsIgnoreFile
//
/**
 * Load services definition file.
 */
$settings['container_yamls'][] = __DIR__ . '/services.yml';

/**
 * Include the Pantheon-specific settings file.
 *
 * n.b. The settings.pantheon.php file makes some changes
 *      that affect all envrionments that this site
 *      exists in.  Always include this file, even in
 *      a local development environment, to insure that
 *      the site settings remain consistent.
 */
include __DIR__ . "/settings.pantheon.php";


// Dev settings.
if (getenv('SITE_ENVIRONMENT') == 'local' || !getenv('SITE_ENVIRONMENT')) {
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
