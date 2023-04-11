import React from 'react';
import type { FC } from 'react';
import { InspectorControls } from '@wordpress/block-editor';
import {
	PanelBody,
	SelectControl,
	TextControl,
	ToggleControl,
} from '@wordpress/components';
import { usePostTypeMetas } from '../hooks/usePostTypeMetas';

interface Attributes {
	href: string;
	layout: 'hierarchical' | 'flat';
	targetBlank: boolean;
	hideEmpty: boolean;
	postTypes: string[];
}

interface Props {
	attributes: Attributes;
	setAttributes: ( newAttributes: Partial< Attributes > ) => void;
}

export const Controls: FC< Props > = ( { attributes, setAttributes } ) => {
	const { href, layout, targetBlank, hideEmpty, postTypes } = attributes;

	const postTypeMetas = usePostTypeMetas();

	return (
		<InspectorControls>
			<PanelBody>
				<TextControl
					label="リンク先"
					value={ href }
					onChange={ ( href ) => setAttributes( { href } ) }
				/>

				<ToggleControl
					label="階層で表示する"
					checked={ layout === 'hierarchical' }
					onChange={ () =>
						setAttributes( {
							layout:
								layout === 'hierarchical'
									? 'flat'
									: 'hierarchical',
						} )
					}
				/>

				<ToggleControl
					label="別タブで開く"
					checked={ targetBlank }
					onChange={ () =>
						setAttributes( { targetBlank: ! targetBlank } )
					}
				/>

				<ToggleControl
					label="使われていないエリアを非表示"
					checked={ hideEmpty }
					onChange={ () =>
						setAttributes( { hideEmpty: ! hideEmpty } )
					}
				/>

				{ hideEmpty && (
					<SelectControl
						label="投稿タイプとの紐付け"
						multiple
						value={ postTypes }
						options={ postTypeMetas.map( ( postTypeMeta ) => ( {
							label: postTypeMeta.label,
							value: postTypeMeta.name,
						} ) ) }
						onChange={ ( postTypes ) =>
							setAttributes( { postTypes } )
						}
					/>
				) }
			</PanelBody>
		</InspectorControls>
	);
};
