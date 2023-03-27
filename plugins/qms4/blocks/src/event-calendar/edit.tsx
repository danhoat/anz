import React from 'react';
import { useBlockProps } from '@wordpress/block-editor';
import { date, dateI18n } from '@wordpress/date';
import { useEventCalendar } from '../hooks/useEventCalendar';
import { Controls } from './Controls';
import { Calendar } from './Calendar';
import { CalendarWithPosts } from './CalendarWithPosts';

import './editor.scss';

export function Edit( { attributes, setAttributes } ) {
	const { postType, showPosts } = attributes;

	const today = new Date();

	const [ currentMonth, calendarDates, prevMonth, nextMonth ] =
		useEventCalendar( postType, today );

	const blockProps = useBlockProps( {
		className: 'qms4__block__event-calendar',
	} );

	return (
		<div { ...blockProps } data-show-posts={ showPosts }>
			<Controls
				attributes={ attributes }
				setAttributes={ setAttributes }
			/>

			<div className="qms4__block__event-calendar__container">
				<div className="qms4__block__event-calendar__month-header">
					<button
						className="qms4__block__event-calendar__button-prev"
						onClick={ prevMonth }
					>
						前の月
					</button>

					<div className="qms4__block__event-calendar__month-title">
						{ currentMonth }
					</div>

					<button
						className="qms4__block__event-calendar__button-next"
						onClick={ nextMonth }
					>
						次の月
					</button>
				</div>

				{ showPosts ? (
					<CalendarWithPosts calendarDates={ calendarDates } />
				) : (
					<Calendar calendarDates={ calendarDates } />
				) }

				<div className="qms4__block__event-calendar__month-footer">
					<button
						className="qms4__block__event-calendar__button-prev"
						onClick={ prevMonth }
					>
						前の月
					</button>

					<button
						className="qms4__block__event-calendar__button-next"
						onClick={ nextMonth }
					>
						次の月
					</button>
				</div>
			</div>

			{ ! showPosts && (
				<div className="qms4__block__event-calendar__display">
					<div className="qms4__block__event-calendar__display-inner">
						<div className="qms4__block__event-calendar__display-header">
							{ dateI18n( 'n月j日（D）', today ) }のイベント
						</div>

						<div className="qms4__block__event-calendar__display-list">
							<div className="qms4__block__event-calendar__display-list-item">
								<span>
									<div className="qms4__block__event-calendar__display-list-item__thumbnail">
										<img
											src="https://picsum.photos/id/905/300/200"
											alt=""
										/>
									</div>
									<div className="qms4__block__event-calendar__display-list-item__inner">
										<ul className="qms4__block__event-calendar__display-list-item__icons">
											<li className="qms4__block__event-calendar__display-list-item__icon">
												カテゴリ
											</li>
											<li className="qms4__block__event-calendar__display-list-item__icon">
												カテゴリ
											</li>
										</ul>
										<div className="qms4__block__event-calendar__display-list-item__title">
											タイトルが入りますタイトルが入りますタイトルが入りますタイトルが入りますタイトルが入りますタイトルが入りますタイトルが入りますタイトルが入りますタイトルが入りますタイトルが入りますタイトルが入りますタイトルが入りますタイトルが入ります
										</div>
									</div>
								</span>
							</div>

							<div className="qms4__block__event-calendar__display-list-item">
								<span>
									<div className="qms4__block__event-calendar__display-list-item__thumbnail">
										<img
											src="https://picsum.photos/id/905/300/200"
											alt=""
										/>
									</div>
									<div className="qms4__block__event-calendar__display-list-item__inner">
										<ul className="qms4__block__event-calendar__display-list-item__icons">
											<li className="qms4__block__event-calendar__display-list-item__icon">
												カテゴリ
											</li>
											<li className="qms4__block__event-calendar__display-list-item__icon">
												カテゴリ
											</li>
										</ul>
										<div className="qms4__block__event-calendar__display-list-item__title">
											タイトルが入りますタイトルが入りますタイトルが入りますタイトルが入りますタイトルが入りますタイトルが入りますタイトルが入りますタイトルが入りますタイトルが入りますタイトルが入りますタイトルが入りますタイトルが入りますタイトルが入ります
										</div>
									</div>
								</span>
							</div>

							<div className="qms4__block__event-calendar__display-list-item">
								<span>
									<div className="qms4__block__event-calendar__display-list-item__thumbnail">
										<img
											src="https://picsum.photos/id/905/300/200"
											alt=""
										/>
									</div>
									<div className="qms4__block__event-calendar__display-list-item__inner">
										<ul className="qms4__block__event-calendar__display-list-item__icons">
											<li className="qms4__block__event-calendar__display-list-item__icon">
												カテゴリ
											</li>
											<li className="qms4__block__event-calendar__display-list-item__icon">
												カテゴリ
											</li>
										</ul>
										<div className="qms4__block__event-calendar__display-list-item__title">
											タイトルが入りますタイトルが入りますタイトルが入りますタイトルが入りますタイトルが入りますタイトルが入りますタイトルが入りますタイトルが入りますタイトルが入りますタイトルが入りますタイトルが入りますタイトルが入りますタイトルが入ります
										</div>
									</div>
								</span>
							</div>

							<div className="qms4__block__event-calendar__display-list-item">
								<span>
									<div className="qms4__block__event-calendar__display-list-item__thumbnail">
										<img
											src="https://picsum.photos/id/905/300/200"
											alt=""
										/>
									</div>
									<div className="qms4__block__event-calendar__display-list-item__inner">
										<ul className="qms4__block__event-calendar__display-list-item__icons">
											<li className="qms4__block__event-calendar__display-list-item__icon">
												カテゴリ
											</li>
											<li className="qms4__block__event-calendar__display-list-item__icon">
												カテゴリ
											</li>
										</ul>
										<div className="qms4__block__event-calendar__display-list-item__title">
											タイトルが入りますタイトルが入りますタイトルが入りますタイトルが入りますタイトルが入りますタイトルが入りますタイトルが入りますタイトルが入りますタイトルが入りますタイトルが入りますタイトルが入りますタイトルが入りますタイトルが入ります
										</div>
									</div>
								</span>
							</div>
						</div>
					</div>
				</div>
			) }
		</div>
	);
}
