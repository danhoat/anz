<?php

namespace QMS4\PostMeta;


class Timetable
{
	/** @var    string */
	const KEY = 'qms4__timetable';

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
	 * @param    int    $post_id
	 * @return    array[]|null
	 */
	public static function get_post_meta( int $post_id ): ?array
	{
		$timetable = get_post_meta( $post_id, self::KEY, /* $single = */ true );

		return is_string( $timetable ) && empty( $timetable ) ? null : $timetable;
	}

	/**
	 * @param    int    $post_id
	 * @param    mixed    $value
	 * @return    void
	 */
	public static function update_post_meta( int $post_id, $value ): void
	{
		if ( is_array( $value ) && empty( $value ) ) {
			$value = null;
		}

		update_post_meta( $post_id, self::KEY, $value );
	}

	// ====================================================================== //

	/**
	 * @return    void
	 */
	public function register_meta(): void
	{
		register_post_meta(
			$this->post_type,
			self::KEY,
			array(
				'type' => 'array',
				'description' => 'イベント タイムテーブル',
				'single' => true,
				'default' => $this->default(),
				'show_in_rest' => array(
					'schema' => $this->schema(),
				),
			)
		);
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

	/**
	 * @param    \WP_Post    $wp_post
	 * @return    void
	 */
	public function add_meta_box( \WP_Post $wp_post ): void
	{
	}

	/**
	 * @param    int    $post_id
	 * @param    \WP_Post    $wp_post
	 * @return    void
	 */
	public function save_post( int $post_id, \WP_Post $wp_post ): void
	{
	}

	// ====================================================================== //

	/**
	 * @return    array[]|null
	 */
	private function default(): ?array
	{
		return null;
	}

	/**
	 * @return    array
	 */
	private function schema(): array
	{
		return array(
			'type' => array( 'array', 'null' ),
			'items' => array(
				'type' => 'object',
				'properties' => array(
					'label' => array(
						'type' => 'string',
						'description' => '時間帯のラベル',
					),
					'capacity' => array(
						'type' => 'string',
						'enum' => array( '◎', '○', '△', '×' ),
					),
					'available' => array(
						'type' => 'boolean',
						'description' => '予約可能かどうか',
					),
					'comment' => array(
						'type' => 'string',
						'description' => '備考',
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
		$object,
		string $field_name,
		\WP_REST_Request $wp_rest_request
	): ?array
	{
		$post_id = $object[ 'id' ];

		$timetable = get_post_meta( $post_id, self::KEY, /* $single = */ true );

		return is_string( $timetable ) && empty( $timetable ) ? $this->default() : $timetable;
	}

	public function update(
		$value,
		\WP_Post $wp_post,
		string $field_name,
		\WP_REST_Request $wp_rest_request,
		string $post_type
	): void
	{
		update_post_meta( $wp_post->ID, self::KEY, $value );
	}
}
