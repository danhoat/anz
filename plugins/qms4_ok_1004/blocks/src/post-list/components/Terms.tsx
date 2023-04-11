import React from 'react';
import type { FC } from 'react';

interface Attributes {
	taxonomy: string | undefined;
	color: 'background' | 'text' | 'none';
}

interface Props {
	attributes: Attributes;
}

export const Terms: FC< Props > = ( { attributes } ) => {
	const { taxonomy, color } = attributes;

	return (
		<ul
			className="qms4__post-list__terms"
			data-taxonomy={ taxonomy }
			data-color={ color }
		>
			<li className="qms4__post-list__terms__icon">カテゴリ</li>
			<li className="qms4__post-list__terms__icon">カテゴリ</li>
		</ul>
	);
};
