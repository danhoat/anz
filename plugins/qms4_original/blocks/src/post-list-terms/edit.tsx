import React from 'react';
import { useBlockProps } from '@wordpress/block-editor';
import { Controls } from './Controls';

import './editor.scss';

export function Edit( { context, attributes, setAttributes } ) {
	const { taxonomy, color } = attributes;

	const blockProps = useBlockProps( {
		className: 'qms4__post-list__terms',
	} );

	return (
		<>
			<Controls
				context={ context }
				attributes={ attributes }
				setAttributes={ setAttributes }
			/>

			<ul
				{ ...blockProps }
				data-taxonomy={ taxonomy }
				data-color={ color }
			>
				<li className="qms4__post-list__terms__icon">カテゴリ</li>
				<li className="qms4__post-list__terms__icon">カテゴリ</li>
			</ul>
		</>
	);
}
