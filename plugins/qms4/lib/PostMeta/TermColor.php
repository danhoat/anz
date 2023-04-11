<?php

namespace QMS4\PostMeta;


class TermColor
{
	/** @var    string */
	const KEY = 'qms4__term__color';

	/** @var    string */
	private $taxonomy;

	/** @var    string */
	private $position;

	/**
	 * @param    string    $taxonomy
	 * @param    string    $position
	 */
	public function __construct( string $taxonomy, string $position = 'normal' )
	{
		$this->taxonomy = $taxonomy;
		$this->position = $position;
	}

	// ====================================================================== //

	/**
	 * @return    void
	 */
	public function register_meta(): void
	{
		if ( ! function_exists( 'acf_add_local_field_group' ) ) { return; }

		$json = file_get_contents( __DIR__ . '/field_groups/term__color.json' );
		$json = str_replace( '[__KEY__]', "acf__term__color__{$this->taxonomy}", $json );
		$json = str_replace( '[__SLUG__]', $this->taxonomy, $json );
		$json = str_replace( '[__POSITION__]', $this->position, $json );

		$field_groups = json_decode( $json, true );

		if ( ! is_array( $field_groups ) ) {
			throw new \RuntimeException( 'フィールドグループの設定ファイルが正しくありません。' );
		}

		foreach ( $field_groups as $field_groups ) {
			acf_add_local_field_group( $field_groups );
		}
	}

	// ====================================================================== //

	/**
	 * @param    int    $term_id
	 * @return    string|null
	 */
	public static function get_post_meta( int $term_id ): ?string
	{
		$value = get_metadata( 'term', $term_id, self::KEY, /* $single = */ true );

		return empty( $value ) ? null : $value;
	}

	/**
	 * @param    int    $term_id
	 * @param    mixed
	 * @return    void
	 */
	public static function update_post_meta( int $term_id, $value ): void
	{
		update_metadata( 'term', $term_id, self::KEY, $value );
	}
}
