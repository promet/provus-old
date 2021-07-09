#!/bin/bash

# Acquia does not support copying individual file folders so we run this after db update.

ROOT=/var/www/html

# Variables provided during Acquia post-db-copy.
application=$1
current_env=$2
site=$3
env=$4

if [ "$current_env" != "dev" ] && [ "$current_env" != "test" ]
then
  echo "Current environment must be 'dev' or 'test'. $current_env provided"
  exit 1
fi

if [ "$env" == "prod" ]
then
  rsync -av $application.$env@$application.ssh.$env.acquia-sites.com:$ROOT/$application.$env/docroot/sites/$site/files/ $ROOT/$application.$current_env/docroot/sites/$site/files/
elif [ "$env" == "test" ]
then
  rsync -av $ROOT/$application.$env/docroot/sites/$site/files/ $ROOT/$application.$current_env/docroot/sites/$site/files/
fi

drush9 @$application.$current_env cim -y -l $site
drush9 @$application.$current_env cr -l $site

