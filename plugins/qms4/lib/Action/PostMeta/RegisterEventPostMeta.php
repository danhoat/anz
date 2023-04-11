<?php

namespace QMS4\Action\PostMeta;

use QMS4\PostMeta\EventDate;
use QMS4\PostMeta\ParentEventId;
use QMS4\PostMeta\Schedules;
use QMS4\PostMeta\Timetable;
use QMS4\PostMeta\TimetableButtonText;


class RegisterEventPostMeta
{
	/** @var    string */
	private $post_type;

	/**
	 * @param    string    $post_type
	 */
	public function __construct( string $post_type )
	{
		$this->post_type = $post_type;
	}

	public function __invoke(): void
	{
		if ( $this->post_type !== 'qms4__block_template' ) {
			( new Schedules( $this->post_type ) )->register_meta();
		}

		( new Timetable( $this->post_type ) )->register_meta();
		( new TimetableButtonText( $this->post_type ) )->register_meta();

		$schedule_post_type = "{$this->post_type}__schedule";

		( new EventDate( $schedule_post_type ) )->register_meta();
		( new ParentEventId( $schedule_post_type ) )->register_meta();
	}
}
