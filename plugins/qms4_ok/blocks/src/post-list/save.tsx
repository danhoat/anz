import React from 'react';
import { useBlockProps, InnerBlocks } from '@wordpress/block-editor';

export function save() {
	return (
		<>
			<InnerBlocks.Content />
		</>
	);
}
