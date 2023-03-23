<?php
declare(strict_types=1);

namespace QMS3\BrickAdmin\PostType;

use QMS3\BrickAdmin\PostType\PostType;


class NotificationMasterPostType extends PostType
{
	/**
	 * @return    string
	 */
	protected function name()
	{
		return 'brick__notification';
	}

	/**
	 * @return    string
	 */
	protected function label()
	{
		return '通知マスタ';
	}

	/**
	 * @return    array<string,string>
	 */
	protected function labels()
	{
		return array(
			'name' => $this->label(),
			'singular_name' => $this->label(),
			'all_items' => $this->label(),
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
	protected function is_public()
	{
		return false;
	}

	/**
	 * @return    bool
	 */
	protected function show_ui()
	{
		return true;
	}

	/**
	 * @return    bool|string
	 */
	protected function show_in_menu()
	{
		return 'edit.php?post_type=brick';
	}

	/**
	 * @return    string[]|false
	 */
	protected function supports()
	{
		return array(
			'title',
		);
	}
}
