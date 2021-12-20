#!/usr/bin/env bash
# Deploys storybook.

if [ -z "$TRAVIS_REPO_SLUG" ]
then
  echo "Must add TRAVIS_REPO_SLUG to docksal"
  exit 1
elif [ -z "$GH_TOKEN" ]
then
  echo "Must add GH_TOKEN to travis"
  exit 1
fi

STORYBOOK_BRANCH="storybook-www"

TMPDIR=$(mktemp -d /tmp/sbpub.XXXX)
GIT_REPO="${GH_TOKEN}@github.com/${TRAVIS_REPO_SLUG}"

## Get source "storybook"...
##
git clone --branch ${STORYBOOK_BRANCH} https://${GIT_REPO} $TMPDIR

cp -r ${PROJECT_ROOT}/web/themes/custom/${THEME_NAME} $TMPDIR/${THEME_NAME}
cd $TMPDIR/${THEME_NAME}
yarn
echo "Building docs"
yarn build-storybook -o ../docs/${STORYBOOK_FOLDER}
echo "Copying images"
rm -rf $TMPDIR/docs/${STORYBOOK_FOLDER}/images
cp -r ${PROJECT_ROOT}/${THEME_NAME}/images $TMPDIR/docs/${STORYBOOK_FOLDER}/images
echo "Adding to git"
cd $TMPDIR
git add docs
git commit -m "Updates storybook"
git push origin ${STORYBOOK_BRANCH}
