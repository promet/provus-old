import React from 'react';

import formStructure from './form/form.twig';
import formData from './form/form.yml';

export default { title: 'Molecules/Form' };

export const form = () => (
  <div dangerouslySetInnerHTML={{ __html: formStructure(formData) }} />);
