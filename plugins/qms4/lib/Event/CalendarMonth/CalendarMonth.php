<?php

namespace QMS4\Event\CalendarMonth;

use QMS4\Event\BorderDate;
use QMS4\Event\CalendarMonth\CalendarDate;
use QMS4\Event\CalendarMonth\CalendarTerm;

use QMS4\Event\CalendarMonth\DateClassFormatter;
use QMS4\Event\CalendarMonth\ScheduleFormatter;


class CalendarMonth implements \IteratorAggregate
{
	/** @var    CalendarTerm */
	private $calendar_term;

	/** @var    array<string,CalendarDate> */
	private $calendar_dates;

	/** @var    BorderDate */
	private $border_date;

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
		foreach ( $this->calendar_term as $date_str => $_ ) {
			if ( ! isset( $this->calendar_dates[ $date_str ] ) ) {
				throw new \RuntimeException();
			}

			$calendar_date = $this->calendar_dates[ $date_str ];
			$calendar_date->set_enable( $this->border_date );  // TODO: セッターでやるのやめたい

			yield $date_str => $calendar_date;
		}
	}

	// ====================================================================== //

	/**
	 * @param    BorderDate    $border_date
	 * @return    void
	 */
	public function set_border_date( BorderDate $border_date ): void
	{
		$this->border_date = $border_date;
	}

	/**
	 * $base_date 以降でイベントスケジュールのある日を返す
	 *
	 * @param    \DateTimeImmutable    $base_date
	 * @return    CalendarDate|null
	 */
	public function recent_enable_date( \DateTimeImmutable $base_date ): ?CalendarDate
	{
		$end_of_month = $this->calendar_term->end_of_month()->format( 'Y-m-d' );
		$interval = new \DateInterval( 'P1D' );

		for (
			$current = $base_date;
			$current->format( 'Y-m-d' ) <= $end_of_month;
			$current = $current->add( $interval )
		) {
			$current_str = $current->format( 'Y-m-d' );

			if (
				isset( $this->calendar_dates[ $current_str ] )
				&& ! empty( $this->calendar_dates[ $current_str ]->schedules() )
			) {
				return $this->calendar_dates[ $current_str ];
			}
		}

		return null;
	}

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
		$dates = array();
		foreach ( $this->calendar_term as $date_str => $_ ) {
			if ( ! isset( $this->calendar_dates[ $date_str ] ) ) {
				throw new \RuntimeException();
			}

			$calendar_date = $this->calendar_dates[ $date_str ];
			$calendar_date->set_enable( $this->border_date );  // TODO: セッターでやるのやめたい

			$dates[] = $calendar_date->to_array(
				$date_class_formatter,
				$schedule_formatter
			);
		}

		return $dates;
	}
}
