<?php

namespace QMS4\Area;

use QMS4\Area\AreaTree;


class AreaTreeFactory
{
	/** @var    AreaTree */
	private static $instance = null;

	/**
	 * @return    AreaTree
	 */
	public function create(): AreaTree
	{
		if ( ! is_null( self::$instance ) ) { return self::$instance; }

		$query = new \WP_Query( array(
			'post_type' => 'qms4__area_master',
			'post_status' => 'publish',
			'orderby' => 'menu_order',
			'order' => 'ASC',
			'posts_per_page' => -1,
		) );

		return self::$instance = $this->build( $query->posts );
	}

	/**
	 * @param    \WP_Post[]    $wp_posts
	 * @param    int    $parent_id
	 * @return    AreaTree
	 */
	private function build( array $wp_posts, int $parent_id = 0 ): AreaTree
	{
		$root_posts = array();
		$post_dict = array();
		$child_ids_dict = array();
		foreach ( $wp_posts as $wp_post ) {
			if ( $wp_post->post_parent == $parent_id ) {
				$root_posts[] = $wp_post;
			}

			$post_dict[ $wp_post->ID ] = $wp_post;

			if ( ! isset( $child_ids_dict[ $wp_post->post_parent ] ) ) {
				$child_ids_dict[ $wp_post->post_parent ] = array();
			}
			$child_ids_dict[ $wp_post->post_parent ][] = $wp_post->ID;
		}

		$sub_trees = array();
		$child_ids_list = array();
		$max_depth = 0;
		foreach ( $root_posts as $wp_post ) {
			list( $child_ids, $children, $depth ) = $this->collect(
				$post_dict,
				$child_ids_dict,
				$wp_post->ID,
				1
			);

			$sub_trees[ $wp_post->ID ] = $this->build( $children, $wp_post->ID );
			$child_ids_list[ $wp_post->ID ] = $child_ids;
			$max_depth = max( $max_depth, $depth );
		}

		return new AreaTree( $root_posts, $sub_trees, $child_ids_list, $max_depth );
	}

	/**
	 * @param    array<int,\WP_Post>    $post_dict
	 * @param    array<int,array>    $child_ids_dict
	 * @param    int    $post_id
	 * @param    int    depth
	 * @return    array
	 */
	private function collect(
		array $post_dict,
		array $child_ids_dict,
		int $post_id,
		int $depth
	): array
	{
		$child_ids = isset( $child_ids_dict[ $post_id ] )
			? $child_ids_dict[ $post_id ]
			: array();

		$all_child_ids = $child_ids;
		$children = array();
		$max_depth = $depth;
		foreach ( $child_ids as $child_id ) {
			list ( $family_ids, $family, $_depth ) = $this->collect(
				$post_dict,
				$child_ids_dict,
				$child_id,
				$depth + 1
			);

			$all_child_ids = array_merge( $all_child_ids, $family_ids );
			$children[] = $post_dict[ $child_id ];
			$children = array_merge( $children, $family );
			$max_depth = max( $max_depth, $_depth );
		}

		return array( $all_child_ids, $children, $max_depth );
	}
}
