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
        get_stylesheet_directory_uri() . '/custom-fabric.css',
        array( 'arkhe-main-style' ),
        rand()
    );
}
add_action( 'wp_enqueue_scripts', 'farbic_theme_enqueue_styles' );


function farbic_list_categories_filter(){ ?>
	<div class="l-search">
		<div class="wp-block-columns is-layout-flex wp-container-10">
			<div class="wp-block-column is-layout-flow">
				<div class="widget qms4__term-list" data-taxonomy="fair__category" data-query-key="CAT">
				<ul class="widget__main-list">
					<?php
					global $post;
					$cur_url = get_permalink($post->ID);
					$categories = get_terms( array('taxonomy' => 'fair__category','hide_empty' => true) );
					$slugs 		= isset($_GET['CAT']) ? $_GET['CAT'] : '';
					if ( $categories && ! is_wp_error( $categories ) ) {
						$list = explode('|', $slugs);

						$href = array();
						foreach ( $categories as $term ) { ?>
							<?php
							$cat = '?CAT=';
							$active = '';
							$slug = urldecode( $term->slug );

							if( empty($slugs ) ){
								$href[$slug] = $term->slug;
							} else {
								if( ! in_array($slug,$list) ){

									$href[$slug] = $slugs.'|'.$term->slug;

								} else {

									$active = 'active';
									$new_list = $list;
									$pos = array_search($slug, $new_list);
									unset($new_list[$pos]);
									$href[$slug] = implode("|", $new_list);
									if(empty($new_list)) $cat = '';

								}
							}


							?>
							<li class="<?= $active?>">
								<a href="<?= $cur_url ?><?=$cat ?><?= $href[$slug];?>">
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
/**
 * Filter the excerpt length to 80 words on homepage and 50 words on page template
 *
 * @param int $length Excerpt length.
 * @return int modified excerpt length.
 */
function farbic_custom_excerpt_length( $length ) {
  if ( is_front_page() || is_home()  ) {
        return 56;
    } else {
        return $length;
    }
}

add_filter( 'excerpt_length', 'farbic_custom_excerpt_length', 9999 );
add_filter('excerpt_mblength','farbic_custom_excerpt_length', 9999);

add_filter('fair_execept_lenght','farbic_custom_excerpt_length', 9999);

function farbic_show_fair_icons( $fair_id ){

	$specials = get_the_terms( $fair_id, 'fair__special' );
	if ( $specials && ! is_wp_error( $specials ) ) {
		$html = '';
		foreach ( $specials as $term ) {
			$color = get_field( 'field_62fb7354562ba', $term->taxonomy . '_' . $term->term_id );
			$bg = 'style = "background-color: '.$color.'" ';
			$html .= '<li class="icon" '.$bg.'>'.$term->name.'</li>';
		}
		?>
		<ul class="p-postList__icon"><?= $html ?> </ul>
		<?php
	}
}
function farbic_show_categories($fair_id){
	$categories = get_the_terms( $fair_id, 'fair__category' );

	if ( $categories && ! is_wp_error( $categories ) ) {
		$html = '';
		foreach ( $categories as $term ) {
			$html .= '<li class="icon">'.$term->name.'</li>';
		}
		?>
		<ul class="p-postList__icon"><?= $html ?> </ul>
		<?php
	}
}
function farbic_list_fair_shortcode($atts){
	$posts_per_page = isset($atts['number_items']) ? (int) $atts['number_items'] : 15;
	$orderby = isset($atts['orderby']) ? trim($atts['orderby']) : '';

	$list_type 		= 'card';

	$args = array(
		'post_type' 		=> 'fair',
		'post_status' 		=> 'publish',
		'posts_per_page' 	=> $posts_per_page,
		);
	if(!empty($orderby)) $args['orderby'] = $orderby;

	$slugs = isset($_GET['CAT']) ? $_GET['CAT'] : '';
	if($slugs){
		$slugs = explode("|", $slugs);
		$args['tax_query'] =  array(
			array(
				'taxonomy' => 'fair__category',
				'field'    => 'slug',
				'terms'    => $slugs,
				'operator' => 'IN',
			)
		);
	}
	$query 		= new WP_Query($args);
	ob_start(); ?>
	<?php farbic_list_categories_filter() ?>
	<div class=" l-main__body p-archive page_fair_calendar">
		<ul class="p-postList -type-list">
			<?php if( $query->have_posts() ) { ?>

				<?php while($query->have_posts() ){ ?>

					<?php $query->the_post(); ?>
					<?php global $post;  $item = fabric_load_item(); ?>

					<li class="<?php echo esc_attr( trim( 'p-postList__item ' . $list_class ) ); ?>">
						<a href="<?php the_permalink();?>" class="p-postList__link">
							<?php
								Arkhe::get_part( 'post_list/item/thumb', array(
										'sizes' => 'card' === $list_type ? '(min-width: 600px) 400px, 100vw' : '(min-width: 600px) 400px, 40vw',
									) );
							?>
							<div class="p-postList__body">
								<h2 class="p-postList__title"> <?php the_title() ; ?> </h2>

								<div class="p-postList__excerpt"> <?php the_excerpt(); ?></div>

								<div class="c-postIcon">
									<?php farbic_show_fair_icons($item->ID);?>
								</div>
								<div class="c-postIcon p-postList__hash">
									<?php farbic_show_categories($item->ID) ?>
								</div>
							</div>
						</a>
					</li>
				<?php }?>
			<?php } ?>
			<?php wp_reset_query() ?>
		</ul>
	</div>
	<?php

 	return ob_get_clean();
	}

add_shortcode( 'block_fair_filter', 'farbic_list_fair_shortcode' );

add_filter( 'body_class', function( $classes ) {
	if( is_singular('fair') )
		return array_merge( $classes, array( 'single-event' ) );
	return $classes;
} );

