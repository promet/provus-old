#!/usr/bin/env bash
# Lints storybook.

source ${PROJECT_ROOT}/scripts/bin/env.sh

cd ${PROJECT_ROOT}/${DOCROOT}/themes/custom/countyoc; yarn; yarn lint 
