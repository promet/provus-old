import React from 'react';
import { useEffect } from '@storybook/client-api';

import feedStructure from './social-feed.twig';
import feedDataYoutube from './social-feed-youtube.yml';
import feedDataTwitter from './social-feed-twitter.yml';
import instagramStructure from './instagram-widget.twig';

export default { title: 'Organisms/Social-Feed' };

export const socialFeedYoutube = () => {
  useEffect(() => Drupal.attachBehaviors(), []);
  return <div dangerouslySetInnerHTML={{ __html: feedStructure(feedDataYoutube) }} />;
};

export const socialFeedTwitter = () => {
  useEffect(() => Drupal.attachBehaviors(), []);
  return <div dangerouslySetInnerHTML={{ __html: feedStructure(feedDataTwitter) }} />;
};

export const instagramWidget = () => {
  useEffect(() => Drupal.attachBehaviors(), []);
  return <div dangerouslySetInnerHTML={{ __html: instagramStructure() }} />;
};
