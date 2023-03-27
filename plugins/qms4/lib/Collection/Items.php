<?php

namespace QMS4\Collection;

use Traversable;

class Items implements \Countable, \IteratorAggregate
{
	/** @var    array */
	private $items;

	/**
	 * @param    array    $items
	 */
	public function __construct( array $items )
	{
		$this->items = $items;
	}

	/**
	 * @return    int
	 */
	public function count(): int
	{
		return count( $this->items );
	}

	/**
	 * @return    Traversable
	 */
	public function getIterator(): Traversable
	{
		return new \ArrayIterator( $this->items );
	}
}
