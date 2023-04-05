<?php
/**
 * Template Name: Fair_calendar
 */

get_header();
while ( have_posts() ) :
	the_post();
	$the_id = get_the_ID();
?>
	<main id="main_content" class="<?php Arkhe::main_class(); ?>">
		<article <?php post_class( Arkhe::get_main_body_class() ); ?> data-postid="<?php echo esc_attr( $the_id ); ?>">
			<?php
				the_content();
			?>
		</article>

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


$list = qms4_list( 'fair', $param );



// ========================================================================== //
?>

<? // qms4_site_part( 859 ) ?>


		<div class=" l-main__body p-archive">

		  	<ul class="p-postList -type-list">
				<?php foreach ( $list as $item ) { ?>

				<?php
				global $post;
				$post = get_post($item->ID);

				$item = fabric_load_item();

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
								<?php echo '</' . esc_attr( $h_tag ) . '>';
				      ?>


				      <div class="qms4__post-list__post-date"><?= $item->date_html ?></div>
							<?php if ( Arkhe::$excerpt_length ) : ?>
								<div class="p-postList__excerpt">
									<?php the_excerpt(); ?>
								</div>
							<?php endif; ?>
							<?= $item->term_html ?>
							<?php
								Arkhe::get_part( 'post_list/item/meta', array(
									'show_date'     => false,
									'show_modified' => false,
									'show_cat'      => $show_cat,
									'author_id'     => $show_author ? $item->post_author : 0,
								) );
							?>
						</div>
					</a>
				</li>
			<?php } ?>
		  </ul>

		  <?php the_posts_pagination( array( 'mid_size' => 2 ) ); ?>
		</div>

	</main>
<?php
endwhile;
get_footer();
