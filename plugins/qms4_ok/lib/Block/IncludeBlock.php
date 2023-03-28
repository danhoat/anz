<?php

namespace QMS4\Block;

use Arkhe;


class IncludeBlock
{
	/** @var    string */
	private $name = 'include';

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
		$initialized = $attributes[ 'initialized' ];

		if ( ! $initialized ) { return '<div />'; }

		$filepath = $attributes[ 'filepath' ] ?? '';
		$filepath = trim( $filepath, " \t\n\r\0\x0B/" );
		$filepath = preg_replace( '/\.php$/', '', $filepath );

		ob_start();

		if ( method_exists( 'Arkhe', 'get_part' ) ) {
			echo Arkhe::get_part( $filepath );
		} elseif (
			( $inc_path = get_theme_file_uri( 'template-parts/' . $filepath . '.php' ) )
			&& file_exists( $inc_path )
		) {
			require( $inc_path );
		} else {
			echo "<p>インクルードファイルが見つかりません。</p>";
		}

		return ob_get_clean();
	}
}
