<?php

namespace QMS4\Action\PostMeta;

use QMS4\PostTypeMeta\PostTypeMeta;


class EnqueueEventStyle
{
	/** @var    string */
	private $post_type;

	/**
	 * @param    string    $post_type
	 */
	public function __construct( string $post_type )
	{
		$this->post_type = $post_type;
	}

	/**
	 * @param    string    $hook_suffix
	 * @return    void
	 */
	public function __invoke( string $hook_suffix )
	{
		global $post_type;

		if ( $this->post_type !== $post_type ) { return; }

		wp_enqueue_style(
			'qms4__event__schedule',
			plugins_url( '../../../blocks/build/schedules/index.css', __FILE__ )
		);
	}
}
