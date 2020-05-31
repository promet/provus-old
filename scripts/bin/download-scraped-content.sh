#!/usr/bin/env bash
# Script to download asset file from tag release using GitHub API v3.
# See: http://stackoverflow.com/a/35688093/55075    

source ${PROJECT_ROOT}/scripts/bin/env.sh

CWD="$(cd -P -- "$(dirname -- "$0")" && pwd -P)"

# Check dependencies.
set -e
type curl grep sed tr >&2
xargs=$(which xargs || which xargs)

[ "$GITHUB_API_TOKEN" ] || { echo "Error: Please define GITHUB_API_TOKEN variable." >&2; exit 1; }

# Define variables.
GH_API="https://api.github.com"
GH_REPO="$GH_API/repos/$owner/$repo"
GH_TAGS="$GH_REPO/releases/tags/$tag"
AUTH="Authorization: token $GITHUB_API_TOKEN"
WGET_ARGS="--content-disposition --auth-no-challenge --no-cookie"
CURL_ARGS="-LJO#"

# Validate token.
curl -o /dev/null -sH "$AUTH" $GH_REPO || { echo "Error: Invalid repo, token or network issue!";  exit 1; }

# Read asset tags.
response=$(curl -sH "$AUTH" $GH_TAGS)
# Get ID of the asset based on given name.
eval $(echo "$response" | grep -C3 "name.:.\+$name" | grep -w id | tr : = | tr -cd '[[:alnum:]]=')
[ "$id" ] || { echo "Error: Failed to get asset id, response: $response" | awk 'length($0)<100' >&2; exit 1; }
GH_ASSET="$GH_REPO/releases/assets/$id"

# Download asset file.
echo "Downloading asset..." >&2
#~curl $CURL_ARGS -H "Authorization: token $GITHUB_API_TOKEN" -H 'Accept: application/octet-stream' "$GH_ASSET"
TEMP_RELEASE_DIR=$(mktemp -d $PROJECT_ROOT/temp-XXXX)
curl -o $TEMP_RELEASE_DIR/$name $CURL_ARGS -H "Authorization: token $GITHUB_API_TOKEN" -H 'Accept: application/octet-stream' "$GH_ASSET"

tar -C $TEMP_RELEASE_DIR -zxvf $TEMP_RELEASE_DIR/release.tar.gz

# TODO: Update once we are using multi-site.
IMPORT_DEST=$PROJECT_ROOT/$DOCROOT/sites/default/files/import/data

mkdir -p $IMPORT_DEST

rsync -avz $TEMP_RELEASE_DIR/data/* $IMPORT_DEST
rm -rf $TEMP_RELEASE_DIR
echo "$0 done." >&2

