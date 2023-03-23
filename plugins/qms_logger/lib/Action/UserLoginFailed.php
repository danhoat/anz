<?php
namespace QMS\Logger\Action;

use QMS\Logger\Logger\UserActionLogger;


class UserLoginFailed
{
	public function __invoke( string $username, \WP_Error $error )
	{
		UserActionLogger::channel( 'core', 'incident' )
			->notice( 'login_failed', array(
				'username' => $username,
				'remote_addr' => $_SERVER[ 'REMOTE_ADDR' ] ?? null,
			) );
	}
}
