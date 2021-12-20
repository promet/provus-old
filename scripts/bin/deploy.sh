#!/bin/bash
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
  echo "...Adding desination repo [git remote add deploy $REMOTE_GIT_REPO]"
  git remote add deploy $REMOTE_GIT_REPO
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

set_perms() {
  chmod u+x config/content* -R
  chmod u+x vendor/drush/drush/drush
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

storybook_deploy()
{
  ${PROJECT_ROOT}/scripts/bin/storybook-deploy.sh
}

setup()
{
  add_remote
  build
  set_perms
}


push()
{
  echo "...deleting the files folder we just added"
  sudo rm -rf /var/www/web/sites/default/files
  sudo rm -rf /var/www/sites/default/files
  quiet_git add --force -A $DOCROOT vendor hooks scripts drush
    if [ "$HOSTING_PLATFORM" == "pantheon" ]
    then
      echo "...GIT deleting the files folder we just added"
      sudo git rm -rf web/sites/default/files
      sudo git rm -rf sites/default/files
    fi
  quiet_git commit -m "Build for $1"
  quiet_git push deploy HEAD:$1 --force
}

tag ()
{
  quiet_git tag -d $CURRENT_TAG
  quiet_git add --force -A $DOCROOT vendor hooks scripts drush
  quiet_git commit -m "Pushing tag to $HOSTING_PLATFORM..."
  quiet_git tag $CURRENT_TAG
  quiet_git push deploy $CURRENT_TAG --force
  quiet_git push origin artifact-$CURRENT_TAG --force
}


## ==============
## main() below.
## ==============

auth_hosting
if [ $CURRENT_TAG != "undefined" ]
then
  setup
  if [ "$HOSTING_PLATFORM" == "pantheon" ]
  then
    # Pantheon doesn't accept tags. Create a branch with the tag name.
    push $CURRENT_TAG
  else
    tag
  fi
elif [ $CURRENT_BRANCH == $DEV_BRANCH ]
then
  setup
  push $REMOTE_DEPLOY_BRANCH
    if [ "$HOSTING_PLATFORM" == "pantheon" ]
    then
      echo "...Loggin into Pantheon with Terminus"
      $TERMINUS_BIN auth:login --machine-token=$SECRET_TERMINUS_TOKEN
      # Make sure Pantheon is ready
      echo "...Waiting 60s to be sure Pantheonn is ready"
      sleep 60
      echo "...PANTHEON: config import for ${HOSTING_SITE}.${REMOTE_DEPLOY_BRANCH}"
      $TERMINUS_BIN drush -n ${HOSTING_SITE}.${REMOTE_DEPLOY_BRANCH} cim -y
      echo "...PANTHEON: UPDATE DB"
      $TERMINUS_BIN drush -n ${HOSTING_SITE}.${REMOTE_DEPLOY_BRANCH} updb -y
      echo "...PANTHEON: Rebuilding Caches"
      $TERMINUS_BIN drush ${HOSTING_SITE}.${REMOTE_DEPLOY_BRANCH} cr
  fi
  storybook_deploy
fi
