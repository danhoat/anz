<?php

namespace QMS4\Event\CalendarMonth;

use QMS4\Event\BorderDate;
use QMS4\Event\CalendarMonth\CalendarTerm;


class DateClassFormatter
{
	/** @var     string */
	private $today_str;

	/** @var     string */
	private $border_date_str;

	/** @var     string */
	private $start_of_month_str;

	/** @var     string */
	private $end_of_month_str;

	/** @var     string */
	private $prefix;

	/**
	 * @param    \DateTimeImmutable    $today
	 * @param    BorderDate    $border_date
	 * @param    CalendarTerm    $calendar_term
	 * @param    string    $prefix
	 */
	public function __construct(
		\DateTimeImmutable $today,
		BorderDate $border_date,
		CalendarTerm $calendar_term,
		string $prefix
	)
	{
		$this->today_str = $today->format( 'Y-m-d' );
		$this->border_date_str = $border_date->format( 'Y-m-d' );
		$this->start_of_month_str = $calendar_term->start_of_month()->format( 'Y-m-d' );
		$this->end_of_month_str = $calendar_term->end_of_month()->format( 'Y-m-d' );
		$this->prefix = $prefix;
	}

	/**
	 * @param    \DateTimeImmutable    $date
	 * @return    string[]
	 */
	public function format( \DateTimeImmutable $date ): array
	{
		$date_str = $date->format( 'Y-m-d' );

		$dow = strtolower( $date->format( 'D' ) );
		$is_weekend = $dow == 'sat' || $dow == 'sun';

		$is_out_of_month_before = $date_str < $this->start_of_month_str;
		$is_out_of_month_after = $this->end_of_month_str < $date_str;

		$date_classes = array(
			$date_str < $this->today_str ? $this->prefix . 'past' : '',
			$date_str == $this->today_str ? $this->prefix . 'today' : '',
			$date_str > $this->today_str ? $this->prefix . 'future' : '',

			$date_str < $this->border_date_str ? $this->prefix . 'disable' : '',
			$date_str == $this->border_date_str ? $this->prefix . 'border-date' : '',
			$date_str >= $this->border_date_str ? $this->prefix . 'enable' : '',

			$this->prefix . $dow,
			$is_weekend ? $this->prefix . 'weekend' : '',
			! $is_weekend ? $this->prefix . 'weekday' : '',

			$is_out_of_month_before || $is_out_of_month_after ? $this->prefix . 'out-of-month' : '',
			$is_out_of_month_before ? $this->prefix . 'out-of-month-before' : '',
			$is_out_of_month_after ? $this->prefix . 'out-of-month-after' : '',
			! $is_out_of_month_before && ! $is_out_of_month_after ? $this->prefix . 'in-month' : '',

			$this->prefix . "year-{$date->format('Y')}",
			$this->prefix . "month-{$date->format('m')}",
			$this->prefix . "day-{$date->format('d')}",
		);

		return array_values( array_filter( $date_classes ) );
	}
}
