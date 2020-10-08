#!/usr/bin/env bash

source ${PROJECT_ROOT}/scripts/bin/env.sh

cd ${PROJECT_ROOT}/tests/cypress; npm install; npm run open 
