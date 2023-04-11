<?php

namespace QMS4\Block;

use QMS4\Fetch\MonthlyPosts as DataMonthlyPosts;
use QMS4\QueryString\QueryString;


class MonthlyPosts
{
	/** @var    string */
	private $name = 'monthly-posts';

	/** @var    string */
	private $post_type;

	/** @var    int */
	private $this_year;

	/** @var    QueryString */
	private $query_string;

	public function register()
	{
		register_block_type(
			QMS4_DIR . "/blocks/build/{$this->name}",
			array(
				'render_callback' => array( $this, 'render' ),
			)
		);
	}

	/**
	 * @param    array<string,mixed>    $attributes
	 * @param    string|null    $content
	 * @return    string
	 */
	public function render( array $attributes, ?string $content ): string
	{
		$post_type = $attributes[ 'postType' ];
		$this->post_type = $post_type;

		$query_string = QueryString::from_global_get( $_GET, 'ym' );
		$this->query_string = $query_string;

		$now = new \DateTime( 'now', wp_timezone() );
		$this_year = $now->format( 'Y' );
		$this->this_year = $this_year;

		$rows = ( new DataMonthlyPosts( $post_type ) )->fetch( $this_year );

		ob_start();
		if ( file_exists( QMS4_DIR . "/blocks/templates/{$this->name}.php" ) ) {
			require( QMS4_DIR . "/blocks/templates/{$this->name}.php" );
		}
		return ob_get_clean();
	}

	// ====================================================================== //

	/**
	 * @param    int    $month
	 * @return    bool
	 */
	private function active( int $month ): bool
	{
		$ym = $this->this_year . str_pad( $month, 2, '0', STR_PAD_LEFT );
		return $this->query_string->has( 'ym', $ym );
	}

	private function href( int $month ): string
	{
		$ym = $this->this_year . str_pad( $month, 2, '0', STR_PAD_LEFT );
		return get_post_type_archive_link( $this->post_type ) . '?ym=' . $ym;
	}
}
