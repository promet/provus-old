<?php

// @codingStandardsIgnoreFile
/******************** DRUPAL SETTINGS ********************/
$settings['install_profile'] = 'minimal';
$settings['hash_salt'] = 'ts3MpgAEZcYSm0_tCkJgrYiEMg1rRSHPzyrXRHLGTg7uBOHXRojaOnIfu1sQJO4hWxZVGJSqTA';
$settings['container_yamls'][] = $app_root . '/' . $site_path . '/services.yml';
$settings['file_scan_ignore_directories'] = [
  'node_modules',
  'bower_components',
];
$settings['entity_update_batch_size'] = 50;
$settings['locale_custom_strings_en']['Address label'] = [
  'Company' => 'Name',
];
$config_directories[CONFIG_SYNC_DIRECTORY] = '../config/default';
$settings['config_sync_directory'] = '../config/default';
$settings['config_vcs_directory'] = '../config/default';
$settings['update_free_access'] = FALSE;

/******************** ENVIRONMENT SETTINGS ********************/
$site_env = getenv('SITE_ENVIRONMENT') ? getenv('SITE_ENVIRONMENT') : 'local';
$acquia_env = getenv('AH_SITE_ENVIRONMENT') ? getenv('AH_SITE_ENVIRONMENT') : '';
$site = $site_path;

// Colors.
$black = '000000';
$white = '#FFFFFF';
$lightGreen = '#42FF33';
$blue = '#0000FF';
$violet = '#FF00FF';
$yellow = '#FFFF00';
$orange = '#FFAA00';
$red = '#FF0400';

## Sets the colors for the Environment Indicator module based on environment.
// Local Developer Environments (green background, black text)
if ($site_env == 'local') {
  $config['environment_indicator.indicator']['bg_color'] = $lightGreen;
  $config['environment_indicator.indicator']['fg_color'] = $black;
  $config['environment_indicator.indicator']['name'] = 'Local';
}
// prometdev environment (blue background, white text)
elseif ($site_env == 'prometdev') {
  $config['environment_indicator.indicator']['bg_color'] = $blue;
  $config['environment_indicator.indicator']['fg_color'] = $white;
  $config['environment_indicator.indicator']['name'] = 'Promet Dev';
}
// prometstaging environment (pink background, black text)
elseif ($site_env == 'prometstg') {
  $config['environment_indicator.indicator']['bg_color'] = $violet;
  $config['environment_indicator.indicator']['fg_color'] = $black;
  $config['environment_indicator.indicator']['name'] = 'Promet Staging';
}
// Acquia dev environment (yellow background, black text)
if ($acquia_env == 'dev') {
  $config['environment_indicator.indicator']['bg_color'] = $yellow;
  $config['environment_indicator.indicator']['fg_color'] = $black;
}
// Acquia stage environment (orange background, black text)
elseif ($acquia_env == 'test') {
  $config['environment_indicator.indicator']['bg_color'] = $orange;
  $config['environment_indicator.indicator']['fg_color'] = $black;
}
// Acquia prod environment (red background, black text)
elseif ($acquia_env == 'prod') {
  $config['environment_indicator.indicator']['bg_color'] = $red;
  $config['environment_indicator.indicator']['fg_color'] = $black;
}

## End Environment Indicator settings.
if (getenv('MEMCACHE_ENABLED'))  {
  $settings['memcache']['servers'] = ['memcached:11211' => 'default'];
  $settings['memcache']['bins'] = ['default' => 'default'];
  $settings['memcache']['key_prefix'] = '';
  $settings['cache']['default'] = 'cache.backend.memcache';
}
