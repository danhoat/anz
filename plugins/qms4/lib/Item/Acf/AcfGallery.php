<?php

namespace QMS4\Item\Acf;

use QMS4\Item\Acf\AcfInterface;
use QMS4\Item\Util\Compute;
use QMS4\Item\Post\Image;
use QMS4\Param\Param;


class AcfGallery implements AcfInterface
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
	 * @param    int    $gallery_count
	 * @return    Image[]
	 */
	protected function __compute(
		array $field_object,
		int $gallery_count = -1
	): array
	{
		$value = $field_object[ 'value' ] ?: array();

		$items = array();
		foreach ( $value as $attachment ) {
			// NOTE: 画像が削除済みの場合、 get_post( $attachment_id ) === null となる
			if ( ! ( $wp_post = get_post( $attachment ) ) ) { continue; }

			$items[] = new Image( $wp_post, $this->_param );

			// $gallery_count が指定されている場合には
			// $items の要素数を調べて、要素数が $gallery_count に足りていればループを抜ける
			if ( $gallery_count > 0 && count( $items ) >= $gallery_count ) {
				break;
			}
		}

		return $items;
	}
}
