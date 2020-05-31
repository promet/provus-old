# Provus 


## Installation

[Install Docksal](https://docs.docksal.io/getting-started/setup/).

Once you've setup docker run:

``fin init``

This will install the database and get you all ready to roll.

### Docksal Commands

Most commands needed to run the project can be found by typing ``fin`` and viewing the "Custom commands".

```yml
Custom commands:
  behat                    	Test behat
  init                     	Initialize stack and site (full reset)
  init-site                	Initialize/reinstall site
  pa11y                    	Test pa11y
  php-cbf                   Runs phpcbf	
  php-cs                    Runs phpcs	
  phpunit                  	Runs PHPUnit tests found in custom code
  storybook                	Run storybook tool locally
  test                     	Test site installation
  test-content             	Imports test content
  theme-lint               	lint theme js
```

## Test

Currently supported:

  * phpUnit ``fin phpunit``
  * behat ``fin behat``
  * pa11y ``fin pa11y``

### Theming

1. Edit scss files.
2. Run ``fin build-theme`` to compile sass files

To "watch" the files update in real-time run storybook command below.

### Storybook

#### Run Locally

To run Storybook ``fin storybook`` and click on the local network link:

![image](https://user-images.githubusercontent.com/512243/74872340-0ae99200-532b-11ea-9f67-2b4a4c68ea89.png)
