import React from 'react';
import type { FC } from 'react';
import { Panel, PanelBody, TextControl } from '@wordpress/components';
import type { Attributes } from '../Attributes';
import { TaxonomyFilterQuery } from './TaxonomyFilterQuery';

interface Props {
	attributes: Attributes;
	setAttributes: ( newAttributes: Partial< Attributes > ) => void;
}

export const FilterQuerySettings: FC< Props > = ( {
	attributes,
	setAttributes,
} ) => {
	const { excludePostIds, includePostIds } = attributes;

	return (
		<Panel>
			<PanelBody title="絞り込み設定" initialOpen={ false }>
				<TaxonomyFilterQuery
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<TextControl
					label="この投稿 ID を除外"
					help="投稿 ID をカンマで区切って登録"
					placeholder="例）10,20,30"
					value={ excludePostIds }
					onChange={ ( excludePostIds ) =>
						setAttributes( { excludePostIds } )
					}
				/>

				<TextControl
					label="この投稿 ID を表示"
					help="投稿 ID をカンマで区切って登録"
					placeholder="例）10,20,30"
					value={ includePostIds }
					onChange={ ( includePostIds ) =>
						setAttributes( { includePostIds } )
					}
				/>
			</PanelBody>
		</Panel>
	);
};
