import React from 'react';
import { storiesOf } from '@storybook/react';
import { hrefTo } from '@storybook/addon-links';
import { useEffect } from '@storybook/client-api';

import '../../02-molecules/menus/main-menu/main-menu';

import home from './home.twig';

import bannerData from '../../03-organisms/banner/banner.yml';
import mainMenuData from '../../02-molecules/menus/main-menu/main-menu.yml';
import breadcrumbData from '../../02-molecules/menus/breadcrumbs/breadcrumbs.yml';
import socialMenuData from '../../02-molecules/menus/social/social-menu.yml';
import footerMenuData from '../../03-organisms/site/footer-menu.yml';
import c3 from '../../03-organisms/group/carousel/3-item/carousel-3-items.yml';
import c4 from '../../03-organisms/group/4-card-featured-group/4-card-featured-group.yml';
import cm from '../../03-organisms/group/carousel/multicard/multicard.yml';
import btn from '../../03-organisms/group/by-the-numbers/by-the-numbers.yml';
import cr from '../../03-organisms/group/column-row/column-row.yml';
import tabbed from '../../03-organisms/group/tabbed/tabbed.yml';

bannerData.settings.search = true;

const items = {
  c3,
  c4,
  cm,
  btn,
  cr,
  tabbed,
};

// Adds search bar.
bannerData.settings.search = true;

/**
 * Storybook Definition.
 */
hrefTo('Pages/Content Types', 'Article').then((url) => {
  // TODO: Can't figure out how to link pages with hrefTo and storiesOf.
  storiesOf('Pages/Landing Pages', module)
    .add('Home', () => {
      useEffect(() => Drupal.attachBehaviors(), []);
      return (
        <div dangerouslySetInnerHTML={{
          __html: home({
            page_layout_modifier: 'contained',
            ...bannerData,
            ...items,
            ...mainMenuData,
            ...breadcrumbData,
            ...socialMenuData,
            ...footerMenuData,
            card_link_url: url,
            card__link__text: 'Click here',
          }),
        }}
        />
      );
    });
});
