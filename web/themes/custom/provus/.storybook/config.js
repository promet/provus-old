import { configure, addDecorator, addParameters } from "@storybook/react"
import { withA11y } from '@storybook/addon-a11y';
import { action } from "@storybook/addon-actions"
import withExternalLinks from 'storybook-external-links';
import './jquery-global.js';
import once from 'jquery-once';

// Adds google translate.
const url = '//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit';
const externalLinkDecorator = withExternalLinks(url);
addDecorator(externalLinkDecorator);

// Add bootstrap js.
import '../libraries/bootstrap/dist/js/bootstrap.min';
import '../libraries/lightslider/src/js/lightslider';

// Theming
import emulsifyTheme from './emulsifyTheme';

addParameters({
  options: {
    theme: emulsifyTheme,
  },
});

// GLOBAL CSS
import '../components/style.scss';

addDecorator(withA11y)

const Twig = require('twig')
const twigDrupal = require('twig-drupal-filters')
const twigBEM = require('bem-twig-extension');
const twigAddAttributes = require('add-attributes-twig-extension');

Twig.cache();

twigDrupal(Twig);
twigBEM(Twig);
twigAddAttributes(Twig);

// If in a Drupal project, it's recommended to import a symlinked version of drupal.js.
import './_drupal.js';

// automatically import all files ending in *.stories.js
configure(require.context('../components', true, /\.stories\.js$/), module);
