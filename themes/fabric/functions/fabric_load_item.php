<?php

use QMS4\Item\Post\AbstractPost as Post;


/**
 * @return    Post
 */
function fabric_load_item(): Post
{
	global $post, $item;

	if (
		! ( $post instanceof \WP_Post ) && ! ( $item instanceof Post )
	) {
		throw new \RuntimeException( '$post or $item is not found.' );
	}

	if ( $item instanceof Post ) {
		return $item;
	} elseif ( function_exists( 'qms4_detail' ) ) {
		return qms4_detail( $post->ID, array() );
	}
}
