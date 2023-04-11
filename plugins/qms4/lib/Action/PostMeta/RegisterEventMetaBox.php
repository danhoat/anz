<?php

namespace QMS4\Action\PostMeta;

use QMS4\MetaBox\EventOverwrite;
use QMS4\MetaBox\EventSchedules;
use QMS4\MetaBox\EventStructure;
use QMS4\PostMeta\EventDate;
use QMS4\PostMeta\ParentEventId;
use QMS4\PostMeta\Timetable;
use QMS4\PostMeta\TimetableButtonText;


class RegisterEventMetaBox
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

	/**
	 * @param    string    $post_type
	 * @param    \WP_Post    $wp_post
	 * @return    void
	 */
	public function __invoke( string $post_type, \WP_Post $wp_post )
	{
		if ( $this->post_type === $post_type ) {
			if ( $this->post_type !== 'qms4__block_template' ) {
				( new EventSchedules( $this->post_type ) )->add_meta_box( $wp_post );
			}

			( new Timetable( $this->post_type ) )->add_meta_box( $wp_post );
			( new TimetableButtonText( $this->post_type ) )->add_meta_box( $wp_post );
		}


		$schedule_post_type = "{$this->post_type}__schedule";
		if ( $schedule_post_type === $post_type ) {
			( new EventStructure( $schedule_post_type ) )->add_meta_box( $wp_post );
			( new EventOverwrite( $schedule_post_type ) )->add_meta_box( $wp_post );
		}
	}
}
