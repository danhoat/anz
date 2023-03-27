import { useBlockProps, InnerBlocks, InspectorControls } from '@wordpress/block-editor';
import { PanelBody, RadioControl } from '@wordpress/components';
import { WPElement } from '@wordpress/element';

import './editor.scss';


export function Edit({ attributes, setAttributes }): WPElement {
	const { screen } = attributes;

	const options = [
		{ value: 'pc', label: 'PC のみ表示' },
		{ value: 'sp', label: 'SP のみ表示' },
	];

	return (
		<div { ...useBlockProps() } data-screen={ screen }>
			<InspectorControls>
				<PanelBody>
					<RadioControl
						selected={ screen }
						options={ options }
						onChange={ screen => setAttributes( { screen } ) }
					/>
				</PanelBody>
			</InspectorControls>

			<InnerBlocks />
		</div>
	);
}
