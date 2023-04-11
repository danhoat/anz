<?php

namespace QMS4\Item\Acf;

use QMS4\Item\Acf\AcfInterface;
use QMS4\Item\Acf\AcfFactory;
use QMS4\Item\Util\Compute;
use QMS4\Item\Util\Items;
use QMS4\Item\Post\Image;
use QMS4\Param\Param;


class AcfRepeater implements AcfInterface
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
	 * @param    int    $item_count
	 * @return    Image[]
	 */
	protected function __compute(
		array $field_object,
		int $item_count = -1
	): array
	{
		$values = $field_object[ 'value' ] ?: array();
		$sub_fields = $field_object[ 'sub_fields' ];

		$factory = new AcfFactory();

		$items = array();
		foreach ( $values as $sub_values ) {
			$sub_item = array();
			foreach ( $sub_fields as $sub_field_object ) {
				$sub_name = $sub_field_object[ 'name' ];

				$key = $sub_field_object[ 'key' ];
				$sub_value = $sub_values[ $key ];
				$sub_field_object[ 'value' ] = $sub_value;

				$sub_item[ $sub_name ] = $factory->from_field_object(
					$sub_field_object,
					$this->_param
				);
			}

			$items[] = new Items( $sub_item, $this->_param );

			if ( $item_count > 0 && count( $items ) >= $item_count ) {
				break;
			}
		}

		return $items;
	}
}
