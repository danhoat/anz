import React from 'react';
import type { FC } from 'react';

interface Attributes {}

interface Props {
	attributes: Attributes;
}

export const PostAuthor: FC< Props > = ( { attributes } ) => {
	return <div className="qms4__post-list__post-author">ダミー投稿者名</div>;
};
