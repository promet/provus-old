import React from 'react';
import { useEffect } from '@storybook/client-api';

import '../../02-molecules/menus/main-menu/main-menu';

import searchTwig from './search.twig';

import mainMenuData from '../../02-molecules/menus/main-menu/main-menu.yml';
import breadcrumbData from '../../02-molecules/menus/breadcrumbs/breadcrumbs.yml';
import socialMenuData from '../../02-molecules/menus/social/social-menu.yml';
import footerMenuData from '../../02-molecules/menus/inline/inline-menu.yml';

import searchInputData from '../../02-molecules/form/search/search-banner.yml';
import searchResultsData from '../../02-molecules/content/search-results.yml';

/**
 * Storybook Definition.
 */
export default { title: 'Pages/Feature Pages' };

export const search = () => {
  useEffect(() => Drupal.attachBehaviors(), []);
  return (
    <div
      dangerouslySetInnerHTML={{
        __html: searchTwig({
          page_layout_modifier: 'contained',
          ...searchInputData,
          ...searchResultsData,
          ...mainMenuData,
          ...breadcrumbData,
          ...socialMenuData,
          ...footerMenuData,
          card__link__text: 'Click here',
        }),
      }}
    />
  );
};
