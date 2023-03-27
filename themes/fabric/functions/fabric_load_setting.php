<?php

/**
 * setting.php を読み込んで内容を返す
 *
 * @return    array<string,mixed>
 */
function fabric_load_setting()
{
	static $setting = null;

	if ( is_null( $setting ) ) {
		return $setting = require( __DIR__ . '/../setting.php' );
	} else {
		return $setting;
	}
}
