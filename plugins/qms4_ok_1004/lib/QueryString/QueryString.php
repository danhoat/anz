<?php

namespace QMS4\QueryString;

use QMS4\QueryString\QueryCond;


class QueryString
{
	/** @var    array<string,QueryCond> */
	private $query_conds;

	/** @var    array<string,mixed> */
	private $keep;

	/**
	 * @param    array<string,QueryCond>    $query_conds
	 * @param    array<string,mixed>    $keep
	 */
	public function __construct( array $query_conds, array $keep )
	{
		$this->query_conds = $query_conds;
		$this->keep = $keep;
	}

	/**
	 * @return    string
	 */
	public function __toString(): string
	{
		$query_conds = array();
		foreach ( $this->query_conds as $key => $cond ) {
			$query_conds[ $key ] = (string) $cond;
		}

		$query_conds = array_filter( $query_conds );

		$query_parts = array_merge( $query_conds, $this->keep );

		ksort( $query_parts );

		return http_build_query( $query_parts );
	}

	/**
	 * @param    array<string,mixed>    $global_get
	 * @param    string    ...$keys
	 * @return    self
	 */
	public static function from_global_get( array $global_get, string ...$keys ): self
	{
		$query_conds = array();
		foreach ( $keys as $key ) {
			if ( isset( $global_get[ $key ] ) ) {
				$query_conds[ $key ] = QueryCond::from_string( $global_get[ $key ] );
				unset( $global_get[ $key ] );
			} else {
				$query_conds[ $key ] = new QueryCond( array(), array() );
			}
		}

		return new self( $query_conds, $global_get );
	}

	// ====================================================================== //

	/**
	 * @param    string    $key
	 * @param    string    $cond
	 * @return    bool
	 */
	public function has( string $key, string $cond ): bool
	{
		if ( ! isset( $this->query_conds[ $key ] ) ) {
			throw new \InvalidArgumentException( "\$key: {$key}" );
		}

		return $this->query_conds[ $key ]->has( $cond );
	}

	/**
	 * @param    string    $key
	 * @return    QueryCond
	 */
	public function get( string $key ): QueryCond
	{
		if ( ! isset( $this->query_conds[ $key ] ) ) {
			throw new \InvalidArgumentException( "\$key: {$key}" );
		}

		return $this->query_conds[ $key ];
	}

	/**
	 * @param    string    $key
	 * @param    string    $cond
	 * @return    self
	 */
	public function add( string $key, string $cond ): self
	{
		if ( ! isset( $this->query_conds[ $key ] ) ) {
			throw new \InvalidArgumentException();
		}

		$query_conds = $this->query_conds;
		$query_conds[ $key ] = $query_conds[ $key ]->add( $cond );

		return new self( $query_conds, $this->keep );
	}

	public function remove( string $key, string $cond )
	{
		if ( ! isset( $this->query_conds[ $key ] ) ) {
			throw new \InvalidArgumentException();
		}

		$query_conds = $this->query_conds;
		$query_conds[ $key ] = $query_conds[ $key ]->remove( $cond );

		return new self( $query_conds, $this->keep );
	}
}
