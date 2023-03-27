<?php
namespace Fabric\Util;


class GetPart
{
	/** @var    string */
	private $alt_path;

	/**
	 * @param    string    $path
	 * @param    string    $alt_path
	 */
	public function __construct( string $path, string $alt_path )
	{
		$this->alt_path = $alt_path;

		add_filter( "arkhe_part_path__{$path}", array( $this, 'get_part' ) );
	}

	/**
	 * @param    string    $inc_path
	 * @return    string
	 */
	public function get_part( string $inc_path ): string
	{
		$screen = $this->screen();

		if ( ! $screen ) { return $inc_path; }

		$alt_path = __DIR__ . "/../../template-parts/{$screen}/{$this->alt_path}.php";

		return file_exists( $alt_path ) ? $alt_path : $inc_path;
	}

	/**
	 * @return    string|null;
	 */
	private function screen(): ?string
	{
		if ( is_404() ) { return '404'; }

		if ( is_front_page() ) { return 'front_page'; }

		if ( is_page() ) { return 'page'; }

		if ( is_archive() ) { return get_query_var( 'post_type' ); }

		if ( is_single() ) {
			global $post;
			return $post->post_type;
		}

		return null;
	}
}
