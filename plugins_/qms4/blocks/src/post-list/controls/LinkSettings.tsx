import React from 'react';
import type { FC } from 'react';
import { Panel, PanelBody, TextControl } from '@wordpress/components';
import {
	__experimentalToggleGroupControl as ToggleGroupControl,
	__experimentalToggleGroupControlOption as ToggleGroupControlOption,
} from '@wordpress/components';

import type { Attributes } from '../Attributes';

interface Props {
	attributes: Attributes;
	setAttributes: ( newAttributes: Partial< Attributes > ) => void;
}

export const LinkSettings: FC< Props > = ( { attributes, setAttributes } ) => {
	const { linkTarget, linkTargetCustom } = attributes;

	return (
		<Panel>
			<PanelBody title="リンク設定" initialOpen={ false }>
				<ToggleGroupControl
					label="リンクターゲット"
					value={ linkTarget }
					onChange={ ( linkTarget ) =>
						setAttributes( { linkTarget } )
					}
					isBlock
				>
					<ToggleGroupControlOption value="_self" label="_self" />
					<ToggleGroupControlOption value="_blank" label="_blank" />
					<ToggleGroupControlOption
						value="__custom"
						label="カスタム"
					/>
				</ToggleGroupControl>

				{ linkTarget === '__custom' && (
					<TextControl
						label="カスタムリンクターゲット"
						value={ linkTargetCustom }
						onChange={ ( linkTargetCustom ) =>
							setAttributes( { linkTargetCustom } )
						}
					/>
				) }
			</PanelBody>
		</Panel>
	);
};
