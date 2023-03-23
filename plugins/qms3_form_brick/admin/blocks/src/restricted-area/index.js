import { registerBlockType } from '@wordpress/blocks';
import './style.scss';

import { Edit } from './Edit';
import { save } from './save';

registerBlockType('brick/restricted-area', {
	edit: Edit,
	save,
});
