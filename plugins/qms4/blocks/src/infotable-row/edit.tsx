import React from 'react';
import { useBlockProps, InnerBlocks, RichText } from '@wordpress/block-editor';
import { type TemplateArray } from '@wordpress/blocks';
import className from 'classnames';

import { Controls } from './Controls';

import './editor.scss';

export function Edit( { attributes, setAttributes } ) {
	const { wide, label } = attributes;

	const blockProps = useBlockProps( {
		className: className( 'qms4__infotable-row', {
			'qms4__infotable-row--wide': wide,
		} ),
	} );

	const template: TemplateArray = [
		[ 'core/paragraph', { placeholder: 'ここにテキストを入力' } ],
	];

	return (
		<>
			<Controls
				attributes={ attributes }
				setAttributes={ setAttributes }
			/>

			<dl { ...blockProps }>
				<RichText
					tagName="dt"
					value={ label }
					onChange={ ( label ) => setAttributes( { label } ) }
					placeholder="ここに見出しを入力..."
				/>
				<dd>
					<InnerBlocks template={ template } />
				</dd>
			</dl>
		</>
	);
}
