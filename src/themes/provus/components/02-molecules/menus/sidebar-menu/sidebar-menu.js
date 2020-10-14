(($) => {
  Drupal.behaviors.sideBarMenu = {
    attach(context, settings) {
      const self = this;
      $(document).ready(function () {
        self.init(context, settings);
      });
    },
    init: function (context, settings) {
      this.mobileElementPositioning();
      const thisRef = this;
      $(window).on('resize', function () {
        thisRef.mobileElementPositioning();
      });
    },
    mobileElementPositioning: function () {
      if ($(window).width() < 768) {
        $('.main-sidebar .navbar').each(function () {
          $(this).prependTo('.pre-content .pre-content__inner');
        });
      }
      else {
        $('.pre-content .pre-content__inner .navbar').each(function () {
          $(this).appendTo('.main-sidebar');
        });
      }
    }
  };
})(jQuery);
