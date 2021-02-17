# Creating a New Component for the Link Group

The [Link Group](https://promet.github.io/provus/block-types.html#link-group) is a block that offers several view modes as well as content types for placing widgets.

Steps for creating a new component for the link group include:

### 1. Create a card in the component library

In the site theme, create a new [card](https://github.com/promet/provus/tree/develop/src/themes/provus/components/02-molecules/card) by following the example in the ``card`` folder of the component library.

### 2. Create a new wrapper for the card

This step is only neccarsy if the card can only be used in certain contexts that require a wrapper around it. This is true for components that use Javascript like a carousel.

See the [Groups](https://github.com/promet/provus/tree/develop/src/themes/provus/components/03-organisms/group) as an example. The group or other template should embed the card itself. See [multi-card](https://github.com/promet/provus/blob/develop/src/themes/provus/components/03-organisms/group/carousel/multicard/multicard.twig) as an example.

### 3. Update the Link Group
