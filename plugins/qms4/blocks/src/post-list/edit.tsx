import React from 'react';
import { useBlockProps, InnerBlocks } from '@wordpress/block-editor';
import { useInnerBlocks } from './useInnerBlocks';
import { Controls } from './Controls';
import { Initializer } from './Initializer';
import { Item } from './components/Item';

import './editor.scss';

export function Edit( { clientId, isSelected, attributes, setAttributes } ) {
	const { layout, numColumnsPc, numColumnSp, numPostsPc, numPostsSp } =
		attributes;

	const blocks = useInnerBlocks( clientId );

	const allowedBlocks = [
		'qms4/post-list-area',
		'qms4/post-list-html',
		'qms4/post-list-post-author',
		'qms4/post-list-post-date',
		'qms4/post-list-post-excerpt',
		'qms4/post-list-post-modified',
		'qms4/post-list-post-thumbnail',
		'qms4/post-list-post-title',
		'qms4/post-list-terms',
	];

	const blockProps = useBlockProps( {
		className: 'qms4__post-list',
	} );

	return (
		<div
			{ ...blockProps }
			data-layout={ layout }
			data-num-columns-pc={ numColumnsPc }
			data-num-columns-sp={ numColumnSp }
			data-num-posts-pc={ numPostsPc }
			data-num-posts-sp={ numPostsSp }
		>
			<Initializer
				clientId={ clientId }
				isSelected={ isSelected }
				attributes={ attributes }
				setAttributes={ setAttributes }
			>
				<Controls
					attributes={ attributes }
					setAttributes={ setAttributes }
				/>

				<div className="qms4__post-list__list">
					<div className="qms4__post-list__list-item qms4__post-list__list-item--edit">
						<InnerBlocks
							allowedBlocks={ allowedBlocks }
							orientation={
								layout === 'text' ? 'horizontal' : 'vertical'
							}
						/>
					</div>
					{ Array.from(
						Array( Math.max( numPostsPc, numPostsSp ) - 1 ),
						( _, index ) => (
							<div
								key={ index }
								className="qms4__post-list__list-item"
							>
								<Item blocks={ blocks } />
							</div>
						)
					) }
				</div>
			</Initializer>
		</div>
	);
}
