<?php

namespace QMS4\Util;


class FindInPostTree
{
	/** @var    array<int,\WP_Post> */
	private $posts_dict;

	/** @var    array<int,array> */
	private $child_ids_dict;

	/** @var    array<int,array> */
	private $families = null;

	/** @var    array<int,array> */
	private $cache = array();

	public function __construct( array $wp_posts )
	{
		$posts_dict = array();
		$child_ids_dict = array();
		foreach ( $wp_posts as $wp_post ) {
			$posts_dict[ $wp_post->ID ] = $wp_post;

			if ( ! isset( $child_ids_dict[ $wp_post->post_parent ] ) ) {
				$child_ids_dict[ $wp_post->post_parent ] = array();
			}
			$child_ids_dict[ $wp_post->post_parent ][] = $wp_post->ID;
		}

		$this->posts_dict = $posts_dict;
		$this->child_ids_dict = $child_ids_dict;
	}

	// ====================================================================== //

	/**
	 * @param    callable    $get_field
	 * @param    mixed|mixed[]    $cond
	 * @return    int[]
	 */
	public function find( callable $get_status, $cond ): array
	{
		$families = $this->families();

		$cond = is_array( $cond ) ? $cond : array( $cond );

		$matches = array();
		foreach ( $families as $post_id => $family ) {
			$wp_post = $this->posts_dict[ $post_id ];
			$status = $get_status( $wp_post );

			if ( in_array( $status, $cond, /* $strict = */ true ) ) {
				$matches = array_merge( $matches, $family );
			}
		}

		return array_unique( $matches );
	}

	// ====================================================================== //

	/**
	 * @return    array<int,array>
	 */
	private function families(): array
	{
		if ( ! is_null( $this->families ) ) { return $this->families; }

		$families = array();
		foreach ( $this->posts_dict as $post_id => $_ ) {
			$families[ $post_id ] = $this->collect( $post_id );
		}

		return $this->families = $families;
	}

	/**
	 * @param    int    $post_id
	 * @return    int[]
	 */
	private function collect( int $post_id ): array
	{
		if ( isset( $this->cache[ $post_id ] ) ) {
			return $this->cache[ $post_id ];
		}

		$children_ids = $this->child_ids_dict[ $post_id ] ?? array();

		$results = array( $post_id );
		foreach ( $children_ids as $children_id ) {
			$results = array_merge( $results, $this->collect( $children_id ) );
		}

		return $this->cache[ $post_id ] = $results;
	}
}
