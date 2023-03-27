<?php

namespace QMS4\Item\Acf;

use QMS4\Item\Acf\AcfInterface;
use QMS4\Item\Post\Post;
use QMS4\Item\Util\Compute;
use QMS4\Param\Param;


class AcfRelationship implements AcfInterface
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
	 * @param    int    $rel_count
	 * @param    string|string[]    $post_status
	 * @return    Post[]
	 */
	protected function __compute(
		array $field_object,
		int $rel_count = -1,
		$post_status = 'publish'
	): array
	{
		$value = $field_object[ 'value' ] ?: array();
		$post_status = is_array( $post_status ) ? $post_status : array( $post_status );

		$items = array();
		foreach ( $value as $post_id ) {
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

			if ( $rel_count > 0 && count( $items ) >= $rel_count ) { break; }
		}

		return $items;
	}
}
