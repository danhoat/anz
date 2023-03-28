<?php

namespace QMS4\Item\Acf;

use QMS4\Item\Acf\AcfInterface;
use QMS4\Item\Term\Term;
use QMS4\Item\Term\Terms;
use QMS4\Item\Util\Compute;
use QMS4\Param\Param;


class AcfTaxonomy implements AcfInterface
{
	/** @var    array<string,mixed> */
	private $_field_object;

	/** @var    Param */
	private $_param;

	/**
	 * @param    array<string,mixed>    $field_object
	 * @param    Param    $param
	 */
	public function __construct( array $field_object, Param $param )
	{
		$this->_field_object = $field_object;
		$this->_param = $param;
	}

	/**
	 * @param    array<string|mixed>    $args
	 */
	public function invoke( array $args )
	{
		$compute = new Compute( $this, '__compute' );

		return $compute->bind( $this->_param )->invoke( $this->_field_object, $args );
	}

	/**
	 * @param    array<string,mixed>    $field_object
	 * @param    int    $length
	 * @param    bool    $slash_to_br
	 * @return    Terms
	 */
	protected function __compute(
		array $field_object,
		int $term_count = -1,
		string $term_html = '<li class="icon" style="background-color:[color]">[name]</li>'
	): Terms
	{
		$value = $field_object[ 'value' ] ?: array();
		$taxonomy = $field_object[ 'taxonomy' ];

		$terms = array();
		foreach ( $value as $term_id ) {
			$wp_term = get_term( $term_id, $taxonomy );

			if ( is_null( $wp_term ) || is_wp_error( $wp_term ) ) { continue; }

			$terms[] = new Term( $wp_term, $this->_param );
		}

		return new Terms( $terms, $term_count, $term_html );
	}
}
