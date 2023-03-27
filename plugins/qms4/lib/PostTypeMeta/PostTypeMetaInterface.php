<?php

namespace QMS4\PostTypeMeta;

use QMS4\TaxonomyMeta\TaxonomyMeta;


interface PostTypeMetaInterface
{
	/**
	 * @return    int
	 */
	public function id(): int;

	/**
	 * @return    string
	 */
	public function name(): string;

	/**
	 * @return    string
	 */
	public function label(): string;

	/**
	 * @return    bool
	 */
	public function is_public(): bool;

	/**
	 * リビジョン保存数
	 *
	 * @return    int
	 */
	public function revisions_to_keep(): int;

	/**
	 * @return    string
	 */
	public function permalink_type(): string;

	/**
	 * @return    string
	 */
	public function func_type(): string;

	/**
	 * @return    string
	 */
	public function reserve_url(): string;

	/**
	 * @return    string
	 */
	public function editor(): string;

	/**
	 * @return    int
	 */
	public function cal_base_date(): int;

	/**
	 * @return    string[]
	 */
	public function components(): array;

	/**
	 * @return    TaxonomyMeta[]
	 */
	public function taxonomies(): array;

	/**
	 * @return    string    'default' | 'card' | 'list' | 'text'
	 */
	public function archive_layout(): string;

	/**
	 * @return    string
	 */
	public function orderby(): string;

	/**
	 * @return    string
	 */
	public function order(): string;

	/**
	 * @return    int
	 */
	public function new_date(): int;

	/**
	 * @return    string
	 */
	public function new_class(): string;

	/**
	 * @return    bool|null
	 */
	public function show_sidebar_archive(): ?bool;

	/**
	 * @return    bool|null
	 */
	public function show_sidebar_single(): ?bool;

	/**
	 * @return    int|null
	 */
	public function posts_per_page(): ?int;

	/**
	 * @return    int
	 */
	public function term_html();
}
