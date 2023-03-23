import React from 'react';
import type { FC } from 'react';
import className from 'classnames';
import { type CalendarDate } from '../hooks/useEventCalendar';

interface Props {
	calendarDates: CalendarDate[];
}

export const Calendar: FC< Props > = ( { calendarDates } ) => {
	return (
		<div className="qms4__block__event-calendar__calendar">
			<div className="qms4__block__event-calendar__calendar-header">
				<div className="qms4__block__event-calendar__header-cell qms4__block__event-calendar__header-cell--mon">
					月
				</div>
				<div className="qms4__block__event-calendar__header-cell qms4__block__event-calendar__header-cell--tue">
					火
				</div>
				<div className="qms4__block__event-calendar__header-cell qms4__block__event-calendar__header-cell--wed">
					水
				</div>
				<div className="qms4__block__event-calendar__header-cell qms4__block__event-calendar__header-cell--thu">
					木
				</div>
				<div className="qms4__block__event-calendar__header-cell qms4__block__event-calendar__header-cell--fri">
					金
				</div>
				<div className="qms4__block__event-calendar__header-cell qms4__block__event-calendar__header-cell--sat">
					土
				</div>
				<div className="qms4__block__event-calendar__header-cell qms4__block__event-calendar__header-cell--sun">
					日
				</div>
			</div>

			<div className="qms4__block__event-calendar__calendar-body">
				{ calendarDates.map( ( calendarDate ) => (
					<div
						key={ calendarDate.date_str }
						className={ className(
							'qms4__block__event-calendar__body-cell',
							calendarDate.date_class
						) }
					>
						{ calendarDate.schedules.length === 0 ? (
							<span className="qms4__block__event-calendar__day-title">
								{ calendarDate.date.getDate() }
							</span>
						) : (
							<button className="qms4__block__event-calendar__day-title">
								{ calendarDate.date.getDate() }
							</button>
						) }
					</div>
				) ) }
			</div>
		</div>
	);
};
