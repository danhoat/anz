<?php

namespace QMS4\Item\Post;

use QMS4\Item\Post\AbstractPost;
use QMS4\Item\Post\Image;
use QMS4\Item\Post\NoImage;
use QMS4\Item\Util\Date;
use QMS4\Item\User\User;
use QMS4\PostMeta\Area;
use QMS4\Param\Param;

/**
 * @property-read   int    $id    投稿 ID
 * @property-read   string    $title    投稿タイトル
 *
 * @method    string    id()    投稿 ID
 * @method    string    title( string $title_length = -1, bool $slash_to_br = true )    投稿タイトル
 */
class Post extends AbstractPost
{

	public function __construct( \WP_Post $wp_post, Param $param )
	{
		$this->_wp_post = $wp_post;
		$this->_param = $param;
		// $this->set_post_date();
	}
	/**
	 * @return    Post|null
	 */
	protected function _area(): ?Post
	{
		$key = Area::KEY;
		return $this->$key;
	}

	/**
	 * @return    User
	 */
	protected function _author(): User
	{
		$wp_user = get_user_by( 'ID', $this->_wp_post->post_author );

		return new User( $wp_user, $this->_param );
	}

	/**
	 * @return    string
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

	protected function __excerpt(
		int $length = 200
	): string
	{
		$excerpt = $this->_wp_post->post_excerpt;

		// HACK: インスタンス変数 ___excerpt_mblength 唐突に使っている。汚い
		$this->___excerpt_mblength = $length;
		add_filter( 'excerpt_mblength', array( $this, '___excerpt_mblength' ) );

		$excerpt = apply_filters( 'get_the_excerpt', $excerpt, $this->_wp_post );
		$excerpt = apply_filters( 'the_excerpt', $excerpt );

		remove_filter( 'excerpt_mblength', array( $this, '___excerpt_mblength' ) );

		return $excerpt;
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
	protected function __id(): int
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
	 * @param    int    $new_date
	 * @param    string    $new_class
	 */
	protected function __new_class(
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
	 * @param    string|null    $date_format
	 * @void    Date
	 */
	protected function set_post_date()
	{

		if( $this->_param[ 'date_format']){
			$this->_wp_post->post_date  =  wp_date($this->_param[ 'date_format'], strtotime($this->_wp_post->post_date));
		}
	}


	/**
	 * @param    string|null    $date_format
	 * @return    Date
	 */
	protected function __post_date(
		?string $date_format = null
	): Date
	{
		$date_format = $date_format ?: $this->_param[ 'date_format' ];
		return new Date( $this->_wp_post->post_date, null, $date_format);
	}

	protected function __date_html(
		?string $date_format = null
	): Date
	{
		$date_format = $date_format ?: $this->_param[ 'date_format' ];

		return new Date( $this->_wp_post->post_date, null, $date_format);
	}

	protected function __date_format() : string
	{
		$date_format =  $this->_param[ 'date_format' ];

		return $date_format;
	}

	/**
	 * @param    string|null    $date_format
	 * @return    Date
	 */
	protected function __post_modified(
		?string $date_format = null
	): Date
	{
		$date_format = $date_format ?: $this->_param[ 'date_format' ];

		return new Date( $this->_wp_post->post_modified, null, $date_format);
	}

	/**
	 * @return    string
	 */
	protected function __post_status(): string
	{
		return $this->_wp_post->post_status;
	}

	/**
	 * @return    string
	 */
	protected function __post_type(): string
	{
		return $this->_wp_post->post_type;
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
			? $this->_wp_post->post_name
			: urldecode( $this->_wp_post->post_name );
	}

	/**
	 * @param    int    $title_length
	 * @param    bool    $slash_to_br
	 * @return    string
	 */
	protected function __title(
		int $title_length = -1,
		bool $slash_to_br = true
	): string
	{
		$title = $this->_wp_post->post_title;

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
