<?php

namespace QMS4\Item\Acf;

use QMS4\Item\Acf\AcfInterface;
use QMS4\Item\Util\Compute;
use QMS4\Item\Post\Attachment;
use QMS4\Param\Param;


class AcfFile implements AcfInterface
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
	 * @return    Attachment|null
	 */
	protected function __compute( array $field_object ): ?Attachment
	{
		$value = $field_object[ 'value' ];

		if ( empty( $value ) ) { return null; }

		$wp_post = get_post( $value );

		return new Attachment( $wp_post, $this->_param );
	}
}
