<?php

namespace QMS4\PostType;

use QMS4\PostType\PostType;


class Qms4PostType extends PostType
{
	/**
	 * @return    string
	 */
	protected function name(): string
	{
		return 'qms4';
	}

	/**
	 * @return    string
	 */
	protected function label(): string
	{
		return 'QMS4 設定';
	}

	/**
	 * @return    array<string,string>
	 */
	protected function labels(): array
	{
		return array(
			'name'          => 'QMS4 設定',
			'singular_name' => 'QMS4 設定',
			'all_items'     => '投稿タイプ 一覧',
			'add_new'       => '投稿タイプ 追加',
			'add_new_item'  => '新規投稿',
			'edit_item'     => '編集',
			'new_item'      => 'カスタム投稿追加',
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
	 * @return    int|null
	 */
	protected function menu_position(): ?int
	{
		return 80;  // 設定の下
	}

	/**
	 * @return    string|null
	 */
	protected function menu_icon(): ?string
	{
		return 'dashicons-admin-generic';
	}

	/**
	 * @return    string|string[]
	 */
	protected function capability_type()
	{
		return 'qms4';
	}

	/**
	 * @return    array<string,string>
	 */
	protected function capabilities(): array
	{
		return array(
			'create_posts' => 'qms4__create_posts',
			'edit_post' => 'qms4__edit_post',
			'read_post' => 'qms4__read_post',
			'delete_post' => 'qms4__delete_post',
			'edit_posts' => 'qms4__edit_posts',
			'edit_others_posts' => 'qms4__edit_others_posts',
			'delete_posts' => 'qms4__delete_posts',
			'publish_posts' => 'qms4__publish_posts',
			'read_private_posts' => 'qms4__read_private_posts',

			'delete_private_posts' => 'qms4__delete_private_posts',
			'delete_published_posts' => 'qms4__delete_published_posts',
			'delete_others_posts' => 'qms4__delete_others_posts',
			'edit_private_posts' => 'qms4__edit_private_posts',
			'edit_published_posts' => 'qms4__edit_published_posts',
		);
	}

	/**
	 * @return    string[]|false
	 */
	protected function supports()
	{
		return array( 'title' );
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
