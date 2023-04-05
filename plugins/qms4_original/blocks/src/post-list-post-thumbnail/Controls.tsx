import React from 'react';
import type { FC } from 'react';
import { InspectorControls } from '@wordpress/block-editor';
import { PanelBody, SelectControl } from '@wordpress/components';
import {
	__experimentalToggleGroupControl as ToggleGroupControl,
	__experimentalToggleGroupControlOption as ToggleGroupControlOption,
} from '@wordpress/components';

interface Attributes {
	aspectRatio:
		| '16:9'
		| '3:2'
		| '4:3'
		| '1:1'
		| '3:4'
		| '2:3'
		| '9:16'
		| 'auto';
	objectFit: 'cover' | 'contain';
}

interface Props {
	attributes: Attributes;
	setAttributes: ( newAttributes: Partial< Attributes > ) => void;
}

export const Controls: FC< Props > = ( { attributes, setAttributes } ) => {
	const { aspectRatio, objectFit } = attributes;

	return (
		<InspectorControls>
			<PanelBody>
				<SelectControl
					label="縦横比"
					value={ aspectRatio }
					options={ [
						{ label: '16:9', value: '16:9' },
						{ label: '3:2', value: '3:2' },
						{ label: '4:3', value: '4:3' },
						{ label: '1:1', value: '1:1' },
						{ label: '3:4', value: '3:4' },
						{ label: '2:3', value: '2:3' },
						{ label: '9:16', value: '9:16' },
						{ label: 'オリジナル', value: 'auto' },
					] }
					onChange={ ( aspectRatio ) =>
						setAttributes( { aspectRatio } )
					}
				/>

				<ToggleGroupControl
					label="縮小表示"
					value={ objectFit }
					onChange={ ( objectFit ) => setAttributes( { objectFit } ) }
					isBlock
				>
					<ToggleGroupControlOption value="cover" label="cover" />
					<ToggleGroupControlOption value="contain" label="contain" />
				</ToggleGroupControl>
			</PanelBody>
		</InspectorControls>
	);
};
