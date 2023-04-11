<?php

namespace QMS4\Item\Acf;

use QMS4\Item\Acf\AcfInterface;
use QMS4\Item\Util\Compute;
use QMS4\Param\Param;


class AcfTextarea implements AcfInterface
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
	 * @param    bool    $nl2br
	 * @return    string
	 */
	protected function __compute(
		array $field_object,
		int $length = -1,
		bool $nl2br = true
	): string
	{
		$value = $field_object[ 'value' ];

		if ( $length >= 0 ) {
			$value = mb_strimwidth( $value, 0, $length * 2 );
		}

		if ( $nl2br ) {
			$value = nl2br( $value, /* $use_xhtml = */ false );
		}

		return $value;
	}
}
