import React from 'react';
import type { FC } from 'react';
import { InspectorControls } from '@wordpress/block-editor';
import { PanelBody, SelectControl, ToggleControl } from '@wordpress/components';
import { useTaxonomies } from '../hooks/useTaxonomies';

interface Attributes {
	content: string;
}

interface Props {
	attributes: Attributes;
	setAttributes: ( newAttributes: Partial< Attributes > ) => void;
}

export const Controls: FC< Props > = ( { attributes, setAttributes } ) => {
	const { content } = attributes;

	return (
		<InspectorControls>
			<PanelBody>
				<SelectControl
					label="タクソノミー"
					options={ taxonomies.map( ( taxonomy ) => ( {
						label: taxonomy.name,
						value: taxonomy.slug,
					} ) ) }
					value={ taxonomy }
					onChange={ ( taxonomy ) => setAttributes( { taxonomy } ) }
				/>

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
