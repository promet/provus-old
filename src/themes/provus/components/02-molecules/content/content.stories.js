import React from 'react';

import searchResultStructure from './search-results.twig';
import searchResultData from './search-results.yml';

/**
 * Storybook Definition.
 */
export default { title: 'Molecules/Content' };

export const searchResults = () => (
  <div dangerouslySetInnerHTML={{ __html: searchResultStructure(searchResultData) }} />);
