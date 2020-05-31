import React from 'react';
import { useEffect } from '@storybook/client-api';
import {
  withKnobs,
  text,
  boolean,
} from '@storybook/addon-knobs';

import './banner';

import bannerStructure from './banner.twig';
import bannerData from './banner.yml';
import insideBannerStructure from './inside-banner.twig';
import insideBannerData from './inside-banner.yml';

export default {
  title: 'Organisms/Banner',
  decorators: [withKnobs],
};

export const banner = () => {
  useEffect(() => Drupal.attachBehaviors(), []);
  const search = boolean('Search bar', false);
  const heading = text('Header', 'Welcome to Southport County');
  bannerData.heading = heading;
  bannerData.settings.search = search;
  return <div dangerouslySetInnerHTML={{ __html: bannerStructure(bannerData) }} />;
};

export const insideBanner = () => (
  <div dangerouslySetInnerHTML={{ __html: insideBannerStructure(insideBannerData) }} />);
