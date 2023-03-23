<?php
declare(strict_types=1);

namespace QMS3\BrickAdmin\PostType;

use QMS3\BrickAdmin\PostType\PostType;


class BrickPostType extends PostType
{
    /**
	 * @return    string
	 */
	protected function name()
    {
        return 'brick';
    }

	/**
	 * @return    string
	 */
	protected function label()
    {
        return 'フォーム';
    }

	/**
	 * @return    array<string,string>
	 */
	protected function labels()
    {
        return array(
            'name' => 'フォーム',
            'singular_name' => 'フォーム',
            'all_items' => 'フォーム 一覧',
            'add_new' => 'フォーム 追加',
            'add_new_item' => '新規登録',
            'edit_item' => '編集',
            'new_item' => 'フォーム追加',
            'view_item' => '詳細を表示',
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
	 * @return    bool
	 */
	protected function show_in_rest()
	{
		return true;
	}

    /**
	 * @return    int|null
	 */
	protected function menu_position()
	{
		return 55;
	}

    /**
	 * @return    string|null
	 */
	protected function menu_icon()
	{
		return 'dashicons-list-view';
	}

    /**
	 * @return    string|string[]
	 */
	protected function capability_type()
	{
		return array( 'brick', 'bricks' );
	}

    /**
	 * @return    array<string,string>
	 */
	protected function capabilities()
	{
		return array(
            'create_posts' => 'create_bricks',
        );
	}

    /**
	 * @return    bool|null
	 */
	protected function map_meta_cap()
	{
		return true;
	}

    /**
	 * @return    string[]|false
	 */
	protected function supports()
	{
		return array(
            'title',
            'revisions',
        );
	}

    /**
	 * @return    bool|array<string,mixed>
	 */
	protected function rewrite()
	{
		return false;
	}

    /**
	 * @return    bool|null
	 */
	protected function delete_with_user()
	{
		return false;
	}
}
