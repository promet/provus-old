#!/bin/bash

DEV_BRANCH="develop"
BUILD_BRANCH="build"
CURRENT_BRANCH=`git name-rev --name-only HEAD`
CURRENT_TAG=`git name-rev --tags --name-only $(git rev-parse HEAD)`

prep ()
{
  git remote add github https://${GH_TOKEN}@github.com/promet/provus.git 
}

quiet_git() {
  stdout=$(tempfile)
  stderr=$(tempfile)

  if ! git "$@" </dev/null >$stdout 2>$stderr; then
      cat $stderr >&2
      rm -f $stdout $stderr
      exit 1
  fi

  rm -f $stdout $stderr
}

add()
{
  quiet_git add --force -A docroot vendor scripts .docksal load.environment.php drush
  quiet_git commit -m "Updates build"
}

build ()
{
  ${PROJECT_ROOT}/scripts/bin/build-artifacts.sh
}

branch ()
{
  prep
  git checkout --orphan $BUILD_BRANCH
  build
  add
  echo "Pushing branch to build..."
  git push --force github $BUILD_BRANCH
}

tag ()
{
  prep
  git tag -d $CURRENT_TAG
  build
  add
  echo "Updating tag ..."
  git tag $CURRENT_TAG
  git push $CURRENT_TAG
}

if [ $CURRENT_TAG != "undefined" ]
then
  tag
elif [ $CURRENT_BRANCH == $DEV_BRANCH ]
then
  branch
fi

