import { registerFormatType } from '@wordpress/rich-text';
import { Edit } from './Edit';

registerFormatType( 'qms4/restricted-br', {
	title: '特殊な改行',
	tagName: 'br',
	className: null,
	edit: Edit,
} );
