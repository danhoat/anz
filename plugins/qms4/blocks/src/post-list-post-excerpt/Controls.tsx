import React from 'react';
import type { FC } from 'react';
import { InspectorControls } from '@wordpress/block-editor';
import { PanelBody } from '@wordpress/components';
import {
	__experimentalToggleGroupControl as ToggleGroupControl,
	__experimentalToggleGroupControlOption as ToggleGroupControlOption,
} from '@wordpress/components';

interface Attributes {
	numLinesPc: number;
	numLinesSp: number;
}

interface Props {
	attributes: Attributes;
	setAttributes: ( newAttributes: Partial< Attributes > ) => void;
}

export const Controls: FC< Props > = ( { attributes, setAttributes } ) => {
	const { numLinesPc, numLinesSp } = attributes;

	return (
		<InspectorControls>
			<PanelBody>
				<ToggleGroupControl
					label="PC 表示行数"
					value={ numLinesPc }
					onChange={ ( numLinesPc ) =>
						setAttributes( { numLinesPc } )
					}
					isBlock
				>
					<ToggleGroupControlOption value={ 1 } label="1行" />
					<ToggleGroupControlOption value={ 2 } label="2行" />
					<ToggleGroupControlOption value={ 3 } label="3行" />
					<ToggleGroupControlOption value={ -1 } label="全行" />
				</ToggleGroupControl>

				<ToggleGroupControl
					label="SP 表示行数"
					value={ numLinesSp }
					onChange={ ( numLinesSp ) =>
						setAttributes( { numLinesSp } )
					}
					isBlock
				>
					<ToggleGroupControlOption value={ 1 } label="1行" />
					<ToggleGroupControlOption value={ 2 } label="2行" />
					<ToggleGroupControlOption value={ 3 } label="3行" />
					<ToggleGroupControlOption value={ -1 } label="全行" />
				</ToggleGroupControl>
			</PanelBody>
		</InspectorControls>
	);
};
