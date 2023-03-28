<?php

namespace QMS4\Item\Term;

use QMS4\Param\Param;
use QMS4\Item\Util\Compute;
use QMS4\Item\Term\Term;


class Terms implements \Countable, \ArrayAccess, \IteratorAggregate
{
	/** @var    Term[] */
	private $_terms;

	/** @var    Term[] */
	private $_filtered_terms;

	/** @var    int|null */
	private $_term_count;

	/** @var    int|string|null */
	private $_parent;

	/** @var    string|null */
	private $_term_html;

	/**
	 * @param    Term[]    $terms
	 * @param    int|null    $term_count
	 * @param    int|string|null    $parent
	 * @param    string|null    $term_html
	 */
	public function __construct(
		array $terms,
		?int $term_count = null,
		$parent = null,
		?string $term_html = null
	)
	{
		$this->_terms = $terms;
		$this->_term_count = $term_count;
		$this->_parent = $parent;
		$this->_term_html = $term_html;


		$filtered_terms = array();

		if ( is_null( $parent ) ) {
			$filtered_terms = $terms;
		} elseif ( is_numeric( $parent ) ) {
			foreach ( $terms as $term ) {
				if ( $term->parent_id == $parent ) {
					$filtered_terms[] = $term;
				}
			}
		} else {
			foreach ( $terms as $term ) {
				if ( $term->parent && $term->parent->slug == $parent ) {
					$filtered_terms[] = $term;
				}
			}
		}

		$this->_filtered_terms = $filtered_terms;
	}

	/**
	 * @return    string
	 */
	public function __toString(): string
	{

		$terms = $this->_term_count >= 0
			? array_slice( $this->_filtered_terms, 0, $this->_term_count )
			: $this->_filtered_terms;

		$htmls = array();
		foreach ( $terms as $term ) {
			$term = $term->___inject( array(
				'term_html' => $this->_term_html,
			) );

			$htmls[] = (string) $term;
		}

		return join( '', $htmls );
	}

	/**
	 * @return    array<string, mixed>
	 */
	public function __debugInfo(): array
	{
		$terms = $this->_term_count >= 0
			? array_slice( $this->_filtered_terms, 0, $this->_term_count )
			: $this->_filtered_terms;


		return array(
			"\0" . self::class . "\0_terms" => $terms,
		);
	}

	// ====================================================================== //

	/**
	 * @return    int
	 */
	public function count(): int
	{
		return $this->_term_count >= 0
			? min( count( $this->_filtered_terms ), $this->_term_count )
			: count( $this->_filtered_terms );
	}

	// ====================================================================== //

	/**
	 * @param    int|string    $offset
	 * @return    bool
	 */
	public function offsetExists( $offset ): bool
	{
		if ( $this->_term_count >= 0 ) {
			$length = min( count( $this->_filtered_terms ), $this->_term_count );
			return $offset < $length;
		} else {
			return $offset < count( $this->_filtered_terms );
		}
	}

	/**
	 * @param    int|string    $offset
	 * @return    Term|null
	 */
	public function offsetGet( $offset ): ?Term
	{
		if ( $this->_term_count >= 0 ) {
			$length = min( count( $this->_filtered_terms ), $this->_term_count );

			return $offset < $length
				? $this->_filtered_terms[ $offset ]
				: null;
		} else {
			return isset( $this->_filtered_terms[ $offset ] )
				? $this->_filtered_terms[ $offset ]
				: null;
		}
	}

	/**
	 * @param    int|string    $offset
	 * @param    mixed    $value
	 * @return    void
	 */
	public function offsetSet( $offset, $value ): void
	{
		throw new \RuntimeException();
	}

	/**
	 * @param    int|string    $offset
	 * @return    void
	 */
	public function offsetUnset( $offset ): void
	{
		throw new \RuntimeException();
	}

	// ====================================================================== //

	/**
	 * @return    \Traversable<Term>
	 */
	public function getIterator(): \Traversable
	{
		$terms = $this->_term_count >= 0
			? array_slice( $this->_filtered_terms, 0, $this->_term_count )
			: $this->_filtered_terms;

		return new \ArrayIterator( $terms );
	}

	// ====================================================================== //

	/**
	 * @param    array<string,mixed>    $args
	 * @return    self
	 */
	public function invoke( array $args )
	{
		$compute = new Compute( $this, '___compute' );

		$param = new Param( array(
			'term_count' => $this->_term_count,
			'parent' => $this->_parent,
			'term_html' => $this->_term_html,
		) );

		return $compute->bind( $param )->invoke( $this->_terms, $args );
	}

	/**
	 * @param    Term[]    $terms
	 * @param    int    $term_count
	 * @param    int|string|null    $parent
	 * @param    string    $term_html
	 * @return    self
	 */
	private function ___compute(
		array $terms,
		int $term_count = -1,
		$parent = null,
		string $term_html = '<li class="icon" style="background-color:[color]">[name]</li>'
	): self
	{
		$new_terms = array();
		foreach ( $terms as $term ) {
			$new_terms[] = $term->___inject( array(
				'term_count' => $term_count,
				'parent' => $parent,
				'term_html' => $term_html,
			) );
		}

		return new self( $new_terms, $term_count, $parent, $term_html );
	}

	// ====================================================================== //

	/**
	 * @param    string|callable    $field
	 * @return    mixed[]
	 */
	public function map( $field ): array
	{
		$values = array();
		foreach ( $this->_filtered_terms as $index => $term ) {
			$values[] = is_callable( $field )
				? $field( $term, $index, $this->_filtered_terms )
				: $term->$field;
		}

		return $values;
	}

	/**
	 * @param    string|callable    $field
	 * @param    string    $separator
	 * @return    string
	 */
	public function join( $field = 'slug' , string $separator = ',' ): string
	{
		return join( $separator, $this->map( $field ) );
	}
}
