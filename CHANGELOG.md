# Changelog

All notable changes to this project will be documented in this file.

## [Unreleased]

## [2.1.2] - 2023-10-17

### Fixed

- Corrected variable adjustments in the Provus theme to align with the design's typography.
- Resolved the compatibility issue with Slick and Blazy, aligning them with our pinned version of Slick.
- Fixed an installation error where not all extensions were being selected.

### Changed

- Enhanced the layout builder editorial experience.
    - Installed the `gin_lb` module
    - Transitioned the Gin theme settings from "Classic" to "Vertical."
    - Renamed SVG image components by eliminating the "-white" suffixes (location: `web/profiles/provus/modules/custom/provus_core/provus_blocks/config/install`).
    - Adjusted module weight of `boostrap_styles` to address the issue of missing styles in the Provus installation's layout builder.
        - Theme-related changes:
        - Added JavaScript code to `custom.js`.
        - Included additional SVG images in the `provus_bootstrap` theme folder.
        - Updated `scss/admin/_layout_builder.scss`.


## [2.1.2] - 2023-09-13

## Added

- Introduced focal points for image styles.
- Installed `default_content_deploy` and added default content as test options.

## Fixed

- Standardized card styles and view modes.