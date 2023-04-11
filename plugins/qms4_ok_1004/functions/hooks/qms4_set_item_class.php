<?php

/**
 * qms4_list() 関数, qms4_detail() 関数の挙動をカスタマイズする関数
 *
 * qms4_list() 関数, qms4_detail() 関数はデフォルトでは
 * QMS4\Item\Post\Post クラスのインスタンスを返すが、これを変更できる
 *
 *
 * @example
 *     class CustomPost extends QMS4\Item\Post\Post
 *     {
 *         protected function _custom_prop()
 *         {
 *             return $this->_wp_post->custom;
 *         }
 *     }
 *
 *     qms4_set_item_class( CustomPost::class );
 *
 *
 *     $param = array();
 *     $list = qms4_list( $type, $param );
 *
 *     foreach ( $list as $item ) {
 *         echo $item->custom_prop . "\n";
 *     }
 *
 *
 *     $param = array();
 *     $item = qms4_detail( $post_id, $param );
 *
 *     echo $item->custom_prop . "\n";
 *
 *
 * @param    string    $class_name
 * @param    string|string[]    $post_types
 */
function qms4_set_item_class(
	string $class_name,
	$post_types = array()
): void
{
	if ( ! class_exists( $class_name ) ) {
		throw new \InvalidArgumentException( "指定されたクラスが見つかりませんでした。: \$class_name: {$class_name}" );
	}

	$post_types = is_array( $post_types ) ? $post_types : array( $post_types );

	add_filter(
		'qms4_list_item_class',
		function ( $item_class, $_post_types ) use ( $class_name, $post_types ) {
			if ( ! empty( $post_types ) ) {
				$intersect = array_intersect( $_post_types, $post_types );
				if ( empty( $intersect ) ) { return $item_class; }
			}

			return $class_name;
		},
		/* $priority     = */ 10,
		/* accepted_args = */ 2
	);

	add_filter(
		'qms4_detail_item_class',
		function ( $item_class, $post_type ) use ( $class_name, $post_types ) {
			if ( ! empty( $post_types ) ) {
				$enable = in_array( $post_type, $post_types, /* $strict = */ true );
				if ( ! $enable ) { return $item_class; }
			}

			return $class_name;
		},
		/* $priority     = */ 10,
		/* accepted_args = */ 2
	);
}
