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
		<?php the_content(); ?>
	</article>

<?php endwhile; ?>

<?php

$list_type     = ARKHE_LIST_TYPE;
$list_type 		= 'card';
$cur_url 	= get_permalink($post->ID);

$args = array(
	'post_type' 	=> 'fair',
	'post_status' => 'publish',
	'posts_per_page' => 20,
	);
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

?>
<?php farbic_list_categories_filter($cur_url) ?>

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
										<?php farbic_show_categories($item->ID) ?>
										<?php farbic_show_fair_icons($item->ID);?>
								  	</div>
								</div>
							</a>
						</li>
					<?php }?>
			<?php } ?>
			<?php wp_reset_query() ?>
		  </ul>
		</div>
	</main>
<?php get_footer();
