<?php

use QMS4\PostTypeMeta\PostTypeMeta;
use QMS4\Taxonomy\Taxonomy;

/**
 * @param    string|string[]    $post_id
 * @return    Items
 */

function qms4_get_color( $post_id)
{

	$terms 	= get_the_terms($post_id, 'fair__special' );
	if( !$terms  || is_wp_error($terms) ){
		return 0;
	}

	$term 	= $terms[0];

	$color = get_field( 'field_62fb7354562ba', $term->taxonomy . '_' . $term->term_id );
	if( empty($color) ) return 0;

   return (object) array('slug'=>$term->term_id,'color' => $color);
}

function debug_fc(){
	$event_id = 220;
	$term 	= get_the_terms($event_id, 'fair__special' );
	$term = qms4_get_color($event_id);
}
// add_action('wp_footer','debug_fc');