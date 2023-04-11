import React from 'react';
import type { FC } from 'react';
import { InspectorControls } from '@wordpress/block-editor';
import { PanelBody, TextControl, ToggleControl } from '@wordpress/components';

interface Attributes {
	url: string;
	targetBlank: boolean;
	showSubmenu: boolean;
}

interface Props {
	attributes: Attributes;
	setAttributes: ( newAttributes: Partial< Attributes > ) => void;
}

export const Controls: FC< Props > = ( { attributes, setAttributes } ) => {
	const { url, targetBlank, showSubmenu } = attributes;

	return (
		<InspectorControls>
			<PanelBody>
				<TextControl
					label="リンク先 URL"
					value={ url }
					onChange={ ( url ) => setAttributes( { url } ) }
				/>

				<ToggleControl
					label="別タブで開く"
					checked={ targetBlank }
					onChange={ () =>
						setAttributes( { targetBlank: ! targetBlank } )
					}
				/>

				<ToggleControl
					label="サブメニューを表示する"
					checked={ showSubmenu }
					onChange={ () =>
						setAttributes( { showSubmenu: ! showSubmenu } )
					}
				/>
			</PanelBody>
		</InspectorControls>
	);
};
