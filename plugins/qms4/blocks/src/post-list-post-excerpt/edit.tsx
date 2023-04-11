import React from 'react';
import { useBlockProps } from '@wordpress/block-editor';
import { Controls } from './Controls';

import './editor.scss';

export function Edit( { attributes, setAttributes } ) {
	const { numLinesPc, numLinesSp } = attributes;

	const blockProps = useBlockProps( {
		className: 'qms4__post-list__post-excerpt',
	} );

	return (
		<>
			<Controls
				attributes={ attributes }
				setAttributes={ setAttributes }
			/>

			<p
				{ ...blockProps }
				data-num-lines-pc={ numLinesPc }
				data-num-lines-sp={ numLinesSp }
			>
				ダミー抜粋文_あのイーハトーヴォのすきとおった風、夏でも底に冷たさをもつ青いそら、うつくしい森で飾られたモリーオ市、郊外のぎらぎらひかる草の波。
			</p>
		</>
	);
}
