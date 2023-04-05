<?php

namespace QMS4\CalendarMonth;

use QMS4\CalendarMonth\CalendarDate;
use QMS4\CalendarMonth\CalendarMonth;
use QMS4\CalendarMonth\CalendarTerm;
use QMS4\Item\Post\Schedule;


class CalendarMonthFactory
{
	/** @var    CalendarTerm */
	private $calendar_term;

	/**
	 * @param    CalendarTerm    $calendar_term
	 */
	public function __construct( CalendarTerm $calendar_term )
	{
		$this->calendar_term = $calendar_term;
	}

	/**
	 * @param    Schedule[]    $schedules
	 * @return    CalendarMonth
	 */
	public function create( array $schedules ): CalendarMonth
	{
		$calendar_dates = array();
		foreach ( $this->calendar_term as $date_str => $date ) {
			$calendar_dates[ $date_str ] = new CalendarDate( $date, array() );
		}

		foreach ( $schedules as $schedule ) {
			$date_str = (string) $schedule->date( 'Y-m-d' );
			if ( isset( $calendar_dates[ $date_str ] ) ) {
				$calendar_dates[ $date_str ]->push( $schedule );
			}
		}

		return new CalendarMonth( $this->calendar_term, $calendar_dates );
	}
}
