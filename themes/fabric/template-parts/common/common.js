/* slick
-------------------------------------------------*/
jQuery(function ($) {
  $('.l-gallery-slide .ark-block-columns__inner').slick({
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
          slidesToShow: 1.4, // 画面幅750px以下でスライド3枚表示
        }
      }
    ]
  });
});


/* slick
-------------------------------------------------*/
jQuery(function ($) {
  const width = $(window).width();
  if(width <= 760){
    $('.l-slide .qms4__post-list__list').not('.slick-initialized').slick({
      dots: true,
      arrows: true,
      speed: 300,
      autoplaySpeed: 4000,
      autoplay: true,
      slidesToShow: 1,
    });
  }
});



/* faq
-------------------------------------------------*/
jQuery(function ($) {
  const width = $(window).width();
  if(width <= 760){
    $(".l-page-faq .p-item__title").on("click", function() {
      $(this).next().slideToggle();
      $(this).toggleClass("active");
    });
  }
});
