import React from 'react';

import lineStructure from './line.twig';

/**
 * Storybook Definition.
 */
export default { title: 'Atoms/Line' };

export const lineSeparator = () => (
  <div dangerouslySetInnerHTML={{ __html: lineStructure() }} />);
