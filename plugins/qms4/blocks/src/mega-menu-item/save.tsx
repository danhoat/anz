import React from 'react';
import { useBlockProps, InnerBlocks } from '@wordpress/block-editor';

export function save( { attributes } ) {
	const { label, url, targetBlank, showSubmenu } = attributes;

	const target = targetBlank ? { target: '_blank' } : {};

	const blockProps = useBlockProps.save( {
		className: 'qms4__mega-menu__item',
	} );

	return (
		<div { ...blockProps } data-show-submenu={ showSubmenu }>
			<div className="qms4__mega-menu__item__label">
				{ url.length > 0 ? (
					<a href={ url } { ...target }>
						{ label }
					</a>
				) : (
					<span>{ label }</span>
				) }
			</div>
			<div className="qms4__mega-menu__item__content">
				<InnerBlocks.Content />
			</div>
		</div>
	);
}
