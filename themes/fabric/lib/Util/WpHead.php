<?php
namespace Fabric\Util;


class WpHead
{
	/** @var    callable */
	private $callback;

	/**
	 * @param    callable    $callback
	 */
	public function __construct( callable $callback )
	{
		$this->callback = $callback;

		add_action( 'wp_head', array( $this, 'wp_head' ) );
	}

	/**
	 * @return    void
	 */
	public function wp_head(): void
	{
		call_user_func( $this->callback );
	}
}
