import React from 'react';
import { useEffect } from '@storybook/client-api';

import utility from './utility/_utility-wrapper.twig';

import './utility/utility';

import mainMenubData from '../02-molecules/menus/main-menu/main-menu.yml';
import navigation from './navigation/navigation-wrapper.twig';
import './navigation/navigation';

export default { title: 'Organisms/Header' };

export const Utility = () => {
  useEffect(() => Drupal.attachBehaviors(), []);
  return <div dangerouslySetInnerHTML={{ __html: utility() }} />;
};

export const Navigation = () => {
  useEffect(() => Drupal.attachBehaviors(), []);
  return <div dangerouslySetInnerHTML={{ __html: navigation(mainMenubData) }} />;
};
