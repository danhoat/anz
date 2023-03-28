<?php

namespace QMS4\Item\Acf;

use QMS4\Item\Acf\AcfInterface;
use QMS4\Item\Util\Compute;
use QMS4\Item\Util\Date;
use QMS4\Item\Util\NoDate;
use QMS4\Param\Param;


class AcfDatePicker implements AcfInterface
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

	protected function __compute(
		array $field_object,
		?string $date_format = null
	)
	{
		$value = $field_object[ 'value' ];

		if ( is_null( $date_format ) ) {
			$date_format = isset( $this->_param[ 'date_format' ] )
				? $this->_param[ 'date_format' ]
				: 'Y/m/d';
		}

		return empty( $value )
			? new NoDate( wp_timezone(), $date_format )
			: new Date( $value, wp_timezone(), $date_format );
	}
}
