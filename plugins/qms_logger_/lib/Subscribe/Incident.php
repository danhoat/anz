<?php
namespace QMS\Logger\Subscribe;

use QMS\Logger\Action;


class Incident
{
	public function __construct()
	{
		add_action( 'wp_login_failed', new Action\UserLoginFailed(), 10, 2 );
	}
}
