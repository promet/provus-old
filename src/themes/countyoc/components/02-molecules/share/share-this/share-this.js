(($) => {
  Drupal.behaviors.shareThis = {
    attach(context, settings) {
      $('.ul-share-this__link-link').once().click(
        function() {
          var dummy = document.createElement('input'),
          text = window.location.href;

          document.body.appendChild(dummy);
          dummy.value = text;
          dummy.select();
          document.execCommand('copy');
          document.body.removeChild(dummy);
          alert('Copied to clipboard');

          return false;
        }
      );
    },
  };
})(jQuery);
