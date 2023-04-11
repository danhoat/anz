import React from 'react';
import type { FC } from 'react';
import { InspectorControls } from '@wordpress/block-editor';
import { PanelBody, SelectControl, ToggleControl } from '@wordpress/components';
import { useTaxonomies } from '../hooks/useTaxonomies';

interface Context {
	'qms4/post-list/postType': string;
}

interface Attributes {
	taxonomy: string;
	color: 'background' | 'text' | 'none';
}

interface Props {
	context: Context;
	attributes: Attributes;
	setAttributes: ( newAttributes: Partial< Attributes > ) => void;
}

export const Controls: FC< Props > = ( {
	context,
	attributes,
	setAttributes,
} ) => {
	const { 'qms4/post-list/postType': postType } = context;
	const { taxonomy, color } = attributes;

	const taxonomies = useTaxonomies().filter( ( taxonomy ) =>
		taxonomy.types.includes( postType )
	);
	if ( ! taxonomy && taxonomies.length > 0 ) {
		setAttributes( { taxonomy: taxonomies[ 0 ].slug } );
	}

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
