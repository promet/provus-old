#!/bin/bash

DEV_BRANCH="develop"
ACQUIA_BUILD_BRANCH="acquia-develop"
CURRENT_BRANCH=`git name-rev --name-only HEAD`
CURRENT_TAG=`git name-rev --tags --name-only $(git rev-parse HEAD)`

prep ()
{
  ssh-keyscan -t rsa svn-23450.prod.hosting.acquia.com >> ~/.ssh/known_hosts 
  git remote add acquia orangecounty@svn-23450.prod.hosting.acquia.com:orangecounty.git
  git remote add oc https://${GH_TOKEN}@github.com/promet/oc-multi.git 
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
  quiet_git add --force -A docroot vendor hooks scripts .docksal load.environment.php drush
  quiet_git commit -m "Updates acquia develop"
}

build ()
{
  ${PROJECT_ROOT}/scripts/bin/build-artifacts.sh
  drush sites-prep-files-dirs
}

branch ()
{
  prep
  git checkout --orphan $ACQUIA_BUILD_BRANCH
  build
  add
  echo "Pushing branch to acquia..."
  git push --force acquia $ACQUIA_BUILD_BRANCH
  echo "Pushing branch to github..."
  git push --force oc $ACQUIA_BUILD_BRANCH
}

tag ()
{
  prep
  # git checkout --orphan $ACQUIA_BUILD_BRANCH
  git tag -d $CURRENT_TAG
  build
  add
  echo "Pushing tag to acquia..."
  git tag $CURRENT_TAG
  git push acquia $CURRENT_TAG
}

if [ $CURRENT_TAG != "undefined" ]
then
  tag
elif [ $CURRENT_BRANCH == $DEV_BRANCH ]
then
  branch
fi

