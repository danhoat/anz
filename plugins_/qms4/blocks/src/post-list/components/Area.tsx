import React from 'react';
import type { FC } from 'react';

interface Attributes {
	color: 'background' | 'text' | 'none';
}

interface Props {
	attributes: Attributes;
}

export const Area: FC< Props > = ( { attributes } ) => {
	const { color } = attributes;

	return (
		<ul className="qms4__post-list__area" data-color={ color }>
			<li className="qms4__post-list__area__icon">エリア</li>
		</ul>
	);
};
