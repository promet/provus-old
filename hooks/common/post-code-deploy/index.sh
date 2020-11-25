#!/bin/bash

ACCOUNT=$1
ENV=$2

drush9 cc drush
drush9 @$ACCOUNT.$ENV cim -y 
drush9 @$ACCOUNT.$ENV updb -y 
drush9 @$ACCOUNT.$ENV cr 
