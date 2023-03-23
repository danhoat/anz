<?php

namespace QMS4\PostMeta;


class OutputSetting
{
	/**
	 * @return    void
	 */
	public function register_meta(): void
	{
		if ( ! function_exists( 'acf_add_local_field_group' ) ) { return; }

		$json = file_get_contents( __DIR__ . '/field_groups/output_setting.json' );
		$field_groups = json_decode( $json, true );

		if ( ! is_array( $field_groups ) ) {
			throw new \RuntimeException( 'フィールドグループの設定ファイルが正しくありません。' );
		}

		foreach ( $field_groups as $field_groups ) {
			acf_add_local_field_group( $field_groups );
		}
	}
}
