<?php

use QMS4\PostTypeMeta\PostTypeMeta;
use QMS4\Taxonomy\Taxonomy;

/**
 * @param    string|string[]    $post_id
 * @return    Items
 */

function qms4_get_color( $post_id )
{

	$terms 	= get_the_terms($post_id, 'event__category' );
	if( !$terms  || is_wp_error($terms) ){
		return '';
	}
	$term = $terms[0];
	$color = get_field( 'field_62fb7354562ba', $term->taxonomy . '_' . $term->term_id );

    return (object) array('slug'=>$term->term_id,'color' => $color); //can not use slug because it auto generate slug to special char
}