import React from 'react';
import type { FC } from 'react';
import { Panel, PanelBody, SelectControl } from '@wordpress/components';

import type { Attributes } from '../Attributes';

interface Props {
	attributes: Attributes;
	setAttributes: ( newAttributes: Partial< Attributes > ) => void;
}

export const LayoutSettings: FC< Props > = ( {
	attributes,
	setAttributes,
} ) => {
	const { layout } = attributes;

	return (
		<Panel>
			<PanelBody title="レイアウト" initialOpen={ false }>
				<SelectControl
					label="レイアウト"
					options={ [
						{ label: 'カード型', value: 'card' },
						{ label: 'リスト型', value: 'list' },
						{ label: 'テキスト型', value: 'text' },
					] }
					value={ layout }
					onChange={ ( layout ) => setAttributes( { layout } ) }
				/>
			</PanelBody>
		</Panel>
	);
};
