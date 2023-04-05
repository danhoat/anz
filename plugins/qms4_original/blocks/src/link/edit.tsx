import React from 'react';
import { useBlockProps, InnerBlocks } from '@wordpress/block-editor';
import { Controls } from './Controls';

import './editor.scss';

export function Edit( { attributes, setAttributes } ) {
	const blockProps = useBlockProps( {
		className: 'qms4__link',
	} );

	return (
		<span { ...blockProps }>
			<Controls
				attributes={ attributes }
				setAttributes={ setAttributes }
			/>

			<InnerBlocks />
		</span>
	);
}
