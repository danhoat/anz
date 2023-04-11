<?php

namespace QMS4\PostMeta;


class AreaChildIds
{
	/** @var    string */
	const KEY = 'qms4__child_ids';

	// ====================================================================== //

	/**
	 * @param    int    $post_id
	 * @return    int[]
	 */
	public static function get_post_meta( int $post_id ): array
	{
		return get_post_meta( $post_id, self::KEY, /* $single = */ false );
	}

	/**
	 * @param    int    $post_id
	 * @param    mixed
	 * @return    void
	 */
	public static function update_post_meta( int $post_id, $value )
	{
		$prev_value = self::get_post_meta( $post_id );

		foreach ( array_diff( $prev_value, $value ) as $child_id ) {
			delete_metadata( 'post', $post_id, self::KEY, $child_id );
		}

		foreach ( array_diff( $value, $prev_value ) as $child_id ) {
			add_metadata( 'post', $post_id, self::KEY, $child_id, false );
		}
	}

	// ====================================================================== //

	/**
	 * @return    void
	 */
	public function register_meta(): void
	{
		register_post_meta(
			'qms4__area_master',
			self::KEY,
			array(
				'type' => 'array',
				'description' => 'Chiled Ids',
				'single' => false,
				'default' => $this->default(),
				'show_in_rest' => array(
					'schema' => $this->schema(),
				),
			)
		);
	}

	// ====================================================================== //

	/**
	 * @return    int[]
	 */
	private function default(): array
	{
		return array();
	}

	/**
	 * @return    array
	 */
	private function schema(): array
	{
		return array(
			'type' => 'array',
			'items'=> array(
				'type' => 'integer',
			),
		);
	}
}
