<?php

namespace QMS4\MetaBox;

use QMS4\PostMeta\EventDate;
use QMS4\PostMeta\ParentEventId;


class EventStructure
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
	 * @param    \WP_Post    $wp_post
	 * @return    void
	 */
	public function add_meta_box( \WP_Post $wp_post ): void
	{
		add_meta_box(
			/* $id = */ 'qms4__event-structure',
			/* $title = */ '日程 基本情報',
			/* $callback = */ array( $this, 'render_meta_box' ),
			/* $screen = */ $this->post_type,
			/* $context = */ 'normal',
			/* $priority = */ 'high'
		);
	}

	/**
	 * @param    \WP_Post    $wp_post
	 */
	public function render_meta_box( \WP_Post $wp_post ): void
	{
		$event_date = EventDate::get_post_meta( $wp_post->ID );
		$parent_event_id = ParentEventId::get_post_meta( $wp_post->ID );

		require( QMS4_DIR . '/blocks/templates/event-structure.php' );
	}
}
