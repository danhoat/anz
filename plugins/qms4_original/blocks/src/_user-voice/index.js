import { registerBlockType, registerBlockVariation } from '@wordpress/blocks';
import './style.scss';

import { Edit } from './edit';
import { save } from './save';

registerBlockType( 'qms4/user-voice', {
	edit: Edit,
	save,
} );

registerBlockVariation( 'qms4/user-voice', [
	{
		name: 'qms4/user-voice/image',
		title: 'お客様の声(写真+テキスト)',
		attributes: {
			layout: 'image',
		},
		example: {
			title: 'お客様の声',
			imageId: 0,
			imageUrl: '',
			content:
				'テキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキスト',
		},
		scope: [ 'transform' ],
	},
	{
		name: 'qms4/user-voice/youtube',
		title: 'お客様の声(YouTube+テキスト)',
		attributes: {
			layout: 'youtube',
		},
		example: {
			title: 'お客様の声',
			youtubeId: 'jNQXAC9IVRw',
			youtubeUrl: 'https://youtu.be/jNQXAC9IVRw',
			content:
				'テキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキスト',
		},
		scope: [ 'transform' ],
	},
	{
		name: 'qms4/user-voice/none',
		title: 'お客様の声(テキストのみ)',
		attributes: {
			layout: 'none',
		},
		example: {
			title: 'お客様の声',
			content:
				'テキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキスト',
		},
		scope: [ 'transform' ],
	},
] );
