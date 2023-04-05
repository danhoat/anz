import {
	registerBlockType,
	createBlock,
	registerBlockVariation,
} from '@wordpress/blocks';
import './style.scss';

import { Edit } from './edit';
import { save } from './save';

registerBlockType( 'qms4/user-voice', {
	edit: Edit,
	save,
	transforms: {
		to: [
			{
				type: 'block',
				blocks: [ 'qms4/user-voice' ],
				transform: ( attributes, innerBlocks ) => {
					console.info( { attributes, innerBlocks } );

					return createBlock(
						'qms4/user-voice',
						attributes,
						innerBlocks
					);
				},
			},
		],
	},
} );

registerBlockVariation( 'qms4/user-voice', [
	{
		name: 'qms4/user-voice/layout-image',
		title: 'お客様の声(テキスト+写真)',
		attributes: {
			layout: 'image',
		},
		scope: [ 'transform' ],
	},
	{
		name: 'qms4/user-voice/layout-embed',
		title: 'お客様の声(テキスト+YouTube)',
		attributes: {
			layout: 'embed',
		},
		scope: [ 'transform' ],
	},
	{
		name: 'qms4/user-voice/layout-none',
		title: 'お客様の声(テキストのみ)',
		attributes: {
			layout: 'none',
		},
		scope: [ 'transform' ],
	},
] );
