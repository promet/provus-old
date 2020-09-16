import React from 'react';
import { useEffect } from '@storybook/client-api';

import collapsibleStructure from './collapsible.twig';

import collapsibleData from './collapsible.yml';

/**
 * Storybook Definition.
 */
export default { title: 'Molecules/Collapsible' };

export const collapsible = () => {
  useEffect(() => Drupal.attachBehaviors(), []);
  return <div dangerouslySetInnerHTML={{ __html: collapsibleStructure(collapsibleData) }} />;
};
