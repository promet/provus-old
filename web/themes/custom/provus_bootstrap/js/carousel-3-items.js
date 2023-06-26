(($) => {
  Drupal.behaviors.carousel3Items = {
    attach(context) {
      const breakpointSmall = 720;
      const breakpointLarge = 980;
      const marginBig = 160;
      once('provus-carousel-3',".carousel-3-items .lightslider",context).forEach(function (value,i) {
        const slider = $(value).lightSlider({
          onSliderLoad: function maxHeightFunc(el) {
            let maxHeight = 0;
            const container = $(el);
            const children = container.children();
            children.each(function getMaxHeightFunc() {
              const childHeight = $(this).height();
              if (childHeight > maxHeight) {
                maxHeight = childHeight;
              }
            });
            container.height(maxHeight);
            children.each(function getMaxHeightFunc() {
              $(this).height(maxHeight);
            });
          },
          item: 3,
          loop: false,
          controls: true,
          slideMove: 3,
          slideMargin: 15,
          enableDrag: false,
          easing: 'cubic-bezier(0.25, 0, 0.25, 1)',
          speed: 600,
          keyPress: true,
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
      });
    },
  };
})(jQuery);
