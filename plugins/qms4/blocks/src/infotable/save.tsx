import React from 'react';
import { useBlockProps, InnerBlocks } from '@wordpress/block-editor';
import className from 'classnames';

export function save( { attributes } ) {
	const { numColumns } = attributes;

	const blockProps = useBlockProps.save( {
		className: className(
			'qms4__infotable',
			`qms4__infotable--num-columns-${ numColumns }`
		),
	} );

	return (
		<div { ...blockProps }>
			<InnerBlocks.Content />
		</div>
	);
}
