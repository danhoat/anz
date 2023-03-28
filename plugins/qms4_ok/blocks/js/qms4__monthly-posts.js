// 年月別アーカイブ
jQuery( function ( $ ) {
	/**
	 * @param {string} link
	 * @param {{ year: number, month: number, count: number }[]} rows
	 * @param {string} current
	 * @returns
	 */
	function list_content( link, rows, current ) {
		return rows.map( ( { year, month, count } ) => {
			const yearStr = String( year ).padStart( 4, '0' );
			const monthStr = String( month ).padStart( 2, '0' );

			const href = link
				.replace( '%year%', yearStr )
				.replace( '%month%', monthStr );

			console.log( { current } );

			return `
				<li ${ current === yearStr + monthStr ? 'data-active' : '' }>
					<a href="${ href }">${ month }月（${ count }）</a>
				</li>
			`;
		} );
	}

	$( '.js__qms4__monthly-posts' ).each( function () {
		const $unit = $( this );
		const $prev = $unit.find( '.js__qms4__monthly-posts__button-prev' );
		const $next = $unit.find( '.js__qms4__monthly-posts__button-next' );
		const $year = $unit.find( '.js__qms4__monthly-posts__year' );
		const $list = $unit.find( '.js__qms4__monthly-posts__list' );

		const endpoint = $unit.data( 'endpoint' );
		const link = $unit.data( 'link' );
		const current = String( $unit.data( 'current' ) );

		let year = new Date().getFullYear();

		$prev.on( 'click', async function ( event ) {
			year--;

			const rows = await fetch( endpoint.replace( '%year%', year ) ).then(
				( response ) => response.json()
			);

			$list.html( list_content( link, rows, current ) );
			$year.text( year );
		} );

		$next.on( 'click', async function ( event ) {
			year++;

			const rows = await fetch( endpoint.replace( '%year%', year ) ).then(
				( response ) => response.json()
			);

			$list.html( list_content( link, rows, current ) );
			$year.text( year );
		} );
	} );
} );
