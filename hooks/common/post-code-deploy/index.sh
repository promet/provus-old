#!/bin/bash

ACCOUNT=$1
ENV=$2

drush9 cc drush
drush9 @$ACCOUNT.$ENV sites-update
