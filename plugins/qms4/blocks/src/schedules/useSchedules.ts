import { select, subscribe } from '@wordpress/data';
import { useEffect, useState, useCallback } from '@wordpress/element';
import apiFetch from '@wordpress/api-fetch';
import { format } from 'date-fns';
import { useSavePostComplate } from './useSavePostComplate';

export interface Schedule {
	post_id: number | null;
	date: string;
	active: boolean;
}

async function fetchSchedules(
	postType: string | null,
	postId: number | null
): Promise< Schedule[] > {
	if ( ! postType || ! postId ) {
		return [];
	}

	const { qms4__schedules: schedules } = await apiFetch( {
		path: `/wp/v2/${ postType }/${ postId }/`,
	} );

	return schedules;
}

function mergeSchedules(
	target: Schedule[],
	source: Schedule[] | null
): Schedule[] {
	if ( ! source ) {
		return target;
	}

	const result = [ ...target ];
	outer: for ( let schedule of source ) {
		for ( let i = 0, len = result.length; i < len; i++ ) {
			if ( result[ i ].date === schedule.date ) {
				result[ i ].post_id = schedule.post_id;
				continue outer;
			}
		}
		result.push( schedule );
	}

	return result;
}

export function useSchedules(
	postType: string | null,
	postId: number | null
): readonly [ Schedule[], ( date: Date ) => boolean, ( date: Date ) => void ] {
	const [ schedules, setSchedules ] = useState< Schedule[] >( [] );

	useEffect( () => {
		( async () => {
			const schedules = await fetchSchedules( postType, postId );
			setSchedules( schedules );
		} )();
	}, [ postType, postId ] );

	useSavePostComplate( () => {
		( async () => {
			const _schedules = await fetchSchedules( postType, postId );
			setSchedules( mergeSchedules( schedules, _schedules ) );
		} )();
	} );

	const filtered = schedules.filter(
		( schedule ) => schedule.post_id != null || schedule.active
	);

	const isSelected = useCallback(
		( date: Date ) => {
			const dateStr = format( date, 'yyyy-MM-dd' );
			const schedule = schedules.find(
				( date ) => date.date === dateStr
			);

			return !! schedule?.active;
		},
		[ schedules ]
	);

	const toggleDate = useCallback(
		( date: Date ) => {
			const dateStr = format( date, 'yyyy-MM-dd' );
			const schedule = schedules.find(
				( date ) => date.date === dateStr
			);

			if ( schedule ) {
				setSchedules(
					schedules.map( ( _schedule ) => {
						return _schedule.date === dateStr
							? { ...schedule, active: ! schedule.active }
							: _schedule;
					} )
				);
			} else {
				const schedule = {
					post_id: null,
					date: dateStr,
					active: true,
				};
				setSchedules( [ ...schedules, schedule ] );
			}
		},
		[ schedules ]
	);

	return [ filtered, isSelected, toggleDate ] as const;
}
