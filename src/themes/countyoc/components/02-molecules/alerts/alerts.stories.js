import React from 'react';

import alertsStructure from './alerts.twig';
import alertsData from './alerts.yml';

/**
 * Storybook Definition.
 */
export default { title: 'Molecules/Alerts' };

export const alertsExamples = () => (
  <div dangerouslySetInnerHTML={{ __html: alertsStructure(alertsData) }} />);
