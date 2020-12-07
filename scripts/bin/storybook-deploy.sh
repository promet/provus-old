#!/usr/bin/env bash
# Deploys storybook.

STORYBOOK_BRANCH="storybook-www"

echo "Copying docs"
cp -r ${PROJECT_ROOT}/src/themes/${THEME_NAME} ${PROJECT_ROOT}/${THEME_NAME}
cd ${PROJECT_ROOT}/${THEME_NAME}
yarn
git checkout -b ${STORYBOOK_BRANCH} origin/${STORYBOOK_BRANCH}
git reset --hard origin/${STORYBOOK_BRANCH}
git pull
echo "Building docs"
yarn build-docs -o ../docs/${STORYBOOK_FOLDER}
echo "Copying images"
rm -rf ${PROJECT_ROOT}/docs/${STORYBOOK_FOLDER}/images
cp -r ${PROJECT_ROOT}/${THEME_NAME}/images ${PROJECT_ROOT}/docs/${STORYBOOK_FOLDER}/images
echo "Removing artifact"
rm -rf ${PROJECT_ROOT}/${THEME_NAME}
echo "Adding to git"
git add docs
git commit -m "Updates storybook"
git push origin ${STORYBOOK_BRANCH}
