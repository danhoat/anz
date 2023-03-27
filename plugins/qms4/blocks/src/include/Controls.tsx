import React from 'react';
import type { FC } from 'react';
import { InspectorControls } from '@wordpress/block-editor';
import { PanelBody, TextControl } from '@wordpress/components';

interface Attributes {
	initialized: boolean;
	filepath: string;
}

interface Props {
	attributes: Attributes;
	setAttributes: ( newAttributes: Partial< Attributes > ) => void;
}

export const Controls: FC< Props > = ( { attributes, setAttributes } ) => {
	const { filepath } = attributes;

	return (
		<InspectorControls>
			<PanelBody>
				<TextControl
					label="インクルードファイル"
					value={ filepath }
					onChange={ ( filepath ) =>
						setAttributes( { filepath, initialized: true } )
					}
					placeholder="path/to/file.php"
				/>
			</PanelBody>
		</InspectorControls>
	);
};
