<?php

namespace QMS4\Item\Post;

use QMS4\Item\Post\AbstractPost;


class Attachment extends AbstractPost
{
	/**
	 * @return    string
	 */
	public function __toString(): string
	{
		return $this->url;
	}


	/**
	 * ファイル名
	 *
	 * @param    bool    $remove_extension
	 * @return    array<string,string>
	 */
	protected function _filename(): array
	{
		$filepath = get_attached_file( $this->_wp_post->ID );

		return pathinfo( $filepath );
	}

	/**
	 * @param    array<string,string>
	 * @param    bool    $remove_extension
	 * @return    string
	 */
	protected function __filename(
		array $value,
		bool $remove_extension = true
	): string
	{
		return $remove_extension ? $value[ 'basename' ] : $value[ 'filename' ];
	}


	/**
	 * @return    int
	 */
	protected function _filesize(): int
	{
		$filepath = get_attached_file( $this->_wp_post->ID );

		return filesize( $filepath );
	}

	/**
	 * @param    int
	 * @param    int|false    $size_format
	 * @return    string
	 */
	protected function __filesize(
		int $value,
		$size_format = 2
	): string
	{
		return $size_format === false
			? (string) $value
			: size_format( $value, $size_format );
	}


	/**
	 * @return    string
	 */
	protected function _url(): string
	{
		return wp_get_attachment_url( $this->_wp_post->ID );
	}
}
