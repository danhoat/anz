<?php

namespace QMS4\Action\AdminPage;


class RemoveMenuNode
{
	/**
	 * @param    \WP_Admin_Bar    $wp_admin_bar
	 * @return    void
	 */
	public function __invoke( \WP_Admin_Bar $wp_admin_bar )
	{
		$wp_admin_bar->remove_node( 'new-post' );
	}
}
