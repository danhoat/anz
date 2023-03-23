import { createBlock } from '@wordpress/blocks';

export const defaultLayoutCard = () => [
	createBlock( 'qms4/post-list-post-thumbnail', {} ),
	createBlock( 'qms4/post-list-terms', {} ),
	createBlock( 'qms4/post-list-post-date', {} ),
	createBlock( 'qms4/post-list-post-title', {} ),
];

export const defaultLayoutText = () => [
	createBlock( 'qms4/post-list-post-date', {} ),
	createBlock( 'qms4/post-list-terms', {} ),
	createBlock( 'qms4/post-list-post-title', {} ),
];
