#!/usr/bin/env bash
# Starts phpunit.

source ${PROJECT_ROOT}/scripts/bin/env.sh

run_tests() {
    cd ${PROJECT_ROOT}
    ${PROJECT_ROOT}/vendor/bin/phpunit -c "${PROJECT_ROOT}/phpunit.xml" --printer "\\Drupal\\Tests\\Listeners\\HtmlOutputPrinter" "$@"
    cd -
}

run_tests "$@"
