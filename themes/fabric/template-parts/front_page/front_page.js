/* 魅力
-------------------------------------------------*/
jQuery(function ($) {
  $('.l-top-reason__gallery .ark-block-columns__inner').not('.slick-initialized').slick({
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





