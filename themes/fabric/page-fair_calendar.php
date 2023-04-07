<?php
/**
 * Template Name: Fair_calendar
 */

get_header();?>
<main id="main_content" class="<?php Arkhe::main_class(); ?>">
<?php while ( have_posts() ){ ?>
	<?php
		the_post();
		$the_id = get_the_ID();?>

		<article <?php post_class( Arkhe::get_main_body_class() ); ?> data-postid="<?php echo esc_attr( $the_id ); ?>">
			<?php the_content(); ?>
		</article>

<?php } ?>


</main>
<script type="text/javascript">
	var url = window.location.href;

	<?php if( wp_is_mobile() ){?>
		if(url.split("?CAT=").length == 2){
		    window.scrollTo({
			  top: 1050,
			  behavior: "smooth",
			});
		}
	<?php } else { ?>

		if(url.split("?CAT=").length == 2){
		    window.scrollTo({
			  top: 1200,
			  behavior: "smooth",
			});
		}
<?php } ?>
</script>
<?php get_footer();?>