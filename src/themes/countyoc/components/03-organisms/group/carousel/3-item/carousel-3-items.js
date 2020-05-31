(($) => {
  Drupal.behaviors.carousel3Items = {
    attach() {
      const breakpointSmall = 480;
      const breakpointLarge = 960;
      const marginBig = 160;
      const slider = $('.carousel-3-items .lightslider').once().lightSlider({
        item: 3,
        loop: false,
        controls: false,
        slideMove: 3,
        slideMargin: 5,
        enableDrag: false,
        easing: 'cubic-bezier(0.25, 0, 0.25, 1)',
        speed: 600,
        responsive: [
          {
            breakpoint: breakpointLarge + marginBig,
            settings: {
              slideMargin: 10,
              item: 2,
              slideMove: 2,
            },
          },
          {
            breakpoint: breakpointSmall,
            settings: {
              item: 1,
              slideMove: 1,
            },
          },
        ],
      });
      $('.carousel-3-items .carousel-control-prev').once().on('click', (() => {
        slider.goToPrevSlide();
      }));
      $('.carousel-3-items .carousel-control-next').once().on('click', (() => {
        slider.goToNextSlide();
      }));
    },
  };
})(jQuery);
