import React from 'react';
import type { FC } from 'react';
import { InspectorControls } from '@wordpress/block-editor';
import {
	PanelBody,
	SelectControl,
	TextControl,
	ToggleControl,
} from '@wordpress/components';
import { useEffect } from '@wordpress/element';
import { useEventPostTypes } from './useEventPostTypes';
import { useTaxonomies } from '../hooks/useTaxonomies';

interface Attributes {
	postType: string;
	showPosts: boolean;
	showArea: boolean;
	showTerms: boolean;
	taxonomies: string[];
	linkFormat: string;
	linkTarget: string;
}

interface Props {
	attributes: Attributes;
	setAttributes: ( newAttributes: Partial< Attributes > ) => void;
}

export const Controls: FC< Props > = ( { attributes, setAttributes } ) => {
	const {
		postType,
		showPosts,
		showArea,
		showTerms,
		taxonomies,
		linkFormat,
		linkTarget,
	} = attributes;

	const postTypeMetas = useEventPostTypes();
	useEffect( () => {
		if ( ! postType && postTypeMetas.length > 0 ) {
			setAttributes( { postType: postTypeMetas[ 0 ].name } );
		}
	}, [ postType, postTypeMetas ] );

	const _taxonomies = useTaxonomies( postType );
	useEffect( () => {
		if (
			showTerms &&
			taxonomies.length === 0 &&
			_taxonomies.length !== 0
		) {
			setAttributes( {
				taxonomies: _taxonomies.map( ( t ) => t.slug ),
			} );
		}
	}, [ _taxonomies ] );

	return (
		<InspectorControls>
			<PanelBody>
				<SelectControl
					label="投稿タイプ"
					value={ postType }
					options={ postTypeMetas.map( ( postTypeMeta ) => ( {
						label: postTypeMeta.label,
						value: postTypeMeta.name,
					} ) ) }
					onChange={ ( postType ) => setAttributes( { postType } ) }
				/>

				<ToggleControl
					label="イベントを表示する"
					checked={ showPosts }
					onChange={ () =>
						setAttributes( { showPosts: ! showPosts } )
					}
				/>

				<ToggleControl
					label="エリアアイコンを表示する"
					checked={ showArea }
					onChange={ () => setAttributes( { showArea: ! showArea } ) }
				/>

				<ToggleControl
					label="タームアイコンを表示する"
					checked={ showTerms }
					onChange={ () =>
						setAttributes( { showTerms: ! showTerms } )
					}
				/>

				{ showTerms && (
					<SelectControl
						label="アイコン表示するタクソノミー"
						multiple
						value={ taxonomies }
						options={ _taxonomies.map( ( taxonomy ) => ( {
							label: taxonomy.name,
							value: taxonomy.slug,
						} ) ) }
						onChange={ ( taxonomies ) =>
							setAttributes( { taxonomies } )
						}
					/>
				) }

				<TextControl
					label="リンクフォーマット"
					value={ linkFormat }
					onChange={ ( linkFormat ) =>
						setAttributes( { linkFormat } )
					}
				/>

				<TextControl
					label="リンクターゲット"
					value={ linkTarget }
					onChange={ ( linkTarget ) =>
						setAttributes( { linkTarget } )
					}
				/>
			</PanelBody>
		</InspectorControls>
	);
};
