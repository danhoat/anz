import React from 'react';
import { useBlockProps, InnerBlocks } from '@wordpress/block-editor';

export function save( { attributes } ) {
	const { label, url, targetBlank, showSubmenu } = attributes;

	const target = targetBlank ? { target: '_blank' } : {};

	const blockProps = useBlockProps.save( {
		className: 'qms4__panel-menu__item js__qms4__panel-menu__item',
	} );

	return (
		<div { ...blockProps } data-show-submenu={ showSubmenu }>
			<div className="qms4__panel-menu__item__label js__qms4__panel-menu__item__label">
				{ showSubmenu ? (
					<span>{ label }</span>
				) : (
					<a href={ url } { ...target }>
						{ label }
					</a>
				) }
			</div>
			{ showSubmenu && (
				<div className="qms4__panel-menu__item__content js__qms4__panel-menu__item__content">
					<InnerBlocks.Content />
				</div>
			) }
		</div>
	);
}
