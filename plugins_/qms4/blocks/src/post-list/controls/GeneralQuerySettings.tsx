import React from 'react';
import type { FC } from 'react';
import { Panel, PanelBody, SelectControl } from '@wordpress/components';
import { usePostTypes } from '../../hooks/usePostTypes';

import type { Attributes } from '../Attributes';

interface Props {
	attributes: Attributes;
	setAttributes: ( newAttributes: Partial< Attributes > ) => void;
}

export const GeneralQuerySettings: FC< Props > = ( {
	attributes,
	setAttributes,
} ) => {
	const { postType, orderby, order } = attributes;

	const postTypes = usePostTypes();
	if ( ! postType && postTypes.length > 0 ) {
		setAttributes( { postType: postTypes[ 0 ].slug } );
	}

	return (
		<Panel>
			<PanelBody title="クエリ設定" initialOpen={ true }>
				<SelectControl
					label="投稿タイプ"
					value={ postType }
					options={ postTypes.map( ( postType ) => ( {
						label: postType.name,
						value: postType.slug,
					} ) ) }
					onChange={ ( postType ) => setAttributes( { postType } ) }
				/>

				<SelectControl
					label="並び順"
					value={ `${ orderby }/${ order }` }
					options={ [
						{
							label: '管理画面順 (上 → 下)',
							value: 'menu_order/ASC',
						},
						{
							label: '投稿日時順 (最新 → 過去)',
							value: 'post_date/DESC',
						},
						{
							label: '更新日時順 (最新 → 過去)',
							value: 'post_modified/DESC',
						},
					] }
					onChange={ ( orderStr ) => {
						switch ( orderStr ) {
							case 'menu_order/ASC':
								setAttributes( {
									orderby: 'menu_order',
									order: 'ASC',
								} );
								break;
							case 'post_date/DESC':
								setAttributes( {
									orderby: 'post_date',
									order: 'DESC',
								} );
								break;
							case 'post_modified/DESC':
								setAttributes( {
									orderby: 'post_modified',
									order: 'DESC',
								} );
								break;
						}
					} }
				/>
			</PanelBody>
		</Panel>
	);
};
