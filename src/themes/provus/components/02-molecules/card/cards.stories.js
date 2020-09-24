import React from 'react';
import { useEffect } from '@storybook/client-api';

import backgroundCardStructure from './card-bg.twig';
import borderedCardStructure from './card-bordered.twig';
import byTheNumberStructure from './by-the-number.twig';
import cardStructure from './card.twig';
import cardMiniStructure from './card-mini.twig';
import columnTextWithLeftImageStructure from './column-text-left-img.twig';
import columnTextWithRightImageStructure from './column-text-right-img.twig';
import eventCardStructure from './event-card.twig';
import personCardStructure from './person-card.twig';
import searchCardStructure from './card-search.twig';
import simpleColumnTextStructure from './simple-column-text.twig';
import promoCardStructure from './card-promo.twig';
import squareImgStructure from './square-img.twig';
import circleImgStructure from './circle-img.twig';
import textLinkStructure from './text-link.twig';
import textLinkWithIconStructure from './text-link-with-icon.twig';
import twoColumnTextStructure from './two-column-text.twig';
import threeColumnTextStructure from './three-column-text.twig';

import backgroundCardData from './card-bg.yml';
import borderedCardData from './card-bordered.yml';
import byTheNumberData from './by-the-number.yml';
import cardData from './card.yml';
import cardMiniData from './card-mini.yml';
import columnTextWithLeftImageData from './column-text-left-img.yml';
import columnTextWithRightImageData from './column-text-right-img.yml';
import eventCardData from './event-card.yml';
import personCardData from './person-card.yml';
import promoCardData from './card-promo.yml';
import searchCardData from './card-search.yml';
import simpleColumnTextData from './simple-column-text.yml';
import squareImgData from './square-img.yml';
import circleImgData from './circle-img.yml';
import textLinkData from './text-link.yml';
import textLinkWithIconData from './text-link-with-icon.yml';
import twoColumnTextData from './two-column-text.yml';
import threeColumnTextData from './three-column-text.yml';

/**
 * Storybook Definition.
 */
export default { title: 'Molecules/Cards' };

export const backgroundCard = () => (
  <div dangerouslySetInnerHTML={{ __html: backgroundCardStructure(backgroundCardData) }} />);
export const borderedCard = () => (
  <div dangerouslySetInnerHTML={{ __html: borderedCardStructure(borderedCardData) }} />);
export const byTheNumber = () => (
  <div dangerouslySetInnerHTML={{ __html: byTheNumberStructure(byTheNumberData) }} />);
export const card = () => <div dangerouslySetInnerHTML={{ __html: cardStructure(cardData) }} />;
export const cardMini = () => (
  <div dangerouslySetInnerHTML={{ __html: cardMiniStructure(cardMiniData) }} />);
export const columnTextWithLeftImage = () => {
  useEffect(() => Drupal.attachBehaviors(), []);
  return (
    <div
      dangerouslySetInnerHTML={{
        __html: columnTextWithLeftImageStructure({ ...columnTextWithLeftImageData }),
      }}
    />
  );
};
export const columnTextWithRightImage = () => {
  useEffect(() => Drupal.attachBehaviors(), []);
  return (
    <div
      dangerouslySetInnerHTML={{
        __html: columnTextWithRightImageStructure({ ...columnTextWithRightImageData }),
      }}
    />
  );
};
export const eventCard = () => (
  <div dangerouslySetInnerHTML={{ __html: eventCardStructure(eventCardData) }} />);
export const promoCard = () => (
  <div dangerouslySetInnerHTML={{ __html: promoCardStructure(promoCardData) }} />);
export const squareImage = () => (
  <div dangerouslySetInnerHTML={{ __html: squareImgStructure(squareImgData) }} />);
export const circleImage = () => (
  <div dangerouslySetInnerHTML={{ __html: circleImgStructure(circleImgData) }} />);
export const textLink = () => (
  <div dangerouslySetInnerHTML={{ __html: textLinkStructure(textLinkData) }} />);
export const textLinkWithIcon = () => (
  <div dangerouslySetInnerHTML={{ __html: textLinkWithIconStructure(textLinkWithIconData) }} />);
export const personCard = () => (
  <div dangerouslySetInnerHTML={{ __html: personCardStructure(personCardData) }} />);
export const searchCard = () => (
  <div dangerouslySetInnerHTML={{ __html: searchCardStructure(searchCardData) }} />);
export const simpleColumnTex = () => {
  useEffect(() => Drupal.attachBehaviors(), []);
  return (
    <div
      dangerouslySetInnerHTML={{
        __html: simpleColumnTextStructure({ ...simpleColumnTextData }),
      }}
    />
  );
};
export const threeColumnTex = () => {
  useEffect(() => Drupal.attachBehaviors(), []);
  return (
    <div
      dangerouslySetInnerHTML={{
        __html: threeColumnTextStructure({ ...threeColumnTextData }),
      }}
    />
  );
};
export const twoColumnTex = () => {
  useEffect(() => Drupal.attachBehaviors(), []);
  return (
    <div
      dangerouslySetInnerHTML={{
        __html: twoColumnTextStructure({ ...twoColumnTextData }),
      }}
    />
  );
};
