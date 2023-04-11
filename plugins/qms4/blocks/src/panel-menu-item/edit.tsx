import React from 'react';
import { useBlockProps, InnerBlocks } from '@wordpress/block-editor';
import type { TemplateArray } from '@wordpress/blocks';
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

	const template: TemplateArray = [
		[ 'qms4/panel-menu-subitem', { label: 'メニュー1' } ],
		[ 'qms4/panel-menu-subitem', { label: 'メニュー2' } ],
		[ 'qms4/panel-menu-subitem', { label: 'メニュー3' } ],
	];

	const blockProps = useBlockProps( {
		className: 'qms4__panel-menu__item',
	} );

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

			<div className="qms4__panel-menu__item__label">
				{ isSelected ? (
					<TextControl
						value={ label }
						onChange={ ( label ) => setAttributes( { label } ) }
					/>
				) : (
					<span>{ label }</span>
				) }
			</div>
			<div className="qms4__panel-menu__item__content">
				<InnerBlocks
					template={ template }
					renderAppender={ () =>
						isSelected && <InnerBlocks.ButtonBlockAppender />
					}
				/>
			</div>
		</div>
	);
}
