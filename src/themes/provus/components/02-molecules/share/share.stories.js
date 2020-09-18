import React from 'react';

import shareStructure from './share-this/share-this.twig';
import shareData from './share-this/share-this.yml';

/**
 * Storybook Definition.
 */
export default { title: 'Molecules/Share' };

export const shareThis = () => (
  <div dangerouslySetInnerHTML={{ __html: shareStructure(shareData) }} />);
