import React from 'react';
import {
	useBlockProps,
	InnerBlocks,
	ButtonBlockAppender,
} from '@wordpress/block-editor';
import { type TemplateArray } from '@wordpress/blocks';
import className from 'classnames';
import { Controls } from './Controls';

import './editor.scss';

export function Edit( { clientId, attributes, setAttributes } ) {
	const { numColumns } = attributes;

	const blockProps = useBlockProps( {
		className: className(
			'qms4__infotable',
			`qms4__infotable--num-columns-${ numColumns }`
		),
	} );

	const allowedBlocks = [ 'qms4/infotable-row' ];

	const template: TemplateArray = [
		[
			'qms4/infotable-row',
			{},
			[ [ 'core/paragraph', { placeholder: 'ここにテキストを入力' } ] ],
		],
		[
			'qms4/infotable-row',
			{},
			[ [ 'core/paragraph', { placeholder: 'ここにテキストを入力' } ] ],
		],
		[
			'qms4/infotable-row',
			{},
			[ [ 'core/paragraph', { placeholder: 'ここにテキストを入力' } ] ],
		],
	];

	return (
		<div { ...blockProps }>
			<Controls
				attributes={ attributes }
				setAttributes={ setAttributes }
			/>

			<InnerBlocks
				allowedBlocks={ allowedBlocks }
				template={ template }
				renderAppender={ () => (
					<ButtonBlockAppender rootClientId={ clientId } />
				) }
				orientation={ numColumns === 4 ? 'horizontal' : '' }
			/>
		</div>
	);
}
