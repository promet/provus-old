import React from 'react';
import { useEffect } from '@storybook/client-api';

import filesStructure from './files.twig';
import filesData from './files.yml';

/**
 * Storybook Definition.
 */
export default { title: 'Molecules/Files' };

export const filesTable = () => {
  useEffect(() => Drupal.attachBehaviors(), []);
  return (
    <div
      dangerouslySetInnerHTML={{
        __html: filesStructure({ ...filesData }),
      }}
    />
  );
};
