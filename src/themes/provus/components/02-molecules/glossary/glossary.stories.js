import React from 'react';

import glossary from './glossary.twig';

/**
 * Storybook Definition.
 */
export default { title: 'Molecules/Glossary' };

export const glossaryExample = () => (
  <div dangerouslySetInnerHTML={{ __html: glossary() }} />);
