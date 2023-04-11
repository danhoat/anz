import { registerBlockType } from '@wordpress/blocks';
import './style.scss';

import { Edit } from './edit';
import { save } from './save';

registerBlockType( 'qms4/mega-menu', {
	edit: Edit,
	save,
} );
