import React from 'react';
import { useBlockProps } from '@wordpress/block-editor';
import { Controls } from './Controls';

import './editor.scss';

export function Edit( { attributes, setAttributes } ) {
	const { textAlign, numLinesPc, numLinesSp } = attributes;

	const blockProps = useBlockProps( {
		className: 'qms4__post-list__post-title',
	} );

	return (
		<>
			<Controls
				attributes={ attributes }
				setAttributes={ setAttributes }
			/>

			<div
				{ ...blockProps }
				data-text-align={ textAlign }
				data-num-lines-pc={ numLinesPc }
				data-num-lines-sp={ numLinesSp }
			>
				ダミー投稿タイトル_あのイーハトーヴォのすきとおった風、夏でも底に冷たさをもつ青いそら、うつくしい森で飾られたモリーオ市、郊外のぎらぎらひかる草の波。
			</div>
		</>
	);
}
