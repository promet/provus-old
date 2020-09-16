import React from 'react';

import locationTeaserStructure from './location-teaser.twig';

import locationTeaserData from './location-teaser.yml';

/**
 * Storybook Definition.
 */
export default { title: 'Molecules/Teasers' };

export const locationTeaser = () => (
  <div dangerouslySetInnerHTML={{ __html: locationTeaserStructure(locationTeaserData) }} />);
