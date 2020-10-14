import React from 'react';
import { useEffect } from '@storybook/client-api';
import button from './twig/button.twig';
import buttonData from './twig/button.yml';
import buttonAltData from './twig/button-alt.yml';
import buttonBgData from './twig/button-bg.yml';
import buttonClearStructure from './twig/button-clear.twig';
import buttonClearData from './twig/button-clear.yml';

const styles = {
  backgroundColor: 'black',
  width: '100%',
  height: '100vh',
};

/*eslint-disable */
const Center = ({ children }) => (
  <div style={styles}>
    {children}
  </div>
);
/* eslint-enable */

/**
 * Storybook Definition.
 */
export default {
  title: 'Atoms/Button',
};

export const PrimaryButtons = () => {
  useEffect(() => Drupal.attachBehaviors(), []);
  return <div dangerouslySetInnerHTML={{ __html: button(buttonData) }} />;
};
export const SecondaryButtons = () => {
  useEffect(() => Drupal.attachBehaviors(), []);
  return <div dangerouslySetInnerHTML={{ __html: button(buttonAltData) }} />;
};
export const ButtonsOnDarkBackground = () => {
  useEffect(() => Drupal.attachBehaviors(), []);
  return (
    <Center>
      <div dangerouslySetInnerHTML={{ __html: button(buttonBgData) }} />
    </Center>
  );
};

export const buttonClear = () => (
  <div dangerouslySetInnerHTML={{ __html: buttonClearStructure(buttonClearData) }} />);
