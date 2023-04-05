<?php
/**
 * 固定ページテンプレート
 */
get_header();
while ( have_posts() ) :
	the_post();
	$the_id = get_the_ID();
?>
	<main id="main_content" class="<?php Arkhe::main_class(); ?>" arkhe - page.php>
		<article <?php post_class( Arkhe::get_main_body_class() ); ?> data-postid="<?php echo esc_attr( $the_id ); ?>">
			<?php
				do_action( 'arkhe_start_page_main', $the_id );
				Arkhe::get_part( 'page' );
				do_action( 'arkhe_end_page_main', $the_id );
			?>
		</article>
	</main>
<?php
endwhile;
get_footer();
