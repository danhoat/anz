<?php

namespace QMS4\Item\PostMeta;

use QMS4\Item\PostMeta\PostMeta;


class PostMetaFactory
{
	/**
	 * @param    \WP_Post|\WP_Term|\WP_User|int    $object
	 * @param    string    $name
	 * @return    PostMeta
	 */
	public function from_name( $object, string $name )
	{
		$value = $this->get_value( $object, $name );

		return new PostMeta( $value );
	}

	/**
	 * @param    \WP_Post|\WP_Term|\WP_User|int    $object
	 * @param    string    $name
	 * @return    mixed
	 */
	private function get_value( $object, string $name )
	{
		if ( $object instanceof \WP_Post ) {
			return get_metadata( $object->post_type, $object->ID, $name, /* $single = */ true );
		}

		if ( $object instanceof \WP_Term ) {
			return get_metadata( 'term', $object->term_id, $name, /* $single = */ true );
		}

		if ( $object instanceof \WP_User ) {
			return get_metadata( 'user', $object->ID, $name, /* $single = */ true );
		}

		return get_post_meta( $object, $name, /* $single = */ true );
	}
}
