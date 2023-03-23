import React from 'react';
import type { FC } from 'react';

interface Attributes {
	textAlign: 'left' | 'center' | 'right' | 'justify';
	numLinesPc: number;
	numLinesSp: number;
}

interface Props {
	attributes: Attributes;
}

export const PostTitle: FC< Props > = ( { attributes } ) => {
	const { textAlign, numLinesPc, numLinesSp } = attributes;

	return (
		<div
			className="qms4__post-list__post-title"
			data-text-align={ textAlign }
			data-num-lines-pc={ numLinesPc }
			data-num-lines-sp={ numLinesSp }
		>
			ダミー投稿タイトル_あのイーハトーヴォのすきとおった風、夏でも底に冷たさをもつ青いそら、うつくしい森で飾られたモリーオ市、郊外のぎらぎらひかる草の波。
		</div>
	);
};
