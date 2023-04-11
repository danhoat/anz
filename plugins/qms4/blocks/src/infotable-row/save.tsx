import React from 'react';
import { useBlockProps, InnerBlocks, RichText } from '@wordpress/block-editor';
import className from 'classnames';

export function save( { attributes } ) {
	const { wide, label } = attributes;

	const blockProps = useBlockProps.save( {
		className: className( 'qms4__infotable-row', {
			'qms4__infotable-row--wide': wide,
		} ),
	} );

	return (
		<dl { ...blockProps }>
			<RichText.Content tagName="dt" value={ label } />
			<dd>
				<InnerBlocks.Content />
			</dd>
		</dl>
	);
}
