<?php

namespace QMS4\Action\PostMeta;

use QMS4\PostMeta\EventDate;
use QMS4\PostMeta\ParentEventId;
use QMS4\PostMeta\Schedules;
use QMS4\PostMeta\Timetable;
use QMS4\PostMeta\TimetableButtonText;


class RegisterEventRestField
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

	public function __invoke( \WP_REST_Server $wp_rest_server ): void
	{
		if ( $this->post_type !== 'qms4__block_template' ) {
			( new Schedules( $this->post_type ) )->register_field( $wp_rest_server );
		}

		( new Timetable( $this->post_type ) )->register_field( $wp_rest_server );
		( new TimetableButtonText( $this->post_type ) )->register_field( $wp_rest_server );

		$schedule_post_type = "{$this->post_type}__schedule";

		( new EventDate( $schedule_post_type ) )->register_field( $wp_rest_server );
		( new ParentEventId( $schedule_post_type ) )->register_field( $wp_rest_server );
		( new Timetable( $schedule_post_type ) )->register_field( $wp_rest_server );
		( new TimetableButtonText( $schedule_post_type ) )->register_field( $wp_rest_server );
	}
}
