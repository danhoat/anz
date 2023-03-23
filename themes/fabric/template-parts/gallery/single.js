// 一覧ページから引き継いできた絞り込み条件を、さらに一覧ページに引き継ぐ
jQuery( function( $ ) {
  if ( ! location.search ) {
    // クエリが無ければ何もしない
    return;
  }

  const query = location.search.slice( 1 );

  const archiveUrl = `${ location.origin }/works`;
  const regexp = archiveUrl
    .replace( /\//g, '\\/' )
    .replace( /\./g, '\\.' )
    .replace( /\-/g, '\\-' );
  const cond = new RegExp( `${ regexp }\/?(\\?|$)` );

  console.log( { cond } );

  $( '.l-article a[href]' ).each( function() {
    const $a = $( this );
    const href = this.href;

    if ( ! cond.test( href ) ) {
      // 一覧ページの条件に一致しないなら何もしない
      return;
    }

    const url = new URL( href );
    url.search = url.search ? `${ url.search }&${ query }` : `?${ query }`;

    $a.attr( 'href', String( url ) );
  } );
} );


jQuery(function($) {
  const count = $('.thumb_gallery_slide .thumbnail-item').length;
  if (count <= 4) {
    /* 4以下の時 */
    $('.thumb_gallery').addClass('center')
  }
  $('.main_gallery').slick({
    slidesToShow: 1,
    slidesToScroll: 1,
    arrows: false,
    fade: true,
    draggable: true,
    autoplay:true,
    autoplaySpeed: 3000,
    asNavFor: '.thumb_gallery',
    accessibility: true,
    // prevArrow: '<div class="slide-arrow prev-arrow pc"><img src="../images/cmn/prev.svg" width="38" height="67"></div>',
    // nextArrow: '<div class="slide-arrow next-arrow pc"><img src="../images/cmn/next.svg" width="38" height="67"></div>',
    responsive: [
      {
       breakpoint: 761, //760px以下のサイズに適用
       settings: {
        // adaptiveHeight: true,
       }
      }
    ]
  });
  $('.thumb_gallery').slick({
    slidesToShow: 6,
    slidesToScroll: 1,
    asNavFor: '.main_gallery',
    dots: false,
    arrows: false,
    centerMode: false,
    autoplay:true,
    swipeToSlide: true,
    focusOnSelect: true,
    accessibility: true,
    responsive: [
      {
       breakpoint: 761, //760px以下のサイズに適用
       settings: {
        slidesToShow: 3,
       }
      }
    ]
  });
});
