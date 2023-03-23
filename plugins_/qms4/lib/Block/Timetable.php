<?php

namespace QMS4\Block;

use QMS4\PostMeta\Timetable as TimetablePostMeta;
use QMS4\PostMeta\TimetableButtonText;
use QMS4\PostTypeMeta\PostTypeMetaFactory;


class Timetable
{
	/** @var    string */
	private $name = 'timetable';

	/**
	 * @return    void
	 */
	public function register(): void
	{
		register_block_type(
			__DIR__ . "/../../blocks/build/{$this->name}",
			array(
				'render_callback' => array( $this, 'render' ),
			)
		);
	}

	/**
	 * @param   array<string,mixed>    $attributes
	 * @param   string|null    $inner_content
	 * @return    string
	 */
	public function render( array $attributes, $inner_content ): string
	{
		global $post;

		$post_id = $post->ID;
		$post_type = $post->post_type;

		$factory = new PostTypeMetaFactory();
		$post_type_meta = $factory->from_name( array( $post_type ) );
		$reserve_url = home_url( $post_type_meta->reserve_url() );

		$timetable = get_post_meta( $post->ID, TimetablePostMeta::KEY, /* $single = */ true );
		$button_text = get_post_meta( $post->ID, TimetableButtonText::KEY, /* $single = */ true );

		if ( isset( $_GET[ 'ymd' ] ) && preg_match( '/^\d{8}$/', $_GET[ 'ymd' ] ) ) {
			$year = substr( $_GET[ 'ymd' ], 0, 4 );
			$month = substr( $_GET[ 'ymd' ], 4, 2 );
			$day = substr( $_GET[ 'ymd' ], 6 );
			$ymd = "{$year}-{$month}-{$day}";
		} else {
			$ymd = null;
		}

		ob_start();
		if ( file_exists( QMS4_DIR . "/blocks/templates/{$this->name}.php" ) ) {
			require( QMS4_DIR . "/blocks/templates/{$this->name}.php" );
		}
		return ob_get_clean();
	}

	// ====================================================================== //

	public function enqueue_script(): void
	{
		$asset_file = require( QMS4_DIR . '/blocks/build/timetable/view.asset.php' );

		wp_enqueue_script(
			'qms4__timetable',
			plugins_url( '../../blocks/build/timetable/view.js', __FILE__ ),
			$asset_file['dependencies'],
			$asset_file['version'],
			true
		);
	}
}
