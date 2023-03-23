import React from 'react';
import { useBlockProps, InnerBlocks } from '@wordpress/block-editor';
import { useInnerBlocks } from '../hooks/useInnerBlocks';
import { toLayoutImage, toLayoutEmbed, toLayoutNone } from './updateBlocks';

import './editor.scss';

export function Edit( { clientId, attributes } ) {
	const { layout } = attributes;

	useInnerBlocks(
		clientId,
		( [ layout ], blocks ) => {
			switch ( layout ) {
				case 'embed':
					return toLayoutEmbed( blocks );
				case 'none':
					return toLayoutNone( blocks );
				case 'image':
				default:
					return toLayoutImage( blocks );
			}
		},
		[ layout ]
	);

	const blockProps = useBlockProps( {
		className: 'qms4__block__user-voice',
	} );

	return (
		<div { ...blockProps }>
			<InnerBlocks templateLock="all" />
		</div>
	);
}
