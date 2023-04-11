import React from 'react';
import { useBlockProps } from '@wordpress/block-editor';
import { Controls } from './Controls';

import './editor.scss';

export function Edit( { attributes, setAttributes } ) {
	const { aspectRatio, objectFit } = attributes;

	const blockProps = useBlockProps( {
		className:
			'qms4__post-list__post-thumbnail qms4__post-list__post-thumbnail--edit',
	} );

	return (
		<>
			<Controls
				attributes={ attributes }
				setAttributes={ setAttributes }
			/>

			<div
				{ ...blockProps }
				data-aspect-ratio={ aspectRatio }
				data-object-fit={ objectFit }
			>
				<img src="https://picsum.photos/id/905/400/300/" alt="" />
			</div>
		</>
	);
}
