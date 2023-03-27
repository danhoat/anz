<?php

namespace QMS4\QueryBuilderPart;

use QMS4\Event\ValidEventIdsRepository;
use QMS4\Param\Param;
use QMS4\QueryBuilderPart\QueryBuilderPart;


class ValidEventQuery extends QueryBuilderPart
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
	 * @return    array<string,mixed>
	 */
	protected function default_param(): array
	{
		return array();
	}

	/**
	 * @param    Param    $param
	 * @return    array<string,mixed>
	 */
	protected function query_args( Param $param ): array
	{
		$repository = new ValidEventIdsRepository();
		$event_ids = $repository->find( array( 'post_type' => $this->post_type ) );

		if ( empty( $event_ids ) ) {
			// ここで 'post__in' => array() としてしまうと、
			// 「フィルター条件なし」と同等の意味になり、結果として全てのイベント投稿が表示される
			// それを避けるために絶対に存在しない投稿 ID を条件に指定する
			return array(
				'post__in' => array( -1 ),
			);
		} else {
			return array(
				'post__in' => $event_ids,
			);
		}
	}
}
