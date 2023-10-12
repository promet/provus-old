/**
 * @file
 * Global utilities.
 *
 */
(function($, Drupal) {

  'use strict';

  Drupal.behaviors.provus = {
    attach: function(context, settings) {

      $('.layout-builder__link').each(function(i, v) {
        $(v).contents().eq(0).wrap('<span class="layout-builder__link-wrapped"></span>');
      });
      // Begin: Displaying LB Region
      // When using the layout builder,
      //   remember the user's preference regarding showing region lines.
      let glbpreviewaction = localStorage.getItem('glbpreviewaction');
      if (glbpreviewaction == 1) {
        $('#glb-toolbar-preview-regions').prop('checked', true);
        $('body').addClass('glb-preview-regions--enable')
      }

      $('.layout-builder-active #glb-toolbar-preview-regions').on('change', function () {
        let is_checked = $(this).is(':checked') ? 1 : 0;
        localStorage.setItem('glbpreviewaction', is_checked);
      })
      // End: LB Region Display

      // Custom code here
      $('#searchCollapse').on('shown.bs.collapse', function () {
        $('#searchCollapse .form-search').focus()
      });

      $('#searchCollapse').on('hidden.bs.collapse', function () {
        $('#searchCollapse .form-search').blur();
      });
    }
  }

})(jQuery, Drupal);
