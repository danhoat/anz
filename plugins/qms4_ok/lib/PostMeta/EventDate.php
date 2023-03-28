<?php

namespace QMS4\PostMeta;


class EventDate
{
	/** @var    string */
	const KEY = 'qms4__event_date';

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
	 * @return    DateTimeImmutable|null
	 */
	public static function get_post_meta( int $post_id ): ?\DateTimeImmutable
	{
		$value = get_post_meta( $post_id, self::KEY, /* $single = */ true );

		return preg_match( '/^\d{4}-\d{2}-\d{2}$/', $value )
			? new \DateTimeImmutable( $value )
			: null;
	}

	/**
	 * @param    int    $post_id
	 * @param    mixed
	 * @return    void
	 */
	public static function update_post_meta( int $post_id, $value )
	{
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
				'type' => 'string',
				'description' => 'イベント 開催日',
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
	 * @return    string
	 */
	private function default(): string
	{
		return '1970-01-01';
	}

	/**
	 * @return    array
	 */
	private function schema(): array
	{
		return array(
			'type' => 'string',
			'format' => 'date',
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
	): string
	{
		$post_id = $object[ 'id' ];

		$value = get_post_meta( $post_id, self::KEY, /* $single = */ true );

		return empty( $value ) ? $this->default() : $value;
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
