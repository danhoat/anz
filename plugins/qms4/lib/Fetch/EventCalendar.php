<?php

namespace QMS4\Fetch;

use QMS4\Event\CalendarMonth\CalendarMonth;
use QMS4\Event\CalendarMonth\CalendarMonthFactory;
use QMS4\Event\CalendarMonth\CalendarTerm;
use QMS4\Item\Post\Schedule;
use QMS4\Param\Param;
use QMS4\PostMeta\Area;
use QMS4\PostMeta\EventDate;
use QMS4\PostMeta\ParentEventId;
use QMS4\Util\FindInPostTree;


class EventCalendar
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
	 * @param    CalendarTerm    $calendar_term
	 * @param    array<string,mixed>    $param
	 * @return    CalendarMonth
	 */
	public function fetch(
		CalendarTerm $calendar_term,
		array $param = array()
	): CalendarMonth
	{
		$wp_posts = $this->query(
			$calendar_term->start_of_term(),
			$calendar_term->end_of_term(),
			$param
		);

		$param = new Param( array() );

		$schedules = array();
		foreach ( $wp_posts as $wp_post ) {
			$schedule = new Schedule( $wp_post, $param );
			if ( $schedule->event->post_status === 'publish' ) {
				$schedules[] = new Schedule( $wp_post, $param );
			}
		}

		$factory = new CalendarMonthFactory( $calendar_term );
		return $factory->create( $schedules );
	}

	/**
	 * @param    \DateTimeInterface    $start
	 * @param    \DateTimeInterface    $end
	 * @param    array<string,mixed>    $param
	 * @param    int|null    $parent_event_id
	 * @return    \WP_Post[]
	 */
	private function query(
		\DateTimeInterface $start,
		\DateTimeInterface $end,
		array $param
	): array
	{
		$parent_ids = $this->parent_ids( $param );

		if ( empty( $parent_ids ) ) { return array(); }


		$meta_query = array(
			array(
				'key' => ParentEventId::KEY,
				'value' => $parent_ids,
				'compare' => 'IN',
			),
			array(
				'key' => EventDate::KEY,
				'value' => $start->format( 'Y-m-d' ),
				'compare' => '>=',
				'type' => 'DATE',
			),
			array(
				'key' => EventDate::KEY,
				'value' => $end->format( 'Y-m-d' ),
				'compare' => '<=',
				'type' => 'DATE',
			),
		);

		if ( ! empty( $param[ 'parent_event_id' ] ) ) {
			$meta_query[] = array(
				'key' => ParentEventId::KEY,
				'value' => $param[ 'parent_event_id' ],
				'type' => 'NUMERIC',
			);
		}

		$query = new \WP_Query( array(
			'post_type' => "{$this->post_type}__schedule",
			'post_status' => 'publish',
			'posts_per_page' => -1,
			'meta_query' => $meta_query,
		) );

		return $query->posts;
	}

	// ====================================================================== //

	/**
	 * @param    array<string,mixed>    $param
	 * @return    int[]
	 */
	private function parent_ids( array $param ): array
	{
		$meta_query = array();

		if ( ! empty( $param[ 'area' ] ) ) {
			list( $in__slugs, $not_in__slugs ) = $this->query_slugs( $param[ 'area' ] );
			list( $in__ids, $not_in__ids ) = $this->area_post_ids( $in__slugs, $not_in__slugs );

			if ( empty( $in__ids ) && empty( $not_in__ids ) ) {
				return array();
			} elseif ( ! empty( $in__ids ) && ! empty( $not_in__ids ) ) {
				$in__ids = array_diff( $in__ids, $not_in__ids );

				$meta_query[] = array(
					'key' => Area::KEY,
					'value' => $in__ids,
					'compare' => 'IN',
				);
			} elseif ( ! empty( $in__ids ) && empty( $not_in__ids ) ) {
				$meta_query[] = array(
					'key' => Area::KEY,
					'value' => $in__ids,
					'compare' => 'IN',
				);
			} elseif ( empty( $in__ids ) && ! empty( $not_in__ids ) ) {
				$meta_query[] = array(
					'key' => Area::KEY,
					'value' => $not_in__ids,
					'compare' => 'NOT IN',
				);
			}
		}

		$query = new \WP_Query( array(
			'post_type' => $this->post_type,
			'post_status' => empty( $param[ 'post_status' ] )
				? 'publish'
				: $param[ 'post_status' ],
			'meta_query' => $meta_query,
			'posts_per_page' => -1,
			'fields' => 'ids',
		) );

		return $query->posts;
	}

	// ====================================================================== //

	/**
	 * @param    string|string[]    $query_str
	 * @return    array[]
	 */
	private function query_slugs( $query_str )
	{
		$query_str = is_array( $query_str ) ? $query_str : array( $query_str );

		$query_strs = array();
		foreach ( $query_str as $str ) {
			$query_strs = array_merge( $query_strs , explode( ',', $str ) );
		}

		$in = array();
		$not_in = array();
		foreach ( $query_strs as $query ) {
			$query = trim( $query );

			if ( strlen( $query ) >= 2 && $query[0] === '-' ) {
				// $cat_str がマイナス記号 '-' から始まる場合はカテゴリ除外リスト $not_in に入れる
				$not_in[] = substr( $query, 1 );
			} else {
				// さもなくばカテゴリ指定リスト $in に入れる
				$in[] = $query;
			}
		}

		return array( $in, $not_in );
	}

	/**
	 * @param    string[]    $in__slugs
	 * @param    string[]    $not_in__slugs
	 * @return    array[]
	 */
	private function area_post_ids( array $in__slugs, array $not_in__slugs ): array
	{
		$query = new \WP_Query( array(
			'post_type' => 'qms4__area_master',
			'post_status' => 'publish',
			'posts_per_page' => -1,
			'orderby' => 'menu_order',
			'order' => 'ASC',
		) );

		$tree = new FindInPostTree( $query->posts );

		$in__ids = $tree->find( function ( \WP_Post $wp_post ) {
			return urldecode( $wp_post->post_name );
		}, $in__slugs );

		$not_in__ids = $tree->find( function ( \WP_Post $wp_post ) {
			return urldecode( $wp_post->post_name );
		}, $not_in__slugs );

		return array( $in__ids, $not_in__ids );
	}
}
