/**
 * @file
 * Global utilities.
 *
 */
(function($, Drupal) {

  'use strict';

  Drupal.behaviors.provusAdmin = {
    attach: function(context, settings) {
      $(document).ajaxComplete(function() {
        $('#drupal-off-canvas details').each(function(){
          $(this).removeAttr('open');
        });
      });
    }
  }

})(jQuery, Drupal);
