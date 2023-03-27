<?php

namespace QMS4\Action\PostMeta;

use QMS4\MetaBox\EventOverwrite;
use QMS4\MetaBox\EventSchedules;
use QMS4\PostMeta\Timetable;
use QMS4\PostMeta\TimetableButtonText;


class SaveEventPostMeta
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
	 * @param    int    $post_id
	 * @param    \WP_Post    $wp_post
	 * @return    void
	 */
	public function __invoke( int $post_id, \WP_Post $wp_post )
	{
		if ( $this->post_type === $wp_post->post_type ) {
			if ( $this->post_type !== 'qms4__block_template' ) {
				( new EventSchedules( $this->post_type ) )->save_post( $post_id, $wp_post );
			}

			( new Timetable( $this->post_type ) )->save_post( $post_id, $wp_post );
			( new TimetableButtonText( $this->post_type ) )->save_post( $post_id, $wp_post );
		}


		$schedule_post_type = "{$this->post_type}__schedule";
		if ( $schedule_post_type === $wp_post->post_type ) {
			( new EventOverwrite( $schedule_post_type ) )->save_post( $post_id, $wp_post );
		}
	}
}
