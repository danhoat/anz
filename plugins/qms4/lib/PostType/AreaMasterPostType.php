<?php

namespace QMS4\PostType;

use QMS4\PostType\PostType;


class AreaMasterPostType extends PostType
{
	/**
	 * @return    string
	 */
	protected function name(): string
	{
		return 'qms4__area_master';
	}

	/**
	 * @return    string
	 */
	protected function label(): string
	{
		return 'エリアマスタ';
	}

	/**
	 * @return    array<string,string>
	 */
	protected function labels(): array
	{
		return array(
			'name'          => 'エリアマスタ',
			'singular_name' => 'エリアマスタ',
			'all_items'     => 'エリア 一覧',
			'add_new'       => 'エリア 追加',
			'add_new_item'  => '新規エリア',
			'edit_item'     => '編集',
			'new_item'      => 'エリア追加',
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
	protected function hierarchical(): bool
	{
		return true;
	}

	/**
	 * @return    bool
	 */
	protected function show_ui(): bool
	{
		return true;
	}

	/**
	 * @return    int|null
	 */
	protected function menu_position(): ?int
	{
		return 21;  // 固定ページ・サイトパーツの下
	}

	/**
	 * @return    string|null
	 */
	protected function menu_icon(): ?string
	{
		return 'dashicons-admin-site-alt2';
	}

	/**
	 * @return    string[]|false
	 */
	protected function supports()
	{
		return array( 'title', 'page-attributes' );
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
