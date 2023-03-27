import React from 'react';
import type { FC } from 'react';
import { InspectorControls } from '@wordpress/block-editor';
import { PanelBody, TextControl, ToggleControl } from '@wordpress/components';

interface Attributes {
	url: string;
	targetBlank: boolean;
}

interface Props {
	attributes: Attributes;
	setAttributes: ( newAttributes: Partial< Attributes > ) => void;
}

export const Controls: FC< Props > = ( { attributes, setAttributes } ) => {
	const { url, targetBlank } = attributes;

	return (
		<InspectorControls>
			<PanelBody>
				<TextControl
					label="リンク先 URL"
					value={ url }
					onChange={ ( url ) => setAttributes( { url } ) }
				/>

				<ToggleControl
					label="新しいタブで開く"
					checked={ targetBlank }
					onChange={ () =>
						setAttributes( { targetBlank: ! targetBlank } )
					}
				/>
			</PanelBody>
		</InspectorControls>
	);
};
