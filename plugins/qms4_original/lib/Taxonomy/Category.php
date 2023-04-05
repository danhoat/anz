<?php

namespace QMS4\Taxonomy;

use QMS4\PostTypeMeta\PostTypeMeta;
use QMS4\Taxonomy\Taxonomy;


class Category extends Taxonomy
{
	/** @var    PostTypeMeta */
	private $post_type_meta;

	/** @var    int */
	private $index;

	/**
	 * @param    PostTypeMeta    $post_type_meta
	 * @param    int    $index
	 */
	public function __construct( PostTypeMeta $post_type_meta, $index )
	{
		$this->post_type_meta = $post_type_meta;
		$this->index = $index;
	}

	/**
	 * @return   string
	 */
	protected function name(): string
	{
		$taxonomies = $this->post_type_meta->taxonomies();
		$taxonomy = $taxonomies[ $this->index ];

		return $taxonomy->taxonomy();
	}

	/**
	 * @return    string
	 */
	protected function object_type(): string
	{
		return $this->post_type_meta->name();
	}

	/**
	 * @return    array<string,string>
	 */
	protected function labels(): array
	{
		$taxonomies = $this->post_type_meta->taxonomies();
		$taxonomy = $taxonomies[ $this->index ];

		$label = $taxonomy->label();
		$object_label = $this->post_type_meta->label();

		return array(
			'name' => "{$object_label} {$label}",
			'singular_name' => "{$object_label} {$label}",
			'menu_name' => "{$label} 編集",
			'edit_item' => "{$object_label} {$label}を編集",
			'add_new_item' => "新規{$label}を追加",
			'parent_item' => "親{$label}",
			'search_items' => "{$label}検索",
		);
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
	protected function show_in_rest(): bool
	{
		return true;
	}

	/**
	 * @return    bool
	 */
	protected function show_tagcloud(): bool
	{
		return false;
	}

	/**
	 * @return    bool
	 */
	protected function show_admin_column(): bool
	{
		return true;
	}

		/**
	 * @return    array<string,string>
	 */
	protected function capabilities(): array
	{
		$id = $this->post_type_meta->id();
		$object_name = $this->post_type_meta->name();

		return array(
			'manage_terms' => "{$object_name}_{$id}__manage_terms",
			'edit_terms' => "{$object_name}_{$id}__edit_terms",
			'delete_terms' => "{$object_name}_{$id}__delete_terms",
			'assign_terms' => "{$object_name}_{$id}__assign_terms",
		);
	}
}
