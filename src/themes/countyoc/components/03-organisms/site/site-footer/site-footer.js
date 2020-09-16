(($) => {
  Drupal.behaviors.siteFooter = {
    attach(context) {
      $('.footer__inner h2', context).once()
        .click((e) => {
          $(e.target).next().toggleClass('open');
          $(e.target).toggleClass('open');
        })
        .focus((e) => {
          $(e.target).next().toggleClass('open');
          $(e.target).toggleClass('open');
        });
    },
  };
})(jQuery);
