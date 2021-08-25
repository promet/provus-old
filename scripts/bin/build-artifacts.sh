# Abort if anything fails
set -e

${PROJECT_ROOT}/scripts/bin/build-theme.sh
composer -n build-assets
