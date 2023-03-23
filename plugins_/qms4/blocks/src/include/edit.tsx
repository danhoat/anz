import React from 'react';
import { useBlockProps } from '@wordpress/block-editor';
import ServerSideRender from '@wordpress/server-side-render';
import { Controls } from './Controls';
import { Initializer } from './Initializer';

import './editor.scss';

export function Edit( { name, attributes, setAttributes } ) {
	return (
		<div { ...useBlockProps() }>
			<Controls
				attributes={ attributes }
				setAttributes={ setAttributes }
			/>

			<Initializer
				attributes={ attributes }
				setAttributes={ setAttributes }
			>
				<ServerSideRender block={ name } attributes={ attributes } />
			</Initializer>
		</div>
	);
}
