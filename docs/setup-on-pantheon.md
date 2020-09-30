# Setting up on Pantheon

1. Create new site on pantheon "newsite"
2. Create an empty upstream: ``terminus site:upstream:set newsite empty``
3. Clone provus or site already in git locally ``git clone git@github.com:promet/provus.git newsite"
4. Add pantheon remote to docksal.env ``REMOTE_GIT_REPO=ssh://////////``
5. Build and push: ``fin deploy``
6. Copy aliases to project: ``terminus aliases; cp ~/.drush/sites/newsite.site.yml drush/sites/.``
7. Install site; ``fin drush @newsite.dev si minimal;fin drush @newsite.dev config-set system.site uuid 1aa3a078-6e7f-4336-b6d9-bec3b1e61561 -y; fin drush @newsite.dev cim -y``
8. Profit
