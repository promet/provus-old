import React from 'react';

import contentAndImageStructure from './content-and-image/content-and-image.twig';
import contentAndImageData from './content-and-image/content-and-image.yml';
import locationStructure from './content-top/content-top-location.twig';
import locationData from './content-top/content-top-location.yml';
import eventStructure from './content-top/content-top-event.twig';
import eventData from './content-top/content-top-event.yml';
import personCardStructure from './person-card/person-card.twig';
import personCardData from './person-card/person-card.yml';

export default { title: 'Organisms/Content' };

export const contentAndImage = () => (
  <div dangerouslySetInnerHTML={{ __html: contentAndImageStructure(contentAndImageData) }} />);

export const contentTopLocation = () => (
  <div dangerouslySetInnerHTML={{ __html: locationStructure(locationData) }} />);

export const contentTopEvent = () => (
  <div dangerouslySetInnerHTML={{ __html: eventStructure(eventData) }} />);

export const personCard = () => (
  <div dangerouslySetInnerHTML={{ __html: personCardStructure(personCardData) }} />);
