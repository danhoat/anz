import React from 'react';
import { useBlockProps, InnerBlocks } from '@wordpress/block-editor';

export function save() {
	return (
		<div { ...useBlockProps.save() }>
			<InnerBlocks.Content />
		</div>
	);
}
