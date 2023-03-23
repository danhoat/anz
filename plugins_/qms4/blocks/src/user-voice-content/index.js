import { registerBlockType, registerBlockVariation } from '@wordpress/blocks';
import './style.scss';

import { Edit } from './edit';
import { save } from './save';

registerBlockType( 'qms4/user-voice-content', {
	edit: Edit,
	save,
} );
