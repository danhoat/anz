<?php

namespace QMS4\Event\CalendarMonth;

use QMS4\Event\BorderDate;
use QMS4\Event\CalendarMonth\DateClassFormatter;
use QMS4\Event\CalendarMonth\ScheduleFormatter;
use QMS4\Item\Post\Schedule;


class CalendarDate
{
	/** @var    \DateTimeImmutable */
	private $date;

	/** @var    Schedule[] */
	private $schedules;

	/** @var    bool */
	private $enable;

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

	/**
	 * @return    bool
	 */
	public function enable(): bool
	{
		return $this->enable;
	}

	/**
	 * TODO: セッターでやるのやめたい
	 *
	 * @param    BorderDate    $border_date
	 * @return    void
	 */
	public function set_enable( BorderDate $border_date ): void
	{
		$this->enable = $this->date->format( 'Y-m-d' ) >= $border_date->format( 'Y-m-d' );
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
			'enable' => $this->enable,
			'date' => $this->date->format( 'Y-m-d' ),
			'date_class' => $date_class_formatter->format( $this->date ),
			'schedules' => $schedules,
		);
	}
}
