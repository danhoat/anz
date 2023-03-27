import React from 'react';
import type { FC } from 'react';

interface Attributes {
	numLinesPc: number;
	numLinesSp: number;
}

interface Props {
	attributes: Attributes;
}

export const PostExcerpt: FC< Props > = ( { attributes } ) => {
	const { numLinesPc, numLinesSp } = attributes;

	return (
		<p
			className="qms4__post-list__post-excerpt"
			data-num-lines-pc={ numLinesPc }
			data-num-lines-sp={ numLinesSp }
		>
			ダミー抜粋文_あのイーハトーヴォのすきとおった風、夏でも底に冷たさをもつ青いそら、うつくしい森で飾られたモリーオ市、郊外のぎらぎらひかる草の波。
		</p>
	);
};
