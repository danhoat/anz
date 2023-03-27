import { registerBlockType } from '@wordpress/blocks';
import './style.scss';

import { Edit } from './Edit';
import { save } from './save';

registerBlockType( 'qms4/monthly-posts', {
	edit: Edit,
	save,
} );
