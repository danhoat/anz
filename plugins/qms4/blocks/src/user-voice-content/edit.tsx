import React from 'react';
import { createBlock } from '@wordpress/blocks';
import { useBlockProps, InnerBlocks } from '@wordpress/block-editor';
import { useInnerBlocks } from '../hooks/useInnerBlocks';

import './editor.scss';

export function Edit( { clientId } ) {
	useInnerBlocks(
		clientId,
		( [], blocks ) => {
			if ( blocks.length == 0 ) {
				return [ createBlock( 'core/paragraph' ) ];
			}
		},
		[]
	);

	const blockProps = useBlockProps( {
		className: 'qms4__block__user-voice__content',
	} );

	return (
		<div { ...blockProps }>
			<InnerBlocks />
		</div>
	);
}
