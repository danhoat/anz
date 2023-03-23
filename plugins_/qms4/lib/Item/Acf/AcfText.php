<?php

namespace QMS4\Item\Acf;

use QMS4\Item\Acf\AcfInterface;
use QMS4\Item\Util\Compute;
use QMS4\Param\Param;


class AcfText implements AcfInterface
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
	 * @param    bool    $slash_to_br
	 * @return    string
	 */
	protected function __compute(
		array $field_object,
		int $length = -1,
		bool $slash_to_br = true
	): string
	{
		$value = $field_object[ 'value' ];

		if ( $length >= 0 ) {
			$value = mb_strimwidth( $value, 0, $length * 2 );
		}

		if ( $slash_to_br ) {
			// 2連続のスラッシュ '//' を <br> タグに置換する
			// ただし、 'http://' や 'https://' に含まれる '//' は置換しない (否定後読み)
			$value = preg_replace( '%(?<!http:|https:)//%', '<br>', $value );
		}

		return $value;
	}
}
