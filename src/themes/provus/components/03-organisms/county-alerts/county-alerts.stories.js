import React from 'react';
import { useEffect } from '@storybook/client-api';

import alertsStructure from './county-alerts.twig';

/**
 * Storybook Definition.
 */
export default { title: 'Organisms/CountyAlerts' };

export const countyAlertsExamples = () => {
  useEffect(() => Drupal.attachBehaviors(), []);
  return <div dangerouslySetInnerHTML={{ __html: alertsStructure() }} />;
};
