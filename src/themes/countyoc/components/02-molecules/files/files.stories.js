import React from 'react';
import { useEffect } from '@storybook/client-api';

import filesTableStructure from './files-table.twig';
import filesListStructure from './files-list.twig';
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
        __html: filesTableStructure({ ...filesData }),
      }}
    />
  );
};

export const filesList = () => {
  useEffect(() => Drupal.attachBehaviors(), []);
  return (
    <div
      dangerouslySetInnerHTML={{
        __html: filesListStructure({ ...filesData }),
      }}
    />
  );
};
