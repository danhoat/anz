import React from 'react';
import type { FC } from 'react';
import { date, dateI18n } from '@wordpress/date';
import { useDateTimeFormat } from '../../hooks/useDateTimeFormat';

interface Attributes {
	showDate: boolean;
	showTime: boolean;
}

interface Props {
	attributes: Attributes;
}

export const PostDate: FC< Props > = ( { attributes } ) => {
	const { showDate, showTime } = attributes;

	const now = new Date();
	const [ dateFormat, timeFormat ] = useDateTimeFormat();

	return (
		<div className="qms4__post-list__post-date">
			<time dateTime={ date( 'Y-m-d H:i:s', now ) }>
				{ showDate && dateI18n( dateFormat, now ) }
				{ showTime && dateI18n( timeFormat, now ) }
			</time>
		</div>
	);
};
