import React from 'react';
import { useBlockProps } from '@wordpress/block-editor';

export function save( { attributes } ) {
	const { label, url, targetBlank } = attributes;

	const target = targetBlank ? { target: '_blank' } : {};

	const blockProps = useBlockProps.save( {
		className: 'qms4__panel-menu__subitem',
	} );

	return (
		<div { ...blockProps }>
			<div className="qms4__panel-menu__subitem__inner">
				<a href={ url } { ...target }>
					{ label }
				</a>
			</div>
		</div>
	);
}
