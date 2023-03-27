import React from 'react';
import { useBlockProps, InnerBlocks } from '@wordpress/block-editor';
import type { TemplateArray } from '@wordpress/blocks';
import { Controls } from './Controls';

import './editor.scss';

export function Edit( { attributes, setAttributes } ) {
	const { justifyContent } = attributes;

	const allowedBlocks = [ 'qms4/mega-menu-item' ];

	const template: TemplateArray = [
		[
			'qms4/mega-menu-item',
			{ label: 'ラベル1' },
			[ [ 'core/paragraph', { content: 'コンテンツ1' } ] ],
		],
		[
			'qms4/mega-menu-item',
			{ label: 'ラベル2' },
			[ [ 'core/paragraph', { content: 'コンテンツ2' } ] ],
		],
		[
			'qms4/mega-menu-item',
			{ label: 'ラベル3' },
			[ [ 'core/paragraph', { content: 'コンテンツ3' } ] ],
		],
	];

	const blockProps = useBlockProps( {
		className: 'qms4__mega-menu',
	} );

	return (
		<div { ...blockProps } data-justify-content={ justifyContent }>
			<Controls
				attributes={ attributes }
				setAttributes={ setAttributes }
			/>

			<InnerBlocks
				allowedBlocks={ allowedBlocks }
				template={ template }
				orientation="horizontal"
			/>
		</div>
	);
}
