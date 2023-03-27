import React from 'react';
import type { FC } from 'react';
import { InspectorControls } from '@wordpress/block-editor';
import { PanelBody, ToggleControl } from '@wordpress/components';

interface Attributes {
	color: 'background' | 'text' | 'none';
}

interface Props {
	attributes: Attributes;
	setAttributes: ( newAttributes: Partial< Attributes > ) => void;
}

export const Controls: FC< Props > = ( { attributes, setAttributes } ) => {
	const { color } = attributes;

	return (
		<InspectorControls>
			<PanelBody>
				<ToggleControl
					label="ターム毎の背景色"
					checked={ color === 'background' }
					onChange={ () => {
						if ( color === 'background' ) {
							setAttributes( { color: 'none' } );
						} else {
							setAttributes( { color: 'background' } );
						}
					} }
				/>

				<ToggleControl
					label="ターム毎の文字色"
					checked={ color === 'text' }
					onChange={ () => {
						if ( color === 'text' ) {
							setAttributes( { color: 'none' } );
						} else {
							setAttributes( { color: 'text' } );
						}
					} }
				/>
			</PanelBody>
		</InspectorControls>
	);
};
