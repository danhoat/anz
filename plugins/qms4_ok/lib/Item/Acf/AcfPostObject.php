<?php

namespace QMS4\Item\Acf;

use QMS4\Item\Acf\AcfInterface;
use QMS4\Item\Post\Post;
use QMS4\Item\Util\Compute;
use QMS4\Param\Param;


class AcfPostObject implements AcfInterface
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
	 * @param    string|string[]    $post_status
	 * @return    Post[]|Post|null
	 */
	protected function __compute(
		array $field_object,
		$post_status = 'publish'
	)
	{
		$value = $field_object[ 'value' ];
		$post_status = is_array( $post_status ) ? $post_status : array( $post_status );


		if ( empty( $field_object[ 'multiple' ] ) ) {
			// カスタムフィールドの「複数の値を選択できるか？」オプションが false のとき

			if (
				empty( $value )
				|| ! ( $wp_post = get_post( $value ) )
				|| ! in_array( $wp_post->post_status, $post_status, /* $strict = */ true )
			) { return null; }

			$item_class = Post::class;
			$item_class = apply_filters(
				'qms4_detail_item_class',
				$item_class,
				$wp_post->post_type
			);

			return new $item_class( $wp_post, $this->_param );
		} else {
			// カスタムフィールドの「複数の値を選択できるか？」オプションが true のとき

			$items = array();
			foreach ( $value ?: array() as $post_id ) {
				$wp_post = get_post( $post_id );

				if (
					! $wp_post
					|| ! in_array( $wp_post->post_status, $post_status, /* $strict = */ true )
				) { continue; }

				$item_class = Post::class;
				$item_class = apply_filters(
					'qms4_detail_item_class',
					$item_class,
					$wp_post->post_type
				);

				$items[] = new $item_class( $wp_post, $this->_param );
			}

			return $items;
		}
	}
}
