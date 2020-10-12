# Moving to Canonical or Production Database

Once a site is using a canonical database, change the [init-site](https://github.com/promet/provus/blob/develop/.docksal/commands/init-site) command to no longer rebuild but pull from the dev, test, or production databases.

```diff
- fin drush si -y --account-pass=admin
- fin drush config-set system.site uuid ${UUID} -y
+ fin pull db
```
