import React from 'react';

import locationStructure from './location.twig';
import locationData from './location.yml';
import phoneStructure from './phone.twig';
import phoneData from './phone.yml';
import emailStructure from './email.twig';
import emailData from './email.yml';
import locationHoursStructure from './location-hours.twig';
import locationHoursData from './location-hours.yml';
import dateAndTimeStructure from './date-time.twig';
import dateAndTimeData from './date-time.yml';
import imageStructure from './image.twig';
import imageData from './image.yml';
import filelinkStructure from './filelink.twig';
import filelinkData from './filelink.yml';

/**
 * Storybook Definition.
 */
export default { title: 'Molecules/Info' };

export const location = () => (
  <div dangerouslySetInnerHTML={{ __html: locationStructure(locationData) }} />);

export const phone = () => (
  <div dangerouslySetInnerHTML={{ __html: phoneStructure(phoneData) }} />);

export const email = () => (
  <div dangerouslySetInnerHTML={{ __html: emailStructure(emailData) }} />);

export const locationHours = () => (
  <div dangerouslySetInnerHTML={{ __html: locationHoursStructure(locationHoursData) }} />);

export const dateAndTime = () => (
  <div dangerouslySetInnerHTML={{ __html: dateAndTimeStructure(dateAndTimeData) }} />);

export const image = () => (
  <div dangerouslySetInnerHTML={{ __html: imageStructure(imageData) }} />);

export const fileLink = () => (
  <div dangerouslySetInnerHTML={{ __html: filelinkStructure(filelinkData) }} />);
