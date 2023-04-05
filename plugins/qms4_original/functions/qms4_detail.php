<?php

use QMS4\Item\Post\Post;
use QMS4\Param\Param;
use QMS4\PostTypeMeta\PostTypeMetaFactory;


/**
 * @param    int    $post_id
 * @param    array<string,mixed>    $param
 * @return    Post
 */
function qms4_detail( int $post_id, array $param = array() ): Post
{
	$wp_post = get_post( $post_id );

	$factory = new PostTypeMetaFactory();
	$post_type_meta = $factory->from_name( array( $wp_post->post_type ) );

	$param = Param::new( $post_type_meta, $param );

	$item_class = Post::class;
	$item_class = apply_filters(
		'qms4_detail_item_class',
		$item_class,
		$wp_post->post_type
	);

	return new $item_class( $wp_post, $param );
}
