import React from 'react';
import type { FC } from 'react';
import { InspectorControls } from '@wordpress/block-editor';
import { PanelBody, RadioControl } from '@wordpress/components';

interface Attributes {
	numColumns: number;
}

interface Props {
	attributes: Attributes;
	setAttributes: ( newAttributes: Partial< Attributes > ) => void;
}

export const Controls: FC< Props > = ( { attributes, setAttributes } ) => {
	const { numColumns } = attributes;

	return (
		<InspectorControls>
			<PanelBody>
				<RadioControl
					label="カラム数"
					options={ [
						{ label: '2カラム', value: '2' },
						{ label: '4カラム', value: '4' },
					] }
					selected={ '' + numColumns }
					onChange={ ( numColumns ) =>
						numColumns &&
						setAttributes( { numColumns: +numColumns } )
					}
				/>
			</PanelBody>
		</InspectorControls>
	);
};
