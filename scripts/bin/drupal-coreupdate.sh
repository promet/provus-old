#!/usr/bin/env bash

REPO_NAME=provus
GH_REPO=github.com/promet/${REPO_NAME}.git

coreupdate_try() {
  if [[ $(composer outdated "drupal/*"|grep -ic "^drupal/core") -eq 1 ]]; then
    composer update drupal/core --with-dependencies && \
    drush updb -y                                   && \
    drush cr -y                                     && \
    drush st
  else
    echo "No drupal core update detected."
  fi
} ## END: coreupdate_try()

coreupdate_pr() {
  dcore_ver=$(drush st|grep 'Drupal version'|awk '{print $4}')
  DRUPALCORE_BRANCH=drupalcore_${dcore_ver}

  git fetch
  PRBRANCH_EXIST=$(git ls-remote https://${GH_TOKEN}@${GH_REPO} | grep -c ${DRUPALCORE_BRANCH}$)

  if [[ $PRBRANCH_EXIST -eq 0 ]]; then

    ## Attempt to create a PR source branch
    ##
    TMP_PRBRANCH=$(mktemp -d /tmp/d8pr-XXXX)
    cd $TMP_PRBRANCH
    git clone https://${GH_TOKEN}@${GH_REPO} $DRUPALCORE_BRANCH
    cd $DRUPALCORE_BRANCH
    git fetch
    git checkout -b $DRUPALCORE_BRANCH
    cp ${PROJECT_ROOT}/composer.lock .

    ## However, make sure something changed. Otherwise,
    ## ...do not send PR.
    ## 
    if [[ $(git status -s|wc -l|sed 's/\ //g') -ne 0 ]]; then
        git add composer.lock
        git commit -m"PTECH-1569: Drupal update to ${dcore_ver}"
        git push -u origin $DRUPALCORE_BRANCH
        cd ${PROJECT_ROOT}

        PR_JSON=${TMP_PRBRANCH}/pr.json
        ## Assemble the PR JSON file.
        ##
	cat > ${PR_JSON} <<- JSON_BLOCK
	{
	   "title": "Drupal core: ${dcore_ver}",
	   "head" : "${DRUPALCORE_BRANCH}",
	   "base" : "develop"
	}
	JSON_BLOCK

        echo "[INFO] Creating PR now."
        curl -X POST -u "icasimpan:${GH_TOKEN}"                          \
             -H "Accept: application/vnd.github.v3+json"                 \
             https://api.github.com/repos/promet/${REPO_NAME}/pulls      \
             -d @${PR_JSON}
    else
      echo "[INFO] No detected changes to commit."
    fi

  ## Oops, seems like PR already done and waiting for review and merge.
  ##
  else
     echo "[INFO] ${DRUPALCORE_BRANCH} already exist in remote repo."
     echo "       PR creation for Drupal core update skipped."
     echo
     echo "       To override: delete the branch and rerun 'fin drupalcore-update'."
     echo
  fi
} ## END: coreupdate_pr()

if [[ "$TRAVIS_EVENT_TYPE" = "cron" ]] || [[ "$2" = "--local" ]]; then
  case $1 in
    try)
      coreupdate_try
    ;;
    sendpr)
      coreupdate_pr
    ;;
    *)
      coreupdate_try
    ;;
  esac
else
  echo "Drupal core update skipped. See 'fin help drupal-coreupdate'."
fi
