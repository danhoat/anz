<?php

namespace QMS4\Item\Term;

use QMS4\Item\Term\AbstractTerm;
use QMS4\PostMeta\TermColor;


/**
 * @property-read   string|null    $color    色
 * @property-read   int    $count    タームと紐づいている記事数
 * @property-read   string    $description    タームディスクリプション
 * @property-read   int    $id    ターム ID
 * @property-read   string    $name    名前
 * @property-read   string    $slug    スラッグ
 * @property-read   int    $order    管理画面での並び順
 * @property-read   self|null    $parent    親ターム
 * @property-read   int    $parent_id    親ターム ID
 * @property-read   string    $taxonomy    タクソノミー名
 *
 * @method    string|null    color()    色
 * @method    int    count()    タームと紐づいている記事数
 * @method    string    description()    タームディスクリプション
 * @method    int    id()    ターム ID
 * @method    string    name()    名前
 * @method    string    slug( bool $urlencode = false )    スラッグ
 * @method    int    order()    管理画面での並び順
 * @method    self|null    parent()    親ターム
 * @method    int    parent_id()    親ターム ID
 * @method    string    taxonomy()    タクソノミー名
 */
class Term extends AbstractTerm
{
	/**
	 * @param    array<string,mixed>    $param
	 * @return    self
	 */
	public function ___inject( array $param ): self
	{
		$new_param = $this->_param->inject( $param );

		return new self( $this->_wp_term, $new_param, $this->_memo );
	}

	// ====================================================================== //

	/**
	 * @return    string|null
	 */
	protected function _color(): ?string
	{
		return TermColor::get_post_meta( $this->_wp_term->term_id );
	}


	/**
	 * @return    int
	 */
	protected function _count(): int
	{
		return $this->_wp_term->count;
	}


	/**
	 * @return    string
	 */
	protected function _description(): string
	{
		return $this->_wp_term->description;
	}


	/**
	 * @return    int
	 */
	protected function _id(): int
	{
		return $this->_wp_term->term_id;
	}


	/**
	 * @return    string
	 */
	protected function _name(): string
	{
		return $this->_wp_term->name;
	}


	/**
	 * @return    string
	 */
	protected function _slug(): string
	{
		return urldecode( $this->_wp_term->slug );
	}

	/**
	 * @param    string    $value
	 * @param    bool    $urlencode
	 * @return    string
	 */
	protected function __slug( string $value, bool $urlencode = false ): string
	{
		return $urlencode ? urlencode( $value ) : $value;
	}


	/**
	 * @return    int
	 */
	protected function _order(): int
	{
		return (int) $this->_wp_term->term_order;
	}

	/**
	 * @return    self|null
	 */
	protected function _parent(): ?self
	{
		$parent_id = $this->_wp_term->parent;

		if ( ! $parent_id ) { return null; }

		$wp_term = get_term( $parent_id, $this->_wp_term->taxonomy );

		return new self( $wp_term, $this->_param );
	}

	/**
	 * @return    int
	 */
	protected function _parent_id(): int
	{
		return $this->_wp_term->parent;
	}

	/**
	 * @return    string
	 */
	protected function _taxonomy(): string
	{
		return $this->_wp_term->taxonomy;
	}
}
