import { useState, useEffect, useCallback } from '@wordpress/element';
import apiFetch from '@wordpress/api-fetch';
import { addMonths } from 'date-fns';
import 'date-fns';

export interface Schedule {
	id: number;
	title: string;
	[ key: string ]: any;
}

export interface CalendarDate {
	date_str: string;
	date: Date;
	date_class: string[];
	schedules: Schedule[];
}

export function useEventCalendar(
	postType: string | null,
	borderDate: Date | null
) {
	const [ baseDate, setBaseDate ] = useState< Date >(
		borderDate ?? new Date()
	);
	const [ calendarDates, setCalendarDates ] = useState< CalendarDate[] >(
		[]
	);

	const currentMonth = baseDate.getMonth() + 1;

	const prevMonth = useCallback(
		() => setBaseDate( ( date ) => addMonths( date, -1 ) ),
		[]
	);
	const nextMonth = useCallback(
		() => setBaseDate( ( date ) => addMonths( date, 1 ) ),
		[]
	);

	useEffect( () => {
		( async () => {
			if ( ! postType ) {
				return;
			}
			const dates = await apiFetch<
				{ date: string; date_class: string[]; schedules: Schedule[] }[]
			>( {
				path: `/qms4/v1/event/calendar/${ postType }/${ baseDate.getFullYear() }/${
					baseDate.getMonth() + 1
				}/`,
			} );

			setCalendarDates(
				dates.map( ( date ) => ( {
					...date,
					date_str: date.date,
					date: new Date( date.date ),
				} ) )
			);
		} )();
	}, [ postType, baseDate ] );

	return [ currentMonth, calendarDates, prevMonth, nextMonth ] as const;
}
