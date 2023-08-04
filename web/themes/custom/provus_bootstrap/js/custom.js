/**
 * @file
 * Global utilities.
 *
 */
(function($, Drupal) {

  'use strict';

  Drupal.behaviors.provus = {
    attach: function(context, settings) {

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
