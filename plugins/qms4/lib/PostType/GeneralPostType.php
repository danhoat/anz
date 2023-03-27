<?php

namespace QMS4\PostType;

use QMS4\PostTypeMeta\PostTypeMeta;
use QMS4\PostType\PostType;
use QMS4\Util\BuildBlockTemplate;


class GeneralPostType extends PostType
{
	/** @var    PostTypeMeta */
	protected $post_type_meta;

	public function __construct( PostTypeMeta $post_type_meta )
	{
		$this->post_type_meta = $post_type_meta;
	}

	/**
	 * @return    string
	 */
	protected function name(): string
	{
		return $this->post_type_meta->name();
	}

	/**
	 * @return    string
	 */
	protected function label(): string
	{
		return $this->post_type_meta->label();
	}

	/**
	 * @return    array<string,string>
	 */
	protected function labels(): array
	{
		$label = $this->post_type_meta->label();

		return array(
			'name' => $label,
			'singular_name' => $label,
			'all_items' => "{$label} 一覧",
			'add_new' => '新規投稿',
			'add_new_item' => '新規投稿',
			'edit_item' => '編集',
			'new_item' => '投稿追加',
			'view_item' => '内容を表示',
		);
	}

	/**
	 * @return    bool
	 */
	protected function public(): bool
	{
		return $this->post_type_meta->is_public();
	}

	/**
	 * @return    bool
	 */
	protected function show_ui(): bool
	{
		return true;
	}

	/**
	 * @return    bool
	 */
	protected function show_in_rest(): bool
	{
		return true;
	}

	/**
	 * @return    int|null
	 */
	protected function menu_position(): ?int
	{
		return 35;
	}

	/**
	 * @return    string|null
	 */
	protected function menu_icon(): ?string
	{
		return 'dashicons-feedback';
	}

	/**
	 * @return    string|string[]
	 */
	protected function capability_type()
	{
		$name = $this->post_type_meta->name();
		$id = $this->post_type_meta->id();

		return "{$name}_{$id}";
	}

	/**
	 * @return    array<string,string>
	 */
	protected function capabilities(): array
	{
		$name = $this->post_type_meta->name();
		$id = $this->post_type_meta->id();

		$capabilities = array(
			'create_posts'       => "{$name}_{$id}__create_posts",
			'edit_post'          => "{$name}_{$id}__edit_post",
			'read_post'          => "{$name}_{$id}__read_post",
			'delete_post'        => "{$name}_{$id}__delete_post",
			'edit_posts'         => "{$name}_{$id}__edit_posts",
			'edit_others_posts'  => "{$name}_{$id}__edit_others_posts",
			'delete_posts'       => "{$name}_{$id}__delete_posts",
			'publish_posts'      => "{$name}_{$id}__publish_posts",
			'read_private_posts' => "{$name}_{$id}__read_private_posts",
		);

		if ( $this->map_meta_cap() ) {
			$capabilities_for_mapping = array(
				'delete_private_posts'   => "{$name}_{$id}__delete_private_posts",
				'delete_published_posts' => "{$name}_{$id}__delete_published_posts",
				'delete_others_posts'    => "{$name}_{$id}__delete_others_posts",
				'edit_private_posts'     => "{$name}_{$id}__edit_private_posts",
				'edit_published_posts'   => "{$name}_{$id}__edit_published_posts",
			);

			$capabilities = array_merge( $capabilities, $capabilities_for_mapping );
		}

		return $capabilities;
	}

	/**
	 * @return    bool|null
	 */
	protected function map_meta_cap(): ?bool
	{
		return true;
	}

	/**
	 * @return    string[]|false
	 */
	protected function supports()
	{
		$components = $this->post_type_meta->components();

		return array_filter( array(
			'title',
			'author',
			in_array( 'content', $components, true ) ? 'editor' : null,
			in_array( 'thumbnail', $components, true ) ? 'thumbnail' : null,
		) );
	}

	/**
	 * @param    \WP_Post    $wp_post
	 * @return    void
	 */
	public function register_meta_box_cb( \WP_Post $wp_post )
	{
	}

	/**
	 * @return    bool|string
	 */
	protected function has_archive()
	{
		return true;
	}
}
