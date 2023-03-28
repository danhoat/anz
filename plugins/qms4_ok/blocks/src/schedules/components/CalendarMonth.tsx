import React from 'react';
import type { FC } from 'react';
import {
	addDays,
	isSameDay,
	isSameMonth,
	isMonday,
	isSunday,
	startOfMonth,
	lastDayOfMonth,
	previousMonday,
	nextSunday,
	format,
} from 'date-fns';
import classNames from 'classnames';

interface Props {
	current: Date;
	isSelected: ( date: Date ) => boolean;
	toggleSelect: ( date: Date ) => void;
}

export const CalendarMonth: FC< Props > = ( {
	current,
	isSelected,
	toggleSelect,
} ) => {
	let first = startOfMonth( current );
	first = isMonday( first ) ? first : previousMonday( first );

	let last = lastDayOfMonth( current );
	last = addDays( isSunday( last ) ? last : nextSunday( last ), 1 );

	const dates: Date[] = [];
	for ( let d = first; ! isSameDay( d, last ); d = addDays( d, 1 ) ) {
		dates.push( d );
	}

	return (
		<div className="qms4__event-calendar__body-main">
			{ dates.map( ( date ) => {
				const dateStr = format( date, 'yyyy-MM-dd' );

				return (
					<div
						className={ classNames( 'qms4__event-calendar__day', {
							out_of_month: ! isSameMonth( date, current ),
						} ) }
					>
						<input
							type="checkbox"
							id={ `qms4__event-calendar__date-${ dateStr }` }
							checked={ isSelected( date ) }
							onChange={ () => toggleSelect( date ) }
						/>
						<label
							htmlFor={ `qms4__event-calendar__date-${ dateStr }` }
						>
							{ format( date, 'd' ) }
						</label>
					</div>
				);
			} ) }
		</div>
	);
};
