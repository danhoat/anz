// 一覧ページの絞り込み条件を詳細ページに引き継ぐ
jQuery( function( $ ) {
  if ( ! location.search ) {
    // クエリが無ければ何もしない
    return;
  }

  const query = location.search.slice( 1 );

  $( '.l-content__body .p-postList a' ).each( function() {
    const $a = $( this );
    const href = this.href;

    const url = new URL( href );
    url.search = url.search ? `${ url.search }&${ query }` : `?${ query }`;

    $a.attr( 'href', String( url ) );
  } );
} );

// 8個以上のハッシュタグがある場合の表示切り替え
jQuery( function( $ ) {
  const hashCount = $('.l-hash li').length;
  const hashLimit = 8;
  console.log(hashCount);

  if (hashCount > hashLimit){
    $('.l-hash').addClass("hidden");
    $('.l-hash').append('<div class="more_button"><div>');
    $(".more_button").click(function(){
      $('.l-hash').toggleClass("open");
    });
    if (location.search) {
      $('.l-hash').toggleClass("open");
    }
  }

} );
