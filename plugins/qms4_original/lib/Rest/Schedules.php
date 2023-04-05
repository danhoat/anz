<?php

namespace QMS4\Rest;

use QMS4\PostTypeMeta\PostTypeMeta;


class Schedules
{
	/** @var    PostTypeMeta */
	private $post_type_meta;

	/**
	 * @param    PostTypeMeta    $post_type_meta
	 */
	public function __construct( PostTypeMeta $post_type_meta )
	{
		$this->post_type_meta = $post_type_meta;
	}

	/**
	 * @return    void
	 */
	public function register_field(): void
	{
		register_rest_field(
			$this->post_type_meta->name(),
			'schedules',
			array(
				'get_callback' => array( $this, 'get' ),
				'schema' => $this->schema(),
			)
		);
	}

	/**
	 * @return    array<string,mixed>
	 */
	public function schema(): array
	{
		return array(
			'type' => 'array',
			'items' => array(
				'type' => 'object',
				'properties' => array(
					'post_id' => array( 'type' => 'number' ),
					'date' => array(
						'type' => 'string',
						'pattern' => '^[0-9]{4}-[0-9]{2}-[0-9]{2}$',
					),
					'active' => array( 'type' => 'boolean' ),
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

		$query = new \WP_Query( array(
			'post_type' => "{$this->post_type_meta->name()}__schedule",
			'post_status' => array( 'publish', 'private' ),
			'posts_per_page' => -1,
			'meta_query' => array(
				array(
					'key' => 'parent_id',
					'value' => $parent_id,
				),
			),
			'fields' => 'ids',
		) );

		$schedules = array();
		foreach ( $query->posts as $post_id ) {
			$schedules[] = array(
				'post_id' => $post_id,
				'date' => get_post_meta( $post_id, 'event_date', /* $single = */ true ),
				'active' => get_post_status( $post_id ) === 'publish',
			);
		}

		return $schedules;
	}
}
