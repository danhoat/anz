<?php

namespace QMS4\QueryString;


class QueryCond
{
	/** @var    atring[] */
	private $in;

	/** @var    atring[] */
	private $not_in;

	/**
	 * @param    string[]    $in
	 * @param    string[]    $not_in
	 */
	public function __construct( array $in, array $not_in )
	{
		$this->in = $in;
		$this->not_in = $not_in;
	}

	/**
	 * @return    string
	 */
	public function __toString(): string
	{
		$cond_strs = array_merge(
			$this->in,
			array_map( function ( $cond ) { return "-{$cond}"; }, $this->not_in )
		);

		return join( ',', $cond_strs );
	}

	/**
	 * @param    string    $str
	 * @return    self
	 */
	public static function from_string( string $str ): self
	{
		$conds = explode( ',', $str );
		$conds = array_filter( array_map( 'trim', $conds ) );

		$in = array();
		$not_in = array();
		foreach ( $conds as $cond ) {
			if ( strpos( $cond, '-' ) === 0 ) {
				$not_in[] = substr( $cond, 1 );
			} else {
				$in[] = $cond;
			}
		}

		$in = array_unique( $in );
		sort( $in );

		$not_in = array_unique( $not_in );
		sort( $not_in );

		return new self( $in, $not_in );
	}

	// ====================================================================== //

	/**
	 * @return    string[]
	 */
	public function in(): array
	{
		return $this->in;
	}

	/**
	 * @return    string[]
	 */
	public function not_in(): array
	{
		return $this->not_in;
	}

	// ====================================================================== //

	/**
	 * @param    string    $cond
	 * @return    bool
	 */
	public function has( string $cond ): bool
	{
		return in_array( $cond, $this->in, /* $strict = */ true );
	}

	/**
	 * @param    string    $cond
	 * @return    self
	 */
	public function add( string $cond ): self
	{
		$in = $this->in;
		$not_in = $this->not_in;

		if ( in_array( $cond, $not_in, /* $strict = */ true ) ) {
			$not_in = array_filter(
				$not_in,
				function ( $_cond ) use ( $cond ) { return $cond != $_cond; }
			);
		}

		if ( ! in_array( $cond, $in, /* $strict = */ true ) ) {
			$in[] = $cond;
			sort( $in );
		}

		return new self( $in, $not_in );
	}

	/**
	 * @param    string    $cond
	 * @return    self
	 */
	public function remove( string $cond ): self
	{
		$in = $this->in;
		$not_in = $this->not_in;

		if ( in_array( $cond, $in, /* $strict = */ true ) ) {
			$in = array_filter(
				$in,
				function ( $_cond ) use ( $cond ) { return $cond != $_cond; }
			);
		}

		return new self( $in, $not_in );
	}
}
