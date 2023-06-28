// photo gallery
(function ($, Drupal) {
  function runPhotoGallery(){
    var elements = document.getElementsByClassName('gallery-container');
    for (let item of elements) {
      console.log('each gallery');
      lightGallery(item, {
        autoplayFirstVideo: false,
        pager: false,
        plugins: [
          lgThumbnail,
        ],
        allowMediaOverlap: true,
        toggleThumb: false,
        mobileSettings: {
          download: false,
          rotate: false,
        },
      })
    }
  }
  runPhotoGallery();
})(jQuery, Drupal);
