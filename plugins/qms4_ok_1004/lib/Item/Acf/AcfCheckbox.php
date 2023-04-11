<?php

namespace QMS4\Item\Acf;

use QMS4\Item\Acf\AcfInterface;
use QMS4\Item\Util\Compute;
use QMS4\Param\Param;


class AcfCheckbox implements AcfInterface
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
		string $return_format = 'label'
	)
	{
		$labels = $field_object[ 'value' ] ?: array();
		$choices = $field_object[ 'choices' ];

		if ( $return_format === 'both' ) {
			$items = array();
			foreach ( $labels as $label ) {
				if ( ! isset( $choices[ $label ] ) ) { continue; }
				$items[ $label ] = $choices[ $label ];
			}

			return $items;
		} else if ( $return_format === 'value' ) {
			return $labels;
		} else {
			$items = array();
			foreach ( $labels as $label ) {
				if ( ! isset( $choices[ $label ] ) ) { continue; }
				$items[] = $choices[ $label ];
			}

			return $items;
		}
	}
}
