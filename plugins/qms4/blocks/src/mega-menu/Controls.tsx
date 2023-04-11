import React from 'react';
import type { FC } from 'react';
import { InspectorControls } from '@wordpress/block-editor';
import { PanelBody, TextControl, ToggleControl } from '@wordpress/components';
import {
	__experimentalToggleGroupControl as ToggleGroupControl,
	__experimentalToggleGroupControlOption as ToggleGroupControlOption,
} from '@wordpress/components';

interface Attributes {
	justifyContent: 'flex-start' | 'center' | 'flex-end' | 'space-between';
}

interface Props {
	attributes: Attributes;
	setAttributes: ( newAttributes: Partial< Attributes > ) => void;
}

export const Controls: FC< Props > = ( { attributes, setAttributes } ) => {
	const { justifyContent } = attributes;

	return (
		<InspectorControls>
			<PanelBody>
				<ToggleGroupControl
					label="左右の揃え"
					value={ justifyContent }
					onChange={ ( justifyContent ) =>
						setAttributes( { justifyContent } )
					}
					isBlock
				>
					<ToggleGroupControlOption value="flex-start" label="左" />
					<ToggleGroupControlOption value="center" label="中央" />
					<ToggleGroupControlOption value="flex-end" label="右" />
					<ToggleGroupControlOption
						value="space-between"
						label="両端"
					/>
				</ToggleGroupControl>
			</PanelBody>
		</InspectorControls>
	);
};
