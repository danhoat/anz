<?php

namespace QMS4\Param;

use QMS4\PostTypeMeta\PostTypeMetaInterface as PostTypeMeta;


class Param implements \ArrayAccess, \IteratorAggregate
{
	/** @var    array<string,mixed> */
	private $param;

	public function __construct( array $param = array() )
	{
		$this->param = $param;
	}

	/**
	 * @param    PostTypeMeta    $post_type_meta
	 * @param    array<string,mixed>    $param
	 * @return    self
	 */
	public static function new(
		PostTypeMeta $post_type_meta,
		array $param = array()
	): self
	{
		$default_param = array(
			'count' => $post_type_meta->posts_per_page() ?: -1,
			'orderby' => $post_type_meta->orderby(),
			'order' => $post_type_meta->order(),
			'new_date' => $post_type_meta->new_date(),
			'new_class' => $post_type_meta->new_class(),
			'term_html' => $post_type_meta->term_html(),
			'date_format' => get_option( 'date_format' ),
			'time_format' => get_option( 'time_format' ),
		);

		$param = array_merge( $default_param, $param );

		return new self( $param );
	}

	// ====================================================================== //

	/**
	 * @param    array<string,mixed>    $default_param
	 * @return    self
	 */
	public function fill( array $default_param ): self
	{
		$new_param = array_merge( $default_param, $this->param );

		return new self( $new_param );
	}

	/**
	 * @param    array<string,mixed>    $values
	 * @return    self
	 */
	public function inject( array $values ): self
	{
		$new_param = array_merge( $this->param, $values );

		return new self( $new_param );
	}

	/**
	 * @return    array<string,mixed>
	 */
	public function to_array(): array
	{
		return $this->param;
	}

	// ====================================================================== //

	/**
	 * @param    int|string    $offset
	 * @return    bool
	 */
	public function offsetExists( $offset ): bool
	{
		return isset( $this->param[ $offset ] );
	}

	/**
	 * @param    int|string    $offset
	 * @return    mixed
	 */
	public function offsetGet( $offset )
	{
		return isset( $this->param[ $offset ] )
			? $this->param[ $offset ]
			: null;
	}

	/**
	 * @param    string    $offset
	 * @param    mixed    $value
	 * @return    void
	 */
	public function offsetSet( $offset, $value ): void
	{
		if ( is_null( $offset ) || is_int( $offset ) ) {
			throw new \LogicException( 'Param に値を設定するとき、キーは文字列でなければいけません: $offset: ' . $offset );
		}

		$this->preset[ $offset ] = $value;
	}

	/**
	 * @param    int|string    $offset
	 * @return    void
	 */
	public function offsetUnset( $offset ): void
	{
		unset( $this->param[ $offset ] );
	}

	// ====================================================================== //

	/**
	 * @return    Traversable
	 */
	public function getIterator(): \Traversable
	{
		return new \ArrayIterator( $this->param );
	}
}
