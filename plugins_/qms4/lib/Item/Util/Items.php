<?php

namespace QMS4\Item\Util;

use QMS4\Item\Acf\AcfInterface;
use QMS4\Item\Post\Post;
use QMS4\Item\Term\Term;
use QMS4\Item\Util\Compute;
use QMS4\Param\Param;


class Items implements \Countable, \ArrayAccess, \IteratorAggregate
{
	/** @var    array */
	private $_items;

	/** @var    Param */
	private $_param;

	/**
	 * @param    array    $items
	 * @param    Param    $param
	 */
	public function __construct( array $items, Param $param )
	{
		$this->_items = $items;
		$this->_param = $param;
	}

	/**
	 * @param    string    $name
	 * @return    mixed
	 */
	public function __get( string $name )
	{
		return $this->___proxy( $name, array() );
	}

	/**
	 * @param    string    $name
	 * @param    array    $args
	 * @return    mixed
	 */
	public function __call( string $name, array $args )
	{
		return $this->___proxy( $name, $args );
	}

	/**
	 * @param    int|string    $name
	 * @param    array    $args
	 * @return    mixed
	 */
	private function ___proxy( $name, array $args )
	{
		if ( ! array_key_exists( $name, $this->_items ) ) {
			throw new \RuntimeException( '"{$name}" は未定義のキーです' );
		}

		$value = $this->_items[ $name ];

		if ( is_array( $value ) ) {
			$compute = new Compute( $this, '__array' );
			return $compute->bind( $this->_param )->invoke( $value, $args );
		} elseif ( is_numeric( $value ) ) {
			$compute = new Compute( $this, '__numeric' );
			return $compute->bind( $this->_param )->invoke( $value, $args );
		} elseif ( is_string( $value ) ) {
			$compute = new Compute( $this, '__string' );
			return $compute->bind( $this->_param )->invoke( $value, $args );
		} elseif ( $value instanceof \WP_Post ) {
			return new Post( $value, $this->_param );
		} elseif ( $value instanceof \WP_Term ) {
			return new Term( $value, $this->_param );
		} elseif ( $value instanceof AcfInterface ) {
			return $value->invoke( $args );
		}

		return $value;
	}

	// ====================================================================== //

	/**
	 * @return    int
	 */
	public function count(): int
	{
		$items = array();
		foreach ( $this->_items as $name => $_ ) {
			$items[ $name ] = $this->___proxy( $name, array() );
		}

		$items = array_filter( $items );

		return count( $items );
	}

	/**
	 * @param    int|string    $offset
	 * @return    bool
	 */
	public function offsetExists( $offset ): bool
	{
		return array_key_exists( $offset, $this->_items );
	}

	/**
	 * @param    int|string    $offset
	 * @return    mixed
	 */
	public function offsetGet( $offset )
	{
		return $this->___proxy( $offset, array() );
	}

	/**
	 * @param    int|string    $offset
	 * @param    mixed    $value
	 * @return    void
	 */
	public function offsetSet( $offset, $value ): void
	{
		throw new \RuntimeException( '値は読み取り専用です。' );
	}

	/**
	 * @param    int|string    $offset
	 * @return    void
	 */
	public function offsetUnset( $offset ):void
	{
		throw new \RuntimeException( '値は読み取り専用です。' );
	}

	/**
	 * @return    \Traversable
	 */
	public function getIterator (): \Traversable
	{
		$items = array();
		foreach ( $this->_items as $name => $_ ) {
			$items[ $name ] = $this->___proxy( $name, array() );
		}

		return new \ArrayIterator( $items );
	}

	// ====================================================================== //

	/**
	 * @param    array    $value
	 * @param    int    $item_count
	 * @param    string|false|null    $join
	 * @return    self|string
	 */
	protected function __array(
		array $value,
		int $item_count = -1,
		?string $join = null
	)
	{
		if ( $item_count >= 0 ) {
			$value = array_slice( $value, 0, $item_count );
		}

		return is_null( $join ) || $join === false
			? new self( $value, $this->_param )
			: join( $join, $value );
	}

	/**
	 * @param    mixed    $value
	 * @param    bool|null    $number_format
	 * @param    int|null    $round
	 * @return    string
	 */
	protected function __numeric(
		$value,
		?bool $number_format = false,
		?int $round = null
	): string
	{
		if ( ! is_null( $round ) ) {
			$value = round( $value, $round );
		}

		if ( ! is_null( $number_format ) && $number_format ) {
			$value = number_format( $value );
		}

		return (string) $value;
	}

	/**
	 * @param    string    $value
	 * @param    int    $length
	 * @param    bool    $slash_to_br
	 * @return    string
	 */
	protected function __string(
		string $value,
		int $length = -1,
		bool $slash_to_br = true
	)
	{
		if ( $length >= 0 ) {
			$value = mb_strimwidth( $value, 0, $length * 2 );
		}

		if ($slash_to_br) {
			// 2連続のスラッシュ '//' を <br> タグに置換する
			// ただし、 'http://' や 'https://' に含まれる '//' は置換しない (否定後読み)
			$value = preg_replace( '%(?<!http:|https:)//%', '<br>', $value );
		}

		return $value;
	}

	// ====================================================================== //

	/**
	 * @return    array
	 */
	public function to_array(): array
	{
		return $this->_items;
	}
}
