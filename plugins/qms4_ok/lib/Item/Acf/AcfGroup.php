<?php

namespace QMS4\Item\Acf;

use QMS4\Item\Acf\AcfInterface;
use QMS4\Item\Acf\AcfFactory;
use QMS4\Item\Util\Compute;
use QMS4\Item\Util\Items;
use QMS4\Param\Param;


class AcfGroup implements AcfInterface
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

		$factory = new AcfFactory();

		$items = array();
		foreach ( $field_object[ 'sub_fields' ] as $sub_field_object ) {
			$sub_name = $sub_field_object[ 'name' ];
			$key = $sub_field_object[ 'key' ];

			$sub_value = $value[ $key ];
			$sub_field_object[ 'value' ] = $sub_value;

			$items[ $sub_name ] = $factory->from_field_object(
				$sub_field_object,
				$this->_param
			);
		}

		return new Items( $items, $this->_param );
	}
}
