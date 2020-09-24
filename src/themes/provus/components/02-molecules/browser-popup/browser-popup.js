(($) => {
  Drupal.behaviors.browser_detect = {
    attach() {
      if (window.navigator.appName == 'Microsoft Internet Explorer') {
        $('#modal-browser').modal('show');
      } else if (window.navigator.userAgent.indexOf("Trident") > -1) {
        $('#modal-browser').modal('show');
      }
      else {
        $('#modal-browser').modal('hide');
      }
    },
  };
})(jQuery);