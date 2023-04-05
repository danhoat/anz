<?php

namespace QMS4\PostMeta;


class Memo
{
	/** @var    string */
	const KEY = 'qms4__memo';

	/** @var    string */
	private $post_type;

	/** @var    string */
	private $position;

	/**
	 * @param    string    $post_type
	 * @param    string    $position
	 */
	public function __construct( string $post_type, string $position = 'side' )
	{
		$this->post_type = $post_type;
		$this->position = $position;
	}

	/**
	 * @return    void
	 */
	public function register_meta(): void
	{
		if ( ! function_exists( 'acf_add_local_field_group' ) ) { return; }

		$json = file_get_contents( __DIR__ . '/field_groups/memo.json' );
		$json = str_replace( '[__KEY__]', "acf__memo__{$this->post_type}", $json );
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
