import React from 'react';
import type { FC } from 'react';
import { InspectorControls } from '@wordpress/block-editor';
import { PanelBody, TextControl, ToggleControl } from '@wordpress/components';

interface Attributes {
	label: string;
	url: string;
	targetBlank: boolean;
	showSubmenu: boolean;
}

interface Props {
	attributes: Attributes;
	setAttributes: ( newAttributes: Partial< Attributes > ) => void;
}

export const Controls: FC< Props > = ( { attributes, setAttributes } ) => {
	const { label, url, targetBlank, showSubmenu } = attributes;

	return (
		<InspectorControls>
			<PanelBody>
				<ToggleControl
					label="サブメニューを表示する"
					checked={ showSubmenu }
					onChange={ () =>
						setAttributes( { showSubmenu: ! showSubmenu } )
					}
				/>

				{ ! showSubmenu && (
					<TextControl
						label="リンク先 URL"
						value={ url }
						onChange={ ( url ) => setAttributes( { url } ) }
					/>
				) }

				{ ! showSubmenu && (
					<ToggleControl
						label="別タブで開く"
						checked={ targetBlank }
						onChange={ () =>
							setAttributes( { targetBlank: ! targetBlank } )
						}
					/>
				) }
			</PanelBody>
		</InspectorControls>
	);
};
