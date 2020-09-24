import React from 'react';

import colors from './colors.twig';

import branding from './colors-branding.yml';

/**
 * Storybook Definition.
 */
export default { title: 'Base/Colors' };

export const Branding = () => <div dangerouslySetInnerHTML={{ __html: colors(branding) }} />;
