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

// Automatically generated include for settings managed by docksal.
if (file_exists(__DIR__ . '/settings.docksal.php') && getenv('IS_DOCKSAL_PROJECT') == TRUE) {
  include __DIR__ . '/settings.docksal.php';
}

// Local option.
if (getenv('SITE_ENVIRONMENT') == 'local' && !getenv('SITE_ENVIRONMENT') && file_exists(__DIR__ . '/settings.local.php')) {
  include __DIR__ . '/settings.local.php';
}

// Drupal settings.
if (file_exists( __DIR__ . '/settings.promet.php')) {
  include  __DIR__ . '/settings.promet.php';
}
$settings['install_profile'] = 'minimal';
