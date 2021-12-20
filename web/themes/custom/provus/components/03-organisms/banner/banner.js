(($) => {
  Drupal.behaviors.banner = {
    attach() {
      $('.banner.carousel').once().each((n, i) => {
        const slider = $(i).lightSlider({
          auto: true,
          item: 1,
          loop: true,
          keyPress: true,
          controls: false,
          pause: 5000,
          speed: 600,
        });
        $(i)
          .parent()
          .parent()
          .siblings('.carousel-pause-play')
          .children('.pause-play')
          .once()
          .on('click', (e) => {
            const button = e.target;
            const mode = $(button).attr('data-mode');
            if (mode === 'play') {
              slider.pause();
              $(button).attr('data-mode', 'pause');
              $(button).text('play_arrow');
            } else {
              slider.play();
              $(button).attr('data-mode', 'play');
              $(button).text('pause');
            }
          });
      });
    },
  };
})(jQuery);
