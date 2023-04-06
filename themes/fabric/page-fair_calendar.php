<?php
/**
 * Template Name: Fair_calendar
 */

get_header();?>
<main id="main_content" class="<?php Arkhe::main_class(); ?>">
<?php
while ( have_posts() ) :
	the_post();
	$the_id = get_the_ID();?>

	<article <?php post_class( Arkhe::get_main_body_class() ); ?> data-postid="<?php echo esc_attr( $the_id ); ?>">
		<?php
			the_content();
		?>
	</article>

<?php endwhile; ?>

<?php
$param['count'] = -1;
$h_tag         = isset( $args['h_tag'] ) ? $args['h_tag'] : 'h2';
$list_class    = true;
$show_date     = true;
$show_modified = false;
$show_cat      = true;
$show_author   = true;
$list_type     = ARKHE_LIST_TYPE;
$list_type 		= 'card';
$param = array();
$param['count'] = 6;
$param['posts_per_page'] = 6;


$list 		= qms4_list( 'fair', $param );
$cur_url 	= get_permalink($post->ID);
?>
<div class="l-search">
	<div class="wp-block-columns is-layout-flex wp-container-10"> <div class="wp-block-column is-layout-flow"><div class="widget qms4__term-list" data-taxonomy="fair__category" data-query-key="CAT">
  <ul class="widget__main-list">
    <li class="active">
      <a href="<?= $cur_url ?>?CAT=">
        <span classname="widget__main-list__term-name">相談会</span>
      </a>
    </li>
    <li class="active">
      <a href="<?= $cur_url ?>?CAT=">
        <span classname="widget__main-list__term-name">無料試食</span>
      </a>
    </li>
    <li class="">
      <a href="<?= $cur_url ?>?CAT=">
        <span classname="widget__main-list__term-name">模擬披露宴</span>
      </a>
    </li>
  </ul>
  <!-- /.widget__main-list -->
</div>
<!-- /.widget.qms4__term-list -->
</div> </div>
	</div>
	<div class=" l-main__body p-archive">


		<ul class="p-postList -type-list">
			<?php foreach ( $list as $item ) { ?>
			<?php
			global $post;
			$post = get_post($item->ID);
			?>

			<li class="<?php echo esc_attr( trim( 'p-postList__item ' . $list_class ) ); ?>">
				<a href="<?php the_permalink();?>" class="p-postList__link">
					<?php
						Arkhe::get_part( 'post_list/item/thumb', array(
								'sizes' => 'card' === $list_type ? '(min-width: 600px) 400px, 100vw' : '(min-width: 600px) 400px, 40vw',
							) );
					?>
					<div class="p-postList__body">
						<?php
							echo '<' . esc_attr( $h_tag ) . ' class="p-postList__title">';
							?>
						<?php the_title() ; ?>
						<?php echo '</' . esc_attr( $h_tag ) . '>'; ?>

						<div class="p-postList__excerpt"> <?php the_excerpt(); ?></div>

							<div class="c-postIcon">
								<?php
								$categories = get_the_terms( get_the_ID(), 'fair__category' );

								if ( $categories && ! is_wp_error( $categories ) ) :
									$draught_links = array();
									$html = '';
									foreach ( $categories as $term ) {
										$html .= '<li class="icon">'.$term->name.'</li>';
									}
									?>
									<ul class="p-postList__icon"><?= $html ?> </ul>
								<?php endif; ?>
								<?php

								$specials = get_the_terms( get_the_ID(), 'fair__special' );
								if ( $specials && ! is_wp_error( $specials ) ) :
									$draught_links = array();
									$html = '';
									foreach ( $specials as $term ) {
										$color = get_field( 'field_62fb7354562ba', $term->taxonomy . '_' . $term->term_id );
										$bg = 'style = "background-color: '.$color.'" ';
										$html .= '<li class="icon" '.$bg.'>'.$term->name.'</li>';
									}
									?>
									<ul class="p-postList__icon"><?= $html ?> </ul>
								<?php endif; ?>

						  </div>
						</div>
					</a>
				</li>
			<?php } ?>
		  </ul>
		</div>
	</main>
<?php get_footer();
