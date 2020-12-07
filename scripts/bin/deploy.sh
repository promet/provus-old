#!/bin/bash

DEV_BRANCH="develop"
CURRENT_BRANCH=`git name-rev --name-only HEAD`
CURRENT_TAG=`git name-rev --tags --name-only $(git rev-parse HEAD)`

auth_hosting()
{
  if [ "$HOSTING_PLATFORM" == "pantheon" ]
  then
   TERMINUS_BIN=$PROJECT_ROOT/scripts/vendor/terminus

   $TERMINUS_BIN auth:login --machine-token=$SECRET_TERMINUS_TOKEN
  elif [ "$HOSTING_PLATFORM" == "acquia" ]
  then
   echo "Building for Acquia."
  else
   echo "ERROR: Unknown hosting. Supports Pantheon and Acquia for now."
   exit 1
  fi
}

add_remote()
{
  git remote add deploy $REMOTE_GIT_REPO
}

set_perms() {
  chmod u+x config/content* -R
  chmod u+x $DOCROOT/sites/default/files
}

pantheon_conn_switch()
{
  $TERMINUS_BIN connection:set ${PANTHEON_SITE_NAME}.dev $1
}

build()
{
  ${PROJECT_ROOT}/scripts/bin/build-artifacts.sh
}

push()
{
  add_remote
  build
  set_perms
  git add .
  git commit -m "Build for $1"
  [[ "$HOSTING_PLATFORM" == "pantheon" ]] && pantheon_conn_switch git  ## Must be 'git mode' in Pantheon to commit.
  git push deploy HEAD:$1 --force
}

## ==============
## main() below.
## ==============

auth_hosting
if [ $CURRENT_TAG != "undefined" ]
then
  push $CURRENT_TAG
elif [ $CURRENT_BRANCH == $DEV_BRANCH ]
then
  push $REMOTE_DEPLOY_BRANCH
fi
