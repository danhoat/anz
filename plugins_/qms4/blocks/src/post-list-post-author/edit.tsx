import React from 'react';
import { useBlockProps } from '@wordpress/block-editor';

import './editor.scss';

export function Edit() {
	const blockProps = useBlockProps( {
		className: 'qms4__post-list__post-author',
	} );

	return <div { ...blockProps }>ダミー投稿者名</div>;
}
