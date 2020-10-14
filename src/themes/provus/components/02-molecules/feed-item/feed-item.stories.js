import React from 'react';
import { useEffect } from '@storybook/client-api';

import itemStructureYoutube from './feed-item-youtube.twig';
import itemDataYoutube from './feed-item-youtube.yml';
import itemStructureTwitter from './feed-item-twitter.twig';
import itemDataTwitter from './feed-item-twitter.yml';

export default { title: 'Molecules/Feed-Item' };

export const feedItemYoutube = () => {
  useEffect(() => Drupal.attachBehaviors(), []);
  return <div dangerouslySetInnerHTML={{ __html: itemStructureYoutube(itemDataYoutube) }} />;
};

export const feedItemTwitter = () => {
  useEffect(() => Drupal.attachBehaviors(), []);
  return <div dangerouslySetInnerHTML={{ __html: itemStructureTwitter(itemDataTwitter) }} />;
};
