import { dateI18n } from '@wordpress/date';
import type { Schedule } from './Schedule';

export function update(
	{ $dates, $timetable, $prev, $next, buttonLabel, reserveUrl },
	schedules: Schedule[],
	current: number
) {
	updateDates( $dates, schedules, current );
	updateTimetable( $timetable, buttonLabel, reserveUrl, schedules, current );
	updatePrev( $prev, schedules, current );
	updateNext( $next, schedules, current );
}

function updateDates( $dates, schedules: Schedule[], current: number ) {
	const schedule = schedules[ current ];
	$dates.val( schedule.date );
}

function updateTimetable(
	$timetable,
	buttonLabel,
	reserveUrl,
	schedules: Schedule[],
	current: number
) {
	const schedule = schedules[ current ];

	const eventName = schedule.title;
	const eventDate = dateI18n( 'Y年n月j日（D）', schedule.date );

	const htmls = schedule.timetable.map( ( row ) => {
		const capacityMark = ( () => {
			switch ( row.capacity ) {
				case '○':
					return '<img src="/wp-content/themes/fabric/images/icon_possible.png" alt="○" />';
				case '△':
					return '<img src="/wp-content/themes/fabric/images/icon_few.png" alt="△" />';
				case '×':
					return '<img src="/wp-content/themes/fabric/images/icon_vacant.png" alt="×" />';
				default:
					return '<img src="/wp-content/themes/fabric/images/icon_possible.png" alt="○" />';
			}
		} )();

		return `<div class="qms4__block__timetable__timetable-body-row">
			<div class="qms4__block__timetable__timetable-body-time">${ row.label }</div>
			<div class="qms4__block__timetable__timetable-body-capacity">${ capacityMark }</div>
			<div class="qms4__block__timetable__timetable-body-entry">
				<p class="qms4__block__timetable__timetable-body-comment">
					${ row.comment }
				</p>
				<div class="qms4__block__timetable__timetable-body-button">
					${
						row.capacity !== '×'
							? `<a href="${ reserveUrl }?event_name=${ eventName }&event_time=${ eventDate }%20${ row.label }">${ buttonLabel }</a>`
							: ''
					}
				</div>
			</div>
		</div>`;
	} );
	$timetable.html( htmls );
}

function updatePrev( $prev, schedules: Schedule[], current: number ) {
	if ( current === 0 ) {
		$prev.hide();
	} else {
		const schedule = schedules[ current - 1 ];
		$prev.html( dateI18n( 'n月j日', schedule.date ) ).show();
	}
}

function updateNext( $next, schedules: Schedule[], current: number ) {
	if ( current === schedules.length - 1 ) {
		$next.hide();
	} else {
		const schedule = schedules[ current + 1 ];
		$next.html( dateI18n( 'n月j日', schedule.date ) ).show();
	}
}
