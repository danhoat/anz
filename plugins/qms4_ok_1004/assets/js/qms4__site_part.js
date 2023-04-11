console.log( 'qms4__site_part.js' );

{
	const buttons = document.querySelectorAll(
		'.qms4__site_part__post_id__shortcode'
	);
	for ( const button of buttons ) {
		button.addEventListener( 'click', function ( event ) {
			event.preventDefault();

			const { postId } = button.dataset;

			const el = document.createElement( 'input' );
			el.id = 'qms4__site_part__post_id__shortcode__code';
			el.value = `<?= qms4_site_part( ${ postId } ) ?>`;

			document.body.appendChild( el );

			el.select();
			document.execCommand( 'copy' );

			el.remove();
		} );
	}
}
