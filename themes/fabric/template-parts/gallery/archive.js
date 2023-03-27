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


// 12個以上投稿がある場合の表示切り替え
jQuery( function( $ ) {
  const postCount = $('.l-main .p-postList__item').length;
  const postLimit = 12;
  console.log(postCount);

  if (postCount > postLimit){
    $('.l-main').addClass("hidden");
    $('.l-main').append('<div class="more_button"><span>さらに表示</span><div>');
    $(".more_button").click(function(){
      $('.l-main').addClass("open");
    });
  }

} );
