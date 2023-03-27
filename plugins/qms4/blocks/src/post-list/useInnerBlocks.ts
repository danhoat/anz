import type { BlockInstance } from '@wordpress/blocks';
import { select, subscribe } from '@wordpress/data';
import { useState } from '@wordpress/element';

export function useInnerBlocks( clientId: string ): BlockInstance[] {
	const [ innerBlocks, setInnerBlocks ] = useState< BlockInstance[] >( [] );

	subscribe( () => {
		const _innerBlocks: BlockInstance[] =
			select( 'core/block-editor' ).getBlocks( clientId );

		if ( ! Object.is( innerBlocks, _innerBlocks ) ) {
			setInnerBlocks( _innerBlocks );
		}
	} );

	return innerBlocks;
}
