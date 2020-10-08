# Setting up on Pantheon
This assumes you have Terminus already installed.

1. Create Pantheon Token using https://pantheon.io/docs/machine-tokens.
2. Authenticate to Pantheon using the Token: ``terminus auth:login --machine-token=<token_here>``
3. Add the token to ``docksal-local.env`` using ``SECRET_TERMINUS_TOKEN=``
4. Create new site on Pantheon with empty upstream ``terminus site:create <site_name> <readable_label> <empty>``
![empty_patheon_project](images/empty_pantheon_project.png)

OR if you have an existing site you can set it to an empty upstream: ``terminus site:upstream:set newsite empty``

5. Clone provus or site already in git locally ``git clone git@github.com:promet/provus.git newsite"``
6. Add pantheon remote to docksal.env ``REMOTE_GIT_REPO=ssh://////////``
7. Build and push: ``fin deploy``
8. Copy aliases to project: ``terminus aliases; cp ~/.drush/sites/newsite.site.yml drush/sites/.``
9. Install site; ``fin drush @newsite.dev si minimal;fin drush @newsite.dev config-set system.site uuid 1aa3a078-6e7f-4336-b6d9-bec3b1e61561 -y; fin drush @newsite.dev cim -y``
10. Update pantheon aliases through terminus ``terminus aliases`` and copy alias ``cp ~/.drush/sites/newsite.alias.yml drush/sites/.``
