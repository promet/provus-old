# Provus 

This is a starter project for Drupal 8/9 projects that provides the following:

* [Emusilfy](http://emulsify.io)-based theme with large number of [beautiful components](https://promet.github.io/ps_component)
* Pre-configured Drupal blocks that integrate with the [beautiful components](https://promet.github.io/ps_component)
* The following basic content types:
  * Articles (News)
  * Pages
  * Events
  * Locations
  * People
  * Landing page
  
This is meant as a starter to clone and build off of. It is not meant to be an upstream.

## Drag and Drop Landing Pages

This project creates a starting point for re-usable blocks that can be used with Layout Builder for drag and drop page building.

## Block Types

The following block types are included:

### Link Group

This is the "swiss army knife" block that allows users to create lists of content either dynamically (through Views in the backend) or manually. The current user-interface:

![image](https://user-images.githubusercontent.com/512243/83434225-0e031c80-a408-11ea-85b8-fcfe43dc850c.png)

### Number Group

A block to create a "By the Numbers" group:

![image](https://user-images.githubusercontent.com/512243/83434448-8833a100-a408-11ea-8ed7-280de913c0bf.png)


### Banner



### Accordian

### Others

There are others including media items and buttons.




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
