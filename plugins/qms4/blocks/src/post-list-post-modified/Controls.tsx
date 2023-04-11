import React from 'react';
import type { FC } from 'react';
import { InspectorControls } from '@wordpress/block-editor';
import { PanelBody, ToggleControl } from '@wordpress/components';

interface Attributes {
	showDate: boolean;
	showTime: boolean;
}

interface Props {
	attributes: Attributes;
	setAttributes: ( newAttributes: Partial< Attributes > ) => void;
}

export const Controls: FC< Props > = ( { attributes, setAttributes } ) => {
	const { showDate, showTime } = attributes;

	return (
		<InspectorControls>
			<PanelBody>
				<ToggleControl
					label="日付を表示する"
					checked={ showDate }
					onChange={ () => {
						if ( showDate && ! showTime ) {
							setAttributes( {
								showDate: false,
								showTime: true,
							} );
						} else {
							setAttributes( { showDate: ! showDate } );
						}
					} }
				/>

				<ToggleControl
					label="時刻を表示する"
					checked={ showTime }
					onChange={ () => {
						if ( ! showDate && showTime ) {
							setAttributes( {
								showDate: true,
								showTime: false,
							} );
						} else {
							setAttributes( { showTime: ! showTime } );
						}
					} }
				/>
			</PanelBody>
		</InspectorControls>
	);
};
