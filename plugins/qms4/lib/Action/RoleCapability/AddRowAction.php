<?php

namespace QMS4\Action\RoleCapability;


class AddRowAction
{
	/**
	 * @param    string[]    $actions
	 * @param    \WP_Post    $post
	 * @return    string[]
	 */
	public function __invoke( array $actions, \WP_Post $post ): array
	{
		if ( $post->post_type !== 'qms4' ) { return $actions; }

		$url = admin_url( "/post.php?post={$post->ID}&action=reset_caps" );
		$actions[ 'reset_caps' ] = "<a href={$url}>権限リセット</a>";

		return $actions;
	}
}
