<?php

namespace QMS4\Block;


class BlockUtil
{
	/** @var    string[] */
	public static $use = array();

	/**
	 * @param    string    $block
	 * @return    void
	 */
	public static function use( string $block ): void
	{
		self::$use[] = $block;
	}

	/**
	 * @param    string    $block
	 * @return    bool
	 */
	public static function used( string $block ): bool
	{
		return in_array( $block, self::$use, /* $strict = */ true );
	}
}
