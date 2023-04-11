import { dateI18n } from '@wordpress/date';
import { Schedule } from './Schedule';

export function createOptions( schedules: Schedule[] ) {
	return schedules.map(
		( schedule ) =>
			`<option value="${ schedule.date }">${ dateI18n(
				'n月j日（D）',
				schedule.date
			) }</option>`
	);
}
