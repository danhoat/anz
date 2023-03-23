import React from 'react';
import { useBlockProps, InnerBlocks } from '@wordpress/block-editor';

export function save() {
	const blockProps = useBlockProps.save( {
		className: 'qms4__block__user-voice',
	} );

	return (
		<div { ...blockProps }>
			<InnerBlocks.Content />
		</div>
	);
}
