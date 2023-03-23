import React from 'react';
import className from 'classnames';
import { createBlock } from '@wordpress/blocks';
import { useBlockProps, InnerBlocks } from '@wordpress/block-editor';
import { useInnerBlocks } from '../hooks/useInnerBlocks';

import './editor.scss';

export function Edit( { clientId, attributes } ) {
	const { type } = attributes;

	useInnerBlocks(
		clientId,
		( [ type ], blocks ) => {
			if ( blocks.length == 0 || blocks[ 0 ].name !== `core/${ type }` ) {
				return createInnerBlocks( type );
			}
		},
		[ type ]
	);

	const blockProps = useBlockProps( {
		className: className(
			'qms4__block__user-voice__media',
			`qms4__block__user-voice__media--type-${ type }`
		),
	} );

	return (
		<div { ...blockProps }>
			<InnerBlocks templateLock="all" />
		</div>
	);
}

function createInnerBlocks( type: 'image' | 'embed' | 'none' ) {
	switch ( type ) {
		case 'image':
			return [
				createBlock( 'core/image', {
					sizeSlug: 'large',
					url: 'https://picsum.photos/id/838/1200/800',
				} ),
			];

		case 'embed':
			return [
				createBlock( 'core/embed', {
					type: 'video',
					providerNameSlug: 'youtube',
					responsive: true,
					className: 'wp-embed-aspect-4-3 wp-has-aspect-ratio',
				} ),
			];

		case 'none':
		default:
			return [];
	}
}
