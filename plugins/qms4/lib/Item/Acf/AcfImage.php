<?php

namespace QMS4\Item\Acf;

use QMS4\Item\Acf\AcfInterface;
use QMS4\Item\Util\Compute;
use QMS4\Item\Post\Image;
use QMS4\Item\Post\NoImage;
use QMS4\Param\Param;


class AcfImage implements AcfInterface
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

	protected function __compute( array $field_object )
	{
		$value = $field_object[ 'value' ];

		return empty( $value ) || ! ( $wp_post = get_post( $value ) )
			? new NoImage( $this->_param )
			: new Image( $wp_post, $this->_param );
	}
}
