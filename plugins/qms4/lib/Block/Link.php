<?php

namespace QMS4\Block;


class Link
{
	/** @var    string */
	private $name = 'link';

	public function register()
	{
		register_block_type(
			QMS4_DIR . "/blocks/build/{$this->name}",
			array(
				'render_callback' => array( $this, 'render' ),
			)
		);
	}

	/**
	 * @param    array<string,mixed>    $attributes
	 * @param    string|null    $content
	 * @return    string
	 */
	public function render( array $attributes, ?string $content ): string
	{
		// <a> タグはネスト出来ない！
		// $content の中の <a> タグを取り除く
		$content = preg_replace( '%<a [^>]*>%', '', $content );
		$content = str_replace( '</a>', '', $content );

		$url = $attributes['url'] ?? '';
		$target = $attributes['targetBlank'] ? 'target="_blank"' : '';

		return '<a class="qms4__link" href="' . $url . '" ' . $target . '>' . $content . '</a>';
	}
}
