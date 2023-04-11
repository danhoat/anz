console.log( 'qms4__panel_menu.js' );

jQuery( function ( $ ) {
	$( '.js__qms4__panel-menu' ).each( function () {
		const $unit = $( this );
		const $items = $unit.find(
			'.js__qms4__panel-menu__item[data-show-submenu=true]'
		);
		const $subitem_list = $unit.find(
			'.js__qms4__panel-menu__subitem-list'
		);

		const $contents = $items.find( '.js__qms4__panel-menu__item__content' );
		$subitem_list.append( $contents );

		$items.each( function ( index ) {
			const $item = $( this );
			$item.on( 'click', function ( event ) {
				$contents.hide();
				$items
					.find( '.js__qms4__panel-menu__item__label' )
					.removeClass( 'qms4__panel-menu__item__label--selected' );

				$contents.eq( index ).show();
				$item
					.find( '.js__qms4__panel-menu__item__label' )
					.addClass( 'qms4__panel-menu__item__label--selected' );
			} );
		} );

		$items
			.eq( 0 )
			.find( '.js__qms4__panel-menu__item__label' )
			.addClass( 'qms4__panel-menu__item__label--selected' );
		$contents.eq( 0 ).show();
	} );
} );
