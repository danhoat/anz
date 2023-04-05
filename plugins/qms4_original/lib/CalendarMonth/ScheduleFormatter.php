<?php

namespace QMS4\CalendarMonth;

use QMS4\Item\Post\Schedule;


class ScheduleFormatter
{
	/**
	 * @return    array<string,mixed>
	 */
	public function format( Schedule $schedule ): array
	{
		return array(
			'id' => $schedule->id,
			'title' => $schedule->title,
		);
	}
}
