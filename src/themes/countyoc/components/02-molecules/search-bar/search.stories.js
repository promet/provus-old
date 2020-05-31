import React from 'react';
import { useEffect } from '@storybook/client-api';

import searchInputStructure from './search-input.twig';
import searchInputData from './search-input.yml';
import searchResultStructure from './search-results.twig';
import searchResultData from './search-results.yml';

/**
 * Storybook Definition.
 */
export default { title: 'Molecules/Search' };

export const searchInput = () => {
  useEffect(() => Drupal.attachBehaviors(), []);
  return (
    <div
      dangerouslySetInnerHTML={{
        __html: searchInputStructure({ ...searchInputData }),
      }}
    />
  );
};

export const searchResults = () => (
  <div dangerouslySetInnerHTML={{ __html: searchResultStructure(searchResultData) }} />);
