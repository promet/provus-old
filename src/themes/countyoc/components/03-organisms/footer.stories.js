import React from 'react';
import { useEffect } from '@storybook/client-api';

import './site/site-footer/site-footer';

import logoData from './logo-footer/logo-footer.twig';

export default { title: 'Organisms/Footer' };

export const logo = () => {
  useEffect(() => Drupal.attachBehaviors(), []);
  return <div dangerouslySetInnerHTML={{ __html: logoData() }} />;
};
