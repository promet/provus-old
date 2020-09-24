# Abort if anything fails
set -e

THEME_NAME='provus'
${PROJECT_ROOT}/scripts/bin/build-theme.sh
echo "Removing node modules"
rm -rf ${PROJECT_ROOT}/src/themes/$THEME_NAME/node_modules
composer -n build-assets
