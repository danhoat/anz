import $ from 'jquery';
import apiFetch from '@wordpress/api-fetch';
import type { Schedule } from './view/Schedule';
import { createOptions } from './view/createOptions';
import { update } from './view/update';

{
	const cache = Object.create( null );

	function init( endpoint ) {
		if ( cache[ endpoint ] ) {
			return cache[ endpoint ];
		}

		return ( cache[ endpoint ] = apiFetch( { path: endpoint } ).then(
			( res ) => res.qms4__schedules
		) );
	}

	$( '.js__qms4__block__timetable' ).each( async function () {
		const $unit = $( this );
		const $dates = $unit.find(
			'.js__qms4__block__timetable__dates select'
		);
		const $timetable = $unit.find(
			'.js__qms4__block__timetable__timetable-body'
		);
		const $prev = $unit.find( '.js__qms4__block__timetable__button-prev' );
		const $next = $unit.find( '.js__qms4__block__timetable__button-next' );

		const currentDate = $unit.data( 'current' );
		const endpoint = $unit.data( 'endpoint' );
		const buttonLabel = $unit.data( 'button-label' );
		const reserveUrl = $unit.data( 'reserve-url' );

		const $elems = {
			$dates,
			$timetable,
			$prev,
			$next,
			buttonLabel,
			reserveUrl,
		};

		const schedules: Schedule[] = await init(
			`${ endpoint }?enable_schedule_only=1`
		).then( ( schedules ) =>
			schedules.sort( ( left, right ) =>
				left.date < right.date ? -1 : 1
			)
		);

		console.info( { schedules } );

		if ( schedules.length === 0 ) {
			return;
		}

		let current = 0;
		if ( currentDate ) {
			current = schedules.findIndex(
				( schedule ) => schedule.date === currentDate
			);
			current = current < 0 ? 0 : current;
		}

		$dates.html( createOptions( schedules ).join( '' ) );

		update( $elems, schedules, current );

		$dates.on( 'change', function ( event ) {
			const date = $dates.val();
			const index = schedules.findIndex(
				( schedule ) => schedule.date === date
			);
			if ( index >= 0 ) {
				current = index;
				update( $elems, schedules, current );
			}
		} );

		$prev.on( 'click', function ( event ) {
			event.preventDefault();
			current = current - 1;
			update( $elems, schedules, current );
		} );

		$next.on( 'click', function ( event ) {
			event.preventDefault();
			current = current + 1;
			update( $elems, schedules, current );
		} );
	} );
}
