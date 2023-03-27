<?php

namespace QMS4\Action\PostTyeps;

use QMS4\Event\ValidEventIdsRepository;


class SetValidEventFilter
{
	/**
	 * @param    \WP_Query    $query
	 */
	public function __invoke( \WP_Query $query ): void
	{
		if ( is_admin() ) { return; }
		if ( ! $query->is_main_query() ) { return; }
		if ( ! $query->is_archive() ) { return; }

		$post_type = $query->get( 'post_type' );
		$post_type = is_array( $post_type ) ? $post_type[ 0 ] : $post_type;

		$repository = new ValidEventIdsRepository();
		try {
			$event_ids = $repository->find( array( 'post_type' => $post_type ) );
		}
		catch ( \LogicException $e ) {
			// $post_type が "機能タイプ: イベント" ではない場合、
			// ValidEventIdsRepository は例外を投げる。
			// そのときは何もフィルターしたくないので、ただ処理を抜ければいい
			return;
		}

		if ( empty( $event_ids ) ) {
			// ここで set( 'post_in', array() ) としてしまうと、
			// 「フィルター条件なし」と同等の意味になり、結果として全てのイベント投稿が表示される
			// それを避けるために絶対に存在しない投稿 ID を条件に指定する
			$query->set( 'post__in', array( -1 ) );
		} else {
			$query->set( 'post__in', $event_ids );
		}
	}
}
