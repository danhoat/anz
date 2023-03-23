<?php
declare(strict_types=1);

namespace QMS3\BrickAdmin\Block;


class BlockUtil
{
	/** @var    string[] */
	public static $use = array();

	/**
	 * @param    string    $block
	 * @return    void
	 */
	public static function uses( $block )
	{
		self::$use[] = $block;
	}

	/**
	 * @param    string    $block
	 * @return    bool
	 */
	public static function used( $block )
	{
		return in_array( $block, self::$use, /* $strict = */ true );
	}
}
