#!/bin/bash

DEV_BRANCH="develop"
REMOTE="master"
CURRENT_BRANCH=`git name-rev --name-only HEAD`
CURRENT_TAG=`git name-rev --tags --name-only $(git rev-parse HEAD)`

auth_hosting()
{
 if [ "$HOSTING_PLATFORM" == "pantheon" ]
 then
   TERMINUS_BIN=$PROJECT_ROOT/scripts/vendor/terminus

   $TERMINUS_BIN auth:login --machine-token=$SECRET_TERMINUS_TOKEN
 else
   ## TODO: Support Acquia soon...
   echo "ERROR: Unknown hosting. Supports Pantheon.io for now."
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
  $TERMINUS_BIN connection:set ${HOSTING_SITE}.dev $1
}

push()
{
  add_remote
  set_perms
  git add .
  git commit -m "Build for $1"
  [[ "$HOSTING_PLATFORM" == "pantheon" ]] && pantheon_conn_switch git  ## Must be 'git mode' in Pantheon to commit.
  git push deploy HEAD:$REMOTE --force
  [[ "$HOSTING_PLATFORM" == "pantheon" ]] && pantheon_conn_switch sftp ## Must be 'sftp mode' in Pantheon to install Drupal.
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
  push $CURRENT_BRANCH
fi
