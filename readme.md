# Setup storybook for a project

## Add initial storybook

1. Clone provus `storybook` branch
	```
	git clone --branch storybook https://github.com/promet/provus.git
	```

1. Add new project repo's remote
	```
	git remote add <project> https://github.com/promet/<project>.git
	```

1. Push `storybook` branch to your project's remote
	```
	git push -u <project> storybook
	```
Travis is expected to fail at this point.

## Configure github pages

Make sure travis has completely run.

1. From repo's github settings, ensure the following is set in Github Pages
	- Source Branch: storybook-www
	- Folder: /docs

## Configure hidden variables in Travis

1. Add the following variables:
	- GH_TOKEN - The github token that can commit to your code repo
	- STYLEGUIDE_PARTIAL_URL - gibberish part of the URL created using
	```
	date | sha256sum | cut -d' ' -f1
	```
## Populate real storybook

At this point, storybook will be accessible from URL https://promet.github.io/<project>/<STYLEGUIDE_PARTIAL_URL> but with dummy content.

1. Run the following from `develop` branch:

	```
	fin storybook-deploy
	```
It may take some time but once it's finished, real storybook/styleguide will be in the URL https://promet.github.io/<project>/<STYLEGUIDE_PARTIAL_URL>
