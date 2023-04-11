<?php

namespace QMS4\Util;


/**
 * PC/スマホ 判定
 *
 * @example
 * 	if ( IsMobile::detect() ) {
 * 		echo 'スマホ';
 * 	} else {
 * 		echo 'PC';
 * 	}
 */
class IsMobile
{
	static $cache = 0;

	/**
	 * @return    bool|null
	 */
	public static function detect(): ?bool
	{
		if ( ! is_int( self::$cache ) ) { return self::$cache; }

		if ( ! isset( $_SERVER[ 'HTTP_USER_AGENT' ] ) ) {
			return self::$cache = null;
		}

		$user_agent = $_SERVER[ 'HTTP_USER_AGENT' ];

		return self::$cache = preg_match( '/iphone|ipod|android/ui', $user_agent ) != 0;
	}
}
