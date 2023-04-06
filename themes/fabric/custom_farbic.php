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


function farbic_theme_enqueue_styles() {
    wp_enqueue_style( 'farbic-style',
        get_stylesheet_directory_uri() . '/custom-farbic.css',
        array( 'arkhe-main-style' ),
        rand()
    );
    $css_path = ARKHE_THEME_URI . '/dist/css';

	if ( is_front_page() ) {
		// カスタマイザー
		wp_enqueue_style( 'arkhe-icon', $css_path . '/icon.css', array(), rand() );

	}


}
add_action( 'wp_enqueue_scripts', 'farbic_theme_enqueue_styles' );