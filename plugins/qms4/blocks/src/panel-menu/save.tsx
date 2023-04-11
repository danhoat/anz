import React from 'react';
import { useBlockProps, InnerBlocks } from '@wordpress/block-editor';

export function save() {
	const blockProps = useBlockProps.save( {
		className:
			'qms4__panel-menu qms4__panel-menu__front js__qms4__panel-menu',
	} );

	return (
		<div { ...blockProps }>
			<div className="qms4__panel-menu__item-list">
				<InnerBlocks.Content />
			</div>
			<div className="qms4__panel-menu__subitem-list js__qms4__panel-menu__subitem-list"></div>
		</div>
	);
}
