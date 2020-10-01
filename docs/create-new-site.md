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

1. Copy the theme: ``mv src/themes/provus src/themes/newtheme`
2. Update theme filenames: ``rename 's/provus/newtheme/' src/themes/newtheme/*.*``
3. Update theme strings: ``sed -i 's/provus/newtheme/g' src/themes/newtheme/*.*``
4. Update blocks ``sed -i 's/provus/newtheme/g' config/default/block.*.yml``
5. Update default theme ``sed -i 's/provus/newtheme/g' config/default/theme.settings.yml; ``
5. Add "newtheme" in theme section to ``config/default/core.extensions.yml``
7. Update responsive images ``sed -i 's/provus/newtheme/g' config/default/responsive_image.styles.*.yml``
8. Run config import ``fin drush cim -y``
9. Unininstall the "provus" theme in ``/admin/appearance``
10. Remove "provus" from ``config/default/core.extensions.yml`` or export config
11. Delete old provus theme ``rm src/themes/provus``

