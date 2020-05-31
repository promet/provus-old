<?php

$acquia_env = getenv('AH_SITE_ENVIRONMENT') ? getenv('AH_SITE_ENVIRONMENT') : '';
$site = 'ocwr';

if ($acquia_env) {
  // Acquia db settings.
  if (file_exists('/var/www/site-php')) {
    require '/var/www/site-php/orangecounty/' . $site . '-settings.inc';
  }
}
else {
  // Docksal DB connection settings.
  $databases['default']['default'] = array (
    'database' => $site,
    'username' => 'user',
    'password' => 'user',
    'host' => 'db',
    'driver' => 'mysql',
  );

}
