<?php

namespace QMS4\PostForest;

use QMS4\PostForest\BuildIdTree;
use QMS4\PostForest\PostForest;
use QMS4\PostForest\PostTree;


class PostForestFactory
{
	/** @var    array<string,PostForest> */
	private static $cache = null;

	/**
	 * @param    string    $post_type
	 * @return    PostForest
	 */
	public function from_post_type( string $post_type ): PostForest
	{
		if ( isset( self::$cache[ $post_type ] ) ) {
			return self::$cache[ $post_type ];
		}

		$query = new \WP_Query( array(
			'post_type' => $post_type,
			'post_status' => 'publish',
			'orderby' => 'menu_order',
			'order' => 'ASC',
			'posts_per_page' => -1,
		) );

		return self::$cache[ $post_type ] = $this->build( $query->posts );
	}

	/**
	 * @param    \WP_Post[]    $wp_posts
	 * @param    int    $parent_id
	 * @return    AreaTree
	 */
	private function build( array $wp_posts ): PostForest
	{
		$post_dict = array();
		$child_ids_dict = array();
		foreach ( $wp_posts as $wp_post ) {
			$post_dict[ $wp_post->ID ] = $wp_post;

			if ( ! isset( $child_ids_dict[ $wp_post->post_parent ] ) ) {
				$child_ids_dict[ $wp_post->post_parent ] = array();
			}
			$child_ids_dict[ $wp_post->post_parent ][] = $wp_post->ID;
		}

		$id_tree = BuildIdTree::build( $child_ids_dict, 0 );

		return $this->create_forest( $post_dict, $id_tree[ 'sub_trees' ] );
	}

	/**
	 * @param    array<int,\WP_Post>    $post_dict
	 * @param    array[]    $id_trees
	 * @return    PostForest
	 */
	private function create_forest(
		array $post_dict,
		array $id_trees
	): PostForest
	{
		$post_trees = array();
		foreach ( $id_trees as $id_tree ) {
			$post_id = $id_tree[ 'post_id' ];
			$post_trees[ $post_id ] = $this->create_tree( $post_dict, $id_tree );
		}

		return new PostForest( $post_trees );
	}

	private function create_tree( array $post_dict, array $id_tree )
	{
		$post_id = $id_tree[ 'post_id' ];
		$wp_post = $post_dict[ $post_id ];

		$sub_id_trees = $id_tree[ 'sub_trees' ];
		$sub_forest = $this->create_forest( $post_dict, $sub_id_trees );

		return new PostTree( $wp_post, array(), $sub_forest );
	}
}
