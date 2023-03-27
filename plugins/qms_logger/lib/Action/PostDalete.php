<?php
namespace QMS\Logger\Action;

use QMS\Logger\Logger\UserActionLogger;


class PostDalete
{
	public function __invoke( int $post_id, \WP_Post $post ): void
	{
		UserActionLogger::channel( 'core' )
			->info( 'delete_post', array(
				'post_type' => $post->post_type,
				'ID' => $post_id,
				'post_author' => (int) $post->post_author,
			) );
	}
}
