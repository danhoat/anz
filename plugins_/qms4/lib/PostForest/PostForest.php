<?php

namespace QMS4\PostForest;

use QMS4\PostForest\PostTree;
use QMS4\PostForest\MapReduce\MapperInterface as Mapper;
use QMS4\PostForest\MapReduce\ReducerInterface as Reducer;


class PostForest implements \ArrayAccess, \IteratorAggregate
{
	/** @var    array<int,PostTree> */
	private $post_trees;

	/**
	 * @param    array<int,PostTree>    $post_trees
	 */
	public function __construct( array $post_trees )
	{
		$this->post_trees = $post_trees;
	}

	public function init_fields( Mapper $mapper, Reducer $reducer )
	{
		foreach ( $this->post_trees as & $post_tree ) {
			$post_tree->update_fields( $mapper, $reducer );
		}
	}

	/**
	 * @param    callable    $callback
	 * @return    self
	 */
	public function filter( callable $callback ): PostForest
	{
		$new_post_trees = array();
		$post_trees = array_values( $this->post_trees );
		foreach ( $post_trees as $index => $post_tree ) {
			if ( $callback( $post_tree, $index ) ) {
				$new_post_trees[] = $post_tree->filter( $callback );
			}
		}

		return new self( $new_post_trees );
	}

	// ====================================================================== //

	/**
	 * @param    int|string    $offset
	 * @return    bool
	 */
	public function offsetExists( $offset ): bool
	{
		return isset( $this->post_trees[ $offset ] );
	}

	/**
	 * @param    int|string    $offset
	 * @return    PostTree
	 */
	public function offsetGet( $offset ): PostTree
	{
		return $this->post_tree[ $offset ];
	}

	/**
	 * @param    int|string    $offset
	 * @param    mixed    $value
	 * @return    void
	 */
	public function offsetSet( $offset, $value ): void
	{
		throw new \LogicException();
	}

	/**
	 * @param    int|string    $offset
	 * @return    void
	 */
	public function offsetUnset( $offset ): void
	{
		throw new \LogicException();
	}

	// ====================================================================== //

	/**
	 * @return    \Generator<int,PostTree>
	 */
	public function getIterator(): \Traversable
	{
		$post_trees = array_values( $this->post_trees );
		foreach ( $this->post_trees as $index => $post_tree ) {
			yield $index => $post_tree;
		}
	}

	// ====================================================================== //

	/**
	 * @return    bool
	 */
	public function is_empty(): bool
	{
		return empty( $this->post_trees );
	}

	/**
	 * @param    callable    $callback
	 * @param    string    $name
	 * @param    mixed    $default
	 * @return    mixed
	 */
	public function map_field( callable $callback, string $name, $default = null )
	{
		$values = array();
		foreach ( $this->post_trees as $post_id => $post_tree ) {
			$values[ $post_id ] = $post_tree->field( $name, $default );
		}

		return $callback( $values );
	}
}
