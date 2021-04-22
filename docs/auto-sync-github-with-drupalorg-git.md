# Sync Mechanism

Done via Github Actions

# Configurations

Main yaml config is in .github/workflows/develop-sync.yml

Make sure to add the following secrets (in https://github.com/promet/provus/settings > Secrets):

* DRUPAL_ORG_SSH_KEY - ssh private key of one of the maintainer(s)
* DRUPAL_REPO_URL - make sure it is 'git@git.drupal.org:project/provus.git'
* KNOWN_HOSTS - Get it using command `ssh-keyscan git.drupal.org`
* SSH_CONFIG - Currently set as follows:
```
host git.drupalcode.org
     user icasimpan
     IdentityFile /home/runner/.ssh/id_rsa
```
