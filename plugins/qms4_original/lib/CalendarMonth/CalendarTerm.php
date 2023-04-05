<?php

namespace QMS4\CalendarMonth;

use QMS4\CalendarMonth\DayOfWeek;


class CalendarTerm implements \IteratorAggregate
{
	/** @var    \DateTimeImmutable */
	private $start_of_term;

	/** @var    \DateTimeImmutable */
	private $start_of_month;

	/** @var    \DateTimeImmutable */
	private $end_of_month;

	/** @var    \DateTimeImmutable */
	private $end_of_term;

	/**
	 * @param    \DateTimeImmutable    $start_of_term
	 * @param    \DateTimeImmutable    $start_of_month
	 * @param    \DateTimeImmutable    $end_of_month
	 * @param    \DateTimeImmutable    $end_of_term
	 */
	private function __construct(
		\DateTimeImmutable $start_of_term,
		\DateTimeImmutable $start_of_month,
		\DateTimeImmutable $end_of_month,
		\DateTimeImmutable $end_of_term
	)
	{
		$this->start_of_term = $start_of_term;
		$this->start_of_month = $start_of_month;
		$this->end_of_month = $end_of_month;
		$this->end_of_term = $end_of_term;
	}

	/**
	 * @param    DayOfWeek    $start_of_week
	 * @param    \DateTimeImmutable    $base_date
	 * @return    self
	 */
	public static function from_base_date(
		DayOfWeek $start_of_week,
		\DateTimeImmutable $base_date
	): CalendarTerm
	{
		$start_of_month = $base_date->modify( 'first day of this month' );

		$start_of_month__day = DayOfWeek::from_datetime( $start_of_month );
		$start_of_term = $start_of_month__day->is_same( $start_of_week )
			? $start_of_month
			: $start_of_month->modify( 'previous ' . $start_of_week->format( 'D' ) );

		$end_of_month = $base_date->modify( 'last day of this month' );

		$end_of_month__day = DayOfWeek::from_datetime( $end_of_month );
		$end_of_term = $end_of_month__day->is_same( $start_of_week->previous() )
			? $end_of_month
			: $end_of_month->modify( 'next ' . $start_of_week->previous()->format( 'D' ) );

		return new self(
			$start_of_term,
			$start_of_month,
			$end_of_month,
			$end_of_term
		);
	}

	/**
	 * @param    DayOfWeek    $start_of_week
	 * @param    int    $year
	 * @param    int    $month
	 * @return    self
	 */
	public static function from_year_month(
		DayOfWeek $start_of_week,
		int $year,
		int $month
	): CalendarTerm
	{
		$tz = wp_timezone();
		$base_date = new \DateTimeImmutable( "{$year}/{$month}/1", $tz );

		return self::from_base_date( $start_of_week, $base_date );
	}

	// ====================================================================== //

	/**
	 * @return    \DateTimeImmutable
	 */
	public function start_of_term(): \DateTimeImmutable
	{
		return $this->start_of_term;
	}

	/**
	 * @return    \DateTimeImmutable
	 */
	public function start_of_month(): \DateTimeImmutable
	{
		return $this->start_of_month;
	}

	/**
	 * @return    \DateTimeImmutable
	 */
	public function end_of_month(): \DateTimeImmutable
	{
		return $this->end_of_month;
	}

	/**
	 * @return    \DateTimeImmutable
	 */
	public function end_of_term(): \DateTimeImmutable
	{
		return $this->end_of_term;
	}

	// ====================================================================== //

	/**
	 * @return    \Genrator<string,\DateTimeImmutable>
	 */
	public function getIterator(): \Traversable
	{
		$interval = new \DateInterval( 'P1D' );
		$end_of_term_str = $this->end_of_term->format( 'Y-m-d' );

		$current = $this->start_of_term;
		$current_str = $current->format( 'Y-m-d' );
		do {
			yield $current_str => $current;

			$current = $current->add( $interval );
			$current_str = $current->format( 'Y-m-d' );
		} while ( $current_str <= $end_of_term_str );
	}
}
