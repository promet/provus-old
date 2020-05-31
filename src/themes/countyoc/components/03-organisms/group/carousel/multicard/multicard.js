(($) => {
  Drupal.behaviors.multicard = {
    attach() {
      const breakpointSmall = 480;
      const breakpointLarge = 960;
      const breakpointBig = 1140;
      const marginBig = 0;

      const slider = $('.multicard .lightslider').once().lightSlider({
        item: 5,
        loop: false,
        controls: false,
        slideMove: 1,
        pager: false,
        slideMargin: 20,
        enableDrag: false,
        easing: 'cubic-bezier(0.25, 0, 0.25, 1)',
        speed: 600,
        responsive: [
          {
            breakpoint: breakpointBig + marginBig,
            settings: {
              slideMargin: 10,
              item: 2,
              slideMove: 1,
            },
          },
          {
            breakpoint: breakpointLarge + marginBig,
            settings: {
              slideMargin: 10,
              item: 2,
              slideMove: 1,
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
      $('.multicard-control-prev').once().on('click', (() => {
        slider.goToPrevSlide();
      }));
      $('.multicard-control-next').once().on('click', (() => {
        slider.goToNextSlide();
      }));
    },
  };
})(jQuery);
