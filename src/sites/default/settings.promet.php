<?php

// @codingStandardsIgnoreFile

$config_directories[CONFIG_SYNC_DIRECTORY] = '../config/default';
$site_env = getenv('SITE_ENVIRONMENT') ? getenv('SITE_ENVIRONMENT') : 'local';
$acquia_env = getenv('AH_SITE_ENVIRONMENT') ? getenv('AH_SITE_ENVIRONMENT') : '';

## Disable split config settings.  Will turn on correct one later.
$config['config_split.config_split.local']['status'] = FALSE;
$config['config_split.config_split.dev']['status'] = FALSE;
$config['config_split.config_split.stage']['status'] = FALSE;
$config['config_split.config_split.prod']['status'] = FALSE;

## Sets the colors for the Environment Indicator module based on environment.
// Local Developer Environments (green background, black text)
if ($site_env == 'local') {
  $config['environment_indicator.indicator']['bg_color'] = '#42FF33';
  $config['environment_indicator.indicator']['fg_color'] = '#000000';
  $config['environment_indicator.indicator']['name'] = 'Local';
  $config['config_split.config_split.local']['status'] = TRUE;
}
// prometdev environment (blue background, white text)
elseif ($site_env == 'prometdev') {
  $config['environment_indicator.indicator']['bg_color'] = '#0000FF';
  $config['environment_indicator.indicator']['fg_color'] = '#FFFFFF';
  $config['environment_indicator.indicator']['name'] = 'Promet Dev';
  $config['config_split.config_split.dev']['status'] = TRUE;
}
// prometstaging environment (pink background, black text)
elseif ($site_env == 'prometstg') {
  $config['environment_indicator.indicator']['bg_color'] = '#FF00FF';
  $config['environment_indicator.indicator']['fg_color'] = '#000000';
  $config['environment_indicator.indicator']['name'] = 'Promet Staging';
  $config['config_split.config_split.stage']['status'] = TRUE;
}
// Acquia dev environment (yellow background, black text)
if ($acquia_env == 'dev') {
  $config['environment_indicator.indicator']['bg_color'] = '#FFFF00';
  $config['environment_indicator.indicator']['fg_color'] = '#000000';
  $config['environment_indicator.indicator']['name'] = 'Acquia Dev';
  $config['config_split.config_split.dev']['status'] = TRUE;
}
// Acquia stage environment (orange background, black text)
elseif ($acquia_env == 'test') {
  $config['environment_indicator.indicator']['bg_color'] = '#FFAA00';
  $config['environment_indicator.indicator']['fg_color'] = '#000000';
  $config['environment_indicator.indicator']['name'] = 'Acquia QA';
  $config['config_split.config_split.stage']['status'] = TRUE;
}
// Acquia prod environment (red background, black text)
elseif ($acquia_env == 'prod') {
  $config['environment_indicator.indicator']['bg_color'] = '#FF0400';
  $config['environment_indicator.indicator']['fg_color'] = '#000000';
  $config['environment_indicator.indicator']['name'] = 'Acquia  Production';
  $config['config_split.config_split.prod']['status'] = TRUE;
}

## End Environment Indicator settings.
if (getenv('MEMCACHE_ENABLED'))  {
  $settings['memcache']['servers'] = ['memcached:11211' => 'default'];
  $settings['memcache']['bins'] = ['default' => 'default'];
  $settings['memcache']['key_prefix'] = '';
  $settings['cache']['default'] = 'cache.backend.memcache';
}

$settings['locale_custom_strings_en']['Address label'] = [
  'Company' => 'Name',
];

$settings['update_free_access'] = FALSE;
