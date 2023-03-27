<?php

namespace QMS4\Area;


class AreaTree implements \IteratorAggregate
{
	/** @var    \WP_Post[] */
	private $wp_posts;

	/** @var    array<int,AreaTree> */
	private $sub_trees;

	/** @var    array<int,array> */
	private $child_ids_dict;

	/** @var    int */
	private $depth;

	/**
	 * @param    \WP_Post[]    $wp_posts
	 * @param    array<int,AreaTree>    $sub_trees
	 * @param    int[]    $child_ids
	 * @param    int    $depth
	 */
	public function __construct(
		array $wp_posts,
		array $sub_trees,
		array $child_ids_dict,
		int $depth
	)
	{
		$this->wp_posts = $wp_posts;
		$this->sub_trees = $sub_trees;
		$this->child_ids_dict = $child_ids_dict;
		$this->depth = $depth;
	}

	// ====================================================================== //

	/**
	 * @return    \Generator<{\WP_Post,self}>
	 */
	public function getIterator(): \Traversable
	{
		foreach ( $this->wp_posts as $wp_post ) {
			$sub_tree = $this->sub_trees[ $wp_post->ID ];
			yield array( $wp_post, $sub_tree );
		}
	}

	// ====================================================================== //

	/**
	 * @return    bool
	 */
	public function is_empty(): bool
	{
		return empty( $this->wp_posts );
	}

	/**
	 * @return    int[]
	 */
	public function child_ids( int $post_id ): array
	{
		return $this->child_ids_dict[ $post_id ];
	}

	/**
	 * @return    int
	 */
	public function depth(): int
	{
		return $this->depth;
	}
}
