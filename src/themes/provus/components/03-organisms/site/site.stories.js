import React from 'react';
import { useEffect } from '@storybook/client-api';

import footerTwig from './site-footer/site-footer.twig';
import siteHeader from './site-header/site-header.twig';
import footerSocial from '../../02-molecules/menus/social/social-menu.yml';
import footerMenu from './footer-menu.yml';
import siteHeaderData from './site-header/site-header.yml';
import mainMenubData from '../../02-molecules/menus/main-menu/main-menu.yml';
import '../../02-molecules/menus/main-menu/main-menu';

import './site-footer/site-footer';
import './site-header/site-header';

/**
 * Storybook Definition.
 */
export default { title: 'Organisms/Site' };

export const footer = () => {
  useEffect(() => Drupal.attachBehaviors(), []);
  return (
    <div
      dangerouslySetInnerHTML={{ __html: footerTwig({ ...footerSocial, ...footerMenu }) }}
    />
  );
};
export const header = () => {
  useEffect(() => Drupal.attachBehaviors(), []);
  return (
    <div
      dangerouslySetInnerHTML={{
        __html: siteHeader({ ...mainMenubData, ...siteHeaderData }),
      }}
    />
  );
};
