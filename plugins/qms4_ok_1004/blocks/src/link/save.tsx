import React from 'react';
import { useBlockProps, InnerBlocks } from '@wordpress/block-editor';

export function save( { attributes } ) {
	const { url, targetBlank } = attributes;

	const blockProps = useBlockProps.save( {
		className: 'qms4__link',
	} );

	return (
		<a
			href={ url }
			target={ targetBlank ? '_blank' : undefined }
			{ ...blockProps }
		>
			<InnerBlocks.Content />
		</a>
	);
}
