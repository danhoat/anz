<?php
/**
 * Template Name: Date Event
 */

get_header();
while ( have_posts() ) :
	the_post();
	$the_id = get_the_ID();
?>
	<main id="main_content" class="<?php Arkhe::main_class(); ?>">
		<article <?php post_class( Arkhe::get_main_body_class() ); ?> data-postid="<?php echo esc_attr( $the_id ); ?>">
			<?php

				echo do_shortcode('[events_date]');
			?>
		</article>
	</main>
<?php
endwhile;
get_footer();
