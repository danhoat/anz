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


function farbic_list_categories_filter( $cur_url ){ ?>
	<div class="l-search">
		<div class="wp-block-columns is-layout-flex wp-container-10">
			<div class="wp-block-column is-layout-flow">
				<div class="widget qms4__term-list" data-taxonomy="fair__category" data-query-key="CAT">
				<ul class="widget__main-list">
					<?php
					$categories = get_terms( 'fair__category' );

					if ( $categories && ! is_wp_error( $categories ) ) {
						foreach ( $categories as $term ) { ?>
							<li class="active">
								<a href="<?= $cur_url ?>?CAT=<?=$term->slug?>">
									<span classname="widget__main-list__term-name"><?= $term->name ?></span>
								</a>
							</li>
							<?php
						}
					}
					?>

				</ul>
				<!-- /.widget__main-list -->
		</div>
		<!-- /.widget.qms4__term-list -->
		</div>
		</div>
	</div>

	<?php
}