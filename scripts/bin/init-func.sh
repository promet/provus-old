#!/bin/bash

source ${PROJECT_ROOT}/scripts/bin/env.sh

#-------------------------- Settings --------------------------------

# PROJECT_ROOT and DOCROOT are set as env variables in cli
SITE_DIRECTORY="default"
DOCROOT_PATH="${PROJECT_ROOT}/${DOCROOT}"
SRC_PATH="${PROJECT_ROOT}/src"
THEME_PATH="${SRC_PATH}/themes/countyoc"
SITEDIR_PATH="${DOCROOT_PATH}/sites/${SITE_DIRECTORY}"

#-------------------------- END: Settings --------------------------------

#-------------------------- Helper functions --------------------------------

# Console colors
red='\033[0;31m'
green='\033[0;32m'
green_bg='\033[1;97;42m'
yellow='\033[1;33m'
NC='\033[0m'

echo-red () { echo -e "${red}$1${NC}"; }
echo-green () { echo -e "${green}$1${NC}"; }
echo-green-bg () { echo -e "${green_bg}$1${NC}"; }
echo-yellow () { echo -e "${yellow}$1${NC}"; }

# Copy a settings file.
# Skips if the destination file already exists.
# @param $1 source file
# @param $2 destination file
copy_settings_file()
{
	local source="$1"
	local dest="$2"

	if [[ ! -f $dest ]]; then
		echo "Copying ${dest}..."
		cp $source $dest
	else
		echo "${dest} already in place."
	fi
}

#-------------------------- END: Helper functions --------------------------------

#-------------------------- Functions --------------------------------

composer_install ()
{
	cd "$PROJECT_ROOT"
	echo-green "Installing dependencies..."
	composer install
}

# Initialize local settings files
init_settings ()
{
	# drupal-composer/drupal-project creates settings.php from default.settings.php.
	# Since we supply our own settings.php below, we have to drop the default file first.
	# TODO: Uncomment the local config (settings.local.php) include section in settings.php inline instead.
	# That'a the only change we need in the stock settings.php
	#rm -f "${SITEDIR_PATH}/settings.php"

	#copy_settings_file "${PROJECT_ROOT}/.docksal/settings/settings.php" "${SITEDIR_PATH}/settings.php"
	copy_settings_file "${PROJECT_ROOT}/.docksal/settings/settings.local.php" "${SITEDIR_PATH}/settings.local.php"
}

# Fix file/folder permissions
fix_permissions ()
{
	echo-green "Making site directory writable..."
	chmod 755 "${SITEDIR_PATH}"
}

# Install site
site_install ()
{
	cd "$DOCROOT_PATH"

	echo-green "Installing Drupal..."
	drush si minimal -y 
  drush config-set system.site uuid 1aa3a078-6e7f-4336-b6d9-bec3b1e61561 -y
  drush cim -y
  drush uli
}

# Build the dist folder for the theme.
build_theme_dist ()
{

	cd "$THEME_PATH"

	echo-green "Building theme files..."
  yarn
  yarn build
}
