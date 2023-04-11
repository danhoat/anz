<?php

namespace QMS4\Item\Term;

use QMS4\Item\Term\Term;
use QMS4\Item\Term\Terms;
use QMS4\Param\Param;


class TermsFactory
{
	/**
	 * @param    \WP_Post    $wp_post
	 * @param    string    $taxonomy
	 * @param    Param    $param
	 * @return    Terms
	 */
	public function from_taxonomy(
		\WP_Post $wp_post,
		string $taxonomy,
		Param $param
	): Terms
	{
		$term_count = isset( $param[ 'term_count' ] ) ? $param[ 'term_count' ] : -1;
		$parent = isset( $param[ 'parent' ] ) ? $param[ 'parent' ] : null;
		$term_html = isset( $param[ 'term_html' ] )
			? $param[ 'term_html' ]
			: '<li class="icon" style="background-color:[color]">[name]</li>';


		$wp_terms = get_the_terms( $wp_post->ID, $taxonomy );

		if ( is_wp_error( $wp_terms ) || $wp_terms === false ) {
			return new Terms( array() );
		}

		$terms = array();
		foreach ( $wp_terms as $wp_term ) {
			$terms[] = new Term( $wp_term, $param );
		}

		return new Terms( $terms, $term_count, $parent, $term_html );
	}
}
