import React from 'react';
import type { FC, ReactNode } from 'react';
import { Button, SelectControl, Placeholder } from '@wordpress/components';
import { useState } from '@wordpress/element';
import { useDispatch } from '@wordpress/data';
import { listView } from '@wordpress/icons';
import ServerSideRender from '@wordpress/server-side-render';
import { useInnerBlocks } from './useInnerBlocks';
import { useHasSelectedInnerBlock } from './useHasSelectedInnerBlock';
import { usePostTypes } from '../hooks/usePostTypes';
import { defaultLayoutCard, defaultLayoutText } from './defaultLayout';

interface Attributes {
	postType: string;
	layout: 'card' | 'text';
}

interface Props {
	clientId: string;
	isSelected: boolean;
	attributes: Attributes;
	setAttributes: ( newAttributes: Partial< Attributes > ) => void;
	children: ReactNode;
}

export const Initializer: FC< Props > = ( {
	clientId,
	isSelected,
	attributes,
	setAttributes,
	children,
} ) => {
	const { postType: _postType, layout: _layout } = attributes;

	const postTypes = usePostTypes();

	const [ postType, setPostType ] = useState( _postType );
	if ( postType == null && postTypes.length > 0 ) {
		setPostType( postTypes[ 0 ].slug );
	}

	const [ layout, setLayout ] = useState( _layout );
	if ( layout == null ) {
		setLayout( 'card' );
	}

	const replaceInnerBlocks =
		useDispatch( 'core/block-editor' ).replaceInnerBlocks;

	const onSubmit = () => {
		switch ( layout ) {
			case 'card':
				replaceInnerBlocks( clientId, defaultLayoutCard() );
				break;
			case 'text':
				replaceInnerBlocks( clientId, defaultLayoutText() );
				break;
		}

		setAttributes( { postType, layout } );
	};

	const blocks = useInnerBlocks( clientId );
	const hasSelectedInnerBlock = useHasSelectedInnerBlock( clientId );

	if ( ! isSelected && ! hasSelectedInnerBlock && blocks.length > 0 ) {
		// return (
		// 	<ServerSideRender
		// 		block="qms4/post-list"
		// 		attributes={ { ...attributes, innerBlocks: blocks } }
		// 	/>
		// );
		return <>{ children }</>;
	} else if ( _postType != null || _layout != null ) {
		return <>{ children }</>;
	} else {
		return (
			<Placeholder
				icon={ listView }
				label="投稿リスト"
				instructions="投稿の一覧を取得して表示します"
			>
				<SelectControl
					label="投稿タイプ"
					value={ postType }
					options={ postTypes.map( ( postType ) => ( {
						label: postType.name,
						value: postType.slug,
					} ) ) }
					onChange={ ( postType ) => setPostType( postType ) }
				/>

				<Button
					variant="primary"
					onClick={ onSubmit }
					disabled={ postType == null || layout == null }
				>
					確定
				</Button>
			</Placeholder>
		);
	}
};
