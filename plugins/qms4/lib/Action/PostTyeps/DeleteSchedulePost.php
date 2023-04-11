<?php

namespace QMS4\Action\PostTyeps;

use QMS4\PostMeta\ParentEventId;


class DeleteSchedulePost
{
	/**
	 * @param    int    $post_id
	 * @param    \WP_Post    $wp_post
	 * @return    void
	 */
	public function __invoke( int $post_id, \WP_Post $wp_post )
	{
		$post_type = $wp_post->post_type;
		$schedule_post_type = "{$post_type}__schedule";

		if ( ! post_type_exists( $schedule_post_type ) ) { return; }

		$query = new \WP_Query( array(
			'post_type' => $schedule_post_type,
			'post_status' => array(
				'publish',
				'future',
				'draft',
				'pending',
				'private',
				'trash',
				'auto-draft',
			),
			'meta_query' => array(
				array(
					'key' => ParentEventId::KEY,
					'value' => $post_id,
				),
			),
			'posts_per_page' => 999999,
			'fields' => 'ids',
		) );

		foreach ( $query->posts as $schedule_id ) {
			wp_delete_post( $schedule_id, /* $force_delete = */ true );
		}
	}
}
