import React from 'react';
import { useEffect } from '@storybook/client-api';

import fullWidthTwig from './full-width.twig';
import withSidebarTwig from './with-sidebar.twig';

import mainMenu from '../02-molecules/menus/main-menu/main-menu.yml';
import socialMenu from '../02-molecules/menus/social/social-menu.yml';
import footerMenu from '../03-organisms/site/footer-menu.yml';

/**
 * Storybook Definition.
 */
export default { title: 'Templates/Layouts' };

export const fullWidth = () => {
  useEffect(() => Drupal.attachBehaviors(), []);
  return (
    <div
      dangerouslySetInnerHTML={{
        __html:
        fullWidthTwig({ ...mainMenu, ...socialMenu, ...footerMenu }),
      }}
    />
  );
};
export const withSidebar = () => {
  useEffect(() => Drupal.attachBehaviors(), []);
  return (
    <div
      dangerouslySetInnerHTML={{
        __html:
        withSidebarTwig({ ...mainMenu, ...socialMenu, ...footerMenu }),
      }}
    />
  );
};
