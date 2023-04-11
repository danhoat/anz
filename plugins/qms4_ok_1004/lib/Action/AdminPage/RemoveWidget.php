<?php

namespace QMS4\Action\AdminPage;


class RemoveWidget
{
	/**
	 * @return    void
	 */
	public function __invoke()
	{
		remove_meta_box( 'dashboard_quick_press', 'dashboard', 'side' );
	}
}
