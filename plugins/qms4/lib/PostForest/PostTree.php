<?php

namespace QMS4\PostForest;

use QMS4\PostForest\PostForest;
use QMS4\PostForest\MapReduce\MapperInterface as Mapper;
use QMS4\PostForest\MapReduce\ReducerInterface as Reducer;


class PostTree
{
	/** @var    \WP_Post */
	private $wp_post;

	/** @var    array<string,mixed> */
	private $fields;

	/** @var    PostForest */
	private $sub_forest;

	/**
	 * @param    \WP_Post    $wp_post
	 * @param    array<string,mixed>    $fields
	 * @param    PostForest    $sub_forest
	 */
	public function __construct(
		\WP_Post $wp_post,
		array $fields,
		PostForest $sub_forest
	)
	{
		$this->wp_post = $wp_post;
		$this->fields = $fields;
		$this->sub_forest = $sub_forest;
	}

	/**
	 * @param    string    $name
	 * @return    mixed
	 */
	public function __get( string $name )
	{
		return array_key_exists( $name, $this->fields )
			? $this->fields[ $name ]
			: $this->wp_post->$name;
	}

	// ====================================================================== //

	public function update_fields( Mapper $mapper, Reducer $reducer )
	{
		$self_value = $mapper->map( $this->wp_post );

		$child_values = array();
		foreach ( $this->sub_forest as $sub_tree ) {
			$child_values[] = $sub_tree->update_fields( $mapper, $reducer );
		}

		return $this->fields = $reducer->reduce( $self_value, $child_values );
	}

	/**
	 * @param    callable    $callback
	 * @return    self
	 */
	public function filter( callable $callback ): PostTree
	{
		$new_sub_forest = $this->sub_forest->filter( $callback );

		return new self( $this->wp_post, $this->fields, $new_sub_forest );
	}

	// ====================================================================== //

	/**
	 * @return    \WP_Post
	 */
	public function wp_post(): \WP_Post
	{
		return $this->wp_post;
	}

	/**
	 * @return    PostForest
	 */
	public function sub_forest()
	{
		return $this->sub_forest;
	}

	/**
	 * @return    bool
	 */
	public function is_leaf(): bool
	{
		return $this->sub_forest->is_empty();
	}

	/**
	 * @param    string    $name
	 * @param    mixed    $default
	 * @return    mixed
	 */
	public function field( string $name, $default = null )
	{
		return array_key_exists( $name, $this->fields )
			? $this->fields[ $name ]
			: $default;
	}
}
