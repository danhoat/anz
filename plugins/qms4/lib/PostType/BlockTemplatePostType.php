<?php

namespace QMS4\PostType;

use QMS4\PostType\PostType;


class BlockTemplatePostType extends PostType
{
	/**
	 * @return    string
	 */
	protected function name(): string
	{
		return 'qms4__block_template';
	}

	/**
	 * @return    string
	 */
	protected function label(): string
	{
		return 'ブロックテンプレート';
	}

	/**
	 * @return    array<string,string>
	 */
	protected function labels(): array
	{
		return array(
			'name'          => 'ブロックテンプレート',
			'singular_name' => 'ブロックテンプレート',
			'all_items'     => 'ブロックテンプレート',
			'add_new'       => 'テンプレート 追加',
			'add_new_item'  => '新規テンプレート',
			'edit_item'     => '編集',
			'new_item'      => 'テンプレート追加',
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
	 * @return    bool|string
	 */
	protected function show_in_menu()
	{
		return "edit.php?post_type=qms4";
	}

	/**
	 * @return    bool
	 */
	protected function show_in_rest(): bool
	{
		return true;
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
		return array( 'editor', 'revisions' );
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
