(($) => {
  Drupal.behaviors.carouselPromoCard = {
    attach() {
      const breakpointSmall = 480;
      const breakpointLarge = 960;
      const marginBig = 160;

      const slider = $('.carousel-promo-card .lightslider').once().lightSlider({
        item: 4,
        loop: false,
        controls: false,
        slideMove: 4,
        slideMargin: 25,
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
      $('.carousel-promo-card .carousel-control-prev').once().on('click', (() => {
        slider.goToPrevSlide();
      }));
      $('.carousel-promo-card .carousel-control-next').once().on('click', (() => {
        slider.goToNextSlide();
      }));
    },
  };
})(jQuery);
