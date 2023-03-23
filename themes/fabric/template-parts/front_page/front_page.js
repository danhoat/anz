/* slick
-------------------------------------------------*/
jQuery(function ($) {
  var width = $(window).width();
  if(width <= 760){
    $('.l-slide .qms4__post-list__list').slick({
      dots: true,
      arrows: true,
      speed: 300,
      autoplaySpeed: 4000,
      autoplay: true,
    });
  }
});
