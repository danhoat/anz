<?php

namespace QMS4\Coodinator;

use QMS4\Action\AdminPage\RemoveMenuNode;
use QMS4\Action\AdminPage\RemoveMenuPage;
use QMS4\Action\AdminPage\RemoveWidget;


class AdminPageCoodinator
{
	public function __construct()
	{
		add_action( 'admin_menu', new RemoveMenuPage() );
		add_action( 'admin_bar_menu', new RemoveMenuNode(), 999 );
		add_action( 'wp_dashboard_setup', new RemoveWidget(), 999 );
	}
}
