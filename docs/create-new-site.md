# Create a new site based off Provus

The following are steps to create a new site off of Provus.

## Base Setup

1. Clone Provus
2. Remove .git folder ``rm -rf .git``
3. Add new git remote ``git init; git add -A .; git commit -m "Initial commit"; git remote add origin [git url]``


## Update to use "docroot" if desired

Out of the box Provus compiles to `web`. There are several places to change between "web" and "docroot" if you want to use "docroot."

1. Update [.composer.json](https://github.com/promet/provus/blob/develop/.composer.json)
2. Update [.docksal/docksal.env](https://github.com/promet/provus/blob/develop/.docksal/docksal.env)
3. Update [tests/behat/behat.yml](https://github.com/promet/provus/blob/develop/tests/behat/behat.yml)

## Update the theme

If you want to use the Provus theme it should be [copy/pasted](https://en.wikipedia.org/wiki/Anti-pattern) to the new theme name. Config will also need to be updated:

1. Ensure ``THEME_NAME`` is updated in ``.docksal/docksal.env``
2. Ensure "web" directory does not exist locally (``rm -rf web``)
3. Run ``fin new-site``
4. Run ``fin init-site``
