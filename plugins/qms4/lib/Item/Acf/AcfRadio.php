<?php

namespace QMS4\Item\Acf;

use QMS4\Item\Acf\AcfInterface;
use QMS4\Item\Util\Compute;
use QMS4\Param\Param;


class AcfRadio implements AcfInterface
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
	 * @param    string    $return_format
	 * @param    string    $default
	 * @return    string|array<string,string>
	 */
	protected function __compute(
		array $field_object,
		string $return_format = 'label',
		string $default = ''
	)
	{
		$label = $field_object[ 'value' ];
		$choices = $field_object[ 'choices' ];

		if ( empty( $label ) ) { return $default; }

		if ( $return_format === 'both' ) {
			$value = isset( $choices[ $label ] ) ? $choices[ $label ] : $default;
			return array( $label => $value );
		} elseif ( $return_format === 'value' ) {
			return $label;
		} else {
			return isset( $choices[ $label ] ) ? $choices[ $label ] : $default;
		}
	}
}
