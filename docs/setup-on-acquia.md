# Setting up on Acquia 

1. Add Acquia remote to docksal.env ``REMOTE_GIT_REPO=ssh://////////``
2. Add desired Acquia remote develop branch to docksal.env ``REMOTE_DEVELOP_BRANCH=acquia-develop``
3. Run initial deployment ``fin deploy``
4. Add Acquia connector, Acquia Search, and Acquia purge modules and settings
5. Ensure hooks work on dev environment. Update according to site needs.
6. Download and copy alias ``cp ~/.drush/sites/newsite.alias.yml drush/sites/.`` 
7. Optionally remove ``pantheon.yml`` which is only for Pantheon.
