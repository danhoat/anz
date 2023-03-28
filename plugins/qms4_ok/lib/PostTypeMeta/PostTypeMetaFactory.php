<?php

namespace QMS4\PostTypeMeta;

use QMS4\PostTypeMeta\DefaultPostTypeMeta;
use QMS4\PostTypeMeta\PostTypeMeta;
use QMS4\PostTypeMeta\PostTypeMetaInterface;


class PostTypeMetaFactory
{
	/**
	 * @param    int    $post_id
	 * @return    PostTypeMetaInterface
	 */
	public function from_post_id( $post_id ): PostTypeMetaInterface
	{
		$wp_post = get_post( $post_id );

		if ( $wp_post->post_type == 'qms4' ) {
			return new PostTypeMeta( $wp_post );
		} else {
			$wp_post_type = get_post_type_object( $wp_post->post_type );
			return new DefaultPostTypeMeta( $wp_post_type );
		}
	}

	/**
	 * @param    string[]    $name
	 * @return    PostTypeMetaInterface
	 */
	public function from_name( array $names ): PostTypeMetaInterface
	{
		global $wpdb;

		$placeholder = join( ', ', array_fill( 0, count( $names ), '%s' ) );

		$sql = "
			SELECT `ID`
			FROM {$wpdb->posts}
			WHERE
				1
				AND `post_type` = 'qms4'
				AND `post_status` = 'publish'
				AND `ID` IN (
					SELECT `post_id`
					FROM {$wpdb->postmeta}
					WHERE
						1
						AND `meta_key` = 'qms4__post_type__name'
						AND `meta_value` IN ( " . $placeholder . " )
				)
		";

		$stmt = $wpdb->prepare( $sql, ...$names );
		$post_id = $wpdb->get_var( $stmt );

		if ( $post_id ) {
			$wp_post = get_post( $post_id );
			return new PostTypeMeta( $wp_post );
		}

		$wp_post_type = get_post_type_object( $names[0] );

		if ( ! $wp_post_type ) {
			throw new \RuntimeException( 'PostTypeMeta が見つかりません。不明なスラッグです: $names: ' . join( '/', $names ) );
		}

		return new DefaultPostTypeMeta( $wp_post_type );
	}
}
