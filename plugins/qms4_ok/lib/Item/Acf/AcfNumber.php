<?php

namespace QMS4\Item\Acf;

use QMS4\Item\Acf\AcfInterface;
use QMS4\Item\Util\Compute;
use QMS4\Param\Param;


class AcfNumber implements AcfInterface
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
	 * @param    bool    $number_format    数値に3桁毎のカンマを入れて表示するかどうか
	 * @param    int|null    $round    指定された位(くらい)で四捨五入した結果を返す。$round = null ならば四捨五入は行わない
	 * @return    string|null
	 */
	protected function __compute(
		array $field_object,
		bool $number_format = false,
		?int $round = null
	): ?string
	{
		$value = $field_object[ 'value' ];

		if ( ! is_numeric( $value ) && empty( $value ) ) { return null; }

		if ( ! is_null( $round ) ) {
			$value = round( $value, $round );
		}

		if ( ! is_null( $number_format ) && $number_format ) {
			$value = number_format( $value );
		}

		return $value;
	}
}
