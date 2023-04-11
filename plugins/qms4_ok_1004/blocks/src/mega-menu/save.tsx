import React from 'react';
import { useBlockProps, InnerBlocks } from '@wordpress/block-editor';

export function save( { attributes } ) {
	const { justifyContent } = attributes;

	const blockProps = useBlockProps.save( {
		className: 'qms4__mega-menu',
	} );

	return (
		<div { ...blockProps } data-justify-content={ justifyContent }>
			<InnerBlocks.Content />
		</div>
	);
}
