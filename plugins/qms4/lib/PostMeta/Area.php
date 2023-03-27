<?php

namespace QMS4\PostMeta;


class Area
{
	/** @var    string */
	const KEY = 'qms4__area';

	/** @var    string */
	private $post_type;

	/** @var    string */
	private $position;

	public function __construct( string $post_type, string $position = 'side' )
	{
		$this->post_type = $post_type;
		$this->position = $position;
	}

	// ====================================================================== //

	/**
	 * @param    int    $post_id
	 * @return    int|null
	 */
	public static function get_post_meta( int $post_id ): ?int
	{
		$value = get_post_meta( $post_id, self::KEY, /* $single = */ true );

		return empty( $value ) ? null : (int) $value;
	}

	/**
	 * @param    int    $post_id
	 * @param    mixed
	 * @return    void
	 */
	public static function update_post_meta( int $post_id, $value )
	{
		if ( $value ) {
			update_post_meta( $post_id, self::KEY, $value );
		} else {
			delete_post_meta( $post_id, self::KEY );
		}
	}

	// ====================================================================== //

	public function register_meta(): void
	{
		if ( ! function_exists( 'acf_add_local_field_group' ) ) { return; }

		$json = file_get_contents( __DIR__ . '/field_groups/area.json' );
		$json = str_replace( '[__KEY__]', "acf__area__{$this->post_type}", $json );
		$json = str_replace( '[__SLUG__]', $this->post_type, $json );
		$json = str_replace( '[__POSITION__]', $this->position, $json );

		$field_groups = json_decode( $json, true );

		if ( ! is_array( $field_groups ) ) {
			throw new \RuntimeException( 'フィールドグループの設定ファイルが正しくありません。' );
		}

		foreach ( $field_groups as $field_groups ) {
			acf_add_local_field_group( $field_groups );
		}
	}
}
