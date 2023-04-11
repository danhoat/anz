import React from 'react';
import { useBlockProps } from '@wordpress/block-editor';
import { Controls } from './Controls';

import './editor.scss';

export function Edit( { attributes, setAttributes } ) {
	const { taxonomy, color } = attributes;

	const blockProps = useBlockProps( {
		className: 'qms4__post-list__area',
	} );

	return (
		<>
			<Controls
				attributes={ attributes }
				setAttributes={ setAttributes }
			/>

			<ul
				{ ...blockProps }
				data-taxonomy={ taxonomy }
				data-color={ color }
			>
				<li className="qms4__post-list__area__icon">エリア</li>
			</ul>
		</>
	);
}
