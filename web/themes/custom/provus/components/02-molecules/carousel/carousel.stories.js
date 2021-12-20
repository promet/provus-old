import React from 'react';
import { useEffect } from '@storybook/client-api';

import './carousel';

import carouselStructure from './carousel.twig';
import carouselData from './carousel.yml';

/**
 * Storybook Definition.
 */
export default { title: 'Molecules/Carousel' };

export const carousel = () => {
  useEffect(() => Drupal.attachBehaviors(), []);
  return <div dangerouslySetInnerHTML={{ __html: carouselStructure(carouselData) }} />;
};
