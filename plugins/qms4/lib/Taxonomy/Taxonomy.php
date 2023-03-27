<?php

namespace QMS4\Taxonomy;


abstract class Taxonomy
{
	/**
	 * @return void
	 */
	final public function register()
	{
		register_taxonomy(
			$this->name(),
			$this->object_type(),
			array(
				'labels' => $this->labels(),
				'description' => $this->description(),
				'public' => $this->public(),
				'publicly_queryable' => $this->publicly_queryable(),
				'hierarchical' => $this->hierarchical(),
				'show_ui' => $this->show_ui(),
				'show_in_menu' => $this->show_in_menu(),
				'show_in_nav_menus' => $this->show_in_nav_menus(),
				'show_in_rest' => $this->show_in_rest(),
				'rest_base' => $this->rest_base(),
				'rest_namespace' => $this->rest_namespace(),
				'rest_controller_class' => $this->rest_controller_class(),
				'show_tagcloud' => $this->show_tagcloud(),
				'show_in_quick_edit' => $this->show_in_quick_edit(),
				'show_admin_column' => $this->show_admin_column(),
				'meta_box_cb' => $this->meta_box_cb(),
				'meta_box_sanitize_cb' => $this->meta_box_sanitize_cb(),
				'capabilities' => $this->capabilities(),
				'rewrite' => $this->rewrite(),
				'query_var' => $this->query_var(),
				'update_count_callback' => $this->update_count_callback(),
				'default_term' => $this->default_term(),
				'sort' => $this->sort(),
			)
		);
	}

	// ====================================================================== //

	/**
	 * @return   string
	 */
	abstract protected function name(): string;

	/**
	 * @return    string
	 */
	abstract protected function object_type(): string;

	/**
	 * @return    array<string,string>
	 */
	abstract protected function labels(): array;

	/**
	 * @return    string
	 */
	protected function description(): string
	{
		return '';
	}

	/**
	 * @return    bool
	 */
	protected function public(): bool
	{
		return true;
	}

	/**
	 * @return    bool
	 */
	protected function publicly_queryable(): bool
	{
		return $this->public();
	}

	/**
	 * @return    bool
	 */
	protected function hierarchical(): bool
	{
		return false;
	}

	/**
	 * @return    bool
	 */
	protected function show_ui(): bool
	{
		return $this->public();
	}

	/**
	 * @return    bool
	 */
	protected function show_in_menu(): bool
	{
		return $this->show_ui();
	}

	/**
	 * @return    bool
	 */
	protected function show_in_nav_menus(): bool
	{
		return $this->public();
	}

	/**
	 * @return    bool
	 */
	protected function show_in_rest(): bool
	{
		return false;
	}

	/**
	 * @return    string
	 */
	protected function rest_base(): string
	{
		return $this->name();
	}

	/**
	 * @return    string
	 */
	protected function rest_namespace(): string
	{
		return 'wp/v2';
	}

	/**
	 * @return    string
	 */
	protected function rest_controller_class(): string
	{
		return 'WP_REST_Terms_Controller';
	}

	/**
	 * @return    bool
	 */
	protected function show_tagcloud(): bool
	{
		return $this->show_ui();
	}

	/**
	 * @return   bool
	 */
	protected function show_in_quick_edit(): bool
	{
		return $this->show_ui();
	}

	/**
	 * @return    bool
	 */
	protected function show_admin_column(): bool
	{
		return false;
	}

	/**
	 * @return    callable
	 */
	protected function meta_box_cb()
	{
		return $this->hierarchical()
			? 'post_categories_meta_box'
			: 'post_tags_meta_box';
	}

	/**
	 * @return    callable
	 */
	protected function meta_box_sanitize_cb()
	{
		$meta_box_cb = $this->meta_box_cb();

		switch ( $meta_box_cb ) {
			case 'post_categories_meta_box':
				$args['meta_box_sanitize_cb'] = 'taxonomy_meta_box_sanitize_cb_checkboxes';
				break;

			case 'post_tags_meta_box':
			default:
				$args['meta_box_sanitize_cb'] = 'taxonomy_meta_box_sanitize_cb_input';
				break;
		}
	}

	/**
	 * @return    array<string,string>
	 */
	protected function capabilities(): array
	{
		return array(
			'manage_terms' => 'manage_categories',
			'edit_terms' => 'manage_categories',
			'delete_terms' => 'manage_categories',
			'assign_terms' => 'edit_posts',
		);
	}

	/**
	 * @return    bool|array<string,mixed>
	 */
	protected function rewrite()
	{
		return true;
	}

	/**
	 * @return    string|bool
	 */
	protected function query_var()
	{
		return $this->name();
	}

	/**
	 * @return    callable
	 */
	protected function update_count_callback()
	{
		return '_update_post_term_count';
	}

	/**
	 * @return    string|array|null
	 */
	protected function default_term()
	{
		return null;
	}

	/**
	 * @return    bool
	 */
	protected function sort(): bool
	{
		return false;
	}
}
