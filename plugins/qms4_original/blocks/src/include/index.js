import { registerBlockType } from '@wordpress/blocks';

import { Edit } from './edit';
import { save } from './save';

registerBlockType( 'qms4/include', {
	edit: Edit,
	save,
} );
