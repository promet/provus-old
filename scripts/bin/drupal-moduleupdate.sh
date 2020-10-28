#!/bin/bash

## TODO: When this script is consolidated with bin/drupal-coreupdate.sh,
##       'update_type' variable becomes script parameter $1
##
##       Ideally, eliminating sepate core and module update scripts is
##       better, however, the two has contradicting logic where the latest
##       detected version of the module is used.
##
##       In core (at least for latest d8), corresponding drupal 9 is being
##       offered as latest.
##       ===>  drupal/core,8.9.7,9.0.7
##
##       We can't cross drupal major versions despite D8 being similar to D9
##       as it could possibly break the site due to module compatibility issues.
##
##       The most that can be done is create a unified script
##       'bin/drupal-update.sh' and in docksal custom commands, create
##       separate wrapper custom command scripts but using the same common
##       script.
update_type='module'
#~update_type=$1

## TODO: Check these two variables and see where to move it.
##       Make sure it's reachable by this script (standalone),
##        not just as part of docksal.
##
REPO_NAME=provus
GH_REPO=github.com/promet/${REPO_NAME}.git

update_try() {
  local update_type=$1
  local drupal_component_name=$2  ## TODO: 'component' here is a reference to either 'core'
                                  ##        or 'module'. Not sure if this needs to be renamed.
                                  ##        From drupal docs, it doesn't mention the generic term
                                  ##        to use so perhaps for consistency's sake, we just use
                                  ##        component in this script.
  case $update_type in
    module)
      composer update $drupal_component_name --with-dependencies
    ;;

    core)
      if [[ $(composer outdated "drupal/*"|grep -ic "^drupal/core") -eq 1 ]]; then
        composer update drupal/core --with-dependencies && \
        drush updb -y                                   && \
        drush cr -y                                     && \
        drush st
      else
        echo 'No drupal core update detected.'
      fi
    ;;

    *)
      echo '[WARNING] Unrecognized update type.'
    ;;
  esac
} ## END: update_try()

update_branch() {
  local update_type=$1                 ## either 'module' or 'core'
  local update_branchname=$2
  local raw_component_name_versions=$3 ## e.g. "drupal/core,8.9.6,8.9.7"
                                       ##  col 1 => component name (drupal/core)
                                       ##  col 2 => current version (8.9.6)
                                       ##  col 3 => new version     (8.9.7)

  local component_name=$(  echo $raw_component_name_versions | cut -d',' -f1)
  local component_oldver=$(echo $raw_component_name_versions | cut -d',' -f2)
  local component_newver=$(echo $raw_component_name_versions | cut -d',' -f3)

  local PRBRANCH_EXIST=$(git ls-remote https://${GH_TOKEN}@${GH_REPO} | grep -c ${update_branchname}$)
  if [[ $PRBRANCH_EXIST -eq 0 ]]; then
    ## Attempt to create a PR source branch
    ##
    local TMP_PRBRANCH=$(mktemp -d /tmp/d8pr-XXXX)
    cd $TMP_PRBRANCH
    git clone https://${GH_TOKEN}@${GH_REPO} $update_branchname
    cd $update_branchname
    git fetch
    git checkout -b $update_branchname
    cp ${PROJECT_ROOT}/composer.lock .

    ## However, make sure something changed. Otherwise,
    ## ...do not PUSH branch
    ##
    if [[ $(git status -s|wc -l|sed 's/\ //g') -ne 0 ]]; then
        git add composer.lock
        git commit -m"PTECH-1569: Drupal ${update_type} update $component_name from ${component_oldver} to ${component_newver}"
        git push -u origin $update_branchname
        cd ${PROJECT_ROOT}
    else
        echo "**** [WARNING - update_branch] Could be an issue. No changes detected for branch: ${update_branchname} ****"
    fi
  else
    echo "******** [WARNING - update_branch] $update_branch already exist. Did not overwrite, need to be checked *********"
  fi
} ## END: update_branch()

#~ DEBUG TODO: stub/fake update_pr for debugging (start)

update_pr() {
  local update_type=$1                 ## either 'module' or 'core'
  local update_branchname=$2
  local raw_component_name_versions=$3 ## e.g. "drupal/core,8.9.6,8.9.7"
                                       ##  col 1 => component name (drupal/core)
                                       ##  col 2 => current version (8.9.6)
                                       ##  col 3 => new version     (8.9.7)

  local component_name=$(  echo $raw_component_name_versions | cut -d',' -f1)
  local component_oldver=$(echo $raw_component_name_versions | cut -d',' -f2)
  local component_newver=$(echo $raw_component_name_versions | cut -d',' -f3)

  echo "* [debug - PR TITLE] PTECH-1569: Drupal ${update_type} update $component_name from ${component_oldver} to ${component_newver}"
}

#~ DEBUG TODO: stub/fake update_pr for debugging (end)

#~ DEBUG TODO ** Use real 'update_pr()' below when finalizing behavior
DISABLED__update_pr() {
  local update_type=$1                 ## either 'module' or 'core'
  local update_branchname=$2
  local raw_component_name_versions=$3 ## e.g. "drupal/core,8.9.6,8.9.7"
                                       ##  col 1 => component name (drupal/core)
                                       ##  col 2 => current version (8.9.6)
                                       ##  col 3 => new version     (8.9.7)

  local component_name=$(  echo $raw_component_name_versions | cut -d',' -f1)
  local component_oldver=$(echo $raw_component_name_versions | cut -d',' -f2)
  local component_newver=$(echo $raw_component_name_versions | cut -d',' -f3)

  PRBRANCH_EXIST=$(git ls-remote https://${GH_TOKEN}@${GH_REPO} | grep -c ${update_branchname}$)
  if [[ $PRBRANCH_EXIST -eq 0 ]]; then

    ## Attempt to create a PR source branch
    ##
#~    TMP_PRBRANCH=$(mktemp -d /tmp/d8pr-XXXX)
#~    cd $TMP_PRBRANCH
#~    git clone https://${GH_TOKEN}@${GH_REPO} $update_branchname
#~    cd $update_branchname
#~    git fetch
#~    git checkout -b $update_branchname
#~    cp ${PROJECT_ROOT}/composer.lock .

    ## TODO: Review the "if block"...
    ## However, make sure something changed. Otherwise,
    ## ...do not send PR.
    ##
    if [[ $(git status -s|wc -l|sed 's/\ //g') -ne 0 ]]; then
        git add composer.lock
        git commit -m"PTECH-1569: Drupal ${update_type} update $component_name from ${component_oldver} to ${component_newver}"
        git push -u origin $update_branchname
        cd ${PROJECT_ROOT}

        PR_JSON=${TMP_PRBRANCH}/pr.json
        ## Assemble the PR JSON file.
        ##
        ##  * NOTE this "JSON_BLOCK" breaks when tabs is converted to several spaces in IDE or text editors.
        ##    It's a bash heredoc limitation where use of tabs is mandatory so code readability is not sacrificed.
        ##    There could be a better way but this is it for now.
	cat > ${PR_JSON} <<- JSON_BLOCK
	{
		"title": "Drupal ${update_type} update of ${component_name} ${component_oldver} to ${component_newver}",
		"head" : "${update_branchname}",
		"base" : "develop"
	}
	JSON_BLOCK

        ## TODO: * Replace 'icasimpan' account with 'promet-ci' perhaps.
        ##       * Authentication may be deprecated. Double-check in URL
        ##         https://developer.github.com/changes/2020-02-10-deprecating-auth-through-query-param

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
     echo "[INFO] ${update_branchname} already exist in remote repo."
     echo "       PR creation for Drupal ${update_type} update skipped."
     echo
     echo "       To override: delete the branch and rerun 'fin drupal-${update_type}update'."
     echo
  fi
} ## END: update_pr()

### ============================
### main()
### ============================

## TODO: --local is $1 unless we merge this with 'core-update' in the near future, making it $2 by then.
##
if [[ "$TRAVIS_EVENT_TYPE" = "cron" ]] || [[ "$1" = "--local" ]]; then

  ## core_filter - wether to only check only for modules (default) or core.
  ##               Cannot be both. Undefining variable would make it default to core.
  core_filter='-v'
  [[ "$update_type" = "core" ]] && core_filter=''

  ## NOTE: Slight difference in $new_ver parsing:
  ##  * fin composer => in 3rd position in awk
  ##  * composer     => in 4th position in awk, 3rd seems to be translated into color coding in 'fin'

  for each_component in $(composer outdated "drupal/*"|grep ^drupal|awk '{print $1","$2","$4}'|grep ${core_filter} "drupal/core"); do
    raw_component_name=$(echo $each_component        | cut -d',' -f1)
    component_name=$(    echo $raw_component_name    | cut -d'/' -f2)
    curr_ver=$(          echo $each_component        | cut -d',' -f2)
    new_ver=$(           echo $each_component        | cut -d',' -f3)

    # Create component branch
    update_branch="${raw_component_name}-${new_ver}"  ## e.g. 'drupal/admin_toolbar-x.y.z'
                                                      ## TODO: When core updating gets merged here, branch will use the same
                                                      ##       format
    # Try and do an update
    update_try "$update_type" "${update_branch}" "${raw_component_name}"

    # Process PR creation...if needed.
    ## NOTE for core: need to re-read the new drupal core version as it changes afte the "update_try"
    ##  * Do not re-read 'curr_ver' as it will surely be updated after the 'update_try' for drupal core and will mess up
    ##    the entire core update logic.
    if [[ "$update_type" = "core" ]]; then
       raw_component_name=$(composer outdated "drupal/*"|grep ^drupal|awk '{print $1","$2","$4}'|grep ${core_filter} "drupal/core")
       core_name='drupal/core'
       new_ver=$(echo $raw_component_name | cut -d',' -f2)
       update_branch="${core_name}-${new_ver}"
    fi
    update_branch "$update_type" "$update_branch" "${raw_component_name},${curr_ver},${new_ver}"
#~    update_pr "$update_type" "$update_branch" "${raw_component_name},${curr_ver},${new_ver}"

    ## ---- debugging below ----
    ## echo "UPDATE BRANCH: $update_branch"

    ## RAW DATA
    #~echo "***** RAW DATA: $each_component"

    ## Show the PR Name
    #~PR_name="${component_name}-${new_ver}"
    #~echo "* PR Name: ${PR_name}"

    ## Show the PR Commit Comment
    #~echo "** PR Comment: ${raw_component_name} from ${curr_ver} to ${new_ver}"
  done
else
  echo "Drupal ${update_type} update skipped. See 'fin help drupal-${update_type}update'."
fi
