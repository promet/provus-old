<?php

$acquia_env = getenv('AH_SITE_ENVIRONMENT') ? getenv('AH_SITE_ENVIRONMENT') : '';

if ($acquia_env) {
  // Acquia db settings.
  if (file_exists('/var/www/site-php')) {
    require '/var/www/site-php/orangecounty/orangecounty-settings.inc';
  }
}
else {
  // Docksal DB connection settings.
  $databases['default']['default'] = array (
    'database' => 'default',
    'username' => 'user',
    'password' => 'user',
    'host' => 'db',
    'driver' => 'mysql',
  );

}
