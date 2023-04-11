<?php

if ( ! function_exists( 'qms4_site_part' ) ) {
	/**
	 * @param    int    $post_id
	 * @return    string
	 */
	function qms4_site_part( int $post_id ): string
	{
		$content = get_the_content( null, null, $post_id );

		$content = do_shortcode( $content );
		$content = apply_filters( 'the_content', $content );
		$content = str_replace( ']]>', ']]&gt;', $content );

		return $content;
	}
}
