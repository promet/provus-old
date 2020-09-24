import React from 'react';
import { useEffect } from '@storybook/client-api';

import './banner';

import bannerStructure from './banner.twig';
import bannerData from './banner.yml';
import insideBannerStructure from './inside-banner.twig';
import insideBannerData from './inside-banner.yml';

export default {
  title: 'Organisms/Banner',
};

export const banner = () => {
  useEffect(() => Drupal.attachBehaviors(), []);
  bannerData.settings.search = false;
  return <div dangerouslySetInnerHTML={{ __html: bannerStructure(bannerData) }} />;
};

export const bannerWithSearch = () => {
  useEffect(() => Drupal.attachBehaviors(), []);
  bannerData.settings.search = true;
  return <div dangerouslySetInnerHTML={{ __html: bannerStructure(bannerData) }} />;
};

export const bannerWithPic = () => {
  useEffect(() => Drupal.attachBehaviors(), []);
  bannerData.settings.search = false;
  bannerData.settings.picture = true;
  bannerData.logo_src = 'images/OOCR_Logo.png';
  bannerData.heading = '';
  bannerData.body = '';
  return <div dangerouslySetInnerHTML={{ __html: bannerStructure(bannerData) }} />;
};

export const insideBanner = () => (
  <div dangerouslySetInnerHTML={{ __html: insideBannerStructure(insideBannerData) }} />);
