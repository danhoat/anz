<?php

/**
 *  replace new function arkhe_the__postdate in the archive list page and single custom post_type.
 *
 */
function farbic_the__postdate( $post_date, $type  ='post', $date_format = '' )
{

	$type_class  = "-{$type}";
	if( is_int($post_date) )
		$post_date =  wp_date( $date_format, $post_date ) ;


	$return = '<time class="c-postTimes__item u-flex--aic ' . esc_attr( $type_class ) . '" >' .
		Arkhe::get_svg( $type, array( 'class' => 'c-postMetas__icon' ) ) .
		esc_html($post_date ) .
	'</time>';


	// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	echo apply_filters( 'farbic_the__postdate', $return, $type );
}

