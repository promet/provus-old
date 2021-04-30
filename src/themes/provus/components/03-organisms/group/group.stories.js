import React from 'react';
import { useEffect } from '@storybook/client-api';

import './carousel/3-item/carousel-3-items';
import './carousel/multicard/multicard';
import './carousel/promo-card/carousel-promo-card';

import byTheNumberStructure from './by-the-numbers/by-the-numbers.twig';
import byTheNumberData from './by-the-numbers/by-the-numbers.yml';
import multicardStructure from './carousel/multicard/multicard.twig';
import multicardData from './carousel/multicard/multicard.yml';
import quicklinksStructure from './quicklinks/quicklinks.twig';
import quicklinksData from './quicklinks/quicklinks.yml';
import quicklinksDataIcons from './quicklinks/quicklinks-icons.yml';
import carousel3Structure from './carousel/3-item/carousel-3-items.twig';
import carousel3Data from './carousel/3-item/carousel-3-items.yml';
import fourCardFeaturedGroupStructure from './4-card-featured-group/4-card-featured-group.twig';
import fourCardFeaturedGroupData from './4-card-featured-group/4-card-featured-group.yml';
import tabbedStructure from './tabbed/tabbed.twig';
import tabbedQuicklinkStructure from './tabbed/tabbed_quicklist.twig';
import tabbedData from './tabbed/tabbed.yml';
import columnRowStructure from './column-row/column-row.twig';
import columnRowData from './column-row/column-row.yml';
import promoCardStructure from './carousel/promo-card/carousel-promo-card.twig';
import promoCardData from './carousel/promo-card/carousel-promo-card.yml';

export default { title: 'Organisms/Group' };

export const byTheNumber = () => {
  useEffect(() => Drupal.attachBehaviors(), []);
  return <div dangerouslySetInnerHTML={{ __html: byTheNumberStructure(byTheNumberData) }} />;
};

export const linkGroupMulticardCarousel = () => {
  useEffect(() => Drupal.attachBehaviors(), []);
  return (
    <div
      dangerouslySetInnerHTML={{
        __html: multicardStructure({ ...multicardData }),
      }}
    />
  );
};

export const linkGroupCarousel3Items = () => {
  useEffect(() => Drupal.attachBehaviors(), []);
  return (
    <div
      dangerouslySetInnerHTML={{
        __html: carousel3Structure({ ...carousel3Data }),
      }}
    />
  );
};

export const fourCardFeaturedGroup = () => {
  useEffect(() => Drupal.attachBehaviors(), []);
  return (
    <div
      dangerouslySetInnerHTML={{
        __html: fourCardFeaturedGroupStructure({ ...fourCardFeaturedGroupData }),
      }}
    />
  );
};

export const quicklinksList = () => {
  useEffect(() => Drupal.attachBehaviors(), []);
  return <div dangerouslySetInnerHTML={{ __html: quicklinksStructure(quicklinksData) }} />;
};

export const quicklinksListIcons = () => {
  useEffect(() => Drupal.attachBehaviors(), []);
  return <div dangerouslySetInnerHTML={{ __html: quicklinksStructure(quicklinksDataIcons) }} />;
};

export const tabbedList = () => {
  useEffect(() => Drupal.attachBehaviors(), []);
  return <div dangerouslySetInnerHTML={{ __html: tabbedStructure(tabbedData) }} />;
};

export const tabbedQuicklinkList = () => {
  useEffect(() => Drupal.attachBehaviors(), []);
  const tQs = { topics: quicklinksData, res: quicklinksDataIcons };
  return <div dangerouslySetInnerHTML={{ __html: tabbedQuicklinkStructure(tQs) }} />;
};

export const columnRow = () => {
  useEffect(() => Drupal.attachBehaviors(), []);
  return <div dangerouslySetInnerHTML={{ __html: columnRowStructure(columnRowData) }} />;
};

export const promoCard = () => {
  useEffect(() => Drupal.attachBehaviors(), []);
  return <div dangerouslySetInnerHTML={{ __html: promoCardStructure(promoCardData) }} />;
};
