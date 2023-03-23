import { registerBlockType } from '@wordpress/blocks';
import './style.scss';

import { Edit } from './Edit';
import { save } from './save';

registerBlockType('brick/form', {
	edit: Edit,
	save,
});
