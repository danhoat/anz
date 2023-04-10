<?php

namespace QMS4\PostTypeMeta;

use QMS4\PostTypeMeta\PostTypeMetaInterface;
use QMS4\TaxonomyMeta\TaxonomyMeta;
use QMS4\TaxonomyMeta\TaxonomyMetaFactory;


class PostTypeMeta implements PostTypeMetaInterface
{
	/** @var    array<string,mixed> */
	private $cache = array();

	/** @var    \WP_Post */
	private $wp_post;

	/**
	 * @param    \WP_Post    $wp_post
	 */
	public function __construct( \WP_Post $wp_post )
	{
		$this->wp_post = $wp_post;
	}

	/**
	 * @param    int    $post_id
	 * @return    self
	 */
	public static function from_post_id( int $post_id ): self
	{
		$wp_post = get_post( $post_id );

		return new self( $wp_post );
	}

	/**
	 * @param    string[]    $name
	 * @return    self
	 */
	public static function from_name( array $names ): self
	{


		$default_types = array('post','page','attachment','nav_menu_item','wp_block','wp_template');
		$type = $names[0];

		if(in_array($type, $default_types)){

			$query = new \WP_Query( array(
				'post_type' => $type,
				'post_status' => 'publish',
				'posts_per_page' => 1

			) );
		} else{

			$query = new \WP_Query( array(
				'post_type' => 'qms4',
				'post_status' => 'publish',
				'posts_per_page' => 1,
				'meta_query' => array(
					array(
						'key' => 'qms4__post_type__name',
						'value' => $names,
						'compare' => 'IN',
					),
				),
			) );
		}

		if ( ! $query->found_posts ) {
			return 0;
			throw new \RuntimeException( 'PostTypeMeta が見つかりません。不明なスラッグです: $names: ' . join( '/', $names ) );
		}

		return new self( $query->posts[0] );
	}

	// ====================================================================== //

	/**
	 * @return    int
	 */
	public function id(): int
	{
		if ( isset( $this->cache[ 'id' ] ) ) { return $this->cache[ 'id' ]; }

		return $this->cache[ 'id' ] = $this->wp_post->ID;
	}

	/**
	 * @return    string
	 */
	public function name(): string
	{
		if ( isset( $this->cache[ 'name' ] ) ) { return $this->cache[ 'name' ]; }

		$name = get_post_meta( $this->wp_post->ID, 'qms4__post_type__name', true );

		return $this->cache[ 'name' ] = trim( $name );
	}

	/**
	 * @return    string
	 */
	public function label(): string
	{
		if ( isset( $this->cache[ 'label' ] ) ) { return $this->cache[ 'label' ]; }

		$label = $this->wp_post->post_title;

		return $this->cache[ 'label' ] = trim( $label );
	}

	/**
	 * @return    bool
	 */
	public function is_public(): bool
	{
		if ( isset( $this->cache[ 'is_public' ] ) ) { return $this->cache[ 'is_public' ]; }

		if ( ! function_exists( 'get_field' ) ) { return true; }

		$is_public = get_field( 'qms4__post_type__public', $this->wp_post->ID );

		return $this->cache[ 'is_public' ] = (bool) $is_public;
	}

	/**
	 * リビジョン保存数
	 *
	 * @return    int
	 */
	public function revisions_to_keep(): int
	{
		if ( isset( $this->cache[ 'revisions_to_keep' ] ) ) { return $this->cache[ 'revisions_to_keep' ]; }

		$revisions_to_keep = get_post_meta( $this->wp_post->ID, 'qms4__post_type__revisions_to_keep', true );

		if ( ! is_numeric( $revisions_to_keep ) ) {
			$revisions_to_keep = 5;
		}

		return $this->cache[ 'revisions_to_keep' ] = (int) $revisions_to_keep;
	}

	/**
	 * @return    string
	 */
	public function permalink_type(): string
	{
		if ( isset( $this->cache[ 'permalink_type' ] ) ) { return $this->cache[ 'permalink_type' ]; }

		$permalink_type = get_post_meta( $this->wp_post->ID, 'qms4__post_type__permalink_type', true );

		return $this->cache[ 'permalink_type' ] = $permalink_type;
	}

	/**
	 * @return    string    'general'|'event'|'calendar'
	 */
	public function func_type(): string
	{
		if ( isset( $this->cache[ 'func_type' ] ) ) { return $this->cache[ 'func_type' ]; }

		$func_type = get_post_meta( $this->wp_post->ID, 'qms4__post_type__func_type', true );

		return $this->cache[ 'func_type' ] = $func_type;
	}

	/**
	 * @return    string
	 */
	public function reserve_url(): string
	{
		if ( isset( $this->cache[ 'reserve_url' ] ) ) { return $this->cache[ 'reserve_url' ]; }

		$reserve_url = get_post_meta( $this->wp_post->ID, 'qms4__post_type__reserve_url', true );

		return $this->cache[ 'reserve_url' ] = $reserve_url ?: '/reserve_e/';
	}

	/**
	 * @return    string
	 */
	public function editor(): string
	{
		if ( isset( $this->cache[ 'editor' ] ) ) { return $this->cache[ 'editor' ]; }

		$editor = get_post_meta( $this->wp_post->ID, 'qms4__post_type__editor', true );

		return $this->cache[ 'editor' ] = $editor ?: 'block_editor';
	}

	/**
	 * @return    int
	 */
	public function cal_base_date(): int
	{
		if ( isset( $this->cache[ 'base_date' ] ) ) { return $this->cache[ 'base_date' ]; }

		if ( ! function_exists( 'get_field' ) ) { return 0; }

		$base_date = get_field( 'qms4__post_type__cal_base_date', $this->wp_post->ID );

		return $this->cache[ 'base_date' ] = (int) $base_date;
	}

	/**
	 * @return    string[]
	 */
	public function components(): array
	{
		if ( isset( $this->cache[ 'components' ] ) ) { return $this->cache[ 'components' ]; }

		if ( ! function_exists( 'get_field' ) ) { return array(); }

		$components = get_post_meta( $this->wp_post->ID, 'qms4__post_type__components', true );

		return $this->cache[ 'components' ] = $components ?: array();
	}

	/**
	 * @return    TaxonomyMeta[]
	 */
	public function taxonomies(): array
	{
		if ( isset( $this->cache[ 'taxonomies' ] ) ) { return $this->cache[ 'taxonomies' ]; }

		$factory = new TaxonomyMetaFactory();

		return $this->cache[ 'taxonomies' ] = $factory->from_post_type( $this->name() );
	}

	/**
	 * @return    string    'default' | 'card' | 'list' | 'text'
	 */
	public function archive_layout(): string
	{
		if ( isset( $this->cache[ 'archive_layout' ] ) ) { return $this->cache[ 'archive_layout' ]; }

		$layout = get_post_meta( $this->wp_post->ID, 'qms4__output__archive_layout', true );

		return $this->cache[ 'archive_layout' ] = $layout ?: 'default';
	}

	/**
	 * @return    string
	 */
	public function orderby(): string
	{
		if ( isset( $this->cache[ 'orderby' ] ) ) { return $this->cache[ 'orderby' ]; }

		if ( ! function_exists( 'get_field' ) ) { return 'menu_order'; }

		$orderby = get_field( 'qms4__output__orderby', $this->wp_post->ID );

		return $this->cache[ 'orderby' ] =  !empty($orderby) ? $orderby : 'menu_order';
	}

	/**
	 * @return    string
	 */
	public function order(): string
	{
		if ( isset( $this->cache[ 'order' ] ) ) { return $this->cache[ 'order' ]; }

		if ( ! function_exists( 'get_field' ) ) { return 'ASC'; }

		$order = get_field( 'qms4__output__order', $this->wp_post->ID );

		return $this->cache[ 'order' ] = !empty($order) ? $order: 'ASC';
	}

	/**
	 * @return    int
	 */
	public function new_date(): int
	{
		if ( isset( $this->cache[ 'new_date' ] ) ) { return $this->cache[ 'new_date' ]; }

		if ( ! function_exists( 'get_field' ) ) { return 5; }

		$new_date = get_field( 'qms4__output__new_date', $this->wp_post->ID );

		return $this->cache[ 'new_date' ] = (int) $new_date;
	}
	/**
	 * @retrn string
	 */
	 public function date_format(): string
	{
	 	if (!function_exists("get_field")) { return "Y年n月j日（J-D）"; }

		$date_format = get_field( 'qms4__date_format', $this->wp_post->ID );


		return $this->cache['date_format']  = ($date_format) && is_string($date_format) ?  $date_format  : "Y年n月j日（J-D）";

	 }

	 /**
	  * return string
	  */
	 public function date_html() : string
	 {
	 	if (!function_exists("get_field")) { return "Y年n月j日（J-D）"; }

		$date_format = get_field( 'qms4__date_format', $this->wp_post->ID );


		return $this->cache['date_html']  = ($date_format) && is_string($date_format) ?  $date_format  : "Y年n月j日（J-D）";
	 }
	 /**
	  * return string
	  */
	// public function modified_html() : string
	//  {
	//  	if (!function_exists("get_field")) { return "Y年n月j日（J-D）"; }

	// 	$date_format = get_field( 'qms4__date_format', $this->wp_post->ID );


	// 	return $this->cache['modified_html']  = ($date_format) && is_string($date_format) ?  $date_format  : "Y年n月j日（J-D）";
	//  }

	/**
	 * @return    string
	 */
	public function new_class(): string
	{
		if ( isset( $this->cache[ 'new_class' ] ) ) { return $this->cache[ 'new_class' ]; }

		if ( ! function_exists( 'get_field' ) ) { return 'new'; }

		$new_class = get_field( 'qms4__output__new_class', $this->wp_post->ID );

		return $this->cache[ 'new_class' ] = !empty($new_class) ? $new_class : '';
	}

	/**
	 * @return    bool|null
	 */
	public function show_sidebar_archive(): ?bool
	{
		if ( isset( $this->cache[ 'show_sidebar_archive' ] ) ) { return $this->cache[ 'show_sidebar_archive' ]; }

		$show_sidebar = get_post_meta( $this->wp_post->ID, 'qms4__output__show_sidebar_archive', true );

		switch ( $show_sidebar ) {
			case 'true': return true;
			case 'false': return false;
			default: return null;
		}
	}

	/**
	 * @return    bool|null
	 */
	public function show_sidebar_single(): ?bool
	{
		if ( isset( $this->cache[ 'show_sideshow_sidebar_singlebar_archive' ] ) ) { return $this->cache[ 'show_sidebar_single' ]; }

		$show_sidebar = get_post_meta( $this->wp_post->ID, 'qms4__output__show_sidebar_single', true );

		switch ( $show_sidebar ) {
			case 'true': return true;
			case 'false': return false;
			default: return null;
		}
	}

	/**
	 * @return    int|null
	 */
	public function posts_per_page(): ?int
	{
		if ( isset( $this->cache[ 'posts_per_page' ] ) ) { return $this->cache[ 'posts_per_page' ]; }

		$posts_per_page = get_post_meta( $this->wp_post->ID, 'qms4__output__posts_per_page', true );

		return $this->cache[ 'posts_per_page' ] = (int) $posts_per_page ?: null;
	}

	/**
	 * @return    int
	 */
	public function term_html()
	{
		if ( isset( $this->cache[ 'term_html' ] ) ) { return $this->cache[ 'term_html' ]; }

		if ( ! function_exists( 'get_field' ) ) { return '<li class="icon">[name]</li>'; }

		$term_html = get_field( 'qms4__output__term_html', $this->wp_post->ID );

		return $this->cache[ 'term_html' ] = $term_html;
	}

	// ====================================================================== //

	// TODO: これ不要なはず。削除する
	public function carbon_copy()
	{
		update_post_meta(
			$this->wp_post->ID,
			'qms4__post_type__name_cc',
			$this->name()
		);
	}
}
