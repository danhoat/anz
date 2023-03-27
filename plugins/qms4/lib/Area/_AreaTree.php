<?php

namespace QMS4\Area;

use QMS4\PostForest\PostForest;


class _AreaTree implements \IteratorAggregate
{
	/** @var    PostForest */
	private $post_forest;

	/**
	 * @param    PostForest    $post_forest
	 */
	public function __construct( PostForest $post_forest )
	{
		$this->post_forest = $post_forest;
	}

	// ====================================================================== //

	/**
	 * @return    \Generator<{\WP_Post,self}>
	 */
	public function getIterator(): \Traversable
	{
		foreach ( $this->post_forest as $post_tree ) {
			yield array(
				$post_tree->wp_post(),
				new self( $post_tree->sub_forest() ),
			);
		}
	}

	// ====================================================================== //

	/**
	 * @return    bool
	 */
	public function is_empty(): bool
	{
		return $this->post_forest->is_empty();
	}

	/**
	 * @return    int
	 */
	public function depth(): int
	{
		$length = array();
		foreach ( $this->post_forest as $post_tree ) {
			$length[] = $post_tree->field( 'length' );
		}

		switch ( count( $length ) ) {
			case 0: return 0;
			case 1: return $length[ 0 ];
			default: return max( $length );
		}
	}
}
