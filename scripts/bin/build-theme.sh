#!/usr/bin/env bash

source ${PROJECT_ROOT}/scripts/bin/env.sh
cd ${PROJECT_ROOT}/web/themes/${THEME_NAME}; yarn; yarn build 
