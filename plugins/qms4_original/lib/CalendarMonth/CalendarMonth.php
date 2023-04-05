<?php

namespace QMS4\CalendarMonth;

use QMS4\CalendarMonth\CalendarDate;
use QMS4\CalendarMonth\CalendarTerm;

use QMS4\CalendarMonth\DateClassFormatter;
use QMS4\CalendarMonth\ScheduleFormatter;


class CalendarMonth implements \IteratorAggregate
{
	/** @var    CalendarTerm */
	private $calendar_term;

	/** @var    array<string,CalendarDate> */
	private $calendar_dates;

	/**
	 * @param    CalendarTerm    $calendar_term
	 * @param    array<string,CalendarDate>    $calendar_dates
	 */
	public function __construct(
		CalendarTerm $calendar_term,
		array $calendar_dates
	)
	{
		$this->calendar_term = $calendar_term;
		$this->calendar_dates = $calendar_dates;
	}

	// ====================================================================== //

	/**
	 * @return    \Generator<string,CalendarDate>
	 */
	public function getIterator(): \Traversable
	{
		$dates = array();
		foreach ( $this->calendar_term as $date_str => $_ ) {
			if ( ! isset( $this->calendar_dates[ $date_str ] ) ) {
				throw new \RuntimeException();
			}

			yield $date_str => $this->calendar_dates[ $date_str ];
		}

		return $dates;
	}

	// ====================================================================== //

	/**
	 * @param    DateClassFormatter    $date_class_formatter
	 * @param    ScheduleFormatter    $schedule_formatter
	 * @return    array[]
	 */
	public function to_array(
		DateClassFormatter $date_class_formatter,
		ScheduleFormatter $schedule_formatter
	): array
	{
		// $date_class_formatter = new DateClassFormatter(
		// 	new \DateTimeImmutable( 'now', wp_timezone() ),
		// 	$this->base_date,
		// 	$this->calendar_term,
		// 	'qms4__block__event-calendar__body-cell--'
		// );

		// $schedule_formatter = new ScheduleFormatter();

		$dates = array();
		foreach ( $this->calendar_term as $date_str => $_ ) {
			if ( ! isset( $this->calendar_dates[ $date_str ] ) ) {
				throw new \RuntimeException();
			}

			$calendar_date = $this->calendar_dates[ $date_str ];

			$dates[] = $calendar_date->to_array(
				$date_class_formatter,
				$schedule_formatter
			);
		}

		return $dates;
	}
}
