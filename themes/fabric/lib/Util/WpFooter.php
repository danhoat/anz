<?php
namespace Fabric\Util;


class WpFooter
{
	/** @var    callable */
	private $callback;

	/**
	 * @param    callable    $callback
	 */
	public function __construct( callable $callback )
	{
		$this->callback = $callback;

		add_action( 'wp_footer', array( $this, 'wp_footer' ), 20 );
	}

	/**
	 * @return    void
	 */
	public function wp_footer(): void
	{
		call_user_func( $this->callback );
	}
}
