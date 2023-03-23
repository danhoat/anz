import { useState, useEffect, useCallback } from '@wordpress/element';
import apiFetch from '@wordpress/api-fetch';

export interface MonthlyPostsRow {
	year: number;
	month: number;
	count: number;
}

export function useMonthlyPosts( postType: string | null, thisYear?: number ) {
	thisYear = thisYear == null ? new Date().getFullYear() : thisYear;

	const [ year, setYear ] = useState( thisYear );
	const [ posts, setPosts ] = useState< MonthlyPostsRow[] >( [] );

	const prevYear = useCallback( () => setYear( ( year ) => year - 1 ), [] );
	const nextYear = useCallback( () => setYear( ( year ) => year + 1 ), [] );

	useEffect( () => {
		( async () => {
			if ( ! postType ) {
				return;
			}
			const posts = await apiFetch< MonthlyPostsRow[] >( {
				path: `/qms4/v1/monthly-posts/${ postType }/${ year }/`,
			} );
			setPosts( posts );
		} )();
	}, [ postType, year ] );

	return [ year, posts, prevYear, nextYear ] as const;
}
