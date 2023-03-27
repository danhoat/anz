import { select, subscribe } from '@wordpress/data';
import { useState } from '@wordpress/element';

export function useHasSelectedInnerBlock( clientId: string ): boolean {
	const [ selected, setSelected ] = useState< boolean >( false );

	subscribe( () => {
		const _selected = select( 'core/block-editor' ).hasSelectedInnerBlock(
			clientId,
			true
		);

		if ( selected != _selected ) {
			setSelected( _selected );
		}
	} );

	return selected;
}
