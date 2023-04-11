<?php

namespace QMS4\Fetch;

use QMS4\Event\BorderDate;
use QMS4\Event\BorderDateFactory;
use QMS4\Item\Post\Schedule;
use QMS4\Param\Param;
use QMS4\PostMeta\EventDate;
use QMS4\PostMeta\ParentEventId;


class EventSchedules
{
	/**
	 * @param    int    $post_id
	 * @param    bool    $enable_schedule_only
	 * @param    string[]    $dates
	 */
	public function fetch(
		int $post_id,
		bool $enable_schedule_only = false,
		array $dates = array()
	)
	{
		$event_post = get_post( $post_id );
		$wp_posts = $this->query( $event_post, $enable_schedule_only, $dates );

		$param = new Param( array() );

		$schedules = array();
		foreach ( $wp_posts as $wp_post ) {
			$schedules[] = new Schedule( $wp_post, $param );
		}

		return $schedules;
	}

	/**
	 * @param    \WP_Post    $post_id
	 * @param    bool    $enable_schedule_only
	 * @param    string[]    $dates
	 * @return    \WP_Post[]
	 */
	private function query(
		\WP_Post $event_post,
		bool $enable_schedule_only = false,
		array $dates
	): array
	{
		$schedule_post_type = "{$event_post->post_type}__schedule";

		$meta_query = array(
			array(
				'key' => ParentEventId::KEY,
				'value' => $event_post->ID,
			),
		);

		if ( $enable_schedule_only ) {
			$factory = new BorderDateFactory();
			$border_date = $factory->from_post_type( $event_post->post_type );

			$meta_query[] = array(
				'key' => EventDate::KEY,
				'value' => $border_date->format( 'Y-m-d' ),
				'compare' => '>=',
				'type' => 'DATE',
			);
		}

		if ( ! empty( $dates ) ) {
			$meta_query[] = array(
				'key' => EventDate::KEY,
				'value' => $dates,
				'compare' => 'IN',
				'type' => 'DATE',
			);
		}

		$query = new \WP_Query( array(
			'post_type' => $schedule_post_type,
			'post_status' => $enable_schedule_only
				? 'publish'
				: array( 'publish', 'private' ),
			'orderby' => 'meta_value_date',
			'meta_key' => EventDate::KEY,
			'order' => 'ASC',
			'posts_per_page' => 999999,
			'meta_query' => $meta_query,
		) );

		return $query->posts;
	}
}
