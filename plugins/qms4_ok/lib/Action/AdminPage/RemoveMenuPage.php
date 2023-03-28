<?php

namespace QMS4\Action\AdminPage;


class RemoveMenuPage
{
	/**
	 * @return    void
	 */
	public function __invoke()
	{
		remove_menu_page( 'index.php' );
		remove_menu_page( 'edit.php' );
	}
}
