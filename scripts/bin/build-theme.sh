#!/usr/bin/env bash

source ${PROJECT_ROOT}/scripts/bin/env.sh
cd ${PROJECT_ROOT}/src/themes/${THEME_NAME}; yarn; yarn build 
