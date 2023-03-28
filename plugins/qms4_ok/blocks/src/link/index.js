import { registerBlockType, createBlock } from '@wordpress/blocks';
import './style.scss';

import { Edit } from './edit';
import { save } from './save';

registerBlockType( 'qms4/link', {
	edit: Edit,
	save,

	transforms: {
		from: [
			{
				type: 'block',
				isMultiBlock: true,
				blocks: [ '*' ],
				__experimentalConvert( blocks ) {
					const groupInnerBlocks = blocks.map( ( block ) => {
						return createBlock(
							block.name,
							block.attributes,
							block.innerBlocks
						);
					} );

					return createBlock( 'qms4/link', {}, groupInnerBlocks );
				},
			},
		],
		to: [
			{
				type: 'block',
				blocks: [ '*' ],
				transform: ( attributes, innerBlocks ) => innerBlocks,
			},
		],
	},
} );
