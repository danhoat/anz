<?php

namespace QMS4\Event;

use QMS4\Event\BorderDateFactory;
use QMS4\PostMeta\EventDate;
use QMS4\PostMeta\ParentEventId;


/**
 * 有効なイベントだけをフィルターして、その投稿 ID の配列を返す
 *
 * ここで "有効な" イベントの条件は
 * - イベントの投稿ステータスが「公開済み」
 * - イベント日程を1つ以上持つ
 */
class ValidEventIdsRepository
{
	public function find( array $cond )
	{
		$this->validate( $cond );

		$post_type = $cond[ 'post_type' ];

		$factory = new BorderDateFactory();
		$border_date = $factory->from_post_type( $post_type );


		global $wpdb;

		$sql = "
			SELECT DISTINCT
				EVENT_POSTS.`ID` AS 'ID'
			FROM {$wpdb->posts} AS EVENT_POSTS

			-- ここで「イベント投稿」と「親イベントカスタムフィード」とを INNER JOIN している
			-- (LEFT OUTER JOIN ではないことに注目せよ)
			-- 「親イベントカスタムフィード」から参照されていないイベント投稿の行は INNER JOIN すると消えてしまう
			-- これによって『イベント日程をひとつも持っていないイベント投稿を除外する』という条件を表現している
			INNER JOIN {$wpdb->postmeta} AS PARENT_EVENT
				ON
					EVENT_POSTS.`ID` = PARENT_EVENT.`meta_value`
					AND PARENT_EVENT.`meta_key` = '" . ParentEventId::KEY . "'

			INNER JOIN {$wpdb->posts} AS SCHEDULE_POSTS
				ON
					PARENT_EVENT.`post_id` = SCHEDULE_POSTS.`ID`
					AND SCHEDULE_POSTS.`post_status` = 'publish'
			INNER JOIN {$wpdb->postmeta} AS EVENT_DATE
				ON
					SCHEDULE_POSTS.`ID` = EVENT_DATE.`post_id`
					AND EVENT_DATE.`meta_key` = '" . EventDate::KEY . "'
			WHERE
				EVENT_POSTS.`post_type` = %s
				AND EVENT_POSTS.`post_status` = 'publish'
				AND EVENT_DATE.`meta_value` >= %s  -- カレンダー起算日 移行のイベント日程のみ抽出する
			;
		";

		return $wpdb->get_col( $wpdb->prepare(
			$sql,
			$post_type,
			$border_date->format( 'Y-m-d' )
		) );
	}

	private function validate( array $cond ): void
	{
		if ( empty( $cond[ 'post_type' ] ) ) {
			throw new \InvalidArgumentException();
		}
	}
}
