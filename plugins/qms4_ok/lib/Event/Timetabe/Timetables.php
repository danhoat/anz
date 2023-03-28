<?php

namespace QMS4\Event\Timetable;

use QMS4\Item\Post\Schedule;


class Timetables
{
	/** @var    Schedule[] */
	private $schedules;

	public function __construct( array $schedules )
	{
		$this->schedules = $schedules;
	}

	public function to_array(): array
	{
		$dates = array();
		foreach ( $this->schedules as $schedule ) {
			$dates[] = array(
				'date' => (string) $schedule->date( 'Y-m-d' ),
				'timetable' => $schedule->timetable->to_array(),
				'button_text' => $schedule->timetable_button_text,
			);
		}

		return $dates;
	}
}
