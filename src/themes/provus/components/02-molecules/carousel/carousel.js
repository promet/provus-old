(($) => {
  Drupal.behaviors.carousel = {
    attach() {
      $('.imageGallery').once().lightSlider({
        gallery: true,
        item: 1,
        loop: true,
        thumbItem: 9,
        slideMargin: 0,
        enableDrag: false,
        currentPagerPosition: 'left',
        keyPress: true,
      });
    },
  };
})(jQuery);
