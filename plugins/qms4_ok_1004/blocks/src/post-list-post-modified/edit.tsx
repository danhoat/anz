import React from 'react';
import { useBlockProps } from '@wordpress/block-editor';
import { date, dateI18n } from '@wordpress/date';
import { useDateTimeFormat } from '../hooks/useDateTimeFormat';
import { Controls } from './Controls';

import './editor.scss';

export function Edit( { attributes, setAttributes } ) {
	const { showDate, showTime } = attributes;

	const now = new Date();
	const [ dateFormat, timeFormat ] = useDateTimeFormat();

	const blockProps = useBlockProps( {
		className: 'qms4__post-list__post-modified',
	} );

	return (
		<>
			<Controls
				attributes={ attributes }
				setAttributes={ setAttributes }
			/>

			<div { ...blockProps }>
				<time dateTime={ date( 'Y-m-d H:i:s', now ) }>
					{ showDate && dateI18n( dateFormat, now ) }
					{ showTime && dateI18n( timeFormat, now ) }
				</time>
			</div>
		</>
	);
}
