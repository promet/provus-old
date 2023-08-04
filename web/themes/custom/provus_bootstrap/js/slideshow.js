(function ($, Drupal) {
  if ($('.slide-show-with-items-container').length > 0) {
    console.log('start slider')
    $('.slide-show-with-items-container').each(function() {
      console.log('Each slideshow');
      const sliderSpeed = $(this).attr('data-slide-speed');
      const sliderAutoplay = $(this).attr('data-autoplay');
      $(this).slick({
        slidesToShow: 1,
        slidesToScroll: 1,
        arrows: false,
        dots: true,
        fade: false,
        speed: 1000,
        pauseOnHover: false,
        autoplay: sliderAutoplay == 'true' ? true : false,
        autoplaySpeed: sliderSpeed,
        infinite: true
      });
    });


    // Slideshow.
    window.addEventListener('load',function(){
      console.log('on loaded');
      $(".slide-show-with-items-container").each(function(){
        let playPauseContainer = document.createElement('div');
        let playBtn = document.createElement('div');
        let pauseBtn = document.createElement('div');

        $(playPauseContainer).addClass('play-pause-container');
        $(playBtn).addClass('play-btn');
        $(playBtn).addClass('hide');
        $(pauseBtn).addClass('pause-btn');
        $(playBtn).appendTo($(playPauseContainer));
        $(pauseBtn).appendTo($(playPauseContainer));
        $(playPauseContainer).appendTo($(this));


        $(pauseBtn).on('click', function(){
          $(this).addClass('hide');
          $(playBtn).removeClass('hide');;
          $(this).closest('.slide-show-with-items-container').slick('slickPause')
        });
        $(playBtn).on('click', function(){
          $(this).addClass('hide');
          $(pauseBtn).removeClass('hide');;
          $(this).closest('.slide-show-with-items-container').slick('slickPlay')
        });
      });
    });
  }
})(jQuery, Drupal);
