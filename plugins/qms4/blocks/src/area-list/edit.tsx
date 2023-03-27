import React from 'react';
import { useBlockProps } from '@wordpress/block-editor';
import ServerSideRender from '@wordpress/server-side-render';
import { Controls } from './Controls';

import './editor.scss';

export function Edit( { name, attributes, setAttributes } ) {
	const blockProps = useBlockProps( {
		className: 'qms4__area-list',
	} );

	return (
		<div { ...blockProps }>
			<Controls
				attributes={ attributes }
				setAttributes={ setAttributes }
			/>

			<ServerSideRender block={ name } attributes={ attributes } />
		</div>
	);
}
