import { registerFormatType } from '@wordpress/rich-text';
import { Edit } from './Edit';

registerFormatType( 'qms4/wp-post', {
	title: '投稿',
	tagName: 'span',
	className: 'qms4--wp-post',
	edit: Edit,
} );
