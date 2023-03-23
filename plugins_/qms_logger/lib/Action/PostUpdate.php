<?php
namespace QMS\Logger\Action;

use QMS\Logger\Logger\UserActionLogger;


class PostUpdate
{
	public function __invoke(
		string $new_status,
		string $old_status,
		\WP_Post $post
	): void
	{
		UserActionLogger::channel( 'core' )
			->info( 'transition_post_status', array(
				'post_type' => $post->post_type,
				'ID' => $post->ID,
				'new_status' => $new_status,
				'old_status' => $old_status,
				'post_author' => (int) $post->post_author,
			) );
	}
}
