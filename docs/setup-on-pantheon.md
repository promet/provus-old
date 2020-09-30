# Setting up on Pantheon

1. Create new site on pantheon "newsite"
2. Create an empty upstream: ``terminus site:upstream:set newsite empty``
3. Clone provus or site already in git locally ``git clone git@github.com:promet/provus.git newsite"
4. Add pantheon remote to docksal.env ``REMOTE_GIT_REPO=ssh://////////``
5. Build and push: ``fin deploy``
