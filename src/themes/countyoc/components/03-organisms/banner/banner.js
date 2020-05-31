(($) => {
  Drupal.behaviors.banner = {
    attach(context) {
      $('#banner').once().carousel({
        interval: 5000,
        ride: 'carousel',
      });
      $('#pause-play', context).on('click', (e) => {
        const button = e.target;
        const mode = $(button).attr('data-mode');
        if (mode === 'play') {
          $('#banner').carousel('pause');
          $(button).attr('data-mode', 'pause');
          $(button).text('play_arrow');
        } else {
          $('#banner').carousel('cycle');
          $(button).attr('data-mode', 'play');
          $(button).text('pause');
        }
      });
    },
  };
})(jQuery);
