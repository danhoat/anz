import React from 'react';
import { useBlockProps, InnerBlocks } from '@wordpress/block-editor';
import { TextControl } from '@wordpress/components';
import { Controls } from './Controls';
import { useHasSelectedInnerBlock } from '../hooks/useHasSelectedInnerBlock';

import './editor.scss';

export function Edit( {
	clientId,
	isSelected: _isSelected,
	attributes,
	setAttributes,
} ) {
	const { label, showSubmenu } = attributes;

	const hasSelectedInnerBlock = useHasSelectedInnerBlock( clientId );
	const isSelected = _isSelected || hasSelectedInnerBlock;

	const blockProps = useBlockProps( { className: 'qms4__mega-menu__item' } );

	return (
		<div
			{ ...blockProps }
			data-show-submenu={ showSubmenu }
			data-selected={ isSelected }
		>
			<Controls
				attributes={ attributes }
				setAttributes={ setAttributes }
			/>

			<div className="qms4__mega-menu__item__label">
				{ isSelected ? (
					<TextControl
						value={ label }
						onChange={ ( label ) => setAttributes( { label } ) }
					/>
				) : (
					<span>{ label }</span>
				) }
			</div>
			<div className="qms4__mega-menu__item__content">
				<InnerBlocks />
			</div>
		</div>
	);
}
