import React from 'react';
import type { FC } from 'react';

interface Attributes {
	content: string;
}

interface Props {
	attributes: Attributes;
}

export const Html: FC< Props > = ( { attributes } ) => {
	const { content } = attributes;

	return (
		<div
			className="qms4__post-list__html"
			style={ { textAlign: 'center' } }
		>
			HTMLコンテンツ
		</div>
	);
};
