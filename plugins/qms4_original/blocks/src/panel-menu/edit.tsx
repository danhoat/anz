import React from 'react';
import { useBlockProps, InnerBlocks } from '@wordpress/block-editor';
import type { TemplateArray } from '@wordpress/blocks';
import { Controls } from './Controls';
import { useHasSelectedInnerBlock } from '../hooks/useHasSelectedInnerBlock';

import './editor.scss';

export function Edit( {
	clientId,
	isSelected: _isSelected,
	attributes,
	setAttributes,
} ) {
	const hasSelectedInnerBlock = useHasSelectedInnerBlock( clientId );
	const isSelected = _isSelected || hasSelectedInnerBlock;

	const allowedBlocks = [ 'qms4/panel-menu-item' ];

	const template: TemplateArray = [
		[
			'qms4/panel-menu-item',
			{ label: 'ラベル1' },
			[
				[ 'qms4/panel-menu-subitem', { label: 'メニュー1-1' } ],
				[ 'qms4/panel-menu-subitem', { label: 'メニュー1-2' } ],
				[ 'qms4/panel-menu-subitem', { label: 'メニュー1-3' } ],
			],
		],
		[
			'qms4/panel-menu-item',
			{ label: 'ラベル2' },
			[
				[ 'qms4/panel-menu-subitem', { label: 'メニュー2-1' } ],
				[ 'qms4/panel-menu-subitem', { label: 'メニュー2-2' } ],
				[ 'qms4/panel-menu-subitem', { label: 'メニュー2-3' } ],
			],
		],
		[
			'qms4/panel-menu-item',
			{ label: 'ラベル3' },
			[
				[ 'qms4/panel-menu-subitem', { label: 'メニュー3-1' } ],
				[ 'qms4/panel-menu-subitem', { label: 'メニュー3-2' } ],
				[ 'qms4/panel-menu-subitem', { label: 'メニュー3-3' } ],
			],
		],
	];

	const blockProps = useBlockProps( {
		className: 'qms4__panel-menu',
	} );

	return (
		<div { ...blockProps }>
			<Controls
				attributes={ attributes }
				setAttributes={ setAttributes }
			/>

			<InnerBlocks
				allowedBlocks={ allowedBlocks }
				template={ template }
				renderAppender={ () =>
					isSelected && <InnerBlocks.ButtonBlockAppender />
				}
			/>
		</div>
	);
}
