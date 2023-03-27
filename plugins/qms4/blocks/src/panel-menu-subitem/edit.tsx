import React from 'react';
import { useBlockProps, InnerBlocks } from '@wordpress/block-editor';
import { TextControl } from '@wordpress/components';
import { Controls } from './Controls';
import { useHasSelectedInnerBlock } from '../hooks/useHasSelectedInnerBlock';

import './editor.scss';

export function Edit( { isSelected, attributes, setAttributes } ) {
	const { label } = attributes;

	const blockProps = useBlockProps( {
		className: 'qms4__panel-menu__subitem',
	} );

	return (
		<div { ...blockProps }>
			<Controls
				attributes={ attributes }
				setAttributes={ setAttributes }
			/>

			<div className="qms4__panel-menu__subitem__inner">
				{ isSelected ? (
					<TextControl
						value={ label }
						onChange={ ( label ) => setAttributes( { label } ) }
					/>
				) : (
					<span>{ label }</span>
				) }
			</div>
		</div>
	);
}
