import React from 'react';

import imageStructure from './image.twig';
import imageData from './image.yml';
import fullNameStructure from './full-name.twig';
import fullNameData from './full-name.yml';
import professionStructure from './profession.twig';
import professionData from './profession.yml';
import phoneStructure from './phone.twig';
import phoneData from './phone.yml';
import emailStructure from './email.twig';
import emailData from './email.yml';

/**
 * Storybook Definition.
 */
export default { title: 'Atoms/Person' };

export const image = () => (
  <div dangerouslySetInnerHTML={{ __html: imageStructure(imageData) }} />);

export const fullName = () => (
  <div dangerouslySetInnerHTML={{ __html: fullNameStructure(fullNameData) }} />);

export const profession = () => (
  <div dangerouslySetInnerHTML={{ __html: professionStructure(professionData) }} />);

export const phone = () => (
  <div dangerouslySetInnerHTML={{ __html: phoneStructure(phoneData) }} />);

export const email = () => (
  <div dangerouslySetInnerHTML={{ __html: emailStructure(emailData) }} />);
