<?php

namespace QMS4\Collection;

use QMS4\Item\CallableInterface;
use QMS4\Item\Term;
use QMS4\Param\Param;
use Traversable;

class Terms implements CallableInterface, \IteratorAggregate
{
	/** @var    Term[] */
	private $terms;

	/** @var    Param */
	private $param;

	/**
	 * @param    Term[]    $terms
	 */
	public function __construct( array $terms, Param $param )
	{
		$this->terms = $terms;
		$this->param = $param;
	}

	/**
	 * @return    string
	 */
	public function __toString(): string
	{
		$template = $this->param[ 'term_html' ];

		$htmls = array();
		foreach ( $this->terms as $term ) {
			$map = array(
				'id' => $term->term_id,
				'name' => $term->name,
				'slug' => urldecode( $term->slug ),
				'count' => $term->count,
				// 'color' =>
			);

			$htmls[] = str_replace(
				array_keys( $map ),
				$map,
				$template
			);
		}

		return join( '', $htmls );
	}

	// ====================================================================== //

	/**
	 * @return    Traversable
	 */
	public function getIterator(): Traversable
	{
		return new \ArrayIterator( $this->terms );
	}

	// ====================================================================== //

	/**
	 * @param    array    $args
	 * @return    self
	 */
	public function invoke( array $args ): self
	{
		$args = $this->args( $args );

		$terms = $args[ 'term_count' ] >= 0
			? array_slice( $this->terms, 0, $args[ 'term_count' ] )
			: $this->terms;

		$param = $this->param->assign( array(
			'term_html' => $args[ 'term_html' ],
		) );

		return new self( $terms, $param );
	}

	/**
	 * @param    array    $args
	 * @return    array<string,mixed>
	 */
	private function args( array $args ): array
	{
		$default_param = array(
			'term_count' => -1,
			'term_html' => '<li class="icon" style="background-color:[color]">[name]</li>',
		);

		$new_args = array();
		$index = 0;
		foreach ( $default_param as $key => $default_value ) {
			if ( isset( $args[ $key ] ) ) {
				$new_args[ $key ] = $args[ $key ];
			} else if ( isset( $args[ $index ] ) ) {
				$new_args[ $key ] = $args[ $index ];
			} else {
				$new_args[ $key ] = $default_value;
			}

			$index++;
		}

		return $new_args;
	}
}
