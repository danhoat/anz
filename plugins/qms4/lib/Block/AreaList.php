<?php

namespace QMS4\Block;

use QMS4\Area\AreaTreeFactory;
use QMS4\Area\MapReduce\AreaMapper;
use QMS4\Area\MapReduce\AreaReducer;
use QMS4\PostForest\PostTree;
use QMS4\PostForest\PostForestFactory;
use QMS4\QueryString\QueryString;


class AreaList
{
	/** @var    string */
	private $name = 'area-list';

	/** @var    array<string,mixed> */
	private $url;

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
		$href = $attributes[ 'href' ];
		$layout = $attributes[ 'layout' ];
		$target_blank = $attributes[ 'targetBlank' ];
		$hide_empty = $attributes[ 'hideEmpty' ];
		$post_types = $attributes[ 'postTypes' ];

		$this->url = parse_url( $href );
		$this->query_string = QueryString::from_global_get( $_GET, 'area' );

		$target = $target_blank ? 'target="_blank"' : '';


		if ( $layout === 'hierarchical' ) {
			$factory = new PostForestFactory();
			$post_forest = $factory->from_post_type( 'qms4__area_master' );
			$post_forest->init_fields( new AreaMapper(), new AreaReducer() );

			if ( $hide_empty ) {
				$post_forest = $post_forest->filter( function( PostTree $post_tree ) use ( $post_types ) {
					$ref_count = $post_tree->field( 'ref_count', array() );

					foreach ( $post_types as $post_type ) {
						if ( ! empty( $ref_count[ $post_type ] ) ) {
							return true;
						}
					}

					return false;
				} );
			}

			ob_start();
			require( QMS4_DIR . "/blocks/templates/{$this->name}__layout__hierarchical.php" );
			return ob_get_clean();
		} elseif ( $layout === 'flat' ) {
			$query = new \WP_Query( array(
				'post_type' => 'qms4__area_master',
				'post_status' => 'publish',
				'orderby' => 'menu_order',
				'order' => 'ASC',
				'posts_per_page' => -1,
			) );
			$wp_posts = $query->posts;

			if ( $hide_empty ) {
				$new_wp_posts = array();
				foreach ( $wp_posts as $wp_post ) {
					$ref_count = get_post_meta( $wp_post->ID, 'qms4__area__ref_count', true );
					$ref_count = $ref_count ?: array();

					$counts = 0;
					foreach ( $post_types as $post_type ) {
						if ( ! empty( $ref_count[ $post_type ] ) ) {
							$counts += $ref_count[ $post_type ];
						}
					}

					if ( $counts > 0 ) {
						$new_wp_posts[] = $wp_post;
					}
				}

				$wp_posts = $new_wp_posts;
			}

			ob_start();
			require( QMS4_DIR . "/blocks/templates/{$this->name}__layout__flat.php" );
			return ob_get_clean();
		}
	}

	// ====================================================================== //

	/**
	 * @param    string    $slug
	 * @return    bool
	 */
	private function active( string $slug ): bool
	{
		return $this->query_string->has( 'area', $slug );
	}

	/**
	 * @param    string    $slug
	 * @return    string
	 */
	private function href( string $slug ): string
	{
		$url = $this->url;

		$query = $this->query_string->has( 'area', $slug )
			? $this->query_string->remove( 'area', $slug )
			: $this->query_string->add( 'area', $slug );
		$url[ 'query' ] = (string) $query;

		return $this->build_url( $url );
	}

	/**
	 * @param    array<string,mixed>    $elements
	 * @return    string
	 */
	private function build_url( array $elements ): string
	{
		$e = $elements;
		return
			( isset( $e[ 'host' ] ) ? (
				( isset( $e[ 'scheme' ] ) ? "{$e['scheme']}://" : '//' ) .
				( isset( $e[ 'user' ] ) ? $e[ 'user' ] . ( isset( $e[ 'pass' ] ) ? ":{$e['pass']}" : '' ) . '@' : '' ) .
				$e[ 'host' ] .
				( isset( $e[ 'port' ] ) ? ":{$e['port']}" : '' )
			) : '' ) .
			( isset( $e[ 'path' ] ) ? $e[ 'path' ] : '/' ) .
			( isset( $e[ 'query' ] ) ? '?' . ( is_array( $e[ 'query' ] ) ? http_build_query( $e['query'], '', '&' ) : $e[ 'query' ] ) : '' ) .
			( isset( $e[ 'fragment' ] ) ? "#{$e['fragment']}" : '' )
		;
	}

	/**
	 * @param    int[]
	 * @return    int
	 */
	public function max_depth( array $depth ): int
	{
		switch ( count( $depth ) ) {
			case 0: return 0;
			case 1: return $depth[ 0 ];
			default: return max( $depth );
		}
	}
}
