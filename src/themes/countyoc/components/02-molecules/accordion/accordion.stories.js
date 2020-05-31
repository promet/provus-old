import React from 'react';
import { useEffect } from '@storybook/client-api';

import accordionStructure from './accordion.twig';

import accordionData from './accordion.yml';


/**
 * Storybook Definition.
 */
export default { title: 'Molecules/Accordion' };

export const Accordion = () => {
  useEffect(() => Drupal.attachBehaviors(), []);
  return <div dangerouslySetInnerHTML={{ __html: accordionStructure(accordionData) }} />;
};
