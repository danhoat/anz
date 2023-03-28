<?php

namespace QMS4\Item\Acf;

use QMS4\Item\Acf\AcfInterface;
use QMS4\Item\Util\Compute;
use QMS4\Param\Param;


class AcfWysiwyg implements AcfInterface
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
	 * @return    string
	 */
	protected function __compute( array $field_object ): string
	{
		$content = $field_object[ 'value' ];

		remove_filter( 'the_content', 'wpautop' );

		$content = do_shortcode( $content );
		$content = apply_filters( 'the_content', $content );
		$content = str_replace( ']]>', ']]&gt;', $content );

		add_filter( 'the_content', 'wpautop' );

		return $content;
	}
}
