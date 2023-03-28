<?php

namespace QMS4\Block;


class UserVoice
{
	/** @var    string */
	private $name = 'user-voice';

	public function register()
	{
		register_block_type(
			QMS4_DIR . "/blocks/build/{$this->name}",
			array(
				// 'render_callback' => array( $this, 'render' ),
			)
		);

		register_block_type(
			QMS4_DIR . "/blocks/build/user-voice-media",
			array(
				// 'render_callback' => array( $this, 'render' ),
			)
		);

		register_block_type(
			QMS4_DIR . "/blocks/build/user-voice-content",
			array(
				// 'render_callback' => array( $this, 'render' ),
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
		ob_start();
		if ( file_exists( QMS4_DIR . "/blocks/templates/{$this->name}.php" ) ) {
			require( QMS4_DIR . "/blocks/templates/{$this->name}.php" );
		}
		return ob_get_clean();
	}
}
