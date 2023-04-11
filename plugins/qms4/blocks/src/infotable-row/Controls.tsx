import React from 'react';
import type { FC } from 'react';
import { InspectorControls } from '@wordpress/block-editor';
import { PanelBody, ToggleControl } from '@wordpress/components';

interface Attributes {
	wide: boolean;
	label: string;
}

interface Props {
	attributes: Attributes;
	setAttributes: ( newAttributes: Partial< Attributes > ) => void;
}

export const Controls: FC< Props > = ( { attributes, setAttributes } ) => {
	const { wide } = attributes;

	return (
		<InspectorControls>
			<PanelBody>
				<ToggleControl
					label="幅広"
					checked={ wide }
					onChange={ () => setAttributes( { wide: ! wide } ) }
				/>
			</PanelBody>
		</InspectorControls>
	);
};
