import React from 'react';

import alertsStructure from './alerts.twig';
import alertsData from './alerts.yml';

import locationAlertsStructure from './location-alerts.twig';
import locationAlertsData from './location-alerts.yml';

/**
 * Storybook Definition.
 */
export default { title: 'Molecules/Alerts' };

export const alertsExamples = () => (
  <div dangerouslySetInnerHTML={{ __html: alertsStructure(alertsData) }} />);

export const locationAlertsExamples = () => (
  <div dangerouslySetInnerHTML={{ __html: locationAlertsStructure(locationAlertsData) }} />);
