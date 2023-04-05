<?php

namespace QMS4\CalendarMonth;

use QMS4\CalendarMonth\DateClassFormatter;
use QMS4\CalendarMonth\ScheduleFormatter;
use QMS4\Item\Post\Schedule;


class CalendarDate
{
	/** @var    \DateTimeImmutable */
	private $date;

	/** @var    Schedule[] */
	private $schedules;

	/**
	 * @param    \DateTimeImmutable    $date
	 * @param    Schedule[]    $schedules
	 *
	 */
	public function __construct( \DateTimeImmutable $date, array $schedules )
	{
		$this->date = $date;
		$this->schedules = $schedules;
	}

	/**
	 * @param    Schedule    $schedule
	 * @return    void
	 */
	public function push( Schedule $schedule ): void
	{
		$this->schedules[] = $schedule;
	}

	// ====================================================================== //

	/**
	 * @return    \DateTimeImmutable
	 */
	public function date(): \DateTimeImmutable
	{
		return $this->date;
	}

	/**
	 * @return    Schedule[]
	 */
	public function schedules(): array
	{
		return $this->schedules;
	}

	// ====================================================================== //

	/**
	 * @param    DateClassFormatter    $date_class_formatter
	 * @param    ScheduleFormatter    $schedule_formatter
	 * @return    array<string,mixed>
	 */
	public function to_array(
		DateClassFormatter $date_class_formatter,
		ScheduleFormatter $schedule_formatter
	): array
	{
		$schedules = array();
		foreach ( $this->schedules as $schedule ) {
			$schedules[] = $schedule_formatter->format( $schedule );
		}

		return array(
			'date' => $this->date->format( 'Y-m-d' ),
			'date_class' => $date_class_formatter->format( $this->date ),
			'schedules' => $schedules,
		);
	}
}
