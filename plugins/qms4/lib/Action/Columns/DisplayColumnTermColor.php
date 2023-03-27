<?php

namespace QMS4\Action\Columns;

use QMS4\PostMeta\TermColor;


class DisplayColumnTermColor
{
	/**
	 * @param    string    $string
	 * @param    string    $column_name
	 * @param    int    $term_id
	 * @return    string
	 */
	public function __invoke( string $string, string $column_name, int $term_id ): string
	{
		if ( $column_name !== 'qms4__term__color' ) { return $string; }

		$color = TermColor::get_post_meta( $term_id );

		if ( is_null( $color ) ) {
			return  trim( '
				<span aria-hidden="true">—</span>
				<span class="screen-reader-text">色は設定されていません</span>
			' );
		} else {
			return trim( '
				<div
					style="
						display: flex;
						justify-content: center;
						align-items: center;
						width: 100%;
						height: 2em;
						font-size: 16px;
						border: 1px solid #ccc;
						border-radius: 4px;
						background-color: ' . $color . ';
					"
					title="' . $color . '"
				>
					<span
						style="
							color: ' . $color . ';
							filter: invert(100%) grayscale(100%) contrast(100);
						"
					>' . $color . '</span>
				</div>
			' );
		}
	}
}
