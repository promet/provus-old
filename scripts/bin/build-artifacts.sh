# Abort if anything fails
set -e

composer install --no-dev
${PROJECT_ROOT}/scripts/bin/build-theme.sh
echo "Removing node modules"
rm -rf ${PROJECT_ROOT}/${DOCROOT}/themes/custom/countyoc/node_modules
