# Setup storybook for a project

## Update obscured URL

Edit ``STORYBOOK_FOLDER`` in ``.docksal/docksal.env`` if you wish to change the URL that will be published to.

## Configure github pages

Make sure travis has completely run.

1. From repo's github settings, ensure the following is set in Github Pages
	- Source Branch: storybook-www
	- Folder: /storybook

## Populate real storybook

At this point, storybook will be accessible from URL https://promet.github.io/<project>/<STORYBOOK_FOLDER> but with dummy content.

1. Run the following from `develop` branch:

	```
	fin storybook-deploy
	```
It may take some time but once it's finished, real storybook/styleguide will be in the URL https://promet.github.io/<project>/<STORYBOOK_FOLDER>
