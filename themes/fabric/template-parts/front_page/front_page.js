/* 魅力
-------------------------------------------------*/
jQuery(function ($) {
  $('.l-top-reason__gallery .ark-block-columns__inner').slick({
    autoplay: true,
    autoplaySpeed: 0,
    speed: 10000,
    cssEase: "linear",
    slidesToShow: 2.2,
    swipe: false,
    arrows: false,
    pauseOnFocus: false,
    pauseOnHover: false,
    responsive: [
      {
        breakpoint: 750,
        settings: {
          slidesToShow: 1.3, // 画面幅750px以下でスライド3枚表示
        }
      }
    ]
  });
});



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

jQuery(function ($) {
  var width = $(window).width();
  if(width <= 760){
    $('.l-top-plan .qms4__post-list__list').slick({
      dots: true,
      arrows: true,
      speed: 300,
      autoplaySpeed: 4000,
      autoplay: true,
    });

  }
});
