// 一覧ページから引き継いできた絞り込み条件を、さらに一覧ページに引き継ぐ
jQuery( function( $ ) {
  if ( ! location.search ) {
    // クエリが無ければ何もしない
    return;
  }

  const query = location.search.slice( 1 );

  const archiveUrl = `${ location.origin }/event`;
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
