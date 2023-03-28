import React from 'react';
import { useBlockProps, RichText } from '@wordpress/block-editor';
import { Controls } from './Controls';

import './editor.scss';

export function Edit( { attributes, setAttributes } ) {
	const { content } = attributes;

	const blockProps = useBlockProps( {
		className: 'qms4__post-list__html',
	} );

	return (
		<>
			<RichText
				{ ...blockProps }
				tagName="div"
				value={ content }
				onChange={ ( content ) => setAttributes( { content } ) }
			/>
		</>
	);
}
