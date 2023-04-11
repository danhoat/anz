<?php

namespace QMS4\Item\Post;

use QMS4\Item\Post\AbstractPost;


class Image extends AbstractPost
{
	/**
	 * @return    string
	 */
	public function __toString(): string
	{
		return $this->html;
	}

	/**
	 * @return    string
	 */
	protected function _alt(): string
	{
		return get_post_meta( $this->_wp_post->ID, '_wp_attachment_image_alt', /* $single = */ true );
	}


	/**
	 * @return    string
	 */
	protected function _caption(): string
	{
		return $this->_wp_post->post_excerpt;
	}


	/**
	 * @return    string
	 */
	protected function _description(): string
	{
		return $this->_wp_post->post_content;
	}

	/**
	 * @param    array<string,string>    $value
	 * @param    bool    $remove_extension
	 */
	protected function __filename(
		string $filepath,
		bool $remove_extension = true
	)
	{
		$pathinfo = pathinfo( $filepath );

		return $remove_extension ? $pathinfo[ 'filename' ] : $pathinfo[ 'basename' ];
	}


	/**
	 * @return    string
	 */
	protected function _filepath(): string
	{
		return get_attached_file( $this->_wp_post->ID );
	}


	/**
	 * @return    int
	 */
	protected function _filesize(): int
	{
		return filesize( get_attached_file( $this->_wp_post->ID ) );
	}

	/**
	 * @param    string    $image_size
	 * @return    int|null
	 */
	protected function __height(
		string $image_size = 'full'
	): ?int
	{
		$image = image_downsize( $this->_wp_post->ID, $image_size );

		return isset( $image[ 2 ] ) ? $image[ 2 ] : null;
	}

	/**
	 * @param    string    $image_size
	 * @return    string
	 */
	protected function __html(
		string $image_size = 'full'
	): string
	{
		return wp_get_attachment_image( $this->_wp_post->ID, $image_size );
	}


	/**
	 * @return    bool
	 */
	protected function _is_no_image(): bool
	{
		return false;
	}


	/**
	 * @return    string
	 */
	protected function _mime_type(): string
	{
		return get_post_mime_type( $this->_wp_post->ID );
	}


	/**
	 * @param    string    $image_size
	 * @return    string
	 */
	protected function __src(
		string $image_size = 'full'
	): string
	{
		$image_src = wp_get_attachment_image_src( $this->_wp_post->ID, $image_size );

		return $image_src[0];
	}


	/**
	 * @return    string
	 */
	protected function _title(): string
	{
		return $this->_wp_post->post_title;
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
	 * @param    string    $image_size
	 * @return    int|null
	 */
	protected function __width(
		string $image_size = 'full'
	): ?int
	{
		$image = image_downsize( $this->_wp_post->ID, $image_size );

		return isset( $image[ 1 ] ) ? $image[ 1 ] : null;
	}
}
