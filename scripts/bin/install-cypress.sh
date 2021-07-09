#!/usr/bin/env bash

source ${PROJECT_ROOT}/scripts/bin/env.sh

sudo apt-get update
sudo apt-get install -y libgtk2.0-0 libgtk-3-0 libgbm-dev libnotify-dev libgconf-2-4 libnss3 libxss1 libasound2 libxtst6 xauth xvfb
cd ${PROJECT_ROOT}/tests/cypress; npm i 
