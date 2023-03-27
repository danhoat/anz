<?php

namespace QMS4\MetaBox;

use QMS4\PostMeta\EventOverwriteEnable;
use QMS4\PostMeta\EventOverwriteTitle;
use QMS4\PostMeta\ParentEventId;
use QMS4\PostMeta\Timetable;
use QMS4\PostMeta\TimetableButtonText;
use QMS4\PostMeta\EventDate;


class EventSchedules
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
			/* $id = */ 'schedules',
			/* $title = */ '日程',
			/* $callback = */ array( $this, 'render_meta_box' ),
			/* $screen = */ $this->post_type,
			/* $context = */ 'side',
			/* $priority = */ 'high'
		);
	}

	/**
	 * @param    \WP_Post    $wp_post
	 */
	public function render_meta_box( \WP_Post $wp_post ): void
	{
		$asset_file = require( QMS4_DIR . '/blocks/build/schedules/index.asset.php' );

		wp_enqueue_script(
			'qms4__event__schedules',
			plugins_url( '../../blocks/build/schedules/index.js', __FILE__ ),
			$asset_file[ 'dependencies' ],
			$asset_file[ 'version' ],
			/* $in_footer = */ true
		);

		echo wp_nonce_field( __FILE__, 'nonce__qms4__event__schedules' ) . "\n";
		echo '<div id="qms4__event__schedules" class="qms4__event__schedules"></div>';
	}

	/**
	 * @param    int    $post_id
	 * @param    \WP_Post    $wp_post
	 * @return    void
	 */
	public function save_post( int $post_id, \WP_Post $wp_post ): void
	{
		// nonce チェック
		$nonce_key = 'nonce__qms4__event__schedules';
		if (
			! isset( $_POST[ $nonce_key ] )
			|| ! wp_verify_nonce( $_POST[ $nonce_key ], __FILE__ )
		) { return; }

		// 自動保存なら何もしない
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) { return; }

		// 権限チェック
		if ( ! current_user_can( 'edit_post', $wp_post->ID ) ) { return; }

		if ( empty( $_POST[ 'qms4__event__schedules' ] ) ) {
			// JSON 形式の値が送られてくる
			// もし送られてくる値が空配列だったとしても、JSON としての '[]' は空文字ではない
			// そのため empty() で値をチェックして問題無い

			http_response_code(400);  // 400 Bad Request
			exit();
		}

		$schedules_json = stripslashes( $_POST[ 'qms4__event__schedules' ] );
		$schedules = json_decode( $schedules_json );

		foreach ( $schedules as $i => $schedule ) {
			// 新しい日程を登録
			if ( is_null( $schedule->post_id ) && $schedule->active ) {
				$new_post_id = wp_insert_post( array(
					'post_type' => "{$this->post_type}__schedule",
					'post_status' => 'publish',
				), /* $wp_error = */ true );

				ParentEventId::update_post_meta( $new_post_id, $post_id );
				EventDate::update_post_meta( $new_post_id, $schedule->date );
			}

			// 選択された日程を有効化・選択解除された日程を無効化
			if ( ! is_null( $schedule->post_id ) ) {
				wp_update_post( array(
					'ID' => $schedule->post_id,
					'post_status' => $schedule->active ? 'publish' : 'private',
				), /* $wp_error = */ true );
			}
		}
	}
}
