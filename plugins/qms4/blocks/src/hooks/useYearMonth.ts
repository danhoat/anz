import { useState, useCallback } from '@wordpress/element';
import { subMonths, addMonths } from 'date-fns';

export function useYearMonth() {
	const [ date, setDate ] = useState( () => new Date() );

	const prevMonth = useCallback(
		() => setDate( ( date ) => subMonths( date, 1 ) ),
		[]
	);
	const nextMonth = useCallback(
		() => setDate( ( date ) => addMonths( date, 1 ) ),
		[]
	);

	return [ date, prevMonth, nextMonth ] as const;
}
