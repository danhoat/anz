<?php

namespace QMS4\MetaBox;

use QMS4\Fetch\EventSchedules;
use QMS4\PostMeta\EventOverwriteEnable;
use QMS4\PostMeta\EventOverwriteTitle;
use QMS4\PostMeta\ParentEventId;
use QMS4\PostMeta\Timetable;
use QMS4\PostMeta\TimetableButtonText;


class EventOverwrite
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

	// ====================================================================== //

	/**
	 * @param    \WP_Post    $wp_post
	 * @return    void
	 */
	public function add_meta_box( \WP_Post $wp_post ): void
	{
		add_meta_box(
			/* $id = */ 'qms4__event-overwrite',
			/* $title = */ 'イベントタイトル・タイムテーブル',
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
		$overwrite_enable = EventOverwriteEnable::get_post_meta( $wp_post->ID );

		$title = EventOverwriteTitle::get_post_meta( $wp_post->ID );
		$timetable = Timetable::get_post_meta( $wp_post->ID );
		$button_text = TimetableButtonText::get_post_meta( $wp_post->ID );

		$parent_event_id = ParentEventId::get_post_meta( $wp_post->ID );

		$parent_title = get_the_title( $parent_event_id );
		$parent_timetable = Timetable::get_post_meta( $parent_event_id );
		$parent_button_text = TimetableButtonText::get_post_meta( $parent_event_id );

		$fetch_schedules = new EventSchedules();
		$schedules = $fetch_schedules->fetch( $parent_event_id, true );


		$asset_file = require( QMS4_DIR . '/blocks/build/event-overwrite/index.asset.php' );

		$handle = 'qms4__event-overwrite';

		wp_register_script(
			/* $handle = */ $handle,
			/* $src = */ plugins_url( '../../blocks/build/event-overwrite/index.js', __FILE__ ),
			/* $deps = */ $asset_file[ 'dependencies' ],
			/* $ver = */ $asset_file[ 'version' ],
			/* $in_footer = */ true
		);

		wp_localize_script(
			$handle,
			'QMS4__META_BOX__EVENT_OVERWRITE',
			array(
				'overwriteEnable' => $overwrite_enable,
				'title' => $title,
				'timetable' => $timetable,
				'buttonText' => $button_text,
				'parentTitle' => $parent_title,
				'parentTimetable' => $parent_timetable,
				'parentButtonText' => $parent_button_text,
			)
		);

		wp_enqueue_script( $handle );


		wp_enqueue_style(
			$handle,
			plugins_url( '../../blocks/build/event-overwrite/index.css', __FILE__ ),
			array(),
			date( 'His' )
		);


		echo '<div id="qms4__event-overwrite__container" class="qms4__event-overwrite__container"></div>';
	}

	/**
	 * @param    int    $post_id
	 * @param    \WP_Post    $wp_post
	 * @return    void
	 */
	public function save_post( int $post_id, \WP_Post $wp_post ): void
	{
		// 自動保存なら何もしない
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) { return; }

		// 権限チェック
		if ( ! current_user_can( 'edit_post', $wp_post->ID ) ) { return; }

		if ( empty( $_POST[ 'qms4__event__event-overwrite' ] ) ) {
			return;
		}

		$overwrite_json = stripslashes( $_POST[ 'qms4__event__event-overwrite' ] );
		$overwrite = json_decode( $overwrite_json, true );

		EventOverwriteEnable::update_post_meta( $post_id, $overwrite[ 'overwriteEnable' ] );
		EventOverwriteTitle::update_post_meta( $post_id, $overwrite[ 'title' ] );
		Timetable::update_post_meta( $post_id, $overwrite[ 'timetable' ] );
		TimetableButtonText::update_post_meta( $post_id, $overwrite[ 'buttonText' ] );
	}
}
