import React from 'react';

import formStructure from './form/form.twig';
import formData from './form/form.yml';

import searchBannerTwig from './search/search-banner.twig';
import searchBannerFixture from './search/search-banner.yml';

import searchBarTwig from './search/search-bar.twig';
import searchBarFixture from './search/search-bar.yml';

const searchPageFixture = JSON.parse(JSON.stringify(searchBannerFixture));
searchPageFixture.search_input_icon = true;
searchPageFixture.search_form_base_class = 'search-page-form';

export default { title: 'Molecules/Form' };

export const form = () => (
  <div dangerouslySetInnerHTML={{ __html: formStructure(formData) }} />);

export const searchBanner = () => (
  <div dangerouslySetInnerHTML={{ __html: searchBannerTwig(searchBannerFixture) }} />);

export const searchPage = () => (
  <div dangerouslySetInnerHTML={{ __html: searchBannerTwig(searchPageFixture) }} />);

export const searchBar = () => (
  <div dangerouslySetInnerHTML={{ __html: searchBarTwig(searchBarFixture) }} />);
