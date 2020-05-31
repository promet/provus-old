/**
 * @file
 * Behaviors for the oc_sitemap block.
 */

(function ($, Drupal) {
  'use strict';

  Drupal.behaviors.ocSitemapBlock = {
    attach: function (context, settings) {
      $('.sitemap .caret').once('sitemap').on('click', function () {
        $(this).parent().siblings('.nested').toggleClass('active');
        $(this).toggleClass('caret-down');
      });
    }
  };

})(jQuery, Drupal);
