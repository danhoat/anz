import React from 'react';
import type { FC } from 'react';
import { format } from 'date-fns';

import { CalendarMonth } from './CalendarMonth';

interface Props {
	current: Date;
	prevMonth: () => void;
	nextMonth: () => void;
	isSelected: ( date: Date ) => boolean;
	toggleSelect: ( date: Date ) => void;
}

export const Calendar: FC< Props > = ( {
	current,
	prevMonth,
	nextMonth,
	isSelected,
	toggleSelect,
} ) => {
	return (
		<div className="qms4__event-calendar">
			<div className="qms4__event-calendar__header">
				<div className="qms4__event-calendar__current-year-month">
					{ format( current, 'Y年 M月' ) }
				</div>
				<nav>
					<button
						type="button"
						className="qms4__event-calendar__button_prev"
						onClick={ prevMonth }
					>
						前の月
					</button>
					<button
						type="button"
						className="qms4__event-calendar__button_next"
						onClick={ nextMonth }
					>
						次の月
					</button>
				</nav>
			</div>
			<div className="qms4__event-calendar__body">
				<div className="qms4__event-calendar__body-header">
					<div>月</div>
					<div>火</div>
					<div>水</div>
					<div>木</div>
					<div>金</div>
					<div>土</div>
					<div>日</div>
				</div>

				<CalendarMonth
					current={ current }
					isSelected={ isSelected }
					toggleSelect={ toggleSelect }
				/>
			</div>
		</div>
	);
};
