# Setting up on Pantheon
This assumes you have Terminus already installed.

1. Create Pantheon Token using https://pantheon.io/docs/machine-tokens.
2. Authenticate to Pantheon using the Token: ``terminus auth:login --machine-token=<token_here>``
3. Add the token to ``docksal.env`` using ``SECRET_TERMINUS_TOKEN``
3. Create new site on Pantheon with empty upstream ``terminus site:create <site_name> <readable_label> <empty>``
![empty_patheon_project](images/empty_pantheon_project.png)

OR if you have an existing site you can set it to an empty upstream: ``terminus site:upstream:set newsite empty``

4. Clone provus or site already in git locally ``git clone git@github.com:promet/provus.git newsite"``
5. Add pantheon remote to docksal.env ``REMOTE_GIT_REPO=ssh://////////``
6. Build and push: ``fin deploy``
7. Copy aliases to project: ``terminus aliases; cp ~/.drush/sites/newsite.site.yml drush/sites/.``
8. Install site; ``fin drush @newsite.dev si minimal;fin drush @newsite.dev config-set system.site uuid 1aa3a078-6e7f-4336-b6d9-bec3b1e61561 -y; fin drush @newsite.dev cim -y``
9. Profit
