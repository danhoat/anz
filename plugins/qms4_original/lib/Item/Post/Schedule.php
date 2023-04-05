<?php

namespace QMS4\Item\Post;

use QMS4\Item\Post\AbstractPost;
use QMS4\Item\Post\Image;
use QMS4\Item\Post\NoImage;
use QMS4\Item\Post\Post;
use QMS4\Item\Util\Date;
use QMS4\Item\User\User;
use QMS4\Item\Util\Items;
use QMS4\PostMeta\Area;
use QMS4\PostMeta\EventDate;
use QMS4\PostMeta\EventOverwriteEnable;
use QMS4\PostMeta\ParentEventId;
use QMS4\PostMeta\Timetable;
use QMS4\PostMeta\TimetableButtonText;


/**
 * @property-read    string    $date    イベント開催日
 * @property-read    Post    $event    親イベント投稿
 * @property-read    int    $id    投稿 ID
 * @property-read    Image|NoImage    $img    アイキャッチ画像
 * @property-read    string    $permalink    パーマリンク
 * @property-read    Items    $timetable    タイムテーブル
 * @property-read    string    $timetable_button_text    タイムテーブルのエントリーボタンテキスト
 * @property-read    string    $title    イベントタイトル
 */
class Schedule extends AbstractPost
{
	/**
	 * @return    bool
	 */
	protected function _active(): bool
	{
		return $this->_wp_post->post_status === 'publish';
	}

	/**
	 * @return    Post|null
	 */
	protected function _area(): ?Post
	{
		$key = Area::KEY;
		return $this->event->$key;
	}

	/**
	 * @return    string
	 */
	protected function _date(): string
	{
		$date_str = get_post_meta( $this->_wp_post->ID, EventDate::KEY, /* $single = */ true );

		if ( empty( $date_str ) ) {
			throw new \RuntimeException();
		}

		return $date_str;
	}

	/**
	 * @param    string|null    $date_format
	 * @return    Date
	 */
	protected function __date(
		string $date,
		?string $date_format = null
	): Date
	{
		return new Date( $date, null, $date_format );
	}

	/**
	 * @return    string
	 */
	protected function __date_str(
		string $date
	): string
	{
		return $date;
	}

	/**
	 * @return    Post
	 */
	protected function _event(): Post
	{
		return qms4_detail( $this->id, $this->_param->to_array() );
	}

	/**
	 * @return    int
	 */
	protected function _id(): int
	{
		$parent_id = get_post_meta( $this->_wp_post->ID, ParentEventId::KEY, /* $single = */ true );

		if ( empty( $parent_id ) ) {
			throw new \RuntimeException();
		}

		return $parent_id;
	}

	/**
	 * @return    Image|NoImage
	 */
	protected function _img()
	{
		$has_thumbnail = has_post_thumbnail( $this->event->id );
		$thumbnail_id = get_post_thumbnail_id( $this->event->id );

		if ( $has_thumbnail && $thumbnail_id > 0 ) {
			$wp_post = get_post( $thumbnail_id );
			return new Image( $wp_post, $this->_param );
		} else {
			return new NoImage( $this->_param );
		}
	}

	/**
	 * @return    bool
	 */
	protected function _overwrite(): bool
	{
		$value = get_post_meta( $this->_wp_post->ID, EventOverwriteEnable::KEY, /* $signle = */ true );

		return is_string( $value ) && empty( $value ) ? false : $value;
	}

	/**
	 * @return    string
	 */
	protected function _permalink(): string
	{
		return get_permalink( $this->event->id );
	}

	/**
	 * @param    string    $permalink
	 * @param    bool    $urlencode    結果を URL エンコードするかどうか
	 * @return    string
	 */
	protected function __permalink(
		string $permalink,
		bool $urlencode = true
	): string
	{
		if ( $urlencode ) {
			// 全角文字のみ URL エンコードする
			$permalink = preg_replace_callback(
				'/[^\\x01-\\x7E]+/u',
				function ( array $matches ): string {
					return urlencode( $matches[ 0 ] );
				},
				$permalink
			);
		} else {
			$permalink = urldecode( $permalink );
		}

		return $permalink;
	}

	/**
	 * @return    int
	 */
	protected function __schedule_id(): int
	{
		return $this->_wp_post->ID;
	}

	/**
	 * @param    bool    $urlencode
	 * @return    string
	 */
	protected function __slug(
		$urlencode = false
	): string
	{
		return $urlencode
			? $this->event->post_name
			: urldecode( $this->event->post_name );
	}

	/**
	 * @return    Items
	 */
	protected function _timetable(): Items
	{
		if ( $this->overwrite ) {
			$timetable = get_post_meta( $this->_wp_post->ID, Timetable::KEY, /* $single = */ true );

			if ( ! empty( $timetable ) ) {
				return new Items( $timetable, $this->_param );
			}
		}

		$timetable = get_post_meta( $this->event->id, Timetable::KEY, /* $single = */ true );

		return new Items( $timetable ?: array(), $this->_param );
	}

	/**
	 * @return    string
	 */
	protected function _timetable_button_text(): string
	{
		$button_text = get_post_meta( $this->_wp_post->ID, TimetableButtonText::KEY, /* $single = */ true );

		if ( ! empty( $button_text ) ) { return $button_text; }

		return get_post_meta( $this->event->id, TimetableButtonText::KEY, /* $single = */ true );
	}

	/**
	 * @return    string
	 */
	protected function _title(): string
	{
		if ( $this->overwrite ) {
			$title = trim( $this->_wp_post->post_title );

			if ( ! empty( $title ) ) {
				return $title;
			}
		}

		return $this->event->title;
	}

	/**
	 * @param    string    $title
	 * @param    int    $title_length
	 * @param    bool    $slash_to_br
	 * @return    string
	 */
	protected function __title(
		$title,
		int $title_length = -1,
		bool $slash_to_br = true
	): string
	{
		if ($title_length >= 0) {
			$title = mb_strimwidth($title, 0, $title_length * 2);
		}

		$title = $slash_to_br
			? str_replace("//", "<br>\n", $title)
			: str_replace("//", "", $title);

		return $title;
	}

	/**
	 * @return    \WP_Post
	 */
	protected function __wp_post(): \WP_post
	{
		return $this->_wp_post;
	}
}
