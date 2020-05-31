# Abort if anything fails
set -e

if [[ "${1}" == "" ]]; then
   echo "Need to specify dev or prod"
   exit
fi
if [[ "${2}" == "" ]]; then
   echo "Need to specify site"
   exit
fi

if [[ "${1}" == "dev" ]]; then
   echo "Setting site mode to dev..."
   drupal site:mode dev
   drush en -y devel kint views_ui -l $2
   drush -l $2 config-set -y devel.settings devel_dumper kint
elif [[ "${1}" == "prod" ]]; then
   echo "Setting site mode to prod..."
   drupal site:mode prod 
   drush pmu -y devel kint views_ui -l $2
fi
