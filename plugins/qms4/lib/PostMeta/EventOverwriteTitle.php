<?php

namespace QMS4\PostMeta;


class EventOverwriteTitle
{
	/** @var    string */
	const KEY = 'qms4__event_overwrite_title';

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
	 * @return    string
	 */
	public static function get_post_meta( int $post_id ): string
	{
		return get_post_meta( $post_id, self::KEY, /* $single = */ true );
	}

	/**
	 * @param    int    $post_id
	 * @param    mixed    $value
	 * @return    void
	 */
	public static function update_post_meta( int $post_id, $value ): void
	{
		update_post_meta( $post_id, self::KEY, trim( $value ) );
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
				'description' => 'イベント個別設定 タイトル',
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

	// ====================================================================== //

	/**
	 * @return    string
	 */
	private function default(): string
	{
		return '';
	}

	/**
	 * @return    array
	 */
	private function schema(): array
	{
		return array(
			'type' => 'string',
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

		return get_post_meta( $post_id, self::KEY, /* $single = */ true );
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
