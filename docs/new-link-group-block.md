# Creating a New Component for the Link Group

The [Link Group](https://promet.github.io/provus/block-types.html#link-group) is a block that offers several view modes as well as content types for placing widgets.

Steps for creating a new component for the link group include:

## Component Library

### 1. Create a card in the component library

In the site theme, create a new [card](https://github.com/promet/provus/tree/develop/src/themes/provus/components/02-molecules/card) by following the example in the ``card`` folder of the component library.

### 2. Create a new wrapper for the card

This step is only neccarsy if the card can only be used in certain contexts that require a wrapper around it. This is true for components that use Javascript like a carousel.

See the [Groups](https://github.com/promet/provus/tree/develop/src/themes/provus/components/03-organisms/group) as an example. The group or other template should embed the card itself. See [multi-card](https://github.com/promet/provus/blob/develop/src/themes/provus/components/03-organisms/group/carousel/multicard/multicard.twig) as an example.

## Drupal Development

### 3. Create View Mode

Create a new view mode for the card at `/admin/structure/display-modes/view` in the Content area.

### 4. Add the View Mode to the Content Types and Inline Item Block

Each content type exposed in the Link Group needs to implement the new view mode. It should include the fields necessary for the desired output.

Additionally a view mode should be added to the [Inline item block](https://live-provus-playground.pantheonsite.io/admin/structure/block/block-content/manage/inline_item/display).

### 5. Add the wrapper description to the Link Group display list

Add the card wrapper or group to the [list of group displays](https://github.com/promet/provus/blob/a4122d8deb9dd61d4907cf5d83e5176290530fb0/config/default/field.storage.block_content.field_group_display.yml#L12).

### 6. Add the group dispaly to ``theme_override_item_display()``

Add the display id from "#5" to [theme_override_item_display()](https://github.com/promet/provus/blob/a4122d8deb9dd61d4907cf5d83e5176290530fb0/src/themes/provus/provus.theme#L884) and select the desired card for that display.

## Drupal Theming

### 7. Create template for the card

Create a template for the inline item block for the card. See [card borderd](https://github.com/promet/provus/blob/a4122d8deb/src/themes/provus/templates/block/block--inline-item--card-bordered.html.twig) as an example.

### 8. Create a template for the link group

See [Carousel Multi](https://github.com/promet/provus/blob/a4122d8deb9dd61d4907cf5d83e5176290530fb0/src/themes/provus/templates/block/block--link-group--carousel-multi.html.twig) for an example.


