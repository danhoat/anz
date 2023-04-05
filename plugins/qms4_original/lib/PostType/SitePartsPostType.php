<?php

namespace QMS4\PostType;

use QMS4\PostType\PostType;


class SitePartsPostType extends PostType
{
	/**
	 * @return    string
	 */
	protected function name(): string
	{
		return 'qms4__site_parts';
	}

	/**
	 * @return    string
	 */
	protected function label(): string
	{
		return 'サイトパーツ';
	}

	/**
	 * @return    array<string,string>
	 */
	protected function labels(): array
	{
		return array(
			'name'          => 'サイトパーツ',
			'singular_name' => 'サイトパーツ',
			'all_items'     => 'サイトパーツ 一覧',
			'add_new'       => 'サイトパーツ 追加',
			'add_new_item'  => '新規投稿',
			'edit_item'     => '編集',
			'new_item'      => 'サイトパーツ追加',
			'view_item'     => '詳細を表示',
		);
	}

	/**
	 * @return    bool
	 */
	protected function public(): bool
	{
		return false;
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
		return 20;  // 固定ページの下
	}

	/**
	 * @return    string|null
	 */
	protected function menu_icon(): ?string
	{
		return 'dashicons-tag';
	}

	/**
	 * @return    string[]|false
	 */
	protected function supports()
	{
		return array( 'title', 'editor' );
	}

	/**
	 * @param    \WP_Post    $wp_post
	 * @return    void
	 */
	public function register_meta_box_cb( \WP_Post $wp_post )
	{
	}

	/**
	 * @return    bool|array<string,mixed>
	 */
	protected function rewrite()
	{
		return false;
	}

	/**
	 * @return    bool|string
	 */
	protected function query_var()
	{
		return false;
	}

	/**
	 * @return    bool
	 */
	protected function can_export(): bool
	{
		return false;
	}

	/**
	 * @return    bool|null
	 */
	protected function delete_with_user(): ?bool
	{
		return false;
	}
}
