<?php

namespace QMS4\PostMeta;

use QMS4\Fetch\EventSchedules;
use QMS4\PostMeta\EventDate;
use QMS4\PostMeta\ParentEventId;


class Schedules
{
	/** @var    string */
	const KEY = 'qms4__schedules';

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
	 * @return    void
	 */
	public function register_meta(): void
	{
	}

	/**
	 * @param    \WP_REST_Server    $wp_rest_server
	 * @return    void
	 */
	public function register_field( \WP_REST_Server $wp_rest_server ): void
	{
		register_rest_field(
			$this->post_type,
			self::KEY,
			array(
				'get_callback' => array( $this, 'get' ),
				'update_callback' => array( $this, 'update' ),
				'schema' => $this->schema(),
			)
		);
	}

	// ====================================================================== //

	/**
	 * @return    array
	 */
	private function schema(): array
	{
		return array(
			'type' => 'array',
			'items' => array(
				'type' => 'object',
				'properties' => array(
					'post_id' => array(
						'type' => array( 'string', 'null' ),
					),
					'active' => array(
						'type' => 'boolean',
					),
					'date' => array(
						'type' => 'string',
					),
					'overwrite' => array(
						'type' => 'boolean',
					),
					'title' => array(
						'type' => 'string',
					),
					'timetable' => array(
						'type' => 'array',
					),
				),
			),
		);
	}

	/**
	 * @param    array    $object,
	 * @param    string    $field_name,
	 * @param    \WP_REST_Request    $request
	 * @return    array[]
	 */
	public function get(
		array $object,
		string $field_name,
		\WP_REST_Request $request
	): array
	{
		$parent_id = $object[ 'id' ];

		$fetch_schedules = new EventSchedules();
		$schedules = $fetch_schedules->fetch(
			$parent_id,
			! empty( $_GET[ 'enable_schedule_only' ] )
		);

		$items = array();
		foreach ( $schedules as $schedule ) {
			$items[] = array(
				'post_id' => $schedule->schedule_id,
				'active' => $schedule->active,
				'date' => $schedule->date_str,
				'overwrite' => $schedule->overwrite,
				'title' => $schedule->title,
				'timetable' => $schedule->timetable->to_array(),
			);
		}

		return $items;
	}

	public function update(
		$value,
		\WP_Post $wp_post,
		string $field_name,
		\WP_REST_Request $wp_rest_request,
		string $post_type
	): void
	{
	}
}
