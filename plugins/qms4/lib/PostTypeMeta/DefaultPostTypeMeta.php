<?php

namespace QMS4\PostTypeMeta;

use QMS4\PostTypeMeta\PostTypeMetaInterface;
use QMS4\TaxonomyMeta\TaxonomyMeta;


class DefaultPostTypeMeta implements PostTypeMetaInterface
{
	/** @var    \WP_Post_Type */
	private $wp_post_type;

	/**
	 * @param    \WP_Post_Type    $wp_post_type
	 */
	public function __construct( \WP_Post_Type $wp_post_type )
	{
		$this->wp_post_type = $wp_post_type;
	}

	/**
	 * @param    string    $name
	 */
	public static function from_name( string $name ): DefaultPostTypeMeta
	{
		$wp_post_type = get_post_type_object( $name );

		if ( ! $wp_post_type ) {
			throw new \RuntimeException( 'PostTypeMeta が見つかりません。不明なスラッグです: $names: ' . $name );
		}

		return new self( $wp_post_type );
	}

	// ====================================================================== //

	/**
	 * @return    int
	 */
	public function id(): int
	{
		return 0;
	}

	/**
	 * @return    string
	 */
	public function name(): string
	{
		return $this->wp_post_type->name;
	}

	/**
	 * @return    string
	 */
	public function label(): string
	{
		return $this->wp_post_type->label;
	}

	/**
	 * @return    bool
	 */
	public function is_public(): bool
	{
		return true;
	}

	/**
	 * リビジョン保存数
	 *
	 * @return    int
	 */
	public function revisions_to_keep(): int
	{
		return 0;
	}

	/**
	 * @return    string
	 */
	public function permalink_type(): string
	{
		// TODO: 実装する
		return '';
	}

	/**
	 * @return    string
	 */
	public function func_type(): string
	{
		return 'general';
	}

	/**
	 * @return    string
	 */
	public function reserve_url(): string
	{
		return '/reserve_e/';
	}

	/**
	 * @return    string
	 */
	public function editor(): string
	{
		return 'block_editor';
	}

	/**
	 * @return    int
	 */
	public function cal_base_date(): int
	{
		return 0;
	}

	/**
	 * @return    string[]
	 */
	public function components(): array
	{
		return array();
	}

	/**
	 * @return    TaxonomyMeta[]
	 */
	public function taxonomies(): array
	{
		return array();
	}

	/**
	 * @return    string    'default' | 'card' | 'list' | 'text'
	 */
	public function archive_layout(): string
	{
		return 'default';
	}

	/**
	 * @return    string
	 */
	public function orderby(): string
	{
		return 'menu_order';
	}

	/**
	 * @return    string
	 */
	public function order(): string
	{
		return 'ASC';
	}

	/**
	 * @return    string
	 */
	public function date_format(): string
	{
		return 'Y年n月j日（J-D';
	}

	/**
	 * @return    int
	 */
	public function new_date(): int
	{
		return 5;
	}
	public function date_html(): string
	{
		return '';
	}

	// public function modified_html(){
	// 	return new Date();
	// }
	/**
	 * @return    string
	 */
	public function new_class(): string
	{
		return 'new';
	}

	/**
	 * @return    bool|null
	 */
	public function show_sidebar_archive(): ?bool
	{
		return null;
	}

	/**
	 * @return    bool|null
	 */
	public function show_sidebar_single(): ?bool
	{
		return null;
	}

	/**
	 * @return    int|null
	 */
	public function posts_per_page(): ?int
	{
		return get_option( 'posts_per_page', 12 );
	}

	/**
	 * @return    int
	 */
	public function term_html()
	{
		return '<li class="icon">[name]</li>';
	}
}
