import React from 'react';
import type { FC } from 'react';
import { InspectorControls } from '@wordpress/block-editor';

import type { Attributes } from './Attributes';
import { LayoutSettings } from './controls/LayoutSettings';
import { DisplaySettings } from './controls/DisplaySettings';
import { GeneralQuerySettings } from './controls/GeneralQuerySettings';
import { FilterQuerySettings } from './controls/FilterQuerySettings';
import { LinkSettings } from './controls/LinkSettings';

interface Props {
	attributes: Attributes;
	setAttributes: ( newAttributes: Partial< Attributes > ) => void;
}

export const Controls: FC< Props > = ( { attributes, setAttributes } ) => {
	return (
		<InspectorControls>
			<DisplaySettings
				attributes={ attributes }
				setAttributes={ setAttributes }
			/>

			<GeneralQuerySettings
				attributes={ attributes }
				setAttributes={ setAttributes }
			/>

			<FilterQuerySettings
				attributes={ attributes }
				setAttributes={ setAttributes }
			/>

			<LinkSettings
				attributes={ attributes }
				setAttributes={ setAttributes }
			/>
		</InspectorControls>
	);
};
