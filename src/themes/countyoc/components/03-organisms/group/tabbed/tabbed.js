/* eslint-disable */
(function ($) {
  Drupal.behaviors.tabbed = {
    attach(context) {
      $('#pills-tab a').once().on('click', function (e) {
        e.preventDefault();
        $(this).tab('show');
      });
    },
  };
}(jQuery));
