<?php

namespace QMS4\Item\Post;

use QMS4\Item\Post\AbstractPost;
use QMS4\Item\Post\Image;
use QMS4\Item\Post\NoImage;
use QMS4\Item\Util\Date;
use QMS4\Item\User\User;

/**
 * @property-read   int    $id    投稿 ID
 * @property-read   string    $title    投稿タイトル
 *
 * @method    string    id()    投稿 ID
 * @method    string    title( string $title_length = -1, bool $slash_to_br = true )    投稿タイトル
 */
class Post extends AbstractPost
{
	/**
	 * @return    User
	 */
	protected function _author(): User
	{
		$wp_user = get_user_by( 'ID', $this->_wp_post->post_author );

		return new User( $wp_user, $this->_param );
	}


	/**
	 * @return    atring
	 */
	protected function _content(): string
	{
		$content = get_the_content( null, null, $this->_wp_post->ID );

		remove_filter( 'the_content', 'wpautop' );

		$content = do_shortcode( $content );
		$content = apply_filters( 'the_content', $content );
		$content = str_replace( ']]>', ']]&gt;', $content );

		add_filter( 'the_content', 'wpautop' );

		return $content;
	}


	/**
	 * @return    string
	 */
	protected function _excerpt(): string
	{
		return $this->_wp_post->post_excerpt;
	}

	protected function __excerpt(
		string $value,
		int $length = 200
	): string
	{
		// HACK: インスタンス変数 ___excerpt_mblength 唐突に使っている。汚い
		$this->___excerpt_mblength = $length;
		add_filter( 'excerpt_mblength', array( $this, '___excerpt_mblength' ) );

		$value = apply_filters( 'get_the_excerpt', $value, $this->_wp_post );
		$value = apply_filters( 'the_excerpt', $value );

		remove_filter( 'excerpt_mblength', array( $this, '___excerpt_mblength' ) );

		return $value;
	}

	/**
	 * @see    https://eastcoder.com/code/wp-multibyte-patch/
	 *
	 * @param    int    $length
	 * @return    int
	 */
	public function ___excerpt_mblength( int $length ): int
	{
		return $this->___excerpt_mblength;
	}


	/**
	 * @return    int
	 */
	protected function _id(): int
	{
		return $this->_wp_post->ID;
	}


	/**
	 * @return    Image|NoImage
	 */
	protected function _img()
	{
		$has_thumbnail = has_post_thumbnail( $this->_wp_post->ID );
		$thumbnail_id = get_post_thumbnail_id( $this->_wp_post->ID );

		if ( $has_thumbnail && $thumbnail_id > 0 ) {
			$wp_post = get_post( $thumbnail_id );
			return new Image( $wp_post, $this->_param );
		} else {
			return new NoImage( $this->_param );
		}
	}


	/**
	 * @param    null    $_
	 * @param    int    $new_date
	 * @param    string    $new_class
	 */
	protected function __new_class(
		$_,
		int $new_date = 5,
		string $new_class = 'new'
	): string
	{
		$tz = wp_timezone();

		$post_date = new \DateTimeImmutable( $this->_wp_post->post_date, $tz );

		$now = new \DateTimeImmutable( 'now', $tz );
		$interval = new \DateInterval( "P{$new_date}D" );

		return $now->sub( $interval )->format( 'Y-m-d' ) < $post_date->format( 'Y-m-d' )
			? $new_class
			: '';
	}


	/**
	 * @return    string
	 */
	protected function _permalink(): string
	{
		return get_permalink( $this->_wp_post );
	}

	/**
	 * @param    string    $value
	 * @param    bool    $urlencode
	 * @return    string
	 */
	protected function __permalink(
		string $value,
		bool $urlencode = true
	): string
	{
		if ( $urlencode ) {
			// 全角文字のみ URL エンコードする
			$value = preg_replace_callback(
				'/[^\\x01-\\x7E]+/u',
				function ( array $matches ): string {
					return urlencode( $matches[ 0 ] );
				},
				$value
			);
		} else {
			$value = urldecode( $value );
		}

		return $value;
	}


	/**
	 * @return    Date
	 */
	protected function _post_date(): Date
	{
		$tz = wp_timezone();

		$date_format = isset( $this->_param[ 'date_format' ] )
			? $this->_param[ 'date_format' ]
			: 'Y/m/d';

		return new Date( $this->_wp_post->post_date, $tz, $date_format);
	}


	/**
	 * @return    Date
	 */
	protected function _post_modified(): Date
	{
		$tz = wp_timezone();

		$date_format = isset( $this->_param[ 'date_format' ] )
			? $this->_param[ 'date_format' ]
			: 'Y/m/d';

		return new Date( $this->_wp_post->post_modified, $tz, $date_format);
	}


	/**
	 * @return    string
	 */
	protected function _post_status(): string
	{
		return $this->_wp_post->post_status;
	}


	/**
	 * @return    string
	 */
	protected function _post_type(): string
	{
		return $this->_wp_post->post_type;
	}


	/**
	 * @return    string
	 */
	protected function _title(): string
	{
		return $this->_wp_post->post_title;
	}

	/**
	 * @param    string    $value
	 * @param    int    $title_length
	 * @param    bool    $slash_to_br
	 * @return    string
	 */
	protected function __title(
		string $value,
		int $title_length = -1,
		bool $slash_to_br = true
	): string
	{
		if ($title_length >= 0) {
			$value = mb_strimwidth($value, 0, $title_length * 2);
		}

		$value = $slash_to_br
			? str_replace("//", "<br>\n", $value)
			: str_replace("//", "", $value);

		return $value;
	}
}
