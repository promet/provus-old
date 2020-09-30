#!/bin/bash

DEV_BRANCH="develop"
REMOTE="master"
CURRENT_BRANCH=`git name-rev --name-only HEAD`
CURRENT_TAG=`git name-rev --tags --name-only $(git rev-parse HEAD)`

add_remote()
{
  git remote add deploy $REMOTE_GIT_REPO
}

set_perms() {
  chmod u+x config/content* -R
  chmod u+x $DOCROOT/sites/default/files
}

push()
{
  add_remote
  set_perms
  git add .
  git commit -m "Build for $1"
  git push deploy HEAD:$REMOTE --force
}

if [ $CURRENT_TAG != "undefined" ]
then
  push $CURRENT_TAG
elif [ $CURRENT_BRANCH == $DEV_BRANCH ]
then
  push $CURRENT_BRANCH
fi

